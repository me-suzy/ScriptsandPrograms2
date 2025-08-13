<? if(!defined('CONFIG')) die;

/*

 * Obtained from

 *	http://www.thewebmasters.net/php/

 *	http://clubpro.spb.ru/projects/fasttemplate.html

 *

 * @(#)Class.Fasttemplate.php3   1.1.1 2002/05/10

 * Original Perl module CGI::FastTemplate by Jason Moore jmoore@sober.com

 * PHP3 port by CDI cdi@thewebmasters.net

 * PHP3 Version Copyright (c) 1999 CDI, cdi@thewebmasters.net,

 * All Rights Reserved.

 * Perl Version Copyright (c) 1998 Jason Moore jmoore@sober.com.

 * All Rights Reserved.

 * This program is free software; you can redistribute it and/or modify it

 * under the GNU General Artistic License, with the following stipulations:

 * Changes or modifications must retain these Copyright statements. Changes

 * or modifications must be submitted to both AUTHORS.

 * This program is released under the General Artistic License.

 * This program is distributed in the hope that it will be useful,

 * but WITHOUT ANY WARRANTY; without even the implied warranty of

 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the Artistic

 * License for more details. This software is distributed AS-IS.

 *

 * AiK' modifications:

 *  -- more strict dynamic templates handling, including "silently removing"

 * of unassigned  dynamic blocks

 *  -- showDebugInfo() method that print into html conole some debug info

 *

 *Random's modificaitons:

 * --add truely dynamic blocks - removed old dynamic templates

 * --simplified several parts

 */



 /**

  * The <code>FastTemplate</code> class provides easy and quite fast

  * template handling functionality.

  * @author Jason Moore jmoore@sober.com.

  * @author CDI cdi@thewebmasters.net

  * @author Artyem V. Shkondin aka AiK artvs@clubpro.spb.ru

  * @version 1.1.2

  */





class FastTemplate {

    var $FILELIST   =   array();    //  Holds the array of filehandles

                                    //  FILELIST[HANDLE] == "fileName"



    var $PARSEVARS  =   array();    //  Holds the array of Variable

                                    //  handles.

                                    //  PARSEVARS[HANDLE] == "value"



    var $PARSED     =   array();    //  Holds the array of Parsed

                                    //  files.

                                    //  PARSED[HANDLE] == "parsed template"



    var $SOURCE     =   array();    //  Holds the array of Sources

                                    //  files or spliced or dynamic text

                                    //  SOURCE[HANDLE] == "soucre template"



    var $LOADED     =   array();    //  We only want to load a template

                                    //  once - when it's used.

                                    //  LOADED[FILEHANDLE] == 1 if loaded

                                    //  undefined if not loaded yet.



    var $HANDLE     =   array();    //  Holds the handle names assigned

                                    //  by a call to parse()



    var $ROOT       =   "";         //  Holds path-to-templates



    var $WIN32      =   false;      //  Set to true if this is a WIN32 server



    var $ERROR      =   "";         //  Holds the last error message



    var $LAST       =   "";         //  Holds the HANDLE to the last

                                    //  template parsed by parse()



    var $STRICT     =   true;       //  Strict template checking.

                                    //  Unresolved vars in templates will

                                    //  generate a warning when found.

//by AiK

    var $start;                     // holds time of start generation





    /**

     * Constructor.

     * @param $pathToTemplates template root.

     * @return FastTemplate

     */

    function FastTemplate ($pathToTemplates = ""){

        global $php_errormsg;



        if(!empty($pathToTemplates)){

            $this->set_root($pathToTemplates);

        }

        // by AiK

        $this->start = $this->utime();

    }   // end (new) FastTemplate ()





   /**

    * Sets template root

    * All templates will be loaded from this "root" directory

    * Can be changed in mid-process by re-calling with a new

    * value.

    * @param $root path to templates dir

    * @return void

    */



    function set_root ($root){

        $trailer = substr($root,-1);



        if(!$this->WIN32){

            if( (ord($trailer)) != 47 ){

                $root = "$root". chr(47);

            }

            if(is_dir($root)){

                $this->ROOT = $root;

            }else{

                $this->ROOT = "";

                $this->error("Specified ROOT dir [$root] is not a directory");

            }

        }else{

            // WIN32 box - no testing

            if( (ord($trailer)) != 92 ){

                $root = "$root" . chr(92);

            }

            $this->ROOT = $root;

        }



    }   // End set_root()





   /**

    * Calculates current microtime

    * I throw this into all my classes for benchmarking purposes

    * It's not used by anything in this class and can be removed

    * if you don't need it.

    * @return void

    */



    function utime () {

        $time = explode( " ", microtime());

        $usec = (double)$time[0];

        $sec = (double)$time[1];

        return $sec + $usec;

    }



   /**

    * Strict template checking, if true sends warnings to STDOUT when

    * parsing a template with undefined variable references

    * Used for tracking down bugs-n-such. Use no_strict() to disable.

    * @return void

    */



    function strict (){

        $this->STRICT = true;

    }



   /**

    * Silently discards (removes) undefined variable references

    * found in templates

    * @return void

    */

    function no_strict (){

        $this->STRICT = false;

    }



    /**

    * A quick check of the template file before reading it.

    * This is -not- a reliable check, mostly due to inconsistencies

    * in the way PHP determines if a file is readable.

    * @return boolean

    */

    function is_safe ($filename){

        if(!file_exists($filename)){

            $this->error("[$filename] does not exist",0);

            return false;

        }

        return true;

    }



   /**

    * Grabs a template from the root dir and

    * reads it into a (potentially REALLY) big string

    * @param $template template name

    * @return string

    */

    function get_template ($template, $key){global $loc_tpl;

        if(empty($this->ROOT)){

            $this->error("Cannot open template. Root not valid.",1);

            return false;

        }



        if(substr($template, 0, 1) == "/"){ //special cmd to use diff template or cache

         //migh add verifcation later

         $filename = substr($template, 1); //remove extra /

        } else $filename = $this->ROOT . $template; //added default location of pages



        $contents = @implode("",(@file($filename)));

        if( (!$contents) or (empty($contents)) ){

            $this->error("get_template() failure: [$filename] $key $template $php_errormsg",1);

        }

        return $contents;

    } // end get_template



   /**

    * Prints the warnings for unresolved variable references

    * in template files. Used if STRICT is true

    * @param $Line string for variable references checking

    * @return void

    */

    function show_unknowns ($Line){

        $unknown = array();

        if (ereg("({[A-Z0-9_]+})",$Line,$unknown)) {

            $UnkVar = $unknown[1];

            if(!(empty($UnkVar))){

                @error_log("[FastTemplate] Warning: no value found for variable: $UnkVar ",0);

            }

        }

    }   // end show_unknowns()



   /**

    * This routine get's called by parse() and does the actual

    * {VAR} to VALUE conversion within the template.

    * @param $template string to be parsed

    * @param $tpl_array array of variables

    * @return string

    * @author CDI cdi@thewebmasters.net

    * @author Artyem V. Shkondin artvs@clubpro.spb.ru

    * @version 1.1.1

    */

    function parse_template ($template, $tpl_array){

      //run required conversions for each

      foreach($tpl_array as $key => $val)

         if($key != ''){

             if(gettype($val) != "string") settype($val,"string");



             $keys[] = "{".$key."}";

	     $vals[] = $val;

	 }



      //use str_replaces internal functions to speed up all replaces

      return str_replace($keys, $vals, $template);



    }   // end parse_template();



    /**

     *  The meat of the whole class. The magic happens here.

     *  @param  $ReturnVar template handle

     *  @param  $template nick name

     *  @return void

     */



    function parse ( $ReturnVar, $FileTags, $append = false, $assign = array()){

    	if(is_array($append)){

    	 $assign = $append;

    	 $append = false;

    	} $this->assign($assign);



        $this->LAST = $ReturnVar;

        $this->HANDLE[$ReturnVar] = 1;

        //echo "startparse $ReturnVar";

        if(is_array($FileTags)){

            unset($this->PARSED[$ReturnVar]);   // Clear any previous data



            while ( list ( $key , $val ) = each ( $FileTags ) ) {

                if ( (!isset($this->SOURCE[$val])) || (empty($this->SOURCE[$val])) ) {

                    $this->LOADED[$val] = 1;

                    $this->SOURCE[$val] = $this->get_template($this->FILELIST[$val], $key);

                }

                //  Array context implies overwrite

                $this->PARSED[$ReturnVar] = $this->parse_template($this->SOURCE[$val],$this->PARSEVARS);

                //  For recursive calls.

                $this->assign( array( $ReturnVar => $this->PARSED[$ReturnVar] ) );

            }

        }   // end if FileTags is array()

        else{

            // FileTags is not an array



            $val = $FileTags;



            if( (substr($val,0,1)) == '.' ){

                // Append this template to a previous ReturnVar



                $append = true;

                $val = substr($val,1);

            }

            if ( (!isset($this->SOURCE[$val])) || (empty($this->SOURCE[$val])) ){

                    $this->LOADED[$val] = 1;

                    $this->SOURCE[$val] = $this->get_template($this->FILELIST[$val], $ReturnVar);

            }

            if($append){

               // changed by AiK

                if (isset($this->PARSED[$ReturnVar])){

                    $this->PARSED[$ReturnVar] .= $this->parse_template($this->SOURCE[$val],$this->PARSEVARS);

                }else{

                    $this->PARSED[$ReturnVar] = $this->parse_template($this->SOURCE[$val], $this->PARSEVARS);

                }

            }else{

                   $this->PARSED[$ReturnVar] = $this->parse_template($this->SOURCE[$val], $this->PARSEVARS);



            }



            //  For recursive calls.

            $this->assign(array( $ReturnVar => $this->PARSED[$ReturnVar]) );

        }



        //allow for quick commands

        return $this->fetch($ReturnVar);

    }   //  End parse()



    /**

     * Prints parsed template

     * @param $template template handler

     * @return void

     * @see FastTemplate#fetch()

     */



    function FastPrint ( $template = "" ){

        if(empty($template)){

            $template = $this->LAST;

        }



        if( (!(isset($this->PARSED[$template]))) || (empty($this->PARSED[$template])) ) {

            $this->error("Nothing parsed, nothing printed",0);

            return;

        }else{

            $template = $this->PARSED[$template];

            print eval("?>$template<?");

        }

        return;

    }



    /**

     * Prints parsed template

     * @param $template template handler

     * @return parsed template

     * @see FastTemplate#FastPrint()

     */



//  ************************************************************



    function fetch ( $template = "" )

    {

        if(empty($template))

        {

            $template = $this->LAST;

        }

        if( (!(isset($this->PARSED[$template]))) || (empty($this->PARSED[$template])) )

        {

            //$this->error("Nothing parsed, nothing printed",0);

            //this is used to check if its there too

            return "";

        }



        return($this->PARSED[$template]);

    }



//  ************************************************************



    function define ($fileList, $file = '')

    {

        global $tpages, $loc_tpl;



        if(!is_array($fileList))

            if($file != ''){

	 	//Override Pages

	 	if(isset($tpages[$file])){

	 	        //Completly New Position

	 	        if(substr($tpages, 0, 1) == "/")

                                $file = $tpages[$file];

                        else //New Layout

                                $file = "/" . $loc_tpl . $tpages[$file] . "/" . $file;



	 	}





	 	$this->FILELIST[$fileList] = $file;

	 	return true;

	    }



        while ( list ($FileTag,$FileName) = each ($fileList) )

            $this->define($FileTag, $FileName);



        return true;

    }



//  ************************************************************



    function clear_parse ( $ReturnVar = "")

    {

        $this->clear($ReturnVar);

    }



//  ************************************************************



    function clear ( $ReturnVar = "" )

    {

        // Clears out hash created by call to parse()



        if(!empty($ReturnVar))

        {

            if( (gettype($ReturnVar)) != "array")

            {

                unset($this->PARSED[$ReturnVar]);

                return;

            }

            else

            {

                while ( list ($key,$val) = each ($ReturnVar) )

                {

                    unset($this->PARSED[$val]);

                }

                return;

            }

        }



        // Empty - clear all of them



        while ( list ( $key,$val) = each ($this->HANDLE) )

        {

            $KEY = $key;

            unset($this->PARSED[$KEY]);

        }

        return;



    }   //  end clear()



//  ************************************************************



    function clear_all ()

    {

        $this->clear();

        $this->clear_assign();

        $this->clear_define();

        $this->clear_tpl();



        return;



    }   //  end clear_all



//  ************************************************************



    function clear_tpl ($fileHandle = "")

    {

        if(empty($this->LOADED))

        {

            // Nothing loaded, nothing to clear



            return true;

        }

        if(empty($fileHandle))

        {

            // Clear ALL fileHandles



            while ( list ($key, $val) = each ($this->LOADED) )

            {

                unset($this->SOURCE[$key]);

            }

            unset($this->LOADED);



            return true;

        }

        else

        {

            if( (gettype($fileHandle)) != "array")

            {

                if( (isset($this->PARSED[$fileHandle])) || (!empty($this->PARSED[$fileHandle])) )

                {

                    unset($this->LOADED[$fileHandle]);

                    unset($this->PARSED[$fileHandle]);

                    return true;

                }

            }

            else

            {

                while ( list ($Key, $Val) = each ($fileHandle) )

                {

                    unset($this->LOADED[$Key]);

                    unset($this->PARSED[$Key]);

                }

                return true;

            }

        }



        return false;



    }   // end clear_tpl



//  ************************************************************



    function clear_define ( $FileTag = "" )

    {

        if(empty($FileTag))

        {

            unset($this->FILELIST);

            return;

        }



        if( (gettype($Files)) != "array")

        {

            unset($this->FILELIST[$FileTag]);

            return;

        }

        else

        {

            while ( list ( $Tag, $Val) = each ($FileTag) )

            {

                unset($this->FILELIST[$Tag]);

            }

            return;

        }

    }



//  ************************************************************

//  Aliased function - used for compatibility with CGI::FastTemplate

    function clear_parse2 ()

    {

        $this->clear_assign();

    }



//  ************************************************************

//  Clears all variables set by assign()



    function clear_assign ()

    {

        if(!(empty($this->PARSEVARS)))

        {

            while(list($Ref,$Val) = each ($this->PARSEVARS) )

            {

                unset($this->PARSEVARS[$Ref]);

            }

        }

    }



//  ************************************************************



    function clear_href ($href)

    {

        if(!empty($href))

        {

            if( (gettype($href)) != "array")

            {

                unset($this->PARSEVARS[$href]);

                return;

            }

            else

            {

                while (list ($Ref,$val) = each ($href) )

                {

                    unset($this->PARSEVARS[$Ref]);

                }

                return;

            }

        }

        else

        {

            // Empty - clear them all



            $this->clear_assign();

        }

        return;

    }



//  ************************************************************



    /**

     * assign variables

     */

    function assign ($tpl_array, $trailer="")

    {

        if(gettype($tpl_array) == "array")

        {

            while ( list ($key,$val) = each ($tpl_array) )

            {

                if (!(empty($key)))

                {

                    //  Empty values are allowed

                    //  Empty Keys are NOT



                    $this->PARSEVARS[$key] = $val;

                }

            }

        }

        else

        {

            // Empty values are allowed in non-array context now.

            if (!empty($tpl_array))

            {

                $this->PARSEVARS[$tpl_array] = $trailer;

            }

        }

    }



//  ************************************************************

//  Return the value of an assigned variable.

//  Christian Brandel cbrandel@gmx.de



    function get_assigned($tpl_name = "")

    {

        if(empty($tpl_name)) { return false; }

        if(isset($this->PARSEVARS[$tpl_name]))

        {

            return ($this->PARSEVARS[$tpl_name]);

        }

        else

        {

            return false;

        }

    }



//	Return the contents of a parsed file

//	Nathan 'Random' Rini @ random@tribalgames.net



	function fetchfile($tpl_name, $assign = array(), $forceclear = 0){

	 $this->parsefile($tpl_name, $tpl_name, $assign, $forceclear);

	 return $this->fetch($tpl_name); //return it

	}



//	************************************************************

//	Defines and Parses File

//	Nathan 'Random' Rini @ random@tribalgames.net



	function parsefile($parse_name = '', $tpl_name, $assign = array(), $forceclear = 0){

	 if($parse_name == '') $parse_name = $tpl_name;



	 if(!empty($assign) || $forceclear) $this->clear($tpl_name); //clear out old so it doesnt return the last

	 $this->assign($assign);



	 $this->define(array($parse_name => $tpl_name)); //define

	 $this->parse($parse_name,$parse_name); //parse

	}



//	************************************************************

//	Splices Blocked Template

//	Nathan 'Random' Rini @ random@tribalgames.net



        function splice($name, $template){

         //redeclaring splice causes evil evil evil bugs !!!

         if($this->SOURCE[$name]) return;



         $block = $this->fetchfile($template);



         //declare a template formats

 	 $block_start     = "<template name=\"";

	 $block_start_end = "\">";



	 $block_end     = "</template name=\"";

	 $block_end_end = "\">";



	 $len_block_start     = strlen($block_start);

	 $len_block_start_end = strlen($block_start_end);



	 $len_block_end     = strlen($block_end);

	 $len_block_end_end = strlen($block_end_end);



	 $var_start = "{";

	 $var_end   = "}";



	 $append  = $name . "->"; //name to append to each subblock



         //go through and find all blocks starts

	 for($i=0;$i < 9999; $i++){ //error check loop

	  $subblock[$i]["start"] = @strpos($block, $block_start,  isset($subblock[$i-1])?($subblock[$i-1]["start_end"] + $len_block_start_end):0);



	  if($subblock[$i]["start"] === FALSE){ //break when no more found

	   unset($subblock[$i]);

	   break;

	  }



	  $subblock[$i]["start_end"] = strpos($block, $block_start_end, $subblock[$i]["start"]);

	  $subblock[$i]["var"]       = substr($block, $subblock[$i]["start"] + $len_block_start, $subblock[$i]["start_end"] - ($subblock[$i]["start"] + $len_block_start));

	  $subblock[$i]["end"]       = strpos($block, $block_end . $subblock[$i]["var"] . $block_end_end , $subblock[$i]["start_end"] + $len_block_start_end);



	  if($subblock[$i]["end"] === FALSE){//make sure you have complete block - if not - error

	   @error_log("ParentTag: [$ParentTag] not loaded! - ".$template." Template ".$subblock[$i]["var"]." missing closing.",0);

	   $this->error("ParentTag: [$ParentTag] not loaded! - ".$template." Template ".$subblock[$i]["var"]." missing closing.",0);

	   return;

	  }



	  $subblock[$i]["content"]   = substr($block, $subblock[$i]["start"], ($subblock[$i]["end"] + strlen($subblock[$i]["var"]) + $len_block_end + $len_block_end_end) - $subblock[$i]["start"]);

	 }



	 //extract parent structure

	 for($i=0;$i < count($subblock);$i++)

	  for($c=0;$c < count($subblock);$c++)

	  //check if its valid

	  if($subblock[$i]["var"] != $subblock[$c]["var"] && $subblock[$c]["var"] != '')

	  //if start of subblock is farther in that the start of the block and it not after the end of the block

	  if(($subblock[$c]["start"] > ($subblock[$i]["start_end"] + $len_subblock_end)) && ($subblock[$i]["end"] > $subblock[$c]["end"]))

	   foreach($subblock as $key => $sblock)

	    if($sblock["var"] == $subblock[$c]["var"])

	     $subblock[$i]["children"][] = $key;



	 //parse

	 for($i=0;$i < count($subblock);$i++){

	  //find all parent children

	  if(is_array($subblock[$i]["children"])) //make sure it is valid

	  foreach($subblock[$i]["children"] as $child_id){ //go throught each child

	   $subblock[$child_id]["child"] = 0;

	   //check if they are a child of one of the other children

	   foreach($subblock[$i]["children"] as $id)

	    if(is_array($subblock[$id]["children"]))

	     if(in_array($child_id, $subblock[$id]["children"])){

	      $subblock[$child_id]["child"] = 1;

	      break;

	     }



           //if they are not a child of the one of the children

           if(!$subblock[$child_id]["child"]){ //add them to be replaced

	    $subblock[$i]["parents_cnt"][] = $subblock[$child_id]["content"];

	    $subblock[$i]["parents_var"][] = $var_start. $append . $subblock[$child_id]["var"] .$var_end;

	   }

	  }



          //find children that arent children of anything else

          if(!$subblock[$i]["child"]) //parse the master template

           $block = str_replace($subblock[$i]["content"],  $var_start. $name . "->" . $subblock[$i]["var"] .$var_end, $block);



          //go through and replace all parent children's txt

          $this->SOURCE[$append . $subblock[$i]["var"]] = str_replace($subblock[$i]["parents_cnt"], $subblock[$i]["parents_var"], $subblock[$i]["content"]);



          //remove block start and end

          $this->SOURCE[$append . $subblock[$i]["var"]] = str_replace(array($block_start.$subblock[$i]["var"].$block_start_end, $block_end.$subblock[$i]["var"].$block_end_end), '', $this->SOURCE[$append . $subblock[$i]["var"]]);

	 }



         //add master template

         $this->SOURCE[$name] = $block;

	}



//  ************************************************************

    //save/append template

    //temp for now

    function save($tpl, $content){

     //open folder

     $folder = new folder($GLOBALS["loc_cache"]);



     //grab file

     $file = &$folder->file($tpl);



     //set file contents

     $file->set($content);



     //save changes

     $file->save();

    }



//  ************************************************************

    //assign check

    //if data is empty return '' otherwise parse source with data name



    function assignc ($data, $name, $source)

    {

     if($data) return $this->parse($source, $source, 0, array($name => $data));

     else return "";

    }



//  ************************************************************



    function error ($errorMsg, $die = 0)

    {

        $this->ERROR = $errorMsg;

        echo "ERROR: $this->ERROR <BR> \n";

        if ($die == 1)

        {

            exit;

        }



        return;



    } // end error()





//  ************************************************************



    /**

     *  Prints debug info into console

     * @return void

     * @author AiK

     * @since 1.1.1

     */

    function showDebugInfo(){

        $tm =  $this->utime()  - $this->start;

        // print time

        print "

        <SCRIPT language=javascript>

        _debug_console = window.open(\"\",\"console\",\"width=500,height=420,resizable,scrollbars=yes, top=0 left=130\");

        _debug_console.document.write('<HTML><TITLE>Debug Console</TITLE><BODY bgcolor=#ffffff>');

        _debug_console.document.write('<h3>Debugging info: generated during $tm seconds</h3>');

        ";

        $this->printarray($this->FILELIST, "Templates");

        $this->printarray($this->PARSEVARS, "Parsed variables");



       print " _debug_console.document.close();

       </SCRIPT> ";

    }//end of showDebugInfo()



    /**

     *

     */

     function printarray($arr,$caption){

     if (count($arr)!=0){

        print "

        _debug_console.document.write('<font face=Tahoma color=#0000FF size=2><b>$caption</b> </font>');\n

        _debug_console.document.write(\"<table border=0 width=100%  cellspacing=1 cellpadding=5>\");

        _debug_console.document.write('<tr bgcolor=#CCCCCC><th> key</th><th>value</th></tr>');\n ";

        $flag=true;

         while ( list ($key,$val) = each ($arr) ){

         $flag=!$flag;

             $val=htmlchars(mysql_escape_string ($val));

         if (!$flag) {

            $color ="#EEFFEE";

         }else{

            $color ="#EFEFEF";

         }

            print "_debug_console.document.write('<tr bgcolor=$color><td> $key</td><td valign=bottom><pre>$val</pre></td></tr>');\n ";

         }

        print "_debug_console.document.write(\"</table>\");";

        }

     }



//  ************************************************************



} // End class.FastTemplate.php3



?>