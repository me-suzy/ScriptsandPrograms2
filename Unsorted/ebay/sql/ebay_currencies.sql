/*
 * ebay_currencies.sql
 *
 * This table contains our knowledge about different
 * currencies and how they relate to the product. 
 */

drop table ebay_currencies;

create table ebay_currencies
(
id				number(3)
    constraint   currencies_id_nn
          not null,
currency		varchar2(63)
	constraint	currencies_currency_nn
		not null,
currency_pl		varchar2(63)
	constraint	currencies_currency_pl_nn
		not null,
symbol			varchar2(15)
	constraint	currencies_symbol_nn
		not null,
sub_currency	varchar2(63)
	constraint	currencies_sub_currency_nn
		not null,
sub_currency_pl	varchar2(63)
	constraint	currencies_sub_currency_pl_nn
		not null,
sub_currency_ratio number(6)
	constraint  currencies_ratio_nn
	    not null,
iso_4217		varchar2(7),
name_res_id		number(5)
	constraint currencies_name_res_id_nn
		not null
)
tablespace tmiscd01
storage (initial 10K next 5K pctincrease 0);

//tablespace qcurrenciesd01
//storage (initial 10K next 5K pctincrease 0);

/* Space calculation: */
/* key:   2 bytes (?) x 200 currencies =  .5 K  */
/* table: 42 bytes x 200 countries 	  = 8.5K   */

alter table ebay_currencies
	add constraint		currencies_pk
		primary key		(id)
		using index tablespace tmiscd01
		storage (initial 2K next 1K);

// rcurrenciesi01

// Add a column for email_currency_symbol
// numeric, copied into a char
// 0x24 for dollar
// 0xA3 for pound
// 0xA5 for yen

commit;

// United States
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (1, 'U.S. dollar', 'U.S. dollars', 'US$', 'cent', 'cents', 100, 'USD 840', 0);

// Canada
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (2, 'Canadian dollar', 'Canadian dollars', 'CAD$', 'cent', 'cents', 100, 'CAD 124', 0);

// United Kingdom
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (3, 'Pound Sterling', 'Pounds Sterling', '&pound;', 'pence', 'pence', 100, 'GBP 826', 0);

// Germany
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (4, 'German Mark', 'German Marks', 'DM', 'Pfennig', 'Pfennige', 100, 'DEM 280', 0);

// Australia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (5, 'Australian dollar', 'Australian dollars', 'AU$', 'cent', 'cents', 100, 'AUD 036', 0);

// Japan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (6, 'Japanese yen', 'Japanese yen', 'JP&yen;', ' ', ' ', 0, 'JPY 392', 0);

// Euro
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (7, 'Euro', 'Euros', '&euro;', ' ', ' ', 100, 'EUR 954', 0);

// France
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (8, 'French franc', 'French francs', 'FRF', 'centime', 'centimes', 100, 'FRF 250', 0);

// China
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (14, 'Chinese yuan renminbi', 'Chinese yuan renminbi', 'CNY', 'jiao', 'jiao', 10, 'CNY 156', 0);

// Denmark
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (17, 'Danish krone', 'Danish krones', 'DKK', '&oslash;re', '&oslash;re', 100, 'DKK 208', 0);

// Spain
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (19, 'Spanish peseta', 'Spanish peseta', 'ESP', 'centimos', 'centimos', 100, 'ESP 724', 0);

// Finland
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (20, 'Finnish markka', 'Finnish markkas', 'FIM', 'penni&auml;', 'penni&auml;', 10, 'FIM 246', 0);

// Norway
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (32, 'Norwegian krone', 'Norwegian krone', 'NOK', '&oslash;re', '&oslash;re', 100,  'NOK 578', 0);

// Sweden (krona pl kronor)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (38, 'Swedish krona', 'Swedish kronor', 'SEK', '&ouml;re', '&ouml;re', 100, 'SEK 752', 0);

----
// Afghanistan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (4, 'afghani', 'afghani', 'Af', 'puls', 'puls', 100, 'AFA 004', 0);

// Albania
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (5, 'lek', 'lek', 'L', 'qindarka', 'qintars', 100, ALL 008', 0);

// Algeria
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (6, 'dinar', 'dinar', 'DA', 'centimes', 'centimes', 100, 'DZD 012', 0);

// American Somoa
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (7, 'dollar', 'dollars', '$', 'cent', 'cents', 100, 'USD 840', 0);

// Andorra, 1
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (8, 'peseta', 'pesetas', 'Ptas', 'centimos', 'centimos', 100, 'ESP 724', 0);

/*
// Andorra, 2
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (8, 'franc', 'francs', 'F', 'centimes', 'centimes', 100, 'FRF 250', 0);
*/

// Angola
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (9, 'new kwanza', 'new kwanzas', 'Kz', 'lwei', 'lwei', 100, 'AON 024', 0);

// Anguilla
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (10, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// Antigua and Barbuda
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (11, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// Argentia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (12, 'peso', 'pesos', '$', 'centavos', 'centavos', 100, 'ARS 032', 0);

// Armenia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (13, 'dram', 'drams', ' ', 'luma', 'luma', 100, 'AMD 051', 0);

// Aruba
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (14, 'guilder', 'guilder', 'Af.', 'cent', 'cents', 100, 'AWG 533', 0);


// Austria
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (16, 'schilling', 'schillings', 'S', 'groschen', 'groschen', 100, 'ATS 040', 0);

// Azerbaijan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (17, 'manat', 'manat', ' ', 'gopik', 'gopik', 100, 'AZM 031', 0);

// Bahamas
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (18, 'dollar', 'dollars', 'B$', 'cent', 'cents', 100, 'BSD 044', 0);

// Bahrain
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (19, 'dinar', 'dinar', 'BD', 'fils', 'fils', 1000, 'BHD 048', 0);

// Bangladesh
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (20, 'taka', 'taka', 'Tk', 'paisa', 'paisa', 100, 'BDT 050', 0);

// Barbados
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (21, 'dollar', 'dollars', 'Bds$', 'cent', 'cents', 100, 'BBD 052', 0);

// Belarus
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (22, 'ruble', 'rubles', 'BR', ' ', ' ', 0, 'BYB 112', 0);

// Belgium
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (23, 'franc', 'francs', 'BF', 'centimes', 'centimes', 100, 'BEF 056', 0);

// Belize
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (24, 'dollar', 'dollars', 'BZ$', 'cent', 'cents', 100, 'BZD 084', 0);

// Benin
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (25, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XOF 952', 0);

// Bermuda
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (26, 'dollar', 'dollars', 'Bd$', 'cent', 'cents', 100, 'BMD 060', 0);

// Bhutan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (27, 'ngultrum', 'ngultrums', 'Nu', 'chetrum', 'chetrum', 100, 'BTN 064', 0);

// Bolivia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (28, 'boliviano', 'bolivianos', 'Bs', 'centavos', 'centavos', 100, 'BOB 068', 0);

// Bosnia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (29, 'B.H. dinar', 'B.H. dinars', ' ', 'para', 'para', 100, 'BAD 070', 0);

// Botswana
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (30, 'pula', 'pulas', 'P', 'thebe', 'thebe', 100, 'BWP 072', 0);

// Brazil
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (31, 'real', 'reals', 'R$', 'centavos', 'centavos', 100, 'BRL 076', 0);

// British Virgin Islands
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (32, 'dollar', 'dollars', 'US$', 'cent', 'cents', 100, 'USD 840', 0);

// Brunei
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (33, 'ringgit', 'ringgit', 'B$', 'sen', 'sen', 100, 'BND 096', 0);

// Bulgaria
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (34, 'leva', 'levas', 'Lv', 'stotinki', 'stotinki', 100, 'BGL 100', 0);

// Burkina Faso
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (35, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XOF 952', 0);

// Burma (Myanmar?)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (36, 'kyat', 'kyats', 'K', 'pyas', 'pyas', 100, 'MMK 104', 0);

// Burundi
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (37, 'franc', 'francs', 'FBu', 'centimes', 'centimes', 100, 'BIF 108', 0);

// Cambodia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (38, 'new riel', 'new riels', 'CR', 'sen', 'sen', 100, 'KHR 116', 0);

// Cameroon
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (39, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XAF 950', 0);


// Cape Verde Islands
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (40, 'escudo', 'escudos', 'C.V.Esc.', 'centavos', 'centavos', 100, 'CVE 132', 0);

// Cayman Islands
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (41, 'dollar', 'dollars', 'CI$', 'cent', 'cents', 100, 'KYD 136', 0);

// Central African Republic
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (42, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XAF 950', 0);

// Chad
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (43, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XAF 950', 0);

// Chile
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (44, 'peso', 'pesos', 'Ch$', 'centavos', 'centavos', 100, 'XLP 152', 0);



// Colombia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (46, 'peso', 'pesos', 'Col$', 'centavos', 'centavos', 100, 'COP 170', 0);

// Comoros
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (47, 'franc', 'francs', 'CF', ' ', 0, 'KMF 174', 0);

// Zaire is next:
// Congo
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (48, 'franc', 'francs', ' ', 'centimes', 100, 'CDF 180', 0);

// Congo:
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (49, 'franc', 'francs', 'CFAF', 'centimes', 100, 'XAF 950', 0);

// Cook Islands
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (50, 'dollar', 'NZ$', 'cent', 'cents', 100, 'NZD 554', 0);

// Costa Rica
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (51, 'colon', 'slashed C', 'centimos', 'centimos', 100, 'CRC 188', 0);

// Cote d' Ivoire
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (52, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XOF 952', 0);

// Croatia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (53, 'kuna', 'kuna', 'HRK', 'lipas', 'lipas', 100, 'HRK 191', 0);

// Cuba
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (54, 'peso', 'pesos', 'Cu$', 'centavos', 'centavos', 100, 'CUP 192', 0);

// Cyprus
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (55, 'pound', 'pounds', '&pound;C', 'cent', 'cents', 100, 'CYP 196', 0);

// Czech
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (56, 'koruna', 'korunas', 'Kc', 'haleru', 'haleru', 100, 'CZK 203', 0);


// Djibouti
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (58, 'franc', 'francs', 'DF', 'centimes', 'centimes', 100, 'DJF 262', 0);

// Dominica
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (59, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// Dominican Republic
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (60, 'peso', 'pesos', 'RD$', 'centavos', 'centavos', 100, 'DOP 214', 0);

// Ecuador
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (61, 'sucre', 'sucres', 'S/', 'centavos', 'centavos', 100, 'ECS 218', 0);

// Egypt
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (62, 'pound', 'pounds', '&pound;E', 'piasters', 'piasters', 100, 'EGP 818', 0);

// El Salvador
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (63, 'colon', 'colons', '&cent;', 'centavos', 'centavos', 100, 'SVC 222', 0);

// Equatorial Guinea
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (64, 'ekwele', 'ekwele', 'CFAF', 'centimos', 'centimos', 100, 'GQE 226', 0);

// Eritrea
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (65, 'nakfa', 'nakfas', 'Nfa', 'cent', 'cents', 100, 'ERN 232', 0);

// Estonia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (66, 'kroon', 'kroons', 'KR', 'senti', 'senti', 100, 'EEK 233', 0);

// Ethiopia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (67, 'birr', 'birrs', 'Br', 'cent', 'cents', 100, 'ETB 231', 0);

// Falkland Islands
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (68, 'pound', 'pounds', '&pound;F', 'pence', 'pence', 100, 'FKP 238', 0);

// Fiji
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (69, 'dollar', 'dollars', 'F$', 'cent', 'cents', 100, 'FJD 242', 0);



// French Guiana
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (72, 'franc', 'francs', 'F', 'centimes', 'centimes', 100, 'FRF 250', 0);

// French Polynesia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (73, 'franc', 'francs', 'CFPF', 'centimes', 'centimes', 100, 'XPF 953', 0);

// Gabon
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (74, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XAF 950', 0);

// Gambia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (75, 'dalasi', 'dalasis', 'D', 'butut', 'butut', 100, 'GMD 270', 0);

// Georgia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (76, 'lari', 'laris', ' ',  'tetri', 'tetri', 100, 'GEL 268', 0);


// Ghana
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (78, 'new cdei', 'new cdeis', '&cent;', 'psewas', 'psewas', 100, 'GHC 288', 0);

// Gibraltar
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (79, 'pound', 'pounds', '&pound;', 'pence', 'pence', 100, 'GIP 292', 0);

// Greece
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (80, 'drachma', 'drachmas', 'Dr', 'lepta', 'lepta', 100, 'GRD 300', 0);

// Greenland (same as Denmark)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (81, 'krone', 'krones', 'Dkr', '&oslash;re', '&oslash;re', 100, 'DKK 208', 0);

// Grenada
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (82, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// Guadeloupe (same as France)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (83, 'franc', 'francs', 'F', 'centimes', 'centimes', 100, 'FRF 250', 0);

// Guam (same as US)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (84, 'dollar', 'dollars', '$', 'cent', 'cents', 100, 'USD 840', 0);

// Guatemala
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (85, 'quetzal', 'quetzals', 'Q', 'centavos', 'centavos', 100, 'GTQ 320', 0);

// Guernsey (same as United Kingdom)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (86, 'pound', 'pounds', '&pound;', 'pence', 'pence', 100, 'GBP 826', 0);

// Guinea (1 franc = 100 centimes)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (87, 'syli', 'sylis', 'FG', 'franc', 'francs', 10, 'GNS 324', 0);

// Guinea-Bissau
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (88, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XAF 950', 0);

// Guyana
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (89, 'dollar', 'dollars', 'G$', 'cent', 'cents', 100, 'GYD 328', 0);

// Haiti
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (90, 'gourde', 'gourde', 'G', 'centimes', 'centimes', 100, 'HTG 332', 0);

// Honduras
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (91, 'lempira', 'lempira', 'L', 'centavos', 'centavos', 100, 'HNL 340', 0);

// Hong Kong
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (92, 'dollar', 'dollars', 'HK$', 'cent', 'cents', 100, 'HKD 344', 0);

// Hungary
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (93, 'forint', 'forint', 'Ft', ' ', ' ', 100, 'HUF 348', 0);

// Iceland
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (94, 'kr&oacute;na', 'kr&oacute;na', 'IKr', 'aurar', 'aurar', 100, 'ISk 352', 0);

// India
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (95, 'rupee', 'rupee', 'Rs', 'paise', 'paise', 100, 'INR 356', 0);

// Indonesia (100 sen = rupiah = no longer used)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (96, 'rupiah', 'rupiah', 'Rp', ' ', ' ', 0, 'IDR 360', 0);

// Iran (10 rials = 1 toman)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (97, 'rial', 'rial', 'Rls', ' ', ' ', 0, 'IRR 364', 0);

// Iraq
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (98, 'dinar', 'dinars', 'ID', 'fils', 'fils', 1000, 'IQD 368', 0);

// Ireland (pound or punt) (pence or pingin)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (99, 'pound', 'pounds', 'IR&pound;', 'pence', 'pence', 100, 'IEP 372', 0);

// Israel
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (100, 1.0, 'new shekel', 'new shekels', 'NIS', 'new agorot', 'new agorot', 100, 'ILS 376', 0);

// Italy
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (101, 'lira', 'lira', 'Lit', 'centesimi', 'centesimi', 100, 'ITL 380', 0);

// Jamaica
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (102, 'dollar', 'dollars', 'J$', 'cent', 'cents', 100, 'JMD 388', 0);

// Jan Mayen (same as Norway)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (103, 'krone', 'krones', 'NKr', '&oslash;re', '&oslash;re', 100, 'NOK 578', 0);


// Jersey (same as United Kingdom)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (105, 'pound', 'pounds', '&pound;', 'pence', 'pence', 100, 'GBP 826', 0);

// Jordan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (106, 'dinar', 'dinar', 'JD', 'fils', 'fils', 1000, 'JOD 400', 0);

// Kazakhstan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (107, 'tenge', 'tenge', ' ', 'tiyn', 'tiyn', 100, 'KZT 398', 0);

// Kenya
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (108, 'shilling', 'shillings', 'K Sh', 'cent', 'cents', 100, 'KES 404', 0);

// Kiribati, same as Australia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (109, 'dollar', 'dollars', 'AU$', 'cents', 'cent', 100, 'AUD 036', 0);

// North Korea
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (110, 'won', 'won', 'Wn', 'chon', 'chon', 100, 'KPW 408', 0);

// South Korea
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (111, 'won', 'won', 'W', 'chon', 'chon', 100, 'KRW 410', 0);

// Kuwait
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (112, 'dinar', 'dinar', 'KD', 'fils', 'fils', 1000, 'KWD 414', 0);

// Kyrgyzstan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (113, 'som', 'som', ' ', 'tyyn', 'tyyn', 100, 'KGS 417', 0);

// Laos
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (114, 'new kip', 'new kip', 'KN', 'at', 'at', 100, 'LAK 418', 0);

// Latvia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (115, 'lat', 'lat', 'Ls', 'santims', 'santims', 100, 'LVL 428', 0);

// Lebanon (pound or livre)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (116, 'pound', 'pounds', '&pound;L', 'piastres', 'piastres', 100, 'LBP 422', 0);

// Lesotho (pl. maloti)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (117, 'loti', 'loti', 'L', 'lisente', 'lisente', 100, 'LSL 426', 0);

// Liberia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (118, 'dollar', 'dollars', '$', 'cents', 100, 'LRD 430', 0);

// Libya
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (119, 'dinar', 'dinar', 'LD', 'dirhams', 'dirhams', 1000, 'LYD 434', 0);

// Liechetenstein (see Switzerland) 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (120, 'franc', 'francs', 'SwF', 'rappen', 'rappen', 100, 'CHF 756', 0);

// Lithuania (pl litai)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (121, 'litas', 'litas', ' ', 'centu', 'centu', 100, 'LTL 440', 0);

// Luxembourg
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (122, 'franc', 'francs', 'LuxF', 'centimes', 'centimes', 100, 'LUF 442', 0);

// Macau
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (123, 'pataca', 'pataca', 'P', 'avos', 'avos', 100, 'MOP 446', 0);

// Macedonia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (124, 'denar', 'denar', ' ', ' ', ' ', 0, 'MKD 807', 0);

// Madagascar (1 ariayry = 5 francs, 1 franc = 100 centimes)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (125, 'ariayry', 'ariayry', 'FMG', 'franc',  'francs', 5, 'MGF 450', 0);

// Malawi
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (126, 'kwacha', 'kwacha', 'MK', 'tambala', 'tambala', 100, 'MWK 454', 0);

// Malaysia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (127, 'ringgit', 'ringgit', 'RM', 'sen', 'sen', 100, 'MYR 458', 0);

// Maldives
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (128, 'rufiyaa', 'rufiyaa', 'Rf', 'lari', 'lari', 100, 'MVR 462', 0);

// Mali
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (129, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'MLF 466', 0);

// Malta (pl liri)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (130, 'lira', 'lira', '&pound;m', 'cent', 'cents', 100, 'MTL 470', 0);

// Marshall Islands (same as US)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (131, 'dollar', 'dollars', '$', 'cent', 'cents', 100, 'USD 840', 0);

// Martinique (same as France)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (132, 'franc', 'francs', 'F', 'centimes', 'centimes', 100, 'FRF 250', 0);

// Mauritania
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (133, 'ouguiya', 'ouguiya', 'UM', 'khoums', 'khoums', 5, 'MRO 478', 0);

// Mauritius
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (134, 'rupee', 'rupees', 'Mau Rs', 'cent', 'cents', 100, 'MUR 480', 0);

// Mayotte (same as France)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (135, 'franc', 'francs', 'F', 'centimes', 'centimes', 100, 'FRF 250', 0);

// Mexico
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (136, 'peso', 'pesos', 'Mex$', 'centavos', 'centavos', 100, 'MXP 484', 0);

// Moldova (pl lei)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (137, 'leu', 'leu', ' ', ' ', ' ', 0, 'MDL 498', 0);

// Monaco (same as France)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (138, 'franc', 'francs', 'F', 'centimes', 'centimes', 100, 'FRF 250', 0);

// Mongolia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (139, 'tugrik', 'tugrik', 'Tug', 'mongos', 'mongos', 100, 'MNT 496', 0);

// Montserrat
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (140, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// Morocco
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (141, 'dirham', 'dirham', 'DH', 'centimes', 'centimes', 100, 'MAD 504', 0);

// Mozambique
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (142, 'metical', 'metical', 'Mt', 'centavos', 'centavos', 100, 'MZM 508', 0);

// Namibia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (143, 'dollar', 'dollars', 'N$', 'cent', 'cents', 100, 'NAD 516', 0);

// Nauru (same as Australia) 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (144, 'dollar', 'dollars', 'AU$', 'cent', 'cents', 100, 'AUD 036', 0);

// Nepal
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (145, 'rupee', 'rupees', 'NRs', 'paise', 'paise', 100, 'NPR 524', 0);

// Netherlands (guilder, aka florin or gulden)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (146, 'guilder', 'guilder', 'f.', 'cent', 'cents', 100, 'NLG 528', 0);

// Netherlands Antilles (Ant.f. or NAf.)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (147, 'guilder', 'guilder', 'Ant.f.', 'cent', 'cents', 100, 'ANG 532', 0);

// New Caledonia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (148, 'franc', 'francs', 'CFPF', 'centimes', 'centimes', 100, 'XPF 953', 0);

// New Zealand
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (149, 'dollar', 'dollars', 'NZ$', 'cent', 'cents', 100, 'NZD 554', 0);

// Nicaragua
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (150, 'gold cordoba', 'gold cordoba', 'C$', 'centavos', 'centavos', 100, 'NIC 558', 0);

// Niger
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (151, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XOF 952', 0);

// Nigeria
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (152, 'naira', 'naira', 'double-dashed N', 'kobo', 'kobo', 100, 'NGN 566', 0);

// Niue (same as New Zealand) 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (153, 'dollar', 'dollars', 'NZ$', 'cent', 'cents', 100, 'NZD 554', 0);


// Oman
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (155, 'rial', 'rial', 'RO', 'baizas', 'baizas', 1000, 'OMR 512', 0);

// Pakistan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (156, 'rupee', 'rupees', 'Rs', 'paisa', 'paisa', 100, 'PKR 586', 0);

// Palau (see United States)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (157, 'dollar', 'dollars', '$', 'cent', 'cents', 100, 'USD 840', 0);

// Panama
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (158, 'balboa', 'balboa', 'B', 'centesimos', 'centesimos', 100, 'PAB 590', 0);

// Papua New Guinea
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (159, 'kina', 'kina', 'K', 'toeas', 'toeas', 100, 'PGK 598', 0);

// Paraguay
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (160, 'guarani', 'guarani', 'slashed G', 'centimos', 'centimos', 100, 'PYG 600', 0);

// Peru
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (161, 'new sol', 'new sols', 'S/', 'centimos', 'centimos', 100, 'PEN 604', 0);

// Philippines
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (162, 'peso', 'pesos', 'dashed P', 'centavos', 'centavos', 100, 'PHP 608', 0);

// Poland
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (163, 'zloty', 'zloty', 'z dashed l', 'groszy', 'groszy', 100, 'PLZ 616', 0);

// Portugal
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (164, 'escudo', 'escudo', 'Esc', 'centavos', 'centavos', 100, 'PTE 620', 0);

// Puerto Rico (same as US)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (165, 'dollar', 'dollars', '$', 'cent', 'cents', 100, 'USD 840', 0);

// Qatar
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (166, 'riyal', 'riyal', 'QR', 'dirhams', 'dirhams', 100, 'QAR 634', 0);

// Romania (pl lei)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (167, 'leu', 'leu', 'L', 'bani', 'bani', 100, 'ROL 642', 0);

// Russia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (168, 'ruble', 'rubles', 'R', 'kopecks', 'kopecks', 100, 'RUR 810', 0);

// Rwanda
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (169, 'franc', 'francs', 'RF', 'centimes', 'centimes', 100, 'RWF 646', 0);

// Saint Helena
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (170, 'pound', 'pounds', ' ', 'pence', 'pence', 100, 'SHP 654', 0);

// Saint Kitts-Nevis
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (171, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// Saint Lucia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (172, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// Saint Pierre and Miquelon (territory of France)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (173, 'franc', 'francs', 'F', 'centimes', 'centimes', 100, 'FRF 250', 0);

// Saint Vincent and the Grenadines
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (174, 'dollar', 'dollars', 'EC$', 'cent', 'cents', 100, 'XCD 951', 0);

// San Marino (same as Italy) 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (175, 'lira', 'lira', 'Lit', 'centesimi', 'centesimi', 100, 'ITL 380', 0);

// Saudi Arabia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (176, 'riyal', 'riyal', 'SRls', 'halalat', 'halalat', 100, 'SAR 682', 0);

// Senegal
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (177, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XOF 952', 0);

// Seychelles
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (178, 'rupee', 'rupees', 'SR', 'cent', 'cents', 100, 'SCR 690', 0);

// Sierra Leone
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (179, 'leone', 'leone', 'Le', 'cent', 'cents', 100, 'SLL 694', 0);

// Singapore
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (180, 'dollar', 'dollars', 'S$', 'cent', 'cents', 100, 'SGD 702', 0);

// Slovak Republic:
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (181, 'koruna', 'koruna', 'Sk', 'haliers', 'haliers', 100, 'SKK 703', 0);

// Slovenia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (182, 'tolar', 'tolar', 'SlT', 'stotinov', 'stotinov', 100, 'SIT 705', 0);

// Solomon Islands
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (183, 'dollar', 'dollars', 'SI$', 'cent', 'cents', 100, 'SBD 090', 0);

// Somalia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (184, 'shilling', 'shillings', 'So. Sh.', 'centisimi', 'centisimi', 100, 'SOS 706', 0);

// South Africa
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (185, 'rand', 'rands', 'R', 'cent', 'cents', 100, 'ZAR 710', 0);


// Sri Lanka 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (187, 'rupee', 'rupees', 'SLRs', 'cent', 'cents', 100, 'KKR 144', 0);

// Sudan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (188, 'pound', 'pounds', ' ', 'piastress', 100, 'SDP ---', 0);

// Suriname (aka florin or gulden) (Sur.f. or Sf.)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (189, 'guilder', 'guilder', 'Sur.f.', 'cent', 'cents', 100, 'SRG 740', 0);

// Svalbard (same as Norway) 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (190, 'krone', 'krone', 'NKr', '&oslash;re', 100, 'NOK 578', 0);

// Swaziland (lilangeni, pl emalangeni) (L pl E)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (191, 'lilangeni', 'lilangeni', 'L', 'cent', 'cents', 100, 'SZL 748', 0);


// Switzerland (rappen or centimes)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (193, 'franc', 'francs', 'SwF', 'rappen', 'rappen', 100, 'CHF 756', 0);

// Syria
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (194, 'pound', 'pounds', '&pound;S', 'piasters', 'piasters', 100, 'SYP 760', 0);

// See French Polynesia 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (195, 'franc', 'francs', 'CFPF', 'centimes', 'centimes', 100, 'XPF 953', 0);

// Taiwan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (196, 'new dollar', 'new dollars', 'NT$', 'cent', 'cents', 100, 'TWD 901', 0);

// Tajikistan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (197, 'ruble', 'rubles', ' ', ' ', ' ', 0, 'TJR 762', 0);

// Tanzania
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (198, 'shilling', 'shilling', 'TSh', 'cent', 'cents', 100, 'TZS 834', 0);

// Thailand (Bht or Bt)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (199, 'baht', 'baht', 'Bht', 'sastangs', 'sastangs', 100, 'THB 764', 0);

// Togo
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (200, 'franc', 'francs', 'CFAF', 'centimes', 'centimes', 100, 'XOF 952', 0);

// Tonga (PT or T$)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (201, 'pa anga', 'pa anga', 'PT', 'seniti', 'seniti', 100, 'TOP 776', 0);

// Trinidad and Tobago
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (202, 'dollar', 'dollars', 'TT$', 'cent', 'cents', 100, 'TTD 780', 0);

// Tunisia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (203, 'dinar', 'dinar', 'TD', 'millimes', 'millimes', 1000, 'TND 788', 0);

// Turkey
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (204, 'lira', 'lira', 'TL', 'kurus', 'kurus', 100, 'TRL 792', 0);

// Turkmenistan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (205, 'manat', 'manat', ' ', 'tenga', 'tenga', 100, 'TMM 795', 0);

// Turks and Caicos Islands
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (206, 'dollar', 'dollars', '$', 'cent', 'cents', 100, 'USD 840', 0);

// Tuvalu, same as Australia 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (207, 'dollar', 'dollars', 'AU$', 'cent', 'cents', 100, 'AUD 036', 0);

// Uganda
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (208, 'shilling', 'shillings', 'USh', 'cent', 'cents', 100, 'UGX 800', 0);

// Ukraine
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (209, 'Hryvnia', 'Hryvnia', ' ', 'kopiykas', 'kopiykas', 100, 'UAH 804', 0);

// United Arab Emirates
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (210, 'dirham', 'dirham', 'Dh', 'fils', 'fils', 100, 'AED 784', 0);

// Uruguay
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (211, 'peso uruguayo', 'peso uruguayo', '$U', 'cent&eacute;simos', 'cent&eacute;simos', 100, 1.0, 'UYU 858', 0);

// Uzbekistan
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (212, 'som', 'som', ' ', 'tiyin', 'tiyin', 100, 'UZS 860', 0);

// Vanuatu
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (213, 'vatu', 'VT', 'centimes', 'centimes', 100, 'VUV 548', 0);

// Vatican, see Italy 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (214, 'lira', 'lira', 'Lit', 'centesimi', 'centesimi', 100, 'ITL 380', 0);

// Venezuela
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (215, 'bolivar', 'bolivar', 'Bs', 'centimos', 'centimos', 100, 'VEB 862', 0);

// Viet Nam (10 hao or 100 xu or 1 new dong)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (216, 'new dong', 'new dongs', 'D', 'xu', 'xu', 100, 'VND 704', 0);

// Virgin Islands (US)
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (217, 'dollar', 'dollars', '$', 'cent', 'cents', 100, 'USD 840', 0);

// Wallis and Futuna
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (218, 'franc', 'francs', 'CFPF', 'centimes', 'centimes', 100, 'XPF 953', 0);

// Western Sahara, same as Spain 
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (219, 'peseta', 'peseta', 'Ptas', 'centimos', 'centimos', 100, 'ESP 724', 0);

// Western Samoa
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (220, 'tala', 'tala', 'WS$', 'sene', 'sene', 100, 'WST ---', 0);

// Yemen
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (221, 'rial', 'rial', 'URls', 'fils', 'fils', 100, 'YER 886', 0);

// Yugo
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (222, 'dinar', 'dinar', 'Din', 'paras', 'paras', 100, 'YUM 890', 0);

// Zambia
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (223, 'kwacha', 'kwacha', 'ZK', 'ngwee', 'ngwee', 100, 'ZMK 894', 0);

// Zimbabwe
insert into ebay_currencies (id, currency, currency_pl, symbol, sub_currency, sub_currency_pl, sub_currency_ratio, iso_4217, name_res_id)
 values (224, 'dollar', 'dollars', 'Z$', 'cent', 'cents', 100, 'ZWD 716', 0);



<tr><td align=>Afghanistan</td>
    <td align=>afghani</td>
    <td align=>Af</td>
    <td align=>100 puls</td>
    <td align=><code>AFA 004</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Albania</td>
    <td align=>lek</td>
    <td align=>L</td>
    <td align=>100 qindarka (qintars)</td>
    <td align=><code>ALL 008</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Algeria</td>
    <td align=>dinar</td>
    <td align=>DA</td>
    <td align=>100 centimes</td>
    <td align=><code>DZD 012</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>American Samoa</td>
    <td align=center colspan=5>see United States of America</td>
</tr>
<tr><td align=>Andorra</td>
    <td align=center colspan=5>Andorran Peseta (1/1 to Spanish Peseta) and Andorran Franc (1/1 to French Franc)</td>
</tr>
<tr><td align=>Angola</td>
    <td align=>kwanza</td>
    <td align=>Kz</td>
    <td align=>100 lwei</td>
    <td align=><code>AOK ---</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Angola</td>
    <td align=>new kwanza (kwanza reajustado)</td>
    <td align=>Kz</td>
    <td align=>100 lwei</td>
    <td align=><code>AON 024</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Anguilla</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>Antarctica</td>
    <td align=center colspan=5>each Antarctic base uses the currency of its home country</td>
</tr>
<tr><td align=>Antigua and Barbuda</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>Argentina</td>
    <td align=>austral (-1991)</td>
    <td align=>double dashed A</td>
    <td align=>100 centavos</td>
    <td align=><code>ARA ---</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Argentina</td>
    <td align=>peso (1991-)</td>
    <td align=>$</td>
    <td align=>100 centavos</td>
    <td align=><code>ARS 032</code></td>
    <td align=>US-$ (1.0)</td>
</tr>
<tr><td align=>Armenia</td>
    <td align=>dram</td>
    <td align=>&nbsp;</td>
    <td align=>100 luma</td>
    <td align=><code>AMD 051</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Aruba</td>
    <td align=>guilder (a.k.a. florin or gulden)</td>
    <td align=>Af.</td>
    <td align=>100 cents</td>
    <td align=><code>AWG 533</code></td>
    <td align=>US-$ (1.79)</td>
</tr>
<tr><td align=>Australia</td>
    <td align=>dollar</td>
    <td align=>AU$</td>
    <td align=>100 cents</td>
    <td align=><code>AUD 036</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Austria</td>
    <td align=>schilling</td>
    <td align=>S</td>
    <td align=>100 groschen</td>
    <td align=><code>ATS 040</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Azerbaijan</td>
    <td align=>manat</td>
    <td align=>&nbsp;</td>
    <td align=>100 gopik</td>
    <td align=><code>AZM 031</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Bahamas</td>
    <td align=>dollar</td>
    <td align=>B$</td>
    <td align=>100 cents</td>
    <td align=><code>BSD 044</code></td>
    <td align=>US-$ (1.0)</td>
</tr>
<tr><td align=>Bahrain</td>
    <td align=>dinar</td>
    <td align=>BD</td>
    <td align=>1,000 fils</td>
    <td align=><code>BHD 048</code></td>
    <td align=>US-$ (lim.flex.)</td>
</tr>
<tr><td align=>Bangladesh</td>
    <td align=>taka</td>
    <td align=>Tk</td>
    <td align=>100 paisa (poisha)</td>
    <td align=><code>BDT 050</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Barbados</td>
    <td align=>dollar</td>
    <td align=>Bds$</td>
    <td align=>100 cents</td>
    <td align=><code>BBD 052</code></td>
    <td align=>US-$ (2.0)</td>
</tr>
<tr><td align=>Belarus</td>
    <td align=>ruble</td>
    <td align=>BR</td>
    <td align=>&nbsp;</td>
    <td align=><code>BYB 112</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Belgium</td>
    <td align=>franc</td>
    <td align=>BF</td>
    <td align=>100 centimes</td>
    <td align=><code>BEF 056</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Belize</td>
    <td align=>dollar</td>
    <td align=>BZ$</td>
    <td align=>100 cents</td>
    <td align=><code>BZD 084</code></td>
    <td align=>US-$ (2.0)</td>
</tr>
<tr><td align=>Belorussia</td>
    <td align=center colspan=5>old name of Belarus</td>
</tr>
<tr><td align=>Benin</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XOF 952</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Bermuda</td>
    <td align=>dollar</td>
    <td align=>Bd$</td>
    <td align=>100 cents</td>
    <td align=><code>BMD 060</code></td>
    <td align=>US-$ (1.0)</td>
</tr>
<tr><td align=>Bhutan</td>
    <td align=>ngultrum</td>
    <td align=>Nu</td>
    <td align=>100 chetrum</td>
    <td align=><code>BTN 064</code></td>
    <td align=>Indian Rupee (1.0)</td>
</tr>
<tr><td align=>Bolivia</td>
    <td align=>boliviano</td>
    <td align=>Bs</td>
    <td align=>100 centavos</td>
    <td align=><code>BOB 068</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Bosnia</td>
    <td align=>B.H. dinar</td>
    <td align=>&nbsp;</td>
    <td align=>100 para</td>
    <td align=><code>BAD 070</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Botswana</td>
    <td align=>pula</td>
    <td align=>P</td>
    <td align=>100 thebe</td>
    <td align=><code>BWP 072</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Bouvet Island</td>
    <td align=center colspan=5>see Norway</td>
</tr>
<tr><td align=>Brazil</td>
    <td align=>cruzeiro (-1993)</td>
    <td align=>&nbsp;</td>
    <td align=>100 centavos</td>
    <td align=><code>BRE 076</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Brazil</td>
    <td align=>cruzeiro (1993-94)</td>
    <td align=>&nbsp;</td>
    <td align=>100 centavos</td>
    <td align=><code>BRR 076</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Brazil</td>
    <td align=>real (1994-)</td>
    <td align=>R$</td>
    <td align=>100 centavos</td>
    <td align=><code>BRL 076</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>British Indian Ocean Territory</td>
    <td align=center colspan=5>legal currency is GBP, but mostly USD is used</td>
</tr>
<tr><td align=>British Virgin Islands</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Brunei</td>
    <td align=>ringgit (a.k.a. Bruneian dollar)</td>
    <td align=>B$</td>
    <td align=>100 sen (a.k.a. 100 cents)</td>
    <td align=><code>BND 096</code></td>
    <td align=>S$ (1.0)</td>
</tr>
<tr><td align=>Bulgaria</td>
    <td align=>leva</td>
    <td align=>Lv</td>
    <td align=>100 stotinki</td>
    <td align=><code>BGL 100</code></td>
    <td align=>German Mark (1000)</td>
</tr>
<tr><td align=>Burkina Faso</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XOF 952</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Burma</td>
    <td align=center colspan=5>now Myanmar.</td>
</tr>
<tr><td align=>Burundi</td>
    <td align=>franc</td>
    <td align=>FBu</td>
    <td align=>100 centimes</td>
    <td align=><code>BIF 108</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Cambodia</td>
    <td align=>new riel</td>
    <td align=>CR</td>
    <td align=>100 sen</td>
    <td align=><code>KHR 116</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Cameroon</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XAF 950</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Canada</td>
    <td align=>dollar</td>
    <td align=>CAD$</td>
    <td align=>100 cents</td>
    <td align=><code>CAD 124</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Canton and Enderbury Islands</td>
    <td align=center colspan=5>see Kiribati</td>
</tr>
<tr><td align=>Cape Verde Island</td>
    <td align=>escudo</td>
    <td align=>C.V.Esc.</td>
    <td align=>100 centavos</td>
    <td align=><code>CVE 132</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Cayman Islands</td>
    <td align=>dollar</td>
    <td align=>CI$</td>
    <td align=>100 cents</td>
    <td align=><code>KYD 136</code></td>
    <td align=>US-$ (0.85)</td>
</tr>
<tr><td align=>Central African Republic</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XAF 950</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Chad</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XAF 950</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Chile</td>
    <td align=>peso</td>
    <td align=>Ch$</td>
    <td align=>100 centavos</td>
    <td align=><code>CLP 152</code></td>
    <td align=>indicators</td>
</tr>
<tr><td align=>China</td>
    <td align=>yuan renminbi</td>
    <td align=>Y</td>
    <td align=>10 jiao = 100 fen</td>
    <td align=><code>CNY 156</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Christmas Island</td>
    <td align=center colspan=5>see Australia</td>
</tr>
<tr><td align=>Cocos (Keeling) Islands</td>
    <td align=center colspan=5>see Australia</td>
</tr>
<tr><td align=>Colombia</td>
    <td align=>peso</td>
    <td align=>Col$</td>
    <td align=>100 centavos</td>
    <td align=><code>COP 170</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Comoros</td>
    <td align=>franc</td>
    <td align=>CF</td>
    <td align=>-</td>
    <td align=><code>KMF 174</code></td>
    <td align=>French Franc (75.0)</td>
</tr>
<tr><td align=>Congo</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XAF 950</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Congo, Dem. Rep.</td>
    <td align=>franc</td>
    <td align=>&nbsp;</td>
    <td align=>100 centimes</td>
    <td align=><code>CDF 180</code></td>
    <td align=>US-$ (2.50)</td>
</tr>
<tr><td align=>Cook Islands</td>
    <td align=center colspan=5>see New Zealand</td>
</tr>
<tr><td align=>Costa Rica</td>
    <td align=>colon</td>
    <td align=>slashed C</td>
    <td align=>100 centimos</td>
    <td align=><code>CRC 188</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>C&ocirc;te d'Ivoire</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XOF 952</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Croatia</td>
    <td align=>kuna</td>
    <td align=>HRK</td>
    <td align=>100 lipas</td>
    <td align=><code>HRK 191</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Cuba</td>
    <td align=>peso</td>
    <td align=>Cu$</td>
    <td align=>100 centavos</td>
    <td align=><code>CUP 192</code></td>
    <td align=>US-$ (1.0)</td>
</tr>
<tr><td align=>Cyprus</td>
    <td align=>pound</td>
    <td align=>&#163;C</td>
    <td align=>100 cents</td>
    <td align=><code>CYP 196</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Cyprus (Northern)</td>
    <td align=center colspan=5>see Turkey</td>
</tr>
<tr><td align=>Czechoslovakia</td>
    <td align=center colspan=5>split into Czech Republic and Slovak Republic on January 1, 1993</td>
</tr>
<tr><td align=>Czech Republic</td>
    <td align=>koruna</td>
    <td align=>Kc (with hacek on c)</td>
    <td align=>100 haleru</td>
    <td align=><code>CZK 203</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Denmark</td>
    <td align=>krone (pl. kroner)</td>
    <td align=>Dkr</td>
    <td align=>100 &oslash;re</td>
    <td align=><code>DKK 208</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Djibouti</td>
    <td align=>franc</td>
    <td align=>DF</td>
    <td align=>100 centimes</td>
    <td align=><code>DJF 262</code></td>
    <td align=>US-$ (177.72)</td>
</tr>
<tr><td align=>Dominica</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>Dominican Rep.</td>
    <td align=>peso</td>
    <td align=>RD$</td>
    <td align=>100 centavos</td>
    <td align=><code>DOP 214</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Dronning Maud Land</td>
    <td align=center colspan=5>see Norway</td>
</tr>
<tr><td align=>East Timor</td>
    <td align=center colspan=5>see Indonesia</td>
</tr>
<tr><td align=>Ecuador</td>
    <td align=>sucre</td>
    <td align=>S/</td>
    <td align=>100 centavos</td>
    <td align=><code>ECS 218</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Egypt</td>
    <td align=>pound</td>
    <td align=>&#163;E</td>
    <td align=>100 piasters or 1,000 milliemes</td>
    <td align=><code>EGP 818</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>El Salvador</td>
    <td align=>colon</td>
    <td align=>&#162;</td>
    <td align=>100 centavos</td>
    <td align=><code>SVC 222</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Equatorial Guinea</td>
    <td align=>ekwele (CFA franc)</td>
    <td align=>CFAF</td>
    <td align=>100 centimos</td>
    <td align=><code>GQE 226</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Eritrea</td>
    <td align=>nakfa</td>
    <td align=>Nfa</td>
    <td align=>100 cents</td>
    <td align=><code>ERN 232</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Estonia</td>
    <td align=>kroon (pl. krooni)</td>
    <td align=>KR</td>
    <td align=>100 senti</td>
    <td align=><code>EEK 233</code></td>
    <td align=>German Mark (8.0)</td>
</tr>
<tr><td align=>Ethiopia</td>
    <td align=>birr</td>
    <td align=>Br</td>
    <td align=>100 cents</td>
    <td align=><code>ETB 231</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>European Union (-1998)</td>
    <td align=>European Currency Unit</td>
    <td align=>ecu</td>
    <td align=>&nbsp;</td>
    <td align=><code>XEU 954</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>European Union (1999-)</td>
    <td align=><a href="/xr/euro/">Euro</a></td>
    <td align=><img src="/xr/euro/eurosign-small.gif"></td>
    <td align=>100 euro-cents</td>
    <td align=><code>EUR ---</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Faeroe Islands (F&oslash;royar)</td>
    <td align=center colspan=5>see Denmark</td>
</tr>
<tr><td align=>Falkland Islands</td>
    <td align=>pound</td>
    <td align=>&#163;F</td>
    <td align=>100 pence</td>
    <td align=><code>FKP 238</code></td>
    <td align=>British Pound (1.0)</td>
</tr>
<tr><td align=>Fiji</td>
    <td align=>dollar</td>
    <td align=>F$</td>
    <td align=>100 cents</td>
    <td align=><code>FJD 242</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Finland</td>
    <td align=>markka (pl. markkaa)</td>
    <td align=>mk</td>
    <td align=>100 penni&auml; (sg. penni)</td>
    <td align=><code>FIM 246</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>France</td>
    <td align=>franc</td>
    <td align=>F</td>
    <td align=>100 centimes</td>
    <td align=><code>FRF 250</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>French Guiana</td>
    <td align=center colspan=5>see France</td>
</tr>
<tr><td align=>French Polynesia</td>
    <td align=>franc</td>
    <td align=>CFPF</td>
    <td align=>100 centimes</td>
    <td align=><code>XPF 953</code></td>
    <td align=>FFr (18.18)</td>
</tr>
<tr><td align=>Gabon</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XAF 950</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Gambia</td>
    <td align=>dalasi</td>
    <td align=>D</td>
    <td align=>100 butut</td>
    <td align=><code>GMD 270</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Gaza</td>
    <td align=center colspan=5>see Israel and Jordan</td>
</tr>
<tr><td align=>Georgia</td>
    <td align=>lari</td>
    <td align=>&nbsp;</td>
    <td align=>100 tetri</td>
    <td align=><code>GEL 268</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Germany</td>
    <td align=>deutsche mark</td>
    <td align=>DM</td>
    <td align=>100 pfennig</td>
    <td align=><code>DEM 280</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Ghana</td>
    <td align=>new cedi</td>
    <td align=>&#162;</td>
    <td align=>100 psewas</td>
    <td align=><code>GHC 288</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Gibraltar</td>
    <td align=>pound</td>
    <td align=>&#163;G</td>
    <td align=>100 pence</td>
    <td align=><code>GIP 292</code></td>
    <td align=>British Pound (1.0)</td>
</tr>
<tr><td align=>Great Britain</td>
    <td align=center colspan=5>see United Kingdom</td>
</tr>
<tr><td align=>Greece</td>
    <td align=>drachma</td>
    <td align=>Dr</td>
    <td align=>100 lepta (sg. lepton)</td>
    <td align=><code>GRD 300</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Greenland</td>
    <td align=center colspan=5>see Denmark</td>
</tr>
<tr><td align=>Grenada</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>Guadeloupe</td>
    <td align=center colspan=5>see France</td>
</tr>
<tr><td align=>Guam</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Guatemala</td>
    <td align=>quetzal</td>
    <td align=>Q</td>
    <td align=>100 centavos</td>
    <td align=><code>GTQ 320</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Guernsey</td>
    <td align=center colspan=5>see United Kingdom</td>
</tr>
<tr><td align=>Guinea-Bissau (-Apr1997)</td>
    <td align=>peso</td>
    <td align=>PG</td>
    <td align=>100 centavos</td>
    <td align=><code>GWP 624</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Guinea-Bissau (May1997-)</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XAF 950</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Guinea</td>
    <td align=>syli</td>
    <td align=>FG</td>
    <td align=>10 francs, 1 franc = 100 centimes</td>
    <td align=><code>GNS 324</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Guinea</td>
    <td align=>franc</td>
    <td align=>&nbsp;</td>
    <td align=>&nbsp;</td>
    <td align=><code>GNF 324</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Guyana</td>
    <td align=>dollar</td>
    <td align=>G$</td>
    <td align=>100 cents</td>
    <td align=><code>GYD 328</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Haiti</td>
    <td align=>gourde</td>
    <td align=>G</td>
    <td align=>100 centimes</td>
    <td align=><code>HTG 332</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Heard and McDonald Islands</td>
    <td align=center colspan=5>see Australia</td>
</tr>
<tr><td align=>Honduras</td>
    <td align=>lempira</td>
    <td align=>L</td>
    <td align=>100 centavos</td>
    <td align=><code>HNL 340</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Hong Kong</td>
    <td align=>dollar</td>
    <td align=>HK$</td>
    <td align=>100 cents</td>
    <td align=><code>HKD 344</code></td>
    <td align=>US-$ (7.73 central parity)</td>
</tr>
<tr><td align=>Hungary</td>
    <td align=>forint</td>
    <td align=>Ft</td>
    <td align=>-none-</td>
    <td align=><code>HUF 348</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Iceland</td>
    <td align=>kr&oacute;na</td>
    <td align=>IKr</td>
    <td align=>100 aurar (sg. aur)</td>
    <td align=><code>ISK 352</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>India</td>
    <td align=>rupee</td>
    <td align=>Rs</td>
    <td align=>100 paise</td>
    <td align=><code>INR 356</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Indonesia</td>
    <td align=>rupiah</td>
    <td align=>Rp</td>
    <td align=>100 sen (no longer used)</td>
    <td align=><code>IDR 360</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>International Monetary Fund</td>
    <td align=><a href="/xr/SDR.html">Special Drawing Right</a></td>
    <td align=>SDR</td>
    <td align=>&nbsp;</td>
    <td align=><code>XDR 960</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Iran</td>
    <td align=>rial</td>
    <td align=>Rls</td>
    <td align=>10 rials = 1 toman</td>
    <td align=><code>IRR 364</code></td>
    <td align=>US-$ (4750)</td>
</tr>
<tr><td align=>Iraq</td>
    <td align=>dinar</td>
    <td align=>ID</td>
    <td align=>1,000 fils</td>
    <td align=><code>IQD 368</code></td>
    <td align=>US-$ (0.3109)</td>
</tr>
<tr><td align=>Ireland</td>
    <td align=>punt or pound</td>
    <td align=>IR&#163;</td>
    <td align=>100 pingin  or pence</td>
    <td align=><code>IEP 372</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Isle of Man</td>
    <td align=center colspan=5>see United Kingdom</td>
</tr>
<tr><td align=>Israel</td>
    <td align=>new shekel</td>
    <td align=>NIS</td>
    <td align=>100 new agorot</td>
    <td align=><code>ILS 376</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Italy</td>
    <td align=>lira (pl. lire)</td>
    <td align=>Lit</td>
    <td align=>100 centesimi (sg. centesimo)</td>
    <td align=><code>ITL 380</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Ivory Coast</td>
    <td align=center colspan=5>see C&ocirc;te d'Ivoire</td>
</tr>
<tr><td align=>Jamaica</td>
    <td align=>dollar</td>
    <td align=>J$</td>
    <td align=>100 cents</td>
    <td align=><code>JMD 388</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Japan</td>
    <td align=>yen</td>
    <td align=>&#165;</td>
    <td align=>100 sen (not used)</td>
    <td align=><code>JPY 392</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Jersey</td>
    <td align=center colspan=5>see United Kingdom</td>
</tr>
<tr><td align=>Johnston Island</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Jordan</td>
    <td align=>dinar</td>
    <td align=>JD</td>
    <td align=>1,000 fils</td>
    <td align=><code>JOD 400</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Kampuchea</td>
    <td align=center colspan=5>see Cambodia</td>
</tr>
<tr><td align=>Kazakhstan</td>
    <td align=>tenge</td>
    <td align=>&nbsp;</td>
    <td align=>100 tiyn</td>
    <td align=><code>KZT 398</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Kenya</td>
    <td align=>shilling</td>
    <td align=>K Sh</td>
    <td align=>100 cents</td>
    <td align=><code>KES 404</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Kiribati</td>
    <td align=center colspan=5>see Australia.</td>
</tr>
<tr><td align=>Korea, North</td>
    <td align=>won</td>
    <td align=>Wn</td>
    <td align=>100 chon</td>
    <td align=><code>KPW 408</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Korea, South</td>
    <td align=>won</td>
    <td align=>W</td>
    <td align=>100 chon</td>
    <td align=><code>KRW 410</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Kuwait</td>
    <td align=>dinar</td>
    <td align=>KD</td>
    <td align=>1,000 fils</td>
    <td align=><code>KWD 414</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Kyrgyzstan</td>
    <td align=>som</td>
    <td align=>&nbsp;</td>
    <td align=>100 tyyn</td>
    <td align=><code>KGS 417</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Laos</td>
    <td align=>new kip</td>
    <td align=>KN</td>
    <td align=>100 at</td>
    <td align=><code>LAK 418</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Latvia</td>
    <td align=>lat</td>
    <td align=>Ls</td>
    <td align=>100 santims</td>
    <td align=><code>LVL 428</code></td>
    <td align=>SDR</td>
</tr>
<tr><td align=>Lebanon</td>
    <td align=>pound (livre)</td>
    <td align=>&#163;L</td>
    <td align=>100 piastres</td>
    <td align=><code>LBP 422</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Lesotho</td>
    <td align=>loti, pl., maloti</td>
    <td align=> L, pl., M</td>
    <td align=>100 lisente</td>
    <td align=><code>LSL 426</code></td>
    <td align=>South African Rand (1.0)</td>
</tr>
<tr><td align=>Liberia</td>
    <td align=>dollar</td>
    <td align=>$</td>
    <td align=>100 cents</td>
    <td align=><code>LRD 430</code></td>
    <td align=>US-$ (1.0)</td>
</tr>
<tr><td align=>Libya</td>
    <td align=>dinar</td>
    <td align=>LD</td>
    <td align=>1,000 dirhams</td>
    <td align=><code>LYD 434</code></td>
    <td align=>SDR (8.5085)</td>
</tr>
<tr><td align=>Liechtenstein</td>
    <td align=center colspan=5>see Switzerland</td>
</tr>
<tr><td align=>Lithuania</td>
    <td align=>litas, pl., litai</td>
    <td align=>&nbsp;</td>
    <td align=>100 centu</td>
    <td align=><code>LTL 440</code></td>
    <td align=>US-$ (4.0)</td>
</tr>
<tr><td align=>Luxembourg</td>
    <td align=>franc</td>
    <td align=>LuxF</td>
    <td align=>100 centimes</td>
    <td align=><code>LUF 442</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Macao (Macau)</td>
    <td align=>pataca</td>
    <td align=>P</td>
    <td align=>100 avos</td>
    <td align=><code>MOP 446</code></td>
    <td align=>HK-$ (1.03)</td>
</tr>
<tr><td align=>Macedonia (Former Yug. Rep.)</td>
    <td align=>denar</td>
    <td align=>&nbsp;</td>
    <td align=>&nbsp;</td>
    <td align=><code>MKD 807</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Madagascar</td>
    <td align=>ariayry = 5 francs</td>
    <td align=>FMG</td>
    <td align=>1 francs = 100 centimes</td>
    <td align=><code>MGF 450</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Malawi</td>
    <td align=>kwacha</td>
    <td align=>MK</td>
    <td align=>100 tambala</td>
    <td align=><code>MWK 454</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Malaysia</td>
    <td align=>ringgit</td>
    <td align=>RM</td>
    <td align=>100 sen</td>
    <td align=><code>MYR 458</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Maldives</td>
    <td align=>rufiyaa</td>
    <td align=>Rf</td>
    <td align=>100 lari</td>
    <td align=><code>MVR 462</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Mali</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>MLF 466</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Malta</td>
    <td align=>lira, pl., liri</td>
    <td align=>&#163;m</td>
    <td align=>100 cents</td>
    <td align=><code>MTL 470</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Martinique</td>
    <td align=center colspan=5>see France</td>
</tr>
<tr><td align=>Mauritania</td>
    <td align=>ouguiya</td>
    <td align=>UM</td>
    <td align=>5 khoums</td>
    <td align=><code>MRO 478</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Mauritius</td>
    <td align=>rupee</td>
    <td align=>Mau Rs</td>
    <td align=>100 cents</td>
    <td align=><code>MUR 480</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Micronesia</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Midway Islands</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Mexico</td>
    <td align=>peso</td>
    <td align=>Mex$</td>
    <td align=>100 centavos</td>
    <td align=><code>MXP 484</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Moldova</td>
    <td align=>leu, pl., lei</td>
    <td align=>&nbsp;</td>
    <td align=>&nbsp;</td>
    <td align=><code>MDL 498</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Monaco</td>
    <td align=center colspan=5>see France</td>
</tr>
<tr><td align=>Mongolia</td>
    <td align=>tugrik (tughrik?)</td>
    <td align=>Tug</td>
    <td align=>100 mongos</td>
    <td align=><code>MNT 496</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Montserrat</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>Morocco</td>
    <td align=>dirham</td>
    <td align=>DH</td>
    <td align=>100 centimes</td>
    <td align=><code>MAD 504</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Mozambique</td>
    <td align=>metical</td>
    <td align=>Mt</td>
    <td align=>100 centavos</td>
    <td align=><code>MZM 508</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Myanmar</td>
    <td align=>kyat</td>
    <td align=>K</td>
    <td align=>100 pyas</td>
    <td align=><code>MMK 104</code></td>
    <td align=>US-$ (5.86of, 200-300bm)</td>
</tr>
<tr><td align=>Nauru</td>
    <td align=center colspan=5>see Australia</td>
</tr>
<tr><td align=>Namibia</td>
    <td align=>dollar</td>
    <td align=>N$</td>
    <td align=>100 cents</td>
    <td align=><code>NAD 516</code></td>
    <td align=>South African Rand (1.0)</td>
</tr>
<tr><td align=>Nepal</td>
    <td align=>rupee</td>
    <td align=>NRs</td>
    <td align=>100 paise</td>
    <td align=><code>NPR 524</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Netherlands Antilles</td>
    <td align=>guilder (a.k.a. florin or gulden)</td>
    <td align=>Ant.f. or NAf.</td>
    <td align=>100 cents</td>
    <td align=><code>ANG 532</code></td>
    <td align=>US-$ (1.79)</td>
</tr>
<tr><td align=>Netherlands</td>
    <td align=>guilder (a.k.a. florin or gulden)</td>
    <td align=>f.</td>
    <td align=>100 cents</td>
    <td align=><code>NLG 528</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>New Caledonia</td>
    <td align=>franc</td>
    <td align=>CFPF</td>
    <td align=>100 centimes</td>
    <td align=><code>XPF 953</code></td>
    <td align=>FFr (18.18)</td>
</tr>
<tr><td align=>New Zealand</td>
    <td align=>dollar</td>
    <td align=>NZ$</td>
    <td align=>100 cents</td>
    <td align=><code>NZD 554</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Nicaragua</td>
    <td align=>gold cordoba</td>
    <td align=>C$</td>
    <td align=>100 centavos</td>
    <td align=><code>NIC 558</code></td>
    <td align=>indicators</td>
</tr>
<tr><td align=>Niger</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XOF 952</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Nigeria</td>
    <td align=>naira</td>
    <td align=>double-dashed N</td>
    <td align=>100 kobo</td>
    <td align=><code>NGN 566</code></td>
    <td align=>US-$ ((82.0))</td>
</tr>
<tr><td align=>Niue</td>
    <td align=center colspan=5>see New Zealand</td>
</tr>
<tr><td align=>Norfolk Island</td>
    <td align=center colspan=5>see Australia</td>
</tr>
<tr><td align=>Norway</td>
    <td align=>krone (pl. kroner)</td>
    <td align=>NKr</td>
    <td align=>100 &oslash;re</td>
    <td align=><code>NOK 578</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Oman</td>
    <td align=>rial</td>
    <td align=>RO</td>
    <td align=>1,000 baizas</td>
    <td align=><code>OMR 512</code></td>
    <td align=>US-$ (1/2.6)</td>
</tr>
<tr><td align=>Pakistan</td>
    <td align=>rupee</td>
    <td align=>Rs</td>
    <td align=>100 paisa</td>
    <td align=><code>PKR 586</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Palau</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Panama</td>
    <td align=>balboa</td>
    <td align=>B</td>
    <td align=>100 centesimos</td>
    <td align=><code>PAB 590</code></td>
    <td align=>US-$ (1.0)</td>
</tr>
<tr><td align=>Panama Canal Zone</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Papua New Guinea</td>
    <td align=>kina</td>
    <td align=>K</td>
    <td align=>100 toeas</td>
    <td align=><code>PGK 598</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Paraguay</td>
    <td align=>guarani</td>
    <td align=>slashed G</td>
    <td align=>100 centimos</td>
    <td align=><code>PYG 600</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Peru</td>
    <td align=>inti</td>
    <td align=>&nbsp;</td>
    <td align=>100 centimos</td>
    <td align=><code>PEI ---</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Peru</td>
    <td align=>new sol</td>
    <td align=>S/.</td>
    <td align=>100 centimos</td>
    <td align=><code>PEN 604</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Philippines</td>
    <td align=>peso</td>
    <td align=>dashed P</td>
    <td align=>100 centavos</td>
    <td align=><code>PHP 608</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Pitcairn Island</td>
    <td align=center colspan=5>see New Zealand</td>
</tr>
<tr><td align=>Poland</td>
    <td align=>zloty</td>
    <td align=>z dashed l</td>
    <td align=>100 groszy</td>
    <td align=><code>PLZ 616</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Portugal</td>
    <td align=>escudo</td>
    <td align=>Esc</td>
    <td align=>100 centavos</td>
    <td align=><code>PTE 620</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Puerto Rico</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Qatar</td>
    <td align=>riyal</td>
    <td align=>QR</td>
    <td align=>100 dirhams</td>
    <td align=><code>QAR 634</code></td>
    <td align=>US-$ (lim.flex.)</td>
</tr>
<tr><td align=>Reunion</td>
    <td align=center colspan=5>see France</td>
</tr>
<tr><td align=>Romania</td>
    <td align=>leu (pl. lei)</td>
    <td align=>L</td>
    <td align=>100 bani</td>
    <td align=><code>ROL 642</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Russia</td>
    <td align=>ruble</td>
    <td align=>R</td>
    <td align=>100 kopecks</td>
    <td align=><code>RUR 810</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Rwanda</td>
    <td align=>franc</td>
    <td align=>RF</td>
    <td align=>100 centimes</td>
    <td align=><code>RWF 646</code></td>
    <td align=>SDR (201.8?)</td>
</tr>
<tr><td align=>Saint Helena</td>
    <td align=>pound</td>
    <td align=>&nbsp;</td>
    <td align=>100 pence</td>
    <td align=><code>SHP 654</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Samoa (Western)</td>
    <td align=>tala</td>
    <td align=>WS$</td>
    <td align=>100 sene</td>
    <td align=><code>WST 882</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Samoa (America)</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>San Marino</td>
    <td align=center colspan=5>see Italy</td>
</tr>
<tr><td align=>Sao Tome &amp; Principe</td>
    <td align=>dobra</td>
    <td align=>Db</td>
    <td align=>100 centimos</td>
    <td align=><code>STD 678</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Saudi Arabia</td>
    <td align=>riyal</td>
    <td align=>SRls</td>
    <td align=>100 halalat</td>
    <td align=><code>SAR 682</code></td>
    <td align=>US-$ (lim.flex.)</td>
</tr>
<tr><td align=>Senegal</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XOF 952</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Serbia</td>
    <td align=center colspan=5>see Yugoslavia</td>
</tr>
<tr><td align=>Seychelles</td>
    <td align=>rupee</td>
    <td align=>SR</td>
    <td align=>100 cents</td>
    <td align=><code>SCR 690</code></td>
    <td align=>SDR (7.2345)</td>
</tr>
<tr><td align=>Sierra Leone</td>
    <td align=>leone</td>
    <td align=>Le</td>
    <td align=>100 cents</td>
    <td align=><code>SLL 694</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Singapore</td>
    <td align=>dollar</td>
    <td align=>S$</td>
    <td align=>100 cents</td>
    <td align=><code>SGD 702</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Slovakia</td>
    <td align=>koruna</td>
    <td align=>Sk</td>
    <td align=>100 haliers (halierov?)</td>
    <td align=><code>SKK 703</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Slovenia</td>
    <td align=>tolar</td>
    <td align=>SlT</td>
    <td align=>100 stotinov (stotins)</td>
    <td align=><code>SIT 705</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Solomon Island</td>
    <td align=>dollar</td>
    <td align=>SI$</td>
    <td align=>100 cents</td>
    <td align=><code>SBD 090</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Somalia</td>
    <td align=>shilling</td>
    <td align=>So. Sh.</td>
    <td align=>100 centesimi</td>
    <td align=><code>SOS 706</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>South Africa</td>
    <td align=>rand</td>
    <td align=>R</td>
    <td align=>100 cents</td>
    <td align=><code>ZAR 710</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Spain</td>
    <td align=>peseta</td>
    <td align=>Ptas</td>
    <td align=>100 centimos</td>
    <td align=><code>ESP 724</code></td>
    <td align=>EMU</td>
</tr>
<tr><td align=>Sri Lanka</td>
    <td align=>rupee</td>
    <td align=>SLRs</td>
    <td align=>100 cents</td>
    <td align=><code>LKR 144</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>St. Helena</td>
    <td align=>pound</td>
    <td align=>&#163;S</td>
    <td align=>100 new pence</td>
    <td align=><code>SHP ---</code></td>
    <td align=>GBP (1.0)</td>
</tr>
<tr><td align=>St. Kitts and Nevis</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>St. Lucia</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>St. Vincent and the Grenadines</td>
    <td align=>dollar</td>
    <td align=>EC$</td>
    <td align=>100 cents</td>
    <td align=><code>XCD 951</code></td>
    <td align=>US-$ (2.7)</td>
</tr>
<tr><td align=>Sudan</td>
    <td align=>pound</td>
    <td align=>&nbsp;</td>
    <td align=>100 piastres</td>
    <td align=><code>SDP ---</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Suriname</td>
    <td align=>guilder (a.k.a. florin or gulden)</td>
    <td align=>Sur.f. or Sf.</td>
    <td align=>100 cents</td>
    <td align=><code>SRG 740</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Svalbard and Jan Mayen Islands</td>
    <td align=center colspan=5>see Norway</td>
</tr>
<tr><td align=>Swaziland</td>
    <td align=>lilangeni, pl., emalangeni</td>
    <td align=>L, pl., E</td>
    <td align=>100 cents</td>
    <td align=><code>SZL 748</code></td>
    <td align=>South African rand (1.0)</td>
</tr>
<tr><td align=>Sweden</td>
    <td align=>krona (pl. kronor)</td>
    <td align=>Sk</td>
    <td align=>100 &ouml;re</td>
    <td align=><code>SEK 752</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Switzerland</td>
    <td align=>franc</td>
    <td align=>SwF</td>
    <td align=>100 rappen/centimes</td>
    <td align=><code>CHF 756</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Syria</td>
    <td align=>pound</td>
    <td align=>&#163;S</td>
    <td align=>100 piasters</td>
    <td align=><code>SYP 760</code></td>
    <td align=>US-$ (11.225)</td>
</tr>
<tr><td align=>Tahiti</td>
    <td align=center colspan=5>see French Polynesia</td>
</tr>
<tr><td align=>Taiwan</td>
    <td align=>new dollar</td>
    <td align=>NT$</td>
    <td align=>100 cents</td>
    <td align=><code>TWD 901</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Tajikistan</td>
    <td align=>ruble</td>
    <td align=>&nbsp;</td>
    <td align=>&nbsp;</td>
    <td align=><code>TJR 762</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Tanzania</td>
    <td align=>shilling</td>
    <td align=>TSh</td>
    <td align=>100 cents</td>
    <td align=><code>TZS 834</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Thailand</td>
    <td align=>baht</td>
    <td align=>Bht or Bt</td>
    <td align=>100 sastangs</td>
    <td align=><code>THB 764</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Togo</td>
    <td align=>franc</td>
    <td align=>CFAF</td>
    <td align=>100 centimes</td>
    <td align=><code>XOF 952</code></td>
    <td align=>French Franc (100.0)</td>
</tr>
<tr><td align=>Tokelau</td>
    <td align=center colspan=5>see New Zealand</td>
</tr>
<tr><td align=>Tonga</td>
    <td align=>pa'anga</td>
    <td align=>PT or T$</td>
    <td align=>100 seniti</td>
    <td align=><code>TOP 776</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Trinidad and Tobago</td>
    <td align=>dollar</td>
    <td align=>TT$</td>
    <td align=>100 cents</td>
    <td align=><code>TTD 780</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Tunisia</td>
    <td align=>dinar</td>
    <td align=>TD</td>
    <td align=>1,000 millimes</td>
    <td align=><code>TND 788</code></td>
    <td align=>m.float (1.0)</td>
</tr>
<tr><td align=>Turkey</td>
    <td align=>lira</td>
    <td align=>TL</td>
    <td align=>100 kurus</td>
    <td align=><code>TRL 792</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Turkmenistan</td>
    <td align=>manat</td>
    <td align=>&nbsp;</td>
    <td align=>100 tenga</td>
    <td align=><code>TMM 795</code></td>
    <td align=>US-$ (10.0;230.0)</td>
</tr>
<tr><td align=>Turks and Caicos Islands</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Tuvalu</td>
    <td align=center colspan=5>see Australia.</td>
</tr>
<tr><td align=>Uganda</td>
    <td align=>shilling</td>
    <td align=>USh</td>
    <td align=>100 cents</td>
    <td align=><code>UGS ---</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Uganda</td>
    <td align=>shilling</td>
    <td align=>USh</td>
    <td align=>100 cents</td>
    <td align=><code>UGX 800</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Ukraine</td>
    <td align=>Hryvnia</td>
    <td align=>&nbsp;</td>
    <td align=>100 kopiykas</td>
    <td align=><code>UAH 804</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>United Arab Emirates</td>
    <td align=>dirham</td>
    <td align=>Dh</td>
    <td align=>100 fils</td>
    <td align=><code>AED 784</code></td>
    <td align=>US-$ (lim.flex.)</td>
</tr>
<tr><td align=>United Kingdom</td>
    <td align=>pound</td>
    <td align=>&#163;</td>
    <td align=>100 pence</td>
    <td align=><code>GBP 826</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>United States of America</td>
    <td align=>dollar</td>
    <td align=>$</td>
    <td align=>100 cents</td>
    <td align=><code>USD 840</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Upper Volta</td>
    <td align=center colspan=5>now Burkina Faso</td>
</tr>
<tr><td align=>Uruguay (-1975)</td>
    <td align=>peso</td>
    <td align=>Ur$</td>
    <td align=>100 cent&eacute;simos</td>
    <td align=><code>UYP ---</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Uruguay (1975-93)</td>
    <td align=>new peso</td>
    <td align=>NUr$</td>
    <td align=>100 cent&eacute;simos</td>
    <td align=><code>UYN ---</code></td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Uruguay (1993-)</td>
    <td align=>peso uruguayo</td>
    <td align=>$U</td>
    <td align=>100 cent&eacute;simos</td>
    <td align=><code>UYU 858</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Uzbekistan</td>
    <td align=>som</td>
    <td align=>&nbsp;</td>
    <td align=>100 tiyin</td>
    <td align=><code>UZS 860</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Vanuatu</td>
    <td align=>vatu</td>
    <td align=>VT</td>
    <td align=>100 centimes</td>
    <td align=><code>VUV 548</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Vatican</td>
    <td align=center colspan=5>see Italy</td>
</tr>
<tr><td align=>Venezuela</td>
    <td align=>bolivar</td>
    <td align=>Bs</td>
    <td align=>100 centimos</td>
    <td align=><code>VEB 862</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Viet Nam</td>
    <td align=>new dong</td>
    <td align=>D</td>
    <td align=>10 hao or 100 xu</td>
    <td align=><code>VND 704</code></td>
    <td align=>m.float</td>
</tr>
<tr><td align=>Virgin Islands</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Wake Island</td>
    <td align=center colspan=5>see United States</td>
</tr>
<tr><td align=>Wallis and Futuna Islands</td>
    <td align=>franc</td>
    <td align=>CFPF</td>
    <td align=>100 centimes</td>
    <td align=><code>XPF 953</code></td>
    <td align=>FFr (18.18)</td>
</tr>
<tr><td align=>Western Sahara</td>
    <td align=center colspan=5>see Spain, Mauritania and Morocco</td>
</tr>
<tr><td align=>Western Samoa</td>
    <td align=>tala</td>
    <td align=>WS$</td>
    <td align=>100 sene</td>
    <td align=><code>WST ---</code></td>
    <td align=>composite</td>
</tr>
<tr><td align=>Yemen</td>
    <td align=>rial</td>
    <td align=>YRls</td>
    <td align=>100 fils</td>
    <td align=><code>YER 886</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Yugoslavia</td>
    <td align=>dinar</td>
    <td align=>Din</td>
    <td align=>100 paras</td>
    <td align=><code>YUM 890</code></td>
    <td align=>&nbsp;</td>
</tr>
<tr><td align=>Za&iuml;re (-Nov 1994)</td>
    <td align=>zaire</td>
    <td align=>Z</td>
    <td align=>100 makuta</td>
    <td align=>&nbsp;</td>
    <td align=>(replaced)</td>
</tr>
<tr><td align=>Za&iuml;re (-1997)</td>
    <td align=>new zaire</td>
    <td align=>NZ</td>
    <td align=>100 new makuta</td>
    <td align=><code>ZRN 180</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Za&iuml;re</td>
    <td align=center colspan=5>country renamed in 1997 to Democratic Republic of Congo</td>
</tr>
<tr><td align=>Zambia</td>
    <td align=>kwacha</td>
    <td align=>ZK</td>
    <td align=>100 ngwee</td>
    <td align=><code>ZMK 894</code></td>
    <td align=>float</td>
</tr>
<tr><td align=>Zimbabwe</td>
    <td align=>dollar</td>
    <td align=>Z$</td>
    <td align=>100 cents</td>
    <td align=><code>ZWD 716</code></td>
    <td align=>float</td>
</tr>
</table>
