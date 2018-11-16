#!/usr/bin/php -q
<?php
$email=file_get_contents('php://stdin');
preg_match_all("/NO\n\n(.*)/",$email,$matches);
$content=$matches[1][0];
$message_con=explode(',',$content,2);
$class=$message_con[0];
$roll=$message_con[1];
$servername = "localhost";
$username = "xxxxxxxxxxxxxxx";
$password = "xxxxxxxxxxxxx";
$dbname = "xxxxxxxxxxxxx";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT `roll_id`,`alternet_mobile_number`,`display_name`,`id`,`class_name` FROM `studentdata` WHERE `class_name` =$class AND `roll_id` IN ($roll)";


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["alternet_mobile_number"]."<br>";

$mn=$row["alternet_mobile_number"];
$student_name=$row["display_name"];
$student_id=$row["id"];
$class_id=$row["class_name"];

file("http://bigmsg.in/api/sendhttp.php?authkey=14512AZiQ8u8q545e7db6&mobiles=$mn&message=Your child $student_name is absent today and updated1&sender=TechSw&route=1");
$curr_date=date("Y-m-d");

$sql2="INSERT INTO wp_attendence (user_id, class_id, attend_by, attendence_date, status, role_name)
VALUES ('$student_id','$class','1','$curr_date','Absent','student')";
$result1 = $conn->query($sql2);

} 
}
