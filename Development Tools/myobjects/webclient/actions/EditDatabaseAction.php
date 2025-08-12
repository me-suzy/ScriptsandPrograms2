<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: EditDatabaseAction.php,v 1.7 2004/11/13 20:47:17 kills Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class EditDatabaseAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        
        if(!($this->ddl instanceof DDL)) {
            header("Location: index.php");
            return;
        }
        
        if($this->post) {
            $this->updateDatabase();
            return;
        }
        
        if(!isset($_GET['database'])) {
            header("Location: index.php");
            return;
        }
        
        $database = $this->ddl->getDatabase($_GET['database']);
        if(!$database) {
            header("Location: index.php");
            return;
        }
        
        $tables = $database->getTables();
        $rows = '';
        $i = 1;
        $r = 2;
        foreach ($tables as $table) {
            $rows .= '<tr><td align="center" class="row'.$r.'"><input name="tables[]" type="checkbox" id="tables[]" value="'.$table->getName().'" /></td>' . "\n";
            $rows .= '<td class="row'.$r.'"><a href="?editTable&amp;database='.$_GET['database'].'&amp;table='.$table->getName().'" title="Edit this table">'.$table->getName().'</a>';
            $rows .= '</td><td class="row'.$r.'" style="font-size:12px;">[ <a href="?addTable&amp;database='.$_GET['database'].'&amp;index='.$i.'">Add New Table After This</a> ] [ <a href="?export&amp;database='.$_GET['database'].'&amp;table='.$table->getName().'">Export this table</a> ]</td>' . "\n";
            if($r == 2) $r = 1;
            else ($r = 2);
            $i++;
        }

        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('databaseName', $_GET['database']);
        $template->assign('tableRows', $rows);
        $this->assignSchema($template);
        $template->display('editdatabase.html');
    }
    
    private function updateDatabase() {
        if(!isset($_POST['database'])) {
            header("Location: index.php");
            return;
        }
        
        $database = $this->ddl->getDatabase($_POST['database']);
        if(!$database) {
            header("Location: index.php");
            return;
        }
        
        if(isset($_POST['delete'])) {
        	if(isset($_POST['tables'])) {
	            foreach ($_POST['tables'] as $delete) {
	                $database->removeTable($delete);
	            }
        	}
        
	        $doc = new DOMDocument();
	        $root = $doc->createElement('ddl');
	        $root->setAttribute('version', '1.0');
	        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
	        $root = $doc->appendChild($root);
	        
	        foreach ($this->ddl->getDatabases() as $database) {
	            $database->createXmlElement($doc);
	        }
	        
	        $doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
	        header("Location: index.php?editDatabase&database=".$_POST['database']);
	        return;
        }
        elseif (isset($_POST['export'])) {
    		$tables = array();
	        if(isset($_POST['tables'])) {
	            foreach ($_POST['tables'] as $table) {
	                $tables[] = $table;
	            }
	        }
	        $action = new ExportAction();
	        $action->exportTables($_POST['database'], $tables);
	        exit;
        }
        elseif (isset($_POST['changeName'])) {
        	$database->setName($_POST['databaseName']);
	        $doc = new DOMDocument();
	        $root = $doc->createElement('ddl');
	        $root->setAttribute('version', '1.0');
	        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
	        $root = $doc->appendChild($root);
	        
	        foreach ($this->ddl->getDatabases() as $database) {
	            $database->createXmlElement($doc);
	        }
	        
	        $doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
           
            // move database settings
            if(isset($_SESSION[$_POST['database']])) {
                $_SESSION[$_POST['databaseName']] = $_SESSION[$_POST['database']];
                unset( $_SESSION[$_POST['database']]); 
            }
	        header("Location: index.php?editDatabase&database=".$_POST['databaseName']);
        }
    }
}
?>