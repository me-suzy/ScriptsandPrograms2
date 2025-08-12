<?php
/*******************************************************************************
* instalar/plantillas/actualizar_168.inc.php
*
* plantilla para la visualización de la pantalla de actualizarción hasta la
* versión 1.6.8
*

PHPfileNavigator versión 2.1.0

Copyright (C) 2004-2005 Lito <lito@eordes.com>

http://phpfilenavigator.litoweb.net/

Este programa es software libre. Puede redistribuirlo y/o modificarlo bajo los
términos de la Licencia Pública General de GNU según es publicada por la Free
Software Foundation, bien de la versión 2 de dicha Licencia o bien (según su
elección) de cualquier versión posterior. 

Este programa se distribuye con la esperanza de que sea útil, pero SIN NINGUNA
GARANTÍA, incluso sin la garantía MERCANTIL implícita o sin garantizar la
CONVENIENCIA PARA UN PROPÓSITO PARTICULAR. Véase la Licencia Pública General de
GNU para más detalles. 

Debería haber recibido una copia de la Licencia Pública General junto con este
programa. Si no ha sido así, escriba a la Free Software Foundation, Inc., en
675 Mass Ave, Cambridge, MA 02139, EEUU. 
*******************************************************************************/

defined('OK') or die();

include_once ($paths['include'].'formatear.php');
include_once ($paths['include'].'mysql.php');
include_once ($paths['include'].'clases.php');
include_once ($paths['include'].'class_usuarios.php');
include_once ($paths['include'].'class_niveles.php');

$usuarios = new PFN_Usuarios($conf);
$niveles = new PFN_Niveles($conf);
?>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(5); return false;">(?)</a> <?php echo $conf->t('charset'); ?>:</td>
		<td>
			<select name="charset" size="1" tabindex="2">
				<option value="US-ASCII">US-ASCII United States</option>
				<option value="ISO-8859-1">ISO-8859-1 Westerm European</option>
				<option value="ISO-8859-2">ISO-8859-2 East European</option>
				<option value="ISO-8859-3">ISO-8859-3 South European</option>
				<option value="ISO-8859-4">ISO-8859-4 North European</option>
				<option value="ISO-8859-5">ISO-8859-5 Cyrillic</option>
				<option value="ISO-8859-6">ISO-8859-6 Arabic</option>
				<option value="ISO-8859-7">ISO-8859-7 Greek</option>
				<option value="ISO-8859-8">ISO-8859-8 Hebrew</option>
				<option value="ISO-8859-9">ISO-8859-9 Turkish</option>
				<option value="ISO-8859-10">ISO-8859-10 Nordic</option>
				<option value="ISO-8859-11">ISO-8859-11 Thai</option>
				<option value="ISO-8859-14">ISO-8859-14 Celtic</option>
				<option value="ISO-8859-15">ISO-8859-15 Latin-9</option>
				<option value="ISO-8859-16">ISO-8859-16 South-Eastern European</option>
				<option value="UTF-8" selected="selected">UTF-8 Unicode</option>
				<option value="Big5">Big5 Chinese Traditional (Taiwan, HongKong)</option>
				<option value="EUC-TW">EUC-TW Chinese Traditional</option>
				<option value="GB2312">GB2312 Chinese Simplified</option>
				<option value="GB">GB (GuoBiao) Chinese Simplified</option>
				<option value="GBK">GBK Chinese Simplified</option>
				<option value="HZ">HZ Chinese Simplified</option>
				<option value="ISO-2022-GB">ISO-2022-GB New Chinese standard</option>
				<option value="EUC-JP">EUC-JP Japanese</option>
				<option value="EUC-JIS">EUC-JIS Japanese</option>
				<option value="ISO-2022-JP">ISO-2022-JP Japanese</option>
				<option value="ISO-2022-KR">ISO-2022-KR Korean</option>
				<option value="EUC-KR">EUC-KR Korean</option>
				<option value="KO18-R">KO18-R Cyrillic</option>
				<option value="KO18-U">KO18-U Cyrillic</option>
			</select>
		</td>
	</tr>
	<tr id="tr_axuda5" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','charset'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(20); return false;">(?)</a> <?php echo $conf->t('aviso_instalacion'); ?>:</td>
		<td><input type="checkbox" name="aviso_instalacion" value="true" class="checkbox" tabindex="55" checked="checked" /></td>
	</tr>
	<tr id="tr_axuda20" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','aviso_instalacion'); ?></td>
	</tr>
	<tr>
		<td colspan="2"><h1><a href="#" onclick="Xamosa_axuda(2); return false;">(?)</a> <?php echo $conf->t('raices_atopadas'); ?></h1></td>
	</tr>
	<tr id="tr_axuda2" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','raices_atopadas'); ?></td>
	</tr>
	<?php for ($usuarios->init('raices'); $usuarios->mais(); $usuarios->seguinte()) { ?>
	<tr>
		<td>
			<?php echo '<strong>'.$usuarios->get('nome').'</strong>'; ?>
		</td>
		<td>
			<?php echo $usuarios->get('host').' - '.$usuarios->get('path'); ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2"><h1><a href="#" onclick="Xamosa_axuda(3); return false;">(?)</a> <?php echo $conf->t('usuarios_atopados'); ?></h1></td>
	</tr>
	<tr id="tr_axuda3" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','usuarios_atopados'); ?></td>
	</tr>
	<?php for ($usuarios->init('usuarios'); $usuarios->mais(); $usuarios->seguinte()) { ?>
	<tr>
		<td>
			<?php echo '<strong>'.$usuarios->get('nome').'</strong> - '.$usuarios->get('usuario'); ?>
		</td>
		<td>
			<?php echo $conf->t('grupo'); ?>:
			<select name="ids_grupo[<?php echo $usuarios->get('id'); ?>]" size="1" tabindex="<?php echo 2+$usuarios->indice(); ?>">
				<option value="1"<?php echo ($usuarios->get('admin') == 1)?' selected="selected"':''; ?>><?php echo $conf->t('admins'); ?></option>
				<option value="2"<?php echo ($usuarios->get('admin') == 1)?'':' selected="selected"'; ?>><?php echo $conf->t('usuarios'); ?></option>
			</select>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2"><h1><a href="#" onclick="Xamosa_axuda(4); return false;">(?)</a> <?php echo $conf->t('configuracions_atopadas'); ?></h1></td>
	</tr>
	<tr id="tr_axuda4" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','configuracions_atopadas'); ?></td>
	</tr>
	<?php
	$lista = $niveles->carga_contido($paths['conf'], true, true);
	foreach ($lista['nome'] as $v) {
		if (!ereg('\.inc\.php$', $v)) {
			continue;
		}
	?>
	<tr>
		<td colspan="2"><strong><?php echo $v; ?></strong></td>
	</tr>
	<?php } ?>
