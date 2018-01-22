<?php
    $servername="localhost";
    $username="root";
    //$password="kkksss333";
    $password="kjsce";
    //$dbname="dbACAWA";
    $dbname="dbACAWAR"; 
    $conn=mysqli_connect($servername,$username,$password,$dbname);
?>

<?php
    //create table
    $sql="create table Demo(
    Roll_number int, 
    pref_1 varchar(2),
    pref_2 varchar(2), 
    pref_3 varchar(2), 
    pref_4 varchar(2), 
    pref_5 varchar(2),
    allottedChoice int, 
    primary key(Roll_number))";

    if(mysqli_query($conn,$sql)){
    echo "Demo table created successfully<br/>";
    }
    else{
        echo "Error while creating Demo table ".mysqli_error($conn)."<br/>";
    }
?>










<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        
    </body>
</html>
