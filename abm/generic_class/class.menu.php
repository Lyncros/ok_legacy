<?php

include_once("./generic_class/class.cargaConfiguracion.php");
include_once("./generic_class/class.controlAccesos.php");
include_once("clases/class.perfilesBSN.php");

class Menu {

    /**
     * Despliega el menu o barra de herramientas para la vista en tabla.
     *
     *
     * @param 	$contenido -> Array que contiene las opciones del menu, por cada elemento se ingresa un array
     * 							que contiene como primer valor El nombre de la opcion
     * 							como segundo valor La imagen asociada a la opcion.
     * 							como tercer valor el script a ejecutar
     */
    public function barraHerramientas($contenido) {
        print "<div id='tools' name='tools'>\n";
        print "<table>\n";
        $x = 0;
        $cantitems = 14;
        $filasmenu = intval(sizeof($contenido) / $cantitems);
        if (sizeof($contenido) % $cantitems != 0) {
            $filasmenu++;
        }
        for ($filas = 0; $filas < $filasmenu; $filas++) {
            print "  <tr>\n";
            for ($y = 0; $y < $cantitems; $y++) {
                if ($contenido[$x][1] == '') {
                    $item_menu = $contenido[$x][0];
                } else {
                    $item_menu = "<img src='" . $contenido[$x][1] . "' alt='" . $contenido[$x][0] . "' title='" . $contenido[$x][0] . "' />";
                }
                print "		<td class='cd_celda_herr' align='center'><a href=\"javascript: " . $contenido[$x][2] . ";\">";
                print $item_menu . "</a></td>\n";
                $x++;
                if ($x == sizeof($contenido) && $y < $cantitems - 1) {
                    print "		<td class='cd_celda_herr' colspan='";
                    echo $cantitems - $y;
                    print "'>&nbsp;</td>\n";
                    break;
                }
            }
            print "  </tr>\n";
        }
        print "</table>\n";
        print "</div>\n";
    }

    public function barraHerramientas2String($contenido) {
        $strTool='';
        $strTool.="<div id='tools' name='tools'>\n";
        $strTool.="<table>\n";
        $x = 0;
        $cantitems = 14;
        $filasmenu = intval(sizeof($contenido) / $cantitems);
        if (sizeof($contenido) % $cantitems != 0) {
            $filasmenu++;
        }
        for ($filas = 0; $filas < $filasmenu; $filas++) {
            $strTool.="  <tr>\n";
            for ($y = 0; $y < $cantitems; $y++) {
                if ($contenido[$x][1] == '') {
                    $item_menu = $contenido[$x][0];
                } else {
                    $item_menu = "<img src='" . $contenido[$x][1] . "' alt='" . $contenido[$x][0] . "' title='" . $contenido[$x][0] . "' />";
                }
                $strTool.= "		<td class='cd_celda_herr' align='center'><a href=\"javascript: " . $contenido[$x][2] . ";\">";
                $strTool.= $item_menu . "</a></td>\n";
                $x++;
                if ($x == sizeof($contenido) && $y < $cantitems - 1) {
                    $strTool.= "		<td class='cd_celda_herr' colspan='";
                    $strTool.= $cantitems - $y;
                    $strTool.= "'>&nbsp;</td>\n";
                    break;
                }
            }
            $strTool.= "  </tr>\n";
        }
        $strTool.= "</table>\n";
        $strTool.= "</div>\n";
        return $strTool;
    }
    
    
    /**
     * Despliega el menu o barra de herramientas para la vista en tabla.
     *
     *
     * @param 	$nombre -> nombre del formulario del cual se levanta la opcion y se identifica el proceso donde ir
     * 			$opcion -> nombre del campo que contiene la opcion del menu a ejecutar
     * 			$contenido -> Array que contiene las opciones del menu, por cada elemento se ingresa un array
     * 							que contiene como primer valor El nombre de la opcion
     * 										 como segundo valor La imagen asociada a la opcion.
     */
    public function barraHerramientas_ORIG($nombre, $opcion, $contenido) {

        print "<table>\n";
        $x = 0;
        $cantitems = 8;
        $filasmenu = intval(sizeof($contenido) / $cantitems);
        if (sizeof($contenido) % $cantitems != 0) {
            $filasmenu++;
        }
        for ($filas = 0; $filas < $filasmenu; $filas++) {
            print "  <tr>\n";
            for ($y = 0; $y < $cantitems; $y++) {
                if ($contenido[$x][2] == '') {
                    $item_menu = $contenido[$x][1];
                } else {
                    $item_menu = "<img src='" . $contenido[$x][2] . "' alt='" . $contenido[$x][1] . "'>";
                }
                print "		<td class='cd_celda_menu' align='center'><a href=\"javascript: submitform('" . $contenido[$x][0] . $x . "')\">";
                print $item_menu . "</a></td>\n";
                $x++;
                if ($x == sizeof($contenido) && $y < $cantitems - 1) {
                    print "		<td class='cd_celda_menu' colspan='";
                    echo $cantitems - $y;
                    print "'>&nbsp;</td>\n";
                    break;
                }
            }
            print "  </tr>\n";
        }
        print "</table>\n";


        print "<SCRIPT language=\"JavaScript\">\n";
        print "function submitform(valor){";
        print "  document.forms." . $nombre . "." . $opcion . ".value=valor;";
        print "  document.forms." . $nombre . ".submit();";
        print "}";
        print "</SCRIPT>";
    }

    public function abrirPopUp($link) {
        print "<SCRIPT language=\"JavaScript\">\n";
        print "function popup(param){\n";
        print "var link='http://" . $link . "' + param ;\n";
        print "window.open(link);\n";
        print "}\n";
        print "</SCRIPT>";
    }

    public function ventanaAlerta($_msg) {
        print "<SCRIPT language=\"JavaScript\">\n";
        print "alert($_msg)\n";
        print "</SCRIPT>";
    }

    public function redireccionURL($url) {
        header("Location:$url");
        //		header("Location:http://$url");
    }

    /**
     * Despliega el menu para la vista en tabla.
     *
     *
     * @param	$menu -> Array que contiene las opciones del menu, por cada elemento se ingresa un array
     * 							que contiene como primer valor el ID de la opcion
     * 										 como segundo valor el NOMBRE de la opcion
     * 										 como tercer valor el COMENTARIO presentado como alternativo al texto.
     * 										 como cuarto valor dependiendo de la configuracion de tipo de menu
     * 											presenta un array de este mismo tipo con las opciones posibles como submenu.
     * @param 	$nombre -> nombre del formulario del cual se levanta la opcion y se identifica el proceso donde ir
     * @param	$opcion -> nombre del campo que contiene la opcion del menu a ejecutar
     *
     * En el caso de no pasar NOMBRE u OPCION, se generara su contraparte dentro de esta generacion:
     * 			Sin NOMBRE -> defino un Form dentro de esta opcion, sino utiliza el creado en origen.
     * 			Sin OPCION -> crea un campo hidden que contien la opcion del menu.
     */
    public function barraMenu($menu, $nombre = "", $opcion = "") {
        $conf = CargaConfiguracion::getInstance();
        $anchoPagina = $conf->leeParametro("ancho_pagina");
        $tipoMenu = $conf->leeParametro("tipomenu");
        $nomMenu = $conf->leeParametro("formmenu");
        $opMenu = $conf->leeParametro("opcionmenu");
        $encForm = "";
        $finForm = "";
        $hidden = "";

        if ($nombre == "") {
            $nombre = $nomMenu;

            $encForm = "<form name='" . $nombre . "' method='post' action='respondeMenu.php' >";
            $finForm = "</form>\n";
        }

        if ($opcion == "") {
            $opcion = $opMenu;
            $hidden = "<input type='hidden' name='" . $opcion . "' />\n";
        }


        print "<script language=\"JavaScript\">\n";
        print "function submitform(nameForm,valor){\n";
        print "  if(nameForm==''){\n";
        print "     nombre='$nombre';\n";
        print "  }else{\n";
        print "     nombre=nameForm;\n";
        print "  }\n";
        print "	 document.forms[nombre].action='respondeMenu.php';\n";
        print "  document.forms[nombre]." . $opcion . ".value=valor;\n";
        print "  document.forms[nombre].submit();\n";
        //		print "	 document.forms.".$nombre.".action='respondeMenu.php';\n";
        //		print "  document.forms.".$nombre.".".$opcion.".value=valor;\n";
        //		print "  document.forms.".$nombre.".submit();\n";
        print "}\n";
        print "</script>\n";


        print $encForm;

        switch ($tipoMenu) {
            case 'pd':
                print $hidden;
                $this->dibujaMenuPullDown($menu);
                print "<br style=\"clear:left;\" />";
                break;
            case 'il':
            default:
                print $hidden;
                $this->dibujaMenuInLine($menu);
                break;
        }


        print $finForm;
    }

    protected function dibujaMenuInLine($menu) {
        print "<table width='$anchoPagina' cellspacing=\"0\" cellpadding=\"0\"  class=\"cd_celda_menu\">\n";
        print "  <tr>\n";
        print "     <td>\n";
        print "<table bgcolor=\"#FFF\" cellspacing=\"0\" cellpadding=\"0\"  class=\"cd_celda_menu\" style=\"border-right: 1px solid #FFFFFF;\">\n";
        print "  <tr>\n";
        foreach ($menu as $men) {
            print "		<td class='cd_celda_menu' align='center'><a href=\"javascript: submitform(''," . $men[0] . ")\" >";
            print $men[1] . "</a></td>\n";
        }
        print "  </tr>\n";
        //}
        print "</table>\n";
        print "   </td>\n";
        print " </tr>\n";
        print "</table>\n";
    }

    protected function dibujaMenuPullDown($menu) {
        $perf = new PerfilesBSN();
        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

        if (sizeof($menu) > 0) {
            if ($menu[0][4] == '0') {
                print "<ul  id=\"nav\">\n";
            } else {
                print "<ul>\n";
            }
            $inicio = 0;
            foreach ($menu as $menuItem) {
                if ($inicio == 0 && $menu[0][4] != '0') {
                    print "   <li><a href=\"javascript: submitform(''," . $menuItem[0] . ")\" title=\"" . $menuItem[2] . "\" style=\"border-top: thin solid #24491D;\">" . $menuItem[1] . "</a>";
                    $inicio++;
                } else {
                    if ($perfGpo == 'SUPERUSER' || $perfGpo == 'admin' || $perfGpo == 'GRAL' || strtoupper($perfGpo) == 'GERENCIA' || strtoupper($perfGpo) == 'STAFF' || $menuItem[0] == 9) {
                        print "   <li><a href=\"javascript: submitform(''," . $menuItem[0] . ")\" title=\"" . $menuItem[2] . "\">" . $menuItem[1] . "</a>";
                    }else{
                        print "   <li><a href=\"#\" title=\"" . $menuItem[2] . "\">" . $menuItem[1] . "</a>";
                    }                }
                if (sizeof($menuItem[5]) > 0) {
                    $this->dibujaMenuPullDown($menuItem[5]);
                }
                print "</li>\n";
            }
            print "</ul>\n";
        }
    }


    public function barraMenuAjax($menu, $nombre = "", $opcion = "") {
        $conf = CargaConfiguracion::getInstance();
        $anchoPagina = $conf->leeParametro("ancho_pagina");
        $tipoMenu = $conf->leeParametro("tipomenu");
        $nomMenu = $conf->leeParametro("formmenu");
        $opMenu = $conf->leeParametro("opcionmenu");
        $encForm = "";
        $finForm = "";
        $hidden = "";

        if ($nombre == "") {
            $nombre = $nomMenu;

            $encForm = "<form name='" . $nombre . "' method='post' action='respondeMenu.php' >";
            $finForm = "</form>\n";
        }

        if ($opcion == "") {
            $opcion = $opMenu;
            $hidden = "<input type='hidden' name='" . $opcion . "' />\n";
        }

        
        $cuerpo= "<script language=\"JavaScript\">\n";
        $cuerpo.="function submitform(nameForm,valor){\n";
        $cuerpo.="  if(nameForm==''){\n";
        $cuerpo.="     nombre='$nombre';\n";
        $cuerpo.="  }else{\n";
        $cuerpo.="     nombre=nameForm;\n";
        $cuerpo.="  }\n";
        $cuerpo.="	 document.forms[nombre].action='respondeMenu.php';\n";
        $cuerpo.="  document.forms[nombre]." . $opcion . ".value=valor;\n";
        $cuerpo.="  document.forms[nombre].submit();\n";
        $cuerpo.="}\n";
        $cuerpo.="</script>\n";


        $cuerpo.=$encForm;

        switch ($tipoMenu) {
            case 'pd':
                $cuerpo.= $hidden;
                $cuerpo.=($this->dibujaMenuPullDownAjax($menu));
                $cuerpo.="<br style=\"clear:left;\" />";
                break;
            case 'il':
            default:
                $cuerpo.= $hidden;
                $cuerpo.=($this->dibujaMenuInLineAjax($menu));
                break;
        }


        $cuerpo.=$finForm;
        return $cuerpo;
    }

    protected function dibujaMenuInLineAjax($menu) {
        $aux='';
        $aux.="<table width='$anchoPagina' cellspacing=\"0\" cellpadding=\"0\"  class=\"cd_celda_menu\">\n";
        $aux.="  <tr>\n";
        $aux.="     <td>\n";
        $aux.="<table bgcolor=\"#FFF\" cellspacing=\"0\" cellpadding=\"0\"  class=\"cd_celda_menu\" style=\"border-right: 1px solid #FFFFFF;\">\n";
        $aux.="  <tr>\n";
        foreach ($menu as $men) {
            $aux.=("		<td class='cd_celda_menu' align='center'><a href=\"javascript: submitform(''," . $men[0] . ")\" >");
            $aux.=($men[1] . "</a></td>\n");
        }
        $aux.="  </tr>\n";
        //}
        $aux.="</table>\n";
        $aux.="   </td>\n";
        $aux.=" </tr>\n";
        $aux.="</table>\n";
        return $aux;
    }

    protected function dibujaMenuPullDownAjax($menu) {
        $aux='';
        $perf = new PerfilesBSN();
        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);

        if (sizeof($menu) > 0) {
            if ($menu[0][4] == '0') {
                $aux.="<ul  id=\"nav\">\n";
            } else {
                $aux.="<ul>\n";
            }
            $inicio = 0;
            foreach ($menu as $menuItem) {
                if ($inicio == 0 && $menu[0][4] != '0') {
                    $aux.=("   <li><a href=\"javascript: submitFormMenu(''," . $menuItem[0] . ")\" title=\"" . $menuItem[2] . "\" style=\"border-top: thin solid #24491D;\">" . $menuItem[1] . "</a>");
                    $inicio++;
                } else {
                    if ($perfGpo == 'SUPERUSER' || $perfGpo == 'admin' || $perfGpo == 'GRAL' || strtoupper($perfGpo) == 'GERENCIA' || strtoupper($perfGpo) == 'STAFF' || $menuItem[0] == 9) {
                        $aux.=("   <li><a href=\"javascript: submitFormMenu(''," . $menuItem[0] . ")\" title=\"" . $menuItem[2] . "\">" . $menuItem[1] . "</a>");
                    }else{
                        $aux.=("   <li><a href=\"#\" title=\"" . $menuItem[2] . "\">" . $menuItem[1] . "</a>");
                    }                }
                if (sizeof($menuItem[5]) > 0) {
                    $aux.=($this->dibujaMenuPullDownAjax($menuItem[5]));
                }
                $aux.="</li>\n";
            }
            $aux.="</ul>\n";
        }
        return $aux;
    }
    
    
    
    public function menuAnterior($_actual) {
        $retorno = 0;
        $menu = new MenuPGDAO();
        $conf = CargaConfiguracion::getInstance();
        $tipodb = $conf->leeParametro('tipodb');
        if ($tipodb == "my") {
            $nrow = "mysql_numrows";
            $fetch = "mysql_fetch_array";
        } else {
            $nrow = "pg_numrows";
            $fetch = "pg_fetch_array";
        }
        $result = $menu->findByPadre($_actual);
        if ($nrow($result) != 0) {
            while ($row = $fetch($result)) {
                $retorno = $row['padre'];
            }
            $result = $menu->findByPadre($retorno);
            if ($nrow($result) != 0) {
                while ($row = $fetch($result)) {
                    $retorno = $row['padre'];
                }
            }
        } else {
            die("Fallo la aplicacion por favor comuniquese con Gestion Tecnica");
        }
        return $retorno;
    }

    public function dibujaMenu($nombre = "", $opcion = "") {
        if (!array_key_exists('opcionMenu', $_SESSION)) {
            $_SESSION['opcionMenu'] = 0;
        }
        $padre = $_SESSION['opcionMenu'];
        if (!array_key_exists('Userrole', $_SESSION)) {
            $_SESSION['Userrole'] = '';
        }
        $perfil = $_SESSION['Userrole'];

        $acceso = new Acceso();
        $opMenu = $acceso->arrayOpciones($padre, $perfil);
        //		print_r($opMenu);
        $this->armaPathMenu($padre);
        $this->barraMenu($opMenu, $nombre, $opcion);
    }

    public function dibujaMenuAjax($nombre = "", $opcion = "") {
        if (!array_key_exists('opcionMenu', $_SESSION)) {
            $_SESSION['opcionMenu'] = 0;
        }
        $padre = $_SESSION['opcionMenu'];
        if (!array_key_exists('Userrole', $_SESSION)) {
            $_SESSION['Userrole'] = '';
        }
        $perfil = $_SESSION['Userrole'];

        $acceso = new Acceso();
        $opMenu = $acceso->arrayOpciones($padre, $perfil);
        //		print_r($opMenu);
        $this->armaPathMenu($padre);
        return $this->barraMenuAjax($opMenu, $nombre, $opcion);
    }

    
    protected function armaPathMenu($_actual) {
        if ($_actual != 0) {
            $menuDB = new MenuPGDAO();
            $result = $menuDB->findById($_actual);
            $conf = CargaConfiguracion::getInstance();
            $tipodb = $conf->leeParametro('tipodb');
            if ($tipodb == "my") {
                $nrow = "mysql_numrows";
                $fetch = "mysql_fetch_array";
            } else {
                $nrow = "pg_numrows";
                $fetch = "pg_fetch_array";
            }
            while ($row = $fetch($result)) {
                $opcion = $row['opcion'];
            }
            $this->armaPathMenu($row['padre']);
            //echo "->".$opcion;
        } else {
            //echo "Principal";
        }
    }

}

// FIN CLASE
?>