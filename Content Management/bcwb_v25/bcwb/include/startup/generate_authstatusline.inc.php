<?
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

$auth_status_line='
<style>
a.asline { color: White; text-decoration : none; font-size: 10px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; }
a.asline:hover { color: White; text-decoration : none; font-size: 10px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;  }
a.asline:visited { color: White; text-decoration : none; font-size: 10px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; }
.abtn {font-family: Verdana; font-size: 11px;}
</style>
<table width="100%" BGCOLOR="black" border="0" cellpadding="5">
	<tr>
		<td width=100% style="background-color: Black; color: White; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;">
		<strong>Ñèñòåìà óïðàâëåíèÿ ñàéòîì "ÀÎ ÁÐÒ"</strong> | '.$lang["User"].': '.$rn["userinfo"]["name"].' | <A href="'.$http_path_admin.'/modules/objstructure/onsite/'.$rn["group_langid"]."/".$rn["group_siteid"].'/page/'.$rn["group_release"].'/" class=asline>'.$lang["Go_CMS_for_editing"].'</A>
		</td>
		<td align=right nowrap style="background-color: Black; color: White; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;">
		<a href="'.$http_path.'?logout=1" class=asline>'.$lang["Logout"].'</a>
		</td>
	</tr>
</table>
';

$template_content=preg_replace("/\<body(.*?)>/is","<body\\1>".$auth_status_line, $template_content);
?>