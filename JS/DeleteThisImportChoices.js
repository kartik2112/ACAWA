function VerifyNUploadThisFile() {
    if (!$("#ExcelFileUpload").prop("files")[0]) {
        alert("No files selected for upload");
        return false;
    }
            
}

/**
* Checks the file extension to verify that it is an excel file
* Currently only support for xls and xlsx is added
*/
function VerifyFileType() {
    var f = event.target.files[0];
    if (!f.name.endsWith("xlsx") && !f.name.endsWith("xls")) {
        alert("Incorrect file type!");
        this.value = null;
        $("#ExcelFileUpload").val(null);
    }
}


/**
* These variables are defined for Choices to be uploaded
*/
var ChoicesToBeAdded;
var noOfChoices;
var successChoicesCount = 0;
var errorChoicesCount = 0;
var totalChoicesHandled = 0;
var alreadyExistsChoicesCount = 0;
var ChoicesUploadStartedFlag = false;
var ChoicesUploadCompletedFlag = false;

function startChoicesUpload() {
    $("#StartChoicesAddButton").attr("disabled", "disabled");
    $("#StartChoicesAddButton").fadeOut(200);

    $(".TotalNoOfChoicesAdd").text(noOfChoices);

    $("#ChoicesUploadOuterDiv").fadeIn(200);

    ChoicesUploadStartedFlag = true;

    for (var i = 2; i <= noOfChoices+1; i++) {
        $.ajax({
            url: "Ajax/DeleteThisImportChoicesAJAX.php",
            type: "post",
            data: { 'ChoiceData': ChoicesToBeAdded[i] }            
        });
    }
    $('html, body').animate({
        scrollTop: ($("#ChoicesUploadOuterDiv").offset().top - 60)
    }, 1000);

}




$(document).ready(function () {
    $(document).ajaxSuccess(function (event, xhr, options) {
        
        //alert(xhr.responseText);
        if (xhr.responseText.indexOf("ChoiceSuccess") != -1) {
            successChoicesCount++;
            totalChoicesHandled++;
            $("#ChoiceProgressBar").animate({ width: (successChoicesCount+alreadyExistsChoicesCount) / noOfChoices * 100 + "%" }, 1000 / noOfChoices, function () {
                $("#ChoicesProgressBarStatusValue").text(((successChoicesCount+alreadyExistsChoicesCount) / noOfChoices * 100).toFixed(2) + "%");
            });
            $("#ChoicesAddSuccessNo").text(successChoicesCount);
        }
        else if (xhr.responseText.indexOf("ChoiceError") != -1) {
            errorChoicesCount++;
            totalChoicesHandled++;
            $("#ChoicesFailNo").text(errorChoicesCount);
            $("#ChoicesAddErrorStatus").html($("#ChoicesAddErrorStatus").html() + xhr.responseText.substring(11));
        }
        else if (xhr.responseText.indexOf("ChoiceAlreadyExisting") != -1) {
            alreadyExistsChoicesCount++;
            totalChoicesHandled++;            
            $("#ChoiceProgressBar").animate({ width: (successChoicesCount+alreadyExistsChoicesCount) / noOfChoices * 100 + "%" }, 1000 / noOfChoices, function () {
                $("#ChoicesProgressBarStatusValue").text(((successChoicesCount+alreadyExistsChoicesCount) / noOfChoices * 100).toFixed(2) + "%");
            });
            $("#ChoicesAlreadyExistsNo").text(alreadyExistsChoicesCount);
        }
        
        if (ChoicesUploadCompletedFlag==false && totalChoicesHandled >= noOfChoices) {
            ChoicesUploadCompletedFlag = true;
            window.setTimeout(function () {
                $("#ChoiceProgressDetails").slideUp(500);                
            }, 1000);
            window.setTimeout(function () {
                alert(successChoicesCount + " Choices added successfully!");
            }, 2000);
        }

    });
});


window.onbeforeunload = function (e) {
    if( ( ChoicesUploadStartedFlag==true && ChoicesUploadCompletedFlag==false ) ){
        return "Please click 'Stay on this Page' if you did this unintentionally";
    }
};
