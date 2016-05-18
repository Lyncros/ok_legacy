// Documento JavaScript
// Esta funci�n cargar� las paginas

function llamarasincrono(url, id_contenedor){
    var pagina_requerida = false
    if (window.XMLHttpRequest) {// Si es Mozilla, Safari etc
        pagina_requerida = new XMLHttpRequest()
    } else if (window.ActiveXObject){ // pero si es IE
        try {
            pagina_requerida = new ActiveXObject("Msxml2.XMLHTTP")
        } 
        catch (e){ // en caso que sea una versi�n antigua
            try{
                pagina_requerida = new ActiveXObject("Microsoft.XMLHTTP")
            }
            catch (e){}
        }
    }
    else
        return false
    pagina_requerida.onreadystatechange=function(){ // funci�n de respuesta
        cargarpagina(pagina_requerida, id_contenedor)
    }

    pagina_requerida.open('GET', url, true) // asignamos los m�todos open y send
    pagina_requerida.send(null)
}


// todo es correcto y ha llegado el momento de poner la informaci�n requerida
// en su sitio en la pagina xhtml
function cargarpagina(pagina_requerida, id_contenedor){
    //	alert(pagina_requerida+' -- '+id_contenedor);
    if (pagina_requerida.readyState == 4 && (pagina_requerida.status==200 || window.location.href.indexOf("http")==-1)){
        //		alert(pagina_requerida+' -- '+id_contenedor);
        document.getElementById(id_contenedor).innerHTML=pagina_requerida.responseText
    } else {
    //		document.getElementById(id_contenedor).innerHTML="Fallo la carga."
    }
}

function comboTipocomponente(campo,valor,destino){
    tipo=document.getElementById(campo).value;
    if(tipo=='C' || tipo==null){
        document.getElementById('divCompoCli').style.display='block';
        elemTipo=document.getElementById('aux_id');
        if(elemTipo != null){
            document.getElementById('aux_id').name='id';
            document.getElementById('aux_id').id='id';
        }
    }else{
        elemTipo=document.getElementById('id');
        if(elemTipo != null){
            document.getElementById('id').name='aux_id';
            document.getElementById('id').id='aux_id';
        }
        document.getElementById('divCompoCli').style.display='none';
    }
    url='llenaComboComponente.php?t='+tipo+'&v='+valor;
    llamarasincrono(url,destino);
}


function comboPromociones(campo,valor,destino){
    tipo=document.getElementById(campo).value;
    if(tipo==3 || tipo==null){
        document.getElementById('divPromoCli').style.display='block';
        elemPromo=document.getElementById('aux_promo');
        if(elemPromo != null){
            document.getElementById('aux_promo').name='id_promo';
            document.getElementById('aux_promo').id='id_promo';
        }
    }else{
        elemPromo=document.getElementById('id_promo');
        if(elemPromo != null){
            document.getElementById('id_promo').name='aux_promo';
            document.getElementById('id_promo').id='aux_promo';
        }
        document.getElementById('divPromoCli').style.display='none';
    }
    url='llenaComboPromocion.php?t='+tipo+'&v='+valor;
    llamarasincrono(url,destino);
}

function listaComponentesTarea(evento,destino){
    url='llenaListaConmponentesTarea.php?ev='+evento;
    llamarasincrono(url,destino);
}


function listaRelacionesCliente(id,destino){
    url='llenaListaRelaciones.php?id='+id;
    llamarasincrono(url,destino);
}

function listaTareas(tipocont,cont,destino){
    url='llenaListaTareas.php?t='+tipocont+'&tc='+cont;
    llamarasincrono(url,destino);
}

function listaTelefonos(tipocont,cont,destino){
    url='llenaListaTelefonos.php?t='+tipocont+'&tc='+cont;
    llamarasincrono(url,destino);
}

function listaDomicilios(tipocont,cont,destino){
    url='llenaListaDomicilios.php?t='+tipocont+'&tc='+cont;
    llamarasincrono(url,destino);
}

function listaFamiliares(tipocont,cont,destino){
    url='llenaListaFamiliares.php?t='+tipocont+'&tc='+cont;
    llamarasincrono(url,destino);
}

function listaMediosElectronicos(tipocont,cont,destino){
    url='llenaListaMediosElectronicos.php?t='+tipocont+'&tc='+cont;
    llamarasincrono(url,destino);
}

function comboLocalidad(campo,destino,actual,campoloc,campoemp){
    valor=document.getElementById(campo).value;
    //	alert(campoemp+' * '+destino+' * '+valor);	
    url='llenaComboLocalidad.php?p='+valor+'&a='+actual+'&c='+campoloc+'&e='+campoemp;
    llamarasincrono(url,destino);
}

function comboEmprendimiento(zona,actual,destino){
    url='llenaComboEmprendimiento.php?z='+zona+'&a='+actual;
    //        window.open(url);
    //        alert(url+' - '+destino);
    llamarasincrono(url,destino);
}

function comboSubtipo_prop(campo,destino,actual,campoloc){
    valor=document.getElementById(campo).value;
    //	alert(campo+' * '+destino+' * '+valor);	
    url='llenaComboSubtipo_prop.php?p='+valor+'&a='+actual+'&c='+campoloc;
    llamarasincrono(url,destino);
}


function comboTipoCarac(campo,destino){
    valor=document.getElementById(campo).value;
    //	alert(campo+' * '+destino+' * '+valor);	
    url='llenaOrdenTipoCarac.php?p='+valor+'&c='+destino;
    llamarasincrono(url,destino);
}

function llenaDatosprop(campo,destino){
    valor=document.getElementById(campo).value;
    id_prop=document.getElementById('id_prop').value;
    if(id_prop==''){
        id_prop=0;
    }
    //	alert(campo+' * '+destino+' * '+valor);	
    url='llenaDatosprop.php?p='+valor+'&i='+id_prop+'&d='+destino;
    llamarasincrono(url,destino);
}


function muestraCalendario(dia,mes,anio,destino,cdia,cmes,canio){

    if(dia!=''){
        tdia='&dia='+dia;
    }else{
        tdia='';
    }
    if(mes!=''){
        tmes='&mes='+mes;
    }else{
        tmes='';
    }
    if(anio!=''){
        tanio='&anio='+anio;
    }else{
        tanio='';
    }
    url='llenaCalendario.php?p=0'+tdia+tmes+tanio;
    llamarasincrono(url,destino);
	
}

function comboRubro(destino,valor){
    url='recargaComboRubro.php?u='+valor;
    //	alert(url);
    llamarasincrono(url,destino);
}

function recargaComentario(datos,prop,destino){
    url='recargaComentario.php?prop='+prop+'&tipo='+datos;
    llamarasincrono(url,destino);
}

function cargaComponentesRelacion(tipo,id_pc,id_sc,id_rel,destino){
    url ='recargaComponentesRelacion.php?t='+tipo+'&pc='+id_pc+'&sc='+id_sc+'&r='+id_rel;
    //	alert(url);
    llamarasincrono(url,destino);
}

function cargaComponentesRelacionCliente(tipo,id_cli,destino){
    url ='recargaComponentesRelacionCliente.php?t='+tipo+'&c='+id_cli;
    //	alert(url);
    llamarasincrono(url,destino);
}

function filtraContacto(campoCon,campoRub,destino){
    contacto=document.getElementById(campoCon).value;
    rubro=document.getElementById(campoRub).value;
    url='filtrarContacto.php?c='+contacto+'&r='+rubro;
    llamarasincrono(url,destino);
}

function filtraUbicacion(campoNombre,destino){
    nombre=document.getElementById(campoNombre).value;
    url='filtrarUbicacion.php?n='+nombre;
    llamarasincrono(url,destino);
}


function filtraClientes(parametro,id,destino){
    url='filtrarCliente.php?c='+parametro+'&pos=1&id='+id;
    llamarasincrono(url,destino);
}

function muestraCliente(parametro,destino){
    url='muestraCliente.php?c='+parametro;
    llamarasincrono(url,destino);
}

function muestraRelacionCliente(parametro,destino){
    url='muestraRelacionCliente.php?c='+parametro;
    llamarasincrono(url,destino);
}

function muestraPromo(parametro,destino){
    url='muestraPromocion.php?promo='+parametro;
    llamarasincrono(url,destino);
}

function armaFiltro(campo,destino){
    valor=document.getElementById(campo).value;
    //	alert(campo+"++"+valor+"++"+destino);
    url='llenaFiltro.php?p='+valor;
    llamarasincrono(url,destino);
}

function modificaPropiedad(id,destino){
    url='ajax/carga_propiedad.php?i='+id;
    llamarasincrono(url,destino);
}

function muestraDatos(campo,origen,destino){
    valor=document.getElementById(campo).value;
    switch(origen){
        case "D":
            url='muestraDatosProp.php?i='+valor;
            break;
        case "C":
            url='muestraCaracProp.php?i='+valor+'&v=1';
            break;
        case "F":
            url='muestraFotosProp.php?i='+valor;
            break;
        case "P":
            url='muestraPlanosProp.php?i='+valor;
            break;
        case "M":
            url='muestraMapa.php?i='+valor;
            break;
        case "I":
            url='muestraCaracProp.php?i='+valor+'&v=0';
            break;
    }
    //	url='muestraDatosProp.php?i='+valor;
    llamarasincrono(url,destino);
}

function pasaDatosCrm(id,filtro,texto,adjunto){
    var dt = new Date();
    dt.setTime(dt.getTime() + 200);
    while (new Date().getTime() < dt.getTime()){};

    idFiltro=document.getElementById(id).value;
    codFiltro=document.getElementById(filtro).value;
    txtFiltro=document.getElementById(texto).value;
    txtAdjunto=document.getElementById(adjunto).value;
    //	alert(id+"++"+filtro+"++"+texto+"++"+adjunto);
    url='activaWSCrm.php?i='+idFiltro+'&c='+codFiltro+'&t='+txtFiltro+'&a='+txtAdjunto;
    //	url='activaWSCrm.php?i='+id+'&c='+filtro+'&t='+texto+'&a='+adjunto;
    //	url='http://www.zgroupsa.com.ar/achaval/leeCrm.php?i='+idFiltro+'&c='+codFiltro+'&t='+txtFiltro+'&a='+txtAdjunto;
    llamarasincrono(url,'grabado');
}