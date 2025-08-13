<form action="$PHP_SELF?do=backsql" method="post">
<table border=0 cellpadding=0 cellspacing=0 align=center width=80%>
  <tr>
    <td class=tblborder>
      <table border=0 cellpadding=4 cellspacing=1 align=center width=100%>
        <tr>
	  <td class=tblhead colspan=6>
	  Backup Database
	  </td>
	</tr>
	<tr>
	  <td class='firstalt' width='60%' colspan='5'><b style="font-size:10pt;color: red">$db[name]</b>
	  <input type="hidden" name="endall" value="1" />
	  <input type="hidden" name="drop" value="1" />
	  <input type="hidden" name="data" value="1" />

	  </td>
	  <td class='firstalt' rowspan='2' align='center' width='30%'>
	    <input type='submit' name='backitup' value='Backup SQL' />
	  </td>
	</tr>
	<!-- <tr>
	  <td class='thrdalt'>
	  <li></li>
	  </td>
	  <td class='secondalt' nowrap>
	     <input type='checkbox' name='endall' value='1' checked/> <b>End with ';'</b>
	     
	  <td class='secondalt' nowrap>
	     <input type='checkbox' name='drop' value='1' checked/> <b>Add 'DROP TABLE'</b>
	  </td>
	  <td class='secondalt' nowrap>
	     <input type='checkbox' name='data' value='1' checked/> <b>Include Table Data</b>
	  </td>
	  <td class='secondalt' nowrap>
	     <input type='checkbox' name='only' value='1' /> <b>Restricted</b>
	  </td>
	  
	</tr> -->
      </table>
    </td>
  </tr>
</table>
</form>
<br />

<table border=0 cellpadding=0 cellspacing=0 align=center width=80%>
  <tr>
    <td class=tblborder>
      <table border=0 cellpadding=4 cellspacing=1 align=center width=100%>
        <tr>
	  <td class=tblhead colspan=5>
	  $evoLANG[backedup]
	  </td>
	</tr>
	$file_loop
      </table>
    </td>
  </tr>
</table>
<br />
<table border=0 cellpadding=0 cellspacing=0 align=center width=80%>
  <tr>
    <td class=tblborder>
      <table border=0 cellpadding=4 cellspacing=1 align=center width=100%>
        <tr>
	  <td class=tblhead colspan=3>
	  $evoLANG[word_folder]
	  </td>
	</tr>
	$dir_loop
      </table>
    </td>
  </tr>
</table>
<br />