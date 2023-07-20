<html>
<body>
    <?php
        // Creates a table of media for the patron to view before picking one to rent
        SESSION_Start();
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        //$dbhost = 'localhost';
        //$dbuser = 'odroid';
        //$dbpass = '';
        //$dbse = 'TherpstonsLibDB';
        //$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbse);

        $config = parse_ini_file('/home/odroid/csc362-proj-therpston-databases-inc/mysqli_patron.ini');
        $dbname = 'TherpstonCountyLibrary';

    //Use the name of the input database to connect to that one specifically
	    $conn = new mysqli(
        $config['mysqli.default_host'],
        $config['mysqli.default_user'],
        $config['mysqli.default_pw'],
        $dbname
        );

        $dbquery = 'SELECT * FROM Media';
        $stmt = $conn->prepare($dbquery);
        
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
	
	 ?>
        <h1> <u>Therpston County Library Catalog</u> </h1>
        <br>

        <?php


        
        echo "Hello " . $_SESSION["user_name"] . "<br>";
        
        ?>
        
        <a href ="http://rajah.centre.edu/patron/PatronReservations.php">Currently Reserved</a>
        <a href ="http://rajah.centre.edu/patron/PatronClubs.php">Clubs</a>
        <a href ="http://rajah.centre.edu/patron/PatronsSettings.php">User Settings</a>
	
	<br>
	<br>
        <button onclick="window.location.href='http://rajah.centre.edu/patron/PatronBookView.php';">
        Books Only
        </button>

        <button onclick="window.location.href='http://rajah.centre.edu/patron/PatronMovieView.php';">
        Movies Only
        </button>
    
        <?php

        
        ShowMedia($result);
    ?>
</body>
    
</html>
<?php
function ShowMedia($result_obj){
    $n = $result_obj -> num_rows;
    $m = $result_obj -> field_count;
    $data = $result_obj -> fetch_all();
    $fields = $result_obj -> fetch_fields();
?>
    
    <form action = 'Checkout.php' , METHOD = 'POST'>
    <table>
    <thead>
    <tr>
        <th>Order</th>
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
		$TheID = $data[$i][0];
        echo "<td><input type=\"radio\" name=\"checkout\" value=\"$TheID\" /></td>";
        for($j = 0; $j < $m; $j++){
        ?>
        <td>
            <?php
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
    </tbody>
    </table>
    <input type = 'submit', value = 'submit', name = 'SelectedMedia', method = 'POST'/>
</form>
<?php
}
?>
