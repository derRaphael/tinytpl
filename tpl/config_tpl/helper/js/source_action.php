$( "ul.file-list a" ).click(function(e){
    e.preventDefault();
    var n = $.trim($(this).text());

    $.ajax({
        url: "/tinyAdmin/admin/source",
        type: "POST",
        dataType: "html",
        success: function(d,t,j){
            $('div.source-container').show();
            $('.source-name').text(n.replace(/.*\//,''));
            $('div.source').html(d)
        },
        data: { value: "source", n: n }
    });
});

$('.source-file-filter input[name=filter]').bind({
    keyup: function(){

        var term = $('.source-file-filter input[name=filter]').val(),
            matcher = new RegExp( $.ui.autocomplete.escapeRegex(term), "i" );

        $( "ul.file-list li" ).each(function(i,e){
            var filename = $(e).text();
            if ( filename != "" && ( term == "" || matcher.test(filename) ) )
            {
                $(e).show();
            } else {
                $(e).hide();
            }
        });

    }
});

$('.source-hide-source').click(function(e){
    e.preventDefault();
    $('.source-container').hide();
    $('.source-container .source').html("");
    $('.source-name').text("");
}).trigger("click");