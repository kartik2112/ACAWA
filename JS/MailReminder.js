
//Phase 1
var Phase1Temp1RNosList;
var Phase1Temp1totalusers;
var Phase1Temp1successCount = 0;
var Phase1Temp1failCount = 0;
var Phase1Temp1totalUsersCount = 0;
var Phase1Temp1alreadyRemindedCount = 0;

//Phase 2
var Phase2Temp1RNosList;
var Phase2Temp1totalusers;
var Phase2Temp1successCount = 0;
var Phase2Temp1failCount = 0;
var Phase2Temp1totalUsersCount = 0;
var Phase2Temp1alreadyRemindedCount = 0;

var Phase2Temp2RNosList;
var Phase2Temp2totalusers;
var Phase2Temp2successCount = 0;
var Phase2Temp2failCount = 0;
var Phase2Temp2totalUsersCount = 0;
var Phase2Temp2alreadyRemindedCount = 0;

//Phase 3
var Phase3Temp1RNosList;
var Phase3Temp1totalusers;
var Phase3Temp1successCount = 0;
var Phase3Temp1failCount = 0;
var Phase3Temp1totalUsersCount = 0;
var Phase3Temp1alreadyRemindedCount = 0;

var Phase3Temp2RNosList;
var Phase3Temp2totalusers;
var Phase3Temp2successCount = 0;
var Phase3Temp2failCount = 0;
var Phase3Temp2totalUsersCount = 0;
var Phase3Temp2alreadyRemindedCount = 0;

var Phase3Temp3RNosList;
var Phase3Temp3totalusers;
var Phase3Temp3successCount = 0;
var Phase3Temp3failCount = 0;
var Phase3Temp3totalUsersCount = 0;
var Phase3Temp3alreadyRemindedCount = 0;


$(document).ready(function () {
    $(".SendMailButton").click(function () {
        var templateNo = $(this).val();

        $(this).addClass("disabled");
        $(this).attr("disabled", "disabled");

        $(this).slideUp(500);
        $(this).remove();

        $.each(window[templateNo + 'RNosList'], function (key, value) {
            $.ajax({
                url: 'Ajax/MailReminderAJAX.php',
                type: "post",
                data: {
                    'receiverRno': value,
                    'templateID': templateNo,
                    'PostScript': $("#PS" + templateNo).val()
                },
                complete: function (result, status) {
                    //alert("Complete");
                }
            });
        });

        $("#MailProgressMainDiv" + templateNo).slideDown(500);
    });


    $(document).ajaxSuccess(function (event, xhr, options) {
        let responseJSON = JSON.parse(xhr.responseText);
        // var templateNo = xhr.responseText.substring(0, 11);
        var templateNo = responseJSON.templateID;
        // if (xhr.responseText.indexOf("RemindedAlready") != -1) {
        if ( responseJSON.status == "RemindedAlready" ) {
            window[templateNo + 'totalUsersCount']++;

            window[templateNo + 'alreadyRemindedCount']++;

            $("#UsersAlreadyReminded"+templateNo).html(window[templateNo + 'alreadyRemindedCount'] + "/" + window[templateNo + 'totalusers']);

            $("#ProgressBar" + templateNo).animate({ width: (window[templateNo + 'successCount'] + window[templateNo + 'alreadyRemindedCount']) / window[templateNo + 'totalusers'] * 100 + "%" }, 1000 / window[templateNo + 'totalusers'], function () {
                $("#ProgressBarStatusValue" + templateNo).text(((window[templateNo + 'successCount'] + window[templateNo + 'alreadyRemindedCount']) / window[templateNo + 'totalusers'] * 100).toFixed(2) + "%");
            });
        }
        // else if (xhr.responseText.indexOf("SuccessMail") != -1) {
        else if ( responseJSON.status == "SuccessMail" ) {
            window[templateNo + 'totalUsersCount']++;
            
            window[templateNo + 'successCount']++;

            $("#MailsSentYetValues" + templateNo).html(window[templateNo + 'successCount'] + "/" + window[templateNo + 'totalusers']);

            $("#ProgressBar" + templateNo).animate({ width: (window[templateNo + 'successCount'] + window[templateNo + 'alreadyRemindedCount']) / window[templateNo + 'totalusers'] * 100 + "%" }, 1000 / window[templateNo + 'totalusers'], function () {
                $("#ProgressBarStatusValue" + templateNo).text(((window[templateNo + 'successCount'] + window[templateNo + 'alreadyRemindedCount']) / window[templateNo + 'totalusers'] * 100).toFixed(2) + "%");
            });

        }
        // else if (xhr.responseText.indexOf("ErrorMail") != -1) {
        else if ( responseJSON.status == "ErrorMail" ) {
            window[templateNo + 'totalUsersCount']++;

            $("#MailSendHappeningDiv" + templateNo).html( responseJSON.errorDescription + $("#MailSendHappeningDiv" + templateNo).html());

            window[templateNo + 'failCount']++;

            $("#MailsNotSentValues" + templateNo).html(window[templateNo + 'failCount'] + "/" + window[templateNo + 'totalUsersCount']);

        }


        $("#UsersHandledYetValues" + templateNo).html(window[templateNo + 'totalUsersCount'] + "/" + window[templateNo + 'totalusers']);

        if (window[templateNo + 'totalUsersCount'] >= window[templateNo + 'totalusers']) {

            window.setTimeout(function () {
                $("#ProgressBarDiv" + templateNo).slideUp(500);
                $("#BeforeProgressBarDiv" + templateNo).slideUp(200);
                $("#MailSendStart" + templateNo).slideUp(200);
                $("#MailsHandled" + templateNo).slideDown(200);
                //window.clearInterval(displayvars);

            }, 1000);
            window.setTimeout(function () {
                alert(window[templateNo + 'totalUsersCount'] + " users' mails handled!");

            }, 2000);


        }
    });


});