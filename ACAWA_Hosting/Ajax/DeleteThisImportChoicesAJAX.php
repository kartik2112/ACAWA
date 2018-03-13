<?php
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: ../login.php");
    }

    if(!isset($_POST['ChoiceData'])) header("location:../index.php");

    require "../CommonFiles/connection.php";
    require "../CommonFiles/CommonConstants.php";
    
    if($_POST['ChoiceData']['A']==""){
        die("ChoiceErrorRoll Number not entered for a row.<br/>");
    }

    if($_POST['ChoiceData']['B']=="" || $_POST['ChoiceData']['C']=="" || $_POST['ChoiceData']['D']=="" || $_POST['ChoiceData']['E']=="" || $_POST['ChoiceData']['F']=="" || $_POST['ChoiceData']['G']=="" || $_POST['ChoiceData']['H']=="" || $_POST['ChoiceData']['I']==""){
        die("ChoiceErrorOne of the columns B, C, D, E or F is not entered for Roll Number: ".$_POST['ChoiceData']['A']."<br/>");
    }

    $insertionErrorMessage="";
    $sqlCheckChoice="select * from AC".mysqli_real_escape_string($conn,stripslashes(trim($_POST['ChoiceData']['G'])))." where Roll_number=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['ChoiceData']['A'])));
    $resultCheckChoice=mysqli_query($conn,$sqlCheckChoice);
    if(mysqli_num_rows($resultCheckChoice)==0){
        $sqlInsertChoice="insert into AC".$_POST['ChoiceData']['G']."(Roll_number,pref_1,pref_2,pref_3,pref_4,pref_5,submittedOn) values(".$_POST['ChoiceData']['A'].",".$_POST['ChoiceData']['B'].",".$_POST['ChoiceData']['C'].",".$_POST['ChoiceData']['D'].",".$_POST['ChoiceData']['E'].",".$_POST['ChoiceData']['F'].",'".$_POST['ChoiceData']['H']." ".$_POST['ChoiceData']['I']."')";
        if(!mysqli_query($conn,$sqlInsertChoice)){
            $insertionErrorMessage.="ChoiceErrorError while recording choices of User with Roll No ".$_POST['ChoiceData']['A']." <br>".mysqli_error($conn)."<br/>";
        }        
    }
    else if(mysqli_num_rows($resultCheckChoice)==1){
        $insertionErrorMessage.="ChoiceAlreadyExistingUser with Roll No ".$_POST['ChoiceData']['A']." has submitted his choices already.<br/>";
    }

    if($insertionErrorMessage!=""){
        echo $insertionErrorMessage."<br/>";
    }
    else{
        echo "ChoiceSuccessChoices submitted by user with Roll No ".$_POST['ChoiceData']['A']." recorded successfully<br/>";
    }
    
      
?>