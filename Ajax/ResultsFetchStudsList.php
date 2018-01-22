<?php
    session_start();
    if(!isset($_SESSION['userid']) ){
        header("location: ../login.php");
    }

    if( $_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['SubjID']) && isset($_POST['Sem']) ){
        require "../CommonFiles/connection.php";
        require "../CommonFiles/CommonConstants.php";

        $sqlSearchStuds = "select a.Roll_number,u.Name,Branch,ProfilePicLink from AC".$_POST['Sem']." a,User u where  a.allottedChoice=".$_POST['SubjID']." AND a.Roll_number = u.Roll_number";
        $resultSearchStuds = mysqli_query($conn,$sqlSearchStuds);
                
        //$view = mysqli_fetch_array($resultList,MYSQLI_ASSOC);
        if(mysqli_num_rows($resultSearchStuds)==0){
            echo "<h5>No student have been allotted for this Subject</h5>";
        }
        else{
            echo '<div style="margin: auto;overflow-x: scroll;padding: 20px;"><table class="myTable">';
            echo    '<tr class="myTableHeaderRow"><th></th><th>Roll Number</th><th>Name</th><th>Branch</th></tr>';

            while($rowStud = mysqli_fetch_array($resultSearchStuds,MYSQLI_ASSOC)){
                echo '<tr>';
                if($rowStud['ProfilePicLink']==NULL){
                    echo '<td><img src="images/UserProfilePix/avatar.png" style="border-radius:50%;width:50px;height:50px;"/></td>';
                }
                else{
                    echo '<td><img src="images/UserProfilePix/'.$rowStud['ProfilePicLink'].'" style="border-radius:50%;width:30px;height:30px;"/></td>';
                }
                
                echo '<td>'.$rowStud['Roll_number'].'</td>';
                echo '<td>'.$rowStud['Name'].'</td>';
                echo '<td>'.$rowStud['Branch'].'</td>';
                echo '</tr>';                    
            }
            echo '</table></div>';
        }
        
    }
    else{
        header("location: ../index.php");
    }

    

?>