<?php
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: login.php");
    }

    require "CommonFiles/connection.php";
    require "CommonFiles/CommonConstants.php";
    require("CommonFiles/ImpDatesXMLData.php");

    $sqlTotalNoOfStuds="select count(Roll_number) from User where Type='S'";
    $resultTotalNoOfStuds=mysqli_query($conn,$sqlTotalNoOfStuds);
    $rowTotalNoOfStuds=mysqli_fetch_array($resultTotalNoOfStuds,MYSQLI_ASSOC);
    $totalNoOfStuds=$rowTotalNoOfStuds['count(Roll_number)'];
    
    $filledNoOfStuds=0;
    for($i=1;$i<=NO_OF_SEMS;$i++){
        $sqlACNoOfStuds="select count(*) as noOfStuds from AC".$i;
        $resultACNoOfStuds=mysqli_query($conn,$sqlACNoOfStuds);
        $rowACNoOfStuds=mysqli_fetch_array($resultACNoOfStuds,MYSQLI_ASSOC);
        $filledNoOfStuds=$filledNoOfStuds+intval($rowACNoOfStuds['noOfStuds']);
    }

    //Read XML file containing dates of deadlines
    $errorMessage="";
    $tempDoc = new DOMDocument();
    $tempDoc->load('ImpFiles/ImpDatesXMLData.xml');
    $tempData = $tempDoc->getElementsByTagName( "DeadlineBeginDate" );
    $startDateFetched=$tempData ->item(0)->nodeValue;
    $tempData = $tempDoc->getElementsByTagName( "DeadlineEndDate" );
    $endDateFetched=$tempData ->item(0)->nodeValue;
    $tempData = $tempDoc->getElementsByTagName( "Key" );
    $Key=$tempData ->item(0)->nodeValue;
    if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['action']) ){
        if((md5($_POST['Key'])==$Key)){
            $_POST['DeadlineStartDate']=str_replace(",","",$_POST['DeadlineStartDate']);
            $_POST['DeadlineEndDate']=str_replace(",","",$_POST['DeadlineEndDate']);
            $tempStartDateFetched=date("Y-m-d",strtotime($_POST['DeadlineStartDate']));
            $tempEndDateFetched=date("Y-m-d",strtotime($_POST['DeadlineEndDate']));
            if($tempStartDateFetched < $tempEndDateFetched){            
                $tempData = $tempDoc->getElementsByTagName( "DeadlineBeginDate" );
                $tempData ->item(0)->nodeValue=$tempStartDateFetched;
                $tempData = $tempDoc->getElementsByTagName( "DeadlineEndDate" );
                $tempData ->item(0)->nodeValue=$tempEndDateFetched;
                $tempData = $tempDoc->getElementsByTagName( "DateModified" );
                $tempData ->item(0)->nodeValue=date("Y-m-d");
                $tempData = $tempDoc->getElementsByTagName( "SortingPerformed" );
                $tempData ->item(0)->nodeValue="FALSE";
                $tempDoc->save('ImpFiles/ImpDatesXMLData.xml');
                $tempData = $tempDoc->getElementsByTagName( "DeadlineBeginDate" );
                $startDateFetched=$tempData ->item(0)->nodeValue;
                $tempData = $tempDoc->getElementsByTagName( "DeadlineEndDate" );
                $endDateFetched=$tempData ->item(0)->nodeValue;
            }
            else{
                $errorMessage.="Incorrect dates provided! <br/>Submission start date should be before deadline date.<seperator>";
            }
        }
        else{
            $errorMessage.="Incorrect Admin Key provided! <br/>Cannot update dates.<seperator>";
        }
        
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Home</title>
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



    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        .menuTable td{
            text-align: center;
            margin: auto;
        }
        .picker__wrap{
            max-height: 80%!important;
            margin: 0 auto;
            zoom: 80%;
        }
    </style>
</head>

<body style = "">
    <?php include "CommonFiles/Menu.php"?>
    
    <script>
        var pickerStart,pickerEnd;
        $(document).ready(function () {
            //XML interaction part
            var deadlineStartIP = $('#DeadlineStartDate').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 3 // Creates a dropdown of 15 years to control year
            });
            var deadlineEndIP = $('#DeadlineEndDate').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 3 // Creates a dropdown of 15 years to control year
            });
            pickerStart = deadlineStartIP.pickadate('picker');
            pickerEnd = deadlineEndIP.pickadate('picker');

            pickerStart.set('select', '<?php echo $startDateFetched; ?>', { format: 'yyyy-mm-dd' });
            pickerEnd.set('select', '<?php echo $endDateFetched;; ?>', { format: 'yyyy-mm-dd' });
            

            <?php
                if($errorMessage){
                    $splitErrorMessage=explode("<seperator>",$errorMessage);
                    $timerToToast=0;
                    foreach($splitErrorMessage as $displayErrorMessage){
                        if($displayErrorMessage!=""){
                            echo 'window.setTimeout(function(){';
                            echo        "var \$toastContent = \$('<span>$displayErrorMessage</span>');";
                            echo        "Materialize.toast(\$toastContent, 7000);";
                            echo '},'.($timerToToast*1000).');';
                        }        
                        $timerToToast++;                
                    }
                    
                }
            ?>
        });
    </script>
    
    <script>
        $(document).ready(function () {
            $("#MenuAdminHomeLI").addClass("active");
            $("#SideMenuAdminHomeLI").addClass("active");

            $("#DeadlineStartDate").change(function () {
                var startDate = new Date($("#DeadlineStartDate").val());
                var endDate = new Date($("#DeadlineEndDate").val());
                if (startDate >= endDate) {
                    startDate.setDate(endDate.getDate() - 1);
                    pickerStart.set('select', startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate(), { format: 'yyyy-mm-dd' });

                    alert("Cannot set submission start date after deadline date!");
                }
                else {
                    $("#Key").removeAttr("disabled");
                    $("#KeyOuter").css("display", "block");
                    $('#UpdateDate').removeClass('disabled');
                    $('#UpdateDate').removeAttr('disabled');
                }

            });


            $("#DeadlineEndDate").change(function () {
                var startDate = new Date($("#DeadlineStartDate").val());
                var endDate = new Date($("#DeadlineEndDate").val());
                if (startDate >= endDate) {
                    endDate.setDate(startDate.getDate() + 1);
                    pickerEnd.set('select', endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate(), { format: 'yyyy-mm-dd' });

                    alert("Cannot set deadline date before submission start date!");
                }
                else {
                    $("#Key").removeAttr("disabled");
                    $("#KeyOuter").css("display", "block");
                    $('#UpdateDate').removeClass('disabled');
                    $('#UpdateDate').removeAttr('disabled');
                }

            });

        });
        
    </script>
    <?php
        if(strtotime($endDateFetched) > strtotime(date("Y-m-d"))){
    ?>

                <h3 style="margin: auto;">Choice filling deadline occurs in ...</h3>
                    <div id="deadline_countdown" style="text-align: center;margin: 2em auto;width: 620px"></div>
                <h1 id="deadlineOverMessage"></h1>
        
    <?php
        }
        else{
            echo '<h3 style="text-align:center;margin:auto;">Deadline has passed to fill choices...</h3><br/><br/>';
        }
    ?>
    

    <!--<h3>The following part will be shown after the deadline is over</h3>-->
    <div id="Sem1" class="chartsSlidable" style="margin: auto;width: 50%;display: none;">
        <div id="barchart_material_Sem1" class="z-depth-4" style="padding: 20px;border-radius: 10px;background-color: white;"></div>
        <br/><br/>
        <div id="piechart_Sem1" class="z-depth-4" style="padding: 10px;border-radius: 10px;background-color: white;"></div>
        <BR><BR>
    </div>

    <h4 style="margin: auto;text-align: center;">No of students who have filled their choices: <br/><span><?php echo $filledNoOfStuds." / ".$totalNoOfStuds; ?></span></h4>
    <br/>
    <br/>
    <div class="row">
        <form action="" method="post">
            <div id="Menu" style="text-align: center;" class="col s12 m5 offset-m1">                
                <a style="margin: 15px;width: 200px;" class="waves-effect waves-light btn-large" href="ModifySubjects.php">Modify Subjects</a>                      
                <a style="margin: 15px;width: 200px;" class="waves-effect waves-light btn-large" href="ImportData.php">Import Data</a>
                <a style="margin: 15px;width: 200px;" class="waves-effect waves-light btn-large" href="Allottment.php">Allottment Stats</a>
                <a style="margin: 15px;width: 200px;" class="waves-effect waves-light btn-large" href="ViewStudentsList.php">Students List</a>
                <a style="margin: 15px;width: 200px;" class="waves-effect waves-light btn-large" href="MailReminder.php">Remind Students</a>
            </div>
            <div class="col s12 m4 offset-m1">
                <div class="input-field col s12">
                    <label for="DeadlineStartDate" class="active">Submission Start Date</label>
                    <input id="DeadlineStartDate" name="DeadlineStartDate" type="date" class="datepicker">
                </div>
                <div class="input-field col s12">
                    <label for="DeadlineEndDate" class="active">Deadline Date</label>
                    <input id="DeadlineEndDate" name="DeadlineEndDate" type="date" class="datepicker">
                </div>
                <div class="input-field col" id="KeyOuter" style="display: none;">
					<i class="material-icons prefix">vpn_key</i>
					<input id="Key" type="password" name="Key" class="" required disabled/>
					<label for="pswrd">Key</label>
				</div>
                <br/><br/>
                <button class="modal-action modal-close waves-effect waves-green btn-large disabled" id="UpdateDate" type="submit" name="action" disabled>Update
                    <i class="material-icons">access_time</i>
                </button>
            </div>
            
        </form>
    </div>
    
</body>
</html>