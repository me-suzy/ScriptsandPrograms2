<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: WebClient.php,v 1.18 2004/12/07 19:30:03 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
require_once(MYOBJECTS_ROOT . '/compiler/DDL.php');
require_once(MYOBJECTS_ROOT . '/compiler/Exceptions.php');

function __autoload($name) {
    require_once(MYOBJECTS_ROOT . '/webclient/actions/' . $name . '.php');
}

function deldir($dir) {
   $dh=opendir($dir);
   while ($file = readdir($dh)) {
       if($file != "." && $file != "..") {
           $fullpath = $dir . "/" . $file;
           if(!is_dir($fullpath)) {
               unlink($fullpath);
           } else {
               deldir($fullpath);
           }
       }
   }

   closedir($dh);
  
   if(rmdir($dir)) {
       return true;
   } else {
       return false;
   }
}

class WebClient {
    
    public function __construct() {
        session_start();
        
        if(!isset($_SESSION['workFolder'])) {
            srand ((double) microtime() * 1000000);
            $_SESSION['workFolder'] = substr(md5 (uniqid (rand())), 5, 8);
        }
        
        try {
            $action = $this->getAction();
            
            $ddl = $action->getDdl();
            if ( $ddl != null) {
                foreach ($ddl->getDatabases() as $database) {
                    $databaseName = $database->getName();
              
                    if(isset($_SESSION[$databaseName])) {
                        continue;
                    }
                
                    if(!isset($_SESSION[$databaseName]['comments'])) {
                        $_SESSION[$databaseName]['comments'] = true;
                    }
                    if(!isset($_SESSION[$databaseName]['setters'])) {
                        $_SESSION[$databaseName]['setters'] = true;
                    }
                    if(!isset($_SESSION[$databaseName]['getters'])) {
                        $_SESSION[$databaseName]['getters'] = true;
                    }
                    if(!isset($_SESSION[$databaseName]['seperateFiles'])) {
                        $_SESSION[$databaseName]['seperateFiles'] = true;
                    }
                    if(!isset($_SESSION[$databaseName]['version'])) {
                        $_SESSION[$databaseName]['version'] = '1.0';
                    }
                }
            }
            
            $action->handle();
        } catch (Exception $e) {
            $action = new ErrorAction($e);
            $action->handle();
        }
    }
    
    public static function getToolBar() {
        $toolbar = new HorizontalMenu('menu1');
        
        $homeColum = new MenuColumn('Home');
        $homeColum->appendRow(new MenuElement('Home Page', '.'));
        
        $schemaColumn = new MenuColumn('Schema');
        $schemaColumn->appendRow(new MenuElement('New Database Schema', '?newSchema'));
        $schemaColumn->appendRow(new MenuElement('Load Schema File', '?loadSchema'));
        $schemaColumn->appendRow(new MenuElement('Save Schema File', '?saveSchema'));
        $schemaColumn->appendRow(new MenuElement('Generate Schema From Existing Database', '?generateSchema'));
        
        $sqlColumn = new MenuColumn('Sql');
        $sqlColumn->appendRow(new MenuElement('Generate Sql Dump Of Current Schema', '?generateSql'));
        
        $complierColumn = new MenuColumn('Class Generator');
        $complierColumn->appendRow(new MenuElement('Compile Current Schema', '?compile'));
        
        $helpColumn = new MenuColumn('Help');
        $helpColumn->appendRow(new MenuElement('Documentation', 'docs'));
        $helpColumn->appendRow(new MenuElement('About', '?about'));
        
        $toolbar->addColumn($homeColum);
        $toolbar->addColumn($schemaColumn);
        $toolbar->addColumn($sqlColumn);
        $toolbar->addColumn($complierColumn);
        $toolbar->addColumn($helpColumn);
        
        return $toolbar;
    }
    
    public static function getAction() {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            if(isset($_GET['generateSchema'])) {
                return new GenerateSchemaAction();
            }
            elseif(isset($_GET['newSchema'])) {
                $doc = new DOMDocument();
                $root = $doc->createElement('ddl');
                $root->setAttribute('version', '1.0');
                $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
                $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
                $root = $doc->appendChild($root);
                if(!file_exists(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'])) {
                    mkdir(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder']);
                }
                
                // Prune the old work folders that are older than a week
                $d = dir(MYOBJECTS_ROOT . '/webclient/tmp/');
                while($entry = $d->read()) {
                    if ($entry != "." && $entry != ".." && $entry != "CVS" && $entry != $_SESSION['workFolder']) {
						if(is_dir(MYOBJECTS_ROOT . '/webclient/tmp/' . $entry)) {
	                        if(filemtime(MYOBJECTS_ROOT . '/webclient/tmp/' . $entry) < time() - 604800) {
	                        	deldir(MYOBJECTS_ROOT . '/webclient/tmp/' . $entry);
	                        }
						}
                    }
                }
                
                $doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
                return new AddDatabaseAction();
            }
            elseif(isset($_GET['options'])) {
                return new OptionsAction();
            }
            elseif(isset($_GET['loadSchema'])) {
                return new LoadSchemaAction();
            }
            elseif(isset($_GET['saveSchema'])) {
                if(file_exists(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml')) {
                    $fp = fopen(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml', 'rb');
                    header("Cache-Control: ");// leave blank to avoid IE errors
                    header("Pragma: ");// leave blank to avoid IE errors
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=\"schema.xml\"");
                    header("Content-length:".(string)(filesize(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml')));
                    fpassthru($fp);
                    exit;
                } else {
                    header("Location: index.php");
                    exit;
                }
            }
            elseif(isset($_GET['generateSql'])) {
                return new GenerateSqlAction();
            }
            elseif(isset($_GET['compile'])) {
                return new CompilerAction();
            }
            elseif(isset($_GET['about'])) {
                return new AboutAction();
            }
            elseif(isset($_GET['addField'])) {
                return new AddFieldAction();
            }
            elseif(isset($_GET['editField'])) {
                return new EditFieldAction();
            }
            elseif(isset($_GET['addTable'])) {
                return new AddTableAction();
            }
            elseif(isset($_GET['editTable'])) {
                return new EditTableAction();
            }
            elseif(isset($_GET['addDatabase'])) {
                return new AddDatabaseAction();
            }
            elseif(isset($_GET['editDatabase'])) {
                return new EditDatabaseAction();
            }
            elseif(isset($_GET['editServer'])) {
                return new EditServerAction();
            }
            elseif(isset($_GET['export'])) {
            	return new ExportAction();
            }
        } else {
            if(isset($_POST['action'])) {
                if($_POST['action'] == 'generateSchema') {
                    return new GenerateSchemaAction(true);
                }
                elseif($_POST['action'] == 'options') {
                    return new OptionsAction(true);
                }
                elseif($_POST['action'] == 'loadSchema') {
                    return new LoadSchemaAction(true);
                }
                elseif($_POST['action'] == 'addField') {
                    return new AddFieldAction(true);
                }
                elseif($_POST['action'] == 'editField') {
                    return new EditFieldAction(true);
                }
                elseif($_POST['action'] == 'addTable') {
                    return new AddTableAction(true);
                }
                elseif($_POST['action'] == 'editTable') {
                    return new EditTableAction(true);
                }
                elseif($_POST['action'] == 'addDatabase') {
                    return new AddDatabaseAction(true);
                }
                elseif($_POST['action'] == 'editDatabase') {
                    return new EditDatabaseAction(true);
                }
                elseif($_POST['action'] == 'editServer') {
                    return new EditServerAction(true);
                }
                elseif($_POST['action'] == 'compile') {
                    return new CompilerAction(true);
                }
                elseif($_POST['action'] == 'import') {
                    return new ImportAction();
                }
                elseif($_POST['action'] == 'runQuery') {
                    return new RunQueryAction();
                }
            }
        }
        return new DefaultAction();
    }
}

/**
* Abstract Class For Web Actions
*
* This class defines the method handle() that all the Action classes
* should implement. It also parses the ddl file in object creation that is registered
* with the session.
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
abstract class Action {
    
    /**
    * The current database schema object
    */
    protected $ddl;
    
    /**
    * Initializes an Action object based on the current user session
    *
    * Checks if a session registered database schema file exists. If there is a
    * schema file that is in developement it parses it and creates a DDL object
    * for that schema.
    *
    * @return void
    */
    public function __construct() {
        // Check if a database schema file is registered with the user session
        if(isset($_SESSION['workFolder']) && file_exists(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml')) {
            // Validate the schema file against loose ddl Xml Schema definition
            $doc = new DOMDocument();
            $doc->load(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
            if(!$doc->schemaValidate(MYOBJECTS_ROOT . '/compiler/ddl-loose.xsd')) {
                throw new Exception("Current database schema file is corrupt.");
            }
            
            // Create a DDL object
            $this->ddl = new DDL();
            // Gather the info stored in the ddl file into the DDL object
            $this->ddl->parse($doc);
        }
    }
    
    /**
    * The method that will be called by the WebClient when the user requests this action.
    *
    * This method should handle the core functionality of a specific Action
    *
    * @return void
    */
    public abstract function handle();
    
    /**
    * Assigns the database schema graph to the specified Template
    *
    * @param Template $template The template object
    * @return void
    */
    protected function assignSchema(Template $template) {
        if($this->ddl instanceof DDL) {
            $dynamicTree = new DynamicTree("tree", "MySql Server");
            $dynamicTree->loadDDL($this->ddl);
            $template->assign('schemaGraph', $dynamicTree->__toString() . $dynamicTree->getInit());
        } else {
            $message  = "<p>Currently no schema file is loaded</p>\n";
            $template->assign('schemaGraph', $message);
        }
    }
    
    public function getDdl() {
        return $this->ddl;
    }
}
?>