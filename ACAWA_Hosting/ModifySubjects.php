<?php
    session_start();
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: login.php");
    }

    require "CommonFiles/connection.php";
    require "CommonFiles/CommonConstants.php";
    require("CommonFiles/ImpDatesXMLData.php");

    $displayMessage="";

    if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['action']) ){
        if($_POST['action']=='delete'){
            $sqlSelectExistingSubject="select imagelink,File_Link,Sem from Subject where Subj_ID=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])));
            $resultSelectExistingSubject=mysqli_query($conn,$sqlSelectExistingSubject);
            $rowSelectExistingSubject=mysqli_fetch_array($resultSelectExistingSubject,MYSQLI_ASSOC);
                        
            $sqlCheckIfCanDelete="select count(*) from AC".$rowSelectExistingSubject['Sem']." where pref_1=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])))." OR pref_2=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])))." OR pref_3=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])))." OR pref_4=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])))." OR pref_5=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])));
            $resultCheckIfCanDelete=mysqli_query($conn,$sqlCheckIfCanDelete);
            $rowCheckIfCanDelete=mysqli_fetch_array($resultCheckIfCanDelete,MYSQLI_ASSOC);
            if($rowCheckIfCanDelete['count(*)']==0){                
                        
                if(isset($rowSelectExistingSubject['File_Link'])){
                    unlink("UploadedDocs/Syllabus/".$rowSelectExistingSubject['File_Link']);
                }
                if( isset($rowSelectExistingSubject['imagelink']) && strpos($rowSelectExistingSubject['imagelink'],"StandardIcons")===FALSE){
                    unlink("images/SubjectIcons/".$rowSelectExistingSubject['imagelink']);
                }

                $sqlDeleteSubject="delete from Subject where Subj_ID=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])));
                if(!mysqli_query($conn,$sqlDeleteSubject)){
                    $displayMessage.="Error while deleting subject from Subject table ".mysqli_error($conn)."<br/>Please report this error to the office.<seperator>";
                }
            } 
            else{
                $displayMessage.="Cannot delete this subject since one or many students have already given this as their choice!<seperator>";
            }     

            

        }
        else if($_POST['action']=='update'){
            $sqlSelectExistingSubject="select imagelink,File_Link from Subject where Subj_ID=".$_POST['SubjIDModal'];
            $resultSelectExistingSubject=mysqli_query($conn,$sqlSelectExistingSubject);
            $rowSelectExistingSubject=mysqli_fetch_array($resultSelectExistingSubject,MYSQLI_ASSOC);
            $syllFileAdd=FALSE;
            $thumbnailFileAdd=FALSE;

            if($_POST['deleteSyllFile']==="true"){
                
                if( !empty($_FILES['syllFileChange']['tmp_name']) && is_uploaded_file($_FILES['syllFileChange']['tmp_name']) ){
                    $syllFileTmp = $_FILES['syllFileChange']['tmp_name'];
                    $str123 = explode('.',$_FILES['syllFileChange']['name']);
                    $syllFileExt=strtolower(end($str123));
      
                    $syllFileExtensions= array("doc","docx","pdf");
      
                    if(in_array($syllFileExt,$syllFileExtensions)=== false){
                        $displayMessage.=$syllFileExt." extension not allowed, please choose a doc or docx or pdf file.<seperator>";
                        $syllFileAdd=FALSE;
                    }
                    else{
                        $syllabusFilePath="Sem ".$_POST['SemesterModal']."-".$_FILES['syllFileChange']['name'];  
                        unlink("UploadedDocs/Syllabus/".$rowSelectExistingSubject['File_Link']);                  
                        move_uploaded_file($syllFileTmp,"UploadedDocs/Syllabus/".$syllabusFilePath);                        
                        $syllFileAdd=TRUE;
                    }
                }

            }

            if($_POST['deleteThumbnail']==="true"){
                
                if( !empty($_FILES['thumbnailFileChange']['tmp_name']) && is_uploaded_file($_FILES['thumbnailFileChange']['tmp_name']) ){
                    $thumbnailFileTmp = $_FILES['thumbnailFileChange']['tmp_name'];
                    $str123 = explode('.',$_FILES['thumbnailFileChange']['name']);
                    $thumbnailFileExt=strtolower(end($str123));
      
                    $thumbnailFileExtensions= array("png","gif","jpg","jpeg");
      
                    if(in_array($thumbnailFileExt,$thumbnailFileExtensions)=== false){
                        $displayMessage.=$thumbnailFileExt." extension not allowed, please choose a png or gif or jpg or jpeg file.<seperator>";
                        $thumbnailFileAdd=FALSE;
                    }
                    else{
                        
                        $thumbnailFilePath="Sem ".$_POST['SemesterModal']."-".$_FILES['thumbnailFileChange']['name']; 
                        if(strpos($rowSelectExistingSubject['imagelink'],"StandardIcons")===FALSE){
                            unlink("images/SubjectIcons/".$rowSelectExistingSubject['imagelink']);
                        }                   
                        move_uploaded_file($_FILES['thumbnailFileChange']['tmp_name'],"images/SubjectIcons/".$thumbnailFilePath);                        
                        $thumbnailFileAdd=TRUE;
                    }
                }
            }
            

            $sqlModifySubject="update Subject set Subject_Name='".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjectModal'])))."' , Teacher='".mysqli_real_escape_string($conn,stripslashes(trim($_POST['TeacherModal'])))."' , Capacity=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CapacityModal'])));
            if($syllFileAdd){
                $sqlModifySubject.=" , File_Link='".$syllabusFilePath."'";
            }
            if($thumbnailFileAdd){
                $sqlModifySubject.=" , imagelink='".$thumbnailFilePath."'";
            }

            $sqlModifySubject.=" where Subj_ID=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjIDModal'])));
            if(!mysqli_query($conn,$sqlModifySubject)){
                echo "Error while updating Subject table ".mysqli_error($conn)."<br/>Please report this error to the office.";
            }
        }
        else if($_POST['action']=='addSubj'){
            $syllFileAdd=FALSE;
            $thumbnailFileAdd=FALSE;
            
            $sqlCheckCourse="select * from Subject where Subject_Name='".mysqli_real_escape_string($conn,stripslashes(trim(substr($_POST['SubjectModalAdd'],0,SUBJ_NAME_LENGTH))))."' and Sem=".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SemesterModalAdd'])));
            $resultCheckCourse=mysqli_query($conn,$sqlCheckCourse);
            if(mysqli_num_rows($resultCheckCourse)==0){

                if( !empty($_FILES['syllabusFileAdd']['tmp_name']) && is_uploaded_file($_FILES['syllabusFileAdd']['tmp_name']) ){
                    $syllFileTmp = $_FILES['syllabusFileAdd']['tmp_name'];
                    $str123 = explode('.',$_FILES['syllabusFileAdd']['name']);
                    $syllFileExt=strtolower(end($str123));
      
                    $syllFileExtensions= array("doc","docx","pdf");
      
                    if(in_array($syllFileExt,$syllFileExtensions)=== false){
                        $displayMessage.=$syllFileExt."extension not allowed, please choose a doc or docx or pdf file.<seperator>";
                        $syllFileAdd=FALSE;
                    }
                    else{
                        $syllabusFilePath="Sem ".$_POST['SemesterModalAdd']."-".$_FILES['syllabusFileAdd']['name'];                    
                        move_uploaded_file($syllFileTmp,"UploadedDocs/Syllabus/".$syllabusFilePath);
                        $syllFileAdd=TRUE;
                    }
                }

                if( !empty($_FILES['thumbnailFileAdd']['tmp_name']) && is_uploaded_file($_FILES['thumbnailFileAdd']['tmp_name']) ){
                    $thumbnailFileTmp = $_FILES['thumbnailFileAdd']['tmp_name'];
                    $str123 = explode('.',$_FILES['thumbnailFileAdd']['name']);
                    $thumbnailFileExt=strtolower(end($str123));
      
                    $thumbnailFileExtensions= array("png","jpg","gif","jpeg");
      
                    if(in_array($thumbnailFileExt,$thumbnailFileExtensions)=== false){
                        $displayMessage.=$thumbnailFileExt."extension not allowed, please choose a doc or docx or pdf file.<seperator>";
                        $thumbnailFileAdd=FALSE;
                    }
                    else{
                        $thumbnailFilePath="Sem ".$_POST['SemesterModalAdd']."-".$_FILES['thumbnailFileAdd']['name'];
                        move_uploaded_file($thumbnailFileTmp,"images/SubjectIcons/".$thumbnailFilePath);
                        $thumbnailFileAdd=TRUE;
                    }
                }
                else{
                
                    $thumbnailFilePath="StandardIcons/".rand(1,NO_OF_STD_IMAGES).".jpg";
                    $thumbnailFileAdd=TRUE;
                }

                $sqlAddSubject="insert into Subject(Sem,Subject_Name,Teacher,Capacity";
                if($syllFileAdd){
                    $sqlAddSubject.=",File_Link";
                }
                if($thumbnailFileAdd){                
                    $sqlAddSubject.=",imagelink";
                }
                $sqlAddSubject.=") values(".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SemesterModalAdd']))).",'".mysqli_real_escape_string($conn,stripslashes(trim($_POST['SubjectModalAdd'])))."','".mysqli_real_escape_string($conn,stripslashes(trim($_POST['TeacherModalAdd'])))."',".mysqli_real_escape_string($conn,stripslashes(trim($_POST['CapacityModalAdd'])));
                if($syllFileAdd){
                    $sqlAddSubject.=",'".$syllabusFilePath."'";
                }
                if($thumbnailFileAdd){
                    $sqlAddSubject.=",'".$thumbnailFilePath."'";
                }
                $sqlAddSubject.=")";
                if(!mysqli_query($conn,$sqlAddSubject)){
                    $displayMessage.="Error while adding subject to Subject table ".mysqli_error($conn)."<br/>Please report this error to the office.<seperator>";
                }
            }
            else{
                $displayMessage.="Cannot add Subject: \'".$_POST['SubjectModalAdd']."\'<br/> since subject with same name exists in Semester ".$_POST['SemesterModalAdd']."<seperator>";
            }
            
        }
    }

?>

<!DOCTYPE html> <!-- for HTML 5 -->
<html>
<head>
    <?php include("CommonFiles/CommonHead.php"); ?>
	<title>Modify Subjects</title>
	<style>
	
    #modal_add{
         max-height: 80%!important;
         height: 80%!important;
     }
     
     #modal_update_delete{
         max-height: 80%!important;
         height: 80%!important;
     }
     @media screen and (max-width: 600px){
        .modalFooterMedContent{
            display:none;
        }
    }

	</style>    
    <script src="JS/ModifySubjects.js"></script>
            
</head>

<body style = "">

    <?php include "CommonFiles/Menu.php"?>
    
    <script>
        $(document).ready(function(){
            <?php
                if($displayMessage){
                    $splitDisplayMessage=explode("<seperator>",$displayMessage);
                    $timerToToast=0;
                    foreach($splitDisplayMessage as $displaySplitMessage){
                        if($displaySplitMessage!=""){
                            echo 'window.setTimeout(function(){';
                            echo        "var \$toastContent = \$('<span>$displaySplitMessage</span>');";
                            echo        "Materialize.toast(\$toastContent, 10000);";
                            echo '},'.($timerToToast*1000).');';
                        }        
                        $timerToToast++;                
                    }
                    
                }
            ?>
        });
    </script>

    <?php
        if(isset($thumbnailFileErrorMsg) || isset($syllFileErrorMsg) ){
            echo '$(document).ready(function () {';
            echo    'var $toastContent = $("<span>'.$thumbnailFileErrorMsg.' '.$syllFileErrorMsg.'</span>");';
            echo    'Materialize.toast($toastContent, 5000);';
            echo '});';
        }
    ?>
    <div class="container">
		<h1 style="margin: auto;">MODIFY SUBJECTS</h1>

		<div>
            
            <ul class="collapsible popout" data-collapsible="accordion">
                <?php
                    $romanArray=['I','II','III','IV','V','VI','VII','VIII'];
                    for($i=1;$i<=NO_OF_SEMS;$i++){
                        echo '<li>';
                        echo    '<div class="collapsible-header"><i class="material-icons">filter_'.$i.'</i>SEMESTER '.$romanArray[($i-1)].'</div>';
                        echo    '<div class="collapsible-body" style="background-color: #b3e0fd;">';
                        echo        '<div class="tableOuter">';
                        $semesterSearch=$i;
                        include("CommonFiles/ModifyPagesCommon.php");
                        echo        '</div>';
                        echo    '</div>';
                        echo '</li>';
                    }
                ?>                
            </ul>

		</div>
	</div>
    
    <!-- Modal Structure to update and delete subjects-->
    <div id="modal_update_delete" class="modal modal-fixed-footer">
        <form class="col s6" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" id="SubjIDModal" name="SubjIDModal" value=""/>
            <input type="hidden" id="SemesterModal" name="SemesterModal"/>
            <div class="modal-content">
				<div class="column" style="width: 50%;margin: auto;">
					<div class="input-field col s6">
						<i class="material-icons prefix">library_books</i>
						<input id="SubjectModal" type="text" name="SubjectModal" class="Modal_Main_inputs" maxlength="<?php echo SUBJ_NAME_LENGTH; ?>" disabled/>
						<label id="SubjectModalLabel" for="SubjectModal">Subject</label>
					</div>
					<br/>

                    <div class="input-field col s6">
						<i class="material-icons prefix">school</i>
						<input id="TeacherModal" type="text" name="TeacherModal" class="Modal_Main_inputs" maxlength="<?php echo TEACHER_NAME_LENGTH; ?>" disabled/>
						<label id="TeacherModalLabel" for="TeacherModal">Teacher</label>
					</div>
					<br/>

					<div class="input-field col s6">
						<i class="material-icons prefix">people</i>
						<input id="CapacityModal" type="number" name="CapacityModal" class="Modal_Main_inputs" disabled/>
						<label id="CapacityModalLabel" for="CapacityModal">Capacity</label>
					</div>
					<br/>
                    
                    <div id="SyllFileDivModalOuter">
                        <span style="margin-bottom: 50px;font-weight: bold;">Syllabus File:</span><span class="deleteOptionSpan" id="syllFileISpan" style="display:none;margin-left: 30px;border-radius: 50%;"><i class="material-icons prefix deleteOption md-24" id="syllFileIid" style="border-radius: 50%;">highlight_off</i></span><br/>
                        <span id="SyllFileNameDivModal"></span><br/>
                        <div id="SyllFileDivModal" style="display: inline-block;"></div>
                    </div>
                    <input type="hidden" id="deleteSyllFile" name="deleteSyllFile" value="false"/>
                    <div id="NoSyllFileDisplayModal" class="input-field col s6" style="display: none;">
                        <div class="file-field input-field">
						    <div class="btn Modal_Main_inputs">
                                <span>SELECT SYLLABUS FILE</span>
                                <input type="file" id="syllFileChange" name="syllFileChange" class="Modal_Main_inputs" disabled>
                            </div>
                            <br/><br/>
                            <div class="file-path-wrapper">
                                <input class="file-path" type="text" class="Modal_Main_inputs" id="syllFileChangeName" onchange="checkSyllFileAdded();" disabled>
                            </div>
                        </div>
					</div>
                    <br/>
                    <br/>

                    <div id="ThumbnailFileDivModalOuter">
                        <span style="margin-top: 30px;margin-bottom: 50px;font-weight: bold;">Thumbnail:</span><span class="deleteOptionSpan" id="thumbnailFileISpan" style="display:none;margin-left: 30px;border-radius: 50%;"><i class="material-icons prefix deleteOption md-24" id="thumbnailFileIid" style="border-radius: 50%;">highlight_off</i></span><br/>                        
                        <div id="ThumbnailFileDivModal" style="display: inline-block;"></div>
                    </div>
                    <input type="hidden" id="deleteThumbnail" name="deleteThumbnail" value="false"/>
                    <div id="NoThumbnailFileDisplayModal" class="input-field col s6" style="display: none;">
                        <div class="file-field input-field">
						    <div class="btn Modal_Main_inputs">
                                <span>SELECT THUMBNAIL FILE</span>
                                <input type="file" id="thumbnailFileChange" name="thumbnailFileChange" class="Modal_Main_inputs" disabled>
                            </div>
                            <br/>
                            <div class="file-path-wrapper">
                                <input class="file-path" type="text" class="Modal_Main_inputs" id="thumbnailFileChangeName" onchange="checkThumbnailFileAdded();" disabled>
                            </div>
                        </div>
					</div>

					<br/>
				</div>
            </div>
            <div class="modal-footer">
                <a id="CancelModalButton" class=" modal-action waves-effect waves-red btn-flat">Cancel</a>
                <button class="modal-action modal-close waves-effect waves-red btn-flat" type="submit" id="DeleteModalButton" name="action" value="delete" onclick="return confirm('Are you sure about deleting this subject?');"><i class="material-icons right">delete_forever</i><span class="modalFooterMedContent">Delete Subject</span></button>
                <a class="modal-action waves-effect waves-orange btn-flat" id="ModifyModalButton"><i class="material-icons right">edit</i><span class="modalFooterMedContent">Modify</span></a>
                <button class="modal-action modal-close waves-effect waves-green btn-flat" type="submit" id="SaveModalButton" name="action" style="display: none;" value="update"><i class="material-icons right">save</i><span class="modalFooterMedContent">Save</span></button>
                
            </div>
        </form>
    </div>


    <!-- Modal Structure to add new subjects-->
    <div id="modal_add" class="modal modal-fixed-footer">
        <form class="col s6" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" id="SemesterModalAdd" name="SemesterModalAdd"/>
            <div class="modal-content">
				<div class="column" style="width: 50%;margin: auto;">
					<div class="input-field col s6">
						<i class="material-icons prefix">library_books</i>
						<input id="SubjectModalAdd" type="text" name="SubjectModalAdd" class="Modal_Main_inputs_Add" maxlength="<?php echo SUBJ_NAME_LENGTH; ?>" required class="Modal_Main_inputs_Add"/>
						<label id="SubjectModalAddLabel" for="SubjectModalAdd" class="Modal_Main_inputs_Add_Labels">Subject</label>
					</div>
					<br/>

                    <div class="input-field col s6">
						<i class="material-icons prefix">school</i>
						<input id="TeacherModalAdd" type="text" name="TeacherModalAdd" class="Modal_Main_inputs_Add" maxlength="<?php echo TEACHER_NAME_LENGTH; ?>" required class="Modal_Main_inputs_Add"/>
						<label id="TeacherModalAddLabel" for="TeacherModalAdd" class="Modal_Main_inputs_Add_Labels">Teacher</label>
					</div>
					<br/>

					<div class="input-field col s6">
						<i class="material-icons prefix">people</i>
						<input id="CapacityModalAdd" type="number" name="CapacityModalAdd" class="Modal_Main_inputs_Add" required class="Modal_Main_inputs_Add"/>
						<label id="CapacityModalAddLabel" for="CapacityModalAdd" class="Modal_Main_inputs_Add_Labels">Capacity</label>
					</div>
					<br/>
                    
                    <div class="input-field col s6">
                        <div class="file-field input-field">                            
						    <div class="btn">
                                <span>SELECT SYLLABUS FILE</span>
                                <input type="file" id="syllFileAdd" name="syllabusFileAdd" class="Modal_Main_inputs_Add">
                            </div>
                            <br/>
                            <div class="file-path-wrapper">
                                <input class="file-path" id="syllFileAddName" type="text" onchange="checkSyllFileExtension();" class="Modal_Main_inputs_Add">
                            </div>
                        </div>
					</div>

                    <div class="input-field col s6">
                        <div class="file-field input-field">                            
						    <div class="btn">
                                <span>SELECT THUMBNAIL FILE</span>
                                <input type="file" name="thumbnailFileAdd" class="Modal_Main_inputs_Add">
                            </div>
                            <br/>
                            <div class="file-path-wrapper">
                                <input class="file-path" id="thumbnailFileAddName" type="text" onchange="checkThumbnailFileExtension();" class="Modal_Main_inputs_Add">
                            </div>
                        </div>
					</div>

					<br/>
				</div>
            </div>
            <div class="modal-footer">
                <a id="CancelAddModalButton" class=" modal-action modal-close waves-effect waves-red btn-flat">Cancel</a>
                <button class="modal-action waves-effect waves-green btn-flat" type="submit" id="AddModalButton" name="action" value="addSubj"><i class="material-icons right">send</i>Add Subject</button>
                
            </div>
        </form>
    </div>


</body>
</html>