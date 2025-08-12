<?php
/****************************************************************************
* data/plantillas/subir_url.inc.php
*
* plantilla para la visualización de la acción de subir una url remota
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

var imaxe = new Image(350,80);
imaxe.src = '<?php echo $conf->g('estilo'); ?>imx/subir_url.gif';

//--></script>
<div id="ver_info">
	<div class="bloque_info"><h1><?php echo $conf->t('accion').' &raquo; '.$conf->t('subir_url'); ?></h1></div>
	<div class="bloque_info">
		<form id="formulario" action="accion.php?<?php echo PFN_get_url(false); ?>" method="post" onsubmit="return submitonce();">
		<fieldset>

		<div style="text-align: center;">
			<div id="capa_formulario_espera" style="visibility: hidden; position: absolute; z-index: 1; text-align: center; width: 100%;">
				<img src="<?php echo $conf->g('estilo'); ?>imx/subir_url.gif" alt="<?php echo $conf->t('subir_url'); ?>" /><br /><br />
				<div class="aviso"><?php echo $conf->t('estado.subir_url',5); ?></div><br /><br />
				<input type="hidden" name="cancelar" value="" />
				<input type="button" name="bnt_cancelar" value=" <?php echo $conf->t('cancelar'); ?> " class="boton" onclick="anula_envio();" />
			</div>

			<div id="capa_formulario_subir" style="visibility: visible; position: relative; z-index: 2; text-align: center;">
				<div class="aviso_info"><?php echo $msx_adv; ?></div><br />
				<input type="hidden" name="accion" value="subir_url" />
				<input type="hidden" name="executa" value="true" />
				<table class="tabla_info" summary="">
					<tr>
						<td><label for="nome_url"><?php echo $conf->t('direccion_url'); ?>:</label></td>
						<td><input type="text" id="nome_url" name="nome_url" class="text" /></td>
					</tr>
					<tr>
						<td><label for="nome_arquivo"><?php echo $conf->t('nome_arquivo'); ?>:</label></td>
						<td><input type="text" id="nome_arquivo" name="nome_arquivo" class="text" /></td>
					</tr>
		<?php foreach ($inc->crea_formulario('url') as $v) { ?>
					<tr>
						<td><?php echo $v['campo']; ?></td>
						<td><?php echo $v['valor']; ?></td>
					</tr>
		<?php }; ?>
					<tr>
						<td><label for="sobreescribir"><?php echo $conf->t("sobreescribir"); ?></label></td>
						<td><input type="checkbox" id="sobreescribir" name="sobreescribir" value="1" class="checkbox" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="reset" value=" <?php echo $conf->t('cancelar'); ?> " class="boton" onclick="enlace('navega.php?<?php echo PFN_get_url(false); ?>');" />
							<input type="submit" name="btn_aceptar" value=" <?php echo $conf->t('aceptar'); ?> " class="boton" onclick="amosa_espera();" style="margin-left: 40px;" />
						</td>
					</tr>
				</table>
			</div>
		</div>

		</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript"><!--

function anula_envio (boton) {
	obx = MM_findObj('formulario');

	obx.cancelar.value = "cancelar";
	obx.bnt_cancelar.value = "<?php echo $conf->t('anulando'); ?>";
	obx.bnt_cancelar.disabled = true;

	obx.submit();
}

MM_showHideLayers("capa_formulario_espera","","hide");
MM_showHideLayers("capa_formulario_subir","","show");

function amosa_espera () {
	MM_showHideLayers("capa_formulario_espera","","show");
	MM_showHideLayers("capa_formulario_subir","","hide");
}

window.onload = function() {
	obx_form = MM_findObj('formulario');
	obx_form.nome_url.focus();
}

//--></script>
