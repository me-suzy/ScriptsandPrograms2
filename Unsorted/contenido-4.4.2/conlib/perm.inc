<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: perm.inc,v 1.3 2002/08/16 18:16:02 eppner Exp $
 *
 */

class Contenido_Perm {

  var $classname = "Contenido_Perm";
  var $areacache = array();
  var $actioncache = array();

    function getGroupsForUser ($user)
    {
	   	  global $cfg;
      	  
      	  $db = new DB_Contenido;
      	  
      	  $sql = "SELECT group_id FROM
      	  		". $cfg["tab"]["groupmembers"] ."
    			WHERE user_id = '".$user."'";
    	  $db->query($sql);
    	  
    	  while ($db->next_record())
    	  {
    	  	$groups[] = $db->f("group_id");
    	  }
    	  
    	  return $groups;
      }
      
  function getIDForArea ($area)
  {
        global $cfg;

        $db_perm = new DB_Contenido;
        
    	if (!is_numeric($area))
	    {
	    	if (!@array_key_exists($area,$this->areacache))
	    	{
    	        $sql = "SELECT idarea 
    	                    FROM
    		   ".$cfg["tab"]["area"]."
    		     WHERE
    		        name = \"".$area."\"";
                           $db_perm->query($sql);

    	         $db_perm->next_record();
      			 $this->areacache[$area] = $db_perm->f("idarea");
                 $area = $db_perm->f(0);
	    	} else {
	    		$area = $this->areacache[$area];
	    	}             
             
             return($area);
	    }

        return $area;

  }

  function getIDForAction ($action)
  {

        global $cfg;

        $db_perm = new DB_Contenido;
        
        if (!is_numeric($action))
	    {
	    	if (!@array_key_exists($action,$this->actioncache))
	    	{
    	        $sql = "SELECT idaction 
    	                    FROM
                  ".$cfg["tab"]["actions"]."
     		        WHERE
    		       name = \"".$action."\"";
                
                $db_perm->query($sql);
    	        $db_perm->next_record();
    
    			$this->actioncache[$action] = $db_perm->f("idaction");
    	        $action = $db_perm->f(0);
	    	} else {
	    		$action = $this->actioncache[$action];
	    	}
            return ($action);

	   }

       return $action;

  }

  function load_permissions($force = false){

     global $sess,$area_rights,$item_rights,$db,$client,$lang,$auth,$cfg,$changelang,$changeclient;

     $return = "1";
     //if not admin or sysadmin
     if(!$this->have_perm()){
        $return = isset($area_rights);

         if(!isset($area_rights)||!isset($item_rights)||isset($changeclient)||isset($changelang)||$force) {
                    $return = "3";
                    //register variables
                    $sess->register("area_rights");
                    $sess->register("item_rights");
					$item_rights=array();
					
					
					
                    $groups = $this->getGroupsForUser($auth->auth["uid"]);
                    
                    if (is_array($groups))
                    {
                    	foreach ($groups as $group)
                    	{
	                    	$this->load_permissions_for_user($group);
    	                }
                    }
                    
                    $this->load_permissions_for_user($auth->auth["uid"]);
                    
                    
         }

     }

    return $return;
  }
  
  function load_permissions_for_user ($user)
  {
  	global $db, $client, $lang, $cfg;
  	global $area_rights, $item_rights;
  	$sql="SELECT *
                          FROM ".$cfg["tab"]["rights"]."
                          WHERE user_id='".$user."' AND idcat='0' AND idclient='$client' AND idlang='$lang'";
                    $db->query($sql);

					if (!is_array($area_rights))
					{
                    	$area_rights=array();
					}
                    while($db->next_record()) {
                          $area_rights[$db->f("idarea")][$db->f("idaction")]=true;
                    }

$return = 4;

                    // Select Rights for Article and Sructure    Atention    Hard code Areas
	     $sql = "SELECT
	                 idarea
	             FROM
	               ".$cfg["tab"]["area"];
                    $db->query($sql);

	     
	     $tmp_area = array();
                    while($db->next_record())
	     {
	        array_push($tmp_area,$db->f("idarea"));
	     }
                   // $tmp_area=array('con','con_editside','con_sidelist','con_edittpl','str');

                    $tmp_area_string = implode("','",array_keys($tmp_area));
                    $sql="SELECT
	     	idarea, idaction, idcat
                          FROM ".$cfg["tab"]["rights"]."
                          WHERE user_id='".$user."' AND idclient='$client' AND idlang='$lang' AND idarea IN ('$tmp_area_string') AND idcat != '0'";
                    $db->query($sql);
                    
                    
                    
                    while($db->next_record()) {
                          $item_rights[$db->f(0)][$db->f(1)][$db->f(2)]=$db->f(2);

                    }
                    //check if there area any rights in each area
                    foreach($tmp_area as $value){

                            //is there no right
                           /* if(!$item_rights[$value]){
                                //set this flag
                                $item_rights[$value]="noright";
                            }*/

                    }
  	
  }
  	


  function have_perm_area_action_item($area,$action,$itemid){
           global $item_rights,$auth,$client,$lang,$cfg;
           $db=new DB_Contenido;
           //if not admin or sysadmin

           
           
           $area = $this->getIDForArea($area);
           $action = $this->getIDForAction($action);

           if($this->have_perm()){
                return true;
           } else {
               //if the user has a right on this action in this area   check for the items
               if($this->have_perm_area_action($area,$action)){
                        return true;
                }
                  //check rights for the action in this area at this item
                  if($item_rights[$area][$action][$itemid]){
                          //if have action for area + action +item  check right for client and lang
                          return true;
                  }elseif($item_rights[$area]!="noright"){
                  	
                  		  $groupsForUser = $this->getGroupsForUser($auth->auth[uid]);
                  		  $groupsForUser[] = $auth->auth[uid];
                  		  
                  		  $tmp_userstring = implode("','",$groupsForUser);
                  		  
                          $sql="SELECT *
                          FROM ".$cfg["tab"]["rights"]."
                          WHERE user_id IN ('".$tmp_userstring."') AND idclient = '$client' AND idlang = '$lang' AND idarea = '$area'  AND idcat != '0'";
                          $db->query($sql);

                          //if there are no rights for this area set the flag norights
                          if($db->nf()==0){
                                $item_rights[$area]="noright";
                                return false;
                          }

                          while($db->next_record()) {
                                $item_rights[$db->f("idarea")][$db->f("idaction")][$db->f("idcat")]=$db->f("idcat");
                          }

                          //check
                          if($item_rights[$area][$action][$itemid]){
                                //if have action for area + action +item  check right for client and lang
                                return true;

                          }


                  }
               return false;
           }
           //return true if is sysadmin or admin
           return false;



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



  function have_perm_area_action($area,$action=0){
           global $area_rights,$client,$lang,$cfg;
           //if not admin or sysadmin
           $db_perm = new DB_Contenido;

           $area = $this->getIDForArea($area);
           $action = $this->getIDForAction($action);
      
           if ( $action == 0)
           {
             $area = $this->getParentAreaId($area);
           }

           $area = $this->getIDForArea($area);

           if(!$this->have_perm()){

                  if($action==0&&$area_rights[$area]){
                       //if have action for area + action   check right for client and lang
                       return ($this->have_perm_client_lang($client,$lang));
                  }
	  
	   

                  //check rights for the action in this area
                  if($area_rights[$area][$action]){
                       //if have action for area + action   check right for client and lang
                       return $this->have_perm_client_lang($client,$lang);
                  }

                  return false;
           }
           return true;



  }





  function have_perm_client_lang($client,$lang){
  	return($this->have_perm("client[$client],lang[$lang]"));
       
           
  }

  function have_perm_client($p="x") {
    global $auth,$client;

    if (! isset($auth->auth["perm"]) ) {
      $auth->auth["perm"] = "";
    }

    //split   the permissions of the user
    $userperm = split(",", $auth->auth["perm"]);


    //if User is sysadmin or admin at this client return true
    if(in_array("sysadmin",$userperm)){
            return true;
    }

             //if there are more permissions to ask split them
             $pageperm = split(",", $p);
             foreach($pageperm as $value){
                     if(!in_array($value,$userperm)){
                         return false;
                     }
             }
    return true;

  }


  function have_perm($p="x") {
    global $auth,$client;

    if (! isset($auth->auth["perm"]) ) {
      $auth->auth["perm"] = "";
    }

    //split   the permissions of the user
    $userperm = split(",", $auth->auth["perm"]);


    //if User is sysadmin or admin at this client return true
    if(in_array("sysadmin",$userperm)){
            return true;
    }elseif(in_array("admin[$client]",$userperm)){
            return true;

    //else check rights for the client and the language
    }else{
             //if there are more permissions to ask split them
             $pageperm = split(",", $p);
             foreach($pageperm as $value){
                     if(!in_array($value,$userperm)){
                         return false;
                     }
             }
    }
    return true;

  }



  //checks if an item have any perms
  function have_perm_item($mainarea,$itemid){
    global $cfg,$item_rights,$cfg,$client,$lang,$auth,$area_tree,$sess;


    //if is not admin or sysadmin
    if(!$this->have_perm()){

         $db=new DB_Contenido;

         showareas($mainarea);
         $flg=false;
         //chek if there area any rights for this areas
         foreach($area_tree[$mainarea] as $value){
                 // if the flag noright is set   there are no rights in this area
                 if($item_rights[$value]=="noright"){
                       return false;

                 }elseif(is_array($item_rights[$value])){
                       //if there are any rights
                       foreach($item_rights[$value] as $value2){
                               if(in_array($itemid,$value2)){
                                  return true;
                               }
                       }

                 }elseif($item_rights[$value]!="noright"){

                       //else searcch for rights for this user in this area
                       $sql="SELECT *
                             FROM ".$cfg["tab"]["rights"]."
                             WHERE user_id='".$auth->auth["uid"]."' AND idclient = '$client' AND idlang = '$lang' AND idarea = '$value'  AND idcat != '0'";
                       $db->query($sql);

                       //if there are no rights for this area set the flag norights
                       if($db->affected_rows()==0){
                               $item_rights[$area]="noright";
                       }

                       //set the rights
                       while($db->next_record()) {
                               if($db->f("idcat")==$itemid){
                                  $flg=true;
                               }
                               $item_rights[$db->f("idarea")][$db->f("idaction")][$db->f("idcat")]=$db->f("idcat");
                       }


                 }//end if

         }//end for
         return $flg;
    }//end if
    return true;

  }



}
?>
