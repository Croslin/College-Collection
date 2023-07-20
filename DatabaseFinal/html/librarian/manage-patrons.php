<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- Displays the list of patrons of the library and allows a librarian to manage their information -->

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
    
    //Display patron info
    $dbquery = file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/PatronsRead.sql');  
    $patrons = $conn->query($dbquery);

    $n = $patrons -> num_rows;
    $m = $patrons -> field_count;
    $data = $patrons -> fetch_all();
    $fields = $patrons -> fetch_fields(); 

    $needs_redirect = false;
    if (isset($_POST['RemovePatrons'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/PatronsDelete.sql'));
        $del_stmt->bind_param('i', $patron_id);

        for ($i = 0; $i < $n; $i++) {
            $patron_id = $data[$i][1];

            if (isset($_POST["checkbox$patron_id"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }
    }

    $user_feedback = '';
    //This code runs after a user has submitted a name and password. If correct, this page redirects. If not, then display intelligent errors.
    if (isset($_POST["AccountSubmit"])) {
        $name = explode(" ", htmlspecialchars($_POST["Name"]));
        $email = htmlspecialchars($_POST["email"]);
        $phonenum = htmlspecialchars($_POST["phonenum"]);
        $street = htmlspecialchars($_POST["street"]);
        $city = htmlspecialchars($_POST["city"]);
        $state = htmlspecialchars($_POST["state"]);
        $zip = htmlspecialchars($_POST["zip"]);
        
        if (count($name) < 2) {
            //Check that we have the right data to insert a customer
            $user_feedback = "<p>Error: Name improperly formatted. Please provide both a first and last name separated by a space.</p>";
        } else if (strlen($phonenum) != 10) {
            $user_feedback = "<p>Error: Phone Number improperly formatted. Please list 10 digits with no separators.</p>";
        } else {
            $check_user = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallFindUser.sql'));
            $check_user->bind_param('ss', $name[0], $name[1]);
            $check_user->execute();
            $check_user->bind_result($does_user_exist);
            $check_user->fetch();
            $check_user->close();

            if ($does_user_exist != 1) {
                if ($_POST['Password'] != $_POST['Password2']) {
                    $user_feedback = "<p>Error: Passwords are different. Please verify you entered the same password twice.</p>";
                } else {
                    $password_hash = password_hash($_POST['Password'], PASSWORD_DEFAULT);
                    $needs_redirect = true;

                    $insert_user = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/PatronsInsert.sql'));
                    $insert_user->bind_param('sssssssis', $name[0], $name[1], $email, $phonenum, $street, $city, $state, $zip, $password_hash);
                    if (!$insert_user->execute()) {
                        echo $conn->error;
                    }
                }
            } else {
                $user_feedback = "<p>An account already exists under this name!</p>";
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
    <a href="http://rajah.centre.edu/librarian/manage-clubs.php">Manage Clubs</a>
</p>

<!-- Header for the catalog -->
<h1> <u>Therpston County Library Patrons List</u> </h1>
<?php echo isset($_SESSION['user_name']) ? "<p><b>Hello ".$_SESSION['user_name']."!</b></p>" : "<p><b>Librarian View</b></p>"; ?>

<?php echo $user_feedback ?>

<h3><u>Manage Existing Patrons</u></h3>

<form action='manage-patrons.php', METHOD='POST'>
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
                    <td> <input type="checkbox", name="checkbox<?php echo $data[$i][1]; ?>"/> </td>
                    <td> <a href="http://rajah.centre.edu/librarian/manage-patron.php?patron_id=<?php echo $data[$i][1]; ?>"><?php echo $data[$i][0]; ?></a> </td>
                    <?php for($j = 1; $j < $m; $j++) { ?>
                        <td> <?php echo $data[$i][$j]; ?> </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <input type="submit", value="Remove Patrons", name="RemovePatrons", method='POST'/>
</form>

<h3><u>Enter a New Patron</u></h3>

<form action="manage-patrons.php", method='POST'>
    <!-- Input -->
    <table>
        <tbody>
            <tr> 
                <td>Enter First and Last Name:</td>
                <td><input type="text", id="Name", name="Name"/></td>
            </tr>
            <tr>
                <td>Enter Email:</td>
                <td><input type="text", id="email", name="email"/></td>
            </tr>
            <tr>
                <td>Enter Phone Number:</td>
                <td><input type="number", id="phonenum", name="phonenum"/></td>
            </tr>
            <tr>
                <td>Enter Street Address:</td>
                <td><input type="text", id="street", name="street"/></td>
            </tr>
            <tr> 
                <td>Enter City:</td>
                <td><input type="text", id="city", name="city"/></td>
            </tr>
            <tr>
                <td>Enter State Abbreviation:</td>
                <td><input type="text", id="state", name="state"/></td>
            </tr>
            <tr>
                <td>Enter ZIP Code:</td>
                <td><input type="number", id="zip", name="zip"/></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password", id="Password", name="Password"/></td>
            </tr>
            <tr>
                <td>Confirm Password:</td>
                <td><input type="password", id="Password2", name="Password2"/></td>
            </tr>
        </tbody>            
    </table>
    <!-- Submit! -->
    <input type="submit", value="Create Patron", name="AccountSubmit", method='POST'/>
</form>