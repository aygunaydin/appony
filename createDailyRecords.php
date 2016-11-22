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

$sql = "SELECT appid,appname FROM app_list";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {


echo "INFO: started";
$bipURL='http://itunes.apple.com/lookup?id='.$row["appid"].'&country=tr';
$bipGet= file_get_contents($bipURL);
$bipJson= json_decode($bipGet);
//$bipImageURL=$bipJson->results[0]->artworkUrl512;
$bipRating=$bipJson->results[0]->averageUserRating;
$bipRaterNum=$bipJson->results[0]->userRatingCount;
$bipCurrentRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
$bipCurrentRaterNum=$bipJson->results[0]->userRatingCountForCurrentVersion;
$appID=$bipJson->results[0]->trackId;
$version=$bipJson->results[0]->version;
$CurrentVersionReleaseDate=$bipJson->results[0]->currentVersionReleaseDate;
echo "</br>INFO-appID: ".$appID;
echo "</br>INFO-appname: ".$row["appname"];
echo "</br>INFO-Rating: ".$bipRating;
echo "</br>INFO-RaterNumber: ".$bipRaterNum;
echo "</br>INFO-Current_Rating: ".$bipCurrentRating;
echo "</br>INFO-Current_RaterNumber: ".$bipCurrentRaterNum;
echo "</br>INFO-Version: ".$version;

$return=createRatingRecord($appID,$bipRaterNum,$bipRating,$bipCurrentRating,$bipCurrentRaterNum,$version,$CurrentVersionReleaseDate);
echo "</br>INFO: completed ".$return;
echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";

    }
} else {
    echo "0 results";
}

//Android KAYIT


$sql = "SELECT android_name,appname,androidToken FROM app_list where androidToken is not null";
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
$return=createAndroidRatingRecord($appname,$bipAndroidRaterNum,$bipAndroidRating);
echo "</br>INFO-appName: ".$appname;
echo "</br>INFO-app-name-android: ".$row["android_name"];
echo "</br>INFO-Anrdoid-Rating: ".$bipAndroidRating;
echo "</br>INFO-Android-RaterNumber: ".$bipAndroidRaterNum;
echo "</br>INFO: Android insertcompleted ".$return;
echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";


    }
} else {
    echo "0 results";
}










$conn->close();












?>
