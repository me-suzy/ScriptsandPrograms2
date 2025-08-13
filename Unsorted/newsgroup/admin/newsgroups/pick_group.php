<?
// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //


// --------- Servers Administration ------------//
//
// File: pick_group.php
//
// Created: 10/06/2002
//
// Description:
//
// Build List of Available NewsGroups.
//
// Note:  	BEWARE !!  O_O
//			-----------------
//		    This is the only .php in the whole MyNewsGroups :)
//			project that has PHP code embedded into the HTML.   
//			What is the main reason for this? 
//			This PHP script mainly reads some lines from a text
//			file and then builds a Form List with that entries.
//			Normally we'll have about 15000 newsgroups names in that
//			list, so we need some speed to do that. 
//			PHPLib's templates class can not be used here
// 			because we need much more time to perform this task.

session_start();

// Systemcheck standalone or postnuke
if(LOADED_AS_MODULE=="1") {
    // Define Modulename for Postnuke
    $ModName = basename( dirname( __FILE__ ) );
    $myng['system'] = "postnuke";
    // Include the postnuke config file
    include("modules/$ModName/config.php");
} else {
    $myng['system'] = "standalone";
    // Include the standard config file, with all the configuration and files required.
    include("../../config.php");
}

// Need DB connection
$db=new My_db;
$db->connect();

// MyNG setting up...
init();


// Check Authentication
if(!isset($_SESSION['adm_id'])){

    // Redirect or change the Interface shown?
    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{

	// Show the newsgroups list file
	$file_path = $_SESSION['conf_system_root']."upload/lists/".real2table($_GET['server']).".list";
				
	// If the file do not exist..
	if(file_exists($file_path)){
		
		// Change the 'max_execution_time' parameter of php.ini
		ini_set("max_execution_time","1000");
		//$file_path = $_SESSION['conf_system_root']."upload/lists/".real2table($_GET['server']).".list";
		$i=0;
		// Get an array with all the newsgroups names!!
		$fcontents = file($file_path);
		// Sort the array??
		sort($fcontents);
		
	}else{
		echo "Error: Get the NewsGroups List First!";	
		exit();
		
	}
	
		
}


?>

<html>
<head>
<title>Pick a Group!!</title>
<script language="JavaScript">

function init(){
	this.file = opener.file;		
	this.inFile = file.value;
	
}

function return_value(result){

	//alert(result);
	this.inFile=result	
	file.value=this.inFile;
	window.close();
}

</script>
<link rel="stylesheet" href="<? echo $_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme'];?>/styles/style.css" type="text/css">
</head>
<body onLoad="init()">
<script language="JavaScript" src="<? $_SESSION['conf_system_prefix'];?>admin/includes/admin.js"></script>
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>         
      <form name="form" method="post" action="detail.php">
        <br>
        <table width="500" class="tabla_menu">
          <!-- NewsGroup Name-->
          <tr> 
            <td valign="top" class="top_link"> <div align="right">Server:&nbsp;</div></td>
            <td> 
            <!-- NewsGroups list!! -->
            <select name="grp_name" size="10" class="form_admin">
    		<? 
    			while (list ($line_num, $line) = each ($fcontents)) {   
    		?>         
                <option value="<? echo $line; ?>"><? echo $line ?></option>
    			<? 
    				$i ++; 
    			}//While END
    			?>       
            </select>
            
           </td>
          </tr>
          <tr> 
            <td colspan="2"> <table width="0%" border="0" cellspacing="0" cellpadding="2" align="center">
                <tr> 
                  <td> 
                    <!-- BEGIN add_block -->
					<input type="submit" name="Submit" value="Close" class="form_admin" onClick="return_value(document.form.grp_name.value)">                     
                    <!-- END add_block -->
                  </td>                
                </tr>
              </table>
              <div align="center"></div></td>
          </tr>
        </table>
        <br>
            <input type="hidden" name="grp_id" value="{grp_id}">
            <input type="hidden" name="action">
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<body>
</html>