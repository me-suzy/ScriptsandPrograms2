<?

/**************************************************/
/*                                                */
/*  build_index.php                               */
/*                                                */
/*                                                */
/**************************************************/

require_once("config.inc.php"); 

$conn = OCILogon($CV_ORACLE_db_user, $CV_ORACLE_db_password, $CV_ORACLE_db_name);
if ($conn == FALSE)
	die ("DB Oracle Connection Error");

$sql = "drop index ITW_CV_INDEX";

$stmt = OCIparse($conn, $sql);
OCIexecute($stmt,OCI_DEFAULT);

$sql = "create index ITW_CV_INDEX on ITW_CURRICULUM ( CV_FILE_IMAGE ) indextype is ctxsys.context";

$stmt = OCIparse($conn, $sql);
OCIexecute($stmt,OCI_DEFAULT);

OCICommit($conn);

OCIFreeStatement($stmt);
OCILogOff($conn);

echo "<script>alert ('DB Index Built !!');</script>";
echo "<HTML><META HTTP-EQUIV='REFRESH' CONTENT='0; URL=search_cv.php'><BODY></BODY></HTML>";

exit;

?>



