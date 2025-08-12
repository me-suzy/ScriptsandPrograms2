<?php

/*********************************************************
 * Name: browse.php
 * Author: Dave Conley
 * Contact: realworld@rwscripts.com
 * Description: Class wrapper for main browsing the downloads functions
 * Version: 4.00
 * Last edited: 5th March, 2004
 *********************************************************/
    
$loader = new browse();

class browse
{

    function browse()
    {
		global $IN;

		switch($IN["ACT"])
		{
			case 'idx':
			$this->main();
			break;
			
			case 'offline':
			$this->isoffline();
			break;
		}
    }

    function main()
    {
		global $std, $OUTPUT, $CONFIG, $IN;

		require_once ROOT_PATH."/functions/category.php";
		require_once ROOT_PATH."/functions/files.php";

		$cat = new Category();
		$files = new Files();

        $IN['cid'] = intval($IN['cid']);
        
		if ($IN["cid"])
		{
			$std->updateNav("", $IN["cid"]);

			$numfiles = $files->listAll($IN["cid"]);
            if ( !$numfiles )
                $std->warning(GETLANG("nofiles"));
		}
		else if ($IN["dlid"])
		{
			$files->show($IN["dlid"]);
		}
		else
		{
			$std->updateNav("", 0);
			// Category root
			$cat->listAll(0);
		}
		$OUTPUT->add_output($cat->output);
		$OUTPUT->add_output($files->output);

	}
	
	function isoffline()
	{
		global $CONFIG, $OUTPUT, $std;
		$std->updateNav(" > ".GETLANG("dcoffline"), 0);
		$output = "<div class='offline'><b>".GETLANG("dcoffline")."</b><br><br>".$CONFIG['offlinemsg']."</div>";
		$OUTPUT->add_output($output);
	}

}