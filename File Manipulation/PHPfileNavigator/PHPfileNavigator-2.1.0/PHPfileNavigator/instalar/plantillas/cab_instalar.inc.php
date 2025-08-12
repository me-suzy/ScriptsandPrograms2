<?php
/*******************************************************************************
* instalar/plantillas/cab_instalar.inc.php
*
* plantilla para la visualización de la cabecera de la instalacion
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
<script type="text/javascript"><!--

document.write('<link rel="stylesheet" href="instalar/instalar.css" type="text/css" />');

//--></script>

<div id="inst_benvido"><?php echo $conf->t('benvido'); ?></div>
<br />
<?php if (is_array($erro)) { ?>
<div class="inst_aviso">
	<?php echo $conf->t('avisos_instalacion'); ?>:<br />
			<?php
			foreach ($erro as $v) {
				echo '<br />- '.$conf->t('erros', $v);

				if ($v == 29) {
					echo '<p>'.implode('<br />', $erro_q).'</p>';
				}
			}
			?>
</div>
<?php } ?>
<br />
<form action="<?php echo $vars->server('PHP_SELF'); ?>" method="post" id="formulario">
<fieldset>
<input type="hidden" name="executa" value="true" />

<table class="tabla_info" summary="">
	<tr>
		<td><a href="#" onclick="Xamosa_axuda(1); return false;">(?)</a> <?php echo $conf->t('accion'); ?>:</td>
		<td>
			<select name="accion" size="1" onchange="return enlace('index.php?idioma=<?php echo $idioma; ?>&amp;accion='+this.value);" tabindex="1">
				<option value="instalar" <?php echo ($accion == 'instalar')?'selected="selected"':''; ?>><?php echo $conf->t('a:instalar'); ?></option>
				<option value="actualizar_168" <?php echo ($accion == 'actualizar_168')?'selected="selected"':''; ?>><?php echo $conf->t('a:actualizar_168'); ?></option>
				<option value="nada" <?php echo ($accion == 'nada')?'selected="selected"':''; ?>><?php echo $conf->t('a:nada'); ?></option>
			</select>
		</td>
	</tr>
	<tr id="tr_axuda1" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','accion'); ?></td>
	</tr>
	<?php include ($paths['instalar'].'plantillas/'.$accion.'.inc.php'); ?>
	<tr>
		<td colspan="2" align="center"><br /><input type="submit" name="Submit" value=" <?php echo $conf->t('enviar'); ?> " class="boton" tabindex="999" /></td>
	</tr>
</table>
</fieldset>
</form>
