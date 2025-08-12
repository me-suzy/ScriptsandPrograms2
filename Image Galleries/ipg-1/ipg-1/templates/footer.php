<!--END CONTENT -->
    </div></td>
	<?php if(MENU_POS == '2') { ?>
<td class="right_sidebar" valign="top"> <a href="index.php" class="menu_pdb">Home</a>
  <div class="menu_divider"></div>
  <?php print build_menu(); ?>
  <a href="login.php" class="menu_pdb">Login</a> 
	  <?php if($_SESSION['user_id']){ ?>
	  <?php if(!$_SESSION['admin']){ ?>
	  <div class="menu_divider"></div>
      <a href="private.php" class="menu_pdb">Private Gallery</a> 
      <div class="menu_divider"></div>
      <a href="logout.php" class="menu_pdb">Logout</a> 
      <?php }//end if
	  }//end if ?>
</td>
<?php }//end if ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td class="footer"> 
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p align="center"><?php print COPYRIGHT_NOTICE; ?></p>
      <p>&nbsp;</p>
    </td>
  </tr>
</table>
</body>
</html>