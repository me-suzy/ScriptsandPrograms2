<form action="$_SERVER[PHP_SELF]?rate" method="post" name="rate$id" />
<div id="ratebox" align="center">
  <b>$evoLANG[rateart] $evoLANG_addon[article]</b> :
  <input type="hidden" name="id" value="$id" />

  <table border="0" cellpadding="1" cellspacing="0" style="text-align:center">
  <tr>
  <td> <input name="rate" type="radio" value="1" onclick="document.rate$id.submit()" /><br />1 </td>
  <td> <input name="rate" type="radio" value="2" onclick="document.rate$id.submit()" /><br />2 </td>
  <td> <input name="rate" type="radio" value="3" onclick="document.rate$id.submit()" /><br />3 </td>
  <td> <input name="rate" type="radio" value="4" onclick="document.rate$id.submit()" /><br />4 </td>
  <td> <input name="rate" type="radio" value="5" onclick="document.rate$id.submit()" /><br />5 </td>
  <td> <input name="rate" type="radio" value="6" onclick="document.rate$id.submit()" /><br />6 </td>
  <td> <input name="rate" type="radio" value="7" onclick="document.rate$id.submit()" /><br />7 </td>
  <td> <input name="rate" type="radio" value="8" onclick="document.rate$id.submit()" /><br />8 </td>
  <td> <input name="rate" type="radio" value="9" onclick="document.rate$id.submit()" /><br />9 </td>
  <td> <input name="rate" type="radio" value="10" onclick="document.rate$id.submit()" /><br />10 </td>
  </tr>
  <tr>
  <td colspan="2" class="red">
  $evoLANG[poor]
  </td>
  <td colspan="6"></td>
  <td colspan="2" class="green">
  $evoLANG[exel]
  </td>
  </table>


  </select>
</div>
</form>



