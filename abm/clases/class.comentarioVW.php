<?php

include_once ("generic_class/class.menu.php");
include_once ("clases/class.comentarioBSN.php");
include_once ("clases/class.comentario.php");
//include_once("generic_class/class.fechas.php");
include_once("generic_class/class.cargaConfiguracion.php");
include_once("generic_class/class.VW.php");
include_once("clases/class.loginwebuserBSN.php");

class ComentarioVW extends VW {

    protected $clase = "Comentario";
    protected $comentario;
    protected $nombreId = "Id_com";

    public function __construct($_comentario = 0) {
        ComentarioVW::creaObjeto();
        if ($_comentario instanceof Comentario) {
            ComentarioVW::seteaVW($_comentario);
        }
        if (is_numeric($_comentario)) {
            if ($_comentario != 0) {
                ComentarioVW::cargaVW($_comentario);
            }
        }
        ComentarioVW::cargaDefinicionForm();
    }

    public function setIdPropiedad($id){
        $this->comentario->setId_prop($id);
    }

    public function getIdPropiedad(){
        return $this->comentario->getId_prop();
    }
    
    public function cargaDatosVW($id_prop) {
        print "<form action='carga_Comentario.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaComentario(this);'>\n";
        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_titulo'>Carga de Comentarios de Propiedades - ID ".$id_prop."</td></tr>\n";
        print "<tr><td align='center'>";
        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
        if ($_SESSION['Userrole'] == 'admin') {
            print "<tr><td class='cd_celda_texto' width='15%'>Publico</td>";
            print "<td><input type='checkbox' name='visible' id='visible' ";
            if ($this->arrayForm['visible'] == 0 && $this->arrayForm['id_com'] != '') {
                print ">";
            } else {
                print " checked >";
            }
            print "</td></tr>\n\n";
        } else {
            print "<input type='hidden' name='visible' id='visible' value='1'>\n";
        }
        print "<tr><td class='cd_celda_texto' width='15%'>Comentario</td>";
        print "<td width='85%'><textarea id='comentario' name='comentario' cols='50' rows='10' maxlength='1000'>" . $this->arrayForm ['comentario'] . "</textarea></td></tr>\n";
        print "<input type='hidden' name='id_com' id='id_com' value='" . $this->arrayForm ['id_com'] . "'>\n";
//        print "<input type='hidden' name='id_prop' id='id_prop' value='" . $this->arrayForm ['id_prop'] . "'>\n";
        print "<input type='hidden' name='id_prop' id='id_prop' value='" . $id_prop . "'>\n";
        if($this->arrayForm ['id_com']!=0){
            print "<input type='hidden' name='id_user' id='id_user' value='" . $this->arrayForm ['id_user'] . "'>\n";
            print "<input type='hidden' name='fecha' id='fecha' value='" . $this->arrayForm ['fecha'] . "'>\n";
        }else{
            print "<input type='hidden' name='id_user' id='id_user' value='" . $_SESSION['UserId'] . "'>\n";
            print "<input type='hidden' name='fecha' id='fecha' value='" . date('Y-m-d') . "'>\n";
        }
//        print "<input type='hidden' name='fecha' id='fecha' value='" . $this->arrayForm ['fecha'] . "'>\n";
        print "<br>";
        print "<tr><td align='right'>&nbsp;</td>";
        print "<td align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
    }

    public function leeDatosVW() {
        if ($_POST['visible'] == 'on') {
            $_POST['visible'] = 1;
        }
        parent::leeDatosVW();
    }

    /**
     * Muestra una tabla con los datos de los comentarios
     */
    public function vistaTablaVW($id_prop = 0) {
    	if ($id_prop == 0 || is_nan($id_prop)) {
            echo "Debe seleccionar un Propiedad para poder verificar sus Comentarios";
        } else {
            $comBSN = new ComentarioBSN();
            print "<form name='lista' method='POST' action='respondeMenu.php'>";

            print "<script type='text/javascript' language='javascript'>\n";
            print "function envia(nameForm,opcion,id){\n";
            print "     document.forms.lista.id_cartel.value=id;\n";
            print "   	submitform(nameForm,opcion);\n";
            print "}\n";
            print "function cargaComentario(prop,coment){\n";
            print " if(coment==0){\n";
            print "   acc='n';\n";
            print " }else{\n";
            print "   acc='m';\n";
            print " }\n";
            print "   window.open('carga_Comentario.php?i='+prop+'&o='+coment+'&acc='+acc, 'ventanaComentario', 'menubar=1,resizable=1,width=950,height=550');\n";
            print "}\n";
            print "function borra(prop,coment){\n";
            print "   window.open('carga_Comentario.php?i='+prop+'&o='+coment+'&acc=b', 'ventanaComentario', 'menubar=1,resizable=1,width=950,height=550');\n";
            print "}\n";
            print "function filtro(){\n";
            $comBSN->leeChkboxComentarioJS('coment');
            print "   recargaComentario(coment,".$id_prop.",'vistaTablaCom');\n";
            print "}\n";

            print "</script>\n";

            print "<div class='pg_titulo'>Listado de Comentarios para la Propiedad ID ".$id_prop."</div>\n";

            $arrayTools = array(array('Nuevo', 'images/balloon--plus.png', 'cargaComentario(' . $id_prop . ',0)'), array('Regresar', 'images/ui-button-navigation-back.png', 'KillMe()'));
            $menu = new Menu();
            $menu->barraHerramientas($arrayTools);

            print "<div id='auto_datos' style='border-top:thin solid #CCC;'>\n";
            print "<b>Mostrar:</b>\n";
            $comBSN->checkboxComentario();
            print "<input type='button' value='Filtrar' onclick='filtro();' />\n";
            print "</div>\n";

            $evenBSN = new ComentarioBSN();
            $arrayDatos = $evenBSN->cargaColeccionComentarios($id_prop);
            $this->despliegaTabla($arrayDatos);

            print "<input type='hidden' name='id_com' id='id_com' value=''>";
            print "<input type='hidden' name='id_prop' id='id_prop' value='" . $id_prop . "'>";
            print "<input type='hidden' name='opcion' id='opcion' value=''>";
            print "</form>";
        }
    }

    public function despliegaTabla($arrayDatos) {
        $usrBSN = new LoginwebuserBSN();
        print "<div id='vistaTablaCom' class='vistaTabla' >\n";
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {

            print "    <ul>\n";
            print "     <li class=\"li_lista_titulo\" id='comAcc' >&nbsp;</li>\n";
            print "     <li class=\"li_lista_titulo\" id='comFec' >Fecha</li>\n";
            print "     <li class=\"li_lista_titulo\" id='comCon' >Concepto</li>\n";
            print "     <li class=\"li_lista_titulo\" id='comDet' >Detalle</li>\n";
            print "     <li class=\"li_lista_titulo\" id='comCom' >Comentario</li>\n";
            print "     <li class=\"li_lista_titulo\" id='comUsu' >Usuario</li>\n";
            print "	  </ul>\n";
            $fila = 0;

            print "<div style='overflow:auto; clear:both; height:900px;'>\n";

            foreach ($arrayDatos as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                $usuario='';
                switch ($Even ['concepto']) {
                    case 'CARTEL':
                        $usuario=$Even ['id_user'];
                        break;
                    case 'AVISO':
                        $usuario='';
                        break;
                    default:
                        if($Even ['id_user']!=0 && $Even ['id_user']!=''){
                            $usrBSN->cargaById($Even ['id_user']);
                            $apell=$usrBSN->getObjeto()->getApellido();
                            $nom=$usrBSN->getObjeto()->getNombre();
                            $usuario=$apell.' '.$nom;
                        }
                        break;
                }
                print "<ul>\n";
                if( $Even ['id_com']!=0){
                    if($Even ['id_user'] == $_SESSION['UserId']){
	                    print "	 <li class=\"li_lista_" . $fila . "\"  id='comAcc'>";
	                    print "    <a href='javascript:cargaComentario(" . $Even ['id_prop'] . "," . $Even ['id_com'] . ");' border='0'>";
	                    print "       <img src='images/balloon--pencil.png' alt='Editar' title='Editar' border=0></a>&nbsp;&nbsp;";
	                    print "    <a href='javascript:borra(" . $Even ['id_prop'] . "," . $Even ['id_com'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
	                    print "       <img src='images/balloon--minus.png' alt='Borrar' title='Borrar' border=0></a>";
	                    print "  </li>\n";
	                }else{
                   		print "     <li class=\"li_lista_" . $fila . "\" id='comAcc' >&nbsp;</li>\n";
                	}
                }else{
                    print "     <li class=\"li_lista_" . $fila . "\" id='comAcc' >&nbsp;</li>\n";
                }
                print "     <li class=\"li_lista_" . $fila . "\" id='comFec' >" . $Even ['fecha'] . "</li>\n";
                print "     <li class=\"li_lista_" . $fila . "\" id='comCon' >" . $Even ['concepto'] . "</li>\n";
                print "     <li class=\"li_lista_" . $fila . "\" id='comDet' >" . $Even ['detalle'] . "</li>\n";
                print "     <li class=\"li_lista_" . $fila . "\" id='comCom' >" . $Even ['comentario'] . "</li>\n";
                print "     <li class=\"li_lista_" . $fila . "\" id='comUsu' >" . $usuario . "</li>\n";
                print "	</ul>\n";
            }
            print "  </div>\n";
        }
        print "  </div>\n";
    }

}

// fin clase
?>