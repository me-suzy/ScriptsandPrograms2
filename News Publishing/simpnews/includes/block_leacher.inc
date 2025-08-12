<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(($blockleacher==1) && is_leacher($HTTP_USER_AGENT))
{
	header("HTTP/1.0 403 Forbidden");
	exit;
}
?>