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
		<td><a href="#" onclick="Xamosa_axuda(20); return false;">(?)</a> <?php echo $conf->t('aviso_instalacion'); ?>:</td>
		<td><input type="checkbox" name="aviso_instalacion" value="true" class="checkbox" tabindex="55" checked="checked" /></td>
	</tr>
	<tr id="tr_axuda20" style="display: none;">
		<td colspan="2"><?php echo $conf->t('axuda','aviso_instalacion'); ?></td>
	</tr>
