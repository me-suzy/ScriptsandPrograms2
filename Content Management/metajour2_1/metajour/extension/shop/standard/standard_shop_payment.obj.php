<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 314153;
function objfile_314153 () {
$obj = owNew('template');
$objdata['name'] = "standard_shop_payment";
$objdata['content'] = "{include file=\"standard_shop_orderdetails\" context=\"payment\"}
{#text#}
<input class=\"extbutton\" type=\"button\" value=\"{#buttontext#}\" onclick=\"location.href='{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=paymentaccept'\">

{*
{if \$result.paymentid == 313904}

{literal}
<script LANGUAGE=\"JavaScript\"><!--
newwin = null;
function doPopup(f) {
  newwin = window.open('', 'Betaling','scrollbars,status,width=550,height=600');
  newwin.focus();
  if(!self.name) { self.name = 'shopwin'; }
  if (!newwin.opener) { newwin.opener = self; }
  f.opener.value = self.name;
  return true;
} 
//-->
</SCRIPT>
{/literal}
<p>Tryk p&aring; &quot;betal&quot; for at aktivere DIBS betalingsvindue.</p>
<p>For at teste en betaling kan f&oslash;lgende testkort informationer benyttes:</p>

<p>Kortnummer: 12345<br>
  Udl&oslash;bsdato: 06/05<br>
  Kontrolv&aelig;rdi: 684</p>
<p> 
<form method=\"post\" action=\"https://payment.architrade.com/cashreg.pfml\" onsubmit=\"return doPopup(this);\" target=\"Betaling\"> 
  <input type=\"hidden\" name=\"test\" value=\"foo\">
  <input type=\"hidden\" name=\"opener\" value=\"\"> 
  <input type=\"hidden\" name=\"merchant\" value=\"4154645\">
  <input type=\"hidden\" name=\"orderid\" value=\"1234\">

  <input type=\"hidden\" name=\"lang\" value=\"da\"> 
  <input type=\"hidden\" name=\"color\" value=\"blue\"> 
  <input type=\"hidden\" name=\"amount\" value=\"128061\"> 
  <input type=\"hidden\" name=\"currency\" value=\"208\"> 
  <input type=\"hidden\" name=\"accepturl\" value=\"{\$me}&_ext={\$_ext}&_extcf={\$_extcf}&_cmd=paymentaccept\">
<!--  <input type=\"hidden\" name=\"callbackurl\" value=\"http://www.dibs.dk/index.php?id=290\">
  <input type=\"hidden\" name=\"cancelurl\" value=\"http://www.dibs.dk/index.php?id=290\">-->
  <input type=\"hidden\" name=\"delivery1.Navn\" value=\"Jens Hansen\"> 
  <input type=\"hidden\" name=\"delivery2.Adresse\" value=\"Holger Danskes Vej 40b, 3\"> 
  <input type=\"hidden\" name=\"delivery3.Postnummer\" value=\"2000\">
  <input type=\"hidden\" name=\"delivery4.By\" value=\"Frederiksberg\"> 
  <input type=\"hidden\" name=\"delivery5.Telefon\" value=\"70203077\">
  <input type=\"hidden\" name=\"delivery6.Email\" value=\"info@dibs.dk\">

  <input type=\"hidden\" name=\"ordline0-1\" value=\"Varenummer\"> 
  <input type=\"hidden\" name=\"ordline0-2\" value=\"Beskrivelse\"> 
  <input type=\"hidden\" name=\"ordline0-3\" value=\"Antal\"> 
  <input type=\"hidden\" name=\"ordline0-4\" value=\"Pris (DKK)\"> 
  <input type=\"hidden\" name=\"ordline1-1\" value=\"102\"> 
  <input type=\"hidden\" name=\"ordline1-2\" value=\"&AElig;blemost\"> 
  <input type=\"hidden\" name=\"ordline1-3\" value=\"8\"> 
  <input type=\"hidden\" name=\"ordline1-4\" value=\"100.13\"> 
  <input type=\"hidden\" name=\"ordline2-1\" value=\"201\"> 
  <input type=\"hidden\" name=\"ordline2-2\" value=\"Gyldne sko\"> 
  <input type=\"hidden\" name=\"ordline2-3\" value=\"1\"> 
  <input type=\"hidden\" name=\"ordline2-4\" value=\"50.12\"> 
  <input type=\"hidden\" name=\"ordline3-1\" value=\"111\"> 
  <input type=\"hidden\" name=\"ordline3-2\" value=\"&AElig;bler\"> 
  <input type=\"hidden\" name=\"ordline3-3\" value=\"8\"> 
  <input type=\"hidden\" name=\"ordline3-4\" value=\"80.12\">

  <input type=\"hidden\" name=\"ordline4-1\" value=\"864\"> 
  <input type=\"hidden\" name=\"ordline4-2\" value=\"&Aring;l\"> 
  <input type=\"hidden\" name=\"ordline4-3\" value=\"10\"> 
  <input type=\"hidden\" name=\"ordline4-4\" value=\"1000.12\"> 
  <input type=\"hidden\" name=\"priceinfo1.Leveringsomkostninger\" value=\"50.12\"> 
  <input type=\"hidden\" name=\"priceinfo2.Total\" value=\"1280.61\"> 
  <input type=\"submit\" name=\"next\" value=\"Betal med kreditkort\"> 
</form>
{else}
<form action=\"{\$me}\" method=\"post\">
<input type=\"hidden\" name=\"_ext\" value=\"{\$_ext}\">
<input type=\"hidden\" name=\"_extcf\" value=\"{\$_extcf}\">
<input type=\"hidden\" name=\"_cmd\" value=\"processaccept\">
<input type=\"hidden\" name=\"_ext_goto\" value=\"finish\">
<input type=\"submit\" value=\"Godkend bestilling\">
</form>
{/if}

*}";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = ".extcolorcell {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #dce1e5
}
.extlightcell {
        PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #ececec
}
.extdarkcell {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #c0c8d0
}
.extheader {
	PADDING-RIGHT: 4px; PADDING-LEFT: 4px; FONT-WEIGHT: bold; FONT-SIZE: 85%; BACKGROUND-IMAGE: url(img/extbg.gif); COLOR: #ffffff; WHITE-SPACE: nowrap; HEIGHT: 24px; BACKGROUND-COLOR: #006699
}
.exttablebg {
	BACKGROUND-COLOR: #a9b8c2
}
.extmsgbody {
	LINE-HEIGHT: 140%
}
.extbutton {
background-color: #c0c8d0; font-style: bold; font-size: 80%; font-weight: bold;
}";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "text = \"Credit card transaction should be put here\"
buttontext = \"Proceed\"

[DA]
text = \"<P>Her vil være indlejring af kreditkort-transaktion fra godkendt betalingsgateway efter eget valg.<P>Når betaling er gennemført, føres automatisk videre til ordreafslutning:<P>\"
buttontext = \"Gå til ordre-afslutning\"
";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
