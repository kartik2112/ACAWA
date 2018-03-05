<?php
$servername="localhost";
$username="root";
$password="";
//$password="kjsce";
$dbname="dbACAWA";
//$dbname="dbACAWAR"; 
$conn=mysqli_connect($servername,$username,$password,$dbname);
if(mysqli_connect_error()){
    die("Cannot access db ".mysqli_error($conn));
    
}
?>
