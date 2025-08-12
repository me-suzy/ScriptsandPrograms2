<?php
/*******************************************************************************
* instalar/plantillas/instalar.inc.php
*
* plantilla para la visualización de la pantalla de instalación
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
?>
	<tr>
		<td colspan="2"><h1><?php echo $conf->t('datos_xerais'); ?></h1></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(2); return false;">(?)</a> <?php echo $conf->t('idioma'); ?>:</td>
		<td>
			<select name="idioma" size="1" onchange="return enlace('index.php?accion=<?php echo $accion ?>&amp;idioma='+this.value);" tabindex="20">
				<?php
				foreach ($idiomas_valen as $k => $v) {
					echo '<option value="'.$k.'"'.(($k == $idioma)?' selected="selected"':'').'>'.$conf->t('lista_idiomas', $k).'</option>';		
				}
				?>
			</select>
		</td>
	</tr>
	<tr id="tr_axuda2" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','idioma'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(3); return false;">(?)</a> <?php echo $conf->t('gd2'); ?>:</td>
		<td>
			<select name="gd2" size="1" tabindex="30">
				<option value="true" <?php echo ($gd2 == true)?'selected="selected"':''; ?>><?php echo $conf->t('si'); ?></option>
				<option value="false" <?php echo ($gd2 == false)?'selected="selected"':''; ?>><?php echo $conf->t('non'); ?></option>
			</select>
		</td>
	</tr>
	<tr id="tr_axuda3" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','gd2'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(4); return false;">(?)</a> <?php echo $conf->t('zlib'); ?>:</td>
		<td>
			<select name="zlib" size="1" tabindex="40">
				<option value="true" <?php echo ($zlib == true)?'selected="selected"':''; ?>><?php echo $conf->t('si'); ?></option>
				<option value="false" <?php echo ($zlib == false)?'selected="selected"':''; ?>><?php echo $conf->t('non'); ?></option>
			</select>
		</td>
	</tr>
	<tr id="tr_axuda4" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','zlib'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(5); return false;">(?)</a> <?php echo $conf->t('charset'); ?>:</td>
		<td>
			<select name="charset" size="1" tabindex="50">
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
		<td colspan="2"><h1><?php echo $conf->t('base_datos'); ?></h1></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(6); return false;">(?)</a> <?php echo $conf->t('host'); ?>:</td>
		<td><input type="text" name="db_host" value="<?php echo $db_host; ?>" size="50" class="formulario" tabindex="60" /></td>
	</tr>
	<tr id="tr_axuda6" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','db_host'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(7); return false;">(?)</a> <?php echo $conf->t('db_nome'); ?>:</td>
		<td><input type="text" name="db_nome" value="<?php echo $db_nome; ?>" size="50" class="formulario" tabindex="70" /></td>
	</tr>
	<tr id="tr_axuda7" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','db_nome'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(8); return false;">(?)</a> <?php echo $conf->t('usuario'); ?>:</td>
		<td><input type="text" name="db_usuario" value="<?php echo $db_usuario; ?>" size="50" class="formulario" tabindex="80" /></td>
	</tr>
	<tr id="tr_axuda8" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','db_usuario'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(9); return false;">(?)</a> <?php echo $conf->t('contrasinal'); ?>:</td>
		<td><input type="password" name="db_contrasinal" value="<?php echo $db_contrasinal; ?>" size="50" class="formulario" tabindex="90" /></td>
	</tr>
	<tr id="tr_axuda9" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','db_contrasinal'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(10); return false;">(?)</a> <?php echo $conf->t('db_prefixo'); ?>:</td>
		<td><input type="text" name="db_prefixo" value="<?php echo $db_prefixo; ?>" size="50" class="formulario" tabindex="100" /></td>
	</tr>
	<tr id="tr_axuda10" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','db_prefixo'); ?></td>
	</tr>
	<tr>
		<td colspan="2"><h1><?php echo $conf->t('administrador'); ?></h1></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(11); return false;">(?)</a> <?php echo $conf->t('nome'); ?>:</td>
		<td><input type="text" name="ad_nome" value="<?php echo $ad_nome; ?>" size="50" class="formulario" tabindex="110" /></td>
	</tr>
	<tr id="tr_axuda11" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ad_nome'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(12); return false;">(?)</a> <?php echo $conf->t('usuario'); ?>:</td>
		<td><input type="text" name="ad_usuario" value="<?php echo $ad_usuario; ?>" size="50" class="formulario" tabindex="120" /></td>
	</tr>
	<tr id="tr_axuda12" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ad_usuario'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(13); return false;">(?)</a> <?php echo $conf->t('contrasinal'); ?>:</td>
		<td><input type="password" name="ad_contrasinal" size="50" class="formulario" tabindex="130" /></td>
	</tr>
	<tr id="tr_axuda13" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ad_contrasinal'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(14); return false;">(?)</a> <?php echo $conf->t('rep_contrasinal'); ?>:</td>
		<td><input type="password" name="rep_contrasinal" size="50" class="formulario" tabindex="140" /></td>
	</tr>
	<tr id="tr_axuda14" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ad_rep_contrasinal'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(15); return false;">(?)</a> <?php echo $conf->t('email'); ?>:</td>
		<td><input type="text" name="email" value="<?php echo $email; ?>" size="50" class="formulario" tabindex="150" /></td>
	</tr>
	<tr id="tr_axuda15" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ad_email'); ?></td>
	</tr>
	<tr>
		<td colspan="2"><h1><?php echo $conf->t('raiz'); ?></h1></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(16); return false;">(?)</a> <?php echo $conf->t('nome'); ?>:</td>
		<td><input type="text" name="ra_nome" value="<?php echo $ra_nome; ?>" size="50" class="formulario" tabindex="160" /></td>
	</tr>
	<tr id="tr_axuda16" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ra_nome'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(17); return false;">(?)</a> <?php echo $conf->t('ra_path'); ?>:</td>
		<td><input type="text" name="ra_path" value="<?php echo $ra_path; ?>" size="50" class="formulario" tabindex="170" /></td>
	</tr>
	<tr id="tr_axuda17" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ra_path'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(18); return false;">(?)</a> <?php echo $conf->t('ra_web'); ?>:</td>
		<td><input type="text" name="ra_web" value="<?php echo $ra_web; ?>" size="50" class="formulario" tabindex="180" /></td>
	</tr>
	<tr id="tr_axuda18" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ra_web'); ?></td>
	</tr>
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(19); return false;">(?)</a> <?php echo $conf->t('host'); ?>:</td>
		<td><input type="text" name="ra_host" value="<?php echo $ra_host; ?>" size="50" class="formulario" tabindex="190" /></td>
	</tr>
	<tr id="tr_axuda19" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','ra_host'); ?></td>
	</tr>
