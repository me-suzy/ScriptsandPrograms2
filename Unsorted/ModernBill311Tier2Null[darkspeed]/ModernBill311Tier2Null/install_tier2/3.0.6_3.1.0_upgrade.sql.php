<ul>
<? // 3.0.6 to 3.0.8 UPGRADE INSTALL

$stop=NULL;

mysql_query("ALTER TABLE client_package ADD aff_code VARCHAR(255)");
mysql_query("ALTER TABLE client_package ADD aff_last_paid INT(11)");
mysql_query("ALTER TABLE affiliate_config ADD aff_pay_time INT(5) NOT NULL AFTER aff_count");
mysql_query("ALTER TABLE affiliate_config ADD aff_pay_amount DECIMAL(10,2) NOT NULL AFTER aff_pay_time");
mysql_query("ALTER TABLE affiliate_config ADD aff_pay_cycle INT(11) NOT NULL AFTER aff_pay_type");
mysql_query("CREATE TABLE client_news (
                          ID bigint(255) NOT NULL default '0',
                          Subject text NOT NULL,
                          Post_user text NOT NULL,
                          Post_email text NOT NULL,
                          Date text NOT NULL,
                          Time text NOT NULL,
                          Headline_date text NOT NULL,
                          Date_time text NOT NULL,
                          Text text NOT NULL,
                          Modify_date text NOT NULL,
                          Modify_user text NOT NULL,
                          mainpage enum('N','Y') NOT NULL default 'N',
                          mainid int(255) NOT NULL default '0'
                          ) TYPE=MyISAM");
                          
if (mysql_query("CREATE TABLE sessions (id varchar(50) NOT NULL default '',data mediumtext NOT NULL,t_stamp timestamp(14) NOT NULL,PRIMARY KEY (id),KEY t_stamp (t_stamp))")) {
    echo "<li> <font color=blue>The \"sessions\" table was added successfully.</font>";
} else {
    echo "<li> <font color=red>The \"sessions\" table was NOT added successfully.</font>";
}

if (mysql_query("ALTER TABLE config ADD config_51 VARCHAR(255),ADD config_52 VARCHAR(255),ADD config_53 VARCHAR(255),ADD config_54 VARCHAR(255),ADD config_55 VARCHAR(255),ADD config_56 VARCHAR(255),ADD config_57 VARCHAR(255),ADD config_58 VARCHAR(255),ADD config_59 VARCHAR(255),ADD config_60 VARCHAR(255)")) {
    echo "<li> <font color=blue>The \"config\" table was updated successfully.</font>";
} else {
    echo "<li> <font color=red>The \"config\" table was NOT updated successfully.</font>";
}
?>
</ul>