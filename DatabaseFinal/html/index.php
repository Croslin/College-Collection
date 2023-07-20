<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- A simple login page for Therpston Country Library users to be directed to the right pages -->

<?php
    //Boilerplate
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
    session_start();

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
    if (isset($_POST["LoginSubmit"])) {
        $name = explode(" ", htmlspecialchars($_POST["Name"]));
        if (count($name) < 2) {
            $user_feedback = "<p>Error: Name improperly formatted. Please provide both a first and last name separated by a space.</p>";
        } else {
            $check_user = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallFindUser.sql'));
            $check_user->bind_param('ss', $name[0], $name[1]);
            $check_user->execute();
            $check_user->bind_result($does_user_exist);
            $check_user->fetch();
            $check_user->close();

            if ($does_user_exist == 1) {
                $login_user = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallFindUserPass.sql'));
                $login_user->bind_param('ss', $name[0], $name[1]);
                $login_user->execute();
                $login_user->bind_result($user_pass);
                $login_user->fetch();
                $login_user->close();

                if (password_verify($_POST["Password"], $user_pass)) {
                    $find_user_type = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallFindUserType.sql'));
                    $find_user_type->bind_param('ss', $name[0], $name[1]);
                    $find_user_type->execute();
                    $find_user_type->bind_result($user_type);
                    $find_user_type->fetch();
                    $find_user_type->close();
                    
                    $_SESSION['user_name'] = $name[0].' '.$name[1];

                    if ($user_type == 1) {
                        //Librarian
                        $_SESSION['user_type'] = 'Librarian';
                        header("Location: http://rajah.centre.edu/librarian/manage-catalog.php");
                    } else {
                        //Patron
                        $_SESSION['user_type'] = 'Patron';
                        header("Location: http://rajah.centre.edu/patron/PatronCatalogView.php");
                    }
                } else {
                    $user_feedback = "<p>Incorrect Password</p>";
                }
            } else {
                $user_feedback = "<p>An account does not exist under this name.<br> Click the button below to join as a patron.</p>";
            }
        }
    }
?>

<h1> Login to Therpston County Library </h1>

<?php echo $user_feedback ?>

<body>
    <form action="index.php", method='POST'>
            <!-- Input -->
            <label for="Name">Enter First and Last Name:</label>
            <input type="text", id="Name", name="Name"/><br>
            <label for="Password">Password:</label>
            <input type="password", id="Password", name="Password"/><br>
            <!-- Submit! -->
            <input type="submit", value="Login", name="LoginSubmit", method='POST'/>
    </form>
    <form action="create-patron.php", method='POST'>
            <!-- Submit! -->
            <input type="submit", value="Create Account as Patron", name="create_account", method='POST'/>
    </form>
</body>
