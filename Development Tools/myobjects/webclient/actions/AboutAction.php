<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: AboutAction.php,v 1.3 2004/11/02 09:39:30 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class AboutAction extends Action {
    public function handle() {
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('version', MYOBJECTS_VERSION);
        $template->display('about.html');
    }
}
?>