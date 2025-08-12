<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: AddDatabaseAction.php,v 1.5 2004/11/07 14:19:56 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class AddDatabaseAction extends Action {
    
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
            $this->insertDatabase();
            return;
        }
        

        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $this->assignSchema($template);
        $template->display('adddatabase.html');
    }
    
    private function insertDatabase() {
        
        $database = new DDLDatabase($_POST['databaseName']);
       
        $this->ddl->addDatabase($database); 
       
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
        header("Location: index.php?addTable&database=".$_POST['databaseName']."&index=0");
        return;
    }
}
?>