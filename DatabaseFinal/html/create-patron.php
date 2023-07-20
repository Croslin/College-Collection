<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- A simple login page for Therpston Country Library users to be directed to the right pages -->

<?php
    //Boilerplate
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
    $config = parse_ini_file('/home/odroid/csc362-proj-therpston-databases-inc/mysqli_login.ini');
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
            //Check that we have the write data to insert a customer
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
        
                    $check_user = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/PatronsInsert.sql'));
                    $check_user->bind_param('sssssssis', $name[0], $name[1], $email, $phonenum, $street, $city, $state, $zip, $password_hash);
                    $check_user->execute();
        
                    $user_feedback = "<p>Success!</p>";
                }
            } else {
                $user_feedback = "<p>An account already exists under this name!/p>";
            }
        }
    }
?>

<h1> Join the Therpston County Library as a New Patron! </h1>

<?php echo $user_feedback ?>

<body>
    <form action="create-patron.php", method='POST'>
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
            <input type="submit", value="Create Account", name="AccountSubmit", method='POST'/>
    </form>
    Return to login <a href='http://rajah.centre.edu/index.php'>here.</a>
</body>

