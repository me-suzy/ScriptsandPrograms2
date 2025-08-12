<?php
/*
class: template
purpose: template engine
*/
ini_set(display_errors,off);
class template{

/*
function: template
purpose: configuration
*/
function template(){
        //Configuration
        //Example values given...

        $this->path=".\\";
        //Local path to htdocs
        $this->webpath="http://realms.tcgames.net/";
        //Web path to htdocs
        $this->template_dir="templates";
        //Folder that holds templates, relative to $this->path
}

/*
function: deslash
purpose: remove extra slashes from path
*/
function deslash($file){
        $file=preg_replace('/\057{2,}/','/',$file);
        $file=preg_replace('/\134{2,}/','/\134/',$file);
        $file=preg_replace('/(http|ftp):\057/','$1://',$file);
        return $file;
}

/*
function: define_file
purpose: defines template file
*/
function define_file($file){
        $this->template_file=$file;
        $this->template_regioncount=-1;
}

/*
function: add_region
purpose: adds template dynamic region
*/
function add_region($region,$value){
        $this->template_regioncount++;
        $this->template_regions[$this->template_regioncount]=$region;
        $this->template_regionvalues[$this->template_regioncount]=$value;
}

/*
function: debug
purpose: set debugging to true
*/
function debug(){
        $this->template_debug=TRUE;
}

/*
function: make_template
purpose: make and display template
*/
function make_template(){
        if (!@fopen($this->deslash($this->path."/".$this->template_dir."/".$this->template_file),"r")){
                echo "<b>Error</b>: The template ".$this->deslash($this->path."/".$this->template_dir."/".$this->template_file)." could not be found.";
        }else{
                $fp = @fopen($this->deslash($this->path."/".$this->template_dir."/".$this->template_file),"r");
                $regioncount=count($this->template_regions);
                for ($i=0; $i<$regioncount; $i++){
                        if ($this->template_regions[$i]=="headers"){
                                $page=$this->template_regionvalues[$i];
                        }
                }
                $this->webpath=$this->deslash($this->webpath);
                if (substr($this->webpath,strlen($this->webpath)-1,1)<>"/"){$this->webpath.="/";}
                while (!feof($fp)) {
                        $line = fgets($fp,4096);
                        $line = stripslashes($line);
                        $line = preg_replace("/href=\"..\//","href=\"".$this->webpath,$line);
                        $line = preg_replace("/src=\"..\//","src=\"".$this->webpath,$line);
                        $line = preg_replace("/action=\"..\//","action=\"".$this->webpath,$line);
                        for ($i=0; $i<$regioncount; $i++){
                                $line = preg_replace("/{".$this->template_regions[$i]."}/",$this->template_regionvalues[$i],$line);
                        }
                        $page.=$line;
                }
                if ($this->template_debug==TRUE){
                        $page.="<p><h3>Source:</h3><hr>".highlight_string($page,TRUE);
                }
                reset ($GLOBALS);
                while (list ($key, $val) = each ($GLOBALS)){
                        //eval("$".$key." = \"".$val."\";\n");
                        eval("\$" . "\$key = \"$val\";");
                        //print"$".$key." = \"".$val."\";\n";
                        //print"$$key = $val";
                        //print"<BR>\n";
                }
                $questMark = "?";
                $phpOpenTag = "<${questMark}php";
                $phpCloseTag = "${questMark}>";
                return eval("$phpCloseTag" . stripslashes($page) . "$phpOpenTag ");
        }
}

}
?>