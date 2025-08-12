<?
include ('../includes/global.php');
if($Task == "Tier_Settings") { $output=setup_Tiers($Task2); }
print $output;

function setup_Tiers($Task2)
{
    global $B1,$Task3;
    global $admin_url,$include_path;

    if($Task2 == "Edit")
    {
        if($Task3 != "Configure" && $Task2 != "Save")
        {
            if(!file_exists("$include_path/vars_commission.php") || $Task3 == "ConfigureTier")
            {
                if($Task3 == "ConfigureTier")
                {
                    if(file_exists("$include_path/vars_commission.php")) { include ("$include_path/vars_commission.php");}
                }
                $output = <<< HTM
            <form action="$admin_url/setup_tiers.php?Task=Tier_Settings&Task2=Edit&Task3=Configure" method="post"><center>
            <table border="0" cellSpacing="0" cellPadding="5" width="500" border="1">
            <tbody><tr>
            <td width="100%">
            <p align="center">
             <font face="Arial" size="3"><b>Member Tier and Commission Settings</b>
             </font></p>
              </td>
              </tr>
              <tr>
                 <td width="100%">
                     <div align="center">
                      <table cellSpacing="0" cellPadding="6" width="500" border="0">
                         <tbody>
                             <tr>
                                <td align="right" width="221">
                                <font face="Arial" size="2">Number of Tiers</font></td>
                                      <td width="317">
                                      <input type=text name="Tiers" size="5" maxlength=3 value="$Total_Tiers">
                                      </td>
                             </tr>
                         </tbody></table></div></td></tr></tbody></table>
         <br><br>
         <input type=submit name="B1" value="  CONFIGURE  "></center>
         </form>
HTM;
        }
        elseif(file_exists ("$include_path/vars_commission.php"))
        {
            header("Location:$admin_url/setup_tiers.php?Task=Tier_Settings&Task2=Edit&Task3=Configure");
        }
    }
    elseif($Task3 == "Configure")
    {
        if(file_exists("$include_path/vars_commission.php"))
        {
            include ("$include_path/vars_commission.php");
            $link = <<<HTM
                <a href="$admin_url/setup_tiers.php?Task=Tier_Settings&Task2=Edit&Task3=ConfigureTier">
                <center><font face="arial" color="red" size="3">Configure Tier Level</font></center></a>
HTM;
        }
        global $Tiers;
        if(!$Tiers)
            $Tiers=$Total_Tiers;
        for($i=0;$i<$Tiers;$i++)
        {
            $j=$i+1;
            $click_commission_text.= <<<HTM
<font face="Arial" size="2">Tier $j Rate&nbsp; </font>
<input size="4" name="PCRate[]" value="$PerClickRate[$i]"><br>
HTM;
        }
        for($i=0;$i<$Tiers;$i++)
        {
            $j=$i+1;
            $referral_commission_text.= <<<HTM
<font face="Arial" size="2">Tier $j Rate&nbsp; </font>
<input size="4" name="PRRate[]" value="$PerReferralRate[$i]"><br>
HTM;
       }

       $output = <<< HTM
       <form name="frm1" action="$admin_url/setup_tiers.php?Task=Tier_Settings&Task2=Save" method="post">
            <input type=hidden value="$Tiers" name="Tiers_Nos"><center>
            <table border="0" cellSpacing="0" cellPadding="5" width="500" border="1">
            <tbody><tr>
            <td width="100%">
            <p align="center">
             <font face="Arial" size="3"><b>Member Tier and Commission Settings</b>
             </font></p>
              </td>
              </tr>
              <tr>
                 <td width="100%">
                     <div align="center">
                      <table cellSpacing="0" cellPadding="6" width="500" border="0">
                         <tbody>
                           <tr>
                            <td align="right" width="221">
                              <font face="Arial" size="2">Pay Per Click Commission Rate</font></td>
                              <td width="317">
                                 $click_commission_text
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="221">
                               <font face="Arial" size="2">Pay Per Referral Commission Rate</font></td>
                               <td width="317">
                                 <br><br> $referral_commission_text
                               </td>
                             </tr>

                       </tbody></table></div></td></tr></tbody></table>

         <br><br>
         <input type=submit name="B1" value="  SAVE  "></center>
         </form><br><br>$link <br><br><pre>Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
HTM;

      }

    }
    elseif($Task2 == "Save")
    {
        global $Tiers_Nos,$PCRate,$PRRate;
        for($i=0;$i<$Tiers_Nos;$i++)
        {
            $Click_Commn.= "\$PerClickRate[$i] = ".$PCRate[$i].";\n";
        }
        for($i=0;$i<$Tiers_Nos;$i++)
        {
            $Referral_Commn.= "\$PerReferralRate[$i] = ".$PRRate[$i].";\n";
        }
        $contents = <<< HTM
<?
\$Total_Tiers = "$Tiers_Nos";

$Click_Commn
$Referral_Commn
?>
HTM;
        //writing variable to the vars_money.php file
        file_writer("$include_path/vars_commission.php",$contents);

        $output = <<<HTM
 <center><font color=green><b>Datas SuccessfullyUpdated</b></font><br><br>
 The page will automatically redirected to Admin Index page. <br><br>
 If not redirected within 5 seconds,Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a>
<META HTTP-EQUIV=REFRESH CONTENT="2; URL=$admin_url/admin.php"></center>
HTM;
    }
     return $output;
}
?>