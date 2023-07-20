<html>
<head>
    <title>Checkout Page</title>
</head>
<body>
    
    <a href="http://rajah.centre.edu/patron/PatronClubs.php">Back to The Clubs</a>
    
    <h1>Checkout Page</h1>
    
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $config = parse_ini_file('/home/odroid/csc362-proj-therpston-databases-inc/mysqli_patron.ini');
        $dbname = 'TherpstonCountyLibrary';

    //Use the name of the input database to connect to that one specifically
	$conn = new mysqli(
        $config['mysqli.default_host'],
        $config['mysqli.default_user'],
        $config['mysqli.default_pw'],
        $dbname
    );

        $ClubFor = $_POST['checkout'];

        echo "Club: " . $ClubFor . "<br>";

        $find_club = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/ReadOneClub.sql'));
        $find_club->bind_param('s', $ClubFor);
        $find_club->execute();
        $club_info = $find_club->get_result();
        displayChoice($club_info)
        
    ?>
    
    <h2>Would you like to join this club?</h2>
    
</body>
</html>
<?php
    
    function displayChoice($result_obj){
    $n = $result_obj -> num_rows;
    $m = $result_obj -> field_count;
    $data = $result_obj -> fetch_all();
    $fields = $result_obj -> fetch_fields();
?>
<form action="PatronJoinClub.php" method="POST">
    <table>
    <thead>
    <tr>
        <?php
        for($j = 0; $j < $m; $j++){
        echo "<th>". $fields[$j]->name ."</th>";
    }
    ?>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i = 0; $i < $n; $i++){
     ?>
        <tr>
        <?php
        for($j = 0; $j < $m; $j++){
        ?>
        <td>
<?php
                $second = $data[$i][0];
            echo $data[$i][$j];
            ?>
        </td>
        <?php
        }
        ?>
        </tr>
        <?php
    }
?>
        <h2>Are you sure you want to reserve this?</h2>
        
        <?php

        echo "<td><input type=\"checkbox\" name=\"Confirm\" value=\"$second\"  />";
        ?>
        <input type= 'submit', value= 'submit', name = 'Yes', method = 'POST'/>

    </tbody>
    </table>
        </form>
    <?php
    }
?>
