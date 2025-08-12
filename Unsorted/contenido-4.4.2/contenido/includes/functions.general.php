<?php

/*****************************************
*
* $Id: functions.general.php,v 1.22.2.1 2003/10/28 12:38:47 timo.hummel Exp $
*
* File      :   $RCSfile: functions.general.php,v $
* Project   :   Contenido
* Descr     :   Defines the general
*               contenido functions
*
* Author    :   $Author: timo.hummel $
* Modified  :   $Date: 2003/10/28 12:38:47 $
*
* © four for business AG, www.4fb.de
******************************************/

/**
 * Extracts the available content-
 * types from the database
 *
 * Creates an array $a_content[type][number] = content string
 * f.e. $a_content['CMS_HTML'][1] = content string
 * Same for array $a_description
 *
 * @param int $idartlang Language specific ID of the arcticle
 * @return void
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG
 */
function getAvailableContentTypes($idartlang)
{
    global $db, $cfg, $a_content, $a_description;

    $sql = "SELECT
                *
            FROM
                ".$cfg["tab"]["content"]." AS a,
                ".$cfg["tab"]["art_lang"]." AS b,
                ".$cfg["tab"]["type"]." AS c
            WHERE
                a.idtype    = c.idtype AND
                a.idartlang = b.idartlang AND
                b.idartlang = '".$idartlang."'";

    $db->query($sql);
    
    while ($db->next_record())
    {
        $a_content[$db->f("type")][$db->f("typeid")] = urldecode($db->f("value"));
        $a_description[$db->f("type")][$db->f("typeid")] = i18n($db->f("description"));
    }

}

/**
 * Checks if an article is assigned
 * to multiple categories
 *
 * @param Int Article-Id ($idart)
 * @param Int Client-Id
 * @return Bool Article assigned to multiple categories
 * @access Public
 */
function isArtInMultipleUse($idart)
{
    global $cfg, $client;
    
    $db = new DB_Contenido;
    $sql = "SELECT idart FROM ".$cfg["tab"]["cat_art"]." WHERE idart = '".$idart."'";
    $db->query($sql);
    
    return ($db->affected_rows() > 1);
}

/**
 * Checks if a value is alphanumeric
 *
 * @param Mixed Value to test
 * @param Bool [Use german Umlaute] Optional
 * @return Bool Value is alphanumeric
 */
function is_alphanumeric($test, $umlauts = true) {
	
	if ($umlauts == true)
	{
		$match = "/^[a-z0-9ÄäÖöÜü ]+$/i";		
	} else {
		$match = "/^[a-z0-9 ]+$/i";
	}
	
	return (preg_match($match, $test));
}

function getCanonicalMonth($month)
{
    switch ($month)
    {
        case 1:     return(i18n("January"));break;
        case 2:     return(i18n("February"));break;
        case 3:     return(i18n("March"));break;
        case 4:     return(i18n("April"));break;
        case 5:     return(i18n("May"));break;
        case 6:     return(i18n("June"));break;
        case 7:     return(i18n("July"));break;
        case 8:     return(i18n("August"));break;
        case 9:     return(i18n("September"));break;
        case 10:    return(i18n("October"));break;
        case 11:    return(i18n("November"));break;
        case 12:    return(i18n("December"));break;
    }
}

function getParentAreaId($area)  {

    global $client, $lang, $cfg, $sess;

    $db = new DB_Contenido;
    
    if (is_numeric($area))
    {
        $sql = "SELECT
                    b.name
                FROM
                    ".$cfg["tab"]["area"]." AS a, 
                    ".$cfg["tab"]["area"]." AS b
                WHERE
                    a.idarea = '".$area."' AND
                    b.name = a.parent_id";
    } else {
        $sql = "SELECT
                    b.name
                FROM
                    ".$cfg["tab"]["area"]." AS a, 
                    ".$cfg["tab"]["area"]." AS b
                WHERE
                    a.name = '".$area."' AND
                    b.name = a.parent_id";



    }
    $db->query($sql);
    
    if ($db->next_record()) {
        return $db->f(0);
    } else {
        return $area;

    }

}

/**
 * Write JavaScript to mark
 *
 * @param int $menuitem Which menuitem to mark
 * @param bool $return Return or echo script
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function markSubMenuItem($menuitem, $return = false) {

   $str  =

   '<script type="text/javascript">

        /* Check if submenuItem is existing
           and mark it */
           
        if ( parent.frames["right_top"].document.getElementById("c_'.$menuitem.'") ) {
            menuItem = parent.frames["right_top"].document.getElementById("c_'.$menuitem.'");
            parent.frames["right_top"].sub.click(menuItem);
        }
        
    </script>';
    
    if ($return) {
        return $str;
        
    } else {
        echo $str;
        
    }
}

/**
 * Redirect to main area
 *
 * @param bool $send Redirect Yes/No
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function backToMainArea($send) {

    if ($send) {

        /* Global vars */
        global $area, $cfg, $db, $sess, $idart, $idcat, $idartlang, $idcatart, $frame;

        /* Get main area */
        $sql = "SELECT
                    a.name
                FROM
                    ".$cfg["tab"]["area"]." AS a,
                    ".$cfg["tab"]["area"]." AS b
                WHERE
                    b.name      = '".$area."' AND
                    b.parent_id = a.name";

        $db->query($sql);
        $db->next_record();

        $parent = $db->f("name");

        /* Create url string */
        $url_str = 'main.php?'.
                   'area='.$parent.'&'.
                   'idcat='.$idcat.'&'.
                   'idart='.$idart.'&'.
                   'idartlang='.$idartlang.'&'.
                   'idcatart='.$idcatart.'&'.
                   'force=1&'.
                   'frame='.$frame;

        $url = $sess->url($url_str);

        /* Redirect */
        header("location: $url");

    }

}




function showLocation($area) {
        global $db;
        global $cfgPath,$lngArea;
        global $cfg;
        global $belang;

        //Create new xml Class and load the file

        $xml = new XML_doc;
        if ($xml->load($cfg['path']['xml'] . $cfg['lang'][$belang]) == false)
        {
        	if ($xml->load($cfg['path']['xml'] . 'lang_en_US.xml') == false)
        	{
        		die("Unable to load any XML language file");
        	}
        }

        $sql="SELECT location
              FROM ".$cfg["tab"]["area"]." as A, ".$cfg["tab"]["nav_sub"]." as B
              Where A.name='$area' AND A.idarea=B.idarea AND A.online='1'";

        $db->query($sql);
        if($db->next_record()){

              echo "<b>".$xml->valueOf($db->f("location"))."</b>";

        }else{

              $sql="SELECT parent_id
                    FROM ".$cfg["tab"]["area"]."
                    WHERE name='$area' AND online='1'";
              $db->query($sql);
              $db->next_record();
              $parent=$db->f("parent_id");

              $sql="SELECT location
                    FROM ".$cfg["tab"]["area"]." as A, ".$cfg["tab"]["nav_sub"]." as B
                    Where A.name='$parent' AND A.idarea = B.idarea AND A.online='1'";

              $db->query($sql);
              $db->next_record();
              echo "<b>".$xml->valueOf($db->f("location")).$lngArea[$area]."</b>";


        }


}

function showActions($area) {
        global $cfgPathInc;
        $tmp = "include(\"".$HTTP_HOST.$cfgPathInc."actions_".$area.".php\");";
        echo ($tmp);
}

function showTable($tablename) {
        global $db;

        $sql = "SELECT * FROM $tablename";
        $db->query($sql);
        while($db->next_record())
        {
                while (list($key, $value) = each($db->Record))
                {
                        print(is_string($key) ? "<b>$key</b>: $value | " : "");
                }
                print("<br>");
        }

}

function getLanguagesByClient($client) {
        global $db;
        global $cfg;

        $sql = "SELECT idlang FROM ".$cfg["tab"]["clients_lang"]." WHERE idclient='$client'";
        $db->query($sql);
        while($db->next_record())
        {
                $list[]=$db->f("idlang");
        }

        return $list;
}

function getLanguageNamesByClient($client) {
        global $db;
        global $cfg;

        $sql = "SELECT 
                    a.idlang AS idlang,
                    b.name AS name
                FROM
                  ".$cfg["tab"]["clients_lang"]." AS a,
                  ".$cfg["tab"]["lang"]." AS b
                WHERE
                    idclient='$client' AND
                    a.idlang = b.idlang
                ORDER BY
                    idlang ASC";

        $db->query($sql);
        while($db->next_record())
        {
                $list[$db->f("idlang")]=$db->f("name");
        }

        return $list;
}


function set_magic_quotes_gpc(&$code) {
        if (get_magic_quotes_gpc() == 0) $code = addslashes($code);

}



function showareas($mainarea){

         global $area_tree;
         global $sess;
         global $cfg;
         $db=new DB_Contenido;

         //if $area_tree for this area is not register
         if(!isset($area_tree[$mainarea])){
                 $sess->register("area_tree");
                 //check which subareas are there and write them in the array
                 $sql="SELECT idarea FROM ".$cfg["tab"]["area"]." WHERE parent_id='$mainarea' OR idarea='$mainarea'";
                 $db->query($sql);
                 $area_tree[$mainarea]=array();
                 while($db->next_record()){
                       $area_tree[$mainarea][]=$db->f("idarea");
                 }


         }


}




function SaveKeywordsforart($keycode, $idart, $place, $lang) {

    global $db, $cfg, $keyword_out;

    $keywords = array();
    $keyold   = array();

if (is_array($keycode)) {

    foreach($keycode as $key => $value){

          foreach($value as $key =>$code){


                   $code=StripSlashes($code);
                   $code=strip_tags($code);
                   $code=strtolower($code);

                   //füge zeichen und zahlen zum ausschluss hinzu
                   for($a=33;$a<65;$a++){
                    $out1[]=chr($a);

                   }
                   for($a=91;$a<97;$a++){
                          $out1[]=chr($a);

                   }
                   for($a=123;$a<192;$a++){
                          $out1[]=chr($a);

                   }


                   $code=str_replace($out1," ",$code);



                   //entferne alle Zeichn,Zahlen,Worte die nicht enthalten sein sollen.

                   if (is_array($keyword_out[$lang])) {
                       foreach($keyword_out[$lang] as $value){
                               $code=preg_replace("/\b$value\b/"," ",$code);
                       }
                    }

                   //entferne einzelne Buchstaben
                   $code=preg_replace("/\b[a-z]\b/"," ",$code);



                   //zerlege den String in einen Array
                   if($code!=""){

                         $arraycode=preg_split("/[\s,]+/",trim($code));
                         rsort($arraycode);



                         $before=$arraycode[0];
                         $key1_count=0;
                         foreach($arraycode as $key =>$value){
                            if($value!=""){
                                $value=trim($value);

                                if($before==$value){

                                      $key1_count++;
                                       // for the last one
                                      if($key+1==sizeof($arraycode)){
                                               if(in_array($value,array_keys($keywords))){
                                                     $keywords[$value]+=$key1_count;
                                               }else{
                                                     $keywords[$value]=$key1_count;
                                               }
                                      }

                                }else{

                                      if(in_array($before,array_keys($keywords))){

                                               $keywords[$before]+=$key1_count;
                                      }else{

                                               $keywords[$before]=$key1_count;
                                      }
                                      // for the last one
                                      if($key+1==sizeof($arraycode)){
                                               if(in_array($value,array_keys($keywords))){
                                                     $keywords[$value]+=1;
                                               }else{
                                                     $keywords[$value]=1;
                                               }
                                      }
                                      $before=$value;
                                      $key1_count=1;
                                }

                            }//end if

                         }
                         unset($code);
                         unset($arraycode);
                   }
          }

    } // end foreach

} // end if is_array



//is keyword set?
$keys=implode("','",array_keys($keywords));
$sql="SELECT keyword,auto,self FROM ".$cfg["tab"]["keywords"]." WHERE idlang='$lang' AND keyword IN ('$keys') OR $place REGEXP '&$idart='";
$db->query($sql);
$keyold=array();
while ($db->next_record()) {
      $abschuss=false;
      $keyold[$db->f("keyword")]=$db->f($place);
      if($place=="auto"){
           if($db->f("self")==""){
              $abschuss=true;
           }
      }else{
           if($db->f("auto")==""){
              $abschuss=true;
           }
      }


}



// Delete Keywords which are not in use

$arraydel=array_diff(array_keys($keyold),array_keys($keywords));




foreach($arraydel as $delkeyword){


     $key_set1=preg_replace("/&$idart=[0-9]+&*/","&",$keyold[$delkeyword]);

     if($key_set1!="&"&&$key_set1!=""&&($key_set1!=$keyold[$delkeyword])){
        if(substr($key_set1,-1)=="&"){
               $key_set1=substr($key_set1,0,-1);
        }

        $sql = "UPDATE ".$cfg["tab"]["keywords"]." SET $place='$key_set1' WHERE idlang='$lang' AND keyword='$delkeyword'";
        $db->query($sql);
     }elseif($abschuss){

        $sql = "DELETE FROM ".$cfg["tab"]["keywords"]." WHERE idlang='$lang' AND keyword='$delkeyword'";
        $db->query($sql);
     }
}

//Save/update new Keywords
foreach($keywords as $keyword =>$key_count){
      $flg=0;
      //if keyword is already in DB -> update the place
      if(isset($keyold[$keyword])){
             //split the old place
             $key_tmp1=substr($keyold[$keyword],1);
             $key_tmp1 = split("&", $key_tmp1);


             foreach($key_tmp1 as $key1 => $value1) {
                     // explode the place in idart and count
                     $key_tmp2 = explode("=", $value1);
                     //if idart is mentioned replace the old one
                     if($key_tmp2[0]==$idart){
                        //if the old count ist similar continue
                        if($key_tmp2[1]==$key_count){

                                continue 2;
                        }else{


                        $key_tmp1[$key1]="$idart=$key_count";
                        $flg=1;
                        }
                     }

             }

             if($flg==0){
                  $update="&$idart=$key_count";
             }else{
                  $update="";
             }


             //set the new place string
             $key_set="&".implode("&",$key_tmp1).$update;

             $key_set=str_replace("&&","&",$key_set);


             $sql = "UPDATE ".$cfg["tab"]["keywords"]." SET $place='$key_set' WHERE idlang='$lang' AND keyword='$keyword'";
             $db->query($sql);


      }else{

			 $nextid = $db->nextid($cfg["tab"]["keywords"]);
			 
             $sql = "INSERT INTO ".$cfg["tab"]["keywords"]." (keyword,$place,idlang, idkeyword) VALUES ('$keyword','&$idart=$key_count',$lang,$nextid)";
             $db->query($sql);

      }


}
}

function fakeheader($time){
        global $con_time0;
        if(!isset($con_time0)){
            $con_time0=$time;
        }

        if ($time >= $con_time0 + 1000) {
            $con_time0 = $time;
            header('X-pmaPing: Pong');
        } // end if
}

function recursive_copy ($from_path, $to_path)
{
    mkdir($to_path, 0777); 
    $old_path = getcwd();
    $this_path = getcwd(); 

    if (is_dir($from_path))
    { 
        chdir($from_path); 
        $myhandle=opendir('.'); 

        while (($myfile = readdir($myhandle))!==false)
        { 
            if (($myfile != ".") && ($myfile != ".."))
            { 
                if (is_dir($myfile))
                { 
                    recursive_copy ($from_path.$myfile."/", $to_path.$myfile."/"); 
                    chdir($from_path); 
                } 
                if (is_file($myfile))
                {
                    copy($from_path.$myfile, $to_path.$myfile); 
                } 
            } 
        } 
        closedir($myhandle); 
    }

    chdir($old_path);
    return;
}

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
} 

/* Small hack to clean up unused sessions.
   As we are probably soon rewriting the
   session management, this hack is OK. */
function cleanupSessions (){
	global $cfg;
	
	$db = new DB_Contenido;
	$db2 = new DB_Contenido;

	$col = new InUseCollection;
	
	$temp = new Contenido_Challenge_Crypt_Auth;
	
	$maxdate = date("YmdHis",time() - ($temp->lifetime * 60));

	$sql = "SELECT changed, sid FROM ".$cfg["tab"]["phplib_active_sessions"];
	$db->query($sql);
	
	while ($db->next_record())
	{
		if ($db->f("changed") < $maxdate)
		{
			$sql = "DELETE FROM ".$cfg["tab"]["phplib_active_sessions"]." WHERE sid = '".$db->f("sid")."'";
			$db2->query($sql);
			$col->removeSessionMarks($db->f("sid"));
		}
	}
	
	$col->select();
	
	while ($c = $col->next())
	{
		$sql = "SELECT sid FROM ".$cfg["tab"]["phplib_active_sessions"]." WHERE sid = '".$c->get("session")."'";
		$db2->query($sql);
		if (!$db2->next_record())
		{
			$col->delete($c->get("idinuse"));
		}
	}
}

function isGroup ($uid)
{
	$users = new User;
	
	if ($users->loadUserByUserID($uid) == false)
    {
    	return true;
    } else {
	    return false;
    }
}

function getGroupOrUserName ($uid)
{
	$users = new User;

	if ($users->loadUserByUserID($uid) === false)
    {
    	$groups = new Group;
    	/* Yes, it's a group. Let's try to load the group members! */
    	if ($groups->loadGroupByGroupID($uid) === false)
    	{
    		return false;
    	} else {
    		return substr($groups->getField("groupname"),4);
    	}
    } else {
    	return $users->getField("realname");
    }
}

/**
 * getPhpModuleInfo - parses phpinfo() output
 *
 * parses phpinfo() output
 * (1) get informations for a specific module (parameter $modulname)
 * (2) get informations for all modules (no parameter for $modulname needed)
 *
 * if a specified extension doesn't exists or isn't activated an array will be returned:
 * Array
 *     (
 *          [error] => extension is not available
 *     )
 * 
 *
 * to get specified information on one module use (1):
 * getPhpModuleInfo($moduleName = 'gd');
 * 
 * to get all informations use (2):
 * getPhpModuleInfo($moduleName);
 * 
 * 
 * EXAMPLE OUTPUT (1):
 * Array
 * (
 *    [GD Support] => Array
 *        (
 *            [0] => enabled
 *         )
 * ...
 * )
 * 
 * 
 * EXAMPLE OUTPUT (2):
 * Array
 * (
 *     [yp] => Array
 *         (
 *              [YP Support] => Array
 *                  (
 *                      [0] => enabled
 *                   )
 * 
 *         )
 * ...
 * }
 *
 * foreach ($moduleSettings as $setting => $value)
 * $setting contains the modul settings
 * $value contains the settings as an array ($value[0] => Local Value && $value[1] => Master Value)
 *
 * @param $modulName string specify modul name or if not get all settings
 * 
 * @return array see above for example		
 * @author Marco Jahn
 */
function getPhpModuleInfo($moduleName) {
	
	ob_start();
	phpinfo(INFO_MODULES); // get information vor modules
	$string = ob_get_contents();
	ob_end_clean();

	$pieces = explode("<h2", $string); // get several modules

	foreach($pieces as $val)
	{
		// perform a regular expression match on every module header
		preg_match("/<a name=\"module_([^<>]*)\">/", $val, $sub_key);

		// perform a regular expression match on tabs with 2 columns
		preg_match_all("/<tr[^>]*>
		<td[^>]*>(.*)<\/td>
		<td[^>]*>(.*)<\/td>/Ux", $val, $sub);
		
		// perform a regular expression match on tabs with 3 columns		
		preg_match_all("/<tr[^>]*>
		<td[^>]*>(.*)<\/td>
		<td[^>]*>(.*)<\/td>
		<td[^>]*>(.*)<\/td>/Ux", $val, $sub_ext);

		if (isset($moduleName)) // if $moduleName is specified
		{
			if (extension_loaded($moduleName)) //check if specified extension exists or is loaded
			{
				if ($sub_key[1] == $moduleName) { //create array only for specified $moduleName
					foreach($sub[0] as $key => $val)
					{
						$moduleSettings[strip_tags($sub[1][$key])] = array(strip_tags($sub[2][$key]));
					}
				}
			}
			else //specified extension is not loaded or doesn't exists
			{
				$moduleSettings['error'] = 'extension is not available';	
			}
		}
		else // $moduleName isn't specified => get everything
		{
			foreach($sub[0] as $key => $val)
			{
				$moduleSettings[$sub_key[1]][strip_tags($sub[1][$key])] = array(strip_tags($sub[2][$key]));
			}

			foreach($sub_ext[0] as $key => $val)
			{
				$moduleSettings[$sub_key[1]][strip_tags($sub_ext[1][$key])] = array(strip_tags($sub_ext[2][$key]), strip_tags($sub_ext[3][$key]));
			}	
		}
	}
	return $moduleSettings;
}


function isValidMail($email)
{
	return preg_match ("/^[0-9a-z]([-_.]*[0-9a-z]*)*@[a-z0-9-]+.([a-z])/i",$email);
}

function htmldecode($string) {
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   $ret = strtr ($string, $trans_tbl);
   return preg_replace('/&#(\d+);/me',
      "chr('\\1')",$ret);
}

function rereadClients ()
{
	global $cfgClient;
	global $errsite_idcat;
	global $errsite_idart;
	global $db;
	global $cfg;
	
	$sql = "SELECT
                idclient,
                frontendpath,
                htmlpath,
                errsite_cat,
                errsite_art
            FROM
            ".$cfg["tab"]["clients"];

    $db->query($sql);

    while ($db->next_record())
    {
    
            $cfgClient["set"] = "set";
            $cfgClient[$db->f("idclient")]["path"]["frontend"] = $db->f("frontendpath");
            $cfgClient[$db->f("idclient")]["path"]["htmlpath"] = $db->f("htmlpath");
            $errsite_idcat[$db->f("idclient")] = $db->f("errsite_cat");
            $errsite_idart[$db->f("idclient")] = $db->f("errsite_art");
            
            $cfgClient[$db->f("idclient")]["images"] = $db->f("htmlpath")."images/";
            $cfgClient[$db->f("idclient")]["upload"] = "upload/";

            $cfgClient[$db->f("idclient")]["htmlpath"]["frontend"] = $cfgClient[$db->f("idclient")]["path"]["htmlpath"];
            $cfgClient[$db->f("idclient")]["upl"]["path"] = $cfgClient[$db->f("idclient")]["path"]["frontend"]."upload/";
            $cfgClient[$db->f("idclient")]["upl"]["htmlpath"] = $cfgClient[$db->f("idclient")]["htmlpath"]["frontend"]."upload/";
            $cfgClient[$db->f("idclient")]["upl"]["frontendpath"] = "upload/";
            $cfgClient[$db->f("idclient")]["css"]["path"] = $cfgClient[$db->f("idclient")]["path"]["frontend"] . "css/";
            $cfgClient[$db->f("idclient")]["js"]["path"] = $cfgClient[$db->f("idclient")]["path"]["frontend"] . "js/";

        }
        

}
?>
