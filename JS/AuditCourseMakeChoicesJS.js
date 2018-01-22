/**
* This function first disables the select elements to prevent any errors,
* then all the options are enabled.
* After this, one by one each select element is checked and the selected option is deleted from other select elements
*/
function RemoveSame(elem) {
    $("#Modal1" + elem.name).html(elem.getAttribute("data-subj-name"));

    $("select").attr("disabled", "disabled"); //legacy select
    $("ul.select-dropdown").addClass("disabled"); //materialized select


    $("ul.select-dropdown>li").css("display", "block");     //materialized select options


    //materialized select options values check and eliminate corresponding options in other select options
    var elements = document.querySelectorAll("ul.select-dropdown>li.active");
    for (var i = 0; i < elements.length; i++) {
        $("ul.select-dropdown>li[name=" + elements[i].getAttribute("name") + "]:not(.selected)").css("display", "none");
    }



    $("ul.select-dropdown").removeClass("disabled"); //materialized select
    $("select").removeAttr("disabled");
}

/**
* This function will unselect all options in all select elements.
* After this, only the default options are selected.
*/
function ResetAll() {
    //legacy select options reset value
    $("option").removeAttr("selected");
    $("option").css("display", "block");
    $("option.noneSelected").attr("selected", "selected");



    //materialized select options reset value
    $("ul.select-dropdown>li").css("display", "block");
    $("ul.select-dropdown>li").removeClass("active");
    $("ul.select-dropdown>li").removeClass("selected");
    $("ul.select-dropdown>li[value=noneSelected]").addClass("selected");
    $("ul.select-dropdown>li[value=noneSelected]").addClass("active");
    $("input.select-dropdown").attr("value", "Choose an audit course");

    $('select').prop('selectedIndex', 0);
    $('select').material_select();
}


/**
* This function will check if all of the choices are selected.
* If any of the select element has the default option selected, 
* the form won't submit.
*/
function checkAllChoices() {
    var incompleteFlag = true;
    var elements = document.querySelectorAll("select.choice_select");
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].value == "noneSelected") {
            incompleteFlag = false;
            break;
            //Add class to this field
        }
    }
    if (incompleteFlag == false) {
        alert("Please select all choices");
        return false;
    }
    else {

        var elements = document.querySelectorAll("ul.select-dropdown>li.active>span");
        for (var i = 0; i < elements.length; i++) {    
        //alert(elements[i].innerHTML);                    
            $("#Modal1Choice" + (i + 1)).html((i + 1)+". "+elements[i].innerHTML);
        }

        $('#modal1').openModal();
    }

}