/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//0 campo,1 tipo,2 max,3 label,4 posx,5 posy,6 ancho,7 alto,8 default,9 contDatos,
//10 campovalor,11 divContenedor,12 clase,13 opciones,14 clase titulo 
// posicion de OPCIONES, sirve para indicar el nombre del array que compone las opciones
// del campo Select o del Campo Autocomp.
// en el primer caso es un array de dos dimensiones, donde la segunda corresponde al par valor ID - Nombre
// para el autocomp, es un array con la definicion del Fuente de la recarga y el nombre de la funcion que resive el parametro de la seleccion
arrCamposDom=Array();
arrCamposDom.push(['id_dom' ,'hidden'   ,0,''           ,0,0,100,20     ,0  ,'arrDatosDom',0,'div_dom','','','tituColCli']);
arrCamposDom.push(['tipo'   ,'select'   ,0,'Tipo Dom'   ,30,0,80,16     ,0  ,'arrDatosDom',1,'div_dom','','arrDom','tituColCli']);
arrCamposDom.push(['ubica'  ,'autocomp' ,0,'Ubicacion'  ,60,0,100,16    ,0  ,'arrDatosDom',2,'div_dom','','arrDefUbica','tituColCli']);
arrCamposDom.push(['id_ubica'  ,'hidden',0,''           ,60,0,100,16    ,0  ,'arrDatosDom',2,'div_dom','','','tituColCli']);
arrCamposDom.push(['calle'  ,'text'     ,0,'Calle'      ,90,0,100,16    ,'' ,'arrDatosDom',3,'div_dom','','','tituColCli']);
arrCamposDom.push(['nro'    ,'text'     ,0,'Nro'        ,120,0,50,16    ,'' ,'arrDatosDom',4,'div_dom','','','tituColCli']);
arrCamposDom.push(['piso'   ,'text'     ,0,'Piso'       ,180,0,35,16    ,'' ,'arrDatosDom',5,'div_dom','','','tituColCli']);
arrCamposDom.push(['dpto'   ,'text'     ,0,'Dpto'       ,180,0,35,16    ,'' ,'arrDatosDom',6,'div_dom','','','tituColCli']);
arrCamposDom.push(['entre1' ,'text'     ,0,'Entre'      ,210,0,100,16   ,'' ,'arrDatosDom',7,'div_dom','','','tituColCli']);
arrCamposDom.push(['entre2' ,'text'     ,0,'y Entre'    ,240,0,100,16   ,'' ,'arrDatosDom',8,'div_dom','','','tituColCli']);
arrCamposDom.push(['cp'     ,'text'     ,0,'CodPost'    ,270,0,70,16    ,'' ,'arrDatosDom',9,'div_dom','','','tituColCli']);

arrCamposTel=Array();
arrCamposTel.push(['id_telefono' ,'hidden'   ,0,''          ,0,0,70,20  ,0  ,'arrDatosTel',0,'div_tel','',''        ,'tituColCli']);
arrCamposTel.push(['tipotel'     ,'select'   ,0,'Tipo'      ,0,0,116,16  ,0  ,'arrDatosTel',1,'div_tel','','arrTel'  ,'tituColCli']);
arrCamposTel.push(['codpais'     ,'text'     ,0,'Pais'      ,0,0,30,16  ,'054','arrDatosTel',2,'div_tel','',''        ,'tituColCli']);
arrCamposTel.push(['codarea'     ,'text'     ,0,'Area'      ,0,0,30,16  ,'11' ,'arrDatosTel',3,'div_tel','',''        ,'tituColCli']);
arrCamposTel.push(['numero'      ,'text'     ,0,'Telefono'  ,0,0,100,16  ,'' ,'arrDatosTel',4,'div_tel','',''        ,'tituColCli']);
arrCamposTel.push(['interno'     ,'text'     ,0,'Int'       ,0,0,36,16  ,'' ,'arrDatosTel',5,'div_tel','',''        ,'tituColCli']);


arrCamposMed=Array();
arrCamposMed.push(['id_medio'   ,'hidden'   ,0,''          ,0,0,70,20  ,0   ,'arrDatosMed',0,'div_mail','',''        ,'tituColCli']);
arrCamposMed.push(['id_tipomed' ,'select'   ,0,'Tipo'      ,0,0,80,16  ,0   ,'arrDatosMed',1,'div_mail','','arrMed'  ,'tituColCli']);
arrCamposMed.push(['contacto'   ,'text'     ,0,'Contacto'  ,0,0,245,16  ,''  ,'arrDatosMed',2,'div_mail','',''        ,'tituColCli']);

arrCamposPar=Array();
arrCamposPar.push(['id_fam'     ,'hidden'       ,0,''           ,0,0,70,20  ,0   ,'arrDatosPar',0,'div_fam','',''        ,'tituColCli']);
arrCamposPar.push(['id_parent'  ,'select'       ,0,'Parentezco' ,0,0,90,16  ,0   ,'arrDatosPar',1,'div_fam','','arrPar'  ,'tituColCli']);
arrCamposPar.push(['nombre'     ,'text'         ,0,'Nombre'     ,0,0,250,16  ,''  ,'arrDatosPar',2,'div_fam','',''        ,'tituColCli']);
arrCamposPar.push(['apellido'   ,'text'         ,0,'Apellido'   ,0,0,250,16  ,''  ,'arrDatosPar',3,'div_fam','',''        ,'tituColCli']);
arrCamposPar.push(['fecha_nac'  ,'datepicker'   ,0,'Nacimiento' ,0,0,90,16  ,''  ,'arrDatosPar',4,'div_fam','',''        ,'tituColCli']);

arrCamposRel=Array();
arrCamposRel.push(['id_pc'          ,'select'   ,0,'Usuario'      ,0,0,300,16  ,0   ,'arrDatosRel',0,'div_ejec','','arrRUsr'        ,'tituColCli']);
arrCamposRel.push(['id_relacion'    ,'select'   ,0,'Relacion'     ,0,0,200,16  ,0   ,'arrDatosRel',1,'div_ejec','','arrRel'  ,'tituColCli']);

var prefImgDel='del_';
var prefBrT='brt_';
var prefBr='br_';
            
            
var valId=0;

cantP=0;
cantT=0;
cantM=0;
cantR=0;
cantD=0;

function cargaActuales(){
    for(var x=0;x < arrDatosTel.length; x++){
        addField('t',arrDatosTel[x]);
    }
    for(x=0;x < arrDatosMed.length; x++){
        addField('m',arrDatosMed[x]);
    }
    for(x=0;x < arrDatosPar.length; x++){
        addField('p',arrDatosPar[x]);
    }
    for(x=0;x < arrDatosRel.length; x++){
        addField('r',arrDatosRel[x]);
    }
    for(x=0;x < arrDatosDom.length; x++){
        addField('d',arrDatosDom[x]);
    }
}
            
function addField(tipo,arrayDatos){
    switch(tipo){
        case 't':
            if(cantT==0){
                addLabels2(arrCamposTel);
            }
            cantT++;
            addField2(arrCamposTel,valId,arrayDatos);
            contenedor=document.getElementById(arrCamposTel[0][11]);
            break;
        case 'm':
            if(cantM==0){
                addLabels2(arrCamposMed);
            }
            cantM++;
            addField2(arrCamposMed,valId,arrayDatos);
            contenedor=document.getElementById(arrCamposMed[0][11]);

            break;
        case 'p':
            if(cantP==0){
                addLabels2(arrCamposPar);
            }
            cantP++;
            addField2(arrCamposPar,valId,arrayDatos);
            contenedor=document.getElementById(arrCamposPar[0][11]);

            break;
        case 'r':
            if(cantR==0){
                addLabels2(arrCamposRel);
            }
            cantR++;
            addField2(arrCamposRel,valId,arrayDatos);
            contenedor=document.getElementById(arrCamposRel[0][11]);

            break;
        case 'd':
            if(cantD==0){
                addLabels2(arrCamposDom);
            }
            cantD++;
            addField2(arrCamposDom,valId,arrayDatos);
            contenedor=document.getElementById(arrCamposDom[0][11]);
            break;
    }
    addBotonDel(contenedor,tipo);
    addSalto(contenedor);
    valId++;
}
            
function addSalto(contenedor){
    salto=document.createElement('br');
    salto.setAttribute("id",prefBr+valId);
    contenedor.appendChild(salto);
}

function addLabels(contenedor,arrLabs){
    for(var x=0;x<arrLabs.length;x++){
        lab1=document.createElement('input');
        lab1.setAttribute("type", "text");
        lab1.setAttribute("id", arrLabs[x][0]);
        lab1.setAttribute("class",arrLabs[x][1]);
        lab1.setAttribute("readonly","readonly");
        lab1.setAttribute("value",arrLabs[x][2]);
        contenedor.appendChild(lab1);
    }
    salto=document.createElement('br');
    salto.setAttribute("id",prefBrT+valId);
    contenedor.appendChild(salto);
}   

function addBotonDel(contenedor,tipo){
    botNuevo=document.createElement('img');
    botNuevo.setAttribute("name",prefImgDel+valId);
    botNuevo.setAttribute("id", prefImgDel+valId);
    botNuevo.setAttribute("src",'images/delete.png');
    contenedor.appendChild(botNuevo);
    boton=document.getElementById(prefImgDel+valId);
    boton.addEventListener('click', function(e) {
        delField(e,tipo)
    }, false);
    
}   


            
function delField(campo,tipo){
    nomCampo=campo.currentTarget.id;
    auxId=nomCampo.substr(nomCampo.indexOf("_")+1);

    deleteBr(auxId);
    deleteBotonDel(auxId);

    switch(tipo){
        case 'm':
            deleteField(arrCamposMed,auxId);
            cantM--;
            if(cantM==0){
                deleteTitulos(arrCamposMed);
            }
            break;
        case 't':
            deleteField(arrCamposTel,auxId);
            cantT--;
            if(cantT==0){
                deleteTitulos(arrCamposTel);
            }
            break;
        case 'p':
            deleteField(arrCamposPar,auxId);
            cantP--;
            if(cantP==0){
                deleteTitulos(arrCamposPar);
            }
            break;
        case 'r':
            deleteField(arrCamposRel,auxId);
            cantR--;
            if(cantR==0){
                deleteTitulos(arrCamposRel);
            }
            break;
        case 'd':
            deleteField(arrCamposDom,auxId);
            cantD--;
            if(cantD==0){
                deleteTitulos(arrCamposDom);
            }
            break;
    }
    
}

function deleteTitulos(arrayDef){
    div=arrayDef[0][11];
    for(x=0, len=arrayDef.length; x < len; x++){
        if(arrayDef[x][3]!=''){
            auxCamp=document.getElementById("l_"+arrayDef[x][0]);
            document.getElementById("l_"+arrayDef[x][0]).parentNode.removeChild(auxCamp);
        }
    }
    auxCamp=document.getElementById(prefBrT+div);
    document.getElementById(prefBrT+div).parentNode.removeChild(auxCamp);
}

function deleteBr(delId){
    auxCamp=document.getElementById(prefBr+delId);
    document.getElementById(prefBr+delId).parentNode.removeChild(auxCamp);
}

function deleteBotonDel(delId){
    auxCamp=document.getElementById(prefImgDel+delId);
    document.getElementById(prefImgDel+delId).parentNode.removeChild(auxCamp);
}

function deleteField(arrayDef,delId){
    for(x=0, len=arrayDef.length; x < len; x++){
        auxCamp=document.getElementById(arrayDef[x][0]+"_"+delId);
        document.getElementById(arrayDef[x][0]+"_"+delId).parentNode.removeChild(auxCamp);
    }
}

function asignarDatepicker(elem){
    nombre="#"+elem;
    jQuery(nombre).datepicker({
        changeYear: true,
        numberOfMonths: 1,
        changeMonth: true ,
        yearRange: '-90 :+100'
    });
}


function asignarAutocompletar(elem,defAutocomp){
    if(typeof defAutocomp == 'string'){
        arrayOpciones=eval(defAutocomp);
    }else{
        arrayOpciones=defAutocomp;
    }

    nombre="#"+elem;
    jQuery(nombre).autocomplete(autoubi_opt);
}

function addField2(arrayEspec,valId,arrayDatos){
    if(typeof(arrayDatos)!='object' || !(arrayDatos instanceof Array)){
        arrayDatos=Array();
    }
    for(var compo=0,lenEsp=arrayEspec.length ; compo < lenEsp ;compo++){
        switch(arrayEspec[compo][1]){
            case 'hidden':
                armaCampoHidden(arrayEspec[compo],valId,arrayDatos);
                break;
            case 'select':
                armaCampoSelect(arrayEspec[compo],valId,arrayDatos);
                break;
            case 'text':
                armaCampoText(arrayEspec[compo],valId,arrayDatos);
                break;
            case 'autocomp':
                armaCampoAutocomp(arrayEspec[compo],valId,arrayDatos);
                break
            case 'datepicker':
                armaCampoDPicker(arrayEspec[compo],valId,arrayDatos);
                break
        }
    }
}

function armaCampoDPicker(defCampo,valId,arrayDatos){
    contenedor=document.getElementById(defCampo[11]);
    if(arrayDatos.length==0){
        valor=defCampo[8];
    }else{
        valor=arrayDatos[defCampo[10]];
    }
    campoNuevo=document.createElement('input');
    campoNuevo.setAttribute("type",'text');
    campoNuevo.setAttribute("name",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("id",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("value",valor);
    if(defCampo[2]!=0){
        campoNuevo.setAttribute("maxLength",defCampo[2]);
    }
    if(defCampo[2]!=''){
        campoNuevo.setAttribute("class",defCampo[12]);
    }
    contenedor.appendChild(campoNuevo);
    asignarDatepicker(defCampo[0]+'_'+valId);
    setPosicion(defCampo,'C');
}


function armaCampoAutocomp(defCampo,valId,arrayDatos){
    contenedor=document.getElementById(defCampo[11]);
    if(arrayDatos.length==0){
        valor=defCampo[8];
    }else{
        valor=arrayDatos[defCampo[10]];
    }
    campoNuevo=document.createElement('input');
    campoNuevo.setAttribute("type",'text');
    campoNuevo.setAttribute("name",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("id",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("value",valor);
    if(defCampo[2]!=0){
        campoNuevo.setAttribute("maxLength",defCampo[2]);
    }
    if(defCampo[2]!=''){
        campoNuevo.setAttribute("class",defCampo[12]);
    }
    contenedor.appendChild(campoNuevo);
    asignarAutocompletar(defCampo[0]+'_'+valId,defCampo[13]);
    setPosicion(defCampo,'C');
    
}


function armaCampoSelect(defCampo,valId,arrayDatos){
    contenedor=document.getElementById(defCampo[11]);
    if(arrayDatos.length==0){
        valor=defCampo[8];
    }else{
        valor=arrayDatos[defCampo[10]];
    }
    selNuevo=document.createElement('select');
    selNuevo.setAttribute("name",defCampo[0]+'_'+valId);
    selNuevo.setAttribute("id",defCampo[0]+'_'+valId);
    if(defCampo[12]!=''){
        selNuevo.setAttribute("class",defCampo[12]);
    }
    contenedor.appendChild(selNuevo);
    
    selector=document.getElementById(defCampo[0]+'_'+valId);
    if(typeof defCampo[13] == 'string'){
        arrayOpciones=eval(defCampo[13]);
    }else{
        arrayOpciones=defCampo[13];
    }
    for(var i=0, cantOp=arrayOpciones.length ; i < cantOp ; i++){
        elem=arrayOpciones[i];
        optNueva=document.createElement('option');
        optNueva.setAttribute("value", elem[0]);
        if(elem[0]==valor){
            optNueva.setAttribute("selected", "selected");
        }
        optNueva.innerHTML=elem[1];
        selector.appendChild(optNueva);
    }
    setPosicion(defCampo,'C');
}

function armaCampoText(defCampo,valId,arrayDatos){
    contenedor=document.getElementById(defCampo[11]);
    if(arrayDatos.length==0){
        valor=defCampo[8];
    }else{
        valor=arrayDatos[defCampo[10]];
    }
    campoNuevo=document.createElement('input');
    campoNuevo.setAttribute("type",'text');
    campoNuevo.setAttribute("name",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("id",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("value",valor);
    if(defCampo[2]!=0){
        campoNuevo.setAttribute("maxLength",defCampo[2]);
    }
    if(defCampo[12]!=''){
        campoNuevo.setAttribute("class",defCampo[12]);
    }
    contenedor.appendChild(campoNuevo);
    setPosicion(defCampo,'C');
}

function armaCampoHidden(defCampo,valId,arrayDatos){
    contenedor=document.getElementById(defCampo[11]);
    if(arrayDatos.length==0){
        valor=defCampo[8];
    }else{
        valor=arrayDatos[defCampo[10]];
    }
    campoNuevo=document.createElement('input');
    campoNuevo.setAttribute("type",'hidden');
    campoNuevo.setAttribute("name",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("id",defCampo[0]+'_'+valId);
    campoNuevo.setAttribute("value",valor);
    contenedor.appendChild(campoNuevo);
}

// Define valores de posicion y tamaño de campos
function setPosicion(defCampo,tipo){
    if(tipo=='L'){
        campo="l_"+defCampo[0];
    }else{
        campo=defCampo[0]+'_'+valId;
    }
    if(defCampo[4]!=0){
        document.getElementById(campo).style.left=defCampo[4]+'px';
    }
    if(defCampo[5]!=0){
        document.getElementById(campo).style.top=defCampo[5]+'px';
    }

    if(defCampo[6]!=0){
        document.getElementById(campo).style.width=defCampo[6]+'px';
    }
    if(defCampo[7]!=0){
        document.getElementById(campo).style.height=defCampo[7]+'px';
    }
}

// Agrega las etiquetas de los campos 
function addLabels2(arrayCampos){
    contenedor=document.getElementById(arrayCampos[0][11]);
    div=arrayCampos[0][11];
    for(var x=0, len=arrayCampos.length ; x<len ;x++){
        if(arrayCampos[x][1]!='hidden'){
//            lab1=document.createElement('input');
//            lab1.setAttribute("type", "text");
//            lab1.setAttribute("id", "l_"+arrayCampos[x][0]);
//            lab1.setAttribute("class",arrayCampos[x][14]);
//            lab1.setAttribute("readonly","readonly");
//            lab1.setAttribute("value",arrayCampos[x][3]);
//            contenedor.appendChild(lab1);

            lab1=document.createElement('div');
            lab1.setAttribute("id", "l_"+arrayCampos[x][0]);
            lab1.setAttribute("class",arrayCampos[x][14]);
            lab1.innerHTML = arrayCampos[x][3];
            contenedor.appendChild(lab1);

            setPosicion(arrayCampos[x],'L');
        }
    }
    limpiaClear = document.createElement('div');
    limpiaClear.setAttribute("id", "clearfix");
    //salto=document.createElement('br');
    //salto.setAttribute("id",prefBrT+div);
//    contenedor.appendChild(salto);
    contenedor.appendChild(limpiaClear);
    valId++;
}