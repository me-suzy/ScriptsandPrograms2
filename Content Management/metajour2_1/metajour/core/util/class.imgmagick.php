<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage util
 */

class imgmagick {

	function imgmagick($image, $outfile, $cmds) {
		$convert_path = 'c:/programmer/imagemagick/convert';

		if (!file_exists($image)) {
			die('ERROR');
		}
		preg_match_all('/\+*(([a-z]+)(\(([^\)]*)\))?)\+*/',
               	$cmds,
               	$matches, PREG_SET_ORDER);

		// convert query string to an imagemagick command string
		$commands = '';
		foreach ($matches as $match) {
			// $match[2] is the command name
			// $match[4] the parameter
	
			// check input
			if (!preg_match('/^[a-z]+$/',$match[2])) {
				die('ERROR: Invalid command.');
			}
			if (!preg_match('/^[a-z0-9\/{}+-<>!@%]+$/',$match[4])) {
				die('ERROR: Invalid parameter.');
			}
		
			// replace } with >, { with <
			// > and < could give problems when using html
			$match[4] = str_replace('}','>',$match[4]);
			$match[4] = str_replace('{','<',$match[4]);
		
			// check for special, scripted commands
			switch ($match[2]) {
		
				case 'type':
					// convert the image to this file type
					if (!preg_match('/^[a-z]+$/',$match[4])) {
						die('ERROR: Invalid parameter.');
					}
					$new_type = $match[4];
					break;
		
				default:
					// nothing special, just add the command
					if ($match[4]=='') {
						// no parameter given, eg: flip
						$commands .= ' -'.$match[2].'';
					} else {
						$commands .= ' -'.$match[2].' "'.$match[4].'"';
					}
			}
		}

		// create the convert-command
		$convert = $convert_path.' '.$commands.' "'.$image.'" ';
		if (file_exists($outfile)) @unlink($outfile);
		if (isset($new_type)) {
			// send file type-command to imagemagick
			$convert .= $new_type.':';
		}
		$convert .= '"'.$outfile.'"';
		echo ($convert);
		exec($convert);
	}
}
?>
