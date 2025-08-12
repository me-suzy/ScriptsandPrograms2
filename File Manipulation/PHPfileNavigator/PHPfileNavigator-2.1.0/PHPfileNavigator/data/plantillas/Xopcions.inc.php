<?php
/****************************************************************************
* data/plantillas/Xopcions.inc.php
*
* plantilla para la visualización del menú superior de opciones en la
* zona de administrador
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
<div id="menu_principal">
	<div id="escolle_ancho"><a href="#" onclick="return marca_ancho_corpo(true);" title="<?php echo $conf->t('maximizar_corpo'); ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/ancho_corpo.png" alt="<?php echo $conf->t('maximizar_corpo'); ?>" /></a></div>

	<h1 id="logo"><span>&nbsp;</span><?php echo $conf->t('PFN'); ?></h1>

	<ul id="menu1">
		<li class="admin"><a href="<?php echo $Xopcions['m_comezo']; ?>"><?php echo $conf->t('comezo'); ?></a></li>
		<li><a href="<?php echo $Xopcions['m_admin']; ?>"><?php echo $conf->t('zona_admin'); ?></a></li>
		<li><a href="<?php echo $Xopcions['m_sair']; ?>"><?php echo $conf->t('sair'); ?></a></li>
	</ul>

	<br class="nada" />

	<ul id="menu2">
		<li><a href="<?php echo $Xopcions['Xm_crear_raiz']; ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/crear_raiz.png" alt="" />&nbsp;<?php echo $conf->t('Xm_crear_raiz'); ?></a></li>
		<li><a href="<?php echo $Xopcions['Xm_crear_usuario']; ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/crear_usuario.png" alt="" />&nbsp;<?php echo $conf->t('Xm_crear_usuario'); ?></a></li>
		<li><a href="<?php echo $Xopcions['Xm_crear_grupo']; ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/crear_grupo.png" alt="" />&nbsp;<?php echo $conf->t('Xm_crear_grupo'); ?></a></li>
		<li><a href="<?php echo $Xopcions['Xm_informes']; ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/informes.png" alt="" />&nbsp;<?php echo $conf->t('Xm_informes'); ?></a></li>
		<li><a href="<?php echo $Xopcions['Xm_varios']; ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/varios.png" alt="" />&nbsp;<?php echo $conf->t('Xm_varios'); ?></a></li>
		<li><a href="<?php echo $Xopcions['Xm_traduccion']; ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/traduccion.png" alt="" />&nbsp;<?php echo $conf->t('Xm_traduccion'); ?></a></li>
	</ul>
</div>

<br class="nada" />
