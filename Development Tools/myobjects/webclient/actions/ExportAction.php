<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: ExportAction.php,v 1.1 2004/11/12 20:36:51 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class ExportAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
    }
    
    public function handle() {

        if(!($this->ddl instanceof DDL)) {
            header("Location: index.php");
            return;
        }

        if (isset($_GET['database']) && isset($_GET['table'])) {
        	$this->exportTable($_GET['database'], $_GET['table']);
        }
        elseif (isset($_GET['database'])) {
        	$this->exportDatabase($_GET['database']);
        }
        else {
        	header("Location: index.php");
        	return;
        }
    }

    private function exportDatabase($d) {
        $database = $this->ddl->getDatabase($d);
        if(!$database) {
            header("Location: index.php");
            return;
        }
        
		$doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        $database->createXmlElement($doc);
        
        $output = $doc->saveXML();
		header("Cache-Control: ");// leave blank to avoid IE errors
        header("Pragma: ");// leave blank to avoid IE errors
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$d.".xml\"");
        header("Content-length:".(string)(strlen($output)));
        echo $output;
    }
    
    private function exportTable($d, $t) {
		$table = $this->ddl->getDatabase($d)->getTable($t);
        if(!$table) {
            header("Location: index.php");
            return;
        }
        
		$doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        $database = $doc->createElement('database');
        $database->setAttribute('name', $d);
        
        $table->createXmlElement($doc, $database);
        $database = $root->appendChild($database);
        
		$output = $doc->saveXML();
		header("Cache-Control: ");// leave blank to avoid IE errors
        header("Pragma: ");// leave blank to avoid IE errors
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$d.".".$t.".xml\"");
        header("Content-length:".(string)(strlen($output)));
        echo $output;
    }
    
    public function exportDatabases($databases) {
		$doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        foreach ($databases as $dbName) {
        	$database = $this->ddl->getDatabase($dbName);
        	if($database) $database->createXmlElement($doc);
        }
        
        
        $output = $doc->saveXML();
		header("Cache-Control: ");// leave blank to avoid IE errors
        header("Pragma: ");// leave blank to avoid IE errors
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"databases.xml\"");
        header("Content-length:".(string)(strlen($output)));
        echo $output;
    }
    
    public function exportTables($dbName, $tables) {
		$doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        $database = $doc->createElement('database');
        $database->setAttribute('name', $dbName);
        
        $database = $root->appendChild($database);
        
        foreach ($tables as $tableName) {
        	$table = $this->ddl->getDatabase($dbName)->getTable($tableName);
        	if($table) $table->createXmlElement($doc, $database);
        }
        
        $output = $doc->saveXML();
		header("Cache-Control: ");// leave blank to avoid IE errors
        header("Pragma: ");// leave blank to avoid IE errors
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$dbName.".tables.xml\"");
        header("Content-length:".(string)(strlen($output)));
        echo $output;
    }
}
?>