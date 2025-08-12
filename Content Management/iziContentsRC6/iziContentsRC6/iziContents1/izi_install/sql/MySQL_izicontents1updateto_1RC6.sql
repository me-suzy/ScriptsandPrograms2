ALTER TABLE `contents` ADD `pdfprint` CHAR( 1 ) DEFAULT 'N' AFTER `printerfriendly` ;
ALTER TABLE `modules` ADD `hascats` char(1) DEFAULT 'Y';
INSERT INTO `settings` ( `settingname` , `cssentry` , `settingvalue` )
VALUES ('PDFIcon', NULL , 'icons/filetypes/pdf.gif' );
ALTER TABLE `contents` ADD `tellfriend` CHAR( 1 ) DEFAULT 'N' AFTER `pdfprint` ;
INSERT INTO `settings` ( `settingname` , `cssentry` , `settingvalue` )
VALUES ('tellfriendicon', NULL , 'icons/email.gif' );
INSERT INTO `filetypes` ( `filetypeid` , `filetype` , `mimetype` , `authorid` , `filecat` , `fileicon` )
VALUES ('', 'zip', 'application/zip', '1', 'Modules', NULL);
UPDATE `filetypes` SET `mimetype` = 'application/zip' WHERE `filetypeid` = '6' LIMIT 1 ;
INSERT INTO `filetypes` ( `filetypeid` , `filetype` , `mimetype` , `authorid` , `filecat` , `fileicon` )
VALUES ('', 'zip', 'application/zip', '1', 'Languages', NULL);