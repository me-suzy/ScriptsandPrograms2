<?php
return array(
	10 => 'INSERT INTO '.$db_prefixo.'raices'
		.' (id,nome,path,web,host,estado) VALUES'
		.' (1,"'.$ra_nome.'","'.$ra_path.'","'.$ra_web.'","'.$ra_host.'",1);',
	20 => 'INSERT INTO '.$db_prefixo.'grupos'
		.' (id,nome,id_conf,estado) VALUES'
		.' (1,"'.$conf->t('admins').'",3,1);',
	30 => 'INSERT INTO '.$db_prefixo.'usuarios'
		.' (id,nome,usuario,contrasinal,email,estado,admin,id_grupo) VALUES'
		.' (1,"'.$ad_nome.'","'.$ad_usuario.'","'.md5($ad_contrasinal).'","'.$email.'",1,1,1);',
	40 => 'INSERT INTO '.$db_prefixo.'configuracions'
		.' (id,conf,vale) VALUES'
		.' (1,"basicas",0), (2,"login",0), (3,"default",1);',
	50 => 'INSERT INTO '.$db_prefixo.'raices_usuarios'
		.' (id_raiz,id_usuario) VALUES'
 		.' (1,1);',
	60 => 'INSERT INTO '.$db_prefixo.'raices_grupos_configuracions'
		.' (id_raiz,id_grupo,id_conf) VALUES'
		.' (1,1,3);',
);
?>
