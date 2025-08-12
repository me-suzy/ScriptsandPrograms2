<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/

// Note: some of the following functions may not be in use in this version.

function private_cat($id){
    if(!strlen($id)) { return false; }
	db_connect();
	$sql = "SELECT private FROM " . PDB_PREFIX . "categories WHERE id = " . $id;
	$result = db_query($sql);
	$row = db_fetch_array($result);
	return $row['private'];
}

function build_menu()
{
	db_connect();
	$sql = "SELECT id, menu_label FROM " . PDB_PREFIX . "content WHERE menu_label != '' AND id != 1 ORDER BY id";
	$resultMenu = db_query($sql);
	while($rowMenu = db_fetch_array($resultMenu)){
		$strMenu .= '<a href="content.php?cid=' . $rowMenu['id'] . '" class="menu_pdb">' . $rowMenu['menu_label'] . '</a><div class="menu_divider"></div>';
	}
	return $strMenu;
}

function create_content($frm)
{
	db_connect();
	$sql = "INSERT INTO " . PDB_PREFIX . "content VALUES(0, '" . $frm['title'] . "','','')";
	$result = db_query($sql);
    if(db_affected_rows($result)) {
		return mysql_insert_id();
	}
	return false;
}

function delete_content($id)
{
	db_connect();	
	$sql = "DELETE FROM " . PDB_PREFIX . "content WHERE id = " . $id;
  			db_query($sql);
	  return true;

}

function create_category($frm)
{
	db_connect();
	$sql = "INSERT INTO " . PDB_PREFIX . "categories VALUES(0, '" . $frm['name'] . "',0)";
	$result = db_query($sql);
    if(db_affected_rows($result)) {
		return mysql_insert_id();
	}
	return false;
}

function edit_category($frm)
{
	db_connect();
	$sql = "UPDATE " . PDB_PREFIX . "categories SET cat_name = '" . $frm['new_name'] . "' WHERE id = " . $frm['cat_id'];
	$result = db_query($sql);
    if(db_affected_rows($result)) {
		return true;
	}
	return false;
}

function create_private_user($frm)
{
	db_connect();
	$sql = "INSERT INTO " . PDB_PREFIX . "auth VALUES(0, '" . $frm['username'] . "','" . md5($frm['password']) . "','" . $frm['name'] . "'," . $frm['cat_id'] . ",1)";
	$result = db_query($sql);
    if(db_affected_rows($result)) {
		return true;
	}
	return false;
}

function update_private_user($frm)
{
	db_connect();
	$sql = "UPDATE " . PDB_PREFIX . "auth SET username = '" . $frm['username'] . "', password = '" . md5($frm['password']) . "', name = '" . $frm['name'] . "' WHERE cat_id = " . $frm['cat_id'];
	$result = db_query($sql);
    if(db_affected_rows($result)) {
		return true;
	}
	return false;
}
 
function delete_category($id)
{
	db_connect();
	$sql = "SELECT i.image, i.id FROM " . PDB_PREFIX . "images i,
					" . PDB_PREFIX . "images_to_categories i2c  WHERE i.id = i2c.image_id AND i2c.cat_id = " . $id;
					
	$result = db_query($sql);

	while($row = db_fetch_array($result)){

	     	delete_image(DIR_PORTFOLIOS . "/thumbs/", $row['image']);
  			delete_image(DIR_PORTFOLIOS . "/", $row['image']);
  
  			$sql = "DELETE FROM " . PDB_PREFIX . "images WHERE id = " . $row['id'];
  			db_query($sql);			
	}
	
	$sql = "DELETE FROM " . PDB_PREFIX . "images_to_categories WHERE cat_id = " . $id;
  			db_query($sql);
			$sql = "DELETE FROM " . PDB_PREFIX . "categories WHERE id = " . $id;
  			db_query($sql);
			$sql = "DELETE FROM " . PDB_PREFIX . "user_text WHERE content_cat = " . $id;
  			db_query($sql);
	  return true;

}

function destroy_this_session() {
	// Unset all of the session variables.
	$_SESSION = array();
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (isset($_COOKIE[session_name()])) {
	   setcookie(session_name(), '', time()-42000, '/');
	}
	// Finally, destroy the session.
	session_destroy();
}

function count_click($image_id){
 db_connect();
	$sql = "SELECT * FROM " . PDB_PREFIX . "image_ratings WHERE id = " . $image_id;
	if(!db_num_rows(db_query($sql))){
	    $sql = "INSERT INTO " . PDB_PREFIX . "image_ratings VALUES (" . $image_id . ", 0, 0, 0, 0, '0000-00-00')";
		if(!db_affected_rows(db_query($sql))){
		    return false; //error codes in future class
		}
	}
    $sql = "UPDATE " . PDB_PREFIX . "image_ratings SET times_clicked = (times_clicked +1) WHERE id = " . $image_id;
	return db_affected_rows(db_query($sql));

}

function randString($length=8)
{
    mt_srand((double)microtime()*1000000);
    $newstring="";

    if($length>0){
        while(strlen($newstring)<$length){
            switch(mt_rand(1,3)){
                case 1: $newstring.=chr(mt_rand(65,90)); break;  // A-Z
                case 2: $newstring.=chr(mt_rand(65,90)); break;  // A-Z
                case 3: $newstring.=chr(mt_rand(65,90)); break;  // A-Z
            }
        }
    }
    return $newstring;
}

function row_color($i, $c1, $c2)
{	
	if ( ($i % 2) == 0 ) {
		return $c1;
	} else {
		return $c2;
	}
}

function clearForm(&$frm)
{
    foreach($frm as $key => $value) {
        $frm[$key] = '';
    }
}

function stripPostSlashes(&$frm)
{
    foreach($frm as $key => $value) {
	    if(!is_array($value))
        $frm[$key] = stripslashes($frm[$key]); 
    }
}

function addPostSlashes(&$frm)
{
    foreach($frm as $key => $value) {
	    if(!is_array($value))
        $frm[$key] = addslashes($frm[$key]); 
    }
}

function err($i)
{
	if ( isset($i) ) {
		return ERROR_MARKER;
	} else {
		return '';
	}
}


function catId2catName($ci){
    if(strlen($ci)){
		$sql = "SELECT id, cat_name FROM " . PDB_PREFIX . "categories WHERE id = " . $ci;	
		$result = db_query($sql);
		if(db_num_rows($result)){
			while($cats = db_fetch_array($result)){
				$name = $cats['cat_name'];
			}
			return $name;
		}
	}
	return false;
}


//-----------------------------------------------------------------------------------/
//	Function:		string make_selectbox($selname, $options, $default='', $extras='', 
//                  $options_as_values='true')
//	RETURN:			string - dynamically generated selectbox
//-----------------------------------------------------------------------------------/

function make_selectbox($selname, $options, $default='', $extras='', $options_as_values=1) 
{
  // $default = default selection
  // $selname: selectbox name
  // $options: array of options
  // $extras: attributes, such as javascript
  // $options_as_values:  true if the options are also the "values"
  //  false if the $options array indexes are to make up the "value" attributes.
  
  $str = "<SELECT " . $extras . " name='" . $selname . "'>\r\n";
  if ($default == '') {
      $str .= '<OPTION value="">Select</OPTION>';
  }
  while(list($key, $value) = @each($options)) {
//print "DEFAULT: " . $default . " KEY: " . $key . " VALUE: " . $value. "<br>";
      if(!$options_as_values) {
          $str .= '<OPTION value="' . $key . '"';
      } else {
          $str .= '<OPTION value="' . $options[$key] . '"';
      }//end if options as values
	  if(!$options_as_values){
	      if($default == $key){
		  
              $str .= ' selected ';
	      }
	  } else {
	      if($default == $options[$key]){
              $str .= ' selected ';
	    }
	  }
      
      $str .= '>' . $options[$key] . '</option>' . "\r\n";
  }//end while
  $str .= "</SELECT>\r\n";
  return $str;
}// end make_selectbox()


//----------------------------------------------------------------------------------------/
//	Function:		string make_checkboxes($default, $cbname, $options, $col_length=15)
//	RETURN:			string - formatted checkboxes
//----------------------------------------------------------------------------------------/

function make_checkboxes($default, $cbname, $options, $col_length=15, $options_as_values=1) {
  // $default = ANY or no default
  // $cbname: checkbox name
  // $options: array of options - the different checkboxes
  // $col_length: number of checkboxes per column
  $str = "<table>\r\n<tr>\r\n<td valign='top' nowrap>";
  $col = 1;
  
  while(list($key, $value) = @each($options)) {
      if($options_as_values) {
          $str .= '<input type="checkbox" name="' . $cbname . '[]" value="' . $options[$key] . '"';
		  
      } else {
          $str .= '<input type="checkbox" name="' . $cbname . '[]" value="' . $key . '"';
		  
      }//end if options as values
      if(@in_array($key, $default)){
            $str .= ' checked ';
          }
       $str .= '>' . ucwords($options[$key]);
	   if($col >= $col_length) {
	   	  $str .="</td>\r\n<td valign='top' nowrap>";
		  		  $col = 0;
	   } else {
	      $str .= "<br>\r\n";

	   }
	   $col++;
  }//end while
  $str .= '</td></tr></table>';
  return $str;
}// end make_checkboxes

//----------------------------------------------------------/
//	Function: mkuid()
//	Return the next number in a sequence.
//----------------------------------------------------------/

function mkuid(){
	db_connect();
	$sql = "UPDATE " . PDB_PREFIX . "uid_generator SET uid = LAST_INSERT_ID(uid + 1)";
	if( db_affected_rows($result = db_query($sql)) > 0 ) {
		return mysql_insert_id();
	} 
	return false;
}//end mkuid

//----------------------------------------------------------/
//	Function: username_exists()
//	Return BOOLEAN, existance of username in db.
//----------------------------------------------------------/

function username_exists($un){
	db_connect();
	$sql = "SELECT id FROM " . PDB_PREFIX . "users 
		where username = '" . $un . "'";
	$result = db_query($sql);
	if(db_num_rows($result) > 0) {
		return true;
	}
  return false;
}//end username_exists

//----------------------------------------------------------/
//	Function: is_active()
//	Return BOOLEAN, is user flagged as active in db.
//----------------------------------------------------------/

function is_active($uid){
	db_connect();
	$sql = "SELECT active FROM " . PDB_PREFIX . "auth where id = $uid";
	$result = db_query($sql);
	$row = db_fetch_array($result);
	if($row['active'] == 0) {
		return false;
	}
  return true;
}//end is_active

function redirect($path){
  header('Location: ' . $path);
  exit;
}

?>