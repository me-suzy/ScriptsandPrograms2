<?

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth", "perm" => "Credigold_Perm", "account" => "Credigold_Account"));

if ($type == "emails")

   {

      $dc->query("SELECT body FROM ".$_Config["database_emails"]." WHERE id='$tempID';");

      if ($dc->num_rows() == 0)

         {

            print "<font face=Verdana size=2><center><b>Please select an email to edit from the menu above</b></center></font>";

         }

      else

         {

            $dc->next_record();

            $dc->write("body");

         }

   }

else

   {

      $dc->query("SELECT html FROM ".$_Config["database_pages"]." WHERE id='$tempID';");

      if ($dc->num_rows() == 0)

         {

            print "<font face=Verdana size=2><center><b>Please select a custom page to edit from the menu above or create a new one</b></center></font>";

         }

      else

         {

            $dc->next_record();

            $dc->write("html");

         }

   }

?>