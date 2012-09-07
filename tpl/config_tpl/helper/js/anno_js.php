
<script type="text/javascript">
    var annotations = <?=json_encode( $this->DATA['ANNOTATIONS'] )?>;

    if ( window.jQuery )
    {
        $(document).ready(function(){
            if ( $('.anno-trigger').length == 0 )
            {
                $('<div></div>')
                    .css({
                        width: "128px",
                        height: "128px",
                        position: "absolute",
                        left: "-32px",
                        top: "50%",
                        "margin-top": "-64px",
                        background: "transparent url(/special/img/128/knotes-2.png) no-repeat center",
                        cursor: "pointer"
                    })
                    .addClass("anno-trigger")
                    .appendTo("body")
                    .animate({left:'-96px', opacity: .25},500)
                    .bind({
                        mouseenter: function(){
                            $(this).stop(true,true).animate({left:'10px', opacity: 1},250)
                        },
                        mouseleave: function(){
                            $(this).stop(true,true).animate({left:'-96px', opacity: .25},500)
                        },
                        click: function(){jQuery.fn.stickyNotes.createNote()}
                    });

            }
        });
    }

</script>
