<html>

</head>

<body>

<p align="center"><font color="#0000FF" size="5">You can add a header file here 
to blend this into your site.</font></p>
<p align="center"><!--webbot bot="HTMLMarkup" startspan --><?php
  if ($banner = escs_banner_exists('dynamic', '468X60'))
  {
  ?>
  <tr>
  <td width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
   <td align="center"> <br><?php echo escs_display_banner('static',
$banner); ?><br><br></td>
  </tr>
  </table>
  </td>
  </tr>
  <?php
  }
?>         <!--webbot bot="HTMLMarkup" endspan --></p>

</body>

</html>