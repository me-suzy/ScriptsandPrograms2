<?
#########################################################
#                                                       #
#         ModernBill .:. Client Billing System          #
#         ====================================          #
#  Copyright © 2001 ModernBill   All Rights Reserved.   #
#                                                       #
#########################################################
include("config.php");
?>
<html>
<head>
<title><?=$company_name?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; CHARSET=<?=CHARSET?>">
<? if ($include_css) include("template/index.css"); ?>
</head>
<body bgcolor=FFFFFF>
<table align=center width=435 cellpadding=4>
<tr>
 <td>
  <center><?=MFH?><b><?=WHATISCVV2?></b><?=EF?></center><br>
  <?=CVV2IS?>
  <hr size=1>
  <?=MFH?><b><?=VISAMASTERCARD?>:</b><?=EF?><br><br>
  <center><IMG height=103 src="<?=$DIR?>images/visa_cvv2.gif"></center><br>
  <?=CVV2VISA?>
  <hr size=1>
  <?=MFH?><b><?=AMERICANEXPRESS?>:</b><?=EF?><br><br>
  <center><IMG height=129 src="<?=$DIR?>images/amex_cvv2.gif" width=248></center><br>
  <?=CVV2AMEX?>
  <hr size=1>
  <div align=right><A onclick="window.close(); return false;" href="#"><b><?=CLOSETHISWIN?></b></A></div>
 </td>
</tr>
</table>
</body>
</html>