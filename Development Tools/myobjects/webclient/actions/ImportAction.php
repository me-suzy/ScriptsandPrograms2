<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: ImportAction.php,v 1.1 2004/11/12 20:36:51 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class ImportAction extends Action {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function handle() {
        if(!($this->ddl instanceof DDL)) {
            header("Location: index.php");
            return;
        }
        
        if (move_uploaded_file($_FILES['schemaFile']['tmp_name'],
            MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/import.xml')) {
        } else {
           die ("Possible file upload attack!  Here's some debugging info:\n" . print_r($_FILES, true));
        }
        
        switch ($_POST['importing']) {
        	case 'database':
        		$this->importDatabase();
        		return;
        	case 'table':
        		$this->importTable($_POST['where']);
        		return;
        	default:
        		header("Location: index.php");
        		return;
        }
    }
    
    private function importDatabase() {
    	$import = new DOMDocument();
    	$import->load(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/import.xml');
    	
        $doc = new DOMDocument();
        $doc->load(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
    	
    	$databases = $import->getElementsByTagName('database');
    	$ddl = $doc->documentElement;
    	foreach ($databases as $database) {
    		$dbNode = $doc->importNode($database, true);
			$dbNode = $ddl->appendChild($dbNode);
    	}
    	
    	$doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
    	header("Location: index.php?editServer");
    }
    
    private function importTable($dbName) {
    	$import = new DOMDocument();
    	$import->load(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/import.xml');
    	
        $doc = new DOMDocument();
        $doc->load(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
    	
    	$databases = $doc->getElementsByTagName('database');
    	$found = false;
    	foreach ($databases as $database) {
    		if($database->getAttribute('name') == $dbName) {
    			$found = true;
    			break;
    		}
    	}
    	
    	if($found) {
    		$tables = $import->getElementsByTagName('table');
	    	foreach ($tables as $table) {
	    		$tableNode = $doc->importNode($table, true);
				$tableNode = $database->appendChild($tableNode);
	    	}
    	}
    	
    	$doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
    	header("Location: index.php?editDatabase&amp;database=".$dbName);
    }
}
?>