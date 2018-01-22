<?php
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: ../login.php");
    }

    if(!isset($_POST['CourseData'])) header("location:../index.php");

    require "../CommonFiles/connection.php";
    require "../CommonFiles/CommonConstants.php";
    
    if($_POST['CourseData']['B']==""){
        die("CourseErrorSubject Name not entered for a row.<br/>");
    }

    if($_POST['CourseData']['A']=="" || $_POST['CourseData']['C']=="" || $_POST['CourseData']['D']==""){
        die("CourseErrorOne of the columns A, C or D is not entered for Subject: ".$_POST['CourseData']['B']);
    }

    $insertionErrorMessage="";
    $sqlCheckCourse="select * from Subject where Subject_Name='".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['CourseData']['B'],0,SUBJ_NAME_LENGTH))))."' and Sem=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CourseData']['A'])));
    $resultCheckCourse=mysqli_query($conn,$sqlCheckCourse);
    if(mysqli_num_rows($resultCheckCourse)==0){
        $sqlInsertCourse="insert into Subject(Subject_Name,Sem,Teacher,Capacity,imagelink) values('".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['CourseData']['B'],0,SUBJ_NAME_LENGTH))))."',".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CourseData']['A']))).",'".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['CourseData']['C'],0,TEACHER_NAME_LENGTH))))."',".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CourseData']['D']))).",'StandardIcons/".rand(1,NO_OF_STD_IMAGES).".jpg')";
        if(!mysqli_query($conn,$sqlInsertCourse)){
            $insertionErrorMessage.="CourseErrorError while adding Course Details of course: ".$_POST['CourseData']['B']." of Semester ".$_POST['CourseData']['A']." <br>".mysqli_error($conn)."<br/>";
        }        
    }
    else if(mysqli_num_rows($resultCheckCourse)==1){
        $insertionErrorMessage.="CourseAlreadyExistingCourse: ".$_POST['CourseData']['B']." of Semester ".$_POST['CourseData']['A']." already exists <br/>";
    }

    if($insertionErrorMessage!=""){
        echo $insertionErrorMessage."<br/>";
    }
    else{
        echo "CourseSuccess Course: ".$_POST['CourseData']['B']." of Semester: ".$_POST['CourseData']['A']." <br/>";
    }
    
      
?>