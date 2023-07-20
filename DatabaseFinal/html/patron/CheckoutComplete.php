<html>
<head>
</head>
<body>

    <a href="http://rajah.centre.edu/patron/PatronCatalogView.php">Back to The Catalog</a>

    <h1>Checkout Complete</h1>

    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        session_start();
        $config = parse_ini_file('/home/odroid/csc362-proj-therpston-databases-inc/mysqli_patron.ini');
        $dbname = 'TherpstonCountyLibrary';

    //Use the name of the input database to connect to that one specifically
            $conn = new mysqli(
        $config['mysqli.default_host'],
        $config['mysqli.default_user'],
        $config['mysqli.default_pw'],
        $dbname
    );

        $MediaChecked = (int)$_POST['Confirm'];

        echo "MediaId: " . (string)$MediaChecked . "<br>";

        $name = explode(" ", $_SESSION["user_name"]);
        //$find = "SELECT PatronID FROM Patrons WHERE PatronFirstName = $name[0] AND PatronLastName = $name[1]";

        $lookforid = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallGetPatronID.sql'));
        $lookforid->bind_param('ss', $name[0], $name[1]);
        $lookforid->execute();
        $lookforid->bind_result($theid);
        $lookforid->fetch();
        $lookforid->close();
        if ($theid != NULL){
            $sql = "INSERT INTO MediaReservations (PatronID, MediaID)
            VALUES ('$theid', '$MediaChecked')";

            if ($conn->query($sql) === TRUE) {
                echo "Reservation created successfully";
}           else {
                echo "Error: " . $sql . "<br>" . $conn->error;
}
        }
        $conn->close();


    ?>
