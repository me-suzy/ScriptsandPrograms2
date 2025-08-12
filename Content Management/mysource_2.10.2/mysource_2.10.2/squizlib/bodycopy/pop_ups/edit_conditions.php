<?php
# because this page changes depending on which condition is selected
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: '. gmdate('D, d M Y H:i:s',time()-3600) . ' GMT');

require_once(dirname(__FILE__)."/header.php"); 
?>
<script language="JavaScript" src="<?=squizlib_href('var_serialise','var_serialise.js');?>"></script>
<?
global $XTRAS_PATH;

$condition = $_GET['condition'];
if (substr($condition,-9) == '_is_false') {
	$condition = substr($condition,0,-9);
}
$file = "$XTRAS_PATH/conditions/$condition/$condition.inc";

if (file_exists("$file")) {
	include_once("$file");
	$cond = new $condition();
	if (method_exists($cond, 'print_backend')) {
		$cond->print_backend();
		# Added this if to allow the use of Iframes in the Condition code so scrollbars can appear
		# An Iframe can be used with the src URL being _SELF
		# set iframe=1 when getting the contents of the Iframe so it is not resized
		if ($_GET['iframe'] != 1) {
			?>
			<script type="text/javascript" language="javascript">
				resizeTo(<?= $cond->width; ?>, <?= $cond->height; ?>);
			</script>
			<?php
		}
	} else {
		?>
		This Show If has no conditions to customise.
		<?php
	}
} else {
	?>
	This Show If has no conditions to customise.
	<?php
}
require_once(dirname(__FILE__)."/footer.php"); 
?>