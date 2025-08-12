<?php
/**
 * This response loads a part of the config file into a
 * user editable web form. Assumes a special code layout in the
 * config file.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */


class EditConfig extends Response {

	function EditConfig( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function verify( &$args ) {
		global $gError, $gConfig;

		if (!$gConfig->mProp['AllowEditConfig']) {
			$gError->add("The current configuration doesn't allow to edit InterWiki links or variable values.");
		}

		if (empty($_REQUEST['iSection'])) {
			$gError->add("Missing  section parameter.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$this->changeState('out/Html');

		$arrEntries = array();
		if ($_REQUEST['iSection'] == 'interwikilinks') {
			$arrEntries = $gConfig->mIWiki;
			$args['Heading'] = "Configuration: InterWiki Links";
		} else if ($_REQUEST['iSection'] == 'variables') {
			$arrEntries = $gConfig->mVars;
			$args['Heading'] = "Configuration: Variables";
		} else {
			$gError->add("Invalid section parameter.");
			return false;
		}

		echo <<<eoh
$buffer
<p>
Select a section:
<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/EditConfig&amp;iSection=interwikilinks'>[Edit Inter Wiki Links]</a>
<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/EditConfig&amp;iSection=variables'>[Edit Variables]</a>
</p>
<form action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='extra/SaveConfig'>
<input type='hidden' name='iSection' value='{$_REQUEST['iSection']}'>
<small>
Names must not contain special chars. Only letters, numbers and the underscore '_' are allowed.
Values must not contain double quotes.
</small>
<table border='0' cellspacing='2' cellpadding='2' width='100%' class='layout'>
<tr>
	<th>Name</th><th>Value</th>
</tr>
eoh;

	$rows = $this->_getRows( sizeof( $arrEntries ) );
	$cols = (int)($gConfig->mProp['EditColumns'] * 0.62);
	$tsize= (int)($gConfig->mProp['EditColumns'] * 0.25);
	$currentRowSize = $rows;
	ksort( $arrEntries );
	foreach ($arrEntries as $key => $value) {

		//short values will always result in a row size of 2
		$currentRowSize = (strlen($value) < 2 * $cols)?(2):($rows);
		$value = str_replace( array('&', '>', '<', '"'), array('&amp;', '&gt;', '&lt;', '&quot;'), $value);

		echo <<<eoh
<tr>
	<td class='layout'>
		<input type='text' name='iKey[]' value='$key' size='$tsize' maxlength='128'>
	</td><td class='layout'>
		<textarea name='iValue[]' rows='$currentRowSize' cols='$cols' wrap='virtual'>$value</textarea>
	</td>
</tr>
eoh;

	}

echo <<<eoh
<tr>
	<td class='layout'>
		<input type='text' name='iKey[]' value='' size='$tsize' maxlength='128'>
	</td><td class='layout'>
		<textarea name='iValue[]' rows='$rows' cols='$cols' wrap='virtual'></textarea>
	</td>
</tr>
<tr>
	<td class='layout'>
		<input type='text' name='iKey[]' value='' size='$tsize' maxlength='128'>
	</td><td class='layout'>
		<textarea name='iValue[]' rows='$rows' cols='$cols' wrap='virtual'></textarea>
	</td>
</tr>
<tr>
	<td class='layout'>
		<input type='submit' value=' OK '>
		<input type='reset' value='Cancel'>
		<input type='button' value='Go Back' onclick='history.back()'>
	</td><td class='layout'>
		<p>
			<small>
			Need more new fields? Just repeat this action!
			<br>
			Delete an entry by removing its name.
			</small>
		</p>
	</td>
</tr>
</table>
</form>
eoh;
	}

	/** Helper which calculates the rows of text areas depending on array sizes. */
	function _getRows( $arrSize ) {
		if ($arrSize < 4) {
			return 8;
		} else if ($arrSize < 8) {
			return 6;
		} else if ($arrSize < 16) {
			return 4;
		} else {
			return 3;
		}
	}

}

?>