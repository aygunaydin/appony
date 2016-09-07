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
//Android KAYIT
$sql = "SELECT android_name,appname,androidToken FROM app_list where androidToken is not null order by androidToken desc";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
$ch = curl_init();

$url='https://api.apptweak.com/android/applications/'.$row["android_name"].'/ratings.json';
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$headers = array();
$headers[] = "X-Apptweak-Key:".$row["androidToken"];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$bipAndroidResponse = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch).' Kontrol1 </br>';
}
curl_close ($ch);
//$bipCurl='curl -H "X-Apptweak-Key: PyyyQZCTxW1Yg_Ud6CNSKVUfln0" \ 
//https://api.apptweak.com/android/applications/com.turkcell.bipi/information.json?country=tr&language=tr;';
//$bipAndroidJson= exec($bipcurl, $bipAndroidJson);
//file_get_contents("https://api.apptweak.com/android/applications/com.boxer.email/information.json?country=fr&language=fr",false,$contextAx);
//$bipAndroidURL='https://gplaystore.p.mashape.com/applicationDetails?id=com.turkcell.bip';
//$bipAndroidGet= file_get_contents($bipAndroidURL);
$bipAndroidJson= json_decode($bipAndroidResponse);
//echo 'INFO: Kontrol Get contents: '.$bipAndroidJson;
$bipAndroidRating=$bipAndroidJson->content->average;
$bipAndroidRaterNum=$bipAndroidJson->content->count;
$bipAndroidRating=substr($bipAndroidRating,0,7);
$appname=$row["appname"];
if ($bipAndroidRaterNum>0) {
$return=createAndroidRatingRecord($appname,$bipAndroidRaterNum,$bipAndroidRating);
echo "</br>INFO:Android insertcompleted ".$return;
} else { echo '</br><a style="color:blue;">ERROR: degerler alinamadi</a></br>'; }
echo "</br>INFO-Response: ".$bipAndroidResponse."</br>";
//echo "</br>INFO-JSON: ".$bipAndroidJson."</br>";
echo "</br>INFO-appName: ".$appname;
echo "</br>INFO-appName: ".$row["androidToken"];
echo "</br>INFO-TOKEN: ".$row["android_name"];
echo "</br>INFO-Anrdoid-Rating: ".$bipAndroidRating;
echo "</br>INFO-Android-RaterNumber: ".$bipAndroidRaterNum;

echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";


    }
} else {
    echo "0 results";
}










$conn->close();












?>
