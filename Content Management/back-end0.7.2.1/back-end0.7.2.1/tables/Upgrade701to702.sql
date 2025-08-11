UPDATE psl_variable SET value="0.7.0.2" WHERE variable_id='100';

# Nov 13th - Bug fix for related articles block contributed by IanC at CUPE

create table be_keyword2article(
   keyword varchar(32) not null,
   articleID int(11) not null,   
   languageID char(3) not null,   
   index keyword(keyword),   
   index articleID(articleID),   
   index languageID(languageID)
);