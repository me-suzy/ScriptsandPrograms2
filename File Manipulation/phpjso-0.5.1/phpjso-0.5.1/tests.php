<?php
/**
 * phpJSO unit tests. Run like so on the command line:
 *
 * phpunit Tests
 *
 * phpunit2 must be installed. Use the PEAR installer.
 * http://www.phpunit.de/en/phpunit2_install.php
 *
 * @started: Wednesday, September 28, 2005
 * @copyright: Copyright (c) 2004, 2005 JPortal, All Rights Reserved
 * @website: www.jportalhome.com
 * @license: Free, zlib/libpng license - see LICENSE
 * @subversion: $Id: tests.php 27 2005-10-04 15:16:45Z josh $
 */

require_once("PHPUnit2/Framework/TestCase.php");
define('UNIT_TEST', true);
require("phpjso.php");

class Tests extends PHPUnit2_Framework_TestCase
{
	/**
	 * Test string and multi-line comment removal.
	 */
	public function test_string_comment_removal ()
	{
		// This code is full of multi-line comments and strings that should be parsed out
		$start_code = "/** test\ntest\ntest\n. Hopefully phpJSO won\'t get confused\"! */\n"
			. "// -- Create global items container stylesheet object ' \" --\n"
			. "/* ' * \" ''  */"
			. "document.write('\\'/*<style>'+/**/\n"
			. "'div.WebDDM_items_container {*/'+\n"
			. "'position: /*absolute; top: 0px;'+\n"
			. "'*/}' + '</style>/*\"*/');\n\n"
			. "'\*/string/*'.match(/\/*/).match(/\/*\//).match(/.*/);"
			. "/**\n"
			. " * Specially escapes a string so that it can be put inside of another string to\n"
			. " * be evaluated. The quoteType should be either ' or \".\n"
			. " * NOTE: This function is not used in this source file but it is used in some\n"
			. " * plugins and is still very useful'"
			. " */// test";

		$expected_result =  "\n\ndocument.write(`0`+\n"
			. "`1`+\n"
			. "`2`+\n"
			. "`3` + `4`);\n\n"
			. "`5`.match(/\/*/).match(/\/*\//).match(/.*/);";
		
		// Get real result and verify it
		$str_array = array();
		$real_result = $start_code;
		phpJSO_strip_strings_and_comments($real_result, $str_array, substr(md5(time()), 10, 2));
		if ($expected_result != $real_result)
		{
			print_r($str_array);
			print $real_result . "\n-----\n" . $expected_result;
		}
		self::assertTrue($expected_result == $real_result);

		// Check that the correct strings are in the array
		self::assertTrue(count($str_array) == 6);
		self::assertTrue($str_array[0] == "'\'/*<style>'");
		self::assertTrue($str_array[1] == "'div.WebDDM_items_container {*/'");
		self::assertTrue($str_array[2] == "'position: /*absolute; top: 0px;'");
		self::assertTrue($str_array[3] == "'*/}'");
		self::assertTrue($str_array[4] == "'</style>/*\"*/'");
		self::assertTrue($str_array[5] == "'\*/string/*'");
	}
	
	/**
	 * Test whitespace and single-line comment removal
	 * Bugs to look out for: under the old phpJSO code, pre-0.5,
	 * not all whitespace would be removed. Specifically, whitespace
	 * between two closing brackets, preceded by a non-word character.
	 * IE:
	 * if (1) {
	 *    if (2) {
	 *      alert(3);
	 *    }
	 * }
	 *
	 * Would become: if(1){if(2){alert(3)}
	 * }
	 *
	 * Extra regex has been addded. In the phpJSO_strip_junk function,
	 * look at the six similar regexes; one of them excludes whitespace
	 * from the search (\s) and the other includes it. This is the fix
	 * as of right now.
	 */
	public function test_junk_removal_removal ()
	{
		// Code, full of random whitespace that should be removed
		$start_code =  "var domLib_userAgent = navigator.userAgent.toLowerCase();"
			. "\t\n\r\t  var domLib_isMac\t=\tnavigator.appVersion.indexOf('Mac') != -1; \n 	\n\n 	   "
			. "if (domLib_isMac) {\n"
			. "\t\talert(1)\n\t\t\t"
			. "if (1){alert(1.1)\r\n}\r"
			. "\t}\n"
			. "else\n{\t\talert(2); \tvar\ttest; \r\n\r\n}\t\n";
			
		// Should look like this after parsing
		$expected_result = "var domLib_userAgent=navigator.userAgent.toLowerCase();var domLib_isMac=navigator.appVersion.indexOf('Mac')!=-1;if(domLib_isMac){alert(1)if(1){alert(1.1)}}else{alert(2);var\ttest}";

		// Get the real result
		$real_result = $start_code;
		phpJSO_strip_junk($real_result);

		// Check for errors
		if ($real_result != $expected_result)
		{
			print $real_result . "\n----\n" . $expected_result;
		}
		self::assertTrue($expected_result == $real_result);	
	}

	/**
	 * Test getting tokens from code.
	 */
	public function test_get_tokens ()
	{
		// Code with all the tokens
		$code = 'container.innerHTML = \'<div style="width: 5; visibility: visible;" id="WebDDM_loading_\' + container.id + \'">A WebDDM menu is loading; please wait!</div>\';'
			. 'var test = 5 + 7 + 7; alert(container.innerHTML); container.innerHTML = id;';

		// Expected results
		$expected_tokens = array
		(
			0 => 'container',
			1 => 'innerHTML',
			2 => 'div',
			3 => 'id'
		);
		$expected_numeric_tokens = array
		(
			0 => 5,
			1 => 7
		);

		// Get actual results
		$real_tokens = array();
		$real_numeric_tokens = array();
		phpJSO_get_tokens($code, $real_numeric_tokens, $real_tokens);
		
		// Verify results
		foreach (array('tokens', 'numeric_tokens') as $token_type)
		{
			self::assertTrue(count(${"expected_$token_type"}) == count(${"real_$token_type"}));

			foreach (${"expected_$token_type"} as $k=>$v)
			{
				self::assertTrue($v == ${"real_$token_type"}[$k]);
			}
		}
	}

	/**
	 * Test merging of numeric token array into token array.
	 */
	public function test_merge_token_arrays ()
	{
		// Beginning token array and numeric token array
		$begin_tokens = array
		(
			0 => 'container',
			1 => 'innerHTML',
			2 => 'div',
			3 => 'id',
			4 => 'yo',
			5 => 'mama'
		);
		$begin_numeric_tokens = array
		(
			10,
			13,
			5,
			4,
			3,
			2,
			1
		);

		// Get actual results of merging and verify them
		$real_tokens = $begin_tokens;
		$real_numeric_tokens = $begin_numeric_tokens;
		phpJSO_merge_token_arrays($real_tokens, $real_numeric_tokens);
		self::assertTrue($real_tokens[0] == $begin_tokens[0]);
		self::assertTrue($real_tokens[1] == '');
		self::assertTrue($real_tokens[2] == '');
		self::assertTrue($real_tokens[3] == '');
		self::assertTrue($real_tokens[4] == '');
		self::assertTrue($real_tokens[5] == '');
		self::assertTrue($real_tokens[6] == $begin_tokens[1]);
		self::assertTrue($real_tokens[7] == $begin_tokens[2]);
		self::assertTrue($real_tokens[8] == $begin_tokens[3]);
		self::assertTrue($real_tokens[9] == $begin_tokens[4]);
		self::assertTrue($real_tokens[10] == '');
		self::assertTrue($real_tokens[11] == $begin_tokens[5]);
		self::assertTrue(!isset($real_tokens[12]));
	}

	/**
	 * Test function that inserts tokens into the token array
	 */
	public function test_insert_token ()
	{
		$token_array = array();

		phpJSO_insert_token($token_array, 'test1', -1);
		self::assertTrue($token_array[0] == 'test1');

		phpJSO_insert_token($token_array, 'test2', -1);
		self::assertTrue($token_array[0] == 'test1');
		self::assertTrue($token_array[1] == 'test2');
		
		phpJSO_insert_token($token_array, 'test3', 0);
		self::assertTrue($token_array[0] == 'test3');
		self::assertTrue($token_array[1] == 'test1');
		self::assertTrue($token_array[2] == 'test2');
		
		phpJSO_insert_token($token_array, 'test4', 2);
		self::assertTrue($token_array[0] == 'test3');
		self::assertTrue($token_array[1] == 'test1');
		self::assertTrue($token_array[2] == 'test4');
		self::assertTrue($token_array[3] == 'test2');
		
		phpJSO_insert_token($token_array, 'test5', 1);
		self::assertTrue($token_array[0] == 'test3');
		self::assertTrue($token_array[1] == 'test5');
		self::assertTrue($token_array[2] == 'test1');
		self::assertTrue($token_array[3] == 'test4');
		self::assertTrue($token_array[4] == 'test2');
	}

	/**
	 * Test the function that restores all removed strings
	 * back into the code.
	 */
	public function test_restore_strings ()
	{
		// This is what we'll work from
		$strings = array('"test1"', "'str2'", "'s1'", "'s2'", "'s3'", '"s4"');
		$start_code = 'var test = `5` + `1`; alert(`2`,`3`,`4`,`0`);';

		// Get expected result
		$expected_result = 'var test = "s4" + \'str2\'; alert(\'s1\',\'s2\',\'s3\',"test1");';
		
		// Get actual result
		$real_result = $start_code;
		phpJSO_restore_strings($real_result, $strings);

		// Make sure everything is ok....
		if ($real_result != $expected_result)
		{
			print("$real_result\n-----\n$expected_result");
		}
		self::assertTrue($real_result == $expected_result);
	}

	/**
	 * And finally, check the dupe counter.
	 */
	public function test_dupe_counter ()
	{
		// Get the token array to count for dupes
		$dupe_array = array
		(
			'webddm_',
			'webddm',
			'webddm',
			'webddm_',
			'webddm',
			'webddm_',
			'webddm_',
			'_webddm',
			'webddm',
			'_webddm',
			'webddm',
			'webddm',
			'webddm_',
			'webddm',
			'_webddm',
			'webddm',
			'webddm_',
			'_webddm'
		);

		// Expected array to be returned
		$expected_dupe_counts = array
		(
			'webddm' => 8,
			'_webddm' => 4,
			'webddm_' => 6
		);
		
		// Get actual result
		$actual_dupe_counts = array();
		phpJSO_count_duplicates($actual_dupe_counts, $dupe_array);

		// Check if results are okay!
		self::assertTrue(count($actual_dupe_counts) == 3);
		self::assertTrue($actual_dupe_counts['webddm'] == $expected_dupe_counts['webddm']);
		self::assertTrue($actual_dupe_counts['_webddm'] == $expected_dupe_counts['_webddm']);
		self::assertTrue($actual_dupe_counts['webddm_'] == $expected_dupe_counts['webddm_']);
	}

	/**
	 * Tests actual replacement of tokens with token identifiers in the code.
	 */
	public function test_token_replacement ()
	{
		// Code that will be modified
		$begin_code = "function Hash()"
			. "{"
				. "this.length=0;"
				. "this.numericLength=0;"
				. "this.elementData=[];"
				. "if(arguments[0]&&arguments[0].elementData)"
				. "{"
					. "this.length=arguments[0].length;"
					. "this.numericLength=arguments[0].numericLength;"
					. "this.elementData=arguments[0].elementData;"
					. "return"
				. "}"
				. "if(typeof(arguments[0])=='object')"
				. "{"
					. "for (var i in arguments[0])"
					. "{"
						. "this.set(i,arguments[0][i])"
					. "}"
					. "return"
				. "}"
				. "for(var i=0;i<arguments.length;i+=2)"
				. "{"
					. "if (typeof(arguments[i+1])!='undefined')"
					. "{"
						. "this.set(arguments[i],arguments[i+1])"
					. "}"
				. "}"
			. "}";

		// Array of tokens in this code
		$tokens = array
		(
			0 => '',
			1 => '',
			2 => '',
			3 => 'this',
			4 => 'length',
			5 => 'numericLength',
			6 => 'elementData',
			7 => 'if',
			8 => 'arguments',
			9 => 'return',
			10 => 'typeof',
			11 => 'for',
			12 => 'var',
			13 => 'set'
		);
		
		// Expected result
		$expected_result = 'function Hash(){3.4=0;3.5=0;3.6=[];7(8[0]&&8[0].6){3.4=8[0].4;3.5=8[0].5;3.6=8[0].6;9}7(10(8[0])==\'object\'){11 (12 i in 8[0]){3.13(i,8[0][i])}9}11(12 i=0;i<8.4;i+=2){7 (10(8[i+1])!=\'undefined\'){3.13(8[i],8[i+1])}}}';

		// Get real results and verify them
		$real_result = $begin_code;
		phpJSO_replace_tokens($tokens, $real_result);
		if ($real_result != $expected_result)
		{
			print("\n$real_result\n-----\n$expected_result\n");
		}
		self::assertTrue($expected_result == $real_result);
	}
};
?>
