<?

include("prepend.php3");

$dc->query("SELECT * FROM ".$_Config["database_pages"]." WHERE id='$id';");

$dc->next_record();



if ($dc->get("permissions") == "L")	page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth"));



if ($dc->num_rows() == 0)

   {

      initPage();

      ?>

      <center><font class=head color=darkred>Page Does Not Exist!</font></center>

      <br>

      <p class=text align=justify style="width:500px">

      &nbsp;&nbsp;The page you are trying to access is not available or has been turned off temporarily. Please contact us for more details on that. Thank you!

      </p>

      <?

   }

else

   {

      initPage();

      $dc->query("SELECT html FROM ".$_Config["database_pages"]." WHERE id='$id';");

      ?>

      <p class=text align=justify style="width:500px">

      <?

         $dc->next_record();

         $dc->write("html");

      ?>

      </p>

      <?

   }

endPage();

page_close();

exit;

?>

