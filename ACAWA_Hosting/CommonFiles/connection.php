<?php
$servername="localhost";
$username="root";
$password="kjsce";
//$password="kjsce";
$dbname="dbacawa";
//$dbname="dbACAWAR"; 
$conn=mysqli_connect($servername,$username,$password,$dbname);
if(mysqli_connect_error()){
    die("Cannot access db ".mysqli_error($conn)." Password, Username for hosting server is not added to Github for obvious reasons. So pleaseeee. If you are a content creator, you should already be knowing the credentials.");
    
}
?>
