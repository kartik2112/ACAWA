<?php
    $romanArray = ['I','II','III','IV','V','VI','VII','VIII'];
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: login.php");
    }

    require "CommonFiles/connection.php";
    require "CommonFiles/CommonConstants.php";
    require("CommonFiles/ImpDatesXMLData.php");

    if(strtotime("$endDateFetched +4 hours") <= strtotime(date('Y/m/d H:i:s'))){
        $canAllot=TRUE;
    }
    else{
        $canAllot=FALSE;
    }

    //echo $sortingPerformedStatus;

    if($_SERVER['REQUEST_METHOD']=="POST" && $sortingPerformedStatus=="FALSE" && $_POST['action']=="allot" && $canAllot==TRUE ){
            for($i=1;$i<=NO_OF_SEMS;$i++){
                //sorting function
                $sqlAC="select * from AC".$i." where allottedChoice IS NULL order by submittedOn"; // ORDER BY Demo.submittedOn"; add thsi because no timestamp in demo
                $resultAC=mysqli_query($conn,$sqlAC);
    
                $sqlSub = "select Subj_ID,Capacity from Subject where Sem=".$i; 
                $resultSub = mysqli_query($conn,$sqlSub);

                if(mysqli_num_rows($resultAC)!=0 && mysqli_num_rows($resultSub)!=0){
                        $count = array();

                        //filling values in array
                        while($Sub =mysqli_fetch_array($resultSub,MYSQLI_ASSOC))
                        {
                            $count[$Sub['Subj_ID']] = $Sub['Capacity'];
                        }
    
                        //algorithm
                        while($Submit = mysqli_fetch_array($resultAC,MYSQLI_ASSOC))
                        {   
                            $rn =  $Submit['Roll_number'];

                            if($count[$Submit['pref_1']] > 0)
                            {
                                $sqlAllot = "UPDATE AC".$i." SET allottedChoice =".$Submit['pref_1']." WHERE Roll_number =".$rn;
                                if(!mysqli_query($conn,$sqlAllot)){
                                    echo 'Error while allotting pref 1 audit course to Roll number '.$rn;
                                }
                                $count[$Submit['pref_1']]--;

                            }
                            elseif($count[$Submit['pref_2']] > 0)
                            {
                                $sqlAllot = "UPDATE AC".$i." SET allottedChoice =".$Submit['pref_2']." WHERE Roll_number =".$rn;
                                if(!mysqli_query($conn,$sqlAllot)){
                                    echo 'Error while allotting pref 2 audit course to Roll number '.$rn;
                                }
                                $count[$Submit['pref_2']]--;
                            }
                            elseif($count[$Submit['pref_3']] > 0)
                            {
                                $sqlAllot = "UPDATE AC".$i." SET allottedChoice =".$Submit['pref_3']." WHERE Roll_number =".$rn;
                                if(!mysqli_query($conn,$sqlAllot)){
                                    echo 'Error while allotting pref 3 audit course to Roll number '.$rn;
                                }
                                $count[$Submit['pref_3']]--;
                            }
                            elseif($count[$Submit['pref_4']] > 0)
                            {
                                $sqlAllot = "UPDATE AC".$i." SET allottedChoice =".$Submit['pref_4']." WHERE Roll_number =".$rn;
                                if(!mysqli_query($conn,$sqlAllot)){
                                    echo 'Error while allotting pref 4 audit course to Roll number '.$rn;
                                }
                                $count[$Submit['pref_4']]--;
                            }
                            elseif($count[$Submit['pref_5']] > 0)
                            {
                                $sqlAllot = "UPDATE AC".$i." SET allottedChoice =".$Submit['pref_5']." WHERE Roll_number =".$rn;
                                if(!mysqli_query($conn,$sqlAllot)){
                                    echo 'Error while allotting pref 5 audit course to Roll number '.$rn;
                                }
                                $count[$Submit['pref_5']]--;
                            }
                            else
                            {
                                //Student not allotted any audit course
                            }
                        }
                }
            }
            $tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName( "SortingDate" );
            $tempDataImpDatesXML ->item(0)->nodeValue=date("Y-m-d");   //Set the sorting date to today
            $tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName( "SortingPerformed" );
            $tempDataImpDatesXML ->item(0)->nodeValue="TRUE";   //Indicate that sorting has been performed
            $ImpDatesXMLDoc->save("ImpFiles/ImpDatesXMLData.xml");

            require("CommonFiles/ImpDatesXMLData.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include("CommonFiles/CommonHead.php"); ?>
        <title>Allottment Stats</title>


        <?php
            if($sortingPerformedStatus=="TRUE"){
        ?>


                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        <script type="text/javascript">
                            google.charts.load('current', { 'packages': ['bar', 'corechart'] });
                            <?php
                                for($i=1;$i<=NO_OF_SEMS;$i++){
                    
                                    $sqlSearchSubjects="select Subject_Name,Subj_ID,Capacity from Subject where Sem=".$i;
                                    $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                                    if(mysqli_num_rows($resultSearchSubjects)!=0){
                                        $noOfSubjs=mysqli_num_rows($resultSearchSubjects);
                        
                                        //Bar Chart
                                        echo 'google.charts.setOnLoadCallback(drawBarChartSem'.$i.');';
                                        echo 'function drawBarChartSem'.$i.'() {';
                                        echo '    var data = google.visualization.arrayToDataTable([';
                                        echo "        ['Subject', 'Filled Seats', 'Empty Seats'],";

                                        while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects,MYSQLI_ASSOC)){
                                            $sqlFindFilled="select count(*) from AC".$i." where allottedChoice=".$rowSearchSubjects['Subj_ID'];
                                            $resultFindFilled=mysqli_query($conn,$sqlFindFilled);
                                            $rowFindFilled=mysqli_fetch_array($resultFindFilled,MYSQLI_ASSOC);
                                            echo "        ['".mysqli_real_escape_string($conn,stripslashes($rowSearchSubjects['Subject_Name']))."', ".$rowFindFilled['count(*)']." ,".( intval($rowSearchSubjects['Capacity']) - intval($rowFindFilled['count(*)']) )."],";
                                        }

                                        echo "    ]);";

                                        echo "    var options = {";
                                        echo "        chart: {";
                                        echo "            title: 'Subject-wise Seats Allotment',";
                                        echo "            subtitle: 'Semester ".$romanArray[$i-1]."'";
                                        echo "        },";
                                        echo "        bars: 'horizontal', ";
                                        echo "        height: ".(35*$noOfSubjs)."";
                                        echo "    };";

                                        echo "    var chart = new google.charts.Bar(document.getElementById('barchart_material_Sem".$i."'));";
                                        echo "    chart.draw(data, options);";
                                        echo "}";

                                    }

                                    //Pie Chart
                                    $sqlSearchSubjects="select Subject_Name,Subj_ID,Capacity from Subject where Sem=".$i;
                                    $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                                    if(mysqli_num_rows($resultSearchSubjects)!=0){
                                        $noOfSubjs=mysqli_num_rows($resultSearchSubjects);
                                        echo "google.charts.setOnLoadCallback(drawPieChartSem".$i.");";
                                        echo "function drawPieChartSem".$i."() {";
                                        echo "    var data = google.visualization.arrayToDataTable([";
                                        echo "        ['Subject', 'No of Students'],";

                                        $totalSeats=0;
                                        $totalFilled=0;
                                        while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects)){
                                            $sqlFindFilled="select count(*) from AC".$i." where allottedChoice=".$rowSearchSubjects['Subj_ID'];
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
                                        echo "        subtitle: 'Semester ".$romanArray[$i-1]."',";
                                        echo "        height: 300";
                                        echo "    };";

                                        echo "    var chart = new google.visualization.PieChart(document.getElementById('piechart_Sem".$i."'));";

                                        echo "    chart.draw(data, options);";
                                        echo "}";

                                    }
                                }
                            ?>
                        </script>
                        <script>
                            function DispayThoseGraphs() {
                                $("div.chartsSlidable").slideUp(1000);                
                                switch($("ul.select-dropdown>li.active").val()){
                                    <?php
                                        $sqlSearchSubjects="select count(Subject_Name),Sem from Subject group by Sem";
                                        $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                                        if(mysqli_num_rows($resultSearchSubjects)!=0){
                                            while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects,MYSQLI_ASSOC)){
                                                if($rowSearchSubjects['count(Subject_Name)']!=0){
                                                    echo 'case '.$rowSearchSubjects['Sem'].': $("div#Sem'.$rowSearchSubjects['Sem'].'").slideDown(1000);';
                                                    echo '        break;';
                                                }
                                            }
                                        }
                                    ?>
                    
                                }
                
                            }
                        </script>
                        <script>
                            $(window).resize(function () {
                                <?php                
                                    $sqlSearchSubjects="select count(Subject_Name),Sem from Subject group by Sem";
                                    $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                                    if(mysqli_num_rows($resultSearchSubjects)!=0){
                                        while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects,MYSQLI_ASSOC)){
                                            if($rowSearchSubjects['count(Subject_Name)']!=0){
                                                echo 'drawBarChartSem'.$rowSearchSubjects['Sem'].'();';
                                                echo 'drawPieChartSem'.$rowSearchSubjects['Sem'].'();';
                                            }
                                        }
                                    }
                                ?>            
                            });

                        </script>


        <?php
            }
            //Closing bracket of main if clause in PHP (checking whether deadline date has passed)
        ?>


        <?php
            if($canAllot==FALSE){
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
                        futureDate.setHours(4,0,0,0); 
                        // Calculate the difference in seconds between the future and current date
                        var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
                        // Instantiate a coutdown FlipClock
                        clock = $('#deadline_countdown').FlipClock(diff, {
                            clockFace: 'DailyCounter',
                            countdown: true,
                            callbacks: {
                                stop: function () {
                                    $('#deadlineOverMessage').html('Refreshing Page...');                                    
                                    window.location.reload(true);                        
                                }
                            }
                        });
                    });
	            </script>

        <?php
            }
        ?>

</head>
<body>
        <?php include "CommonFiles/Menu.php"; ?>
        <script>
            $(document).ready(function () {
                $('select').material_select();
                $("#MenuAllottmentLI").addClass("active");
                $("#SideMenuAllottmentLI").addClass("active");
            });
        </script>

        <h3 style="text-align: center;display: block;margin: auto;">Welcome, Admin</h3>


        <?php
            if($sortingPerformedStatus=="FALSE" && $canAllot==FALSE){
        ?>

                    <h3>You can Allot Choices in ...</h3>
                        <div id="deadline_countdown" style="text-align: center;margin: 2em auto;width: 620px"></div>
                    <h5 id="deadlineOverMessage" style="margin: auto;text-align: center;"></h5>
        
        <?php
            }
            if($sortingPerformedStatus=="FALSE" && $canAllot==TRUE){
        ?>
                    <form action="" method="post">
                        <button class="modal-action modal-close waves-effect waves-green btn" type="submit" name="action" value="allot">Allot
                            <i class="material-icons right">poll</i>
                        </button>
                    </form>
                    <br/><br/>

        <?php
            }
        ?>

        

        <?php
            if($canAllot==TRUE && $sortingPerformedStatus=="TRUE"){
        ?>
        

                    <div style="text-align: center;width: 98%;margin: auto;">
            
                        <div class="input-field col s6" style="width: 60%;margin: auto;">                
                            <select onchange="DispayThoseGraphs()">
                                <option value="none" disabled selected>SELECT SEMESTER</option>
                                <?php           
                                    $sqlSearchSubjects="select count(Subject_Name),Sem from Subject group by Sem";
                                    $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                                    if(mysqli_num_rows($resultSearchSubjects)!=0){
                                        while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects,MYSQLI_ASSOC)){
                                            if($rowSearchSubjects['count(Subject_Name)']!=0){
                                                echo '<option value="'.$rowSearchSubjects['Sem'].'">SEMESTER '.$romanArray[$rowSearchSubjects['Sem']-1].'</option>';
                                            }
                                        }
                                    }
                        
                                ?>
                            </select> 
                            <label >SELECT THE SEMESTER</label>
                        </div>
                        <H2>
                            STATISTICS            
                        </H2>
                        <?php               
                            $sqlSearchSubjects="select count(Subject_Name),Sem from Subject group by Sem";
                            $resultSearchSubjects=mysqli_query($conn,$sqlSearchSubjects);
                            if(mysqli_num_rows($resultSearchSubjects)!=0){
                                while($rowSearchSubjects=mysqli_fetch_array($resultSearchSubjects,MYSQLI_ASSOC)){
                                    if($rowSearchSubjects['count(Subject_Name)']!=0){
                                        echo '<div id="Sem'.$rowSearchSubjects['Sem'].'" class="chartsSlidable">';
                                        echo'    <div id="barchart_material_Sem'.$rowSearchSubjects['Sem'].'" class="z-depth-4 charts" style="padding: 20px;border-radius: 10px;background-color: white;"></div>';
                                        echo '    <br/><br/>';
                                        echo '    <div id="piechart_Sem'.$rowSearchSubjects['Sem'].'" class="z-depth-4 charts" style="padding: 10px;border-radius: 10px;background-color: white;"></div>';
                                        echo '    <BR><BR>';
                                        echo '</div>';
                                    }
                                }
                            }
                
                        ?>
            
            
                    </div> 
        <?php
            }
            //Closing bracket of main if clause in PHP (checking whether deadline date has passed)
        ?>    


    </body>
</html>
