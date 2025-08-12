<?php

//define ("PB_CRYPT_LINKS" , "1");

function DoEvents($this) {
	global $_CONF , $_TSM;

	$_TSM["MENU"] = "";

	//checking if user is logged in
	if (!$_SESSION["minibase"]["user"]) {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			//autentificate
			$user = $this->db->QFetchArray("select * from {$this->tables[users]} where `user_login` = '{$_POST[user]}' AND `user_password` = '{$_POST[pass]}'");

			if (is_array($user)) {
				$_SESSION["minibase"]["user"] = 1;
				$_SESSION["minibase"]["raw"] = $user;

				//redirecting to view sites
				header("Location: $_CONF[default_location]");
				exit;
			} else
				return $this->templates["login"]->blocks["Login"]->output;

		} else
			return $this->templates["login"]->blocks["Login"]->output;
	}
	if ($_SESSION["minibase"]["raw"]["user_level"] == 0) {
		$_TSM["MENU"] = $this->templates["login"]->blocks["MenuAdmin"]->output;
	} else {
		$_TSM["MENU"] = $this->templates["login"]->blocks["MenuUser"]->output;
	}

	if (!$_POST["task_user"])
		$_POST["task_user"] = $_SESSION["minibase"]["user"];

	if($_SESSION["minibase"]["raw"]["user_level"] == 1) {
		$_CONF["forms"]["adminpath"] = $_CONF["forms"]["userpath"];
	}

	switch ($_GET["sub"]) {
		case "logout":
			unset($_SESSION["minibase"]["user"]);
			header("Location: index.php");

			return $this->templates["login"]->EmptyVars();
		break;

		case "export1":


			$project = $this->db->QFetchArray("SELECT * FROM {$this->tables[projects]} WHERE project_id='$_GET[project_id]'");

			if (!is_array($project)) {
				header("Location: index.php?sub=projects");
				exit;
			} else {
				//read all the tasks
				$_task_cats = $this->db->QFetchRowArray("SELECT * FROM {$this->tables[taskcats]}");
				if (is_array($_task_cats)) {
					foreach ($_task_cats as $key => $val) 
						$task_cats[$_val["cat_id"]] = $val["cat_name"];
				}

				$_task_status = $this->db->QFetchRowArray("SELECT * FROM {$this->tables[taskstatus]}");
				if (is_array($_task_status)) {
					foreach ($_task_status as $key => $val) 
						$task_status[$_val["status_id"]] = $val["status_name"];
				}

				header("Content-Type: text/x-csv");
				header("Content-Disposition: inline; filename=" . urlencode($project["project_name"]) .".csv");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: public");

				echo 'id,Project,"Task Title","Task Description","Start Date","Estimated Completion Date","Completed Date","Task Category","Task Status"' . "\n";

				//now read the tasks
				$tasks = $this->db->QFetchRowArray("SELECT * FROM {$this->tables[tasks]} WHERE task_project='{$_GET[project_id]}'");
				if (is_array($tasks)) {
					$id = 1;
					foreach ($tasks as $key => $val) {
						echo $id . "," .
						(strstr($project["project_name"] , " ") ? '"' . $project["project_name"] . '"' : $project["project_name"]) . "," .
						(strstr($val["task_name"] , " ") ? '"' . $val["task_name"] . '"' : $val["task_name"]) . "," .
						(strstr($val["task_description"] , " ") ? '"' . $val["task_description"] . '"' : $val["task_description"]) . "," .
						"\"" . ($val["task_date"] > 0 ? date("m.d.Y g:i a",$val["task_date"]) : "not available") . "\"," . 
						"\"" . ($val["date_ecompleted"] > 0 ? date("m.d.Y g:i a",$val["date_ecompleted"]) : "not available"). "\"," . 
						"\"" . ($val["date_completed"] > 0 ? date("m.d.Y g:i a",$val["date_completed"]) : "not available" ). "\"," . 
						(strstr($task_cats[$val["task_cat"]] , " ") ? '"' . $task_cats[$val["task_cat"]] . '"' : $task_cats[$val["task_cat"]]) . "," .
						(strstr($task_status[$val["task_status"]] , " ") ? '"' . $task_status[$val["task_status"]] . '"' : $task_status[$val["task_status"]]) . "" . "\n";
		
						$id++;
					}
					
				}

				die;
			}			

		break;

		case "export":
			$data = new CForm($_CONF["forms"]["admintemplate"],$this->db,$this->tables);

			if ($_SERVER["REQUEST_METHOD"] == "GET") {

				$values["values"]["report_id"] = $_GET["expense_report"];
				$values["values"]["status"] = $_GET["status"];
								
			}

			if ($_GET["action"] == "store") {
				if (is_array($values = $data->Validate($_CONF["forms"]["adminpath"] . "export.xml",$_POST))) {

					return $data->Show($_CONF["forms"]["adminpath"] . "export.xml", $values);
				} else {
					//do the nasty things hercopy and redirect to the project details

					$_cats = $this->db->QFetchRowArray("SELECT * FROM {$this->tables[cats]}");
					if (is_array($_cats)) {
						foreach ($_cats as $key => $val) 
							$cats[$val["cat_id"]] = $val["cat_name"];
					}

					$report = $this->db->QFetchArray("SELECT * FROM {$this->tables[reports]} WHERE report_id={$_POST[report_id]}");

					$from = mktime ( 0, 0, 0, $_POST["date_start_month"], $_POST["date_start_day"], $_POST["date_start_year"] );
					$to = mktime ( 23, 59, 59, $_POST["date_end_month"], $_POST["date_end_day"], $_POST["date_end_year"] );

					$condition = "expense_report=$_POST[report_id] and expense_date <= $to and expense_date >= $from " . ($_POST["status"] != 0 ? " and expense_status = $_POST[status]" : "");

					$expenses = $this->db->QFetchRowArray("SELECT * FROM {$this->tables[expenses]} WHERE $condition ");

					header("Content-Type: text/x-csv");
					header("Content-Disposition: inline; filename=" . urlencode($report["report_name"]) .".csv");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Pragma: public");

					echo 'date,Status,Category,Amount' . "\n";


					if (is_array($expenses)) {
						foreach ($expenses as $key => $val) {
							$val["expense_cat"] = $cats[$val["expense_cat"]];
							switch ($val["expense_status"]) {
								case "1":$val["status"] = "approved" ;break;
								case "2":$val["status"] = "\"not approved\"" ;break;
								case "3":$val["status"] = "pending" ;break;
								case "4":$val["status"] = "other" ;break;
							}
							

							echo
								"\"" . ($val["expense_date"] > 0 ? date("m.d.Y g:i a",$val["expense_date"]) : "not available") . "\"," . 
								$val["status"] . "," . 
								(strstr($val["expense_cat"] , " ") ? '"' . $val["expense_cat"] . '"' : $val["expense_cat"]) . "," .
								'$' . number_format($val["expense_amount"], 2) . "\n";

						}						

					}					

					die;
				}
			} else 
				return $data->Show($_CONF["forms"]["adminpath"] . "export.xml", $values);
		break;

		case "reports":
		case "cats":
		case "comments":
		case "expenses":

			if (($_GET["sub"] == "expenses") && ($_GET["action"] == "store") && $_POST["expense_id"]){
				
				//read the ex expense
				$old = $this->db->QFetchArray("SELECT * FROM {$this->tables[expenses]} WHERE expense_id='{$_POST[expense_id]}';");
				//do the check
				if ($old["expense_status"] != $_POST["expense_status"]) {
					//ok, send the mail to the guy

						//read the user #data
						$report = $this->db->QFetchArray("SELECT * FROM {$this->tables[reports]} WHERE report_id={$old[expense_report]}");
						$user = $this->db->QFetchArray("SELECT * FROM {$this->tables[users]} WHERE user_id={$report[report_user]}");

						switch ($_POST["expense_status"]) {
							case "1":$val["status"] = "approved" ;break;
							case "2":$val["status"] = "not approved" ;break;
							case "3":$val["status"] = "pending" ;break;
							case "4":$val["status"] = "other" ;break;
						}

						switch ($old["expense_status"]) {
							case "1":$val2["status"] = "approved" ;break;
							case "2":$val2["status"] = "not approved" ;break;
							case "3":$val2["status"] = "pending" ;break;
							case "4":$val2["status"] = "other" ;break;
						}

						$mail = new CTemplate($this->vars->data["status_mail"] , "string");
						$mail = $mail->Replace(array(
										"USER_NAME" => $user["user_name"],
										"USER_EMAIL" => $user["user_email"],
										"REPORT_NAME" => $report["report_name"],
										"EXPENSE_LOCATION" => $old["expense_location"],								
										"EXPENSE_DATE" => date("m.d.Y",$old["expense_date"]),
										"EXPENSE_AMOUNT" => '$' . number_format($old["expense_amount"] , 2),
										"EXPENSE_NEW_STATUS" => $val["status"],
										"EXPENSE_OLD_STATUS" => $val2["status"]
										
										
									));

						$headers  = "MIME-Version: 1.0\n"; 
						$headers .= "Content-type: text/plain\n"; 
						$headers .= "From: {$this->vars->data[status_address]}\n"; 
						//mailing the email
						
						@mail( $user["user_email"] , $this->vars->data["status_title"], $mail, $headers);
	
				}
			}
			
			if ($_SESSION["minibase"]["raw"]["user_level"] != 0) {
				$_POST["report_user"] = $_SESSION["minibase"]["raw"]["user_id"];
			}

			if ($_SERVER["REQUEST_METHOD"] != "POST") {
				$_POST["comment_user"] = $_SESSION["minibase"]["raw"]["user_id"];
				$_POST["comment_date"] = time();
			}

			if (($_GET["sub"] == "reports") && ($_GET["action"] == "details")) {
				switch ($_GET["status"]) {
					case "1":
						$tmp = $this->db->QFetchRowArray("SELECT sum(expense_amount) FROM {$this->tables[expenses]} WHERE expense_status={$_GET[status]} AND expense_report={$_GET[report_id]}");
						$total = $tmp[0]["sum(expense_amount)"];

						$task = new CSQLAdmin("expenses.aproved", $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);
					break;

					case "2":
						$tmp = $this->db->QFetchRowArray("SELECT sum(expense_amount) FROM {$this->tables[expenses]} WHERE expense_status={$_GET[status]} AND expense_report={$_GET[report_id]}");
						$total = $tmp[0]["sum(expense_amount)"];

						$task = new CSQLAdmin("expenses.notaproved", $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);
					break;

					case "3":
						$tmp = $this->db->QFetchRowArray("SELECT sum(expense_amount) FROM {$this->tables[expenses]} WHERE expense_status={$_GET[status]} AND expense_report={$_GET[report_id]}");
						$total = $tmp[0]["sum(expense_amount)"];

						$task = new CSQLAdmin("expenses.pending", $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);
					break;

					case "4":
						$tmp = $this->db->QFetchRowArray("SELECT sum(expense_amount) FROM {$this->tables[expenses]} WHERE expense_status={$_GET[status]} AND expense_report={$_GET[report_id]}");
						$total = $tmp[0]["sum(expense_amount)"];

						$task = new CSQLAdmin("expenses.other", $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);
					break;

					default:
						$tmp = $this->db->QFetchRowArray("SELECT sum(expense_amount) FROM {$this->tables[expenses]} WHERE expense_report={$_GET[report_id]}");
						$total = $tmp[0]["sum(expense_amount)"];

						$task = new CSQLAdmin("expenses", $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);
					break;
				}

				

				if ($_SESSION["minibase"]["raw"]["user_level"] == 0) {
					$extra["details"]["fields"]["report_user"] .= $task->DoEvents();

					$comments = new CSQLAdmin("comments", $_CONF["forms"]["admintemplate"],$this->db,$this->tables);
					$extra["details"]["after"] .= $comments->DoEvents();
				} else
					$extra["details"]["fields"]["report_description"] .= $task->DoEvents();
 
			
			}

			if (($_GET["sub"] == "tasks") && ($_GET["action"] == "det")) {
				$comments = new CSQLAdmin("comments", $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);
				$extra["details"]["after"] = $comments->DoEvents();				
			}

			$data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);

			if (($_GET["sub"] == "reports") && ($_GET["action"] == "details")) {
				$data->forms["forms"]["details"]["fields"]["report_total"]= array(
															
															"type" => "text",
															"title" => "Expenses Amount",
															"action" => "price",
															"preffix" => "$",
															"forcevalue" => $total

															);
			}
			return $data->DoEvents("","",$_POST);
		break;

		case "users":
			
			if ((!$_GET["action"])&&($_SESSION["minibase"]["raw"]["user_level"] != 0 )) {
				$_GET["action"] = "details";				
			}

			if ($_SESSION["minibase"]["raw"]["user_level"] == 1) {
				$_GET["user_id"] = $_SESSION["minibase"]["raw"]["user_id"]; 
				$_POST["user_id"] = $_SESSION["minibase"]["raw"]["user_id"];
			}
			
			$data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$this->db,$this->tables);
			return $data->DoEvents();
		break;

		case "settings":

			$file = $_GET["sub"] . ".xml";

			$data = new CForm($_CONF["forms"]["admintemplate"],$this->db,$this->tables);

			if ($_GET["action"] == "store") {
				if (is_array($values = $data->Validate($_CONF["forms"]["adminpath"] . $file,$_POST))) {

					return $data->Show($_CONF["forms"]["adminpath"] . $file, $values);
				} else {

					$this->vars->SetAll($_POST);
					$this->vars->Save();

					header("location: index.php?mod=eshop&sub=" . $_GET["sub"]);
					exit;
				}
				
			}
					
			return $data->Show($_CONF["forms"]["adminpath"] . $file , array("values"=>$this->vars->data));

		break;


	}
}

?>