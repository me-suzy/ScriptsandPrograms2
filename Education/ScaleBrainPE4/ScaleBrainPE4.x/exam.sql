# Table structure for table `book`

DROP TABLE IF EXISTS book;
CREATE TABLE book (
  id int(11) NOT NULL auto_increment,
  book_name varchar(255) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  description varchar(255) NOT NULL default '',
  STATUS char(1) NOT NULL default '',
  creation_dt_time datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
);

# Table structure for table `book_chapter`

DROP TABLE IF EXISTS book_chapter;
CREATE TABLE book_chapter (
  id int(11) NOT NULL auto_increment,
  book_id int(11) NOT NULL default '0',
  chapter_name varchar(255) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  description varchar(255) NOT NULL default '',
  STATUS char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `book_content`

DROP TABLE IF EXISTS book_content;
CREATE TABLE book_content (
  id int(11) NOT NULL auto_increment,
  topic_id int(11) NOT NULL default '0',
  template_id int(11) NOT NULL default '0',
  content mediumtext NOT NULL,
  image_name varchar(255) NOT NULL default '',
  document_name varchar(255) NOT NULL default '',
  status char(1) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  added_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
);

# Table structure for table `book_topic`

DROP TABLE IF EXISTS book_topic;
CREATE TABLE book_topic (
  id int(11) NOT NULL auto_increment,
  chapter_id int(11) NOT NULL default '0',
  topic_name varchar(255) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  description varchar(255) NOT NULL default '',
  STATUS char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `chat_details`

DROP TABLE IF EXISTS chat_details;
CREATE TABLE chat_details (
  id int(11) NOT NULL auto_increment,
  chat_index_id int(11) NOT NULL default '0',
  sender_name varchar(50) NOT NULL default '',
  message_text text NOT NULL,
  time int(11) default NULL,
  PRIMARY KEY  (id)
);

# Table structure for table `chat_index`

DROP TABLE IF EXISTS chat_index;
CREATE TABLE chat_index (
  id int(11) NOT NULL auto_increment,
  client_id int(11) NOT NULL default '0',
  client_session varchar(50) NOT NULL default '',
  op_id int(11) NOT NULL default '0',
  op_session varchar(50) NOT NULL default '',
  status varchar(20) NOT NULL default '',
  lp_timestamp_c int(11) NOT NULL default '0',
  lp_timestamp_o int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

# Table structure for table `chat_operator_status`

DROP TABLE IF EXISTS chat_operator_status;
CREATE TABLE chat_operator_status (
  id int(11) NOT NULL auto_increment,
  op_id int(11) NOT NULL default '0',
  op_session varchar(50) NOT NULL default '',
  status varchar(20) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `color_scheme`

DROP TABLE IF EXISTS color_scheme;
CREATE TABLE color_scheme (
  id int(11) NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  corporate_id varchar(15) NOT NULL default '',
  added_by varchar(15) NOT NULL default '',
  page_bg varchar(15) NOT NULL default '',
  page_fg varchar(15) NOT NULL default '',
  page_img varchar(150) NOT NULL default '',
  hf_bg varchar(15) NOT NULL default '',
  hf_fg varchar(15) NOT NULL default '',
  hf_line varchar(15) NOT NULL default '',
  box_h_bg varchar(15) NOT NULL default '',
  box_h_fg varchar(15) NOT NULL default '',
  box_h_line varchar(15) NOT NULL default '',
  box_bg varchar(15) NOT NULL default '',
  box_fg varchar(15) NOT NULL default '',
  leftmenu_bg varchar(15) NOT NULL default '',
  leftmenu_fg varchar(15) NOT NULL default '',
  right_panel_bar_bg varchar(15) NOT NULL default '',
  right_panel_bar_fg varchar(15) NOT NULL default '',
  css_file_name varchar(255) NOT NULL default '',
  using_default char(1) NOT NULL default '',
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `color_scheme`

INSERT INTO color_scheme VALUES (1, 'default', '', '2', 'FFFFFF', '000000', '', '004785', 'FFFFFF', 'EAEAEA', 'DBDBDB', '000000', 'DBDBDB', 'F2F2F2', 'FFEBCD', 'F2F2F2', '000080', '999999', 'FFFFFF', '', 'y', 'a');

# Table structure for table `colors`

DROP TABLE IF EXISTS colors;
CREATE TABLE colors (
  id int(11) NOT NULL auto_increment,
  color_code varchar(15) NOT NULL default '',
  color_name varchar(50) NOT NULL default '',
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `colors`

INSERT INTO colors VALUES (1, 'F0F8FF', 'aliceblue', 'a');
INSERT INTO colors VALUES (2, 'FAEBD7', 'antiquewhite', 'a');
INSERT INTO colors VALUES (3, '00FFFF', 'aqua', 'a');
INSERT INTO colors VALUES (4, '7FFFD4', 'aquamarine', 'a');
INSERT INTO colors VALUES (5, 'F0FFFF', 'azure', 'a');
INSERT INTO colors VALUES (6, 'F5F5DC', 'beige', 'a');
INSERT INTO colors VALUES (7, 'FFE4C4', 'bisque', 'a');
INSERT INTO colors VALUES (8, '000000', 'black', 'a');
INSERT INTO colors VALUES (9, 'FFEBCD', 'blanchedalmond', 'a');
INSERT INTO colors VALUES (10, '0000FF', 'blue', 'a');
INSERT INTO colors VALUES (11, '8A2BE2', 'blueviolet', 'a');
INSERT INTO colors VALUES (12, 'A52A2A', 'brown', 'a');
INSERT INTO colors VALUES (13, 'DEB887', 'burlywood', 'a');
INSERT INTO colors VALUES (14, '5F9EA0', 'cadetblue', 'a');
INSERT INTO colors VALUES (15, '7FFF00', 'chartreuse', 'a');
INSERT INTO colors VALUES (16, 'D2691E', 'chocolate', 'a');
INSERT INTO colors VALUES (17, 'FF7F50', 'coral', 'a');
INSERT INTO colors VALUES (18, '6495ED', 'cornflowerblue', 'a');
INSERT INTO colors VALUES (19, 'FFF8DC', 'cornsilk', 'a');
INSERT INTO colors VALUES (20, 'DC143C', 'crimson', 'a');
INSERT INTO colors VALUES (21, '00FFFF', 'cyan', 'a');
INSERT INTO colors VALUES (22, '00008B', 'darkblue', 'a');
INSERT INTO colors VALUES (23, '008B8B', 'darkcyan', 'a');
INSERT INTO colors VALUES (24, 'B8860B', 'darkgoldenrod', 'a');
INSERT INTO colors VALUES (25, 'A9A9A9', 'darkgray', 'a');
INSERT INTO colors VALUES (26, '006400', 'darkgreen', 'a');
INSERT INTO colors VALUES (27, 'BDB76B', 'darkkhaki', 'a');
INSERT INTO colors VALUES (28, '8B008B', 'darkmagenta', 'a');
INSERT INTO colors VALUES (29, '556B2F', 'darkolivegreen', 'a');
INSERT INTO colors VALUES (30, 'FF8C00', 'darkorange', 'a');
INSERT INTO colors VALUES (31, '9932CC', 'darkorchid', 'a');
INSERT INTO colors VALUES (32, '8B0000', 'darkred', 'a');
INSERT INTO colors VALUES (33, 'E9967A', 'darksalmon', 'a');
INSERT INTO colors VALUES (34, '8FBC8F', 'darkseagreen', 'a');
INSERT INTO colors VALUES (35, '483D8B', 'darkslateblue', 'a');
INSERT INTO colors VALUES (36, '2F4F4F', 'darkslategray', 'a');
INSERT INTO colors VALUES (37, '00CED1', 'darkturquoise', 'a');
INSERT INTO colors VALUES (38, '9400D3', 'darkviolet', 'a');
INSERT INTO colors VALUES (39, 'FF1493', 'deeppink', 'a');
INSERT INTO colors VALUES (40, '00BFFF', 'deepskyblue', 'a');
INSERT INTO colors VALUES (41, '696969', 'dimgray', 'a');
INSERT INTO colors VALUES (42, '1E90FF', 'dodgerblue', 'a');
INSERT INTO colors VALUES (43, 'B22222', 'firebrick', 'a');
INSERT INTO colors VALUES (44, 'FFFAF0', 'floralwhite', 'a');
INSERT INTO colors VALUES (45, '228B22', 'forestgreen', 'a');
INSERT INTO colors VALUES (46, 'FF00FF', 'fuchsia', 'a');
INSERT INTO colors VALUES (47, 'DCDCDC', 'gainsboro', 'a');
INSERT INTO colors VALUES (48, 'F8F8FF', 'ghostwhite', 'a');
INSERT INTO colors VALUES (49, 'FFD700', 'gold', 'a');
INSERT INTO colors VALUES (50, 'DAA520', 'goldenrod', 'a');
INSERT INTO colors VALUES (51, '808080', 'gray', 'a');
INSERT INTO colors VALUES (52, '008000', 'green', 'a');
INSERT INTO colors VALUES (53, 'ADFF2F', 'greenyellow', 'a');
INSERT INTO colors VALUES (54, 'F0FFF0', 'honeydew', 'a');
INSERT INTO colors VALUES (55, 'FF69B4', 'hotpink', 'a');
INSERT INTO colors VALUES (56, 'CD5C5C', 'indianred', 'a');
INSERT INTO colors VALUES (57, '4B0082', 'indigo', 'a');
INSERT INTO colors VALUES (58, 'FFFFF0', 'ivory', 'a');
INSERT INTO colors VALUES (59, 'F0E68C', 'khaki', 'a');
INSERT INTO colors VALUES (60, 'E6E6FA', 'lavender', 'a');
INSERT INTO colors VALUES (61, 'FFF0F5', 'lavenderblush', 'a');
INSERT INTO colors VALUES (62, '7CFC00', 'lawngreen', 'a');
INSERT INTO colors VALUES (63, 'FFFACD', 'lemonchiffon', 'a');
INSERT INTO colors VALUES (64, 'ADD8E6', 'lightblue', 'a');
INSERT INTO colors VALUES (65, 'F08080', 'lightcoral', 'a');
INSERT INTO colors VALUES (66, 'E0FFFF', 'lightcyan', 'a');
INSERT INTO colors VALUES (67, 'FAFAD2', 'lightgoldenrodyellow', 'a');
INSERT INTO colors VALUES (68, '90EE90', 'lightgreen', 'a');
INSERT INTO colors VALUES (69, 'D3D3D3', 'lightgray', 'a');
INSERT INTO colors VALUES (70, 'FFB6C1', 'lightpink', 'a');
INSERT INTO colors VALUES (71, 'FFA07A', 'lightsalmon', 'a');
INSERT INTO colors VALUES (72, '20B2AA', 'lightseagreen', 'a');
INSERT INTO colors VALUES (73, '87CEFA', 'lightskyblue', 'a');
INSERT INTO colors VALUES (74, '778899', 'lightslategray', 'a');
INSERT INTO colors VALUES (75, 'B0C4DE', 'lightsteelblue', 'a');
INSERT INTO colors VALUES (76, 'FFFFE0', 'lightyellow', 'a');
INSERT INTO colors VALUES (77, '00FF00', 'lime', 'a');
INSERT INTO colors VALUES (78, '32CD32', 'limegreen', 'a');
INSERT INTO colors VALUES (79, 'FAF0E6', 'linen', 'a');
INSERT INTO colors VALUES (80, 'FF00FF', 'magenta', 'a');
INSERT INTO colors VALUES (81, '800000', 'maroon', 'a');
INSERT INTO colors VALUES (82, '66CDAA', 'mediumaquamarine', 'a');
INSERT INTO colors VALUES (83, '0000CD', 'mediumblue', 'a');
INSERT INTO colors VALUES (84, 'BA55D3', 'mediumorchid', 'a');
INSERT INTO colors VALUES (85, '9370DB', 'mediumpurple', 'a');
INSERT INTO colors VALUES (86, '3CB371', 'mediumseagreen', 'a');
INSERT INTO colors VALUES (87, '7B68EE', 'mediumslateblue', 'a');
INSERT INTO colors VALUES (88, '00FA9A', 'mediumspringgreen', 'a');
INSERT INTO colors VALUES (89, '48D1CC', 'mediumturquoise', 'a');
INSERT INTO colors VALUES (90, 'C71585', 'mediumvioletred', 'a');
INSERT INTO colors VALUES (91, '191970', 'midnightblue', 'a');
INSERT INTO colors VALUES (92, 'F5FFFA', 'mintcream', 'a');
INSERT INTO colors VALUES (93, 'FFE4E1', 'mistyrose', 'a');
INSERT INTO colors VALUES (94, 'FFE4B5', 'moccasin', 'a');
INSERT INTO colors VALUES (95, 'FFDEAD', 'navajowhite', 'a');
INSERT INTO colors VALUES (96, '000080', 'navy', 'a');
INSERT INTO colors VALUES (97, 'FDF5E6', 'oldlace', 'a');
INSERT INTO colors VALUES (98, '808000', 'olive', 'a');
INSERT INTO colors VALUES (99, '6B8E23', 'olivedrab', 'a');
INSERT INTO colors VALUES (100, 'FFA500', 'orange', 'a');
INSERT INTO colors VALUES (101, 'FF4500', 'orangered', 'a');
INSERT INTO colors VALUES (102, 'DA70D6', 'orchid', 'a');
INSERT INTO colors VALUES (103, 'EEE8AA', 'palegoldenrod', 'a');
INSERT INTO colors VALUES (104, '98FB98', 'palegreen', 'a');
INSERT INTO colors VALUES (105, 'AFEEEE', 'paleturquoise', 'a');
INSERT INTO colors VALUES (106, 'DB7093', 'palevioletred', 'a');
INSERT INTO colors VALUES (107, 'FFEFD5', 'papayawhip', 'a');
INSERT INTO colors VALUES (108, 'FFDAB9', 'peachpuff', 'a');
INSERT INTO colors VALUES (109, 'CD853F', 'peru', 'a');
INSERT INTO colors VALUES (110, 'FFC0CB', 'pink', 'a');
INSERT INTO colors VALUES (111, 'DDA0DD', 'plum', 'a');
INSERT INTO colors VALUES (112, 'B0E0E6', 'powderblue', 'a');
INSERT INTO colors VALUES (113, '800080', 'purple', 'a');
INSERT INTO colors VALUES (114, 'FF0000', 'red', 'a');
INSERT INTO colors VALUES (115, 'BC8F8F', 'rosybrown', 'a');
INSERT INTO colors VALUES (116, '4169E1', 'royalblue', 'a');
INSERT INTO colors VALUES (117, '8B4513', 'saddlebrown', 'a');
INSERT INTO colors VALUES (118, 'FA8072', 'salmon', 'a');
INSERT INTO colors VALUES (119, 'F4A460', 'sandybrown', 'a');
INSERT INTO colors VALUES (120, '2E8B57', 'seagreen', 'a');
INSERT INTO colors VALUES (121, 'FFF5EE', 'seashell', 'a');
INSERT INTO colors VALUES (122, 'A0522D', 'sienna', 'a');
INSERT INTO colors VALUES (123, 'C0C0C0', 'silver', 'a');
INSERT INTO colors VALUES (124, '87CEEB', 'skyblue', 'a');
INSERT INTO colors VALUES (125, '6A5ACD', 'slateblue', 'a');
INSERT INTO colors VALUES (126, '708090', 'slategray', 'a');
INSERT INTO colors VALUES (127, 'FFFAFA', 'snow', 'a');
INSERT INTO colors VALUES (128, '00FF7F', 'springgreen', 'a');
INSERT INTO colors VALUES (129, '4682B4', 'steelblue', 'a');
INSERT INTO colors VALUES (130, 'D2B48C', 'tan', 'a');
INSERT INTO colors VALUES (131, '008080', 'teal', 'a');
INSERT INTO colors VALUES (132, 'D8BFD8', 'thistle', 'a');
INSERT INTO colors VALUES (133, 'FF6347', 'tomato', 'a');
INSERT INTO colors VALUES (134, '40E0D0', 'turquoise', 'a');
INSERT INTO colors VALUES (135, 'EE82EE', 'violet', 'a');
INSERT INTO colors VALUES (136, 'F5DEB3', 'wheat', 'a');
INSERT INTO colors VALUES (137, 'FFFFFF', 'white', 'a');
INSERT INTO colors VALUES (138, 'F5F5F5', 'whitesmoke', 'a');
INSERT INTO colors VALUES (139, 'FFFF00', 'yellow', 'a');
INSERT INTO colors VALUES (140, '9ACD32', 'yellowgreen', 'a');
INSERT INTO colors VALUES (141, '004785', 'defalutblue', 'a');
INSERT INTO colors VALUES (142, 'EAEAEA', 'defaultlinegray', 'a');
INSERT INTO colors VALUES (143, 'DBDBDB', 'defaultheaderbggray', 'a');
INSERT INTO colors VALUES (144, 'F2F2F2', 'defaultboxbggray', 'a');
INSERT INTO colors VALUES (145, '999999', 'defaultrightpanelbarbg', 'a');
INSERT INTO colors VALUES (146, '5E82D5', 'xp_color', '');

# Table structure for table `corporate`

DROP TABLE IF EXISTS corporate;
CREATE TABLE corporate (
  id int(11) NOT NULL auto_increment,
  comp_name varchar(100) NOT NULL default '',
  comp_house_no varchar(100) NOT NULL default '',
  comp_street varchar(100) NOT NULL default '',
  comp_city varchar(100) NOT NULL default '',
  comp_state varchar(100) NOT NULL default '',
  comp_zip varchar(20) NOT NULL default '',
  comp_country int(11) NOT NULL default '0',
  comp_logo varchar(150) NOT NULL default '',
  billing_name varchar(100) NOT NULL default '',
  bill_house_no varchar(100) NOT NULL default '',
  bill_street varchar(100) NOT NULL default '',
  bill_city varchar(100) NOT NULL default '',
  bill_state varchar(100) NOT NULL default '',
  bill_zip varchar(20) NOT NULL default '',
  bill_country int(11) NOT NULL default '0',
  contact_name varchar(100) NOT NULL default '',
  email_address varchar(100) NOT NULL default '',
  telephone varchar(100) NOT NULL default '',
  fax varchar(100) NOT NULL default '',
  general_notes mediumtext NOT NULL,
  yearly_management_fee float(10,2) NOT NULL default '0.00',
  cost_per_pool float(10,2) NOT NULL default '0.00',
  cost_per_test float(10,2) NOT NULL default '0.00',
  discount_per_nct float(10,2) NOT NULL default '0.00',
  ac_creation_date datetime NOT NULL default '0000-00-00 00:00:00',
  comp_initial varchar(10) NOT NULL default '',
  contract_signed_revised char(1) NOT NULL default '',
  contract_start_date datetime NOT NULL default '0000-00-00 00:00:00',
  contract_renewal_date datetime NOT NULL default '0000-00-00 00:00:00',
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `exam`

DROP TABLE IF EXISTS exam;
CREATE TABLE exam (
  id int(11) NOT NULL auto_increment,
  exam_name varchar(100) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  pool_id int(11) NOT NULL default '0',
  no_of_questions int(11) NOT NULL default '0',
  exam_type char(1) NOT NULL default '',
  feedback_status char(1) NOT NULL default '',
  feedback_time int(11) NOT NULL default '0',
  feedback_email varchar(85) default NULL,
  pause_time int(11) NOT NULL default '0',
  no_of_pause int(11) NOT NULL default '0',
  duration int(11) NOT NULL default '0',
  price float(10,2) NOT NULL default '0.00',
  status char(1) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  retest_price float(10,2) NOT NULL default '0.00',
  easy_random_pc tinyint(4) NOT NULL default '0',
  medium_random_pc tinyint(4) NOT NULL default '0',
  hard_random_pc tinyint(4) NOT NULL default '0',
  corporate_general_flag char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `exam_privilege`

DROP TABLE IF EXISTS exam_privilege;
CREATE TABLE exam_privilege (
  id int(11) NOT NULL auto_increment,
  exam_ids mediumtext NOT NULL,
  deleted_exam_ids mediumtext NOT NULL,
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

# Table structure for table `hdr_country`

DROP TABLE IF EXISTS hdr_country;
CREATE TABLE hdr_country (
  id int(11) NOT NULL auto_increment,
  country varchar(100) NOT NULL default '',
  default_flag char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `hdr_country`

INSERT INTO hdr_country VALUES (1, 'United Kingdom', 'y');
INSERT INTO hdr_country VALUES (2, 'United States', '');
INSERT INTO hdr_country VALUES (3, 'Australia', '');
INSERT INTO hdr_country VALUES (4, 'Canada', '');
INSERT INTO hdr_country VALUES (18, 'Albania', '');
INSERT INTO hdr_country VALUES (28, 'APO/FPO', '');
INSERT INTO hdr_country VALUES (17, 'Afghanistan', '');
INSERT INTO hdr_country VALUES (19, 'Algeria', '');
INSERT INTO hdr_country VALUES (20, 'American Samoa', '');
INSERT INTO hdr_country VALUES (21, 'Andorra', '');
INSERT INTO hdr_country VALUES (22, 'Angola', '');
INSERT INTO hdr_country VALUES (23, 'Anguilla', '');
INSERT INTO hdr_country VALUES (24, 'Antigua and Barbuda', '');
INSERT INTO hdr_country VALUES (25, 'Argentina', '');
INSERT INTO hdr_country VALUES (26, 'Armenia', '');
INSERT INTO hdr_country VALUES (27, 'Aruba', '');
INSERT INTO hdr_country VALUES (29, 'Austria', '');
INSERT INTO hdr_country VALUES (30, 'Azerbaijan Republic', '');
INSERT INTO hdr_country VALUES (31, 'Bahamas', '');
INSERT INTO hdr_country VALUES (32, 'Bahrain', '');
INSERT INTO hdr_country VALUES (33, 'Bangladesh', '');
INSERT INTO hdr_country VALUES (34, 'Barbados', '');
INSERT INTO hdr_country VALUES (35, 'Belarus', '');
INSERT INTO hdr_country VALUES (36, 'Belgium', '');
INSERT INTO hdr_country VALUES (37, 'Belize', '');
INSERT INTO hdr_country VALUES (38, 'Benin', '');
INSERT INTO hdr_country VALUES (39, 'Bermuda', '');
INSERT INTO hdr_country VALUES (40, 'Bhutan', '');
INSERT INTO hdr_country VALUES (41, 'Bolivia', '');
INSERT INTO hdr_country VALUES (42, 'Bosnia and Herzegovina', '');
INSERT INTO hdr_country VALUES (43, 'Botswana', '');
INSERT INTO hdr_country VALUES (44, 'Brazil', '');
INSERT INTO hdr_country VALUES (45, 'British Virgin Islands', '');
INSERT INTO hdr_country VALUES (46, 'Brunei Darussalam', '');
INSERT INTO hdr_country VALUES (47, 'Bulgaria', '');
INSERT INTO hdr_country VALUES (48, 'Burkina Faso', '');
INSERT INTO hdr_country VALUES (49, 'Burma', '');
INSERT INTO hdr_country VALUES (50, 'Burundi', '');
INSERT INTO hdr_country VALUES (51, 'Cambodia', '');
INSERT INTO hdr_country VALUES (52, 'Cameroon', '');
INSERT INTO hdr_country VALUES (53, 'Cape Verde Islands', '');
INSERT INTO hdr_country VALUES (54, 'Cayman Islands', '');
INSERT INTO hdr_country VALUES (55, 'Central African Republic', '');
INSERT INTO hdr_country VALUES (56, 'Chad', '');
INSERT INTO hdr_country VALUES (57, 'Chile', '');
INSERT INTO hdr_country VALUES (58, 'China', '');
INSERT INTO hdr_country VALUES (59, 'Columbia', '');
INSERT INTO hdr_country VALUES (60, 'Comoros', '');
INSERT INTO hdr_country VALUES (61, 'Congo, Democratic Republic of the', '');
INSERT INTO hdr_country VALUES (62, 'Congo, Republic of the', '');
INSERT INTO hdr_country VALUES (63, 'Cook Islands', '');
INSERT INTO hdr_country VALUES (64, 'Costa Rica', '');
INSERT INTO hdr_country VALUES (65, 'Cote d Ivoire (Ivory Coast)', '');
INSERT INTO hdr_country VALUES (66, 'Croatia, Republic of', '');
INSERT INTO hdr_country VALUES (67, 'Cyprus', '');
INSERT INTO hdr_country VALUES (68, 'Czech Republic', '');
INSERT INTO hdr_country VALUES (69, 'Denmark', '');
INSERT INTO hdr_country VALUES (70, 'Djibouti', '');
INSERT INTO hdr_country VALUES (71, 'Dominica', '');
INSERT INTO hdr_country VALUES (72, 'Dominican Republic', '');
INSERT INTO hdr_country VALUES (73, 'Ecuador', '');
INSERT INTO hdr_country VALUES (74, 'Egypt', '');
INSERT INTO hdr_country VALUES (75, 'El Salvador', '');
INSERT INTO hdr_country VALUES (76, 'Equatorial Guinea', '');
INSERT INTO hdr_country VALUES (77, 'Eritrea', '');
INSERT INTO hdr_country VALUES (78, 'Estonia', '');
INSERT INTO hdr_country VALUES (79, 'Ethiopia', '');
INSERT INTO hdr_country VALUES (80, 'Falkland Islands (Islas Malvinas)', '');
INSERT INTO hdr_country VALUES (81, 'Fiji', '');
INSERT INTO hdr_country VALUES (82, 'Finland', '');
INSERT INTO hdr_country VALUES (83, 'France', '');
INSERT INTO hdr_country VALUES (84, 'French Guiana', '');
INSERT INTO hdr_country VALUES (85, 'French Polynesia', '');
INSERT INTO hdr_country VALUES (86, 'Gabon Republic', '');
INSERT INTO hdr_country VALUES (87, 'Gambia', '');
INSERT INTO hdr_country VALUES (88, 'Georgia', '');
INSERT INTO hdr_country VALUES (89, 'Germany', '');
INSERT INTO hdr_country VALUES (90, 'Ghana', '');
INSERT INTO hdr_country VALUES (91, 'Gibraltar', '');
INSERT INTO hdr_country VALUES (92, 'Greece', '');
INSERT INTO hdr_country VALUES (93, 'Greenland', '');
INSERT INTO hdr_country VALUES (94, 'Grenada', '');
INSERT INTO hdr_country VALUES (95, 'Guadeloupe', '');
INSERT INTO hdr_country VALUES (96, 'Guam', '');
INSERT INTO hdr_country VALUES (97, 'Guatemala', '');
INSERT INTO hdr_country VALUES (98, 'Guernsey', '');
INSERT INTO hdr_country VALUES (99, 'Guinea', '');
INSERT INTO hdr_country VALUES (100, 'Guinea-Bissau', '');
INSERT INTO hdr_country VALUES (101, 'Guyana', '');
INSERT INTO hdr_country VALUES (102, 'Haiti', '');
INSERT INTO hdr_country VALUES (103, 'Honduras', '');
INSERT INTO hdr_country VALUES (104, 'Hong Kong', '');
INSERT INTO hdr_country VALUES (105, 'Hungary', '');
INSERT INTO hdr_country VALUES (106, 'Iceland', '');
INSERT INTO hdr_country VALUES (107, 'India', '');
INSERT INTO hdr_country VALUES (108, 'Indonesia', '');
INSERT INTO hdr_country VALUES (109, 'Ireland', '');
INSERT INTO hdr_country VALUES (110, 'Israel', '');
INSERT INTO hdr_country VALUES (111, 'Italy', '');
INSERT INTO hdr_country VALUES (112, 'Jamaica', '');
INSERT INTO hdr_country VALUES (113, 'Jan Mayen', '');
INSERT INTO hdr_country VALUES (114, 'Japan', '');
INSERT INTO hdr_country VALUES (115, 'Jersey', '');
INSERT INTO hdr_country VALUES (116, 'Jordan', '');
INSERT INTO hdr_country VALUES (117, 'Kazakhstan', '');
INSERT INTO hdr_country VALUES (118, 'Kenya Coast Republic', '');
INSERT INTO hdr_country VALUES (119, 'Kiribati', '');
INSERT INTO hdr_country VALUES (120, 'Korea, South', '');
INSERT INTO hdr_country VALUES (121, 'Kuwait', '');
INSERT INTO hdr_country VALUES (122, 'Kyrgyzstan', '');
INSERT INTO hdr_country VALUES (123, 'Laos', '');
INSERT INTO hdr_country VALUES (124, 'Latvia', '');
INSERT INTO hdr_country VALUES (125, 'Lebanon', '');
INSERT INTO hdr_country VALUES (126, 'Liechtenstein', '');
INSERT INTO hdr_country VALUES (127, 'Lithuania', '');
INSERT INTO hdr_country VALUES (128, 'Luxembourg', '');
INSERT INTO hdr_country VALUES (129, 'Macau', '');
INSERT INTO hdr_country VALUES (130, 'Macedonia', '');
INSERT INTO hdr_country VALUES (131, 'Madagascar', '');
INSERT INTO hdr_country VALUES (132, 'Malawi', '');
INSERT INTO hdr_country VALUES (133, 'Malaysia', '');
INSERT INTO hdr_country VALUES (134, 'Maldives', '');
INSERT INTO hdr_country VALUES (135, 'Mali', '');
INSERT INTO hdr_country VALUES (136, 'Malta', '');
INSERT INTO hdr_country VALUES (137, 'Marshall Islands', '');
INSERT INTO hdr_country VALUES (138, 'Martinique', '');
INSERT INTO hdr_country VALUES (139, 'Mauritania', '');
INSERT INTO hdr_country VALUES (140, 'Mauritius', '');
INSERT INTO hdr_country VALUES (141, 'Mayotte', '');
INSERT INTO hdr_country VALUES (142, 'Mexico', '');
INSERT INTO hdr_country VALUES (143, 'Micronesia', '');
INSERT INTO hdr_country VALUES (144, 'Moldova', '');
INSERT INTO hdr_country VALUES (145, 'Monaco', '');
INSERT INTO hdr_country VALUES (146, 'Mongolia', '');
INSERT INTO hdr_country VALUES (147, 'Montserrat', '');
INSERT INTO hdr_country VALUES (148, 'Morocco', '');
INSERT INTO hdr_country VALUES (149, 'Mozambique', '');
INSERT INTO hdr_country VALUES (150, 'Namibia', '');
INSERT INTO hdr_country VALUES (151, 'Nauru', '');
INSERT INTO hdr_country VALUES (152, 'Nepal', '');
INSERT INTO hdr_country VALUES (153, 'Netherlands', '');
INSERT INTO hdr_country VALUES (154, 'Netherlands Antilles', '');
INSERT INTO hdr_country VALUES (155, 'New Caledonia', '');
INSERT INTO hdr_country VALUES (156, 'New Zealand', '');
INSERT INTO hdr_country VALUES (157, 'Nicaragua', '');
INSERT INTO hdr_country VALUES (158, 'Niger', '');
INSERT INTO hdr_country VALUES (159, 'Nigeria', '');
INSERT INTO hdr_country VALUES (160, 'Niue', '');
INSERT INTO hdr_country VALUES (161, 'Norway', '');
INSERT INTO hdr_country VALUES (162, 'Oman', '');
INSERT INTO hdr_country VALUES (163, 'Pakistan', '');
INSERT INTO hdr_country VALUES (164, 'Palau', '');
INSERT INTO hdr_country VALUES (165, 'Panama', '');
INSERT INTO hdr_country VALUES (166, 'Papua New Guinea', '');
INSERT INTO hdr_country VALUES (167, 'Paraguay', '');
INSERT INTO hdr_country VALUES (168, 'Peru', '');
INSERT INTO hdr_country VALUES (169, 'Philippines', '');
INSERT INTO hdr_country VALUES (170, 'Poland', '');
INSERT INTO hdr_country VALUES (171, 'Portugal', '');
INSERT INTO hdr_country VALUES (172, 'Puerto Rico', '');
INSERT INTO hdr_country VALUES (173, 'Qatar', '');
INSERT INTO hdr_country VALUES (174, 'Romania', '');
INSERT INTO hdr_country VALUES (175, 'Russian Federation', '');
INSERT INTO hdr_country VALUES (176, 'Rwanda', '');
INSERT INTO hdr_country VALUES (177, 'Saint Helena', '');
INSERT INTO hdr_country VALUES (178, 'Saint Kitts-Nevis', '');
INSERT INTO hdr_country VALUES (179, 'Saint Lucia', '');
INSERT INTO hdr_country VALUES (180, 'Saint Pierre and Miquelon', '');
INSERT INTO hdr_country VALUES (181, 'Saint Vincent and the Grenadines', '');
INSERT INTO hdr_country VALUES (182, 'San Marino', '');
INSERT INTO hdr_country VALUES (183, 'Saudi Arabia', '');
INSERT INTO hdr_country VALUES (184, 'Senegal', '');
INSERT INTO hdr_country VALUES (185, 'Seychelles', '');
INSERT INTO hdr_country VALUES (186, 'Sierra Leone', '');
INSERT INTO hdr_country VALUES (187, 'Singapore', '');
INSERT INTO hdr_country VALUES (188, 'Slovakia', '');
INSERT INTO hdr_country VALUES (189, 'Slovenia', '');
INSERT INTO hdr_country VALUES (190, 'Solomon Islands', '');
INSERT INTO hdr_country VALUES (191, 'Somalia', '');
INSERT INTO hdr_country VALUES (192, 'South Africa', '');
INSERT INTO hdr_country VALUES (193, 'Spain', '');
INSERT INTO hdr_country VALUES (194, 'Sri Lanka', '');
INSERT INTO hdr_country VALUES (195, 'Suriname', '');
INSERT INTO hdr_country VALUES (196, 'Svalbard', '');
INSERT INTO hdr_country VALUES (197, 'Swaziland', '');
INSERT INTO hdr_country VALUES (198, 'Sweden', '');
INSERT INTO hdr_country VALUES (199, 'Switzerland', '');
INSERT INTO hdr_country VALUES (200, 'Syria', '');
INSERT INTO hdr_country VALUES (201, 'Tahiti', '');
INSERT INTO hdr_country VALUES (202, 'Taiwan', '');
INSERT INTO hdr_country VALUES (203, 'Tajikistan', '');
INSERT INTO hdr_country VALUES (204, 'Tanzania', '');
INSERT INTO hdr_country VALUES (205, 'Thailand', '');
INSERT INTO hdr_country VALUES (206, 'Togo', '');
INSERT INTO hdr_country VALUES (207, 'Tonga', '');
INSERT INTO hdr_country VALUES (208, 'Trinidad and Tobago', '');
INSERT INTO hdr_country VALUES (209, 'Tunisia', '');
INSERT INTO hdr_country VALUES (210, 'Turkey', '');
INSERT INTO hdr_country VALUES (211, 'Turkmenistan', '');
INSERT INTO hdr_country VALUES (212, 'Turks and Caicos Islands', '');
INSERT INTO hdr_country VALUES (213, 'Tuvalu', '');
INSERT INTO hdr_country VALUES (214, 'Uganda', '');
INSERT INTO hdr_country VALUES (215, 'Ukraine', '');
INSERT INTO hdr_country VALUES (216, 'United Arab Emirates', '');
INSERT INTO hdr_country VALUES (217, 'Uruguay', '');
INSERT INTO hdr_country VALUES (218, 'Uzbekistan', '');
INSERT INTO hdr_country VALUES (219, 'Vanuatu', '');
INSERT INTO hdr_country VALUES (220, 'Vatican City State', '');
INSERT INTO hdr_country VALUES (221, 'Venezuela', '');
INSERT INTO hdr_country VALUES (222, 'Vietnam', '');
INSERT INTO hdr_country VALUES (223, 'Virgin Islands (U.S.)', '');
INSERT INTO hdr_country VALUES (224, 'Wallis and Futuna', '');
INSERT INTO hdr_country VALUES (225, 'Western Sahara', '');
INSERT INTO hdr_country VALUES (226, 'Western Samoa', '');
INSERT INTO hdr_country VALUES (227, 'Yemen', '');
INSERT INTO hdr_country VALUES (228, 'Yugoslavia', '');
INSERT INTO hdr_country VALUES (229, 'Zambia', '');
INSERT INTO hdr_country VALUES (230, 'Zimbabwe', '');

# Table structure for table `hdr_currency`

DROP TABLE IF EXISTS hdr_currency;
CREATE TABLE hdr_currency (
  id int(11) NOT NULL auto_increment,
  currency varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  default_flag char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `hdr_currency`

INSERT INTO hdr_currency VALUES (1, 'AUD', 'Australian Dollar', 'n');
INSERT INTO hdr_currency VALUES (2, 'ATS', 'Austrian Schilling', 'n');
INSERT INTO hdr_currency VALUES (3, 'BHD', 'Bahraini Dinar', 'n');
INSERT INTO hdr_currency VALUES (4, 'BDT', 'Bangladesh Taka', 'n');
INSERT INTO hdr_currency VALUES (5, 'BEF', 'Belgian Franc', 'n');
INSERT INTO hdr_currency VALUES (6, 'BRL', 'Brazil Real', 'n');
INSERT INTO hdr_currency VALUES (7, 'GBP', 'British Pound', 'y');
INSERT INTO hdr_currency VALUES (8, 'CAD', 'Canadian Dollar', 'n');
INSERT INTO hdr_currency VALUES (9, 'CNY', 'China Renminbi', 'n');
INSERT INTO hdr_currency VALUES (10, 'COP', 'Colombian Peso', 'n');
INSERT INTO hdr_currency VALUES (11, 'CYP', 'Cyprus Pound', 'n');
INSERT INTO hdr_currency VALUES (12, 'DKK', 'Danish Krone', 'n');
INSERT INTO hdr_currency VALUES (13, 'NLG', 'Dutch Guilder', 'n');
INSERT INTO hdr_currency VALUES (14, 'EUR', 'European Euro', 'n');
INSERT INTO hdr_currency VALUES (15, 'FIM', 'Finnish Markka', 'n');
INSERT INTO hdr_currency VALUES (16, 'FRF', 'French Franc', 'n');
INSERT INTO hdr_currency VALUES (17, 'DEM', 'German Mark', 'n');
INSERT INTO hdr_currency VALUES (18, 'GRD', 'Greek Drachma', 'n');
INSERT INTO hdr_currency VALUES (19, 'HKD', 'Hong Kong Dollar', 'n');
INSERT INTO hdr_currency VALUES (20, 'ISK', 'Iceland Krona', 'n');
INSERT INTO hdr_currency VALUES (21, 'INR', 'Indian Rupee', 'n');
INSERT INTO hdr_currency VALUES (22, 'IRR', 'Iranian Rial', 'n');
INSERT INTO hdr_currency VALUES (23, 'IQD', 'Iraqi Dinar', 'n');
INSERT INTO hdr_currency VALUES (24, 'IEP', 'Irish Punt', 'n');
INSERT INTO hdr_currency VALUES (25, 'ITL', 'Italian Lira', 'n');
INSERT INTO hdr_currency VALUES (26, 'JPY', 'Japanese Yen', 'n');
INSERT INTO hdr_currency VALUES (27, 'KRW', 'Korean Won', 'n');
INSERT INTO hdr_currency VALUES (28, 'KWD', 'Kuwaiti Dinar', 'n');
INSERT INTO hdr_currency VALUES (29, 'MYR', 'Malaysian Ringgit', 'n');
INSERT INTO hdr_currency VALUES (30, 'MTL', 'Maltese Lira', 'n');
INSERT INTO hdr_currency VALUES (31, 'MXP', 'Mexican Peso', 'n');
INSERT INTO hdr_currency VALUES (32, 'NPR', 'Nepal Rupee', 'n');
INSERT INTO hdr_currency VALUES (33, 'NZD', 'New Zealand Dollar', 'n');
INSERT INTO hdr_currency VALUES (34, 'NOK', 'Norwegian Krone', 'n');
INSERT INTO hdr_currency VALUES (35, 'OMR', 'Omani Rial', 'n');
INSERT INTO hdr_currency VALUES (36, 'PKR', 'Pakistani Rupee', 'n');
INSERT INTO hdr_currency VALUES (37, 'PTE', 'Portuguese Escudo', 'n');
INSERT INTO hdr_currency VALUES (38, 'QAR', 'Qatari Riyal', 'n');
INSERT INTO hdr_currency VALUES (39, 'SAR', 'Saudi Riyal', 'n');
INSERT INTO hdr_currency VALUES (40, 'SGD', 'Singapore Dollar', 'n');
INSERT INTO hdr_currency VALUES (41, 'ZAL', 'South African Rand', 'n');
INSERT INTO hdr_currency VALUES (42, 'ESP', 'Spanish Peseta', 'n');
INSERT INTO hdr_currency VALUES (43, 'LKR', 'Sri Lankan Rupee', 'n');
INSERT INTO hdr_currency VALUES (44, 'SEK', 'Swedish Krona', 'n');
INSERT INTO hdr_currency VALUES (45, 'CHF', 'Swiss Franc', 'n');
INSERT INTO hdr_currency VALUES (46, 'TWD', 'Taiwan dollar', 'n');
INSERT INTO hdr_currency VALUES (47, 'THB', 'Thai Baht', 'n');
INSERT INTO hdr_currency VALUES (48, 'TTD', 'Trinidad & Tobago Dollar', 'n');
INSERT INTO hdr_currency VALUES (49, 'AED', 'U.A.E. Dirham', 'n');
INSERT INTO hdr_currency VALUES (50, 'USD', 'U.S. Dollar', 'n');
INSERT INTO hdr_currency VALUES (51, 'VEB', 'Venezuelan Bolivar', 'n');
INSERT INTO hdr_currency VALUES (52, 'DZD', 'Algerian Dinar', 'n');
INSERT INTO hdr_currency VALUES (53, 'ARP', 'Argentinian Peso', 'n');
INSERT INTO hdr_currency VALUES (54, 'BSD', 'Bahamian Dollar', 'n');
INSERT INTO hdr_currency VALUES (55, 'BBD', 'Barbados Dollar', 'n');
INSERT INTO hdr_currency VALUES (56, 'BMD', 'Bermudian Dollar', 'n');
INSERT INTO hdr_currency VALUES (57, 'BGL', 'Bulgarian Lev', 'n');
INSERT INTO hdr_currency VALUES (58, 'CLP', 'Chilean Peso', 'n');
INSERT INTO hdr_currency VALUES (59, 'CZK', 'Czech Koruna', 'n');
INSERT INTO hdr_currency VALUES (60, 'ECS', 'Ecuadorian Sucre', 'n');
INSERT INTO hdr_currency VALUES (61, 'EGP', 'Egyptian Pound', 'n');
INSERT INTO hdr_currency VALUES (62, 'HUF', 'Hungarian Forint', 'n');
INSERT INTO hdr_currency VALUES (63, 'IDR', 'Indonesian Rupiah', 'n');
INSERT INTO hdr_currency VALUES (64, 'ILS', 'Israeli New Shekel', 'n');
INSERT INTO hdr_currency VALUES (65, 'JMD', 'Jamaican Dollar', 'n');
INSERT INTO hdr_currency VALUES (66, 'JDD', 'Jordanian Dinar', 'n');
INSERT INTO hdr_currency VALUES (67, 'KZT', 'Karach Tenge', 'n');
INSERT INTO hdr_currency VALUES (68, 'LBP', 'Lebanese Pound', 'n');
INSERT INTO hdr_currency VALUES (69, 'PEN', 'Peruvian New Soles', 'n');
INSERT INTO hdr_currency VALUES (70, 'PHP', 'Philippines Peso', 'n');
INSERT INTO hdr_currency VALUES (71, 'PLZ', 'Polish Zloty', 'n');
INSERT INTO hdr_currency VALUES (72, 'ROL', 'Romanian Lev', 'n');
INSERT INTO hdr_currency VALUES (73, 'RUR', 'Russian Ruble', 'n');
INSERT INTO hdr_currency VALUES (74, 'SKK', 'Slovakian Koruna', 'n');
INSERT INTO hdr_currency VALUES (75, 'KRW', 'South Korean Won', 'n');
INSERT INTO hdr_currency VALUES (76, 'SDD', 'Sudanese Dinar', 'n');
INSERT INTO hdr_currency VALUES (77, 'TRL', 'Turkish Lira', 'n');
INSERT INTO hdr_currency VALUES (78, 'UAH', 'Hukrainean Hryvna', 'n');
INSERT INTO hdr_currency VALUES (79, 'UYU', 'Uruguayan Peso', 'n');
INSERT INTO hdr_currency VALUES (80, 'ZMK', 'Zambian Kwacha', 'n');

# Table structure for table `hdr_department`

DROP TABLE IF EXISTS hdr_department;
CREATE TABLE hdr_department (
  id int(11) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  notes varchar(255) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  type char(1) NOT NULL default '',
  corporate_id int(11) NOT NULL default '0',
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `hdr_qualification`

DROP TABLE IF EXISTS hdr_qualification;
CREATE TABLE hdr_qualification (
  id int(11) NOT NULL auto_increment,
  qualification varchar(100) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  default_flag char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `hdr_qualification`

INSERT INTO hdr_qualification VALUES (1, 'GCSE/Olevel', '', 'y');
INSERT INTO hdr_qualification VALUES (2, 'GVQ', '', 'n');
INSERT INTO hdr_qualification VALUES (3, 'College diploma', '', 'n');
INSERT INTO hdr_qualification VALUES (4, 'A level', '', 'n');
INSERT INTO hdr_qualification VALUES (5, 'HNC', '', 'n');
INSERT INTO hdr_qualification VALUES (6, 'HND', '', 'n');
INSERT INTO hdr_qualification VALUES (7, 'Degree', 'n', 'n');
INSERT INTO hdr_qualification VALUES (8, 'Master Degree', '', 'n');
INSERT INTO hdr_qualification VALUES (9, 'Phd', '', 'n');

# Table structure for table `hdr_reward_point`

DROP TABLE IF EXISTS hdr_reward_point;
CREATE TABLE hdr_reward_point (
  id int(11) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  points int(11) NOT NULL default '0',
  type char(1) NOT NULL default '',
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `hdr_reward_point`

INSERT INTO hdr_reward_point VALUES (1, 'Feedback', 'reward eairned from feedback', 1, '', 'a');
INSERT INTO hdr_reward_point VALUES (2, 'Test Suggestion', 'Suggesting a teset', 2, '', 'a');
INSERT INTO hdr_reward_point VALUES (3, 'Friend Recommendation', 'Recommending a friend', 1, 's', 'a');

# Table structure for table `hdr_sal_range`

DROP TABLE IF EXISTS hdr_sal_range;
CREATE TABLE hdr_sal_range (
  id int(11) NOT NULL auto_increment,
  salary_range varchar(100) NOT NULL default '',
  default_flag char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `hdr_sal_range`

INSERT INTO hdr_sal_range VALUES (1, '< 25k:25ph', 'y');
INSERT INTO hdr_sal_range VALUES (2, '25-30k:25-30ph', 'n');
INSERT INTO hdr_sal_range VALUES (3, '30-35k:30-35ph', 'n');
INSERT INTO hdr_sal_range VALUES (4, '35-40k:35-40ph', 'n');
INSERT INTO hdr_sal_range VALUES (5, '40-45k:40-45ph', 'n');
INSERT INTO hdr_sal_range VALUES (6, '45-50k:45-50ph', 'n');
INSERT INTO hdr_sal_range VALUES (7, '50-55k:50-55ph', 'n');
INSERT INTO hdr_sal_range VALUES (8, '55-60k:55-60ph', 'n');
INSERT INTO hdr_sal_range VALUES (9, '60-65k:60-65ph', 'n');
INSERT INTO hdr_sal_range VALUES (10, '65-70k:65-70ph', 'n');
INSERT INTO hdr_sal_range VALUES (11, '70-80k:70-80ph', 'n');
INSERT INTO hdr_sal_range VALUES (12, '80k+:80ph+', 'n');

# Table structure for table `hdr_test_schedule`

DROP TABLE IF EXISTS hdr_test_schedule;
CREATE TABLE hdr_test_schedule (
  id int(11) NOT NULL auto_increment,
  test_id int(11) NOT NULL default '0',
  created_by int(11) NOT NULL default '0',
  created_date datetime NOT NULL default '0000-00-00 00:00:00',
  valid_from datetime NOT NULL default '0000-00-00 00:00:00',
  valid_to datetime NOT NULL default '0000-00-00 00:00:00',
  reason varchar(255) default NULL,
  status char(1) NOT NULL default '',
  admin_ids_qa varchar(255) NOT NULL default '',
  admin_ids_sc varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `hdr_user`

DROP TABLE IF EXISTS hdr_user;
CREATE TABLE hdr_user (
  id int(11) NOT NULL auto_increment,
  first_name varchar(100) NOT NULL default '',
  user_name varchar(255) NOT NULL default '',
  password varchar(255) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  paypal_id varchar(255) NOT NULL default '',
  house_number varchar(100) NOT NULL default '',
  state varchar(100) NOT NULL default '',
  country varchar(100) NOT NULL default '',
  status char(1) NOT NULL default '',
  type char(1) NOT NULL default '',
  access_level int(11) NOT NULL default '0',
  last_name varchar(100) NOT NULL default '',
  screen_name varchar(100) NOT NULL default '',
  dob datetime NOT NULL default '0000-00-00 00:00:00',
  show_in_ranking char(1) NOT NULL default '',
  highest_qualification varchar(50) NOT NULL default '',
  added_date datetime NOT NULL default '0000-00-00 00:00:00',
  leaving_date datetime NOT NULL default '0000-00-00 00:00:00',
  third_party char(1) NOT NULL default '',
  referer_user_name varchar(255) NOT NULL default '0',
  title varchar(10) NOT NULL default '',
  street varchar(255) NOT NULL default '',
  zip varchar(20) NOT NULL default '',
  home_telephone varchar(20) NOT NULL default '',
  mobile_telephone varchar(20) NOT NULL default '',
  work_telephone varchar(20) NOT NULL default '',
  looking_for_employment char(1) NOT NULL default '',
  salary_range_req varchar(100) NOT NULL default '',
  currency varchar(50) NOT NULL default '',
  notice_period varchar(10) NOT NULL default '',
  nationality varchar(100) NOT NULL default '',
  work_permit char(1) NOT NULL default '',
  prefered_position varchar(100) NOT NULL default '',
  location_for_emp varchar(50) NOT NULL default '',
  secret_question varchar(255) NOT NULL default '',
  answer varchar(255) NOT NULL default '',
  uploaded_cv_name varchar(255) NOT NULL default '',
  real_cv_name varchar(255) NOT NULL default '',
  town_city varchar(100) NOT NULL default '',
  corporate_id int(11) NOT NULL default '0',
  billing_name varchar(100) NOT NULL default '',
  bill_house_no varchar(100) NOT NULL default '',
  bill_street varchar(100) NOT NULL default '',
  bill_city varchar(100) NOT NULL default '',
  bill_state varchar(100) NOT NULL default '',
  bill_zip varchar(20) NOT NULL default '',
  bill_country int(11) NOT NULL default '0',
  regional_office varchar(100) NOT NULL default '',
  department int(11) NOT NULL default '0',
  job_title varchar(100) NOT NULL default '',
  fax varchar(100) NOT NULL default '',
  position varchar(100) NOT NULL default '',
  ext_int_flag char(1) NOT NULL default '',
  notes mediumtext NOT NULL,
  ext_employee_id varchar(50) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

# Dumping data for table `hdr_user`

INSERT INTO hdr_user VALUES (1, 'superuser', 'superuser', 'superuser', 'superuser@scalebrain.com', 'superuser@scalebrain.com', 'testing 123', 'testing 123', '1', 'a', 's', 3, 'superuser', 'superuser', '1904-05-01 00:00:00', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', 'Mr', 'testing 123', 'testing 123', '100', '100', '100', '', '', '', '', 'India', '', '', '', 'My Full Name?', 'My Full Name?', '', '', 'testing 123', 0, '', '', '', '', '', '', 0, '', 1, '', '', '', '', '', '', 0);

# Table structure for table `help_content`

DROP TABLE IF EXISTS help_content;
CREATE TABLE help_content (
  id int(11) NOT NULL auto_increment,
  user_type char(1) NOT NULL default '',
  link_name varchar(255) NOT NULL default '',
  page_name varchar(255) NOT NULL default '',
  help_content mediumtext NOT NULL,
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Dumping data for table `help_content`

INSERT INTO help_content VALUES (1, '', 'home help', 'root/index.php', 'To login as super user use superuser / superuser while you can get other users and admin username and passwords from \r\nsuper user login. <br><u><b>Happy Exploration...</b></u>', 'a');
INSERT INTO help_content VALUES (3, '', 'FAQ help content', 'root/visitor/faq.php', 'You can copy paste any html content in the content are provided at the admin \r\nend. This will be reflected here in FAQ.', 'a');
INSERT INTO help_content VALUES (4, '', 'my control panel', 'root/common/login.php', 'you already logged in <h1>continue</h1>', 'd');
INSERT INTO help_content VALUES (5, '', 'benefits', 'root/visitor/benifits.php', 'You can provide some content of benifits at admin end to appear here.', 'a');
INSERT INTO help_content VALUES (6, '', 'suggest a test', 'root/visitor/suggest_test.php', 'If you would like to see a certain test on this site please enter the details below.\r\n\r\n', 'a');
INSERT INTO help_content VALUES (7, '', 'coming soon', 'root/visitor/comming_soon.php', '<h>coming soon</h>', 'a');
INSERT INTO help_content VALUES (2, '', 'Advetising help', 'root/visitor/advertising.php', 'You can use this page to put advertisements you have. What you have to do is \r\nlogin as super user or any other advanced admin and paste add code in the area \r\ngiven, the ad will be reflected in this page.', 'a');
INSERT INTO help_content VALUES (8, '', 'privacy', 'root/visitor/privacy_policy.php', 'there is a information of security purpose', 'a');
INSERT INTO help_content VALUES (9, '', 'terms & conditions', 'root/visitor/term_conditions.php', 'before go ahead there is some requirements have to fulfill', 'a');
INSERT INTO help_content VALUES (10, '', 'promotion', 'root/visitor/promotions.php', 'to gain points according to performance', 'a');
INSERT INTO help_content VALUES (11, '', 'testimonial', 'root/visitor/testimonial.php', 'Testimonials regarding paranormal experiences', 'a');
INSERT INTO help_content VALUES (12, '', 'my control panel', 'root/admin/index.php', 'you already loggining<h1>continue... </h1>', 'a');
INSERT INTO help_content VALUES (13, '', 'contact us', 'root/visitor/contact_us.php', 'Do you have a question, comment, suggestion then contact us by mail...', 'a');
INSERT INTO help_content VALUES (14, '', 'maintain payment detail', 'root/user/payment_details.php', 'Here you can update your "paypal" payment details', 'a');
INSERT INTO help_content VALUES (15, '', 'my account credits', 'root/user/account_details.php', 'information about current balance and view account transactions', 'a');
INSERT INTO help_content VALUES (16, '', 'add fund', 'root/user/add_fund.php', 'you can add funds to your on-line exam account using your credit/debit card.\r\n', 'a');
INSERT INTO help_content VALUES (17, '', 'my profile', 'root/user/maintain_profile.php', 'if you want to add you in this site please fill the following details', 'a');
INSERT INTO help_content VALUES (18, '', 'change password', 'root/user/change_password.php', 'click on the change password ', 'a');
INSERT INTO help_content VALUES (19, '', 'available test', 'root/user/take_test.php', 'if you want to see test detail then click on the icon which is on the end of the row at action column', 'a');
INSERT INTO help_content VALUES (20, '', 'disconected test', 'root/user/disconected_test.php', 'whole information about attemted,remaning questions', 'a');
INSERT INTO help_content VALUES (21, '', 'test history', 'root/user/test_history.php', 'records of conducted tests', 'a');
INSERT INTO help_content VALUES (22, '', 'ranking', 'root/user/test_ranking.php', 'rank according to performance ', 'a');
INSERT INTO help_content VALUES (23, '', 'suggest a test', 'root/user/suggest_test.php', 'if you want to choose another test go for suggest new test', 'a');
INSERT INTO help_content VALUES (24, '', 'feedback', 'root/user/report_problem.php', 'If you discover a problem with our site, please inform us by providing as much detail as you can', 'a');
INSERT INTO help_content VALUES (25, '', 'reward points', 'root/user/reward_point.php', 'here you can see how much points you got in this test', 'a');
INSERT INTO help_content VALUES (26, '', 'recommend', 'root/user/recommend.php', 'if you want to suggest your friends to view this site then send them mail as follows... ', 'a');
INSERT INTO help_content VALUES (27, '', 'current points', 'root/user/user_reward_point.php', 'till now how much points you collect....', 'a');
INSERT INTO help_content VALUES (28, '', 'add comments', 'root/user/publish_report.php', 'If you would like to post a statement under out testimonial section about the benefits and usefulness of our site please enter details ', 'a');
INSERT INTO help_content VALUES (29, '', 'update account', 'root/update_account.php', 'if you would like some changes in your account then you can update your account.', 'a');
INSERT INTO help_content VALUES (30, '', 'payment detail', 'root/admin/payment_details.php', 'Here you can update your payment related details', 'a');
INSERT INTO help_content VALUES (31, '', 'manage test', 'root/admin/manage_test.php', 'Deleting the exam from here will not affect the status of exam only. Superuser have right to activate, inactivate, delete the exam or put in beta list. \r\n', 'a');
INSERT INTO help_content VALUES (32, '', 'create test', 'root/admin/add_test.php', 'You can create test here. The test will need super user approval after successful creation \r\n', 'a');
INSERT INTO help_content VALUES (33, '', 'status', 'root/admin/test_status.php', 'Here you can set status of test. The "# Questions" and "# Questions Present" represents the number of questions required and the number of question present. \r\n', 'a');
INSERT INTO help_content VALUES (34, '', 'grant permission', 'root/admin/grant_revoke_test.php', 'You can grant and revoke permissions from owner of the test by selecting radio buttons. Click save button to save the setting of this page.\r\n\r\n', 'a');
INSERT INTO help_content VALUES (35, '', 'account', 'root/admin/cor_account.php', 'here you can see the details about how can add and delete account', 'a');
INSERT INTO help_content VALUES (36, '', 'admin', 'root/cor_admin.php', 'if you would like to see details about admin then you can select the company and click on proceed', 'a');
INSERT INTO help_content VALUES (37, '', 'user', 'root/admin/cor_usr.php', 'if you would like to see details about users then you can select the company and click on proceed', 'a');
INSERT INTO help_content VALUES (38, '', 'reset password', 'root/admin/reset_cor_password.php', 'if you would like to chande your password then you can select company to reset password and click on proceed', 'a');
INSERT INTO help_content VALUES (39, '', 'department', 'root/admin/department.php', 'select corporate to view depatment and click on view.from here you can add or delete department', 'a');
INSERT INTO help_content VALUES (40, '', 'change account owner', 'root/admin/change_cor_usr_admn.php', 'select company to change account owner and click on proceed', 'a');
INSERT INTO help_content VALUES (41, '', 'non corporate test', 'root/admin/rpt_non_cor_test_taken.php', 'Details of non-corporate test taken by corporate user:- if you want to view details then click on the icon in action column', 'a');
INSERT INTO help_content VALUES (42, '', 'generate invoice', 'root/admin/invoice.php', 'here you can view/modify settings by select company and click on next', 'a');
INSERT INTO help_content VALUES (43, '', 'add question', 'root/admin/add_question.php', 'select the pattern of question and click on proceed here you can add ouestions?', 'a');
INSERT INTO help_content VALUES (44, '', 'view question', 'root/admin/view_questions.php', 'here you can view the question by clicking on action columns "view questions"', 'a');
INSERT INTO help_content VALUES (45, '', 'pool name', 'root/admin/pool_name.php', 'if you would like to make some changes in pool then click on the edit here you can also add or delete a pool', 'a');
INSERT INTO help_content VALUES (46, '', 'skill', 'root/admin/skill_area_name.php', 'here skill details added or deleted for more information click on action', 'a');
INSERT INTO help_content VALUES (47, '', 'topics', 'root/admin/topic_name.php', 'here you can see the details of topics related to the test topic will be added also ', 'a');
INSERT INTO help_content VALUES (48, '', 'maintane admin', 'root/admin/maintain_admin.php', 'here admin account is shown if you would like to make some changes then you can after making changes you have to  click on save.you can add or delete admin also here', 'a');
INSERT INTO help_content VALUES (49, '', 'non admin', 'root/admin/maintain_non_admin.php', 'here you can create a new users', 'a');
INSERT INTO help_content VALUES (50, '', 'reset password', 'root/admin/reset_passwd.php', 'if you would like to make a change in your password you can it is applicable for active user only', 'a');
INSERT INTO help_content VALUES (51, '', 'reward points', 'root/admin/reward_point.php', 'here points will given to the users according to there category', 'a');
INSERT INTO help_content VALUES (52, '', 'credits', 'root/admin/add_credit.php', 'here Credits added  to User Account', 'a');
INSERT INTO help_content VALUES (53, '', 'list of users', 'root/admin/rpt_reg_usr.php', 'here you can see the registered users if you ant to know about users the click on action column', 'a');
INSERT INTO help_content VALUES (54, '', 'total revanu', 'root/admin/rpt_revenu_y_m.php', 'if you want to see the revanue by month and year select the month and year and click on proceed you will get the whole information ', 'a');
INSERT INTO help_content VALUES (55, '', 'revanue', 'root/admin/rpt_revenu_y_m_t.php', 'Revenue by month, year and test after choosen month ,year and test you will get the detail.', 'a');
INSERT INTO help_content VALUES (56, '', 'reward points', 'root/admin/rpt_reward_point.php', 'here you can see details of marks destibution ', 'a');
INSERT INTO help_content VALUES (57, '', 'test detail', 'root/admin/rpt_test_name.php', 'here you can see the test details by clicking on icon of action column\'s', 'a');
INSERT INTO help_content VALUES (58, '', 'hit site', 'root/admin/rpt_site_hits.php', 'here you can see "how many number of times site is visited by the admin and users."', 'a');
INSERT INTO help_content VALUES (59, '', 'test detail', 'root/admin/rpt_test_by_name.php', ' you can see over here test details by user ', 'a');
INSERT INTO help_content VALUES (60, '', 'About Us Help', 'root/visitor/about_us.php', 'this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us this is some help about us ', 'a');
INSERT INTO help_content VALUES (61, '', 'Login Help', 'root/common/login.php', 'You need to Login to explore various restricted areas of site. As this is a demo \r\nsite you can login as superuser / superuser. From here you can get password of \r\nother users like <b>advanced admin</b>, <b>restricted admin</b>, <b>registered \r\nuser</b>, <b>corporate admin </b>and <b>corporate user</b>.', 'a');

# Table structure for table `liecence`

DROP TABLE IF EXISTS liecence;
CREATE TABLE liecence (
  l_key varchar(100) NOT NULL default '',
  l_val varchar(100) NOT NULL default ''
);


# Table structure for table `one_time`

DROP TABLE IF EXISTS one_time;
CREATE TABLE one_time (
  max_marks int(11) NOT NULL default '0',
  negative_mark varchar(10) NOT NULL default '0',
  admin_paypal_id varchar(50) NOT NULL default '0',
  paypal_varified char(1) NOT NULL default '0',
  feedback_time int(11) NOT NULL default '0',
  pause_time int(11) NOT NULL default '0',
  no_of_pause int(11) NOT NULL default '0',
  duration int(11) NOT NULL default '0',
  price float NOT NULL default '0',
  meta_keyword mediumtext NOT NULL,
  meta_description mediumtext NOT NULL,
  meta_classification mediumtext NOT NULL,
  copyright_text mediumtext NOT NULL,
  reward_point longtext NOT NULL,
  privacy_policy longtext NOT NULL,
  terms_conditions longtext NOT NULL,
  promotional_links longtext NOT NULL,
  contact_us longtext NOT NULL,
  advertising longtext NOT NULL,
  faq longtext NOT NULL,
  benifits longtext NOT NULL,
  about_us longtext NOT NULL,
  logo_name varchar(50) NOT NULL default '',
  banner_name varchar(50) NOT NULL default '',
  ad_code mediumtext NOT NULL,
  coming_soon longtext NOT NULL,
  testimonial longtext NOT NULL,
  home_left longtext NOT NULL,
  home_right longtext NOT NULL
);

# Dumping data for table `one_time`

INSERT INTO one_time VALUES (1, '1', '1', '1', 1, 1, 1, 1, '1', 'web based test creation tool,solaris technical interview questions,technical competence testing,Recruitment cost reduction  control,it Skill assessment and testing,online solaris aptitude test,Pre interview testing,computer solaris jobs in the uk,information technology certifications,computer job in the uk,skill measurement calibration,benchmarking skills,cost cutting recruitment employment,it CV candidate filtering,problem simulations troubleshooting IT,streamlining recruitment selection,vacancies and jobs for unix sa,unix practice exam questions,recruitment & selection,exam preparation and certifications,solaris test tool questions,skills aptitude ranking in technology,Information Technology  talent spotter,performance measure measurement, it recruitment agencies uk.', '\r\nCompanies can automate the technical interview of the employment process by using our tests or creating their own automatically marked \r\ninternet IT  technical tests whilst reducing recruitment costs . Real interview questions allows the individual \r\nto test their IT skills and reach selection by employers.', 'exam portal', 'Copyright © 2004-2005 yoursite.com<sup>®</sup>. All Rights Reserved.<br>Powered by <a href="http://www.scalebrain.com" target="_blank" title="Real Solutions for Virtual World"><font color="#808080">Acumen Software</font></a>', 'No reward point text', 'No privacy policy text', 'No Term and conditions text', 'No promotional link text', '<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n\r\n<p><br></p>\r\n<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">\r\n                      <tr>\r\n                        <td width="23%"> </td>\r\n                        <td width="23%"><font size="1"><strong><font color="#0f3f67">Address</font></strong><br>\r\n                          <br>\r\n                          <br>\r\n                          </font><font size="2">  </font></td>\r\n                        <td width="77%"><font size="1"><strong>:<font color="#0f3f67">Acumen \r\n                          Software</font></strong><br>\r\n                           C-68, Ground Floor,<br>\r\n                           Preet Vihar,<br>\r\n                           Delhi 110092, INDIA</font></td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td> </td>\r\n                        <td><font color="#0f3f67" size="1"><strong>Tel </strong></font></td>\r\n                        <td><font size="1"><strong>:</strong> +91 - 11 - 22043581</font></td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td> </td>\r\n                        <td><font color="#0f3f67" size="1"><strong>Email </strong></font></td>\r\n                        <td><font size="2"><a href="mailto:support@scalebrain.com">support@Software4exam.com</a></font></td>\r\n                      </tr>\r\n                    </table>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n\r\n\r\n\r\n<p><br></p>', 'No ad text', '<a name="88"></a><p align="justify"><font size="2">You will find below a set of Frequently \r\n                      Asked Questions for quick reference. If you do not find \r\n                      the query you have or it is still unresolved, please contact \r\n                      us at +9811235384 or ,send us an e-mail at <strong><a href="mailto:support@scalebrain.com">support@scalebrain.com</a></strong>.</font><font size="2" align="justify"> \r\n                      </font></p>\r\n                    <ul>\r\n                      <li><a href="#1">What is Scalebrain\'s Skill Testing Solution \r\n                        (STS)?</a> </li>\r\n                      <li><a href="#2">How can I purchase Scalebrain STS?</a></li>\r\n                      <li><a href="#3">What kinds of questions are supported by \r\n                        Scalebrain\'s STS?</a></li>\r\n                      <li> <a href="#9">How does STS function? </a></li>\r\n                      <li> <a href="#4">How can I access the demo version?</a></li>\r\n                      <li><a href="#5">Can I conduct outstation tests?</a></li>\r\n                      <li> <a href="#6">Can I change the look and feel of Scalebrain\'s \r\n                        STS?</a></li>\r\n                      <li><a href="#7">How can the administrator view the result?</a></li>\r\n                      <li><a href="#8">How is the scoring done?</a></li>\r\n                      <li><a href="#10">What is self-assessment?</a></li>\r\n                    </ul>\r\n                    <p name="1"><font size="2" color="#0f3f67" align="justify"><a name="1"></a>1. \r\n                      What is Scalebrain\'s Skill Testing Solution (STS)?</font></strong> \r\n                      <font size="2"><br>\r\n                      </font><font size="2">Scalebrain STS is a web based training \r\n                      software. Our online Skill Testing solutions provides an \r\n                      effective method of sharing knowledge within your organization \r\n                      or to outside participants. Our software will save your \r\n                      time, money and increase your staff productivity. <br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong> \r\n                      </font></p>\r\n                    <p><font size="2"><a name="2"></a>2. <font color="#0f3f67">How \r\n                      can I purchase Scalebrain STS?</font><br>\r\n                      Scalebrain\'s sales team will assist you in defining your \r\n                      requirements and customizing software as per the same. Submit \r\n                      your requirement at our website and our team will immediately \r\n                      get in touch with you.<br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong> \r\n                      </font></p>\r\n                    <p><font size="2"><strong><a name="3"></a></strong>3. <font color="#0f3f67">What \r\n                      kinds of questions are supported by Scalebrain\'s STS?</font><br>\r\n                      Scalebrain\'s STS supports the following question types. \r\n                      Each question type supports image as part of the question \r\n                      statement.</font></p>\r\n                    <ul>\r\n                      <li><font size="2"> Multiple Choice Single Response</font></li>\r\n                      <li><font size="2">Fill In The Blanks (Written answers)</font></li>\r\n                      <li><font size="2">Multiple Choice Multiple Response</font></li>\r\n                    </ul>\r\n                    <p><font size="1"><strong><a href="#88">Back to Top </a></strong></font></p>\r\n                    <p> <font size="2"><strong><a name="9"></a></strong>4. </font><font size="2"><font color="#0f3f67">How \r\n                      does STS function?</font><strong><br>\r\n                      </strong>Questions for the tests are selected at random \r\n                      for each test using built-in logic, so that each candidate \r\n                      gets a different set of questions. So, any number of candidates \r\n                      can take the test at the same time.<br>\r\n                      <strong><a href="#88"><font size="1">Back to Top</font><font color="#0000CC" size="1"> \r\n                      </font></a></strong> </font></p>\r\n                    <p><font size="2"><a name="4"></a></font><font size="2" align="justify">5</font><font size="2">.</font> \r\n                      <font size="2"><font color="#0f3f67">How can I access the \r\n                      demo version?</font><br>\r\n                      Scalebrain\'s STS is used for various purposes including \r\n                      hiring, employee evaluation and training evaluation to access \r\n                      the Demo for any of these, <strong><a href="#"><font size="1">click \r\n                      here</font></a></strong><font size="1">.</font><br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong> \r\n                      </font></p>\r\n                    <p><font size="2"><a name="5"></a></font><font size="2" align="justify">6</font><font size="2">.</font> \r\n                      <font size="2"><font color="#0f3f67">Can I conduct outstation \r\n                      tests?</font><br>\r\n                      Yes, you can easily conduct outstation tests as and when \r\n                      you require. All a candidate would need to do is to access \r\n                      the URL and fill in the Registered number provided by you. \r\n                      Scalebrain\'s STS is fully capable of delivering tests over \r\n                      the Internet <br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong> \r\n                      </font></p>\r\n                    <p><font size="2"><strong><a name="6"></a></strong></font><font size="2" align="justify">7</font><font size="2">.</font> \r\n                      <font size="2"><font color="#0f3f67">Can I change the look \r\n                      and feel of Scalebrain\'s STS?</font><br>\r\n                      Scalebrain\'s STS can be customized with your organization\'s \r\n                      logo and color scheme. The customization gives a personalized \r\n                      outlook to your assessment process and makes it specific \r\n                      to your style.<br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong> \r\n                      </font></p>\r\n                    <p><font size="2"><strong><a name="7"></a></strong></font><font size="2" align="justify">8</font><font size="2">.</font> \r\n                      <font size="2"><font color="#0f3f67">How can the administrator \r\n                      view the result?</font><br>\r\n                      </font><font size="2">The administrator can refer to the \r\n                      &quot;Report Management&quot; section for viewing the reports. \r\n                      In this section, the different formats of the reports are \r\n                      included. Effective and assorted reports provide an efficient \r\n                      means of screening and analyzing candidates\' performance \r\n                      not just on an individual level but in comparison to the \r\n                      entire group of test takers and with respect to a particular \r\n                      topic or even a question.<br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong> \r\n                      </font></p>\r\n                    <p><font size="2" align="justify"><strong><a name="8"></a></strong>9<strong>.</strong> \r\n                      <font color="#0f3f67">How is the scoring done?</font><br>\r\n                      Scoring is done according to the ratings provided to a candidate. \r\n                      Based on those ratings, the potential of a candidate can \r\n                      be judged.<br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong> \r\n                      </font></p>\r\n                    <p><font size="2" align="justify"><a name="10"></a>10. <font color="#0f3f67">What \r\n                      is self-assessment?</font><br>\r\n                      Before taking the test, the users are required to self-assess \r\n                      their knowledge and experience in technology areas. User \r\n                      can rate themselves on a scale of 2 to 4, depending upon \r\n                      their expertise in the technology areas against which they \r\n                      have to be assessed. They also specify the level of experience \r\n                      they possess in each of these technology areas.<br>\r\n                      <strong><a href="#88"><font size="1">Back to Top </font></a></strong></font></p>', '<font size="1"><strong><font color="#000000" size="2">Benefits to \r\n                      Companies </font></strong><br>\r\n                      Increase your chances of employing the right candidate quickly \r\n                      and the first time ,saving on the costs and time associated \r\n                      with recruiting. </font></p>\r\n                    <font size="1">\r\n                    <p> <strong><font color="#0f3f67">1. HOW? </font></strong></p>\r\n                    </font> \r\n                    <ul>\r\n                      <li><font size="1"> You select from those individuals who \r\n                        have already proved they have the technical ability in \r\n                        the areas you need.You get to chose from the best and \r\n                        you could even find a higher calibre individual then you \r\n                        would head hunting see rankings <br>\r\n                        <br>\r\n                        </font></li>\r\n                      <li><font size="1"> Important is it to be able to create \r\n                        your own skills tests, by selecting from a question databank? \r\n                        <br>\r\n                        </font></li>\r\n                    </ul>\r\n                    <ul>\r\n                      <li><font size="1">Is it important that test candidates \r\n                        have the ability to go back at the end of a test to re-try \r\n                        questions that they may have skipped?</font></li>\r\n                    </ul>\r\n                    <p align="justify"><font size="1"> <strong><font color="#0f3f67">2.</font></strong> \r\n                      The filtering is done at the technical level and not at \r\n                      the CV <br>\r\n                      level .So you automatically avoid those interviews where \r\n                      the time and effort was completely wasted ,because although \r\n                      the CV looks good the results of the technical test you \r\n                      set were poor. The time spent organising and executing a \r\n                      interview for one individual is considerable if you sit \r\n                      down and consider everthing that need to be done.</font></p>\r\n                    <p><font size="1"> <strong><font color="#0f3f67">3.</font></strong> \r\n                      Because the filtering has already been done to a large extent, \r\n                      you spend less time overall looking through CV\'s an ultimately \r\n                      you should recruit quicker.</font></p>\r\n                    <p><font size="1"> <strong><font color="#0f3f67">4.</font></strong> \r\n                      We will not disturb you for feedback and responses,so you \r\n                      fit in the recruitement process when it suits your scehedule. \r\n                      </font></p>\r\n                    <p><font size="1"><strong><font color="#0f3f67">5.</font></strong> \r\n                      Because you don\'t have to schedule a technical test, you \r\n                      also do not have to spend the time creating and marking \r\n                      them. </font></p>\r\n                    <p><font size="1"> <strong><font color="#0f3f67">6.</font></strong> \r\n                      Because our overheads are kept to a minimum this benefits \r\n                      you because the finders fees will typically be a lot less \r\n                      than other methods. </font></p>\r\n                    <font size="1"> \r\n                    <p><strong><font color="#0f3f67">7.</font> </strong>Why pay \r\n                      for advertising when you find a pool of good candidates \r\n                      from our site.</p>\r\n                    <p><strong><font color="#0f3f67">9. </font></strong>.We prefer \r\n                      the traditional method of recruitment where we select candidates \r\n                      from a number of CV\'s. </p>\r\n                    <p><strong><font color="#0f3f67">Can Scalebrain STS(Skill \r\n                      Testing Software) still help us ?</font></strong></p>\r\n                    <p align="justify"> For sure. We assume that you will always \r\n                      give someone a technical test so we provide an intuative \r\n                      tool that allows you to create your own web based tests, \r\n                      that are automaticaly marked .You can then schedule a test \r\n                      for a specific individual, who can take the test in your \r\n                      office or at home. Alternatively you may schedule one of \r\n                      the Calibrateit.com tests. In addition to the benefits highlighted \r\n                      above this feature also :</p>\r\n                    </font> <ul>\r\n                      <li><font size="1"> allows you to digitally record interview \r\n                        details including the scores acheived in the technical \r\n                        tests .<br>\r\n                        <br>\r\n                        </font></li>\r\n                      <li><font size="1">can be used to test your own staff e.g. \r\n                        after they have returned from a course ;and to identify \r\n                        training needs </font></li>\r\n                    </ul>', '      <p align="justify"><font size="2">Software4exam online <strong><font color="#0f3f67">Exam software</font></strong> provide a effective \r\n                      method of sharing knowledge within your organization or \r\n                      to outside participants. Our software will save your time, \r\n                      money and increase your staff productivity. </font></p>\r\n                    <p align="justify"><font size="2"> Software4exam Skills Testing \r\n                      and Assessments, provide confidence to the employees and \r\n                      focus areas on which to build greater competency, productivity \r\n                      and skill development for the individual. </font></p>\r\n                    <p><font size="2"><font color="#0f3f67">Since 2002</font><strong> \r\n                      </strong>these skills testing systems have provided the \r\n                      Staffing Industry, Corporate Human Resource Departments, \r\n                      Training Services and Educational Institutions with <font color="#0f3f67">software \r\n                      skills evaluation</font> and <font color="#0f3f67">training \r\n                      systems </font>for popular business applications on the \r\n                      Windows platform.</font></p>\r\n                    <p align="justify"><font size="2">These tests have helped \r\n                      thousands of organizations understand more about the real \r\n                      abilities of their job applicants and prospective trainees. \r\n                      Software4exam has a different, job-related, valid test for \r\n                      just about every position, and can design, customize and \r\n                      validate any test for any purpose.</font></p>\r\n\r\n\r\n<font color="#0f3f67" size="2">Software4exam</font><font size="2"> \r\n                      is a unique training system development platform with built-in \r\n                      Instructional Systems Development (ISD) tools that facilitate \r\n                      the establishment of a performance-based data foundation \r\n                      and integrated learning content objects. </font></p>\r\n                    <p><font size="2">Software4exam\'s  Learning Station is a specialized<font color="#0f3f67"> \r\n                      web-based learning </font>delivery program that connects \r\n                      to the data foundation to generate personalized learning \r\n                      and performance support solutions on demand.</font><br>\r\n\r\n\r\n<p><br></p>\r\n<p></p><p></p>\r\n<p></p>\r\n<p></p>\r\n<p></p>\r\n\r\n', '30b151aeb799da07664e25b94bb475a4logo.gif', '45a5d0b67943d426c354a7c0f1e7fb56logo.gif', '<a href="http://www.scalebrain.com" target="_blank" title="Acumen Software">\r\n           <img src="http://www.missionmasti.com/ian/images/acum_banner.gif" border="0">\r\n       </a>', 'No text', '<p><br></p>\r\n<table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">\r\n                <tr> \r\n                  <td valign="top"><p align="justify<p><font size="2"> They are by far one of the best programming \r\n                      companies I have ever worked with. Mine was a small project \r\n                      that turned out to be quite involved. They should have charged \r\n                      far more than they did because they certainly deserved it. \r\n                      Their price was great, but more importantly, their service \r\n                      was even better. If I EVER had ANY type of question or concern, \r\n                      they always responded promptly and addressed my concerns \r\n                      to my complete satisfaction. They are a real asset to the \r\n                      coding community. Customer satisfaction is obviously very \r\n                      important to them and it shows. </font></p>\r\n                    <p><font size="2">A+++</font></p>\r\n                    <p><font color="#0f3f67" size="1">Mark Cauley, Alabama, USA<br>\r\n                      January 16th 2004</font></p>\r\n                    <p><font size="1">This is a low cost way of increasing your \r\n                      employment prospects . Take an online exam and wait for \r\n                      the employment offers :- Ian Buckle (UK)</font></p>\r\n                    <p><font size="1">It seemed to me, that Acumen Software highest \r\n                      priority was, to satisfy the customer.<br>\r\n                      This way, it is really easy to make a project and it makes \r\n                      fun to work with them.<br>\r\n                      I definitely will use them again!!!<br>\r\n                      <font color="#0f3f67">Holger Hutzl, Germany<br>\r\n                      February 24th 2003</font></font><br>\r\n                    </p></td>\r\n                </tr>\r\n              </table>\r\n\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n<p><br></p>\r\n', 'No text', '<font size="2"><strong><font color="#000000" size="3">Benefits to \r\n                      Companies </font></strong><br>\r\n                      Increase your chances of employing the right candidate quickly \r\n                      and the first time ,saving on the costs and time associated \r\n                      with recruiting. </font></p>\r\n                    <font size="2">\r\n                    <p> <strong><font color="#0f3f67">1. HOW? </font></strong></p>\r\n                    </font> \r\n                    <ul>\r\n                      <li><font size="2"> You select from those individuals who \r\n                        have already proved they have the technical ability in \r\n                        the areas you need.You get to chose from the best and \r\n                        you could even find a higher calibre individual then you \r\n                        would head hunting see rankings <br>\r\n                        <br>\r\n                        </font></li>\r\n                      <li><font size="2"> Important is it to be able to create \r\n                        your own skills tests, by selecting from a question databank? \r\n                        <br>\r\n                        </font></li>\r\n                    </ul>\r\n                    <ul>\r\n                      <li><font size="2">Is it important that test candidates \r\n                        have the ability to go back at the end of a test to re-try \r\n                        questions that they may have skipped?</font></li>\r\n                    </ul>\r\n                    <p align="justify"><font size="2"> <strong><font color="#0f3f67">2.</font></strong> \r\n                      The filtering is done at the technical level and not at \r\n                      the CV <br>\r\n                      level .So you automatically avoid those interviews where \r\n                      the time and effort was completely wasted ,because although \r\n                      the CV looks good the results of the technical test you \r\n                      set were poor. The time spent organising and executing a \r\n                      interview for one individual is considerable if you sit \r\n                      down and consider everthing that need to be done.</font></p>\r\n                    <p><font size="2"> <strong><font color="#0f3f67">3.</font></strong> \r\n                      Because the filtering has already been done to a large extent, \r\n                      you spend less time overall looking through CV\'s an ultimately \r\n                      you should recruit quicker.</font></p>\r\n                    <p><font size="2"> <strong><font color="#0f3f67">4.</font></strong> \r\n                      We will not disturb you for feedback and responses,so you \r\n                      fit in the recruitement process when it suits your scehedule. \r\n                      </font></p>\r\n                    <p><font size="2"><strong><font color="#0f3f67">5.</font></strong> \r\n                      Because you don\'t have to schedule a technical test, you \r\n                      also do not have to spend the time creating and marking \r\n                      them. </font></p>\r\n                    <p><font size="2"> <strong><font color="#0f3f67">6.</font></strong> \r\n                      Because our overheads are kept to a minimum this benefits \r\n                      you because the finders fees will typically be a lot less \r\n                      than other methods. </font></p>\r\n                    <font size="2"> \r\n                    <p><strong><font color="#0f3f67">7.</font> </strong>Why pay \r\n                      for advertising when you find a pool of good candidates \r\n                      from our site.</p>\r\n                    <p><strong><font color="#0f3f67">9. </font></strong>.We prefer \r\n                      the traditional method of recruitment where we select candidates \r\n                      from a number of CV\'s. </p>\r\n                    <p><strong><font color="#0f3f67">Can Scalebrain STS(Skill \r\n                      Testing Software) still help us ?</font></strong></p>\r\n                    <p align="justify"> For sure. We assume that you will always \r\n                      give someone a technical test so we provide an intuative \r\n                      tool that allows you to create your own web based tests, \r\n                      that are automaticaly marked .You can then schedule a test \r\n                      for a specific individual, who can take the test in your \r\n                      office or at home. Alternatively you may schedule one of \r\n                      the Calibrateit.com tests. In addition to the benefits highlighted \r\n                      above this feature also :</p>\r\n                    </font> <ul>\r\n                      <li><font size="2"> allows you to digitally record interview \r\n                        details including the scores acheived in the technical \r\n                        tests .<br>\r\n                        <br>\r\n                        </font></li>\r\n                      <li><font size="2">can be used to test your own staff e.g. \r\n                        after they have returned from a course ;and to identify \r\n                        training needs </font></li>\r\n                    </ul>');

# Table structure for table `question_bank`

DROP TABLE IF EXISTS question_bank;
CREATE TABLE question_bank (
  id int(11) NOT NULL auto_increment,
  question mediumtext NOT NULL,
  option_1 mediumtext NOT NULL,
  option_2 mediumtext NOT NULL,
  option_3 mediumtext NOT NULL,
  option_4 mediumtext NOT NULL,
  option_5 mediumtext NOT NULL,
  explain_answer mediumtext NOT NULL,
  correct_answer mediumtext NOT NULL,
  question_counter int(11) NOT NULL default '0',
  image_flag char(1) NOT NULL default '',
  image_name varchar(255) NOT NULL default '',
  admin_flag char(1) NOT NULL default '0',
  question_type char(1) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  question_status char(1) NOT NULL default 'u',
  owner_user_ids mediumtext NOT NULL,
  created_date datetime NOT NULL default '0000-00-00 00:00:00',
  review_date datetime NOT NULL default '0000-00-00 00:00:00',
  update_note mediumtext NOT NULL,
  PRIMARY KEY  (id)
);

# Table structure for table `question_category`

DROP TABLE IF EXISTS question_category;
CREATE TABLE question_category (
  id int(11) NOT NULL auto_increment,
  question_id int(11) NOT NULL default '0',
  difficulty_level char(1) NOT NULL default '0',
  question_pool_id int(11) NOT NULL default '0',
  question_pool_position int(11) NOT NULL default '0',
  weighting int(11) NOT NULL default '0',
  skill_id int(11) NOT NULL default '0',
  topic_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

# Table structure for table `question_pool`

DROP TABLE IF EXISTS question_pool;
CREATE TABLE question_pool (
  id int(11) NOT NULL auto_increment,
  pool_name varchar(255) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  owned_by_depart int(11) NOT NULL default '0',
  description varchar(255) NOT NULL default '',
  status char(1) NOT NULL default '',
  creation_dt_time datetime NOT NULL default '0000-00-00 00:00:00',
  shared_depart varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `ranking`

DROP TABLE IF EXISTS ranking;
CREATE TABLE ranking (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  exam_id int(11) NOT NULL default '0',
  user_exam_id int(11) NOT NULL default '0',
  tot_ques int(3) NOT NULL default '0',
  att_ques int(3) NOT NULL default '0',
  correct_ans int(3) NOT NULL default '0',
  end_percent float(10,2) NOT NULL default '0.00',
  test_rank int(3) NOT NULL default '0',
  per_rank int(3) NOT NULL default '0',
  PRIMARY KEY  (id)
);

# Table structure for table `session_track`

DROP TABLE IF EXISTS session_track;
CREATE TABLE session_track (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  user_type varchar(20) NOT NULL default '0',
  session_id varchar(100) NOT NULL default '',
  in_time datetime NOT NULL default '0000-00-00 00:00:00',
  login_time datetime NOT NULL default '0000-00-00 00:00:00',
  logout_time datetime NOT NULL default '0000-00-00 00:00:00',
  ip_address varchar(50) NOT NULL default '',
  referrer_page varchar(100) NOT NULL default '',
  user_agant varchar(255) NOT NULL default '',
  java_enable char(1) NOT NULL default '',
  java_security_check varchar(10) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `skill`

DROP TABLE IF EXISTS skill;
CREATE TABLE skill (
  id int(11) NOT NULL auto_increment,
  pool_id int(11) NOT NULL default '0',
  skill_name varchar(255) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  description varchar(255) NOT NULL default '',
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `test_instance_admin_note`

DROP TABLE IF EXISTS test_instance_admin_note;
CREATE TABLE test_instance_admin_note (
  id int(11) NOT NULL auto_increment,
  user_exam_id int(11) NOT NULL default '0',
  admin_id int(11) NOT NULL default '0',
  comment varchar(255) NOT NULL default '',
  added_date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
);

# Table structure for table `test_schedule_user`

DROP TABLE IF EXISTS test_schedule_user;
CREATE TABLE test_schedule_user (
  id int(11) NOT NULL auto_increment,
  test_schedule_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

# Table structure for table `topic`

DROP TABLE IF EXISTS topic;
CREATE TABLE topic (
  id int(11) NOT NULL auto_increment,
  skill_id int(11) NOT NULL default '0',
  topic_name varchar(255) NOT NULL default '',
  added_by int(11) NOT NULL default '0',
  description varchar(255) NOT NULL default '',
  status char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `user_account`

DROP TABLE IF EXISTS user_account;
CREATE TABLE user_account (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  exam_id int(11) NOT NULL default '0',
  retake_flag char(1) NOT NULL default '',
  amount float(10,2) NOT NULL default '0.00',
  description varchar(255) NOT NULL default '',
  dr_cr_flag char(1) NOT NULL default '',
  date_time datetime NOT NULL default '0000-00-00 00:00:00',
  paid_unpaid char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `user_exam`

DROP TABLE IF EXISTS user_exam;
CREATE TABLE user_exam (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  exam_id int(11) NOT NULL default '0',
  test_schedule_id int(11) NOT NULL default '0',
  status varchar(10) NOT NULL default '',
  first_in_dt_time datetime NOT NULL default '0000-00-00 00:00:00',
  date_time datetime NOT NULL default '0000-00-00 00:00:00',
  last_max_dt_time datetime NOT NULL default '0000-00-00 00:00:00',
  time_taken float(10,2) NOT NULL default '0.00',
  pause_used int(3) NOT NULL default '0',
  PRIMARY KEY  (id)
);

# Table structure for table `user_exam_que_ans`

DROP TABLE IF EXISTS user_exam_que_ans;
CREATE TABLE user_exam_que_ans (
  id int(11) NOT NULL auto_increment,
  user_exam_id int(11) NOT NULL default '0',
  question_id int(11) NOT NULL default '0',
  answer varchar(255) NOT NULL default '',
  date_time datetime NOT NULL default '0000-00-00 00:00:00',
  end_result char(1) NOT NULL default '',
  PRIMARY KEY  (id)
);

# Table structure for table `user_reward_points`

DROP TABLE IF EXISTS user_reward_points;
CREATE TABLE user_reward_points (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  reward_point_id int(11) NOT NULL default '0',
  point_eairned int(11) NOT NULL default '0',
  admin_id int(11) NOT NULL default '0',
  notes varchar(255) NOT NULL default '',
  date_time datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
);

INSERT INTO `liecence` VALUES ('s_key', '4887648d4cc3101ec8f5c4711d7ade8f');
INSERT INTO `liecence` VALUES ('u_key', 'cc431fd7ec4437de061c2577a4603995');
INSERT INTO `liecence` VALUES ('d_key', '8c27f6b5dc0e9e5a536aa0371648fb4a');