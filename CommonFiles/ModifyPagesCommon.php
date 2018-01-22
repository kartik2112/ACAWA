<?php
    $sqlSelectSubjects="SELECT Subj_ID,Subject_Name,File_Link,Capacity,Teacher,imagelink FROM Subject where Sem=".$semesterSearch." order by Subject_Name";
    $resultSelectSubjects=mysqli_query($conn,$sqlSelectSubjects);
    echo '<div class="myTableOuter"><table class="myTable">';
    echo '  <tr class="myTableHeaderRow"><th></th><th>Subject</th><th>Teacher</th><th>Capacity</th><th style="text-align:center;">File</th></tr>';
                                        
    if(mysqli_num_rows($resultSelectSubjects)!=0){
        while($rowSelectSubjects=mysqli_fetch_array($resultSelectSubjects,MYSQLI_ASSOC)){
            echo '<tr name="'.$rowSelectSubjects['Subj_ID'].'" data-semester="'.$semesterSearch.'" class="MoreDetailsHover">';
                                            
            echo '<td class="ThumbnailFileTD" style="text-align:center;"><img src="images/SubjectIcons/'.$rowSelectSubjects['imagelink'].'" style="border-radius:50%;width:30px;height:30px;"/></td>';

            echo '<td class="tdSubject">'.$rowSelectSubjects['Subject_Name'].'</td>';
            echo '<td class="tdTeacher">'.$rowSelectSubjects['Teacher'].'</td>';
            echo '<td class="tdCapacity">'.$rowSelectSubjects['Capacity'].'</td>';
            if(isset($rowSelectSubjects['File_Link']) && $rowSelectSubjects['File_Link']!=NULL){
                echo '<td class="SyllFileTD" style="text-align:center;"><a class="waves-effect waves-light btn" target="_blank" href="UploadedDocs/Syllabus/'.$rowSelectSubjects['File_Link'].'">DOWNLOAD</a></td>';
            }
            else{
                echo '<td class="SyllFileTD" style="text-align:center;"><a class="waves-effect waves-light btn disabled">NO FILE UPLOADED</a></td>';
            }    

            echo '</tr>';
        }
    }
    echo '<tr class="AddSubjectTR MoreDetailsHover" name="'.$semesterSearch.'"><td colspan="5" class="AddSubjectTD"><i class="material-icons md-48">add_circle_outline</i> &nbsp;&nbsp; Add New Subject</td></tr>';
    echo '</table></div>';
?>