<?php

   # error_reporting(E_ALL); // we want to be as verbose as possible here

   /*
    File: test.php

    This is a modified version of the test.php script that comes with the
    Horde project.  --Ajay (ssharma@dc.net)

    Horde: Copyright 1999, 2000 Charles J. Hagenbuch <chuck@horde.org>

    You should have received a copy of the GNU Public
    License along with this package; if not, write to the
    Free Software Foundation, Inc., 59 Temple Place - Suite 330,
    Boston, MA 02111-1307, USA.
    */

   function status ($foo) {
      if ($foo) {
         echo '<b>Yes</b>';
      } else {
         echo '<b>No</b>';
      }
   } // END status

   if (@is_readable('config.php')) {
      $cachetimeout = -1;
      include('config.php');

      if (!isset($_PSL['classdir']) || !is_file($_PSL['classdir'] . '/functions.inc')) {
         echo "<div class=\"error\">Missing functions.inc from the class directory!</div>";
         phpinfo();
         exit;
      }

   } else {
      echo "<div class=\"error\">Missing the config.php script!</div>";
      phpinfo();
      exit;
   }

   /* PHP Version */
   $version = phpversion();
   $major = $version[0];
   if ($major == 3) {
      $bits = explode('.', $version);
      $minor = $bits[count($bits) - 1];
   } else {
      if (strspn($version, '0123456789.') == strlen($version)) {
         $bits = explode('.', $version);
         $minor = $bits[count($bits) - 1];
      } else {
         $minor = substr($version, 3);
      }
   }

   /* PHP module capabilities */
   $mysql = function_exists('mysql_pconnect');
   $xml = function_exists('xml_parser_create');

   /* PHPLIB tests */
   $phplib = function_exists('page_open');
   $track_vars = isset($_GET);

   /* PHP Settings */
   $magic_quotes_gpc = !get_magic_quotes_gpc();
   $magic_quotes_runtime = !get_magic_quotes_runtime();

   function profileProcessorSpeed($max = 1000) {  //

      for ($i=0 ; $i < $max ; ++$i) {
         $bigArray[] = 3 * $i;
      }

      foreach($bigArray AS $bigItem) {
         $value = $bigItem / 2;
      }

      // echo "$value end";
   }


   function profileDiskSpeed($max = 400) {
      global $_PSL, $scriptTimer;

      $contents = $handle = null;
      $dir = $_PSL['basedir'] . '/updir/fileCache/';

      $scriptTimer->start('diskSpeed');

      for ($i=0 ; $i < $max ; ++$i) {

         if ($scriptTimer->get_current('diskSpeed') > 30) {
            echo "Script Interrupted, $i disk cycles completed";
            return $i;
            break;
         }

         $filename = $dir . 'testFile_' . $i  . '.txt';
         $somecontent = "Add this to the file $i \n";

         // Let's make sure the directory is writable first.
         if (is_writable($dir)) {

            // Open file
            if (!$handle = fopen($filename, 'a')) {
               echo "Cannot open file ($filename)";
               exit;
            }

            // Write $somecontent to our opened file.
            if (fwrite($handle, $somecontent) === FALSE) {
                echo "Cannot write to file ($filename)";
                exit;
            }
            fclose($handle);

            // Read contents
            $handle = fopen($filename, 'r');
            $contents .= fread($handle, filesize($filename));
            fclose($handle);

            unlink($filename);

         } else {
            echo "The directory $dir is not writable";
         }

      }

      return null;

   }


   function profileDBaccess($max = 400) {
      global $_PSL, $scriptTimer;

      $contents = null;

      $db = pslNew('BEDB');

      $scriptTimer->start('dbSpeed');

      for ($i=0 ; $i < $max ; ++$i) {

         if ($scriptTimer->get_current('dbSpeed') > 30) {
            echo "Script Interrupted, $i database cycles completed";
            return $i;
            break;
         }

         $j = $i + 30000;

         $query = "SELECT value FROM psl_variable WHERE variable_id = '$j'";
         $db->query($query);

         while ($db->next_record()) {
            if ($db->Record['value']) {
               $query = "DELETE FROM psl_variable WHERE variable_id = '$j'";
               $db->query($query);
            }
         }

         $query = "INSERT INTO psl_variable (variable_id, value, variable_name) VALUES ('$j', 'Inserted Value $j', 'Test$j')";
         $db->query($query);

         $query = "UPDATE psl_variable SET value = 'Updated Value $j' WHERE variable_id = '$j'";
         $db->query($query);

         $query = "SELECT value FROM psl_variable WHERE variable_id = '$j'";
         $db->query($query);

         if ($db->next_record()) {
            $contents .= $db->Record['value'];
         }

         $query = "DELETE FROM psl_variable WHERE variable_id = '$j'";
         $db->query($query);

      }

      return null;

   }

   /*** Bandwidth Tester 0.92 ***/
   function profileBandwidth() {

      // How many bytes to test with. Mimimum=70. 128KB=131072. 1MB=1048576
      $testsize = 131072;

      header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
      header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                                                      // always modified
      header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
      header ("Pragma: no-cache");                          // HTTP/1.0
      header ("X-Notice: ");
      header ("X-Notice: Bandwidth-Tester is freeware.");
      header ("X-Notice: You may use it freely on your site.");
      header ("X-Notice: Just don't remove this notice.");

      if(true){

         // seed random
         srand ((double) microtime() * 1000000);

         $realtestsize = $testsize - 70;

         echo "<html>\n<head><title>Bandwidth Tester</title></head>\n<body>";

         if($_SERVER["REQUEST_METHOD"]=="GET" && $_GET["execute"]!="1"){
            echo('<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="GET"><input type="hidden" name="mode" value="profileBandwidth"><input type="submit" value="Click Here To Begin Testing" onClick="this.value=\'Please wait while your request is being processed, it may take a while\'"><input type="hidden" name="execute" value="1"><input type="hidden" name="DO.NOT.CACHE" value="'.rand(255,65536).'"></form>');
         } elseif($_GET["execute"]=="1"){
            $teststring=GetTestString($realtestsize);echo('<form method="POST" action="'.$_SERVER["SCRIPT_NAME"].'?mode=profileBandwidth"><input type="hidden" name="td" value="No Test"><input type="button" value="Please wait while your request is being processed, it may take a while"></form>
<script language="JavaScript">
var Hi = new Date();
</script>'.$teststring.'<script language="JavaScript">
var Bye = new Date();
var NiHao = new Array(Hi.getTime(),Bye.getTime());
var Factor=1024;
if(NiHao[1]==NiHao[0])
Ditt=0;
else
Ditt=(NiHao[1]-NiHao[0])/Factor;
document.forms[0].elements[0].value=Ditt;
document.forms[0].submit();
</script>
<p>Tested. Now processing your request....</p>');
         } elseif($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["td"]>0){
            echo('<p>We have tested your Internet connection.<br />The speed to which you connected to us is: ' . CalculateBandwidth($_POST['td'],$testsize).'</p>');
         } elseif($_SERVER["REQUEST_METHOD"]=="POST" && $_POST['td']==0){
            echo('<p>We were unable to test your connection speed.<br />It was too fast to measure.<br /><a href="' . $_SERVER['SCRIPT_NAME'] . '?execute=1&DO.NOT.CACHE='.rand(255,65536).'" onClick="this.innerText=\'The system is now generating the random test data to benchmark your connection speed. It will take a while.\'">If you would like to try testing again, click here.</a></p><p>'.CalculateBandwidth($_POST['td'], $testsize).'</p>');
         }
      }
   }

   function GetTestString($drealtestsize){
      $duhteststring = '<!'.'--';
      for($i=0;$i<$drealtestsize; ++$i){
         $duhteststring .= generatekeycode();
      }
      $duhteststring .= '-' . '->';
      return $duhteststring;
   }

   function CalculateBandwidth($Ditt,$Dott){
      $Datasize=$Dott;
      $LS=$Datasize/$Ditt;
      $kbps=(($LS*8)*10*1.02)/10;
      $mbps=$kbps/1024;
      if($mbps >= 1){
         $speed = $mbps . ' Mbps ';
      } else {
         $speed = $kbps . ' Kbps ';
      }
      $speed .="<br />Time taken to test connection: ".(($Ditt*1024)/1000)." Seconds <br />A number used to determine your speed: ".$LS."<br />Another number used to determine your speed: ".$Ditt."<br />Tested your connection with ".$Datasize."Bytes/".($Datasize/1024)."KB/".($Datasize/1048576)."MB of random data<br />";
      return $speed;
   }


   function generatekeycode(){
      // srand ((double) microtime() * 1000000);

      // Made the randomizer a little more "random"! :)
      srand ((double) microtime() * rand(100000,1000000) / rand(1,15));
      $tester = rand(33,255);
      if($tester==45) {
         return generatekeycode();
      }
      return chr($tester);
   }


   /* Handle special modes */
   if (isset($_GET['mode'])) {
      switch (clean($_GET['mode'])) {

         case 'phpinfo':
         phpinfo();
         exit();
         break;

         case 'profile':
         $content = '<p>' . date("D M j G:i:s T Y") . ' - ';

         $scriptTimer->start('profile');
         $processorCycles = 10000;
         profileProcessorSpeed($processorCycles);
         $processorSpeed = $scriptTimer->get_current('profile');
         $content .= "Processor Speed ($processorCycles Cycles): $processorSpeed - ";

         $fileCycles = 400;
         $diskCycleResults = profileDiskSpeed($fileCycles);
         $diskCycleResults = (!empty($diskCycleResults)) ? $diskCycleResults : $fileCycles;
         $diskSpeed = $scriptTimer->get_current('profile') - $processorSpeed;
         $content .= "Disk Speed ($diskCycleResults Cycles): $diskSpeed - ";

         $dbCycles = 250;
         $dbCycleResults = profileDBaccess($dbCycles);
         $dbCycleResults = (!empty($dbCycleResults)) ? $dbCycleResults : $dbCycles;
         $dbSpeed = $scriptTimer->get_current('profile') - $processorSpeed - $diskSpeed;
         $content .= "DB Speed ($dbCycleResults Cycles): $dbSpeed </p>\n";

         $dir = $_PSL['basedir'] . '/updir/fileCache';
         $filenameLog = $dir . '/profileLog.txt';
         $filenameAverage = $dir . '/profileAverage.txt';

         // Read contents of log
         if (is_readable($filenameLog)) {
            $handle = fopen($filenameLog, 'r');
            $contents .= fread($handle, filesize($filenameLog));
            fclose($handle);
         }

         // Write log
         if (!$handle = fopen($filenameLog, 'a')) {
            echo "Cannot open file ($filenameLog)";
            exit;
         }
         if (fwrite($handle, $content) === FALSE) {
             echo "Cannot write to file ($filenameLog)";
             exit;
         }
         fclose($handle);

         // Read contents of log
         if (is_readable($filenameAverage)) {
            $handle = fopen($filenameAverage, 'r');
            $values = unserialize(fread($handle, filesize($filenameAverage)));
            fclose($handle);

            $count = $values['count'] + 1;
            $processor = (($values['processor'] * $values['count']) + $processorSpeed) / $count;
            $disk = (($values['disk'] * $values['count']) + $diskSpeed) / $count;
            $database = (($values['database'] * $values['count']) + $dbSpeed) / $count;
         } else {
            $count = 1;
            $processor = $processorSpeed;
            $disk = $diskSpeed;
            $database = $dbSpeed;
         }
         $average = "<p>Count $count - Processor Speed (100000 Cycles): $processor - Disk Speed (400 Cycles): $disk - DB Speed (250 Cycles): $database </p>";

         // Write log
         $value = serialize(array('count' => $count, 'processor' => $processor, 'disk' => $disk, 'database' => $database));
         if (!$handle = fopen($filenameAverage, 'w')) {
            echo "Cannot open file ($filenameAverage)";
            exit;
         }
         if (fwrite($handle, $value) === FALSE) {
             echo "Cannot write to file ($filenameAverage)";
             exit;
         }
         fclose($handle);

         echo "<h1>Last Results</h1> $content <h1>Average Results</h1>$average <h1>All Results</h1> $contents ";

         exit();
         break;

         case 'profileBandwidth':
         profileBandwidth();
         exit();
         break;

         case 'phplib-slash':
         // page_open(array("sess"=>"slashSess","auth"=>"slashAuth","perm"=>"slashPerm"));

         // s is a per session variable, u is a per user variable.
         if (!isset($s)) {
            $s = 0;
            $sess->register('s');
         }
      ?>
    <html>
    <body text="black">
       <a href="<?php $sess->pself_url()?>">Reload</a> this page to see the counters increment.<br />
       <a href="test.php">Go back</a> to the test.php page.<br />
       <a href="test.php?mode=phpinfo">View</a> the output of phpinfo().<br />
       <a href="test.php?mode=profile">Test Server</a> performance of some basic math, db & file processes.<br />
       <a href="test.php?mode=profileBandwidth">Test Bandwidth</a>.<br />
       <h3>Per Session Data: <?php $s++; echo $s ?></h3>
       Session ID: <?php echo $sess->id ?>
       If this page works correctly, then you have a correctly configured slashSess class. You should be done with PHPLIB setup.
      <?php
         echo '</body></html>';
         // Save data back to database.
         page_close();

         // print_r($sess);

         break;

         default:
         break;
      }
   } else {

   ?>

<html>
<head>
<title>Back-End CMS: System Capabilities Test</title>
</head>

<body bgcolor="white" text="black">

<a href="test.php?mode=phpinfo">View</a> the output of phpinfo().<br />
<a href="test.php?mode=profile">Test</a> server performance of some basic math, db & file processes.<br />
<a href="test.php?mode=profileBandwidth">Test Bandwidth</a>.<br />

<h3>Versions</h3>
<ul>
   <li>Back-End Version: <?php echo $_BE['version'] ?></li>
   <li>PHPSlash Version: <?php echo $_PSL['version'] ?></li>
   <li>PHP Version: <?php echo $version ?></li>
   <li>PHP Major Version: <?php echo $major ?></li>
   <li>PHP Minor Version: <?php echo $minor ?></li>
   <?php

      if ($major == 3) {

         echo "<li><div class=\"error\">Back-End no longer supports PHP3</div></li>";

      } elseif ($major == 4) {

         if (strstr($minor, 'b')) {

            echo "<li><div class=\"error\">You are running a beta of PHP4. You should upgrade to a release version, at least 4.0.0.</div></li>";

         } elseif (strstr($minor, 'RC')) {

            echo "<li><div class=\"error\">You are running a release candidate of PHP4. You should upgrade to a release version, at least 4.0.0.</div></li>";

         } elseif ($minor == 4) {

            echo "<li><div class=\"error\">I'm showing that you are running PHP 4.0.4 and there are some massive problems with the template engine included with PHPLIB 7.2c.</div></li>";

         } elseif ($minor >= 0) {

            echo "<li><div class=\"error\">It looks like you are running a stable version of PHP.  Have Fun!!</div></li>";

         }
         // end of PHP4

      } else {

         echo "<li>Wow, a mystical PHP from the future. Let the Back-End team know what version you have so we can fix this script.</li>";

      }
   ?>
</ul>

<h3>PHP Module Capabilities</h3>
<ul>
   <li>MySQL Support: <?php status($mysql) ?></li>
   <?php if (!$mysql) { ?><li><div class="error">You cannot run Back-End without MySQL support in PHP.  Please recompile PHP and add the <code>--with-mysql</code> line when you <code>./configure</code> it.</div></li><?php } ?>

   <li>XML Support: <?php status($xml) ?></li>
   <?php if (!$xml) { ?><li><div class=\"error\">>You cannot use the RSS block type without XML Support.</div></li><?php } ?>

</ul>

<h3>PHPLIB Configuration</h3>
<ul>
   <li>track_vars: <?php echo status($track_vars) ?></li>
   <?php if (!$track_vars) { ?>
   <li><div class="error">PHPLIB will not work correctly with track_vars disabled. Enable it in your config file before continuing.</div></li>
   <?php } ?>
   <li>PHPLIB (is page_open() defined): <?php echo status($phplib) ?></li>
   <?php if ($phplib) { ?>
   <li>I am now going to try to create a slashSess class. If this line is the last thing that you see, then you do not have class slashSess defined in the config.php file. Fix that before proceeding.</li>
   <?php
   if ($sess) {
      ?>
   <li>Created a slashSess instance successfully.</li>
   <li><a href="test.php?mode=phplib-slash">Click here to test PHPLIB for Slash</a> (If this link results in "Document Contains No Data" or "Fatal error...", then you probably have not defined the slashSess class in the config.php file).</li>
   <?php } ?>
   <?php } ?>
</ul>

<h3>Miscellaneous PHP Settings</h3>
<ul>
   <li>magic_quotes_gpc set to Off: <?php echo status($magic_quotes_gpc) ?></li>
   <?php if (!$magic_quotes_gpc) { ?>
   <li><div class="error">magic_quotes_gpc causes data coming from GET, POST, or COOKIE modes to be processed so that single quotes and backslashes are prefixed with a backslash (\'). This makes your email look very ugly. Turn it off. If the PHPLIB installation instructions claim that they want this setting on, they lie - PHPLIB versions 7 and later work perfectly well with it off.  <b>Note</b>: Check the config.php to change this within php by uncommenting ini_set('magic_quotes_gpc', '');</div></li>
   <?php } ?>
   <li>magic_quotes_runtime set to Off: <?php echo status($magic_quotes_runtime) ?></li>
   <?php if (!$magic_quotes_runtime) { ?>
   <li><div class="error">magic_quotes_runtime may not cause quite as many problems as magic_quotes_gpc, but you still do not need it. Turn it off. If the PHPLIB installation instructions claim that they want this setting on, they lie - PHPLIB versions 7 and later work perfectly well with it off.  </div></li>
   <?php } ?>
   <?php
      if ((ini_get('session.use_trans_sid')) AND !($_PSL['rootsubdomain']))
      echo "<li><div class=\"error\">If you see ?slashSess= appended after your url's, go to the config.php and uncomment ini_set('session.use_trans_sid', 0);</div></li>";

      if ($_PSL['rootdomain'] != $_SERVER['HTTP_HOST'])
         echo "<li><div class=\"error\">Have you set your Root url correctly in the config.ini.php file?
         It is '" . $_PSL['rootdomain'] . "' but we think it should be '" . $_SERVER['HTTP_HOST'] . "' </div></li>";

      if (ini_get('session.auto_start'))
         echo "<li><div class=\"error\">You should set your php.ini to session.auto_start = 0 otherwise you will have trouble logging in.</div>";

   ?>

</ul>


   <?php

      // Code from http://martin.f2o.org/php/portable
      define('boolean', 1);
      define('string',  2);
      define('integer', 3);

      $php_ini_all = array(
         array('string',  'arg_separator.input'),
         array('string',  'arg_separator.output'),
         array('boolean', 'display_errors'),
         array('boolean', 'display_startup_errors'),
         array('boolean', 'magic_quotes_runtime'),
         array('integer', 'error_reporting'),
         array('string',  'variables_order'),
         array('string',  'gpc_order'),
         array('boolean', 'session.use_cookies'),
         array('boolean', 'session.use_only_cookies'),
         array('boolean', 'session.use_trans_sid')
      );

      $php_ini_perdir = array(
         array('boolean', 'asp_tags'),
         array('boolean', 'magic_quotes_gpc'),
         array('string',  'output_buffering'),
         array('boolean', 'register_globals'),
         array('boolean', 'short_open_tag')
      );

      function display_php_conf() {
         echo '<ul>';
         foreach ($GLOBALS['php_ini_all'] as $option ) {
            $value = ini_get($option[1]);
            echo "<li> ini_set('$option[1]', '$value');\n";
         }
         echo '</ul>';
      }

      function display_htaccess_conf() {
         global $php_ini_all, $php_ini_perdir;
         echo "<ul>";
         foreach (array_merge($php_ini_all, $php_ini_perdir) as $option ) {
            $value = ini_get($option[1]);

            if ($option[0] == 'boolean') {
               $value = $value ? 'on' : 'off';
               $directive = 'php_flag';
            } else {
               $directive = 'php_value';
            }

            echo "<li> $directive $option[1] $value\n";
         }
         echo '</ul>';
      }

      echo "<h3>Display PHP Configuration</h3>";
      display_php_conf();

      echo "<h3>Display Apache's httpd.conf & .htaccess Configuration</h3>";
      display_htaccess_conf();

   }

   ?>
</body>
</html>
