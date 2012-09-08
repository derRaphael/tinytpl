
$('.small-info.top button').button().click(function(){
    $.ajax({
        url: "/tinyAdmin/admin/logout",
        type: "POST",
        dataType: "script",
        data: { value: "leave" }
    });
});