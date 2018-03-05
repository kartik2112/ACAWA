<?php
    session_start();
    if(!isset($_SESSION['userid']) ){
        header("location: login.php");
    }
    if(isset($_SESSION['Alogin'])){
        header("location:AdminHome.php");
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
    else{
        $canFillChoices=FALSE;
    }

    $sqluser="select Roll_number,Semester,Name from User where u_name='".$_SESSION['userid']."'";
    $resultuser=mysqli_query($conn,$sqluser);
    if(mysqli_num_rows($resultuser)==1){
        $rowuser=mysqli_fetch_array($resultuser,MYSQLI_ASSOC);
    }
    else{
        die("Please report this. More than 1 user present with same username.");
    }

    if($_SERVER['REQUEST_METHOD']=="POST" && $canFillChoices==TRUE && isset($_POST['Choice1']) && isset($_POST['Choice2']) && isset($_POST['Choice3']) && isset($_POST['Choice4']) && isset($_POST['Choice5']) ){
        $sqlInsertChoices="insert into AC".$rowuser['Semester']."(Roll_number,pref_1,pref_2,pref_3,pref_4,pref_5,submittedOn) values('".$rowuser['Roll_number']."',".$_POST['Choice1'].",".$_POST['Choice2'].",".$_POST['Choice3'].",".$_POST['Choice4'].",".$_POST['Choice5'].",'".date("Y-m-d H:i:s")."')";
        if(!mysqli_query($conn,$sqlInsertChoices)){
            //echo $sqlInsertChoices."<br/>";
            //echo "Error while recording your choices!".mysqli_error($conn)."<br/>";
        }
    }
    else if($_SERVER['REQUEST_METHOD']=="POST" && $canFillChoices==FALSE && isset($_POST['Choice1']) && isset($_POST['Choice2']) && isset($_POST['Choice3']) && isset($_POST['Choice4']) && isset($_POST['Choice5']) ){
        //echo "Deadline Over!";
    }
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include("CommonFiles/CommonHead.php"); ?>
        <title>Fill form</title>
        <?php include "CommonFiles/CountdownToDeadline.php" ?>
        
        <script type="text/javascript">

            $(document).ready(function () {
                $('select').material_select();
                $('.collapsible').collapsible({
                  accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
                });
            });

            
        </script>

        <script type="text/javascript" src="JS/AuditCourseMakeChoicesJS.js"></script>


        <?php
            if(strtotime($startDateFetched) > strtotime(date("Y-m-d"))){
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
        ?>

    </head>
    <body>
        <?php include "CommonFiles/Menu.php"?>
        <script>
            $(document).ready(function () {
                $("#MenuFormLI").addClass("active");
                $("#SideMenuFormLI").addClass("active");
            });
        </script>
        
        <h3 style="text-align: center;display: block;">Welcome, <?php echo $rowuser['Name']; ?></h3>

        <?php
            $sqlCheckChoices="select * from AC".$rowuser['Semester']." where Roll_number='".$rowuser['Roll_number']."' and pref_1 IS NOT NULL and pref_2 IS NOT NULL and pref_3 IS NOT NULL and pref_4 IS NOT NULL and pref_5 IS NOT NULL";
            $resultCheckChoices=mysqli_query($conn,$sqlCheckChoices);
            if($canFillChoices==TRUE && mysqli_num_rows($resultCheckChoices)==0){
        ?>
        
                <form action="" method="post" class="col s6" style="margin: auto;width: 50%;">
                    <div class="input-field col s6">                
                        <select id="Choice1_select" class="choice_select" name="Choice1" onchange="RemoveSame(this);" >
                            <option class="noneSelected" value="noneSelected" selected disabled>Choose an audit course</option>
                            <?php
                                $sqlsubj="select * from Subject where Sem=".$rowuser['Semester']." order by Subject_Name";
                                $resultsubj=mysqli_query($conn,$sqlsubj);
                                if(mysqli_num_rows($resultsubj)!=0){
                                    while($rowsubj=mysqli_fetch_array($resultsubj,MYSQLI_ASSOC)){
                                        echo '<option data-icon="images/SubjectIcons/'.$rowsubj['imagelink'].'" name="'.$rowsubj['Subj_ID'].'" class="'.$rowsubj['Subj_ID'].' circle" value="'.$rowsubj['Subj_ID'].'" data-subj-name="'.$rowsubj['Subject_Name'].'">'.$rowsubj['Subject_Name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <label>1st Choice: &nbsp;</label>
                        <br/><br/>
                    </div>
            
            
                    <div class="input-field col s6">                
                        <select id="Choice2_select" class="choice_select" name="Choice2" onchange="RemoveSame(this);">
                            <option class="noneSelected" value="noneSelected" selected disabled>Choose an audit course</option>
                            <?php
                                $sqlsubj="select * from Subject where Sem=".$rowuser['Semester']." order by Subject_Name";
                                $resultsubj=mysqli_query($conn,$sqlsubj);
                                if(mysqli_num_rows($resultsubj)!=0){
                                    while($rowsubj=mysqli_fetch_array($resultsubj,MYSQLI_ASSOC)){
                                        echo '<option data-icon="images/SubjectIcons/'.$rowsubj['imagelink'].'" name="'.$rowsubj['Subj_ID'].'" class="'.$rowsubj['Subj_ID'].' circle" value="'.$rowsubj['Subj_ID'].'" data-subj-name="'.$rowsubj['Subject_Name'].'">'.$rowsubj['Subject_Name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <label for="Choice2_select">2nd Choice: &nbsp;</label>
                        <br/><br/>
                    </div>
            
            
                    <div class="input-field col s6">                
                        <select id="Choice3_select" class="choice_select" name="Choice3" onchange="RemoveSame(this);">
                            <option class="noneSelected" value="noneSelected" selected disabled>Choose an audit course</option>
                            <?php
                                $sqlsubj="select * from Subject where Sem=".$rowuser['Semester']." order by Subject_Name";
                                $resultsubj=mysqli_query($conn,$sqlsubj);
                                if(mysqli_num_rows($resultsubj)!=0){
                                    while($rowsubj=mysqli_fetch_array($resultsubj,MYSQLI_ASSOC)){
                                        echo '<option data-icon="images/SubjectIcons/'.$rowsubj['imagelink'].'" name="'.$rowsubj['Subj_ID'].'" class="'.$rowsubj['Subj_ID'].' circle" value="'.$rowsubj['Subj_ID'].'" data-subj-name="'.$rowsubj['Subject_Name'].'">'.$rowsubj['Subject_Name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <label for="">3rd Choice: &nbsp;</label>
                        <br/><br/>
                    </div>

            
                    <div class="input-field col s6">                
                        <select id="Choice4_select" class="choice_select" name="Choice4" onchange="RemoveSame(this);">
                            <option class="noneSelected" value="noneSelected" selected disabled>Choose an audit course</option>
                            <?php
                                $sqlsubj="select * from Subject where Sem=".$rowuser['Semester']." order by Subject_Name";
                                $resultsubj=mysqli_query($conn,$sqlsubj);
                                if(mysqli_num_rows($resultsubj)!=0){
                                    while($rowsubj=mysqli_fetch_array($resultsubj,MYSQLI_ASSOC)){
                                        echo '<option data-icon="images/SubjectIcons/'.$rowsubj['imagelink'].'" name="'.$rowsubj['Subj_ID'].'" class="'.$rowsubj['Subj_ID'].' circle" value="'.$rowsubj['Subj_ID'].'" data-subj-name="'.$rowsubj['Subject_Name'].'">'.$rowsubj['Subject_Name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <label for="Choice4_select">4th Choice: &nbsp;</label>
                        <br/><br/>
                    </div>

            
                    <div class="input-field col s6">                
                        <select id="Choice5_select" class="choice_select" name="Choice5" onchange="RemoveSame(this);">
                            <option class="noneSelected" value="noneSelected" selected disabled>Choose an audit course</option>
                            <?php
                                $sqlsubj="select * from Subject where Sem=".$rowuser['Semester']." order by Subject_Name";
                                $resultsubj=mysqli_query($conn,$sqlsubj);
                                if(mysqli_num_rows($resultsubj)!=0){
                                    while($rowsubj=mysqli_fetch_array($resultsubj,MYSQLI_ASSOC)){
                                        echo '<option data-icon="images/SubjectIcons/'.$rowsubj['imagelink'].'" name="'.$rowsubj['Subj_ID'].'" class="'.$rowsubj['Subj_ID'].' circle" value="'.$rowsubj['Subj_ID'].'" data-subj-name="'.$rowsubj['Subject_Name'].'">'.$rowsubj['Subject_Name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <label for="Choice5_select">5th Choice: &nbsp;</label>
                        <br/><br/>
                    </div>

            

                    <!-- Modal Trigger -->
                    <a class="waves-effect waves-light btn modal-trigger" href="#modal1" onclick="return checkAllChoices();">Submit Options
                        <i class="material-icons right">send</i>
                    </a>

                    <!-- Modal Structure -->
                    <div id="modal1" class="modal">
                        <div class="modal-content left-align">
                            <h4>You have selected the following audit courses:</h4>

                            <h5 id="Modal1Choice1"></h5>
                            <h5 id="Modal1Choice2"></h5>
                            <h5 id="Modal1Choice3"></h5>
                            <h5 id="Modal1Choice4"></h5>
                            <h5 id="Modal1Choice5"></h5>

                            <p>Click Agree to proceed or Disagree to change options</p>

                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close waves-effect waves-green btn-flat" type="submit" name="action">Agree
                                <i class="material-icons right">send</i>
                            </button>
                            <a href="#!" class=" modal-action modal-close waves-effect waves-red btn-flat">Disagree</a>
                        </div>
                    </div>
                    <br/><br/>
                    <a class="waves-effect waves-light btn" onclick="ResetAll()">Reset</a>
            

                </form><br/><br/>
        
        <?php
            }
            else if(mysqli_num_rows($resultCheckChoices)==1){
                    $rowChoicesProvd=mysqli_fetch_array($resultCheckChoices,MYSQLI_ASSOC);
                    echo '<div style="max-width:90%;margin:auto;"><ul class="collapsible popout accordionMyStyle" data-collapsible="accordion">';
                    
                    echo "<h3>You have given the following choices:</h2>";              
                    for($i=1;$i<=5;$i++){
                        $subjectSearch="select * from Subject where Subj_ID=".$rowChoicesProvd['pref_'.$i];
                        $resultSubjectSearch=mysqli_query($conn,$subjectSearch);
                        $rowSubjectSearch=mysqli_fetch_array($resultSubjectSearch,MYSQLI_ASSOC);
                        echo '<li><div class="collapsible-header"><i class="material-icons">filter_'.$i.'</i>'.$rowSubjectSearch['Subject_Name'].'</div>';
                        echo '<div class="collapsible-body">';
                        echo '<img src="images/SubjectIcons/'.$rowSubjectSearch['imagelink'].'" style="border-radius:50%;width:70px;height:70px;float:left;margin-left:20px;margin-top:20px;"/>';
                        echo '<div style="margin-left:100px;padding:10px;">';
                        echo    "Teacher: ".$rowSubjectSearch['Teacher']."<br/><hr>";
                        echo    "Capacity: ".$rowSubjectSearch['Capacity']."<br/><hr>";
                        echo    $rowSubjectSearch['File_Link']."&nbsp;&nbsp;&nbsp;";
                        if(isset($rowSubjectSearch['File_Link']) && $rowSubjectSearch['File_Link']!=NULL){
                            echo '<a class="waves-effect waves-light btn" target="_blank" href="UploadedDocs/Syllabus/'.$rowSubjectSearch['File_Link'].'">DOWNLOAD</a>';
                        }
                        else{
                            echo '<a class="waves-effect waves-light btn disabled">NO FILE UPLOADED</a>';
                        }  
                        echo '</div>';
                        echo '</div></li>';
                    }

                    echo '</ul></div>';
                    
        ?>

                    
        <?php
            }
            else if($choiceFillingOver==TRUE && mysqli_num_rows($resultCheckChoices)==0){
                echo '<h5>You have missed the deadline! Please report to the office!<h5>';
            }
            else if($choiceFillingStarted==FALSE){
        ?>
                    <h3>You can fill your choices in ...</h3>
                        <div id="deadline_countdown" style="text-align: center;margin: 2em auto;width: 620px"></div>
                    <h5 id="deadlineOverMessage" style="margin: auto;text-align: center;"></h5>


        <?php
            }
        ?>
    </body>
</html>
