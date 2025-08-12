<?

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth", "transfer" => "Credigold_Transfer"));

initPage();

$dc->query("SELECT * FROM ".$_Config["database_fund_records"]." WHERE account='".$auth->auth["unumber"]."' AND transaction_id='".get_session("transaction_id")."';");

if ($dc->num_rows() == 1 && $HTTP_REFERER && $auth->auth["unumber"])

	{

		$dc->next_record();

		if ($dc->get("status") == "Active")

			{

				$rc->query("UPDATE ". $_Config["database_details"]." SET crediGold=crediGold+".$dc->get("amount")." WHERE user_number='".$auth->auth["unumber"]."';");

				$rc->query("UPDATE ".$_Config["database_fund_records"]." SET status='Completed' WHERE account='".$auth->auth["unumber"]."' AND transaction_id='".get_session("transaction_id")."';");

				set_session("transaction_id", "");

				include($_PHPLIB["maindir"]."/ihtml/fund_success.ihtml");

			}

		else

			{

				include($_PHPLIB["maindir"]."/ihtml/fraud_loaded.ihtml");

			}

	}

else

	{

		include($_PHPLIB["maindir"]."/ihtml/fraud_loaded.ihtml");

	}

endPage();

?>