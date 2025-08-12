<?php
# choose a banner

include('../includes/db_connect.php');

if (isset($HTTP_GET_VARS[cat])) {
    $banner=mysql_query("SELECT *, ban_text FROM dir_b_categories b1 LEFT JOIN dir_banners b2 ON (b1.ban_id = b2.ban_id) WHERE ban_category=$cat ORDER BY RAND() LIMIT 1",$link);
    $sql_banner=mysql_fetch_object($banner);
    $banner_text=stripslashes($sql_banner->ban_text);
} else {
    $banner=mysql_query("SELECT *, ban_text FROM dir_b_categories b1 LEFT JOIN dir_banners b2 ON (b1.ban_id = b2.ban_id) WHERE 1 ORDER BY RAND() LIMIT 1",$link);
    $sql_banner=mysql_fetch_object($banner);
    $banner_text=stripslashes($sql_banner->ban_text);
}

?>
<?
check_admin();

if (isset($HTTP_GET_VARS['id'])) {
    $id=$HTTP_GET_VARS['id'];
}

if (isset($HTTP_POST_VARS['submit'])) {

    $submit=$HTTP_POST_VARS['submit'];

    switch ($submit) {
        case 'Save Banner':
            $endday=$HTTP_POST_VARS['lstEndDay'];
            $endmonth=$HTTP_POST_VARS['lstEndMonth'];
            $endyear=$HTTP_POST_VARS['txtEndYear'];
            $end_date=$endyear."-".$endmonth."-".$endday;
            $startday=$HTTP_POST_VARS['lstStartDay'];
            $startmonth=$HTTP_POST_VARS['lstStartMonth'];
            $startyear=$HTTP_POST_VARS['txtStartYear'];
            $start_date=$startyear."-".$startmonth."-".$startday;
            $sponsor=$HTTP_POST_VARS['radiobutton'];
//echo '<pre>';
//print_r($HTTP_POST_VARS);
            $category=$HTTP_POST_VARS['categories'];
            if (get_magic_quotes_runtime() || get_magic_quotes_gpc()) $text=addslashes($HTTP_POST_VARS['text']);

            if (isset($id) && $id != "") {
                $query="UPDATE dir_banners SET ban_start='$start_date', ban_end='$end_date', ban_text='$text', ban_sponsor='$sponsor' WHERE ban_id = $id";
                mysql_query($query,$link) or die(mysql_error());
                mysql_query("DELETE FROM dir_b_categories WHERE ban_id=$id",$link) or die(mysql_error());
                foreach($category as $value) {
                    mysql_query("INSERT INTO dir_b_categories (ban_id, ban_category) VALUES($id, $value)",$link) or die(mysql_error());
                }
            } else {
                $query="INSERT INTO dir_banners (ban_start, ban_end, ban_text, ban_sponsor) VALUES ('$start_date', '$end_date', '$text', '$sponsor')";
                mysql_query($query,$link) or die(mysql_error());
                $id=mysql_insert_id ($link);
                foreach($category as $value) {
                    mysql_query("INSERT INTO dir_b_categories (ban_id, ban_category) VALUES($id, $value)",$link) or die(mysql_error());
                }
            }
            header("Location: banners.php?id=$id&PHPSESSID=".session_id());
            break;
        case 'Banner list':
            header("Location: banners.php?PHPSESSID=".session_id());
            break;
        case 'Delete banner':
            $result=mysql_query("DELETE FROM dir_banners WHERE ban_id=$mode",$link) or die(mysql_error());
            break;
        case 'Menu':
            header("Location: menu.php?PHPSESSID=".session_id());
            break;
        case 'Logoff':
            session_unset();
            session_destroy();
            header("Location: admin.php");
            exit; break;
    }
}

$sql_catarray = array();
if (isset($id)) {

    $result=mysql_query("SELECT * FROM dir_banners WHERE ban_id=$id",$link);
    $sql_array=mysql_fetch_object($result);

    $startday=trim(substr($sql_array->ban_start,8,2));
    $startmonth=trim(substr($sql_array->ban_start,5,2));
    $startyear=trim(substr($sql_array->ban_start,0,4));

    $endday=trim(substr($sql_array->ban_end,8,2));
    $endmonth=trim(substr($sql_array->ban_end,5,2));
    $endyear=trim(substr($sql_array->ban_end,0,4));

    $sponsor=$sql_array->ban_sponsor;

    if ($sponsor=='Y') {$Y_selected='checked';}
    elseif ($sponsor=='N') {$N_selected='checked';}
    else {$N_selected='checked';}

    $text=stripslashes($sql_array->ban_text);
    $cat_result=mysql_query("SELECT ban_category FROM dir_b_categories WHERE ban_id=$id",$link) or die(mysql_error());
    while ($temp= mysql_fetch_array ($cat_result)) {$sql_catarray[]=$temp[0];}
}

include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");

?>
<?php include('../includes/admin_header.php'); ?>
        <table width="100%"  border="0" cellspacing="3" cellpadding="3" align="center">
          <tr>
            <td align="center">
            <p align="center"><?php echo $text ?></p>
            <form action="banner_maint.php?id=<?php echo $id ?>&PHPSESSID=<?php echo session_id() ?>" method="post" name="frmBanners">
            <div align="right"><input type='submit' value='Save Banner' name='submit' class='button'>&nbsp;<input type='submit' value='Banner list' name='submit' class='button'></div>
            <input type="hidden" name="mode" value="">
            <table width="85%"  border="0" cellspacing="3" cellpadding="3">
              <tr align="left" valign="top">
                <td width="34%">Banner Link Text </td>
                <td width="66%">
                  <textarea name="text" cols="50" rows="8"><?php echo $text; ?></textarea>
                </td>
              </tr>
              <tr align="left" valign="top">
                <td>From Date </td>
                <td>
                  <select size="1" name="lstStartDay">
                    <option <?php if ($startday == "00") { print("selected");} ?> value="00">--</option>
                    <option <?php if ($startday == "01") { print("selected");} ?> value="01">01</option>
                    <option <?php if ($startday == "02") { print("selected");} ?> value="02">02</option>
                    <option <?php if ($startday == "03") { print("selected");} ?> value="03">03</option>
                    <option <?php if ($startday == "04") { print("selected");} ?> value="04">04</option>
                    <option <?php if ($startday == "05") { print("selected");} ?> value="05">05</option>
                    <option <?php if ($startday == "06") { print("selected");} ?> value="06">06</option>
                    <option <?php if ($startday == "07") { print("selected");} ?> value="07">07</option>
                    <option <?php if ($startday == "08") { print("selected");} ?> value="08">08</option>
                    <option <?php if ($startday == "09") { print("selected");} ?> value="09">09</option>
                    <option <?php if ($startday == "10") { print("selected");} ?> value="10">10</option>
                    <option <?php if ($startday == "11") { print("selected");} ?> value="11">11</option>
                    <option <?php if ($startday == "12") { print("selected");} ?> value="12">12</option>
                    <option <?php if ($startday == "13") { print("selected");} ?> value="13">13</option>
                    <option <?php if ($startday == "14") { print("selected");} ?> value="14">14</option>
                    <option <?php if ($startday == "15") { print("selected");} ?> value="15">15</option>
                    <option <?php if ($startday == "16") { print("selected");} ?> value="16">16</option>
                    <option <?php if ($startday == "17") { print("selected");} ?> value="17">17</option>
                    <option <?php if ($startday == "18") { print("selected");} ?> value="18">18</option>
                    <option <?php if ($startday == "19") { print("selected");} ?> value="19">19</option>
                    <option <?php if ($startday == "20") { print("selected");} ?> value="20">20</option>
                    <option <?php if ($startday == "21") { print("selected");} ?> value="21">21</option>
                    <option <?php if ($startday == "22") { print("selected");} ?> value="22">22</option>
                    <option <?php if ($startday == "23") { print("selected");} ?> value="23">23</option>
                    <option <?php if ($startday == "24") { print("selected");} ?> value="24">24</option>
                    <option <?php if ($startday == "25") { print("selected");} ?> value="25">25</option>
                    <option <?php if ($startday == "26") { print("selected");} ?> value="26">26</option>
                    <option <?php if ($startday == "27") { print("selected");} ?> value="27">27</option>
                    <option <?php if ($startday == "28") { print("selected");} ?> value="28">28</option>
                    <option <?php if ($startday == "29") { print("selected");} ?> value="29">29</option>
                    <option <?php if ($startday == "30") { print("selected");} ?> value="30">30</option>
                    <option <?php if ($startday == "31") { print("selected");} ?> value="31">31</option>
                  </select>
                  <select size="1" name="lstStartMonth">
                    <option <?php if ($startmonth == "00") { print("selected");} ?> value="00">--</option>
                    <option <?php if ($startmonth == "01") { print("selected");} ?> value="01">Jan</option>
                    <option <?php if ($startmonth == "02") { print("selected");} ?> value="02">Feb</option>
                    <option <?php if ($startmonth == "03") { print("selected");} ?> value="03">Mar</option>
                    <option <?php if ($startmonth == "04") { print("selected");} ?> value="04">Apr</option>
                    <option <?php if ($startmonth == "05") { print("selected");} ?> value="05">May</option>
                    <option <?php if ($startmonth == "06") { print("selected");} ?> value="06">Jun</option>
                    <option <?php if ($startmonth == "07") { print("selected");} ?> value="07">Jul</option>
                    <option <?php if ($startmonth == "08") { print("selected");} ?> value="08">Aug</option>
                    <option <?php if ($startmonth == "09") { print("selected");} ?> value="09">Sep</option>
                    <option <?php if ($startmonth == "10") { print("selected");} ?> value="10">Oct</option>
                    <option <?php if ($startmonth == "11") { print("selected");} ?> value="11">Nov</option>
                    <option <?php if ($startmonth == "12") { print("selected");} ?> value="12">Dec</option>
                  </select>
                  <input type="text" name="txtStartYear" size="5" value="<?php echo $startyear ?>">
                </td>
              </tr>
              <tr align="left" valign="top">
                <td>To Date </td>
                <td>
                  <select size="1" name="lstEndDay">
                    <option <?php if ($endday == "00") { print("selected");} ?> value="00">--</option>
                    <option <?php if ($endday == "01") { print("selected");} ?> value="01">01</option>
                    <option <?php if ($endday == "02") { print("selected");} ?> value="02">02</option>
                    <option <?php if ($endday == "03") { print("selected");} ?> value="03">03</option>
                    <option <?php if ($endday == "04") { print("selected");} ?> value="04">04</option>
                    <option <?php if ($endday == "05") { print("selected");} ?> value="05">05</option>
                    <option <?php if ($endday == "06") { print("selected");} ?> value="06">06</option>
                    <option <?php if ($endday == "07") { print("selected");} ?> value="07">07</option>
                    <option <?php if ($endday == "08") { print("selected");} ?> value="08">08</option>
                    <option <?php if ($endday == "09") { print("selected");} ?> value="09">09</option>
                    <option <?php if ($endday == "10") { print("selected");} ?> value="10">10</option>
                    <option <?php if ($endday == "11") { print("selected");} ?> value="11">11</option>
                    <option <?php if ($endday == "12") { print("selected");} ?> value="12">12</option>
                    <option <?php if ($endday == "13") { print("selected");} ?> value="13">13</option>
                    <option <?php if ($endday == "14") { print("selected");} ?> value="14">14</option>
                    <option <?php if ($endday == "15") { print("selected");} ?> value="15">15</option>
                    <option <?php if ($endday == "16") { print("selected");} ?> value="16">16</option>
                    <option <?php if ($endday == "17") { print("selected");} ?> value="17">17</option>
                    <option <?php if ($endday == "18") { print("selected");} ?> value="18">18</option>
                    <option <?php if ($endday == "19") { print("selected");} ?> value="19">19</option>
                    <option <?php if ($endday == "20") { print("selected");} ?> value="20">20</option>
                    <option <?php if ($endday == "21") { print("selected");} ?> value="21">21</option>
                    <option <?php if ($endday == "22") { print("selected");} ?> value="22">22</option>
                    <option <?php if ($endday == "23") { print("selected");} ?> value="23">23</option>
                    <option <?php if ($endday == "24") { print("selected");} ?> value="24">24</option>
                    <option <?php if ($endday == "25") { print("selected");} ?> value="25">25</option>
                    <option <?php if ($endday == "26") { print("selected");} ?> value="26">26</option>
                    <option <?php if ($endday == "27") { print("selected");} ?> value="27">27</option>
                    <option <?php if ($endday == "28") { print("selected");} ?> value="28">28</option>
                    <option <?php if ($endday == "29") { print("selected");} ?> value="29">29</option>
                    <option <?php if ($endday == "30") { print("selected");} ?> value="30">30</option>
                    <option <?php if ($endday == "31") { print("selected");} ?> value="31">31</option>
                  </select>
                  <select size="1" name="lstEndMonth">
                    <option <?php if ($endmonth == "00") { print("selected");} ?> value="00">--</option>
                    <option <?php if ($endmonth == "01") { print("selected");} ?> value="01">Jan</option>
                    <option <?php if ($endmonth == "02") { print("selected");} ?> value="02">Feb</option>
                    <option <?php if ($endmonth == "03") { print("selected");} ?> value="03">Mar</option>
                    <option <?php if ($endmonth == "04") { print("selected");} ?> value="04">Apr</option>
                    <option <?php if ($endmonth == "05") { print("selected");} ?> value="05">May</option>
                    <option <?php if ($endmonth == "06") { print("selected");} ?> value="06">Jun</option>
                    <option <?php if ($endmonth == "07") { print("selected");} ?> value="07">Jul</option>
                    <option <?php if ($endmonth == "08") { print("selected");} ?> value="08">Aug</option>
                    <option <?php if ($endmonth == "09") { print("selected");} ?> value="09">Sep</option>
                    <option <?php if ($endmonth == "10") { print("selected");} ?> value="10">Oct</option>
                    <option <?php if ($endmonth == "11") { print("selected");} ?> value="11">Nov</option>
                    <option <?php if ($endmonth == "12") { print("selected");} ?> value="12">Dec</option>
                  </select>
                  <input type="text" name="txtEndYear" size="5"value="<?php echo $endyear ?>">
                </td>
                </tr>
              <tr align="left" valign="top">
                <td>Sponsor</td>
                <td>
                  <input name="radiobutton" type="radio" value="Y" <?php echo $Y_selected ?>> Yes
                    <input name="radiobutton" type="radio" value="N" <?php echo $N_selected ?>> No</td>
                </tr>
              <tr align="left" valign="top">
                <td>Category</td>
                <td>
                 <select name="categories[]" size="10" multiple>
                 <?php
                     $result=mysql_query("SELECT * FROM dir_categories", $link);
                    while($sql_categories = mysql_fetch_object($result)) {
                        foreach ($sql_catarray as $value) {
                            print("$sql_categories->cat_id - $value");
                            if ($sql_categories->cat_id==$value) {
                                $selected="selected"; break;
                            } else {
                                $selected="";
                            }
                        }
                        print("<option value='$sql_categories->cat_id' $selected>$sql_categories->cat_parent : $sql_categories->cat_child</option>\n");
                    }
                 ?>
                    </select>
                </td>
                </tr>
            </table>
            </form></td>
          </tr>
        </table>
<?php include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php"); ?>
