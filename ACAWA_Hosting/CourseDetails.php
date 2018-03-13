<?php
    session_start();
    if(!isset($_SESSION['userid']) ){
        header("location: login.php");
    }

    if(isset($_SESSION['Alogin'])){
        header("location: AdminHome.php");
    }

    require "CommonFiles/connection.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Details</title>
    <?php include("CommonFiles/CommonHead.php"); ?>
</head>

<body style = "">
    <?php include "CommonFiles/Menu.php"?>
    <script>
        $(document).ready(function () {
            $("#MenuCourseLI").addClass("active");
            $("#SideMenuCourseLI").addClass("active");
        });
    </script>
    
    <div style="margin: auto;overflow-x: scroll;width:80%;">
        <table class="myTable" style="margin: auto;">
            <caption>
                TABLE FOR SYLLABUS AND FACULTY ASSIGNED
            </caption>
            <tr class="myTableHeaderRow">
                <th></th> 
                <th style="text-align: center">NAME</th>
                <th>TEACHER</th> 
                <th>CAPACITY</th> 
                <th style="text-align:center;">LINK/FILE</th>
            </tr>
            <?php  //for showing the list of the subjects ~Raj
            $sqluser="select Semester from User where u_name='".$_SESSION['userid']."'";
            $resultsem=mysqli_query($conn,$sqluser);
            $sem = mysqli_fetch_array($resultsem,MYSQLI_ASSOC);
            $sem = $sem['Semester'];
  
            $sqlSelectSubjects="SELECT Subject_Name,File_Link,Teacher,Capacity,imagelink FROM Subject where Sem=$sem order by Subject_Name";
            $resultSubjects=mysqli_query($conn,$sqlSelectSubjects);
        
            while($selectedSub = mysqli_fetch_array($resultSubjects,MYSQLI_ASSOC)){
                echo '<tr>';
                                            
                echo '<td style="text-align:center;"><img src="images/SubjectIcons/'.$selectedSub['imagelink'].'" style="border-radius:50%;width:30px;height:30px;"/></td>';

                echo '<td>'.$selectedSub['Subject_Name'].'</td>';
                echo '<td>'.$selectedSub['Teacher'].'</td>';
                echo '<td>'.$selectedSub['Capacity'].'</td>';
                if(isset($selectedSub['File_Link']) && $selectedSub['File_Link']!=NULL){
                    echo '<td style="text-align:center;"><a class="waves-effect waves-light btn" target="_blank" href="UploadedDocs/Syllabus/'.$selectedSub['File_Link'].'">DOWNLOAD</a></td>';
                }
                else{
                    echo '<td style="text-align:center;"><a class="waves-effect waves-light btn disabled">NO FILE UPLOADED</a></td>';
                }  

                echo '</tr>';
            }
            ?>
       
        </table>
    </div>
    <br/><br/>
</body>
</html>