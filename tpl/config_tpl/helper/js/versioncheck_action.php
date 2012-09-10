
// VERSIONCHECK
var localVersion = {
    major: <?=preg_replace('_\..*_','',self::VERSION)?>,
    minor: <?=preg_replace('_^.*?\.|\..*_','',self::VERSION)?>,
    sub: 2 /*<?=preg_replace('_^.*\._','',self::VERSION)?>*/
}

var checkChangeLog = function(event){
    event.preventDefault();
    $('.version-head').text('Getting data. please wait');
    $('.version-check-results').removeClass('warning').addClass('tiny-logo').find('*').remove();
    $.ajax({
        url: "/tinyAdmin/versioncheck",
        dataType: "json",
        type: "POST",
        data: { value: "history", maj: localVersion.major, min: localVersion.minor, sub: localVersion.sub },
        success: function(d,t,j){

            $('.version-head').text('Changelog');

            $('.version-check-results').append('<h3>What\'s new since v'+localVersion.major+"."+localVersion.minor+"."+localVersion.sub+'?</h3>');

            var addLogEntry = function(v,t)
            {
                $("<div></div>")
                    .css({
                        clear: 'both'
                    })
                    .append(
                        $("<div></div>").css({
                            width: "50px",
                            "text-align": "center",
                            float: "left"
                        }).text(v)
                    )
                    .append(
                        $("<div></div>").css({
                            "text-align": "left",
                            'margin-left': '80px',
                            'margin-right':'20px'
                        }).html(t)
                    )
                    .appendTo('.version-check-results')
            }

            addLogEntry('Version','Info');

            $('.version-check-results').find('div:first').css({'border-bottom':'1px solid #888','margin-right':"20px"});

            $.each(d,addLogEntry)
        }
    })
}

// Bind event handler to body
$('body').on('click','.version-check-results a.get-change-log', checkChangeLog );

$.ajax({
    url: "/tinyAdmin/versioncheck",
    dataType: "json",
    type: "POST",
    data: { value: "version" },
    success: function(remoteData,t,j){

        if ( localVersion.major == remoteData.current.major
          && localVersion.minor == remoteData.current.minor
          && localVersion.sub   == remoteData.current.sub
        ) {
            $('.version-head').text('Congrats');
            $('.version-text').remove();
            $('.version-check-results').addClass("tiny-extra-info").addClass('success').html(
                '<h3>Yay!</h3>'+
                '<p>Your version of tinyTpl is up to date.</p>'
            )
        } else {
            $('.version-head').text('Hmmm ...');
            $('.version-text').remove();
            $('.version-check-results').addClass("tiny-extra-info").addClass('warning').html(
                '<h3>Dude, look!</h3>'+
                '<p style="color:#400;font-weight:bold;">Your version of tinyTpl is not up to date.</p>'+
                '<p>The latest tinyTpl Version is <strong>'+
                    remoteData.current.major+"."+
                    remoteData.current.minor+"."+
                    remoteData.current.sub+
                '</strong></p>'+
                '<p><a class="get-change-log" href="#" style="color:#008;text-decoration:underline;">Click here</a>, to learn what has changed</p>'+
                '<p style="font-size:.8em">Take a peak at the project\'s <a href="https://github.com/derRaphael/tinytpl" style="color:#008;text-decoration:underline;">git repository</a>, and download a fresh copy if you want.</p>'
            )
        }
    }
})

