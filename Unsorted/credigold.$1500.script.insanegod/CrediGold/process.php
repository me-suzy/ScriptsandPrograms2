<?php

/*----------------[			crediGold Payment Processor  	      ]------------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 20.4.2003                                                              */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com)*/

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth", "transfer" => "Credigold_Transfer"));



if ($HTTP_POST_VARS['id'] && $HTTP_POST_VARS['amount'] && eregi("transfer\.php\?cmd=fund&id=\d*", $HTTP_REFERER))

	{

		$dc->query("SELECT ".$_Config["database_fund"].".*, ".$_Config["database_index"].".`index` FROM ".$_Config["database_fund"].", ".$_Config["database_index"]." WHERE ".$_Config["database_fund"].".id='".$HTTP_POST_VARS['id']."';");

		$dc->next_record();



		$m = 0;

		preg_match_all ("/\[\"(.*?)\", \"(.*?)\"\]/i", $dc->get("pass_vars"), $matches);

		$all = explode(" ", "a b d e f h k l m n p q r s t w x y z 1 2 3 4 5 6 7 8 9");

		$random = "";

		for($i=0;$i<50;$i++)

			{

				mt_srand((double)microtime()*1000000);

				$randy = rand(0, count($all)-1);

				$random .= $all[$randy];

			}

		foreach($HTTP_POST_VARS as $key => $value) // Processing the POST variables

			{

				if ($key !== "amount")

					{

						$_VARS[$m][name]   = $key;

						$_VARS[$m][value]  = $value;

						$m++;

					}

			}

		for ($i=0;$i<count($matches[0]);$i++) // Processing the internal routines

			{

				$_VARS[$m][name]   = $matches[1][$i];

				$_VARS[$m][value]  = eregi_replace("%dollars%", sprintf("%.2f", $dc->get("index")*$HTTP_POST_VARS['amount']), $transfer->changeValue($matches[2][$i]));

				$m++;

			}

		$thank = split("~\|~", $dc->get("thank_you")); // Thank You Page Generation

		$thost = $dc->get("target");

		$_VARS[$m][name]  = $thank[0];

		$_VARS[$m][value] = $thank[1];

		set_session("transaction_id", $random);

		$dc->query("INSERT INTO ".$_Config["database_fund_records"]." SET id='', account='".$auth->auth["unumber"]."', amount='".$HTTP_POST_VARS['amount']."', transaction_id='".$random."', transaction_ip='".getIP()."', method='".$HTTP_POST_VARS['id']."', dateIt='".time()."';");



		for ($i=0;$i<count($_VARS);$i++)

			{

				$mvalue = $_VARS[$i][value]; 

				$mkey   = $_VARS[$i][name];

				$data  .= "<input type=hidden name=\"$mkey\" value=\"$mvalue\">";

			}

		initPage();

		print "\t<div class=head align=center style=color:green>Processing Payment...Please Wait!</div><form name=process method=POST action=\"".$thost."\"><script>private_key=['".encodeIt($data)."','".strlen($data)."'];</script><script src=modules/mod_gen.php></script></form>";

		endPage();

	}

else

	{

		initPage();

		include($_PHPLIB["maindir"]."/ihtml/fraud_general.ihtml");

		endPage();

	}

?>

