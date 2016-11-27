<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("funcs/dbFunctions.php");

$servername='46.101.113.44';
$username='appony'; 
$password='appony1020';
$dbname='appony';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "select * from appony.android_app_rating_history where  rate_date > now()-INTERVAL 1 DAY
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {



echo $row["app_name"].";".$row["rating"].";".$row["rater_num"].";".$row["rate_date"]."</br>";



    }
} else {
    echo "0 results";
}





?>
