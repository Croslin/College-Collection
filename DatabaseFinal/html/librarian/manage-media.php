<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- Displays all the info for a certain piece of media and its series -->
<!-- A librarian can update media info and delete records -->

<?php
    //Boilerplate
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    session_start();
	
	$config = parse_ini_file('/home/odroid/csc362-proj-therpston-databases-inc/mysqli_librarian.ini');
    $dbname = 'TherpstonCountyLibrary';

    //Use the name of the input database to connect to that one specifically
	$conn = new mysqli(
        $config['mysqli.default_host'],
        $config['mysqli.default_user'],
        $config['mysqli.default_pw'],
        $dbname
    );

	if ($conn->connect_errno) {
 		echo "Error: Failed to make a MySQL connection, here is why: ". "<br>";
 		echo "Errno: " . $conn->connect_errno . "\n";
 		echo "Error: " . $conn->connect_error . "\n";
 		exit; // Quit this PHP script if the connection fails.
	}

    $media_id = $_GET['media_id'];

    //Display media info
    $find_media_type = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallGetMediaType.sql'));
    $find_media_type->bind_param('i', $media_id);
    $find_media_type->execute();
    $find_media_type->bind_result($media_type);
    $find_media_type->fetch();
    $find_media_type->close();

    if ($media_type == "Book") {
        //Query for Book Info
        $find_media = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ReadOneBook.sql'));
        $find_media->bind_param('i', $media_id);
        $find_media->execute();
        $media_info = $find_media->get_result();

        $m_info = $media_info -> field_count;
        $data_info = $media_info -> fetch_all();
        $fields_info = $media_info -> fetch_fields();
    } else if ($media_type == "Movie") {
        //Query for Movie Info
        $find_media = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ReadOneMovie.sql'));
        $find_media->bind_param('i', $media_id);
        $find_media->execute();
        $media_info = $find_media->get_result();

        $m_info = $media_info -> field_count;
        $data_info = $media_info -> fetch_all();
        $fields_info = $media_info -> fetch_fields();
    }

    $media_title = $data_info[0][0];
    $series = $data_info[0][6];

    //Query for Checkout Info
    $find_history = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MediaReservationsReadMediaHistory.sql'));
    $find_history->bind_param('i', $media_id);
    $find_history->execute();
    $checkout_history = $find_history->get_result();

    $n_checkout = $checkout_history -> num_rows;
    $m_checkout = $checkout_history -> field_count;
    $data_checkout = $checkout_history -> fetch_all();
    $fields_checkout = $checkout_history -> fetch_fields(); 

    //Query for Clubs Info
    $find_club_history = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MediaClubReservationHistory.sql'));
    $find_club_history->bind_param('i', $media_id);
    $find_club_history->execute();
    $club_history = $find_club_history->get_result();

    $n_clubs= $club_history -> num_rows;
    $m_clubs = $club_history -> field_count;
    $data_clubs = $club_history -> fetch_all();
    $fields_clubs = $club_history -> fetch_fields(); 

    //Query for Series Info
    $find_series = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/SeriesRead.sql'));
    $find_series->bind_param('si', $series, $media_id);
    $find_series->execute();
    $series_media = $find_series->get_result();

    $n_series = $series_media -> num_rows;
    $m_series = $series_media -> field_count;
    $data_series = $series_media -> fetch_all();
    $fields_series = $series_media -> fetch_fields(); 

    $needs_redirect = false;
    $get_stmt = "?media_id=".$media_id;
    $user_error_message = "";

    if (isset($_POST['UpdateMedia'])) {
        $run_update = true;
        //Prep update statement
        if ($media_type == "Book") {
            $find_media = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/BooksUpdate.sql'));
            $find_media->bind_param('i', $media_id);
            $find_media->execute();
        } else if ($media_type == "Movie") {
            $find_media = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MovieUpdate.sql'));
            $find_media->bind_param('i', $media_id);
            $find_media->execute();
        }

        if ($run_update) {
            $needs_redirect = true;
            if (!$upd_stmt->execute()) {
                echo $conn->error;
            }
        }
    }
    
    //Check for delete media reservations with patrons
    if (isset($_POST['RemoveReservations'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MediaReservationsDelete.sql'));
        $del_stmt->bind_param('iis', $patron_id, $media_id, $checkout_start);

        for ($i = 0; $i < $n_checkout; $i++) {
            $patron_id = $data_checkout[$i][1];
            $checkout_start = $data_checkout[$i][3];

            if (isset($_POST["checkbox$patron_id"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }
    }

    //Check for delete media reservations with clubs
    
    if (isset($_POST['RemoveClubReservations'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubMediaDelete.sql'));
        $del_stmt->bind_param('si', $club_name, $media_id);

        for ($i = 0; $i < $n_clubs; $i++) {
            $club_name = $data_clubs[$i][0]; 

            if (isset($_POST["checkboxClub$i"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }
    }

    if ($needs_redirect) {
        header("Location: ".$_SERVER['REQUEST_URI'], true, 303);
        exit();
    }
?>

<style>
    th {border-style: outset;}
</style>

<!-- List of pages for librarian -->
<p>
    <a href="http://rajah.centre.edu/librarian/manage-catalog.php">Manage Catalog</a><t>
    <a href="http://rajah.centre.edu/librarian/manage-clubs.php">Manage Clubs</a><t>
    <a href="http://rajah.centre.edu/librarian/manage-patrons.php">Manage Patrons</a>
</p>

<!-- Header for the catalog -->
<h1> <u>Media Information for <?php echo $media_title; ?></u> </h1>
<?php echo isset($_SESSION['user_name']) ? "<p><b>Hello ".$_SESSION['user_name']."!</b></p>" : "<p><b>Librarian View</b></p>"; ?>
<?php echo $user_error_message; ?>

<h3><u>Media Information</u></h3>

<form action= <?php echo "\"".'manage-media.php'.$get_stmt."\"" ?>, METHOD='POST'>
    <table>
        <tbody>
                <!-- No changing media id -->
                <tr>
                    <td><b><?php echo $fields_info[0]->name?></b></td>
                    <td><?php echo $data_info[0][0] ?></td>
                </tr>
                <tr>
                    <td><b><?php echo $fields_info[1]->name?></b></td>
                    <td><?php echo $data_info[0][1] ?></td>
                </tr>
            <?php for ($i = 2; $i < $m_info; $i++) {?>
                <tr>
                    <td><b><?php echo $fields_info[$i]->name?></b></td>
                    <td><input type="text", name="new<?php echo $i; ?>", method='POST', value=<?php echo "\"".$data_info[0][$i]."\""?>/></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <input type="submit", value="Submit Changes", name="UpdateMedia", method='POST'/>
</form>

<h3><u>Patron Reservation Information</u></h3>

<?php if ($n_checkout > 0) { ?>
    <form action=<?php echo "\"".'manage-media.php'.$get_stmt."\"" ?>, METHOD='POST'>
        <table>
            <thead>
                <tr>
                    <?php echo "<th>"."Delete from Records?"."</th>";
                        for($j = 0; $j < $m_checkout; $j++){
                            echo "<th>".$fields_checkout[$j]->name ."</th>";   
                        }   
                    ?>
                </tr>    
            </thead>
            <tbody>
                <?php for ($i = 0; $i < $n_checkout; $i++) { ?>
                    <tr> 
                        <td> <input type="checkbox", name="checkbox<?php echo $data_checkout[$i][1]; ?>"/> </td>
                        <td> <a href="http://rajah.centre.edu/librarian/manage-patron.php?patron_id=<?php echo $data_checkout[$i][1]; ?>"><?php echo $data_checkout[$i][0]; ?></a> </td>
                        <?php for($j = 1; $j < $m_checkout; $j++) { ?>
                            <td> <?php echo $data_checkout[$i][$j]; ?> </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <input type="submit", value="Remove Records", name="RemoveReservations", method='POST'/>
    </form>
<?php } ?>

<h3><u>Club Reservation Information</u></h3>

<?php if ($n_clubs > 0) { ?>
    <form action=<?php echo "\"".'manage-media.php'.$get_stmt."\"" ?>, METHOD='POST'>
        <table>
            <thead>
                <tr>
                    <?php echo "<th>"."Delete from Records?"."</th>";
                        for($j = 0; $j < $m_clubs; $j++){
                            echo "<th>".$fields_clubs[$j]->name ."</th>";   
                        }   
                    ?>
                </tr>    
            </thead>
            <tbody>
                <?php for ($i = 0; $i < $n_clubs; $i++) { ?>
                    <tr> 
                        <td> <input type="checkbox", name="checkboxClub<?php echo $i; ?>"/> </td>
                        <td> <a href="http://rajah.centre.edu/librarian/manage-club.php?club_name=<?php echo $data_clubs[$i][0];?>"><?php echo $data_clubs[$i][0]; ?></a> </td>
                        <?php for($j = 1; $j < $m_clubs; $j++) { ?>
                            <td> <?php echo $data_clubs[$i][$j]; ?> </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <input type="submit", value="Remove Records", name="RemoveClubReservations", method='POST'/>
    </form>
<?php } ?>

<h3><u>Other Media in the <?php echo $series; ?> Series</u></h3>

<?php if ($n_series > 0) { ?>
    <table>
        <thead>
            <tr>
                <?php 
                    for($j = 0; $j < $m_series; $j++){
                        echo "<th>".$fields_series[$j]->name ."</th>";   
                    }   
                ?>
            </tr>    
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $n_series; $i++) { ?>
                <tr> 
                    <td> <a href="http://rajah.centre.edu/librarian/manage-media.php?media_id=<?php echo $data_series[$i][1];?>"><?php echo $data_series[$i][0]; ?></a> </td>
                    <?php for($j = 1; $j < $m_series; $j++) { ?>
                        <td> <?php echo $data_series[$i][$j]; ?> </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
<?php } ?>