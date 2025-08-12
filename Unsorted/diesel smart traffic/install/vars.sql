INSERT INTO sysvars VALUES ('','Email cost per member emailed','emailcpmem','50','ppemail');
INSERT INTO sysvars VALUES ('','Email cost per hit','emailcphit','100','ppemail');
INSERT INTO sysvars VALUES ('','Email sender address','PAID_MAIL','admin@yoursite.com','ppemail');
INSERT INTO sysvars VALUES ('','Admin notification sender address','ADM_MAIL','admin@yoursite.com','ppemail');

INSERT INTO sysvars VALUES ('','IP fraud check in seconds (no points for user from the same IP within a certain time 
range)','ipfseconds','3','security');
INSERT INTO sysvars VALUES ('','IP startpage fraud check in seconds (no points for user from the same IP within a certain time 
range)','ipsfseconds','10','security');

INSERT INTO sysvars VALUES ('','Delay before safelist member can email again (days)','safelist_mem_days','3','safelist');
INSERT INTO sysvars VALUES ('','Advertiser cost to send to each email on other safelists','adv_cost_per_email','50','safelist');


INSERT INTO sysvars VALUES ('','New members need to be approved (all members start disabled)','requireapproval','0','register');
INSERT INTO sysvars VALUES ('','New members start as trusted members','newm_trusted','0','register');
INSERT INTO sysvars VALUES ('','New members start as expirable','newm_expirable','1','register');
INSERT INTO sysvars VALUES ('','New members require approval for campaigns','newm_approval','1','register');
INSERT INTO sysvars VALUES ('','New members start as free members','newm_free','1','register');

INSERT INTO sysvars VALUES ('','Table header color','color_head','#D3D9EB','color');
