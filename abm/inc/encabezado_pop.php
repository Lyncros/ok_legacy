<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>O'Keefe Propiedades</title>
<script language="JavaScript" type="text/javascript" src="inc/funciones.js"></script>
<script language="JavaScript" type="text/javascript" src="inc/codigo.js"></script>
<link href="css/ventanas.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="jquery.ui-1.5.2/themes/ui.datepicker.css"  />
<link rel="stylesheet" type="text/css" href="css/vistaTablas.css" />

<script type="text/javascript" src="jquery.ui-1.5.2/jquery.js"></script>
<script type="text/javascript" src="jquery.ui-1.5.2/ui/ui.datepicker.js"></script>
<script language="javascript" type="text/javascript">
            var StayAlive = 1;
            function KillMe(){
                setTimeout("self.close();",StayAlive * 1000);
            }
            <?php if($tipocont != ''){?>
            function RepaintOrigen(){
                listaDomicilios(<?php echo "'$tipocont',$cont, '$div'";?>);
            }
			<?php }?>
        </script>
<script type="text/javascript">
document.oncontextmenu = function(){return false;}
</script>
</head>
<body onload="this.window.focus();">
