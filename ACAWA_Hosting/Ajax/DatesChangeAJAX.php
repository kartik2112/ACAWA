<?php
    $errorMessage="";
    $tempDoc = new DOMDocument();
    $tempDoc->load('../ImpFiles/ImpDatesXMLData.xml');
    $tempData = $tempDoc->getElementsByTagName( "DeadlineBeginDate" );
    $startDateFetched=$tempData ->item(0)->nodeValue;
    $tempData = $tempDoc->getElementsByTagName( "DeadlineEndDate" );
    $endDateFetched=$tempData ->item(0)->nodeValue;
    $tempData = $tempDoc->getElementsByTagName( "Key" );
    $Key=$tempData ->item(0)->nodeValue;
    if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['action']) ){
        if((md5($_POST['Key'])==$Key)){
            $_POST['DeadlineStartDate']=str_replace(",","",$_POST['DeadlineStartDate']);
            $_POST['DeadlineEndDate']=str_replace(",","",$_POST['DeadlineEndDate']);
            $tempStartDateFetched=date("Y-m-d",strtotime($_POST['DeadlineStartDate']));
            $tempEndDateFetched=date("Y-m-d",strtotime($_POST['DeadlineEndDate']));
            if($tempStartDateFetched < $tempEndDateFetched){            
                $tempData = $tempDoc->getElementsByTagName( "DeadlineBeginDate" );
                $tempData ->item(0)->nodeValue=$tempStartDateFetched;
                $tempData = $tempDoc->getElementsByTagName( "DeadlineEndDate" );
                $tempData ->item(0)->nodeValue=$tempEndDateFetched;
                $tempData = $tempDoc->getElementsByTagName( "DateModified" );
                $tempData ->item(0)->nodeValue=date("Y-m-d");
                $tempData = $tempDoc->getElementsByTagName( "SortingPerformed" );
                $tempData ->item(0)->nodeValue="FALSE";
                $tempDoc->save('../ImpFiles/ImpDatesXMLData.xml');
                $tempData = $tempDoc->getElementsByTagName( "DeadlineBeginDate" );
                $startDateFetched=$tempData ->item(0)->nodeValue;
                $tempData = $tempDoc->getElementsByTagName( "DeadlineEndDate" );
                $endDateFetched=$tempData ->item(0)->nodeValue;
            }
            else{
                $errorMessage.="Incorrect dates provided! <br/>Submission start date should be before deadline date.<seperator>";
            }
        }
        else{
            $errorMessage.="Incorrect Admin Key provided! <br/>Cannot update dates.<seperator>";
        }
        if(isset($errorMessage) && $errorMessage != ""){
            $returnJSON['status'] = "Error";
            $returnJSON['errorDescription'] = $errorMessage;
            $returnJSON['errorCode'] = "IncorrectKey";
            echo json_encode($returnJSON);
        }
        else{
            $returnJSON['status'] = "Success";
            echo json_encode($returnJSON);
        }
    }
    else{
        $returnJSON['status'] = "Error";
        $returnJSON['errorDescription'] = "Ill-formed request sent";
        $returnJSON['errorCode'] = "IllRequest";
        echo json_encode($returnJSON);
    }
?>