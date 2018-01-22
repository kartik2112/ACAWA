<?php
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: ../login.php");
    }

    if(!isset($_POST['UserData'])) header("location:../index.php");

    require "../CommonFiles/connection.php";
    require "../CommonFiles/CommonConstants.php";
    
    if($_POST['UserData']['A']=="" || $_POST['UserData']['B']=="" || $_POST['UserData']['C']=="" || $_POST['UserData']['D']=="" || $_POST['UserData']['E']=="" ){
        die("UserErrorOne of the columns A, B, C, D or E is not entered for a row.<br/>");
    }
    $insertionErrorMessage="";
    $sqlCheckUser="select * from User where Roll_number='".mysqli_real_escape_string($conn,stripslashes(trim($_POST['UserData']['A'])))."'";
    $resultCheckUser=mysqli_query($conn,$sqlCheckUser);
    if(mysqli_num_rows($resultCheckUser)==0){
        if($_POST['UserData']['E']=="A"){
            $sqlInsertUser="insert into User(Roll_number,Name,u_name,pwd,Type) values(".mysqli_real_escape_string($conn,stripslashes(trim($_POST['UserData']['A']))).",'".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['UserData']['B'],0,USER_NAME_LENGTH))))."','".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['UserData']['C'],0,UNAME_LENGTH))))."','".md5('kjsce')."','".mysqli_real_escape_string($conn,stripslashes(trim($_POST['UserData']['E'])))."')";
            if(!mysqli_query($conn,$sqlInsertUser)){
                $insertionErrorMessage.="UserErrorError while adding User Details of User with Roll No ".$_POST['UserData']['A']." <br>".mysqli_error($conn)."<br/>";
            }
        }
        else{
            $sqlInsertUser="insert into User(Roll_number,Name,Semester,u_name,pwd,Type,Branch) values(".mysqli_real_escape_string($conn,stripslashes(trim($_POST['UserData']['A']))).",'".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['UserData']['B'],0,USER_NAME_LENGTH))))."','".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['UserData']['C'],0,UNAME_LENGTH))))."','".$_POST['UserData']['D']."','".md5('kjsce')."','".mysqli_real_escape_string($conn,stripslashes(trim($_POST['UserData']['E'])))."','".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['UserData']['F'],0,BRANCH_LENGTH))))."')";
            if(!mysqli_query($conn,$sqlInsertUser)){
                $insertionErrorMessage.="UserErrorError while adding User Details of User with Roll No ".$_POST['UserData']['A']." <br>".mysqli_error($conn)."<br/>";
            }
        }
                
    }
    else if(mysqli_num_rows($resultCheckUser)==1){
        $insertionErrorMessage.="UserAlreadyExistingUser with Roll No ".$_POST['UserData']['A']." already exists <br/>";
    }

    if($insertionErrorMessage!=""){
        echo $insertionErrorMessage."<br/>";
    }
    else{
        echo "UserSuccess User with Roll No ".$_POST['UserData']['A']." <br/>";
    }
    
      
?>