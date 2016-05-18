<div id="resultado">
    <div id="fotoResul" border="0"><a href="<?php echo strtolower(sanear_string($txtTipo));?>_<?php echo strtolower(sanear_string($prop[$i]['operacion']));?>_<?php echo strtolower(sanear_string(trim($loca)));?>_<?php echo strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100)));?>_<?php echo $id_prop; ?>">
      <?php if($fotos[0]['foto'] != "") { ?>
      <img src="http://abm.okeefe.com.ar/fotos_th/<?php echo $fotos[0]['foto']; ?>" width="160" height="107" border="0" alt="<?php echo substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100);?>" />
      <?php }else { ?>
      <img src="images/noDisponible.gif" width="160" height="107" alt="<?php echo substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100);?>" />
      <?php }?>
    </a></div>
    <div id="contenidoResul" style="float:right; width:830px; height:107px; margin-left:5px;">
      <div id="cabezaResul">
        <div id="zonaResul"><h2><a href="<?php echo strtolower(sanear_string($txtTipo));?>_<?php echo strtolower(sanear_string($prop[$i]['operacion']));?>_<?php echo strtolower(sanear_string(trim($loca)));?>_<?php echo strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100)));?>_<?php echo $id_prop; ?>" style="text-decoration:none; color:inherit;"><?php echo substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100);?></a></h2></div>
        <div id="codigoResul">ID <?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?></div>
		<?php if($prop[$i]['oportunidad'] == 1){ ?>
        <div class="oportunidadResul">Oportunidad</div>          
        <?php } ?>
      </div>
      <div id="cuerpoResul" style="width:830px; clear:both;">
        <div id="datos1Resul"><span class="tituResult">Ubicación</span> <span class="caracResul"><?php echo  substr($loca, 0, 45); ?></span><br />
<span class="tituResult">Superficie:</span> <span class="caracResul">
          <?php
		   echo busca_valor($prop[$i]['id_prop'], 198, $carac);
		   if($prop[$i]['id_tipo_prop'] == 6 || $prop[$i]['id_tipo_prop'] == 16){
			   echo "Ha.";
		   }else{
			   echo "m2";
		   }
	
		  switch ($prop[$i]['id_tipo_prop']){
			case 6;
			case 7;
			case 16:
			  	$buscar=303;
				$tituBuscar="Aptitud";
				$valor=busca_valor($prop[$i]['id_prop'], 303, $carac);
				break;
			default:
			  	$valor=$ambientes;
				$tituBuscar="Ambientes";
				break;
		  }
		  ?>
          </span><br />
          <span class="tituResult"><?php echo $tituBuscar . ": </span><span class=\"caracResul\">" . $valor;?></span><br />
          <span class="tituResult">Categoría:</span> <span class="caracResul"><?php echo $txtTipo;?></span><br />
          <span class="tituResult">Estado:</span> <span class="caracResul"><?php echo $prop[$i]['operacion'];?></span><br />
          <span class="tituResult">Precio:</span> <span class="caracResul"><?php echo $moneda . $precio;?></span>
        </div>
        <div id="descripResul"><a href="<?php echo strtolower(sanear_string($txtTipo));?>_<?php echo strtolower(sanear_string($prop[$i]['operacion']));?>_<?php echo strtolower(sanear_string(trim($loca)));?>_<?php echo strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100)));?>_<?php echo $id_prop; ?>">
        <div style="height:71px; max-height:71px; overflow:hidden;"><span class="tituResult">Descripción:</span><br />
          <span>
          <?php
            $desc = busca_valor($prop[$i]['id_prop'], 255, $carac);
            $MaxLENGTH = 250;
            if (strlen($desc) > $MaxLENGTH) {
                $desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
                $desc .= '...';
            }
            echo $desc;
            ?>
            </span>
            </div></a>
            <div>
          <div id="vermasResul"><a href="<?php echo strtolower(sanear_string($txtTipo));?>_<?php echo strtolower(sanear_string($prop[$i]['operacion']));?>_<?php echo strtolower(sanear_string(trim($loca)));?>_<?php echo strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100)));?>_<?php echo $id_prop; ?>" style="color:#FFF; text-decoration:none" rel="nofollow">Más detalles</a></div>
          </div>
          
        </div>
        <div id="datos2Resul">
        <?php if($prop[$i]['id_emp'] != 0){ ?>
        	<div id="verEmpre"><a href="detalleEmprendimiento.php?i=<?php echo $prop[$i]['id_emp'];?>" style="color:#FFF;" rel="nofollow">Emprendimiento</a></div>
        <?php }else{ ?>
        	<div style="height:18px;margin-bottom:4px;"></div>
        <?php } ?>
          <div class="consultar" style="margin-bottom:4px;"><a href="form_recomendar.php?id=<?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?>&TB_iframe=true&height=300&width=300&modal=false" class="thickbox" rel="nofollow">&gt; Recomendar</a></div>
          <div class="consultar"><a href="form_consulta_prop.php?id=<?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?>&t=<?php echo $prop[$i]['id_tipo_prop']; ?>&TB_iframe=true&height=320&width=800&modal=false" class="thickbox" rel="nofollow">&gt; Consultar</a></div>
  <?php if($miBusqueda == 0){?>
          <div class="favoritos"><a href="javascript: Set_Cookie('id[<?php echo $id_prop; ?>]',<?php echo $id_prop; ?>);" rel="nofollow"> Favoritos</a></div>
        </div>
        <?php }else{ ?>
          <div class="favoritosMenos"><a href="javascript: Delete_Cookie('id[<?php echo $id_prop; ?>]',<?php echo $id_prop; ?>);location.reload(); " rel="nofollow"> Favoritos</a></div>
        </div>
        <?php } ?>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>