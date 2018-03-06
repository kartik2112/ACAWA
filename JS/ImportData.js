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
* These variables are defined for Courses to be uploaded
*/
var CoursesToBeAdded;
var noOfCourses;
var successCoursesCount = 0;
var errorCoursesCount = 0;
var totalCoursesHandled = 0;
var alreadyExistsCoursesCount = 0;
var coursesUploadStartedFlag = false;
var coursesUploadCompletedFlag = false;

function startCoursesUpload() {
    $("#StartCoursesAddButton").attr("disabled", "disabled");
    $("#StartCoursesAddButton").fadeOut(200);

    $(".TotalNoOfCoursesAdd").text(noOfCourses);

    $("#CoursesUploadOuterDiv").fadeIn(200);

    coursesUploadStartedFlag = true;

    for (var i = 2; i <= noOfCourses+1; i++) {
        $.ajax({
            url: "Ajax/ImportCourseData.php",
            type: "post",
            data: { 'CourseData': CoursesToBeAdded[i], 'RowNo': i }            
        });
    }
    $('html, body').animate({
        scrollTop: ($("#CoursesUploadOuterDiv").offset().top - 60)
    }, 1000);

}


/**
* These variables are defined for User details to be uploaded
*/
var UsersToBeAdded;
var noOfUsers;
var successUsersCount = 0;
var errorUsersCount = 0;
var totalUsersHandled = 0;
var alreadyExistsUsersCount = 0;
var usersUploadStartedFlag = false;
var usersUploadCompletedFlag = false;

function startUsersUpload() {
    $("#StartUsersAddButton").attr("disabled", "disabled");
    $("#StartUsersAddButton").fadeOut(200);

    $(".TotalNoOfUsersAdd").text(noOfUsers);

    $("#UsersUploadOuterDiv").fadeIn(200);

    usersUploadStartedFlag = true;

    for (var i = 2; i <= noOfUsers+1; i++) {
        $.ajax({
            url: "Ajax/ImportUsersData.php",
            type: "post",
            data: { 'UserData': UsersToBeAdded[i], 'RowNo': i }            
        });
    }
    $('html, body').animate({
        scrollTop: ($("#UsersUploadOuterDiv").offset().top - 60)
    }, 1000);

}




$(document).ready(function () {
    $(document).ajaxSuccess(function (event, xhr, options) {
        
        let responseJSON = JSON.parse(xhr.responseText);
        //alert(xhr.responseText);
        // if (xhr.responseText.indexOf("CourseSuccess") != -1) {
        if ( responseJSON.status == "CourseSuccess" ) {
            successCoursesCount++;
            totalCoursesHandled++;
            $("#CourseProgressBar").animate({ width: (successCoursesCount+alreadyExistsCoursesCount) / noOfCourses * 100 + "%" }, 1000 / noOfCourses, function () {
                $("#CoursesProgressBarStatusValue").text(((successCoursesCount+alreadyExistsCoursesCount) / noOfCourses * 100).toFixed(2) + "%");
            });
            $("#CoursesAddSuccessNo").text(successCoursesCount);
        }
        // else if (xhr.responseText.indexOf("CourseError") != -1) {
        else if ( responseJSON.status == "CourseError" ) {
            errorCoursesCount++;
            totalCoursesHandled++;
            $("#CoursesFailNo").text(errorCoursesCount);
            $("#CoursesAddErrorStatus").html($("#CoursesAddErrorStatus").html() + "Row No " + responseJSON.RowNo + " - " + responseJSON.errorDescription);
        }
        // else if (xhr.responseText.indexOf("CourseAlreadyExisting") != -1) {
        else if ( responseJSON.status == "CourseAlreadyExisting" ) {
            alreadyExistsCoursesCount++;
            totalCoursesHandled++;            
            $("#CourseProgressBar").animate({ width: (successCoursesCount+alreadyExistsCoursesCount) / noOfCourses * 100 + "%" }, 1000 / noOfCourses, function () {
                $("#CoursesProgressBarStatusValue").text(((successCoursesCount+alreadyExistsCoursesCount) / noOfCourses * 100).toFixed(2) + "%");
            });
            $("#CoursesAlreadyExistsNo").text(alreadyExistsCoursesCount);
            // $("#CoursesAddErrorStatus").html($("#CoursesAddErrorStatus").html() + xhr.responseText.substring(21));
            $("#CoursesAddErrorStatus").html($("#CoursesAddErrorStatus").html() + "Row No " + responseJSON.RowNo + " - " + responseJSON.errorDescription);
        }


        // else if (xhr.responseText.indexOf("UserSuccess") != -1) {
        else if ( responseJSON.status == "UserSuccess" ) {
            successUsersCount++;
            totalUsersHandled++;
            $("#UsersProgressBar").animate({ width: (successUsersCount+alreadyExistsUsersCount) / noOfUsers * 100 + "%" }, 1000 / noOfUsers, function () {
                $("#UsersProgressBarStatusValue").text(((successUsersCount+alreadyExistsUsersCount) / noOfUsers * 100).toFixed(2) + "%");
            });
            $("#UsersAddSuccessNo").text(successUsersCount);
        }
        // else if (xhr.responseText.indexOf("UserError") != -1) {
        else if ( responseJSON.status == "UserError" ) {
            errorUsersCount++;
            totalUsersHandled++;
            $("#UsersFailNo").text(errorUsersCount);
            // $("#UsersAddErrorStatus").html($("#UsersAddErrorStatus").html() + xhr.responseText.substring(9));
            $("#UsersAddErrorStatus").html($("#UsersAddErrorStatus").html() + "Row No " + responseJSON.RowNo + " - " + responseJSON.errorDescription);
        }
        // else if (xhr.responseText.indexOf("UserAlreadyExisting") != -1) {
        else if ( responseJSON.status == "UserAlreadyExisting" ) {
            alreadyExistsUsersCount++;
            totalUsersHandled++;
            $("#UsersProgressBar").animate({ width: (successUsersCount+alreadyExistsUsersCount) / noOfUsers * 100 + "%" }, 1000 / noOfUsers, function () {
                $("#UsersProgressBarStatusValue").text(((successUsersCount+alreadyExistsUsersCount) / noOfUsers * 100).toFixed(2) + "%");
            });
            $("#UsersAlreadyExistsNo").text(alreadyExistsUsersCount);
            //$("#UsersAddErrorStatus").html($("#UsersAddErrorStatus").html() + xhr.responseText.substring(19));
            $("#UsersAddErrorStatus").html($("#UsersAddErrorStatus").html() + "Row No " + responseJSON.RowNo + " - " + responseJSON.errorDescription);
        }
        
        if (coursesUploadCompletedFlag==false && totalCoursesHandled >= noOfCourses) {
            coursesUploadCompletedFlag = true;
            window.setTimeout(function () {
                $("#CourseProgressDetails").slideUp(500);                
            }, 1000);
            window.setTimeout(function () {
                alert(successCoursesCount + " courses added successfully!");
            }, 2000);
        }


        if (usersUploadCompletedFlag==false && totalUsersHandled >= noOfUsers) {
            usersUploadCompletedFlag = true;
            window.setTimeout(function () {
                $("#UsersProgressDetails").slideUp(500);                
            }, 1000);
            window.setTimeout(function () {
                alert(successUsersCount + " users' details added successfully!");
            }, 2000);
        }
    });
});


window.onbeforeunload = function (e) {
    if( ( coursesUploadStartedFlag==true && coursesUploadCompletedFlag==false ) || ( usersUploadStartedFlag==true && usersUploadCompletedFlag==false ) ){
        return "Please click 'Stay on this Page' if you did this unintentionally";
    }
};
