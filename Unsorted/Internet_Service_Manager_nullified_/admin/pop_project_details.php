<HTML>
<HEAD>
<TITLE>Project Details!</TITLE>
</HEAD>
<BODY bgcolor="#efefef">
<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "auth.php";

$pr=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='$project_id'"));
    $res=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$pr[client_id]."'"));
//general details..

 echo '<script language="javascript">
        function clientgo()
        {
          window.opener.location="client_list.php?client_id='.$res[id].'";
        }

    </script>';

echo '     <font face="Verdana, Arial, Helvetica, sans-serif" size=2> '."<B>General details for project: ".$pr[id]."</B><P>";

echo '<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Project
        Name: </font></font></font></div>
    </td>
    <td width="244">
     <font face="Verdana, Arial, Helvetica, sans-serif" size=2> '.$pr[project_name].'
    </td>
  </tr>
  <tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Client: </font></font></font></div>
    </td>
    <td width="244"><font face="'.$admin_font.'" size="2"><a href="javascript: clientgo()">'.$res[name].'</a></td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Start
        Date: </font></font></font></div>
    </td>
    <td width="244"><font face="'.$admin_font.'" size="2">'.date("F j, Y, g:i a", $pr[start_date]).'</td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Finish
        Date (mm/dd/yyyy): </font></font></font></div>
    </td>
    <td width="244">
           <font face="Verdana, Arial, Helvetica, sans-serif" size=2> ';
           if($pr[finish_date]>1){echo date("F j, Y, g:i a", $pr[finish_date]);}else{echo "Not Specified";}
           echo '
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Status:
        </font></font></font></div>
    </td>
    <td width="244">
      <font face="Verdana, Arial, Helvetica, sans-serif" size=2>';
         $status=array(0=>"Active", 1=>"Suspended", 2=>"Completed", 3=>"Proposal");
         echo $status[$pr[status]];
      echo '
    </td>
  </tr>
  
    <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Bill After:
        </font></font></font></div>
    </td>
    <td width="244">       <font face="Verdana, Arial, Helvetica, sans-serif" size=2> ';
             $bill_in=array(0=>"Not Set", 1=>"At Completion", 2=>"Each Stage");
         echo $bill_in[$pr[bill_in]];
    echo '</td>
  </tr>
  
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Description:
        </font></font></font></div>
    </td>
    <td width="244">
      <font face="Verdana, Arial, Helvetica, sans-serif" size=2>'.nl2br($pr[description]).'
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Project
        Manager: </font></font></font></div>
    </td>
    <td width="244"> <font face="Verdana, Arial, Helvetica, sans-serif" size=2>';
              $pm=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='".$pr[project_manager]."'"));
              echo $pm[firstname].' '.$pm[lastname].' ('.$pm[title].')';
      echo '
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Admins:
        </font></font></font></div>
    </td>
    <td width="244">  <font face="Verdana, Arial, Helvetica, sans-serif" size=2>';
                  $them=explode(",", $pr[admins]);
                    foreach($them as $t){
                           $pm=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$t'"));
                           if($pm){echo $pm[firstname].' '.$pm[lastname].' ('.$pm[title].')<BR>';}
                    }
    echo '

    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Comments:
        </font></font></font></div>
    </td>
    <td width="244">
      <font face="Verdana, Arial, Helvetica, sans-serif" size="2">'.$pr[comments].'
    </td>
  </tr>
<tr bgcolor="black"><td width=1></td><td width=1></td><td width=1></td></tr>
</table>
<P><B>Stages...</B><P>';




//current stages!

$stages=mysql_query("SELECT * FROM project_stages WHERE project_id='$project_id'");

while($st=mysql_fetch_array($stages)){

 echo '<input type="hidden" name="existingid['.$x.']" value="'.$st[id].'"><input type=hidden name="stages_control['.$x.']" value="old"><table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Stage
        Name: </font></font></font></div>
    </td>
    <td width="244">
      <font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
      echo $st[stage_name];
          if($st[completed]==1){echo "&nbsp;<font size=1>(Stage Completed)</font>";}
      echo '</td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Description:
        </font></font></font></div>
    </td>
    <td width="244">
      <font face="Verdana, Arial, Helvetica, sans-serif" size="2">'.nl2br($st[description]).'
    </td>
  </tr>
    <tr>
    <td width="28">&nbsp;</td>
    <td width="100" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Payment Info:
        </font></font></font></div>
    </td>
    <td width="244">
      <font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
          if($st[billed==1] && $st[paid]==1){
              echo "Billed and Payed";
      }elseif($st[billed==1]){
            echo "Billed";
      }else{
            echo "Not Billed Yet";
      }

      echo '</td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Start
        Date: </font></font></font></div>
    </td>
    <td width="244"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
                   if($st[start_date]>1){echo date("F j, Y, g:i a", $st[start_date]);}else{echo "Not Specified";}
   echo' </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Finish
        Date (mm/dd/yyyy): </font></font></font></div>
    </td>
    <td width="244">
 <font face="Verdana, Arial, Helvetica, sans-serif" size="2">';
                   if($st[finish_date]>1){echo date("F j, Y, g:i a", $st[finish_date]);}else{echo "Not Specified";}
 echo'    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Cost:
        </font></font></font></div>
    </td>
    <td width="244">
       <font face="Verdana, Arial, Helvetica, sans-serif" size="2">'.$payment_unit.$st[cost].'
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Details:
        </font></font></font></div>
    </td>
    <td width="244">
       <font face="Verdana, Arial, Helvetica, sans-serif" size="2">'.$st[details].'
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="100" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Comments:
        </font></font></font></div>
    </td>
    <td width="244">
       <font face="Verdana, Arial, Helvetica, sans-serif" size="2">'.$st[comments].'
    </td>
  </tr>
<tr bgcolor="black"><td width=1></td><td width=1></td><td width=1></td></tr></table><P>
';

}

echo '<font size="1"><a href="javascript: window.close()">Close Me</a></font>';

include "footer.php";
?>
