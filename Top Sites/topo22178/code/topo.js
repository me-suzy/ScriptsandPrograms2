// JavaScript
//
//  code/topo.js
//	rev002
//
function getObject(obj) {
	var theObj;
	if(document.all) {
		if(typeof obj=="string") {
			return document.all(obj);
		} else {
			return obj.style;
		}
	}
	if(document.getElementById) {
		if(typeof obj=="string") {
			return document.getElementById(obj);
		} else {
			return obj.style;
		}
	}
	return null;
}

//Reloj
function Reloj(iSeg,oObj,sTexto,iRecargar) {
	this.iSeg=iSeg;
	this.oObj=oObj;
	this.sTexto=sTexto;
	this.iRecargar=iRecargar;
}
Reloj.prototype.Atras = function() {
	segundos=this.iSeg--;
	objeto=getObject(this.oObj);
	horas=Math.floor(segundos/3600);
	segundos-=3600*horas;
	min=Math.floor(segundos/60);
	segundos-=60*min;
	seg=segundos;
	horas=(horas<10)? "0"+horas : horas;
	min=(min<10)? "0"+min : min;
	seg=(seg<10)? "0"+seg : seg;
	horas=(horas=="00")? "" : horas+":";
	min=(min=="00" && horas=="")? "" : min+":";
	seg=(seg=="00" && min=="")? "Booommm!!" : seg+"''";
	if(seg=="Booommm!!" && this.iRecargar==1) window.location.reload();
	if(this.iSeg < 0) {
		objeto.innerHTML=this.sTexto.replace('{CLOCK}',"Booommm!!");
	} else {
		objeto.innerHTML=this.sTexto.replace('{CLOCK}',horas+min+seg);
	}
}
Reloj.prototype.AtrasBoton = function(destinoURL) {
	segundos=this.iSeg--;
	objeto=getObject(this.oObj);
	horas=Math.floor(segundos/3600);
	segundos-=3600*horas;
	min=Math.floor(segundos/60);
	segundos-=60*min;
	seg=segundos;
	horas=(horas<10)? "0"+horas : horas;
	min=(min<10)? "0"+min : min;
	seg=(seg<10)? "0"+seg : seg;
	horas=(horas=="00")? "" : horas+":";
	min=(min=="00" && horas=="")? "" : min+":";
	seg=(seg=="00" && min=="")? "Booommm!!" : seg+"''";
	if(seg=="Booommm!!" && this.iRecargar==1) window.location.href=destinoURL;
	if(this.iSeg < 0) {
		objeto.value=this.sTexto.replace('{CLOCK}',"0''");
	} else {
		objeto.value=this.sTexto.replace('{CLOCK}',horas+min+seg);
	}
}

//Contador de caracteres.
function Contar(entrada,salida,texto,caracteres) {
	var entradaObj=getObject(entrada);
	var salidaObj=getObject(salida);
	var longitud=caracteres - entradaObj.value.length;
	if(longitud <= 0) {
		longitud=0;
		texto='<span class="disable">&nbsp;'+texto+'&nbsp;</span>';
		entradaObj.value=entradaObj.value.substr(0,caracteres);
	}
	salidaObj.innerHTML = texto.replace("{CHAR}",longitud);
}

//Precarga de banners
function PrecargarBanner(url,imgID,infID,maxSeg,maxAncho,maxAlto) {
	this.url=url;
	this.imgObj=getObject(imgID);
	this.infObj=getObject(infID);
	this.cadenaTimeOut=imgID.replace('n','nner');
	this.maxSeg=maxSeg;
	this.maxAncho=maxAncho;
	this.maxAlto=maxAlto;
	this.imagen=new Image();
	this.cont;

	this.Cargar();
}
PrecargarBanner.prototype.Cargar=function() {
	this.cont=0;
	this.imagen.src=this.url;
	this.Mostrar();

}
PrecargarBanner.prototype.Mostrar=function() {
	if(this.imagen.complete) {
		this.imgObj.src=this.imagen.src;
		this.Ajustar();
		this.Info();
	} else {
		this.cont = 1 + this.cont;
		//this.infObj.innerHTML=this.imagen.nameProp+' => '+(this.maxSeg-this.cont)+' ';
		this.infObj.innerHTML='';
		if(this.cont <= this.maxSeg) {
			setTimeout(this.cadenaTimeOut+'.Mostrar()',1000);
		} else {
			this.imgObj.src='images/no_banner.gif';
			this.infObj.innerHTML='';
		}
	}
}
PrecargarBanner.prototype.Info=function() {
	this.infObj.innerHTML='';
	this.imgObj.title=this.imagen.nameProp+' :: '+this.imagen.width+' x '+this.imagen.height+' :: '+Math.floor(this.imagen.fileSize/1024)+' KB :: '+this.cont+ ' sec.';
}
PrecargarBanner.prototype.Ajustar=function() {
	//Fijamos las propiedades HEIGHT y WIDTH de imgObj.
	if(this.imagen.width>this.maxAncho || this.imagen.height>this.maxAlto) {
		if( (this.imagen.width/this.maxAncho) >= (this.imagen.height/this.maxAlto) ) {
			this.imgObj.width=this.maxAncho;
		} else {
			this.imgObj.height=this.maxAlto;
		}
	}
}
//Precarga de banners Flash
function PrecargarFlash(url,imgID,infID,maxSeg,maxAncho,maxAlto) {
	this.url=url;
	this.imgObj=getObject(imgID);
	this.infObj=getObject(infID);
	this.cadenaTimeOut=imgID.replace('n','nner');
	this.maxSeg=maxSeg;
	this.maxAncho=maxAncho;
	this.maxAlto=maxAlto;
	this.cont;

	this.Cargar();
}
PrecargarFlash.prototype.Cargar=function() {
	this.cont=0;
	this.imgObj.hidden=true;
	this.imgObj.src=this.url;
	this.Mostrar();
}
PrecargarFlash.prototype.Mostrar=function() {
	if(this.imgObj.readyState=='loading' || this.imgObj.readyState=='loaded' || this.imgObj.readyState=='complete') {
		this.imgObj.hidden=false;
		this.Ajustar();
	} else {
		this.cont = 1 + this.cont;
		//this.infObj.innerHTML=this.imagen.nameProp+' => '+(this.maxSeg-this.cont)+' ';
		this.infObj.innerHTML='';
		if(this.cont <= this.maxSeg) {
			setTimeout(this.cadenaTimeOut+'.Mostrar()',1000);
		} else {
			this.imgObj.hidden=false;
			this.imgObj.src='';
			this.infObj.innerHTML='';
		}
	}
}
PrecargarFlash.prototype.Ajustar=function() {
	//Fijamos las propiedades HEIGHT y WIDTH de imgObj.
	if(this.imgObj.width>this.maxAncho || this.imgObj.height>this.maxAlto) {
		if( (this.imgObj.width/this.maxAncho) >= (this.imgObj.height/this.maxAlto) ) {
			this.imgObj.width=this.maxAncho;
		} else {
			this.imgObj.height=this.maxAlto;
		}
	}
}

//Abre nueva ventana centrada en el centro de la pantalla
function ventana(theURL,winName,winWidth,winHeight) {
    var w = (screen.width - winWidth)/2;
    var h = (screen.height - winHeight)/2 - 50;
	if((winHeight%2)==1) {
		features = 'directories=no,location=no,menubar=no,scrollbars=yes,status=yes,toolbar=no,resizable=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
	} else {
		features = 'directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=yes,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;	
	}
    window.open(theURL,winName,features);
}

function submitOnce(theform) {
	if (document.all || document.getElementById) {
		for(i=0 ; i < theform.length ; i++) {
			var tempobj = theform.elements[i];
			if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset") {
				tempobj.disabled=true;
			}
		}
	}
}

function validate(obj,modo,porDefecto, min) {
	var texto = obj.value;
	do {
		texto = texto.replace(/\|\|/,"|");
	} while(/\|\|/.test(texto));
	if(modo=="url") {
		if(texto.substr(0,7)!="http://" && texto.substr(0,8)!="https://") texto = "http://" + texto;
		texto = texto.replace(/ /,"");
	}
	if(modo=="email") {
		if(!/(.+)@(.+)\.(.+)/.test(texto)) texto = porDefecto;
		if(/[\s]+/.test(texto)) texto = porDefecto;
		if(texto.indexOf('@') != texto.lastIndexOf('@')) texto = porDefecto;
		var dominio = texto.substring(1+texto.lastIndexOf('.'));
		if( dominio.length>3 || dominio.length<2) texto = porDefecto;
	}
	if(modo=="icq") {
		do {
			texto = texto.replace(/[^0-9]/,"");
		} while(/[^0-9]/.test(texto));
	}
	if(modo=="number") {
		do {
			texto = texto.replace(/[^0-9]/,"");
		} while(/[^0-9]/.test(texto));
		if(texto=='') texto = porDefecto;
	}
	if(modo=="numberMin") {
		do {
			texto = texto.replace(/[^0-9]/,"");
		} while(/[^0-9]/.test(texto));
		if(texto=='') texto = porDefecto;
		if(parseInt(texto) < min) texto = porDefecto;
	}
	if(modo=="text") {
		if(texto=='') texto = porDefecto;
	}
	obj.value = texto;	
}
