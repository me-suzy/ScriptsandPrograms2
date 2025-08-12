<?php
/****************************************************************************
* data/plantillas/renomear.inc.php
*
* plantilla para la visualización de la acción de renombrar un fichero
* o directorio
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
		<div class="bloque_info"><h1><?php echo $conf->t('accion').' &raquo; '.$conf->t('renomear'); ?></h1></div>
		<form id="formulario" action="accion.php?<?php echo PFN_cambia_url('accion','renomear',false); ?>" method="post" onsubmit="return submitonce();">
		<fieldset>
		<input type="hidden" name="executa" value="true" />
		<input type="hidden" name="cal" value="<?php echo $cal; ?>" />

		<table class="tabla_info" summary="">
			<tr>
				<td><label for="antigo"><?php echo $conf->t('orixinal'); ?>:</label></td>
				<td><input type="text" id="antigo" value="<?php echo $vars->get('cal'); ?>" readonly="readonly" class="text" /></td>
			</tr>
			<tr>
				<td><label for="novo_nome"><?php echo $conf->t('novo'); ?>:</label></td>
				<td><input type="text" id="novo_nome" name="novo_nome" class="text" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="reset" value=" <?php echo $conf->t('cancelar'); ?> " class="boton" onclick="enlace('navega.php?<?php echo PFN_get_url(false); ?>');" />
					<input type="submit" value=" <?php echo $conf->t('aceptar'); ?> " class="boton" style="margin-left: 40px;" />
				</td>
			</tr>
		</table>
		</fieldset>
		</form>
		<script type="text/javascript"><!--

		obx_formulario = MM_findObj('formulario');
		obx_formulario.novo_nome.focus();

		//--></script>
	</div>
</div>
