DELETE FROM menus WHERE 1;

INSERT INTO menus VALUES ('','Members','','N');
INSERT INTO menus VALUES ('','Post news and notifications','post.php','N');
INSERT INTO menus VALUES ('','Statistics','stats.php','N');
INSERT INTO menus VALUES ('','Search members','searchmembers.php','N');
INSERT INTO menus VALUES ('','Free members ','engine.php?spec=members&mem_type=free','N');
INSERT INTO menus VALUES ('','Advertisers','engine.php?spec=members&mem_type=advertiser','N');
INSERT INTO menus VALUES ('','Startup Settings','sqledit.php?table=sysvars&conditions=where vtype=\'register\'','N');
INSERT INTO menus VALUES ('','Automate settings','automate.php','N');


INSERT INTO menus VALUES ('','Portal','','N');
INSERT INTO menus VALUES ('','Site groups','engine.php?spec=groups','N');
INSERT INTO menus VALUES ('','Websites registered (campaigns)','engine.php?spec=campaigns','N');
INSERT INTO menus VALUES ('','Banners in the system','engine.php?spec=banners','N');

INSERT INTO menus VALUES ('','Marketing and Exchange','','N');
INSERT INTO menus VALUES ('','Process payments/withdrawals','process.php','N');
INSERT INTO menus VALUES ('','Payment modes','engine.php?spec=payment_modes','N');
INSERT INTO menus VALUES ('','Email members','spam.php','N');
INSERT INTO menus VALUES ('','Default urls','dban.php','N');
INSERT INTO menus VALUES ('','Default credits','def_credits.php','N');
INSERT INTO menus VALUES ('','Affiliate settings','def_affi.php','N');
INSERT INTO menus VALUES ('','Default exchange rate','def_rate.php','N');
INSERT INTO menus VALUES ('','Pay per email settings','sqledit.php?table=sysvars&conditions=where vtype=\'ppemail\'','N');

INSERT INTO menus VALUES ('','System and Security','','N');
INSERT INTO menus VALUES ('','Clean database','clean.php','N');
INSERT INTO menus VALUES ('','Settings','sqledit.php?table=sysvars','N');
INSERT INTO menus VALUES ('','Ban Traffic','sqledit.php?table=banns','N');

INSERT INTO menus VALUES ('','Layout and Design','','N');
INSERT INTO menus VALUES ('','Template : Member Tools','template.php?fname=../clients/tools','N');
INSERT INTO menus VALUES ('','Template : Top','template.php?fname=../tpl/top.ihtml','N');
INSERT INTO menus VALUES ('','Template : Bottom','template.php?fname=../tpl/bottom.ihtml','N');
INSERT INTO menus VALUES ('','Template : Clients Top','template.php?fname=../tpl/clients_top.ihtml','N');
INSERT INTO menus VALUES ('','Template : Clients Bottom','template.php?fname=../tpl/clients_bottom.ihtml','N');
INSERT INTO menus VALUES ('','Template : Register email','template.php?fname=../tpl/register.mtl','N');
INSERT INTO menus VALUES ('','Template : Forgot email','template.php?fname=../tpl/forgot.mtl','N');
INSERT INTO menus VALUES ('','Template : Top Frame Exchange','template.php?fname=../tpl/top_frame.php','N');
INSERT INTO menus VALUES ('','Template : Home Page','template.php?fname=../src/default','N');
INSERT INTO menus VALUES ('','Website CSS Styles','template.php?fname=../style.css','N');
