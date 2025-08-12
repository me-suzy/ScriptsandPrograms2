<!doctype html public "-//W3C//DTD HTML 4.0 //EN"> 
<html>
<script>
  	function fAusfuehren()
    {
     var path = document.dbupdate.dbphpfile.value;
     document.location=path;
    }
  </script>

<head>
       <title>Cuteflow Datenbank Update</title>
</head>
<body>
<table border="0" align="center">
 <form name="dbupdate" action="DBUpdate.php" method="POST">
  <tr>
   <td>
   <img src="../images/cuteflow_logo_small.png">
   </td>
  </tr>
  <tr>
   <td><b>Datenbank Update</b></td>
  </tr>
  <tr>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td>
    <input type="file" name="dbphpfile" size="60"></input>
   </td>
   <td>
    <input type="submit" onClick="fAusfuehren()" value="Update"></input>
   </td>
  </tr>
 </form>
</table>
</body>
</html>
