<?php
/**
 * Clase que genera automaticamente un listado y los formularios que modifican o agregan datos en una tabla de BD
 * 
 * @uses class_paginado.php, class_orderby.php, class_db.php
 * @author Andres Carizza www.andrescarizza.com.ar
 * @version 3.6.3
 *
 * (A partir de la version 3.0 cambia la forma de aplicar los estilos css)
 * (A partir de la version 3.4 cambia de usar MooTools a usar JQuery)
 * (A partir de la version 3.5.8 cambia a UTF-8)
 * 
 * Datos para array de campos: (ver ejemplo de uso más abajo)
 * 
		campo = nombre del campo en la bd
		tipo = tipo de elemento de formulario (texto, bit, textarea, combo, dbCombo, password, upload)
		tipoBuscar = lo mismo que tipo pero solo para el formulario de busqueda
		titulo = texto para el campo en los formularios y listado
		tituloListado = si esta seteado usa este titulo en el listado
		tituloBuscar = si esta seteado usa este titulo en el formulario de búsqueda
		maxLen = maximo de caracteres que permite ingresar el input del formulario
		requerido = el campo es requerido
		datos = Array("key" => "value"...) para el tipo de campo "combo"
		formItem = una funcion de usuario que reciba el parametro $fila. Es para poner un campo especial en el formulario de alta y modificacion para ese campo en particular. Esto es util por ejemplo para poner un editor WUSIWUG.
		colorearValores = Colorea el texto de esta columna en el listado según el valor. Ej: Array("Hombre" => "blue", "Mujer" => "#FF00AE")
		colorearConEtiqueta = boolean. Agrega el class "label" cuando colorea un valor. Por defecto es FALSE
		
		textoBitTrue = Texto que pone cuando el tipo de campo es "bit" y este es true o =1. Si se deja vacio usa el por defecto definido en $this->textoBitTrue
		textoBitFalse = Texto que pone cuando el tipo de campo es "bit" y este es false o =0. Si se deja vacio usa el por defecto definido en $this->textoBitFalse
		ordenInversoBit = Boolean. Si esta en true muestra primero el false en los <select>. Por defecto es false
		
		uploadFunction = Funcion de usuario que se encarga del archivo subido. Recibe los parametros id y tabla. Debe retornar TRUE si la subida se realizo con éxito.
		borrarSiUploadFalla = Boolean. Para el tipo de campo upload. Si falla el upload borra el registro recien creado. Por defecto es FALSE. No tiene efecto en el update.
		
		buscar = boolean. Si esta en true permite buscar por ese campo. No funciona si se usa la funcion generarAbm() con un query. (NOTA: el buscador funciona verificando variables de $_REQUEST con los nombres de los campos con prefijo "c_". Si se quisiera hacer un formulario de busqueda independiente sin usar el de la class se puede hacer usando los mismos nombres de los campos, o sea con el prefijo "c_".)
		buscarOperador = Operador que usa en el where. Ej. = , LIKE
		buscarUsarCampo = Si esta seteado usa ese campo en el where para buscar
		customFuncionBuscar = Funcion del usuario para poner un HTML especial en el lugar donde iria el form item del formulario de busqueda. La funcion no recibe ningun parametro.
		
		sqlQuery = para el tipo de campo dbCombo
		campoValor = (obligatorio para el tipo de campo dbCombo): campo de la tabla izquierda. Es el que tiene el valor que va en <option value='{acá}'>
		campoTexto = (obligatorio para el tipo de campo dbCombo): campo de la tabla izquierda que tiene el texto que se muestra en el listado y que va en <option value=''>{acá}</option>
		joinTable = (obligatorio para el tipo de campo dbCombo): tabla para hacer join en el listado (es la misma tabla de sqlQuery)
		joinCondition = (obligatorio para el tipo de campo dbCombo): Ej. INNER, LEFT. para hacer join en el listado. Si se deja vacío por defecto es INNER
		
		campoOrder = campo que usa para hacer el order by al cliquear el titulo de la columna, esto es ideal para cuando se usa un query en la funcion generarAbm()
		valorPredefinido = valor predefinido para un campo en el formulario de alta
		incluirOpcionVacia = para los tipo "combo" o "dbCombo", si esta en True incluye <option value=''></option>
		adicionalInput = para agregar html dentro de los tags del input. <input type='text' {acá}>
		
		centrarColumna = centrar los datos de la columna en el listado
		anchoColumna = permite especificar un ancho a esa columna en el listado (ej: 80px)
		
		noEditar = no permite editar el campo en el formulario de edicion
		noListar = no mostrar el campo en el listado
		noNuevo = no incuye ni muestra ese campo en el formulario de alta
		noMostrarEditar = no muestra el campo en el formulario de edicion
		noOrdenar = no permite ordenar por ese campo haciendo click en el titulo de la columna
		
		customPrintListado = sprintf para imprimir en el listado. %s será el valor del campo y {id} se remplaza por el Id del registro definido para la tabla. Ej: <a href='ver_usuario.php?id={id}' target='_blank' title='Ver usuario'>%s</a>
		customEvalListado = para ejecutar PHP en cada celda del listado sin imprimir ni siquiera los tags <td></td>. Las variables utilizables son $id y $valor. Ej: echo "<td align='center'>"; if($valor=="admin"){echo "Si";}else{echo "No";}; echo "</td>";
		customFuncionListado = para ejecutar una funcion del usuario en cada celda del listado sin imprimir ni siquiera los tags <td></td>. La funcion debe recibir el parametro $fila que contendra todos los datos de la fila
		customFuncionValor = para ejecutar una funcion del usuario en el valor antes de usarlo para el query sql en las funciones de INSERT Y UPDATE. La funcion debe recibir el parametro $valor y retornar el nuevo valor
		
		exportar = Boolean. Incluye ese campo en la exportación. Si al menos uno de los campos lo incluye entonces aparecen los iconos de exportar. ATENCION: Leer referencia de la funcion exportar_verificar()
		separador = String con el texto para mostrar en el separador. El separador aparece en los formularios de edicion y alta. Es un TH colspan='2' para separar la informacion visualmente.
 * 
 * Ejemplo de uso:
 * 
   $abm = new class_abm();
	 $abm->tabla = "usuarios";
	 $abm->registros_por_pagina = 40;
	 $abm->textoTituloFormularioAgregar = "Agregar usuario";
	 $abm->textoTituloFormularioEdicion = "Editar usuario";
 	 $abm->campos = array(
				array("campo" => "usuario", 
							"tipo" => "texto", 
							"titulo" => "Usuario", 
							"maxLen" => 30,
							"customPrintListado" => "<a href='ver_usuario.php?id={id}' target='_blank' title='Ver usuario'>%s</a>",
							"buscar" => true
							), 
				array("campo" => "pass", 
							"tipo" => "texto", 
							"titulo" => "Contraseña", 
							"maxLen" => 30
							),
				array("campo" => "activo", 
							"tipo" => "bit", 
							"titulo" => "Activo", 
							"centrarColumna" => true,
							"valorPredefinido" => "0"
							),
				array("campo" => "nivel", 
							"tipo" => "combo", 
							"titulo" => "Admin", 
							"datos" => array("admin"=>"Si", ""=>"No"),
							"customEvalListado" => 'echo "<td align=\"center\">"; if($valor=="admin"){echo "Si";}else{echo "No";}; echo "</td>";'
							),
				array("campo" => "paisId", 
							"tipo" => "dbCombo", 
							"sqlQuery" => "SELECT * FROM paises ORDER BY pais", 
							"campoValor" => "id", 
							"campoTexto" => "pais",
							"joinTable" => "paises", 
							"joinCondition" => "LEFT", 
							"titulo" => "País",
							"incluirOpcionVacia" => true
							),
				array("campo" => "email", 
							"tipo" => "textarea", 
							"titulo" => "Email", 
							"maxLen" => 70,
							"noOrdenar" => true
							),
				array("separador" => "Un separador"
							),
				array("campo" => "donde", 
							"tipo" => "combo", 
							"titulo" => "Donde nos conociste?", 
							"tituloListado" => "Donde", 
							"datos" => array("google"=>"Por Google", "amigo"=>"Por un amigo", "publicidad"=>"Por una publicidad", "otro"=>"Otro"),
							"colorearValores" => array('google'=>'#4990D7', 'amigo'=>'#EA91EA'), 
							),
				array("campo" => "ultimoLogin", 
							"tipo" => "texto", 
							"titulo" => "Ultimo login",
							"noEditar" => true, 
							"noListar" => true,
							"noNuevo" => true
							)
				);
		$abm->generarAbm("", "Administrar usuarios");
		
		
	Ejemplo para incluir una columna adicional personalizada en el listado:
	
		array("campo" => "", 
					"tipo" => "", 
					"titulo" => "Fotos",
					"customEvalListado" => 'echo "<td align=\"center\"><a href=\"admin_productos_fotos.php?productoId=$fila[id]\"><img src=\"img/camara.png\" border=\"0\" /></a></td>";'
					)
  */

class class_abm{
	/**
	 * Nombre de la tabla en BD
	 */
	public $tabla;
	
	/**
	 * Campo ID de la tabla
	 */
	public $campoId="id";
	
	/**
	 * Permite editar el campo que corresponde al ID, Por lo general no permite editarlo pq suele ser autoincremental, por defecto ni se muestra en el abm pq al usuario no le interesa, pero se puede forzar que sea editable con este parametro
	 */
	public $campoIdEsEditable=false;
	
	/**
	 * Los campos de la BD y preferencias para cada uno. (Ver el ejemplo de la class)
	 */
	public $campos;
	
	/**
	 * Valor del atributo method del formulario
	 */
	public $formMethod="POST";
	
	/**
	 * Agrega el atributo autofocus al primer campo del formulario de alta o modificacion
	 */
	public $autofocus = true;
	
	/**
	 * Para poder agregar código HTML en la botonera del listado, antes de los iconos "Exportar" y "Agregar"
	 */
	public $agregarABotoneraListado;
	
	/**
	 * Metodo que usa para hacer los redirect "header" (si no se envio contenido antes) o "html" de lo contrario
	 */
	public $metodoRedirect = "html";
	
	/**
	 * Texto que muestra el boton submit del formulario Nuevo
	 */
	public $textoBotonSubmitNuevo="Guardar";

	/**
	 * Texto que muestra el boton submit del formulario Modificar
	 */
	public $textoBotonSubmitModificar="Guardar";

	/**
	 * Texto que muestra el boton de Cancelar
	 */
	public $textoBotonCancelar="Cancelar";
	
	/**
	 * Texto que muestra cuando la base de datos retorna registro duplicado al hacer un insert. Si se deja el string vacio entonces muestra el mensaje de error del motor de bd.
	 */
	public $textoRegistroDuplicado="Uno de los datos está duplicado y no puede guardarse en la base de datos";
	
	/**
	 * Texto por defecto que se usa cuando el tipo de campo es "bit"
	 */
	public $textoBitTrue="SI";
	
	/**
	 * Texto por defecto que se usa cuando el tipo de campo es "bit"
	 */
	public $textoBitFalse="NO";
	
	/**
	 * Para asignar una acción diferente al boton de Cancelar del formulario de Edicion y Nuevo
	 */
	public $cancelarOnClickJS="";

	public $textoElRegistroNoExiste="El registro no existe. <A HREF='javascript:history.back()'>[Volver]</A>";

	public $textoNoHayRegistros="No hay registros para mostrar";
	
	public $textoNoHayRegistrosBuscando="No hay resultados para la búsqueda";
	
	/** Titulo del formulario de edicion **/
	public $textoTituloFormularioEdicion;
	
	/** Titulo del formulario de agregar **/
	public $textoTituloFormularioAgregar;
	
	/** Titulo del formulario de busqueda **/
	public $textoTituloFormularioBuscar = "Búsqueda";

	/** Muestra los encabezados de las columnas en el listado **/
	public $mostrarEncabezadosListado = true;
	
	/** Muestra el total de registros al final del listado **/
	public $mostrarTotalRegistros = true;
	
	/**
	 * Pagina a donde se redireccionan los formularios. No setear a menos que seapas lo que estas haciendo.
	 */
	public $formAction="";
	
	public $registros_por_pagina = 30;
	
	/** para agregar atributos al tag **/
	public $adicionalesForm;
	
	/** para agregar atributos al tag **/
	public $adicionalesTable;
	
	/** para agregar atributos al tag **/
	public $adicionalesTableListado;
	
	/** para agregar atributos al tag **/
	public $adicionalesSubmit;
	
	/** Ejemplo: AND userId=2 **/
	public $adicionalesWhereUpdate;
	
	/** Ejemplo: AND userId=2 **/
	public $adicionalesWhereDelete;
	
	/** Ejemplo: , userId=2 **/
	public $adicionalesInsert;
	
	/** Ejemplo: AND userId=2 (aplicable siempre y cuando no sea un select custom) **/
	public $adicionalesSelect;
	
	/** Ejemplo: , campo2, campo3, campo4 Esto es últil cuando se necesita traer un campo para usar durante el listado y no esta como visible **/
	public $adicionalesCamposSelect;

	/** Genera el query del listado usando ese string, de esta manera: SELECT $sqlCamposSelect FROM... Esto es util por ejemplo cuando se necesitan hacer sub select **/
	public $sqlCamposSelect;
	
	/** Campo order by por defecto para los select */
	public $orderByPorDefecto;
	
	/** Funcion que se ejecuta antes al borrar un registro. Ej: callbackFuncDelete = "borrarUsuario" (donde borrarUsuario es una funcion que debe recibir los parametros $id y $tabla) **/
	public $callbackFuncDelete;
	
	/** Funcion que se ejecuta despues al actualizar un registro. Ej: callbackFuncUpdate = "actualizarDatosUsuario" (donde actualizarDatosUsuario es una funcion que debe recibir los parametros $id, $tabla, $fueAfectado) **/
	public $callbackFuncUpdate;
	
	/** Funcion que se ejecuta despues de insertar un registro. Ej: callbackFuncInsert = "crearCarpetaUsuario" (donde crearCarpetaUsuario es una funcion que debe recibir los parametros $id y $tabla) **/
	public $callbackFuncInsert;

	/** Cantidad de filas total que retorno el query de listado. NOTA: Tiene que haberse llamado antes la funcion que genera el ABM. **/
	public $totalFilas;
	
	/** Para ejecutar PHP en cada tag <TR {aca}>. Esta disponible el array $fila. Ejemplo: if($fila["nivel"]=="admin")echo "style='background:red'"; **/
	public $evalEnTagTR;
	
	/** texto del confirm() antes de borrar (escapar las comillas dobles si se usan) **/
	public $textoPreguntarBorrar = "¿Confirma que desea borrar el elemento seleccionado?";
	
	/** Muestra el boton Editar en el listado */
	public $mostrarEditar=true;
	
	/** Muestra el boton Nuevo en el listado */
	public $mostrarNuevo=true;
	
	/** Muestra el boton Borrar en el listado */
	public $mostrarBorrar=true;
	
	/** Muestra los datos del listado */
	public $mostrarListado=true;

	/** El titulo de la columna Editar del listado **/
	public $textoEditarListado="Editar";

	/** El titulo de la columna Borrar del listado **/
	public $textoBorrarListado="Borrar";
	
	/** Texto del boton submit del formulario de busqueda **/
	public $textoBuscar="Buscar";
	
	/** Texto del boton limpiar del formulario de busqueda **/
	public $textoLimpiar="Limpiar";
	
	/** La palabra (plural) que pone al lado del total del registros en el pie de la tabla del listado **/
	public $textoStrRegistros="registros";
	
	/** La palabra (singular) que pone al lado del total del registros en el pie de la tabla del listado **/
	public $textoStrRegistro="registro";
	
	/** El palabra "Total" que pone al lado del total del registros en el pie de la tabla del listado **/
	public $textoStrTotal="Total";
	
	/** Texto para el title de los links de los numeros de pagina **/
	public $textoStrIrA="Ir a la página";
	
	/** Cantidad de columnas de inputs en el formulario de busqueda **/
	public $columnasFormBuscar=2;
	
	/** Redireccionar a $redireccionarDespuesInsert despues de hacer un Insert. Ejemplo: archivo.php?id=%d (si el ID de la tabla no fuera un numero usar %s) **/
	public $redireccionarDespuesInsert;
	
	/** Redireccionar a $redireccionarDespuesUpdate despues de hacer un Update. Ejemplo: archivo.php?id=%d (si el ID de la tabla no fuera un numero usar %s) **/
	public $redireccionarDespuesUpdate;
	
	/** Redireccionar a $redireccionarDespuesDelete despues de hacer un Delete. Ejemplo: archivo.php?id=%d (si el ID de la tabla no fuera un numero usar %s) **/
	public $redireccionarDespuesDelete;
	
	/** Icono editar del listado. */
	public $iconoEditar="<a href=\"%s\"><img src='img/editar.gif' title='Editar' alt='Editar' border='0' /></a>";

	/** Icono borrar del listado.  */
	public $iconoBorrar="<a href=\"javascript:void(0)\" onclick=\"%s\"><img src='img/eliminar.gif' title='Eliminar' alt='Eliminar' border='0' /></a>";
	
	/** Icono de Agregar para crear un registro nuevo. */
	public $iconoAgregar="<input type='button' class='btnAgregar' value='Agregar' title='Atajo: ALT+A' accesskey='a' onclick='window.location=\"%s\"'/>";
	
	/** Icono de exportar a Excel. */
	public $iconoExportarExcel="<input type='button' class='btnExcel' title='Exportar a Excel' onclick='window.location=\"%s\"'/>";
	
	/** Icono de exportar a CSV. */
	public $iconoExportarCsv="<input type='button' class='btnCsv' title='Exportar a CSV' onclick='window.location=\"%s\"'/>";

	/** Texto sprintf para el mensaje de campo requerido **/
	public $textoCampoRequerido = "El campo \"%s\" es requerido.";

	/** Lo que agrega al lado del nombre del campo para indicar que es requerido **/
	public $indicadorDeCampoRequerido = "<div class='indRequerido'></div>";
	
	/** Aparece después del nombre del campo en los formularios de Alta y Modificacion. Ej: ":" **/
	public $separadorNombreCampo="";

	/** Codigo JS para poner en window.onload para cada uno de los campos de fecha **/
	public $jsIniciadorCamposFecha = '
	<script>
	$(function(){
		$("#%IDCAMPO%").datepicker({
			regional: "es",
			showAnim: "fade",
			dateFormat: "yy-mm-dd",
			altField: "#display_%IDCAMPO%",
			altFormat: "d MM, yy"
		});
		$("#display_%IDCAMPO%").focus(function(){$("#%IDCAMPO%").datepicker("show")});
		if("%VALOR%" != "") $("#%IDCAMPO%").datepicker("setDate", "%VALOR%");
	});
	</script>
	';
	
	/** Adicional para el atributo class de los input para el chequeo de los campos requeridos **/
	public $chequeoInputRequerido = 'validate[required]';

	/** Formato de fecha a utilizar en los campos tipo fecha del listado. Usa la funcion date() de PHP **/
	public $formatoFechaListado = "d/m/Y";
	
	/** Indica si colorea las filas del listado cuando se pasa por arriba con el puntero **/
	public $colorearFilas = true;
	
	/** Color de la fila del listado cuando se para el puntero por arriba (ver $colorearFilas) **/
	public $colorearFilasColor = '#FFFFD4';
	
	/** Nombre que le pone al archivo que exporta (no incluir la extensión) **/
	public $exportar_nombreArchivo = "exportar";
	
	/** El caracter separador de campos cuando exporta CSV **/
	public $exportar_csv_separadorCampos = ",";

	/** El caracter delimitador de campos cuando exporta CSV **/
	public $exportar_csv_delimitadorCampos = "\"";
	
	/** Usar este query sql para la función de exportar **/
	public $exportar_sql;
	
	/** Los formatos en los que se puede exportar, o sea los botones que muestra (siempre y cuando haya campos con exportar=true) **/
	public $exportar_formatosPermitidos = array('excel', 'csv');

	/** El JS que se agrega cuando un campo es requerido **/
	public $jsIniciadorChequeoForm = '
		<script type="text/javascript">
		$(function(){
		  $("#formularioAbm").validationEngine({promptPosition:"topLeft"});
		});
		</script>
	';

	public $jsHints = '
		<script type="text/javascript">
		$( document ).tooltip({
			position: {
				my: "center bottom-20",
				at: "center top",
				using: function( position, feedback ) {
					$( this ).css( position );
					$( "<div>" )
						.addClass( "arrow" )
						.addClass( feedback.vertical )
						.addClass( feedback.horizontal )
						.appendTo( this );
				}
			}
		});
		</script>
	';
	

	/**
	 * Para saber que formulario está mostrando (listado, alta, editar, dbDelete, dbUpdate, dbInsert), esto es util cuando queremos hacer diferentes en la pagina segun el estado.
	 *
	 */
	public function getEstadoActual(){
		if ($_GET[abm_nuevo]) {
			
			return "alta";
			
		} elseif (isset($_GET[abm_editar])) {
			
			return "editar";
			
		} elseif (isset($_GET[abm_borrar])) {
			
			return "dbDelete";
			
		} elseif (isset($_GET[abm_exportar])) {
			
			return "exportar";
			
		} elseif($this->formularioEnviado()) {
			
			if ($_GET['abm_modif']) {
				
				return "dbUpdate";
				
			}elseif ($_GET['abm_alta']){
				
				return "dbInsert";
			}
			
		} else {
			
			return "listado";
			
		}
	}

	public function generarFormAlta($titulo=""){
		global $db;
		
		$_POST = $this->limpiarEntidadesHTML($_POST);

		//genera el query string de variables previamente existentes
		$get = $_GET;
		unset($get[abm_nuevo]);
		$qsamb = http_build_query($get);
		if($qsamb!="") $qsamb = "&".$qsamb;
		
		//agregar script para inicar FormCheck ?
		foreach($this->campos as $campo){
			if($campo[requerido]){
				echo $this->jsIniciadorChequeoForm;
				break;
			}
		}

		//agregar script para inicar los Hints ?
		foreach($this->campos as $campo){
			if($campo[hint] != ""){
				echo $this->jsHints;
				break;
			}
		}
		
		echo "<div class='mabm'>";
		if (isset($_GET[abmsg])) {
			echo "<div class='merror'>".urldecode($_GET[abmsg])."</div>";
		}
		echo "<form enctype='multipart/form-data' method='".$this->formMethod."' id='formularioAbm' action='".$this->formAction."?abm_alta=1$qsamb' $this->adicionalesForm> \n";
		echo "<input type='hidden' name='abm_enviar_formulario' value='1' /> \n";
		echo "<table class='mformulario' $this->adicionalesTable> \n";
		if (isset($titulo) or isset($this->textoTituloFormularioAgregar)) {
			echo "<thead><tr><th colspan='2'>".(isset($this->textoTituloFormularioAgregar) ? $this->textoTituloFormularioAgregar : $titulo)."&nbsp;</th></tr></thead>";
		}
		
		$i=0;
		foreach($this->campos as $campo){
			
			if($campo[noNuevo] == true) continue;
			if($campo[tipo] == '' and $campo[formItem] == '' and !isset($campo[separador])) continue;
			
			$i++;
			if ($i==1 and $this->autofocus) {
				$autofocusAttr = "autofocus='autofocus'";
			}else{
				$autofocusAttr = "";
			}
			
			if ($campo[requerido]) {
				$requerido = $this->chequeoInputRequerido;
			}else{
				$requerido = "";
			}
			
			echo "<tr> \n";
			
			if (isset($campo[separador])) {
				
				echo "<th colspan='2' class='separador'>".$campo[separador]."&nbsp;</th> \n";
				
			}else{
				
				echo "<th>".($campo[titulo]!=''?$campo[titulo]:$campo[campo]).$this->separadorNombreCampo.($campo[requerido] ? " ".$this->indicadorDeCampoRequerido : "")."</th> \n";
				
				echo "<td> \n";
				
				if ($campo[formItem] != "" and function_exists($campo[formItem])) {
          
					call_user_func_array($campo[formItem], array($fila));
          
				}else{
          
					switch ($campo[tipo]) {
						case "texto":
							echo "<input type='text' name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr value='".($_POST[$campo[campo]] != "" ? $_POST[$campo[campo]] : $campo[valorPredefinido] )."' ".($campo[maxLen]>0 ? "maxlength='$campo[maxLen]'" : "")." class='input-text $requerido' ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]/> \n";
							break;
							
						case "password":
							echo "<input type='password' name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr value='".($_POST[$campo[campo]] != "" ? $_POST[$campo[campo]] : $campo[valorPredefinido] )."' ".($campo[maxLen]>0 ? "maxlength='$campo[maxLen]'" : "")." class='input-text $requerido' ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]/> \n";
							break;
							
						case "textarea":
							echo "<textarea name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-textarea $requerido' ".($campo[maxLen]>0 ? "maxlength='$campo[maxLen]'" : "")." ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]>".($_POST[$campo[campo]] != "" ? $_POST[$campo[campo]] : $campo[valorPredefinido] )."</textarea>\n";
							break;
							
						case "dbCombo":
							echo "<select name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-select $requerido' ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]> \n";
							if($campo[incluirOpcionVacia]) echo "<option value=''></option> \n";
							
							$result = $db->query($campo[sqlQuery]);
							while ($fila = $db->fetch_array($result)) {
								if ((isset($_POST[$campo[campo]]) and $_POST[$campo[campo]] == $fila[$campo[campoValor]]) or $campo[valorPredefinido] == $fila[$campo[campoValor]]) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='".$fila[$campo[campoValor]]."' $sel>".$fila[$campo[campoTexto]]."</option> \n";
							}
							echo "</select> \n";
							break;
							
						case "combo":
							echo "<select name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-select $requerido' ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]> \n";
							if($campo[incluirOpcionVacia]) echo "<option value=''></option> \n";
							
							foreach ($campo[datos] as $valor => $texto) {
								if ((isset($_POST[$campo[campo]]) and $_POST[$campo[campo]] == $valor) or $campo[valorPredefinido] == $valor) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='$valor' $sel>$texto</option> \n";
							}
							echo "</select> \n";
							break;
							
						case "bit":
							echo "<select name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-select $requerido' ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]> \n";
							
							if ($campo[ordenInversoBit]) {
								if ((isset($_POST[$campo[campo]]) and $_POST[$campo[campo]] == false) or $campo[valorPredefinido] == false) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='0' $sel>".($campo[textoBitFalse] != "" ? $campo[textoBitFalse] : $this->textoBitFalse)."</option> \n";
								
								if ((isset($_POST[$campo[campo]]) and $_POST[$campo[campo]] == true) or $campo[valorPredefinido] == true) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='1' $sel>".($campo[textoBitTrue] != "" ? $campo[textoBitTrue] : $this->textoBitTrue)."</option> \n";
							
							}else{
	  
								if ((isset($_POST[$campo[campo]]) and $_POST[$campo[campo]] == true) or $campo[valorPredefinido] == true) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='1' $sel>".($campo[textoBitTrue] != "" ? $campo[textoBitTrue] : $this->textoBitTrue)."</option> \n";
								
								if ((isset($_POST[$campo[campo]]) and $_POST[$campo[campo]] == false) or $campo[valorPredefinido] == false) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='0' $sel>".($campo[textoBitFalse] != "" ? $campo[textoBitFalse] : $this->textoBitFalse)."</option> \n";					
							}
							
							echo "</select> \n";
							break;
		
						case "fecha":
								$valor = $_POST[$campo[campo]] != "" ? $_POST[$campo[campo]] : $campo[valorPredefinido];
								if(strlen($valor) > 10) $valor = substr($valor, 0, 10); //sacar hora:min:seg
								if($valor == '0000-00-00') $valor = "";
								$jsTmp = str_replace('%IDCAMPO%', $campo[campo], $this->jsIniciadorCamposFecha);
								$jsTmp = str_replace('%VALOR%', $valor, $jsTmp);
								
								echo $jsTmp;
								echo "<input type='text' style='position:absolute' name='".$campo[campo]."' id='".$campo[campo]."' value='".($fila[$campo[campo]] != "" ? $fila[$campo[campo]] : $campo[valorPredefinido] )."'/> \n";
								echo "<input type='text' style='position:relative;top:0px;left;0px' $autofocusAttr name='display_".$campo[campo]."' id='display_".$campo[campo]."' class='input-fecha $requerido' $disabled $campo[adicionalInput] ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." readonly='readonly'/> \n";
								break;
							
						case "upload":
							echo "<input type='file' name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='$requerido' ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]/> \n";
							break;
					
						default:
							echo $campo[nombre];
							break;
		
					}
          
				}
				
				echo "</td> \n";
				
			}
			
			echo "</tr> \n";
			
		}
		echo "<tfoot>";
		echo "	<tr>";
		echo "		<th colspan='2'><div class='divBtnCancelar'><input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c' value='$this->textoBotonCancelar' onclick=\"".($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'")."\"/></div> <div class='divBtnAceptar'><input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G' value='$this->textoBotonSubmitNuevo' $this->adicionalesSubmit /></div></th>";
		echo "	</tr>";
		echo "</tfoot>";

		echo "</table> \n";
		echo "</form> \n";
		echo "</div>";
	}
	
	public function generarFormModificacion($id, $titulo=""){
		global $db;
		
		//por cada campo...
		for ($i=0;$i<count($this->campos);$i++){
			if($this->campos[$i][campo] == "") continue;
			if($this->campos[$i][noMostrarEditar] == true) continue;
			if($this->campos[$i][tipo] == "upload") continue;
			
			//campos para el select
			if($camposSelect != "")$camposSelect .= ", ";
			$camposSelect .= $this->campos[$i][campo];
		}
		
		//hace el select para mostrar los datos del formulario de edicion
		$id = $this->limpiarParaSql($id);
		$result = $db->query("SELECT $this->campoId, $camposSelect FROM ".$this->tabla." WHERE ".$this->campoId."='".$id."'");
		if($db->num_rows($result)==0){
			echo $this->textoElRegistroNoExiste;
			return;
		}
		$fila = $db->fetch_array($result);
		
		$fila = $this->limpiarEntidadesHTML($fila);

		//genera el query string de variables previamente existentes
		$get = $_GET;
		unset($get[abm_editar]);
		$qsamb = http_build_query($get);
		if($qsamb!="") $qsamb = "&".$qsamb;
		
		//agregar script para inicar FormCheck ?
		foreach($this->campos as $campo){
			if($campo[requerido]){
				echo $this->jsIniciadorChequeoForm;
				break;
			}
		}

		//agregar script para iniciar los Hints ?
		foreach($this->campos as $campo){
			if($campo[hint] != ""){
				echo $this->jsHints;
				break;
			}
		}
		
		echo "<div class='mabm'>";
		if (isset($_GET[abmsg])) {
			echo "<div class='merror'>".urldecode($_GET[abmsg])."</div>";
		}
		echo "<form enctype='multipart/form-data' method='".$this->formMethod."' id='formularioAbm' action='".$this->formAction."?abm_modif=1&$qsamb' $this->adicionalesForm> \n";
		echo "<input type='hidden' name='abm_enviar_formulario' value='1' /> \n";
		echo "<input type='hidden' name='abm_id' value='".$id."' /> \n";
		echo "<table class='mformulario' $this->adicionalesTable> \n";
		if (isset($titulo) or isset($this->textoTituloFormularioEdicion)) {
			echo "<thead><tr><th colspan='2'>".(isset($this->textoTituloFormularioEdicion) ? $this->textoTituloFormularioEdicion : $titulo)."&nbsp;</th></tr></thead>";
		}
		
		$i=0;
		
		//por cada campo... arma el formulario
		foreach($this->campos as $campo){

			if($campo[noMostrarEditar] == true) continue;
			if($campo[tipo] == '' and $campo[formItem] == '' and !isset($campo[separador])) continue;
			
			$i++;
			if ($i==1 and $this->autofocus) {
				$autofocusAttr = "autofocus='autofocus'";
			}else{
				$autofocusAttr = "";
			}
			
			if($campo[noEditar] == true){
				$disabled = "disabled='disabled'";
			}else{
				$disabled = "";
			}
			
			if ($campo[requerido]) {
				$requerido = $this->chequeoInputRequerido;
			}else{
				$requerido = "";
			}
			
			echo "<tr> \n";
			
			if (isset($campo[separador])) {
				
				echo "<th colspan='2' class='separador'>".$campo[separador]."&nbsp;</th> \n";
				
			}else{
				
				echo "<th>".($campo[titulo]!=''?$campo[titulo]:$campo[campo]).$this->separadorNombreCampo.($campo[requerido] ? " ".$this->indicadorDeCampoRequerido : "")."</th> \n";
				
				echo "<td> \n";
        
				if ($campo[formItem] != "" and function_exists($campo[formItem])) {
          
					call_user_func_array($campo[formItem], array($fila));
          
				}else{
        
					switch ($campo[tipo]) {
						case "texto":
							echo "<input type='text' name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-text $requerido' $disabled value='".$fila[$campo[campo]]."' ".($campo[maxLen]>0 ? "maxlength='$campo[maxLen]'" : "")." ".(($campo[campo]==$this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "")." ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]/> \n";
							break;
							
						case "password":
							echo "<input type='password' name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-text $requerido' $disabled value='".$fila[$campo[campo]]."' ".($campo[maxLen]>0 ? "maxlength='$campo[maxLen]'" : "")." ".(($campo[campo]==$this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "")." ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]/> \n";
							break;
							
						case "textarea":
							echo "<textarea name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr $disabled class='input-textarea $requerido' ".($campo[maxLen]>0 ? "maxlength='$campo[maxLen]'" : "")." ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]>".$fila[$campo[campo]]."</textarea>\n";
							break;
							
						case "dbCombo":
							echo "<select name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-select $requerido' $disabled $campo[adicionalInput]> \n";
							if($campo[incluirOpcionVacia]) echo "<option value=''></option> \n";
							
							$resultCombo = $db->query($campo[sqlQuery]);
							while ($filaCombo = $db->fetch_array($resultCombo)) {
								$filaCombo = $this->limpiarEntidadesHTML($filaCombo);
								if ($filaCombo[$campo[campoValor]] == $fila[$campo[campo]]) {
									$selected = "selected";
								}else{
									$selected = "";
								}
								echo "<option value='".$filaCombo[$campo[campoValor]]."' $selected>".$filaCombo[$campo[campoTexto]]."</option> \n";
							}
							echo "</select> \n";
							break;
							
						case "combo":
							echo "<select name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-select $requerido' $disabled ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]> \n";
							if($campo[incluirOpcionVacia]) echo "<option value=''></option> \n";
							
							foreach ($campo[datos] as $valor => $texto) {
								if ($fila[$campo[campo]] == $this->limpiarEntidadesHTML($valor)) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='$valor' $sel>$texto</option> \n";
							}
							echo "</select> \n";
							break;
							
						case "bit":
							echo "<select name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='input-select $requerido' $disabled ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]> \n";
							
							if ($campo[ordenInversoBit]) {
								if (!$fila[$campo[campo]]) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='0' $sel>".($campo[textoBitFalse] != "" ? $campo[textoBitFalse] : $this->textoBitFalse)."</option> \n";
								
								if ($fila[$campo[campo]]) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='1' $sel>".($campo[textoBitTrue] != "" ? $campo[textoBitTrue] : $this->textoBitTrue)."</option> \n";
							
							}else{
								
								if ($fila[$campo[campo]]) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='1' $sel>".($campo[textoBitTrue] != "" ? $campo[textoBitTrue] : $this->textoBitTrue)."</option> \n";
								
								if (!$fila[$campo[campo]]) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='0' $sel>".($campo[textoBitFalse] != "" ? $campo[textoBitFalse] : $this->textoBitFalse)."</option> \n";					
							}
							
							echo "</select> \n";
							break;
		
						case "fecha":
								$valor = $fila[$campo[campo]];
								if(strlen($valor) > 10) $valor = substr($valor, 0, 10); //sacar hora:min:seg
								if($valor == '0000-00-00') $valor = "";
								$jsTmp = str_replace('%IDCAMPO%', $campo[campo], $this->jsIniciadorCamposFecha);
								$jsTmp = str_replace('%VALOR%', $valor, $jsTmp);
								
								echo $jsTmp;
								echo "<input type='text' style='position:absolute' name='".$campo[campo]."' id='".$campo[campo]."' value='".($fila[$campo[campo]] != "" ? $fila[$campo[campo]] : $campo[valorPredefinido] )."'/> \n";
								echo "<input type='text' style='position:relative;top:0px;left;0px'  $autofocusAttr name='display_".$campo[campo]."' id='display_".$campo[campo]."' class='input-fecha $requerido' $disabled ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput] readonly='readonly'/> \n";
								break;
							
						case "upload":
							echo "<input type='file' name='".$campo[campo]."' id='".$campo[campo]."' $autofocusAttr class='$requerido' ".($campo[hint] != "" ? 'title="'.$campo[hint].'"' : "")." $campo[adicionalInput]/> \n";
							break;
					
						default:
								echo $campo[nombre];
							break;
					}
          
				}
	
				echo "</td> \n";
				
			}
			
			echo "</tr> \n";
			
		}

		echo "<tfoot>";
		echo "	<tr>";
		echo "		<th colspan='2'><div class='divBtnCancelar'><input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c' value='$this->textoBotonCancelar' onclick=\"".($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'")."\"/></div> <div class='divBtnAceptar'><input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G' value='$this->textoBotonSubmitModificar' $this->adicionalesSubmit /></div></th>";
		echo "	</tr>";
		echo "</tfoot>";

		echo "</table> \n";
		echo "</form> \n";
		echo "</div>";
	}
	
	/**
	 * Funcion que exporta datos a formatos como Excel o CSV
	 *
	 * @param string $formato (uno entre: excel, csv)
	 */
	private function exportar($formato){
		global $db;
		
		if (strtolower($formato) == 'excel') {
			
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename={$this->exportar_nombreArchivo}.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			echo "<table border='1'>\n";
			echo "	<tr>\n";
			
		}elseif (strtolower($formato) == 'csv') {
			
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename={$this->exportar_nombreArchivo}.csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			
		}
		
		//contar el total de campos que tienen el parametro "exportar"
		$totalCamposExportar = 0;
		for ($i=0;$i<count($this->campos);$i++){
			if($this->campos[$i][exportar] != true) continue;
			$totalCamposExportar++;
		}
		
		//Por cada campo...
		for ($i=0;$i<count($this->campos);$i++){
			if($this->campos[$i][exportar] != true) continue;
			if($this->campos[$i][campo] == "") continue;
			if($this->campos[$i][tipo] == "upload") continue;
			
			//campos para el select
			if($camposSelect != "")$camposSelect .= ", ";
			$camposSelect .= $this->tabla.".".$this->campos[$i][campo];
			if($this->campos[$i]['joinTable'] != ''){
				if($camposSelect != "")$camposSelect .= ", ";
				$camposSelect .= $this->campos[$i]['joinTable'].".".$this->campos[$i][campoTexto]." AS ".$this->campos[$i]['joinTable']."_".$this->campos[$i][campoTexto];
			}
			
			//tablas para sql join
			if($this->campos[$i]['joinTable'] != ''){
				
				if ($this->campos[$i]['joinCondition'] != '') {
					$joinCondition = $this->campos[$i]['joinCondition'];
				}else{
					$joinCondition = "INNER";
				}
				
				$joinSql .= " $joinCondition JOIN ".$this->campos[$i]['joinTable']." ON ".$this->tabla.'.'.$this->campos[$i]['campo'].'='.$this->campos[$i]['joinTable'].'.'.$this->campos[$i]['campoValor'];
			}
			
			//Encabezados
			if (strtolower($formato) == 'excel') {
				echo "		<th>";
			}
			echo ($this->campos[$i][tituloListado]!="" ? $this->campos[$i][tituloListado] : ($this->campos[$i][titulo]!=''?$this->campos[$i][titulo]:$this->campos[$i][campo]));
			if (strtolower($formato) == 'excel') {
				echo "</th>\n";
			}elseif (strtolower($formato) == 'csv') {
				if($i < $totalCamposExportar-1) echo $this->exportar_csv_separadorCampos;
			}
		}
		
		if (strtolower($formato) == 'excel') {
			echo "	</tr>\n";
		}


		//Datos
		if ($this->exportar_sql!="") {
			$sql = $this->exportar_sql;
		}else if($this->sqlCamposSelect != ""){
			if($this->orderByPorDefecto!="") $orderBy = " ORDER BY ".$this->orderByPorDefecto;
			$sql = "SELECT ".$this->sqlCamposSelect." FROM $this->tabla $joinSql WHERE 1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
		}else{
			if($this->orderByPorDefecto!="") $orderBy = " ORDER BY ".$this->orderByPorDefecto;
			$sql = "SELECT $this->tabla.$this->campoId, $camposSelect FROM $this->tabla $joinSql WHERE 1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
		}
		
		$result = $db->query($sql);
		$i = 0;
		while ($fila = $db->fetch_array($result)) {
			$fila = $this->limpiarEntidadesHTML($fila);
			$i++;
			
			if (strtolower($formato) == 'excel') {
				echo "	<tr>\n";
			}elseif (strtolower($formato) == 'csv') {
				echo "\n";
			}

			$c = 0;
			foreach($this->campos as $campo){
				$c++;
				if($campo[exportar] != true) continue;
				
				if($campo[campoOrder] != ""){
					$campo[campo] = $campo[campoOrder];
				}else{
					if ($campo['joinTable'] != '') {
						$campo[campo] = $campo['joinTable'].'_'.$campo[campoTexto];
					}
				}
				
				if (strtolower($formato) == 'excel') {
					echo "		<td>";
				}
				
				if ($campo[tipo] == "bit") {
					if ($fila[$campo[campo]]) {
						echo ($campo[textoBitTrue] != '' ? $campo[textoBitTrue] : $this->textoBitTrue);
					}else{
						echo ($campo[textoBitFalse] != '' ? $campo[textoBitFalse] : $this->textoBitFalse);
					}
				}else{

					//si es tipo fecha lo formatea
					if ($campo[tipo] == "fecha") {
						if( $fila[$campo[campo]] != "" and $fila[$campo[campo]] != "0000-00-00" and $fila[$campo[campo]] != "0000-00-00 00:00:00" ){
							if (strtotime($fila[$campo[campo]]) !== -1){
								$fila[$campo[campo]] = date($this->formatoFechaListado, strtotime($fila[$campo[campo]]));
							}
						}
					}

					$str = $fila[$campo[campo]];

					//si es formato csv...
					if (strtolower($formato) == 'csv') {
						//quito los saltos de linea que pueda tener el valor
						$str = ereg_replace( chr(13) , "" , $str );
						$str = ereg_replace( chr(10) , "" , $str );

						//verifico que no este el caracter separador de campos en el valor
						if(strpos($str, $this->exportar_csv_separadorCampos) !== false){
							$str = $this->exportar_csv_delimitadorCampos.$str.$this->exportar_csv_delimitadorCampos;
						}
					}

					echo $str;
				}
				
				if (strtolower($formato) == 'excel') {
					echo "</td>\n";
				}elseif (strtolower($formato) == 'csv') {
					if($c < $totalCamposExportar) echo $this->exportar_csv_separadorCampos;
				}
				
			}
			
			if (strtolower($formato) == 'excel') {
				echo "	</tr>\n";
			}
			
		}
		
		if (strtolower($formato) == 'excel') {
			echo "</table>";
		}
		
		exit;
	}
	
	/**
	 * Genera el listado ABM con las funciones de editar, nuevo y borrar (segun la configuracion).
	 * NOTA: Esta funcion solamente genera el listado, se necesita usar la funcion generarAbm() para que funcione el ABM.
	 *
	 * @param string $sql Query SQL personalizado para el listado. Usando este query no se usa $adicionalesSelect
	 * @param string $titulo Un titulo para mostrar en el encabezado del listado
	 */
	public function generarListado($sql="", $titulo){
		global $db;
		
		$agregarFormBuscar = false;
		
		//por cada campo...
		for ($i=0;$i<count($this->campos);$i++){
			if($this->campos[$i][campo] == "") continue;
			if($this->campos[$i][tipo] == "upload") continue;
			if($this->campos[$i][noListar]) continue;
			
			if($this->campos[$i][exportar] == true){
				$mostrarExportar = true;
			}
			
			//para la class de ordenar por columnas
			if($this->campos[$i][noListar] == false and $this->campos[$i][noOrdenar] == false){
				if($camposOrder != "")$camposOrder .= "|";
				if ($this->campos[$i][campoOrder] != "") {
					$camposOrder .= $this->campos[$i][campoOrder];
				}else{
					if($this->campos[$i]['joinTable'] == ''){
						$camposOrder .= $this->tabla.".".$this->campos[$i][campo];
					}else{
						$camposOrder .= $this->campos[$i]['joinTable'].".".$this->campos[$i][campoTexto];
					}
				}
			}
			
			//campos para el select
			if($this->campos[$i][noListar] == false or $this->campos[$i][buscar] == true){
				if($camposSelect != "")$camposSelect .= ", ";
				$camposSelect .= $this->tabla.".".$this->campos[$i][campo];
				if($this->campos[$i]['joinTable'] != ''){
					if($camposSelect != "")$camposSelect .= ", ";
					$camposSelect .= $this->campos[$i]['joinTable'].".".$this->campos[$i][campoTexto]." AS ".$this->campos[$i]['joinTable']."_".$this->campos[$i][campoTexto];
				}
			}
			
			//para el where de buscar
			if($this->campos[$i]['buscar']) $agregarFormBuscar = true;
			if(trim($_REQUEST['c_'.$this->campos[$i][campo]]) != ''){
				$estaBuscando = true;

				//quita la variable de paginado, ya que estoy buscando y no se aplica
				unset($_REQUEST[r]);
				unset($_POST[r]);
				unset($_GET[r]);
			  
				$camposWhereBuscar .= " AND ";
				if($this->campos[$i][buscarUsarCampo] != ""){
				  $camposWhereBuscar .= $this->campos[$i][buscarUsarCampo];
				}else{
				  $camposWhereBuscar .= $this->tabla.".".$this->campos[$i][campo]; 
				}
				
				$camposWhereBuscar .= " ";
				
				if($this->campos[$i]['buscarOperador'] != '' and strtolower($this->campos[$i]['buscarOperador']) != 'like'){
					$camposWhereBuscar .= $this->campos[$i]['buscarOperador']." '".$this->limpiarParaSql($_REQUEST['c_'.$this->campos[$i][campo]])."'";
				}else{
					$camposWhereBuscar .= "LIKE '%".$this->limpiarParaSql($_REQUEST['c_'.$this->campos[$i][campo]])."%'";
				}
			}
			
			//tablas para sql join
			if($this->campos[$i]['joinTable'] != ''){
				
				if ($this->campos[$i]['joinCondition'] != '') {
					$joinCondition = $this->campos[$i]['joinCondition'];
				}else{
					$joinCondition = "INNER";
				}
				
				$joinSql .= " $joinCondition JOIN ".$this->campos[$i]['joinTable']." ON ".$this->tabla.'.'.$this->campos[$i]['campo'].'='.$this->campos[$i]['joinTable'].'.'.$this->campos[$i]['campoValor'];
			}
		}
		
		$camposSelect .= $this->adicionalesCamposSelect;

		//class para ordenar por columna
		$o = new class_orderby($this->orderByPorDefecto, $camposOrder);
		
		if($o->getOrderBy()!="") $orderBy = " ORDER BY ".$o->getOrderBy();
		
		//query del select para el listado
		if ($sql=="" and $this->sqlCamposSelect=="") {
			$sql = "SELECT $this->tabla.$this->campoId, $camposSelect FROM $this->tabla $joinSql WHERE 1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
		}else if ($this->sqlCamposSelect!=""){
			$sql = "SELECT ".$this->sqlCamposSelect." FROM $this->tabla $joinSql WHERE 1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
		}else{
			$sql = $sql." ".$orderBy;
		}

		//class paginado
		$paginado = new class_paginado;
		$paginado->registros_por_pagina = $this->registros_por_pagina;
		$paginado->str_registros = $this->textoStrRegistros;
		$paginado->str_registro = $this->textoStrRegistro;
		$paginado->str_total = $this->textoStrTotal;
		$paginado->str_ir_a = $this->textoStrIrA;
		if($this->mostrarListado) $result = $paginado->query($sql);
		$this->totalFilas = $paginado->total_registros;
		
		//genera el query string de variables previamente existentes
		$get = $_GET;
		unset($get[abmsg]);
		$qsamb = http_build_query($get);
		if($qsamb!="") $qsamb = "&".$qsamb;
		
		echo "<div class='mabm'>";
		?>
		<script type="text/javascript">
		function abmBorrar(id, obj){
			var colorAnt = obj.parentNode.parentNode.style.border;
			obj.parentNode.parentNode.style.border = '3px solid red';
			if (confirm("<?= $this->textoPreguntarBorrar?>")){
				window.location = "<?= $_SERVER[PHP_SELF]."?".$qsamb."&abm_borrar=" ?>" + id;
			}
			obj.parentNode.parentNode.style.border = colorAnt;
			return void(0);
		}
		
		<?php
		if ($this->colorearFilas) {
			echo "
			var colorAntTR;
			function cambColTR(obj,sw){
				if(sw){
					colorAntTR=obj.style.backgroundColor;
					obj.style.backgroundColor='$this->colorearFilasColor';
				}else{
					obj.style.backgroundColor=colorAntTR;
				}
			}
			";
		}
		?>
		
		</script>
		<?php
		if (isset($_GET[abmsg])) {
			echo "<div class='merror'>".urldecode($_GET[abmsg])."</div> \n";
		}
		
		echo "<table class='mlistado' $this->adicionalesTableListado> \n";
		
		//titulo, botones, form buscar
		echo "<thead> \n";
		echo "<tr><th colspan='".(count($this->campos)+2)."'> \n";
		
		echo "<div class='mtitulo'>$titulo</div>";
		
		echo "<div class='mbotonera'> \n";
		echo $this->agregarABotoneraListado;
		if($mostrarExportar and $this->mostrarListado){
			if(in_array('excel', $this->exportar_formatosPermitidos)) echo sprintf($this->iconoExportarExcel, "$_SERVER[PHP_SELF]?abm_exportar=excel");
			if(in_array('csv', $this->exportar_formatosPermitidos)) echo sprintf($this->iconoExportarCsv, "$_SERVER[PHP_SELF]?abm_exportar=csv");
		}
		if($this->mostrarNuevo) echo sprintf($this->iconoAgregar, "$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb");
		echo "</div> \n";
		
		echo "</th></tr> \n";
		
		echo "</thead> \n";
		
		//formulario de busqueda
		if ($agregarFormBuscar and $this->mostrarListado) :
			echo "<tr class='mbuscar'><th colspan='".(count($this->campos)+2)."'> \n";
			echo "<fieldset><legend>$this->textoTituloFormularioBuscar</legend> \n";
			echo "<form method='POST' action='$this->formAction?$qsamb' id='formularioBusquedaAbm'> \n";
			
			$iColumna = 0;
			$maxColumnas = $this->columnasFormBuscar;
			
			foreach($this->campos as $campo) :
				if(!$campo[buscar]) continue;
				$iColumna++;
				echo "<div>\n";
				echo "<label>".($campo[tituloBuscar]!="" ? $campo[tituloBuscar] : ($campo[tituloListado]!="" ? $campo[tituloListado] : ($campo[titulo]!=''?$campo[titulo]:$campo[campo])))."</label>";
				
				if($campo[tipoBuscar] != "") $campo[tipo] = $campo[tipoBuscar];
        
				if ($campo[customFuncionBuscar] != "") {
					
					call_user_func_array($campo[customFuncionBuscar], array());
					
				}else{
	        
					switch ($campo[tipo]) :
						case "dbCombo":
							echo "<select name='c_".$campo[campo]."' id='c_".$campo[campo]."' class='input-select'> \n";
							echo "<option value=''></option> \n";
							
							$resultdbCombo = $db->query($campo[sqlQuery]);
							while ($filadbCombo = $db->fetch_array($resultdbCombo)) {
								if ((isset($_REQUEST['c_'.$campo[campo]]) and $_REQUEST['c_'.$campo[campo]] == $filadbCombo[$campo[campoValor]])) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='".$filadbCombo[$campo[campoValor]]."' $sel>".$filadbCombo[$campo[campoTexto]]."</option> \n";
							}
							echo "</select> \n";
							break;
							
						case "combo":
							echo "<select name='c_".$campo[campo]."' id='c_".$campo[campo]."' class='input-select'> \n";
							echo "<option value=''></option> \n";
							
							foreach ($campo[datos] as $valor => $texto) {
								if ((isset($_REQUEST['c_'.$campo[campo]]) and $_REQUEST['c_'.$campo[campo]] == $valor)) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='$valor' $sel>$texto</option> \n";
							}
							echo "</select> \n";
							break;
							
						case "bit":
							echo "<select name='c_".$campo[campo]."' id='c_".$campo[campo]."' class='input-select'> \n";
							echo "<option value=''></option> \n";
							
							if ($campo[ordenInversoBit]) {
								if ((isset($_REQUEST['c_'.$campo[campo]]) and $_REQUEST['c_'.$campo[campo]] == "0")) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='0' $sel>".($campo[textoBitFalse] != "" ? $campo[textoBitFalse] : $this->textoBitFalse)."</option> \n";
								
								if ((isset($_REQUEST['c_'.$campo[campo]]) and $_REQUEST['c_'.$campo[campo]] == true)) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='1' $sel>".($campo[textoBitTrue] != "" ? $campo[textoBitTrue] : $this->textoBitTrue)."</option> \n";
							
							}else{
	
								if ((isset($_REQUEST['c_'.$campo[campo]]) and $_REQUEST['c_'.$campo[campo]] == true)) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='1' $sel>".($campo[textoBitTrue] != "" ? $campo[textoBitTrue] : $this->textoBitTrue)."</option> \n";
								
								if ((isset($_REQUEST['c_'.$campo[campo]]) and $_REQUEST['c_'.$campo[campo]] == "0")) {
									$sel = "selected='selected'";
								}else{
									$sel = "";
								}
								echo "<option value='0' $sel>".($campo[textoBitFalse] != "" ? $campo[textoBitFalse] : $this->textoBitFalse)."</option> \n";					
							}
							
							echo "</select> \n";
							break;
		
						case "fecha":
							$valor = $this->limpiarEntidadesHTML($_REQUEST['c_'.$campo[campo]]);
							if(strlen($valor) > 10) $valor = substr($valor, 0, 10); //sacar hora:min:seg
							if($valor == '0000-00-00') $valor = "";
							$jsTmp = str_replace('%IDCAMPO%', 'c_'.$campo[campo], $this->jsIniciadorCamposFecha);
							$jsTmp = str_replace('%VALOR%', $valor, $jsTmp);
							
							echo $jsTmp;
							echo "<input type='text' style='position:absolute' class='input-fecha' name='c_".$campo[campo]."' id='c_".$campo[campo]."' value='".($valor)."'/> \n";
							echo "<input type='text' style='position:relative;top:0px;left;0px'  name='display_c_".$campo[campo]."' id='display_c_".$campo[campo]."' class='input-fecha $requerido' $disabled $campo[adicionalInput] readonly='readonly'/> \n";
							break;
					
						default:
							echo "<input type='text' class='input-text' name='c_$campo[campo]' value='".$this->limpiarEntidadesHTML($_REQUEST['c_'.$campo[campo]])."' /> \n";
							break;
		
					endswitch;
					
				}
				
				echo "</div>";
				if ($iColumna == $maxColumnas) {
					$iColumna = 0;
					echo "<div class='mNuevaLinea'></div>\n";
				}
				
			endforeach;
			
			echo "<div class='mBotonesB'> \n";
			echo "<input type='submit' class='mBotonBuscar' value='$this->textoBuscar'/> \n";
			echo "<input type='button' class='mBotonLimpiar' value='$this->textoLimpiar' onclick='window.location=\"$this->formAction?$qsamb\"'/> \n";
			echo "</div> \n";
			echo "</form> \n";
			echo "</fieldset> \n";
			echo "</th></tr> \n";
		endif;
		//fin formulario de busqueda
		
			
		if($paginado->total_registros > 0){
			
			//columnas del encabezado
			if($this->mostrarEncabezadosListado){
				echo "<tr> \n";
				foreach($this->campos as $campo){
					if($campo[noListar] == true) continue;
					if($campo[tipo] == "upload") continue;
					if(isset($campo[separador])) continue;
					
					$styleTh = "";
					if ($campo['centrarColumna']) $styleTh .= "text-align:center;";
					if ($campo['anchoColumna'] != "") $styleTh .= "width:$campo[anchoColumna];";
					
					if($campo[campo]=="" or $campo[noOrdenar]){
						echo "<th ".($styleTh != "" ? "style='$styleTh'" : "").">".($campo[tituloListado]!="" ? $campo[tituloListado] : ($campo[titulo]!=''?$campo[titulo]:$campo[campo]))."</th> \n";
					}else{
						
						if ($campo[campoOrder] != "") {
							$campoOrder = $campo[campoOrder];
						}else{
							if ($campo['joinTable'] != '') {
								$campoOrder = $campo['joinTable'].'.'.$campo[campoTexto];
							}else{
								$campoOrder = $this->tabla.'.'.$campo[campo];
							}
						}
						
						echo "<th ".($styleTh != "" ? "style='$styleTh'" : "").">".$o->linkOrderBy( ($campo[tituloListado]!="" ? $campo[tituloListado] : ($campo[titulo]!=''?$campo[titulo]:$campo[campo])), $campoOrder)."</th> \n";
					}
				}
				if ($this->mostrarEditar) echo "<th class='mtituloColEditar'>$this->textoEditarListado</th> \n";
				if ($this->mostrarBorrar) echo "<th class='mtituloColBorrar'>$this->textoBorrarListado</th> \n";
				echo "</tr> \n";
			}//fin columnas del encabezado
			
			//filas de datos
			$i = 0;
			while ($fila = $db->fetch_array($result)) {
				$fila = $this->limpiarEntidadesHTML($fila);
				$i++;
				$rallado = !$rallado;
				
				echo "<tr class='rallado$rallado' ";
				if($this->colorearFilas) echo " onmouseover=\"cambColTR(this,1)\" onmouseout=\"cambColTR(this,0)\" ";
				if(isset($this->evalEnTagTR)) eval($this->evalEnTagTR);
				echo "> \n";
				
				foreach($this->campos as $campo){
					if($campo[noListar] == true) continue;
					if($campo[tipo] == "upload") continue;
					if(isset($campo[separador])) continue;
					
					if($campo[campoOrder] != ""){
						$campo[campo] = $campo[campoOrder];
					}else{
						if ($campo['joinTable'] != '') {
							$campo[campo] = $campo['joinTable'].'_'.$campo[campoTexto];
						}
					}
					
					if($campo['centrarColumna']){
						$centradoCol = 'align="center"';
					}else{
						$centradoCol = '';
					}
					
					if(is_array($campo['colorearValores'])){
						$arrayValoresColores = $campo['colorearValores']; 
						if(array_key_exists($fila[$campo[campo]], $arrayValoresColores)){
							$spanColorear = "<span class='".($campo['colorearConEtiqueta'] ? "label" : "")."' style='".($campo['colorearConEtiqueta'] ? "background-" : "")."color:".$arrayValoresColores[$fila[$campo[campo]]]."'>";
							$spanColorearFin = "</span>";
						}
					}else{
						$spanColorear = "";
						$spanColorearFin = "";
					}
					
					if ($campo[customEvalListado] != "") {
						
						extract($GLOBALS);
						$id = $fila[$this->campoId];
						$valor = $fila[$campo[campo]];
						eval($campo[customEvalListado]);
						
					}elseif ($campo[customFuncionListado] != "") {
						
						call_user_func_array($campo[customFuncionListado], array($fila));
						
					}elseif ($campo[customPrintListado] != "") {
						
						echo "<td $centradoCol>$spanColorear";
						$campo[customPrintListado] = str_ireplace('{id}', $fila[$this->campoId], $campo[customPrintListado]);
						echo sprintf($campo[customPrintListado], $fila[$campo[campo]]);
						echo "$spanColorearFin</td> \n";
						
					}else{
						
						if ($campo[tipo] == "bit") {
							if ($fila[$campo[campo]]) {
								echo "<td $centradoCol>$spanColorear".($campo[textoBitTrue] != '' ? $campo[textoBitTrue] : $this->textoBitTrue)."$spanColorearFin</td> \n";
							}else{
								echo "<td $centradoCol>$spanColorear".($campo[textoBitFalse] != '' ? $campo[textoBitFalse] : $this->textoBitFalse)."$spanColorearFin</td> \n";
							}
						}else{

							//si es tipo fecha lo formatea
							if ($campo[tipo] == "fecha") {
								if( $fila[$campo[campo]] != "" and $fila[$campo[campo]] != "0000-00-00" and $fila[$campo[campo]] != "0000-00-00 00:00:00" ){
									if (strtotime($fila[$campo[campo]]) !== -1){
										$fila[$campo[campo]] = date($this->formatoFechaListado, strtotime($fila[$campo[campo]]));
									}
								}
							}

							echo "<td $centradoCol>$spanColorear".$fila[$campo[campo]]."$spanColorearFin</td> \n";
						}
						
					}
					
				}
				
				if ($this->mostrarEditar) echo "<td class='celdaEditar'>".sprintf($this->iconoEditar, $_SERVER[PHP_SELF]."?abm_editar=".$fila[$this->campoId].$qsamb)."</td> \n";
				if ($this->mostrarBorrar) echo "<td class='celdaBorrar'>".sprintf($this->iconoBorrar, "abmBorrar('".$fila[$this->campoId]."', this)")."</td> \n";
				echo "</tr> \n";
			}
			
			echo "<tfoot> \n";
			echo "<tr> \n";
			echo "<th colspan='".(count($this->campos)+2)."'>";
			if(!$this->mostrarTotalRegistros) $paginado->mostrarTotalRegistros = false;
			$paginado->mostrar_paginado();
			echo "</th> \n";
			echo "</tr> \n";
			echo "</tfoot> \n";
			
		}else{
			
			echo "<td colspan='".(count($this->campos)+2)."'><div class='noHayRegistros'>".($estaBuscando ? $this->textoNoHayRegistrosBuscando : $this->textoNoHayRegistros)."</div></td>";
			
		}
		
		echo "</table> \n";
		echo "</div>";
		
		if ($this->mostrarNuevo){
			//genera el query string de variables previamente existentes
			$get = $_GET;
			unset($get[abmsg]);
			unset($get[$o->variableOrderBy]);
			$qsamb = http_build_query($get);
			if($qsamb!="") $qsamb = "&".$qsamb;
		}
		
	}
	
	/**
	 * Genera el listado ABM con las funciones de editar, nuevo y borrar (segun la configuracion)
	 *
	 * @param string $sql Query SQL personalizado para el listado. Usando este query no se usa $adicionalesSelect
	 * @param string $titulo Un titulo para mostrar en el encabezado del listado
	 */
	public function generarAbm($sql="", $titulo){
		global $db;
		
		$estado = $this->getEstadoActual();
		
		switch ($estado) {
			case "listado":
				$this->generarListado($sql, $titulo);
				break;
				
			case "alta":
				if(!$this->mostrarNuevo) die("Error"); //chequeo de seguridad, necesita estar activado mostrarNuevo

				$this->generarFormAlta("Nuevo");
				break;
				
			case "editar":
				if(!$this->mostrarEditar) die("Error"); //chequeo de seguridad, necesita estar activado mostrarEditar

				$this->generarFormModificacion($_GET[abm_editar], "Editar");
				break;
				
			case "dbInsert":
				if(!$this->mostrarNuevo) die("Error"); //chequeo de seguridad, necesita estar activado mostrarNuevo

				$r = $this->dbRealizarAlta();
				if($r!=0){
					//el error 1062 es "Duplicate entry"
					if($db->errno() == 1062 and $this->textoRegistroDuplicado != ""){
						$abmsg = "&abmsg=".urlencode($this->textoRegistroDuplicado);
					}else{
						$abmsg = "&abmsg=".urlencode($db->error());
					}
				}
				
				unset($_POST['abm_enviar_formulario']);
				unset($_GET['abm_alta']);
				unset($_GET['abmsg']);
				
				if ($r==0 && $this->redireccionarDespuesInsert != ""){
					$this->redirect(sprintf($this->redireccionarDespuesInsert, $db->insert_id()));
				}else{
					$qsamb = http_build_query($_GET); //conserva las variables que existian previamente
					$this->redirect("$_SERVER[PHP_SELF]?$qsamb$abmsg");
				}

				break;
				
			case "dbUpdate":
				if(!$this->mostrarEditar) die("Error"); //chequeo de seguridad, necesita estar activado mostrarEditar

				$r = $this->dbRealizarModificacion($_POST["abm_id"]);
				if($r!=0){
					//el error 1062 es "Duplicate entry"
					if($db->errno() == 1062 and $this->textoRegistroDuplicado != ""){
						$abmsg = "&abmsg=".urlencode($this->textoRegistroDuplicado);
					}else{
						$abmsg = "&abmsg=".urlencode($db->error());
					}
				}

				unset($_POST['abm_enviar_formulario']);
				unset($_GET['abm_modif']);
				unset($_GET['abmsg']);
				
				if ($r==0 && $this->redireccionarDespuesUpdate != ""){
					$this->redirect(sprintf($this->redireccionarDespuesUpdate, $_POST[$this->campoId]));
				}else{
					$qsamb = http_build_query($_GET); //conserva las variables que existian previamente
					$this->redirect("$_SERVER[PHP_SELF]?$qsamb$abmsg");
				}
				
				break;
				
			case "dbDelete":
				if(!$this->mostrarBorrar) die("Error"); //chequeo de seguridad, necesita estar activado mostrarBorrar

				$r = $this->dbBorrarRegistro($_GET[abm_borrar]);
				if($r!=0) $abmsg = "&abmsg=".urlencode($db->error());
	
				unset($_GET['abm_borrar']);
				
				if ($r==0 && $this->redireccionarDespuesDelete != ""){
					$this->redirect(sprintf($this->redireccionarDespuesDelete, $_GET[abm_borrar]));
				}else{
					$qsamb = http_build_query($_GET); //conserva las variables que existian previamente
					$this->redirect("$_SERVER[PHP_SELF]?$qsamb$abmsg");
				}
				
				break;
				
			default:
				$this->generarListado($sql, $titulo);
				break;
		}
		
	}
	
	private function dbRealizarAlta(){
		global $db;
		
		if(!$this->formularioEnviado()) return;

		$_POST = $this->limpiarParaSql($_POST);
		
		$sql = "INSERT INTO ".$this->tabla." SET \n";

		$camposSql = "";
		
		foreach($this->campos as $campo){
			if($campo[noNuevo] == true) continue;
			if($campo[tipo] == '' or $campo[tipo] == 'upload') continue;
			
			$valor = $_POST[$campo[campo]];
			
			//chequeo de campos requeridos
			if($campo[requerido] and trim($valor)==""){
				//genera el query string de variables previamente existentes
				$get = $_GET;
				unset($get[abmsg]);
				unset($get[abm_alta]);
				$qsamb = http_build_query($get);
				if($qsamb!="") $qsamb = "&".$qsamb;
				
				$this->redirect("$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb&abmsg=".urlencode(sprintf($this->textoCampoRequerido, $campo[titulo])));
			}
			
			if($camposSql != "") $camposSql .= ", \n";
			
			if ($campo[customFuncionValor] != "") {
				$valor = call_user_func_array($campo[customFuncionValor], array($valor));
			}
			
			if (trim($valor) == '') {
				$camposSql .= $campo[campo]."= NULL";
			}else{
				$camposSql .= $campo[campo]."= '".$valor."' ";
			}
		}
		
		$sql .= $camposSql;
		
		$sql .= $this->adicionalesInsert;

		$db->query($sql);
		
		$id = $db->insert_id();
		
		//si mysql_insert_id retorno 0 quiere decir que el campo id no es auto_increment pero si inserto el registro, entonces seteo $id con el valor del campo id
		if ($id == 0 and $this->campoIdEsEditable) {
			$id = $_POST[$this->campoId];
		}
		
		//upload
		if($id !== false){
			foreach($this->campos as $campo){
				if(!$campo[tipo] == 'upload') continue;
				
				if (isset($campo['uploadFunction'])) {
					$r = call_user_func_array($campo['uploadFunction'], array($id, $this->tabla));
					
					if($r == false and $campo['borrarSiUploadFalla']){
						$db->query("DELETE FROM $this->tabla WHERE $this->campoId='$id' LIMIT 1");
					}
				}
			}
		}
		
		if (isset($this->callbackFuncInsert)) {
			call_user_func_array($this->callbackFuncInsert, array($id, $this->tabla));
		}
		
		return $db->errno();
	}
	
	private function dbRealizarModificacion($id){
		global $db;
		
		if(trim($id) == '') die('Parametro id vacio en dbRealizarModificacion');
		if(!$this->formularioEnviado()) return;
		
		$id = $this->limpiarParaSql($id);
		$_POST = $this->limpiarParaSql($_POST);

		$sql = "UPDATE ".$this->tabla." SET \n";

		$camposSql = "";
		
		//por cada campo...
		foreach($this->campos as $campo){
			if($campo[noEditar] or $campo[noMostrarEditar]) continue;
			if($campo[tipo] == '' or $campo[tipo] == 'upload') continue;
			
			$valor = $_POST[$campo[campo]];
			
			//chequeo de campos requeridos
			if($campo[requerido] and trim($valor)==""){
				//genera el query string de variables previamente existentes
				$get = $_GET;
				unset($get[abmsg]);
				unset($get[abm_modif]);
				$qsamb = http_build_query($get);
				if($qsamb!="") $qsamb = "&".$qsamb;
				
				$this->redirect("$_SERVER[PHP_SELF]?abm_editar=$id$qsamb&abmsg=".urlencode(sprintf($this->textoCampoRequerido, $campo[titulo])));
			}
			
			if($camposSql != "") $camposSql .= ", \n";
			
			if ($campo[customFuncionValor] != "") {
				$valor = call_user_func_array($campo[customFuncionValor], array($valor));
			}

			if (trim($valor) == '') {
				$camposSql .= $campo[campo]."= NULL";
			}else{
				$camposSql .= $campo[campo]."= '".$valor."'";
			}
		}
		
		$sql .= $camposSql;
		
		$sql .= $this->adicionalesUpdate." WHERE ".$this->campoId."='".$id."' ".$this->adicionalesWhereUpdate." LIMIT 1";

		$db->query($sql);
		
		if ($db->affected_rows() == 1) {
			$fueAfectado = true;
			
			//si cambio la id del registro
			if ($this->campoIdEsEditable and isset($_POST[$this->campoId]) and $id != $_POST[$this->campoId]) {
				$id = $_POST[$this->campoId];
			}
		}
		
		//upload
		if($id !== false){
			foreach($this->campos as $campo){
				if(!$campo[tipo] == 'upload') continue;
				
				if (isset($campo['uploadFunction'])) {
					$r = call_user_func_array($campo['uploadFunction'], array($id, $this->tabla));
				}
			}
		}
		
		if (isset($this->callbackFuncUpdate)) {
			call_user_func_array($this->callbackFuncUpdate, array($id, $this->tabla, $fueAfectado));
		}
		
		return $db->errno();
	}
	
	private function dbBorrarRegistro($id){
		global $db;
		
		$id = $this->limpiarParaSql($id);
		
		if (isset($this->callbackFuncDelete)) {
			call_user_func_array($this->callbackFuncDelete, array($id, $this->tabla));
		}
		
		$sql = "DELETE FROM ".$this->tabla." WHERE ".$this->campoId."='".$id."' ".$this->adicionalesWhereDelete." LIMIT 1";

		$db->query($sql);

		return $db->errno();
	}
	
	/**
	 * Verifica el query string para ver si hay que llamar a la funcion de exportar
	 * Esta funcion debe llamarse despues de setear los valores de la classe y antes de que se envie cualquier 
	 * salida al navegador, de otra manera no se podrían enviar los Headers
	 * Nota: El nombre de la funcion quedó por compatibilidad
	 */
	public function exportar_verificar(){
		$estado = $this->getEstadoActual();
		if($estado == "exportar" and $this->mostrarListado) $this->exportar($_GET[abm_exportar]);
	}
	
	/**
	 * Retorna true si el formulario fue enviado y estan disponibles los datos enviados
	 *
	 * @return boolean
	 */
	private function formularioEnviado(){
		if ($_POST[abm_enviar_formulario]) {
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Convierte de un array todas las entidades HTML para que sea seguro mostrar en pantalla strings ingresados por los usuarios
	 * Ejemplo: $_REQUEST = limpiarEntidadesHTML($_REQUEST);
	 *
	 * @param Array o String $param Un array o un String
	 * @return Depende del parametro recibido, un array con los datos remplazados o un String
	 */
	private function limpiarEntidadesHTML($param) {
		global $sitio; //de mi framework (no tiene nada que ver con miAbmPhp)
		return is_array($param) ? array_map(array($this, __FUNCTION__), $param) : htmlentities($param, ENT_QUOTES, $sitio->charset);
	}
	
	/**
	 * Escapa de un array todos los caracteres especiales de una cadena para su uso en una sentencia SQL
	 * Ejemplo: $_REQUEST = limpiarParaSql($_REQUEST);
	 *
	 * @param Array o String $param Un array o un String
	 * @return Depende del parametro recibido, un array con los datos remplazados o un String
	 */
	private function limpiarParaSql($param){
		global $db;
		return is_array($param) ? array_map(array($this, __FUNCTION__), $param) : $db->real_escape_string($param);
	}
	
	/**
	 * Redirecciona a $url 
	 */
	private function redirect($url){
		if ($this->metodoRedirect == "header") {
			header("Location:$url");
			exit;
		}else{
			echo "<HTML><HEAD><META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$url\"></HEAD></HTML>";
			exit;
		}
	}
}
?>