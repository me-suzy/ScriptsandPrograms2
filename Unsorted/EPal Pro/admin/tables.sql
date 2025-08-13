CREATE TABLE control (
  id double NOT NULL auto_increment,
  user varchar(50) NOT NULL default '',
  pass varchar(50) NOT NULL default '',
  admin_cookie varchar(100) NOT NULL default '',
  about_text text NOT NULL,
  fees_text text NOT NULL,
  legal_text text NOT NULL,
  privacy_text text NOT NULL,
  contact_text text NOT NULL,
  send_money_text text NOT NULL,
  sell_text text NOT NULL,
  shipping_text text NOT NULL,
  help_text text NOT NULL,
  paypal int(11) NOT NULL default '0',
  paypal_addy varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO control VALUES ('1', 'admin', 'admin', '1m5mrmn3h6sa09gjt8cjd6esw78fkj', 'About Us', 'Pricing and Shipping', 'Legal Disclaimer', 'Privacy Statement', 'Contact Information', 'Send Money Information', 'Sell Information', 'Pricing And Shipping Info', 'Help Text', 1, 'nixiak@singnet.com.sg');

CREATE TABLE transactions (
  id double NOT NULL auto_increment,
  base_amount double NOT NULL default '0',
  base_fee double NOT NULL default '0',
  base_shipping double NOT NULL default '0',
  base_total double NOT NULL default '0',
  ship_method varchar(50) NOT NULL default '',
  r_name varchar(100) NOT NULL default '',
  r_address varchar(100) NOT NULL default '',
  r_city varchar(25) NOT NULL default '',
  r_state varchar(25) NOT NULL default '',
  r_zip varchar(10) NOT NULL default '',
  r_country varchar(15) NOT NULL default '',
  r_email varchar(100) NOT NULL default '',
  s_name varchar(100) NOT NULL default '',
  s_email varchar(100) NOT NULL default '',
  s_address varchar(100) NOT NULL default '',
  s_city varchar(25) NOT NULL default '',
  s_state varchar(25) NOT NULL default '',
  s_zip varchar(15) NOT NULL default '',
  s_country varchar(15) NOT NULL default '',
  s_phone varchar(15) NOT NULL default '',
  o_auction_site varchar(100) NOT NULL default '',
  o_item_num varchar(50) NOT NULL default '',
  o_id varchar(50) NOT NULL default '',
  o_description text NOT NULL,
  pp_status varchar(25) NOT NULL default '',
  pp_date varchar(25) NOT NULL default '',
  pp_amount double NOT NULL default '0',
  pp_fee double NOT NULL default '0',
  pp_trans_id varchar(100) NOT NULL default '',
  time double NOT NULL default '0',
  PRIMARY KEY  (id,id)
) TYPE=MyISAM;
    
    