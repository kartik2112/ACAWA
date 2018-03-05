<?php
    session_start();
    if(!isset($_SESSION['userid']) ){
        header("location: login.php");
    }
    if(isset($_SESSION['Alogin'])){
        header("location: AdminHome.php");
    }
    require "CommonFiles/connection.php";
    require "CommonFiles/CommonConstants.php";
    require("CommonFiles/ImpDatesXMLData.php");

    if(strtotime($startDateFetched)<=strtotime(date("Y-m-d"))){
        $choiceFillingStarted=TRUE;
    }
    else{
        $choiceFillingStarted=FALSE;
    }

    if(strtotime($endDateFetched)<=strtotime(date("Y-m-d"))){
        $choiceFillingOver=TRUE;
    }
    else{
        $choiceFillingOver=FALSE;
    }

    if($choiceFillingStarted==TRUE && $choiceFillingOver==FALSE){
        $canFillChoices=TRUE;
    }

    $sql = " SELECT * FROM User WHERE u_name = '".$_SESSION['userid']."'";    
    $result = mysqli_fetch_array(mysqli_query($conn,$sql));

    $sqlCheckChoicesSubmittedIndex="select * from AC".$result['Semester']." where Roll_number=".$result['Roll_number'];
    $resultCheckChoicesSubmittedIndex=mysqli_query($conn,$sqlCheckChoicesSubmittedIndex);

    if(mysqli_num_rows($resultCheckChoicesSubmittedIndex)==0){
        $notSubmittedIndex="NO";
    }
    else{
        $notSubmittedIndex="YES";
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>User Home</title>
        <?php include("CommonFiles/CommonHead.php"); ?>        
        <style>
            .parallax-container{
                height: 400px;
            }
        </style>
        <?php
            if($choiceFillingStarted==FALSE){
        ?>
            
                <!--Flip Clock Section-->
                <link rel="stylesheet" href="FlipClock-master/compiled/flipclock.css">
	            <script src="FlipClock-master/compiled/flipclock.js"></script>	
                <script type="text/javascript">
                    var clock;
                    $(document).ready(function () {
                        // Grab the current date
                        var currentDate = new Date("<?php echo date('Y/m/d H:i:s'); ?>");
                        var futureDate = new Date("<?php echo $startDateFetched; ?>" );
                        futureDate.setHours(0,0,0,0); 
                        // Calculate the difference in seconds between the future and current date
                        var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
                        // Instantiate a coutdown FlipClock
                        clock = $('#submission_start_countdown').FlipClock(diff, {
                            clockFace: 'DailyCounter',
                            countdown: true,
                            callbacks: {
                                stop: function () {
                                    window.location.reload(true);                        
                                }
                            }
                        });
                    });
	            </script>

        <?php
            }
            else if($canFillChoices==TRUE){
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
                        clock = $('#deadline_start_countdown').FlipClock(diff, {
                            clockFace: 'DailyCounter',
                            countdown: true,
                            callbacks: {
                                stop: function () {
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

<body style="">
    
        <?php include "CommonFiles/Menu.php"; ?>
        <script>
            $(document).ready(function () {
                $('select').material_select();
                $("#MenuHomeLI").addClass("active");
                $("#SideMenuHomeLI").addClass("active");
            });
        </script>
        <?php
            //raj ka code


            echo '<h4 style="text-align: center;display: block;margin:auto;">Welcome, '.$result['Name'].'</h4>';
        
            //kartik put this part in admin home
            /*
            if(isset($_SESSION['Alogin'])
            {
                $sql = " SELECT * FROM User WHERE u_name = '".$_SESSION['Alogin']."'";
    
                $result = mysqli_fetch_array(mysqli_query($conn,$sql));
                echo '<h3 style="text-align: left;display: inline-block;">Welcome,'.$result['Name'].'</h3>';
            }
            */
        ?>

        

        <div class="row">
            <div class="col s12 m8 offset-m2 l6 offset-l3" style="">
                <div class="card z-depth-2" style="text-align: center;margin: auto;padding: 7px;">
                    <?php
                        if($choiceFillingStarted==FALSE){
                    ?>
                        <h5>You can fill your choices in ...</h5>
                        <div id="submission_start_countdown" style="text-align: center;width: 620px;margin: 2em auto;"></div>
                    <?php
                        }
                        else if($canFillChoices==TRUE){                            
                    ?>
                        <h5>Deadline of filling form occurs in ...</h5>
                        <div id="deadline_start_countdown" style="text-align: center;margin: 2em auto;width: 620px;"></div>
                    <?php
                        }
                        else{
                            echo '<h5>Check the results tab for more information</h5>';
                        }
                    ?>
                        <h5><b>Form filling start date: </b><?php echo date("F jS, Y",strtotime($startDateFetched)); ?></h5>
                        <h5><b>Form filling deadline date: </b><?php echo date("F jS, Y",strtotime($endDateFetched)); ?></h5>
                </div>
            </div>
            <div class="col s12 m8 offset-m2 l6 offset-l3" style="">
                <div class="card z-depth-2" style="min-height: 170px;padding: 7px;">
                    <img src="images/UserProfilePix/avatar.png" style="border-radius:50%;width:100px;height:100px;float:left;margin-left:20px;margin-top:30px;"/>
                    <div class="card-content" style="margin-left: 150px;">
                        <h5>My Details</h5>
                        <p>                            
                            <b>SEM :</b> <?php echo $result['Semester']; ?>
                            <BR><b>BRANCH : </b><?php echo $result['Branch']; ?>
                            <BR><b>ROLL NUMBER :</b> <?php echo $result['Roll_number']; ?>
                            <br><b>Form Filled?</b> <?php echo $notSubmittedIndex; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>
