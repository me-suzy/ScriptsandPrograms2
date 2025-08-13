<?php
@set_time_limit(600);
$admin = 1;
$backup_inport = 1;

$configfile = "../../includes/config.php";
include($configfile);
include("dump_func.php");
include("../../includes/hierarchy_lib.php");


$dump="";
$tables = &$conn->MetaTables();
$num_tables = count($tables);

if($num_tables == 0)
	echo $la_backup_no_tables;
else
{	
    $i = 0;
	if ($sql_type == "postgres7") 
		$dump.=create_seq()."\n\n";
	$dump.=create_tables()."\n";
    while($i < $num_tables)
    { 
		if(ereg("^inl_",$tables[$i])>0)
			$dump .= insert_data($tables[$i])."\n";
        $i++;
    }
}

if($oncomp){
	header("Content-disposition: filename=db_dump.php");
	header("Content-type: application/octetstream");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $dump;
}
else{
	if(!$file=fopen("dump.txt","w"))
		echo "$la_backup_error_writing_file $filepath/admin/backup/dump.txt";
	else
	{	fputs($file, $dump);
		fclose($file);
	}
}
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<LINK rel="stylesheet" href="../admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">

<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="../images/icon8-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav7; ?></TD>
    <TD rowspan="2" width="0"><A href="../help/manual.pdf"><IMG src="../images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?action=logout" target="_top"><IMG src="../images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="../images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<br>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">

  <TR> 
      <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_backup ?></TD>
  </TR>

  <TR> 
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">

		  <TR> 
            <TD valign="top" align="middle"><SPAN class="text">
			
			
			
				<?php echo $la_success_backup; ?>		
			
			
			
			</SPAN></TD>
          </TR>
        </TABLE>
        
      </TD>
  </TR>
</TABLE>

</BODY>
</HTML>