<?php
//
//  class_db_text.php
//	rev009
//  PHP v4.2+
//

/**
 * @author Emilio José Jiménez <ej3@myrealbox.com>
 **/

class Index {
	var $registros;				//Array indexado por IDs en formato F10.4 tratadas como strings
	var $numRegistros;			//int
	var $ruta;					//Ruta hasta el directorio /data/ (acabada en /)
	var $numConsultas;			//Número de consultas realizadas
	var $segConsultas;			//Segundos consumidos por las consultas
	
	/**
	 * Index::Index()
	 * Accedemos al index.dat y cargamos los datos.
	 * 
	 * @param string $ruta Ruta hasta el directorio "data" (acabada en /)
	 * @return void
	 **/
	function Index($ruta='') {
		$this->ruta=$ruta;
		$this->numRegistros=0;
		$this->numConsultas=0;
		$this->segConsultas=0;
		$this->numConsultas++;
		$cronometro=ej3Time();
		if(file_exists($this->ruta.'data/index.dat')) {
			$linea=file($this->ruta.'data/index.dat');
			foreach($linea as $value) {
				if(strlen($value)<20) continue;
				$value=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
				$aux=explode('||',$value);
				$this->registros[strval($aux[0])]=$value;
				$this->numRegistros++;
			}
		} else {
			$error=new Error;
			die($error->Archivo($this->ruta.'data/index.dat'));
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
	}

	/**
	 * Index::_Guardar()
	 * Vuelca el contenido en la BD con el formato:
	 * ID||ID_CATEGORIA||VISTO_BUENO||CATEGORIA_LOCK||NOMBRE||\n
	 * ...
	 * 
	 * @return void
	 **/
	function _Guardar() {
		$this->numConsultas++;
		$cronometro=ej3Time();
		ksort($this->registros);
		foreach($this->registros as $value) $data.=$value."\n";
		if($fp=fopen($this->ruta.'data/index.dat','w')) {
			flock($fp,2);
			fwrite($fp,$data);
			fclose($fp);
			@chmod($this->ruta.'data/index.dat',0666);
		} else {
			$error=new Error;
			die($error->Archivo($this->ruta.'data/index.dat'));
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
	}

	/**
	 * Index::Existe()
	 * Nos dice si una ID es válida.
	 * 
	 * @param string $ID 
	 * @return 
	 **/
	function Existe($ID) {
		if(isset($this->registros[$ID])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Index::Listar()
	 * Devuelve un array con las IDs de los sitios pertenecientes a $cat
	 * 
	 * @return array
	 **/
	function Listar($cat='') {
		$this->numConsultas++;
		$cronometro=ej3Time();
		if(count($this->registros)==0) return '';
		if($cat=='0000000000.0000' OR $cat=='') {
			foreach($this->registros as $key => $value) {
				if($GLOBALS['gVistoBueno']) {
					if($this->Leer($key,2)) $lista[]=$key;
				} else {
					$lista[]=$key;
				}
			}
		} else {
			foreach($this->registros as $key => $value) {
				$aux=explode('||',$value);
				if($aux[1]==$cat) {
					if($GLOBALS['gVistoBueno']) {
						if($aux[2]) $lista[]=$key;
					} else {
						$lista[]=$key;
					}
				}
			}
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		return $lista;
	}
	
	function EmailTo($para=0) {
		$this->numConsultas++;
		$cronometro=ej3Time();
		foreach($this->registros as $value) {
			$aux=explode('||',$value);
			switch($para) {
				case 0:
					$lista[]=$aux[0];
					break;
				case 1:
					if($aux[2]==1) $lista[]=$aux[0];
					break;
				case 2:
					if($aux[2]==0) $lista[]=$aux[0];
					break;
			}
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		return $lista;
	}	
	
	/**
	 * Index::Tabla()
	 * Devuelve un array ordenado según $criterio con todos los registros.
	 * 
	 * @param $criterio =0 timestamp, =1 categoria, =4 web
	 * @return array
	 **/
	function Tabla($criterio=4) {
		if($this->numRegistros==0) return FALSE;
		$this->numConsultas++;
		$cronometro=ej3Time();
		foreach($this->registros as $value) {
			$aux=explode('||',$value);
			$orden[]=strtolower($aux[$criterio]).'||'.$value;
		}
		sort($orden);
		foreach($orden as $value) {
			$aux=explode('||',$value);
			$salida[]=$aux[1].'||'.$aux[2].'||'.$aux[3].'||'.$aux[4].'||'.$aux[5].'||'.$aux[6].'||';
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		return $salida;
	}

	/**
	 * Categorias::Select()
	 * Devuelve el código interno de una etiqueta SELECT según el siguiente esquema:
	 * <OPTION value="id_sitio">Nombre_sitio</OPTION>
	 * 
	 * @param int $cuales =0 inactivas, =1 activas, =2 todas 
	 * @return string
	 **/
	function Select($cuales=2,$criterio=4) {
		if($this->numRegistros==0) return FALSE;
		$this->numConsultas++;
		$cronometro=ej3Time();
		foreach($this->registros as $value) {
			$aux=explode('||',$value);
			$orden[]=strtolower($aux[$criterio]).'||'.$value;
		}
		sort($orden);
		foreach($orden as $value) {
			$aux=explode('||',$value);
			if($cuales==0 OR $cuales==1) {
				if($aux[3]==$cuales) {
					$salida.='<OPTION value="'.$aux[1].'"';
					$salida.='>'.$aux[5].'</OPTION>'."\n";
				}
			} else {
				$salida.='<OPTION value="'.$aux[1].'">'.$aux[5].'</OPTION>';
			}
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		return $salida;
	}
	
	function Leer($ID,$indice=0) {
		if($indice==0) {
			return $this->registros[$ID];
		} else {
			$aux=explode('||',$this->registros[$ID]);
			return $aux[$indice];
		}
	}

	function Borrar($ID,$guardar=1) {
		$this->numConsultas++;
		$cronometro=ej3Time();
		unset($this->registros[$ID]);
		@unlink($this->ruta.'data/'.$ID.'.php');
		@unlink($this->ruta.'data/'.$ID.'.dat');
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		if($guardar) $this->_Guardar();
	}
	
	function Escribir($ID,$data,$guardar=1) {
		$this->numConsultas++;
		$cronometro=ej3Time();
		if($url=@parse_url($data[5])) {
			$path=pathinfo($url['path']);
			$data[5]=$url['scheme'].'://'.$url['host'].$path['dirname'].'/';
		}
		$data[5]=str_replace('\/','/',$data[5]);
		$this->registros[$ID]=$ID.'||'.$data[1].'||'.$data[2].'||'.$data[3].'||'.$data[4].'||'.$data[5].'||';
		$this->numRegistros=count($this->registros);
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		if($guardar) $this->_Guardar();
	}
	
	function BorrarCategoria($c,$guardar=1) {
		$this->numConsultas++;
		$cronometro=ej3Time();
		foreach($this->registros as $key => $value) {
			$aux=explode('||',$value);
			if($aux[1]==$c) $this->registros[$key]=$key.'||0000000000.0000||'.$aux[2].'||'.$aux[3].'||'.$aux[4].'||';
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		if($guardar) $this->_Guardar();
	}
	
}

class Categorias {
	var $ruta;			//Ruta hasta el directorio /data/ (acabada en /)
	var $registros;		//Lineas de data/categorias.dat indexadas por IDs
	var $numRegistros;	//Número de categorias.
	var $numConsultas;	//Número de consultas realizadas
	var $segConsultas;	//Segundos consumidos por las consultas

	function Categorias($ruta='') {
		$this->ruta=$ruta;
		$this->numConsultas=0;
		$this->segConsultas=0;
		$this->numConsultas++;
		$cronometro=ej3Time();
		if(file_exists($this->ruta.'data/categories.dat')) {
			$linea=file($this->ruta.'data/categories.dat');
			foreach($linea as $value) {
				if(strlen($value)<20) continue;
				$value=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
				$aux=explode('||',$value);
				$this->registros[$aux[0]]=$value;
				$this->numRegistros++;
			}
		} else {
			$error=new Error;
			die($error->Archivo($this->ruta.'data/categories.dat'));
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
	}
	
	/**
	 * Categorias::_Guardar()
	 * Vuelca el contenido en la BD con el formato:
	 * ID||ACTIVA||NOMBRE||\n
	 * ...
	 * 
	 * @return void
	 **/
	function _Guardar() {
		$this->numConsultas++;
		$cronometro=ej3Time();
		foreach($this->registros as $value) $data.=$value."\n";
		if($fp=fopen($this->ruta.'data/categories.dat','w')) {
			flock($fp,2);
			fwrite($fp,$data);
			fclose($fp);
			@chmod($this->ruta.'data/categories.dat',0666);
		} else {
			$error=new Error;
			die($error->Archivo($this->ruta.'data/categories.dat'));
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
	}
	
	function Escribir($ID,$data) {
		$this->registros[$ID]=$ID.'||'.$data[1].'||'.$data[2].'||';
		$this->numRegistros=count($this->registros);
		$this->_Guardar();
	}
	
	function Borrar($ID) {
		unset($this->registros[$ID]);
		$this->_Guardar();
	}
	
	function Leer($ID,$indice=0) {
		if($indice==0) {
			return $this->registros[$ID];
		} else {
			$aux=explode('||',$this->registros[$ID]);
			return $aux[$indice];
		}
	}
	
	function Activar($ID) {
		$aux=explode('||',$this->registros[$ID]);
		$aux[1]=1;
		$this->Escribir($ID,$aux);	//Guarda automaticamente.
	}
	
	function Desactivar($ID) {
		$aux=explode('||',$this->registros[$ID]);
		$aux[1]=0;
		$this->Escribir($ID,$aux);	//Guarda automaticamente.
	}
	
	function Existe($ID) {
		return isset($this->registros[$ID]);
	}
	
	/**
	 * Categorias::Select()
	 * Devuelve el código interno de una etiqueta SELECT según el siguiente esquema:
	 * <OPTION value="id_categoria">Nombre_categoria</OPTION>
	 * 
	 * @param int $cuales =0 inactivas, =1 activas, =2 todas 
	 * @return string
	 **/
	function Select($cuales=2,$cat='') {
		if($this->numRegistros==0) return '';
		$this->numConsultas++;
		$cronometro=ej3Time();
		$orden=$this->registros;
		sort($orden);
		foreach($orden as $value) {
			$aux=explode('||',$value);
			if($cuales==0 OR $cuales==1) {
				if($aux[1]==$cuales) {
					$salida.='<OPTION value="'.$aux[0].'"';
					if($aux[0]==$cat) $salida.=' selected';
					$salida.='>'.$aux[2].'</OPTION>'."\n";
				}
			} else {
				$salida.='<OPTION value="'.$aux[0].'">'.$aux[2].'</OPTION>';
			}
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		return $salida;
	}

}
 
class SitioWeb {
	var $dat;			//Array de datos estadísticos.
	var $ficheroPHP;	//Fichero de datos básicos.
	var $ficheroDAT;	//Fichero de datos estadísticos.
	var $ID;
	var $clave;
	var $email;
	var $web;
	var $webURL;
	var $bannerURL;
	var $descripcion;
	var $pais;
	
	/**
	 * SitioWeb::SitioWeb()
	 * Cargamos datos básicos y estadísticos
	 * 
	 * @param string $id
	 * @param string $ruta Ruta hasta el directorio data (terminada en /)
	 * @return void
	 **/
	function SitioWeb($id,$ruta='') {
		$this->ID=$id;
		$this->ficheroPHP=$ruta.'data/'.$this->ID.'.php';
		$this->ficheroDAT=$ruta.'data/'.$this->ID.'.dat';
		if(file_exists($this->ficheroPHP) AND file_exists($this->ficheroDAT)) {
			include($this->ficheroPHP);
			$fp=fopen($this->ficheroDAT,'r');
			$aux=fgets($fp,1024);
			fclose($fp);
			$this->dat=explode('||',$aux);
		} else {
			$error=new Error;
			die($error->SitioPerdido($this->ID));
		}
	}
	
	/**
	 * SitioWeb::datLeer()
	 * Devuelve el array $dat ó alguno de sus elementos.
	 * 
	 * @param integer $indice Todo(=0) ó parte(=1..9)
	 * @return string
	 **/
	function datLeer($indice=0) {
		if($indice==0) return $this->dat;
		return $this->dat[$indice];
	}

}


class SitioWebAvanzado extends SitioWeb {
	var $not;		//Array de notas
	var $com;		//Array de comentarios
	var $ips;		//Array de IPs
	var $numConsultas;			//Número de consultas realizadas
	var $segConsultas;			//Segundos consumidos por las consultas
	

	/**
	 * SitioWebAvanzado::SitioWebAvanzado()
	 * Cargamos datos básicos y estadísticos
	 * 
	 * @param string $id
	 * @param integer $caducidad Timestamp para resetear y actualizar IPs
	 * @param string $ruta Ruta hasta el directorio data (terminada en /)
	 * @return void
	 **/
	function SitioWebAvanzado($id,$ruta='') {
		$this->numConsultas=0;
		$this->segConsultas=0;
		$this->numConsultas++;
		$cronometro=ej3Time();
		$this->ID=$id;
		$this->ficheroPHP=$ruta.'data/'.$this->ID.'.php';
		$this->ficheroDAT=$ruta.'data/'.$this->ID.'.dat';
		if(file_exists($this->ficheroPHP)) include($this->ficheroPHP);
		if(file_exists($this->ficheroDAT)) {
			$linea=file($this->ficheroDAT);
			$this->dat=explode('||',$linea[0]);
			unset($linea[0]);
			foreach($linea as $value) {
				if(strlen($value)<20) continue;
				$value=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
				$aux=explode('||',$value);
				$this->{$aux[0]}[$aux[1]]=$value;
			}
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
	}
	
	function Crear() {
		$this->dat[1]=0;
		$this->dat[2]=0;
		$this->dat[3]=0;
		$this->dat[4]=0;
		$this->dat[5]=0;
		$this->dat[6]=0;
		$this->dat[7]=0;
		$this->dat[8]=time();
		$this->dat[9]=time();
		
		$this->_Guardar();
	}
	
	/**
	 * SitioWebAvanzado::IN()
	 * Alias de SitioWebAvanzado::ipsEscribir()
	 * 
	 * @param string $ip
	 * @param integer $caducidad Borra las IPs cuyo TIMESTAMP < $caducidad
	 * @return void
	 **/
	function IN($ip,$caducidad=0) {
		$this->dat[1]++;
		$this->dat[3]++;
		if($caducidad) $this->ipsActualizar($caducidad);
		$this->ipsEscribir($ip);	//Hace una llamada a _Guardar(0,1)
	}
	
	/**
	 * SitioWebAvanzado::OUT()
	 * Contabilizamos el OUT
	 * 
	 * @return void
	 **/
	function OUT() {
		$this->dat[2]++;			//Parcial OUT
		$this->dat[4]++;			//Total OUT
		$this->dat[9]=time();		//Timestamp actual
		$this->_Guardar(0,1);
	}
	
	/**
	 * SitioWebAvanzado::Resetear()
	 * Pone a cero los contadores PARCIAL IN y PARCIAL OUT
	 * Este metodo se utiliza cuando generamos el top.
	 * Volcamos el contenido al archivo $this->ficheroDAT
	 * 
	 * @return void
	 **/
	function Resetear() {
		$this->dat[1]=0;
		$this->dat[2]=0;
		$this->_Guardar(0,1);
	}
	
	/**
	 * SitioWebAvanzado::_Guardar()
	 * Recuenta arrays y vuelca el contenido al disco
	 * 
	 * @param integer $php Guardar fichero PHP
	 * @param integer $dat Guardar fichero DAT
	 * @return void
	 **/
	function _Guardar($php=1,$dat=1) {
		$this->numConsultas++;
		$cronometro=ej3Time();
		if($php) {	//Fichero PHP
			$data="<?php\n";
			$data.="if(stristr(\$_SERVER[\"PHP_SELF\"],'".$this->ID.".php')) {\n";
			$data.='	die("<script>window.location.href=\"../in.php?ID='.$this->ID.'\";</script>");'."\n";
			$data.="}\n";
			$data.="\$this->clave='".$this->clave."';\n";
			$data.="\$this->email='".$this->email."';\n";
			$data.="\$this->web='".$this->web."';\n";
			$data.="\$this->webURL='".$this->webURL."';\n";
			if(is_array($this->bannerURL)) {
				foreach($this->bannerURL as $key => $value) {
					if(strlen($value)<20) continue;
					$data.="\$this->bannerURL[".$key."]='".$value."';\n";
				}
			}
			$data.="\$this->descripcion='".$this->descripcion."';\n";
			$data.="\$this->pais='".$this->pais."';\n";
			$data.="?>";
			if($fp=fopen($this->ficheroPHP,'w')) {
				flock($fp,2);
				fwrite($fp,$data);
				fclose($fp);
				@chmod($this->ficheroPHP,0666);
			} else {
				$error=new Error;
				die($error->Archivo($this->ficheroPHP));
			}
		}
		if($dat) {	//Fichero DAT
			$this->dat[5]=count($this->not);
			$this->dat[7]=count($this->com);
			$data="dat||";
			for($i=1;$i<=9;$i++) $data.=$this->dat[$i]."||";
			$data.="\n";
			if(is_array($this->not))
				foreach($this->not as $value) $data.=$value."\n";
			if(is_array($this->com))
				foreach($this->com as $value) $data.=$value."\n";
			if(is_array($this->ips))
				foreach($this->ips as $value) $data.=$value."\n";
			if($fp=fopen($this->ficheroDAT,'w')) {
				flock($fp,2);
				fwrite($fp,$data);
				fclose($fp);
				@chmod($this->ficheroDAT,0666);
			} else {
				$error=new Error;
				die($error->Archivo($this->ficheroDAT));
			}
		}
		$this->segConsultas+=abs(ej3Time()-$cronometro);
	}
	
	/**
	 * SitioWebAvanzado::notEscribir()
	 * Devuelve una cadena vacia ó la nota antigua.
	 * 
	 * @param string $ip
	 * @param float $nota
	 * @return float
	 **/
	function notEscribir($ip,$nota) {
		$nota=intval($nota);
		$salida='';
		if(isset($this->not[$ip])) {
			$salida=$this->notLeer($ip,4);
			$this->dat[6]+=($nota-$salida);
		} else {
			$this->dat[6]+=$nota;
		}
		$this->not[$ip]='not||'.$ip.'||'.gethostbyaddr($ip) .'||'.time().'||'.$nota.'||';
		$this->_Guardar(0,1);
		return $salida;
	}
	
	/**
	 * SitioWebAvanzado::notLeer()
	 * Lee todo ó parte del registro
	 * not||IP||HOST||TIMESTAMP||PUNTUACION||
	 * 
	 * @param string $ip
	 * @param integer $indice Todo(=0) ó parte(=1..4)
	 * @return $string
	 **/
	function notLeer($ip,$indice=0) {
		if($indice==0) {
			return $this->not[$ip];
		} else {
			$aux=explode('||',$this->not[$ip]);
			return $aux[$indice];
		}
	}
	
	/**
	 * SitioWebAvanzado::notBorrar()
	 * Borra el registro con la $ip pasada
	 * 
	 * @param $ip
	 * @return 
	 **/
	function notBorrar($ip) {
		unset($this->not[$ip]);
	}
	
	function comEscribir($ip,$data) {
		if(!is_array($data)) return;
		$this->com[$ip]='com||'.$ip.'||'.gethostbyaddr($ip) .'||'.time().'||'.$data[0].'||'.$data[1].'||'.$data[2].'||'.$data[3].'||';
		$this->_Guardar(0,1);
		return;
	}
	
	function comLeer($ip,$indice=0) {
		if($indice==0) {
			return $this->com[$ip];
		} else {
			$aux=explode('||',$this->com[$ip]);
			return $aux[$indice];
		}
	}
	
	function comBorrar($ip) {
		unset($this->com[$ip]);
		$this->_Guardar(0,1);
	}
	
	/**
	 * SitioWebAvanzado::ipsEscribir()
	 * Guardamos la IP que realiza la votación
	 * 
	 * @param string $ip
	 * @return bolean Si la IP existe TRUE, sino FALSE.
	 **/
	function ipsEscribir($ip) {
		if(isset($this->ips[$ip])) return TRUE;
		$aux=time();
		$this->ips[$ip]='ips||'.$ip.'||'.gethostbyaddr($ip) .'||'.$aux.'||';
		$this->dat[8]=$aux;
		$this->_Guardar(0,1);
		return FALSE;
	}
	
	/**
	 * SitioWebAvanzado::ipsExiste()
	 * Comprueba que existe una ip
	 * 
	 * @param string $ip
	 * @return string Devuelve 0 ó TIMESTAMP
	 **/
	function ipsExiste($ip) {
		if(!is_array($this->ips)) return FALSE;
		$salida='0';
		if(isset($this->ips[$ip])) {
			$aux=explode('||',$this->ips[$ip]);
			$salida=$aux[3];
		}
		return $salida;
	}
	
	/**
	 * SitioWebAvanzado::ipsActualizar()
	 * Elimina las IPs cuyo TIMESTAMP < $caducidad
	 * 
	 * @param float $caducidad Timestamp
	 * @return void
	 **/
	function ipsActualizar($caducidad) {
		if(!is_array($this->ips)) return;
		foreach($this->ips as $key => $value) {
			$aux=explode('||',$value);
			if($aux[3]<$caducidad) unset($this->ips[$key]);
		}
	}

	function ipsLeer($ip,$indice=0) {
		if($indice==0) {
			return $this->ips[$ip];
		} else {
			$aux=explode('||',$this->ips[$ip]);
			return $aux[$indice];
		}
	}
	
}

class Conversor_v1x_a_v2x {
	var $path='';		//Path hasta el directorio /data/ (acabado en /)
	var $index_file='';	//Path hasta el archivo index.dat antiguo.
	var $lista;			//Array indexado por IDs con el contenido de index.dat antiguo.
	
	function Conversor_v1x_a_v2x($archivo) {
		$this->index_file=$archivo;
		$this->path=str_replace('index.dat','',$archivo);
		if(file_exists($this->index_file)) {
			$linea=file($this->index_file);
			foreach($linea as $value) {
				if(strlen($value)<20) continue;
				$value=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
				$aux=explode('||',$value);
				$this->lista[strval($aux[0])]=$aux[2];
			}
		} else {
			$error=new Error;
			die($error->Archivo($this->index_file));
		}
	}
	
	function Convertir() {
		$indice=new Index();
		foreach($this->lista as $key => $value) {
			if(!file_exists($this->path.$key.'info.php')) continue;
			do {
				$nuevaID=$key.'.'.rand(1000,9999);
			} while($indice->Existe($nuevaID));
			//Archivo .PHP
			include($this->path.$key.'info.php');
			$nuevoPHP="<?php\n";
			$nuevoPHP.="if(stristr(\$_SERVER[\"PHP_SELF\"],'".$nuevaID.".php')) {\n";
			$nuevoPHP.='	die("<script>window.location.href=\"../in.php?ID='.$nuevaID.'\";</script>");'."\n";
			$nuevoPHP.="}\n";
			$viejo=array('\'','\"',"\'",'|','$','<');
			$nuevo=array('&#'.ord("'"),'&#'.ord('"'),'&#'.ord("'"),'&#'.ord('|'),'&#'.ord('$'),'&#'.ord('<'));
			$nuevoPHP.="\$this->clave='".str_replace($viejo,$nuevo,$pass)."';\n";
			$nuevoPHP.="\$this->email='".str_replace($viejo,$nuevo,$email)."';\n";
			$nuevoPHP.="\$this->web='".str_replace($viejo,$nuevo,$web)."';\n";
			$nuevoPHP.="\$this->webURL='".str_replace($viejo,$nuevo,$webURL)."';\n";
			if(strlen($bannerURL)>10) $nuevoPHP.="\$this->bannerURL[0]='".str_replace($viejo,$nuevo,$bannerURL)."';\n";
			$nuevoPHP.="\$this->descripcion='".str_replace($viejo,$nuevo,$descripcion)."';\n";
			$nuevoPHP.="\$this->pais='unknow';\n";
			$nuevoPHP.="?>";
			
			//Archivos .DAT
			if(!file_exists($this->path.$key.'datos.dat')) continue;
			$raw=file($this->path.$key.'datos.dat');
			$aux=explode('||',$raw[0]);
			$nuevoDAT='dat||'.$aux[0].'||'.$aux[1].'||'.$aux[2].'||'.$aux[3].'||'.$aux[5].'||'.$aux[4].'||'.$aux[6].'||'.$aux[7].'||'.$aux[8].'||'."\n";
			
			if($raw=@file($this->path.$key.'notas.dat')) {
				foreach($raw as $registro_nota) {
					unset($aux);
					$aux=explode('||',$registro_nota);
					$nuevoDAT.='not||'.$aux[0].'||unknow||'.$aux[1].'||'.$aux[2].'||'."\n";
				}
			}
			
			if($raw=@file($this->path.$key.'comentarios.dat')) {
				foreach($raw as $registro_comentario) {
					unset($aux);
					$aux=explode('||',$registro_comentario);
					$nuevoDAT.='com||'.$aux[0].'||unknow||'.$aux[1].'||'.$aux[2].'||'.$aux[3].'||'.$aux[4].'||'.$aux[5].'||'."\n";
				}
			}

			if($raw=@file($this->path.$key.'ip.dat')) {
				foreach($raw as $registro_ip) {
					unset($aux);
					$aux=explode('||',$registro_ip);
					$nuevoDAT.='ips||'.$aux[0].'||unknow||'.$aux[1].'||'."\n";
				}
			}
			
			//Guardamos los archivos generados.
			$fp=fopen('data/'.$nuevaID.'.php','w');
			fwrite($fp,$nuevoPHP);
			fclose($fp);

			$fp=fopen('data/'.$nuevaID.'.dat','w');
			fwrite($fp,$nuevoDAT);
			fclose($fp);
			
			//Añadimos el registro al index.dat
			$data[1]='0000000000.0000';
			$data[2]=$value;
			$data[3]=0;
			$data[4]=$web;
			$data[5]=$webURL;
			$indice->Escribir($nuevaID,$data,0);
		}
		$indice->_Guardar();
	}

}	

?>