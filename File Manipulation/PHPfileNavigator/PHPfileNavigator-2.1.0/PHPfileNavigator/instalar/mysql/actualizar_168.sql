ALTER IGNORE TABLE accesos modify login varchar(50) NOT NULL default '';
ALTER IGNORE TABLE accesos modify data int(10) unsigned NOT NULL default '0';
ALTER IGNORE TABLE accesos add ultimo int(10) unsigned NOT NULL default '0';

ALTER IGNORE TABLE arquivos modify id int(10) unsigned NOT NULL auto_increment;
ALTER IGNORE TABLE arquivos modify id_directorio int(10) unsigned NOT NULL default '0';

ALTER IGNORE TABLE arquivos_campos_palabras modify id_arquivo int(10) unsigned NOT NULL default '0';
ALTER IGNORE TABLE arquivos_campos_palabras modify id_palabra int(10) unsigned NOT NULL default '0';

ALTER IGNORE TABLE campos modify id smallint(6) unsigned NOT NULL auto_increment;

DROP TABLE IF EXISTS configuracions;
CREATE TABLE IF NOT EXISTS configuracions (
  id smallint(6) unsigned NOT NULL auto_increment,
  conf varchar(50) NOT NULL default '',
  vale tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS configuracions_datos;
CREATE TABLE IF NOT EXISTS configuracions_datos (
  campo varchar(50) NOT NULL default '',
  valor varchar(250) NOT NULL default '',
  id_conf smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (id_conf,campo)
) TYPE=MyISAM;

ALTER IGNORE TABLE directorios modify id int(10) unsigned NOT NULL auto_increment;
ALTER IGNORE TABLE directorios change id_path id_raiz smallint(6) unsigned NOT NULL default '0';

DROP TABLE IF EXISTS grupos;
CREATE TABLE IF NOT EXISTS grupos (
  id smallint(6) unsigned NOT NULL auto_increment,
  nome varchar(50) NOT NULL default '',
  id_conf smallint(6) unsigned NOT NULL default '0',
  estado tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

ALTER IGNORE TABLE palabras modify id int(10) unsigned NOT NULL auto_increment;

ALTER IGNORE TABLE raices modify id smallint(6) unsigned NOT NULL auto_increment;
ALTER IGNORE TABLE raices add mantemento date NOT NULL default '0000-00-00';
ALTER IGNORE TABLE raices add peso_maximo bigint(20) unsigned NOT NULL default '0';
ALTER IGNORE TABLE raices add peso_actual bigint(20) unsigned NOT NULL default '0';
ALTER IGNORE TABLE raices drop conf;

DROP TABLE IF EXISTS raices_grupos_configuracions;
CREATE TABLE IF NOT EXISTS raices_grupos_configuracions (
  id_raiz smallint(6) unsigned NOT NULL default '0',
  id_grupo smallint(6) unsigned NOT NULL default '0',
  id_conf smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (id_raiz,id_grupo)
) TYPE=MyISAM;

ALTER IGNORE TABLE raices_usuarios modify id_raiz smallint(6) unsigned NOT NULL default '0';
ALTER IGNORE TABLE raices_usuarios modify id_usuario smallint(6) unsigned NOT NULL default '0';

ALTER IGNORE TABLE usuarios modify id smallint(6) unsigned NOT NULL auto_increment;
ALTER IGNORE TABLE usuarios add email varchar(100) NOT NULL default '';
ALTER IGNORE TABLE usuarios add id_grupo smallint(6) unsigned NOT NULL default '0';
ALTER IGNORE TABLE usuarios add mantemento date NOT NULL default '0000-00-00';
ALTER IGNORE TABLE usuarios add descargas_maximo bigint(20) unsigned NOT NULL default '0';
