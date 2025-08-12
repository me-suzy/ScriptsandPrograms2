<?php
/****************************************************************************
* data/plantillas/info_corpo.inc.php
*
* plantilla para la visualización del detalle de ifnormación de un fichero o
* directorio
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

defined('OK') && defined('ACCION') or die();
?>
		<?php if (in_array('descricion', $capas)) { ?>
		<div class="bloque_info">
			<h1><?php echo $conf->t('informacion_xeral'); ?></h1>
			<table class="tabla_info" summary="">
				<tr>
					<td><?php echo $conf->t('tamano_real'); ?></td>
					<td><?php echo $tamano_real; ?></td>
					<td><?php echo $conf->t('propietario'); ?></td>
					<td><?php echo $datos['uid']; ?></td>
				</tr>
				<tr>
					<td><?php echo $conf->t('tamano_disco'); ?></td>
					<td><?php echo $tamano_disco; ?></td>
					<td><?php echo $conf->t('grupo'); ?></td>
					<td><?php echo $datos['gid']; ?></td>
				</tr>
				<tr>
					<td><?php echo $conf->t('ultima_modificacion'); ?></td>
					<td><?php echo date($conf->g('data'), $datos['mtime']); ?></td>
					<td><?php echo $conf->t('permisos'); ?></td>
					<td><?php echo $permisos; ?></td>
				</tr>
				<?php if ($e_imaxe) { ?>
				<tr>
					<td><?php echo $conf->t('ancho_imaxe'); ?></td>
					<td><?php echo $e_imaxe[0]; ?>px</td>
					<td><?php echo $conf->t('alto_imaxe'); ?></td>
					<td><?php echo $e_imaxe[1]; ?>px</td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<?php } if (in_array('enlaces', $capas)) { ?>
		<div class="bloque_info">
			<h1><?php echo $conf->t('enlaces'); ?></h1>
			<table class="tabla_info" summary="">
				<tr>
					<td><?php echo $conf->t('absoluto'); ?></td>
					<td><?php echo $enlace_abs; ?></td>
				</tr>
				<tr>
					<td><?php echo $conf->t('relativo'); ?></td>
					<td><?php echo $enlace_rel; ?></td>
				</tr>
				<tr>
					<td><?php echo $conf->t('ahref'); ?></td>
					<td><?php echo $enlace_href; ?></td>
				</tr>
				<tr>
					<td><?php echo $conf->t('wiki'); ?></td>
					<td><?php echo $enlace_wiki; ?></td>
				</tr>
			</table>
		</div>
		<?php } if (count($datos_inc['desc'][0]['valor']) != '') { ?>
		<div class="bloque_info">
			<h1><?php echo $conf->t('informacion_adicional'); ?></h1>
			<table class="tabla_info" summary="">
			<?php foreach ($datos_inc['desc'] as $v) { ?>
				<tr>
					<td><?php echo $v['campo']; ?></td>
					<td><?php echo $v['valor']; ?></td>
				</tr>
			<?php } ?>
			</table>
		</div>
		<?php } if (count($datos_inc['form']) > 0) { ?>
		<div class="bloque_info">
			<h1><?php echo $conf->t('form_info_adicional'); ?></h1>
			<form id="form_inc" action="accion.php?<?php echo PFN_cambia_url(array('dir','arq','accion'),array($dir,$arq,'info'),false); ?>" method="post" onsubmit="return submitonce();">
			<fieldset>
			<input type="hidden" name="executa" value="true" />
			<input type="hidden" name="formulario" value="form_inc" />
			<input type="hidden" name="cal" value="<?php echo $cal; ?>" />
			<table class="tabla_info" summary="">
			<?php foreach ($datos_inc['form'] as $k => $v) { ?>
				<tr>
					<td><?php echo $v['campo']; ?></td>
					<td><?php echo $v['valor']; ?></td>
				</tr>
			<?php } ?>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="reset" name="<?php echo $conf->t('cancelar'); ?>" value="<?php echo $conf->t('cancelar'); ?>" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="<?php echo $conf->t('aceptar'); ?>" value="<?php echo $conf->t('aceptar'); ?>" />
					</td>
				</tr>
			</table>
			</fieldset>
			</form>
		</div>
		<?php } if (in_array('protexer', $capas) && $conf->g('usuario','admin') && $tipo == 'dir') { ?>
		<div class="bloque_info">
			<h1><?php echo $conf->t('protexer'); ?></h1>
			<?php if ($protexido) { ?>
			<div class="aviso"><?php echo $conf->t('directorio_protexido'); ?>
			<?php } ?>
			<form id="form_protexer" action="accion.php?<?php echo PFN_cambia_url(array('dir','arq','accion'),array($dir,$arq,'info'),false); ?>" method="post" onsubmit="return submitonce();">
			<fieldset>
			<input type="hidden" name="executa" value="true" />
			<input type="hidden" name="formulario" value="protexer" />
			<input type="hidden" name="cal" value="<?php echo $cal; ?>" />
			<table class="tabla_info" summary="">
				<tr>
					<td><?php echo $conf->t('usuario'); ?></td>
					<td><input type="text" name="ht_usuario" value="" class="text" /></td>
				</tr>
				<tr>
					<td><?php echo $conf->t('contrasinal'); ?></td>
					<td><input type="password" name="ht_contrasinal" value="" class="text" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="reset" name="<?php echo $conf->t('cancelar'); ?>" value="<?php echo $conf->t('cancelar'); ?>" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="<?php echo $conf->t('aceptar'); ?>" value="<?php echo $conf->t('aceptar'); ?>" />
					</td>
				</tr>
			</table>
			</fieldset>
			</form>
			<?php if ($protexido) { ?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>
