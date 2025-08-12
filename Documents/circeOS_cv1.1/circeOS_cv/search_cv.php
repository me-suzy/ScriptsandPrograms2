<?

/**************************************************/
/*                                                */
/*  search_cv.php                                 */
/*                                                */
/*                                                */
/**************************************************/
/*                                                */
/* site:   www.circeos.it                         */
/*                                                */
/**************************************************/

require_once ("config.inc.php"); 

?>

<html>
<head>
<title><?echo $SITE_name?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK href="backend_style.css" type=text/css rel=stylesheet>

</head>

<body class=body >
<table class=PageTable border=0 cellpadding=0 cellspacing=2 width=98% align=center><tr><td  width=100% align=center>
<b>Search Curricula</b> <img src='images/lente.gif' border=0 alt='Search Curricula'>
</td></tr>
</table>
<hr>


<form name="SearchForm" method="post" action="index.php?action=search" >
    <table class=SecondTable border="0" cellpadding="0" cellspacing="0" width="70%" align=center>

		<tr>
		  <td  align="right">Surname:&nbsp;</td>
		  <td >
		  <input type="text" name="surname" value="" size="30" maxlength="50">
		  </td>
		</tr>

		<tr>
		  <td  align="right">Name:&nbsp;</td>
		  <td >
		  <input type="text" name="name" value="" size="30" maxlength="50">
		  </td>
		</tr>

		<tr>
			<td align="right">
				Role:&nbsp;
			</td>
			<td align="left">
				<select size="1" name="role">
					<option value='' selected></option>
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
				Years of Experience > 
			</td>
			<td align="left">
				<input name="exp_years" value="<?echo $exp_years?>" size="4" maxlength="4">
			</td>
		</tr>
		

	    <tr>
          <td  align="right">Note:&nbsp;</td>
          <td >
		  <input type="text" name="note" value="" size="50" maxlength="100">
		  </td>
        </tr> 

		<tr>
			<td align="right">
				Expected Contract:&nbsp;
			</td>
			<td align="left">
				<select size="1" name="exp_contract">
					<option value='' selected></option>
					<option value='Consultant'>Consultant</option>
					<option value='Employee'>Employee</option>
				</select>

			</td>
		</tr>

		<tr>
			<td align="right">
				Unit:&nbsp;
			</td>
			<td align="left">
			<select size=1 name='unit'>
				<option value='' selected ></option>
				<option value='Unit1'>Unit1</option>
				<option value='Unit2'>Unit2</option>
				<option value='Unit3'>Unit3</option>
			</select>
			</td>
		</tr>

		<tr>
          <td  align="right">Interviewer:&nbsp;</td>
          <td >
		  <input type="text" name="interviewer" value="" size="30" maxlength="30">
		  </td>
        </tr> 

		<tr>
			<td align="right">
				Valutation:&nbsp;
			</td>
			<td align="left">
				<select size="1" name="valutation">
					<option value='' selected></option>
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
          <td  align="right"><i>Document Keywords 1</i>:&nbsp;</td>
          <td >
		  <input type="text" name="keywords1" value="" size="50" maxlength="100">
		  </td>
        </tr> 

		<tr>
          <td  align="right"><i>Document Keywords 2</i>:&nbsp;</td>
          <td >
		  <input type="text" name="keywords2" value="" size="50" maxlength="100">
		  </td>
        </tr> 

		<tr>
          <td  align="right"><i>Document Keywords 3</i>:&nbsp;</td>
          <td >
		  <input type="text" name="keywords3" value="" size="50" maxlength="100">
		  </td>
        </tr> 


      </table>
    </center>
  <p align="center"><input TYPE="image" src="images/search.gif" alt="Search" border="0"></p>
</form>

<br>
<br>&nbsp;



</body>
</html>
