<?php
/****************************************************************************
* data/plantillas/multiple_eliminar.inc.php
*
* plantilla para la visualización de la acción de eliminar multiples ficheros
*

PHPfileNavigator versión 2.0.0

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
<div id="ver_info">
	<div class="bloque_info"><h1><?php echo $conf->t('accion').' &raquo; '.$conf->t($accion); ?></h1></div>
	<div class="bloque_info">
		<form action="accion.php?<?php echo PFN_cambia_url('accion',$accion,false); ?>" method="post" onsubmit="return submitonce();">
		<fieldset>
		<input type="hidden" name="executa" value="true" />
		<?php foreach ($multiple_escollidos as $v) { ?>
		<input type="hidden" name="multiple_escollidos[]" value="<?php echo $v; ?>" />
		<?php } ?>
		<div class="aviso_info"><?php echo $conf->t('estado.multiple_eliminar',2); ?></div>
		<div style="width: 100%; text-align: center;">
			<table summary=""><tr><td style="text-align: left;"><?php echo $arbore->arbore_txt; ?></td></tr></table>
			<br /><p>
			<input type="reset" value=" <?php echo $conf->t('cancelar'); ?> " class="boton" onclick="enlace('navega.php?<?php echo PFN_get_url(false); ?>');" />
			<input type="submit" value=" <?php echo $conf->t('aceptar'); ?> " class="boton" style="margin-left: 40px;" />
			</p>
		</div>

		</fieldset>
		</form>
		<br /><ul style="text-align: center;">
			<?php foreach ($multiple_escollidos as $v) { ?>
			<li style="margin: 3px; background: #EEE;"><?php echo $v; ?></li>
			<?php } ?>
		</ul>
	</div>
</div>
