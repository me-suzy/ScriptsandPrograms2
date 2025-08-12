<?

/**************************************************/
/*                                                */
/*  download_cv.php                               */
/*                                                */
/*                                                */
/**************************************************/
/*                                                */
/* site:   www.circeos.it                         */
/*                                                */
/**************************************************/

require_once("config.inc.php"); 

$conn = OCILogon($CV_ORACLE_db_user, $CV_ORACLE_db_password, $CV_ORACLE_db_name);
if ($conn == FALSE)
	die ("DB Oracle Connection Error");

$sql = "SELECT CV_FILE_IMAGE, CV_FILE_NAME FROM ITW_CURRICULUM WHERE CV_ID = '$cv_id'";

$stmt = OCIparse($conn, $sql);
OCIexecute($stmt, OCI_DEFAULT);

OCIFetchInto($stmt, $arr, OCI_ASSOC);

$file_name = $arr['CV_FILE_NAME'];
$result = $arr['CV_FILE_IMAGE']->load();

header ("Content-type: application/octet-stream");
header ("Content-Disposition: Attachment;filename=$file_name" );
echo $result;


OCIFreeStatement($stmt);
OCILogOff($conn);

exit;

?>



