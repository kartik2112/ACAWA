<?php
//Define and assign values to the following variables before including this php file
//$receiverNameInMail  - Name of receiver of mail
//$insertDateInMail - Date to be inserted in corresponding template
//$PostScriptInMail - Any additional comments put by admin in mail before sending
$receiverNameInMail = 'Test';
$PostScriptInMail = 'Test'; 


$templateBody['Phase1Temp1']=       '<p>The link is now open to check out the audit course details for your semester.</p>'.
                                    '<p>Login with your SVVNetID and password as \'kjsce\' if you haven\'t changed it yet. If you haven\'t, please change it as soon as possible.</p>'.
                                    '<p><b>Use the following link to check the syllabus and other details of the audit courses offered in your semester:</b></p>'.
                                    '<p><span style=""><a href="http://'.$_SERVER['HTTP_HOST'].'/ACAWA/CourseDetails.php" target="_blank"><span>Click here</span></a></span></p>'.
                                    '<p>The form will be available on '.$insertDateInMail.'</p>'.
                                    '<p>Remember the following while filling the form:</p>'.
                                    '<ul style="list-style-type: circle;">'.
                                        '<li>The allotment will be done on first-come-first-serve basis.</li>'.
                                        '<li>The allotment will be done by checking the availability of choices in the order as provided by you.</li>'.
                                        '<li>The form can be filled only after the above choices submission start date occurs.</li>'.
                                        '<li>The form has to be filled before the deadline is over. The date of deadline will be conveyed after the form becomes available to be filled. The students that have missed filling this form will have to meet the conveyed person-in-charge when asked to.</li>'.
                                        '<li>The students that have not been allotted any audit course will have to meet the conveyed person-in-charge when asked to.</li>'.
                                    '</ul>'.
                                    '<p>Have a nice day ahead.</p>';

$template['Phase1Temp1']='<!DOCTYPE html>'.
                          '<html lang="en" xmlns="http://www.w3.org/1999/xhtml">'.
                          '<head>'.                                    
                          '</head>'.
                          '<body>'.
                                '<div style="margin: auto;">'.
                                    '<p>Dear '.$receiverNameInMail.',</p>'.
                                    $templateBody['Phase1Temp1'].
                                    '<p>PS:&nbsp;'.$PostScriptInMail.'</p>'.
                                '</div>'.
                            '</body>'.
                            '</html>';

$subject['Phase1Temp1']="Audit Course Syllabus";




$templateBody['Phase2Temp1']=       '<p>The link is now open to fill out your choices for your desired audit course.</p>'.
                                    '<p>Login with your SVVNetID and password as \'kjsce\' if you haven\'t changed it yet. If you haven\'t, please change it as soon as possible.</p>'.
                                    '<p><b>Use the following link to fill out the choices:</b></p>'.
                                    '<p><span style=""><a href="http://'.$_SERVER['HTTP_HOST'].'/ACAWA/AuditCourseMakeChoices.php" target="_blank"><span>Click here</span></a></span></p>'.
                                    '<p>The deadline for filling this form is '.$insertDateInMail.'</p>'.
                                    '<p>Remember the following while filling the form:</p>'.
                                    '<ul style="list-style-type: circle;">'.
                                        '<li>The allotment will be done on first-come-first-serve basis.</li>'.
                                        '<li>The allotment will be done by checking the availability of choices in the order as provided by you.</li>'.                                        
                                        '<li>The form has to be filled before the deadline is over. The date of deadline will be conveyed after the form becomes available to be filled.The students that have missed filling this form will have to meet the conveyed person-in-charge when asked to.</li>'.
                                        '<li>The students that have not been allotted any audit course will have to meet the conveyed person-in-charge when asked to.</li>'.
                                    '</ul>'.
                                    '<p>Have a nice day ahead.</p>';


$template['Phase2Temp1']='<!DOCTYPE html>'.
                          '<html lang="en" xmlns="http://www.w3.org/1999/xhtml">'.
                          '<head>'.                                    
                          '</head>'.
                          '<body>'.
                                '<div style="margin: auto;">'.
                                    '<p>Dear '.$receiverNameInMail.',</p>'.
                                    $templateBody['Phase2Temp1'].
                                    '<p>PS:&nbsp;'.$PostScriptInMail.'</p>'.
                                '</div>'.
                            '</body>'.
                            '</html>';

$subject['Phase2Temp1']="Audit Course Choice Filling Started";




$templateBody['Phase2Temp2']=       '<p><b>Please do not forget to fill out your choices for your desired audit course. The link is now open to fill out your choices for your desired audit course.</b></p>'.
                                    '<p>Login with your SVVNetID and password as \'kjsce\' if you haven\'t changed it yet. If you haven\'t, please change it as soon as possible.</p>'.
                                    '<p><b>Use the following link to fill out the choices:</b></p>'.
                                    '<p><span style=""><a href="http://'.$_SERVER['HTTP_HOST'].'/ACAWA/AuditCourseMakeChoices.php" target="_blank"><span>Click here</span></a></span></p>'.
                                    '<p>The deadline for filling this form is '.$insertDateInMail.'</p>'.
                                    '<p>Remember the following while filling the form:</p>'.
                                    '<ul style="list-style-type: circle;">'.
                                        '<li>The allotment will be done on first-come-first-serve basis.</li>'.
                                        '<li>The allotment will be done by checking the availability of choices in the order as provided by you.</li>'.                                        
                                        '<li>The form has to be filled before the deadline is over. The date of deadline will be conveyed after the form becomes available to be filled. The students that have missed filling this form will have to meet the conveyed person-in-charge when asked to.</li>'.
                                        '<li>The students that have not been allotted any audit course will have to meet the conveyed person-in-charge when asked to.</li>'.
                                    '</ul>'.
                                    '<p>Have a nice day ahead.</p>';


$template['Phase2Temp2']='<!DOCTYPE html>'.
                          '<html lang="en" xmlns="http://www.w3.org/1999/xhtml">'.
                          '<head>'.                                    
                          '</head>'.
                          '<body>'.
                                '<div style="margin: auto;">'.
                                    '<p>Dear '.$receiverNameInMail.',</p>'.
                                    $templateBody['Phase2Temp2'].
                                    '<p>PS:&nbsp;'.$PostScriptInMail.'</p>'.
                                '</div>'.
                            '</body>'.
                            '</html>';

$subject['Phase2Temp2']="Reminder to fill Audit Course Choices";




$templateBody['Phase3Temp1']=       '<p><b>The results for the allotment of audit courses have been declared.</b></p>'.
                                    '<p>Login with your SVVNetID and password as \'kjsce\' if you haven\'t changed it yet. If you haven\'t, please change it as soon as possible.</p>'.
                                    '<p><b>Use the following link to check out which audit courses you and your friends have been allotted:</b></p>'.
                                    '<p><span style=""><a href="http://'.$_SERVER['HTTP_HOST'].'/ACAWA/Results.php" target="_blank"><span>Click here</span></a></span></p>'.
                                    '<p>If you have not yet filled out your choices or have not been allotted any audit course, meet the requested person-in-charge for further details.</p>'.
                                    '<p>Have a nice day ahead.</p>';


$template['Phase3Temp1']='<!DOCTYPE html>'.
                          '<html lang="en" xmlns="http://www.w3.org/1999/xhtml">'.
                          '<head>'.                                    
                          '</head>'.
                          '<body>'.
                                '<div style="margin: auto;">'.
                                    '<p>Dear '.$receiverNameInMail.',</p>'.
                                    $templateBody['Phase3Temp1'].
                                    '<p>PS:&nbsp;'.$PostScriptInMail.'</p>'.
                                '</div>'.
                            '</body>'.
                            '</html>';

$subject['Phase3Temp1']="Audit Course Allottment Results Arrived";




$templateBody['Phase3Temp2']=       '<p><b>You have failed to submit your choices before the deadine was over!</b></p>'.
                                    '<p>The results for the allotment of audit courses have been declared.</p>'.
                                    '<p>Login with your SVVNetID and password as \'kjsce\' if you haven\'t changed it yet. If you haven\'t, please change it as soon as possible.</p>'.
                                    '<p><b>Use the following link to check out which audit courses you and your friends have been allotted:</b></p>'.
                                    '<p><span style=""><a href="http://'.$_SERVER['HTTP_HOST'].'/ACAWA/Results.php" target="_blank"><span>Click here</span></a></span></p>'.
                                    '<p><b>Meet the requested person-in-charge for further details.</b></p>'.
                                    '<p>Have a nice day ahead.</p>';
$template['Phase3Temp2']='<!DOCTYPE html>'.
                          '<html lang="en" xmlns="http://www.w3.org/1999/xhtml">'.
                          '<head>'.                                    
                          '</head>'.
                          '<body>'.
                                '<div style="margin: auto;">'.
                                    '<p>Dear '.$receiverNameInMail.',</p>'.
                                    $templateBody['Phase3Temp2'].
                                    '<p>PS:&nbsp;'.$PostScriptInMail.'</p>'.
                                '</div>'.
                            '</body>'.
                            '</html>';

$subject['Phase3Temp2']="URGENT: Audit Course Allottment Results Arrived";





$templateBody['Phase3Temp3']=       '<p><b>You have not been allotted any audit course!</b></p>'.
                                    '<p>The results for the allotment of audit courses have been declared.</p>'.
                                    '<p>Login with your SVVNetID and password as \'kjsce\' if you haven\'t changed it yet. If you haven\'t, please change it as soon as possible.</p>'.
                                    '<p><b>Use the following link to check out which audit courses you and your friends have been allotted:</b></p>'.
                                    '<p><span style=""><a href="http://'.$_SERVER['HTTP_HOST'].'/ACAWA/Results.php" target="_blank"><span>Click here</span></a></span></p>'.
                                    '<p><b>Meet the requested person-in-charge for further details.</b></p>'.
                                    '<p>Have a nice day ahead.</p>';


$template['Phase3Temp3']='<!DOCTYPE html>'.
                          '<html lang="en" xmlns="http://www.w3.org/1999/xhtml">'.
                          '<head>'.                                    
                          '</head>'.
                          '<body>'.
                                '<div style="margin: auto;">'.
                                    '<p>Dear '.$receiverNameInMail.',</p>'.
                                    $templateBody['Phase3Temp3'].
                                    '<p>PS:&nbsp;'.$PostScriptInMail.'</p>'.
                                '</div>'.
                            '</body>'.
                            '</html>';

$subject['Phase3Temp3']="URGENT: Audit Course Allottment Results Arrived";
?>