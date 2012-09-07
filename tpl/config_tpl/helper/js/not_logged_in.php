
$('.small-info.top button').button().click(function(){
    $.ajax({
        url: "/tinyAdmin/admin/login",
        type: "POST",
        dataType: "script",
        data: { value: "enter" }
    });
});