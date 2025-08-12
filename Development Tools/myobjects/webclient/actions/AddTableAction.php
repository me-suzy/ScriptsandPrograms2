<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: AddTableAction.php,v 1.6 2004/12/01 14:46:47 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class AddTableAction extends Action {
    
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
            $this->insertTable();
            return;
        }
        
        if(!isset($_GET['database']) || !isset($_GET['index'])) {
            header("Location: index.php");
            return;
        }

        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('databaseName', $_GET['database']);
        $template->assign('index', $_GET['index']);
        $this->assignSchema($template);
        $template->display('addtable.html');
    }
    
    private function insertTable() {
        if(!isset($_POST['database']) || !isset($_POST['index'])) {
            header("Location: index.php");
            return;
        }
        
        $database = $this->ddl->getDatabase($_POST['database']);
        if(!$database) {
            header("Location: index.php");
            return;
        }
        
        $table = new DDLTable($database, $_POST['tableName']);
        
        if($_POST['className'] != '') {
            $table->setClassName($_POST['className']);
        }
        
        if($_POST['superClassName'] != '') {
            $table->setSuperClassName($_POST['superClassName']);
        }
        
        if($_POST['description'] != '') {
            $table->setDescription($_POST['description']);
        }
        
        $database->addTableAtIndex($_POST['index'], $table);
        
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
        if(isset($_POST['insertAndNew'])) {
            $n = count($database->getTables());
            header("Location: index.php?addTable&database=".$_POST['database']."&index=".$n);
        } else {
            header("Location: index.php?addField&database=".$_POST['database']."&table=".$_POST['tableName']."&index=0");
        }
    }
}
?>