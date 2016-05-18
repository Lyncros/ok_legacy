<?php
/*
DROP TABLE IF EXISTS `achaval`.`cartel`;
CREATE TABLE  `achaval`.`cartel` (
  `id_cartel` int(10) unsigned NOT NULL auto_increment,
  `id_prop` int(10) unsigned NOT NULL,
  `estado` varchar(45) NOT NULL,
  `fecha` datetime NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `observacion` varchar(500) NOT NULL,
  PRIMARY KEY  (`id_cartel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
*/
$mapaBase=array(array('form'=>"id_cartel",'objeto'=>"id_cartel",'tabla'=>"id_cartel"),
			array('form'=>"id_prop",'objeto'=>"id_prop",'tabla'=>"id_prop"),
			array('form'=>"estado",'objeto'=>"estado",'tabla'=>"estado"),
			array('form'=>"cfecha",'objeto'=>"cfecha",'tabla'=>"cfecha"),
			array('form'=>"proveedor",'objeto'=>"proveedor",'tabla'=>"proveedor"),	
			array('form'=>"observacion",'objeto'=>"observacion",'tabla'=>"observacion")		
			);
?>