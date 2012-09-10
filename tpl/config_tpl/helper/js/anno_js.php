
<script type="text/javascript">

    /**
     * Create a ref to global available Annotations
    **/
    var allPageAnnotations = <?=json_encode( $TINY->DATA['ANNOTATIONS'] )?>;

    // The copy is used to find out if we had a change,
    // since a click on body's document triggers server update
    var allPageAnnotationsCopy = <?=json_encode( $TINY->DATA['ANNOTATIONS'] )?>;

    // The pageId (base64 encoded)
    var annotationPageId = "<?=$TINY->DATA['ANNOTATION']['PAGEID']?>";

    /**
     * At present tinyPageAnnotations relies on jQuery and jQuery UI
     * If it isn't present, tinyPageAnnotations simply refuses to
     * work.
    **/
    if ( window.jQuery && jQuery.ui )
    {
        if ( ! jQuery.uID )
        {
            /**
             * Extend jQuery with a uID function to return
             * a string containing a unique id
             *
             * Example: var random_id = jQuery.uID();
             *
             * @uses jQuery
             *
             * @return string
             */
            jQuery.extend({
                uID : function( prefix ){

                        var prefix = prefix || "id-";

                        var a = Math.floor(
                                    Math.random() * 0x100000
                                ).toString(16),
                            b = (new Date()).getMilliseconds().toString(16),
                            c = (new Date()).getTime().toString(16),
                            d = String(a+""+b+""+c);

                    return prefix + d.slice(0,10);
                }
            })
        }

        /**
         * Create designmode extension
         *
         * http://code.google.com/p/wikify/source/browse/trunk/v2/js/jquery.designmode.js?spec=svn294&r=294
         *
         * designMode jQuery plugin v0.1, by Emil Konow.
         * This plugin allows you to handle functionality related to designMode in a cross-browser way.
         */

        if ( ! jQuery.fn.contentDocument || ! jQuery.fn.designMode || ! jQuery.fn.execCommand )
        {
            jQuery.fn.extend({
                /**
                 * Cross-browser function to access a DOM:Document element
                 * Example: jQuery('#foo').contentDocument();
                 *
                 * @uses jQuery
                 *
                 * @return object DOM:Document - the document of the frame, iframe or window
                 */
                contentDocument: function() {
                    var frame = this[0];
                    if (frame.contentDocument) {
                        return frame.contentDocument;
                    } else if (frame.contentWindow && frame.contentWindow.document) {
                        return frame.contentWindow.document;
                    } else if (frame.document) {
                        return frame.document;
                    } else {
                        return null;
                    }
                },

                /**
                 * Cross-browser function to set the designMode property
                 * Example: jQuery('#foo').designMode('on');
                 *
                 * @uses jQuery, jQuery.fn.contentDocument
                 *
                 * @param string mode - Which mode to use, should either 'on' or 'off'
                 *
                 * @return jQuery element - The jQuery element itself, to allow chaining
                 */
                designMode: function(mode) {

                    // Default mode is 'on'
                    var mode = mode || 'on';
                    this.each(function() {
                        var frame = $(this);
                        var doc = frame.contentDocument();
                        if (doc) {
                            doc.designMode = mode;
                            // Some browsers are kinda slow, so you'll have to wait for the window to load
                            frame.load(function() {
                                $(this).contentDocument().designMode = mode;
                            });
                        }
                    });
                    return this;
                },

                /**
                 * Cross-browser function to execute designMode commands
                 * Example: jQuery('#foo').execCommand('formatblock', '<p>');
                 *
                 * @uses jQuery, jQuery.fn.contentDocument
                 *
                 * @param string cmd - The command to execute. Please see http://www.mozilla.org/editor/midas-spec.html
                 * @param string param - Optional parameter, required by some commands
                 *
                 * @return jQuery element - The jQuery element itself, to allow chaining
                 */
                execCommand: function(cmd, param) {
                    this.each(function() {
                        var doc = $(this).contentDocument();
                        if (doc) {
                            // Use try-catch in case of invalid or unsupported commands
                            try {
                                // Non-IE-browsers requires all three arguments
                                doc.execCommand(cmd, false, param);
                            } catch (e) {
                            }
                        }
                    });
                    return this;
                }
            })
        }

        /**
         * Global placeholder for tinyPageAnnotation JS Part
        **/
        var tinyTplPageAnnotations = {
            btn: {
                css: {
                    width: "24px",
                    height: "24px",
                    position: "absolute",
                    opacity: .5,
                    cursor: "pointer"
                },
                animation: {
                    mouseenter: function(){
                        $(this).stop(true,true).animate({opacity: 1},250)
                    },
                    mouseleave: function(){
                        $(this).stop(true,true).animate({opacity: .5},25)
                    }
                }
            },
            create: null,
            allToggle: null,
            allPurge: null,
            allUpdate: null,
            currentEdit: null,
            currentEscape: null,
            currentStop: null,
            currentPurge: null,
            currentResize: null,
            updateTimeout: null,
            updateDataset: null
        };

        $(document).ready(function(){

            /**
             * Since we're here, we may assume,
             * that all libraries have been loaded
             * Let's instatinate our custom functions
             * for handling pageAnnotations
            **/

            tinyTplPageAnnotations.updateDataset = function(){

                var dataToSend = JSON.stringify( allPageAnnotations );

                if ( dataToSend !== allPageAnnotationsCopy )
                {
                    $.ajax({
                        url: "/tinyAdmin/annotations",
                        type: "POST",
                        dataType: "script",
                        data: {
                            value: "update",
                            data: dataToSend,
                            page: annotationPageId
                        }
                    });

                    allPageAnnotationsCopy = dataToSend;
                }

                tinyTplPageAnnotations.updateTimeout = null;
            }

            tinyTplPageAnnotations.allUpdate = function(){

                // Update counter of current Annotations
                $('.tiny-annotation-counter').text( $('.tiny-page-annotation').length );

                var tmpAnnotationHolder = {};

                $('.tiny-page-annotation').each(function(index,annotation){

                    // Get all data from annotation
                    var anno = $(annotation),
                        id = anno.data("id"),
                        currentDataSet = {
                            data: {
                                id: id,
                                headline: anno.find(".tiny-annotation-head").text(),
                                mainhtml: $($(".iframe-"+id).contentDocument()).find('body').html(),
                            },
                            metric: {
                                top: anno.position().top + "px",
                                left: anno.position().left + "px",
                                width: anno.width() + "px",
                                height: anno.height() + "px",
                            }
                        }

                    // insert/overwrite into temporary annotations
                    tmpAnnotationHolder[id] = currentDataSet;
                });

                // Set new global object
                allPageAnnotations = tmpAnnotationHolder;

                // Clean up any previously defined updater
                if ( tinyTplPageAnnotations.updateTimeout != null )
                {
                    window.clearTimeout( tinyTplPageAnnotations.updateTimeout );
                }

                // Set new updater
                tinyTplPageAnnotations.updateTimeout = setTimeout(
                    tinyTplPageAnnotations.updateDataset, 500
                );

            }

            tinyTplPageAnnotations.allToggle = function(){
                if ( $('.tiny-page-annotation').length != 0 && $('.tiny-page-annotation').is(':visible') )
                {
                    $('.tiny-page-annotation').hide();

                } else if ( $('.tiny-page-annotation').length != 0 ) {

                    $('.tiny-page-annotation').show();
                }
            }

            tinyTplPageAnnotations.allPurge = function(){
                $('.tiny-page-annotation').remove();
                tinyTplPageAnnotations.allUpdate();
            }

            tinyTplPageAnnotations.currentPurge = function(){
                // Get a ref to current
                var anno = $('.tiny-page-' + $(this).parents('.tiny-page-annotation').data("id") );

                // remove it
                anno.remove();

                // Store new config in global object
                tinyTplPageAnnotations.allUpdate();
            }

            tinyTplPageAnnotations.currentStop = function(){

                var id = $('.tiny-anno-trigger').data("currentannotation" );
                var annoIframe = $('.iframe-' + id );

                $('.tiny-anno-trigger').data("currentannotation", "null" );

                annoIframe.css({
                    background: "transparent",
                    "border-width": "0px"
                }).designMode("off");

                tinyTplPageAnnotations.allUpdate()
            }

            tinyTplPageAnnotations.currentEscape = function(e){

                if ( e.which == $.ui.keyCode.ESCAPE )
                {
                    tinyTplPageAnnotations.currentStop()
                }

            }

            tinyTplPageAnnotations.currentEdit = function( ){

                var annoIframe = $('.iframe-' + $(this).data("id") );

                $('.tiny-anno-trigger').data("currentannotation", $(this).data("id") );

                annoIframe.css({
                        background: "rgba(0,0,0,.15)",
                        "border-width": "1px"
                    })
                    .designMode("on");

            }

            tinyTplPageAnnotations.currentResize = function( ){
                    var annoId = ".tiny-page-" +  $(this).data("id");
                    $('.iframe-'+$(this).data("id")).css({
                        height:( $( annoId ).height()-60 ) + "px",
                        width:( $( annoId ).width()-20 ) + "px"
                    });
                    tinyTplPageAnnotations.allUpdate();
                }

            tinyTplPageAnnotations.create = function( event, annotation ){

                if ( typeof annotation == "undefined" )
                {
                    // Define a basic annotation data set
                    annotation = {
                        metric: {
                            top: ($(document).height()/2 - 125) + "px",
                            left: ($(document).width()/2 - 150) + "px",
                            width: "300px",
                            height: "250px"
                        },
                        data: {
                            id: "annotation-" + $.uID(),
                            headline: "tinyTpl Note",
                            mainhtml: "Doubleclick to edit me"
                        }
                    }
                }

                /**
                 * Create the annotation and have it placed in the center of the page
                **/
                var cAnno = $('<div></div>')
                    .data('id', annotation.data.id )
                    .data('annotation', annotation )
                    .addClass( "tiny-page-annotation" )
                    .addClass( "tiny-page-" + annotation.data.id )
                    .attr('style',"background-image: linear-gradient(top, rgba(0,0,0,0), rgba(0,0,0,0.25));"
                        +"background-image: -o-linear-gradient(top, rgba(0,0,0,0), rgba(0,0,0,.25));"
                        +"background-image: -moz-linear-gradient(top, rgba(0,0,0,0), rgba(0,0,0,.25));"
                        +"background-image: -ms-linear-gradient(top, rgba(0,0,0,0), rgba(0,0,0,0.25));"
                        +"background-image: -webkit-gradient( linear, left bottom, left top, color-stop(1, rgba(0,0,0,0)), color-stop(0, rgba(0,0,0,.25)) );"
                        +"background-image: -webkit-linear-gradient(top, rgba(0,0,0,0), rgba(0,0,0,0.25));"
                    )
                    .css({
                        cursor: "move",
                        position: "absolute",
                        /**
                         * set a fallback color for all browsers not supporting gradients
                        **/
                        'background-color': '#f6eb6a',

                        /**
                         * draw a nice shadow
                        **/
                        '-moz-box-shadow': '3px 3px 12px #000',
                        '-webkit-box-shadow': '3px 3px 12px #000',
                        'box-shadow': '3px 3px 12px #000'
                    })
                    /**
                     * By simply passing valid css, a custom color may be defined at a later stage of development
                    **/
                    .css( annotation.metric )
                    .append(
                        $('<div></div>').css({
                            margin: '5px',
                            background: '#fcf497 url(/tinyAdmin/special/img/32/tinyTpl.png) no-repeat left center',
                            height: "32px",
                            position: 'relative',
                            'padding-left': "38px",
                            'line-height': '32px'
                        }).addClass('tiny-annotation-head')
                        .append(
                            $('<span></span>').text( annotation.data.headline )
                        ).append(
                            $("<div></div>")
                                .css( tinyTplPageAnnotations.btn.css )
                                .css({
                                    right: "8px",
                                    bottom: "4px",
                                    background: "transparent url(/tinyAdmin/special/img/24/document-close-4.png) no-repeat center",
                                })
                                .attr('title',"Renove this annotation")
                                .bind({
                                    mouseenter: tinyTplPageAnnotations.btn.animation.mouseenter,
                                    mouseleave: tinyTplPageAnnotations.btn.animation.mouseleave,
                                    click: tinyTplPageAnnotations.currentPurge
                                })
                        )
                    )
                    .append(
                        $('<iframe></iframe>')
                            .attr('src','about:')
                            .addClass("iframe-" + annotation.data.id )
                            .data("id", annotation.data.id )
                            .css({
                                position: "absolute",
                                top: "45px",
                                left: "10px",
                                display: "inline",
                                padding: 0,
                                margin: 0,
                                border: "0px solid #888",
                                'border-bottom-color': "#ccc",
                                'border-right-color': "#ccc",
                                background:  "transparent", // "rgba(0,0,0,.15)",
                                "font-family": "sans-serif",
                                "font-size": "10pt",
                                width: ( parseInt(annotation.metric.width.replace(/\D/g,'') )-20 ) + "px",
                                height: ( parseInt(annotation.metric.height.replace(/\D/g,'') )-60 ) + "px"
                            })
                    )
                    .resizable()
                    .draggable()
                    .appendTo('body');

                // Late bind for resize and drag events
                // This avoids updating annotations on server
                // befor rendering is finished and could lead to deletion
                // of all annotations made.
                cAnno.bind({
                    dragstop: tinyTplPageAnnotations.currentResize,
                    resize: tinyTplPageAnnotations.currentResize,
                    resizestop: tinyTplPageAnnotations.currentResize
                });

                // Bind dblclick to iframe's document.
                var iframeDocument = $('.iframe-'+annotation.data.id);

                $( 'body', iframeDocument.contentDocument() )

                    // Bind event handlers
                    .bind({
                        dblclick: tinyTplPageAnnotations.currentEdit,
                        keydown: tinyTplPageAnnotations.currentEscape
                    })

                    // Add annotation id to iframe's document
                    .data("id", annotation.data.id )

                    // Fix font fpr iframe's document
                    .css({
                        'font-family': "sans-serif",
                        'background': "transparent"
                    })

                    // finally - insert the html
                    .append( annotation.data.mainhtml );

                // Update counter of current Annotations
                $('.tiny-annotation-counter').text( $('.tiny-page-annotation').length );

            }

            /**
             * Attach click handler to stop editing
            **/
            $('body').bind({
                click: tinyTplPageAnnotations.currentStop,
                keydown: tinyTplPageAnnotations.currentEscape
            });


            if ( $('.tiny-anno-trigger').length == 0 )
            {
                $('<div></div>')
                    .css({
                        width: "128px",
                        height: "128px",
                        position: "absolute",
                        left: "-32px",
                        top: 0,
                        background: "transparent url(/tinyAdmin/special/img/128/tinyPageAnnotations.png) no-repeat center"
                    })
                    .addClass("tiny-anno-trigger")
                    .appendTo("body")
<?php

    // This ensures, the entry animation is only played once,
    // as it is pretty annoying to see the vanishing
    // postIt everytime a page loads

    if ( ! isset( $_SESSION['tinyadmin']['tinyPageAnnotations'] )
        || $_SESSION['tinyadmin']['tinyPageAnnotations'] != "INIT_DONE" ): ?>
                    .animate({left:'-96px', opacity: .25},500)
<?php
    // Store information into session, that animation was played
    $_SESSION['tinyadmin']['tinyPageAnnotations'] = "INIT_DONE";
?>
<?php else: ?>
                    .css({
                        left: "-96px",
                        opacity: .25
                    })
<?php endif; ?>
                    .append(
                        $("<div></div>")
                            .css({
                                "-webkit-border-radius": "25px",
                                "-moz-border-radius": "25px",
                                "border-radius": "25px",
                                color: "#fff",
                                background: "#f00",
                                position: "absolute",
                                width: "50px",
                                height: "50px",
                                top: "49px",
                                left: "39px",
                                overflow: "hidden",
                                "font-size": "20px",
                                "line-height": "50px",
                                "font-weight": "bold",
                                "text-align": "center"
                            })
                            .addClass("tiny-annotation-counter")
                            .attr('title',"Annotation counter")
                            .text("0")
                    )
                    .append(
                        $("<div></div>")
                            .css( tinyTplPageAnnotations.btn.css )
                            .css({
                                right: "8px",
                                bottom: 0,
                                background: "transparent url(/tinyAdmin/special/img/24/document-new-6.png) no-repeat center",
                            })
                            .attr('title',"Create new Annotation")
                            .bind({
                                mouseenter: tinyTplPageAnnotations.btn.animation.mouseenter,
                                mouseleave: tinyTplPageAnnotations.btn.animation.mouseleave,
                                click: tinyTplPageAnnotations.create
                            })
                    )
                    .append(
                        $("<div></div>")
                            .css( tinyTplPageAnnotations.btn.css )
                            .css({
                                left: "8px",
                                bottom: 0,
                                background: "transparent url(/tinyAdmin/special/img/24/edit-delete-2.png) no-repeat center",
                            })
                            .attr('title',"Remove all annotations for this page")
                            .bind({
                                mouseenter: tinyTplPageAnnotations.btn.animation.mouseenter,
                                mouseleave: tinyTplPageAnnotations.btn.animation.mouseleave,
                                click: tinyTplPageAnnotations.allPurge
                            })
                    )
                    .append(
                        $("<div></div>")
                            .css( tinyTplPageAnnotations.btn.css )
                            .css({
                                left: "52px",
                                bottom: 0,
                                background: "transparent url(/tinyAdmin/special/img/24/edit-copy-6.png) no-repeat center",
                            })
                            .attr('title',"Hide/Show all annotations for this page")
                            .bind({
                                mouseenter: tinyTplPageAnnotations.btn.animation.mouseenter,
                                mouseleave: tinyTplPageAnnotations.btn.animation.mouseleave,
                                click: tinyTplPageAnnotations.allToggle
                            })
                    )
                    .bind({
                        mouseenter: function(){
                            $(this).stop(true,true).animate({left:0, opacity: 1},250)
                        },
                        mouseleave: function(){
                            $(this).stop(true,true).animate({left:'-96px', opacity: .25},500)
                        }
                    });

            }

            // now everything is setup and defined,
            // create all stored annotations
            $.each( allPageAnnotations, function(index,annotation){

                tinyTplPageAnnotations.create( null, annotation );

            });

        });
    }

</script>
