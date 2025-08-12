<?php
if(function_exists('ob_tidyhandler')){    
}elseif(!function_exists('ob_tidyhandler') and function_exists('tidy_repair_string')){    
    function ob_tidyhandler($buffer){
		    return tidy_repair_string($buffer);		
		}			
}

if(function_exists('ob_tidyhandler')){
    ob_start('ob_tidyhandler');
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
}else{
    echo "<!DOCTYPE  HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
}
?>
<html>
<head>
<title>
<?php
echo userModule().'/'.userSubModule();
?>
</title>
</head>
<body>
<table width="600" align="center" bgcolor="#ccccff" border="0"><tr><td bgcolor="#9900ff" align="center">
<?php
require_once('function.viewarray.php');
echo '<h2>FuseLogic version. '.FL_VERSION.'<br> on php-'.phpversion().'</h2>';
?>
</td></tr><tr><td>
<table border="1"><tr><td valign="top">
<?php
echo '<h3>Link</h3>';
echo '<a href="'.index().'">Home</a>';
echo '<br><a href="'.index().'forum">forum</a> (Not Defined Module)';
echo '<br><a href="'.index().'init/blablabla">init/blablabla</a> (Not Defined Sub Module)';
echo '<br><a href="'.index().'test1">test1</a>';
echo '<br><a href="'.index().'test2">test2</a>';
echo '<br><a href="'.index().'test3/test">test3/test</a>';
echo '<br><a href="'.index().'test3/layout1">The Power of Layout</a>';
echo '<br><a href="'.index().'init/modules_info">Modules Info</a> (Show you all The Fuse)';
echo '<br><a href="'.index().'user/phpinfo">phpinfo()</a>';
echo '<br><br><a href="'.index().'documentation" target="_blank"><big><big><b>Documentation</b></big></big></a><br><br>';

?>
</td><td valign="top" bgcolor="#ffffff">
Produced in <b><REPLACE_TIME/></b> sec<br><br>
<?php
echo getLayout();
?>
</td></tr></table>
</td></tr></table>
<br><div align="center">
<REPLACE_INFO/>
<?php

if(function_exists('ob_tidyhandler')){    
		echo '<br><br><img src="'.webPath().'/vxhtml10.png"/>';				
}

?>
</div>
</body>
</html>
<?php
if(function_exists('ob_tidyhandler')){    
		$temp = ob_get_contents();
		ob_end_clean();		
		echo $temp;		
}
?>