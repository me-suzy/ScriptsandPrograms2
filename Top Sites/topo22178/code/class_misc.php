<?php
//
//  class_misc.php
//	rev006
//  PHP v4.2+
//

/**
 * Online
 * 
 * @package EJ3 TOPo
 * @author Emilio José Jiménez Jiménez <ej3soft@ej3.net>
 * @copyright Copyright (c) 2002-2004
 * @version 1.0
 **/
class Online {
	var $IPs=array();			//Array de IPs (indexado por IPs)
	var $paises=array();		//Array de paises (indexados por códigos de pais).
	var $max_total=0;
	var $max_total_timestamp=0;
	var $max_hoy=0;
	var $max_hoy_timestamp=0;
	var $online_ahora=0;
	var $archivo;
	var $ahora;					//Timestamp actual.
	var $numConsultas;			//Número de consultas realizadas
	var $segConsultas;			//Segundos consumidos por las consultas

	function Online($archivo) {
		$this->archivo=$archivo;
		$this->ahora=time();
		$this->numConsultas=0;
		$this->segConsultas=0;
		$this->numConsultas++;
		$cronometro=ej3Time();
		//Cargamos los datos
		if($linea=file($this->archivo)) {
			$aux=explode('||',$linea[0]);
			$this->max_hoy=$aux[0];
			$this->max_hoy_timestamp=$aux[1];
			$this->max_total=$aux[2];
			$this->max_total_timestamp=$aux[3];
			unset($linea[0]);
			foreach($linea as $value) {
				if(strlen($value)<20) continue;
				$value=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
				$aux=explode('||',$value);
				$this->IPs[$aux[0]]['timestamp']=$aux[1];
				$this->IPs[$aux[0]]['pais']=$aux[2];
				$this->paises[$aux[2]]++;
			}
		}
		$this->online_ahora=count($this->IPs);
		$this->segConsultas+=abs(ej3Time()-$cronometro);
	}
	
	/**
	 * Online :: Insert()
	 * Insertamos una linea en el archivo de datos con el formato:
	 * IP||TIMESTAMP||PAIS||
	 * 
	 * @param array $data Array asociativo con los campos ip,timestamp,pais
	 * @return boolean
	 **/
	function Insert($data) {
		$this->IPs[$data['ip']]['timestamp']=$data['timestamp'];
		$this->IPs[$data['ip']]['pais']=$data['pais'];
	}
	
	/**
	 * Online :: Refresh()
	 * Actualizamos los datos en memoria eliminando los que tienen una timestamp
	 * caducada (aquella que es menos que time()-$intervalo) y recalculando las
	 * variables de usuarios online y el array de usuarios por pais.
	 * 
	 * @param int $intervalo Segundos de caducidad
	 * @return void
	 **/
	function Refresh($intervalo) {
		foreach($this->IPs as $key=>$value) {
			if($value['timestamp']<$this->ahora-$intervalo) unset($this->IPs[$key]);
		}
		unset($this->paises);
		foreach($this->IPs as $key=>$value) $this->paises[$value['pais']]++;
		$this->online_ahora=count($this->IPs);
		if($this->max_hoy<$this->online_ahora) {
			$this->max_hoy=$this->online_ahora;
			$this->max_hoy_timestamp=$this->ahora;
		}
		if($this->max_total<$this->online_ahora) {
			$this->max_total=$this->online_ahora;
			$this->max_total_timestamp=$this->ahora;
		}
	}
	
	/**
	 * Online :: Save()
	 * Guardamos los datos en $archivo
	 * 
	 * @return Número de bytes escritos o FALSE si hay error
	 **/
	function Save() {
		$this->numConsultas++;
		$cronometro=ej3Time();
		$data=$this->max_hoy.'||'.$this->max_hoy_timestamp.'||'.$this->max_total.'||'.$this->max_total_timestamp.'||'."\n";
		foreach($this->IPs as $key=>$value) $data.=$key.'||'.$value['timestamp'].'||'.$value['pais'].'||'."\n";
		$fp=fopen($this->archivo,'w');
		flock($fp,2);
		$salida=fwrite($fp,$data);
		fclose($fp);
		@chmod($this->archivo,0777);
		$this->segConsultas+=abs(ej3Time()-$cronometro);
		return $salida;
	}
	
	/**
	 * Online :: overLib()
	 * Genera el código HTML para usar con overLIB
	 * y utilizar variables de localización para las cadenas de texto.
	 * 
	 * @return string Tabla de código HTML
	 **/
	function overLib() {
		$overHTML='<TABLE border=0 cellspacing=0 cellpadding=1>';
		//Usuarios Online
		$overHTML.="<TR class=minititle><TD class=0 align=center colspan=2>".$GLOBALS['_OnlineUsers_']."</TD></TR>";
		$overHTML.="<TR class=description><TD class=1 align=right nowrap>&nbsp; <B>".$GLOBALS['_Now_'].":</B>&nbsp;</TD><TD class=1 nowrap>".$this->online_ahora." [".ej3Date('horaCorta')."]</TD></TR>";
		$overHTML.="<TR class=description><TD class=1 align=right nowrap>&nbsp; <B>".$GLOBALS['_MaxToday_'].":</B>&nbsp;</TD><TD class=1 nowrap>".$this->max_hoy." [".ej3Date('horaCorta',$this->max_hoy_timestamp)."]</TD></TR>";
		$overHTML.="<TR class=description><TD class=1 align=right nowrap>&nbsp; <B>".$GLOBALS['_MaxEver_'].":</B>&nbsp;</TD><TD class=1 nowrap>".$this->max_total." [".ej3Date('fechaCorta',$this->max_total_timestamp)."]</TD></TR>";
		//Usuarios Online por paises.
		if(count($this->paises)) {
			arsort($this->paises);
			$max=max($this->paises);
			$overHTML.="<TR class=minititle><TD class=0 align=center colspan=2>".$GLOBALS['_ByCountry_']."</TD></TR>";
			foreach($this->paises as $key => $value) {
				$ancho=(130*$value)/$max;
				$overHTML.='<TR><TD class='.(1+($i++)%2).' colspan=2>&nbsp;<img src=images/flags/'.$key.'.gif border=0 width=30 height=20 align=absmiddle>&nbsp;<img src=themes/'.$GLOBALS['gTema'].'/bar_1.jpg border=0 width='.$ancho.' height=10 align=absmiddle>&nbsp;<b>'.$value.'</b></TD></TR>';
			}
		}
		$overHTML.="</TABLE>";
		return '<table class=highlight border=0px cellspacing=2 cellpadding=2><tr><td class=1>'.$overHTML.'</td></tr></table>';
	}

}

/**
 * @author Emilio José Jiménez <ej3@myrealbox.com>
 **/

class Cookies {

	function MandarCookie($nombre,$contenido,$caducidad) {
		$expira=time()+$caducidad;
		setcookie($nombre,$contenido,"$expira",'/');
	}

	/**
	 * Cookies :: webHTML()
	 * Genera el código HTML para usar con overLIB
	 * 
	 * @param string $id Código ID de la web, ***OJO QUE TIENE QUE SER UNA CADENA***
	 * @return string Tabla de código HTML
	 **/
	function webHTML($id) {
		$nombre='topoWeb'.str_replace('.','',$id);
		if(isset($_COOKIE[$nombre])) {
			$aux=explode('||',$_COOKIE[$nombre]);
			if($aux[0])	$cadena1='['.ej3Date('fechaCorta',$aux[3]).' ('.ej3Date('horaCorta',$aux[3]).')]';
			if($aux[1]) $cadena2='['.ej3Date('fechaCorta',$aux[4]).' ('.ej3Date('horaCorta',$aux[4]).')]';
			$overHTML='<table class=0 border=0 cellspacing=0 cellpadding=1>';
			$overHTML.='<tr class=minititle><td class=0 colspan=2 align=center>'.$GLOBALS['_PersonalStats_'].'</td></tr>';
			$overHTML.='<tr class=description><td class=1 align=right nowrap><b>'.$GLOBALS['_YourIN_'].'</b></td><td class=2 nowrap>&nbsp;<b><span class=in>'.$aux[0].'</span></b> '.$cadena1.'</td></tr>';
			$overHTML.='<tr class=description><td class=1 align=right nowrap><b>'.$GLOBALS['_YourOUT_'].'</b></td><td class=2 nowrap>&nbsp;<b><span class=out>'.$aux[1].'</span></b> '.$cadena2.'</td></tr>';
			$overHTML.='<tr class=description><td class=1 align=right nowrap><b>'.$GLOBALS['_YourRate_'].'</b></td><td class=2 nowrap>&nbsp;<IMG src=themes/'.$GLOBALS['gTema'].'/rate'.$aux[2].'.gif border=0></td></tr>';
			$overHTML.='</table>';
			return '<table class=highlight border=0px cellspacing=2 cellpadding=2><tr><td class=1>'.$overHTML.'</td></tr></table>';
		} else {
			return FALSE;
		}
	}

	function esAdmin() {
		//global $HTTP_COOKIE_VARS;
		//Hay que implementar para el array $_COOKIES		
		//global $gAdminLogin, $gAdminPass;
		if(isset($_COOKIE['topoAdmin'])) {
			$aux=explode('||',$_COOKIE['topoAdmin']);
			if($aux[0]==$GLOBALS['gAdminLogin'] AND $aux[1]==$GLOBALS['gAdminPass']) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	function webUltIN($id) {
		$nombre='topoWeb'.str_replace('.','',$id);
		if(isset($_COOKIE[$nombre])) {
			$aux=explode('||',$_COOKIE[$nombre]);
			return $aux[3];
		} else {
			return FALSE;
		}
	}
	
	function webActualizar($id,$indice,$valor='') {
		$nombre='topoWeb'.str_replace('.','',$id);
		if(isset($_COOKIE[$nombre])) {
			if(strlen($_COOKIE[$nombre]) < 20) {
				$aux=array(0,0,0,0,0);
			} else {
				$aux=explode('||',$_COOKIE[$nombre]);
			}
		} else {
			$aux=array(0,0,0,0,0);	
		}
		$data=array('IN'=>$aux[0],'OUT'=>$aux[1],'NOTA'=>$aux[2],'ULT_IN'=>$aux[3],'ULT_OUT'=>$aux[4]);
		if($valor=='') {	//Válido para IN y OUT
			$data[$indice]=$data[$indice]+1;
			$data['ULT_'.$indice]=time();
		} else {
			$data[$indice]=$valor;
		}
		$contenido=$data['IN'].'||'.$data['OUT'].'||'.$data['NOTA'].'||'.$data['ULT_IN'].'||'.$data['ULT_OUT'].'||';
		$this->MandarCookie($nombre,$contenido,99999999);
	}

}

class Email {

	/**
 	 * Vars
	 */
	var $debug_status = "yes";			// "yes" | "no" | "halt"
	var $charset = "ISO-8859-1";
	var $mail_subject = "No subject";
	var $mail_from = "Anonymous <fake@mail.com>";
	var $mail_to;
	var $mail_cc;
	var $mail_bcc;
	var $mail_text;
	var $mail_html;
	var $mail_type;
	var $mail_header;
	var $mail_body;
	var $mail_reply_to;
	var $mail_return_path;
	var $attachments_index;
	var $attachments = array();
	var $attachments_img = array();
	var $boundary_mix;
	var $boundary_rel;
	var $boundary_alt;
	var $sended_index;

	var $error_msg = array(
			1	=>	'Mail was not sent',
			2	=>	'Body Build Incomplete',
			3	=>	'Need a mail recipient in mail_to',
			4	=>	'No valid Email',
			5	=>	'Opening File'
	);

	var $mime_types = array(
			'gif'  => 'image/gif',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpe'  => 'image/jpeg',
			'bmp'  => 'image/bmp',
			'png'  => 'image/png',
			'tif'  => 'image/tiff',
			'tiff' => 'image/tiff',
			'swf'  => 'application/x-shockwave-flash',
			'doc'  => 'application/msword',
			'xls'  => 'application/vnd.ms-excel',
			'ppt'  => 'application/vnd.ms-powerpoint',
			'pdf'  => 'application/pdf',
			'ps'   => 'application/postscript',
			'eps'  => 'application/postscript',
			'rtf'  => 'application/rtf',
			'bz2'  => 'application/x-bzip2',
			'gz'   => 'application/x-gzip',
			'tgz'  => 'application/x-gzip',
			'tar'  => 'application/x-tar',
			'zip'  => 'application/zip',
			'html' => 'text/html',
			'htm'  => 'text/html',
			'txt'  => 'text/plain',
			'css'  => 'text/css',
 			'js'   => 'text/javascript'
	);


	/**
	 * Constructor
	 * void Email()
	 */
	function Email(){
		$this->boundary_mix = "=-nxs_mix_" . md5(uniqid(rand()));
		$this->boundary_rel = "=-nxs_rel_" . md5(uniqid(rand()));
		$this->boundary_alt = "=-nxs_alt_" . md5(uniqid(rand()));
		$this->attachments_index = 0;
		$this->sended_index = 0 ;
		if(!defined('BR')){
			define('BR', (strstr(PHP_OS, 'WIN') ? "\r\n" : "\n"), TRUE);
		}
	}


	/**
	 * void set_from(string mail_from, [string name])
	 */
	function set_from($mail_from, $name = ""){
		if ($this->validate_mail($mail_from)){
			$this->mail_from = !empty($name) ? "$name <$mail_from>" : $mail_from;
		}
		else {
			$this->mail_from = "Anonymous <fake@mail.com>";
		}
	}


	/**
	 * bool set_to(string mail_to, [string name])
	 */
	function set_to($mail_to, $name = ""){
		if ($this->validate_mail($mail_to)){
			$this->mail_to = !empty($name) ? "$name <$mail_to>" : $mail_to;
			return true;
		}
		return false;
	}


	/**
	 * bool set_cc(string mail_cc, [string name])
	 */
	function set_cc($mail_cc, $name = ""){
		if ($this->validate_mail($mail_cc)){
			$this->mail_cc = !empty($name) ? "$name <$mail_cc>" : $mail_cc;
			return true;
		}
		return false;
	}


	/**
	 * bool set_bcc(string mail_bcc, [string name])
	 */
	function set_bcc($mail_bcc, $name = ""){
		if ($this->validate_mail($mail_bcc)){
			$this->mail_bcc = !empty($name) ? "$name <$mail_bcc>" : $mail_bcc;
			return true;
		}
		return false;
	}


	/**
	 * bool add_to(string mail_to, [string name])
	 */
	function add_to($mail_to, $name = ""){
		if ($this->validate_mail($mail_to)){
			$mail_to = !empty($name) ? "$name <$mail_to>" : $mail_to;
			$this->mail_to = !empty($this->mail_to) ? $this->mail_to . ", " . $mail_to : $mail_to;
			return true;
		}
		return false;
	}


	/**
	 * bool add_cc(string mail_cc, [string name])
	 */
	function add_cc($mail_cc, $name = ""){
		if ($this->validate_mail($mail_cc)){
			$mail_cc = !empty($name) ? "$name <$mail_cc>" : $mail_cc;
			$this->mail_cc = !empty($this->mail_cc) ? $this->mail_cc . ", " . $mail_cc : $mail_cc;
			return true;
		}
		return false;
	}


	/**
	 * bool add_bcc(string mail_bcc, [string name])
	 */
	function add_bcc($mail_bcc, $name = ""){
		if ($this->validate_mail($mail_bcc)){
			$mail_bcc = !empty($name) ? "$name <$mail_bcc>" : $mail_bcc;
			$this->mail_bcc = !empty($this->mail_bcc) ? $this->mail_bcc . ", " . $mail_bcc : $mail_bcc;
			return true;
		}
		return false;
	}


	/**
	 * bool set_reply_to(string mail_reply_to, [string name])
	 */
	function set_reply_to($mail_reply_to, $name = ""){
		if ($this->validate_mail($mail_reply_to)){
			$this->mail_reply_to = !empty($name) ? "$name <$mail_reply_to>" : $mail_reply_to;
			return true;
		}
		return false;
	}


	/**
	 * bool set_return_path(string mail_return_path)
	 */
	function set_return_path($mail_return_path){
		if ($this->validate_mail($mail_return_path)){
			$this->mail_return_path = $mail_return_path;
			return true;
		}
		return false;
	}


	/**
	 * void set_subject(string subject)
	 */
	function set_subject($subject){
		$this->mail_subject = !empty($subject) ? $subject : "No subject";
	}


	/**
	 * void set_text(string text)
	 */
	function set_text($text){
		if (!empty($text)){
			$this->mail_text = $text;
		}
	}


	/**
	 * void set_html(string html)
	 */
	function set_html($html){
		if (!empty($html)){
			$this->mail_html = $html;
		}
	}


	/**
	 * string get_eml()
	 */
	function get_eml() {
		if ($this->build_body()){
			return
				'To: ' . $this->mail_to . BR .
				'Subject: ' . $this->mail_subject . BR .
				$this->mail_header . BR . BR .
				$this->mail_body;
		}
		return false;
	}


	/**
	 * void new_mail([mixed from], [mixed to], [string subject], [string text], [string html])
	 */
	function new_mail($from = "", $to = "", $subject = "", $text = "", $html = ""){

		// First, clear all vars
		$this->mail_subject = "";
		$this->mail_from = "";
		$this->mail_to = "";
		$this->mail_cc = "";
		$this->mail_bcc = "";
		$this->mail_text = "";
		$this->mail_html = "";
		$this->mail_header = "";
		$this->mail_body = "";
		$this->mail_reply_to = "";
		$this->mail_return_path = "";
		$this->attachments_index = 0;
		$this->sended_index = 0;

		// Clear Array Vars
		$this->attachments = array();
		$this->attachments_img = array();

		// Asign vars
		if (is_array($from)){
			$this->set_from($from[0],$from[1]);
			$this->set_return_path($from[0]);
		}
		else {
			$this->set_from($from);
			$this->set_return_path($from);
		}

		if (is_array($to)){
			$this->set_to($to[0],$to[1]);
		}
		else {
			$this->set_to($to);
		}

		$this->set_subject($subject);
		$this->set_text($text);
		$this->set_html($html);
	}


	/**
	 * void add_attachment(mixed file, string name, [string type])
	 */
	function add_attachment($file, $name, $type = ""){
		if (($content = $this->open_file($file))){
			$this->attachments[$this->attachments_index] = array(
				'content' => chunk_split(base64_encode($content), 76, BR),
				'name' => $name,
				'type' => (empty($type) ? $this->get_mimetype($name): $type),
				'embedded' => false
			);
			$this->attachments_index++;
		}
	}


	/**
	 * bool send()
	 */
	function send(){
		if ($this->sended_index == 0 && !$this->build_body()){
			$this->debug(1);
			return false;
    	}

		if (!empty($this->mail_return_path) && $this->php_version_check('4.0.5') && !($this->php_version_check('4.2.3') && ini_get('safe_mode'))){
			return mail($this->mail_to, $this->mail_subject, $this->mail_body, $this->mail_header, '-f'.$this->mail_return_path);
		}
		else {
			return mail($this->mail_to, $this->mail_subject, $this->mail_body, $this->mail_header);
		}
	}


	/**
	 * Private
	 * bool build_body()
	 */
	function build_body(){
		switch ($this->parse_elements()){
			case 1:
				$this->build_header("Content-Type: text/plain");
				$this->mail_body = $this->mail_text;
				break;
			case 3:
				$this->build_header("Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"");
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR;
				break;
			case 5:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				foreach($this->attachments as $value){
					$this->mail_body .= "--" . $this->boundary_mix . BR;
					$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
					$this->mail_body .= $value['content'] . BR . BR;
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . BR;
				break;
			case 7:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . BR;
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR . BR;
				foreach($this->attachments as $value){
					$this->mail_body .= "--" . $this->boundary_mix . BR;
					$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
					$this->mail_body .= $value['content'] . BR . BR;
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . BR;
				break;
			case 11:
				$this->build_header("Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->boundary_rel\"");
				$this->mail_body .= "--" . $this->boundary_rel . BR;
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR . BR;
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->mail_body .= "--" . $this->boundary_rel . BR;
						$this->mail_body .= "Content-ID: <" . $value['embedded'] . ">" . BR;
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
						$this->mail_body .= $value['content'] . BR . BR;
					}
				}
				$this->mail_body .= "--" . $this->boundary_rel . "--" . BR;
				break;
			case 15:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . BR;
				$this->mail_body .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->boundary_rel\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_rel . BR;
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR . BR;
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->mail_body .= "--" . $this->boundary_rel . BR;
						$this->mail_body .= "Content-ID: <" . $value['embedded'] . ">" . BR;
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
						$this->mail_body .= $value['content'] . BR . BR;
					}
				}
				$this->mail_body .= "--" . $this->boundary_rel . "--" . BR . BR;
				foreach($this->attachments as $value){
					if (!$value['embedded']){
						$this->mail_body .= "--" . $this->boundary_mix . BR;
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
						$this->mail_body .= $value['content'] . BR . BR;
					}
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . BR;
				break;
			default:
				$this->debug(2);
				return false;
		}
		$this->sended_index++;
		return true;
	}


	/**
	 * Private
	 * void build_header()
	 */
	function build_header($content_type){
		if (!empty($this->mail_from)){
			$this->mail_header .= "From: " . $this->mail_from . BR;
			$this->mail_header .= !empty($this->mail_reply_to) ? "Reply-To: " . $this->mail_reply_to . BR : "Reply-To: " . $this->mail_from . BR;
		}
		if (!empty($this->mail_cc)){
			$this->mail_header .= "Cc: " . $this->mail_cc . BR;
		}
		if (!empty($this->mail_bcc)){
			$this->mail_header .= "Bcc: " . $this->mail_bcc . BR;
		}
		if (!empty($this->mail_return_path)){
			$this->mail_header .= "Return-Path: " . $this->mail_return_path . BR;
		}
		$this->mail_header .= "MIME-Version: 1.0" . BR;
		$this->mail_header .= "X-Mailer: neXus MIME Mail - PHP/". phpversion() . BR;
		$this->mail_header .= $content_type;
	}


	/**
	 * Private
	 * bool php_version_check(string vercheck)
	 */
	function php_version_check($vercheck){
		$minver = str_replace(".","", $vercheck);
		$curver = str_replace(".","", phpversion());
		if($curver >= $minver){
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 * Private
	 * mixed parse_elements()
	 */
	function parse_elements(){
		if (empty($this->mail_to)){
			$this->debug(3);
			return false;
		}
		$this->mail_type = 0;
		$this->search_images();
		if (!empty($this->mail_text)){
			$this->mail_type = $this->mail_type + 1;
		}
		if (!empty($this->mail_html)){
			$this->mail_type = $this->mail_type + 2;
			if (empty($this->mail_text)){
				$this->mail_text = strip_tags(eregi_replace("<br>", BR, $this->mail_html));
				$this->mail_type = $this->mail_type + 1;
			}
		}
		if ($this->attachments_index != 0){
			if (count($this->attachments_img) != 0){
				$this->mail_type = $this->mail_type + 8;
			}
			if ((count($this->attachments) - count($this->attachments_img)) >= 1){
				$this->mail_type = $this->mail_type + 4;
			}
		}
		return $this->mail_type;
	}


	/**
	 * Private
	 * void search_images()
	 */
	function search_images(){
		if ($this->attachments_index != 0){
			foreach($this->attachments as $key => $value){

				//TNX to Pawel Tomicki, Enrique Garcia M.
				//only one instruction to support background and src, better REGEX syntax
				//additional CSS support
				if (preg_match('/(css|image)/i', $value['type']) && preg_match('/\s(background|href|src)\s*=\s*[\"|\'](' . $value['name'] . ')[\"|\'].*>/is', $this->mail_html)) {
					$img_id = md5($value['name']) . ".nxs@mimemail";
					$this->mail_html = preg_replace('/\s(background|href|src)\s*=\s*[\"|\'](' . $value['name'] . ')[\"|\']/is', ' \\1="cid:' . $img_id . '"', $this->mail_html);
					$this->attachments[$key]['embedded'] = $img_id;
					$this->attachments_img[] = $value['name'];
				}
			}
		}
	}


	/**
	 * Private
	 * bool validate_mail(string mail)
	 */
	function validate_mail($mail){
		if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',$mail)){
			return true;
		}
		$this->debug(4, $mail);
		return false;
	}


	/**
	 * Private
	 * string get_mimetype(string name)
	 */
	function get_mimetype($name) {

		$ext_array = explode(".", $name);
		if (($last = count($ext_array) - 1) != 0){
			$ext = $ext_array[$last];
			if (isset($this->mime_types[$ext]))
				return $this->mime_types[$ext];
		}
		return "application/octet-stream";
	}


	/**
	 * Private
	 * int open_file(string file)
	 */
	function open_file($file){
		if(($fp = @fopen($file, 'r'))){
			$content = fread($fp, filesize($file));
			fclose($fp);
			return $content;
		}
		$this->debug(5, $file);
		return false;
   }


	/**
	 * Private
	 * void debug(string msg, [string element])
	 */
	function debug($msg, $element=""){
		if ($this->debug_status == "yes"){
			echo "<br><b>Error:</b> " . $this->error_msg[$msg] . " $element<br>";
		}
		elseif ($this->debug_status == "halt"){
			die ("<br><b>Error:</b> " . $this->error_msg[$msg] . " $element<br>");
		}
		return false;
	}

}

class Error {
	var $HEADER;
	var $FOOTER;
	
	function Error() {
		$this->HEADER='<html><head><style text="css/text">';
		$this->HEADER.='BODY { background-color: #000000; font-family : Arial, Helvetica, sans-serif; }';
		$this->HEADER.='.title { font-size: 14pt; font-weight: bold; color : #ff0000; }';
		$this->HEADER.='.text { font-size: 12pt; font-weight: bold; color : #ffffff; }';
		$this->HEADER.='.minitext { font-size: 8pt; font-weight: normal; color : #ffffff; }';
		$this->HEADER.='</style></head><body>';
		$this->FOOTER='</body></html>';
	}

	function Directorio($dir) {
		$ERROR='<CENTER><hr color="#ff0000">';
		$ERROR.='<span class="title">>>> ERROR <<<<BR>TOPo can\'t open <i>'.$dir.'</i> directory.</span>';
		$ERROR.='<BR><span class="text">Check if directory <i>'.$dir.'</i> exists and try to change directory permissions to 766.';
		$ERROR.='<hr color="#ff0000"></CENTER>';
		return $this->HEADER.$ERROR.$this->FOOTER;
	}
	
	function Archivo($file) {
		$ERROR='<CENTER><hr color="#ff0000">';
		$ERROR.='<span class="title">>>> ERROR <<<<BR>TOPo can\'t open <i>'.$file.'</i> file.</span>';
		$ERROR.='<BR><span class="text">Check if file <i>'.$file.'</i> exists and try to change file permissions to 666 or 766.';
		$ERROR.='<hr color="#ff0000"></CENTER>';
		return $this->HEADER.$ERROR.$this->FOOTER;
	}
	
	function SitioPerdido($id) {
		$borrarIndice=new Index();
		$borrarIndice->Borrar($id);	//Se guarda automaticamente.
		$ERROR='<CENTER><hr color="#ff0000">';
		$ERROR.='<span class="title">>>> ERROR <<<<BR>Index file is corrupt.</span>';
		$ERROR.='<BR><span class="text">Fixing index file...';
		$ERROR.='<hr color="#ff0000"></CENTER>';
		$ERROR.='<SCRIPT>';
		//$ERROR.='window.alert("To fix this problem, all references to:\n'.$borrarIndice->Leer($id,4).'\n will be erased.");';
		$ERROR.='window.location.href="index.php";';
		$ERROR.='</SCRIPT>';
		return $this->HEADER.$ERROR.$this->FOOTER;
	}
	
}


?>