<table width="100%" border="0 cellspacing="0" cellpadding="2" align="center">
    <tr align="left" valign="middle">
        <td width="25%"><input type="button" class="button" value="Linkchecker" onclick="window.location='linkchecker.php?start=0&limit=30&PHPSESSID=<?=session_id()?>'"></td>
        <td width="25%"><input type="button" class="button" value="Sitechecker" onclick="window.location='sitechecker.php?PHPSESSID=<?=session_id()?>'"></td>
        <td width="25%"><input type="button" class="button" value="Manage banners" onclick="window.location='banners.php?start=0&limit=30&PHPSESSID=<?=session_id()?>'"></td>
        <td width="25%">
        <?if ($Static == 'on'){?>
            <input type="button" class="button" value="Generate Html" onclick="window.location='generate_html.php?PHPSESSID=<?=session_id()?>'">
        <?}?>    
        </td>
    <tr>
        <td width="25%"><input type="button" class="button" value="Email config" onclick="window.location='templates.php?PHPSESSID=<?=session_id()?>'"></td>
        <td width="25%"><input type="button" class="button" value="Articles" onclick="window.location='articles.php?PHPSESSID=<?=session_id()?>'"></td>
        <td width="25%"><input type="button" class="button" value="Categories" onclick="window.location='categories.php?PHPSESSID=<?=session_id()?>'"></td>
        <td width="25%" colspan=5>
          <input type="button" class="button" value="Log Off" onClick="window.location='login.php?logoff'">
        </td>
    </tr>
    <tr align="center" valign="middle">
        <td colspan="5" align="right">&nbsp;</td>
    </tr>
</table>
<hr>
<br>