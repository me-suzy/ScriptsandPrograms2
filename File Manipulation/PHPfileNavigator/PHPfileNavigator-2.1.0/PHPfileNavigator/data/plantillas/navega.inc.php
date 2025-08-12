<?php
/*******************************************************************************
* data/plantillas/navega.inc.php
*
* plantilla para la visualización de la pantalla principal de navegación
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
<script type="text/javascript"><!--

<?php if ($conf->g('columnas','multiple')) { ?>
function marca_desmarca_multiples () {
	obx_form = MM_findObj('seleccion_multiple');
	ahora = MM_findObj('check_maestro');

	for (b=0; b < obx_form.length; b++) {
		tmpobx = obx_form.elements[b];

		if (tmpobx.name.indexOf('multiple_escollidos[]') == 0) {
			tmpobx.checked = ahora.checked;

			if (ahora.checked) {
				tmpobx.parentNode.parentNode.className = 'trmarcada';
			} else {
				if (document.getElementById) {
					obxtr = tmpobx.parentNode.parentNode;
				} else {
					obxtr = tmpobx.parentElement.parentElement;
				}

				partes = obxtr.id.split('_');

				if (obxtr.id.indexOf('trdir') == 0) {
					obxtr.className = 'trdir'+partes[1];
				} else {
					obxtr.className = 'trarq'+partes[1];
				}
			}
		}
	}
}

function marca_multiple (obx) {
	if (document.getElementById) {
		obxtr = obx.parentNode.parentNode;
	} else {
		obxtr = tmpobx.parentElement.parentElement;
	}

	if (obx.checked) {
		obxtr.className = 'trmarcada';
	} else {
		obxmaestro = MM_findObj('check_maestro');
		partes = obxtr.id.split('_');
		obxmaestro.checked = false;
		obxtr.className = partes[0]+partes[1];
	}
}

function envia_escollidos (accion) {
	ok = false;
	obx_form = MM_findObj('seleccion_multiple');

	for (b=0; b < obx_form.length; b++) {
		tmpobj = obx_form.elements[b];

		if ((tmpobj.name.indexOf('multiple_escollidos[]') == 0) && (tmpobj.checked == true)) {
			ok = true;
			break;
		}
	}

	if (ok && accion != '') {
		if (accion == 'descargar') {
			nome = prompt('<?php echo $conf->t('nome_comprimido') ?>');

			if ((nome == '') || !nome) {
				return false;
			}

			destino = 'accion.php?accion=multiple_'+accion+'&amp;<?php echo PFN_get_url(false); ?>&amp;nome_comprimido='+nome;
		} else {
			destino = 'accion.php?accion=multiple_'+accion+'&amp;<?php echo PFN_get_url(false); ?>';
		}

		obx_form.action = destino.replace(/&amp;/g, '&');
		obx_form.submit();
	}

	return false;
}
<?php } ?>

//--></script>
<?php if ($estado_accion) { ?>
<div class="aviso"><?php echo $estado_accion; ?></div>
<?php } ?>
<?php if ($conf->g('columnas','multiple')) { ?><form id="seleccion_multiple" action="#" method="post" onsubmit="envia_escollidos(); return false;"><?php } ?>
<table id="listado" summary="">
	<thead>
	<tr class="trcab">
		<?php if ($conf->g('columnas','multiple')) { ?>
		<th><input type="checkbox" id="check_maestro" name="check_maestro" onclick="marca_desmarca_multiples();" class="checkbox" /></th>
		<?php } ?>
		<th><a href="<?php echo PFN_cambia_url(array('orde','pos'),array('nome',($pos=='ASC')?'DESC':'ASC')); ?>"><?php echo $conf->t('nome'); ?></a></th>
		<?php if ($conf->g('columnas','tipo')) { ?>
		<th><a href="<?php echo PFN_cambia_url(array('orde','pos'),array('tipo',($pos=='ASC')?'DESC':'ASC')); ?>"><?php echo $conf->t('tipo'); ?></a></th>
		<?php } if ($conf->g('columnas','tamano')) { ?>
		<th><a href="<?php echo PFN_cambia_url(array('orde','pos'),array('tamano',($pos=='ASC')?'DESC':'ASC')); ?>"><?php echo $conf->t('tamano'); ?></a></th>
		<?php } if ($conf->g('columnas','data')) { ?>
		<th><a href="<?php echo PFN_cambia_url(array('orde','pos'),array('data',($pos=='ASC')?'DESC':'ASC')); ?>"><?php echo $conf->t('data'); ?></a></th>
		<?php } if ($conf->g('columnas','permisos')) { ?>
		<th><a href="<?php echo PFN_cambia_url(array('orde','pos'),array('permisos',($pos=='ASC')?'DESC':'ASC')); ?>"><?php echo $conf->t('permisos'); ?></a></th>
		<?php } if ($conf->g('columnas','accions')) { ?>
		<th><a href="<?php echo PFN_cambia_url(array('orde','pos'),array('accions',($pos=='ASC')?'DESC':'ASC')); ?>"><?php echo $conf->t('accions'); ?></a></th>
		<?php } ?>
	</tr>
	</thead>
	<tbody>
	<tr class="trinfo">
		<?php if ($conf->g('columnas','multiple')) { ?>
		<td>&nbsp;</td>
		<?php } ?>
		<?php
		$colspan = 3;
		$colspan -= $conf->g('columnas','tipo')?0:1;
		$colspan -= $conf->g('columnas','tamano')?0:1;
		?>
		<td class="tdnome" colspan="<?php echo $colspan; ?>">
			<?php
			if (($dir == '.') || empty($dir)) {
				$voltar = '';
				$nome_este = $conf->g('raiz','nome');

				echo '<strong>'.$nome_este.'</strong>';
			} else {
				$voltar = dirname($dir);
				$nome_este = array_pop(explode('/',$dir));
			?>
			<a href="navega.php?<?php echo PFN_cambia_url('dir',$voltar,false) ?>"><img src="<?php echo $conf->g('estilo'); ?>imx/arriba.png" alt="<?php echo $conf->t('arriba'); ?>" width="18" height="17" class="icono" /></a>
			<?php
				echo '<strong>'.$nome_este.'</strong>';
			}

			if ($conf->g('inc','estado') && is_object($inc)) {
				echo '<br />'.$inc->crea_listado('dir',180);
			} else {
				echo '&nbsp;';
			}

			?>
		</td>
		<?php if ($conf->g('columnas','data')) { ?>
		<td><?php echo date($conf->g('data'),@filemtime($conf->g('raiz','path').$dir)); ?></td>
		<?php } if ($conf->g('columnas','permisos')) { ?>
		<td><?php echo PFN_permisos(@fileperms($conf->g('raiz','path').$dir)); ?></td>
		<?php
		} if ($conf->g('columnas','accions')) {
			$ucal = (empty($dir) || $dir == '.')?'.':rawurlencode($nome_este);
		?>
		<td>
			<ul class="accions">
				<?php if ($conf->g('permisos','info')) { ?>
				<li class="info"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($voltar,$ucal,'info'),false); ?>" title="<?php echo $conf->t('info'); ?>"><span class="oculto"><?php echo $conf->t('info'); ?></span></a></li>
				<?php } if ($dir != '.' && $dir != './' && !empty($dir)) { ?>
				<?php if ($conf->g('permisos','copiar')) { ?>
				<li class="copiar"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($voltar,$ucal,'copiar'),false); ?>" title="<?php echo $conf->t('copiar'); ?>"><span class="oculto"><?php echo $conf->t('copiar'); ?></span></a></li>
				<?php } if ($conf->g('permisos','mover')) { ?>
				<li class="mover"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($voltar,$ucal,'mover'),false); ?>" title="<?php echo $conf->t('mover'); ?>"><span class="oculto"><?php echo $conf->t('mover'); ?></span></a></li>
				<?php } if ($conf->g('permisos','renomear')) { ?>
				<li class="renomear"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($voltar,$ucal,'renomear'),false); ?>" title="<?php echo $conf->t('renomear'); ?>"><span class="oculto"><?php echo $conf->t('renomear'); ?></span></a></li>
				<?php } if ($conf->g('permisos','eliminar')) { ?>
				<li class="eliminar"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($voltar,$ucal,'eliminar'),false); ?>" title="<?php echo $conf->t('eliminar'); ?>"><span class="oculto"><?php echo $conf->t('eliminar'); ?></span></a></li>
				<?php } if ($conf->g('permisos','permisos')) { ?>
				<li class="permisos"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($voltar,$ucal,'permisos'),false); ?>" title="<?php echo $conf->t('permisos'); ?>"><span class="oculto"><?php echo $conf->t('permisos'); ?></span></a></li>
				<?php } if ($conf->g('permisos','descargar') && $conf->g('permisos','comprimir')) { ?>
				<li class="comprimir"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion','zlib'),array($voltar,$ucal,'descargar',true),false); ?>" title="<?php echo $conf->t('comprimir'); ?>"><span class="oculto"><?php echo $conf->t('comprimir'); ?></span></a></li>
				<?php } } ?>
			</ul>
		</td>
		<?php } ?>
	</tr>
	<?php
	$tempo->rexistra('i:dir');

	foreach ($cada['dir']['nome'] as $k => $v) {
		$on = (($k % 2) == 0)?'1':'0';

		if ($conf->g('inc','estado') && is_object($inc)) {
			$inc->carga_datos($conf->g('raiz','path').$dir.'/'.$v.'/');
			$txt = $inc->crea_listado('dir');
		} else {
			$txt = '';
		}
	?>
	<tr id="trdir_<?php echo $on; ?>_<?php echo $k; ?>" class="trdir<?php echo $on; ?>">
		<?php if ($conf->g('columnas','multiple')) { ?>
		<td><input type="checkbox" id="multiple_escollidos_d<?php echo $k; ?>" name="multiple_escollidos[]" value="<?php echo $v; ?>" class="checkbox" onclick="marca_multiple(this);" /></td>
		<?php } ?>
		<td class="tdnome">
			<a href="<?php echo PFN_cambia_url('dir',"$dir/$v"); ?>">
			<img src="<?php echo $imaxes->icono('dir'); ?>" width="32" height="32" alt="<?php echo $conf->t('dir'); ?>" <?php echo empty($txt)?'style="margin-right: 5px;"':'class="icono"'; ?> />
			<?php echo $v; ?></a>
			<?php echo empty($txt)?'':('<br /><span style="font-weight: normal;">'.$txt.'</span>'); ?>
		</td>
		<?php if ($conf->g('columnas','tipo')) { ?>
		<td>DIR</td>
		<?php } if ($conf->g('columnas','tamano')) { ?>
		<td> - </td>
		<?php } if ($conf->g('columnas','data')) { ?>
		<td><?php echo date($conf->g('data'),$cada['dir']['data'][$k]); ?></td>
		<?php } if ($conf->g('columnas','permisos')) { ?>
		<td><?php echo PFN_permisos($cada['dir']['permisos'][$k]); ?></td>
		<?php } if ($conf->g('columnas','accions')) { ?>
		<td>
			<ul class="accions">
				<?php if ($conf->g('permisos','info')) { ?>
				<li class="info"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'info'),false); ?>" title="<?php echo $conf->t('info'); ?>"><span class="oculto"><?php echo $conf->t('info'); ?></span></a></li>
				<?php } if ($conf->g('permisos','copiar')) { ?>
				<li class="copiar"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'copiar'),false); ?>" title="<?php echo $conf->t('copiar'); ?>"><span class="oculto"><?php echo $conf->t('copiar'); ?></span></a></li>
				<?php } if ($conf->g('permisos','mover')) { ?>
				<li class="mover"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'mover'),false); ?>" title="<?php echo $conf->t('mover'); ?>"><span class="oculto"><?php echo $conf->t('mover'); ?></span></a></li>
				<?php } if ($conf->g('permisos','renomear')) { ?>
				<li class="renomear"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'renomear'),false); ?>" title="<?php echo $conf->t('renomear'); ?>"><span class="oculto"><?php echo $conf->t('renomear'); ?></span></a></li>
				<?php } if ($conf->g('permisos','eliminar')) { ?>
				<li class="eliminar"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'eliminar'),false); ?>" title="<?php echo $conf->t('eliminar'); ?>"><span class="oculto"><?php echo $conf->t('eliminar'); ?></span></a></li>
				<?php } if ($conf->g('permisos','permisos')) { ?>
				<li class="permisos"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'descargar'),false); ?>" title="<?php echo $conf->t('permisos'); ?>"><span class="oculto"><?php echo $conf->t('permisos'); ?></span></a></li>
				<?php } if ($conf->g('permisos','descargar') && $conf->g('permisos','comprimir')) { ?>
				<li class="comprimir"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion','zlib'),array($dir,$v,'descargar',true),false); ?>" title="<?php echo $conf->t('comprimir'); ?>"><span class="oculto"><?php echo $conf->t('comprimir'); ?></span></a></li>
				<?php } ?>
			</ul>
		</td>
		<?php } ?>
	</tr>
	<?php } ?>
	<?php
	$tempo->rexistra('i:arq');

	$i = ($on == 1)?'0':'1';
	$p = ($on == 1)?'1':'0';

	foreach ($cada['arq']['nome'] as $k => $v) {
		$on = (($k % 2) == 0)?$i:$p;
		$ucal = rawurlencode($v);

		if (strstr($v, '.')) {
			$partes = explode('.', $v);
			$ext = array_pop($partes);
		} else {
			$partes = array($v);
			$ext = '';
		}

		if ($conf->g('inc','estado') && is_object($inc)) {
			$inc->carga_datos($conf->g('raiz','path').$dir.'/'.$v);
			$txt = $inc->crea_listado('arq');
		} else {
			$txt = '';
		}
	?>
	<tr id="trarq_<?php echo $on; ?>_<?php echo $k; ?>" class="trarq<?php echo $on; ?>">
		<?php if ($conf->g('columnas','multiple')) { ?>
		<td><input type="checkbox" id="multiple_escollidos_a<?php echo $k; ?>" name="multiple_escollidos[]" value="<?php echo $v; ?>" class="checkbox" onclick="marca_multiple(this);" /></td>
		<?php } ?>
		<td class="tdnome">
			<?php if ($conf->g('permisos','descargar')) { ?>
			<a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'descargar'),false); ?>" onclick="window.open(this.href); return false;">
			<?php } else { ?>
			<a href="<?php echo $niveles->enlace($dir, $v); ?>" onclick="window.open(this.href); return false;">
			<?php } if ($ver_imaxes == true) { ?>
			<img src="<?php echo $imaxes->sello($dir.'/'.$v,true); ?>" alt="<?php echo $conf->t('arq'); ?>" <?php echo empty($txt)?'style="margin-right: 5px;"':'class="icono"'; ?> />
			<?php } else { ?>
			<img src="<?php echo $imaxes->icono($v); ?>" alt="<?php echo $conf->t('arq'); ?>" <?php echo empty($txt)?'style="margin-right: 5px;"':'class="icono"'; ?> />
			<?php } ?>
			<?php echo implode('.', $partes); ?></a>
			<?php echo empty($txt)?'':('<br />'.$txt); ?>
		</td>
		<?php if ($conf->g('columnas','tipo')) { ?>
		<td><?php echo empty($ext)?'&nbsp;':strtoupper($ext); ?></td>
		<?php } if ($conf->g('columnas','tamano')) { ?>
		<td><?php echo PFN_peso($cada['arq']['tamano'][$k]); ?></td>
		<?php } if ($conf->g('columnas','data')) { ?>
		<td><?php echo date($conf->g('data'),$cada['arq']['data'][$k]); ?></td>
		<?php } if ($conf->g('columnas','permisos')) { ?>
		<td><?php echo PFN_permisos($cada['arq']['permisos'][$k]); ?></td>
		<?php } if ($conf->g('columnas','accions')) { ?>
		<td>
			<ul class="accions">
				<?php if ($conf->g('permisos','info')) { ?>
				<li class="info"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'info'),false); ?>" title="<?php echo $conf->t('info'); ?>"><span class="oculto"><?php echo $conf->t('info'); ?></span></a></li>
				<?php } if ($conf->g('permisos','copiar')) { ?>
				<li class="copiar"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'copiar'),false); ?>" title="<?php echo $conf->t('copiar'); ?>"><span class="oculto"><?php echo $conf->t('copiar'); ?></span></a></li>
				<?php } if ($conf->g('permisos','mover')) { ?>
				<li class="mover"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'mover'),false); ?>" title="<?php echo $conf->t('mover'); ?>"><span class="oculto"><?php echo $conf->t('mover'); ?></span></a></li>
				<?php } if ($conf->g('permisos','renomear')) { ?>
				<li class="renomear"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'renomear'),false); ?>" title="<?php echo $conf->t('renomear'); ?>"><span class="oculto"><?php echo $conf->t('renomear'); ?></span></a></li>
				<?php } if ($conf->g('permisos','eliminar')) { ?>
				<li class="eliminar"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'eliminar'),false); ?>"title="<?php echo $conf->t('eliminar'); ?>"><span class="oculto"><?php echo $conf->t('eliminar'); ?></span></a></li>
				<?php } if ($conf->g('permisos','permisos')) { ?>
				<li class="permisos"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion'),array($dir,$v,'permisos'),false); ?>" title="<?php echo $conf->t('permisos'); ?>"><span class="oculto"><?php echo $conf->t('permisos'); ?></span></a></li>
				<?php } if ($conf->g('permisos','descargar')) { ?>
				<li class="descargar"><a href="accion.php?<?php echo PFN_cambia_url(array('dir','cal','accion','modo'),array($dir,$v,'descargar','descargar'),false); ?>;" title="<?php echo $conf->t('descargar'); ?>"><span class="oculto"><?php echo $conf->t('descargar'); ?></span></a></li>
				<?php } ?>
			</ul>
		</td>
		<?php } ?>
	</tr>
	<?php } ?>
	</tbody>
</table>

<div id="utilidades_inferior">
	<div id="paxinar">
		<?php echo PFN_listado_select($cnt_dir+$cnt_arq, $lista, $conf->g('paxinar')); ?>
	</div> 

	<?php if ($conf->g('columnas','multiple')) { ?>
	<ul id="pe_multiple">
		<li><?php echo $conf->t('seleccion'); ?>:</li>
		<?php if ($conf->g('permisos','multiple_copiar')) { ?>
		<li class="copiar"><a href="#" onclick="return envia_escollidos('copiar');" title="<?php echo $conf->t('multiple_copiar'); ?>"><span class="oculto"><?php echo $conf->t('multiple_copiar'); ?></span></a></li>
		<?php } if ($conf->g('permisos','multiple_mover')) { ?>
		<li class="mover"><a href="#" onclick="return envia_escollidos('mover');" title="<?php echo $conf->t('multiple_mover'); ?>"><span class="oculto"><?php echo $conf->t('multiple_mover'); ?></span></a></li>
		<?php } if ($conf->g('permisos','multiple_eliminar')) { ?>
		<li class="eliminar"><a href="#" onclick="return envia_escollidos('eliminar');" title="<?php echo $conf->t('multiple_eliminar'); ?>"><span class="oculto"><?php echo $conf->t('multiple_eliminar'); ?></span></a></li>
		<?php } if ($conf->g('permisos','multiple_permisos')) { ?>
		<li class="permisos"><a href="#" onclick="return envia_escollidos('permisos');" title="<?php echo $conf->t('multiple_permisos'); ?>"><span class="oculto"><?php echo $conf->t('multiple_permisos'); ?></span></a></li>
		<?php } if ($conf->g('permisos','multiple_descargar') && $conf->g('permisos','comprimir')) { ?>
		<li class="comprimir"><a href="#" onclick="return envia_escollidos('descargar');" title="<?php echo $conf->t('multiple_comprimir'); ?>"><span class="oculto"><?php echo $conf->t('multiple_comprimir'); ?></span></a></li>
		<?php } ?>
	</ul>
	<?php } ?>
</div>
<?php if ($conf->g('columnas','multiple')) { ?></form><?php } ?>
<br class="nada" />
<div id="resumo_dir">
	<?php
	echo (($cnt_dir > 0)?($cnt_dir.' '.$conf->t(($cnt_dir == 1)?'dir':'dirs')):'')
		.(($cnt_arq > 0)?((($cnt_dir > 0)?' - ':'').$cnt_arq.' '.$conf->t(($cnt_arq == 1)?'arq':'arqs')):'')
		.(((($cnt_arq > 0) || ($cnt_dir > 0))?' - ':'').' '.$conf->t('peso').': '.$cnt_peso);
	?>
</div>
