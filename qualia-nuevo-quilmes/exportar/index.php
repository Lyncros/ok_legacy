<?
require ("../comun/comun.php");

$abm = new class_abm ();
$abm->tabla = "contacto";
$abm->registros_por_pagina = 20;
$abm->mostrarNuevo = false;
$abm->orderByPorDefecto = 'fechaAlta DESC';

$abm->campos = array (
		array (
				'campo' => 'fechaAlta',
				'tipo' => 'fecha',
				'exportar' => true,
				'noMostrarEditar' => true,
				'titulo' => 'Fecha recibido' 
		),
		array (
				'campo' => 'nombre',
				'tipo' => 'texto',
				'exportar' => true,
				'titulo' => 'Nombre' 
		),
		array (
				'campo' => 'email',
				'tipo' => 'texto',
				'exportar' => true,
				'titulo' => 'Email' 
		),
		array (
				'campo' => 'tel',
				'tipo' => 'tel',
				'exportar' => true,
				'titulo' => 'Tel' 
		),
		array (
				'campo' => 'consulta',
				'tipo' => 'textarea',
				'exportar' => true,
				'titulo' => 'Consulta' 
		),
		array (
				'campo' => 'utm_source',
				'tipo' => 'texto',
				'exportar' => true,
				'titulo' => 'utm_source' 
		),
		array (
				'campo' => 'utm_campaign',
				'tipo' => 'texto',
				'exportar' => true,
				'titulo' => 'utm_campaign' 
		),
		array (
				'campo' => 'utm_medium',
				'tipo' => 'texto',
				'exportar' => true,
				'titulo' => 'utm_medium' 
		) 
);

$abm->exportar_verificar ();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Mensajes recibidos desde la web</title>

<!-- Estilos -->
<link href="css/abm.css" rel="stylesheet" type="text/css">
</head>
<body>

<?
$abm->generarAbm ("", "Mensajes recibidos desde la web");
?>

</body>
</html>