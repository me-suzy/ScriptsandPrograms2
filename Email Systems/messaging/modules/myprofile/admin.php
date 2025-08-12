<?php
class CMyProfile {
	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function CMyProfile() {
	}
	

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function DoEvents() {
		global $_CONF , $_TSM , $_USER;

		$_TSM["PM.INBOX.MESSAGES"] = $this->db->RowCount($this->private->tables["mb_msg"] , "WHERE msg_type=1 AND msg_user='". $_SESSION["minibase"]["raw"]["user_id"] . "' AND msg_delete=0 AND msg_new=1");
		$_TSM["PM.INBOX.HIGHLIGHT"] = $_TSM["PM.INBOX.MESSAGES"] ? "<b>" : "";

		if ($_GET["mod"] != "myprofile") 
			return false;


		switch ($_GET["sub"]) {
			case "logout":
				unset($_SESSION["minibase"]["user"]);
				header("Location: index.php");

				return $this->templates["login"]->EmptyVars();
			break;

			case "users":
			case "workers":
			case "myprofile.notes":
				if ($_USER["user_level"] == 1) {
					header("location: index.php?mod=auth&sub=auth.deny");
					exit;
				}				

				if (is_subaction("workers","userdetails") || is_subaction("users","userdetails")) {
					switch ($_GET["section"]) {
						case 1:
							$data = new CSQLAdmin("myprofile.notes", $_CONF["forms"]["admintemplate"],$this->db,$this->private->tables);					
							$extra["details"]["after"] = $data->DoEvents();
						break;
						
						case 3:
							$data = new CSQLAdmin("myprofile.user.inbox", $_CONF["forms"]["admintemplate"],$this->db,$this->private->tables);					
							$extra["details"]["after"] = $data->DoEvents();
						break;

						case 2:
							$data = new CSQLAdmin("myprofile.user.sent", $_CONF["forms"]["admintemplate"],$this->db,$this->private->tables);					
							$extra["details"]["after"] = $data->DoEvents();
						break;
							
					}					
				}

				$data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$this->db,$this->private->tables,$extra);
				if ((is_subaction("workers","userdetails") || is_subaction("users","userdetails")) && ($_GET["section"])) {
					$found = false;
					foreach ($data->forms["forms"]["details"]["fields"] as $key => $val) {
						if ($key == "subtitle")
							$found = true;

						//remove details fields for a better view
						if ($found)
							unset($data->forms["forms"]["details"]["fields"][$key]);
					}
					
				}				
				return $data->DoEvents();
			break;

			case "myprofile.sent":
			case "myprofile.trash":
			case "myprofile.inbox":
			case "myprofile.account":

				//check to be sure this user can see only his messages
				if (($_GET["action"] == "details") && ($_GET["msg_id"]) && !$this->db->RowCount($this->private->tables["mb_msg"],"WHERE msg_user='{$_USER[user_id]}' AND msg_id='{$_GET[msg_id]}'")) {
					header("location: index.php?mod=auth&sub=auth.deny");
					exit;
				}

				if ($_GET["sub"] == "myprofile.account") {
					$_GET["user_id"]  = $_SESSION["minibase"]["raw"]["user_id"];

					if (!in_array($_GET["action"] , array("details","edit","store")))
						$_GET["action"] = "details";
				}
				
				$data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$this->db,$this->private->tables,$extra);
				return $data->DoEvents();
			break;

			case "myprofile.undelete":
			case "myprofile.delete":
				if ($_GET["empty"]== "true") {
					$id = $_SESSION["minibase"]["raw"]["user_id"];
					$msg = $this->db->Query("DELETE FROM {$this->private->tables[mb_msg]} WHERE msg_user='{$id}' AND msg_delete=1");
					header("Location: " . ($_GET["returnURL"] ? urldecode($_GET["returnURL"]) : "index.php?sub=myprofile.trash"));
					exit;

				}
				
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (is_array($_POST["msg_id"])) {
						foreach ($_POST["msg_id"] as $key => $val) {
							$msg = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[mb_msg]} WHERE msg_id='{$val}'");
							if (is_array($msg)) {
								$msg["msg_delete"] = $_GET["sub"] == "myprofile.delete" ? 1 : 0;
								$this->db->QueryUpdate($this->private->tables["mb_msg"] , $msg , "msg_id={$msg[msg_id]}");
							}
						}						
					}

					header("Location: " . urldecode($_GET["returnurl"]));
					exit;
					
				} else {
				
					$msg = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[mb_msg]} WHERE msg_id='{$_GET[msg_id]}'");
					if (is_array($msg)) {
						$msg["msg_delete"] = $_GET["sub"] == "myprofile.delete" ? 1 : 0;
						$this->db->QueryUpdate($this->private->tables["mb_msg"] , $msg , "msg_id={$msg[msg_id]}");
					}

					header("Location: " . urldecode($_GET["returnURL"]));
					exit;

				}

				
			break;

			case "myprofile.compose":

				$file = $_GET["sub"] . ".xml";

				if ($_GET["user_id"] || $_POST["user_id"]) {
					$_user = $_GET["user_id"] ? $_GET["user_id"] : $_POST["user_id"];
					$user = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[users]} WHERE user_id='{$_user}'");
				}

				if ($_GET["msg_id"]) {

					//usefull for forward messages

	
					//mark the message as readed.
					$msg = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[mb_msg]} WHERE msg_id='{$_GET[msg_id]}'");
					$msg["msg_new"] = 0;
					$this->db->QueryUpdate($this->private->tables["mb_msg"] , $msg , "msg_id={$msg[msg_id]}");

					$body = explode("\n" , $msg["msg_body"]);
					if (is_array($body)) {
	
						if (!is_array($user)) {
							$_POST["subject"] = "Fw: " .$msg["msg_title"];

							$user = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[users]} WHERE user_id='{$msg[msg_from]}'");
							$_POST["mail"] .= "\n\n" . $user["user_name"] . " " . $user["user_surname"] . " wrote: \n";
							unset($user);

						} else  {
							$_POST["mail"] .= "\n\n" . $user["user_name"] . " " . $user["user_surname"] . " wrote: \n";
							$_POST["subject"] = "Re: " .$msg["msg_title"];
						}


						foreach ($body as $key => $val) {
							$_POST["mail"] .= "> " . $val;
						}						
					} else
						$_POST["subject"] = "Re: " .$msg["msg_title"];

				}
					
				if (is_array($user)) {
					$file = $_GET["sub"] . ".xml";
					$_POST["to"] = $_user;
					$_POST["to_name"] = $user["user_name"] . " " . $user["user_surname"] . " (<i>" . $user["user_login"] . "</i>)" ;
				}

				$data = new CForm($_CONF["forms"]["admintemplate"],$this->db,$this->private->tables);

				if ($_GET["action"] == "store") {
					if (is_array($values = $data->Validate($_CONF["forms"]["adminpath"] . $file,$_POST))) {

						return $data->Show($_CONF["forms"]["adminpath"] . $file, $values) . $CHAT_DATA;
					} else {

						$user = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[users]} WHERE user_id='{$_POST[to]}'");

						if ($_GET["user_id"] || $_POST["user_id"])
							$_POST["to"] = $_POST["user_id"];

						$data = array();

						//save the message
						$data["msg_date"] = time();
						$data["msg_type"] = "1";
						$data["msg_to"] = $_POST["to"];
						$data["msg_from"] = $_SESSION["minibase"]["raw"]["user_id"];
						$data["msg_title"] = $_POST["subject"];
						$data["msg_new"] = "1";
						$data["msg_user"] = $_POST["to"];
						$data["msg_body"] = $_POST["mail"];

						$id = $this->db->QueryInsert($this->private->tables["mb_msg"] , $data);
						

						//eventualy make an option here to mail the message, but in a future version

						if ($_POST["save"] == 0) {
							$data["msg_date"] = time();
							$data["msg_type"] = "2";
							$data["msg_to"] = $_POST["to"];
							$data["msg_from"] = $_SESSION["minibase"]["raw"]["user_id"];
							$data["msg_title"] = $_POST["subject"];
							$data["msg_new"] = "0";
							$data["msg_user"] = $_SESSION["minibase"]["raw"]["user_id"];
							$data["msg_body"] = $_POST["mail"];

							//$id = $this->db->QueryInsert($this->private->tables["mb_msg"] , $data);
						}

						//read the curent user data
						$cuser = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[users]} WHERE user_id='{$_USER[user_id]}'");

						if ($cuser["user_msg_send"] == 1) {
							$msg = $this->private->templates["mail"]->blocks["Mail"]->Replace(array(
										"LINK" => ($_SERVER["HTTPS"] ? "https://" : "http://" ) . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != 80 ? ":" . $_SERVER["SERVER_PORT"] : "" ) . $_SERVER["PHP_SELF"],
										"LINK2" => urlencode(($_SERVER["HTTPS"] ? "https://" : "http://" ) . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != 80 ? ":" . $_SERVER["SERVER_PORT"] : "" ) . $_SERVER["PHP_SELF"] . "?mod=myprofile&sub=myprofile.inbox&action=details&msg_id={$id}" ),
										"ID" => $id,
										"USER_NAME" => $_USER["user_name"],
										"USER_NAME2" => $user["user_name"],
										"USER_LOGIN2" => $user["user_login"]

									));

							$user = $this->db->QFetchArray("SELECT * FROM {$this->private->tables[users]} WHERE user_id='{$_POST[to]}'");

							$headers  = "MIME-Version: 1.0\n"; 
							$headers .= "Content-type: text/plain\n"; 
							$headers .= "From: Automailer <automailer@no.reply>\n"; 
							//mailing the email

							@mail( $user["user_name"] . "<" . $user["user_email"] . ">" , $user["user_msg_subject"] ? $user["user_msg_subject"] : "New Message" , $msg, $headers);
						}
						

						header ("Location: index.php?mod=myprofile&sub=myprofile.thanks&return=" . ($_POST["returnurl"] ? $_POST["returnurl"] : "index.php?sub=myprofile.compose"));
						exit;
					}				
				}
						
				return $data->Show($_CONF["forms"]["adminpath"] . $file , array("values"=>$_POST)) . $CHAT_DATA;

			break;

			case "myprofile.thanks":					

				$file = $_GET["sub"] . ".xml";

				$data = new CForm($_CONF["forms"]["admintemplate"],$this->db,$this->private->tables);
				return $data->Show($_CONF["forms"]["adminpath"] . $file , array("values"=>$this->vars->data));
			break;
		}

	}
	
}	
?>