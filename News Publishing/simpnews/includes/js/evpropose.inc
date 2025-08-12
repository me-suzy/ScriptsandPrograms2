<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language=JavaScript src=<?php echo $url_simpnews?>/js/formcheckfunctions.js></script>
<script type="text/javascript" language=JavaScript>
<!--
function checkuserform()
{
	if(document.inputform.email.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noemail)?>");
		return false;
	}
	if(document.inputform.email.value.length>0)
	{
		if(!isEmail(document.inputform.email.value))
		{
			alert("<?php echo undo_htmlentities($l_novalidemail)?>");
			return false;
		}
	}
	if((document.inputform.password.value.length>0) || (document.inputform.password2.value.length>0))
	{
		if(document.inputform.password.value != document.inputform.password2.value)
		{
			alert("<?php echo undo_htmlentities($l_passwordmismatch)?>");
			return false;
		}
	}
	return true;
}
function checkform()
{
<?php
if(bittst($proposereq,BIT_1))
{
?>
	if(document.inputform.poster.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noname)?>");
		return false;
	}
<?php
}
if(bittst($proposereq,BIT_2))
{
?>
	if(document.inputform.email.value.length>0)
	{
		if(!isEmail(document.inputform.email.value))
		{
			alert("<?php echo undo_htmlentities($l_novalidemail)?>");
			return false;
		}
	}
<?php
}
if(bittst($proposereq,BIT_3))
{
?>
	if(document.inputform.input_heading.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noheading)?>");
		return false;
	}
<?php
}
?>
	if(!isDate(document.inputform.sel_year.value,document.inputform.sel_month.value,document.inputform.sel_day.value))
	{
		alert("<?php echo undo_htmlentities($l_novaliddate2)?>");
		return false;
	}
	
	var enteredyear=document.inputform.sel_year.value;
	var enteredmonth=document.inputform.sel_month.value-1;
	var enteredday=document.inputform.sel_day.value;
	var enteredDate = new Date(enteredyear,enteredmonth,enteredday);
	var heute = new Date();
	heute.setHours(0);
	heute.setMinutes(0);
	heute.setSeconds(0);
	heute.setMilliseconds(0);
	var htime=heute.getTime();
	var etime=enteredDate.getTime();
	if(etime<htime)
	{
		alert("<?php echo undo_htmlentities($l_pastdate)?>");
		return false;
	}
	if(document.inputform.entrytxt.value<1)
	{
		alert("<?php echo undo_htmlentities($l_notext)?>");
		return false;
	}
	return true;
}
function checkform_edit()
{
<?php
if(bittst($proposereq,BIT_3))
{
?>
	if(document.inputform.input_heading.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noheading)?>");
		return false;
	}
<?php
}
?>
	if(!isDate(document.inputform.sel_year.value,document.inputform.sel_month.value,document.inputform.sel_day.value))
	{
		alert("<?php echo undo_htmlentities($l_novaliddate2)?>");
		return false;
	}
	
	if(document.inputform.entrytxt.value<1)
	{
		alert("<?php echo undo_htmlentities($l_notext)?>");
		return false;
	}
	return true;
}
//  End -->
</script>
