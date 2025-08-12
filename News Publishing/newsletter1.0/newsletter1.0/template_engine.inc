<?php
/**
*    @project:        template_engine
*    @version:        1.0
*    @description:    simple template engine
*    @author:         Konstantin Atanasov
*
*
NO WARRANTY
 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, 
 THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES 
 PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
 THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. 
 THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, 
 REPAIR OR CORRECTION.
*/

/**
*    syntax:     [variableName]     - replace variableName with value of php variable;
*                <?php ?>           - can use php code , all code in template is inculded in php
*                                     script which load template.         
*/




class template_engine {
    
        var
            $variables,
            $commandParser, 
            $templatePath;
    /**
    *
    *
    */
    function template_engine($templatePath = "") {
        $this->variables = get_defined_vars();
        if (defined("TEMPLATES_PATH")) {
             $this->templatePath = TEMPLATES_PATH;
        } else {
            $this->templatePath = $templatePath;
        }
        
        return this;
    }


    
    /**
    *    simple teplate parse function 
    *
    */
    function compile_template($templateFileName) {
        $fullFileName = $this->templatePath . $templateFileName;
      //  ECHO $fullFileName;
        if ( !file_exists($fullFileName))    {
	    ECHO "<b> template  file '$templateFileName' does not exist.";
            return;
	}
	ob_start();
	include($fullFileName);
	$result = ob_get_contents();
     
        $result = $this->parse($result); // parse 
	ob_end_clean();
      
	return $result;
    }


    /**
    *    parse template
    *
    */
    function parse($text) {
         $result = $text;
         if(preg_match_all("'[\[](.*?)[\]]'si",$text,$maches)) {
             print_r($maches);
             foreach($maches[1] as $variableName) {
                 ECHO $variableName;
                $value = $GLOBALS[$variableName];
                ECHO $value;
                $result = preg_replace("(\[$variableName\])",$value,$result);
             }
            
 
            
         }
         return $result;
    }
    
    /**
    *    Parse template file
    *
    */
    function parseFile($fileName) {
        $text = file_get_contents($fileName);
        return $this->parse($text);
    }






} //end class
?>
