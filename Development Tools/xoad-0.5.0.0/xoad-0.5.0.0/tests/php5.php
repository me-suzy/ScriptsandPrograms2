<?php

class TestClass
{
	function invoke()
	{
		return array(
		'status'	=>	true,
		'values'	=>	array(0, 1, 2)
		);
	}
}

define('XOAD_AUTOHANDLE', true);

require_once('../xoad.php');

?>
<?= XOAD_Utilities::header('..', false) . "\n" ?>
<script type="text/javascript">

var obj = <?= XOAD_Client::register(new TestClass()) ?>;

alert(obj.invoke().values);

</script>