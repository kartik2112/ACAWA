<?php
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin'])){
        header("location: login.php");
    }

    require "CommonFiles/connection.php";    
    require "CommonFiles/CommonConstants.php";
    require("CommonFiles/ImpDatesXMLData.php");
    

    if( strtotime(date("Y-m-d")) < strtotime($startDateFetched) ){
        //Phase 1
        $phase=1;

        $sqlUsersList="select Roll_number from User where Type='S'";
        $resultUsersList=mysqli_query($conn,$sqlUsersList);
        if(mysqli_num_rows($resultUsersList)!=0){
            $countNoOfReceivers['Phase1Temp1']=mysqli_num_rows($resultUsersList);
            while($rowUsersList=mysqli_fetch_array($resultUsersList,MYSQLI_ASSOC)){
                $RnosListToBeMailed['Phase1Temp1'][]=$rowUsersList['Roll_number'];
            }
        }
        
    }
    else if( (strtotime($startDateFetched) <= strtotime(date("Y-m-d")) && ( strtotime(date("Y-m-d")) < strtotime($endDateFetched) ) ) ){
        //Phase 2
        $phase=2;

        $sqlUsersList="select Roll_number,Semester from User where Type='S'";
        $resultUsersList=mysqli_query($conn,$sqlUsersList);
        if(mysqli_num_rows($resultUsersList)!=0){
            $countNoOfReceivers['Phase2Temp1']=mysqli_num_rows($resultUsersList);
            $countNoOfReceivers['Phase2Temp2']=0;
            while($rowUsersList=mysqli_fetch_array($resultUsersList,MYSQLI_ASSOC)){
                $RnosListToBeMailed['Phase2Temp1'][]=$rowUsersList['Roll_number'];

                $sqlCheckIfSubmitted="select count(*) from AC".$rowUsersList['Semester']." where Roll_number=".$rowUsersList['Roll_number'];
                $resultCheckIfSubmitted=mysqli_query($conn,$sqlCheckIfSubmitted);
                $rowCheckIfSubmitted=mysqli_fetch_array($resultCheckIfSubmitted,MYSQLI_ASSOC);
                if($rowCheckIfSubmitted['count(*)']==0){
                    $RnosListToBeMailed['Phase2Temp2'][]=$rowUsersList['Roll_number'];
                    $countNoOfReceivers['Phase2Temp2']++;
                }
            }
        }

    }
    else if( strtotime(date("Y-m-d")) >= strtotime($endDateFetched) && $sortingPerformedStatus=="TRUE"){
        //Phase 3 Allottment done
        $phase=3;

        $sqlUsersList="select Roll_number,Semester from User where Type='S'";
        $resultUsersList=mysqli_query($conn,$sqlUsersList);
        if(mysqli_num_rows($resultUsersList)!=0){
            $countNoOfReceivers['Phase3Temp1']=mysqli_num_rows($resultUsersList);
            $countNoOfReceivers['Phase3Temp2']=0;
            $countNoOfReceivers['Phase3Temp3']=0;
            while($rowUsersList=mysqli_fetch_array($resultUsersList,MYSQLI_ASSOC)){
                $RnosListToBeMailed['Phase3Temp1'][]=$rowUsersList['Roll_number'];

                $sqlCheckIfSubmitted="select pref_1,allottedChoice from AC".$rowUsersList['Semester']." where Roll_number=".$rowUsersList['Roll_number'];
                $resultCheckIfSubmitted=mysqli_query($conn,$sqlCheckIfSubmitted);
                if(mysqli_num_rows($resultCheckIfSubmitted)!=0){
                    $rowCheckIfSubmitted=mysqli_fetch_array($resultCheckIfSubmitted,MYSQLI_ASSOC);

                    if($rowCheckIfSubmitted['allottedChoice']==NULL){
                        //Not allotted any choice
                        $RnosListToBeMailed['Phase3Temp3'][]=$rowUsersList['Roll_number'];
                        $countNoOfReceivers['Phase3Temp3']++;
                    }
                }
                else{
                    //Not submitted his choices
                    $RnosListToBeMailed['Phase3Temp2'][]=$rowUsersList['Roll_number'];
                    $countNoOfReceivers['Phase3Temp2']++;
                }
            }
        }
    }
    else if( strtotime(date("Y-m-d")) >= strtotime($endDateFetched) && $sortingPerformedStatus=="FALSE"){
        //Phase 3 but allottment not done yet
        $phase=3;
        
    }


?>

<!DOCTYPE html> <!-- for HTML 5 -->
<html>
<head>
    <?php include("CommonFiles/CommonHead.php"); ?>
	<title>Mail Reminder</title>
	 
    <script src="JS/MailReminder.js"></script>
       
    <style>
        .collapsible-body{
            padding: 10px;
        }
        .collapsible-body p{
            padding: 10px!important;
        }
        .MailContent ul{
            list-style-position: inside;
        }
        
        .MailContent ul li{
            list-style-type: circle;
        }
        
        .MailContent{
            margin-left: 10px;
        }

    </style>     
</head>

<body style = "">

    <?php include "CommonFiles/Menu.php"?>
    
    
    <div class="container">
		<?php
            if($phase==1){
                $insertDateInMail=date("F jS, Y",strtotime($startDateFetched));
                require("CommonFiles/MailTemplates.php");
        ?>
            <script>
                Phase1Temp1RNosList=<?php echo json_encode($RnosListToBeMailed['Phase1Temp1']); ?>;
                Phase1Temp1totalusers=<?php echo $countNoOfReceivers['Phase1Temp1']; ?>;
            </script>
            <ul class="collapsible popout accordionMyStyle" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header"><i class="material-icons">filter_drama</i>Send Invitation</div>
                    <div class="collapsible-body">
                        
                        <div class="row">
                            <form class="col s12">
                                <fieldset>
                                    <legend>Format of Mail to be sent</legend>
                                    <div class="row">
                                        <p>Dear 'Student Name',</p>
                                        <div style="text-align: left;" class="MailContent">
                                            <?php
                                                echo $templateBody['Phase1Temp1'];
                                            ?>
                                        </div>
                                        
                                        <div class="input-field col s6">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea id="PSPhase1Temp1" class="materialize-textarea" name="PostScript"></textarea>
                                            <label for="PSPhase1Temp1">Additional Message to be added in mail (Post Script)</label>
                                        </div>
                                    </div>
                                    <button class="btn waves-effect waves-light SendMailButton" type="button" name="action" value="Phase1Temp1" style="margin: auto" <?php if($countNoOfReceivers['Phase1Temp1']==0){ echo 'disabled'; } ?> >Send <?php echo $countNoOfReceivers['Phase1Temp1'] ?> students
				                        <i class="material-icons right">send</i>
				                    </button>
                                </fieldset>
                            </form>
                            <div id="MailProgressMainDivPhase1Temp1" style="display: none;margin: auto;width: 95%;">
                                <h6>Mail Success Progress:<b><span id="ProgressBarStatusValuePhase1Temp1" style="line-height: 30px">0%</span></b></h6><br/>
                                <div id="ProgressBarDivPhase1Temp1" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="ProgressBarPhase1Temp1" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                                <div id="MailSendStartPhase1Temp1" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>
                                
                                <h5 id="MailsHandledPhase1Temp1" style="">Mails Sent Successfully: <span id="MailsSentYetValuesPhase1Temp1">0/<?php echo $countNoOfReceivers['Phase1Temp1'] ?></span></h5>
                                <h5 style="">Users that could not be sent mails: <span id="MailsNotSentValuesPhase1Temp1">0/<?php echo $countNoOfReceivers['Phase1Temp1'] ?></span></h5>
                                <h5 style="">Users already reminded: <span id="UsersAlreadyRemindedPhase1Temp1">0/<?php echo $countNoOfReceivers['Phase1Temp1'] ?></span></h5>
                                <h5 style="">Users Handled: <span id="UsersHandledYetValuesPhase1Temp1">0/<?php echo $countNoOfReceivers['Phase1Temp1'] ?></span></h5>
                                <div id="MailSendHappeningPhase1Temp1" style="margin: auto;text-align: center;"></div>                
                            </div>
                        </div>
                    </div>
                </li>
            </ul>


        <?php
            }
            else if($phase==2){
                $insertDateInMail=date("F jS, Y",strtotime($endDateFetched));
                require("CommonFiles/MailTemplates.php");
        ?>
            <script>
                Phase2Temp1RNosList=<?php echo json_encode($RnosListToBeMailed['Phase2Temp1']); ?>;
                Phase2Temp1totalusers=<?php echo $countNoOfReceivers['Phase2Temp1']; ?>;
                Phase2Temp2RNosList=<?php echo json_encode($RnosListToBeMailed['Phase2Temp2']); ?>;
                Phase2Temp2totalusers=<?php echo $countNoOfReceivers['Phase2Temp2']; ?>;
            </script>
            <ul class="collapsible popout accordionMyStyle" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header"><i class="material-icons">filter_drama</i>Send link to choice filling form</div>
                    <div class="collapsible-body">
                        
                        <div class="row">
                            <form class="col s12">
                                <fieldset>
                                    <legend>Format of Mail to be sent</legend>
                                    <div class="row">
                                        <p>Dear 'Student Name',</p>
                                        <div style="text-align: left;" class="MailContent">
                                            <?php
                                                echo $templateBody['Phase2Temp1'];
                                            ?>
                                        </div>
                                        <div class="input-field col s6">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea id="PSPhase2Temp1" class="materialize-textarea" name="PostScript"></textarea>
                                            <label for="PSPhase2Temp1">Additional Message to be added in mail (Post Script)</label>
                                        </div>
                                    </div>
                                    <button class="btn waves-effect waves-light SendMailButton" type="button" name="action" value="Phase2Temp1" style="margin: auto" <?php if($countNoOfReceivers['Phase2Temp1']==0){ echo 'disabled'; } ?> >Send <?php echo $countNoOfReceivers['Phase2Temp1'] ?> students
				                        <i class="material-icons right">send</i>
				                    </button>
                                </fieldset>
                            </form>
                            <div id="MailProgressMainDivPhase2Temp1" style="display: none;margin: auto;width: 95%;">
                                <h6>Mail Success Progress:<b><span id="ProgressBarStatusValuePhase2Temp1" style="line-height: 30px">0%</span></b></h6><br/>
                                <div id="ProgressBarDivPhase2Temp1" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="ProgressBarPhase2Temp1" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                                <div id="MailSendStartPhase2Temp1" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>
                                
                                <h5 id="MailsHandledPhase2Temp1" style="">Mails Sent Successfully: <span id="MailsSentYetValuesPhase2Temp1">0/<?php echo $countNoOfReceivers['Phase2Temp1'] ?></span></h5>
                                <h5 style="">Users that could not be sent mails: <span id="MailsNotSentValuesPhase2Temp1">0/<?php echo $countNoOfReceivers['Phase2Temp1'] ?></span></h5>
                                <h5 style="">Users already reminded: <span id="UsersAlreadyRemindedPhase2Temp1">0/<?php echo $countNoOfReceivers['Phase2Temp1'] ?></span></h5>
                                <h5 style="">Users Handled: <span id="UsersHandledYetValuesPhase2Temp1">0/<?php echo $countNoOfReceivers['Phase2Temp1'] ?></span></h5>
                                <div id="MailSendHappeningPhase2Temp1" style="margin: auto;text-align: center;"></div>                
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">place</i>Reminder to fill forms</div>
                    <div class="collapsible-body">
                        
                        <div class="row">
                            <form class="col s12">
                                <fieldset>
                                    <legend>Format of Mail to be sent</legend>
                                    <div class="row">
                                        <p>Dear 'Student Name',</p>
                                        <div style="text-align: left;" class="MailContent">
                                            <?php
                                                echo $templateBody['Phase2Temp2'];
                                            ?>
                                        </div>
                                        <div class="input-field col s6">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea id="PSPhase2Temp2" class="materialize-textarea" name="PostScript"></textarea>
                                            <label for="PSPhase2Temp2">Additional Message to be added in mail (Post Script)</label>
                                        </div>
                                    </div>
                                    <button class="btn waves-effect waves-light SendMailButton" type="button" name="action" value="Phase2Temp2" style="margin: auto" <?php if($countNoOfReceivers['Phase2Temp2']==0){ echo 'disabled'; } ?> >Send <?php echo $countNoOfReceivers['Phase2Temp2'] ?> students
				                        <i class="material-icons right">send</i>
				                    </button>
                                </fieldset>
                            </form>
                            <div id="MailProgressMainDivPhase2Temp2" style="display: none;margin: auto;width: 95%;">
                                <h6>Mail Success Progress:<b><span id="ProgressBarStatusValuePhase2Temp2" style="line-height: 30px">0%</span></b></h6><br/>
                                <div id="ProgressBarDivPhase2Temp2" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="ProgressBarPhase2Temp2" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                                <div id="MailSendStartPhase2Temp2" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>
                                
                                <h5 id="MailsHandledPhase2Temp2" style="">Mails Sent Successfully: <span id="MailsSentYetValuesPhase2Temp2">0/<?php echo $countNoOfReceivers['Phase2Temp2'] ?></span></h5>
                                <h5 style="">Users that could not be sent mails: <span id="MailsNotSentValuesPhase2Temp2">0/<?php echo $countNoOfReceivers['Phase2Temp2'] ?></span></h5>
                                <h5 style="">Users already reminded: <span id="UsersAlreadyRemindedPhase2Temp2">0/<?php echo $countNoOfReceivers['Phase2Temp2'] ?></span></h5>
                                <h5 style="">Users Handled: <span id="UsersHandledYetValuesPhase2Temp2">0/<?php echo $countNoOfReceivers['Phase2Temp2'] ?></span></h5>
                                <div id="MailSendHappeningPhase2Temp2" style="margin: auto;text-align: center;"></div>                
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

        <?php
            }
            else if($phase==3 && $sortingPerformedStatus=="TRUE"){
                require("CommonFiles/MailTemplates.php");
        ?>
            <script>
                Phase3Temp1RNosList=<?php echo json_encode($RnosListToBeMailed['Phase3Temp1']); ?>;
                Phase3Temp1totalusers=<?php echo $countNoOfReceivers['Phase3Temp1']; ?>;
                Phase3Temp2RNosList=<?php echo json_encode($RnosListToBeMailed['Phase3Temp2']); ?>;
                Phase3Temp2totalusers=<?php echo $countNoOfReceivers['Phase3Temp2']; ?>;
                Phase3Temp3RNosList=<?php echo json_encode($RnosListToBeMailed['Phase3Temp3']); ?>;
                Phase3Temp3totalusers=<?php echo $countNoOfReceivers['Phase3Temp3']; ?>;
            </script>
            <ul class="collapsible popout accordionMyStyle" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header"><i class="material-icons">filter_drama</i>Link to view Results</div>
                    <div class="collapsible-body">                        
                        <div class="row">
                            <form class="col s12">
                                <fieldset>
                                    <legend>Format of Mail to be sent</legend>
                                    <div class="row">
                                        <p>Dear 'Student Name',</p>
                                        <div style="text-align: left;" class="MailContent">
                                            <?php
                                                echo $templateBody['Phase3Temp1'];
                                            ?>
                                        </div>
                                        <div class="input-field col s6">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea id="PSPhase3Temp1" class="materialize-textarea" name="PostScript"></textarea>
                                            <label for="PSPhase3Temp1">Additional Message to be added in mail (Post Script)</label>
                                        </div>
                                    </div>
                                    <button class="btn waves-effect waves-light SendMailButton" type="button" name="action" value="Phase3Temp1" style="margin: auto" <?php if($countNoOfReceivers['Phase3Temp1']==0){ echo 'disabled'; } ?> >Send <?php echo $countNoOfReceivers['Phase3Temp1'] ?> students
				                        <i class="material-icons right">send</i>
				                    </button>
                                </fieldset>
                            </form>
                            <div id="MailProgressMainDivPhase3Temp1" style="display: none;margin: auto;width: 95%;">
                                <h6>Mail Success Progress:<b><span id="ProgressBarStatusValuePhase3Temp1" style="line-height: 30px">0%</span></b></h6><br/>
                                <div id="ProgressBarDivPhase3Temp1" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="ProgressBarPhase3Temp1" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                                <div id="MailSendStartPhase3Temp1" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>
                                
                                <h5 id="MailsHandledPhase3Temp1" style="">Mails Sent Successfully: <span id="MailsSentYetValuesPhase3Temp1">0/<?php echo $countNoOfReceivers['Phase3Temp1'] ?></span></h5>
                                <h5 style="">Users that could not be sent mails: <span id="MailsNotSentValuesPhase3Temp1">0/<?php echo $countNoOfReceivers['Phase3Temp1'] ?></span></h5>
                                <h5 style="">Users already reminded: <span id="UsersAlreadyRemindedPhase3Temp1">0/<?php echo $countNoOfReceivers['Phase3Temp1'] ?></span></h5>
                                <h5 style="">Users Handled: <span id="UsersHandledYetValuesPhase3Temp1">0/<?php echo $countNoOfReceivers['Phase3Temp1'] ?></span></h5>
                                <div id="MailSendHappeningPhase3Temp1" style="margin: auto;text-align: center;"></div>                
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">place</i>Mail to students who have not submitted their choices</div>
                    <div class="collapsible-body">
                        
                        <div class="row">
                            <form class="col s12">
                                <fieldset>
                                    <legend>Format of Mail to be sent</legend>
                                    <div class="row">
                                        <p>Dear 'Student Name',</p>
                                        <div style="text-align: left;" class="MailContent">
                                            <?php
                                                echo $templateBody['Phase3Temp2'];
                                            ?>
                                        </div>
                                        <div class="input-field col s6">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea id="PSPhase3Temp2" class="materialize-textarea" name="PostScript"></textarea>
                                            <label for="PSPhase3Temp2">Additional Message to be added in mail (Post Script)</label>
                                        </div>
                                    </div>
                                    <button class="btn waves-effect waves-light SendMailButton" type="button" name="action" value="Phase3Temp2" style="margin: auto" <?php if($countNoOfReceivers['Phase3Temp2']==0){ echo 'disabled'; } ?> >Send <?php echo $countNoOfReceivers['Phase3Temp2'] ?> students
				                        <i class="material-icons right">send</i>
				                    </button>
                                 </fieldset>
                            </form>
                            <div id="MailProgressMainDivPhase3Temp2" style="display: none;margin: auto;width: 95%;">
                                <h6>Mail Success Progress:<b><span id="ProgressBarStatusValuePhase3Temp2" style="line-height: 30px">0%</span></b></h6><br/>
                                <div id="ProgressBarDivPhase3Temp2" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="ProgressBarPhase3Temp2" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                                <div id="MailSendStartPhase3Temp2" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>
                                
                                <h5 id="MailsHandledPhase3Temp2" style="">Mails Sent Successfully: <span id="MailsSentYetValuesPhase3Temp2">0/<?php echo $countNoOfReceivers['Phase3Temp2'] ?></span></h5>
                                <h5 style="">Users that could not be sent mails: <span id="MailsNotSentValuesPhase3Temp2">0/<?php echo $countNoOfReceivers['Phase3Temp2'] ?></span></h5>
                                <h5 style="">Users already reminded: <span id="UsersAlreadyRemindedPhase3Temp2">0/<?php echo $countNoOfReceivers['Phase3Temp2'] ?></span></h5>
                                <h5 style="">Users Handled: <span id="UsersHandledYetValuesPhase3Temp2">0/<?php echo $countNoOfReceivers['Phase3Temp2'] ?></span></h5>
                                <div id="MailSendHappeningPhase3Temp2" style="margin: auto;text-align: center;"></div>                
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">whatshot</i>Mail to students not allotted any audit courses</div>
                    <div class="collapsible-body">
                        
                        <div class="row">
                            <form class="col s12">
                                <fieldset>
                                    <legend>Format of Mail to be sent</legend>
                                    <div class="row">
                                        <p>Dear 'Student Name',</p>
                                        <div style="text-align: left;" class="MailContent">
                                            <?php
                                                echo $templateBody['Phase3Temp3'];
                                            ?>
                                        </div>
                                        <div class="input-field col s6">
                                            <i class="material-icons prefix">mode_edit</i>
                                            <textarea id="PSPhase3Temp3" class="materialize-textarea" name="PostScript"></textarea>
                                            <label for="PSPhase3Temp3">Additional Message to be added in mail (Post Script)</label>
                                        </div>
                                    </div>
                                    <button class="btn waves-effect waves-light SendMailButton" type="button" name="action" value="Phase3Temp3" style="margin: auto" <?php if($countNoOfReceivers['Phase3Temp3']==0){ echo 'disabled'; } ?> >Send <?php echo $countNoOfReceivers['Phase3Temp3'] ?> students
				                        <i class="material-icons right">send</i>
				                    </button>
                                </fieldset>
                            </form>
                            <div id="MailProgressMainDivPhase3Temp3" style="display: none;margin: auto;width: 95%;">
                                <h6>Mail Success Progress:<b><span id="ProgressBarStatusValuePhase3Temp3" style="line-height: 30px">0%</span></b></h6><br/>
                                <div id="ProgressBarDivPhase3Temp3" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="ProgressBarPhase3Temp3" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                                <div id="MailSendStartPhase3Temp3" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>
                                <h5 style="display: none;">All users handled!</h5>
                                <h5 id="MailsHandledPhase3Temp3" style="">Mails Sent Successfully: <span id="MailsSentYetValuesPhase3Temp3">0/<?php echo $countNoOfReceivers['Phase3Temp3'] ?></span></h5>
                                <h5 style="">Users that could not be sent mails: <span id="MailsNotSentValuesPhase3Temp3">0/<?php echo $countNoOfReceivers['Phase3Temp3'] ?></span></h5>
                                <h5 style="">Users already reminded: <span id="UsersAlreadyRemindedPhase3Temp3">0/<?php echo $countNoOfReceivers['Phase3Temp3'] ?></span></h5>
                                <h5 style="">Users Handled: <span id="UsersHandledYetValuesPhase3Temp3">0/<?php echo $countNoOfReceivers['Phase3Temp3'] ?></span></h5>
                                <div id="MailSendHappeningPhase3Temp3" style="margin: auto;text-align: center;"></div>                
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

        <?php
            }
            else if($phase==3 && $sortingPerformedStatus=="FALSE"){
                echo "<h5>Audit Courses not yet allotted to any students.</h5>";
            }
        ?>
	</div>
    
</body>
</html>