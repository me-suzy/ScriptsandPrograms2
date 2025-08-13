<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

$uname = (exec("uname -a")) ? exec("uname -a") : UNKNOWN." ".UNKNOWN." ".UNKNOWN;
list($system,$host,$kernel) = split(" ",exec("uname -a"), 5);
$uptime  = (exec("uptime")) ? exec("uptime") : UNKNOWN ;

$cpuinfo = (!ini_get("open_basedir")&&file_exists("/proc/cpuinfo")) ? file("/proc/cpuinfo") : NULL ;

for ($i = 0; $i < count($cpuinfo); $i++) {
     list($item, $data) = split(":", $cpuinfo[$i], 2);
     $item = chop($item);
     $data = chop($data);
     if ($item == "processor") {
          $total_cpu++;
          $cpu_info = $total_cpu;
     }
     switch ($item) {
          case vendor_id:      $cpu_info .= $data; break;
          case name:           $cpu_info .= $data; break;
          case cpu." ".MHz:    $cpu_info .= " " . floor($data); $found_cpu = YES ; break;
          case cache." ".size: $cache = $data; break;
          case bogomips:       $bogomips = $data; break;
     }

}
if($found_cpu != YES) { $cpu_info .= " <b>".UNKNOWN."</b>"; }
$cpu_info .= " ".MGHZ;

function lookup_ports($hport,$who)
{
         $fp = fsockopen($who, $hport, &$errno, &$errstr, 4);
         if (!$fp){
             $data = "<font color=red>".NOTOK."</font>";
         } else {
             $data = "<font color=green>".OK."</font>";
             fclose($fp);
         }
         return $data;
}
?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr>
       <td valign=top>
        <?=LFH?><b><?=SERVERINFO?>:</b><?=EF?>
        <br>
        <ul>
            <li> <b><?=HOST?>:</b> <?=$host?></li>
            <li> <b><?=SYSTEMTIME?>:</b> <?=date("D M d h:i:s T Y")?></li>
            <li> <b><?=KERNAL?>:</b> <?=$system?></li>
            <li> <b><?=CPU?>:</b> <?=$cpu_info?></li>
            <li> <b><?=CACHE?>:</b> <?=$cache?></li>
            <li> <b><?=UPTIME?>:</b> <?=$uptime?></li>
            <li> <b><?=OS?>:</b> <?=php_uname()?></li>
        </ul>
       </td>
     </tr>
     <tr>
       <td valign=top>
        <?=LFH?><b><?=SERVERSTATS?>:</b><?=EF?>
        <br>
        <ul>
         <?
         $domain = ($domain) ? strip_tags($domain) : str_replace("www.","",$HTTP_SERVER_VARS["HTTP_HOST"]) ;
         echo "<li><b>".DOMAIN.":</b> $domain</li>";
         echo "<li><b>HTTP:</b> <i>".PORT." 80 = <b>".lookup_ports("80",$domain)."</b></i></li>";
         echo "<li><b>HTTPS:</b> <i>".PORT." 443 = <b>".lookup_ports("443",$domain)."</b></i></li>";
         echo "<li><b>FTP:</b> <i>".PORT." 21 = <b>".lookup_ports("21",$domain)."</b></i></li>";
         echo "<li><b>SSH:</b> <i>".PORT." 22 = <b>".lookup_ports("22",$domain)."</b></i></li>";
         echo "<li><b>SMTP:</b> <i>".PORT." 25 = <b>".lookup_ports("25",$domain)."</b></i></li>";
         echo "<li><b>POP:</b> <i>".PORT." 110 = <b>".lookup_ports("110",$domain)."</b></i></li>";
         echo "<li><b>MySQL:</b> <i>".PORT." 3306 = <b>".lookup_ports("3306",$domain)."</b></i></li>";
         ?>
        </ul>
       </td>
     </tr>
     <form method=post action=<?=$page?>?op=menu&tile=<?=$tile?>>
     <tr><td><ul><input type=text name=domain value=<?=$domain?> size=20 maxlength=255>&nbsp;<?=GO_IMG?></ul></td></tr>
     </form>
    </table>
  </td>
</tr>