ALTER TABLE al_hit RENAME al_stats;

ALTER TABLE al_stats DROP affname;

ALTER TABLE al_stats CHANGE aff ref char(16);

ALTER TABLE al_stats ADD clicks smallint(6) NOT NULL default '0';