<?
class htmlparser_class
{
      var $html="";
      var $ontagfound="";
      var $ontextfound="";
      var $elements=array();
 
      function InsertHTML($htmlcode)
      {
               $this->html = "";
               $this->html=$htmlcode;
               return true;
      }
 
      function LoadHTML($buffer)
      {
               $this->html = "";
               if ($buffer!="")
               {
                  $this->html.=trim($buffer);
               }
      }
 
      function GetElements(&$result)
      {
               if (count($this->elements)==0) { return false; $result=array();  }
               $result=$this->elements;
               return true;
      }
 
      function Parse()
      {
               $ignorechar = true;
               $intag = true;
               $tagdepth = 0;
               $line="";
               $text="";
               $tag="";
               if ($this->html=="")
               { return false;}
 
               $raw = split ("\r\n", $this->html);
 
               while (list($key, $line) = each ($raw))
               {
                     $htmlline = htmlentities($line);
 
                     if ($line=="") { continue; }
 
                     $line = trim($line);
                     for ($charsindex=0;$charsindex<=strlen($line);$charsindex++)
                     {
                         if ($ignorechar==true) { $ignorechar=false;}
 
                         if (($line[$charsindex]=="<") && (!$intag))
                         {
                            if ($text!="")
                            {
                               /* Found Text */
                               $this->elements[]=$text;
                               $text="";
                            }
                            $intag = true;
                         } else
                         
                         if (($line[$charsindex]==">") && ($intag))
                         {
                            $tag .=">";
                            /* Tag Found */
                            $this->elements[]=$tag;
                            $ignorechar = true;
                            $intag=false;
                            $tag="";
                         }
                         
                         if ((!$ignorechar) && (!$intag))
                         {
                             $text .= $line[$charsindex];
                         } else
                         if ((!$ignorechar) && ($intag))
                         {
 
                             $tag .= $line[$charsindex];
                         }
 
                     }
               }
               return true;
      }
 
 
 
}

?>