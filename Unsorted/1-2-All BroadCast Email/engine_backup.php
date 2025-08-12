<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_78; ?></strong></font><font size="4" face="Arial, Helvetica, sans-serif"></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_79; ?></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_80; ?>:</font></p>
<FORM ENCTYPE="multipart/form-data" ACTION="main.php" METHOD=POST>
  <div align="left"> 
    <table width="300" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="130"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_81; ?></font></td>
        <td><input type="text" name="username" size="20"></td>
      </tr>
      <tr> 
        <td width="130"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_82; ?></font></td>
        <td><input type="password" name="password" size="20"></td>
      </tr>
      <tr> 
        <td width="130"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_83; ?></font></td>
        <td><input type="text" name="nama_db" size="20"></td>
      </tr>
      <tr>
        <td><font size="2" face="Arial, Helvetica, sans-serif">Host</font></td>
        <td><input name="host" type="text" id="host" size="20"></td>
      </tr>
    </table>
    <p> 
      <INPUT TYPE="submit" VALUE="<?PHP print $lang_21; ?>" name="backup">
      <input name="page" type="hidden" id="page" value="engine_backup2">
      <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
    </p>
    </div>
</FORM>
