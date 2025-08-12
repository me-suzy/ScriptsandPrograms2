<?php
/****************************************************************************
* data/plantillas/Xconfiguracions.inc.php
*
* plantilla para la visualización de los datos y relaciones de un fichero de 
* configuración
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

defined('OK') && defined('XESTION') or die();
?>
<div id="ver_info">
	<div class="bloque_info"><h1><?php echo $conf->t('xestion').' &raquo; '.$conf->t('Xconf_ver'); ?></h1></div>
	<div class="bloque_info">
		<?php if ((count($erros) > 0) || $ok) { ?>
		<div class="aviso">
			<?php
			if (count($erros)) {
				foreach ($erros as $v) {
					echo $conf->t('Xerros', intval($v)).'<br />';
				}
			} else {
				echo $conf->t('Xok', intval($ok)).'<br />';
			}
			?>
		</div>
		<?php } ?>

		<table class="tabla_normal" summary="">
			<tr>
				<td valign="top" style="text-align: center;">
					<img src="<?php echo $relativo.$conf->g('estilo'); ?>ico/php.png" alt="PHP" width="32" height="32" />
					<br /><strong><?php echo $nome; ?></strong>
					<br /><br />
					<table class="tabla_info" summary="">
						<tr>
							<td><?php echo $conf->t('peso'); ?>:&nbsp;</td>
							<td><strong>&nbsp;<?php echo PFN_peso(PFN_espacio_disco($stat['size'])); ?></strong></td>
						</tr>
						<tr>
							<td><?php echo $conf->t('Xpeso_exacto'); ?>:&nbsp;</td>
							<td><strong>&nbsp;<?php echo PFN_espacio_disco($stat['size'], true); ?> Bytes</strong></td>
						</tr>
						<tr>
							<td><?php echo $conf->t('Xmodificado'); ?>:&nbsp;</td>
							<td><strong>&nbsp;<?php echo date($conf->g('data'), $stat['mtime']); ?></strong></td>
						</tr>
						<tr>
							<td><?php echo $conf->t('Xuid'); ?>:&nbsp;</td>
							<td><strong>&nbsp;<?php echo $stat['uid']; ?></strong></td>
						</tr>
						<tr>
							<td><?php echo $conf->t('Xgid'); ?>:&nbsp;</td>
							<td><strong>&nbsp;<?php echo $stat['gid']; ?></strong></td>
						</tr>
					</table>

					<script type="text/javascript"><!--

					<?php if ($eliminar && $vale) { ?>
					function eliminar () {
						if (confirm(HtmlDecode('<?php echo addslashes($conf->t('Xeliminar_conf')); ?>'))) {
							enlace('eliminar.php?<?php echo PFN_cambia_url('id_conf', $id_conf, false); ?>');
						}
					}
					<?php } if ($vale) { ?>
					function duplicar () {
						nome = prompt(HtmlDecode('<?php echo addslashes($conf->t('Xnovo_nome')); ?>'));

						if (nome != '' && nome != null) {
							enlace('duplicar.php?novo='+nome+'&amp;<?php echo PFN_cambia_url('id_conf', $id_conf, false); ?>');
						}
					}
					<?php } ?>

					//--></script>
					<br />
					<?php if ($existe_arq) { ?>
					<input type="button" value=" <?php echo $conf->t('ver_contido'); ?> " class="boton" onclick="enlace('ver.php?<?php echo PFN_cambia_url('id_conf', $id_conf, false); ?>');" /><br /><br />
					<input type="button" value=" <?php echo $conf->t('Xcomprobar_sintaxis'); ?> " class="boton" onclick="window.open('sintaxis.php?<?php echo PFN_cambia_url('id_conf', $id_conf, false); ?>', 'Sintaxis', 'width=480,height=400,toolbar=no,fullscreen=no,location=no,directories=no,status=no,menubar=no,resizable=yes');" /><br /><br />
					<?php if ($editar) { ?>
					<input type="button" value=" <?php echo $conf->t('editar'); ?> " class="boton" onclick="enlace('modi.php?<?php echo PFN_cambia_url('id_conf', $id_conf, false); ?>');" /><br /><br />
					<?php } if ($vale) { ?>
					<input type="button" value=" <?php echo $conf->t('Xduplicar'); ?> " class="boton" onclick="duplicar();" /><br /><br />
					<?php } } if ($eliminar && $vale) { ?>
					<input type="button" value=" <?php echo $conf->t('eliminar'); ?> " class="boton" onclick="eliminar();" /><br /><br />
					<?php } ?>
				</td>
				<td valign="top">
				<?php if ($usuarios->init('configuracion_grupos', $id_conf)) { ?>
					<strong><?php echo $conf->t('Xgrupos_relacionados'); ?>: </strong>
					<hr noshade="noshade" />
					<?php for (; $usuarios->mais(); $usuarios->seguinte()) { ?>
					<a href="../grupos/index.php?<?php echo PFN_cambia_url('id_grupo', $usuarios->get('id'), false); ?>" class="ao12"><?php echo $usuarios->get('nome'); ?></a><br />
					<?php } ?>
					<br />
				<?php } ?>
				<?php if ($usuarios->init('configuracion_raices', $id_conf)) { ?>
					<strong><?php echo $conf->t('Xraices_relacionadas'); ?>: </strong>
					<hr noshade="noshade" />
					<?php for (; $usuarios->mais(); $usuarios->seguinte()) { ?>
					<a href="../raices/index.php?<?php echo PFN_cambia_url('id_raiz', $usuarios->get('id_raiz'), false); ?>" class="ao12"><?php echo $usuarios->get('raiz'); ?> (<?php echo $usuarios->get('grupo'); ?>)</a><br />
					<?php } ?>
					<br />
				<?php } ?>
			</tr>
		</table>
		<br /><input type="reset" value=" <?php echo $conf->t('voltar'); ?> " class="boton" onclick="enlace('../index.php?<?php echo PFN_cambia_url('opc', 4, false); ?>');" /><br />
	</div>
</div>
