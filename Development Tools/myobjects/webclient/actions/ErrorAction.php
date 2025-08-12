<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: ErrorAction.php,v 1.3 2004/11/20 13:45:17 kills Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class ErrorAction extends Action {
    
    protected $e;
    
    public function __construct(Exception $e) {
        $this->e = $e;
    }
    
    public function handle() {
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('errorMessage', $this->e->getMessage());
        $this->assignSchema($template);
        $template->display('error.html');
    }
}
?>