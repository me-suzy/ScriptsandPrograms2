# phpMyAdmin MySQL-Dump
# version 2.2.6
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Serveur: localhost
# Généré le : Dimanche 26 Décembre 2004 à 18:06
# Version du serveur: 3.23.49
# Version de PHP: 4.2.0
# Base de données: `disco4`
# --------------------------------------------------------

#
# Structure de la table `disco_artistes`
#

CREATE TABLE disco_artistes (
  nom varchar(30) NOT NULL default '',
  id_artiste int(4) NOT NULL auto_increment,
  PRIMARY KEY  (id_artiste),
  UNIQUE KEY nom (nom)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Structure de la table `disco_disques`
#

CREATE TABLE disco_disques (
  artiste varchar(4) NOT NULL default '',
  format varchar(4) NOT NULL default '',
  titre varchar(4) NOT NULL default '',
  id_disque int(4) NOT NULL auto_increment,
  pays varchar(4) NOT NULL default '',
  commentaire mediumtext,
  reference varchar(40) NOT NULL default '',
  date int(4) NOT NULL default '0',
  image varchar(4) NOT NULL default '',
  PRIMARY KEY  (id_disque)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Structure de la table `disco_formats`
#

CREATE TABLE disco_formats (
  id_type int(2) NOT NULL auto_increment,
  type varchar(5) NOT NULL default '',
  des_type varchar(40) NOT NULL default '',
  PRIMARY KEY  (id_type),
  UNIQUE KEY type (type)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Structure de la table `disco_images`
#

CREATE TABLE disco_images (
  id_image int(4) NOT NULL auto_increment,
  imagea varchar(9) NOT NULL default '',
  imageb varchar(9) NOT NULL default '',
  imagec varchar(9) NOT NULL default '',
  PRIMARY KEY  (id_image)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Structure de la table `disco_infos`
#

CREATE TABLE disco_infos (
  id_infos int(4) NOT NULL auto_increment,
  texte longtext NOT NULL,
  date date NOT NULL default '0000-00-00',
  sujet mediumtext NOT NULL,
  image varchar(4) NOT NULL default '',
  PRIMARY KEY  (id_infos)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Structure de la table `disco_pays`
#

CREATE TABLE disco_pays (
  id_pays int(2) NOT NULL auto_increment,
  nom_pays varchar(20) NOT NULL default '',
  abrege char(3) NOT NULL default '',
  PRIMARY KEY  (id_pays),
  UNIQUE KEY nom (nom_pays),
  UNIQUE KEY abrege (abrege)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Structure de la table `disco_titres`
#

CREATE TABLE disco_titres (
  titre text NOT NULL,
  id_titre int(4) NOT NULL auto_increment,
  PRIMARY KEY  (id_titre)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Structure de la table `disco_utilisateurs`
#

CREATE TABLE disco_utilisateurs (
  id_utilisateur int(4) NOT NULL auto_increment,
  nom_utilisateur varchar(10) NOT NULL default '',
  mot_de_passe varchar(10) NOT NULL default '',
  privilege varchar(10) NOT NULL default '',
  level char(1) NOT NULL default '',
  PRIMARY KEY  (id_utilisateur),
  UNIQUE KEY nom (nom_utilisateur)
) TYPE=MyISAM;

