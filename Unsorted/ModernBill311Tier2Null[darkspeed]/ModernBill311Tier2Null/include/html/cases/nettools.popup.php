<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+

class LG_Target {
  var $target;
  var $ip;
  var $fqdn;

  function LG_Target($tg) {
    $this->target=$tg;
    $this->get_ip();
    $this->get_fqdn();
    if ( !$this->is_ip() ) $this->get_ip();
    if ( !$this->is_fqdn() ) $this->get_fqdn();
  }

  function is_ip() {
    return ( !empty($this->ip) );
  }

  function is_fqdn() {
    return ( !empty($this->fqdn) );
  }

  function get_ip() {
    if ( ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$",$this->target) ) {
      $this->ip=$this->target;
    } else {
      if ( !$this->is_fqdn() ) $t=$this->target;
      else $t=$this->fqdn;
      $r=gethostbyname($t);
      if ( $t!=$r ) $this->ip=$r;
    }
  }

  function get_fqdn() {
    if ( !$this->is_ip() ) $t=$this->target;
    else $t=$this->ip;
    $r=@gethostbyaddr($t);
    if ( $t!=$r ) $this->fqdn=$r;
  }

  function is_rfc1918() {
    return ereg("^(192\.168|10|^172\.(1[6-9]|2[0-9]|3[0-2]))\.",$this->ip);
  }

  function go_ip($com) {
    if ( $this->is_rfc1918() ) {
      echo "<h4><font color=\"red\">Sorry, can't do, target: $this->target is RFC1918 space.</font></h4><br>\n";
    } elseif ( !$this->is_ip() ) {
        echo "<h4><font color=\"red\">Sorry, can't do, target: $this->target unresolvable.</font></h4><br>\n";
    } else {
      echo "<hr><h4>Target: $this->target, IP: $this->ip, FQDN: $this->fqdn<br>\n";
      $this->go("$com $this->target");
    }

  }

  function go_msc($com) {
    echo "<hr><h4>Target: $this->target<br>\n";
    $this->go("$com $this->target");
  }

  function go($com) {
    echo COMMAND.": $com</h4><hr>";
    echo "<pre>\n";
    system("$com 2>&1");
    echo "</pre><hr>\n";
  }

  function show() {
    if ( $this->is_rfc1918() ) {
      echo "<h4><font color=\"red\">Sorry, can't do, target: $this->target is RFC1918 space.</font></h4><br>\n";
    } else {
      echo "<hr><table border=\"0\" width=\"50%\"><tr><td>Target:</td><td>$this->target</td></tr>\n<tr><td>IP:</td><td>";
      if ( $this->is_ip() ) echo "$this->ip";
      else echo "Non Resolvable";
      echo "</td></tr>\n<tr><td>FQDN:</td><td>";
      if ( $this->is_fqdn() ) echo "$this->fqdn";
      else echo "Non Resolvable";
      echo "</td></tr></table>\n<hr>";
    }
  }
}
$whois_servers = array(
                 "ac"  => "whois.nic.ac",
                 "al"  => "whois.ripe.net",
                 "am"  => "whois.amnic.net",
                 "as"  => "whois.nic.as",
                 "at"  => "whois.nic.at",
                 "au"  => "whois.aunic.net",
                 "az"  => "whois.ripe.net",
                 "ba"  => "whois.ripe.net",
                 "be"  => "whois.dns.be",
                 "biz" => "whois.neulevel.biz",
                 "bg"  => "whois.ripe.net",
                 "br"  => "whois.registro.br",
                 "by"  => "whois.ripe.net",
                 "ca"  => "whois.cira.ca",
                 "cc"  => "whois.nic.cc",
                 "ch"  => "whois.nic.ch",
                 "ck"  => "whois.ck-nic.org.ck",
                 "cn"  => "whois.cnnic.net.cn",
                 "co.uk" => "nominet.org.uk",
                 "com" => "whois.nsiregistry.net",
                 "cx"  => "whois.nic.cx",
                 "cy"  => "whois.ripe.net",
                 "cz"  => "whois.nic.cz",
                 "de"  => "whois.denic.de",
                 "dk"  => "whois.dk-hostmaster.dk",
                 "dz"  => "whois.ripe.net",
                 "edu" => "rs.internic.net",
                 "ee"  => "whois.ripe.net",
                 "eg"  => "whois.ripe.net",
                 "es"  => "whois.ripe.net",
                 "fi"  => "whois.ripe.net",
                 "fj"  => "whois.usp.ac.fj",
                 "fo"  => "whois.ripe.net",
                 "fr"  => "whois.nic.fr",
                 "gb"  => "whois.ripe.net",
                 "gb.com" => "whois.nomination.net",
                 "gb.net" => "whois.nomination.net",
                 "ge"  => "whois.ripe.net",
                 "gov" => "whois.nic.gov",
                 "gr"  => "whois.ripe.net",
                 "gs"  => "whois.adamsnames.tc",
                 "hk"  => "whois.hknic.net.hk",
                 "hm"  => "whois.registry.hm",
                 "hr"  => "whois.ripe.net",
                 "hu"  => "whois.ripe.net",
                 "id"  => "whois.idnic.net.id",
                 "ie"  => "whois.domainregistry.ie",
                 "info" => "whois.afilias.info",
                 "int" => "whois.isi.edu",
                 "il"  => "whois.ripe.net",
                 "is"  => "whois.isnet.is",
                 "it"  => "whois.nic.it",
                 "jp"  => "whois.nic.ad.jp",
                 "ke"  => "whois.rg.net",
                 "kg"  => "whois.domain.kg",
                 "kr"  => "whois.nic.or.kr",
                 "kz"  => "whois.domain.kz",
                 "li"  => "whois.nic.li",
                 "lk"  => "whois.nic.lk",
                 "lt"  => "whois.ripe.net",
                 "lu"  => "whois.ripe.net",
                 "lv"  => "whois.ripe.net",
                 "ma"  => "whois.ripe.net",
                 "md"  => "whois.ripe.net",
                 "mil" => "whois.nic.mil",
                 "mk"  => "whois.ripe.net",
                 "mm"  => "whois.nic.mm",
                 "ms"  => "whois.adamsnames.tc",
                 "mt"  => "whois.ripe.net",
                 "mx"  => "whois.nic.mx",
                 "net" => "whois.nsiregistry.net",
                 "net.au" => "whois.net.au",
                 "nl"  => "whois.domain-registry.nl",
                 "no"  => "whois.norid.no",
                 "no.com" => "whois.nomination.net",
                 "nu"  => "whois.nic.nu", "nunames",
                 "nz"  => "whois.domainz.net.nz",
                 "org" => "whois.nsiregistry.net",
                 "pl"  => "whois.ripe.net",
                 "pk"  => "whois.pknic.net.pk",
                 "pt"  => "whois.ripe.net",
                 "ro"  => "whois.ripe.net",
                 "ru"  => "whois.ripn.ru",
                 "se"  => "whois.nic-se.se",
                 "se.com" => "whois.nomination.net",
                 "se.net" => "whois.nomination.net",
                 "sg"  => "whois.nic.net.sg",
                 "si"  => "whois.ripe.net",
                 "sh"  => "whois.nic.sh",
                 "sk"  => "whois.ripe.net",
                 "sm"  => "whois.ripe.net",
                 "st"  => "whois.nic.st",
                 "su"  => "whois.ripe.net",
                 "tc"  => "whois.adamsnames.tc",
                 "tf"  => "whois.adamsnames.tc",
                 "tj"  => "whois.nic.tj",
                 "th"  => "whois.thnic.net",
                 "tm"  => "whois.nic.tm",
                 "tn"  => "whois.ripe.net",
                 "to"  => "whois.tonic.to",
                 "tr"  => "whois.ripe.net",
                 "tw"  => "whois.twnic.net",
                 "ua"  => "whois.ripe.net",
                 "uk"  => "whois.nic.uk",
                 "uk.net" => "whois.nomination.net",
                 "uk.com" => "whois.nomination.net",
                 "us"  => "whois.isi.edu",
                 "va"  => "whois.ripe.net",
                 "vg"  => "whois.adamsnames.tc",
                 "ws"  => "whois.nic.ws",
                 "yu"  => "whois.ripe.net",
                 "za"  => "whois.frd.ac.za");

function whois($domain,$ext)
{
        GLOBAL $whois_servers;

        $server = ($whois_servers[$ext]!="") ? $whois_servers[$ext] : "whois.internic.net" ;
        $fp = fsockopen ($server, 43, &$errnr, &$errstr, 20) or die("$errno: $errstr");

        fputs($fp, "$domain\n");
        $whois_buffer = MFB."<b>[$domain = $server]</b>".EF;
        while (!feof($fp)) {
             $whois_buffer .= "<pre>".fgets($fp,2048)."</pre>";
        }
        fclose($fp);

        return $whois_buffer;
}

function arinwhois($domain)
{
        $fcontents = file ("http://www.arin.net/cgi-bin/whois.pl?queryinput=$domain");
        while (list ($line_num, $line) = each ($fcontents)) {
               $whois_buffer .= "<pre>$line</pre>";
        }
        return strip_tags($whois_buffer);
}

include_once("include/functions.inc.php");

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
echo "<span id=\"processing\">$type => $this_domain ...<br><br></span>";
flush();
?>

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
                <input type=radio name=type value=whois <?=($type=="whois")?"CHECKED":($type)?NULL:"CHECKED";;?>> <?=SFB.WHOIS.EF?><br>
                <input type=radio name=type value=trace <?=($type=="trace")?"CHECKED":NULL;?>> <?=SFB.TRACEROUTE.EF?><br>
                <input type=radio name=type value=ping <?=($type=="ping")?"CHECKED":NULL;?>> <?=SFB.PING.EF?><br>
                <input type=radio name=type value=ns <?=($type=="ns")?"CHECKED":NULL;?>> <?=SFB.NSLOOKUP.EF?><br>
                <input type=radio name=type value=mx <?=($type=="mx")?"CHECKED":NULL;?>> <?=SFB.MXRECORDS.EF?><br>
                <input type=radio name=type value=host <?=($type=="host")?"CHECKED":NULL;?>> <?=SFB.IP2HOST.EF?><br>
                <input type=radio name=type value=ip <?=($type=="ip")?"CHECKED":NULL;?>> <?=SFB.HOST2IP.EF?><br>
                <input type=radio name=type value=arin <?=($type=="arin")?"CHECKED":NULL;?>> <?=SFB.ARINWHOIS.EF?><br>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type=submit name=submit value="<?=SUBMIT?>"></td>
            </tr>
            </form>
            </table>
         </ul>
         <br><?=SFB.MORETOOLS.EF?><A href="http://www.samspade.org/t/" target="_blank">http://www.samspade.org/t/</a>
       </td>
     </tr>
     <tr>
     </table>

         <?=LFH?><b><?=RESULTS?>:</b><?=EF?>
         <br>
         <font size=+1>
         <ul>
            <?
            $domain = strtolower($domain);
            $domain = substr($domain,0,strspn($domain,"0123456789abcdefghijklmnopqrstuvwxyz.-"));
            $lg_tg  = new LG_Target($domain);

            switch ($type) {
                    case whois: echo "<pre>" . whois($this_domain,$ext) ."</pre>"; break;
                    case arin:  echo "<pre>" . arinwhois($this_domain) ."</pre>"; break;
                    case trace: $lg_tg->go_ip("traceroute");  break;
                    case ping:  $lg_tg->go_ip("ping -c10");   break;
                    case ns:    $lg_tg->show();               break;
                    case mx:    $lg_tg->go_msc("host -t MX"); break;
                    case host:  echo "<pre>" . gethostbyaddr($this_domain) ."</pre>"; break;
                    case ip:    echo "<pre>" . gethostbyname($this_domain) ."</pre>"; break;
                    default:    echo MFB.NETTOOLSTEXT.EF; break;
            }

            ?>
         </ul>
         </font>
<? echo "<script>processing.style.display='none'</script>"; ?>