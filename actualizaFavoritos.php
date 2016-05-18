<?php
ob_start();
session_start();

//date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once('lib-nusoap/nusoap.php');

//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice  

//¿ocurrio error al llamar al web service? 
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
}else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}
$id_prop = $_GET['id'];

if(isset($_GET['opera']) && $_GET['opera'] == 1){
	$_SESSION['ids'][] = $id_prop;
}else{
	if($_GET['opera'] == 0){
		$key=array_search($id_prop,$_SESSION['ids']);
    	if($key!==false) unset($_SESSION['ids'][$key]);
	}
}

$param=array('id_prop'=>$id_prop);
$prop = $client->call('Propiedad',$param,'');
//print_r($prop);

$param=array('id_prop'=>$id_prop);
$fotos = $client->call('ListarFotosPropiedad',$param,'');
//print_r($fotos);

//print_r($_SESSION);

?>
<script type="text/javascript" language="javascript">
function contador(){
	var a_all_cookies = parent.document.cookie.split( ';' );
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
	var txt=parent.document.getElementById("cant")
	if(cantidad != 0){
		txt.innerHTML = "(" + cantidad + ")";
		//contFavoritos.tinyscrollbar_update();
	}else{
		txt.innerHTML = "&nbsp; &nbsp; &nbsp;";
		//contFavoritos.tinyscrollbar_update();
	}
	//contFavoritos.tinyscrollbar_update();
}
function Delete_Cookie( name, path, domain ) {
    if ( Get_Cookie( name ) ) parent.document.cookie = name + "=" +
        ( ( path ) ? ";path=" + path : "") +
        ( ( domain ) ? ";domain=" + domain : "" ) +
        ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
    contador();
	ajax_loadContent('contFavoritos','actualizaFavoritos.php?id='+name+'remover=1');
}

</script>

<table width="215" border="0" cellspacing="0" cellpadding="2">
<?php
if(count($_SESSION['ids']) > 1){
	 foreach ($_SESSION['ids'] as $valor){ 
			$param=array('id_prop'=>$valor);
			$fotos = $client->call('ListarFotosPropiedad',$param,'');
			if(count($fotos) == 0){
				$foto = "images/noDisponible.gif";
			}else{
				$foto = "http://abm.okeefe.com.ar/fotos_th/" . $fotos[0]['foto'];
			}
?>
  <tr>
    <td width="50" height="33"><img src="<?php echo $foto; ?>" width="50" height="33" alt="Borra Favorito"></td>
    <td><?php echo $txtZona . $valor; ?></td>
    <td width="17" align="center"><img src="images/borraFavorito.gif" width="13" height="14" alt="Borra Favorito" onclick="javascript: Delete_Cookie('id[<?php echo $id_prop; ?>]');"></td>
  </tr>
  <?php } 
}else{
	?>
<tr><td>No existen datos para mostrar</td></tr>
<?php } ?>
</table>

<?php ob_end_flush();?>