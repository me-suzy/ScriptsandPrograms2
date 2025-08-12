<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

?>
<html>
<body>
<?php
$fields = explode(";",$_REQUEST['fields']);
foreach ($fields as $curfield) {
	$arr[$curfield] = $_REQUEST[$curfield];
}
#echo serialize($arr);
?>
<script type="text/javascript">
window.returnValue = '<?php echo serialize($arr); ?>'; 
window.close();
</script>
</body>
</html>