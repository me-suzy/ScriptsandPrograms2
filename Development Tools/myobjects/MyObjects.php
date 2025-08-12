<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: MyObjects.php,v 1.9 2004/11/30 13:11:30 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCommandLineClient
*/

// If the file is reached through a web server redirect to index.php
if(!isset($_SERVER['argv'])) {
    header("Location: index.php");
    exit;
}

// Define MyObjects root directory
define('MYOBJECTS_ROOT', dirname($_SERVER['argv'][0]));

// Import compiler classes
require_once(MYOBJECTS_ROOT . '/compiler/ClassCompiler.php');

// Call the main method
new MyObjectsCommandLineClient($_SERVER['argv']);

function read($message = '', $length = 255) {
    fwrite(STDOUT, $message);
    return trim(fgets (STDIN, $length));
}

function error($err) {
    fwrite(STDERR, "$err\n");
}

function out($str) {
    fwrite(STDOUT, "$str\n");
}

class MyObjectsCommandLineClient {
    
    private $argv;
    private $tasks;
    
    public function __construct($argv) {
        $this->argv = $argv;
        $this->tasks = array();
        out("\n");
        $this->getTasks();
        $this->processTasks();
    }
    
    private function getTasks() {
        
        $getters = true;            // Add getter functions
        $setters = true;            // Add setter functions
        $comments = true;           // Add phpDoc style comments
        $seperateFiles = true;      // Save each class to a seperate file
        $copyRuntime = false;       // Copy runtime classes to output directory
        $outputDir = '.';           // Default output directory
        $generateClasses = false;    // Generate classes task
        $generateSchema = false;    // Generate schema task
        $ddlFile = 'schema.xml';
        $outputSchema = 'schema.xml';
        $outputDir = '.';
        
        // Database names passed as parameter
        $databases = array();
    
        $expectDatabase = false;
        if(count($this->argv) == 1) $this->displayUsage();
        for($i = 1; $i < count($this->argv); $i++) {
            if($this->argv[$i] == '-projectfile' || $this->argv[$i] == '-p') {
                if(count($this->argv) > ($i + 1)) {
                    $projectFile = $this->argv[++$i];
                }
                else {
                    error("\nError: Project file not specified");
                    exit;
                }
                $this->proccessProjectFile($projectFile);
                break;
            }
            elseif($this->argv[$i] == '-generateclasses' || $this->argv[$i] == '-gc') {
                $generateClasses = true;
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-generateschema' || $this->argv[$i] == '-gs') {
                $generateSchema = true;
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-schema' || $this->argv[$i] == '-s') {
                if(count($this->argv) > ($i + 1)) {
                    $ddlFile = $this->argv[++$i];
                }
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-schemaout' || $this->argv[$i] == '-so') {
                if(count($this->argv) > ($i + 1)) {
                    $outputSchema = $this->argv[++$i];
                }
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-out' || $this->argv[$i] == '-o') {
                if(count($this->argv) > ($i + 1)) {
                    $outputDir = $this->argv[++$i];
                }
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-author' || $this->argv[$i] == '-a') {
                if($this->argv[$i + 1]) $author = $this->argv[++$i];
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-version' || $this->argv[$i] == '-v') {
                if($this->argv[$i + 1]) $version = $this->argv[++$i];
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-package' || $this->argv[$i] == '-pck') {
                if($this->argv[$i + 1]) $packageName = $this->argv[++$i];
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-nogetters' || $this->argv[$i] == '-ng') {
                $getters = false;
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-nosetters' || $this->argv[$i] == '-ns') {
                $setters = false;
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-nocomments' || $this->argv[$i] == '-nc') {
                $comments = false;
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-singlefile' || $this->argv[$i] == '-sf') {
                $seperateFiles = false;
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-copyruntime' || $this->argv[$i] == '-cr') {
                $copyRuntime = true;
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-hostname' || $this->argv[$i] == '-h') {
                if(count($this->argv) > ($i + 1)) {
                    $hostName = $this->argv[++$i];
                } else {
                    error("\nError: Host name not specified.");
                    exit;
                }
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-user' || $this->argv[$i] == '-u') {
                if(count($this->argv) > ($i + 1)) {
                    $userName = $this->argv[++$i];
                } else {
                    error("\nError: Database user not specified.");
                    exit;
                }
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-password' || $this->argv[$i] == '-pa') {
                if(count($this->argv) > ($i + 1)) {
                    $password = $this->argv[++$i];
                } else {
                    error("\nError: Database password not specified.");
                    exit;
                }
                $expectDatabase = false;
            }
            elseif($this->argv[$i] == '-databases' || $this->argv[$i] == '-d') {
                $expectDatabase = true;
            }
            else {
                if($expectDatabase) {
                    $databases[] = $this->argv[$i];
                }
                else {
                    error("\Error: Unexpected parameter: '" . $this->argv[$i] . "'");
                    $this->displayUsage();
                }
            }
        }
        
        if($generateClasses) {
            $task = new ClassGenerationTask();
            $task->ddlFile = $ddlFile;
            $task->outputDir = $outputDir;
            $task->getters = $getters;
            $task->setters = $setters;
            $task->comments = $comments;
            if(isset($author)) $task->authorName = $author;
            if(isset($version)) $task->version = $version;
            $task->copyRuntime = $copyRuntime;
            $task->seperateFiles = $seperateFiles;
            if(isset($packageName)) $task->packageName = $packageName;
            $this->tasks[] = $task;
        }
        if($generateSchema) {
            $task = new SchemaGenerationTask();
            $task->outputSchema = $outputSchema;
            $task->dbHost = $hostName;
            $task->dbUser = $userName;
            $task->dbPassword = $password;
            foreach ($databases as $database) {
                $task->addDatabase($database);
            }
            $this->tasks[] = $task;
        }
    }
    
    private function processTasks() {
        foreach ($this->tasks as $t) {
            if($t instanceof SchemaGenerationTask)
            $t->proccess();
        }

        foreach ($this->tasks as $t) {
            if($t instanceof ClassGenerationTask)
            $t->proccess();
        }
    }
    
    public function proccessProjectFile($projectFile) {
        $doc = new DOMDocument();
        $doc->load($projectFile);
        
        // Validate the specified project file using the XML Schema
        if(!$doc->schemaValidate(MYOBJECTS_ROOT . '/project.xsd')) {
            error($projectFile . ' is not a valid project file');
            exit;
        }
        
        $project = $doc->documentElement;
        foreach ($project->childNodes as $taskElement) {
            if (!($taskElement instanceof DOMElement)) {
                continue;
            }
            
            switch ($taskElement->tagName) {
                case 'generateClasses':
                    $this->addGcTask($taskElement);
                    break;
                case 'generateSchema':
                    $this->addGsTask($taskElement);
                    break;
            }
        }
    }
    
    private function addGcTask(DOMElement $taskElement) {
        $task = new ClassGenerationTask();
        if($taskElement->hasAttribute('copyRuntime')) {
            $cr = $taskElement->getAttribute('copyRuntime');
            if($cr == 'true') {
                $task->copyRuntime = true;
            } else {
                $task->copyRuntime = false;
            }
        }
        foreach ($taskElement->childNodes as $node) {
            if(!($node instanceof DOMElement)) {
                continue;
            }
            
            switch ($node->tagName) {
                case 'schemaFile':
                    $task->ddlFile = trim($node->nodeValue);
                    break;
                case 'author':
                    $task->authorName = trim($node->nodeValue);
                    break;
                case 'packageName':
                    $task->packageName = trim($node->nodeValue);
                    break;
                case 'classGenerator':
                    if($node->hasAttribute('addComments')) {
                        $value = $node->getAttribute('addComments');
                        if($value == 'true') {
                            $task->comments = true;
                        } else {
                            $task->comments = false;
                        }
                    } else {
                        $task->comments = true;
                    }
                    
                    if($node->hasAttribute('addGetters')) {
                        $value = $node->getAttribute('addGetters');
                        if($value == 'true') {
                            $task->getters = true;
                        } else {
                            $task->getters = false;
                        }
                    } else {
                        $task->getters = true;
                    }
                    
                    if($node->hasAttribute('addSetters')) {
                        $value = $node->getAttribute('addSetters');
                        if($value == 'true') {
                            $task->setters = true;
                        } else {
                            $task->setters = false;
                        }
                    } else {
                        $task->setters = true;
                    }
                    
                    if($node->hasAttribute('seperateFiles')) {
                        $value = $node->getAttribute('seperateFiles');
                        if($value == 'true') {
                            $task->seperateFiles = true;
                        } else {
                            $task->seperateFiles = false;
                        }
                    } else {
                        $task->seperateFiles = true;
                    }
                    
                    foreach ($node->childNodes as $child) {
                        if($child instanceof DOMElement) {
                            $task->outputDir = trim($child->nodeValue);
                        }
                    }
                    break;
            }
        }
        $this->tasks[] = $task;
    }

    private function addGsTask(DOMElement $taskElement) {
        $task = new SchemaGenerationTask();
        $task->outputSchema = $taskElement->getAttribute('output');
        
        foreach ($taskElement->childNodes as $connect) {
            if($connect instanceof DOMElement) {
                $task->dbHost = $connect->getAttribute('host');
                $task->dbUser = $connect->getAttribute('user');
                $task->dbPassword = $connect->getAttribute('password');
                
                foreach ($connect->childNodes as $databaseElement) {
                    if($databaseElement instanceof DOMElement) {
                        $task->addDatabase(trim($databaseElement->nodeValue));
                    }
                }
            }
        }
        
        $this->tasks[] = $task;
    }
    
    public static function displayUsage() {
        out("MyObjects Command Line Client v1.0\n");
        out("You can perform several tasks using a project file or by passing command line options.\n");
        out("Note that in database schema generation MyObjects Command Line Client");
        out("always assumes default options for every field and can not");
        out("automatically determine foreign keysfrom the database tables.");
        out("These kind of controls, such as foreign keys and validation controls");
        out("should be added manually or you can rather use the MyObjects Web Client");
        out("for more advanced features.\n");
        out("Usage: php MyObjects.php -p <projectFile>");
        out("       php MyObjects.php [options]");
        out("");
        out("  -projectfile, -pf <Project File>      Specify a project file\n");
        out("  -generateclasses, -gc                 Perform class generation task");
        out("    -schema, -s <Database Schema>       Specify database schema file");
        out("    -out, -o <Output Dir>               Specify output directory");
        out("    -author, -a <Author Name>           Specify author name");
        out("    -package, -pck <Package Name>       Specify package name");
        out("    -version, -v <Version Numer>        Specify version number");
        out("    -copyruntime, -cr                   Copy the runtime classes to output dir");
        out("    -nogetters, -ng                     Don't generate getter functions");
        out("    -nosetters, -ns                     Don't generate setter functions");
        out("    -nocomments, -nc                    Don't generate phpdoc comments\n");
        out("  -generateschema, -gs                  Perform database schema generation task");
        out("    -schemaout, -so <Output Schema>     Output schema file");
        out("    -hostname, -h <Database Host>       Database host name");
        out("    -user, -u <Database User>           Database user name");
        out("    -password, -pa <Database Password>  Database password");
        out("    -databases, -d <Databases>          Database names seperated by space"); 
        exit;
    }
}


class ClassGenerationTask {
    public $getters;
    public $setters;
    public $comments;
    public $seperateFiles;
    public $authorName;
    public $version;
    public $packageName;
    public $copyRuntime;
    public $outputDir;
    public $ddlFile;
    
    public function proccess() {
        while(!file_exists($this->ddlFile)) {
            out("Specified database schema file '".$this->ddlFile."' could not be found. Please specifiy the full path of database schema file, type SKIP to skip the task or type EXIT to exit the application.\n");
            $this->ddlFile = read('Input full database schema file path: ');
            if(strtolower($this->ddlFile) == 'exit') {
                exit();
            }
            else if(strtolower($this->ddlFile) == 'skip') {
                break;
            }
        }
        
        if(!file_exists($this->outputDir)) {
            out('Output directory: ' . $this->outputDir . ' does not exists. Do you want to create it ? (Yes/No)');
            $response = read();
            $response = strtolower($response);
            if($response == 'yes' || $response == 'y') {
                mkdir($this->outputDir, null, true);
            } else {
                out('Exiting...');
                exit;
            }
        }
        
        $generator = new DefaultClassGenerator($this->getters, $this->setters);
        if($this->authorName) {
            $generator->setAuthor($this->authorName);
        }
        if($this->version) {
            $generator->setVersion($this->version);
        }
        if($this->packageName) {
            $generator->setPackage($this->packageName);
        }
        
        $compiler = new Compiler($this->ddlFile, $generator, $this->outputDir, $this->seperateFiles, $this->comments);
        
        try {
            $compiler->build(true);
            out('Classes generated');
            
            if($this->copyRuntime) {
                out("Copying runtime classes to: '" . $this->outputDir ."/runtime'");
                $d = dir(MYOBJECTS_ROOT . '/runtime');
                if(!file_exists($this->outputDir .'/runtime')) {
                    mkdir($this->outputDir .'/runtime');
                }
                while($entry = $d->read()) {
                    if ($entry != "." && $entry != ".." && $entry != "CVS" && $entry != "MyObjectsSettings.php") {
                        copy(MYOBJECTS_ROOT . '/runtime/' . $entry, $this->outputDir . '/runtime/' . $entry);
                    }
                }
                
                if(!file_exists($this->outputDir . '/MyObjectsSettings.php')) {
                    $settings = file_get_contents(MYOBJECTS_ROOT . '/runtime/MyObjectsSettings.php');
                    $settings = str_replace('{%runtimePath%}', $this->outputDir . '/runtime', $settings);
                    $settings = str_replace('{%classPath%}', $this->outputDir . '/DATABASE_NAME', $settings);
                    $settings = str_replace('{%dbHost%}', 'localhost', $settings);
                    $settings = str_replace('{%dbUser%}', '', $settings);
                    $settings = str_replace('{%dbPassword%}', '', $settings);
                    $settings = str_replace('{%dbName%}', '', $settings);
                    
                    file_put_contents($this->outputDir . '/MyObjectsSettings.php', $settings);
                }
                
                out("Runtime classes copied.");
            }
            
        } catch (CompileTimeException $e) {
            error($e->getMessage());
        }
    }
}

class SchemaGenerationTask {
    public $outputSchema;
    public $dbHost;
    public $dbUser;
    public $dbPassword;
    public $databases;
    
    public function __construct() {
        $this->databases = array();
    }

    public function addDatabase($database) {
        array_push($this->databases, $database);
    }
    
    public function proccess() {
        
        $doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        $ddl = new DDL();
        foreach ($this->databases as $database) {
            out('Connecting to ' .$database.' at ' . $this->dbHost);
            $db = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $database);
            if (mysqli_connect_errno()) {
                error('Cannot connect to database: ' . $this->dbHost . ':' . $database);
                error('Check the username and password.');
                error('Skipping task...');
                break;
            }
            $ddl->getDatabaseInfo($doc, $db, $database, true);
            $db->close();
        }        
        $doc->save($this->outputSchema);
        
        out('Schema generated and saved to: ' . $this->outputSchema . "\n");
    }
}
?>