<?

session_start();

mt_srand((double)microtime()*1000000);

loadTrial();



function initPage($load = "", $subs = "none", $dhtml = "off")

   {

      global $auth, $dc, $rc, $_Config, $sess;

      global $_PHPLIB, $PHP_SELF, $indexPrice;



	  // Sublevels Extraction Into Interface

      $t_resource = $_PHPLIB["maindir"]."ihtml/site_sub_transfer.ihtml";

      $m_resource = $_PHPLIB["maindir"]."ihtml/site_sub_merchant.ihtml";

      $a_resource = $_PHPLIB["maindir"]."ihtml/site_sub_account.ihtml";



      $fd       = fopen($t_resource, "r");

      while (!feof ($fd)) $t_buffer .= fgets($fd, 4096);

      fclose ($fd);



      $fd       = fopen($m_resource, "r");

      while (!feof ($fd)) $m_buffer .= fgets($fd, 4096);

      fclose ($fd);



      $fd       = fopen($a_resource, "r");

      while (!feof ($fd)) $a_buffer .= fgets($fd, 4096);

      fclose ($fd);



      $subT = eregi_replace("%path%", $_PHPLIB["http_path"]."/", $t_buffer);

      $subM = eregi_replace("%path%", $_PHPLIB["http_path"]."/", $m_buffer);

      $subA = eregi_replace("%path%", $_PHPLIB["http_path"]."/", $a_buffer);



      // SubMenus Generator

      $options = Array("", $subT, $subM, $subA);



      // crediGold Index Extraction

         $dc->query("SELECT * FROM ".$_Config["database_index"].";");

         $dc->next_record();

         $upd   = strftime("%m/%d/%Y %H:%M",$dc->get("updated"));

         $price = $dc->get("index");

		 $indexPrice = $price; // Global Var Allocation

      // End of Index

	

      // People Online

	 if ($_Config["track_online"])

		 {

			if (!$auth->auth["userNumber"]) // KIRO, ne znam function-a ako si logged, sloji go kato vidish tozi comment

				{

				  $rc->query("SELECT IP FROM ".$_Config["database_online"]." WHERE IP='".getIP()."' AND account='Guest';");

				  if ($rc->num_rows() == 0)

					{

						$rc->query("INSERT INTO ".$_Config["database_online"]." SET account='Guest', IP='".getIP()."', last='".time()."', url='".getURL()."';");

					}

				  else

					{

						$rc->query("UPDATE ".$_Config["database_online"]." SET last='".time()."', url='".getURL()."' WHERE IP='".getIP()."' AND account='Guest';");

					}

				}

			$rules = ($auth->auth["userNumber"])?"AND account!='".$auth->auth["userNumber"]."'":"";

			$rc->query("DELETE FROM ".$_Config["database_online"]." WHERE last < ".(time()-300)." $rules;");

		 }

	  // End of People



      // Meta Generation	

      $rc->query("SELECT * FROM ".$_Config["database_meta"].";");

      $rc->next_record();

      $desc   = $rc->get("description");

      $keys   = $rc->get("keywords");

      $custom = $rc->get("custom");

      $chars  = $rc->get("encoding");

      // End of Meta



      $dhtml_enabled = ($dhtml != "off")?("<script language=JavaScript src=".$_PHPLIB["http_path"]."/modules/mod_dhtml.js></script>"):"";

      $loading       = ($load != "")?"onload='$load'":"";

      $log_men       = (isset($auth))?$auth->login_logout():"";

      $acc_user      = (isset($auth))?$auth->who_is():"";

      $sub_menues    = ($subs == "none")?"<!-- Nothing loaded-->":$options[$subs];

      $bannerSystem  = renderBannersSystem();

      $admins        = (eregi("admin", $PHP_SELF))?"

                  <p><ul class=text>

                  <li><a href=index.php?cmd=fund>Fund Options Setup</a>

                  <li><a href=index.php?cmd=withdraw>Withdrawals Admin</a>

                  <li><a href=index.php?cmd=credigold>Currency Index</a>

                  <li><a href=index.php?cmd=load>Load <b>Units</b></a>

                  <li><a href=index.php?cmd=calendar>Transfers Calendar</a>

                  <li><a href=index.php?cmd=profiles>View Profiles</a>

                  <li><a href=index.php?cmd=history>Transactions Viewer</a>

                  <li><a href=index.php?cmd=ip_blocking>Global IP Blocking</a>

                  <li><a href=index.php?cmd=newsletters>Global Newsletter</a>

                  <li><a href=index.php?cmd=online>People Online</a>

                  <li><a href=index.php?cmd=pages>Custom Pages Editor</a>

                  <li><a href=index.php?cmd=emails>Custom Emails Editor</a>

                  <li><a href=index.php?cmd=meta>META Data Editor</a>

                  <li><a href=index.php?cmd=backup>Database BackUp</a>

                  <li><a href=index.php?cmd=banners>Banners Manager</a>

                  <li><a href=index.php?cmd=faq>FAQ Administrator</a>

                  </ul></p>":

                  "";



      $buffer   = "";

      $resource = $_PHPLIB["maindir"]."ihtml/site_header.ihtml";

      $fd       = fopen($resource, "r");

	  if ($fd && $_Config["construction"] == "no")

	  	{

			  while (!feof ($fd)) $buffer .= fgets($fd, 4096);

			  fclose ($fd);

			  $footer = eregi_replace("%path%", $_PHPLIB["http_path"]."/", $buffer);

			  $footer = eregi_replace("%siteName%", $_Config["masterRef"], $footer);

			  $footer = eregi_replace("%siteSlogan%", $_Config["masterSlogan"], $footer);

			  $footer = eregi_replace("%siteUnit%", $_Config["masterSign"], $footer);

			  $footer = eregi_replace("%siteYear%", strftime("%Y", time()), $footer);

			  $footer = eregi_replace("%keywords%", $keys, $footer);

			  $footer = eregi_replace("%description%", $desc, $footer);

			  $footer = eregi_replace("%encoding%", $chars, $footer);

			  $footer = eregi_replace("%custom_meta%", $custom, $footer);

			  $footer = eregi_replace("%dhtml%", $dhtml_enabled, $footer);

			  $footer = eregi_replace("%loading%", $loading, $footer);

			  $footer = eregi_replace("%sub_menues%", $sub_menues, $footer);

			  $footer = eregi_replace("%banner_system%", $bannerSystem, $footer);

			  $footer = eregi_replace("%admin_tools%", $admins, $footer);

			  $footer = eregi_replace("%last_updated%", $upd, $footer);

			  $footer = eregi_replace("%unitPrice%", $price, $footer);

			  $footer = eregi_replace("%logout_menu%", $log_men, $footer);

			  $footer = eregi_replace("%account_user%", $acc_user, $footer);

			  print $footer;

		}

	else

		{

			include($_PHPLIB["maindir"]."ihtml/under_construction.ihtml");

			exit;

		}

   }



function endPage()

   {

    global $_PHPLIB, $_Config;

    $buffer   = "";

    $resource = $_PHPLIB["maindir"]."ihtml/site_footer.ihtml";

    $fd       = fopen($resource, "r");

	if ($fd && $_Config["construction"] == "no")

	  	{

			  while (!feof ($fd)) $buffer .= fgets($fd, 4096);

			  fclose ($fd);

			  $footer = eregi_replace("%path%", $_PHPLIB["http_path"]."/", $buffer);

			  $footer = eregi_replace("%siteName%", $_Config["masterRef"], $footer);

			  $footer = eregi_replace("%siteYear%", strftime("%Y", time()), $footer);

			  print $footer;

		}

	else

		{

			include($_PHPLIB["maindir"]."ihtml/under_construction.ihtml");

			exit;

		}

   }

function secret_question_form($which = "none")

   {

      $secret_questions = array();

      $secret_questions[0] = "Your favourite food";

      $secret_questions[1] = "Your mothers maiden name";

      $secret_questions[2] = "Your pets name";

      $secret_questions[3] = "Your place of birth";

?>

      <select name=secret_question class=box>

         <?for ($i=0;$i<count($secret_questions);$i++) { ?>

            <option value="<?=$secret_questions[$i]?>" <?if ($secret_questions[$i] == $which) print "selected"?>> <?=$secret_questions[$i]?> </option>

         <? }?>

      </select>

<?

   }

function renderBannersSystem()

   {

      global $_PHPLIB, $bc, $_Config;

      $bc->query("SELECT id FROM ".$_Config["database_banners"].";");

	  if ($bc->num_rows() > 0)

	  	{

			  $ids = array();

			  for ($i=0;$i<$bc->num_rows();$i++)

				 {

					$bc->next_record();

					array_push($ids, $bc->get("id"));

				 }

			  $randval .= mt_rand(0, count($ids)-1);

			  $bc->query("SELECT banner_code FROM ".$_Config["database_banners"]." WHERE id='".$ids[$randval]."';");

			  $bc->next_record();

			  $buffer = "<div align=center>".$bc->get("banner_code")."</div><br /><br />";

			  return $buffer;

		}

   }

function extractStatisticalData()

   {

      global $dc, $rc, $ac, $bc, $_Config;

      $data = array();

      $dc->query("SELECT ".$_Config["database_auth"].".*, ".$_Config["database_details"].".crediGold FROM ".$_Config["database_auth"].", ".$_Config["database_details"]." WHERE ".$_Config["database_auth"].".user_id=".$_Config["database_details"].".user_id;");

      $data[totalUsers]   = $dc->num_rows();

      $data[confirmed]    = 0;

      $data[nonconfirmed] = 0;

      $data[referred]     = 0;

      $data[locked]       = 0;

      for ($i=0;$i<$dc->num_rows();$i++)

         {

            $dc->next_record();

            if ($dc->get("active") == "Y")	$data[confirmed]    = $data[confirmed] + 1;

            if ($dc->get("active") == "N")	$data[nonconfirmed] = $data[nonconfirmed] + 1;

            if ($dc->get("lock")   == "Y")	$data[locked]       = $data[locked] + 1;

            if ($dc->get("referrer"))		   $data[referred]     = $data[referred] + 1;

            if ($dc->get("crediGold") == 0)	$data[nofunds]      = $data[nofunds] + 1;

            else							         $data[usersFunds]   = $data[usersFunds] + $dc->get("crediGold");

         } // end for



      $dc->query("SELECT * FROM ".$_Config["database_transactions"].";");

      $data[totalTransactions]   = $dc->num_rows();

      $data[crediVolume]         = 0;

      $data[feesCollect]         = 0;

      for ($i=0;$i<$dc->num_rows();$i++)

         {

            $dc->next_record();

            $data[crediVolume] = $data[crediVolume] + $dc->get("amount");

            $data[feesCollect] = $data[feesCollect] + $dc->get("transaction_fee");

         } // end for



      $dc->query("SELECT * FROM ".$_Config["database_requests"].";");

      $data[totalRequests]  = $dc->num_rows();

      $data[pending]        = 0;

      $data[denied]         = 0;

      $data[requestVolume]  = 0;

      for ($i=0;$i<$dc->num_rows();$i++)

         {

            $dc->next_record();

            if ($dc->get("status") == "Denied")		$data[denied] = $data[denied] + 1;

            if ($dc->get("status") == "Pending")	$data[pending] = $data[pending] + 1;

            $data[requestVolume] = $data[requestVolume] + $dc->get("amount");

         } // end for

      return $data;

   }

?>