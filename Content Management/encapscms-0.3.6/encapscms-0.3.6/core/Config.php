<?php
class Config
{
	function Config($filename)
	{
		include($filename);
		$this->filename = $filename;
		$this->postget= $_POST?$_POST:$_GET;
		$this->html = array();
		$this->html['config'] = $config;

		$this->postget['action'] = isset($this->postget["action"])?$this->postget['action']:'';
		switch($this->postget["action"]){
			case "config_update":$this->update();break;
		}		
		
	}
	
	function update(){
		foreach ($this->postget as $key=>$value){
			$this->html['config'][$key] = $value;
		}

		$fp = fopen($this->filename."","w");
		fputs($fp,"<?php");	
		foreach ($this->html['config'] as $key=>$value){
			$line = "\n".'$config["'.$key.'"] = "'.$value.'";';
			fputs($fp,$line);	
		}
		fputs($fp,"\n?>");	
		fclose($fp);
		
		//$html['action'] = 'config_update_exec';
		//$this->show_setup();
		?><SCRIPT language="JavaScript">document.location.href='index.php'</SCRIPT><?
		
	}
	
	function show(){
		$template["config"] = $this->params;
		
		include($this->params["template_path"]."adm_config.html");
//		var_dump($template["config"]);
	}

	function read()
	{
	
	}

	function show_setup(){
		$html = $this->html;
		include('html/config_init.html');
	}
	
}
?>
