<? include("inc/global.inc.php")?>
<? include("inc/top.inc.php")?>
  <div id="header"><h1>XLS2Mysql : convert Excel Sheets to Mysql data</h1>
  <?

  if (count($_FILES) )
  	{
		$filename= basename($_FILES['xls_file']['name']);
		$uploadfile = $xls_dir . $filename;


		if (move_uploaded_file($_FILES['xls_file']['tmp_name'], $uploadfile) ) {
   		$strConfirmUpload =  "File $filename was successfully uploaded.\n";
		$xlsFile=$filename;
		} else {
   		$strConfirmUpload=  " Problem uploading the file : " .  $uploadfile;
		}
	}



  ?>
		
  <ul>
   <li id="current"> <a href=""  onclick="return showPanel(this, 'panel1');" id="current_tab">Data</a></li>
    <li >  <a href=""  onclick="return showPanel(this, 'panel2');"  >Mysql</a></li>
	</ul>
	
  </div>
  
  
	<div class="panel" id="panel1" style="display: block"> 
	<div style="margin-top:20px; margin-left:20px;"><?=	$strConfirmUpload ?>
	
	<?
	
	
	if ($xlsFile!="") { if (!strstr($xlsFile, ".xls") ) {
		?><h1 class=msg><?=$xlsFile?> is not an Excel File.<br><a href=xls2mysql.php>Please go back and select a file</a>
		<? } else {?>
		<h1>Converting File <b><?=$xlsFile?></b> to mysql...</h1>
	
	<? include ("inc/xls2mysql_data.inc.php")?>
	
	
	<? }
	} else {?><h1 class=msg><a href=xls2mysql.php>Please go back and select a file</a><?}?>
	
	
	</div>
	</div>
	   <div class="panel" id="panel2" style="display: none">
	 <? include("inc/xls2mysql_sql.inc.php");?>
	   </div>
	
	
	
<?include("inc/bottom.inc.php")?>
