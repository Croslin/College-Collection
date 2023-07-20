<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- Displays a specific patron of the library and allows a librarian to manage their information -->

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

    $patron_id = $_GET['patron_id'];

    //Query for Patron Info
    $find_patron = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ReadOnePatron.sql'));
    $find_patron->bind_param('i', $patron_id);
    $find_patron->execute();
    $patron_info = $find_patron->get_result();

    $m_info = $patron_info -> field_count;
    $data_info = $patron_info -> fetch_all();
    $fields_info = $patron_info -> fetch_fields();

    $patron_name = $data_info[0][0];

    //Query for Checkout Info
    $find_history = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MediaReservationsReadPatronHistory.sql'));
    $find_history->bind_param('i', $patron_id);
    $find_history->execute();
    $checkout_history = $find_history->get_result();

    $n_checkout = $checkout_history -> num_rows;
    $m_checkout = $checkout_history -> field_count;
    $data_checkout = $checkout_history -> fetch_all();
    $fields_checkout = $checkout_history -> fetch_fields(); 

    //Query for Membership Info
    $find_clubs = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/PatronReadClubs.sql'));
    $find_clubs->bind_param('s', $patron_id);
    $find_clubs->execute();
    $club_list = $find_clubs->get_result();

    $n_clubs = $club_list -> num_rows;
    $m_clubs = $club_list -> field_count;
    $data_clubs = $club_list -> fetch_all();
    $fields_clubs = $club_list -> fetch_fields(); 

    $needs_redirect = false;
    $get_stmt = "?patron_id=".$patron_id;
    $user_error_message = "";

    if (isset($_POST['UpdatePatron'])) {
        $run_update = true;
        //Prep update statement
        $upd_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/PatronUpdate.sql'));
        $upd_stmt->bind_param('ssdssssi', $patron_email, $patron_phone, $patron_balance, $patron_street, $patron_city, $patron_state, $patron_zip, $patron_id);

        $patron_email = isset($_POST['new1']) ? $_POST['new1'] : $data_info[0][1];

        if (isset($_POST['new2'])) {
            if (strlen($_POST['new2']) == 10) {
                $patron_phone = $_POST['new2'];
            } else {
                $run_update = false;
                $user_error_message = "<p>Error: Phone Number improperly formatted. Please list 10 digits with no separators.</p>";
            }
        } else {
            $patron_phone = $data_info[0][2];
        }
        $patron_balance = isset($_POST['new3']) ? $_POST['new3'] : $data_info[0][3];
        $patron_street = isset($_POST['new4']) ? $_POST['new4'] : $data_info[0][4];
        $patron_city = isset($_POST['new5']) ? $_POST['new5'] : $data_info[0][5];
        $patron_state = isset($_POST['new6']) ? $_POST['new6'] : $data_info[0][6];
        $patron_zip = isset($_POST['new7']) ? $_POST['new7'] : $data_info[0][7];

        if ($run_update) {
            $needs_redirect = true;
            if (!$upd_stmt->execute()) {
                echo $conn->error;
            }
        }
    }
    
    if (isset($_POST['RemoveReservations'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MediaReservationsDelete.sql'));
        $del_stmt->bind_param('iis', $patron_id, $media_id, $checkout_start);

        for ($i = 0; $i < $n_checkout; $i++) {
            $media_id = $data_checkout[$i][1];
            $checkout_start = $data_checkout[$i][4];

            if (isset($_POST["checkbox$media_id"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }
    }

    if (isset($_POST['RemoveMembership'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubMembersDelete.sql'));
        $del_stmt->bind_param('si', $club_name, $patron_id);

        for ($i = 0; $i < $n_clubs; $i++) {
            $club_name = $data_clubs[$i][0];

            if (isset($_POST["checkbox$i"])) {
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
<h1> <u>Patron Information for <?php echo $patron_name; ?></u> </h1>
<?php echo isset($_SESSION['user_name']) ? "<p><b>Hello ".$_SESSION['user_name']."!</b></p>" : "<p><b>Librarian View</b></p>"; ?>
<?php echo $user_error_message; ?>

<h3><u>Patron Information</u></h3>

<form action= <?php echo "\"".'manage-patron.php'.$get_stmt."\"" ?>, METHOD='POST'>
    <table>
        <tbody>
                <!-- No changing patron names -->
                <tr>
                    <td><b><?php echo $fields_info[0]->name?></b></td>
                    <td><?php echo $data_info[0][0] ?></td>
                </tr>
            <?php for ($i = 1; $i < $m_info; $i++) {?>
                <tr>
                    <td><b><?php echo $fields_info[$i]->name?></b></td>
                    <td><input type="text", name="new<?php echo $i; ?>", method='POST', value=<?php echo "\"".$data_info[0][$i]."\""?>/></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <input type="submit", value="Submit Changes", name="UpdatePatron", method='POST'/>
</form>

<h3><u>Patron Reservation History</u></h3>

<?php if ($n_checkout > 0) { ?>
    <form action=<?php echo "\"".'manage-patron.php'.$get_stmt."\"" ?>, METHOD='POST'>
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
                        <td> <a href="http://rajah.centre.edu/librarian/manage-media.php?media_id=<?php echo $data_checkout[$i][1]; ?>"><?php echo $data_checkout[$i][0]; ?></a> </td>
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

<h3><u>Club Membership</u></h3>

<?php if ($n_clubs > 0) { ?>
    <form action=<?php echo "\"".'manage-patron.php'.$get_stmt."\"" ?>, METHOD='POST'>
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
                        <td> <input type="checkbox", name="checkbox<?php echo $i; ?>"/> </td>
                        <td> <a href="http://rajah.centre.edu/librarian/manage-club.php?club_name=<?php echo $data_clubs[$i][0]; ?>"><?php echo $data_clubs[$i][0]; ?></a> </td>
                        <?php for($j = 1; $j < $m_clubs; $j++) { ?>
                            <td> <?php echo $data_clubs[$i][$j]; ?> </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <input type="submit", value="Remove Membership", name="RemoveMembership", method='POST'/>
    </form>
<?php } ?>