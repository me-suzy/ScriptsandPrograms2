<? include("inc/global.inc.php")?>
<? include("inc/top.inc.php")?>
  <div id="header"><h1>XLS2Mysql : convert Excel Sheets to Mysql data</h1>
  <ul>
   <li > <a href=""  onclick="return showPanel(this, 'panel1');">File upload</a></li>
    <li id="current">  <a href=""  onclick="return showPanel(this, 'panel2');"  id="current_tab">File List</a></li>
	</ul>
	</div>



    <div class="panel" id="panel1" style="display: none">
     <div style="margin-top:20px; margin-left:20px;">
	
	Single File Upload <table><form name="frm_file"  action="<?=$process_page?>" method="post"  enctype="multipart/form-data" >
	 <tr><td>
	 
	 Please browse your Excel file<br>
 and press send</td></tr>
	 
	 <tr><td>
	 <input type=file name=xls_file /><div align=right>
	 <input type="submit"   value="Send"/></div>
	 </td></tr>
	 <tr><td>Max file size: <?=ini_get('upload_max_filesize');?></td></tr>
	 </form></table>
	 </div>
	 
	 
    </div>
	
	<br />
    <div class="panel" id="panel2" style="display: block">
        
		<?
		function LoadFiles($dir)
{
 $Files = array();
 $It =  opendir($dir);
 if (! $It)
  die('Cannot list files for ' . $dir);
 while ($Filename = readdir($It))
 {
  if ($Filename == '.' || $Filename == '..')
   continue;
  $LastModified = filemtime($dir . $Filename);
  $Size = filesize($dir . $Filename);
    $Files[] = array($dir .$Filename, $Size, $LastModified);
 }

  return $Files;
}
function DateCmp($a, $b)
{
  return ($a[1] < $b[1]) ? -1 : 0;
}

function SortByDate(&$Files)
{
  usort($Files, 'DateCmp');
}

$arrFiles = LoadFiles($xls_dir);
SortByDate($arrFiles);
 echo '<div style="margin-top:20px; margin-left:20px;">Click on the filename to convert it : <table cellpadding=2 border=1 cellspacing=0>';
while (list($k,$v) = each($arrFiles))
	{
	$file = str_replace($xls_dir,"", $v[0]);
	$url= "<a href=$process_page?xlsFile=$file>";
	echo "<tr><td>$url" . $file . "</a></td><td>" . round( $v[1]/1024) . " Ko</td><td>" .  date('Y/m/d h:n',$v[2]). "</td></tr>";
	}
	 echo "</table></div>";	
		?>
		
		
		
    </div>
	
	
	
	
<?include("inc/bottom.inc.php")?>
