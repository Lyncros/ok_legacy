/*
 * Magazine sample
*/


var pagActual=0;
var maxPag=0;
var selecciones=new Array();

function addPage(page, book) {

	var id, pages = book.turn('pages');

	// Create a new element for this page
	var element = $('<div />', {});

	// Add the page to the flipbook
	if (book.turn('addPage', element, page)) {

		// Add the initial HTML
		// It will contain a loader indicator and a gradient
		element.html('<div class="gradient"></div><div class="loader"></div>');

		// Load the page
		loadPage(page, element);
	}

}

function loadPage(page, pageElement) {

	// Create an image element

	var img = $('<img />');

	img.mousedown(function(e) {
		e.preventDefault();
	});

	img.load(function() {
		
		// Set the size
		$(this).css({width: '100%', height: '100%'});
//		$(this).css({width: '90%', height: '90%'});

		// Add the image to the page after loaded

		$(this).appendTo(pageElement);

		// Remove the loader indicator
		
		pageElement.find('.loader').remove();
	});

	// Load the page

	img.attr('src', 'digital/revista02/' +  page + '.jpg');
	//alert('digital/' +  page + '.jpg');

//	loadRegions(page, pageElement);

}

// Load regions

function loadRegions(page, element) {

	$.getJSON('digital/'+page+'-regions.json').
		done(function(data) {

			$.each(data, function(key, region) {
				addRegion(region, element);
			});
		});
}

// Add region

function addRegion(region, pageElement) {
	
	var reg = $('<div />', {'class': 'region  ' + region['class']}),
		options = $('.magazine').turn('options'),
		pageWidth = options.width/2,
		pageHeight = options.height;

	reg.css({
		top: Math.round(region.y/pageHeight*100)+'%',
//		top: Math.round(region.y/pageHeight*90)+'%',
		left: Math.round(region.x/pageWidth*100)+'%',
		width: Math.round(region.width/pageWidth*100)+'%',
//		left: Math.round(region.x/pageWidth*90)+'%',
		//width: Math.round(region.width/pageWidth*90)+'%',
		height: Math.round(region.height/pageHeight*100)+'%'
//		height: Math.round(region.height/pageHeight*90)+'%'
	}).attr('region-data', $.param(region.data||''));


	reg.appendTo(pageElement);
}

function regionClick(event) {

	var region = $(event.target);
	if (region.hasClass('region')) {
		
		var regionType = $.trim(region.attr('class').replace('region', ''));

		return processRegion(region, regionType);
	}

}

function processRegion(region, regionType) {

	data = decodeParams(region.attr('region-data'));

	switch (regionType) {
		case 'link' :

			window.open(data.url);

		break;
		case 'zoom' :

			var regionOffset = region.offset(),
				viewportOffset = $('.magazine-viewport').offset(),
				pos = {
					x: regionOffset.left-viewportOffset.left,
					y: regionOffset.top-viewportOffset.top
				};

			$('.magazine-viewport').zoom('zoomIn', pos);

		break;
		case 'to-page' :

			$('.magazine').turn('page', data.page);

		break;
	}

}

// Load large page

function loadLargePage(page, pageElement) {
	
	var img = $('<img />');

	img.load(function() {

		var prevImg = pageElement.find('img');
		$(this).css({width: '100%', height: '100%'});
//		$(this).css({width: '90%', height: '90%'});
		$(this).appendTo(pageElement);
		prevImg.remove();
		
	});

	// Loadnew page
	
	img.attr('src', 'digital/revista02/large/' +  page + '.jpg');
}

// Load small page

function loadSmallPage(page, pageElement) {
	
	var img = pageElement.find('img');

	img.css({width: '100%', height: '100%'});
//	img.css({width: '90%', height: '90%'});

	img.unbind('load');
	// Loadnew page

	img.attr('src', 'digital/revista02/' +  page + '.jpg');
}

// http://code.google.com/p/chromium/issues/detail?id=128488

function isChrome() {

	return navigator.userAgent.indexOf('Chrome')!=-1;

}

function disableControls(page) {
		if (page==1)
			$('.previous-button').hide();
		else
			$('.previous-button').show();
					
		if (page==$('.magazine').turn('pages'))
			$('.next-button').hide();
		else
			$('.next-button').show();
}

// Set the width and height for the viewport

function resizeViewport() {

	var width = $(window).width(),
		height = $(window).height(),
		options = $('.magazine').turn('options');

                
	$('.magazine').removeClass('animated');

	$('.magazine-viewport').css({
		width: width,
		height: height
	}).
	zoom('resize');


	if ($('.magazine').turn('zoom')==1) {
		var bound = calculateBound({
			width: options.width,
			height: options.height,
			boundWidth: Math.min(options.width, width),
			boundHeight: Math.min(options.height, height)
		});

		if (bound.width%2!==0)
			bound.width-=1;

			
		if (bound.width!=$('.magazine').width() || bound.height!=$('.magazine').height()) {

			$('.magazine').turn('size', bound.width, bound.height);

			if ($('.magazine').turn('page')==1)
				$('.magazine').turn('peel', 'br');

			$('.next-button').css({height: bound.height, backgroundPosition: '-38px '+(bound.height/2-32/2)+'px'});
			$('.previous-button').css({height: bound.height, backgroundPosition: '-4px '+(bound.height/2-32/2)+'px'});
		}

		$('.magazine').css({top: -bound.height/2, left: -bound.width/2});
	}

	var magazineOffset = $('.magazine').offset(),
		boundH = height - magazineOffset.top - $('.magazine').height(),
		marginTop = (boundH - $('.thumbnails > div').height()) / 2;

	if (marginTop<0) {
		$('.thumbnails').css({height:1});
	} else {
		$('.thumbnails').css({height: boundH});
		$('.thumbnails > div').css({marginTop: marginTop});
	}

	if (magazineOffset.top<$('.made').height())
		$('.made').hide();
	else
		$('.made').show();

    relocateChkbox(options.height);
	$('.magazine').addClass('animated');
	
}


function relocateChkbox(alto){
//    $('.chk_box_izq').css({top:'-270px',left:'-100px'});
//    $('.chk_box_der').css({top:'-289px',left:'70px'});
/*    var top=$('div.gradient').height() / 2;
    if(alto < top){
        $('.chk_container').css({top:top,left:'0px',width:'100%',height:'20px'});
    }else{
        top=($('div.magazine-viewport').height()-$('div.gradient').height())/2+$('div.gradient').height();      
        $('.chk_container').css({top:(-1*top),left:'0px',width:'100%',height:'20px'});
    }
	*/
	//$('.chk_container').css({top:'420px',left:'0px',width:'100%',height:'20px'});
	
    var posIzq=$(window).width()/2-470;
    var posDer=$(window).width()/2+420;
   // $('.chk_box_izq').css({left:posIzq});
 //   $('.chk_box_der').css({left:posDer});
    if(pagActual==1 || pagActual==maxPag){
        $('.chk_container').hide();
    }else{
        $('.chk_container').show();
    }
    console.log('pagActual '+pagActual);
    console.log('maxPag '+maxPag);
    $('.content-selector').css({top:$('.magazine').height()+50});
}
// Width of the flipbook when zoomed in

function largeMagazineWidth() {
	
	return 2214;

}

// decode URL Parameters

function decodeParams(data) {

	var parts = data.split('&'), d, obj = {};

	for (var i =0; i<parts.length; i++) {
		d = parts[i].split('=');
		obj[decodeURIComponent(d[0])] = decodeURIComponent(d[1]);
	}

	return obj;
}

// Calculate the width and height of a square within another square

function calculateBound(d) {
	
	var bound = {width: d.width, height: d.height};

	if (bound.width>d.boundWidth || bound.height>d.boundHeight) {
		
		var rel = bound.width/bound.height;

		if (d.boundWidth/rel>d.boundHeight && d.boundHeight*rel<=d.boundWidth) {
			
			bound.width = Math.round(d.boundHeight*rel);
			bound.height = d.boundHeight;

		} else {
			
			bound.width = d.boundWidth;
			bound.height = Math.round(d.boundWidth/rel);
		
		}
	}
		
	return bound;
}

function marcaSelector(clicked,turned){
    var marca=0;
    var marcad=0;
    if(turned!=0){
        pagActual=turned;
        if(turned%2==0){
                pag1 = pagActual-0; 
                pag2 = pagActual+1; 
                paginas = pag1+'-'+pag2;
           }else{
               pag1 = pagActual-1; 
               pag2 = pagActual+0;            
               paginas = pag1+'-'+pag2;
           }
           
        $('#label-seleccion-pagina').html('Selecciona las páginas '+paginas);
           
        //$('#seleccion-pagina').prop('checked', false);
    }
    console.log('turned '+turned);
    
    
   // $('#label-seleccion-pagina').trigger("click");
             
    for(var a=0; a < selecciones.length; a++){
        if(selecciones[a]==(pagActual-1)){
           // $('#label-seleccion-pagina').trigger("click");
        }else{
           
            
        }
    }
    if(clicked!=0){
        for(var x=0; x < selecciones.length; x++){
            if(selecciones[x]==clicked){
                selecciones[x]=null;
                marca=1;
                break;
            }else{
                
            }
        }
        if(marca==0){
            selecciones.push(clicked);
        }else{
            console.log('rearmaSeleccion ');
            rearmaSeleccion();
        }
    }else{
        for(var x=0; x < selecciones.length; x++){
            /*
            if(turned%2==0){
                if(selecciones[x]==turned){
                        document.getElementById('seleccion-pagina').checked=true;
                       
                        marca=1;
                }
                if(selecciones[x]==turned+1){
                        document.getElementById('seleccion-pagina').checked=true;
                        marcad=1;
                }
            }else{
                if(selecciones[x]==turned-1){
                        document.getElementById('seleccion-pagina').checked=true;
                        marca=1;
                }
                if(selecciones[x]==turned){
                        document.getElementById('seleccion-pagina').checked=true;
                        marcad=1;
                }
            }
            */
               
            if(turned%2==0){
                pos = turned-1;                
           }else{
               pos = turned-2;
           }
           console.log('pos '+pos);
                if(selecciones[x]==pos){
                    console.log('ACTIVA ');
                   document.getElementById('seleccion-pagina').checked=true;
                   marcad=1;
               }
        }
        if (marcad==0){
              document.getElementById('seleccion-pagina').checked=false;            
        }else if(marcad==0){
               document.getElementById('seleccion-pagina').checked=false;
        }
        console.log('turned '+turned);
        console.log('marcad '+marcad);
    }
    if(pagActual==1 || pagActual==2 || pagActual==3 || pagActual==4){
        $('.chk_container').hide();
    }else{
        $('.chk_container').show();
    }
    
}

function rearmaSeleccion(){
    var auxSel=new Array();
    for(var y=0;y < selecciones.length;y++){
        if(selecciones[y]!=null){
            auxSel.push(selecciones[y]);
        }
    }
    selecciones=auxSel;
}

function recuperaListaSelecciones(){
    var retorno     =   '';
    paginasPedido   =   '';
    if(selecciones.length!=0){
        for(var y=0;y < selecciones.length;y++){
            
            paginasPedido = paginasPedido+'['+(selecciones[y]+1)+'-'+(selecciones[y]+2)+'] ';
        }
        
        retorno='Solicito informacion respecto a publicaciones de página/s '+ paginasPedido;
        
    }
    
    return retorno;
}