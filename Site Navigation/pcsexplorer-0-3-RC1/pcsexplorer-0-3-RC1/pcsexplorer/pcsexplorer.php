<?
// -----------------------------------------------------------------------------
// PhpConcept Script - PcsExplorer
// -----------------------------------------------------------------------------
// License GNU/GPL - Vincent Blavet - March 2005
// http://www.phpconcept.net
// -----------------------------------------------------------------------------
// Overview :
//   PcsExplorer is a Javascript/PHP script that manage a popup window
//   to navigate and select file or folder on a remote site.
//
// -----------------------------------------------------------------------------
// CVS : $Id$
// -----------------------------------------------------------------------------

  // ----- Application description
  define( 'PCSEXPLORER_VERSION', '0.3-RC1' );
  define( 'PCSEXPLORER_NAME', 'PcsExplorer' );

  // ----- Trace initialisation : For debug only
  // 0 means no trace, 5 means full trace
  require_once("lib/pcltrace.lib.php");
  $g_trace = 0;
  PclTraceOn($g_trace);

  // ----- Start the php session
  session_start();

  // ----- Look for session reset
  if ((isset($_POST['a_session'])) && ($_POST['a_session'] == 'reset')) {
  	session_unregister('pcsexplorer');
  }

  // ----- Look for session object
  if (!isset($_SESSION['pcsexplorer'])) {
    PclTraceMessage(__FILE__, __LINE__, 1, "Initialisation of the PcsExplorer session");
  	// ----- Initialize the session object
  	$_SESSION['pcsexplorer'] = array();
  }

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerInit()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerInit()
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerInit", "");
    $v_result = 1;

  	// ----- Set default properties
  	// root of the web site
  	$_SESSION['pcsexplorer']['root_path'] = $_SERVER['DOCUMENT_ROOT'];
  	// open_base_dir restriction : everything should be inside this path
  	$_SESSION['pcsexplorer']['base_dir'] = $_SERVER['DOCUMENT_ROOT'];
  	// the reference directory path from where
  	// the result should be relative to
  	$_SESSION['pcsexplorer']['result_ref_dir'] = $_SERVER['DOCUMENT_ROOT'];
  	// the start directory
  	$_SESSION['pcsexplorer']['start_dir'] = $_SERVER['DOCUMENT_ROOT'];
  	// the current showed directory
  	$_SESSION['pcsexplorer']['current_dir'] = '';
  	// the javascript target (usually a text box)
  	$_SESSION['pcsexplorer']['target'] = '';
  	// the type file/dir (later need to add open/save)
  	$_SESSION['pcsexplorer']['type'] = 'file';
  	
  	// List of supported extensions
  	$_SESSION['pcsexplorer']['extensions'] = array();
  	
  	// List of hidden dirs
  	$_SESSION['pcsexplorer']['hidden_dir'] = array();

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerStart()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerStart($p_args)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerStart", "");
    $v_result = 1;

    PclTraceFctMessage(__FILE__, __LINE__, 2, "Reset to default value");
    PcsExplorerInit();

    // ----- Look for arguments in a specific order

	// ----- Look for root_dir
	if (isset($p_args['a_root_dir'])) {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Root dir set='".$p_args['a_root_dir']."'");

		// ----- Reduce path
		$v_dir = PcsExplorerPathReduction($p_args['a_root_dir']);
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Reduced='".$v_dir."'");

		// ----- Check that the parameter is valid
		if (@is_dir($v_dir)) {
  		  $_SESSION['pcsexplorer']['root_path'] = $v_dir;
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'root_path' to '".$_SESSION['pcsexplorer']['root_path']."'");
		}
		else {
            PclTraceFctMessage(__FILE__, __LINE__, 4, "Invalid directory");
			// TBC : Error
 		    $_SESSION['pcsexplorer']['root_path'] = $_SERVER['DOCUMENT_ROOT'];
            PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'root_path' to default '".$_SESSION['pcsexplorer']['root_path']."'");
		}
	}
	else {
      $_SESSION['pcsexplorer']['root_path'] = $_SERVER['DOCUMENT_ROOT'];
      PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'root_path' to default '".$_SESSION['pcsexplorer']['root_path']."'");
	}
	
	// ----- Set default start dir
	$_SESSION['pcsexplorer']['start_dir']
	  = $_SESSION['pcsexplorer']['root_path'];

	// ----- Look for base_dir
	if (isset($p_args['a_base_dir'])) {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Base dir set='".$p_args['a_base_dir']."'");

		// ----- Reduce path
		$v_dir = PcsExplorerPathReduction($p_args['a_base_dir']);
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Reduced='".$v_dir."'");

		// ----- Check that the parameter is valid
		if (@is_dir($v_dir)) {
  		  $_SESSION['pcsexplorer']['base_dir'] = $v_dir;
		}
		else {
            PclTraceFctMessage(__FILE__, __LINE__, 4, "Invalid directory");
			// TBC : Error
 		    $_SESSION['pcsexplorer']['base_dir'] = $_SERVER['DOCUMENT_ROOT'];
		}
	}

	// ----- Look for result_ref_dir
	if (isset($p_args['a_result_ref_dir'])) {
      $_SESSION['pcsexplorer']['result_ref_dir']
	    = PcsExplorerPathReduction($_SESSION['pcsexplorer']['root_path']
		                           .'/'
								   .$p_args['a_result_ref_dir']);
      PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'result_ref_dir'='".$_SESSION['pcsexplorer']['result_ref_dir']."'");
	}
	if (isset($p_args['a_result_ref_dir_from_root'])) {
      $_SESSION['pcsexplorer']['result_ref_dir']
	    = PcsExplorerPathReduction($_SESSION['pcsexplorer']['root_path']
		                           .'/'
								   .$p_args['a_result_ref_dir_from_root']);
      PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'result_ref_dir'='".$_SESSION['pcsexplorer']['result_ref_dir']."'");
	}
	if (isset($p_args['a_result_ref_dir_from_fs'])) {
      $_SESSION['pcsexplorer']['result_ref_dir']
	    = PcsExplorerPathReduction($p_args['a_result_ref_dir_from_fs']);
      PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'result_ref_dir'='".$_SESSION['pcsexplorer']['result_ref_dir']."'");
	}

    // ----- Check the arguments
    foreach ($p_args as $v_argument => $v_value) {
	  switch ($v_argument) {
	    case 'a_target' :
  	      $_SESSION['pcsexplorer']['target'] = $v_value;
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'target'='".$_SESSION['pcsexplorer']['target']."'");
	    break;
	    case 'a_type' :
  	      $_SESSION['pcsexplorer']['type'] = $v_value;
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'type'='".$_SESSION['pcsexplorer']['type']."'");
	    break;

	    case 'a_extensions' :
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Received extensions list :'".$v_value."'");
          if ($v_value != '') {
            $_SESSION['pcsexplorer']['extensions'] = explode(',', $v_value);
          }
          else {
            PclTraceFctMessage(__FILE__, __LINE__, 2, "List is empty");
          }
	    break;

	    case 'a_hidden_dir' :
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Received extensions list :'".$v_value."'");
          $_SESSION['pcsexplorer']['hidden_dir'] = explode(',', $v_value);
	    break;

	    case 'a_start_dir_from_root' :
  	      $_SESSION['pcsexplorer']['start_dir']
		    = PcsExplorerPathReduction($_SESSION['pcsexplorer']['root_path']
			                           .'/'
									   .$v_value);
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'start_dir'='".$_SESSION['pcsexplorer']['start_dir']."'");

          // ----- Reduce path

          // ----- Checking that the result is valid
          // TBC
          // Need to check the open_base_dir restriction & folder filtering

	    break;

	    case 'a_start_dir_from_fs' :
  	      $_SESSION['pcsexplorer']['start_dir']
			= PcsExplorerPathReduction($v_value);
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'start_dir'='".$_SESSION['pcsexplorer']['start_dir']."'");

          // ----- Reduce path

          // ----- Checking that the result is valid
          // TBC
          // Need to check the open_base_dir restriction & folder filtering

	    break;

	    case 'a_start_dir_from_result' :
  	      $_SESSION['pcsexplorer']['start_dir']
		  = PcsExplorerPathReduction($_SESSION['pcsexplorer']['result_ref_dir']
		                             .'/'
									 .$v_value);
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Setting 'start_dir'='".$_SESSION['pcsexplorer']['start_dir']."'");

          // ----- Reduce path

          // ----- Checking that the result is valid
          // TBC
          // Need to check the open_base_dir restriction & folder filtering

	    break;

	    case 'a_base_dir' :
	    case 'a_root_dir' :
	    case 'a_result_ref_dir' :
	    case 'a_result_ref_dir_from_root' :
	    case 'a_result_ref_dir_from_fs' :
	    break;

	    default :
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Unknown argument name '".$v_argument."'");
	  }
    }

	// TBC :
	// Check that there is a javascript target
	// Check the default type (file/dir)
	
	// ----- Set the current directory
    $_SESSION['pcsexplorer']['current_dir']
      = PcsExplorerPathReduction($_SESSION['pcsexplorer']['start_dir']);
    PclTraceFctMessage(__FILE__, __LINE__, 2, "current_dir='".$_SESSION['pcsexplorer']['current_dir']."'");

    // ----- Call the explorer
    $v_result = PcsExplorer();

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerSelectFile()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerSelectFile($p_args)
  {
    $v_result = 1;
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerSelectFile", "file='".$p_args['item']."'");

    // ----- Calculate the result
    PclTraceFctMessage(__FILE__, __LINE__, 2, "current_dir : '".$_SESSION['pcsexplorer']['current_dir']."'");
    $v_answer = PcsExplorerPathReduction($_SESSION['pcsexplorer']['current_dir']
	                                     .'/'.$p_args['item']);
    PclTraceFctMessage(__FILE__, __LINE__, 2, "result : '".$v_answer."'");
    
    // ----- Check that the file is OK
    // TBC : These checks need to be added
    // - exists if 'open' mode
    // - does not exists if 'save' mode
	if (($v_result = PcsExplorerCheckFileBaseDir($v_answer)) == 1) {
	  if (($v_result = PcsExplorerCheckFileDir($v_answer)) == 1) {
	    $v_result = PcsExplorerCheckFileExtensions($v_answer);
	  }
    }
    if ($v_result != 1) {

      // TBC : Error message
?>
<script language="javascript" type="text/javascript">
<!--
  alert("The file '<?php echo $v_answer; ?>' is not a valid selection.");
// -->
</script>

<?php

      $v_result = PcsExplorer();

	  PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    PclTraceFctMessage(__FILE__, __LINE__, 2, "result_ref_dir : '".$_SESSION['pcsexplorer']['result_ref_dir']."'");
    $v_answer
	= PcsExplorerPathRelative($_SESSION['pcsexplorer']['result_ref_dir'],
	                          $v_answer);
    PclTraceFctMessage(__FILE__, __LINE__, 2, "relative answer : '".$v_answer."'");

?>
<script language="javascript" type="text/javascript">
<!--
  window.opener.document.<?php echo $_SESSION['pcsexplorer']['target']; ?>
  = "<? echo $v_answer; ?>";
  //alert("Return will be '"+"<? echo $v_answer; ?>"+"'");
  window.close();
// -->
</script>

<?php
	PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerSelectDir()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerSelectDir($p_args)
  {
    $v_result = 1;
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerSelectDir", "");

    // ----- Calculate the relative start path the calling script
    PclTraceFctMessage(__FILE__, __LINE__, 2, "current_dir : '".$_SESSION['pcsexplorer']['current_dir']."'");
    $v_answer
	  = PcsExplorerPathReduction($_SESSION['pcsexplorer']['current_dir']
	                             .'/'
								 .$p_args['item']);
    PclTraceFctMessage(__FILE__, __LINE__, 2, "result : '".$v_answer."'");

    // ----- Check that the file is OK
    // TBC : These checks need to be added
    // - exists if 'open' mode
    // - does not exists if 'save' mode
	if (($v_result = PcsExplorerCheckFileBaseDir($v_answer)) == 1) {
	  if (($v_result = PcsExplorerCheckFileDir($v_answer)) == 1) {
	    //$v_result = PcsExplorerCheckFileExtensions($v_answer);
	  }
    }
    if ($v_result != 1) {

      // TBC : Error message
?>
<script language="javascript" type="text/javascript">
<!--
  alert("The folder '<?php echo $v_answer; ?>' is not a valid selection.");
// -->
</script>

<?php

      $v_result = PcsExplorer();

	  PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    PclTraceFctMessage(__FILE__, __LINE__, 2, "result_ref_dir : '".$_SESSION['pcsexplorer']['result_ref_dir']."'");
    $v_answer
	= PcsExplorerPathRelative($_SESSION['pcsexplorer']['result_ref_dir'],
	                          $v_answer);
    PclTraceFctMessage(__FILE__, __LINE__, 2, "relative answer : '".$v_answer."'");

?>
<script language="javascript" type="text/javascript">
<!--
  window.opener.document.<?php echo $_SESSION['pcsexplorer']['target']; ?>
  = "<? echo $v_answer; ?>";
  //alert("Return will be '"+"<? echo $v_answer; ?>"+"'");
  window.close();
// -->
</script>
<?php

	PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerOpenDir()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerOpenDir($p_args)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerOpenDir", "dir='".$p_args['dir']."'");

	// ----- Change current directory
	if ((isset($p_args['dir'])) && ($p_args['dir'] != '')) {
	  $_SESSION['pcsexplorer']['current_dir']
	  = PcsExplorerPathReduction($p_args['dir']);
	}

    PclTraceFctMessage(__FILE__, __LINE__, 2, "New current_dir : '".$_SESSION['pcsexplorer']['current_dir']."'");

    // ----- Call the explorer
    $v_result = PcsExplorer();

	PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorer()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorer()
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorer", "");
    $v_result = 1;

	// ----- External scan
	$p_list = array();
	if (PcsExplorerScanDir($_SESSION['pcsexplorer']['current_dir'], $p_list) !=1) {
	}

	// ----- Check PcsExplorer open_base_dir restriction
    PclTraceFctMessage(__FILE__, __LINE__, 2, "Check open_base_dir restriction");
	$v_result
	= PcsExplorerUtilPathInclusion($_SESSION['pcsexplorer']['base_dir'],
	                               $_SESSION['pcsexplorer']['current_dir']);
	if ($v_result == 0) {
      PclTraceFctMessage(__FILE__, __LINE__, 2, "Restriction apply !!");
      PclTraceFctMessage(__FILE__, __LINE__, 2, "Base dir :'".$_SESSION['pcsexplorer']['base_dir']."'");
      PclTraceFctMessage(__FILE__, __LINE__, 2, "Current dir :'".$_SESSION['pcsexplorer']['current_dir']."'");
?>
<script language="javascript" type="text/javascript">
<!--
alert("This folder does not exist, or is protected.");
//window.close();
// -->
</script>
<?
      // ----- Return
      PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
	}
	
	// ----- Display the current folder
	PcsExplorerSkin($p_list);

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerSkin)
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerSkin($p_list)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerSkin", "");
    $v_result = 1;

    // TBC
    $a_type = $_SESSION['pcsexplorer']['type'];

    // ----- Calculate the relative start path the calling script
    PclTraceFctMessage(__FILE__, __LINE__, 2, "result_ref_dir : '".$_SESSION['pcsexplorer']['result_ref_dir']."'");
    PclTraceFctMessage(__FILE__, __LINE__, 2, "current_dir : '".$_SESSION['pcsexplorer']['current_dir']."'");
    $v_relative_dir
	= PcsExplorerPathRelative($_SESSION['pcsexplorer']['result_ref_dir'],
	                          $_SESSION['pcsexplorer']['current_dir']);
    PclTraceFctMessage(__FILE__, __LINE__, 2, "relative dir : '".$v_relative_dir."'");

?>
<HTML style="width:350px; Height: 300px;">
<title><? echo PCSEXPLORER_NAME." - ".$p_list['current_dir']['filename']; ?></title>

<script language="javascript" type="text/javascript">
<!--
function PcjsExplorerSelectFile(p_value)
{
  pceformulaire.item.value = p_value;
  pceformulaire.event.value = "select_file";
}
function PcjsExplorerSelectDir(p_value)
{
  pceformulaire.item.value = p_value;
  pceformulaire.event.value = "select_dir";
}
function PcjsExplorerOpendir(p_value)
{
//	alert('double click '+p_value);

  document.getElementById("message").style.visibility="visible";
  pceformulaire.item.value = "";
  pceformulaire.dir.value = p_value;
  pceformulaire.event.value = "open_dir";
  pceformulaire.submit();

  //var v_result = "open=<? echo $v_relative_dir; ?>"+p_value;
  //alert("Return will be a directory ='"+v_result+"'");
  //window.returnValue = v_result;
  //window.close();
}
function PcjsExplorerSelectCheck()
{
<?
  if (0) //($a_target_type == 'opener')
  {
?>

    // ----- Compose the 'virtual post' in order to give the action parameters to pcsexplorer PHP code
    window.opener.document.write('<FORM  name="pcjsexplorerformulaire" method="POST" action="<? echo $a_target; ?>">');
    window.opener.document.write('<input type="hidden" name="<? echo (isset($a_target_argument_name)?$a_target_argument_name:"a_selection"); ?>" value="<? echo $v_relative_dir; ?>'+pceformulaire.item.value+'">');
    window.opener.document.write('</FORM>');
    window.opener.document.forms.pcjsexplorerformulaire.submit();

    // ----- Give focus to window
    window.opener.focus();
    window.close();

    /*
    var v_result = "open=<? echo $v_relative_dir; ?>"+pceformulaire.item.value;
    //alert("Return will be a directory ='"+v_result+"'");
    window.returnValue = v_result;
    window.close();
    */
<?
  }

  // ----- Put the result directly in the text field
  else // if ($a_target_type == 'input_text')
  {
?>
/*
    var v_result = "select=<? echo $v_relative_dir; ?>"+pceformulaire.item.value;
    //alert("Return will be a directory ='"+v_result+"'");
    window.returnValue = v_result;
    window.close();
    */

/*
    window.opener.document.<? echo $a_target; ?> = "<? echo $v_relative_dir; ?>"+pceformulaire.item.value;
    alert("Return will be ='"+"<? echo $v_relative_dir; ?>"+pceformulaire.item.value+"'");
    window.close();
    */


  document.getElementById("message").style.visibility="visible";
  //pceformulaire.item.value = p_value;
  //pceformulaire.event.value = "open_dir";
  // TBC
  <?php
  if ($a_type == 'dir') {
  ?>
  pceformulaire.event.value = "select_dir";
  <?php
  }
  ?>
  pceformulaire.submit();

/*
    window.returnValue = "<? echo $v_relative_dir; ?>"+pceformulaire.item.value;
    */
<?
  }
?>
}
function PcjsExplorerClose()
{
  window.close();
}
// -->
</script>

<body bgcolor="#FFFFFF" text="#0000CC" link="#993300" vlink="#FF0000">

<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif">
<b><font size="3">
  Web Explorer </font></b></font></p>

<form name="pceformulaire" method="post" action="?">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<div id="message" style="position:absolute; width:200px; height:20px;
                         z-index:2; visibility:hidden; overflow: auto;
						 left: 70px; top: 140px; background-color: #0000CC;
						 layer-background-color: #0000CC;
						 border: 1px none #000000">
<p align=center>
<font face='Verdana, Arial, Helvetica, sans-serif' size=3 color=white>
<b><blink>
Loading ...
</blink></b>
</font>
</p>
</div>

<div id="Layer1" style="position:absolute; width:300px; height:200px;
                        z-index:1; overflow: auto; left: 20px; top: 50px;
						background-color: #CCCCCC;
						layer-background-color: #CCCCCC;
						border: 1px none #000000">


<?

    // ----- File / directory table
    echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
    echo "<tr bgcolor=#0000CC>";
    echo "<td width=10></td>";
    //echo "<td></td>";
    echo "<td colspan=2><div><font face='Verdana, Arial, Helvetica, sans-serif'";
	echo " size=3 color=white>Folder</font></div></td>";
    echo "<td><div><font face='Verdana, Arial, Helvetica, sans-serif' size=3";
	echo " color=white>Size</font></div></td>";
    echo "<td width=10></td></tr>";

	// ----- Display the parent
    if ($p_list['parent_dir']['mode'] == 'na') {
      PclTraceFctMessage(__FILE__, __LINE__, 2, "parent_dir mode is 'na'");
?>
    <tr>
	  <td width=10></td>
      <td colspan=2>
	    <font face='Verdana, Arial, Helvetica, sans-serif' size=2>
		<A onClick='return false;'
		   onDblClick='return false;'
		   title='<?php echo $p_list['current_dir']['filename']; ?>' >
		<img src='images/disk-16.gif' border='0' hspace='0' vspace='0'>
<?php /*		<img src='images/folder02-16.gif' border='0' hspace='0' vspace='0'> */ ?>
		 [Root]
		 </A>
		 </font>
	  </td>
	  <td></td>
	  <td></td>
    </tr>
<?php
    }
    else {
      PclTraceFctMessage(__FILE__, __LINE__, 2, "parent_dir '".$p_list['parent_dir']['shortname']."'");
?>
    <tr>
	  <td width=10></td>
      <td colspan=2>
	    <font face='Verdana, Arial, Helvetica, sans-serif' size=2>

		<A onClick='PcjsExplorerSelectDir("<?php echo $p_list['parent_dir']['shortname']; ?>");'
		   onDblClick='PcjsExplorerOpendir("<?php echo $p_list['parent_dir']['filename']; ?>");'
		   title='<?php echo $p_list['parent_dir']['filename']; ?>' >
		<img src='images/folder02-16.gif' border='0' hspace='0' vspace='0'>
		 [Parent]</A>

		 </font>
	  </td>
	  <td></td>
	  <td></td>
    </tr>
<?php
        }

	// ----- Display the childs
	for ($i=0; $i<sizeof($p_list['childs']); $i++) {
      // ----- Look for mode
      if ($p_list['childs'][$i]['type'] == 'na') {
		continue;
      }
      
      $v_file = $p_list['childs'][$i]['shortname'];
      $v_file_full = $p_list['childs'][$i]['filename'];
      
      // ----- Look for file
      if ($p_list['childs'][$i]['type'] == 'file') {
        if ($a_type == "file") {
          $v_icon = PcsExplorerImageFromExtension($v_file_full);
?>
          <tr>
		    <td width=10></td>
            <td width=10></td>
            <td><font face='Verdana, Arial, Helvetica, sans-serif' size=2>
            <A onClick='PcjsExplorerSelectFile("<?php echo $v_file; ?>");'
               title='<?php echo $v_file_full; ?>'>

            <img src='images/<?php echo $v_icon; ?>' border='0' hspace='0' vspace='0'>
			 <?php echo $v_file; ?>
			</A>
			</font></td>
            <td><?php echo filesize($v_file_full); ?></td><td></td>
          </tr>
<?php   }
      }
      // ----- Look for dir
      elseif ($p_list['childs'][$i]['type'] == 'dir') {
?>
          <tr>
		    <td width=10></td>
            <td width=10></td>
            <td><font face='Verdana, Arial, Helvetica, sans-serif' size=2>
            <A
<?php
            if ($a_type == "dir") {
?>
              onClick='PcjsExplorerSelectDir("<?php echo $v_file; ?>");'
<?php
            }
?>
            onDblClick='PcjsExplorerOpendir("<?php echo $v_file_full; ?>");'
             title='<?php echo $v_file_full; ?>'>
            <img src='images/folder01-16.gif' border='0' hspace='0' vspace='0'>
			 <?php echo $v_file; ?>
			</A>
			</font></td>
            <td>-</td><td></td>
          </tr>
<?php
      }
      // ----- Look for unknown
      else {
      }
    }
	
?>
</table>

</div>

<table width=100% border=0  cellspacing=0 cellpadding=0>
<tr>
<td width=20></td>
<td>
<font face="Verdana, Arial, Helvetica, sans-serif" size=2>
<? echo ($a_type=="file"?"File":"Directory")." :"; ?>
<INPUT TYPE="TEXT"  name="item" value="<? /*echo $p_dir;*/ ?>" align="middle" size="30" maxlength="255"   title="Selected directory">
</font>
</td>
<td width=20></td>
</tr>
</table>

<p align=center>
<INPUT TYPE="SUBMIT" value="Select"
       onclick="PcjsExplorerSelectCheck(); return false;">
<INPUT TYPE="SUBMIT" value="Cancel"
       onclick="PcjsExplorerClose(); return false;">
</p>

  <input type="hidden" name="dir" value="">
  <input type="hidden" name="event" value=<?php echo ($a_type!="file"?"open_dir":"select_file"); ?>>

</form>
<p align=right><font face="Verdana, Arial, Helvetica, sans-serif" size=1>
Powered by <a href="http://www.phpconcept.net" target=_blank><? echo PCSEXPLORER_NAME." ".PCSEXPLORER_VERSION; ?></a>
</font></p>

</HTML>

<?

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerCheckFileBaseDir()
  // Description :
  // Parameters :
  // Return Values :
  //   1 : OK
  //   0 : Error
  // --------------------------------------------------------------------------------
  function PcsExplorerCheckFileBaseDir($p_filename)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerCheckFileBaseDir", "file='".$p_filename."'");

	// ----- Look if valid with open_base_dir restriction
    $v_restriction
	= PcsExplorerUtilPathInclusion($_SESSION['pcsexplorer']['base_dir'],
	                               $p_filename);

    if ($v_restriction == 0) {
      PclTraceFctEnd(__FILE__, __LINE__, 0, "File is open_base_dir protected");
      return 0;
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, 1);
    return 1;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerCheckFileExtensions()
  // Description :
  // Parameters :
  // Return Values :
  //   1 : OK
  //   0 : Error
  // --------------------------------------------------------------------------------
  function PcsExplorerCheckFileExtensions($p_filename)
  {
    $v_result = 1;
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerCheckFileExtensions", "file='".$p_filename."'");

    // ----- Get pathinfo
    $v_path = pathinfo($p_filename);

    // ----- Look for accepted extensions
    if (   (($n=sizeof($_SESSION['pcsexplorer']['extensions'])) > 0)
	    && (isset($v_path['extension']))) {
      $v_result = 0;
      PclTraceFctMessage(__FILE__, __LINE__, 4, "Compare file extension '".$v_path['extension']."'");
	  for ($i=0; ($i<$n) && (!$v_result); $i++) {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Compare with '".$_SESSION['pcsexplorer']['extensions'][$i]."'");
	  	if ($v_path['extension'] == $_SESSION['pcsexplorer']['extensions'][$i]) {
		  $v_result = 1;
	  	}
	  }
	  if ($v_result == 0) {
        PclTraceFctEnd(__FILE__, __LINE__, 0, "File does not have a supported extension");
        return 0;
	  }
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerCheckFileDir()
  // Description :
  // Parameters :
  // Return Values :
  //   1 : OK
  //   0 : Error
  // --------------------------------------------------------------------------------
  function PcsExplorerCheckFileDir($p_filename)
  {
    $v_result = 1;
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerCheckFileDir", "file='".$p_filename."'");

	// ----- Look for hidden dir list
    if (($n=sizeof($_SESSION['pcsexplorer']['hidden_dir'])) > 0) {
      $v_result = 1;
      PclTraceFctMessage(__FILE__, __LINE__, 4, "Look for hidden dir");
	  for ($i=0; ($i<$n) && ($v_result); $i++) {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Check hidden dir '".$_SESSION['pcsexplorer']['hidden_dir'][$i]."'");
	  	if (PcsExplorerUtilPathInclusion($_SESSION['pcsexplorer']['hidden_dir'],
	                                     $p_filename) == 0) {
		  $v_result = 0;
	  	}
	  }
	  if ($v_result == 0) {
        PclTraceFctEnd(__FILE__, __LINE__, 0, "File is in an hidden dir");
        return 0;
	  }
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerCheckFileAccess()
  // Description :
  // Parameters :
  // Return Values :
  //   1 : OK
  //   0 : Error
  // --------------------------------------------------------------------------------
  function PcsExplorerCheckFileAccess($p_filename)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerCheckAccess", "file='".$p_filename."'");

	// ----- Look if valid with open_base_dir restriction
	if (PcsExplorerCheckFileBaseDir($p_filename) != 1) {
      PclTraceFctEnd(__FILE__, __LINE__, 0, "File is open_base_dir protected");
      return 0;
    }

	// ----- Look for supported extensions
	if (PcsExplorerCheckFileExtensions($p_filename) != 1) {
      PclTraceFctEnd(__FILE__, __LINE__, 0, "File does not have valid extensions");
      return 0;
    }

	// ----- Look for hidden dir list
	if (PcsExplorerCheckFileDir($p_filename) != 1) {
      PclTraceFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

	// ----- Look for hidden files (regexp)

	// ----- Look if it is a file
    if (!is_file($p_filename)) {
      PclTraceFctEnd(__FILE__, __LINE__, 0, "Unknown file with that name");
      return 0;
    }

	// ----- Look if readable file
    if (!is_readable($p_filename)) {
      PclTraceFctEnd(__FILE__, __LINE__, 0, "File is read protected");
      return 0;
    }

	// ----- Look if writable file
	/* need to add this for "save as ..." mode
    if (!is_writeable($filename)) {
      PclTraceFctEnd(__FILE__, __LINE__, 0, "File is read protected");
      return 0;
    }
    */

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, 1);
    return 1;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerScanDir()
  // Description :
  // Parameters :
  // Return Values :
  //   1 : OK
  //   0 : Error
  // --------------------------------------------------------------------------------
  function PcsExplorerScanDir($p_dir, &$p_list)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerScanDir", "dir='".$p_dir."'");
    $v_result = 1;

	// ----- Reset the list
	$p_list = array();

	// ----- Look for current directory properties
	$p_list['current_dir']['filename'] = $p_dir;
	// TBC
	$p_list['current_dir']['mode'] = 'rw';  // ro, rw, na
	$p_list['current_dir']['shortname'] = basename($p_dir);

    // ----- Check open_base_dir restriction
    $v_restriction
	= PcsExplorerUtilPathInclusion($_SESSION['pcsexplorer']['base_dir'],
	                               $p_list['current_dir']['filename']);

    if ($v_restriction == 0) {
      PclTraceFctMessage(__FILE__, __LINE__, 4, "Current directory in open_base_dir protected");
	  $p_list['current_dir']['mode'] = 'na';
    }

	// ----- Set default parent properties
	$p_list['parent_dir']['filename'] = "";
	$p_list['parent_dir']['mode'] = 'na';  // ro, rw, na

	// ----- Look for childs
	$p_list['childs'] = array();

    // ----- Scan the current directory
    if (   ($p_list['current_dir']['mode'] == 'na')
	    || (!($v_hdir = @opendir($p_dir)))) {
      PclTraceFctMessage(__FILE__, __LINE__, 4, "Unable to open dir : change mode to 'na'");
	  $p_list['current_dir']['mode'] = 'na';

      // ----- Return
      PclTraceFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Look for each entry in the directory
    for ($i=0; $v_file = readdir($v_hdir); $i++) {
	  // ----- Look for current dir
	  if ($v_file == ".") {
	    continue;
	  }

      // ----- Look for parent directory
      if ($v_file == "..") {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Parent directory");

        // ----- Look for first parent

        // TBC : The problem is to find the parent
        // problems with c:/toto and /toto ...

        if (   ($p_dir == "..")
		    || ((substr($p_dir, 0,2)=="..") && (substr($p_dir, -2)==".."))) {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "First parent found");
          $v_file_full = "../".$p_dir;
        }
        // ----- Look for the current dir
        else if ($p_dir == ".") {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "Current dir found");
          $v_file_full = "..";
        }
        // ----- Look for other path
        else {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "Remove last part of the path '$p_dir'");

          // ----- Remove the last part of the path
          $temp = strrchr($p_dir, "/");
          $v_file_full = substr($p_dir, 0, strlen($p_dir)-strlen($temp));
          unset($temp);
        }

        PclTraceFctMessage(__FILE__, __LINE__, 4, "Calculated path for parent is : '$v_file_full'");

        // ----- Set the parent full name
	    $p_list['parent_dir']['filename'] = $v_file_full;
	    $p_list['parent_dir']['shortname'] = basename($v_file_full);
	    $p_list['parent_dir']['mode'] = 'rw';

        // ----- Check open_base_dir restriction
        $v_restriction
		= PcsExplorerUtilPathInclusion($_SESSION['pcsexplorer']['base_dir'],
		                               $v_file_full);
        if ($v_restriction == 0) {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "Parent directory in open_base_dir protected");
	      $p_list['parent_dir']['mode'] = 'na';
        }
      }

      // ----- Look for normal file or directory
      else {
        // ----- Compose the full name
        $v_file_full = $p_dir."/".$v_file;

        // ----- Look for item type
        if (is_dir($v_file_full)) {
          // ----- Set directory type
          PclTraceFctMessage(__FILE__, __LINE__, 4, "'".$v_file_full."' is a directory");
        }
        elseif (is_file($v_file_full)) {
          // ----- Set file type
          PclTraceFctMessage(__FILE__, __LINE__, 4, "'".$v_file_full."' is a file");
          
          // ----- Check access rights
          if (PcsExplorerCheckFileAccess($v_file_full)) {
            PclTraceFctMessage(__FILE__, __LINE__, 4, "File can be accessed");
		    $j = sizeof($p_list['childs']);
	        $p_list['childs'][$j]['filename'] = $v_file_full;
	        $p_list['childs'][$j]['shortname'] = $v_file;
	        $p_list['childs'][$j]['type'] = 'file';
	        $p_list['childs'][$j]['mode'] = 'rw';
	        continue;
		  }
		  else {
            PclTraceFctMessage(__FILE__, __LINE__, 4, "File is protected");
	        continue;
          }
        }
        else {
          // ----- Set directory type
          PclTraceFctMessage(__FILE__, __LINE__, 4, "'".$v_file_full."' is unknown type");
        }

// TBC : should be removed

		// ----- Look for childs list size
		$j = sizeof($p_list['childs']);
	    $p_list['childs'][$j]['filename'] = $v_file_full;
	    $p_list['childs'][$j]['shortname'] = $v_file;

        PclTraceFctMessage(__FILE__, __LINE__, 4, "Calculated full path is : '$v_file_full'");

        // ----- Set item type
        if (is_dir($v_file_full)) {
          // ----- Set directory type
          PclTraceFctMessage(__FILE__, __LINE__, 4, "'$v_file_full' is a directory");
	      $p_list['childs'][$j]['type'] = 'dir';
        }
        elseif (is_file($v_file_full)) {
          // ----- Set file type
          PclTraceFctMessage(__FILE__, __LINE__, 4, "'$v_file_full' is a file");
	      $p_list['childs'][$j]['type'] = 'file';
        }
        else {
          // ----- Set directory type
          PclTraceFctMessage(__FILE__, __LINE__, 4, "'$v_file_full' is unknown type");
	      $p_list['childs'][$j]['type'] = 'unknown';
        }

        // ----- Look access rights
        if (is_writeable($v_file_full)) {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "is writable");
          $p_list['childs'][$j]['mode'] = 'rw';
        }
        elseif (is_readable($v_file_full)) {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "is readable");
          $p_list['childs'][$j]['mode'] = 'ro';
        }
        else {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "is not readable");
          $p_list['childs'][$j]['mode'] = 'na';
        }

        // ----- Look for filtering
        if (   ($p_list['childs'][$j]['type'] == 'file')
			&& ($_SESSION['pcsexplorer']['type'] == 'dir')) {
	        $p_list['childs'][$j]['mode'] = 'filtered';
		}
      }
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerPathReduction()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerPathReduction($p_dir)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerPathReduction", "dir='$p_dir'");
    $v_result = "";
    
    $v_result = PclZipUtilPathReduction($p_dir);
    
    /*

    // ----- Look for not empty path
    if ($p_dir != "")
    {
      // ----- Explode path by directory names
      $v_list = explode("/", $p_dir);
      $v_path = array();

      // ----- Study directories from last to first
      for ($i=0, $j=0; $i<sizeof($v_list); $i++)
      {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Looking for path i=$i '".$v_list[$i]."', j=$j");

        // ----- Look for current path
        if (($v_list[$i] == ".") && ($i != 0))
        {
          // ----- Ignore this directory only if not the first
        }
        else if ($v_list[$i] == "..")
        {
          // ----- Ignore the last entry in the path (if at least one entry)
          $j = ($j>0?$j-1:0);
          PclTraceFctMessage(__FILE__, __LINE__, 4, ".. found j=$j");
        }
        else if (($v_list[$i] == "") && ($i!=(sizeof($v_list)-1)) && ($i!=0))
        {
          // ----- Ignore only the double '//' in path,
          // but not the first and last '/'
        }
        else
        {
          PclTraceFctMessage(__FILE__, __LINE__, 4, "Set j=$j to '".$v_list[$i]."'");
          $v_path[$j++] = $v_list[$i];
        }
      }
    }

    // ----- Compose the full path
    if ($j>0) {
      $v_result = $v_path[0];
      for ($i=1; $i<$j; $i++)
        $v_result = $v_result."/".$v_path[$i];
    }
    else
      $v_result = "";
      
      */

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilPathReduction()
  // --------------------------------------------------------------------------------
  function PclZipUtilPathReduction($p_dir)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilPathReduction", "dir='$p_dir'");
    $v_result = "";

    // ----- Look for not empty path
    if ($p_dir != "") {
      // ----- Explode path by directory names
      $v_list = explode("/", $p_dir);

      // ----- Study directories from last to first
      $v_skip = 0;
      for ($i=sizeof($v_list)-1; $i>=0; $i--) {
        // ----- Look for current path
        if ($v_list[$i] == ".") {
          // ----- Ignore this directory
          // Should be the first $i=0, but no check is done
        }
        else if ($v_list[$i] == "..") {
		  $v_skip++;
        }
        else if ($v_list[$i] == "") {
		  // ----- First '/' i.e. root slash
		  if ($i == 0) {
            $v_result = "/".$v_result;
		    if ($v_skip > 0) {
		        // ----- It is an invalid path, so the path is not modified
		        // TBC
		        $v_result = $p_dir;
                PclTraceFctMessage(__FILE__, __LINE__, 3, "Invalid path is unchanged");
                $v_skip = 0;
		    }
		  }
		  // ----- Last '/' i.e. indicates a directory
		  else if ($i == (sizeof($v_list)-1)) {
            $v_result = $v_list[$i];
		  }
		  // ----- Double '/' inside the path
		  else {
            // ----- Ignore only the double '//' in path,
            // but not the first and last '/'
		  }
        }
        else {
		  // ----- Look for item to skip
		  if ($v_skip > 0) {
		    $v_skip--;
		  }
		  else {
            $v_result = $v_list[$i].($i!=(sizeof($v_list)-1)?"/".$v_result:"");
		  }
        }
      }

      // ----- Look for skip
      if ($v_skip > 0) {
        while ($v_skip > 0) {
            $v_result = '../'.$v_result;
            $v_skip--;
        }
      }
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerPathRelative()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PcsExplorerPathRelative($p_from_dir, $p_path)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerPathRelative", "from_dir='$p_from_dir', path='$p_path'");
    $v_result = "";
    
    // ----- Quick checks
    if (($p_from_dir == '') || ($p_path == '')) {
      PclTraceFctEnd(__FILE__, __LINE__, $p_path);
      return $p_path;
    }

    // ----- Explode the paths
    $v_from = explode("/", $p_from_dir);
    $v_path = explode("/", $p_path);

    // ----- Look for common part of the path
    $i=0;
    while (   ($i < sizeof($v_from))
	       && ($i < sizeof($v_path))
		   && ($v_from[$i] == $v_path[$i])) {
      PclTraceFctMessage(__FILE__, __LINE__, 4, "Same Item $i '".$v_from[$i]."'");
      $i++;
    }

    // ----- Look for path to remove
    for ($j=$i; $j< sizeof($v_from); $j++) {
      if (($v_from[$j] != "") && ($v_from[$j] != ".")) {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Remove Item $j '".$v_from[$j]."'");
        $v_result = $v_result."../";
      }
      else {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Ignore Item $j '".$v_from[$j]."'");
      }
    }

    // ----- Look for path to add
    for ($j=$i; $j<sizeof($v_path); $j++) {
      if (($v_path[$j] != "") && ($v_path[$j] != ".")) {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Add Item $j '".$v_path[$j]."'");
        $v_result = $v_result.$v_path[$j];
        if ($j<(sizeof($v_path)-1)) {
          $v_result = $v_result."/";
        }
      }
      else {
        PclTraceFctMessage(__FILE__, __LINE__, 4, "Ignore Item $j '".$v_path[$j]."'");
      }
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerImageFromExtension()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function PcsExplorerImageFromExtension($p_file)
  {
    $v_image = "file01-16.gif";
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerImageFromExtension", "file='$p_file'");

    // ----- Get extension
    $v_extension = substr(strrchr($p_file, '.'), 1);

    // ----- Look for image
    if (is_file("images/file-".$v_extension."-16.gif"))
      $v_image = "file-".$v_extension."-16.gif";

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_image);
    return $v_image;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcsExplorerUtilPathInclusion()
  // Description :
  //   This function indicates if the path $p_path is under the $p_dir tree. Or,
  //   said in an other way, if the file or sub-dir $p_path is inside the dir
  //   $p_dir.
  //   The function indicates also if the path is exactly the same as the dir.
  //   This function supports path with duplicated '/' like '//', but does not
  //   support '.' or '..' statements.
  // Parameters :
  // Return Values :
  //   0 if $p_path is not inside directory $p_dir
  //   1 if $p_path is inside directory $p_dir
  //   2 if $p_path is exactly the same as $p_dir
  // --------------------------------------------------------------------------------
  function PcsExplorerUtilPathInclusion($p_dir, $p_path)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PcsExplorerUtilPathInclusion", "dir='$p_dir', path='$p_path'");
    $v_result = 1;

    $v_result = PclZipUtilPathInclusion($p_dir, $p_path);
    
    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
    
    /*

    // ----- Explode dir and path by directory separator
    $v_list_dir = explode("/", $p_dir);
    $v_list_dir_size = sizeof($v_list_dir);
    $v_list_path = explode("/", $p_path);
    $v_list_path_size = sizeof($v_list_path);

    // ----- Study directories paths
    $i = 0;
    $j = 0;
    while (($i < $v_list_dir_size) && ($j < $v_list_path_size) && ($v_result)) {
      PclTraceFctMessage(__FILE__, __LINE__, 5, "Working on dir($i)='".$v_list_dir[$i]."' and path($j)='".$v_list_path[$j]."'");

      // ----- Look for empty dir (path reduction)
      if ($v_list_dir[$i] == '') {
        $i++;
        continue;
      }
      if ($v_list_path[$j] == '') {
        $j++;
        continue;
      }

      // ----- Compare the items
      if (($v_list_dir[$i] != $v_list_path[$j]) && ($v_list_dir[$i] != '') && ( $v_list_path[$j] != ''))  {
        PclTraceFctMessage(__FILE__, __LINE__, 5, "Items ($i,$j) are different");
        $v_result = 0;
      }

      // ----- Next items
      $i++;
      $j++;
    }

    // ----- Look if everything seems to be the same
    if ($v_result) {
      PclTraceFctMessage(__FILE__, __LINE__, 5, "Look for tie break");
      // ----- Skip all the empty items
      while (($v_list_path[$j] == '') && ($j < $v_list_path_size)) $j++;
      while (($v_list_dir[$i] == '') && ($i < $v_list_dir_size)) $i++;
      PclTraceFctMessage(__FILE__, __LINE__, 5, "Looking on dir($i)='".$v_list_dir[$i]."' and path($j)='".$v_list_path[$j]."'");

      if (($i >= $v_list_dir_size) && ($j >= $v_list_path_size)) {
        // ----- There are exactly the same
        $v_result = 2;
      }
      else if ($i < $v_list_dir_size) {
        // ----- The path is shorter than the dir
        $v_result = 0;
      }
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
    */
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclZipUtilPathInclusion()
  // Description :
  //   This function indicates if the path $p_path is under the $p_dir tree. Or,
  //   said in an other way, if the file or sub-dir $p_path is inside the dir
  //   $p_dir.
  //   The function indicates also if the path is exactly the same as the dir.
  //   This function supports path with duplicated '/' like '//', but does not
  //   support '.' or '..' statements.
  // Parameters :
  // Return Values :
  //   0 if $p_path is not inside directory $p_dir
  //   1 if $p_path is inside directory $p_dir
  //   2 if $p_path is exactly the same as $p_dir
  // --------------------------------------------------------------------------------
  function PclZipUtilPathInclusion($p_dir, $p_path)
  {
    PclTraceFctStart(__FILE__, __LINE__, "PclZipUtilPathInclusion", "dir='$p_dir', path='$p_path'");
    $v_result = 1;

    // ----- Explode dir and path by directory separator
    $v_list_dir = explode("/", $p_dir);
    $v_list_dir_size = sizeof($v_list_dir);
    $v_list_path = explode("/", $p_path);
    $v_list_path_size = sizeof($v_list_path);

    // ----- Study directories paths
    $i = 0;
    $j = 0;
    while (($i < $v_list_dir_size) && ($j < $v_list_path_size) && ($v_result)) {
      PclTraceFctMessage(__FILE__, __LINE__, 5, "Working on dir($i)='".$v_list_dir[$i]."' and path($j)='".$v_list_path[$j]."'");

      // ----- Look for empty dir (path reduction)
      if ($v_list_dir[$i] == '') {
        $i++;
        continue;
      }
      if ($v_list_path[$j] == '') {
        $j++;
        continue;
      }

      // ----- Compare the items
      if (($v_list_dir[$i] != $v_list_path[$j]) && ($v_list_dir[$i] != '') && ( $v_list_path[$j] != ''))  {
        PclTraceFctMessage(__FILE__, __LINE__, 5, "Items ($i,$j) are different");
        $v_result = 0;
      }

      // ----- Next items
      $i++;
      $j++;
    }

    // ----- Look if everything seems to be the same
    if ($v_result) {
      PclTraceFctMessage(__FILE__, __LINE__, 5, "Look for tie break");
      // ----- Skip all the empty items
      while (($j < $v_list_path_size) && ($v_list_path[$j] == '')) $j++;
      while (($i < $v_list_dir_size) && ($v_list_dir[$i] == '')) $i++;
      PclTraceFctMessage(__FILE__, __LINE__, 5, "Looking on dir($i)='".($i < $v_list_dir_size?$v_list_dir[$i]:'')."' and path($j)='".($j < $v_list_path_size?$v_list_path[$j]:'')."'");

      if (($i >= $v_list_dir_size) && ($j >= $v_list_path_size)) {
        // ----- There are exactly the same
        $v_result = 2;
      }
      else if ($i < $v_list_dir_size) {
        // ----- The path is shorter than the dir
        $v_result = 0;
      }
    }

    // ----- Return
    PclTraceFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // ---------------------------------------------------------------------------
  // State Engine
  // ---------------------------------------------------------------------------

  // ----- Look for no event
  if (!isset($_POST['event'])) {
    $_POST['event'] = '';
  }
  
  // ----- Trace
  PclTraceMessage(__FILE__, __LINE__, 2, "Received event='".$_POST['event']."'");

  // ----- Main event loop
  switch ($_POST['event']) {
    case "open_dir" :
      PcsExplorerOpenDir($_POST);
      break;
    case "select_dir" :
      PcsExplorerSelectDir($_POST);
      break;
    case "select_file" :
      PcsExplorerSelectFile($_POST);
      break;

    case "start" :
      PcsExplorerStart($_POST);
      break;

    default :
      PclTraceMessage(__FILE__, __LINE__, 2, "No event received, use default event");
      PcsExplorerStart($_POST);
  }

  // ----- End of trace
  PclTraceDisplay();
  //var_dump($_SESSION);

?>
