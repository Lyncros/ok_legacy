function marcar(div){

//	alert(div+" * "+document.getElementById('path').value);

	divant=document.getElementById('path').value;

	if(div!=divant && divant!=''){

//		alert(divant);

		document.getElementById(divant).className='tdlista';

//		alert(document.getElementById('path').value);

	}

	document.getElementById('path').value=div;

	document.getElementById(div).className='tdlistaselected';

}



function cambiarDir(div){

	marcar(div);

	path=div.substr(3,div.length);

	document.getElementById('path').value=path;

	document.getElementById('accion').value='cd';

	document.forms.filemanager.submit();

}



function crearDir(accion){

	document.getElementById('divpath').style.display='block';	

	document.getElementById('boton').style.display='block';	

	document.getElementById('newpath').focus;

	document.getElementById('accion').value=accion;

}



function copiarArchivo(accion){

	path=document.getElementById('path').value;

	archivo=path.substr(3,path.length);

	document.getElementById('accion').value=accion;	

	window.open('selectorDestino.php?arc='+archivo+"&path="+document.getElementById('curpath').value);

}



function moverArchivo(accion){

	path=document.getElementById('path').value;

	archivo=path.substr(3,path.length);

	document.getElementById('accion').value=accion;	

	window.open('selectorDestino.php?arc='+archivo+"&path="+document.getElementById('curpath').value);

}





function actuar(accion){

	document.getElementById('accion').value=accion;

	document.forms.filemanager.submit();

}



function irPapelera(accion){

	document.getElementById('accion').value=accion;

	document.getElementById('vista').value=2;

	document.forms.filemanager.submit();

}



function irAdministracion(accion){

	document.getElementById('accion').value=accion;

	document.getElementById('vista').value=0;

	document.forms.filemanager.submit();

}





function actualizaOrigen() {

//   	opener.document.getElementById("path").value=document.getElementById("path").value;

	opener.document.getElementById("newpath").value=document.getElementById("curpath").value+'/'+document.getElementById("newpath").value;

	opener.document.getElementById('divpath').style.display='block';	

	opener.document.getElementById('boton').style.display='block';	

//	opener.document.getElementById('accion').value='cp';

	window.close();

}



function verArchivo(accion,parte){

	path=document.getElementById('path').value;

	archivo=path.substr(3,path.length);

	dirtotal=document.getElementById('curpath').value;

	dir=dirtotal.substr(parte,dirtotal.length);

	window.open(dir+"/"+archivo);

}



function verArchivoDC(div,parte){

	marcar(div);

	archivo=div.substr(3,div.length);

	dirtotal=document.getElementById('curpath').value;

	dir=dirtotal.substr(parte,dirtotal.length);

	window.open(dir+"/"+archivo);

}



function uploadFile(accion){

	document.getElementById('divarc').style.display='block';	

	document.getElementById('boton').style.display='block';	

	document.getElementById('archivo').focus;

	document.getElementById('accion').value=accion;

	

}



function downloadFile(accion){

	path=document.getElementById('path').value;

	archivo=path.substr(3,path.length);

	document.getElementById('accion').value=accion;	

	window.open('descargar.php?arc='+archivo+"&path="+document.getElementById('curpath').value);

}

