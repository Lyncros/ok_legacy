// JavaScript Document
if (top.location != self.location) top.location = self.location;

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function actualizaBarrio(){
	var zona;
	zona=document.getElementById('opcUbica').value;
	ajax_loadContent('Localidad','armaComboBarrioVentana.php?zona='+zona);
}

function actualizaAmbientes(prop){
	document.getElementById('ambientes').style.display = 'none';
	document.getElementById('despachos').style.display = 'none';
	document.getElementById('supTotal').style.display = 'none';
	switch(prop){
		case 'departamentos y p.h.':
		case 'casas':
		case 'quintas':
		 document.getElementById('ambientes').style.display = 'block';
		  break;
		case 'oficinas':
		 document.getElementById('despachos').style.display = 'block';
		  break;
		case 'campos':
		 document.getElementById('supTotal').style.display = 'block';
		  break;
		}
}

function actualizaDesdeHasta(){
	var vdesde = '';
	var vhasta = '';
	var vcadena = '';
	
	if(document.getElementById('desde').value != document.getElementById('desde').defaultValue && document.getElementById('desde').value != ''){
		vdesde = document.getElementById('desde').value;
	}else{
		document.getElementById('desde').value = document.getElementById('desde').defaultValue;
	}
	
	if(document.getElementById('hasta').value != document.getElementById('hasta').defaultValue && document.getElementById('hasta').value != ''){
		if( document.getElementById('hasta').value <  vdesde){
			alert('El valor Hasta es menor que Desde');
			document.getElementById('hasta').focus();
		}else{
			vhasta = document.getElementById('hasta').value;
		}
	}else{
		document.getElementById('hasta').value = document.getElementById('hasta').defaultValue;
	}
	if(vdesde != '' || vhasta != ''){
		if(vdesde != ''){
			vcadena = vdesde + ' AND ';
		}else{
			vcadena = '0 AND ';
		}
		if(vhasta != '' && vhasta != 0){
			vcadena += vhasta;
		}else{
			vcadena += '99999999999';
		}
	}else{
		vcadena = '';
	}
	document.getElementById('opcPrecioVenta').value = vcadena ;
	//alert(document.getElementById('opcPrecioVenta').value);
}
function actualizaUbica(){
	var valor = 0;
	if(document.getElementById('opcLocalidad').value == 0){
		valor = document.getElementById('opcZona').value;
	}else{
		valor = document.getElementById('opcLocalidad').value;
	}
	document.getElementById('opcUbica').value = valor;
}

function contador(){
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false; // set boolean t/f default f
	var i = '';
	var cantidad = 0;

	for ( i = 0; i < a_all_cookies.length; i++ ){
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );

		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');

		// if the extracted name matches passed check_name
		if ( cookie_name.substring(0,2) == "id" ){
			cantidad++;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	//   alert(cantidad);
	var txt=document.getElementById("cant")
	if(cantidad != 0){
		txt.innerHTML = "(" + cantidad + ")";
		//contFavoritos.tinyscrollbar_update();
	}else{
		txt.innerHTML = "&nbsp; &nbsp; &nbsp;";
		//contFavoritos.tinyscrollbar_update();
	}
	//contFavoritos.tinyscrollbar_update();
}
function mi_busqueda(){
	// first we'll split this cookie up into name/value pairs
	// note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false; // set boolean t/f default f
	var i = '';
	var cantidad = 0;

	for ( i = 0; i < a_all_cookies.length; i++ ){
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );

		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');

		// if the extracted name matches passed check_name
		if ( cookie_name.substring(0,2) == "id" ){
			cantidad++;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	//    alert(cantidad);
	if (cantidad == 0 ){
		var txt=document.getElementById("cant")
		txt.innerHTML = "(0)";
		return false;
	}else{
		window.location.href = "busqueda.php?mb=1";
		//cargaCuadroDatosMB();
	}
}
function Get_Cookie( check_name ) {
    // first we'll split this cookie up into name/value pairs
    // note: document.cookie only returns name=value, not the other components
    var a_all_cookies = document.cookie.split( ';' );
    var a_temp_cookie = '';
    var cookie_name = '';
    var cookie_value = '';
    var b_cookie_found = false; // set boolean t/f default f
    var i = '';
	
    for ( i = 0; i < a_all_cookies.length; i++ )
    {
        // now we'll split apart each name=value pair
        a_temp_cookie = a_all_cookies[i].split( '=' );
		
		
        // and trim left/right whitespace while we're at it
        cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
	
        // if the extracted name matches passed check_name
        if ( cookie_name == check_name )
        {
            b_cookie_found = true;
            // we need to handle case where cookie has no value but exists (no = sign, that is):
            if ( a_temp_cookie.length > 1 )
            {
                cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
            }
            // note that in cases where cookie is initialized but no value, null is returned
            return cookie_value;
            break;
        }
        a_temp_cookie = null;
        cookie_name = '';
    }
    if ( !b_cookie_found )
    {
        return null;
    }
}

/*
only the first 2 parameters are required, the cookie name, the cookie
value. Cookie time is in milliseconds, so the below expires will make the 
number you pass in the Set_Cookie function call the number of days the cookie
lasts, if you want it to be hours or minutes, just get rid of 24 and 60.

Generally you don't need to worry about domain, path or secure for most applications
so unless you need that, leave those parameters blank in the function call.
*/
function Set_Cookie( name, value, expires, path, domain, secure ) {
    // set time, it's in milliseconds
    var today = new Date();
    today.setTime( today.getTime() );
    // if the expires variable is set, make the correct expires time, the
    // current script below will set it for x number of days, to make it
    // for hours, delete * 24, for minutes, delete * 60 * 24
    if ( expires )
    {
        expires = expires * 1000 * 60 * 60 * 24;
    }
    //alert( 'today ' + today.toGMTString() );// this is for testing purpose only
    var expires_date = new Date( today.getTime() + (expires) );
    //alert('expires ' + expires_date.toGMTString());// this is for testing purposes only

    document.cookie = name + "=" +escape( value ) +
    ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + //expires.toGMTString()
    ( ( path ) ? ";path=" + path : "" ) +
    ( ( domain ) ? ";domain=" + domain : "" ) +
    ( ( secure ) ? ";secure" : "" );
    contador();
	//ajax_loadContent('contFavoritos','actualizaFavoritos.php?id='+value+'&opera=1');
}

// this deletes the cookie when called
function Delete_Cookie( name, path, domain ) {
    if ( Get_Cookie( name ) ) document.cookie = name + "=" +
        ( ( path ) ? ";path=" + path : "") +
        ( ( domain ) ? ";domain=" + domain : "" ) +
        ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
    contador();
	//ajax_loadContent('contFavoritos','actualizaFavoritos.php?id='+name+'opera=0');
}
/*
function verFavoritos() {
	var ele = document.getElementById("contFavoritos");
	var backg = document.getElementById("tituFavoritos");
	if(ele.style.display == "" || ele.style.display == "block") {
    	ele.style.display = "none";
		backg.style.backgroundImage = "url(../images/fderecha.gif)";
  	}
	else {
		ele.style.display = "block";
		backg.style.backgroundImage = "url(../images/fAbajo.gif)";
	}
}
*/

function cambioVistos() {
	var contVistos = document.getElementById("contVistos");
	var tituVistos = document.getElementById("tituVistos");
	if(contVistos.style.display == "" || contVistos.style.display == "block") {
    	contVistos.style.display = "none";
		tituVistos.style.backgroundImage = "url(images/fderecha.gif)";
  	}else {
		contVistos.style.display = "block";
		tituVistos.style.backgroundImage = "url(images/fAbajo.gif)";
	}
} 
function detalleProp(i){
	document.getElementById('id').value = i;
	//document.getElementById('textoFiltro').value = document.getElementById('campoTextoFiltro').value;
	document.forms["detalle"].submit();
}
/*
function enviaMenu(i){
	document.getElementById('opcTipoProp').value = i;
	//document.getElementById('textoFiltro').value = document.getElementById('campoTextoFiltro').value;
	document.forms["fmenu"].submit();
}
*/
function abreSelector(){
	var zona = document.getElementById('opcZona').value;
	var loca = document.getElementById('opcLocalidad').value;
	var XPos = (screen.availWidth-350)/2;
   	var YPos = (screen.availHeight-350)/2;
	if(zona == 0){
		alert("Previamente debe seleccionar una ZONA");
	}else{
		window.open('armaComboBarrio.php?zona='+zona+'&loca='+loca, 'localidades', 'status=0,toolbar=0,menubar=0,location=0, top='+YPos+', left='+XPos+',width=350,height=350');
	}
}

function tb_open_new(jThickboxNewLink){
	//alert(jThickboxNewLink);
	tb_show(null,jThickboxNewLink,null);
}

function enviaBuscador(){
	alert('paso');
	var opcTipoOper = document.getElementById('opcTipoOper').value;	
	var opcTipoProp = document.getElementById('opcTipoProp').value;	
//	var opcUbica = document.getElementById('opcUbica').value;	
//	var localidad = document.getElementById('localidad').value;	
//	var opcAmbientes = document.getElementById('opcAmbientes').value;	
//	var opcDespachos = document.getElementById('opcDespachos').value;	
//	var opcSupTotal = document.getElementById('opcSupTotal').value;	
//	var opcMonedaVenta = document.getElementById('opcMonedaVenta').value;	
//	var opcEmprendimiento = document.getElementById('opcEmprendimiento').value;	
//	var opcPrecioVenta = document.getElementById('opcPrecioVenta').value;
	alert( "http://okeefe.site/" + opcTipoOper +"_"+ opcTipoProp);	
	window.location.href = "http://okeefe.site/" + opcTipoOper +"_"+ opcTipoProp;
}

