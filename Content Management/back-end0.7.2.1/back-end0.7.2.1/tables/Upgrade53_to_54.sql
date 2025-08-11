# 09June03:mg
# Adding version number to database to assist future upgrades
#

INSERT into psl_variable (variable_id, variable_name, value, description, variable_group) values (100, "BE_Version", "0.5.4", "Back-End Version Number", "");
UPDATE db_sequence SET nextid=101 WHERE seq_name='psl_variable_seq';

# 11June03:mg
# Adding sitemap section to control look/feel of the sitemap page
#

INSERT INTO be_sections VALUES (3, 'sitemap', 1, '1041835651', '1041835651', '1041835651', '1830775651', '', '1', '', 1, 0, 0, 0, 30, 1, '', '1');
INSERT INTO be_section2section VALUES (1, 3);
INSERT INTO be_sectionText VALUES (3, 3, 'en', 'Site Map', '', 'Site Map Control', 'Site Map Control', '', 'Admin', '', '', NULL, '', 0, 22);

UPDATE db_sequence SET nextid=4 WHERE seq_name='be_sections';