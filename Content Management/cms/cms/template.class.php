<?
class Template {
   var $template;
   function load($filepath) {
      $this->template = file_get_contents($filepath);
   }
   function replace($var, $content) {
      //$this->template = preg_replace("/#$var#/", "$content", $this->template);
      $this->template = str_replace("#$var#", $content, $this->template);
   }
   function publish() {
      $this->template = ereg_replace("<\?[^>]*>","",$this->template);
      eval("?>".$this->template."<?");
   }
}
?>
