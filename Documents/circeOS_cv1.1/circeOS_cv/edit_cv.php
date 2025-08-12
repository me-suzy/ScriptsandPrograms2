 
<?
/**************************************************/
/*                                                */
/*  edit_cv.php                                   */
/*                                                */
/*                                                */
/**************************************************/
/*                                                */
/* site:   www.circeos.it                         */
/*                                                */
/**************************************************/

require_once ("config.inc.php"); 


if (isset ($cv_id)){
	$action = "update";

	$sql = "SELECT * from ITW_CURRICULUM WHERE CV_ID = '$cv_id'";

	$conn = OCILogon($CV_ORACLE_db_user, $CV_ORACLE_db_password, $CV_ORACLE_db_name);
	if ($conn == FALSE)
		die ("DB Oracle Connection Error");

	$stmt = OCIparse($conn, $sql);
	OCIexecute($stmt,OCI_DEFAULT);
	  
	while (OCIfetch($stmt)) {
			$surname = OCIresult($stmt, "CV_SURNAME");	
			$name = OCIresult($stmt, "CV_NAME");	
			$year = OCIresult($stmt, "CV_YEAR");	
			$exp_years = OCIresult($stmt, "CV_EXP_YEARS");	
			$role = OCIresult($stmt, "CV_ROLE");	
			$email = OCIresult($stmt, "CV_EMAIL");	
			$tel = OCIresult($stmt, "CV_TEL");	
			$creation_date = OCIresult($stmt, "CV_DATE");
			$user_creator = OCIresult($stmt, "CV_USER_CREATOR");
			$interview_date = OCIresult($stmt, "CV_DATE_INTERVIEW");	
			$interviewer = OCIresult($stmt, "CV_USER_INTERVIEW");	
			$valutation = OCIresult($stmt, "CV_VALUTATION");	
			$exp_contract = OCIresult($stmt, "CV_EXP_CONTRACT");	
			$act_contract = OCIresult($stmt, "CV_ACT_CONTRACT");	
			$company = OCIresult($stmt, "CV_COMPANY");	
			$employ_code = OCIresult($stmt, "CV_EMPLOY_CODE");	
			$unit = OCIresult($stmt, "CV_UNIT");	
			$valutation = OCIresult($stmt, "CV_VALUTATION");	
			$note = OCIresult($stmt, "CV_NOTE");	
			$level = OCIresult($stmt, "CV_LEVEL");	
			$money = OCIresult($stmt, "CV_MONEY");	
			$exp_money = OCIresult($stmt, "CV_EXP_MONEY");	
	} // end while

	OCIFreeStatement($stmt);
	OCILogOff($conn);

} else 
	$action = "new";

?>


<html>
<head>
<title><?echo $SITE_name?></title>
<LINK href="backend_style.css" type=text/css rel=stylesheet>

<script src="dateSelector.js"></script>

<script type="text/javascript">
// <!--
function form_control(theForm)
{
	
	if ( theForm.surname.value.length == 0 ) {
		alert( 'Please Insert Surname .' );
		theForm.surname.focus();
		return false;
	}
	if ( theForm.name.value.length == 0 ) {
		alert( 'Please Insert Name .' );
		theForm.name.focus();
		return false;
	}

<? if ($action == "new") {?>

	if ( theForm.cv_file.value.length == 0 ) {
		alert( 'Please Select a Document CV File .' );
		theForm.cv_file.focus();
		return false;
	}

<? } ?>
			
return true;
}
#-->
</script>

</head>
<body class="body">

<table class=PageTable  border="0" cellpadding="0" cellspacing="2" width="98%" align=center>
    <tr>
      <td  width="100%" align=center><b>Curriculum</b> <img src='images/new.gif' border=0 alt='CV'></td>
    </tr>
</table>
<hr>

<form enctype='multipart/form-data' name="edit_cv" method="post" action="save_cv.php?action=<?echo $action?>&cv_id=<?echo $cv_id?>" onSubmit="return form_control(this);">
<table  class=SecondTable align=center border="0" cellspacing="0" cellpadding="2" width="80%">

	<tr>
		<td align="right">
			Surname *
		</td>
		<td align="left">
			<input name="surname" value="<?echo $surname?>" size="50" maxlength="50">
		</td>
	</tr>

	<tr>
		<td align="right">
			Name *
		</td>
		<td align="left">
			<input name="name" value="<?echo $name?>" size="50" maxlength="50">
		</td>
	</tr>

	<tr>
		<td align="right">
			<hr>
			Curriculum Document
			<hr>
		</td>
		<td align="left">
			<input type='file' name='cv_file' size='30'>
		</td>
	</tr>

	<tr>
		<td align="right">
			Birth Year
		</td>
		<td align="left">
			<input name="year" value="<?echo $year?>" size="4" maxlength="4">
		</td>
	</tr>

	<tr>
		<td align="right">
			Years of Experience 
		</td>
		<td align="left">
			<input name="exp_years" value="<?echo $exp_years?>" size="4" maxlength="4">
		</td>
	</tr>

	<tr>
		<td align="right">
			Role
		</td>
		<td align="left">
			<select size="1" name="role">
				<option value='' selected></option>
				<option value='<?echo $role?>' selected><?echo $role?></option>
				<option value='Unit Manager'>Unit Manager</option>
				<option value='Project Manager'>Project Manager</option>
				<option value='Team Leader'>Team Leader</option>
				<option value='Solution Architect'>Solution Architect</option>
				<option value='Senior Consultant'>Senior Developer</option>
				<option value='Consultant'>Consultant</option>
				<option value='Senior Developer'>Senior Developer</option>
				<option value='Junior Developer'>Junior Developer</option>
				<option value='Systemist'>Systemist</option>
				<option value='Systemist'>Administrator</option>
			</select>

		</td>
	</tr>

	<tr>
		<td align="right">
			Actual Money
		</td>
		<td align="left">
			<input name="money" value="<?echo $money?>" size="10" maxlength="10">
		</td>
	</tr>

	<tr>
		<td align="right">
			Money Expected
		</td>
		<td align="left">
			<input name="exp_money" value="<?echo $exp_money?>" size="10" maxlength="10">
		</td>
	</tr>

	<tr>
		<td align="right">
			Level
		</td>
		<td align="left">
			<input name="level" value="<?echo $level?>" size="10" maxlength="10">
		</td>
	</tr>

	<tr>
		<td align="right">
			Telephone
		</td>
		<td align="left">
			<input name="tel" value="<?echo $tel?>" size="20" maxlength="20">
		</td>
	</tr>

	<tr>
		<td align="right">
			EMail
		</td>
		<td align="left">
			<input name="email" value="<?echo $email?>" size="50" maxlength="50">
		</td>
	</tr>


	<tr>
		<td align="right">
			Note
		</td>
		<td align="left">
			<input name="note" value="<?echo $note?>" size="50" maxlength="100">
		</td>
	</tr>


	<tr>
		<td align="right">
			Expected Contract
		</td>
		<td align="left">
			<select size="1" name="exp_contract">
				<option value='' selected></option>
				<option value='<?echo $exp_contract?>' selected><?echo $exp_contract?></option>
				<option value='Consultant'>Consultant</option>
				<option value='Employee'>Employee</option>
			</select>

		</td>
	</tr>

	<tr>
		<td align="right">
			Actual Contract
		</td>
		<td align="left">
			<select size="1" name="act_contract">
				<option value='' selected></option>
				<option value='<?echo $act_contract?>' selected><?echo $act_contract?></option>
				<option value='Consultant'>Consultant</option>
				<option value='Employee'>Employee</option>
			</select>

		</td>
	</tr>

	<tr>
		<td align="right">
			Actual Company
		</td>
		<td align="left">
			<input name="company" value="<?echo $company?>" size="30" maxlength="30">
		</td>
	</tr>


	<tr>
		<td align="right">
			Unit
		</td>
		<td align="left">
		<select size=1 name='unit'>
			<option value='' selected ></option>
			<option value='<?echo $unit?>' selected ><?echo $unit?></option>
			<option value='Unit1'>Unit1</option>
			<option value='Unit2'>Unit2</option>
			<option value='Unit3'>Unit3</option>
		</select>
		</td>
	</tr>

	<tr>
		<td align="right">
			Employee Code
		</td>
		<td align="left">
			<input name="employ_code" value="<?echo $employ_code?>" size="10" maxlength="10">
		</td>
	</tr>
	<tr>
		<td align="right">
			Valutation
		</td>
		<td align="left">
			<select size="1" name="valutation">
				<option value='' selected></option>
				<option value='<?echo $valutation?>' selected><?echo $valutation?></option>
				<option value='Excellent'>Excellent</option>
				<option value='Very Good'>Very Good</option>
				<option value='Good'>Good</option>
				<option value='Fair'>Fair</option>
				<option value='Poor'>Poor</option>
				<option value='Inadeguate'>Inadeguate</option>
			</select>
		</td>
	</tr>


	<tr>
		<td align="right">
			User Creator
		</td>
		<td align="left">
			<input name="user_creator" value="<?echo $user_creator?>" size="30" maxlength="30">
		</td>
	</tr>

<? if ($action == "update") {?>

	<tr>
		<td align="right" disabled>
			Date Insertion
		</td>
		<td align="left">
			<input readonly name="date_creation" value="<?echo $creation_date?>" size="10" maxlength="10">
		</td>
	</tr>

<? } ?>

	<tr>
		<td align="right">
			User Interviewer
		</td>
		<td align="left">
			<input name="interviewer" value="<?echo $interviewer?>" size="30" maxlength="30">
		</td>
	</tr>
	<tr>
		<td align="right">
			Date Interview
		</td>
		<td align="left">
			<input name="date_interview" value="<?echo $interview_date?>" size="10" maxlength="10">
			<input type='hidden' name='date_interview_hr'>

			<a class=PageLink href='#' onclick="makeCurrentCalendar('edit_cv', 'date_interview', 'date_interview_hr', 290, 230, 220, 200, true, true)" onmouseout="self.status=''" onmouseover="window.status='Select date';return true;"><img src='images/watch.gif' border=0 alt='Select Date'> Select Date</a>


		</td>
	</tr>

	<tr  valign="bottom" >
		<td align="center" colspan=2><br>
		<input TYPE="image" src="images/save.gif" alt="Save" border="0">
		</td>
	</tr>



</table>
</form>

<br>
<br>
<hr>
<p align=center><font class=SecondTable>* Required Fields</font></p>
<br>
</body>


</html>


