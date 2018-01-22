<?php
    $romanArray=['I','II','III','IV','V','VI','VII','VIII'];
    session_start();
    if(!isset($_SESSION['userid']) || isset($_SESSION['Alogin']) ){
        header("location: login.php");
    }
    require "CommonFiles/connection.php";
    require "CommonFiles/CommonConstants.php";
    require("CommonFiles/ImpDatesXMLData.php");

    $sqlGetUserData="select Roll_number,Semester from User where u_name='".$_SESSION['userid']."'";
    $resultGetUserData=mysqli_query($conn,$sqlGetUserData);
    $rowGetUserData=mysqli_fetch_array($resultGetUserData,MYSQLI_ASSOC);
        
?>

<!DOCTYPE html> <!-- for HTML 5 -->
<html>
<head>
    <title>Results</title>
    <?php include("CommonFiles/CommonHead.php"); ?>
    
    <?php
        if(strtotime($endDateFetched) > strtotime(date("Y-m-d"))){
    ?>
            
            <!--Flip Clock Section-->
            <link rel="stylesheet" href="FlipClock-master/compiled/flipclock.css">
	        <script src="FlipClock-master/compiled/flipclock.js"></script>	
            <script type="text/javascript">
                var clock;
                $(document).ready(function () {
                    // Grab the current date
                    var currentDate = new Date("<?php echo date('Y/m/d H:i:s'); ?>");
                    var futureDate = new Date("<?php echo $endDateFetched; ?>" );
                    futureDate.setHours(0,0,0,0); 
                    // Calculate the difference in seconds between the future and current date
                    var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
                    // Instantiate a coutdown FlipClock
                    clock = $('#deadline_countdown').FlipClock(diff, {
                        clockFace: 'DailyCounter',
                        countdown: true,
                        callbacks: {
                            stop: function () {
                                var timeForReload=parseInt(Math.random()*1000);
                                $('#deadlineOverMessage').html('Refreshing Page in '+timeForReload/1000+' seconds...');
                                window.setTimeout(function(){
                                    $("#PopupOutsideDiv").fadeIn(200);
                                    $(".container").addClass("BlurredBGForPopup");
                                    $(".navbar-fixed").addClass("BlurredBGForPopup");
                                },timeForReload/2);
                        
                                window.setTimeout(function(){
                                    window.location.reload(true);
                                },timeForReload);
                        
                            }
                        }
                    });
                });
	        </script>

    <?php
        }

        if(strtotime($endDateFetched)<=strtotime(date("Y-m-d")) && $sortingPerformedStatus=="TRUE"){
    ?>
                
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script >
                    google.charts.load('current', { 'packages': ['bar', 'corechart'] });
                    <?php
                            
                            $sqlSearchSubjects="select Subject_Name,Subj_ID,Capacity from Subject where Sem=".$rowGetUserData['Semester'];
                            $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                            if(mysqli_num_rows($resultSearchSubjects)!=0){
                                $noOfSubjs=mysqli_num_rows($resultSearchSubjects);
                        
                                //Bar Chart
                                echo 'google.charts.setOnLoadCallback(drawBarChart);';
                                echo 'function drawBarChart() {';
                                echo '    var data = google.visualization.arrayToDataTable([';
                                echo        " ['Subject', 'Filled Seats', 'Empty Seats'],";

                                $tempCount=0;
                                while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects,MYSQLI_ASSOC)){
                                    $sqlFindFilled="select count(*) from AC".$rowGetUserData['Semester']." where allottedChoice=".$rowSearchSubjects['Subj_ID'];
                                    $resultFindFilled=mysqli_query($conn,$sqlFindFilled);
                                    $rowFindFilled=mysqli_fetch_array($resultFindFilled,MYSQLI_ASSOC);
                                    echo        " ['".mysqli_real_escape_string($conn,stripslashes($rowSearchSubjects['Subject_Name']))."', ".$rowFindFilled['count(*)']." ,".( intval($rowSearchSubjects['Capacity']) - intval($rowFindFilled['count(*)']) )."]";
                                    $tempCount++;
                                    if($tempCount!=$noOfSubjs){
                                        echo ",";
                                    }
                                }

                                echo "    ]);";

                                echo "    var options = {";
                                echo "        chart: {";
                                echo "            title: 'Subject-wise Seats Allotment',";
                                echo "            subtitle: 'Semester ".$romanArray[$rowGetUserData['Semester']-1]."'";
                                echo "        },";
                                echo "        bars: 'horizontal', ";
                                echo "        height: ".(35*$noOfSubjs)."";
                                echo "    };";

                                echo "    var chart = new google.charts.Bar(document.getElementById('barchart_material'));";
                                echo "    chart.draw(data, options);";
                                echo "}";

                            }

                            //Pie Chart
                            $sqlSearchSubjects="select Subject_Name,Subj_ID,Capacity from Subject where Sem=".$rowGetUserData['Semester'];
                            $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                            if(mysqli_num_rows($resultSearchSubjects)!=0){
                                $noOfSubjs=mysqli_num_rows($resultSearchSubjects);
                                echo "google.charts.setOnLoadCallback(drawPieChart);";
                                echo "function drawPieChart() {";
                                echo "    var data = google.visualization.arrayToDataTable([";
                                echo "        ['Subject', 'No of Students'],";

                                $totalSeats=0;
                                $totalFilled=0;
                                while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects)){
                                    $sqlFindFilled="select count(*) from AC".$rowGetUserData['Semester']." where allottedChoice=".$rowSearchSubjects['Subj_ID'];
                                    $resultFindFilled=mysqli_query($conn,$sqlFindFilled);
                                    $rowFindFilled=mysqli_fetch_array($resultFindFilled,MYSQLI_ASSOC);
                                    echo "        ['".mysqli_real_escape_string($conn,stripslashes($rowSearchSubjects['Subject_Name']))."', ".$rowFindFilled['count(*)']."],";
                                    $totalFilled+=intval($rowFindFilled['count(*)']);
                                    $totalSeats+=intval($rowSearchSubjects['Capacity']);
                                }

                                echo "        ['Unalloted', ".($totalSeats - $totalFilled)."]";
                                echo "    ]);";

                                echo "    var options = {";
                                echo "        title: 'Subject-wise Students Allotment',";
                                echo "        subtitle: 'Semester ".$romanArray[$rowGetUserData['Semester']-1]."',";
                                echo "        height: 300";
                                echo "    };";

                                echo "    var chart = new google.visualization.PieChart(document.getElementById('piechart'));";

                                echo "    chart.draw(data, options);";
                                echo "}";

                            }
                        
                    ?>
                </script>
                <script>
                    $(window).resize(function () {
                        drawBarChart();
                        drawPieChart();
                    });

                </script>
    <?php
        }
        //Closing bracket of main if clause in PHP (checking whether deadline date has passed)
    ?>



    <script src="JS/Results.js"></script>
    <style>
        .circle-clipper .circle{
            border-width: 8px;
            
        }        
        .preloader-wrapper{
            position: absolute;
            display: block;
            
        }
    </style>
</head>

<body style = "">
    
    <script>
        $(document).ready(function () {
            $("#MenuResultsLI").addClass("active");
            $("#SideMenuResultsLI").addClass("active");
            $('.collapsible').collapsible({
                accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
            });
        });
    </script>

    
    <?php include "CommonFiles/Menu.php"?>
    
    
    <div class="container">
            
            

            <?php
                if(strtotime($endDateFetched) > strtotime(date("Y-m-d"))){
            ?>

                        <h3>Choices Submission Deadline will occur in ...</h3>
                            <div id="deadline_countdown" style="text-align: center;margin: 2em auto;width: 620px"></div>
                        <h5 id="deadlineOverMessage" style="margin: auto;text-align: center;"></h5>
        
            <?php
                }
                else if($sortingPerformedStatus=="FALSE"){
                    echo '<h5 style="margin: auto;text-align: center;"> Wait for the results to be declared...</h5>';
                }

                if(strtotime($endDateFetched)<=strtotime(date("Y-m-d")) && $sortingPerformedStatus=="TRUE"){
            ?>    
                        
                        
                            <?php
                                $sqlSearchAC="select pref_1,allottedChoice from AC".$rowGetUserData['Semester']." where Roll_number=".$rowGetUserData['Roll_number'];
                                $resultSearchAC=mysqli_query($conn,$sqlSearchAC);
                                if(mysqli_num_rows($resultSearchAC)==1){
                                    $rowSearchAC=mysqli_fetch_array($resultSearchAC);
                                    if($rowSearchAC['allottedChoice']!=NULL){
                                        $subjectSearch="select * from Subject where Subj_ID=".$rowSearchAC['allottedChoice'];
                                        $resultSubjectSearch=mysqli_query($conn,$subjectSearch);
                                        $rowSubjectSearch=mysqli_fetch_array($resultSubjectSearch,MYSQLI_ASSOC);
                                        echo '<div class="row"><div class="col s12 m6 offset-m3">';
                                        echo '<h4>You have been allotted the following course:</h4>';
                                        echo    '<ul class="collapsible popout accordionMyStyle" data-collapsible="accordion" style="">';
                                        echo        '<li><div class="collapsible-header"><i class="material-icons">filter_'.$i.'</i>'.$rowSubjectSearch['Subject_Name'].'</div>';
                                        echo            '<div class="collapsible-body">';
                                        echo                '<img src="images/SubjectIcons/'.$rowSubjectSearch['imagelink'].'" style="border-radius:50%;width:70px;height:70px;float:left;margin-left:20px;margin-top:20px;"/>';
                                        echo                '<div style="margin-left:100px;padding:10px;">';
                                        echo                    "Teacher: ".$rowSubjectSearch['Teacher']."<br/><hr>";
                                        echo                    "Capacity: ".$rowSubjectSearch['Capacity']."<br/><hr>";
                                        echo                $rowSubjectSearch['File_Link']."&nbsp;&nbsp;&nbsp;";
                                        if(isset($rowSubjectSearch['File_Link']) && $rowSubjectSearch['File_Link']!=NULL){
                                                                echo '<a class="waves-effect waves-light btn" target="_blank" href="UploadedDocs/Syllabus/'.$rowSubjectSearch['File_Link'].'">DOWNLOAD</a>';
                                        }
                                        else{
                                                                echo '<a class="waves-effect waves-light btn disabled">NO FILE UPLOADED</a>';
                                        }  
                                        echo                '</div>';
                                        echo            '</div>';
                                        echo        '</li>';
                                        echo    '</ul>';
                                        echo '</div></div>';
                                    }
                                    else{
                                        echo '<h4 style="margin:auto;text-align:center;">You have not been allotted any Audit Course</h4>';
                                        echo '<h5>Please meet the person-in-charge that will be conveyed to you for further information.</h5>';
                                    }
                                }
                                else if(mysqli_num_rows($resultSearchAC)==0){
                                    echo '<h4 style="margin:auto;text-align:center;">You have not filled your Choices yet</h4>';
                                    echo '<h5>Please meet the person-in-charge that will be conveyed to you for further information.</h5>';
                                }
                                
                            ?>
                        
                        <div id="SemChart" class="chartsSlidable" style="margin: auto;">
                                    <div id="barchart_material" class="z-depth-4" style="padding: 20px;border-radius: 10px;background-color: white;"></div>
                                    <br/><br/>
                                    <div id="piechart" class="z-depth-4" style="padding: 10px;border-radius: 10px;background-color: white;"></div>
                                    <BR><BR>
                                </div>
          
                        <div style="margin: auto;overflow-x: scroll;padding: 20px;">  
                            <TABLE STYLE="WIDTH:80%;margin: auto;text-align: center;" CLASS="myTable">
                                <CAPTION>FINAL ALLOTMENT OF SUBJECTS TO THE APPLICANTS</CAPTION>
    
                                <tr>
                                    <th></th>
                                    <th>SUBJECT NAME</th>
                                    <th>CAPACITY</th> 
                                    <th>NO OF STUDENTS ALLOTED</th> 
                                    <th>TEACHER</th> 
                                    <th>LINK/FILE</th>
                                    <th>VIEW STUDENTS LIST</th>
                                </tr>
                                <?php
                                    $sqlUser="select Semester from User where u_name='".$_SESSION['userid']."'";
                                    $resultUser=mysqli_query($conn,$sqlUser);
                                    $rowUser = mysqli_fetch_array($resultUser,MYSQLI_ASSOC);
                                    $sem = $rowUser['Semester'];
  
                                    $sqlSelectSubjects="SELECT Subj_ID,Subject_Name,File_Link,Teacher,Capacity,imagelink FROM Subject where Sem=$sem order by Subject_Name";
                                    $resultSubjects=mysqli_query($conn,$sqlSelectSubjects);
        
                                    while($rowSubject = mysqli_fetch_array($resultSubjects,MYSQLI_ASSOC)){
                                        echo '<tr>';
                                            
                                        echo '<td style="text-align:center;"><img src="images/SubjectIcons/'.$rowSubject['imagelink'].'" style="border-radius:50%;width:30px;height:30px;"/></td>';
                                        echo '<td>'.$rowSubject['Subject_Name'].'</td>';
                                        echo '<td>'.$rowSubject['Capacity'].'</td>';

                                        $sqlSearchAllottedStuds="select count(*) from AC".$sem." where allottedChoice=".$rowSubject['Subj_ID'];
                                        $resultAllottedStuds=mysqli_query($conn,$sqlSearchAllottedStuds);
                                        $rowAllottedStuds=mysqli_fetch_array($resultAllottedStuds,MYSQLI_ASSOC);
                                        echo '<td>'.$rowAllottedStuds['count(*)'].'</td>';

                                        echo '<td>'.$rowSubject['Teacher'].'</td>';                    
                                        if(isset($rowSubject['File_Link']) && $rowSubject['File_Link']!=NULL){
                                            echo '<td style="text-align:center;"><a class="waves-effect waves-light btn" target="_blank" href="UploadedDocs/Syllabus/'.$rowSubject['File_Link'].'">DOWNLOAD</a></td>';
                                        }
                                        else{
                                            echo '<td style="text-align:center;"><a class="waves-effect waves-light btn disabled">NO FILE UPLOADED</a></td>';
                                        }  
                                        if($rowAllottedStuds['count(*)']==0){
                                            echo '<td><a class="waves-effect waves-light btn disabled" >VIEW</a></td>';
                                        }
                                        else{
                                            echo '<td><a class="waves-effect waves-light btn requestViewStudList" data-subj-id="'.$rowSubject['Subj_ID'].'" data-sem="'.$sem.'">VIEW</a></td>';
                                        }
                    

                                        echo '</tr>';
                                    }
                                ?>
            
                            </TABLE>
                        </div>

            <?php
                }
                //Closing bracket of main if clause in PHP (checking whether deadline date has passed)
            ?>


    </div>
    <?php
        if(strtotime($endDateFetched)<=strtotime(date("Y-m-d")) && $sortingPerformedStatus=="TRUE"){
    ?>
                <br/><br/>
                <div id="modalDisplayList" class="modal top-sheet">
                    <div id="DisplayStudsListHere" class="modal-content"></div>
                    <div class="modal-footer">
                        <a href="#!" id="closeModalDisplayList" class=" modal-action modal-close waves-effect btn-flat">Close <i class="material-icons">close</i></a>
                    </div>
                </div>
    <?php
        }
        //Closing bracket of main if clause in PHP (checking whether deadline date has passed)
    ?>


    <!--This div is the code for the color changing loader Reference: Materialize - preloader -->
    <div id="PopupOutsideDiv" style="display: none;position: fixed;width: 100%;height: 100%;background-color: rgba(0,0,0,0.4);top: 0px; left: 0px; right: 0px; bottom: 0px; z-index: 19900;">    
        <div id="PopupDiv" style="position: fixed;top: 0px;left: 0px;right: 0px;bottom: 0px;margin: auto;vertical-align: middle;text-align: center;z-index:19999;width: 100px;height: 100px;">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
                </div>

                <div class="spinner-layer spinner-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
                </div>

                <div class="spinner-layer spinner-yellow">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
                </div>

                <div class="spinner-layer spinner-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    
</body>
</html>