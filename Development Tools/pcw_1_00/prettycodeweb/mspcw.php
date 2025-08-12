<?php

/*************************************************************************************
Copyright notice

Pretty Code Web
(c) 2005 mousesoft.co.uk (phil@mousesoft.co.uk)
 *************************************************************************************/
function pcw_strpos($haystack, $needle)
{
   if($needle == "")
   {
      return false;
   }
   else
   {
      return strpos($haystack, $needle);
   }
}

function kw_colors($tmp)
{
   $kw_array = array_change_key_case($GLOBALS['pwpconfig']['keywords'],CASE_LOWER);
   $colors_array = $GLOBALS['pwpconfig']['colors'];
   $kw_color = $colors_array[$kw_array[strtolower($tmp)]];
   return "<font color='".$kw_color."'>".htmlspecialchars($tmp)."</font>";
}

function syntax_colors($tmp, $syntax, $close)
{
   $colors_array = $GLOBALS['pwpconfig']['colors'];
   $syn_color = $colors_array[$syntax];
   if($close)
   {
      return "<font color='".$syn_color."'>".htmlspecialchars($tmp)."</font>";
   }
   else
   {
      return "<font color='".$syn_color."'>".htmlspecialchars($tmp);
   }
}

function is_keyword($word){
   // Check if word is keyword and add formatting
   $ignore_case = $GLOBALS['pwpconfig']['ignore_case'];
   if($ignore_case)
   {
      if (array_key_exists(strtolower($word), array_change_key_case($GLOBALS['pwpconfig']['keywords'],CASE_LOWER)))
      {
         $word = kw_colors(htmlspecialchars($word));
      }
      return $word;
   }
   else
   {
      if (array_key_exists($word, $GLOBALS['pwpconfig']['keywords']))
      {
         $word = kw_colors(htmlspecialchars($word));
      }
      return $word;
   }
}


function process_line($line)
{
   $lngth = strlen($line);
   $comment_line = false;
   $syntax_start = pcw_strpos($line,$GLOBALS['pwpconfig']['syntax_start']);
   $syntax_end = pcw_strpos($line,$GLOBALS['pwpconfig']['syntax_end']);
   $comment_end = pcw_strpos($line,$GLOBALS['pwpconfig']['comment_end']);
   $comment_start = pcw_strpos($line,$GLOBALS['pwpconfig']['comment_start']);
   $comment_single = pcw_strpos($line,$GLOBALS['pwpconfig']['comment_single']);
   $comment_single_alt = pcw_strpos($line,$GLOBALS['pwpconfig']['comment_single_alt']);
   static $in_comment = false;
   $instring = false;
   $altstring = false;
   // For each character 
   for($i=0; $i<=($lngth); $i++)
   {
      if(!$GLOBALS['in_syntax'])
      {
         $tmp = substr($line, $i ,$lngth - $i);
         $syntax_start = pcw_strpos($tmp,$GLOBALS['pwpconfig']['syntax_start']);
         if($syntax_start !== false){$syntax_start = $syntax_start + $i;}
         if($i === $syntax_start)
         {
            $GLOBALS['in_syntax'] = true;
            for($ss=0; $ss<=(strlen($GLOBALS['pwpconfig']['syntax_start'])-1); $ss++)
            {
               $outline = $outline.htmlspecialchars($line[$i + $ss]);
            }
            $i = $i + strlen($GLOBALS['pwpconfig']['syntax_start']);
            $word = $line[$i];
         }
         else
         {
            $outline = $outline.htmlspecialchars($line[$i]);
      
         }
      }
      else
      {        
         $tmp = substr($line, $i ,$lngth - $i);
         $syntax_end = pcw_strpos($tmp,$GLOBALS['pwpconfig']['syntax_end']);
         if($syntax_end !== false){$syntax_end = $syntax_end + $i;}
         if($i === $syntax_end)
         {
            $GLOBALS['in_syntax'] = false;
            $word = is_keyword($word);
            $outline = $outline.$word;
            
            for($se=0; $se<=(strlen($GLOBALS['pwpconfig']['syntax_end'])); $se++)
            {
               $outline = $outline.htmlspecialchars($line[$i + $se]);
            }
            $i = $i + strlen($GLOBALS['pwpconfig']['syntax_end']);
         }
         else
         {
            if($in_comment)
            {
               $tmp = substr($line, $i ,$lngth - $i);
               $comment_end = pcw_strpos($tmp,$GLOBALS['pwpconfig']['comment_end']);
               if($comment_end !== false){$comment_end = $comment_end + $i;}
               if($i === $comment_end)
               {
                  $in_comment = false;
                  for($ce=0; $ce<=(strlen($GLOBALS['pwpconfig']['comment_end'])-1); $ce++)
                  {
                     $outline = $outline.htmlspecialchars($line[$i + $ce]);
                  }
                  $i = $i + strlen($GLOBALS['pwpconfig']['comment_end'])-1;
                  $outline = $outline."</font>";
               }
               else
               {
                  $outline = $outline.htmlspecialchars($line[$i]);
               }
            }
            else
            {
               $tmp = substr($line, $i ,$lngth - $i);
               $comment_start = pcw_strpos($tmp,$GLOBALS['pwpconfig']['comment_start']);
               if($comment_start !== false){$comment_start = $comment_start + $i;}
               if($i === $comment_start)
               {
                  $in_comment = true;
                  $outline = $outline.syntax_colors($line[$i],'comment',false);
               }
               else
               {     
                  //echo "processing ".$line[$i]."   $i<br>";
                  if(!$comment_line)
                  {
                     // Check if character is single comment start 
                     if(($i === $comment_single) or ($i === $comment_single_alt))
                     {
                        $comment_line = true;
                        $outline = $outline.syntax_colors(substr($line, $i, $lngth - $i),'comment',true);
                     }
                     else
                     {  
                        // Check if character is string start or end
                        if (($line[$i] == $GLOBALS['pwpconfig']['string_start']) or ($line[$i] == $GLOBALS['pwpconfig']['string_end']))
                        {
                           $instring =! $instring;
                           if ($instring and !$altstring) {$outline = $outline.syntax_colors("",'string',false);}
                           elseif(!$altstring and !$instring){$outline = $outline.htmlspecialchars($line[$i])."</font>"; $i = $i + 1;}
                           else {}
                        }
                        // Check if character is alt string start or end
                        if (($GLOBALS['pwpconfig']['alt_string_start'] > "") and (($line[$i] == $GLOBALS['pwpconfig']['alt_string_start']) or ($line[$i] == $GLOBALS['pwpconfig']['alt_string_end'])))
                        {
                           $altstring =! $altstring;
                           if ($altstring and !$instring) {$outline = $outline.syntax_colors($line[$i], 'alt_string', false);}
                           elseif(!$altstring and !$instring){$outline = $outline.htmlspecialchars($line[$i])."</font>";}
                           else {$outline = $outline.htmlspecialchars($line[$i]);}
                        }
                        // Check if we are in a text string 
                        elseif($instring or $altstring)
                        { 
                           $outline = $outline.htmlspecialchars($line[$i]);
                        }
                        // Check if character is delimiter and a bracket         
                        elseif((in_array($line[$i], $GLOBALS['pwpconfig']['delimiters']))
                        and (in_array($line[$i], $GLOBALS['pwpconfig']['brackets'])))
                        {
                           $word = is_keyword($word);
                           $outline = $outline.$word.syntax_colors($line[$i], 'bracket', true);
                           $word = "";
                        }
                        // Check if character is delimiter and an operator 
                        elseif((in_array($line[$i], $GLOBALS['pwpconfig']['delimiters']))
                        and (in_array($line[$i], $GLOBALS['pwpconfig']['operators'])))
                        {
                           $word = is_keyword($word);
                           $outline = $outline.$word.syntax_colors($line[$i], 'operator', true);
                           $word = "";
                        }
                        // Check if character is an operator 
                        elseif(in_array($line[$i], $GLOBALS['pwpconfig']['operators']))
                        {
                           $word = $word.syntax_colors($line[$i], 'operator', true);
                        }
                        // Check if character is a bracket 
                        elseif(in_array($line[$i], $GLOBALS['pwpconfig']['operators']))
                        {
                           $word = $word.syntax_colors($line[$i], 'operator', true);
                        }
                  
                        // Check if character is delimiter 
                        elseif((in_array($line[$i], $GLOBALS['pwpconfig']['delimiters'])) or ($i == $lngth))
                        {
                           $word = is_keyword($word);
                           $outline = $outline.$word.$line[$i];
                           $word = "";                      
                        }
                        else
                        {
                           $word = $word.$line[$i];
                           //echo $word."<br>";
                        }
                     }
                  }
               }
            }
         }
      }
   }
   return $outline;
}

function pcw($codefile, $config)
{
include $config;
$GLOBALS['pwpconfig']= $pwp_config;
$GLOBALS['in_syntax']= true;
if($GLOBALS['pwpconfig']['syntax_start'] ==""){$GLOBALS['in_syntax'] = true;}
   else {$GLOBALS['in_syntax'] = false;}
if (file_exists($codefile))
{
   $filename = $codefile;
   $fp = fopen($filename, "r");
   $rawcode= fread($fp, filesize($filename));
   fclose($fp);
}
else
{
   $rawcode = $codefile;
}
$newcode = str_replace(" _"."\n" , 'pwplinespanchar', $rawcode);
$newcode = str_replace($GLOBALS['pwpconfig']['string_escape'].$GLOBALS['pwpconfig']['string_start'], 'pwpescapedquote', $newcode);

$codearray = explode("\n",$newcode);

// For each line 
foreach( $codearray as $id => $line ) 
{  
   $line = & $codearray[$id];
   $lngth = strlen($line);
   
   $line = process_line($line);
   
   unset($line);
}

$finalcode = implode("<br>",$codearray);
$finalcode = str_replace('pwplinespanchar', " _"."<br>", $finalcode);
$finalcode = str_replace('pwpescapedquote', $GLOBALS['pwpconfig']['string_escape'].$GLOBALS['pwpconfig']['string_start'], $finalcode);
$finalcode = "<pre>".$finalcode."</pre>";
$finalcode = $finalcode."<p align='right'><a href='http://www.mousesoft.co.uk/index.php?pcw'><img src='".$GLOBALS['base_path']."pcw_stamp.gif' border=0></img></a></p>";
echo $finalcode;
}
?>