<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- Displays all clubs and allows a librarian to add new clubs-->

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

    //Query for Club Info
    $dbquery = file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubsRead.sql');  
    $clubs = $conn->query($dbquery);

    $n = $clubs -> num_rows;
    $m = $clubs -> field_count;
    $data = $clubs -> fetch_all();
    $fields = $clubs -> fetch_fields(); 

    $needs_redirect = false;
    //Check to delete clubs
    if (isset($_POST['RemoveClubs'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubsDelete.sql'));
        $del_stmt->bind_param('s', $club_name);

        for ($i = 0; $i < $n; $i++) {
            $club_name = $data[$i][0];

            if (isset($_POST["checkbox$i"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }
    }

    $user_feedback = '';
    //Check to insert a new club
    if (isset($_POST["ClubSubmit"])) {
        $club_name = htmlspecialchars($_POST["ClubName"]);
        $club_desc = htmlspecialchars($_POST["ClubDescription"]);
        
        $check_club = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallFindClub.sql'));
        $check_club->bind_param('s', $club_name);
        $check_club->execute();
        $check_club->bind_result($does_club_exist);
        $check_club->fetch();
        $check_club->close();

        if ($does_club_exist != 1) {
            $needs_redirect = true;
            $insert_club = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ClubsInsert.sql'));
            $insert_club->bind_param('ss', $club_name, $club_desc);
            if (!$insert_club->execute()) {
                echo $conn->error;
            }
        } else {
            $user_feedback = "<p>An club already exists under this name!</p>";
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
    <a href="http://rajah.centre.edu/librarian/manage-patrons.php">Manage Patrons</a>
</p>

<!-- Header for the catalog -->
<h1> <u>Therpston County Library Clubs</u> </h1>
<?php echo isset($_SESSION['user_name']) ? "<p><b>Hello ".$_SESSION['user_name']."!</b></p>" : "<p><b>Librarian View</b></p>"; ?>

<!-- Display club info -->

<h3><u>Manage Existing Clubs</u></h3>

<form action='manage-clubs.php', METHOD='POST'>
    <table>
        <thead>
            <tr>
                <?php echo "<th>"."Delete from Records?"."</th>";
                    for($j = 0; $j < $m; $j++){
                        echo "<th>".$fields[$j]->name ."</th>";   
                    }   
                ?>
            </tr>    
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $n; $i++) { ?>
                <tr> 
                    <td> <input type="checkbox", name="checkbox<?php echo $i; ?>"/> </td>
                    <td> <a href="http://rajah.centre.edu/librarian/manage-club.php?club_name=<?php echo $data[$i][0]; ?>"><?php echo $data[$i][0]; ?></a> </td>
                    <?php for($j = 1; $j < $m; $j++) { ?>
                        <td> <?php echo $data[$i][$j]; ?> </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <input type="submit", value="Remove Clubs", name="RemoveClubs", method='POST'/>
</form>

<h3><u>Enter a New Club</u></h3>

<!-- Form for inserting a new club -->

<form action="manage-clubs.php", method='POST'>
    <!-- Input -->
    <table>
        <tbody>
            <tr> 
                <td>Enter Club Name:</td>
                <td><input type="text", id="ClubName", name="ClubName"/></td>
            </tr>
            <tr>
                <td>Enter a Short Club Description:</td>
                <td><textarea id="ClubDescription" name="ClubDescription" rows="4" cols="50"></textarea></td>
            </tr>
        </tbody>            
    </table>
    <!-- Submit! -->
    <input type="submit", value="Create Club", name="ClubSubmit", method='POST'/>
</form>