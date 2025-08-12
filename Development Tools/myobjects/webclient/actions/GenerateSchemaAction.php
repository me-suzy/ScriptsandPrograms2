<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: GenerateSchemaAction.php,v 1.6 2004/11/14 20:30:32 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class GenerateSchemaAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        
        if($this->post) {
            $this->generateSchema();
            return;
        }
        
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $this->assignSchema($template);
        $template->assign('errorMessage', '');
        $template->display('generate.html');
    }
    
    private function generateSchema() {
        
        if(!$_POST['dbHost']) {
            $this->error("Please specify the MySql host name");
            return;
        }
        
        if(!$_POST['dbNames']) {
            $this->error("Please specify at least 1 database name");
            return;
        }
        
        $doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        $ddl = new DDL();
        $databases = explode(',', $_POST['dbNames']);
        
        foreach ($databases as $database) {
            $database = trim($database);
            $db = new mysqli($_POST['dbHost'], $_POST['dbUser'], $_POST['dbPassword'], $database);
            if (mysqli_connect_errno()) {
                $this->error('Cannot connect to database. MySql Error Message:<br/>' . mysqli_error($db));
                return;
            }
            $ddl->getDatabaseInfo($doc, $db, $database);
            $db->close();
        }
        
        if(!$error) {
            if(!file_exists(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'])) {
                mkdir(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder']);
            }
            $doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
            header("Location: index.php");
            return;
        }

    }
    
    private function error($message) {
        $template = new Template();
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('errorMessage', '<span style="color: red">' . $message . '</span>');
        $this->assignSchema($template);
        $template->display('generate.html');
    }
}
?>