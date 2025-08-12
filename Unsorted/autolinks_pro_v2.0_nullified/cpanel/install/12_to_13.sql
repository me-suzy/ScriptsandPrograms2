ALTER TABLE website RENAME al_aff;
ALTER TABLE netsite RENAME al_site;
ALTER TABLE redirection RENAME al_redir;
ALTER TABLE image RENAME al_img;
ALTER TABLE hit RENAME al_hit;

ALTER TABLE al_hit CHANGE netsite site char(16);
ALTER TABLE al_hit CHANGE sitelogin aff char(16);
ALTER TABLE al_hit CHANGE sitename affname char(16);

ALTER TABLE al_img CHANGE sitelogin aff char(16);

ALTER TABLE al_redir CHANGE netsite site char(16);
ALTER TABLE al_redir CHANGE sitelogin aff char(16);

ALTER TABLE al_site DROP images;
ALTER TABLE al_site ADD status int(10) NOT NULL default '1';