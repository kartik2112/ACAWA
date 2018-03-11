<?php
    session_start(); 
    
    if(!isset($_SESSION['userid']) || !isset($_SESSION['Alogin']) ){
        header("location: login.php");
    }

    if(!isset($_POST['templateID']) || !isset($_POST['receiverRno'])) header("location:../index.php");

    require '../CommonFiles/connection.php';  
    require '../CommonFiles/CommonConstants.php'; 
     
    $ImpDatesXMLDoc = new DOMDocument('1.0');
    $ImpDatesXMLDoc->load('../ImpFiles/ImpDatesXMLData.xml');
    $tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName("DeadlineBeginDate");
    $startDateFetched=$tempDataImpDatesXML ->item(0)->nodeValue;
    $tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName("DeadlineEndDate");
    $endDateFetched=$tempDataImpDatesXML ->item(0)->nodeValue;
    $tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName("SortingPerformed");
    $sortingPerformedStatus=$tempDataImpDatesXML ->item(0)->nodeValue;


    $sql_users="select u_name,Name,RemindedDate from User where Roll_number='".$_POST['receiverRno']."'";

    $result_users=mysqli_query($conn,$sql_users);
    if(mysqli_num_rows($result_users)==0){
        // die("Error");
        $returnJSON['receiverRno'] = $_POST['receiverRno'];
        $returnJSON['status'] = 'ErrorMail';
        $returnJSON['templateID'] = $_POST['templateID'];
        $returnJSON['errorDescription'] = "No such user is present";
        die(json_encode($returnJSON));
    }

    $row_users=mysqli_fetch_array($result_users,MYSQLI_ASSOC);

    if($_POST['templateID']!="Phase3Temp2" && $_POST['templateID']!="Phase3Temp3" && $_POST['templateID']!="Phase2Temp2" && strtotime($row_users['RemindedDate'])==strtotime(date("Y-m-d"))){
        // die($_POST['templateID']."RemindedAlready receiverRno:".$_POST['receiverRno']);  //." Name: ".$row_users['Name']
        $returnJSON['receiverRno'] = $_POST['receiverRno'];
        $returnJSON['status'] = 'RemindedAlready';
        $returnJSON['templateID'] = $_POST['templateID'];        
        die(json_encode($returnJSON));
    }


    $receiverNameInMail=$row_users['Name'];
    if(isset($_POST['PostScript'])){
        $PostScriptInMail=$_POST['PostScript'];
    }
    else{
        $PostScriptInMail="";
    }
    

    if( strtotime(date("Y-m-d")) < strtotime($startDateFetched) ){
        //Phase 1
        $phase=1;
        $insertDateInMail=date("F jS, Y",strtotime($startDateFetched));
    }
    else if( (strtotime($startDateFetched) <= strtotime(date("Y-m-d")) && ( strtotime(date("Y-m-d")) < strtotime($endDateFetched) ) ) ){
        //Phase 2
        $phase=2;
        $insertDateInMail=date("F jS, Y",strtotime($endDateFetched));
    }
    else{
        //Phase 3
        $phase=3;
    }

    require '../CommonFiles/MailTemplates.php';  
    

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .="From:ACAWA Admin \r\n";

    //$mailflag=mail($row_users['u_name']."@somaiya.edu",$subject[$_POST['templateID']],$template[$_POST['templateID']],$headers);
    $mailflag=TRUE;
    if($mailflag){
        // echo $_POST['templateID']."SuccessMail receiverRno:".$_POST['receiverRno'];
        $returnJSON['receiverRno'] = $_POST['receiverRno'];
        $returnJSON['status'] = 'SuccessMail';
        $returnJSON['templateID'] = $_POST['templateID'];
    }
    else{
        for($i=0;$i<RETRY_MAIL_LIMIT;$i++){
            //$mailflag=mail($row_users['u_name']."@somaiya.edu",$subject[$_POST['templateID']],$template[$_POST['templateID']],$headers);
            if($mailflag){
                break;
            }
        }
        if($mailflag){
            // echo $_POST['templateID']."SuccessMail receiverRno:".$_POST['receiverRno'];  //." Name: ".$row_users['Name']
            $returnJSON['receiverRno'] = $_POST['receiverRno'];
            $returnJSON['status'] = 'SuccessMail';
            $returnJSON['templateID'] = $_POST['templateID'];
        }
        else{
            // echo $_POST['templateID']."ErrorMail receiverRno:".$_POST['receiverRno'];
            $returnJSON['receiverRno'] = $_POST['receiverRno'];
            $returnJSON['status'] = 'ErrorMail';
            $returnJSON['templateID'] = $_POST['templateID'];
            $returnJSON['errorDescription'] = "Could not connect to SMTP Server";
        }
                
    }


    if($mailflag){
        $sql_set_remind="update User set RemindedDate='".date("Y-m-d")."' where Roll_number='".$_POST['receiverRno']."'";
        if(!mysqli_query($conn,$sql_set_remind)){
            // echo "Error while asserting user has been reminded ".mysqli_error($conn);
            $returnJSON['receiverRno'] = $_POST['receiverRno'];
            $returnJSON['status'] = 'ErrorMail';
            $returnJSON['templateID'] = $_POST['templateID'];
            $returnJSON['errorDescription'] = "Error while asserting user has been reminded ".mysqli_error($conn);
        }

    }

    echo json_encode($returnJSON);
                
?>
        