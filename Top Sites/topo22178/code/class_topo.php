<?php
//
//  class_topo.php
//	rev016
//  PHP v4.2+
//

/**
 * @author Emilio José Jiménez <ej3@myrealbox.com>
 **/
 
class Info {
	var $paginas=0;
	var $webs=0;
	var $ultActualizacion=0;
	var $ultReset=0;	
	var $parcialIN=0;
	var $totalIN=0;
	var $parcialOUT=0;
	var $totalOUT=0;
	var $podium=array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0);
	var $file;

	function Info($prefijo='',$ruta='') {
		$this->file=$ruta.'cache/'.$prefijo.'info.dat';
		$this->ultActualizacion=time();	//La primera vez que se genera el top muestra esta fecha.
		$this->ultReset=time();			//La primera vez que se genera el top muestra esta fecha.
		if(file_exists($this->file)) {
			$linea=file($this->file);
			$aux=explode('||',$linea[0]);
			$this->paginas=$aux[0];
			$this->webs=$aux[1];
			$this->ultActualizacion=$aux[2];
			$this->ultReset=$aux[3];
			$this->parcialIN=$aux[4];
			$this->parcialOUT=$aux[5];
			$this->totalIN=$aux[6];
			$this->totalOUT=$aux[7];
			for($i=1;$i<=10;$i++) if(strlen($aux[$i+7])) $this->podium[$i]=$aux[$i+7];
		} 
	}
	
	function Guardar() {
		$data=$this->paginas.'||'.$this->webs.'||'.$this->ultActualizacion.'||'.$this->ultReset.'||';
		$data.=$this->parcialIN.'||'.$this->parcialOUT.'||'.$this->totalIN.'||'.$this->totalOUT.'||';
		foreach($this->podium as $value) $data.=$value.'||';
		if($fp=@fopen($this->file,'w')) {
			flock($fp,2);
			fwrite($fp,$data);
			fclose($fp);
			@chmod($this->file,0666);
		} else {
			$error=new Error;
			die($error->Archivo($this->file));		
		}
	}
}

class Constructor {
	var $dir;				//Ruta hasta el directorio de trabajo.
	var $infoDAT_local;		//Clase para acceder a info.dat
	var $resetear=0;		//¿Hay que resetear?
	var $webs;				//Array de objetos(webs) indexadas por IDs
	var $numWebs;			//Número de webs procesadas.
	var $criterio;			//Determina el criterio de ordenación.
	var $ordenNUEVO;		//Array indexado por IDs con la posicion actual de la web
	var $ordenVIEJO;		//Array indexado por IDs con la posición anterior de la web
	
	var $tema;				//Nombre del tema que estamos utilizando.
	var $modelo;			//Array con los modelos del tema especificado.
	var $variables;			//Array con las variables propias del tema
							// ANCHO||ALTO||COLOR_BORDE(=0..2 ó vacio=transparente)||ANCHO RATIO||
	var $NAVEGADOR='';		//Selector de categorias.
	var $ESTADISTICAS='';	//Tabla de estadísticas.
	var $SITIODELMOMENTO='';//Tabla con el sitio del momento.
	var $PODIUM='';			//Tabla con el podium.
	var $PAGINAS;			//Array de páginas en HTML.
	
	/**
	 * Constructor::Constructor()
	 * 
	 * @param array $listado Array con las IDs de los sitios que se utilizaran
	 * @param integer $criterio (=1..5) Criterio de ordenacion (indice de webs[]->dat[$criterio])
	 * @param integer $caduca Si $this->infoDAT_local->ultReset < $caduca entonces hay que resetear.
	 * @param string $directorio Prefijo del archivo de /cache/ (NO terminado en /)
	 * @param string $ruta Ruta hasta /cache/ (terminada en /)
	 * @return void
	 **/
	function Constructor($listado,$criterio=1,$caduca=0,$prefijo='',$ruta='') {
		$this->dir=$ruta.'cache/'.$prefijo;	//Usamos prefijos.
		$this->criterio=$criterio;
		//Accedemos al info.dat desde esta clase.
		$this->infoDAT_local=new Info($prefijo,$ruta);
		//¿Hay que resetear?
		//Sólo comprobamos si hay que resetear cuando cargamos la página principal o la de todas las categorias.
		if($prefijo=='' OR $prefijo=='0000000000.0000') {
			if($this->infoDAT_local->ultReset < $caduca) $this->resetear=1;
		}
		//Cargamos las webs y generamos $ordenNUEVO.
		foreach($listado as $value) {
			$this->webs[$value]=new SitioWeb($value);
			if($criterio==5) {	//Orden 'NOTA MEDIA'.
				if($this->webs[$value]->dat[5]==0) {
					$clave=0.000;
				} else {
					$clave=number_format($this->webs[$value]->dat[6]/$this->webs[$value]->dat[5],3,'.','');
				}
			} else {
				$clave=$this->webs[$value]->dat[$criterio];
			}
			$orden[$value]=$clave;
		}
		//Ordenamos el $ordenNUEVO
		arsort($orden);
		foreach($orden as $key => $value) $this->ordenNUEVO[$key]=++$i;
		$this->numWebs=count($this->ordenNUEVO);
		//Cargamos el $ordenVIEJO (si procede)
		if(file_exists($this->dir.'orden.dat')) {
			$fp=fopen($this->dir.'orden.dat','r');
			$data=fread($fp,filesize($this->dir.'orden.dat'));
			fclose($fp);
			$this->ordenVIEJO=unserialize($data);		
		} else {
			$this->ordenVIEJO=$this->ordenNUEVO;
		}
	}
	
	/**
	 * Constructor::_Guardar()
	 * 
	 * @return void
	 **/
	function _Guardar() {
		//Volcamos los datos a pageX.php
		$this->infoDAT_local->ultActualizacion=time();
		foreach($this->PAGINAS as $key => $value) {
			if($fp=fopen($this->dir.'page'.$key.'.php','w')) {
				flock($fp,2);
				fwrite($fp,$value);
				fclose($fp);
				@chmod($this->dir.'page'.$key.'.php',0666);
			} else {
				$error=new Error;
				die($error->Archivo($this->dir.'page'.$key.'.php'));
			}
		}
		
		//Guardamos el vector con el orden.
		$data=serialize($this->ordenNUEVO);
		if($fp=fopen($this->dir.'orden.dat','w')) {
			flock($fp,2);
			fwrite($fp,$data);
			fclose($fp);
			@chmod($this->dir.'orden.dat',0666);
		} else {
			$error=new Error;
			die($error->Archivo($this->dir.'orden.dat'));
		}
		
		//Resetamos si procede
		if($this->resetear) {
			$this->infoDAT_local->ultReset=time();
			$orden=array_flip($this->ordenNUEVO);
			for($i=1;$i<=10;$i++) if(isset($orden[$i-1])) $this->infoDAT_local->podium[$i]=$orden[$i-1];
			foreach($this->webs as $value) {
				$resetearWeb=new SitioWebAvanzado($value->ID);
				$resetearWeb->Resetear();
			}
		}
		
		//Guardamos el archivo info.dat
		$this->infoDAT_local->Guardar();
	}
	
	/**
	 * Constructor::GenerarPaginas()
	 * Genera el código HTML de cada una de las páginas según los modelos del $tema
	 * 
	 * @param $tema
	 * @param $conBanner
	 * @param $numBloques
	 * @param $websPorBloque
	 * @return void
	 **/
	function GenerarPaginas($tema,$titulo,$conBanner,$numBloques,$websPorBloque,$precargarBanner) {
		$this->tema=$tema;
		//Cargamos los modelos.
		$fp=fopen('themes/'.$tema.'/templates.dat','r');
		$data=fread($fp,filesize('themes/'.$tema.'/templates.dat'));
		fclose($fp);
		$this->modelo=explode('||*|||*|||||*',$data);
		$this->variables=explode('||',$this->modelo[1]);
		
		//{HEADER}
		$anchoCentral=$this->variables[1];
		if($this->variables[1]>$anchoCentral) $anchoCentral=$this->variables[1]+10;
		$viejo[0]='{WIDTH}';		$nuevo[0]=$anchoCentral;
		$viejo[1]='{RANK}';			$nuevo[1]=$GLOBALS['_RANK_'];
		$viejo[2]='{WEB_SITES}';	$nuevo[2]=$GLOBALS['_WEBS_'];
		$viejo[3]='{IN}';			$nuevo[3]=$GLOBALS['_IN_'];
		$viejo[4]='{OUT}';			$nuevo[4]=$GLOBALS['_OUT_'];
		$HEADER=str_replace($viejo,$nuevo,$this->modelo[5]);

		//{FOOTER}
		$viejo[0]='{WIDTH}';		$nuevo[0]=$anchoCentral;
		$viejo[1]='{RANK}';			$nuevo[1]=$GLOBALS['_RANK_'];
		$viejo[2]='{WEB_SITES}';	$nuevo[2]=$GLOBALS['_WEBS_'];
		$viejo[3]='{IN}';			$nuevo[3]=$GLOBALS['_IN_'];
		$viejo[4]='{OUT}';			$nuevo[4]=$GLOBALS['_OUT_'];
		$FOOTER=str_replace($viejo,$nuevo,$this->modelo[7]);

		//Generamos páginas del top.
		$orden=array_flip($this->ordenNUEVO);		
		$pos=1; $pag=1; $resto_webs=0;
		while($pos<=$this->numWebs) {
			$TOPLIST[$pag]='<!-- PAGE: '.$pag.'  generate by TOPo -->'."\n";																//BUCLE DE PAGINAS
			for($bloque=1;$bloque<=$numBloques AND $pos<=$this->numWebs;$bloque++) {	//BUCLE DE BLOQUES
				if($bloque!=1) {	//Metemos la publicidad si procede.
					$TOPLIST[$pag].='<?php'."\n";
					$TOPLIST[$pag].='if($gVerBannerMed==0) echo "<br>";'."\n";
					$TOPLIST[$pag].='if($gVerBannerMed==1) include("data/bannermiddle.htm");'."\n";
					$TOPLIST[$pag].='if($gVerBannerMed==2) echo "<br>".$autoBanner['.($bloque-1).']."<br>";'."\n";
					$TOPLIST[$pag].='?>'."\n";
				}
				//{TOPLIST}
				$TOPLIST[$pag].='<table '.$this->variables[3].'>'."\n";
				//¿Quedan páginas por procesar?
				if($this->webs[$orden[$pos]]->dat[$this->criterio] >= $GLOBALS['gMinimoHits']) $TOPLIST[$pag].='<tr class="0">'.$HEADER.'</tr>'."\n";
				for($sitio=1;$sitio<=$websPorBloque AND $pos<=$this->numWebs;$sitio++,$pos++) {	//BUCLE DE WEBS
					if($this->webs[$orden[$pos]]->dat[$this->criterio] < $GLOBALS['gMinimoHits']) {
						$resto_webs=$this->numWebs+2-$pos;		//Webs que nos han quedado por procesar.
						$pos=$this->numWebs+1;			//Salimos del bucle de webs porque ya no hay más válidas.
						continue;
					}
					$verBanner=0;
					if($pos<=$conBanner) $verBanner=1;
					$TOPLIST[$pag].='<tr class="'.(1+$pos%2).'"'."\n";
					if($GLOBALS['gIluminar']) $TOPLIST[$pag].=' onMouseMove="this.className=\'highlight\';" onMouseOut="this.className=\''.(1+$pos%2).'\';"'."\n";
					$TOPLIST[$pag].='>'."\n";
					$TOPLIST[$pag].=$this->_GenerarWeb($orden[$pos],$anchoCentral,$verBanner,($conBanner*$precargarBanner));
					$TOPLIST[$pag].='</tr>'."\n";
					//Actualizamos estadisticas de info.dat
					$parcialIN+=$this->webs[$orden[$pos]]->dat[1];
					$parcialOUT+=$this->webs[$orden[$pos]]->dat[2];
					$totalIN+=$this->webs[$orden[$pos]]->dat[3];
					$totalOUT+=$this->webs[$orden[$pos]]->dat[4];
				}
				$TOPLIST[$pag].='<tr>'.$FOOTER.'</tr>'."\n";
				$TOPLIST[$pag].='</table>'."\n";
			}
			$pag++;	//Pasamos página
		}
		//Actualizamos las páginas y el número de webs.
		$this->infoDAT_local->paginas=$pag-1;
		$this->infoDAT_local->webs=$pos-1-$resto_webs;
		//$this->infoDAT_local->webs=$pos-1;
		$this->infoDAT_local->parcialIN=$parcialIN;
		$this->infoDAT_local->parcialOUT=$parcialOUT;
		$this->infoDAT_local->totalIN=$totalIN;
		$this->infoDAT_local->totalOUT=$totalOUT;

		//Generamos el resto de bloques.
		if($GLOBALS['gEstadisticas']) $this->ESTADISTICAS=$this->_GenerarEstadisticas();
		$this->NAVEGADOR=$this->_GenerarNavegador();
		if($GLOBALS['gSitioDelMomento']) $this->SITIODELMOMENTO=$this->_GenerarSitioDelMomento($anchoCentral,$conBanner*$precargarBanner);
		if($GLOBALS['gPodium']) $this->PODIUM=$this->_GenerarPodium($GLOBALS['gPodium']);
		
		//Añadimos los bloques restantes aplicando el layout general.
		foreach($TOPLIST as $key => $value) {
			unset($viejo); 						unset($nuevo);
			$viejo[0]='{STATS}';				$nuevo[0]=$this->ESTADISTICAS;
			$viejo[1]='{BROWSER}';				$nuevo[1]=$this->NAVEGADOR;
			$viejo[2]='{SITE_OF_THE_MOMENT}';	$nuevo[2]=$this->SITIODELMOMENTO;
			$viejo[3]='{PODIUM_TEXT}';			$nuevo[3]=$GLOBALS['_Podium_'];
			$viejo[4]='{PODIUM}';				$nuevo[4]=$this->PODIUM;
			$viejo[5]='{TOPLIST}';				$nuevo[5]=$value;
			$viejo[6]='{TITLE}';				$nuevo[6]=$titulo;
			$viejo[7]='{JOIN}';					$nuevo[7]='<a href="index.php?m=members&s=html&t=join" target="_blank">'.$GLOBALS['_Join_'].'</a>';
			$viejo[8]='{EDIT}';					$nuevo[8]='<a href="index.php?m=members&s=html&t=edit" target="_blank">'.$GLOBALS['_EditSite_'].'</a>';
			$viejo[9]='{ADMIN}';				$nuevo[9]='<a href="index.php?m=admin&s=html" target="_blank">'.$GLOBALS['_Webmaster_'].'</a>';
			if($GLOBALS['gAviso']) {
				$fp=fopen('data/notice.htm','r');
				$aviso=fread($fp,filesize('data/notice.htm'));
				fclose($fp);
				$viejo[10]='{NOTICE}';		$nuevo[10]=$aviso;
			} else {
				$viejo[10]='{NOTICE}';		$nuevo[10]='';
			}
			$this->PAGINAS[$key]=str_replace($viejo,$nuevo,$this->modelo[2]);
		}
		
		//Volcamos el código al disco
		$this->_Guardar();
	}
	
	function _GenerarWeb($ID,$anchoBanner,$verBanner,$maxTiempo) {
		global $indice, $categorias;	//Estos objetos se generan en index.php
	
		$posAhora=$this->ordenNUEVO[$ID];
		$posAntes=$this->ordenVIEJO[$ID];
		
		unset($viejo);
		unset($nuevo);
		
		if($verBanner AND is_array($this->webs[$ID]->bannerURL)) {
			$num=count($this->webs[$ID]->bannerURL);
			$ind=0;
			if($GLOBALS['gMultiBanner'] AND $num>0) {
				srand((double) microtime() * 1000000);
				$ind=rand(0,$num-1);
			}
			if(substr($this->webs[$ID]->bannerURL[$ind],-4)=='.swf') {	//Banner tipo flash
				$viejo[0]='{BANNER}';
				$nuevo[0]='<span id="inf'.$posAhora.'" class="description"></span>';
				$nuevo[0].='<a href="index.php?m=top&s=out&ID='.$ID.'" target="_blank">';
				$nuevo[0].='<embed id="ban'.$posAhora.'" src="" quality="medium" width="'.$this->variables[1].'" height="'.$this->variables[2].'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></a>';
				$nuevo[0].='<SCRIPT>banner'.$posAhora.'=new PrecargarFlash(\''.$this->webs[$ID]->bannerURL[$ind].'\',\'ban'.$posAhora.'\',\'inf'.$posAhora.'\','.$maxTiempo.','.$this->variables[1].','.$this->variables[2].');</SCRIPT>';
			} elseif($maxTiempo) {	//Con precarga de banners
				$viejo[0]='{BANNER}';
				$nuevo[0]='<span id="inf'.$posAhora.'" class="description"></span>';
				$nuevo[0].='<a href="index.php?m=top&s=out&ID='.$ID.'" target="_blank">';
				$nuevo[0].='<img id="ban'.$posAhora.'" src="themes/'.$this->tema.'/loading.gif" border="0" onMouseMove="window.status=\''.str_replace('&#'.ord("'"),"\'",$this->webs[$ID]->web).'\';" onMouseOut="window.status=\' \';"></a>';
				$nuevo[0].='<SCRIPT>banner'.$posAhora.'=new PrecargarBanner(\''.$this->webs[$ID]->bannerURL[$ind].'\',\'ban'.$posAhora.'\',\'inf'.$posAhora.'\','.$maxTiempo.','.$this->variables[1].','.$this->variables[2].');</SCRIPT>';
			} else {	//Sin precarga
				$img_info=GetImageSize($this->webs[$ID]->bannerURL[$ind]);
				$tam=' ';
				if($img_info[0]>$this->variables[1] OR $img_info[1]>$this->variables[2]) {
					if($img_info[0]>=$img_info[1]) {
						$tam=' width="'.$this->variables[1].'" ';
					} else {
						$tam=' height="'.$this->variables[2].'" ';
					}
				}
				$viejo[0]='{BANNER}';
				$nuevo[0]='<img src="'.$this->webs[$ID]->bannerURL[$ind].'"'.$tam.'border="0">';
				$nuevo[0]='<a href="index.php?m=top&s=out&ID='.$ID.'" target="_blank">'.$nuevo[0].'</a>';
			}
		} else {
			$viejo[0]='{BANNER}';		$nuevo[0]='';
		}
		$viejo[1]='{WEB}';				$nuevo[1]='<a class="webs" href="index.php?m=top&s=out&ID='.$ID.'" target="_blank" onMouseMove="window.status=\''.str_replace('&#'.ord("'"),"\'",$this->webs[$ID]->web).'\';" onMouseOut="window.status=\' \';">'.$this->webs[$ID]->web.'</a>';
		$viejo[2]='{DESCRIPTION}';		$nuevo[2]='<span class="description">'.$this->webs[$ID]->descripcion.'</span>';
		$viejo[3]='{WIDTH}';			$nuevo[3]=$anchoBanner;
		$viejo[4]='{RANK}';				$nuevo[4]=$this->ordenNUEVO[$ID];
		$viejo[5]='{PARCIAL_IN}';		$nuevo[5]=$this->webs[$ID]->dat[1];
		$viejo[6]='{PARCIAL_OUT}';		$nuevo[6]=$this->webs[$ID]->dat[2];
		$viejo[7]='{TOTAL_IN}';			$nuevo[7]=$this->webs[$ID]->dat[3];
		$viejo[8]='{TOTAL_OUT}';		$nuevo[8]=$this->webs[$ID]->dat[4];
		$days=number_format(abs(time()-$ID)/86400,0,'','');
		if($days) {
			$viejo[9]='{AVERAGE_IN}';		$nuevo[9]=str_replace('{AVERAGE}',number_format($this->webs[$ID]->dat[3]/$days,1,'.',''),$GLOBALS['_PerDay_']);
			$viejo[10]='{AVERAGE_OUT}';		$nuevo[10]=str_replace('{AVERAGE}',number_format($this->webs[$ID]->dat[4]/$days,1,'.',''),$GLOBALS['_PerDay_']);
		} else {
			$viejo[9]='{AVERAGE_IN}';		$nuevo[9]=str_replace('{AVERAGE}',0,$GLOBALS['_PerDay_']);
			$viejo[10]='{AVERAGE_OUT}';		$nuevo[10]=str_replace('{AVERAGE}',0,$GLOBALS['_PerDay_']);
		}
		
		$viejo[11]='{IN}';				$nuevo[11]=$GLOBALS['_IN_'];
		$viejo[12]='{OUT}';				$nuevo[12]=$GLOBALS['_OUT_'];
		$viejo[13]='{RATIO_TEXT}';		$nuevo[13]=$GLOBALS['_Ratio_'];
		$viejo[14]='{RATIO_BAR}';
		if($this->webs[$ID]->dat[1] OR $this->webs[$ID]->dat[2]) {
			$nuevo[14]=barra('ratio',$this->variables[4],$this->webs[$ID]->dat[1],$this->webs[$ID]->dat[2]);
		} else {
			$nuevo[14]='';
		}
		$viejo[15]='{INFO_BUTTON}';		$nuevo[15]='<INPUT TYPE="BUTTON" class="minibutton" value="'.$GLOBALS['_Info_'].'" onClick="ventana(\'index.php?m=top&s=info&ID='.$ID.'\',\'_blank\','.(200*$GLOBALS['gComentarios']+361).',424)">';
		if($GLOBALS['gPuntuacion']) {
			$viejo[16]='{RATE_BUTTON}';		$nuevo[16]='<INPUT TYPE="BUTTON" class="minibutton" value="'.$GLOBALS['_RateIt_'].'" onClick="ventana(\'index.php?m=top&s=info&ID='.$ID.'&t=puntuar\',\'_blank\',300,170)">';
		} else {
			$viejo[16]='{RATE_BUTTON}';		$nuevo[16]='';
		}
		$viejo[17]='{PERSONAL_STATS}';
		$nuevo[17]='<?php'."\n";
		$nuevo[17].='if($gEstaPersonales) {'."\n";
		$nuevo[17].='    if(!isset($cookie)) $cookie=new Cookies;'."\n";
		$nuevo[17].='    if($valor=$cookie->webHTML(\''.str_replace('.','',$ID).'\')) {'."\n";
		$nuevo[17].='        echo "<img src=\"themes/'.$GLOBALS['gTema'].'/icon_stats.gif\" border=\"0\" align=\"absmiddle\" onMouseOver=\"return overlib(\'".$valor."\',FULLHTML,STATUS,\''.$GLOBALS['_PersonalStats_'].'\')\" onMouseOut=\"nd();\">";'."\n";
		$nuevo[17].='    } else {'."\n";
		$nuevo[17].='        echo "";'."\n";
		$nuevo[17].='    }'."\n";
		$nuevo[17].='}'."\n";
		$nuevo[17].='?>'."\n";

        $dif=$this->ordenVIEJO[$ID]-$this->ordenNUEVO[$ID];
		$dif_flecha='<img src="themes/'.$GLOBALS['gTema'].'/arrow_equal.gif" border="0">';
		$dif_texto='';
        if($dif>0) {
			$dif_flecha='<img src="themes/'.$GLOBALS['gTema'].'/arrow_up.gif" border="0" align="absmiddle">';
			$dif_texto='<span class="good">+'.$dif.'</span>';
		}
        if($dif<0) {
			$dif_flecha='<img src="themes/'.$GLOBALS['gTema'].'/arrow_down.gif" border="0" align="absmiddle">';
			$dif_texto='<span class="bad">'.$dif."</span>";
		}
		$viejo[18]='{RANK_DIF_IMAGE}';		$nuevo[18]=$dif_flecha;
		$viejo[19]='{RANK_DIF_NUMBER}';		$nuevo[19]=$dif_texto;
		$viejo[20]='{DAYS_IN_TOP}';			$nuevo[20]=str_replace('{DAYS}',$days,$GLOBALS['_DaysInTop_']);
		if($GLOBALS['gComentarios']) {
			$viejo[21]='{COMMENTS_TEXT}';		$nuevo[21]=$GLOBALS['_COMMENTS_'];
			$viejo[22]='{COMMENTS_NUMBER}';		$nuevo[22]=$this->webs[$ID]->dat[7];
		} else {
			$viejo[21]='{COMMENTS_TEXT}';		$nuevo[21]='';
			$viejo[22]='{COMMENTS_NUMBER}';		$nuevo[22]='';
		}
		if($GLOBALS['gPuntuacion']) {
			$viejo[23]='{AVERAGE_TEXT}';		$nuevo[23]=$GLOBALS['_AVERAGERATE_'];
			$viejo[24]='{AVERAGE_NUMBER}';		$nuevo[24]=0;
			$viejo[25]='{AVERAGE_GRAPH}';		$nuevo[25]='<IMG src="themes/'.$GLOBALS['gTema'].'/rate0.gif" border="0">';
			if($this->webs[$ID]->dat[6]) {
				$nuevo[24]=number_format($this->webs[$ID]->dat[6]/$this->webs[$ID]->dat[5],1,'.','');
				$viejo[25]='{AVERAGE_GRAPH}';		$nuevo[25]='<IMG src="themes/'.$GLOBALS['gTema'].'/rate'.number_format($this->webs[$ID]->dat[6]/$this->webs[$ID]->dat[5],0,'.','').'.gif" border="0" align="absmiddle">';
			}
			$viejo[26]='{RATES_TEXT}';			$nuevo[26]=$GLOBALS['_Rates_'];
			$viejo[27]='{RATES_NUMBER}';		$nuevo[27]=$this->webs[$ID]->dat[5];
		} else {
			$viejo[23]='{AVERAGE_TEXT}';		$nuevo[23]='';
			$viejo[24]='{AVERAGE_NUMBER}';		$nuevo[24]='';
			$viejo[25]='{AVERAGE_GRAPH}';		$nuevo[25]='';
			$viejo[26]='{RATES_TEXT}';			$nuevo[26]='';
			$viejo[27]='{RATES_NUMBER}';		$nuevo[27]='';
		}
		if($GLOBALS['gCategorias']) {
			$viejo[28]='{CATEGORY_TEXT}';		$nuevo[28]=$GLOBALS['_CATEGORY_'];
			$viejo[29]='{CATEGORY}';			$nuevo[29]=$categorias->Leer($indice->Leer($ID,1),2);
			if($nuevo[29]=='') $nuevo[28]='';
		} else {
			$viejo[28]='{CATEGORY_TEXT}';		$nuevo[28]='';
			$viejo[29]='{CATEGORY}';			$nuevo[29]='';
		}
		if($GLOBALS['gBanderas'] AND $this->webs[$ID]->pais!='unknow') {
			$viejo[30]='{COUNTRY_FLAG}';			$nuevo[30]='<IMG src="images/flags/'.$this->webs[$ID]->pais.'.gif" width="'.$this->variables[5].'" height="'.$this->variables[6].'" align="absmiddle" border="0" alt="'.$_Country_[$this->webs[$ID]->pais].'">';
			$viejo[31]='{COUNTRY_TEXT}';			$nuevo[31]=$GLOBALS['_Country_'][$this->webs[$ID]->pais];
		} else {
			$viejo[30]='{COUNTRY_FLAG}';			$nuevo[30]='';
			$viejo[31]='{COUNTRY_TEXT}';			$nuevo[31]='';
		}			
	
		//Aplicamos el modelo para webs.
		return str_replace($viejo,$nuevo,$this->modelo[6]);
	}
	
	function _GenerarEstadisticas() {
		$viejo[0]='{NEXT_UPDATE}';
		$nuevo[0]=str_replace('{DATE}',ej3Date('fechaCorta',$GLOBALS['gTiempoActualizar']+$this->infoDAT_local->ultActualizacion),$GLOBALS['_NextUpdate_']);
		
		$viejo[1]='{NEXT_RESET}';
		$nuevo[1]=str_replace('{DATE}',ej3Date('fechaCorta',$GLOBALS['gTiempoResetear']+$this->infoDAT_local->ultReset),$GLOBALS['_NextReset_']);

		$viejo[2]='{NEXT_UPDATE_COUNTDOWN}';
		$nuevo[2]='<div id="next_update"></div>'."\n";
		$nuevo[2].='<script>reloj0=new Reloj(<?php echo $infoDAT->ultActualizacion-time()+$gTiempoActualizar; ?>,"next_update",<?php echo \'"\'.$_NextUpdateCountDown_.\'"\'; ?>,<?php echo $gAutoActualizar; ?>); setInterval("reloj0.Atras()",1000);</script>'."\n";

		$viejo[3]='{NEXT_RESET_COUNTDOWN}';
		$nuevo[3]='<div id="next_reset"></div>'."\n";
		$nuevo[3].='<script>reloj1=new Reloj(<?php echo $infoDAT->ultReset+$gTiempoResetear-time(); ?>,"next_reset",<?php echo \'"\'.$_NextResetCountDown_.\'"\'; ?>,<?php echo $gAutoActualizar; ?>); setInterval("reloj1.Atras()",1000);</script>'."\n";

		$viejo[4]='{WEBS_NUMBER}';
		$nuevo[4]=str_replace('{NUMBER}',$this->infoDAT_local->webs,$GLOBALS['_WebsNumber_']);

		$viejo[5]='{ORDER_BY}';
		$nuevo[5]=str_replace('{TEXT}',$GLOBALS['_SortCriterion_'][$GLOBALS['gCriterioOrden']],$GLOBALS['_OrderBy_']);

		$viejo[6]='{PARCIAL_IN}';
		$nuevo[6]=str_replace('{NUMBER}',$this->infoDAT_local->parcialIN,$GLOBALS['_ParcialIn_']);
		$viejo[7]='{TOTAL_IN}';
		$nuevo[7]=str_replace('{NUMBER}',$this->infoDAT_local->totalIN,$GLOBALS['_TotalIn_']);
		$viejo[8]='{PARCIAL_OUT}';
		$nuevo[8]=str_replace('{NUMBER}',$this->infoDAT_local->parcialOUT,$GLOBALS['_ParcialOut_']);
		$viejo[9]='{TOTAL_OUT}';
		$nuevo[9]=str_replace('{NUMBER}',$this->infoDAT_local->totalOUT,$GLOBALS['_TotalOut_']);
		
		$viejo[10]='{STATS_TEXT}';
		$nuevo[10]=$GLOBALS['_Stats_'];
		

		return str_replace($viejo,$nuevo,$this->modelo[3]);
	}
	
	function _GenerarNavegador() {
		if($this->infoDAT_local->paginas > 1) {
			$viejo[0]='{SELECT_PAGE}';
			$nuevo[0]='<?php'."\n";
			$nuevo[0].='$out="";'."\n";
			$nuevo[0].='if($infoDAT->paginas > 1) {'."\n";
			$nuevo[0].='	$out.="<SELECT onChange=\"window.location.href=this.options[this.selectedIndex].value;\">";'."\n";
			$nuevo[0].='	for($i=1;$i<=$infoDAT->paginas;$i++) {'."\n";
			$nuevo[0].='    	$out.="<OPTION value=\"index.php?p=".$i."&c=".$prefijo."\"";'."\n";
			$nuevo[0].='    	if($i==$p) $out.=" selected";'."\n";
			$nuevo[0].='    	$out.=">Page ".$i;'."\n";		
			$nuevo[0].='	}'."\n";
			$nuevo[0].='	$out.="</SELECT>";'."\n";
			$nuevo[0].='}'."\n";
			$nuevo[0].='echo $out;'."\n";
			$nuevo[0].='?>'."\n";
	
			$viejo[1]='{LIST_PAGE}';
			$nuevo[1]='<?php'."\n";
			$nuevo[1].='$out="";'."\n";
			$nuevo[1].='if($infoDAT->paginas > 1) {'."\n";
			$nuevo[1].='	for($i=1;$i<=$infoDAT->paginas;$i++) {'."\n";
			$nuevo[1].='    	if($i==$p) {'."\n";
			$nuevo[1].='        	$out.="&nbsp;[".$i."]";'."\n";
			$nuevo[1].='    	} else {'."\n";
			$nuevo[1].='        	$out.="&nbsp;<a href=\"index.php?p=".$i."&c=".$prefijo."\">".$i."</a>";'."\n";
			$nuevo[1].='    	}'."\n";				
			$nuevo[1].='	}'."\n";
			$nuevo[1].='}'."\n";
			$nuevo[1].='echo $out;'."\n";
			$nuevo[1].='?>'."\n";
			
			$viejo[2]='{AUTO_PAGE}';
			$nuevo[2]=$nuevo[1];
			if($this->infoDAT_local->paginas > 5) $nuevo[2]=$nuevo[0];
	
			$viejo[3]='{NEXT_PAGE}';
			$nuevo[3]='<?php'."\n";
			$nuevo[3].='if($p<$infoDAT->paginas) echo "<a href=\"index.php?p=".($p+1)."&c=".$prefijo."\"><img src=\"themes/".$gTema."/arrow_next.gif\" border=\"0\"></a>";'."\n";
			$nuevo[3].='?>'."\n";
			
			$viejo[4]='{PREV_PAGE}';
			$nuevo[4]='<?php'."\n";
			$nuevo[4].='if($p>1) echo "<a href=\"index.php?p=".($p-1)."&c=".$prefijo."\"><img src=\"themes/".$gTema."/arrow_prev.gif\" border=\"0\"></a>";'."\n";
			$nuevo[4].='?>'."\n";
		} else {
			$viejo[0]='{SELECT_PAGE}';	$nuevo[0]='&nbsp;';
			$viejo[1]='{LIST_PAGE}';	$nuevo[1]='&nbsp;';
			$viejo[2]='{AUTO_PAGE}';	$nuevo[2]='&nbsp;';
			$viejo[3]='{NEXT_PAGE}';	$nuevo[3]='&nbsp;';
			$viejo[4]='{PREV_PAGE}';	$nuevo[4]='&nbsp;';
		}
		
		if($GLOBALS['gCategorias']) {
			$viejo[5]='{SELECT_CATEGORY}';
			$nuevo[5]='<?php'."\n";
			$nuevo[5].='echo "<SELECT onChange=\"window.location.href=\'index.php?c=\'+this.options[this.selectedIndex].value;\">";'."\n";
			$nuevo[5].='echo "<OPTION value=\"0000000000.0000\">".$_All_."</OPTION>";'."\n";
			$nuevo[5].='echo $categorias->Select(1,$c);'."\n";
			$nuevo[5].='echo "</SELECT>";'."\n";
			$nuevo[5].='?>'."\n";
		} else {
			$viejo[5]='{SELECT_CATEGORY}';
			$nuevo[5]='&nbsp;';
		}
		
		//Aplicamos el modelo para el navegador.
		return str_replace($viejo,$nuevo,$this->modelo[4]);
	}
	
	function _GenerarSitioDelMomento($anchoBanner=468,$maxTiempo=10) {
		//Elegimos un sitio aleatoriamente.
		$elegidoID=array_rand($this->webs);
		//Generamos la tabla con el sitio elegido usando las etiquetas retringidas.
		if(is_array($this->webs[$elegidoID]->bannerURL)) {
			$num=count($this->webs[$elegidoID]->bannerURL);
			$ind=0;
			if($GLOBALS['gMultiBanner'] AND $num>0) $ind=array_rand($this->webs[$elegidoID]->bannerURL);
			if(substr($this->webs[$elegidoID]->bannerURL[$ind],-4)=='.swf') {	//Banner tipo flash
				$viejo[0]='{BANNER}';
				$nuevo[0]='<span id="inf'.$posAhora.'" class="description"></span>';
				$nuevo[0].='<a href="index.php?m=top&s=out&ID='.$elegidoID.'" target="_blank">';
				$nuevo[0].='<embed id="ban'.$posAhora.'" src="" quality="medium" width="'.$this->variables[1].'" height="'.$this->variables[2].'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></a>';
				$nuevo[0].='<SCRIPT>banner'.$posAhora.'=new PrecargarFlash(\''.$this->webs[$elegidoID]->bannerURL[$ind].'\',\'ban'.$posAhora.'\',\'inf'.$posAhora.'\','.$maxTiempo.','.$this->variables[1].','.$this->variables[2].');</SCRIPT>';
			} elseif($maxTiempo) {	//Con precarga de banners
				$viejo[0]='{BANNER}';
				$nuevo[0]='<span id="infSdM" class="description"></span>';
				$nuevo[0].='<a href="index.php?m=top&s=out&ID='.$elegidoID.'" target="_blank">';
				$nuevo[0].='<img id="banSdM" src="themes/'.$this->tema.'/loading.gif" border="0" onMouseMove="window.status=\''.str_replace('&#'.ord("'"),"\'",$this->webs[$elegidoID]->web).'\';" onMouseOut="window.status=\' \';"></a>';
				$nuevo[0].='<SCRIPT>bannerSdM=new PrecargarBanner(\''.$this->webs[$elegidoID]->bannerURL[$ind].'\',\'banSdM\',\'infSdM\','.$maxTiempo.','.$this->variables[1].','.$this->variables[2].');</SCRIPT>';
			} else {			//Sin precarga
				$img_info=@GetImageSize($this->webs[$elegidoID]->bannerURL[$ind]);
				$tam=' ';
				if($img_info[0]>$this->variables[1] OR $img_info[1]>$this->variables[2]) {
					if($img_info[0]>=$img_info[1]) {
						$tam=' width="'.$this->variables[1].'" ';
					} else {
						$tam=' height="'.$this->variables[2].'" ';
					}
				}
				$viejo[0]='{BANNER}';
				$nuevo[0]='<img src="'.$this->webs[$elegidoID]->bannerURL.'"'.$tam.'border="0">';
				$nuevo[0]='<a href="index.php?m=top&s=out&ID='.$elegidoID.'" target="_blank">'.$nuevo[0].'</a>';
			}
		} else {
			$viejo[0]='{BANNER}';		$nuevo[0]='';
		}
		$viejo[1]='{WEB}';				$nuevo[1]='<a class="webs" href="index.php?m=top&s=out&ID='.$elegidoID.'" target="_blank" onMouseMove="window.status=\''.str_replace('&#'.ord("'"),"\'",$this->webs[$elegidoID]->web).'\';" onMouseOut="window.status=\' \';">'.$this->webs[$elegidoID]->web.'</a>';
		$viejo[2]='{DESCRIPTION}';		$nuevo[2]='<span class="description">'.$this->webs[$elegidoID]->descripcion.'</span>';
		$viejo[3]='{WIDTH}';			$nuevo[3]=$anchoBanner+10;
		$viejo[4]='{INFO_BUTTON}';		$nuevo[4]='<INPUT TYPE="BUTTON" class="minibutton" value="'.$GLOBALS['_Info_'].'" onClick="ventana(\'index.php?m=top&s=info&ID='.$elegidoID.'\',\'_blank\','.(200*$GLOBALS['gComentarios']+361).',424)">';
		if($GLOBALS['gPuntuacion']) {
			$viejo[11]='{RATE_BUTTON}';		$nuevo[16]='<INPUT TYPE="BUTTON" class="minibutton" value="'.$GLOBALS['_RateIt_'].'" onClick="ventana(\'index.php?m=top&s=info&ID='.$elegidoID.'&t=puntuar\',\'_blank\',300,170)">';
		} else {
			$viejo[11]='{RATE_BUTTON}';		$nuevo[16]='';
		}
		$viejo[5]='{PERSONAL_STATS}';
		$nuevo[5]='<?php'."\n";
		$nuevo[5].='if($gEstaPersonales) {'."\n";
		$nuevo[5].='    if(!isset($cookie)) $cookie=new Cookies;'."\n";
		$nuevo[5].='    if($valor=$cookie->webHTML(\''.str_replace('.','',$elegidoID).'\')) {'."\n";
		$nuevo[5].='        echo "<img src=themes/'.$GLOBALS['gTema'].'/icon_stats.gif border=0 align=absmiddle onMouseOver=\"return overlib(\'".$valor."\',FULLHTML,STATUS,\''.$GLOBALS['_PersonalStats_'].'\')\" onMouseOut=\"nd();\">";'."\n";
		$nuevo[5].='    } else {'."\n";
		$nuevo[5].='        echo "";'."\n";
		$nuevo[5].='    }'."\n";
		$nuevo[5].='}'."\n";
		$nuevo[5].='?>'."\n";
		if($GLOBALS['gBanderas'] AND $this->webs[$elegidoID]->pais!='unknow') {
			$viejo[6]='{COUNTRY_FLAG}';			$nuevo[6]='<IMG src="images/flags/'.$this->webs[$elegidoID]->pais.'.gif" width="'.$this->variables[5].'" height="'.$this->variables[6].'" align="absmiddle" border="0" alt="'.$GLOBALS['_Country_'][$this->webs[$elegidoID]->pais].'">';
		} else {
			$viejo[6]='{COUNTRY_FLAG}';			$nuevo[6]='';
		}
		$viejo[7]='{SITE_OF_THE_MOMENT_TEXT}';		$nuevo[7]=$GLOBALS['_SiteOfTheMoment_'];
		if($GLOBALS['gPuntuacion']) {
			$viejo[8]='{AVERAGE_NUMBER}';		$nuevo[8]=0;
			$viejo[9]='{AVERAGE_GRAPH}';		$nuevo[9]='<IMG src="themes/'.$GLOBALS['gTema'].'/rate0.gif" border="0">';
			if($this->webs[$elegidoID]->dat[6]>0) {
				$nuevo[8]=number_format($this->webs[$elegidoID]->dat[6]/$this->webs[$elegidoID]->dat[5],1,'.','');
				$nuevo[9]='<IMG src="themes/'.$GLOBALS['gTema'].'/rate'.number_format($this->webs[$elegidoID]->dat[6]/$this->webs[$elegidoID]->dat[5],0,'.','').'.gif" border="0" align="absmiddle">';
			}
		} else {
			$viejo[8]='{AVERAGE_NUMBER}';		$nuevo[8]='';
			$viejo[9]='{AVERAGE_GRAPH}';		$nuevo[9]='';
		}
		$viejo[10]='{CLASS}';		$nuevo[10]=rand(1,2);
		//Aplicamos el modelo para el sitio del momento.
		return str_replace($viejo,$nuevo,$this->modelo[8]);
	}
	
	function _GenerarPodium($num=3) {
		//Recolectamos las IDs de las páginas que figuran en el podium.
		$resta=0;
		$tabla='<table border="0" cellpadding="2" cellspacing="1">';
		foreach($this->infoDAT_local->podium as $key => $value) {
			if($key-$resta>$num) break;
			if(!isset($this->webs[$value])) {
				$resta++;
				continue;
			}
			unset($viejo); unset($nuevo);
			$viejo[0]='{POSITION}';
			$nuevo[0]=$key-$resta;
			$viejo[1]='{WEB}';
			$nuevo[1]='<a class="webs" href="index.php?m=top&s=out&ID='.$value.'" target="_blank" onMouseMove="window.status=\''.str_replace('&#'.ord("'"),"\'",$this->webs[$value]->web).'\';" onMouseOut="window.status=\' \';">'.$this->webs[$value]->web.'</a>';
			//$nuevo[1]='<a class="webs" href="'.$this->webs[$value]->webURL.'" target="_blank">'.$this->webs[$value]->web.'</a>';
			if($GLOBALS['gBanderas'] AND $this->webs[$value]->pais!='unknow') {
				$viejo[2]='{COUNTRY_FLAG}';
				$nuevo[2]='<IMG src="images/flags/'.$this->webs[$value]->pais.'.gif" width="'.$this->variables[5].'" height="'.$this->variables[6].'" align="absmiddle" border="0" alt="'.$GLOBALS['_Country_'][$this->webs[$value]->pais].'">';
			} else {
				$viejo[2]='{COUNTRY_FLAG}';
				$nuevo[2]='';
			}
			$viejo[3]='{PERSONAL_STATS}';
			$nuevo[3]='<?php'."\n";
			$nuevo[3].='if($gEstaPersonales) {'."\n";
			$nuevo[3].='    if(!isset($cookie)) $cookie=new Cookies;'."\n";
			$nuevo[3].='    if($valor=$cookie->webHTML(\''.str_replace('.','',$value).'\')) {'."\n";
			$nuevo[3].='        echo "<img src=\"themes/'.$GLOBALS['gTema'].'/icon_stats.gif\" border=\"0\" align=\"absmiddle\" onMouseOver=\"return overlib(\'".$valor."\',FULLHTML,STATUS,\''.$GLOBALS['_PersonalStats_'].'\')\" onMouseOut=\"nd();\">";'."\n";
			$nuevo[3].='    } else {'."\n";
			$nuevo[3].='        echo "";'."\n";
			$nuevo[3].='    }'."\n";
			$nuevo[3].='}'."\n";
			$nuevo[3].='?>'."\n";

			$viejo[4]='{INFO_BUTTON}';
			$nuevo[4]='<INPUT TYPE="BUTTON" class="minibutton" value="'.$GLOBALS['_Info_'].'" onClick="ventana(\'index.php?m=top&s=info&ID='.$value.'\',\'_blank\','.(200*$GLOBALS['gComentarios']+361).',424)">';
			if($GLOBALS['gPuntuacion']) {
				$viejo[5]='{RATE_BUTTON}';
				$nuevo[5]='<INPUT TYPE="BUTTON" class="minibutton" value="'.$GLOBALS['_RateIt_'].'" onClick="ventana(\'index.php?m=top&s=info&ID='.$value.'&t=puntuar\',\'_blank\',300,170)">';
			} else {
				$viejo[5]='{RATE_BUTTON}';		$nuevo[5]='';
			}
					
			//Aplicamos el modelo para el podium.
			$tabla.='<tr class="'.((1+$i++)%2).'">'.str_replace($viejo,$nuevo,$this->modelo[10]).'</tr>';
		}
		$tabla.='</table>';
		
		//Metemos las filas generadas dentro de la tabla.
		if(strlen($tabla)<60) {
			$tabla='';
		} else {
			$tabla=str_replace(array('{PODIUM_TEXT}','{PODIUM_LIST}'),array($GLOBALS['_Podium_'],$tabla),$this->modelo[9]);
		}
		
		return $tabla;
	}
			
}
	
?>