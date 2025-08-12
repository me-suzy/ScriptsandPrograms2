<table border="1" bgcolor="#ffff99"><tr><td>
<?php
echo 'Hi, I am Fuse <b>"'.module().'/'.subModule().'"</b>';
echo '<br>From __FILE__ = '.__FILE__;
?>
</td></tr></table>
<?php
Queue('test1/test','test1');
Queue('test2/test','test2');
Queue('test3/test','test3');
Queue(module().'/_layout2');

?>
