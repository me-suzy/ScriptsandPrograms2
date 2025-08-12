
<?

/**************************************************/
/*                                                */
/*  delete_cv.php                                 */
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

$sql = "DELETE FROM ITW_CURRICULUM WHERE CV_ID = '$cv_id'";

$stmt = OCIparse($conn, $sql);
OCIexecute($stmt,OCI_DEFAULT);

OCICommit($conn);

OCIFreeStatement($stmt);
OCILogOff($conn);

echo "<script>alert ('Curriculum Deleted !!');</script>";
echo "<HTML><META HTTP-EQUIV='REFRESH' CONTENT='0; URL=index.php'><BODY></BODY></HTML>";

exit;
?>


