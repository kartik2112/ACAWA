//Using these 2 variables from AdminHome.php
// var startDateFetchedStr = '<?php echo $startDateFetched; ?>';
// var endDateFetchedStr = '<?php echo $endDateFetched; ?>';
// var $pickerStart,$pickerEnd;


$(document).ready(function () {
    //XML interaction part
    var deadlineStartIP = $('#DeadlineStartDate').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 3, // Creates a dropdown of 15 years to control year
        onSet: function(thingSet) {
            if(typeof(thingSet.select) != undefined ){
                console.log('Set stuff Start:',thingSet);

                var startDate = new Date(thingSet.select);
                console.log('Set stuff Start:',startDate);

                // var startDate = new Date($("#DeadlineStartDate").val());
                var endDate = new Date($("#DeadlineEndDate").val());
                if (startDate >= endDate) {
                    startDate.setDate(endDate.getDate() - 1);
                    // $pickerStart.set('select', startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate(), { format: 'yyyy-mm-dd' });
                    console.log(startDate.getTime());
                    $pickerStart.set('select', startDate.getTime());

                    alert("Cannot set submission start date after deadline date!");
                }
                else {
                    $("#Key").removeAttr("disabled");
                    $("#KeyOuter").css("display", "block");
                    $('#UpdateDate').removeClass('disabled');
                    $('#UpdateDate').removeAttr('disabled');
                }
            }
        }
    });
    var deadlineEndIP = $('#DeadlineEndDate').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 3, // Creates a dropdown of 15 years to control year
        onSet: function(thingSet) {
            if(typeof(thingSet.select) != undefined ){
                console.log('Set stuff End:',thingSet);
                
                var endDate = new Date(thingSet.select);
                console.log('Set stuff End:',endDate);

                var startDate = new Date($("#DeadlineStartDate").val());
                // var endDate = new Date($("#DeadlineEndDate").val());
                if (startDate >= endDate) {
                    endDate.setDate(startDate.getDate() + 1);
                    // $pickerEnd.set('select', endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate(), { format: 'yyyy-mm-dd' });
                    console.log(endDate.getTime());
                    $pickerEnd.set('select', endDate.getTime());

                    alert("Cannot set submission start date after deadline date!");
                }
                else {
                    $("#Key").removeAttr("disabled");
                    $("#KeyOuter").css("display", "block");
                    $('#UpdateDate').removeClass('disabled');
                    $('#UpdateDate').removeAttr('disabled');
                }
            }
        }
    });
    $pickerStart = deadlineStartIP.pickadate('picker');
    $pickerEnd = deadlineEndIP.pickadate('picker');

    $pickerStart.set('select', new Date(startDateFetchedStr).getTime());
    $pickerEnd.set('select', new Date(endDateFetchedStr).getTime());
    resetDatesChangeForm();


    console.log($pickerStart);

    // $("#DeadlineStartDate").change(function () {
    // $($pickerStart).change(function () {
    //     var startDate = new Date($("#DeadlineStartDate").val());
    //     var endDate = new Date($("#DeadlineEndDate").val());
    //     if (startDate >= endDate) {
    //         startDate.setDate(endDate.getDate() - 1);
    //         $pickerStart.set('select', startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate(), { format: 'yyyy-mm-dd' });

    //         alert("Cannot set submission start date after deadline date!");
    //     }
    //     else {
    //         $("#Key").removeAttr("disabled");
    //         $("#KeyOuter").css("display", "block");
    //         $('#UpdateDate').removeClass('disabled');
    //         $('#UpdateDate').removeAttr('disabled');
    //     }

    // });


    // $("#DeadlineEndDate").change(function () {
    // $($pickerEnd).change(function () {
    //     var startDate = new Date($("#DeadlineStartDate").val());
    //     var endDate = new Date($("#DeadlineEndDate").val());
    //     if (startDate >= endDate) {
    //         endDate.setDate(startDate.getDate() + 1);
    //         $pickerEnd.set('select', endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate(), { format: 'yyyy-mm-dd' });

    //         alert("Cannot set deadline date before submission start date!");
    //     }
    //     else {
    //         $("#Key").removeAttr("disabled");
    //         $("#KeyOuter").css("display", "block");
    //         $('#UpdateDate').removeClass('disabled');
    //         $('#UpdateDate').removeAttr('disabled');
    //     }

    // });

    
});



function updateDatesByAJAX(){
    $.ajax({
        url: 'Ajax/DatesChangeAJAX.php',
        method: 'POST',
        data: {
            "DeadlineStartDate": $("#DeadlineStartDate").val(),
            "DeadlineEndDate": $("#DeadlineEndDate").val(),
            "Key": $("#Key").val(),
            "action":""
        },
        success: function(response){
            response = JSON.parse(response);
            if(response.status == "Success"){
                resetDatesChangeForm();
                $pickerStart.close();
                $pickerEnd.close();
                Materialize.toast("Dates updated successfully", 4000);
            }
            else if(response.status == "Error"){
                Materialize.toast(response.errorDescription, 7000);
                if(response.errorCode == "IncorrectKey"){
                    $("#Key").addClass("invalid");
                }
            }
        },
        error: function(xhr,status,response){
            Materialize.toast("Dates could not be updated. Server resulted status: "+status, 7000);
        }
    });
}

function resetDatesChangeForm(){
    $("#Key").attr("disabled");
    $("#KeyOuter").css("display", "none");
    $('#UpdateDate').addClass('disabled');
    $('#UpdateDate').attr('disabled');
    $("#Key").val("");
    $("#Key").removeClass("invalid");
}