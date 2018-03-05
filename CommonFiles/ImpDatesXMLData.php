<?php
$ImpDatesXMLDoc = new DOMDocument('1.0');
$ImpDatesXMLDoc->load('ImpFiles/ImpDatesXMLData.xml');
$tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName("DeadlineBeginDate");
// echo count($tempDataImpDatesXML);
$startDateFetched=$tempDataImpDatesXML ->item(0)->nodeValue;
$tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName("DeadlineEndDate");
$endDateFetched=$tempDataImpDatesXML ->item(0)->nodeValue;
$tempDataImpDatesXML = $ImpDatesXMLDoc->getElementsByTagName("SortingPerformed");
$sortingPerformedStatus=$tempDataImpDatesXML ->item(0)->nodeValue;
?>
