<?php
/****************************************************************************
* data/plantillas/Xusuarios.inc.php
*
* plantilla para la visualización de la pantalla de modificación de un usuario
* y sus relaciones con las raices
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

defined('OK') && defined('XESTION') or die();
?>
<script type="text/javascript"><!--

function eliminar () {
	<?php if ($id_usuario == $sPFN['usuario']['id']) { ?>
	alert(HtmlDecode('<?php echo addslashes($conf->t('Xerros', 13)); ?>'));
	<?php } else { ?>
	if (confirm(HtmlDecode('<?php echo addslashes($conf->t('Xeliminar_usuario')); ?>'))) {
		enlace('eliminar.php?id_usuario=<?php echo $id_usuario; ?>&amp;<?php echo PFN_get_url(false); ?>');
	}
	<?php } ?>
}

//--></script>
<div id="ver_info">
	<div class="bloque_info"><h1><?php echo $conf->t('xestion').' &raquo; '.$conf->t('Xmodi_usuario'); ?></h1></div>
	<div class="bloque_info">

		<?php if (count($erros) || $ok > 0) { ?>
		<div class="aviso">
			<?php
			if (count($erros)) {
				foreach ($erros as $v) {
					echo $conf->t('Xerros', intval($v)).'<br />';
				}
			} else {
				echo $conf->t('Xok', intval($ok)).'<br />';
			}
			?>
		</div>
		<?php } ?>

		<form action="gdar.php?<?php echo PFN_get_url(false); ?>" method="post" onsubmit="return submitonce();">
		<fieldset>
		<input type="hidden" name="id_usuario" value="<?php echo $usuarios->get('id'); ?>" />

		<table class="tabla_info" summary="">
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(1); return false;">(?)</a> <label for="nome"><?php echo $conf->t('Xnome'); ?>*</label></td>
				<td><input type="text" id="nome" name="nome" value="<?php echo $usuarios->get('nome'); ?>" class="text" tabindex="10" /></td>
			</tr>
			<tr id="tr_axuda1" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_nome'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(2); return false;">(?)</a> <label for="usuario"><?php echo $conf->t('Xusuario'); ?>*</label></td>
				<td><input type="text" id="usuario" name="usuario" value="<?php echo $usuarios->get('usuario'); ?>" class="text" tabindex="20" /></td>
			</tr>
			<tr id="tr_axuda2" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_usuario'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(3); return false;">(?)</a> <label for="contrasinal"><?php echo $conf->t('Xcontrasinal'); ?></label></td>
				<td><input type="password" id="contrasinal" name="contrasinal" value="" class="text" tabindex="30" /></td>
			</tr>
			<tr id="tr_axuda3" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_contrasinal'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(4); return false;">(?)</a> <label for="rep_contrasinal"><?php echo $conf->t('Xrep_contrasinal'); ?></label></td>
				<td><input type="password" id="rep_contrasinal" name="rep_contrasinal" value="" class="text" tabindex="40" /></td>
			</tr>
			<tr id="tr_axuda4" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_rep_contrasinal'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(5); return false;">(?)</a> <label for="email"><?php echo $conf->t('Xemail'); ?></label></td>
				<td><input type="text" id="email" name="email" value="<?echo $usuarios->get('email'); ?>" class="text" tabindex="50" /></td>
			</tr>
			<tr id="tr_axuda5" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_email'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(6); return false;">(?)</a> <label for="max_descargas"><?php echo $conf->t('Xmax_descargas_mes'); ?></label></td>
				<td><input type="text" id="max_descargas" name="max_descargas" value="<?php echo $max_descargas; ?>" class="text" tabindex="60" /> <?php echo $conf->t('Xen_megas'); ?></td>
			</tr>
			<tr id="tr_axuda6" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_max_descargas'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(7); return false;">(?)</a> <label for="actual_descargas"><?php echo $conf->t('descargado_mes'); ?></label></td>
				<td><input type="text" id="actual_descargas" name="actual_descargas" value="<?php echo $actual_descargas; ?>" class="text" tabindex="70" />  <?php echo $conf->t('Xen_megas'); ?></td>
			</tr>
			<tr id="tr_axuda7" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_actual_descargas'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(8); return false;">(?)</a> <label for="id_grupo"><?php echo $conf->t('Xgrupo'); ?>*</label></td>
				<td>
					<select id="id_grupo" name="id_grupo" tabindex="80">
						<?php foreach ($Dgrupos as $k => $v) { ?>
						<option value="<?php echo $k; ?>" <?php echo ($usuarios->get('id_grupo') == $k)?'selected="selected"':''; ?>><?php echo $v; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr id="tr_axuda8" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_grupo'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(9); return false;">(?)</a> <label for="admin"><?php echo $conf->t('Xadministrador'); ?></label></td>
				<td>
					<select id="admin" name="admin" tabindex="90">
						<option value="1" <?php echo $usuarios->get('admin')==1?'selected="selected"':''; ?>>ON</option>
						<option value="0" <?php echo $usuarios->get('admin')==0?'selected="selected"':''; ?>>OFF</option>
					</select>
				</td>
			</tr>
			<tr id="tr_axuda9" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_administrador'); ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="Xamosa_axuda(10); return false;">(?)</a> <label for="estado"><?php echo $conf->t('Xestado'); ?></label></td>
				<td>
					<select id="estado" name="estado" tabindex="100">
						<option value="1" <?php echo $usuarios->get('estado')==1?'selected="selected"':''; ?>>ON</option>
						<option value="0" <?php echo $usuarios->get('estado')==0?'selected="selected"':''; ?>>OFF</option>
					</select>
				</td>
			</tr>
			<tr id="tr_axuda10" style="display: none;">
				<td colspan="2"><?php echo $conf->t('Xaxuda','usuario_estado'); ?></td>
			</tr>
		</table>

		<br /><br />

		<table class="tabla_info" summary=""><tr><td><strong><?php echo $conf->t('Xraices_relacionadas'); ?></strong></td></tr></table>

		<table class="tabla_normal" summary="">
<?php
if (empty($id_usuario)) {
	$usuarios->init('raices');
} else {
	$usuarios->init('raices_usuario', $id_usuario);
}

for ($cont=3; $usuarios->mais() || $cont%3 != 0; $usuarios->seguinte()) {
	$id = $usuarios->get('id');
	$relacion = $usuarios->get('relacion');

	if ($cont == 3) {
		echo '<tr>';
		$cont = 0;
	}

	echo '<td>';

	if (empty($id)) {
		echo '&nbsp;';
	} else {
		echo '<input type="checkbox" id="Fraices_'.$id.'" name="Fraices[]" value="'.$id.'" '.($relacion?'checked="checked"':'').' class="checkbox" /> '
			.'<label for="Fraices_'.$id.'">'.$usuarios->get('nome').'</label>';
	}

	echo '</td>';
	$cont++;

	if ($cont == 3) {
		echo '</tr>';
	}
}
?>
		</table>
		<br />

		<div style="width: 100%; text-align: center;">
			<?php if (!empty($id_usuario)) { ?>
			<input type="reset" value=" <?php echo $conf->t('eliminar'); ?> " class="boton" style="margin-right: 40px;" onclick="eliminar();" tabindex="110" />
			<?php } ?>
			<input type="reset" value=" <?php echo $conf->t('voltar'); ?> " class="boton" style="margin-right: 40px;" onclick="enlace('../index.php?<?php echo PFN_cambia_url('opc', 2, false); ?>');" tabindex="120" />
			<input type="submit" value="<?php echo $conf->t('aceptar'); ?>" class="boton" tabindex="130" /><br />
		</div>
		</fieldset>
		</form>
	</div>
</div>
