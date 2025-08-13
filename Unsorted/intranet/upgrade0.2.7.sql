    CREATE TABLE webcal_user_layers (
      cal_layerid INT DEFAULT '0' NOT NULL,
      cal_login varchar(25) NOT NULL,
      cal_layeruser varchar(25) NOT NULL,
      cal_color varchar(25) NULL,
      cal_dups CHAR(1) DEFAULT 'N',
      PRIMARY KEY ( cal_login, cal_layeruser )
    );
