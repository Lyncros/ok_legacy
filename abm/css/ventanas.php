<?php
header('content-type:text/css');

$color1 = '#00491B';
$color2 = '#F16221';
$colorTxt = '#00491B';
    
echo <<<FINCSS
@charset "UTF-8";
    
a:hover {
    text-decoration: none;
}
a:link {
    text-decoration: none;
}
a:visited {
    text-decoration: none;
}
a:active {
    text-decoration: none;
}

body {
    padding: 0;
    text-align: left; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
    color: $colorTxt;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    background-color: #FFFFFF;
    margin-top: 0;
    margin-right: auto;
    margin-bottom: 0;
    margin-left: auto;
}
#clearfix{
	clear: both;
	height: 0px;
}
#container {
    margin-top: 0;
    margin-right: auto;
    margin-bottom: 0;
    margin-left: auto;
    text-align: left;
}
#cabezal{
    width: 100%;
    height: 70px;
}
#Encabezado{
	padding-top: 10px;
	float: left;
	width: 300px;
}
#menuEncabezado{
	float: right;
	width: 200px;
	text-align: right;
	padding-top: 50px;
	padding-right: 20px;
}
#menuEncabezado a{
	color: $color2;
	font-weight: bold;
}

#divMenu{
	background-color: #24491D;
	height: 25px;
	width: 100%;
}
#divCuerpo{
    width: 100%;
}
#divLogin {
    width: 980px;
    margin-top: 20px;
    margin-right: auto;
    margin-bottom: 0px;
    margin-left: auto;
    text-align: left;
}
.cd_celda_texto {
    vertical-align:middle;
    color: $color1;
    padding: 3px;
}
.row0 {
    font-weight:normal;
    vertical-align:middle;
    color: #333333;
    height: 20px;
    padding: 3px;
    background-color:#FFF;
}
.row0 a{
    color: #333333;
    text-decoration: none;
}
.row1 {
    font-weight:normal;
    background-color:#f1f1f1;
    vertical-align:middle;
    color: #333333;
    height: 20px;
    padding: 3px;
}
.row1 a{
    color: #333333;
    text-decoration: none;
}
.rowDestacado {
    font-weight:normal;
    background-color:#caf9ca;
    vertical-align:middle;
    color: #333333;
    height: 20px;
    padding: 3px;
}
.rowDestacado a{
    color: #333333;
    text-decoration: none;
}
.rowOportunidad {
    font-weight:normal;
    background-color:#ffcccc;
    vertical-align:middle;
    color: #333333;
    height: 20px;
    padding: 3px;
}
.rowOportunidad a{
    color: #333333;
    text-decoration: none;
}
.rowReservado {
    font-weight:normal;
    background-color:#ffFFcc;
    vertical-align:middle;
    color: #333333;
    height: 20px;
    padding: 3px;
}
.rowReservado a{
    color: #333333;
    text-decoration: none;
}
.cd_celda_titulo {
    font-size:14px;
    font-weight:bold;
    background-color:$color1;
    vertical-align:middle;
    text-align: center;
    color: #FFFFFF;
    height: 20px;
    padding-top: 5px;
    padding-bottom: 5px;
}
.linea {
    background-color: #41545e;
    height: 1px;
}
/*---
.campos {
	background-color: #F7F7F7;
	scrollbar-arrow-color: #12406F;
	vertical-align: middle;
	width: 97%;
	border: none;
	height: 20px;
	color: $color1;
	padding: 2px;
	font-size:12px;
	text-align: left;
}---*/
.campos {
    vertical-align: middle;
    width: 98%;
    color: #333;
    padding: 2px;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    border: thin solid #CCCCCC;
    height: 18px;
}
.campos_area {
    color: #333;
    background-color: #FFF;
    border: thin solid #CCCCCC;
    width: 98%;
    vertical-align: middle;
    padding: 2px;
}
.campos_btn{
    vertical-align: middle;
    width: 100%;
    color: #333;
    padding: 3px;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    border: thin solid #CCCCCC;
    height: 22px;
}
.cd_tabla{
    border: none;
}
.cd_lista_titulo {
    font-weight:bold;
    background-color:$color1;
    vertical-align:middle;
    color: #FFFFFF;
    text-align: center;
    height: 20px;
}
.cd_lista_filtro {
    font-weight:bold;
    background-color:$color1;
    vertical-align:middle;
    color: #FFFFFF;
    text-align: center;
    height: 22px;
}
.cd_celda_menu {
    font-size:12px;
    vertical-align:middle;
    color: #FFFFFF;
    height: 25px;
    text-transform: capitalize;
    font-weight: bold;
    background-color: $color1;
}
.cd_celda_menu a {
    color: #FFF;
    font-weight: bold;
    background-color: $color1;
    margin-right: auto;
    margin-left: auto;
    height: 20px;
    display: block;
    padding-top: 5px;
    padding-left: 5px;
    padding-right: 5px;
    border-left-style: solid;
    border-right-style: solid;
    border-left-width: 1px;
    border-right-width: 1px;
    border-left-color: #ffffff;
    border-right-color: #ffffff;
}
.cd_celda_menu a:hover {
    color:#FFFFFF;
    background-color: #ff3300;
}
.cd_celda_menu a:link {
    text-decoration: none;
}
.cd_celda_menu a:visited {
    text-decoration: none;
}
.cd_celda_menu a:active {
    text-decoration: none;
}
.cd_celda_herr{
    font-size:12px;
    vertical-align:middle;
    color: $color1;
    height: 22px;
    width: 22px;
    border-left: thin solid #999;
    padding: 0px 2px;
    text-transform: capitalize;
    font-weight: bold;
}
.pg_titulo {
    color: #FFF;
    font-size:14px;
    font-weight:bold;
    text-align:left;
    margin-top: 5px;
    background-color: $color2;
    height: 20px;
    padding-top: 2px;
    padding-left: 10px;
}
.pg_subtitulo {
    color: $color2;
    font-size:13px;
    font-weight:bold;
    text-align:left;
    margin-top: 5px;
    margin-bottom: 5px;
    height: 22px;
    padding-top: 2px;
    border-bottom: thin solid $color2;
}
.boton_form{
    vertical-align: middle;
/*    width: 90px;*/
    color: #333;
    padding: 2px;
    font-size:11px;
    font-weight: bold;
    text-align: center;
    background-color: #DFDFDF;
    border: thin solid #CCCCCC;
    height: 22px;
    display: block;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    cursor: pointer;
}
.celda_titu_carac{
    background-color: #999999;
    height: 22px;
    width: 100%;
    vertical-align: middle;
    border: thin #DFDFDF;
    color: #FFF;
    font-weight: bold;
    font-size: 12px;
    padding-left: 10px;
}
#auto_datos{
	width: 100%;
	padding: 10px 5px 10px 10px;
}
#datos{
	width: 100%;
	height: auto;
		
}
#tools{
/*	float: left;*/
}
/* --------------- calendario -------------------- */

.TDCalendario {
    width: 218px;
    padding: 10px;
}
.TDSeparacion {
    background-color: $color1;
    width: 1px;
}
.nombre_dias {
    font-size: 9pt;
    font-weight: bold;
    text-align: center;
}
.mes {
    font-size: 10pt;
    font-weight: bold;
    color: #FFFFFF;
    text-align: center;
}
.otro_mes {
    font-size: 8pt;
    color: #FFFFFF;
}
.otro_mes:hover {
    color:#FFFFFF;
    font-size: 8pt;
}
.otro_mes:link {
    color:#FFFFFF;
    font-size: 8pt;
    vertical-align: middle;
}

.otro_mes:visited {
    color:#FFFFFF;
    font-size: 8pt;
}
.otro_mes:active {
    color:#FFFFFF;
    font-size: 8pt;
}
.calendar {
    border: thin solid $color1;
    width: 198px;
}
.dias {
    font-size: 9pt;
    color: $color1;
    text-align: center;
    height: 22px;
    width: 28px;
}
.dias:link {
    color: $color1;
    text-decoration: none;
}
.dias:active {
    color: $color1;
    text-decoration: none;
}
.dias:visited {
    color: $color1;
    text-decoration: none;
}
.dias:hover {
    color: $color1;
    text-decoration: none;
}
.diaselect {
    font-size: 9pt;
    text-align: center;
    font-weight: bold;
    color: #FFFFFF;
    background-color: $color1;
}
.TRmes {
    background-color: $color1;
    vertical-align: middle;
}

/* ------------------ fin calendario ----------------------- */
/*------------------- horarios -----------------------------*/

.TablaHorarios{
    padding: 10px;
}
.TDobleas {
    background-color: #A2B0D8;
    font-size: 10px;
    color: #FFFFFF;
}
.TDcompromisos {
    background-color: #A2B0D8;
    font-size: 11px;
    color: $color1;
}

/*------------------- fin horarios -----------------------------*/
.obligatorio{
    font-size: 12px;
    color: #999;
    font-weight: bold;
}
.txt_obligatorio{
    color: $color1;
    font-size: 10px;
}
.orden_tab{
/*    font-weight:bold;*/
    background-color:#009639;
    vertical-align:middle;
    color: #FFFFFF;
    text-align: left;
    height: 22px;
    padding-left: 3px;
     text-transform: uppercase;
}
.orden_tab img{
    vertical-align: middle;
}
.orden_tab a{
    color: #ffffff;
    text-decoration: none;
    vertical-align: 10%;
}
.orden_tab a:active{
    color: #ffffff;
    text-decoration: none;
}
.orden_tab a:hover{
    color: #ffffff;
    text-decoration: none;
}
.orden_tab a:visited{
    color: #ffffff;
    text-decoration: none;
}
.orden_tab a:link{
    color: #ffffff;
    text-decoration: none;
}
#menu_buscador{
    width: 180px;
    visibility: visible;
    float: left;
    /*    background-color: #F5F4EE;*/
    background-color: #e1e1e1;
}
#resultado{
    width: 800px;
    float:left;
    margin-left:5px;
}
.td_titu_buscador{
    background-color: $color1;
    height: 22px;
    width: 100%;
    vertical-align: middle;
    border: thin #DFDFDF;
    color: #FFF;
    font-weight: bold;
    font-size: 11px;
    padding-left: 2px;
}
.separador{
    padding: 20px;
}
.separador hr{
    color: #FFF;
    background-color: #FFF;
    border: none;
    height: 1px;
}
.buscado{
    padding: 5px 5px 0px 5px;
}
.titu_filtro {
    font-weight:bold;
    background-color:$color1;
    vertical-align:middle;
    color: #FFFFFF;
    text-align: left;
    padding-left: 5px;
    text-transform: uppercase;
    font-size: 11px;
    padding-top:4px;
    height:18px;
    display:block;
    cursor:pointer;
    background-image: url(../images/fabajo.png);
    background-repeat: no-repeat;
    background-position: 160px center;
    border-top: solid 1px #FFF;
}
.total_filtro {
    font-weight:bold;
    background-color:$color1;
    vertical-align:middle;
    color: #FFFFFF;
    text-align: left;
    padding-left: 5px;
    text-transform: uppercase;
    font-size: 11px;
    padding-top:4px;
    height:18px;
    display:block;
    cursor:pointer;
    background-repeat: no-repeat;
    background-position: 160px center;
    border-top: solid 1px #FFF;
}
.boton_form_filtro{
    vertical-align: middle;
/*    width: 90px;*/
    color: #FFF;
    padding: 0px 3px 2px 3px;
    font-size:11px;
    font-weight: bold;
    text-align: center;
    background-color: $color1;
    border: thin solid #666;
    height: 22px;
    display: block;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    vertical-align: middle;
}
.cantidad_filtro {
    font-weight:bold;
    vertical-align:middle;
    color: $color1;
    text-align: left;
    height: 22px;
    padding-left: 5px;
    border-bottom: #FFF thin solid;
}
.cd_celda_filtro{
    font-weight:bold;
    vertical-align:middle;
    color: $color1;
    text-align: left;
    height: 22px;
    padding-left: 5px;
}
.tabla_blco {
    background-color: #FFF;
}
.detalle_ficha {
    font-size: 11px;
    color: #424227;
    font-weight:bold;
    padding-left: 15px;
}
.data_ficha {
    font-size: 11px;
    color: #424227;
    padding-left: 15px;
}
.superficie_ficha {
    font-size: 18px;
    font-weight: bold;
    color: #646419;
}
.precio_ficha {
    font-size: 18px;
    font-weight: bold;
    color: #8aa700;
}
.tabla_ficha {
    height: 16px;
    background-color: #f5f4ee;
}
.legales_ficha {
    font-size: 10px;
    color: #605a4b;
}
#filaEstado div{
	font-size:11px;
	text-align:center;
}


/*  Campos Dinamicos de clientes --------------------------*/
#div_fam{
    width: 100%;
    margin-top:5px;
}
#div_fam input{
    border: none;
    vertical-align: middle;
    padding: 2px;
}
#div_fam img{
    vertical-align: middle;
    margin-left:5px;
}

.dinFam_nom{
    width: 250px;
    color: #333;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    height: 18px;
}
.dinFam_ape{
    width: 200px;
    color: #333;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    height: 18px;
}
.dinFam_sel{
    width: 90px;
    color: #333;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    height: 22px;
    border: none;
}
.dinFam_fec{
    width: 90px;
    color: #333;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    height: 18px;
}
.dinFam_tnom{
    width: 250px;
    text-align: center;
}
.dinFam_tape{
    width: 200px;
    text-align: center;
}
.dinFam_tsel{
    width: 90px;
    text-align: center;
}
.dinFam_tfec{
    width: 90px;
    font-size:10px;
    text-align: center;
}

/*  Telefonos ---------------------------------------------*/
#div_tel{
    width: 100%;
    margin-top:5px;
}
#div_tel input{
    border: none;
    vertical-align: middle;
    padding: 2px;
}
#div_tel img{
    vertical-align: middle;
    margin-left:5px;
}


.dinTel_pais{
    width: 30px;
    color: #333;
    text-align: left;
    background-color: #FFF;
    height: 18px;
    font-size:12px;
}
.dinTel_area{
    width: 30px;
    color: #333;
    text-align: left;
    background-color: #FFF;
    height: 18px;
    font-size:12px;
}
.dinTel_sel{
    width: 120px;
    color: #333;
    text-align: left;
    border: none;
    background-color: #FFF;
    padding: 2px;
    font-size:12px;
    height: 22px;
    font-size:12px;
}
.dinTel_nro{
    width: 100px;
    color: #333;
    text-align: left;
    background-color: #FFF;
    height: 18px;
    font-size:12px;
}
.dinTel_int{
    width: 35px;
    color: #333;
    text-align: left;
    height: 18px;
    font-size:12px;
}

.dinTel_tpais{
    width: 30px;
    text-align: left;
}
.dinTel_tarea{
    width: 30px;
    text-align: left;
}
.dinTel_tsel{
    width: 116px;
    text-align: center;
}
.dinTel_tnro{
    width: 100px;
    text-align: left;
}
.dinTel_tint{
    width: 36px;
    text-align: left;
}

/*  Medios -----------------------------------*/
#div_mail{
    width: 100%;
    margin-top:5px;
}
#div_mail input{
    border: none;
    vertical-align: middle;
    padding: 2px;
}
#div_mail img{
    vertical-align: middle;
    margin-left:5px;
}

.dinMed_sel{
    width: 80px;
    text-align: left;
}
.dinMed_med{
    width: 249px;
    text-align: left;
}
.dinMed_tsel{
    width: 80px;
    text-align: center;
}
.dinMed_tmed{
    width: 245px;
    text-align: center;
}

/*  Relaciones ----------------------------------*/
.dinRel_sel{
    vertical-align: middle;
    width: 150px;
    color: #333;
    padding: 2px;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    border: thin solid #CCCCCC;
    height: 22px;
}
.dinRel_usr{
    vertical-align: middle;
    width: 300px;
    color: #333;
    padding: 2px;
    font-size:12px;
    text-align: left;
    background-color: #FFF;
    border: thin solid #CCCCCC;
    height: 22px;
}
.dinRel_tsel{
    vertical-align: middle;
    width: 150px;
    color: #fff;
    padding: 2px;
    font-size:12px;
    font-weight: bold;
    text-align: center;
    background-color: $color1;
    border: none;
    height: 18px;
}
.dinRel_tusr{
    vertical-align: middle;
    width: 300px;
    color: #fff;
    padding: 2px;
    font-size:12px;
    font-weight: bold;
    text-align: center;
    background-color: $color1;
    border: none;
    height: 18px;
}

/*NUEVO CLIENTES-------------------------*/
/*------------VISTA CLIENTES NUEVA -----------*/
#tituCliente {
	font-weight: bold;
	color: #FFF;
	background-color: $color1;
	text-transform: uppercase;
	padding-top: 3px;
	height: 20px;
	line-height: normal;
	font-size: 14px;
	padding-left: 5px;
	margin-top: 5px;
}
#subtituCliente {
	font-weight: bold;
	color: $color1;
	padding-top: 3px;
	height: 20px;
	line-height: normal;
	font-size: 14px;
	padding-left: 5px;
	margin-top: 5px;
}
.contenedorCli{
	background-color:#FFF;
	height:auto;
	padding:10px;
}
#buscadorCliente {
	background-color: $color2;
	color: #FFF;
	font-size: 14px;
	font-weight: bold;
	height: 25px;
	vertical-align: middle;
	padding-left: 5px;
        margin-top:3px;
}
#buscadorCliente a{
	color: #FFF;
	font-size: 14px;
	font-weight: bold;
}
#buscadorCliente img{
	vertical-align: middle;
}
#buscadorCliente input{
	color: $color1;
	font-size: 12px;
	padding: 2px;
	border:none;
	width:500px;
	margin-top:3px;
}
#datosCli{
	background-color: #F8F8F8;
	padding: 10px;
}
.nombreCampo{
	height:15px;
	width:95%;
	padding-left:3px;
	font-size:.98em;
}
.datoCampo{
	border: thin solid #CCC;
	background-color: #FFF;
	color: #333;
	padding:3px;
	width: 95%;
}
.datoCampo input{
	background-color: #FFF;
	color: #333;
	width: 100%;
        border: none;
}
#masInfo{
	text-align: center;
	padding: 5px;
	background-color: $color1;
	color: #FFF;
	width: 60px;
	float: right;
	font-size: 12px;
	font-weight: bold;
	border-radius:5px;
	margin-top:5px;
	display:block;
	cursor:pointer;
}
#otrosDatos{
	
}
.col3{
	float: left;
	width:33%;
	margin:0px 0px 10px 2px;
}
.col2{
	float: left;
	width:49%;
	margin:0px 0px 10px 2px;
}
#datos{
}
.nombreCampoArea{
	border-left: thin solid #999;
	border-bottom: thin solid #999;
	height:15px;
	width:98%;
	margin-bottom:2px;
	padding-left:5px;
	font-size:.98em;
}
.datoCampoArea{
	border: thin solid #999;
	background-color: #FFF;
	color: #333;
	padding:3px;
	width: 98%;
	min-height:60px;
}
.tituColCli{
    color: #fff;
    background-color: $color2;
    height: 13px;
    font-size:10px;
    font-weight: bold;
}
FINCSS;
?>