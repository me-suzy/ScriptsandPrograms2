<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: DefaultAction.php,v 1.2 2004/11/02 09:39:30 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class DefaultAction extends Action {
    public function handle() {
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $this->assignSchema($template);
        $template->display('index.html');
    }
}
?>