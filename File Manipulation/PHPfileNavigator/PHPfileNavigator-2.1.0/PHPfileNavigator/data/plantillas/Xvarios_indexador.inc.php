<?php
/****************************************************************************
* data/plantillas/Xvarios_indexador.inc.php
*
* plantilla para la visualización de la sección de indexador dentro de
* la administración de Varios
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
		<div id="capa_indexador">
			<h2><?php echo $conf->t('Xreindexar'); ?></h2>

			<?php if ($executa == 'indexador') { ?>
			<div class="aviso">
				<?php
				if (count($erros)) {
					foreach ($erros as $v) {
						echo $conf->t('Xerros',$v).'<br />';
					}
				} else {
					echo $conf->t('Xok_reindexar').' '.$indexador->cnt('dir').' '.$conf->t('dirs')
						.' | '.$indexador->cnt('arq').' '.$conf->t('arqs').'<br /><br />'
						.'<a href="#" onclick="amosa_axuda(\'detalle_indexador\');">'
						.$conf->t('Xver_detalle').'</a>'
						.'<div id="detalle_indexador" style="display: none;"><pre>'.$txt.'</pre></div>';
				}
				?>
			</div>
			<?php } ?>

			<form action="index.php?<?php echo PFN_get_url(false); ?>" method="post" onsubmit="return submitonce();">
			<fieldset>
			<input type="hidden" name="executa" value="indexador" />

			<table class="tabla_info" summary="">
				<caption><?php echo $conf->t('Xreindexar_info'); ?></caption>
				<tr>
					<td><strong><?php echo $conf->t('Xconfirmar_reindexar'); ?></strong></td>
					<td>
						<strong><label for="indexador_id_raiz"><?php echo $conf->t('Xescolle_raiz'); ?>:</label></strong>

						<select id="indexador_id_raiz" name="indexador_id_raiz">
							<?php
							foreach ($raices as $k => $v) {
								echo '<option value="'.$k.'"'.(($k == $indexador_id_raiz)?' selected="selected"':'').'>'.$v.'</option>';
							}
							?>
						</select>
					</td>
					<td><input type="submit" value=" <?php echo $conf->t('enviar'); ?> " /></td>
				</tr>
			</table>

			</fieldset>
			</form>
		</div>
