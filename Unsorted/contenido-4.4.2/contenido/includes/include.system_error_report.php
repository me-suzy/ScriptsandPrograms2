<?php
/*****************************************
* File      :   $RCSfile: include.system_error_report.php,v $
* Project   :   Contenido
* Descr     :   Administrator can create an error report
*				and can send this error report
*
* Author    :   $Author: marco.jahn $
*               
* Created   :   15.08.2003
* Modified  :   $Date: 2003/09/05 09:47:03 $
*
* © four for business AG, www.4fb.de
*
* $Id: include.system_error_report.php,v 1.1 2003/09/05 09:47:03 marco.jahn Exp $
******************************************/

$tpl->reset();

/*
 * extract array information from return array
*/
$tmpReturnVar = explode("||", $tmpReturnVar);
$mailSendError = $tmpReturnVar[0];
$tmp_notification = $tmpReturnVar[1];

/*
 * print out tmp_notifications if any action has been done
*/
if (isset($tmp_notification))
{
	$tpl->set('s', 'TEMPNOTIFICATION', $tmp_notification);
}
else
{
	$tpl->set('s', 'TEMPNOTIFICATION', '');	
}

// get sysadmin email
$sql = "SELECT email FROM
		".$cfg["tab"]["phplib_auth_user_md5"]."
		WHERE perms='sysadmin'";
$db->query($sql);
$db->next_record();

$sysAdminMail = $db->f("email");
	
//build naviation into select field
//$contenidoNav = buildContenidoNavigationIntoASelectBox();

$conNav = new Contenido_Navigation;
$nav = $conNav->data;
	
/*Parse navigation hirarchie for selectbox*/
foreach ($nav as $mainKey => $mainValue)
{
	//echo $mainValue;
	foreach ($mainValue as $mainTitleKey => $mainTitleValue)
	{
		//echo $mainTitleKey;
		if ($mainTitleKey == 0)
		{
			if ($HTTP_POST_VARS['selectarea'] == $mainTitleValue)
			{
				$selNav .= "<option value=\"$mainTitleValue\" selected>".$mainTitleValue."</option>";
			}
			else
			{
				$selNav .= "<option value=\"$mainTitleValue\">".$mainTitleValue."</option>";
			}	
			$mainTitle = $mainTitleValue;
		}
		else
		{
			foreach ($mainTitleValue as $subKey => $subValue) {
				if ($subKey == 0)
				{
					if ($HTTP_POST_VARS['selectarea'] == $subValue)
					{
						$selNav .= "<option value=\"$mainTitle - $subValue\" selected>--- ".$subValue."</option>";
					}
					else
					{
						$selNav .= "<option value=\"$mainTitle - $subValue\">--- ".$subValue."</option>";
					}
				} //if
			}//foreach
		}//if
	}//foreach
}//foreach

/*
 * set form values if $mailSendError != 0
*/
if ($mailSendError != 0)
{
	$tpl->set('s', 'SENDER', $HTTP_POST_VARS['sender']);
	$tpl->set('s', 'FORENAME', $HTTP_POST_VARS['forename']);
	$tpl->set('s', 'SURNAME', $HTTP_POST_VARS['surname']);
	$tpl->set('s', 'BUGREPORT', $HTTP_POST_VARS['bugreport']);
	$tpl->set('s', 'SELAREA', $selNav);	
}
else
{
	$tpl->set('s', 'SENDER', $sysAdminMail);
	$tpl->set('s', 'FORENAME', '');
	$tpl->set('s', 'SURNAME', '');
	$tpl->set('s', 'SELAREA', $selNav);
	$tpl->set('s', 'BUGREPORT', '');
}

$saveUrl = $sess->url("main.php?area=$area&frame=$frame&action=sendMail");
$tpl->set('s', 'ACTION', $saveUrl);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['system_errorreport']);

?>