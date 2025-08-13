<html>
<title>Excel -> MySQL Sample</title>
<body>
<?php

include '../../ExcelExplorer.php';

if( $HTTP_POST_FILES['excel_file'] &&
   ($HTTP_POST_FILES['excel_file']['tmp_name'] != '') ) {

 $fsz = filesize($HTTP_POST_FILES['excel_file']['tmp_name']);
 $fh = @fopen ($HTTP_POST_FILES['excel_file']['tmp_name'],'rb');
 if( !$fh || ($fsz==0) )
  die('No file uploaded');
 $file = fread( $fh, $fsz );
 @fclose($fh);
 if( strlen($file) < $fsz )
  die('Cannot read the file');
} else {
 die('No file uploaded');
}

$ee = new ExcelExplorer;

switch ($ee->Explore($file)) {
 case 0:
  break;
 case 1:
  die('File corrupted or not in Excel 5.0 and above format');
 case 2:
  die('Unknown or unsupported Excel file version');
 default:
  die('ExcelExplorer give up');
}

require 'mysql.inc';

$mysql_link = @mysql_connect($mysql_server,$mysql_username,$mysql_password)
	or die('Could not connect to a database');

@mysql_select_db('exceldb',$mysql_link)
	or die('Could not select "exceldb" database');

mysql_query("delete from sheet");
mysql_query("delete from cell");

for( $sheet=0; $sheet<$ee->GetWorksheetsNum(); $sheet++ ) {
 mysql_query("insert into sheet (id,name) values ($sheet,'".
  addslashes($ee->AsIs($ee->GetWorksheetTitle($sheet)))."')");

if( !$ee->IsEmptyWorksheet($sheet) ) {

 for($col=0; $col<=$ee->GetLastColumnIndex($sheet); $col++) {

  if( !$ee->IsEmptyColumn($sheet,$col) ) {

   for($row=0; $row<=$ee->GetLastRowIndex($sheet); $row++) {

    if( !$ee->IsEmptyRow($sheet,$row) ) {

     $data = $ee->GetCellData($sheet,$col,$row);

     switch( $ee->GetCellType($sheet,$col,$row) ) {
      case 0:
      case 7:
      case 8:
       continue;
      case 1:
      case 3:
       break;
      case 2:
       $data = (100*$data).'%';
       break;
      case 4:
       $data = ($data ? 'TRUE' : 'FALSE');
       break;
      case 5:
       switch( $data ) {
        case 0x00:
         $data = "#NULL!";
         break;
        case 0x07:
         $data = "#DIV/0";
         break;
        case 0x0F:
         $data = "#VALUE!";
         break;
        case 0x17:
         $data = "#REF!";
         break;
        case 0x1D:
         $data = "#NAME?";
         break;
        case 0x24:
         $data = "#NUM!";
         break;
        case 0x2A:
         $data = "#N/A!";
         break;
        default:
         $data = "#UNKNOWN";
         break;
       }
       break;
      case 6:
       $data = $data['string'];
       break;
      default:
       break;
     }

     mysql_query("insert into cell (sheet,col,row,data) values ($sheet,$col,$row,'".
	addslashes($data)."')");
    }

   }

  }

 }
}
}

@mysql_close($mysql_link);

print ('All data stored in mysql tables "sheet" and "cell"');

?>
</body>
</html>