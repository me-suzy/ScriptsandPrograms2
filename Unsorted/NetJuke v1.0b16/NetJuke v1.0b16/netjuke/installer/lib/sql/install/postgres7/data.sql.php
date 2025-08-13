<?php

############################################################ 

# Music Inserts

array_push($sql_statements,"INSERT INTO netjuke_artists (name) VALUES ('N/A')");
array_push($sql_statements,"INSERT INTO netjuke_albums (name) VALUES ('N/A')");
array_push($sql_statements,"INSERT INTO netjuke_genres (name) VALUES ('N/A')");

############################################################ 

# Account Inserts

array_push($sql_statements,"INSERT INTO netjuke_groups (name) VALUES ('Administrator')");
array_push($sql_statements,"INSERT INTO netjuke_groups (name) VALUES ('Editor')");
array_push($sql_statements,"INSERT INTO netjuke_groups (name) VALUES ('User')");
array_push($sql_statements,"INSERT INTO netjuke_groups (name) VALUES ('Anonymous')");

array_push($sql_statements,"
  INSERT INTO netjuke_users (email,password,name,gr_id,created,updated,login_cnt) VALUES (
    'admin@temp.admin',
    '12dc7717c371ea3ddfa4eebc474c0abc',
    'Default Administrator',
    1,
    '2002-01-01 00:00:00',
    '2002-01-01 00:00:00',
    '0'
  )
");

array_push($sql_statements,"
  INSERT INTO netjuke_userprefs (
    us_email,bgcolor,text,link,alink,vlink,td_border,
    td_header,td_header_fc,td_content,font_face,font_size, inv_icn
  )
  VALUES (
    'admin@temp.admin','FFFFFF','000000','0000FF','333333','9900CC','666666',
    '9999CC','EEEEEE','EEEEEE','Verdana, Geneva, Arial, Helvetica, sans-serif',11, 'f'
  )
");

############################################################ 

?>