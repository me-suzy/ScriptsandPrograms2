<?php
///////////////////////////////////////////////////////////////////////////////
//      =   =       ====  =   = ====                                         //
//      =   =       =   = =   = =   =                                        //
//      ==  =  ===  =   = =   = =   =                                        //
//      = = = =   = ====  ===== ====                                         //
//      =  == ===== =     =   = =                                            //
//      =   = =     =     =   = =                                            //
//      =   =  ==== =     =   = =                                            //
//      ------------------------------------------------------               //
//      ====        =     ===     =         =                                //
//      =   =       =       =               =                                //
//      =   = =   = ====    =   ===    ==== ====   ===   ===                 //
//      ====  =   = =   =   =     =   =     =   = =   = =   =                //
//      =     =   = =   =   =     =    ===  =   = ===== =                    //
//      =     =   = =   =   =     =       = =   = =     =                    //
//      =      ===  ====  ===== ===== ====  =   =  ==== =                    //
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
// Program Name         : Nephp Publisher Enterprise                         //
// Release Version      : 3.04                                               //
// Program Author       : Kenny Ngo     (CTO of Nelogic Technologies.)       //
// Program Author       : Ewdision Then (CEO of Nelogic Technologies.)       //
// Retail Price         : $499.00 United States Dollars                      //
// WebForum Price       : $000.00 Always 100% Free                           //
// ForumRu Price        : $000.00 Always 100% Free                           //
// xCGI Price           : $000.00 Always 100% Free                           //
// Supplied by          : Scoons [WTN]                                       //
// Nullified by         : CyKuH [WTN]                                        //
// Distribution         : via WebForum, ForumRU and associated file dumps    //
///////////////////////////////////////////////////////////////////////////////

function _date($time)
{
        $ad=localtime($time);$ad[4]++;
        //$in_date=date ("M d Y H:i:s", mktime ($ad[2],$ad[1],$ad[0],$ad[4],$ad[3],$ad[5]));
	$in_date=date("D, j M Y",mktime ($ad[2],$ad[1],$ad[0],$ad[4],$ad[3],$ad[5]));
        return $in_date;
}
function _cdate($time)
{
	$ad=localtime($time);$ad[4]++;
	$in_date=date("D, j M Y",mktime ($ad[2],$ad[1],$ad[0],$ad[4],$ad[3],$ad[5]));
        return $in_date;
}
function sys01_write($path,$contents)
{
        $fout=fopen ($path, "w");
	flock ($fout,2);
        if ($fout) { fputs ($fout,$contents);}
        else       { _err("Unabled to write contents to '$path'.");}
	flock ($fout,3);
        fclose ($fout);
}
// TEACHNICAL ERROR Generator
function _err($string,$sqlcon)
{
        header("Content-type: text/html");
        print $string;
        if($sqlcon){mysql_close($connection);}
        exit;
}
function _cookie($name,$value,$exp,$path,$domain)
{
	if($path!='')   { $xxtra.="path=$path;";     }
	if($domain!='') { $xxtra.="domain=$domain;"; }
	if($exp!='')    { $xxtra.="expires=".strftime("%A, %d-%b-%Y %H:%M:%S MST", time()+$exp).";";}
	Header ("Set-Cookie: $name=$value;$xxtra\n");
}

// TEMPLATE Generator
function _html($path,$htmlescape)
{
        if(!file_exists($path))
        {
                _err("Unabled to locate the file \"$path\".");
        }
        $fp=fopen($path,"r");
        $contents=fread($fp,filesize($path));
        if($htmlescape == 1)
        {
                $contents=htmlspecialchars($contents);
        }
	unset($path);
        return $contents;
}

function _email($to,$from,$name,$title,$msg)
{
	global $_cfig;
	$fd = popen($_cfig{"sendmail"},"w");
	fputs($fd, "To: $to\n");
	fputs($fd, "Cc: $CC\n");
	fputs($fd, "From: $name <$from>\n");
	fputs($fd, "Subject: $title\n");
	fputs($fd, "X-Mailer: PHP\n");
	fputs($fd, "Content-Type: text/plain; charset=us-ascii\n");
	fputs($fd, "Content-Transfer-Encoding: 7bit\n");
	fputs($fd, $msg);
	pclose($fd);
}
// Session Generator
function _sid($length)
{
        mt_srand((double)microtime()*1000000);
        $counter=$i=0; $value='';
        while ($i<$length)
        {
                if($counter%2==0) { $value .= chr(mt_rand(48,57));  }
                else              { $value .= chr(mt_rand(97,122)); }
                $counter++;$i++;
        }
        return $value;
}
// TEXT TRIMMING
function dtxt($txt)
{
	$cdat=explode("|",$txt);
	while(list($t1,$t2)=each($cdat))
	{
		if($t2!='') { $ctxt.=chr($t2+100);}
	}
	return $ctxt;
}
// SPANNING Generating Function
function _span($span,$page,$totalsize,$url)
{
        $code="<font face=\"Verdana\">";
        $nPages=floor($totalsize/$span);
        if($totalsize%$span>0){$nPages++;}
        if ($nPages<=1)  { $span_display=""; }
        else
        {
                $next_numb=$page+1;$pre_numb = $page-1;
                if ($page!=1) { $code.="<b><a href=\"$url&page=$pre_numb\">Prev</a></b>"; }
                else          {        $code.="<b>Prev</b>";                                     }
                if ($nPages >12)
                {
                        $startpage=$page-6;$endspan=$page+6;
                        if ($startpage<=0)      { $startpage=1;$endspan=12;               }
                        else                    { $startpage=$page-6;                     }
                        if ($endspan > $nPages) { $endspan=$nPages;$startpage=$nPages-12; }
                }
                else
                {
                        $startpage=1;$endspan=$nPages;
                }
                for ($s=$startpage;$s<=$endspan;$s++)
                {
                        if ($s!=$page){$code.=" <a href=\"$url&page=$s\">$s</a> ";}
                        else          {$code.=" <b>$s</b> ";}
                }
                if ($nPages >12)   {if ($endspan != $nPages){$code.=" <b>...</b> ";}}
                if ($page!=$nPages){$code.="<b><a href=\"$url&page=$next_numb\">Next</a></b>";}
                else               {$code.="<b>Next</b>";}
        }
        $code.="</font>";
        return $code;
}
class form_droplist
{
	var $items,$name,$with,$selected,$xtra;
	
	function form_droplist($name,$width,$xtra='')
	{
		$this->name  = $name;
		$this->width = $width;
		$this->xtra  = $xtra;
		$this->items = array();
	}
	function add_items($choice, $value='')
	{
		$this->items[$choice]=$value;
	}
	function set_select($choice)
	{
		$this->selected=$choice;
	}
	function build_form()
	{
		$tmp="<select size='".$this->width."' name='".$this->name."' ".$this->xtra.">\n";
		while(list($key,$value)=each($this->items))
		{
			if($this->selected == $key) { $stag=' selected'; }
			else                        { $stag=''; 	}
			//////////////////////////////////////////////////
			if($value=='') { $tmp.="\t<option$stag>$key</option>\n";                  }
			else           { $tmp.="\t<option value=\"$value\"$stag>$key</option>\n"; }
		}
		$tmp.="</select>";
		return $tmp;
	}
}
// Database fetch by fields
function _db($result,$data)
{
        for($i=0;$i<mysql_num_fields($result);$i++)
         {
                $tmp[mysql_fieldname($result,$i)]=$data[$i];
        }
        return $tmp;
}
/////////////////////////////////////////////////////////////////
// GET Uploaded file information                               //
/////////////////////////////////////////////////////////////////
function getFilename($field)
{
        global $HTTP_POST_FILES;
        return $HTTP_POST_FILES[$field]['name'];
}
function getFileMimeType($field)
{
        global $HTTP_POST_FILES;
        return $HTTP_POST_FILES[$field]['type'];
}
function getFileSize($field)
{
        global $HTTP_POST_FILES;
        return $HTTP_POST_FILES[$field]['size'];
}

/////////////////////////////////////////////////////////////////
// Enhanced Function                                           //
/////////////////////////////////////////////////////////////////

// If-Else shortcut
function cif($c1,$c2,$c3)
{
         $co='';eval("if($c1){ \$co=1;}");
         if($co){return $c2;}else { return $c3;}
}
?>