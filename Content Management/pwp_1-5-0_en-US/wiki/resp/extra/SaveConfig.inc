<?php
/**
 * This response saves variables and InterWiki links,
 * i.e. re-writes a part of the configuration file.
 * This feature may be disabled by the configuration.
 * Tries to filter out any PHP code, just passive text strings
 * must find their way into the config file. Otherwise people might
 * submit any kind of 'unfriendly' PHP code.
 * Assumes or requires a special code layout of the affected sections within
 * the config file.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */
class SaveConfig extends Response {

	function SaveConfig( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function verify( &$args ) {
		global $gError, $gConfig;

		if (!$gConfig->mProp['AllowEditConfig']) {
			$gError->add("The current configuration doesn't allow to edit InterWiki links or variable values.");
		}

		if (empty($_REQUEST['iSection'])) {
			$gError->add("Missing section parameter.");
		}

		if (empty($_REQUEST['iKey'])) {
			$gError->add("Missing keys.");
			return false;
		}

		if (empty($_REQUEST['iValue'])) {
			$gError->add("Missing values.");
			return false;
		}

		if (sizeof($_REQUEST['iValue']) != sizeof($_REQUEST['iKey'])) {
			$gError->add("Cannot assing a value to each key.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		//Cannot forward to extra/EditConfig: Config file has changed but is
		//already included in this request!
		$this->changeState('extra/Extra');

		$function = '';
		$member   = '';
		if ($_REQUEST['iSection'] == 'interwikilinks') {
			$function = '_setInterWikiLinks';
			$member   = 'mIWiki';
		} else if ($_REQUEST['iSection'] == 'variables') {
			$function = '_setVariables';
			$member   = 'mVars';
		} else {
			$gError->add("Invalid section parameter.");
			return false;
		}

		$file = fopen(BASE_PATH.'conf/Config.inc', 'r');
		if ($file) {
			$text = fread($file, filesize(BASE_PATH.'conf/Config.inc'));
			fclose( $file);

			//make a backup
			$file = fopen(BASE_PATH.'conf/Config.bakup.inc', 'w+');
			fwrite( $file, $text );
			fclose( $file);

			//remove old contents
			$text = preg_replace("/^\\s*\\\$this->$member.*$\\n/mi", '', $text);

			//write new contents
			$newConfig = '';
			$counter = sizeof($_REQUEST['iKey']);
			for ($i=0; $i<$counter; $i++) {
				if (!empty($_REQUEST['iKey'][$i])) {
					//slashes! '''is Wiki Code'''
					$_REQUEST['iValue'][$i] = stripslashes( $_REQUEST['iValue'][$i] );
					$_REQUEST['iValue'][$i] = str_replace( '"', "'", $_REQUEST['iValue'][$i]); //make " -> '
					$_REQUEST['iValue'][$i] = str_replace( '$', "\\\\$", $_REQUEST['iValue'][$i]); //no variables but allow SSI
					$_REQUEST['iValue'][$i] = str_replace( "\r", '', $_REQUEST['iValue'][$i]);
					$_REQUEST['iValue'][$i] = str_replace( "\n", "\\n", $_REQUEST['iValue'][$i]); //multi line values
					$_REQUEST['iKey'][$i]   = stripslashes( $_REQUEST['iKey'][$i] );
					if ($_REQUEST['iKey'][$i] != '.') { //exception for list links into the local file system
						$_REQUEST['iKey'][$i]   = preg_replace( '/[\W]*/', '', $_REQUEST['iKey'][$i]); //no special chars except single /
					}
					$newConfig .= "\n\t\t\$this->$member"."['{$_REQUEST['iKey'][$i]}'] = \"{$_REQUEST['iValue'][$i]}\";";
				}
			}

			$text = preg_replace("/^(\\s*function\\s*$function\\s*\\(\\)\\s*\\{)(.*)$/mi", "\\1$newConfig\\2", $text);
			$file = fopen(BASE_PATH.'conf/Config.inc', 'w');
			fwrite( $file, $text );
			fclose( $file );
		} else {
			$gError->add("Could not open config file.");
		}
		$args['extraStatus'] = '<p>OK.  Configuration saved.</p>';
	}
}

?>