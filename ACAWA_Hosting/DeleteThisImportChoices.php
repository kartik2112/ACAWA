<?php

session_start();
if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
    header("location: login.php");
}

require "CommonFiles/connection.php";
require "CommonFiles/CommonConstants.php";

$uploadFlagChoices=FALSE;
$uploadFlagUsers=FALSE;
$successMessage="";
$errorMessage="";
if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['Submit'])){
    $fileLocation="UploadedDocs/ImportData/".basename($_FILES['ExcelFileUpload']['name']);
    $fileExtension=pathinfo($fileLocation,PATHINFO_EXTENSION);
    if($fileExtension=="xls" || $fileExtension=="xlsx"){
        

        //Add more error checking of contents here

        $fileLocation=substr($fileLocation,0,strripos($fileLocation,"."));
        if(file_exists($fileLocation." ".date("d-m-Y").".".$fileExtension)){
            $suffix=1;
            
            while(file_exists($fileLocation." ".date("d-m-Y")." ".$suffix.".".$fileExtension)){
                $suffix++;
            }

            if (move_uploaded_file($_FILES["ExcelFileUpload"]["tmp_name"], $fileLocation." ".date("d-m-Y")." ".$suffix.".".$fileExtension)) {
                $successMessage.="The file ". basename( $_FILES["ExcelFileUpload"]["name"] ). " has been uploaded.<br/>";
                $fileLocation=$fileLocation." ".date("d-m-Y")." ".$suffix.".".$fileExtension;
                $uploadFlagChoices=TRUE;
                $uploadFlagUsers=TRUE;

            } else {
                $errorMessage.="Sorry, there was an error uploading your file.";
            }

        }
        else{
            if (move_uploaded_file($_FILES["ExcelFileUpload"]["tmp_name"], $fileLocation." ".date("d-m-Y").".".$fileExtension)) {
                $successMessage.="The file ". basename( $_FILES["ExcelFileUpload"]["name"]). " has been uploaded.<br/>";
                $fileLocation=$fileLocation." ".date("d-m-Y").".".$fileExtension;
                $uploadFlagChoices=TRUE;
                $uploadFlagUsers=TRUE;

            } else {
                $errorMessage.="Sorry, there was an error uploading your file.<br/>";
            }
        }
        
    }
    else{
        $errorMessage.="Incorrect File Type<br/>";
    }
}

if($uploadFlagChoices && $uploadFlagUsers){
                

                /**
                * This part is directly taken from the examples given for PHPExcel
                * It is just tailored to the application. 
                * The basic loading techniques have been used as given in examples.
                */
                error_reporting(E_ALL);
                set_time_limit(0);
                      
                date_default_timezone_set('Asia/Kolkata');
                set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel-1.8/Classes/');
                include 'PHPExcel/IOFactory.php';
                    
                $inputFileType = PHPExcel_IOFactory::identify($fileLocation);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);


                $objReader->setLoadAllSheets();
                $objPHPExcel = $objReader->load($fileLocation);
                $loadedSheetNames = $objPHPExcel->getSheetNames();

                

                /**
                * This part loads only the Books sheet of the excel file and parses it
                */

                $sheetname = 'CHOICES';
                if(in_array($sheetname,$loadedSheetNames)){
                        $objReader->setLoadSheetsOnly($sheetname);
                        $objPHPExcel = $objReader->load($fileLocation);
                        $successMessage.='Detected sheet '.$sheetname.' of file '.$_FILES["ExcelFileUpload"]["name"].'<br/>';    //pathinfo($fileLocation,PATHINFO_BASENAME) exact name of file saved finally  
        
                        $sheetChoicesData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                
                        if($sheetChoicesData[1]['A']=="Roll Number" && $sheetChoicesData[1]['B']=="Pref1" && $sheetChoicesData[1]['C']=="Pref2" && $sheetChoicesData[1]['D']=="Pref3" && $sheetChoicesData[1]['E']=="Pref4" && $sheetChoicesData[1]['F']=="Pref5" && $sheetChoicesData[1]['G']=="Sem" && $sheetChoicesData[1]['H']=="submittedOn" && $sheetChoicesData[1]['I']=="Time"){
                            $uploadFlagChoices=TRUE;
                            $noOfChoices=count($sheetChoicesData)-1;
                            $successMessage.='Headings found to be correct in sheet '.$sheetname.' of file.<br/>';
                        }
                        else{
                            $errorMessage.="Incorrect Headings provided in excel sheet ".$sheetname."!<br/>";
                            $uploadFlagChoices=FALSE;
                        }
                }
                else{
                    $errorMessage.="ChoiceS sheet not present.<br/>";
                    $uploadFlagChoices=FALSE;
                }
                

                if($uploadFlagChoices===FALSE){
                    unlink($fileLocation);
                    $errorMessage.="Uploaded File deleted from server.<br/>";
                }
                
}
?>

<!DOCTYPE html> <!-- for HTML 5 -->
<html>
<head>
    <title>Import Choices</title>
    <?php include("CommonFiles/CommonHead.php"); ?>    
    <script src="JS/DeleteThisImportChoices.js"></script>
    
</head>

<body style = "">
    <?php include "CommonFiles/Menu.php"?>
    <script>
        $(document).ready(function () {
            $("#SideMenuImportLI").addClass("active");

            <?php
                if($successMessage){
                    $splitSuccessMessage=explode("<br/>",$successMessage);
                    $timerToToast=1;
                    foreach($splitSuccessMessage as $displaySuccessMessage){
                        if($displaySuccessMessage!=""){
                            echo 'window.setTimeout(function(){';
                            echo        "var \$toastContent = \$('<span>$displaySuccessMessage</span>');";
                            echo        "Materialize.toast(\$toastContent, 6000);";
                            echo '},'.($timerToToast*1000).');';
                        }        
                        $timerToToast++;                
                    }
                    
                }

                if($errorMessage){
                    $splitErrorMessage=explode("<br/>",$errorMessage);
                    $timerToToast=1;
                    foreach($splitErrorMessage as $displayErrorMessage){
                        if($displayErrorMessage!=""){
                            echo 'window.setTimeout(function(){';
                            echo        "var \$toastContent = \$('<span>$displayErrorMessage</span>');";
                            echo        "Materialize.toast(\$toastContent, 6000);";
                            echo '},'.($timerToToast*1000).');';
                        }  
                        $timerToToast++;                
                    }
                    
                }
            ?>

        });
    </script>
    
    <div id="main_body" style="color: rgba(255, 255, 255, 0.84);padding: 20px;" >  
        <?php
        if(!$uploadFlagChoices){
        ?>                  
             
               
            <form class="col s6" action="" method = "POST" enctype = "multipart/form-data" onsubmit="return VerifyNUploadThisFile();">
                <div class="column" style="width: 50%;margin: auto;">
                    <div class="file-field input-field">
					    <div class="btn Modal_Main_inputs" style="display: block;">
                            <span>SELECT EXCEL FILE</span>
                            <input type="file" id="ExcelFileUpload" name="ExcelFileUpload" onchange="VerifyFileType()">
                        </div>
                        <div class="file-path-wrapper" style="display: block;">
                            <input class="file-path" type="text" class="Modal_Main_inputs" id="ExcelFileUploadName">
                        </div>
                    </div>                
                    <br/><br/>
                    <button class="btn waves-effect waves-light UploadBtns" type="submit" name="Submit" style="margin: auto" value="Upload">Upload
				        <i class="material-icons right">file_upload</i>
				    </button>
                    
                </div>
            </form>
            
            
            
        <?php
        }
        else{
        ?>
            <div style="margin: auto;text-align: center;">
        <?php
                    if($uploadFlagChoices){
                        echo '<button class="btn waves-effect waves-light UploadBtns" id="StartChoicesAddButton" onclick="startChoicesUpload()" style="margin: auto;">Add Choices
                                    <i class="material-icons right">add_to_photos</i>
                              </button><br/>';
                    }
        ?>
                
            
            
                    <div id="ChoicesUploadOuterDiv" style="display: none;color: #002c6a">
                        <div id="ChoiceProgressDetails">
                            <span id="ChoicesBeforeProgressBarDiv">Choices Add Progress:</span><span id="ChoicesProgressBarStatusValue" style="color: white;line-height: 30px;margin-left: 4px;">0%</span><br/>
                            <div id="ChoicesProgressBarDiv" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="ChoiceProgressBar" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                            <div id="ChoicesUploadStart" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>                
                        </div>
                        <h5>No of Choices added successfully: <span id="ChoicesAddSuccessNo">0</span> / <span class="TotalNoOfChoicesAdd"></span></h5>
                        <h5>No of Choices that could not be added: <span id="ChoicesFailNo">0</span> / <span class="TotalNoOfChoicesAdd"></span></h5>
                        <h5>No of Choices that already exist: <span id="ChoicesAlreadyExistsNo">0</span> / <span class="TotalNoOfChoicesAdd"></span></h5>
                        <div id="ChoicesAddErrorStatus" ></div>
                    </div>
            </div>
            <script>
                ChoicesToBeAdded=<?php echo json_encode($sheetChoicesData); ?>;
                noOfChoices=<?php echo $noOfChoices; ?>;

            </script>
        <?php
        }
        ?>
    </div>
    
    
    
</body>
</html>