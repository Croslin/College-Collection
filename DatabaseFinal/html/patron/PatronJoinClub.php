<html>
<head>
</head>
<body>

    <a href="http://rajah.centre.edu/patron/PatronClubs.php">Back to The Clubs</a>

    <h1>Club Joined</h1>

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

        $ClubChecked = $_POST['Confirm'];

        echo "ClubName: " . $ClubChecked . "<br>";

        $name = explode(" ", $_SESSION["user_name"]);

        $lookforid = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallGetPatronID.sql'));
        $lookforid->bind_param('ss', $name[0], $name[1]);
        $lookforid->execute();
        $lookforid->bind_result($theid);
        $lookforid->fetch();
        $lookforid->close();

        if ($theid != NULL){
            $sql = "INSERT INTO ClubMembers (ClubName, PatronID)
            VALUES ('$ClubChecked', '$theid')";

            if ($conn->query($sql) === TRUE) {
                echo "Club Joined Successfully!";
}           else {
                echo "Error: " . $sql . "<br>" . $conn->error;
}
        }
        $conn->close();


    ?>
