<?php
    if (!defined ('JPGRAPH_PATH')) {
        echo "<b>JPGRAPH_PATH missing!!!</b><br>";
        echo "please add JPGRAPH_PATH to your config file like this: @define (\"JPGRAPH_PATH\", \"path/to/jpgraph\");";
        echo "<br>Have a look in the new default config file to get additional information";
        echo "<br><br>";    
    }    

    /*if (!defined ('NAVIGATION_PAGE')) {
    	@define ("NAVIGATION_PAGE", 'modules/tree/index.php?command=verticaltabs');
    	echo "<b>NAVIGATION_PAGE missing!!!</b><br>";
        echo "please add NAVIGATION_PAGE to your config file like this: @define (\"NAVIGATION_PAGE\", \"modules/tree/index.php?command=verticaltabs\");";
        echo "<br>Have a look in the new default config file to get additional information";
        echo "<br><br>";
	}*/
	    
	echo 'add: require_once (substr(dirname(__FILE__),0,-7)."/extern/log/Log.php"); to configfile';
	echo 'add: $logger = &Log::singleton($LOGGING_STYLE, $LOGGING_OUTPUT_FILE, "default ident"); to configfile';
    
	    
    echo "TODO: Add Edit Datagrid to section 'CategoryManager'<br>";
    echo "TODO: Ordering in collections_ctrl<br>";
    echo "extern/log/Log.php einbinden in Config";
    echo "add             \$this->command->strict = true; to models";

?>