CREATE TABLE `entries` (
  `id` int(11) NOT NULL auto_increment,
  `name` text collate latin1_general_ci NOT NULL,
  `website` text collate latin1_general_ci NOT NULL,
  `email` text collate latin1_general_ci NOT NULL,
  `message` text collate latin1_general_ci NOT NULL,
  `location` text collate latin1_general_ci NOT NULL,
  `ip` text collate latin1_general_ci NOT NULL,
  `time` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;