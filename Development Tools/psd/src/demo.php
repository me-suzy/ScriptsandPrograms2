<?php include 'debug.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Atrise PHP Script Debugger Demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php debug_head();?> 
</head>
<body>

  <h1>Debug points</h1>
  <p>The first php point without variables: <?php debug_point('');?>.
  Now the php code executes 10 iterations of a cycle...</p>

<?php
  $result = 'some string ';
  for($i=0; $i<10; ++$i)
    $result = $result . "$i";
?>
  
  <p>Ok, executed! The second point after 10 php code cycles: <?php debug_point('result');?>.
  You can see the time difference and the result in $result.</p>

  <h1>Debug output</h1>
  <p>This paragraph includes two debug output points:</p>
  <p>The first php output: <?php debug('Bla-bla-bla');?> 
  And the last php output: <?php debug('Some useful info from the php script');?></p>

  <h1>Debug points with your php variables:</h1>
  
<?php
  $variable1 = 'The string variable';
  $variable2 = 3.14;
  $variable3 = '<b>The bold text</b>';
?>
  
  <p>The first php point without variables: <?php debug_point('');?> 
  The second point: <?php debug_point('variable1,variable2,variable3');?> 
  And the last php point<br /> with php array: <?php debug_point('variable1,_SERVER,variable3');?></p>

  <h1>The end of this demo</h1>
  <p><a href="http://www.atrise.com/php-script-debugger/">Go to the
    Atrise PHP Script Debugger Homepage</a></p>
  <p>This is the last paragraph.</p>

</body></html>
<?php debug_foot();?>
