<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: RunQueryAction.php,v 1.1 2004/12/07 19:30:04 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class RunQueryAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        
        if($db = new mysqli($_POST['hostname'], $_POST['username'], $_POST['password'])) {
            foreach ($this->ddl->getDatabases() as $database) {
                $sql .= $database->toSql();
            }
            
            $statments = explode(";\n", $sql);
            foreach ($statments as $statment) {
                $db->query($statment);
            }
            $db->close();
            header('Location: index.php?generateSql&success');
        }
        exit;
    }
}
?>