<?php
/****************************************************************************
* data/plantillas/subir_arq.inc.php
*
* plantilla para la visualización de la accíon de subir un fichero
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
<script type="text/javascript"><!--

function cambia_cantos (val) {
	for (ic = 1; ic <= <?php echo $conf->g('inc','limite'); ?>; ic++) {
		if (ic <= val) {
			amosa('cantos'+ic);
		} else {
			oculta('cantos'+ic);
		}
	}
}

//--></script>
<div id="ver_info">
	<div class="bloque_info"><h1><?php echo $conf->t('accion').' &raquo; '.$conf->t('subir_arq'); ?></h1></div>
	<div class="bloque_info">
		<form action="accion.php?<?php echo PFN_get_url(false); ?>" method="post" enctype="multipart/form-data" onsubmit="return submitonce();">
		<fieldset>

		<div style="text-align: center;">
			<?php echo $conf->t('numero_arquivos'); ?>:&nbsp;&nbsp;&nbsp;
			<select id="cantos" name="cantos" onchange="cambia_cantos(this.value);">
			<?php
			for ($i=1; $i <= $conf->g('inc','limite'); $i++) {
				echo '<option value="'.$i.'" '.(($i==$cantos)?'selected="selected"':'').'> '.$i.' </option>';
			}
			?>
			</select>
		</div><br />

		<input type="hidden" name="accion" value="subir_arq" />
		<input type="hidden" name="executa" value="true" />
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $conf->g('inc','peso'); ?>" />
		<?php for ($i=1; $i <= $conf->g('inc','limite'); $i++) {?>
		<div id="cantos<?php echo $i; ?>">
			<table class="tabla_info" summary="">
				<tr>
					<td><label for="nome_arquivo_<?php echo $i; ?>"><?php echo $conf->t('arq'); ?>:</label></td>
					<td><input type="file" id="nome_arquivo_<?php echo $i; ?>" name="nome_arquivo[<?php echo $i; ?>]" class="file" /></td>
				</tr>
				<?php
				$inc->multiple($i);

				foreach ($inc->crea_formulario('arq') as $v) {
				?>
				<tr>
					<td><?php echo $v['campo']; ?></td>
					<td><?php echo $v['valor']; ?></td>
				</tr>
				<?php } ?>
				<?php if ($conf->g('imaxes','pequena')) { ?>
				<tr>
					<td><label for="imaxe_<?php echo $i; ?>"><?php echo $conf->t('imaxe_reducida'); ?></label></td>
					<td>
						<select id="imaxe_<?php echo $i; ?>" name="imaxe[<?php echo $i; ?>]">
							<option value="" <?php echo ($conf->g('imaxes','defecto')=='false' || !$conf->g('imaxes','defecto'))?'selected="selected"':''; ?>><?php echo $conf->t('non_crear'); ?></option>
							<option value="reducir" <?php echo $conf->g('imaxes','defecto')=='reducir'?'selected="selected"':''; ?>><?php echo $conf->t('reducir'); ?></option>
							<option value="recortar" <?php echo $conf->g('imaxes','defecto')=='recortar'?'selected="selected"':''; ?>><?php echo $conf->t('recortar'); ?></option>
						</select>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td><label for="sobreescribir_<?php echo $i; ?>"><?php echo $conf->t("sobreescribir"); ?></label></td>
					<td><input type="checkbox" id="sobreescribir_<?php echo $i; ?>" name="sobreescribir[<?php echo $i; ?>]" value="1" class="checkbox" /></td>
				</tr>
			</table><br />
		</div>
		<?php } ?>
		<div style="text-align: center; width: 100%">
			<input type="reset" value=" <?php echo $conf->t('cancelar'); ?> " class="boton" onclick="location.href='navega.php?<?php echo PFN_get_url(false); ?>'" />
			<input type="submit" value=" <?php echo $conf->t('aceptar'); ?> " class="boton" style="margin-left: 40px;" />
		</div>

		</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript"><!--

cambia_cantos(1);

//--></script>
