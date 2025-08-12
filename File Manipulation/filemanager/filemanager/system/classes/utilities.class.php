<?php

class Utilities {
	
	/*
	   +----------------------------------------------------------------
	   | forward to site												
	   +----------------------------------------------------------------
	*/
	public static function redirect($url) {
		header("Location: $url");
		exit();
	}
	
	/*
	   +----------------------------------------------------------------
	   | return the module												
	   +----------------------------------------------------------------
	*/
	public static function getModule($action) {
		
		if($action != "") {
			
			$action_exp = explode(".", $action);
			
			if(count($action_exp) == 2) {
				$module = $action_exp[0];
			}
			else {
				$module = "";
			}
		}
		else {
			$module = "main";
		}
		
		return $module;
	}
	
	/*
	   +----------------------------------------------------------------
	   | return the method												
	   +----------------------------------------------------------------
	*/
	public static function getMethod($action) {
		
		if($action != "") {
			
			$action_exp = explode(".", $action);
			
			if(count($action_exp) == 2) {
				$method = $action_exp[1];
			}
			else {
				$method = "";
			}
		}
		else {
			$method = "login";
		}
		
		return $method;
	}
	
	/*
	   +----------------------------------------------------------------
	   | return the number of entries									
	   +----------------------------------------------------------------
	*/
	public static function getNumberOfEntries($sql) {
        
        $resultCount        = mysql_query($sql, Config::getDbLink());
        $numberOfEntries    = mysql_num_rows($resultCount);
		
		return $numberOfEntries;
	}
	
	/*
	   +----------------------------------------------------------------
	   | return an euro date											
	   +----------------------------------------------------------------
	*/
	public static function getEuroDate($date_us) {
		
		if($date_us != "" && $date_us != "0000-00-00 00-00-00") {
			
			$date_exp = explode(" ", $date_us);
			$date_par = explode("-", $date_exp[0]);
			
			$date_yea = $date_par[0];
			$date_mon = $date_par[1];
			$date_day = $date_par[2];
			
			$date_new = "$date_day.$date_mon.$date_yea";
		}
		else {
			$date_new = "&ndash;";
		}
		
		return $date_new;
	}
	
	/*
	   +----------------------------------------------------------------
	   | check login													
	   +----------------------------------------------------------------
	*/
	public static function checkLogin() {
		
		if(isset($_SESSION['s_role'])) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | check admin													
	   +----------------------------------------------------------------
	*/
	public static function checkAdmin() {
		
		if(isset($_SESSION['s_role']) && $_SESSION['s_role'] == "admin") {
			return true;
		}
		else {
			return false;
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | return filesize in kb											
	   +----------------------------------------------------------------
	*/
	public static function getFileSize($file_size) {
		
		$file_size_kb = round(($file_size/1000),0);
		
		return $file_size_kb;
	}
	
	/*
	   +----------------------------------------------------------------
	   | calculate execution time										
	   +----------------------------------------------------------------
	*/
	public static function getMicroTime() {
    	list($usec, $sec) = explode(" ", microtime());
    	return ((float)$usec + (float)$sec);
	}
	
	/*
	   +----------------------------------------------------------------
	   | calculate execution time										
	   +----------------------------------------------------------------
	*/
	public static function uploadFile($filename,$filename_name) {
		
		ini_set('post_max_size','20M');
		ini_set('max_execution_time', '1040');
		
		$file_name_exp	= explode(".", $filename_name);
		$file_name_cnt	= count($file_name_exp)-1;
		$file_name_ext	= $file_name_exp[$file_name_cnt];
		$file_name		= uniqid().".$file_name_ext";
		
    	$conn_id = ftp_connect(Application::getFtpHost());
		ftp_login($conn_id, Application::getFtpUsername(), Application::getFtpPassword());
		ftp_chdir($conn_id, Application::getFtpDataPath());
		ftp_put($conn_id, "$file_name", "$filename", FTP_BINARY);
		ftp_quit($conn_id);
		
		return $file_name;
	}
	
	/*
	   +----------------------------------------------------------------
	   | count category files											
	   +----------------------------------------------------------------
	*/
	public static function countFiles($category_id) {
    	
		$sql = "SELECT * FROM `relation_file2category` WHERE `category_id` = '$category_id'";
		$result = mysql_query($sql, Config::getDbLink());
		$num = mysql_affected_rows();
		
		return $num;
	}
	
	/*
	   +----------------------------------------------------------------
	   | check category access											
	   +----------------------------------------------------------------
	*/
	public static function checkAccess($category_id) {
    	
		$sql = "
		SELECT
		  `user_category`.`category_id`
		FROM
		  `relation_user2group`
		INNER JOIN `relation_group2category` ON (`relation_user2group`.`group_id` = `relation_group2category`.`group_id`)
		INNER JOIN `user_category` ON (`relation_group2category`.`category_id` = `user_category`.`category_id`)
		WHERE
		  `relation_user2group`.`user_id` = '$_SESSION[s_userid]' AND
		  `user_category`.`category_id` = '$category_id'
		";
		$result = mysql_query($sql, Config::getDbLink());
		if($data = mysql_fetch_array($result)) {
			
			return true;
		}
		else {
			
			return false;
		}
	}
}

?>