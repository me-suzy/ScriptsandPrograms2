# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Servidor: localhost
# Tempo de Generação: Set 27, 2005 at 05:35 PM
# Versão do Servidor: 4.0.15
# Versão do PHP: 4.3.3
# 
# 

# --------------------------------------------------------

#
# Estrutura da tabela `x_contato`
#

CREATE TABLE `x_contato` (
  `x_contaid` int(11) NOT NULL auto_increment,
  `x_connome` varchar(100) default NULL,
  `x_con_mail` varchar(90) default NULL,
  `x_con_depa` varchar(90) NOT NULL default '',
  `x_conassunto` varchar(90) default NULL,
  `x_con_mensa` text,
  `x_conobserva` text,
  `x_constatus` varchar(90) default NULL,
  `x_condata` varchar(45) default NULL,
  `x_re_con_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`x_contaid`)
) TYPE=InnoDB AUTO_INCREMENT=43 ;

#
# Extraindo dados da tabela `x_contato`
#

INSERT INTO `x_contato` VALUES (41, 'admin', 'admin@admin.com.br', 'Outros', 'Email enviado pelo admin', 'Email enviado pelo admin', NULL, 'Aguardando', '24/09/2005 00:00:00', 0);
INSERT INTO `x_contato` VALUES (42, 'admin', 'admin@admin.com.br', 'Resposta', 'Re: Email enviado pelo admin', 'Email enviado pelo admin', NULL, 'Ok', '26/09/2005 00:00:00', 41);

# --------------------------------------------------------

#
# Estrutura da tabela `x_contato_conf`
#

CREATE TABLE `x_contato_conf` (
  `x_conf_id` int(11) NOT NULL auto_increment,
  `x_co_titulo` varchar(100) default NULL,
  `x_co_top` text,
  `x_co_remete` varchar(90) default 'email@email.com.br',
  `x_co_obrigado` text,
  `x_co_site` varchar(100) default NULL,
  `x_co_toporegi` text,
  `x_co_enviado` text,
  `x_co_novacon` text,
  `x_assu_novaco` varchar(100) NOT NULL default '',
  `x_assu_contat` varchar(100) NOT NULL default '',
  `x_assu_respo` varchar(100) NOT NULL default '',
  `x_assu_novaad` varchar(100) NOT NULL default '',
  `x_assu_conadm` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`x_conf_id`)
) TYPE=InnoDB AUTO_INCREMENT=2 ;

#
# Extraindo dados da tabela `x_contato_conf`
#

INSERT INTO `x_contato_conf` VALUES (1, 'Formulario de contato', 'Esse e o texto topo do contato. Editavel Pelo form na administracao\r\n', 'email@email.com.br', 'Seu envio foi recebido corretamente. Para editar seu envio e acessar o historico dos seus contatos, crie uma conta a seguir\r\n\r\n', 'http://localhost/contato/', 'Para acompanhar o historico do seu envio. Crie uma conta a seguir e acesse o seu painel de contatos.\r\n', 'Texto adicional de contato enviado recebido pelo usuario\r\n', 'Texto adicional do email de contato enviado, recebido pelo usuario\r\n', 'Re: Conta criada com sucesso !!', 'Re: Recebemos seu contato', 'Re: Resposta recebida', 'Recebemos um novo usuario', 'Recebemos um novo contato');

# --------------------------------------------------------

#
# Estrutura da tabela `x_contato_depa`
#

CREATE TABLE `x_contato_depa` (
  `x_depaid` int(11) NOT NULL auto_increment,
  `x_nomedepa` varchar(90) default NULL,
  PRIMARY KEY  (`x_depaid`)
) TYPE=InnoDB AUTO_INCREMENT=6 ;

#
# Extraindo dados da tabela `x_contato_depa`
#

INSERT INTO `x_contato_depa` VALUES (1, 'Comercial');
INSERT INTO `x_contato_depa` VALUES (2, 'Suporte');
INSERT INTO `x_contato_depa` VALUES (3, 'Pessoal');
INSERT INTO `x_contato_depa` VALUES (5, 'Outros');

# --------------------------------------------------------

#
# Estrutura da tabela `x_estados`
#

CREATE TABLE `x_estados` (
  `estado_id` int(11) NOT NULL auto_increment,
  `estado_nome` varchar(50) default NULL,
  PRIMARY KEY  (`estado_id`)
) TYPE=InnoDB AUTO_INCREMENT=28 ;

#
# Extraindo dados da tabela `x_estados`
#

INSERT INTO `x_estados` VALUES (1, 'Acre');
INSERT INTO `x_estados` VALUES (2, 'Alagoas');
INSERT INTO `x_estados` VALUES (3, 'Amapá');
INSERT INTO `x_estados` VALUES (4, 'Amazonas');
INSERT INTO `x_estados` VALUES (5, 'Bahia');
INSERT INTO `x_estados` VALUES (6, 'Ceará');
INSERT INTO `x_estados` VALUES (7, 'Distrito Federal');
INSERT INTO `x_estados` VALUES (8, 'Goiás');
INSERT INTO `x_estados` VALUES (9, 'Espírito Santo');
INSERT INTO `x_estados` VALUES (10, 'Maranhão');
INSERT INTO `x_estados` VALUES (11, 'Mato Grosso');
INSERT INTO `x_estados` VALUES (12, 'Mato Grosso do Sul');
INSERT INTO `x_estados` VALUES (13, 'Minas Gerais');
INSERT INTO `x_estados` VALUES (14, 'Pará');
INSERT INTO `x_estados` VALUES (15, 'Paraíba');
INSERT INTO `x_estados` VALUES (16, 'Paraná');
INSERT INTO `x_estados` VALUES (17, 'Pernambuco');
INSERT INTO `x_estados` VALUES (18, 'Piauí');
INSERT INTO `x_estados` VALUES (19, 'Rio de Janeiro');
INSERT INTO `x_estados` VALUES (20, 'Rio Grande do Norte');
INSERT INTO `x_estados` VALUES (21, 'Rio Grande do Sul');
INSERT INTO `x_estados` VALUES (22, 'Rondônia');
INSERT INTO `x_estados` VALUES (23, 'Roraima');
INSERT INTO `x_estados` VALUES (24, 'São Paulo');
INSERT INTO `x_estados` VALUES (25, 'Santa Catarina');
INSERT INTO `x_estados` VALUES (26, 'Sergipe');
INSERT INTO `x_estados` VALUES (27, 'Tocantins');

# --------------------------------------------------------

#
# Estrutura da tabela `x_grupos`
#

CREATE TABLE `x_grupos` (
  `group_id` int(11) NOT NULL default '0',
  `group_name` varchar(50) default NULL,
  PRIMARY KEY  (`group_id`)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `x_grupos`
#

INSERT INTO `x_grupos` VALUES (1, 'usuario');
INSERT INTO `x_grupos` VALUES (2, 'admin');

# --------------------------------------------------------

#
# Estrutura da tabela `x_paises`
#

CREATE TABLE `x_paises` (
  `pais_id` int(11) NOT NULL auto_increment,
  `pais_nome` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pais_id`)
) TYPE=InnoDB AUTO_INCREMENT=264 ;

#
# Extraindo dados da tabela `x_paises`
#

INSERT INTO `x_paises` VALUES (1, 'Afghanistan');
INSERT INTO `x_paises` VALUES (2, 'Albania');
INSERT INTO `x_paises` VALUES (3, 'Algeria');
INSERT INTO `x_paises` VALUES (4, 'American Samoa');
INSERT INTO `x_paises` VALUES (5, 'Andorra');
INSERT INTO `x_paises` VALUES (6, 'Angola');
INSERT INTO `x_paises` VALUES (7, 'Anguilla');
INSERT INTO `x_paises` VALUES (8, 'Antarctica');
INSERT INTO `x_paises` VALUES (9, 'Antigua and Barbuda');
INSERT INTO `x_paises` VALUES (10, 'Argentina');
INSERT INTO `x_paises` VALUES (11, 'Armenia');
INSERT INTO `x_paises` VALUES (12, 'Aruba');
INSERT INTO `x_paises` VALUES (13, 'Ashmore/Cartier Islands');
INSERT INTO `x_paises` VALUES (14, 'Australia');
INSERT INTO `x_paises` VALUES (15, 'Austria');
INSERT INTO `x_paises` VALUES (16, 'Azerbaijan');
INSERT INTO `x_paises` VALUES (17, 'Bahamas, The');
INSERT INTO `x_paises` VALUES (18, 'Bahrain');
INSERT INTO `x_paises` VALUES (19, 'Baker Island');
INSERT INTO `x_paises` VALUES (20, 'Bangladesh');
INSERT INTO `x_paises` VALUES (21, 'Barbados');
INSERT INTO `x_paises` VALUES (22, 'Bassas da India');
INSERT INTO `x_paises` VALUES (23, 'Belarus');
INSERT INTO `x_paises` VALUES (24, 'Belgium');
INSERT INTO `x_paises` VALUES (25, 'Belize');
INSERT INTO `x_paises` VALUES (26, 'Benin');
INSERT INTO `x_paises` VALUES (27, 'Bermuda');
INSERT INTO `x_paises` VALUES (28, 'Bhutan');
INSERT INTO `x_paises` VALUES (29, 'Bolivia');
INSERT INTO `x_paises` VALUES (30, 'Bosnia and Herzegovina');
INSERT INTO `x_paises` VALUES (31, 'Botswana');
INSERT INTO `x_paises` VALUES (32, 'Bouvet Island');
INSERT INTO `x_paises` VALUES (33, 'Brazil');
INSERT INTO `x_paises` VALUES (34, 'British Indian Ocean Territory');
INSERT INTO `x_paises` VALUES (35, 'British Virgin Islands');
INSERT INTO `x_paises` VALUES (36, 'Brunei');
INSERT INTO `x_paises` VALUES (37, 'Bulgaria');
INSERT INTO `x_paises` VALUES (38, 'Burkina Faso');
INSERT INTO `x_paises` VALUES (39, 'Burma');
INSERT INTO `x_paises` VALUES (40, 'Burundi');
INSERT INTO `x_paises` VALUES (41, 'Cambodia');
INSERT INTO `x_paises` VALUES (42, 'Cameroon');
INSERT INTO `x_paises` VALUES (43, 'Canada');
INSERT INTO `x_paises` VALUES (44, 'Cape Verde');
INSERT INTO `x_paises` VALUES (45, 'Cayman Islands');
INSERT INTO `x_paises` VALUES (46, 'Central African Republic');
INSERT INTO `x_paises` VALUES (47, 'Chad');
INSERT INTO `x_paises` VALUES (48, 'Chile');
INSERT INTO `x_paises` VALUES (49, 'China');
INSERT INTO `x_paises` VALUES (50, 'Christmas Island');
INSERT INTO `x_paises` VALUES (51, 'Clipperton Island');
INSERT INTO `x_paises` VALUES (52, 'Cocos (Keeling) Islands');
INSERT INTO `x_paises` VALUES (53, 'Colombia');
INSERT INTO `x_paises` VALUES (54, 'Comoros');
INSERT INTO `x_paises` VALUES (55, 'Congo, Democratic Republic of the');
INSERT INTO `x_paises` VALUES (56, 'Congo, Republic of the');
INSERT INTO `x_paises` VALUES (57, 'Cook Islands');
INSERT INTO `x_paises` VALUES (58, 'Coral Sea Islands');
INSERT INTO `x_paises` VALUES (59, 'Costa Rica');
INSERT INTO `x_paises` VALUES (60, 'Cote d\'Ivoire');
INSERT INTO `x_paises` VALUES (61, 'Croatia');
INSERT INTO `x_paises` VALUES (62, 'Cuba');
INSERT INTO `x_paises` VALUES (63, 'Cyprus');
INSERT INTO `x_paises` VALUES (64, 'Czech Republic');
INSERT INTO `x_paises` VALUES (65, 'Denmark');
INSERT INTO `x_paises` VALUES (66, 'Djibouti');
INSERT INTO `x_paises` VALUES (67, 'Dominica');
INSERT INTO `x_paises` VALUES (68, 'Dominican Republic');
INSERT INTO `x_paises` VALUES (69, 'East Timor');
INSERT INTO `x_paises` VALUES (70, 'Ecuador');
INSERT INTO `x_paises` VALUES (71, 'Egypt');
INSERT INTO `x_paises` VALUES (72, 'El Salvador');
INSERT INTO `x_paises` VALUES (73, 'Equatorial Guinea');
INSERT INTO `x_paises` VALUES (74, 'Eritrea');
INSERT INTO `x_paises` VALUES (75, 'Estonia');
INSERT INTO `x_paises` VALUES (76, 'Ethiopia');
INSERT INTO `x_paises` VALUES (77, 'Europa Island');
INSERT INTO `x_paises` VALUES (78, 'Falkland Islands (Islas Malvinas)');
INSERT INTO `x_paises` VALUES (79, 'Faroe Islands');
INSERT INTO `x_paises` VALUES (80, 'Fiji');
INSERT INTO `x_paises` VALUES (81, 'Finland');
INSERT INTO `x_paises` VALUES (82, 'France');
INSERT INTO `x_paises` VALUES (83, 'French Guiana');
INSERT INTO `x_paises` VALUES (84, 'French Polynesia');
INSERT INTO `x_paises` VALUES (85, 'French Southern and Antarctic Lands');
INSERT INTO `x_paises` VALUES (86, 'Gabon');
INSERT INTO `x_paises` VALUES (87, 'Gambia, The');
INSERT INTO `x_paises` VALUES (88, 'Gaza Strip');
INSERT INTO `x_paises` VALUES (89, 'Georgia');
INSERT INTO `x_paises` VALUES (90, 'Germany');
INSERT INTO `x_paises` VALUES (91, 'Ghana');
INSERT INTO `x_paises` VALUES (92, 'Gibraltar');
INSERT INTO `x_paises` VALUES (93, 'Glorioso Islands');
INSERT INTO `x_paises` VALUES (94, 'Greece');
INSERT INTO `x_paises` VALUES (95, 'Greenland');
INSERT INTO `x_paises` VALUES (96, 'Grenada');
INSERT INTO `x_paises` VALUES (97, 'Guadeloupe');
INSERT INTO `x_paises` VALUES (98, 'Guam');
INSERT INTO `x_paises` VALUES (99, 'Guatemala');
INSERT INTO `x_paises` VALUES (100, 'Guernsey');
INSERT INTO `x_paises` VALUES (101, 'Guinea');
INSERT INTO `x_paises` VALUES (102, 'Guinea-Bissau');
INSERT INTO `x_paises` VALUES (103, 'Guyana');
INSERT INTO `x_paises` VALUES (104, 'Haiti');
INSERT INTO `x_paises` VALUES (105, 'Heard Island and McDonald Islands');
INSERT INTO `x_paises` VALUES (106, 'Holy See (Vatican City)');
INSERT INTO `x_paises` VALUES (107, 'Honduras');
INSERT INTO `x_paises` VALUES (108, 'Hong Kong');
INSERT INTO `x_paises` VALUES (109, 'Howland Island');
INSERT INTO `x_paises` VALUES (110, 'Hungary');
INSERT INTO `x_paises` VALUES (111, 'Iceland');
INSERT INTO `x_paises` VALUES (112, 'India');
INSERT INTO `x_paises` VALUES (113, 'Indonesia');
INSERT INTO `x_paises` VALUES (114, 'Iran');
INSERT INTO `x_paises` VALUES (115, 'Iraq');
INSERT INTO `x_paises` VALUES (116, 'Ireland');
INSERT INTO `x_paises` VALUES (117, 'Israel');
INSERT INTO `x_paises` VALUES (118, 'Italy');
INSERT INTO `x_paises` VALUES (119, 'Jamaica');
INSERT INTO `x_paises` VALUES (120, 'Jan Mayen');
INSERT INTO `x_paises` VALUES (121, 'Japan');
INSERT INTO `x_paises` VALUES (122, 'Jarvis Island');
INSERT INTO `x_paises` VALUES (123, 'Jersey');
INSERT INTO `x_paises` VALUES (124, 'Johnston Atoll');
INSERT INTO `x_paises` VALUES (125, 'Jordan');
INSERT INTO `x_paises` VALUES (126, 'Juan de Nova Island');
INSERT INTO `x_paises` VALUES (127, 'Kazakhstan');
INSERT INTO `x_paises` VALUES (128, 'Kenya');
INSERT INTO `x_paises` VALUES (129, 'Kingman Reef');
INSERT INTO `x_paises` VALUES (130, 'Kiribati');
INSERT INTO `x_paises` VALUES (131, 'Korea, North');
INSERT INTO `x_paises` VALUES (132, 'Korea, South');
INSERT INTO `x_paises` VALUES (133, 'Kuwait');
INSERT INTO `x_paises` VALUES (134, 'Kyrgyzstan');
INSERT INTO `x_paises` VALUES (135, 'Laos');
INSERT INTO `x_paises` VALUES (136, 'Latvia');
INSERT INTO `x_paises` VALUES (137, 'Lebanon');
INSERT INTO `x_paises` VALUES (138, 'Lesotho');
INSERT INTO `x_paises` VALUES (139, 'Liberia');
INSERT INTO `x_paises` VALUES (140, 'Libya');
INSERT INTO `x_paises` VALUES (141, 'Liechtenstein');
INSERT INTO `x_paises` VALUES (142, 'Lithuania');
INSERT INTO `x_paises` VALUES (143, 'Luxembourg');
INSERT INTO `x_paises` VALUES (144, 'Macau');
INSERT INTO `x_paises` VALUES (145, 'Macedonia');
INSERT INTO `x_paises` VALUES (146, 'Madagascar');
INSERT INTO `x_paises` VALUES (147, 'Malawi');
INSERT INTO `x_paises` VALUES (148, 'Malaysia');
INSERT INTO `x_paises` VALUES (149, 'Maldives');
INSERT INTO `x_paises` VALUES (150, 'Mali');
INSERT INTO `x_paises` VALUES (151, 'Malta');
INSERT INTO `x_paises` VALUES (152, 'Man, Isle of');
INSERT INTO `x_paises` VALUES (153, 'Marshall Islands');
INSERT INTO `x_paises` VALUES (154, 'Martinique');
INSERT INTO `x_paises` VALUES (155, 'Mauritania');
INSERT INTO `x_paises` VALUES (156, 'Mauritius');
INSERT INTO `x_paises` VALUES (157, 'Mayotte');
INSERT INTO `x_paises` VALUES (158, 'Mexico');
INSERT INTO `x_paises` VALUES (159, 'Micronesia, Federated States of');
INSERT INTO `x_paises` VALUES (160, 'Midway Islands');
INSERT INTO `x_paises` VALUES (161, 'Moldova');
INSERT INTO `x_paises` VALUES (162, 'Monaco');
INSERT INTO `x_paises` VALUES (163, 'Mongolia');
INSERT INTO `x_paises` VALUES (164, 'Montserrat');
INSERT INTO `x_paises` VALUES (165, 'Morocco');
INSERT INTO `x_paises` VALUES (166, 'Mozambique');
INSERT INTO `x_paises` VALUES (167, 'Namibia');
INSERT INTO `x_paises` VALUES (168, 'Nauru');
INSERT INTO `x_paises` VALUES (169, 'Navassa Island');
INSERT INTO `x_paises` VALUES (170, 'Nepal');
INSERT INTO `x_paises` VALUES (171, 'Netherlands');
INSERT INTO `x_paises` VALUES (172, 'Netherlands Antilles');
INSERT INTO `x_paises` VALUES (173, 'New Caledonia');
INSERT INTO `x_paises` VALUES (174, 'New Zealand');
INSERT INTO `x_paises` VALUES (175, 'Nicaragua');
INSERT INTO `x_paises` VALUES (176, 'Niger');
INSERT INTO `x_paises` VALUES (177, 'Nigeria');
INSERT INTO `x_paises` VALUES (178, 'Niue');
INSERT INTO `x_paises` VALUES (179, 'Norfolk Island');
INSERT INTO `x_paises` VALUES (180, 'Northern Mariana Islands');
INSERT INTO `x_paises` VALUES (181, 'Norway');
INSERT INTO `x_paises` VALUES (182, 'Oman');
INSERT INTO `x_paises` VALUES (183, 'Pakistan');
INSERT INTO `x_paises` VALUES (184, 'Palau');
INSERT INTO `x_paises` VALUES (185, 'Palmyra Atoll');
INSERT INTO `x_paises` VALUES (186, 'Panama');
INSERT INTO `x_paises` VALUES (187, 'Papua New Guinea');
INSERT INTO `x_paises` VALUES (188, 'Paracel Islands');
INSERT INTO `x_paises` VALUES (189, 'Paraguay');
INSERT INTO `x_paises` VALUES (190, 'Peru');
INSERT INTO `x_paises` VALUES (191, 'Philippines');
INSERT INTO `x_paises` VALUES (192, 'Pitcairn Islands');
INSERT INTO `x_paises` VALUES (193, 'Poland');
INSERT INTO `x_paises` VALUES (194, 'Portugal');
INSERT INTO `x_paises` VALUES (195, 'Puerto Rico');
INSERT INTO `x_paises` VALUES (196, 'Qatar');
INSERT INTO `x_paises` VALUES (197, 'Reunion');
INSERT INTO `x_paises` VALUES (198, 'Romania');
INSERT INTO `x_paises` VALUES (199, 'Russia');
INSERT INTO `x_paises` VALUES (200, 'Rwanda');
INSERT INTO `x_paises` VALUES (201, 'Saint Helena');
INSERT INTO `x_paises` VALUES (202, 'Saint Kitts and Nevis');
INSERT INTO `x_paises` VALUES (203, 'Saint Lucia');
INSERT INTO `x_paises` VALUES (204, 'Saint Pierre and Miquelon');
INSERT INTO `x_paises` VALUES (205, 'Saint Vincent and the Grenadines');
INSERT INTO `x_paises` VALUES (206, 'Samoa');
INSERT INTO `x_paises` VALUES (207, 'San Marino');
INSERT INTO `x_paises` VALUES (208, 'Sao Tome and Principe');
INSERT INTO `x_paises` VALUES (209, 'Saudi Arabia');
INSERT INTO `x_paises` VALUES (210, 'Senegal');
INSERT INTO `x_paises` VALUES (211, 'Serbia and Montenegro');
INSERT INTO `x_paises` VALUES (212, 'Seychelles');
INSERT INTO `x_paises` VALUES (213, 'Sierra Leone');
INSERT INTO `x_paises` VALUES (214, 'Singapore');
INSERT INTO `x_paises` VALUES (215, 'Slovakia');
INSERT INTO `x_paises` VALUES (216, 'Slovenia');
INSERT INTO `x_paises` VALUES (217, 'Solomon Islands');
INSERT INTO `x_paises` VALUES (218, 'Somalia');
INSERT INTO `x_paises` VALUES (219, 'South Africa');
INSERT INTO `x_paises` VALUES (220, 'South Georgia/South Sandwich Islands');
INSERT INTO `x_paises` VALUES (221, 'Spain');
INSERT INTO `x_paises` VALUES (222, 'Spratly Islands');
INSERT INTO `x_paises` VALUES (223, 'Sri Lanka');
INSERT INTO `x_paises` VALUES (224, 'Sudan');
INSERT INTO `x_paises` VALUES (225, 'Suriname');
INSERT INTO `x_paises` VALUES (226, 'Svalbard');
INSERT INTO `x_paises` VALUES (227, 'Swaziland');
INSERT INTO `x_paises` VALUES (228, 'Sweden');
INSERT INTO `x_paises` VALUES (229, 'Switzerland');
INSERT INTO `x_paises` VALUES (230, 'Syria');
INSERT INTO `x_paises` VALUES (231, 'Taiwan');
INSERT INTO `x_paises` VALUES (232, 'Tajikistan');
INSERT INTO `x_paises` VALUES (233, 'Tanzania');
INSERT INTO `x_paises` VALUES (234, 'Thailand');
INSERT INTO `x_paises` VALUES (235, 'Togo');
INSERT INTO `x_paises` VALUES (236, 'Tokelau');
INSERT INTO `x_paises` VALUES (237, 'Tonga');
INSERT INTO `x_paises` VALUES (238, 'Trinidad and Tobago');
INSERT INTO `x_paises` VALUES (239, 'Tromelin Island');
INSERT INTO `x_paises` VALUES (240, 'Tunisia');
INSERT INTO `x_paises` VALUES (241, 'Turkey');
INSERT INTO `x_paises` VALUES (242, 'Turkmenistan');
INSERT INTO `x_paises` VALUES (243, 'Turks and Caicos Islands');
INSERT INTO `x_paises` VALUES (244, 'Tuvalu');
INSERT INTO `x_paises` VALUES (245, 'Uganda');
INSERT INTO `x_paises` VALUES (246, 'Ukraine');
INSERT INTO `x_paises` VALUES (247, 'United Arab Emirates');
INSERT INTO `x_paises` VALUES (248, 'United Kingdom');
INSERT INTO `x_paises` VALUES (249, 'United States');
INSERT INTO `x_paises` VALUES (250, 'US Minor Outlying Islands');
INSERT INTO `x_paises` VALUES (251, 'Uruguay');
INSERT INTO `x_paises` VALUES (252, 'Uzbekistan');
INSERT INTO `x_paises` VALUES (253, 'Vanuatu');
INSERT INTO `x_paises` VALUES (254, 'Venezuela');
INSERT INTO `x_paises` VALUES (255, 'Vietnam');
INSERT INTO `x_paises` VALUES (256, 'Virgin Islands');
INSERT INTO `x_paises` VALUES (257, 'Wake Island');
INSERT INTO `x_paises` VALUES (258, 'Wallis and Futuna');
INSERT INTO `x_paises` VALUES (259, 'West Bank');
INSERT INTO `x_paises` VALUES (260, 'Western Sahara');
INSERT INTO `x_paises` VALUES (261, 'Yemen');
INSERT INTO `x_paises` VALUES (262, 'Zambia');
INSERT INTO `x_paises` VALUES (263, 'Zimbabwe');

# --------------------------------------------------------

#
# Estrutura da tabela `x_resposta`
#

CREATE TABLE `x_resposta` (
  `x_reaid` int(11) NOT NULL auto_increment,
  `x_re_nome` varchar(100) default NULL,
  `x_re_mail` varchar(90) default NULL,
  `x_re_depa` varchar(90) NOT NULL default '',
  `x_reassunto` varchar(90) default NULL,
  `x_re_mensa` text,
  `x_re_observa` text,
  `x_re_status` varchar(90) default NULL,
  `x_re_data` varchar(45) default NULL,
  `re_co_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`x_reaid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

#
# Extraindo dados da tabela `x_resposta`
#


# --------------------------------------------------------

#
# Estrutura da tabela `x_status`
#

CREATE TABLE `x_status` (
  `x_statuid` int(11) NOT NULL auto_increment,
  `x_status` varchar(90) default NULL,
  PRIMARY KEY  (`x_statuid`)
) TYPE=InnoDB AUTO_INCREMENT=3 ;

#
# Extraindo dados da tabela `x_status`
#

INSERT INTO `x_status` VALUES (1, 'Aguardando');
INSERT INTO `x_status` VALUES (2, 'Ok');

# --------------------------------------------------------

#
# Estrutura da tabela `x_usuarios`
#

CREATE TABLE `x_usuarios` (
  `user_id` int(11) NOT NULL auto_increment,
  `x_apelido` varchar(50) default NULL,
  `x_senha` varchar(50) default NULL,
  `x_nome` varchar(50) default NULL,
  `x_sobrenome` varchar(50) default NULL,
  `x_titulo` varchar(50) default NULL,
  `group_id` int(11) default NULL,
  `x_fone` varchar(50) default NULL,
  `phone_work` varchar(50) default NULL,
  `phone_day` varchar(50) default NULL,
  `x_celular` varchar(50) default NULL,
  `x_comentarios` text,
  `fax` varchar(50) default NULL,
  `x_email` varchar(90) default NULL,
  `x_notas` text,
  `x_card_num` varchar(50) default NULL,
  `x_card_expira` varchar(50) default NULL,
  `pais_id` int(11) default NULL,
  `estado_id` int(11) default NULL,
  `x_cidade` varchar(50) default NULL,
  `x_cep` varchar(50) default NULL,
  `x_endere1` text,
  `x_endere2` varchar(50) default NULL,
  `x_endere3` varchar(50) default NULL,
  `date_add` varchar(45) default NULL,
  `date_last_login` timestamp(14) NOT NULL,
  `ip_add` varchar(50) default NULL,
  `ip_update` varchar(50) default NULL,
  `x_categoria` varchar(90) default NULL,
  `image_url` varchar(50) default NULL,
  `x_idade` int(11) default NULL,
  `gender_id` int(11) default NULL,
  `education_id` int(11) default NULL,
  `x_status2` varchar(90) default NULL,
  `x_status3` varchar(90) default NULL,
  PRIMARY KEY  (`user_id`)
) TYPE=InnoDB AUTO_INCREMENT=16 ;

#
# Extraindo dados da tabela `x_usuarios`
#

INSERT INTO `x_usuarios` VALUES (1, 'visita', 'visita', 'Visitante', 'Membro', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'visitante@visitante.com', NULL, NULL, NULL, 249, 2, 'São Paulo', '94080', NULL, NULL, NULL, '2000-11-24 17:41:00', 20050919191205, '195.24.149.53', NULL, '1', NULL, NULL, 1, NULL, '1', NULL);
INSERT INTO `x_usuarios` VALUES (14, 'marcelo', '123456', 'Marcelo Couto Campos', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'email@email.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20050925174030, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `x_usuarios` VALUES (15, 'admin', 'admin', NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, 'admin@admin.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20050919190054, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
    