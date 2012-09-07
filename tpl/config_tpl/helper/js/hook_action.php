
var hookToggle = function( hookName ){

        if ( typeof hookName == "undefined" )
        {
            return;
        } else {
            hookName = '.hook-' + hookName;
        }

        if ( $(hookName).eq(0).hasClass('hook-enabled') )
        {
            $(hookName)
                .removeClass('hook-enabled')
                .addClass('hook-disabled');

            $("td", hookName).stop(true,true).animate({
                    'background-color':'#ffeedd',
                    'color':'#880000'
                },200,
                function(){
                    $('th:last div', hookName).removeClass("enabled").addClass("disabled");
                    $('td.status', hookName).html("disabled");
                }
            );

        } else {
            $(hookName)
                .removeClass('hook-disabled')
                .addClass('hook-enabled')

            $("td", hookName).stop(true,true).animate({
                    'background-color':'#ccffdd',
                    'color':'#008800'
                },200,
                function(){
                    $('th:last div', hookName).removeClass("disabled").addClass("enabled");
                    $('td.status', hookName).html("enabled");
                }
            );
        }
    },
    hookToggleClick = function(){

        var hookName = $(this).data("id");

        $('.tiny-extra-info').trigger('click');

        $.ajax({
            url: "/tinyAdmin/admin/hooks",
            type: "POST",
            dataType: "script",
            data: { value: hookName }
        });

    };

$('table.hookinfo tr.hook').each(function(){

    var $hookinfo = $(this).next("tr.hookinfo").find("td");
    $hookinfo.find("span.x-info").appendTo($('td.info',this));
    $hookinfo.find("span.x-author").appendTo($('td.author',this));
    $hookinfo.find("span.x-version").appendTo($('td.version',this));
    $hookinfo.find("span.x-licence").appendTo($('td.licence',this));
    $hookinfo.parent("tr").show();

});

$('table.hookinfo tbody tr').click(hookToggleClick);

$('body').on('click','.tiny-extra-info',function(){
    $('.tiny-extra-info').stop(true,true).animate({opacity:0,height:0},500,function(){$(this).remove()});
})


window.hookToggle = hookToggle;
window.hookToggleClick = hookToggleClick;