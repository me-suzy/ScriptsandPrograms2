<?php
//
//  inc_footer.php
//	rev003
//
?>
<BR><CENTER><SPAN class='minitexto0'><b><?php echo $gTopCopyright; ?></b>
<!-- Before remove this line you must think that TOPo is a free script -->
<!-- and the only way to promote it is showing this link at the bottom of web pages like yours -->
<!-- That´s why if you like TOPo and you want support it helping to improve its quality, you must keep this line. -->
<BR>Powered by <a href="http://ej3soft.ej3.net" target="_blank"><b>TOPo v<?php echo $gVer; ?></b></a>
<!-- Thank you very much -->
<?
if($gRendimiento) {
	$db_numConsultas=0;
	$db_segConsultas=0;
	if(isset($categorias)) { $db_numConsultas+=$categorias->numConsultas; $db_segConsultas+=$categorias->segConsultas; }
	if(isset($indice)) { $db_numConsultas+=$indice->numConsultas; $db_segConsultas+=$indice->segConsultas; }
	if(isset($online)) { $db_numConsultas+=$online->numConsultas; $db_segConsultas+=$online->segConsultas; }
	if(is_a($web,'SitioWebAvanzado')) { $db_numConsultas+=$web->numConsultas; $db_segConsultas+=$web->segConsultas; }
	echo '<br>{PERFORMANCE}';
}
?>
</SPAN></CENTER>
</body>
</html>




















