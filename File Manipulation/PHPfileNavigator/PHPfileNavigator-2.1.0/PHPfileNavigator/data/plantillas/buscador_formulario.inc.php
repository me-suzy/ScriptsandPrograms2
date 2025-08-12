<?php
/****************************************************************************
* data/plantillas/buscador_formulario.inc.php
*
* plantilla para la visualización de la acción de buscar un fichero o directorio
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
<script type="text/javascript"><!--

function desmarca_campo (cal) {
	formu = MM_findObj('formulario');
	
	for (i=0; i < formu.length; i++) {
		if (formu.elements[i].name == "campos_buscar[]") {
			if (cal == "nome") {
				if (formu.elements[i].value == "nome" ) {
					formu.elements[i].checked = false;
				}
			} else {
				if (formu.elements[i].value != "nome" ) {
					formu.elements[i].checked = false;
				}
			}
		}
	}
}

//--></script>

<div id="ver_info">
	<div class="bloque_info"><h1><?php echo $conf->t('accion').' &raquo; '.$conf->t('buscador'); ?></h1></div>
	<div class="bloque_info">
		<form action="accion.php?<?php echo PFN_get_url(false); ?>" method="post" id="formulario" onsubmit="return submitonce();">
		<fieldset>
		<input type="hidden" name="accion" value="buscador" />
		<input type="hidden" name="executa" value="true" />

		<table class="tabla_info" summary="">
			<tr>
				<td><label for="palabra_buscar_1"><?php echo $conf->t('buscar'); ?></label></td>
				<td><input type="text" id="palabra_buscar_1" name="palabra_buscar" value="<?php echo $vars->post('palabra_buscar'); ?>" style="width: 25em;" /></td>
			</tr>
			<tr>
				<td><label for="donde_buscar_1"><?php echo $conf->t('donde'); ?></label></td>
				<td>
					<select id="donde_buscar_1" name="donde_buscar">
						<option value="" <?php echo !$vars->post('donde_buscar')?'selected="selected"':''; ?>><?php echo $conf->t('este_directorio'); ?></option>
						<option value="1" <?php echo $vars->post('donde_buscar')?'selected="selected"':''; ?>><?php echo $conf->t('toda_raiz'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $conf->t('campos'); ?></td>
				<td>
					<table summary="">
						<tr>
							<td>
								<label><input type="checkbox" id="campos_buscar_0" name="campos_buscar[]" value="nome" onclick="desmarca_campo('mais');" onkeypress="desmarca_campo('mais');"<?php echo @in_array('nome', $vars->post('campos_buscar'))?'checked="checked"':''; ?> class="checkbox" /> <?php echo $conf->t('nome'); ?></label>
							</td>
<?php
if ($conf->g('inc','estado')) {
	$cnt = $i = 1;

	foreach ($conf->g('inc','campos_indexar') as $v) {
		if ($cnt == 3) {
			echo '<tr valign="top">';
			$cnt = 0;
		}

		echo '<td>'
			.'<label><input type="checkbox" id="campos_buscar_'.$i
			.'" name="campos_buscar[]" value="'.$v.'" onclick="desmarca_campo(\'nome\');" onkeypress="desmarca_campo(\'nome\');"'
			.(@in_array($v, $vars->post('campos_buscar'))?' checked="checked"':'').' class="checkbox" /> '.$conf->t($v)
			.'</label></td>';

		$cnt++;

		if ($cnt == 3) {
			echo '</tr>';
		}

		$i++;
	}
}
?>
					</table>
				</td>
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
	</div>
</div>
