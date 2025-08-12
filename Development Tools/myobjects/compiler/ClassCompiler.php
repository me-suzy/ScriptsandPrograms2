<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: ClassCompiler.php,v 1.4 2004/11/20 15:38:05 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/

if(!defined('MYOBJECTS_ROOT')) {
    define('MYOBJECTS_ROOT', dirname($_SERVER['SCRIPT_FILENAME']));
}

require_once(MYOBJECTS_ROOT . '/compiler/DDL.php');
require_once(MYOBJECTS_ROOT . '/compiler/Exceptions.php');
require_once(MYOBJECTS_ROOT . '/compiler/DefaultClassGenerator.php');

/**
* Compiles the classes that map to the database
*
* This class compiles the ddl file into the classes that map
* to the database tables. The ddl file is first parsed and 
* ddl objects are created. Using the ddl objects this class
* uses the supplied ClassGenerator class to generate Mapable classes.
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class Compiler {
    
    /**
    * @const VERSION Version of the Compiler
    */
    const VERSION = '1.0';
    
    /**
    * @var DOMDocument $doc DOM object for the specified xml file
    */
    protected $doc;
    
    /**
    * @var string $outputDir Name of the output directory that will be used
    * to store the generated classes.
    */
    protected $outputDir;
    
    /**
    * @var array $databases Array of DDLDatabases
    */
    protected $databases;
    
    /**
    * @var boolean $seperateFiles Should be true if all generated classes will be in
    * seperate files named as {classname}.php
    */
    protected $seperateFiles;
    
    /**
    * @var ClassGenerator $generator The object that will create classes
    * using the DDL objects
    */
    protected $generator;
    
    /**
    * @var boolean $comments Should be assigned true to generate phpDoc style comments
    */
    protected $comments;

    /**
    * Constructs a new Compiler
    *
    * @param string $xmlFile Path of ddl file
    * @param ClassGenerator $generator The class generator that 
    * will be used to create classes
    * @param string $outputDir The output directory
    * @param boolean $seperateFiles If passed true all the generated
    * classes will be saved in seperate files named as "Classname.php"
    * @return void
    */
    function __construct($xmlFile, ClassGenerator $generator, $outputDir = '.',
                         $seperateFiles = true, $comments = true) {
        $this->doc = new DOMDocument();
        $this->doc->load($xmlFile);
        // Validate the specified ddl file using the XML Schema
        if(!$this->doc->schemaValidate(MYOBJECTS_ROOT . '/compiler/ddl.xsd')) {
            throw new DDLFileNotValidException($xmlFile . ' is not a valid DDL file');
        }
        
        $this->generator = $generator;
        $this->outputDir = $outputDir;
        $this->seperateFiles = $seperateFiles;
        $this->comments = $comments;
        $this->databases = array();
    }
    
    /**
    * Compiles the assigned ddl file into classes
    *
    * This method checks some logical erros in the ddl file, creates
    * the classes using the supplied Generator object and saves the
    * generated source code to files.
    *
    * @param boolean $verbose Makes verbose output if true
    * @throws CompileTimeException
    * @return void
    */
    private function compile($verbose) {
        $classes = '';
        foreach ($this->databases as $ddlDb) {
            $workDir = $this->outputDir . '/' . $ddlDb->getName();
            $this->generator->load($ddlDb);
            while($this->generator->hasMoreClasses()) {
                $className = $this->generator->currentClass();
        
                if($verbose) {
                    echo "Creating class named: '$className'\n";
                }
                
                try {
                    $classStr = $this->generator->generateClass();
                    // Remove the /* */ style comments from the code if needed
                    if(!$this->comments) {
                        $classStr =preg_replace("'/\*[\d\D]*?\*/'i", "", $classStr);
                    }
                    if($this->seperateFiles) {
                        if(!file_exists($workDir)) {
                            mkdir($workDir);
                        }
                        file_put_contents($workDir . '/' . $className . '.php', $classStr);
                    } else {
                        $classes .= $classStr . "\n";
                    }
                } catch (InvalidForeignKeyException $e) {
                    $err  = 'Error: ' . $e->getMessage() . "\n";
                    $err .= 'Foreign table \'' . $e->reference . '\' referenced in '
                          . $e->fieldName . ' field of ';
                    $err .= 'table ' . $className .' could not be found';
                    throw  new CompileTimeException($err);
                } catch (BooleanFlagNotSetException $e) {
                    $err  = 'Error: ' . $e->getMessage() . "\n";
                    $err .= 'Boolean flag in one of the values defined in ' . $e->fieldName
                          . ' is not set';
                    throw new CompileTimeException($err);
                } catch (InvalidBooleanFieldException $e) {
                    $err  = 'The field: ' . $e->getMessage() . " claims to be boolean but '
                          . 'does not have 2 values\n";
                    throw new CompileTimeException($err);
                }
            }
            if(!$this->seperateFiles) {
                if(!file_exists($workDir)) {
                    mkdir($workDir);
                }
                file_put_contents($workDir .'/classes.php', $classes);
            }
            
            $classNameFunc = $this->generator->generateGetClassNameMethod();
            file_put_contents($workDir . '/tables.php', $classNameFunc);
        }
    }
    
    /**
    * Parses the ddl file and compiles the classes
    *
    * @param boolean $verbose Makes verbose output if true
    * @throws CompileTimeException
    * @return boolean Returns true if the build is successful
    */
    public function build($verbose = false) {
        // Check if the output dir exists
        if(!file_exists($this->outputDir)) {
            throw new CompileTimeException('Output dir not exists: ' . $this->outputDir);
        }
        
        // Check if hte output dir is a directory
        if(!is_dir($this->outputDir)) {
            throw new CompileTimeException('Supplied output dir is not a directory: '
            . $this->outputDir);
        }
        
        // Check if the output dir is writable
        if(!is_writable($this->outputDir)) {
            throw new CompileTimeException('Output dir is not writable: '
            . $this->outputDir);
        }
        
        if($verbose) {
            echo "Parsing database schema file... ";
        }
        
        $ddl = new DDL();
        $ddl->parse($this->doc);
        $this->databases = $ddl->getDatabases();
        
        if($verbose) {
            echo "Done\n";
        }
        
        if($verbose) {
            echo "Compiling classes...\n";
        }
        $this->compile($verbose);
        
        if($verbose) {
            echo "Done\n";
        }
        
        return true;
    }
    
    public function getDatabases() {
        return $this->databases;
    }
}

/**
* Interface that defines the methods that should be implemented by Generator Classes
*
* @package MyObjectsCompiler
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
*/
interface ClassGenerator {
    public function load(DDLDatabase $database);
    public function hasMoreClasses();
    public function currentClass();
    public function generateClass();
    public function generateGetClassNameMethod();
}
?> 