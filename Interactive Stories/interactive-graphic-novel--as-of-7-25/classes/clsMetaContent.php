<?php
class Meta{
   Function metadata($ptitle){
   
   if(empty($ptitle)){
      $description = $this->description;
   } else {
      $description = "$ptitle - $this->description";
   }

   $keywords = strtolower($ptitle);
   $keywords = str_replace("  ", " ", $keywords);
   $meta_words = str_replace(" ", ", ", $keywords);
   
   $meta = "";
   if(!$ptitle){
      $meta = "<TITLE>$this->sitename - ".
      $meta .= "$this->slogan</TITLE>\n";
   } else {
      $meta = "<TITLE>$this->sitename: ".
      $meta .= "$ptitle </TITLE>\n";
   }
   
   $meta .= "<META NAME=\"KEYWORDS\" CONTENT=\"$meta_words, $this->keywords2\">\n";
   $meta .= "<META NAME=\"DESCRIPTION\" CONTENT=\"$this->description\">\n";
   $meta .= "<META NAME=\"ROBOTS\" CONTENT=\"INDEX, Follow\">\n";
   $meta .= "<META NAME=\"AUTHOR\" CONTENT=\"$this->company_name\">\n";
   $meta .= "<META NAME=\"REVISITE-AFTER\" CONTENT=\"2 DAYS\">\n";
   
   return $meta;
   }    
}
?>