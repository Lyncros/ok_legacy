<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Revista Digital</title>
<script src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="js/turn.min.js"></script>
<script type="text/javascript" src="js/zoom.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="js/modernizr.2.5.3.min.js"></script>
<script type="text/javascript" src="js/magazine.js"></script>
<script type="text/javascript" src="js/hash.js"></script>
<script type="text/javascript" src="js/jspatch.js"></script>
<link rel="stylesheet" type="text/css" href="css/magazine.css"/>
<link type='text/css' href='css/contact.css' rel='stylesheet' media='screen' />
</head>

<body>
<div id="contenedor_contacto">
  <div class="contacto">
    <div id='contact-form' > SELECCIONA LAS P&Aacute;GINAS SOBRE LAS QUE DESEAS SOLICITAR INFORMACI&Oacute;N Y LUEGO HAZ <a href='#' class='contact contact_a'> CLICK AQUI </a> PARA ENVIAR TU CONSULTA </div>
    <div style='display:none'> <img src='img/contact/loading.gif' alt='' /> </div>
  </div>
</div>
<div id="contenedor-ppal" class="contenedor">
  <div class="magazine-viewport">
    <div class="container">
      <div class="magazine"> 
        <!-- Next button -->
        <div ignore="1" class="next-button"></div>
        <!-- Previous button -->
        <div ignore="1" class="previous-button"></div>
      </div>
    </div>
      <!--
    <div id="chk_container" class="chk_container">
      <div class="chk_box_izq regular-checkbox big-checkbox">
        <input id="chk_box_izq" type="checkbox" onclick="javascript:marcaSelector(pagActual-2,0);" />
      </div>
      <div class="chk_box_der regular-checkbox big-checkbox">
        <input id="chk_box_der" type="checkbox" onclick="javascript:marcaSelector(pagActual-1,0);" />
        
        
      </div>
      -->
      
    </div>
    
  </div>
       <div class="chk_container content-selector">
        <div class="content-center">
            <input type="checkbox" name="seleccion-pagina" id="seleccion-pagina" class="css-checkbox" onclick="javascript:marcaSelector(pagActual-1,0);"/>
            <label for="seleccion-pagina" class="css-label radGroup1" id="label-seleccion-pagina"></label>
        </div>
        
    </div>
        
  <!-- Thumbnails -->
  <div class="thumbnails">
    <div>
      <ul>
        
        <?php
                        $cantPages = 100;
                        $pages = "";
                        for ($i = 2; $i <= $cantPages; $i = $i + 2) {
                            $page = $i;
                            if ($i < $cantPages) {
                                $clase = "d";
                            } else {
                                $clase = "i";
                            }
                            ?>
        
          <?php
                                    if ($i < $cantPages) {
                                        $page .= " - " . ($i + 1);
                                        ?>
         
          <?php } ?>
          
        <?php } ?>
      </ul>
    </div>
  </div>
  <script type="text/javascript">

                                        function loadApp() {
                                                $('.thumbnails').hide();
                                            
                                            pagActual=1;
                                            maxPag=<?php echo $cantPages; ?>;
                                            // Create the flipbook

                                            $('.magazine').turn({
                                                // Magazine width

                                                width: 820,
                                                // Magazine height

                                                height: 550,
                                                // Elevation will move the peeling corner this number of pixels by default

                                                elevation: 30,
                                                // Hardware acceleration

                                                acceleration: !isChrome(),
                                                // Enables gradients

                                                gradients: true,
                                                // Auto center this flipbook

                                                autoCenter: true,
                                                // The number of pages

                                                pages: <?php echo $cantPages; ?>,
                                                // Events
                                                when: {
                                                    turning: function(event, page, view) {

                                                        var book = $(this),
                                                                currentPage = book.turn('page'),
                                                                pages = book.turn('pages');

                                                        // Update the current URI

                                                        Hash.go('pagina-' + page).update();


                                                        // Show and hide navigation buttons

                                                        disableControls(page);
                                                        marcaSelector(0,page);

                                                        $('.thumbnails .page-' + currentPage).
                                                                parent().
                                                                removeClass('current');

                                                        $('.thumbnails .page-' + page).
                                                                parent().
                                                                addClass('current');

                                                    },
                                                    turned: function(event, page, view) {

                                                        disableControls(page);

                                                        $(this).turn('center');

                                                        if (page == 1) {
                                                            $(this).turn('peel', 'br');
                                                        }

                                                    },
                                                    missing: function(event, pages) {

                                                        // Add pages that aren't in the magazine

                                                        for (var i = 0; i < pages.length; i++)
                                                            addPage(pages[i], $(this));

                                                    }
                                                }

                                            });

                                            // Zoom.js

                                            $('.magazine-viewport').zoom({
                                                flipbook: $('.magazine'),
                                                max: function() {

                                                    return largeMagazineWidth() / $('.magazine').width();

                                                },
                                                when: {
                                                    tap: function(event) {

                                                        if ($(this).zoom('value') == 1) {
                                                            $('.magazine').
                                                                    removeClass('animated').
                                                                    addClass('zoom-in');
                                                            $(this).zoom('zoomIn', event);
                                                        } else {
                                                            $(this).zoom('zoomOut');
                                                        }
                                                    },
                                                    resize: function(event, scale, page, pageElement) {

                                                        if (scale == 1)
                                                            loadSmallPage(page, pageElement);
                                                        else
                                                            loadLargePage(page, pageElement);

                                                    },
                                                    zoomIn: function() {

                                                        $('.thumbnails').hide();
                                                        $('.chk_container').hide();
                                                        $('.made').hide();
                                                        $('.magazine').addClass('zoom-in');

                                                        if (!window.escTip && !$.isTouch) {
                                                            escTip = true;

                                                            $('<div />', {'class': 'esc'}).
                                                                    html('<div>Press ESC to exit</div>').
                                                                    appendTo($('body')).
                                                                    delay(2000).
                                                                    animate({opacity: 0}, 500, function() {
                                                                $(this).remove();
                                                            });
                                                        }
                                                    },
                                                    zoomOut: function() {

                                                        $('.esc').hide();
                                                        $('.thumbnails').fadeIn();
                                                        $('.chk_container').fadeIn();
                                                        $('.made').fadeIn();

                                                        setTimeout(function() {
                                                            $('.magazine').addClass('animated').removeClass('zoom-in');
                                                            resizeViewport();
                                                        }, 0);

                                                    },
                                                    swipeLeft: function() {

                                                        $('.magazine').turn('next');

                                                    },
                                                    swipeRight: function() {

                                                        $('.magazine').turn('previous');

                                                    }
                                                }
                                            });

                                            // Using arrow keys to turn the page

                                            $(document).keydown(function(e) {

                                                var previous = 37, next = 39, esc = 27;

                                                switch (e.keyCode) {
                                                    case previous:

                                                        // left arrow
                                                        $('.magazine').turn('previous');
                                                        e.preventDefault();

                                                        break;
                                                    case next:

                                                        //right arrow
                                                        $('.magazine').turn('next');
                                                        e.preventDefault();

                                                        break;
                                                    case esc:

                                                        $('.magazine-viewport').zoom('zoomOut');
                                                        e.preventDefault();

                                                        break;
                                                }
                                            });

                                            // URIs - Format #/page/1 

                                            Hash.on('^page\/([0-9]*)$', {
                                                yep: function(path, parts) {
                                                    var page = parts[1];

                                                    if (page !== undefined) {
                                                        if ($('.magazine').turn('is'))
                                                            $('.magazine').turn('page', page);
                                                    }

                                                },
                                                nop: function(path) {

                                                    if ($('.magazine').turn('is'))
                                                        $('.magazine').turn('page', 1);
                                                }
                                            });


                                            $(window).resize(function() {
                                                resizeViewport();
                                            }).bind('orientationchange', function() {
                                                resizeViewport();
                                            });

                                            // Events for thumbnails

                                            $('.thumbnails').click(function(event) {

                                                var page;

                                                if (event.target && (page = /page-([0-9]+)/.exec($(event.target).attr('class')))) {

                                                    $('.magazine').turn('page', page[1]);
                                                }
                                            });

                                            $('.thumbnails li').
                                                    bind($.mouseEvents.over, function() {

                                                $(this).addClass('thumb-hover');

                                            }).bind($.mouseEvents.out, function() {

                                                $(this).removeClass('thumb-hover');

                                            });

                                            if ($.isTouch) {

                                                $('.thumbnails').
                                                        addClass('thumbanils-touch').
                                                        bind($.mouseEvents.move, function(event) {
                                                    event.preventDefault();
                                                });

                                            } else {

                                                $('.thumbnails ul').mouseover(function() {

                                                    $('.thumbnails').addClass('thumbnails-hover');

                                                }).mousedown(function() {

                                                    return false;

                                                }).mouseout(function() {

                                                    $('.thumbnails').removeClass('thumbnails-hover');

                                                });

                                            }


                                            // Regions

                                            if ($.isTouch) {
                                                $('.magazine').bind('touchstart', regionClick);
                                            } else {
                                                $('.magazine').click(regionClick);
                                            }

                                            // Events for the next button

                                            $('.next-button').bind($.mouseEvents.over, function() {

                                                $(this).addClass('next-button-hover');

                                            }).bind($.mouseEvents.out, function() {

                                                $(this).removeClass('next-button-hover');

                                            }).bind($.mouseEvents.down, function() {

                                                $(this).addClass('next-button-down');

                                            }).bind($.mouseEvents.up, function() {

                                                $(this).removeClass('next-button-down');

                                            }).click(function() {

                                                $('.magazine').turn('next');

                                            });

                                            // Events for the next button

                                            $('.previous-button').bind($.mouseEvents.over, function() {

                                                $(this).addClass('previous-button-hover');

                                            }).bind($.mouseEvents.out, function() {

                                                $(this).removeClass('previous-button-hover');

                                            }).bind($.mouseEvents.down, function() {

                                                $(this).addClass('previous-button-down');

                                            }).bind($.mouseEvents.up, function() {

                                                $(this).removeClass('previous-button-down');

                                            }).click(function() {

                                                $('.magazine').turn('previous');

                                            });


                                            resizeViewport();

                                            $('.magazine').addClass('animated');
                                            $('.thumbnails').addClass('animated');
                                            $('.chk_container').addClass('animated');
                                        }



                                        // Load the HTML4 version if there's not CSS transform

                                        yepnope({
                                            test: Modernizr.csstransforms,
                                            yep: ['js/turn.min.js'],
                                            //nope: ['js/turn.html4.min.js'],
                                            both: ['js/zoom.min.js'],
                                            complete: loadApp
                                        });

                                    </script> 
</div>
<!--                                   <div id="contenedor_contacto"  class="contenedor derecho">
                                            <div class="contacto">
                                                <div id='contact-form' >


                                                    <a href='#' class='contact contact_a'>
                                                        <img src="images/sobre.gif" alt="eMail" width="30px"/><br />
                                                        Solicitar Informacion 
                                                    </a>

                                                </div>
                                                <div style='display:none'>
                                                    <img src='img/contact/loading.gif' alt='' />
                                                </div>
                                            </div>

                                    </div>
--> 
<!--                                <script type='text/javascript' src='js/jquery.js'></script>  --> 
<script type='text/javascript' src='js/jquery.simplemodal.js'></script> 
<script type='text/javascript' src='js/contact.js'></script>
</body>
</html>
