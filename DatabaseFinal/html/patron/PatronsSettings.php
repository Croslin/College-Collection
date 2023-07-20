<html>
<head>
    <title>Account Settings</title>
</head>
<body>
    
    <a href="http://rajah.centre.edu/patron/PatronCatalogView.php">Back to Menu</a>
    
    <h1>Patron Settings</h1>
    
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $config = parse_ini_file('/home/odroid/csc362-proj-therpston-databases-inc/mysqli_patron.ini');
        $dbname = 'TherpstonCountyLibrary';

	    $conn = new mysqli(
        $config['mysqli.default_host'],
        $config['mysqli.default_user'],
        $config['mysqli.default_pw'],
        $dbname
        );

        $name = explode(" ", $_SESSION["user_name"]);

        $lookforid = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallGetPatronID.sql'));
        $lookforid->bind_param('ss', $name[0], $name[1]);
        $lookforid->execute();
        $lookforid->bind_result($theid);
        $lookforid->fetch();
        $lookforid->close();

        $query = "SELECT PatronID, PatronFirstName, PatronLastName, PatronAddressStreet, PatronAddressState, PatronAddressZip, PatronEmail, PatronPhoneNumber, PatronBalance FROM Patrons WHERE PatronID = $theid"; // will select the checked media from the catalog
        $stmt = $conn->prepare($query);
        
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        displayChoice($result)
        
    ?>
    
    
</body>
</html>
<?php
    
    function displayChoice($result_obj){
        $n = $result_obj -> num_rows;
    $m = $result_obj -> field_count;
    $data = $result_obj -> fetch_all();
    $fields = $result_obj -> fetch_fields();
?>
    <table>
    <thead>
    <tr>
        
        <?php
        for($j = 0; $j < $m; $j++){
        ?><tr><?php
        echo "<th>". $fields[$j]->name ."</th>";
        echo "<td>".$data[0][$j]."</td>";
        ?></tr><?php
    	}
    ?>
    </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
    <?php
    }
?>
