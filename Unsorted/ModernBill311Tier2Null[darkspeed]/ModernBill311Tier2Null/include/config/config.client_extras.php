<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
** THIS IS THE PAYMENTS CONFIG FILE.
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
//$dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
//mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");

$this_client_extras_1_5_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='client_extras_1_5'"));

$jb=0;
for($ia=1;$ia<=5;$ia++)
{
    $jb++; ${"client_field_active_$ia"}     = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_required_$ia"}   = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_title_$ia"}      = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_type_$ia"}       = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_size_$ia"}       = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_maxlength_$ia"}  = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_admin_only_$ia"} = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_append_$ia"}     = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_value_$ia"}      = $this_client_extras_1_5_config["config_$jb"];
    $jb++; ${"client_field_vortech_$ia"}    = $this_client_extras_1_5_config["config_$jb"];
}

$this_client_extras_6_10_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='client_extras_6_10'"));

$jb=0;
for($ia=6;$ia<=10;$ia++)
{
    $jb++; ${"client_field_active_$ia"}     = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_required_$ia"}   = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_title_$ia"}      = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_type_$ia"}       = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_size_$ia"}       = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_maxlength_$ia"}  = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_admin_only_$ia"} = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_append_$ia"}     = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_value_$ia"}      = $this_client_extras_6_10_config["config_$jb"];
    $jb++; ${"client_field_vortech_$ia"}    = $this_client_extras_6_10_config["config_$jb"];
}
?>