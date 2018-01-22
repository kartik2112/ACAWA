<?php
$servername="localhost";
$username="root";
//$password="kkksss333";
$password="kjsce";
$dbname="dbACAWAR"; 
//$dbname="dbACAWA";
/*$conn=mysqli_connect($servername,$username,$password);
if(mysqli_connect_error()){
   die("Error connecting server");
}
$sql="create database ".$dbname;
if(mysqli_query($conn,$sql)){
   echo "Database creation successful<br/>";

}
else{
   die("Error creating database. ".mysqli_error($conn));
}*/

require "CommonFiles/connection.php";
require "CommonFiles/CommonConstants.php";

$sql="create table Subject(Subj_ID int AUTO_INCREMENT,Subject_Name varchar(".SUBJ_NAME_LENGTH."), Sem int,File_Link varchar(".FILE_LINK_LENGTH."),Capacity int,Teacher varchar(".TEACHER_NAME_LENGTH."),imagelink varchar(".IMAGELINK_LENGTH."),primary key(Subj_ID))";
if(mysqli_query($conn,$sql)){
    echo "Subject table created successfully<br/>";
}
else{
    echo "Error while creating Subject table ".mysqli_error($conn)."<br/>";
}


$sql="create table User(Roll_number int, Name varchar(".USER_NAME_LENGTH."), Semester int(1), u_name varchar(".UNAME_LENGTH.") UNIQUE,pwd varchar(32),Type varchar(1), Branch varchar(".BRANCH_LENGTH."), ProfilePicLink varchar(".PROFILEPIC_LINK_LENGTH."),RemindedDate varchar(10), primary key(Roll_number))";
if(mysqli_query($conn,$sql)){
    echo "User table created successfully<br/>";
}
else{
    echo "Error while creating User table ".mysqli_error($conn)."<br/>";
}


for($i=1;$i<=NO_OF_SEMS;$i++){

    $sql="create table AC".$i."(
        Roll_number int, 
        submittedOn datetime,
        pref_1 int,pref_2 int, 
        pref_3 int, 
        pref_4 int, 
        pref_5 int,
        allottedChoice int, 
        foreign key(Roll_number) references User(roll_number),
        foreign key(pref_1) references Subject(Subj_ID),
        foreign key(pref_2) references Subject(Subj_ID),
        foreign key(pref_3) references Subject(Subj_ID),
        foreign key(pref_4) references Subject(Subj_ID),
        foreign key(pref_5) references Subject(Subj_ID),
        primary key(Roll_number))";
    if(mysqli_query($conn,$sql)){
        echo "AC".$i." table created successfully<br/>";
    }
    else{
        echo "Error while creating AC".$i." table ".mysqli_error($conn)."<br/>";
    }

}




?>

