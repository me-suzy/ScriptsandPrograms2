<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: EditServerAction.php,v 1.6 2004/11/12 20:13:09 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class EditServerAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        
        if(!($this->ddl instanceof DDL)) {
            header("Location: index.php");
        }
        
        if($this->post) {
            $this->updateServer();
            return;
        }
        
        $databases = $this->ddl->getDatabases();
        $rows = '';
        $i = 1;
        $r = 2;
        foreach ($databases as $database) {
            $rows .= '<tr><td align="center" class="row'.$r.'"><input name="databases[]" type="checkbox" id="databases[]" value="'.$database->getName().'" /></td>' . "\n";
            $rows .= '<td class="row'.$r.'"><a href="?editDatabase&amp;database='.$database->getName().'" title="Edit this database">'.$database->getName().'</a></td><td class="row'.$r.'" style="font-size:12px;">[ <a href="?export&amp;database='.$database->getName().'">Export this database</a> ]</td>';
            if($r == 2) $r = 1;
            else ($r = 2);
            $i++;
        }

        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('databaseRows', $rows);
        $this->assignSchema($template);
        $template->display('editserver.html');
    }
    
    private function updateServer() {
	   if(isset($_POST['remove'])) {
	        if(isset($_POST['databases'])) {
	            foreach ($_POST['databases'] as $delete) {
	                $this->ddl->removeDatabase($delete);
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
    	}
    	elseif (isset($_POST['export'])) {
    		$databases = array();
	        if(isset($_POST['databases'])) {
	            foreach ($_POST['databases'] as $database) {
	                $databases[] = $database;
	            }
	        }
	        $action = new ExportAction();
	        $action->exportDatabases($databases);
	        exit;
    	}
        header("Location: index.php?editServer");
    }
}
?>