<?php
return array(
	10 => 'INSERT INTO '.$db_prefixo.'grupos'
		.' (id,nome,id_conf,estado) VALUES'
		.' (1,"'.$conf->t('admins').'",3,1),(2,"'.$conf->t('usuarios').'",3,1);',
	20 => 'INSERT INTO '.$db_prefixo.'configuracions'
		.' (id,conf,vale) VALUES'
		.' (1,"basicas",0), (2,"login",0), (3,"default",1);',
);
?>
