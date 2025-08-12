<?php
//echo phpinfo();

if(isset($_GET['all'])){
   $fuselogic_root = dirname(__FILE__);
   for($i=0;$i<4;$i++){
       $fuselogic_root = dirname($fuselogic_root);
       if(file_exists($fuselogic_root.'/fuselogic_root.php')){							      
          break;
       }
   }
   $fuselogic_root = str_replace('\\','/',$fuselogic_root);	
   $_COOKIE['test_path'] = $fuselogic_root.'/modules';
}

if(isset($_COOKIE['test_path'])){
   define('START_DIRECTORY',$_COOKIE['test_path']);
}

if(!defined('START_DIRECTORY')) define('START_DIRECTORY',dirname(__FILE__));

ob_start();

if(!defined('SIMPLE_TEST')){
        define('SIMPLE_TEST',dirname(__FILE__).'/');				
}

require_once(SIMPLE_TEST.'unit_tester.php');
require_once(SIMPLE_TEST.'reporter.php');
require_once(SIMPLE_TEST.'class.testfile.php');

$test = &new GroupTest('FuseLogic Tests on PHP-'.phpversion());

$testFile = &new testfile();

if(!defined('START_DIRECTORY')) define('START_DIRECTORY',dirname(__FILE__));

$files = $testFile->getList(START_DIRECTORY);
$count = count($files);
for($i=0;$i<$count;$i++){
    $test->addTestFile($files[$i]);
}

$test->run(new HtmlReporter());

$temp = ob_get_contents();

?>