$(".raphael_stats_holder").show();

// Invoke HITS Stats Holder into DOM if neccessary
if ( $('div#stats_hits_info_holder').length == 0 )
{
    $("<div></div>").attr("id","stats_hits_info_holder").addClass("raphael_stats_holder").appendTo("#stats_holder");
}

// Invoke URLS Stats Holder into DOM if neccessary
if ( $('div#stats_urls_info_holder').length == 0 )
{
    $("<div></div>").attr("id","stats_urls_info_holder").addClass("raphael_stats_holder").appendTo("#stats_holder");
}

// Invoke OS Stats Holder into DOM if neccessary
if ( $('div#stats_os_info_holder').length == 0 )
{
    $("<div></div>").attr("id","stats_os_info_holder").addClass("raphael_stats_holder").appendTo("#stats_holder");
}

// Invoke BROWSER Stats Holder into DOM if neccessary
if ( $('div#stats_browser_info_holder').length == 0 )
{
    $("<div></div>").attr("id","stats_browser_info_holder").addClass("raphael_stats_holder").appendTo("#stats_holder");
}

// KickIn RaphaelJs
var pie_hover_in = function() {
        this.sector.stop();
        this.sector.scale(1.1, 1.1, this.cx, this.cy);

        if (this.label) {
            this.label[0].stop();
            this.label[0].attr({ r: 7.5 });
            this.label[1].attr({ "font-weight": 800 });
        }

    };

var pie_hover_out = function() {
        this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");

        if (this.label) {
            this.label[0].animate({ r: 5 }, 500, "bounce");
            this.label[1].attr({ "font-weight": 400 });
        }
    };

var r_hits = Raphael("stats_hits_info_holder"),
    r_hits_lc = r_hits.linechart(40, 100, 550, 350,
        [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],
        stats_hits_values, {
            nostroke: false, gutter: 3,
            axis: "0 0 1 1", axisxstep: 23,
            symbol: "circle", smooth: false,
            shade: true
    }).hoverColumn(function () {

        this.tags = r_hits.set();

        for (var i = 0, ii = this.y.length; i < ii; i++) { /* > */
            this.tags.push(
                r_hits
                    .tag(this.x, this.y[i], this.values[i], 160,10)
                    .insertBefore(this)
                    .attr([{ fill: "#000000" },{ fill: "#888888" }])
            );
        }
    }, function () {
        this.tags && this.tags.remove();
    });
    r_hits.text(320, 50, "Total Hits" + stats_hits_total).attr({ font: "20px sans-serif", fill: "#888888" });

    r_hits_lc.axis[0].attr({stroke:'#888888'});
    r_hits_lc.axis[1].attr({stroke:'#888888'});

    // Modify the x axis labels
    var xText = r_hits_lc.axis[0].text.items;

    for(var i in xText)
    {
        xText[i].attr({ fill: '#666666'});

    };

    var yText = r_hits_lc.axis[1].text.items;

    for(var i in yText)
    {
        yText[i].attr({fill: '#666666'});
    };

var r_url = Raphael("stats_urls_info_holder"),
    r_url_set = r_url.hbarchart(200, 80, 400, 400, [stats_url_values], {stacked: false, type: "soft"})
        .label([stats_url_labels2], true)
        .label([stats_url_labels], false);

    for(var i in r_url_set.labels)
    {
        if ( /\d+/.test(i) )
        {
            // Fix Textcolor and x positioning
            r_url_set.labels[i].attr({ fill: '#666666', x: 20 });
        }

    };
    r_url.text(320, 50, stats_url_info).attr({ font: "20px sans-serif", fill: "#888888" });


var r_os = Raphael("stats_os_info_holder"),
    pie_os = r_os.piechart(200, 260, 150, stats_os_values, { legend: stats_os_legend, legendpos: "east", legendcolor: '#888888'});

    r_os.text(320, 50, "OS usage" + stats_os_info).attr({ font: "20px sans-serif", fill: "#888888" });
    pie_os.hover(pie_hover_in, pie_hover_out);

var r_browser = Raphael("stats_browser_info_holder"),
    pie_browser = r_browser.piechart(200, 260, 150, stats_browser_values, { legend: stats_browser_legend, legendpos: "east", legendcolor: '#888888'});

    r_browser.text(320, 50, "Browser usage" + stats_browser_info).attr({ font: "20px sans-serif", fill: "#888888" });
    pie_browser.hover(pie_hover_in, pie_hover_out);

$(".raphael_stats_holder:not(:first)").hide();

// Clean up menu

$('.stats_menu')
    .find('a.stats-toggle').removeClass('enabled').addClass("disabled")
    .end()
    .find('a.stats-toggle:first').addClass("enabled").removeClass("diabled");
