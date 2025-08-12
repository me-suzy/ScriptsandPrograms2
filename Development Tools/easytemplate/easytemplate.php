<?php
/*
 @ Project: EasyTemplate 1.1 
 @ Link http://daif.net/easy/
 @ Author Daifallh Al Otaibi <daif55@gmail.com>
*/
	class EasyTemplate{
		var $files	= array();
		var $vars;
		var $fid;
		var $temp_path="./";// your template path OR "template"
		var $cash_path="easycach";// must be writeable check permission OR $_ENV["TEMP"];
		var $color = array();
		var $page;
		//patterns Array
		var $pats = array(
		//Foreach Variables
		"/{{([A-Z0-9_]{1,})}}/i",
		//Variables
		"/{([A-Z0-9_]{1,})}/i",
		//Foreach Statement
		"/<LOOP NAME=(\"|)+([a-z0-9_]{1,})+(\"|)>/i",
		"/<\/LOOP>/i",
		//IF & Else Statement
		"/<IF NAME=\"([A-Z0-9_]{1,})=(.+)\">/i",
		"/<IF NAME=(\"|)+([a-z0-9_]{1,})+(\"|)>/i",
		"/<ELSE>/i",
		"/<\/IF>/i",
		//Include Statement
		"/<INCLUDE NAME=\"(.+)\">/iU",
		//Switch Color
		"/(#[0-9A-Z]{6})\|(#[0-9A-Z]{6})/iU"
		);
		//Replacements Array
		var $reps = array(
		"<?= \$var[\\1]?>",
		"<?= \$this->vars[\"\\1\"]?>",
		"<? foreach(\$this->vars[\"\\2\"] as \$key=>\$var){ ?>",
		"<? } ?>",
		"<? if(\$this->vars[\"\\1\"]==\"\\2\"){ ?>",
		"<? if(\$this->vars[\"\\2\"]){ ?>",
		"<?} else {?>",
		"<? } ?>",
		"<?= EasyTemplate::display(\"\\1\"); ?>",
		"<?= (\$this->sw(\"\\1\\2\")) ? \"\\1\":\"\\2\";?>"
		);

	//Function to load a template file.
		function load_file($template){
			if(!file_exists($this->temp_path)) exit("<b>ERROR:</b> Template Folder $this->temp_path Not Exists");
			if(!file_exists($template)) exit("<b>ERROR:</b> Template File $template Not Exists");
			$this->files[$this->fid] = file_get_contents($template);
		}
	//Function to Switch Color.
		function sw($index){
			return $this->color["$index"] = ($this->color["$index"]) ? false:true;
		}
	//Function to parse the Template Tags
		function parse(){
			$this->files[$this->fid] = preg_replace($this->pats,$this->reps,$this->files[$this->fid]);
		}
	//Function to OUTPUT
		function display($template) {
			$this->vars = &$GLOBALS;
			$this->fid = $template.".php";
			if($this->temp_path) $template = $this->temp_path."/".$template;
			$tmd = @filemtime($template);
			if(!is_writeable($this->cash_path))
			$this->cash_path = $_ENV["TEMP"];
			$id =$this->cash_path."/".$tmd;
			if(!file_exists($id.$this->fid)){
				$this->load_file($template);
				$this->parse();
				$fp = fopen($id.$this->fid,"w");
				fwrite($fp,$this->files[$this->fid]);
				fclose($fp);
			}
			ob_start();
			include($id.$this->fid);
			$this->page = ob_get_contents();
			ob_end_clean();
			return $this->page;
		}
	}
?>