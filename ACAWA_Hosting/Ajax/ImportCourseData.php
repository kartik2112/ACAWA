<?php
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: ../login.php");
    }

    if(!isset($_POST['CourseData'])) header("location:../index.php");

    require "../CommonFiles/connection.php";
    require "../CommonFiles/CommonConstants.php";
    
    if($_POST['CourseData']['B']==""){
        // die("CourseErrorSubject Name not entered for a row.<br/>");
        $returnJSON['RowNo'] = $_POST['RowNo'];
        $returnJSON['status'] = 'CourseError';
        $returnJSON['sucessDescription'] = "";
        $returnJSON['errorDescription'] = "Subject Name not entered for a row.<br/>";
        die(json_encode($returnJSON));
    }

    if($_POST['CourseData']['A']=="" || $_POST['CourseData']['C']=="" || $_POST['CourseData']['D']==""){
        // die("CourseErrorOne of the columns A, C or D is not entered for Subject: ".$_POST['CourseData']['B']);
        $returnJSON['RowNo'] = $_POST['RowNo'];
        $returnJSON['status'] = 'CourseError';
        $returnJSON['sucessDescription'] = "";
        $returnJSON['errorDescription'] = "One of the columns A, C or D is not entered for Subject: ".$_POST['CourseData']['B'];
        die(json_encode($returnJSON));
    }


    $returnJSON['RowNo'] = $_POST['RowNo'];
    $insertionErrorMessage="";
    $sqlCheckCourse="select * from Subject where Subject_Name='".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['CourseData']['B'],0,SUBJ_NAME_LENGTH))))."' and Sem=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CourseData']['A'])));
    $resultCheckCourse=mysqli_query($conn,$sqlCheckCourse);
    if(mysqli_num_rows($resultCheckCourse)==0){
        $sqlInsertCourse="insert into Subject(Subject_Name,Sem,Teacher,Capacity,imagelink) values('".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['CourseData']['B'],0,SUBJ_NAME_LENGTH))))."',".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CourseData']['A']))).",'".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['CourseData']['C'],0,TEACHER_NAME_LENGTH))))."',".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CourseData']['D']))).",'StandardIcons/".rand(1,NO_OF_STD_IMAGES).".jpg')";
        if(!mysqli_query($conn,$sqlInsertCourse)){
            $returnJSON['status'] = 'CourseError';
            $insertionErrorMessage.="Error while adding Course Details of course: ".$_POST['CourseData']['B']." of Semester ".$_POST['CourseData']['A']." <br>".mysqli_error($conn)."<br/>";
        }        
    }
    else if(mysqli_num_rows($resultCheckCourse)==1){
        $returnJSON['status'] = 'CourseAlreadyExisting';
        $insertionErrorMessage.="Course: ".$_POST['CourseData']['B']." of Semester ".$_POST['CourseData']['A']." already exists <br/>";
    }

    if($insertionErrorMessage!=""){
        // echo $insertionErrorMessage."<br/>";
        $returnJSON['sucessDescription'] = "";
        $returnJSON['errorDescription'] = $insertionErrorMessage."<br/>";
        echo(json_encode($returnJSON));
    }
    else{
        // echo "CourseSuccess Course: ".$_POST['CourseData']['B']." of Semester: ".$_POST['CourseData']['A']." <br/>";
        $returnJSON['status'] = 'CourseSuccess';
        $returnJSON['errorDescription'] = "";
        $returnJSON['sucessDescription'] = "Course: ".$_POST['CourseData']['B']." of Semester: ".$_POST['CourseData']['A']." <br/>";
        echo(json_encode($returnJSON));
    }
    
      
?>