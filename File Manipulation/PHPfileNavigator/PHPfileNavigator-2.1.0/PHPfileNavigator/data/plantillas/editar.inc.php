<?php
/****************************************************************************
* data/plantillas/editar.inc.php
*
* plantilla para la edición de un fichero
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
		<div class="bloque_info"><h1><?php echo $conf->t('accion').' &raquo; '.$conf->t('editar'); ?></h1></div>
		<div style="width: 100%; text-align: center;">
			<form action="accion.php?<?php echo PFN_cambia_url(array('accion','cal'),array('editar',$cal),false); ?>" method="post" onsubmit="return submitonce();">
			<label for="ancho"><?php echo $conf->t('tamano'); ?>:</label>
			<input type="text" id="ancho" name="ancho" value="<?php echo $editar_ancho; ?>" style="width: 50px;" />
			&nbsp;x&nbsp;
			<input type="text" id="alto" name="alto" value="<?php echo $editar_alto; ?>" style="width: 50px;" />
			&nbsp;&nbsp;
			<input type="submit" name="cambiar_tamano" value="<?php echo $conf->t('enviar'); ?>" />
			</form>
			<form action="accion.php?<?php echo PFN_cambia_url('accion','editar',false); ?>" method="post" onsubmit="return submitonce();">
			<fieldset>
			<input type="hidden" name="executa" value="true" />
			<input type="hidden" name="cal" value="<?php echo $cal; ?>" />
			<br />
			<textarea id="texto" name="texto" style="width: <?php echo $editar_ancho; ?>px; height: <?php echo $editar_alto; ?>px"><?php echo PFN_textoInterno2Form($arquivos->get_contido($arquivo)); ?></textarea>
			<p><br />
			<input type="reset" value=" <?php echo $conf->t('cancelar'); ?> " class="boton" onclick="enlace('navega.php?<?php echo PFN_get_url(false); ?>');" />
			<input type="submit" value=" <?php echo $conf->t('aceptar'); ?> " class="boton" style="margin-left: 40px;" />
			</p>
		</div>
		</fieldset>
		</form>
	</div>
</div>
