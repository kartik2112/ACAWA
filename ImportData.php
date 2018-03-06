<?php

session_start();
if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
    header("location: login.php");
}

require_once "CommonFiles/connection.php";
require_once "CommonFiles/CommonConstants.php";
require_once "CommonFiles/ImpDatesXMLData.php";

$uploadFlagCourses=FALSE;
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
                $uploadFlagCourses=TRUE;
                $uploadFlagUsers=TRUE;

            } else {
                $errorMessage.="Sorry, there was an error uploading your file.";
            }

        }
        else{
            if (move_uploaded_file($_FILES["ExcelFileUpload"]["tmp_name"], $fileLocation." ".date("d-m-Y").".".$fileExtension)) {
                $successMessage.="The file ". basename( $_FILES["ExcelFileUpload"]["name"]). " has been uploaded.<br/>";
                $fileLocation=$fileLocation." ".date("d-m-Y").".".$fileExtension;
                $uploadFlagCourses=TRUE;
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

if($uploadFlagCourses && $uploadFlagUsers){
                

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

                /**
                * This will load all sheet names and store in array $loadedSheetNames
                */
                $objReader->setLoadAllSheets();
                $objPHPExcel = $objReader->load($fileLocation);
                $loadedSheetNames = $objPHPExcel->getSheetNames();

                

                /**
                * This part loads only the Courses sheet of the excel file and parses it
                */

                $sheetname = 'COURSES';
                if(in_array($sheetname,$loadedSheetNames)){
                        $objReader->setLoadSheetsOnly($sheetname);
                        $objPHPExcel = $objReader->load($fileLocation);
                        $successMessage.='Detected sheet '.$sheetname.' of file '.$_FILES["ExcelFileUpload"]["name"].'<br/>';    //pathinfo($fileLocation,PATHINFO_BASENAME) exact name of file saved finally  
        
                        $sheetCoursesData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                
                        if($sheetCoursesData[1]['A']=="Semester" && $sheetCoursesData[1]['B']=="Subject" && $sheetCoursesData[1]['C']=="Teacher" && $sheetCoursesData[1]['D']=="Capacity"){
                            $uploadFlagCourses=TRUE;
                            $noOfCourses=count($sheetCoursesData)-1;
                            $successMessage.='Headings found to be correct in sheet '.$sheetname.' of file.<br/>';
                        }
                        else{
                            $errorMessage.="Incorrect Headings provided in excel sheet ".$sheetname."!<br/>";
                            $uploadFlagCourses=FALSE;
                        }
                }
                else{
                    $errorMessage.="COURSES sheet not present.<br/>";
                    $uploadFlagCourses=FALSE;
                }
                


                /**
                * This part loads only the Users sheet of the excel file and parses it
                */
                $sheetname = 'USERS';
                if(in_array($sheetname,$loadedSheetNames)){
                        $objReader->setLoadSheetsOnly($sheetname);
                        $objPHPExcel = $objReader->load($fileLocation);
                        $successMessage.='Detected sheet '.$sheetname.' of file '.$_FILES["ExcelFileUpload"]["name"].'<br/>';    //pathinfo($fileLocation,PATHINFO_BASENAME) exact name of file saved finally  
        
                        $sheetUsersData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                
                        if($sheetUsersData[1]['A']=="Roll Number" && $sheetUsersData[1]['B']=="Name" && $sheetUsersData[1]['C']=="Semester" && $sheetUsersData[1]['D']=="Username" && $sheetUsersData[1]['E']=="Type" && $sheetUsersData[1]['F']=="Branch"){
                            $uploadFlagUsers=TRUE;
                            $noOfUsers=count($sheetUsersData)-1;
                            $successMessage.='Headings found to be correct in sheet '.$sheetname.' of file.<br/>';
                        }
                        else{
                            $errorMessage.="Incorrect Headings provided in excel sheet ".$sheetname."!<br/>";                            
                            $uploadFlagUsers=FALSE;
                        }
                }
                else{
                    $errorMessage.="USERS sheet not present.<br/>";
                    $uploadFlagUsers=FALSE;
                }

                if($uploadFlagCourses===FALSE && $uploadFlagUsers===FALSE){
                    unlink($fileLocation);
                    $errorMessage.="Uploaded File deleted from server.<br/>";
                }
                
}
?>

<!DOCTYPE html> <!-- for HTML 5 -->
<html>
<head>
    <title>Import Data</title>
    <?php include("CommonFiles/CommonHead.php"); ?>    
    <script src="JS/ImportData.js"></script>
    
</head>

<body style = "">
    <?php include "CommonFiles/Menu.php"?>
    <script>
        $(document).ready(function () {
            $("#SideMenuImportLI").addClass("active");

            <?php
                if($successMessage){
                    $splitSuccessMessage=explode("<br/>",$successMessage);
                    $timerToToast=0;
                    foreach($splitSuccessMessage as $displaySuccessMessage){
                        if($displaySuccessMessage!=""){
                            echo 'window.setTimeout(function(){';
                            echo        "var \$toastContent = \$('<span>$displaySuccessMessage</span>');";
                            echo        "Materialize.toast(\$toastContent, 7000);";
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
        if(!$uploadFlagCourses && !$uploadFlagUsers){
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
            <div style="margin: auto;text-align: center;">
                <h5 style="width: 80%;margin: auto;">
                    The excel sheet should not have any errors. The uploader has to ensure that the column values are as per the column headers.
                    The system cannot handle these errors. The uploader has to ensure that the key fields must be unique.
                    It should use only the following column and sheet names:
                </h5>
                <img src="UploadedDocs/Templates/COURSES Template.PNG" alt="Courses Template" class="templateImages col s6 m6" style="margin: 15px;"/>
                <img src="UploadedDocs/Templates/USERS Template.PNG" alt="Courses Template" class="templateImages col s6 m6" style="margin: 15px;"/>
                <h5 style="width: 80%;margin: auto;">
                    Instead of creating a new excel file you can fill your content using the template provided below
                </h5>
                <a class=" modal-action waves-effect waves-red btn UploadBtns" href="UploadedDocs/Templates/ImportTemplate.xlsx" target="_blank" style="margin: auto;">Download Template</a>
            </div>
            
            
        <?php
        }
        else{
        ?>
            <div style="margin: auto;text-align: center;">
        <?php
                    if($uploadFlagCourses){
                        echo '<button class="btn waves-effect waves-light UploadBtns" id="StartCoursesAddButton" onclick="startCoursesUpload()" style="margin: auto;">Add courses
                                    <i class="material-icons right">add_to_photos</i>
                              </button><br/>';
                    }
                    if($uploadFlagUsers){
                        echo '<button class="btn waves-effect waves-light UploadBtns" id="StartUsersAddButton" onclick="startUsersUpload()" style="margin: auto;">Add users
                                    <i class="material-icons right">add_to_photos</i>
                              </button><br/>';
                    }
        ?>
                
            
            
                    <div id="CoursesUploadOuterDiv" style="display: none;color: #002c6a">
                        <div id="CourseProgressDetails">
                            <span id="CoursesBeforeProgressBarDiv">Courses Add Progress:</span><b><span id="CoursesProgressBarStatusValue" style="line-height: 30px;margin-left: 4px;">0%</span></b><br/>
                            <div id="CoursesProgressBarDiv" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="CourseProgressBar" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                            <div id="CoursesUploadStart" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>                
                        </div>
                        <h5>No of Courses added successfully: <span id="CoursesAddSuccessNo">0</span> / <span class="TotalNoOfCoursesAdd"></span></h5>
                        <h5>No of Courses that could not be added: <span id="CoursesFailNo">0</span> / <span class="TotalNoOfCoursesAdd"></span></h5>
                        <h5>No of Courses that already exist: <span id="CoursesAlreadyExistsNo">0</span> / <span class="TotalNoOfCoursesAdd"></span></h5>
                        <div id="CoursesAddErrorStatus" ></div>
                    </div>
                    <br/>
                    <div id="UsersUploadOuterDiv" style="display: none;color: #002c6a">
                        <div id="UsersProgressDetails">
                            <span id="UsersBeforeProgressBarDiv">Users Add Progress:</span><span id="UsersProgressBarStatusValue" style="color: white;line-height: 30px;margin-left: 4px;">0%</span><br/>
                            <div id="UsersProgressBarDiv" class="ProgressBarDiv" style="width: 300px; height: 30px;margin: auto;z-index: 2;"><div id="UsersProgressBar" class="ProgressBar" style="height: 100%;width: 0%;text-align: center;"></div></div>
                            <div id="UsersUploadStart" style="margin: auto;text-align: center;">Don't turn off your PC or close this page. It may lead to inconsistencies in database</div>                
                        </div>                        
                        <h5>No of Users added successfully: <span id="UsersAddSuccessNo">0</span> / <span class="TotalNoOfUsersAdd"></span></h5>
                        <h5>No of Users that could not be added: <span id="UsersFailNo">0</span> / <span class="TotalNoOfUsersAdd"></span></h5>
                        <h5>No of Users that already exist: <span id="UsersAlreadyExistsNo">0</span> / <span class="TotalNoOfUsersAdd"></span></h5>
                        <div id="UsersAddErrorStatus" ></div>
                    </div>
            </div>
            <script>
                CoursesToBeAdded=<?php echo json_encode($sheetCoursesData); ?>;
                noOfCourses=<?php echo $noOfCourses; ?>;

                UsersToBeAdded=<?php echo json_encode($sheetUsersData); ?>;
                noOfUsers=<?php echo $noOfUsers; ?>;
            </script>
        <?php
        }
        ?>
    </div>
    
    
    
</body>
</html>