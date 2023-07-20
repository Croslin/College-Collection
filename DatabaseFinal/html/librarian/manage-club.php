<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- Displays club information and allows the librarian to modify club data, club reservation data, and club member data -->

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

    $club_name = $_GET['club_name'];

    //Query for Club
    $find_club = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ReadOneClub.sql'));
    $find_club->bind_param('s', $club_name);
    $find_club->execute();
    $club_info = $find_club->get_result();

    $m_info = $club_info -> field_count;
    $data_info = $club_info -> fetch_all();
    $fields_info = $club_info -> fetch_fields();

    //Query for Club Checkout Info
    $find_history = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubMediaHistory.sql'));
    $find_history->bind_param('s', $club_name);
    $find_history->execute();
    $checkout_history = $find_history->get_result();

    $n_checkout = $checkout_history -> num_rows;
    $m_checkout = $checkout_history -> field_count;
    $data_checkout = $checkout_history -> fetch_all();
    $fields_checkout = $checkout_history -> fetch_fields(); 

    //Query for Membership Info
    $find_members = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubMembersRead.sql'));
    $find_members->bind_param('s', $club_name);
    $find_members->execute();
    $member_list = $find_members->get_result();

    $n_members = $member_list -> num_rows;
    $m_members = $member_list -> field_count;
    $data_members = $member_list -> fetch_all();
    $fields_members = $member_list -> fetch_fields(); 

    $needs_redirect = false;
    $get_stmt = "?club_name=".$club_name;
    $user_error_message = "";

    //Check for upoate club data
    if (isset($_POST['UpdateClub'])) {
        //Prep update statement
        $upd_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubsUpdate.sql'));
        $upd_stmt->bind_param('ss', $new_club_desc, $club_name);

        $new_club_desc = isset($_POST['ClubDescription']) ? htmlspecialchars($_POST['ClubDescription']) : $data_info[0][1];

        $needs_redirect = true;
        if (!$upd_stmt->execute()) {
            echo $conn->error;
        }
    }

    //Check for delete club reservation
    if (isset($_POST['RemoveReservations'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubMediaDelete.sql'));
        $del_stmt->bind_param('si', $club_name, $media_id);

        for ($i = 0; $i < $n_checkout; $i++) {
            $media_id = $data_checkout[$i][1];

            if (isset($_POST["checkbox$i"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }
    }

    //Check for delete members from a club
    if (isset($_POST['RemoveMembers'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubMembersDelete.sql'));
        $del_stmt->bind_param('si', $club_name, $patron_id);

        for ($i = 0; $i < $n_members; $i++) {
            $patron_id = $data_members[$i][1];

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
<h1> <u>Club Information for <?php echo $club_name; ?></u> </h1>
<?php echo isset($_SESSION['user_name']) ? "<p><b>Hello ".$_SESSION['user_name']."!</b></p>" : "<p><b>Librarian View</b></p>"; ?>
<?php echo $user_error_message; ?>


<!-- Display club information -->
<form action= <?php echo "\"".'manage-club.php'.$get_stmt."\"" ?>, METHOD='POST'>
    <table>
        <tbody>
            <!-- No changing Club names -->
            <tr>
                <td><b><?php echo $fields_info[0]->name?></b></td>
                <td><?php echo $data_info[0][0] ?></td>
            </tr>
            <tr>
                <td><b><?php echo $fields_info[1]->name?></b></td>
                <td><textarea id="ClubDescription" name="ClubDescription" rows="4" cols="50"?><?php echo $data_info[0][1]?></textarea></td>
            </tr>
        </tbody>
    </table>

    <input type="submit", value="Submit Changes", name="UpdateClub", method='POST'/>
</form>

<h3><u>Club Media Reservation History</u></h3>

<?php if ($n_checkout > 0) { ?>
    <form action=<?php echo "\"".'manage-club.php'.$get_stmt."\"" ?>, METHOD='POST'>
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
                        <td> <input type="checkbox", name="checkbox<?php echo $i; ?>"/> </td>
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

<h3><u>Club Members</u></h3>

<?php if ($n_members > 0) { ?>
    <form action=<?php echo "\"".'manage-club.php'.$get_stmt."\"" ?>, METHOD='POST'>
        <table>
            <thead>
                <tr>
                    <?php echo "<th>"."Delete from Records?"."</th>";
                        for($j = 0; $j < $m_members; $j++){
                            echo "<th>".$fields_members[$j]->name ."</th>";   
                        }   
                    ?>
                </tr>    
            </thead>
            <tbody>
                <?php for ($i = 0; $i < $n_members; $i++) { ?>
                    <tr> 
                        <td> <input type="checkbox", name="checkbox<?php echo $i; ?>"/> </td>
                        <td> <a href="http://rajah.centre.edu/librarian/manage-patron.php?patron_id=<?php echo $data_members[$i][1]; ?>"><?php echo $data_members[$i][0]; ?></a> </td>
                        <?php for($j = 1; $j < $m_members; $j++) { ?>
                            <td> <?php echo $data_members[$i][$j]; ?> </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <input type="submit", value="Remove Members", name="RemoveMembers", method='POST'/>
    </form>
<?php } ?>

