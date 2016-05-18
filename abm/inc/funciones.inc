<?php

include_once("generic_class/class.cargaParametricos.php");

function armaComboHora($hora_sel) {
    if ($hora_sel != '00') {
        //		$horas = "<option value=\"00\">--</option>\n";
        for ($i = 7; $i <= 22; $i++) {
            if ($hora_sel == $i) {
                if (strlen($i) == 1) {
                    $horas = $horas . "<option value=\"0" . $i . "\" class=\"campoDato\" selected=\"selected\">0" . $i . "</option>\n";
                } else {
                    $horas = $horas . "<option value=\"" . $i . "\" class=\"campoDato\" selected=\"selected\">" . $i . "</option>\n";
                }
            } else {
                if (strlen($i) == 1) {
                    $horas = $horas . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
                } else {
                    $horas = $horas . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
                }
            }
        }
    } else {
        //		$horas = "<option value=\"00\" selected=\"selected\">--</option>\n";
        for ($i = 1; $i <= 24; $i++) {
            if (strlen($i) == 1) {
                $horas = $horas . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
            } else {
                $horas = $horas . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
            }
        }
    }
    return $horas;
}

function armaComboMinu($minu_sel) {
    if ($minu_sel != '00') {
        //		$minu = "<option value=\"00\">--</option>\n";
        for ($i = 0; $i <= 55; $i+=5) {
            if ($minu_sel == $i) {
                if (strlen($i) == 1) {
                    $minu = $minu . "<option value=\"0" . $i . "\" class=\"campoDato\" selected=\"selected\">0" . $i . "</option>\n";
                } else {
                    $minu = $minu . "<option value=\"" . $i . "\" class=\"campoDato\" selected=\"selected\">" . $i . "</option>\n";
                }
            } else {
                if (strlen($i) == 1) {
                    $minu = $minu . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
                } else {
                    $minu = $minu . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
                }
            }
        }
    } else {
        //		$minu = "<option value=\"00\" selected=\"selected\">--</option>\n";
        for ($i = 0; $i <= 55; $i+=5) {
            if (strlen($i) == 1) {
                $minu = $minu . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
            } else {
                $minu = $minu . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
            }
        }
    }
    return $minu;
}

function armaComboDia($dia_sel) {
    if ($dia_sel != '00') {
        $dias = "<option value=\"00\">--</option>\n";
        for ($i = 1; $i <= 31; $i++) {
            if ($dia_sel == $i) {
                if (strlen($i) == 1) {
                    $dias = $dias . "<option value=\"0" . $i . "\" class=\"campoDato\" selected=\"selected\">0" . $i . "</option>\n";
                } else {
                    $dias = $dias . "<option value=\"" . $i . "\" class=\"campoDato\" selected=\"selected\">" . $i . "</option>\n";
                }
            } else {
                if (strlen($i) == 1) {
                    $dias = $dias . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
                } else {
                    $dias = $dias . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
                }
            }
        }
    } else {
        $dias = "<option value=\"00\" selected=\"selected\">--</option>\n";
        for ($i = 1; $i <= 31; $i++) {
            if (strlen($i) == 1) {
                $dias = $dias . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
            } else {
                $dias = $dias . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
            }
        }
    }
    return $dias;
}

function armaComboMes($mes_sel) {
    if ($mes_sel != '00') {
        $mes = "<option value=\"00\">--</option>\n";
        for ($i = 1; $i <= 12; $i++) {
            if ($mes_sel == $i) {
                if (strlen($i) == 1) {
                    $mes = $mes . "<option value=\"0" . $i . "\" class=\"campoDato\" selected=\"selected\">0" . $i . "</option>\n";
                } else {
                    $mes = $mes . "<option value=\"" . $i . "\" class=\"campoDato\" selected=\"selected\">" . $i . "</option>\n";
                }
            } else {
                if (strlen($i) == 1) {
                    $mes = $mes . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
                } else {
                    $mes = $mes . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
                }
            }
        }
    } else {
        $mes = "<option value=\"00\" selected=\"selected\">--</option>\n";
        for ($i = 1; $i <= 12; $i++) {
            if (strlen($i) == 1) {
                $mes = $mes . "<option value=\"0" . $i . "\" class=\"campoDato\">0" . $i . "</option>\n";
            } else {
                $mes = $mes . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
            }
        }
    }
    return $mes;
}

function armaComboAnio($anio_sel) {
    if ($anio_sel != '0000') {
        $anio = "<option value=\"0000\">----</option>\n";
        for ($i = 1930; $i <= 2008; $i++) {
            if ($anio_sel == $i) {
                $anio = $anio . "<option value=\"" . $i . "\" class=\"campoDato\" selected=\"selected\">" . $i . "</option>\n";
            } else {
                $anio = $anio . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
            }
        }
    } else {
        $anio = "<option value=\"0000\" selected=\"selected\">----</option>\n";
        for ($i = 1930; $i <= 2008; $i++) {
            $anio = $anio . "<option value=\"" . $i . "\" class=\"campoDato\">" . $i . "</option>\n";
        }
    }
    return $anio;
}

function armaFecha($dia, $mes, $anio) {
    if ($dia == -1) {
        $dia = "00";
    }
    if ($mes == -1) {
        $mes = "00";
    }
    if ($anio == -1) {
        $anio = "0000";
    }
    return ("'" . $anio . "/" . $mes . "/" . $dia . "'");
}

/*
  function armaComboAmbientes($cant){
  if($cant != '00'){
  //		$ambientes = "<option value=\"00\">--</option>\n";
  for ($i=1; $i <= 15; $i++){
  if($cant == $i){
  if (strlen($i) == 1){
  $ambientes = $ambientes . "<option value=\"" . $i. "\" class=\"campoDato\" selected=\"selected\">".$i." Ambientes</option>\n";
  }
  }else{
  $ambientes = $ambientes . "<option value=\"" . $i. "\" class=\"campoDato\">".$i." Ambientes</option>\n";
  }
  }
  }else{
  //		$ambientes = "<option value=\"00\" selected=\"selected\">--</option>\n";
  for ($i=1; $i <= 15; $i++){
  if (strlen($i) == 1){
  $ambientes = $ambientes . "<option value=\"" . $i. "\" class=\"campoDato\">".$i." Ambientes</option>\n";
  }
  }
  }
  return $ambientes;
  }
 */

function generacalendario($anio, $mes, $dia, $largo_nombre_mes = 3, $primer_dia = 0) {

    $primero_del_mes = gmmktime(0, 0, 0, $mes, 1, $anio);

    $nombre_dias = array(); #generate all the day names according to the current locale
    for ($n = 0, $t = (3 + $primer_dia) * 86400; $n < 7; $n++, $t+=86400) #January 4, 1970 was a Sunday
        $nombre_dias[$n] = utf8_decode(ucfirst(gmstrftime('%A', $t))); #%A means full textual day name

    list($mes, $anio, $nombre_mes, $dia_semana) = explode(',', gmstrftime('%m,%Y,%B,%w', $primero_del_mes));
    $dia_semana = ($dia_semana + 7 - $primer_dia) % 7; #adjust for $primer_dia
    $titulo = htmlentities(ucfirst($nombre_mes)) . '&nbsp;' . $anio;  #note that some locales don't capitalize month and day names

    $p = substr(gmstrftime('%B', mktime(0, 0, 0, $mes, 0, $anio)), 0, $largo_nombre_mes);
    $n = substr(gmstrftime('%B', mktime(0, 0, 0, $mes + 2, 0, $anio)), 0, $largo_nombre_mes);

    if ($mes - 1 == 0) {
        $mes_ant = 12;
        $anio = $anio - 1;
    } else {
        $mes_ant = $mes - 1;
    }

    $p = '&nbsp;<a href="' . $_SERVER[PHP_SELF] . "?dia=$dia&mes=" . $mes_ant . '&anio=' . $anio . '"  class="otro_mes">' . ucfirst($p) . '</a>';

    if ($mes + 1 == 13) {
        $mes_sig = 1;
        $anio = $anio + 1;
    } else {
        $mes_sig = $mes + 1;
    }


    $n = '&nbsp;<a href="' . $_SERVER[PHP_SELF] . "?dia=$dia&mes=" . $mes_sig . '&anio=' . $anio . '" class="otro_mes">' . ucfirst($n) . '</a>&nbsp;';

    $calendar = '<table class="calendar" cellpadding="0" cellspacing="0">' . "\n" .
            '<tr class="TRmes"><td valign="middle">' . $p . '</td><td colspan="5" class="mes">' . $titulo . '<td valign="middle">' . $n . "</td></tr>\n<tr>";

    if ($largo_nombre_mes) { #if the day names should be shown ($largo_nombre_mes > 0)
        #if day_name_length is >3, the full name of the day will be printed
        foreach ($nombre_dias as $d)
            $calendar .= '<td abbr="' . htmlentities($d) . '" class="nombre_dias">' . htmlentities($largo_nombre_mes < 4 ? substr($d, 0, 1) : $d) . '</td>';
        $calendar .= "</tr>\n<tr>";
        $calendar .= "<tr><td colspan='7' height='1' bgcolor='#1A2A71'></td></tr><tr>";
    }

    if ($dia_semana > 0)
        $calendar .= '<td colspan="' . $dia_semana . '" class="dias">&nbsp;</td>';#initial 'empty' days
    for ($day = 1, $dias_in_month = gmdate('t', $primero_del_mes); $day <= $dias_in_month; $day++, $dia_semana++) {
        if ($dia_semana == 7) {
            $dia_semana = 0; #start a new week
            $calendar .= "</tr>\n<tr>";
        }
        if ($dia == $day) {
            $calendar .= "<td class=\"diaselect\">$day</td>";
        } else {
            $calendar .= "<td class=\"dias\"><a href='$_SERVER[PHP_SELF]?dia=$day&mes=$mes&anio=$anio' class=\"dias\">$day</a></td>";
        }
    }
    if ($dia_semana != 7)
        $calendar .= '<td colspan="' . (7 - $dia_semana) . '">&nbsp;</td>';#remaining "empty" days

    return $calendar . "</tr>\n</table>\n";
}

function armaLocalidad($var) {
    $query = "SELECT * FROM okeefe.localidad ORDER BY nombre_loca";
    $result = mysql_query($query);
    print "<select name='localidad' id='localidad' class=\"campos_btn\">";
    if ($var == '') {
        $var = 'CABA';
    }
    while ($row = mysql_fetch_assoc($result)) {
        if ($var == $row['nombre_loca']) {
            print "<option value=\"" . $row['id_loca'] . "\" class=\"campoDato\" selected=\"selected\">" . $row['nombre_loca'] . "</option>\n";
        } else {
            print "<option value=\"" . $row['id_loca'] . "\" class=\"campoDato\">" . $row['nombre_loca'] . "</option>\n";
        }
    }
    print "</select>";
    mysql_free_result($result);
}

function armaProvincia($var) {
    $query = "SELECT * FROM okeefe.zona ORDER BY nombre_zona";
    $result = mysql_query($query);
    print "<select name='provincia' id='provincia' class=\"campos_btn\">";
    if ($var == '') {
        $var = 'CABA';
    }
    while ($row = mysql_fetch_assoc($result)) {
        if ($var == $row['nombre_zona']) {
            print "<option value=\"" . $row['id_zona'] . "\" class=\"campoDato\" selected=\"selected\">" . $row['nombre_zona'] . "</option>\n";
        } else {
            print "<option value=\"" . $row['id_zona'] . "\" class=\"campoDato\">" . $row['nombre_zona'] . "</option>\n";
        }
    }
    print "</select>";
    mysql_free_result($result);
}

function armaBarrio($var) {
    $query = "SELECT nombre FROM agenda.barrios ORDER BY nombre";
    $result = mysql_query($query);
    print "<select name='barrio' id='barrio' class=\"campos_btn\">";
    while ($row = mysql_fetch_assoc($result)) {
        if ($var == $row['nombre']) {
            print "<option value=\"" . $row['nombre'] . "\" class=\"campoDato\" selected=\"selected\">" . $row['nombre'] . "</option>\n";
        } else {
            print "<option value=\"" . $row['nombre'] . "\" class=\"campoDato\">" . $row['nombre'] . "</option>\n";
        }
    }
    print "</select>";
    mysql_free_result($result);
}

function armaAmbientes($var) {
    $query = "SELECT desc_ambientes FROM agenda.ambientes ORDER BY desc_ambientes";
    $result = mysql_query($query);
    print "<select name='id_ambientes' id='id_ambientes' class=\"campos_btn\">";
    while ($row = mysql_fetch_assoc($result)) {
        if ($var == $row['desc_ambientes']) {
            print "<option value=\"" . $row['desc_ambientes'] . "\" class=\"campoDato\" selected=\"selected\">" . $row['desc_ambientes'] . "</option>\n";
        } else {
            print "<option value=\"" . $row['desc_ambientes'] . "\" class=\"campoDato\">" . $row['desc_ambientes'] . "</option>\n";
        }
    }
    print "</select>";
    mysql_free_result($result);
}

function armaOrientacion($var) {
    $array = array('Norte', 'NorEste', 'Este', 'SurEste', 'Sur', 'SurOeste', 'Oeste', 'NorOeste');
    armaComboArray($array, $var, 'orientacion', 'campos_btn', 0);
}

function armaUbicacion($var) {
    $array = array('Frente', 'Lateral', 'Contrafrente');
    armaComboArray($array, $var, 'ubicacion', 'campos_btn', 0);
}

function armaTipoCampo($var) {
    $array = array('CheckBox', 'Lista', 'Numerico', 'Texto', 'Texto Largo', 'Web', 'Usuarios');
    armaComboArray($array, $var, 'tipo', 'campos_btn', 1);
//	print "<select name='tipo' id='tipo' class=\"campos\" onselect='javascript:validaMaximo();' onchange='javascript:validaMaximo();'>";
}

function armaTipoOperacion($var, $opcion = 0, $tipo = 'operacion', $valida = 1) {
    $params = new CargaParametricos('tiposOperacion.xml');
    $array = $params->getParametros();
    armaComboArray2($array, $var, $tipo, 'campos_btn', $valida, $opcion);
}

function armaEstadoCartel($var) {
    $params = new CargaParametricos('estadosCartel.xml');
    $array = $params->getParametros();
    armaComboArray2($array, $var, 'estado', 'campos_btn', 0);
}

function armaEstadoTasacion($var) {
    $params = new CargaParametricos('estadosTasacion.xml');
    $array = $params->getParametros();
    armaComboArray2($array, $var, 'estado', 'campos_btn', 0);
}

function armaEstadoEmprendimiento($var,$opcion=0) {
    $params = new CargaParametricos('estadosEmprendimiento.xml');
    $array = $params->getParametros();
    if($opcion!=0){
        $opcion=3;
    }
    armaComboArray2($array, $var, 'estado', 'campos_btn',0, $opcion);
}

function armaComentarioCampo($var) {
    $array = array('Si', 'No');
    armaComboArray($array, $var, 'comentario', 'campos_btn', 0);
}

function armaIntermediacion($var) {
    $params = new CargaParametricos('tiposIntermediacion.xml');
    $array = $params->getParametros();
    armaComboArray2($array, $var, 'intermediacion', 'campos_btn', 1);
}

function armaTipoDocumento($var) {
    $params = new CargaParametricos('tiposDocumentos.xml');
    $array = $params->getParametros();
    armaComboArray2($array, $var, 'tipo_doc', 'campos_btn', 1);
}

function armaTipoResponsable($var) {
    $params = new CargaParametricos('tiposResponsable.xml');
    $array = $params->getParametros();
    armaComboArray2($array, $var, 'tipo_responsable', 'campos_btn', 1);
}

function armaComboArray($lista, $valor = '', $nombre = 'combo', $clase = 'campos_btn', $valida = 0, $opcion = 0) {
    print "<select name='" . $nombre . "' id='" . $nombre . "'  style='width: 97%;' class=\"" . $clase . "\" ";
    if ($valida == 1) {
        $nomMay = strtoupper(substr($nombre, 0, 1)) . substr($nombre, 1);
        print " onselect='javascript:valida" . $nomMay . "();' onchange='javascript:valida" . $nomMay . "();'";
    }
    print ">";

    switch ($opcion) {
        case 1:
            print "<option value='0'";
            if ($valor == 0) {
                print " SELECTED ";
            }
            print ">Todas</option>\n";
            break;
        case 2:
            print "<option value='0'";
            if ($valor == 0) {
                print " SELECTED ";
            }
            print ">Seleccione una opcion</option>\n";
            break;

        default:
            break;
    }


    foreach ($lista as $var) {
        print "<option value=\"" . $var . "\"";
        if (strcmp($var, $valor) == 0) {
            print " selected=\"selected\"";
        }
        print ">" . $var . "</option>\n";
    }
    print "</select>";
}

function armaComboArray2($lista, $valor = '', $nombre = 'combo', $clase = 'campos_btn', $valida = 0, $opcion = 0) {
    $arrayClaves = array_keys($lista);
    print "<select name='" . $nombre . "' id='" . $nombre . "'  style='width: 97%;' class=\"" . $clase . "\" ";
    if ($valida == 1) {
        $nomMay = strtoupper(substr($nombre, 0, 1)) . substr($nombre, 1);
        print " onselect='javascript:valida" . $nomMay . "();' onchange='javascript:valida" . $nomMay . "();'";
    }
    print ">";

    switch ($opcion) {
        case 1:
            print "<option value='0'";
            if ($valor == 0) {
                print " SELECTED ";
            }
            print ">Todas</option>\n";
            break;
        case 3:
            print "<option value=''";
            if ($valor == '') {
                print " SELECTED ";
            }
            print ">Todas</option>\n";
            break;
        case 2:
            print "<option value='0'";
            if ($valor == 0) {
                print " SELECTED ";
            }
            print ">Seleccione una opcion</option>\n";
            break;

        default:
            break;
    }


    for ($pos = 0; $pos < sizeof($lista); $pos++) {
        print "<option value='" . $arrayClaves[$pos] . "'";
        if ($arrayClaves[$pos] == $valor) {
            print " SELECTED ";
        }
        print ">" . $lista[$arrayClaves[$pos]] . "</option>\n";
    }
    print "</select>";
}

function armaInmo($var) {
    print "<select name='inmobiliaria' id='inmobiliaria' class=\"campos_btn\">";
    if ($var == "inmobiliaria1") {
        print "<option value=\"inmobiliaria1\" class=\"campoDato\" selected=\"selected\">inmobiliaria 1</option>\n";
    } else {
        print "<option value=\"inmobiliaria1\" class=\"campoDato\">inmobiliaria 1</option>\n";
    }
    if ($var == "inmobiliaria1") {
        print "<option value=\"inmobiliaria2\" class=\"campoDato\" selected=\"selected\">inmobiliaria 2</option>\n";
    } else {
        print "<option value=\"inmobiliaria2\" class=\"campoDato\">inmobiliaria 2</option>\n";
    }
    print "</select>";
}

function parserURL() {
    $urlcomp = parse_url(urldecode($_SERVER['REQUEST_URI']));
    $query = array();
    if (array_key_exists('query',$urlcomp)) {
        $urlquery = $urlcomp['query'];
        $querycom = split("&", $urlquery);
        foreach ($querycom as $elem) {
            $comp = split("=", $elem);
            $query[$comp[0]] = $comp[1];
        }
    }
    $_GET = $query;
}

function remover_acentos($str, $utf8=true){
    $str = (string)$str;
    if(is_null($utf8)) {
        if( !function_exists('mb_detect_encoding') ) {
            $utf8 = (strtolower( mb_detect_encoding($str) )=='utf-8');
        }else{
            $length = strlen($str);
            $utf8 = true;
            for ($i=0; $i < $length; $i++) {
                $c = ord($str[$i]);
                if ($c < 0x80) $n = 0; # 0bbbbbbb
                elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
                elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
                elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
                elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
                elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
                else return false; # Does not match any model
                for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
                    if ((++$i == $length)
                        || ((ord($str[$i]) & 0xC0) != 0x80)) {
                        $utf8 = false;
                        break;
                    }

                }
            }
        }

    }

    if(!$utf8)
        $str = utf8_encode($str);

    $transliteration = array(
    'Ĳ' => 'I', 'Ö' => 'O','Œ' => 'O','Ü' => 'U','ä' => 'a','æ' => 'a',
    'ĳ' => 'i','ö' => 'o','œ' => 'o','ü' => 'u','ß' => 's','ſ' => 's',
    'À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A',
    'Æ' => 'A','Ā' => 'A','Ą' => 'A','Ă' => 'A','Ç' => 'C','Ć' => 'C',
    'Č' => 'C','Ĉ' => 'C','Ċ' => 'C','Ď' => 'D','Đ' => 'D','È' => 'E',
    'É' => 'E','Ê' => 'E','Ë' => 'E','Ē' => 'E','Ę' => 'E','Ě' => 'E',
    'Ĕ' => 'E','Ė' => 'E','Ĝ' => 'G','Ğ' => 'G','Ġ' => 'G','Ģ' => 'G',
    'Ĥ' => 'H','Ħ' => 'H','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I',
    'Ī' => 'I','Ĩ' => 'I','Ĭ' => 'I','Į' => 'I','İ' => 'I','Ĵ' => 'J',
    'Ķ' => 'K','Ľ' => 'K','Ĺ' => 'K','Ļ' => 'K','Ŀ' => 'K','Ł' => 'L',
    'Ñ' => 'N','Ń' => 'N','Ň' => 'N','Ņ' => 'N','Ŋ' => 'N','Ò' => 'O',
    'Ó' => 'O','Ô' => 'O','Õ' => 'O','Ø' => 'O','Ō' => 'O','Ő' => 'O',
    'Ŏ' => 'O','Ŕ' => 'R','Ř' => 'R','Ŗ' => 'R','Ś' => 'S','Ş' => 'S',
    'Ŝ' => 'S','Ș' => 'S','Š' => 'S','Ť' => 'T','Ţ' => 'T','Ŧ' => 'T',
    'Ț' => 'T','Ù' => 'U','Ú' => 'U','Û' => 'U','Ū' => 'U','Ů' => 'U',
    'Ű' => 'U','Ŭ' => 'U','Ũ' => 'U','Ų' => 'U','Ŵ' => 'W','Ŷ' => 'Y',
    'Ÿ' => 'Y','Ý' => 'Y','Ź' => 'Z','Ż' => 'Z','Ž' => 'Z','à' => 'a',
    'á' => 'a','â' => 'a','ã' => 'a','ā' => 'a','ą' => 'a','ă' => 'a',
    'å' => 'a','ç' => 'c','ć' => 'c','č' => 'c','ĉ' => 'c','ċ' => 'c',
    'ď' => 'd','đ' => 'd','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e',
    'ē' => 'e','ę' => 'e','ě' => 'e','ĕ' => 'e','ė' => 'e','ƒ' => 'f',
    'ĝ' => 'g','ğ' => 'g','ġ' => 'g','ģ' => 'g','ĥ' => 'h','ħ' => 'h',
    'ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ī' => 'i','ĩ' => 'i',
    'ĭ' => 'i','į' => 'i','ı' => 'i','ĵ' => 'j','ķ' => 'k','ĸ' => 'k',
    'ł' => 'l','ľ' => 'l','ĺ' => 'l','ļ' => 'l','ŀ' => 'l','ñ' => 'n',
    'ń' => 'n','ň' => 'n','ņ' => 'n','ŉ' => 'n','ŋ' => 'n','ò' => 'o',
    'ó' => 'o','ô' => 'o','õ' => 'o','ø' => 'o','ō' => 'o','ő' => 'o',
    'ŏ' => 'o','ŕ' => 'r','ř' => 'r','ŗ' => 'r','ś' => 's','š' => 's',
    'ť' => 't','ù' => 'u','ú' => 'u','û' => 'u','ū' => 'u','ů' => 'u',
    'ű' => 'u','ŭ' => 'u','ũ' => 'u','ų' => 'u','ŵ' => 'w','ÿ' => 'y',
    'ý' => 'y','ŷ' => 'y','ż' => 'z','ź' => 'z','ž' => 'z','Α' => 'A',
    'Ά' => 'A','Ἀ' => 'A','Ἁ' => 'A','Ἂ' => 'A','Ἃ' => 'A','Ἄ' => 'A',
    'Ἅ' => 'A','Ἆ' => 'A','Ἇ' => 'A','ᾈ' => 'A','ᾉ' => 'A','ᾊ' => 'A',
    'ᾋ' => 'A','ᾌ' => 'A','ᾍ' => 'A','ᾎ' => 'A','ᾏ' => 'A','Ᾰ' => 'A',
    'Ᾱ' => 'A','Ὰ' => 'A','ᾼ' => 'A','Β' => 'B','Γ' => 'G','Δ' => 'D',
    'Ε' => 'E','Έ' => 'E','Ἐ' => 'E','Ἑ' => 'E','Ἒ' => 'E','Ἓ' => 'E',
    'Ἔ' => 'E','Ἕ' => 'E','Ὲ' => 'E','Ζ' => 'Z','Η' => 'I','Ή' => 'I',
    'Ἠ' => 'I','Ἡ' => 'I','Ἢ' => 'I','Ἣ' => 'I','Ἤ' => 'I','Ἥ' => 'I',
    'Ἦ' => 'I','Ἧ' => 'I','ᾘ' => 'I','ᾙ' => 'I','ᾚ' => 'I','ᾛ' => 'I',
    'ᾜ' => 'I','ᾝ' => 'I','ᾞ' => 'I','ᾟ' => 'I','Ὴ' => 'I','ῌ' => 'I',
    'Θ' => 'T','Ι' => 'I','Ί' => 'I','Ϊ' => 'I','Ἰ' => 'I','Ἱ' => 'I',
    'Ἲ' => 'I','Ἳ' => 'I','Ἴ' => 'I','Ἵ' => 'I','Ἶ' => 'I','Ἷ' => 'I',
    'Ῐ' => 'I','Ῑ' => 'I','Ὶ' => 'I','Κ' => 'K','Λ' => 'L','Μ' => 'M',
    'Ν' => 'N','Ξ' => 'K','Ο' => 'O','Ό' => 'O','Ὀ' => 'O','Ὁ' => 'O',
    'Ὂ' => 'O','Ὃ' => 'O','Ὄ' => 'O','Ὅ' => 'O','Ὸ' => 'O','Π' => 'P',
    'Ρ' => 'R','Ῥ' => 'R','Σ' => 'S','Τ' => 'T','Υ' => 'Y','Ύ' => 'Y',
    'Ϋ' => 'Y','Ὑ' => 'Y','Ὓ' => 'Y','Ὕ' => 'Y','Ὗ' => 'Y','Ῠ' => 'Y',
    'Ῡ' => 'Y','Ὺ' => 'Y','Φ' => 'F','Χ' => 'X','Ψ' => 'P','Ω' => 'O',
    'Ώ' => 'O','Ὠ' => 'O','Ὡ' => 'O','Ὢ' => 'O','Ὣ' => 'O','Ὤ' => 'O',
    'Ὥ' => 'O','Ὦ' => 'O','Ὧ' => 'O','ᾨ' => 'O','ᾩ' => 'O','ᾪ' => 'O',
    'ᾫ' => 'O','ᾬ' => 'O','ᾭ' => 'O','ᾮ' => 'O','ᾯ' => 'O','Ὼ' => 'O',
    'ῼ' => 'O','α' => 'a','ά' => 'a','ἀ' => 'a','ἁ' => 'a','ἂ' => 'a',
    'ἃ' => 'a','ἄ' => 'a','ἅ' => 'a','ἆ' => 'a','ἇ' => 'a','ᾀ' => 'a',
    'ᾁ' => 'a','ᾂ' => 'a','ᾃ' => 'a','ᾄ' => 'a','ᾅ' => 'a','ᾆ' => 'a',
    'ᾇ' => 'a','ὰ' => 'a','ᾰ' => 'a','ᾱ' => 'a','ᾲ' => 'a','ᾳ' => 'a',
    'ᾴ' => 'a','ᾶ' => 'a','ᾷ' => 'a','β' => 'b','γ' => 'g','δ' => 'd',
    'ε' => 'e','έ' => 'e','ἐ' => 'e','ἑ' => 'e','ἒ' => 'e','ἓ' => 'e',
    'ἔ' => 'e','ἕ' => 'e','ὲ' => 'e','ζ' => 'z','η' => 'i','ή' => 'i',
    'ἠ' => 'i','ἡ' => 'i','ἢ' => 'i','ἣ' => 'i','ἤ' => 'i','ἥ' => 'i',
    'ἦ' => 'i','ἧ' => 'i','ᾐ' => 'i','ᾑ' => 'i','ᾒ' => 'i','ᾓ' => 'i',
    'ᾔ' => 'i','ᾕ' => 'i','ᾖ' => 'i','ᾗ' => 'i','ὴ' => 'i','ῂ' => 'i',
    'ῃ' => 'i','ῄ' => 'i','ῆ' => 'i','ῇ' => 'i','θ' => 't','ι' => 'i',
    'ί' => 'i','ϊ' => 'i','ΐ' => 'i','ἰ' => 'i','ἱ' => 'i','ἲ' => 'i',
    'ἳ' => 'i','ἴ' => 'i','ἵ' => 'i','ἶ' => 'i','ἷ' => 'i','ὶ' => 'i',
    'ῐ' => 'i','ῑ' => 'i','ῒ' => 'i','ῖ' => 'i','ῗ' => 'i','κ' => 'k',
    'λ' => 'l','μ' => 'm','ν' => 'n','ξ' => 'k','ο' => 'o','ό' => 'o',
    'ὀ' => 'o','ὁ' => 'o','ὂ' => 'o','ὃ' => 'o','ὄ' => 'o','ὅ' => 'o',
    'ὸ' => 'o','π' => 'p','ρ' => 'r','ῤ' => 'r','ῥ' => 'r','σ' => 's',
    'ς' => 's','τ' => 't','υ' => 'y','ύ' => 'y','ϋ' => 'y','ΰ' => 'y',
    'ὐ' => 'y','ὑ' => 'y','ὒ' => 'y','ὓ' => 'y','ὔ' => 'y','ὕ' => 'y',
    'ὖ' => 'y','ὗ' => 'y','ὺ' => 'y','ῠ' => 'y','ῡ' => 'y','ῢ' => 'y',
    'ῦ' => 'y','ῧ' => 'y','φ' => 'f','χ' => 'x','ψ' => 'p','ω' => 'o',
    'ώ' => 'o','ὠ' => 'o','ὡ' => 'o','ὢ' => 'o','ὣ' => 'o','ὤ' => 'o',
    'ὥ' => 'o','ὦ' => 'o','ὧ' => 'o','ᾠ' => 'o','ᾡ' => 'o','ᾢ' => 'o',
    'ᾣ' => 'o','ᾤ' => 'o','ᾥ' => 'o','ᾦ' => 'o','ᾧ' => 'o','ὼ' => 'o',
    'ῲ' => 'o','ῳ' => 'o','ῴ' => 'o','ῶ' => 'o','ῷ' => 'o','А' => 'A',
    'Б' => 'B','В' => 'V','Г' => 'G','Д' => 'D','Е' => 'E','Ё' => 'E',
    'Ж' => 'Z','З' => 'Z','И' => 'I','Й' => 'I','К' => 'K','Л' => 'L',
    'М' => 'M','Н' => 'N','О' => 'O','П' => 'P','Р' => 'R','С' => 'S',
    'Т' => 'T','У' => 'U','Ф' => 'F','Х' => 'K','Ц' => 'T','Ч' => 'C',
    'Ш' => 'S','Щ' => 'S','Ы' => 'Y','Э' => 'E','Ю' => 'Y','Я' => 'Y',
    'а' => 'A','б' => 'B','в' => 'V','г' => 'G','д' => 'D','е' => 'E',
    'ё' => 'E','ж' => 'Z','з' => 'Z','и' => 'I','й' => 'I','к' => 'K',
    'л' => 'L','м' => 'M','н' => 'N','о' => 'O','п' => 'P','р' => 'R',
    'с' => 'S','т' => 'T','у' => 'U','ф' => 'F','х' => 'K','ц' => 'T',
    'ч' => 'C','ш' => 'S','щ' => 'S','ы' => 'Y','э' => 'E','ю' => 'Y',
    'я' => 'Y','ð' => 'd','Ð' => 'D','þ' => 't','Þ' => 'T','ა' => 'a',
    'ბ' => 'b','გ' => 'g','დ' => 'd','ე' => 'e','ვ' => 'v','ზ' => 'z',
    'თ' => 't','ი' => 'i','კ' => 'k','ლ' => 'l','მ' => 'm','ნ' => 'n',
    'ო' => 'o','პ' => 'p','ჟ' => 'z','რ' => 'r','ს' => 's','ტ' => 't',
    'უ' => 'u','ფ' => 'p','ქ' => 'k','ღ' => 'g','ყ' => 'q','შ' => 's',
    'ჩ' => 'c','ც' => 't','ძ' => 'd','წ' => 't','ჭ' => 'c','ხ' => 'k',
    'ჯ' => 'j','ჰ' => 'h','#' => '','@' => '','%' => '','º' => '','&' => '','$' => '','˚' => ''
    );
    $str = str_replace( array_keys( $transliteration ),
                        array_values( $transliteration ),
                        $str);
    return $str;
}
//- remove_accents()

?>
