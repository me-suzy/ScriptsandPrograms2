<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: CompilerAction.php,v 1.7 2004/11/30 13:11:40 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class CompilerAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        $ddlFile = MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml';
        $error = "";
        if(!file_exists($ddlFile)) {
            $error = "You haven't generated or loaded a schema yet";
        } else {
            $doc = new DOMDocument('1.0');
            $doc->load($ddlFile);
            if(!$doc->schemaValidate(MYOBJECTS_ROOT . '/compiler/ddl.xsd')) {
                $error = "Current schema is not a valid database schema. Make sure that you have added all necessary tables, fields and databases";
            }
        }
        
        if($this->post && $error == "") {
            $this->compile();
            return;
        }

        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $this->assignSchema($template);
        $template->assign('errorMessage', $error != '' ? $error : '');
        $template->display('compile.html');
    }
    
    private function compile() {
        include_once(MYOBJECTS_ROOT . '/compiler/ClassCompiler.php');
        $error = '';
        
        if(!$_POST['outputDir']) {
            $error = 'Output directory is not supplied';
        } else {
            if(is_writable(dirname($_POST['outputDir']))) {
                if(!file_exists($_POST['outputDir'])) {
                    mkdir($_POST['outputDir'], null, true);
                }
            }
            if(!is_dir($_POST['outputDir']) || !is_writable($_POST['outputDir'])) {
                $error = "Can't write to the supplied output directory: " . $_POST['outputDir'];
            }
        }

        if($error == '') {
        	
        	$getters = $setters = $separateFiles = $comments = false;
        	
        	if(isset($_POST['getters']) && $_POST['getters'] == 'true') {
        		$getters = true;
        	}
        	
        	if(isset($_POST['setters']) && $_POST['setters'] == 'true') {
        		$setters = true;
        	}
        	
        	if(isset($_POST['separateFiles']) && $_POST['separateFiles'] == 'true') {
        		$separateFiles = true;
        	}
        	
        	if(isset($_POST['comments']) && $_POST['comments'] == 'true') {
        		$comments = true;
        	}
        	
            $generator = new DefaultClassGenerator($getters, $setters);
            $generator->setAuthor($_POST['authorName']);

            $generator->setVersion($_POST['version']);
            $generator->setPackage($_POST['packageName']);
            
            $compiler = new Compiler(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml', $generator, $_POST['outputDir'], $separateFiles, $comments);
            
            try {
                $compiler->build(false);
                if(isset($_POST['copyRuntime'])) {
                    $d = dir(MYOBJECTS_ROOT . '/runtime');
                    if(!file_exists($_POST['outputDir'] .'/runtime')) {
                        mkdir($_POST['outputDir'] .'/runtime');
                    }
                    while($entry = $d->read()) {
                        if ($entry != "." && $entry != ".." && $entry != "CVS" && $entry != "MyObjectsSettings.php") {
                            copy(MYOBJECTS_ROOT . '/runtime/' . $entry, $_POST['outputDir'] . '/runtime/' . $entry);
                        }
                    }
                    
                    if(!file_exists($_POST['outputDir'] . '/MyObjectsSettings.php')) {
                        $settings = file_get_contents(MYOBJECTS_ROOT . '/runtime/MyObjectsSettings.php');
                        $settings = str_replace('{%runtimePath%}', $_POST['outputDir'] . '/runtime', $settings);
                        $settings = str_replace('{%classPath%}', $_POST['outputDir'] . '/DATABASE_NAME', $settings);
                        $settings = str_replace('{%dbHost%}', $_POST['dbHost'], $settings);
                        $settings = str_replace('{%dbUser%}', $_POST['dbUser'], $settings);
                        $settings = str_replace('{%dbPassword%}', $_POST['dbPassword'], $settings);
                        $settings = str_replace('{%dbName%}', '', $settings);
                        
                        file_put_contents($_POST['outputDir'] . '/MyObjectsSettings.php', $settings);
                    }
                }
            } catch (CompileTimeException $e) {
                $error = $e->getMessage();
            }
            $template = new Template();
            
            $template->assign('toolBar', WebClient::getToolBar()->__toString());
            $this->assignSchema($template);
            $template->assign('errorMessage', 'Classes generated at ' . $_POST['outputDir']);
            $template->display('compile.html');
        }
        if($error != '') {
            $template = new Template();
            
            $template->assign('toolBar', WebClient::getToolBar()->__toString());
            $this->assignSchema($template);
            $template->assign('errorMessage', $error != '' ? $error : '');
            $template->display('compile.html');
        }
    }
}
?>