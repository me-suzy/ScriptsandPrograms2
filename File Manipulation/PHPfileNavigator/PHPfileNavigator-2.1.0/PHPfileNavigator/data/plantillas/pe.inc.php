<?php
/****************************************************************************
* data/plantillas/pe.inc.php
*
* plantilla para la visualización del pie de página
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

if (is_object($clases)) {
	$clases->desconectar();
}
?>
	<div id="pe_separador"></div>
</div>
<div id="pe">
	<?php if ($conf->g('usuario','descargas_maximo') || $conf->g('raiz','peso_maximo')) { ?>
	<table id="pe_utiles" summary="">
		<tr>
			<?php if ($conf->g('raiz','peso_maximo') > 0) { ?>
			<td>
				&nbsp;<?php echo $conf->t('peso_restante').': <strong>'.PFN_peso($conf->g('raiz','peso_maximo') - $conf->g('raiz','peso_actual')); ?></strong>
				<?php
				$libre = intval((($conf->g('raiz','peso_maximo') - $conf->g('raiz','peso_actual')) / $conf->g('raiz','peso_maximo')) * 100);
				$cor_libre = ($libre > 50)?'0C0':(($libre > 25)?'FC6':(($libre > 10)?'F60':'F00'));
				?>
				<br /><div style="border: 1px solid #000; width: <?php echo $libre; ?>%; background-color: #<?php echo $cor_libre; ?>; font-weight: bold;"><?php echo $libre; ?>%</div>
			</td>
			<?php } if ($conf->g('usuario','descargas_maximo') > 0) { ?>
			<td>
				&nbsp;<?php echo $conf->t('descargas_restante').': <strong>'.PFN_peso($conf->g('usuario','descargas_maximo') - $conf->g('usuario','descargas_actual')); ?></strong>
				<?php
				$libre = intval((($conf->g('usuario','descargas_maximo') - $conf->g('usuario','descargas_actual')) / $conf->g('usuario','descargas_maximo')) * 100);
				$cor_libre = ($libre > 50)?'0C0':(($libre > 25)?'FC6':(($libre > 10)?'F60':'F00'));
				?>
				<br /><div style="border: 1px solid #000; width: <?php echo $libre; ?>%; background-color: #<?php echo $cor_libre; ?>; font-weight: bold;"><?php echo $libre; ?>%</div>
			</td>
		<?php } ?>
		</tr>
	</table>
	<?php } else { ?>
	<table id="pe_utiles" summary=""><tr><td style="border: 0;">&nbsp;</td></tr></table>
	<?php } ?>
	<div id="pe_texto"><a href="http://pfn.sourceforge.net/" onkeypress="window.open(this.href, '_blank'); return false" onclick="window.open(this.href, '_blank'); return false"><?php echo $conf->t('PFN').'</a> - '.$conf->t('tempo_execucion').': '.$tempo->total().' '.$conf->t('segundos'); ?></div>
</div>
<!--

Tempos de execucion:

<?php echo $tempo->dump(); ?>

//-->
</body>
</html>
