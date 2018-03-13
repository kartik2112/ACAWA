function checkSyllFileExtension() {
    var fileExt = $("#syllFileAddName").val().split(".").pop();
    if (fileExt != 'doc' && fileExt != 'docx' && fileExt != 'pdf') {
        alert("Incorrect file type provided! Only doc, docx, pdf files are allowed!");
        $("#syllFileAddName").val("");
        $("#syllFileAdd").val("");
        
    }
    else {
        if ($("#SubjectModalAdd").val().length == 0) {
            $("#SubjectModalAddLabel").addClass("active");
            $("#SubjectModalAdd").val($("#syllFileAddName").val());
        }
    }
}

function checkThumbnailFileExtension() {
    var fileExt = $("#thumbnailFileAddName").val().split(".").pop();
    if (fileExt != 'png' && fileExt != 'gif' && fileExt != 'jpg' && fileExt != 'jpeg') {
        alert("Incorrect file type provided! Only jpg, jpeg, png, gif files are allowed!");
        $("#thumbnailFileAddName").val("");
        $("#thumbnailFileAdd").val("");
    }
}

function checkSyllFileAdded() {
    var fileExt = $("#syllFileChangeName").val().split(".").pop();
    if (fileExt != 'doc' && fileExt != 'docx' && fileExt != 'pdf') {
        alert("Incorrect file type provided! Only doc, docx, pdf files are allowed!");
        $("#syllFileChangeName").val("");
        $("#syllFileChange").val("");
        $("#deleteSyllFile").val("false");
    }
    else {
        $("#deleteSyllFile").val("true");
    }

}

function checkThumbnailFileAdded() {
    var fileExt = $("#thumbnailFileChangeName").val().split(".").pop();
    if (fileExt != 'png' && fileExt != 'gif' && fileExt != 'jpg' && fileExt != 'jpeg') {
        alert("Incorrect file type provided! Only jpg, jpeg, png, gif files are allowed!");
        $("#thumbnailFileChangeName").val("");
        $("#thumbnailFileChange").val("");
        $("#deleteThumbnail").val("false");
    }
    else {
        $("#deleteThumbnail").val("true");
    }

}

$(document).ready(function () {
    //$("#MenuModifySubjLI").addClass("active");
    $("#SideMenuModifySubjLI").addClass("active");
    $('.collapsible').collapsible({
        accordion: true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });


    function ValidateString() {
        var regex = new RegExp(/^[0-9a-zA-Z_ ]+$/);

        if (!regex.test(this.value)) {
            $("#" + this.name + "Invalid").text("This field can only have 0-9 a-z A-Z _ and spaces!");
            $(this).removeClass("valid");
            $(this).addClass("invalid");
        }
        else {
            $("#" + this.name + "Invalid").text("");
            $(this).removeClass("invalid");
            $(this).addClass("valid");
        }
    }


    $("table.myTable tr:not(.AddSubjectTR):not(.myTableHeaderRow)").click(function () {
        $(".Modal_Main_inputs").attr("disabled", "disabled");
        $("#ModifyModalButton").css("display", "block");
        $("#SaveModalButton").css("display", "none");
        $(".deleteOptionSpan").css("display", "none");
        $(".Modal_Main_inputs").val("");

        var subj_ID = this.getAttribute("name");
        $("#SubjIDModal").val(subj_ID);
        $("#SemesterModal").val($(this).data("semester"));
        $("#SubjectModal").val($("table.myTable tr[name=" + subj_ID + "] td.tdSubject").text());
        $("#TeacherModal").val($("table.myTable tr[name=" + subj_ID + "] td.tdTeacher").text());
        $("#CapacityModal").val($("table.myTable tr[name=" + subj_ID + "] td.tdCapacity").text());


        if ($("table.myTable tr[name=" + subj_ID + "] td.SyllFileTD a").html() == "DOWNLOAD") {
            /**
            * This branch will execute if File is present for this course
            */
            $("#SyllFileDivModal").html($("table.myTable tr[name=" + subj_ID + "] td.SyllFileTD").html());
            $("#SyllFileDivModalOuter").css("display", "inline-block");
            $("#NoSyllFileDisplayModal").css("display", "none");
            $("#SyllFileNameDivModal").html($("table.myTable tr[name=" + subj_ID + "] td.SyllFileTD a").attr("href").substr(22));  // "UploadedDocs/Syllabus/" is 22 characters long
        }
        else {
            $("#SyllFileDivModalOuter").css("display", "none");
            $("#NoSyllFileDisplayModal").css("display", "block");
        }

        $("#ThumbnailFileDivModal").html($("table.myTable tr[name=" + subj_ID + "] td.ThumbnailFileTD").html());
        $("#ThumbnailFileDivModal img").css("width", "200px");
        $("#ThumbnailFileDivModal img").css("height", "200px");


        $("#SubjectModalLabel").addClass("active");
        $("#TeacherModalLabel").addClass("active");
        $("#CapacityModalLabel").addClass("active");

        $("#ThumbnailFileDivModalOuter").css("display", "inline-block");
        $("#NoThumbnailFileDisplayModal").css("display", "none");
        $(".Modal_Main_inputs").attr("disabled", "disabled");
        $("#modal_update_delete").openModal();
    });

    $("table.myTable tr.AddSubjectTR").click(function () {
        var sem = this.getAttribute("name");
        $("#SemesterModalAdd").val(sem);
        $(".Modal_Main_inputs_Add").val("");
        $("#modal_add").openModal();
    });

    $("#ModifyModalButton").click(function () {
        $(".Modal_Main_inputs").removeAttr("disabled");
        $("#ModifyModalButton").css("display", "none");
        $("#SaveModalButton").css("display", "block");
        $(".deleteOptionSpan").fadeIn(500);
    });

    $("#CancelModalButton").click(function () {
        $("#modal_update_delete").closeModal();
        $(".Modal_Main_inputs").attr("disabled", "disabled");
        $("#ModifyModalButton").css("display", "block");
        $("#SaveModalButton").css("display", "none");
        $(".deleteOptionSpan").css("display", "none");
        $(".Modal_Main_inputs").val("");

    });

    $("#CancelAddModalButton").click(function () {
        $("#modal_add").closeModal();
        $(".Modal_Main_inputs_Add_Labels").removeClass("active");
        $(".Modal_Main_inputs_Add").val("");
    });

    $("#syllFileISpan").click(function () {
        //alert("Yo");
        $("#SyllFileDivModalOuter").slideUp(500);
        $("#NoSyllFileDisplayModal").slideDown(500);
    });

    $("#thumbnailFileISpan").click(function () {
        $("#ThumbnailFileDivModalOuter").slideUp(500);
        $("#NoThumbnailFileDisplayModal").slideDown(500);
    });


});