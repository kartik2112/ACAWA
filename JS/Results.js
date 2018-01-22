$(document).ready(function () {

    $(".requestViewStudList").click(function () {
        $("#PopupOutsideDiv").fadeIn(200);
        $(".container").addClass("BlurredBGForPopup");
        $(".navbar-fixed").addClass("BlurredBGForPopup");
        $.ajax({
            url: "Ajax/ResultsFetchStudsList.php",
            type: "post",
            data: {
                'SubjID': $(this).data("subj-id"),
                'Sem': $(this).data("sem")
            }
        });
    });

    $(document).ajaxSuccess(function (event, xhr, options) {
        window.setTimeout(function () {
            $("#PopupOutsideDiv").fadeOut(500,function(){
                $("#DisplayStudsListHere").html(xhr.responseText);
                $("#modalDisplayList").openModal({
                    complete: function(){
                        window.setTimeout(function () {
                            $(".container").removeClass("BlurredBGForPopup");
                            $(".navbar-fixed").removeClass("BlurredBGForPopup");
                        }, 500);
                    }
                });
            });
            
        },1500);
        
    });

    $("#closeModalDisplayList").click(function () {
        window.setTimeout(function () {
            $(".container").removeClass("BlurredBGForPopup");
            $(".navbar-fixed").removeClass("BlurredBGForPopup");
        }, 500);
    });

});