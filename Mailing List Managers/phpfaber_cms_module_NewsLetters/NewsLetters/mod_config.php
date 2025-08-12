<?php

// -----------------------------------------------------------------------------
//
// phpFaber CMS v.1.0
// Copyright(C), phpFaber LLC, 2004-2005, All Rights Reserved.
// E-mail: products@phpfaber.com
//
// All forms of reproduction, including, but not limited to, internet posting,
// printing, e-mailing, faxing and recording are strictly prohibited.
// One license required per site running phpFaber CMS.
// To obtain a license for using phpFaber CMS, please register at
// http://www.phpfaber.com/i/products/cms/
//
// 12:21 AM 09/23/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

$_MOD = array(
  'name'  => 'Newsletters',
  'descr' => 'Allows you to send your emails to your subscribers.',
);

$_MOD_SUBPG = array(
  'setup'     => '_HEADER_MODULE_SETTINGS',
  'ml_manage' => '_HEADER_ML_MANAGE',
  'nl_add'    => '_HEADER_NL_ADD',
  'nl_log'    => '_HEADER_NL_LOG',
);

$_MOD_CFG = array(
  'SUBS_NUM'  => 50,
  'NL_NUM'    => 10,
  'EN_M_CRON' => 1,
);

$_MOD_TBL = array(

  'list'       => "CREATE TABLE list (
                    id int(11) NOT NULL auto_increment,
                    name varchar(100) NOT NULL,
                    bottom_text text NOT NULL,
                    descr text NOT NULL,
                    PRIMARY KEY (id))",

  'subscriber' => "CREATE TABLE subscriber (
                    id int(11) unsigned NOT NULL auto_increment,
                    list_id int(11) unsigned DEFAULT '0' NOT NULL,
                    name varchar(255) NOT NULL,
                    email varchar(50) NOT NULL,
                    date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY (id),
                    UNIQUE (list_id, email))",

  'pending'     => "CREATE TABLE pending (
                    list_id int(11) unsigned DEFAULT '0' NOT NULL,
                    name varchar(255) NOT NULL,
                    email varchar(50) NOT NULL,
                    code varchar(20) NOT NULL,
                    date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY (list_id, email))",

  'letter'     => "CREATE TABLE letter (
                    id int(11) unsigned NOT NULL auto_increment,
                    list_id int(11) DEFAULT '0' NOT NULL,
                    date date DEFAULT '0000-00-00' NOT NULL,
                    is_html CHAR (1) not null,
                    subj varchar(255) NOT NULL,
                    content text NOT NULL,
                    amount int(11) unsigned DEFAULT '0' NOT NULL,
                    status tinyint(3) unsigned DEFAULT '0' NOT NULL,
                    PRIMARY KEY (id),
                    KEY (list_id))",

);

$_MOD_EXTRA_SQL = array(

  "INSERT INTO list VALUES
   ('1', 'General Info & Updates', ' To subscribe or unsubscribe via the World Wide Web, \n visit ##unsub_url##', 'General Information, Announces & Site Updates')",

);


?>
