<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: GenerateSqlAction.php,v 1.4 2004/12/07 19:30:04 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class GenerateSqlAction extends Action {
    
    public function handle() {
        
        $ddlFile = MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml';
        $error = '';
        if(!file_exists($ddlFile)) {
            $error = 'You haven\'t generated or loaded a schema yet';
        } else {
            $doc = new DOMDocument('1.0');
            $doc->load($ddlFile);
            if(!@$doc->schemaValidate(MYOBJECTS_ROOT . '/compiler/ddl-strict.xsd')) {
                $error = 'Current schema is not a valid database schema. Make sure that you have added all necessary tables, fields and databases.
                <br/>You shouldn\'t use generic Numeric or String field types to dump sql file.';
            }
        }
        
        $sql = '';
        if($error == '') {
            foreach ($this->ddl->getDatabases() as $database) {
                $sql .= $database->toSql();
            }
        }
        
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $this->assignSchema($template);
        $template->assign('sqlDump', $sql);
        if(isset($_GET['success'])) $error = 'Query executed successfully';
        if(isset($_GET['failure'])) $error = 'Query error';
        $template->assign('errorMessage', $error);
        $template->display('sqldump.html');
    }
}
?>