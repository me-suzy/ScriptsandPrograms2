<?php
/****************************************************************************
* data/plantillas/Xmenu.inc.php
*
* plantilla para la visualización de la pantalla inicial de administración
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
	<div class="bloque_info"><h1><?php echo $conf->t('xestion').' &raquo; '.$conf->t('Xmenu_principal'); ?></h1></div>
	<div class="bloque_info">

<?php if (strlen($erros) || ($ok > 0)) { ?>
	<div class="aviso">
	<?php
	if (strlen($erros)) {
		foreach (explode(',', $erros) as $v) {
			echo $conf->t('Xerros', intval($v)).'<br />';
		}
	} else {
		echo $conf->t('Xok', intval($ok));
	}
	?>
	</div>
<?php } ?>

		<ul id="XLIopcions">
			<li id="XLIopcion1"><a href="#" id="Xenlace1" onclick="Xcambia_opcion(1);" onkeypress="Xcambia_opcion(1);"><?php echo $conf->t('Xraices'); ?></a></li>
			<li id="XLIopcion2"><a href="#" id="Xenlace2" onclick="Xcambia_opcion(2);" onkeypress="Xcambia_opcion(2);"><?php echo $conf->t('Xusuarios'); ?></a></li>
			<li id="XLIopcion3"><a href="#" id="Xenlace3" onclick="Xcambia_opcion(3);" onkeypress="Xcambia_opcion(3);"><?php echo $conf->t('Xgrupos'); ?></a></li>
			<li id="XLIopcion4"><a href="#" id="Xenlace4" onclick="Xcambia_opcion(4);" onkeypress="Xcambia_opcion(4);"><?php echo $conf->t('Xconfiguracions'); ?></a></li>
		</ul>

		<div id="XidOpcion1" class="XCapaOpcion"> 
			<form action="gdar.php?<?php echo PFN_get_url(false); ?>" method="post" onsubmit="return submitonce();">
			<fieldset>
			<input type="hidden" name="accion" value="raices" />
			<input type="hidden" name="opc" value="1" />
			<table class="Xmenu" summary="">
				<tr>
					<th><?php echo $conf->t('Xnome'); ?></th>
					<th><?php echo $conf->t('Xestado'); ?></th>
				</tr>
				<?php
				$usuarios->init('raices');

				for ($i = 0; $usuarios->mais(); $usuarios->seguinte(), $i++) {
					$on = (($i % 2) == 0)?'1':'0';
					$id = $usuarios->get('id');
				?>
				<tr class="trarq<?php echo $on; ?>">
					<td>
						<input type="hidden" name="antes[<?php echo $id; ?>]" value="<?php echo $usuarios->get('estado'); ?>" />
						<a href="raices/index.php?<?php echo PFN_cambia_url('id_raiz',$id,false); ?>"><?php echo $usuarios->get('nome'); ?></a>
					</td>
					<td>
						<select id="estado_1_<?php echo $id; ?>" name="estado[<?php echo $id; ?>]">
							<option value="1" <?php echo $usuarios->get('estado')==1?'selected="selected"':''; ?>>ON</option>
							<option value="0" <?php echo $usuarios->get('estado')==0?'selected="selected"':''; ?>>OFF</option>
						</select>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="2" style="text-align: center;"><br /><input type="submit" value="<?php echo $conf->t('Xcambiar'); ?>" class="boton" /></td>
				</tr>
			</table>
			</fieldset>
			</form>
		</div>

		<div id="XidOpcion2" class="XCapaOpcion"> 
			<form action="gdar.php?<?php echo PFN_get_url(false); ?>" method="post" onsubmit="return submitonce();">
			<fieldset>
			<input type="hidden" name="accion" value="usuarios" />
			<input type="hidden" name="opc" value="2" />
			<table class="Xmenu" summary="">
				<tr>
					<th><?php echo $conf->t('Xnome'); ?></th>
					<th><?php echo $conf->t('Xestado'); ?></th>
				</tr>
				<?php
				$usuarios->init('usuarios');

				for ($i = 0; $usuarios->mais(); $usuarios->seguinte(), $i++) {
					$on = (($i % 2) == 0)?'1':'0';
					$id = $usuarios->get('id');
				?>
				<tr class="trarq<?php echo $on; ?>">
					<td>
						<input type="hidden" name="antes[<?php echo $id; ?>]" value="<?php echo $usuarios->get('estado'); ?>" />
						<a href="usuarios/index.php?<?php echo PFN_cambia_url('id_usuario',$id,false); ?>"><?php echo $usuarios->get('nome'); ?></a>
					</td>
					<td>
						<select id="estado_2_<?php echo $id; ?>" name="estado[<?php echo $id; ?>]">
							<option value="1" <?php echo $usuarios->get('estado')==1?'selected="selected"':''; ?>>ON</option>
							<option value="0" <?php echo $usuarios->get('estado')==0?'selected="selected"':''; ?>>OFF</option>
						</select>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="2" style="text-align: center;"><br /><input type="submit" value="<?php echo $conf->t('Xcambiar'); ?>" class="boton" /></td>
				</tr>
			</table>
			</fieldset>
			</form>
		</div>

		<div id="XidOpcion3" class="XCapaOpcion"> 
			<form action="gdar.php?<?php echo PFN_get_url(false); ?>" method="post" onsubmit="return submitonce();">
			<fieldset>
			<input type="hidden" name="accion" value="grupos" />
			<input type="hidden" name="opc" value="3" />
			<table class="Xmenu" summary="">
				<tr>
					<th><?php echo $conf->t('Xnome'); ?></th>
					<th><?php echo $conf->t('Xestado'); ?></th>
				</tr>
				<?php
				$usuarios->init('grupos');

				for ($i = 0; $usuarios->mais(); $usuarios->seguinte(), $i++) {
					$on = (($i % 2) == 0)?'1':'0';
					$id = $usuarios->get('id');
				?>
				<tr class="trarq<?php echo $on; ?>">
					<td>
						<input type="hidden" name="antes[<?php echo $id; ?>]" value="<?php echo $usuarios->get('estado'); ?>" />
						<a href="grupos/index.php?<?php echo PFN_cambia_url('id_grupo',$id,false); ?>"><?php echo $usuarios->get('nome'); ?></a>
					</td>
					<td>
						<select id="estado_3_<?php echo $id; ?>" name="estado[<?php echo $id; ?>]">
							<option value="1" <?php echo $usuarios->get('estado')==1?'selected="selected"':''; ?>>ON</option>
							<option value="0" <?php echo $usuarios->get('estado')==0?'selected="selected"':''; ?>>OFF</option>
						</select>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="2" style="text-align: center;"><br /><input type="submit" value="<?php echo $conf->t('Xcambiar'); ?>" class="boton" /></td>
				</tr>
			</table>
			</fieldset>
			</form>
		</div>

		<div id="XidOpcion4" class="XCapaOpcion"> 
			<table class="Xmenu" summary="">
				<tr>
					<th><?php echo $conf->t('Xconf'); ?></th>
					<th><?php echo $conf->t('Xdetalle'); ?></th>
					<th><?php echo $conf->t('editar'); ?></th>
				</tr>
				<?php
				$usuarios->init('configuracions');

				for ($i = 0; $usuarios->mais(); $usuarios->seguinte(), $i++) {
					$on = (($i % 2) == 0)?'1':'0';
				?>
				<tr class="trarq<?php echo $on; ?>">
					<td>
						<a href="configuracions/index.php?<?php echo PFN_cambia_url('id_conf', $usuarios->get('id'), false); ?>"><?php echo $usuarios->get('conf'); ?></a>
					</td>
					<td>
						<a href="configuracions/ver.php?<?php echo PFN_cambia_url('id_conf', $usuarios->get('id'), false); ?>"><?php echo $conf->t('Xdetalle'); ?></a>
					</td>
					<td>
						<?php if (is_writable($paths['conf'].$usuarios->get('conf').'.inc.php')) { ?>
						<a href="configuracions/modi.php?<?php echo PFN_cambia_url('id_conf', $usuarios->get('id'), false); ?>"><?php echo $conf->t('editar'); ?></a>
						<?php } else { ?>
						&nbsp;
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript"><!--

Xcambia_opcion(<?php echo intval($vars->get('opc'))?intval($vars->get('opc')):1; ?>);

//--></script>
