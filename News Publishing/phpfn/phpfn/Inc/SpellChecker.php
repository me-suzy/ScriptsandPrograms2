<?php 

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

class spell_checker
{ 
	var $mode = PSPELL_NORMAL; 
	var $pspell_link; 
	var $pspell_cfg_link; 

	// ==============================================================================================================================

	function spell_checker($Dict = "en", $SkipLen = 3)	// Default of "en", 3
	{ 
		// Create and tailor the config
		$this->pspell_cfg_link = pspell_config_create($Dict); 
		pspell_config_ignore($this->pspell_cfg_link, $SkipLen); 
		pspell_config_mode($this->pspell_cfg_link, $this->mode);

		$this->pspell_link = pspell_new_config($this->pspell_cfg_link); 
	}

	// ==============================================================================================================================

	function check($word)
	{ 
		return pspell_check($this->pspell_link, $word); 
	} 

	// ==============================================================================================================================

	function suggest($word)
	{
		return pspell_suggest($this->pspell_link, $word); 
	} 
};
?>