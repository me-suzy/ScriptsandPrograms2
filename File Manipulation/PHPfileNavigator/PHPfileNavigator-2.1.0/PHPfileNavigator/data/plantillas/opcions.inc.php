<?php
/****************************************************************************
* data/plantillas/opcions.inc.php
*
* plantilla para la visualización del menú superior de opciones
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
<div id="menu_principal">
	<div id="escolle_ancho"><a href="#" onclick="return marca_ancho_corpo(true);" title="<?php echo $conf->t('maximizar_corpo'); ?>"><img src="<?php echo $relativo.$conf->g('estilo'); ?>imx/ancho_corpo.png" alt="<?php echo $conf->t('maximizar_corpo'); ?>" /></a></div>
	<h1 id="logo"><span>&nbsp;</span><?php echo $conf->t('PFN'); ?></h1>
	<ul id="menu1">
<?php if (!$conf->g('raiz','unica')) { ?>
		<li><a href="<?php echo $menu_opc['escoller_raiz']; ?>"><?php echo $conf->t('escoller_raiz'); ?></a></li>
<?php } if (!empty($menu_opc['zona_admin'])) { ?>
		<li class="admin"><a href="<?php echo $menu_opc['zona_admin']; ?>"><?php echo $conf->t('zona_admin'); ?></a></li>
<?php } if (!empty($menu_opc['buscador'])) { ?>
		<li><a href="<?php echo $menu_opc['buscador']; ?>"><?php echo $conf->t('buscador'); ?></a></li>
<?php } if (!empty($menu_opc['axuda'])) { ?>
		<li><a href="<?php echo $menu_opc['axuda']; ?>"><?php echo $conf->t('axuda'); ?></a></li>
<?php } if (!empty($menu_opc['sair'])) { ?>
		<li><a href="<?php echo $menu_opc['sair']; ?>'"><?php echo $conf->t('sair'); ?></a></li>
<?php } ?>
	</ul>

	<br class="nada" />


	<ul id="menu2">
		<?php if (!empty($menu_opc['actualizar'])) { ?>
		<li>
			<a href="<?php echo $menu_opc['actualizar']; ?>">
			<img src="<?php echo $conf->g('estilo'); ?>imx/actualizar.png" alt="" />
			<?php echo $conf->t('actualizar'); ?></a>
		</li>
		<?php } if (!empty($menu_opc['crear_dir'])) { ?>
		<li>
			<a href="<?php echo $menu_opc['crear_dir']; ?>">
			<img src="<?php echo $conf->g('estilo'); ?>imx/crear_dir.png" alt="" />
			<?php echo $conf->t('crear_dir'); ?></a>
		</li>
		<?php } if (!empty($menu_opc['subir_arq'])) { ?>
		<li>
			<a href="<?php echo $menu_opc['subir_arq']; ?>">
			<img src="<?php echo $conf->g('estilo'); ?>imx/subir_arq.png" alt="" />
			<?php echo $conf->t('subir_arq'); ?></a>
		</li>
		<?php } if (!empty($menu_opc['subir_url'])) { ?>
		<li>
			<a href="<?php echo $menu_opc['subir_url']; ?>">
			<img src="<?php echo $conf->g('estilo'); ?>imx/subir_url.png" alt="" />
			<?php echo $conf->t('subir_url'); ?></a>
		</li>
		<?php } if (!empty($menu_opc['ver_imaxes'])) { ?>
		<li>
			<a href="<?php echo $menu_opc['ver_imaxes']; ?>">
			<img src="<?php echo $conf->g('estilo'); ?>imx/ver_imaxes.png" alt="" />
			<?php echo $conf->t('ver_imaxes'); ?></a>
		</li>
		<?php } if (!empty($menu_opc['arbore'])) { ?>
		<li>
			<a href="<?php echo $menu_opc['arbore']; ?>">
			<img src="<?php echo $conf->g('estilo'); ?>imx/arbore.png" alt="" />
			<?php echo $conf->t('arbore'); ?></a>
		</li>
		<?php } ?>
	</ul>
</div>
