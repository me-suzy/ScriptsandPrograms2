ALTER TABLE `pl_links` ADD FULLTEXT (
`website`
);

ALTER TABLE `pl_links` ADD FULLTEXT (
`description`
);

ALTER TABLE `pl_links` ADD FULLTEXT (
`url`
);

INSERT INTO `pl_config` ( `config_name` , `config_value` , `config_help` )
VALUES (
'linktarget', '_parent', 'This allows you to control the target of the links in the directory. Set it to _parent to open them in the current window or _blank to open links in a new window.'
);