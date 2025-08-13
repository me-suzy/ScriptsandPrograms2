<?
/* THIS FILE IS NOT IS USE
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
########################################
######### MAIN CONFIG ##################
########################################
      $args = array(array("column"         => "config_type",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),

                    ##### General Config #####
                    array("type"           => "HEADERROW",
                           "title"         => VEXTRACLIENTINFOCONFIG));

$counter = 6;
for($ix=1;$ix<=50;$ix++)
{
     $jx++;

     switch ($jx) {
         case 1:
              array_push ($args, array("type"          => "HEADERROW",
                                       "title"         => VARIABLE."_$counter"));

              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VACTIVE,
                                       "type"          => "FUNCTION_CALL",
                                       "function_call" => true_false_radio("config_$ix",${config_.$ix}),
                                       "admin_only"    => 1));
              $counter++;
         break;

         case 2:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VREQUIRED,
                                       "type"          => "FUNCTION_CALL",
                                       "function_call" => true_false_radio("config_$ix",${config_.$ix}),
                                       "admin_only"    => 1));
         break;

         case 3:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VTITLE,
                                       "type"          => "TEXT",
                                       "size"          => 40,
                                       "maxlength"     => 255,
                                       "admin_only"    => 1,
                                       "append"        => "Any SHORT text string."));
         break;

         case 4:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VTYPE,
                                       "type"          => "TEXT",
                                       "size"          => 20,
                                       "maxlength"     => 255,
                                       "admin_only"    => 1,
                                       "append"        => "CHOICES: TEXT, TEXTAREA"));
         break;

         case 5:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VSIZE,
                                       "type"          => "TEXT",
                                       "size"          => 5,
                                       "maxlength"     => 255,
                                       "admin_only"    => 1,
                                       "append"        => "Any numeric value. This is the size of the TEXT field OR rows in the TEXTAREA."));
         break;

         case 6:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VMAXLENGTH,
                                       "type"          => "TEXT",
                                       "size"          => 5,
                                       "maxlength"     => 255,
                                       "admin_only"    => 1,
                                       "append"        => "Any numeric value. This is the maxlength of the input field."));
         break;

         case 7:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VADMINONLY,
                                       "type"          => "FUNCTION_CALL",
                                       "function_call" => true_false_radio("config_$ix",${config_.$ix}),
                                       "admin_only"    => 1,
                                       "append"        => "Is this an admin only field?"));
         break;

         case 8:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VAPPEND,
                                       "type"          => "TEXT",
                                       "size"          => 40,
                                       "maxlength"     => 255,
                                       "admin_only"    => 1,
                                       "append"        => "Any text string that appends to the input field."));
         break;

         case 9:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VVALUE,
                                       "type"          => "TEXT",
                                       "size"          => 40,
                                       "maxlength"     => 255,
                                       "admin_only"    => 1,
                                       "append"        => "Any text string that is the default value of the input field."));
         break;

         case 10:
              array_push ($args, array("column"        => "config_$ix",
                                       "required"      => 0,
                                       "title"         => VVORTECH,
                                       "type"          => "FUNCTION_CALL",
                                       "function_call" => true_false_radio("config_$ix",${config_.$ix}),
                                       "append"        => "Display in the Vortech Signup Form?"));
         break;
     }

     $jx = ($jx==10) ? 0 : $jx ;

}

if ($debug) { echo "<pre>"; print_r($args); echo "</pre>"; }
?>