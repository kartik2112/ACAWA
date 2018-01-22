<?php
session_start();
if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
    header("location: login.php");
}
    
require "CommonFiles/connection.php";
require "CommonFiles/CommonConstants.php";

if($_SERVER['REQUEST_METHOD']=="GET" && isset($_GET['Sem'])){
    $sqlSearchStuds = "select u.Roll_number,u.Name,Branch,ProfilePicLink,a.allottedChoice,a.pref_1 from User u LEFT OUTER JOIN AC".$_GET['Sem']." a ON a.Roll_number = u.Roll_number where u.Type='S' and u.Semester=".$_GET['Sem'];
    $resultSearchStuds = mysqli_query($conn,$sqlSearchStuds);
}

       
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students List</title>
    <?php include("CommonFiles/CommonHead.php"); ?>    
    <script>
        function RemoveList() {
            $("#StudentsList").slideUp(500);
        }

        function ApplyFilters() {
            $("input.select-dropdown").attr("disabled","disabled");
            var allotValue = $("#AllotedFilter").val();
            var submitValue = $("#SubmittedFilter").val();

            if (submitValue == "noneSelected") {
                if (allotValue == "noneSelected") {
                    $("table#StudsTable tr.Submitted").fadeIn(500);
                    $("table#StudsTable tr.NotSubmitted").fadeIn(500);
                }
                else if (allotValue == "Allotted") {                    
                    $("table#StudsTable tr.NotAllotted").fadeOut(500);
                    $("table#StudsTable tr.Allotted").fadeIn(500);
                }
                else if (allotValue == "NotAllotted") {
                    $("table#StudsTable tr.Allotted").fadeOut(500);
                    $("table#StudsTable tr.NotAllotted").fadeIn(500);
                }
            }
            else if (submitValue == "Submitted") {
                if (allotValue == "noneSelected") {                    
                    $("table#StudsTable tr.NotSubmitted").fadeOut(500);
                    $("table#StudsTable tr.Submitted").fadeIn(500);
                }
                else if (allotValue == "Allotted") {                                        
                    $("table#StudsTable tr.Allotted").fadeIn(500);
                    $("table#StudsTable tr.NotAllotted").fadeOut(500);
                }
                else if (allotValue == "NotAllotted") {
                    $("table#StudsTable tr").fadeOut(500);
                    $("table#StudsTable tr.NotAllotted").fadeIn(500);
                    
                }
            }
            else if (submitValue == "NotSubmitted") {
                if (allotValue == "noneSelected") {
                    $("table#StudsTable tr.Submitted").fadeOut(500);
                    $("table#StudsTable tr.NotSubmitted").fadeIn(500);
                }
                else if (allotValue == "Allotted") {                    
                    $("table#StudsTable tr.NotAllotted").fadeOut(500);
                    $("table#StudsTable tr.Allotted").fadeIn(500);
                }
                else if (allotValue == "NotAllotted") {
                    $("table#StudsTable tr.Allotted").fadeOut(500);
                    $("table#StudsTable tr.NotAllotted").fadeIn(500);
                }
            }
            window.setTimeout(function () {
                $("input.select-dropdown").removeAttr("disabled");
            },800);
            
        }

    </script>
    
</head>

<body style = "">
    <?php include "CommonFiles/Menu.php"; ?>
    <script>
        $(document).ready(function () {
            $('select').material_select();
        });
    </script>
    <form action="" method="get" class="col s6" style="margin: auto;width: 50%;">
        <div class="input-field col s6">                
            <select id="Sem" name="Sem" onchange="RemoveList();">
                <option class="noneSelected" value="noneSelected" <?php if(!isset($_GET['Sem'])){ echo "selected"; } ?> disabled>Select Semester</option>
                <?php
                    $romanArray=['I','II','III','IV','V','VI','VII','VIII'];
                    for($i=1;$i<=NO_OF_SEMS;$i++){
                        echo '<option value="'.$i.'" ';
                        if(isset($_GET['Sem']) && $i==$_GET['Sem']){
                            echo "selected";
                        }
                        echo '>SEMESTER '.$romanArray[$i-1].'</option>';
                    }
                ?>
            </select>
            <label>Semester: &nbsp;</label>
            <br/><br/>
        </div>        
        <button class="modal-action modal-close waves-effect waves-green btn" type="submit">Check this out
            <i class="material-icons right">send</i>
        </button>
    </form>

    

    <div class="row" style="margin: auto;text-align: center;">
        <br/><br/>
        <span style="margin: auto;width: 50%;text-align: center;">Filters: <i class="material-icons">filter_list</i></span><br/><br/>
        <div class="input-field col s10 m5 offset-m1 offset-s1" style="">                
            <select id="SubmittedFilter" name="SubmittedFilter" onchange="ApplyFilters();">
                <option value="noneSelected" selected>No Restriction</option>
                <option value="Submitted">Choices Submitted</option>
                <option value="NotSubmitted">Choices Not Submitted</option>
            </select>
            <label>Submitted Filter: &nbsp;</label>
            <br/><br/>
        </div>
        <div class="input-field col s10 m5 offset-s1" style="">                
            <select id="AllotedFilter" name="AllottedFilter" onchange="ApplyFilters();">
                <option value="noneSelected" selected>No Restriction</option>
                <option value="Allotted">Allotted</option>
                <option value="NotAllotted">Not Allotted</option>
            </select>
            <label>Allotted Filter: &nbsp;</label>
        </div>
    </div>
    

    <div id="StudentsList">
        <?php
            if(isset($_GET['Sem'])){
                    if(mysqli_num_rows($resultSearchStuds)==0){
                        echo '<h5 style="margin:auto;text-align:center;">No students present in this semester</h5>';
                    }
                    else{
                        echo '<div style="margin: auto;overflow-x: scroll;padding: 20px;width:90%;">';
                        echo '<table id="StudsTable" class="myTable">';
                        echo '<tr class="myTableHeaderRow"><th class="CentreThis"></th><th class="CentreThis">Roll Number</th><th>Name</th><th class="CentreThis">Branch</th><th class="CentreThis">Choices Submitted?</th><th>Allotted Subject</th></tr>';
                        while($rowStud = mysqli_fetch_array($resultSearchStuds,MYSQLI_ASSOC)){
                            echo '<tr class="';                            
                            if($rowStud['pref_1']!=NULL){
                                echo 'Submitted ';                                
                            }
                            else{
                                echo 'NotSubmitted ';
                            }
                            if($rowStud['allottedChoice']!=NULL){
                                echo 'Allotted ';
                            }
                            else{
                                echo 'NotAllotted ';
                            }
                            echo '">';
                            if($rowStud['ProfilePicLink']==NULL){
                                echo '<td class="CentreThis"><img src="images/UserProfilePix/avatar.png" style="border-radius:50%;width:50px;height:50px;"/></td>';
                            }
                            else{
                                echo '<td class="CentreThis"><img src="images/UserProfilePix/'.$rowStud['ProfilePicLink'].'" style="border-radius:50%;width:30px;height:30px;"/></td>';
                            }
                
                            echo '<td class="CentreThis">'.$rowStud['Roll_number'].'</td>';
                            echo '<td>'.$rowStud['Name'].'</td>';
                            echo '<td class="CentreThis">'.$rowStud['Branch'].'</td>';

                            if($rowStud['pref_1']!=NULL){
                                echo '<td class="CentreThis"><i class="material-icons md-48" style="color:green;">done</i></td>';
                            }
                            else{
                                echo '<td class="CentreThis"><i class="material-icons md-48" style="color:red;">clear</i></td>';
                            }

                            if($rowStud['allottedChoice']==NULL){
                                echo '<td> - </td>';
                            }
                            else{
                                $sqlSearchSubject="select Subject_Name from Subject where Subj_ID=".$rowStud['allottedChoice'];
                                $resultSearchSubject=mysqli_query($conn,$sqlSearchSubject);
                                if(mysqli_num_rows($resultSearchSubject)==0){
                                    echo '<script>alert("Inconsistency found in database! User: '.$rowStud['Roll_number'].' has been allotted non-existing Subject: '.$rowStud['allottedChoice'].'");</script>';
                                }
                                else{
                                    $rowSearchSubject=mysqli_fetch_array($resultSearchSubject,MYSQLI_ASSOC);
                                    echo '<td>'.$rowSearchSubject['Subject_Name'].'</td>';
                                }
                            }
                    

                            echo '</tr>';                    
                        }

                        echo '</table></div>';
                    }
                
            }
        
        ?>
    </div>

</body>
</html>