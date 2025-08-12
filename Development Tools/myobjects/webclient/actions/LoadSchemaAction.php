<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: LoadSchemaAction.php,v 1.2 2004/11/02 09:39:30 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class LoadSchemaAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        
        if($this->post) {
            $this->loadSchema();
            return;
        }
        
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $this->assignSchema($template);
        $template->assign('errorMessage', '');
        $template->display('load.html');
    }
    
    private function loadSchema() {
        if(!file_exists(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'])) {
            mkdir(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder']);
        }
        

        if (move_uploaded_file($_FILES['schemaFile']['tmp_name'],
            MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml')) {
        } else {
           die ("Possible file upload attack!  Here's some debugging info:\n" . print_r($_FILES, true));
        }
        
        $doc = new DOMDocument();
        $doc->load(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
        // Validate the specified ddl file using the XML Schema
        if(!$doc->schemaValidate(MYOBJECTS_ROOT . '/compiler/ddl.xsd')) {
            $this->error('The supplied file: ' . $_FILES['schemaFile']['name'] . ' is not a valid
            Database Description Language (Schema) File.');
            unlink(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
            return;
        } else {
            header("Location: index.php");
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