<?
/**************************************************/
/*                                                */
/*  index.php                                     */
/*                                                */
/*                                                */
/**************************************************/
/*                                                */
/*                                                */
/* site:   www.circeos.it                         */
/*                                                */
/**************************************************/

require_once("config.inc.php"); 

?> 

<html>
<head>
<title><?echo $SITE_name?></title>
<link href="backend_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Pragma" CONTENT="no-cache">
<meta http-equiv='Cache-Control' CONTENT='no-cache'>
<meta http-equiv="Expires" CONTENT="-1">
</head>

<body class="body">
<table class=PageTable width="98%" align=center>
    <tr>
      <td  align=left><b>Curricula</b> <img src='images/cv.gif' border=0 alt='CV'></td>
      <td  align=right class=SecondTable>
	  <a class=PageLink href="index.php">All CV <img src='images/lists.gif' border=0 alt='List All CV'>
	  &nbsp;&nbsp;
	  <a class=PageLink href="build_index.php">Build Index <img src='images/ingest.gif' border=0 alt='Rebuild Indexes'>
	  &nbsp;&nbsp;
	  <a class=PageLink href="search_cv.php">Search CV <img src='images/lente.gif' border=0 alt='Search CV'>
	  &nbsp;&nbsp;
	  <a class=PageLink href="edit_cv.php">New CV <img src='images/new.gif' border=0 alt='New CV'>
	  </td>
    </tr>
</table>
<hr>

<table class=SecondTable border="1" cellpadding="0" cellspacing="0" width="98%" align=center>
	<tr>
		<td align="center">Surname</td>
		<td align="center">Name</td>
		<td align="center">Year</td>
		<td align="center">Inserted</td>
		<td align="center">Interview</td>
		<td align="center">Valutation</td>
		<td align="center">Role</td>
		<td align="center">View</td>
		<td align="center">CV</td>
		<td align="center">Edit</td>
		<td align="center">Del</td>
	</tr>

<?
$cc = 0;

$sql = "SELECT * from ITW_CURRICULUM WHERE CV_SURNAME IS NOT NULL ";

if ($action == "search"){

	if (isset($surname) && trim($surname) != "")
		$sql .= " AND CV_SURNAME = '$surname'";
	if (isset($name) && trim($name) != "")
		$sql .= " AND CV_NAME = '$name'";
	if (isset($note) && trim($note) != "")
		$sql .= " AND CV_NOTE LIKE '%$note%'";
	if (isset($exp_contract) && trim($exp_contract) != "")
		$sql .= " AND CV_EXP_CONTRACT = '$exp_contract'";
	if (isset($role) && trim($role) != "")
		$sql .= " AND CV_ROLE = '$role'";
	if (isset($unit) && trim($unit) != "")
		$sql .= " AND CV_UNIT = '$unit'";
	if (isset($interviewer) && trim($interviewer) != "")
		$sql .= " AND CV_INTERVIEWER LIKE '%$interviewer%'";
	if (isset($valutation) && trim($valutation) != "")
		$sql .= " AND CV_VALUTATION LIKE '%$valutation%'";
	if (isset($exp_years) && trim($exp_years) != "")
		$sql .= " AND CV_EXP_YEARS >= '$exp_years'";

	if (isset($keywords1) && trim($keywords1) != "")
		$sql .= " AND CONTAINS(CV_FILE_IMAGE,'$keywords1')>0";
	if (isset($keywords2) && trim($keywords2) != "")
		$sql .= " AND CONTAINS(CV_FILE_IMAGE,'$keywords2')>0";
	if (isset($keywords3) && trim($keywords3) != "")
		$sql .= " AND CONTAINS(CV_FILE_IMAGE,'$keywords3')>0";

}

$sql .= " ORDER BY CV_SURNAME";

/* connessione al DB */

$conn = OCILogon($CV_ORACLE_db_user, $CV_ORACLE_db_password, $CV_ORACLE_db_name);
if ($conn == FALSE)
	die ("DB Oracle Connection Error");

$stmt = OCIparse($conn, $sql);
OCIexecute($stmt,OCI_DEFAULT);

  
while (OCIfetch($stmt)) {
		$cv_id = OCIresult($stmt, "CV_ID");	
		$surname = OCIresult($stmt, "CV_SURNAME");	
		$name = OCIresult($stmt, "CV_NAME");	
		$year = OCIresult($stmt, "CV_YEAR");	
		$creation_date = OCIresult($stmt, "CV_DATE");	
		$interview_date = OCIresult($stmt, "CV_DATE_INTERVIEW");	
		$valutation = OCIresult($stmt, "CV_VALUTATION");	
		$role = OCIresult($stmt, "CV_ROLE");	

?>
		<tr>
		<td align="left" >&nbsp;
			<?echo $surname?>
		</td>
		<td align="left" >&nbsp;
			<?echo $name?>
		</td>
		<td align="center" >&nbsp;
			<?echo $year?>
		</td>

		<td align="center" >&nbsp;
			<?echo $creation_date?>
		</td>
		<td align="center" >&nbsp;
			<?echo $interview_date?>
		</td>
		<td align="left" >&nbsp;
			<?echo $valutation?>
		</td>
		<td align="center" >&nbsp;
			<?echo $role?>
		</td>

		<td align="center" valign="middle" >
			<a href="view_cv.php?cv_id=<?echo $cv_id?>" target="_blank"><img border="0" src="images/preview.gif" alt="view"></a>
		</td>

		<td align="center" valign="middle" >
			<a href="download_cv.php?cv_id=<?echo $cv_id?>"><img border="0" src="images/floppy.gif" alt="download"></a>
		</td>

		<td align="center" valign="middle" >
			<a href="edit_cv.php?cv_id=<?echo $cv_id?>"><img border="0" src="images/edit.gif" alt="edit"></a>
		</td>

		<td align="center" valign="middle" >&nbsp;

			<a href="delete_cv.php?cv_id=<?echo $cv_id?>&surname=<?echo $surname?>" onclick="return confirm('Confirm Delete ?')"><img border="0" src="images/delete.gif" alt="delete"></a>

		</td>
			
	</tr>
<?
	$cc ++;

} // end while

/* chiusura DB */

OCIFreeStatement($stmt);
OCILogOff($conn);


?>

</table>

<br><hr>
<table class=SecondTable width="98%" align=center>
    <tr>
      <td  width="50%" align=left>
	  Records found: <?echo $cc?>
	  </td>
      <td  width="50%" align=right>
	  <a href="http://www.circeos.it" target="_blank"><img src="images/logo_enterprise_small.gif" border=0/></a>
	  &nbsp;
	  </td>
    </tr>
</table>


<br>
<br>&nbsp;

</body>        
</html>        
      
