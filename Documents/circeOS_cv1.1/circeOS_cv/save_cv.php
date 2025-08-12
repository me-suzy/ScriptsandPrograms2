<?

/**************************************************/
/*                                                */
/*  save_cv.php                                   */
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

if ($action == "update"){

	if (!isset ($cv_file) || $cv_file == ""){   // il file cv non è cambiato

		$sql = "UPDATE ITW_CURRICULUM SET CV_SURNAME='$surname', CV_NAME='$name', CV_YEAR='$year', CV_ROLE='$role', CV_EXP_YEARS='$exp_years', CV_MONEY='$money', CV_EXP_MONEY='$exp_money', CV_LEVEL='$level', CV_EMAIL='$email', CV_TEL='$tel', CV_NOTE='$note', CV_USER_INTERVIEW='$interviewer', CV_USER_CREATOR='$user_creator', CV_DATE_INTERVIEW='$date_interview', CV_EXP_CONTRACT='$exp_contract', CV_ACT_CONTRACT='$act_contract', CV_EMPLOY_code='$employ_code', CV_UNIT='$unit', CV_COMPANY='$company', CV_VALUTATION='$valutation' WHERE CV_ID='$cv_id'";

		$stmt=OCIParse($conn,$sql);
		if(!$stmt){
			die ("Errore Query Parse");
		}
		$execute=OCIExecute($stmt);
		if(!$execute){
			die ("Errore Query Execute");
		}

	} else {   // il file cv è cambiato

		// CANCELLAZIONE DEL RECORD

		$sql = "DELETE FROM ITW_CURRICULUM WHERE CV_ID='$cv_id'";

		$stmt=OCIParse($conn,$sql);
		if(!$stmt){
			die ("Errore Query Parse");
		}
		$execute=OCIExecute($stmt);
		if(!$execute){
			die ("Errore Query Execute");
		}
		
		// REINSERIMENTO DEL RECORD

		$file_name = $cv_file_name;

		$arr_exp = explode (".", $cv_file_name);
		$file_type =  strtolower ($arr_exp[count($arr_exp) - 1]);
	
		$sql = "INSERT INTO ITW_CURRICULUM (CV_ID, CV_FILE_IMAGE, CV_FILE_NAME, CV_FILE_TYPE, CV_SURNAME, CV_NAME, CV_ROLE, CV_YEAR, CV_EXP_YEARS, CV_MONEY, CV_EXP_MONEY, CV_LEVEL, CV_EMAIL, CV_TEL, CV_NOTE, CV_USER_CREATOR, CV_DATE, CV_USER_INTERVIEW, CV_DATE_INTERVIEW, CV_EXP_CONTRACT, CV_ACT_CONTRACT, CV_EMPLOY_CODE, CV_COMPANY, CV_UNIT, CV_VALUTATION) VALUES ('$cv_id', EMPTY_BLOB(), '$file_name', '$file_type',  '$surname', '$name', '$role', '$year', '$exp_years', '$money', '$exp_money', '$level', '$email', '$tel', '$note', '$user_creator', '$date_creation', '$interviewer', '$date_interview', '$exp_contract', '$act_contract', '$employ_code', '$unit', '$company', '$valutation') RETURNING CV_FILE_IMAGE INTO :CV_FILE_IMAGE";

		$lob = OCINewDescriptor($conn, OCI_D_LOB);

		$stmt=OCIParse($conn, $sql);
		if(!$stmt){
			die ("Errore Query Parse");
		}

		OCIBindByName($stmt, ':CV_FILE_IMAGE', $lob, -1, OCI_B_BLOB);
			 
		$execute=OCIExecute($stmt, OCI_DEFAULT);
		if(!$execute){
			die ("Errore Query Execute");
		}

		if ($lob->savefile($cv_file)) {
			echo "<font size=2 color=navy face=verdana>CV file successfully uploaded</font>\n";
		} else {
			echo "<font size=2 color=navy face=verdana>Could not upload CV file</font>\n";
		}

		$lob->free();

	}
	
} else {

	$date = date("Y-m-d");
	$cv_id = date("YmdHis").rand (10,99);

	$file_name = $cv_file_name;

	$arr_exp = explode (".", $cv_file_name);
	$file_type = strtolower ($arr_exp[count($arr_exp) - 1]);

	$sql = "INSERT INTO ITW_CURRICULUM (CV_ID, CV_FILE_IMAGE, CV_FILE_NAME, CV_FILE_TYPE, CV_SURNAME, CV_NAME, CV_ROLE, CV_YEAR, CV_EXP_YEARS, CV_MONEY, CV_EXP_MONEY, CV_LEVEL, CV_EMAIL, CV_TEL, CV_NOTE, CV_USER_CREATOR, CV_DATE, CV_USER_INTERVIEW, CV_DATE_INTERVIEW, CV_EXP_CONTRACT, CV_ACT_CONTRACT, CV_EMPLOY_CODE, CV_COMPANY, CV_UNIT, CV_VALUTATION) VALUES ('$cv_id', EMPTY_BLOB(), '$file_name', '$file_type', '$surname', '$name', '$role', '$year', '$exp_years', '$money', '$exp_money', '$level', '$email', '$tel', '$note', '$user_creator', '$date', '$interviewer', '$date_interview', '$exp_contract', '$act_contract', '$employ_code', '$company', '$unit', '$valutation') RETURNING CV_FILE_IMAGE INTO :CV_FILE_IMAGE";

	$lob = OCINewDescriptor($conn, OCI_D_LOB);

	$stmt=OCIParse($conn, $sql);
	if(!$stmt){
		die ("Errore Query Parse");
	}

	OCIBindByName($stmt, ':CV_FILE_IMAGE', $lob, -1, OCI_B_BLOB);
         
	$execute=OCIExecute($stmt, OCI_DEFAULT);
	if(!$execute){
		die ("Errore Query Execute");
	}

	if ($lob->savefile($cv_file)) {
		echo "<font size=2 color=navy face=verdana>CV file successfully uploaded</font>\n";
	} else {
		echo "<font size=2 color=navy face=verdana>Could not upload CV file</font>\n";
	}

	$lob->free();

} 

OCICommit($conn);
@OCIFreeStatement($stmt);


$sql = "alter index ITW_CV_INDEX rebuild online parameters('sync memory 50M')";

$stmt = OCIparse($conn, $sql);
OCIexecute($stmt,OCI_DEFAULT);

OCICommit($conn);
OCIFreeStatement($stmt);

@OCILogoff($conn);


echo "<script>alert ('Curriculum Saved and Oracle Index Updated!!');</script>";
echo "<HTML><META HTTP-EQUIV='REFRESH' CONTENT='0; URL=index.php'><BODY></BODY></HTML>";
exit;


?>