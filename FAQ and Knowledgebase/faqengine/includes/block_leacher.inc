<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(($blockleacher==1) && is_leacher($HTTP_USER_AGENT))
{
	header("HTTP/1.0 403 Forbidden");
	exit;
}
?>