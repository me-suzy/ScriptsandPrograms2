<?
$libdir="inc/";
require_once $libdir . 'Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
 $data->setUTFEncoder('mb');
 $data->read($xls_dir . $xlsFile);
 
 

 ?>Reading First Line for columns<br>
 <table><tr><?
 for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
 		{
		echo "<td class=s>".$data->sheets[0]['cells'][1][$j]."</td>";
		
		}
		?>
		</tr>
<?
 
 for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
 	echo "<tr>";
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo "<td class=s>".$data->sheets[0]['cells'][$i][$j]."</td>";
	}
	echo "</tr>";
}


?></table>