<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
$DIR = "../../";
include_once($DIR."include/functions.inc.php");
$is_popup = TRUE;

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if ($domain)
{
    ereg("\.(.*)",$domain,$args);
    $ext = $args[1];
}
else
{
    $domain = str_replace("www.","",$HTTP_SERVER_VARS["HTTP_HOST"]) ;
}
$this_domain = $domain;
print str_repeat(" ", 300) . "\n";
flush();
?>
<html>
<head><title><?=NETWORKINGTOOLS?></title></head>
<body>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr>
       <td valign=top>
         <?=LFH?><b><?=NETWORKINGTOOLS?>:</b><?=EF?>
         <br>
         <ul>
            <table border=0 cellpadding=2 cellspacing=2>
            <form method=post action=<?$PHP_SELF?>>
            <tr>
              <td>
               <?=SFB?><b><?=HOST?>:</b><?=EF?>
              </td>
              <td>
               <input type=text name=domain value="<?=$domain?>" size=25 maxlength=100>
              </td>
            </tr>
            <tr>
              <td>
               <?=SFB?><b><?=TYPE?>:</b><?=EF?>
              </td>
              <td>
                <select name=type>
                <option value=whois <?=($type=="whois")?"SELECTED":($type)?NULL:"SELECTED";;?>><?=WHOIS?></option>
                <option value=trace <?=($type=="trace")?"SELECTED":NULL;?>><?=TRACEROUTE?></option>
                <option value=ping <?=($type=="ping")?"SELECTED":NULL;?>><?=PING?></option>
                <option value=ns <?=($type=="ns")?"SELECTED":NULL;?>><?=NSLOOKUP?></option>
                <option value=mx <?=($type=="mx")?"SELECTED":NULL;?>><?=MXRECORDS?></option>
                <option value=host <?=($type=="host")?"SELECTED":NULL;?>><?=IP2HOST?></option>
                <option value=ip <?=($type=="ip")?"SELECTED":NULL;?>><?=HOST2IP?></option>
                <option value=allhosts <?=($type=="allhosts")?"SELECTED":NULL;?>><?=ALLHOSTS?></option>
                <option value=arin <?=($type=="arin")?"SELECTED":NULL;?>><?=ARINWHOIS?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type=submit name=submit value="<?=SUBMIT?>"></td>
            </tr>
            </form>
            </table>
         </ul>
       </td>
     </tr>
     <tr>
     </table>

         <?=LFH?><b><?=RESULTS?>:</b><?=EF?> <span id="processing"><font color=RED><?=SFB?><?=PROCESSING?> ...<?=EF?></font></span>
         <br>
         <ul>
            <?
            $domain = strtolower($domain);
            $domain = substr($domain,0,strspn($domain,"0123456789abcdefghijklmnopqrstuvwxyz.-"));
            $lg_tg  = new LG_Target($domain);

            switch ($type) {
                    case whois:    echo "<pre>" . basic_whois($this_domain,$ext) ."</pre>"; break;
                    case arin:     echo "<pre>" . arinwhois($this_domain) ."</pre>"; break;
                    case trace:    $lg_tg->go_ip("traceroute");  break;
                    case ping:     $lg_tg->go_ip("ping -c10");   break;
                    case ns:       $lg_tg->show();               break;
                    case mx:       $lg_tg->go_msc("host -t MX"); break;
                    case allhosts: echo "<pre>" . hostdrilldown($this_domain) ."</pre>"; break;
                    case host:     echo "<pre>" . gethostbyaddr($this_domain) ."</pre>"; break;
                    case ip:       echo "<pre>" . gethostbyname($this_domain) ."</pre>"; break;
                    default:       echo MFB.NETTOOLSTEXT.EF; break;
            }

            ?>
         </ul>
<? echo "<script>processing.style.display='none'</script>"; ?>
</body>
</html>