<?php
    $loggingHookEnabled = false;

    foreach( $this->_store as $ObjectIndex => $Object )
    {
        if ( is_a( $Object, 'tinyTpl\hooks\tinySimplePageLog' ) )
        {
            $logFacility = $this->_store->current();
            $loggingHookEnabled = true;
            break;
        }
    }
?>

<?php if ( $loggingHookEnabled == true ): ?>

<?=tpl('helper/js/stats_raphael_js')?>

// Invoke Menu Clicks
$('.stats_menu a.stats-toggle').click(function(e){
    e.preventDefault();

    $('.stats_menu a.stats-toggle').removeClass('enabled').addClass('disabled');
    var target = '#stats_' + $(this).data('name') + '_info_holder';
    $(".raphael_stats_holder").hide();
    $(this).removeClass('disabled').addClass('enabled');
    $(target).show();

});

$('.stats_menu a.stats-show-all').click(function(e){
    e.preventDefault();

    $('.stats_menu a.stats-toggle').removeClass('disabled').addClass('enabled');
    $(".raphael_stats_holder").show();

});

$('a.global-access-toggle').click(function(e){
    e.preventDefault();
    $.ajax({
        url: "/tinyAdmin/stats",
        type: "POST",
        dataType: "json",
        data: {
            value: "toggle"
        },
        success: function(d,t,j){
            $('p.global-toggle span').text(d[1]);
            $('p.global-toggle a').text(d[2]);
        }
    })
})

var _availableDates = <?=json_encode($logFacility->getValidDaysFolderList())?>;
window.availableDates = _availableDates;

var _beforeShowDayTester = function( date ){
    dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
    if ($.inArray(dmy, availableDates) != -1) {
        return [true,"",""];
    } else {
        return [false,"",""];
    }
};
window.beforeShowDayTester = _beforeShowDayTester;

$('.stats-select-date').click(function(e){
    e.preventDefault();
    $('#datepicker').datepicker(
        "dialog",
        new Date(<?=date("Y")?>, <?=date("n")-1?>, <?=date("j, G, i, s")?>),
        function(d,u){
            // Remove RaphaelJs Papers
            r_hits.remove();
            r_url.remove();
            r_os.remove();
            r_browser.remove();

            // Remove Holder
            $(".raphael_stats_holder").remove();

            $.ajax({
                url: "/tinyAdmin/stats",
                type: "POST",
                dataType: "script",
                data: {
                    value: "showDate",
                    d: d
                }
            });

        },
        {
            maxDate: new Date(<?=date("Y")?>, <?=date("n")-1?>, <?=date("j, G, i, s")?>),
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            beforeShowDay: beforeShowDayTester,
            showAnim: "fadeIn"
        }
    );
});

<?php endif; ?>