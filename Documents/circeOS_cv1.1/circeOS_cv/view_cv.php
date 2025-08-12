<?

/**************************************************/
/*                                                */
/*  view_cv.php                                   */
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

$sql = "SELECT CV_FILE_IMAGE, CV_FILE_TYPE FROM ITW_CURRICULUM WHERE CV_ID = '$cv_id'";

$stmt = OCIparse($conn, $sql);
OCIexecute($stmt, OCI_DEFAULT);

OCIFetchInto($stmt, $arr, OCI_ASSOC);

$result = $arr['CV_FILE_IMAGE']->load();
$doc_type = $arr ['CV_FILE_TYPE'];

switch ($doc_type){

	case ("doc"):
		$content_type = "application/msword";
		break;

	case ("rtf"):
		$content_type = "application/msword";
		break;

	case ("html"):
		$content_type = "text/html";
		break;

	case ("htm"):
		$content_type = "text/html";
		break;

	case ("xls"):
		$content_type = "application/vnd.ms-excel";
		break;

	case ("txt"):
		$content_type = "text/html";
		break;

	case ("pdf"):
		$content_type = "application/pdf";
		break;

	case ("ppt"):
		$content_type = "application/vnd.ms-powerpoint";
		break;

	default:
		break;

} // end switch

header("Content-type: $content_type");

echo $result;


OCIFreeStatement($stmt);
OCILogOff($conn);

exit;

?>



