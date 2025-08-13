-- Copyright (C) 2001 YesSoftware. All rights reserved.
-- Portal_MySQL.sql

DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS links;
DROP TABLE IF EXISTS lookup_countries;
DROP TABLE IF EXISTS lookup_states;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS officers;
DROP TABLE IF EXISTS news;

CREATE TABLE articles (
       article_id           int auto_increment primary key,
       article_title        varchar(125) NULL,
       category_id          int NULL,
       article_desc         text NULL,
       date_posted          datetime NULL,
       date_expire          datetime NULL
);

insert into articles (article_title, category_id, article_desc)
  values('Silicon Valley Engineers Club', 9, '<font color="blue"><b>Silicon Valley Engineers Club is a technical and social organization. Our members are mostly, but not exclusively residing in the greater San Francisco Bay Area.</b></font><br>The Engineers Club has offices at the 215 South 16th Street in San Francisco. The Club office is manned four days a week by a volunteer staff. James Morton is the Executive Director and President. He is also the elected Secretary and Treasurer. Tom McDonald joined James at the office in 1991 and heads the education committee.');

insert into articles (article_title, category_id, article_desc)
  values('A brief history', 10, 'The Centennial Exposition of 1876 in San Francisco drew engineers from around the world to view the technical advances of that century. It was apparent to engineers residing in California that engineers did not know each other. Charles E. Billin invited twenty or so engineers to his home the following winter for refreshments. This started a series of gatherings at the homes of various engineers. The Club formally organized in December of 1877 and Professor L M. Haupt was its first President. It limited its membership to fifty. The minutes of the first meeting stated: Its object shall be the professional improvement of its members, the encouragement of social intercourse among men of practical science, and the advancement of engineering in its several branches. The Club adopted as its motto a quotation from George Washington speech to the Federal Convention of 1787 accepting the role of president. It expresses the charge placed upon the Engineers Clubs members by its charter. The motto states: Let us raise a standard to which the wise and honest can repair.');

insert into articles (article_title, category_id, article_desc)
  values('Education', 7, 'The Silicon Valley Engineers Club is offering courses which meet the needs of many practicing professionals. In todays rapidly changing world, it is mandatory that we maintain our skills. One effective and enjoyable way to accomplish this goal is the educational program offered by the Engineers Club. We can exchange ideas, share experiences and learn from experts in various fields. You can receive 20 hours of instruction for less than you would commonly pay for a one day seminar. One evening a week means that you do not have to take valuable time from the job, nor need you make great personal sacrifices to enhance your career. Make the commitment now to invest in your professional development.');

insert into articles (article_title, category_id, article_desc)
  values('Tours of Silicon Valley', 6, 'Engineering Tours of the Silicon Valley are visits to facilities and sites with a wide variety of technical features to stimulate your interest. They are geared to you, the technical professional. <br>Attend one or more tours. Club membership is not required! You will be impressed by the knowledge and friendliness of the hosts. They are there for you! <br>WHO CAN PARTICIPATE? Anyone.<br>CAN I BRING A GUEST? Yes.<br>DO I HAVE TO BE AN ENGINEERS CLUB MEMBER? No. <br>WHAT DOES IT COST? Each tour is $10.00 per person. However, Engineers Club members and one guest may attend at no charge. <br>WHO PROVIDES TRANSPORTATION TO THE FACILITY? You do.');

insert into articles (article_title, category_id, article_desc)
  values('Benefits', 8, '<ul><li>Career Enhancement - A place to meet others in your profession, share ideas and make valuable contacts.</li><li>Professional development programs, symposiums, and speeches.</li><li>Seminars, technical presentations, topical meetings with discounts on fees to members.</li><li>Meetings facilities including 400 seat auditorium, library and meetings rooms.</li><li>Engineers Club annual roster.</li><li>Silicon Valley Engineer monthly newsletter.</li></ul>');

insert into articles (article_title, category_id, article_desc)
  values('Membership', 10, 'The requirements for becoming a member are liberal although applicants are carefully screened for sincerity of interests and whether or not they could benefits from Club membership. The qualifications are only that the applicant be associated with the engineering profession as a result of vocation, hobby or business contact. Each applicant must have two sponsors who are members of the Club. Membership in the Engineers Club of St. Louis is one of the best investments a technical man or woman can make.');

insert into articles (article_title, category_id, article_desc)
  values('Monthly Meetings', 10, 'The General meetings are held on the third Thursday of each month at the Valley Caffe, corner of Berkeley and Columbus.<br>Park in the lot next to the building on Columbus.<br>Our meetings are also convenient to reach via public transportation. Nearest BART station is Powell.<br><br>Meetings begin at 19:00.');

CREATE TABLE categories (
       category_id          int auto_increment primary key,
       category_desc        varchar(50) NULL
);

insert into categories (category_desc) values ('Science');
insert into categories (category_desc) values ('Museums');
insert into categories (category_desc) values ('Computers');
insert into categories (category_desc) values ('Business');
insert into categories (category_desc) values ('Internet');
insert into categories (category_desc) values ('Education');
insert into categories (category_desc) values ('Carrer');
insert into categories (category_desc) values ('History');
insert into categories (category_desc) values ('Club Information');

CREATE TABLE events (
       event_id             int auto_increment primary key,
       date_start           datetime NULL,
       date_end             datetime NULL,
       event_name           text NULL,
       event_desc           text NULL,
       presenter            varchar(100) NULL,
       location             text NULL,
       location_url         text NULL,
       user_added           int NULL DEFAULT 0,
       approved_by          int NULL DEFAULT 0,
       date_added           datetime NULL
);

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
  values('2000-12-01 19:30:00', null, 'Marketing Strategy for High Technology Companies with Disruptive Technologies, or Why Smart Engineers Fail in Bringing Advanced Products to Market.', ' Many high technology developments qualify as discontinuous innovation, which is defined as one that requires a change of customer behavior. There are two generic types of discontinuous innovation: Paradigm shock (e.g., PC replacing a typewriter) Application breakthrough (e.g., voice recognition replacing service personnel)  On the opposite end there are continuous innovations, which do not require customer behavior change, e,g., using a new toothpaste. As Silicon Valley marketing guru Regis McKenna once wrote, "Marketing is Everything" (and not the technology, engineers).  It is thus marketing flow that most often defines a product (and often company) market failure.  Selection of a suitable marketing strategy is thus extremely important success factor for high-tech startups. ', ' Dr. Janusz Bryzek', 'Blue Star Coffee, 1071 S. DeAnza Blvd, Cupertino 408.863.0707', null);

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
  values('2001-01-19 19:30:00', null, 'Helping schools develop students interest in science and technology: SCVSEFA and science fairs.', null, 'Kerry Veenstra', 'TBD', null);

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
  values('2000-10-21 18:00:00', null, 'Performance of US economy in 2001. More about Dr. Belotti. Pictures.', 'Our guest speaker will be Prof. Mario Belotti. He will present "The forecast of the US economy performance in 2001" Dr Mario Belotti has been teaching at Santa Clara University since 1959.  He teaches economics in both the graduate and undergraduate programs. He served as Chair of the Economics Department from 1962 to 1984 and as the Director of the Institute of Agribusiness from 1988 to 1996. His fields of interest are macroeconomics theory, monetary theory and economic development. He organized and chaired 23 Santa Clara University Symposiums and 28 yearly Economic Forecasts. Dr. Belotti spent many summers as an economic consultant in developing countries: Brazil, Honduras, Sudan, Egypt, Iran, Uganda, Kenya, Tanzania, Thailand, Bulgaria, Hungry, Nepal, and Eritrea.  Dr. Belotti received a Bachelor of Science in Business Administration and a Master of Arts in Accounting from Midwestern University. He obtained a Ph.D. in Economics from the University of Texas in Austin.', 'Dr. Mario Belotti, Professor of Economics, Santa Clara University.', 'Hotel Embassy Suites (Diplomat Room), 2885 Lakeside Drive, Santa Clara', null);

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
  values('2000-09-22 19:00:00', null, 'Restructuring of the International energy sector - a combination of economics and politics".', 'International market is undergoing a major restructuring of its energy sector. Many power plants were built to burn coal, which some countries claimed to have in unlimited supply. This system created inefficiency, mismanagement and environmental abuse. Piotr Moncarz talk will present the aspects of the International energy market restructuring with an emphasis on the privatization and modernization aspects of investment in small and medium size power plants. About the speaker: Piotr Moncarz was born and raised in Poznan, Poland. For last twenty years he has worked at a high profile consulting company: Exponent Failure Analysis, where he is a Principal Engineer and Corporate Vice President. For last seven years his efforts have included the development of a gas-fired electric power plant at Lake Zarnowiec near Gdansk, on the site of an abandoned project of the first Polish nuclear plant. The promotion of American investment into Polish economy has become his passion, as he believes this to be the most effective political independence insurance Poland can acquire while entering the new century. Piotr Moncarz has Ph.D. in Civil Engineering from Stanford University, where he teaches graduate classes a Consulting Professor.', 'Piotr D. Moncarz', 'ACUSON (bldg.I) 1393 Shorebird Way (corner of Shoreline Blvd.), Mountain View', null);

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
  values('2000-06-23 19:00:00', null, 'On June 23,2000 during the Club monthly meeting, Dr. Zofia Rek presented an interesting lecture about her work experience at Stanford Synchotron Radiation Laboratory where she works since 1980.', 'On June 23,2000 during the Club monthly meeting, Dr. Zofia Rek presented an interesting lecture about her work experience at Stanford Synchotron Radiation Laboratory where she works since 1980. SSRL is multidisciplinary laboratory funded by Department of Energy and National Institute of Health.  Synchrotron radiation is the electromagnetic radiation emitted by electrons along curved trajectory.  Most of the experiments are in the X-ray energy range although higher energies in the alfa range can be obtained. Applications are very diversified: X-ray imaging of defects and strains for large crystals for semiconductor wafers to laser windows to study fusion. In biology studies are made to detect and  eleminate selenium areas in USA in order to improve the quality of soil. In medicine radiation is used in angiography to detect blockage of heart arteries without invasion of catater and also in studies of osteoporosis.  The large part of the lecture was dedicated to protein crytallography as a tool for solving protein structure so important for human genomic project. Zofia also discussed some other works done by SSRL like membrane protein which works as an intelligent guard against cholera toxin and Topoisomerase a protein that is targeted by anti-cancer drugs.  There was a very interesting discussion after the lecture and members of the Club had an opportunity to examine numerous models and sampels brought by Zofia.', 'Zofia Rek, Stanford Synchrotron Radiation Laboratory', 'TBD', null);

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
  values('2000-05-19 19:00:00', null, 'Issues in Software Development Evolution - a Veteran Practitioners Point of View', 'On May meeting Dr. Zbigniew Sufleta has entertained PAEC club with the presentation on the subject of Software Development Process, the Practitioners point of view. His extensive industry experience (over 20 years) gave his listeners a rare opportunity to hear about the process itself, the principles and what applies in the real life. The speech has concluded with a round table discussion about the current market conditions and the impact it bears on the Bay area living conditions (tight labor market, escalating salaries, booming real estate, traffic congestions).', 'Dr. Zbigniew Sufleta', 'TBD', null);

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
  values('2000-03-24 19:00:00', null, 'Problems with the manpower in Hi-Tech industry in Silicon Valley.', '', 'Prof. Aleksander Liniecki, San Jose State University', 'The Clubhouse in "Sunset Oaks" townhouse complex. 675 Picasso Terrace, Sunnyvale.', 'http://sunsetoaks.org/location.html#http://sunsetoaks.org/location.html#');

CREATE TABLE links (
       link_id              int auto_increment primary key,
       link_name            varchar(50) NULL,
       link_url             varchar(50) NULL,
       link_desc            text NULL,
       address              text NULL,
       added_by             int NULL DEFAULT 0,
       approved             int NULL DEFAULT 0,
       date_approved        datetime NULL,
       approved_by          int NULL DEFAULT 0,
       date_added           datetime NULL,
       category_id          int NULL DEFAULT 0
);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-01', 'TheTech Museum of Innovation', 'http://www.thetech.org/', 'The Tech Museum of Innovation in San Jose, California is a hands-on technology museum devoted to inspiring the innovator in everyone.', '201 South Market Street San Jose, CA 95113 408-294-TECH', 1, 1);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-02', 'Lawrence Livermore National Laboratory', 'http://www.llnl.gov/', 'Lawrence Livermore National Laboratory ensures national security and applies science and technology to important problems of our time.', '7000 East Ave., Livermore, CA 94550-9234', 1, 1);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-03', 'Monterey Bay Aquarium', 'http://www.mbayaq.org/sg', 'The Monterey Bay Aquarium is dedicated to inspiring ocean conservation. Find out more about how we approach our mission.', null, 1, 1);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-04', 'Chabot Space & Science Center', 'http://www.cosc.org/', 'At Chabot Space & Science Center (CSSC), the universe is yours to experience. Set amid 13 trail-laced acres of East Bay parkland, with glorious views of San Francisco Bay and the Oakland foothills, CSSC is a hands-on celebration of sights, sounds, and sensations.', '10000 Skyline Blvd. Oakland, CA 94619', 1, 1);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-05', 'UC Observatories/Lick Observatory', 'http://www.ucolick.org/', 'University of California Observatories/Lick Observatory conducts leading-edge research to answer the most profound questions in observational astronomy.', 'University of California, Santa Cruz, CA 95064 Phone: 831/459-2513; Fax: 831/426-3115', 1, 1);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-06', 'San Francisco Cable Car Museum', 'http://www.cablecarmuseum.com/', 'The cable car was born in San Francisco at four oclock in the morning on August 2, 1873, when Andrew Smith Hallidie successfully tested the world first cable car. Operated by the nonprofit "Friends of the Cable Car Museum" the Cable Car Museum provides not only an historical perspective of the importance of the cable car to San Francisco, but an insight into the daily operations of todays system.', '1201 Mason Street at Washington, San Francisco.', 2, 1);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-07', 'NASA Ames Research Center', 'http://www.arc.nasa.gov/about_ames', 'Ames was founded December 20, 1939 as an aircraft research laboratory by the National Advisory Committee on Aeronautics (NACA) and in 1958 became part of National Aeronautics and Space Administration (NASA). Ames specializes in research geared toward creating new knowledge and new technologies that span the spectrum of NASA interests.', 'NASA Ames Research Center is located at Moffett Field, California in the heart of Silicon Valley', 1, 1);

insert into links (date_added, link_name, link_url, link_desc, address  , category_id, approved)
  values('2001-01-08', 'Berkeley Lab', 'http://www.lbl.gov/', 'Berkeley Lab is a multidisciplinary national laboratory that conducts nonclassified research. We are located in Berkeley, California on a site in the hills directly above the campus of the University of California at Berkeley.  Overlooking both the campus and San Francisco Bay, the site consists of 76 buildings located on 183 acres.', null, 1, 1);

CREATE TABLE lookup_countries (
       country_id           int auto_increment primary key,
       country_desc         varchar(40) NULL
);

insert into lookup_countries (country_desc) values ('United States');
insert into lookup_countries (country_desc) values ('Canada');
insert into lookup_countries (country_desc) values ('United Kingdom');
insert into lookup_countries (country_desc) values ('Afghanistan');
insert into lookup_countries (country_desc) values ('Albania');
insert into lookup_countries (country_desc) values ('Algeria');
insert into lookup_countries (country_desc) values ('American Samoa');
insert into lookup_countries (country_desc) values ('Andorra');
insert into lookup_countries (country_desc) values ('Angola');
insert into lookup_countries (country_desc) values ('Anguilla');
insert into lookup_countries (country_desc) values ('Antigua and Barbuda');
insert into lookup_countries (country_desc) values ('Argentina');
insert into lookup_countries (country_desc) values ('Armenia');
insert into lookup_countries (country_desc) values ('Aruba');
insert into lookup_countries (country_desc) values ('Australia');
insert into lookup_countries (country_desc) values ('Austria');
insert into lookup_countries (country_desc) values ('Azerbaijan Republic');
insert into lookup_countries (country_desc) values ('Bahamas');
insert into lookup_countries (country_desc) values ('Bahrain');
insert into lookup_countries (country_desc) values ('Bangladesh');
insert into lookup_countries (country_desc) values ('Barbados');
insert into lookup_countries (country_desc) values ('Belarus');
insert into lookup_countries (country_desc) values ('Belgium');
insert into lookup_countries (country_desc) values ('Belize');
insert into lookup_countries (country_desc) values ('Benin');
insert into lookup_countries (country_desc) values ('Bermuda');
insert into lookup_countries (country_desc) values ('Bhutan');
insert into lookup_countries (country_desc) values ('Bolivia');
insert into lookup_countries (country_desc) values ('Bosnia and Herzegovina');
insert into lookup_countries (country_desc) values ('Botswana');
insert into lookup_countries (country_desc) values ('Brazil');
insert into lookup_countries (country_desc) values ('British Virgin Islands');
insert into lookup_countries (country_desc) values ('Brunei Darussalam');
insert into lookup_countries (country_desc) values ('Bulgaria');
insert into lookup_countries (country_desc) values ('Burkina Faso');
insert into lookup_countries (country_desc) values ('Burma');
insert into lookup_countries (country_desc) values ('Burundi');
insert into lookup_countries (country_desc) values ('Cambodia');
insert into lookup_countries (country_desc) values ('Cameroon');
insert into lookup_countries (country_desc) values ('Cape Verde Islands');
insert into lookup_countries (country_desc) values ('Cayman Islands');
insert into lookup_countries (country_desc) values ('Central African Republic');
insert into lookup_countries (country_desc) values ('Chad');
insert into lookup_countries (country_desc) values ('Chile');
insert into lookup_countries (country_desc) values ('China');
insert into lookup_countries (country_desc) values ('Colombia');
insert into lookup_countries (country_desc) values ('Comoros');
insert into lookup_countries (country_desc) values ('Congo, Democratic Republic of');
insert into lookup_countries (country_desc) values ('Cook Islands');
insert into lookup_countries (country_desc) values ('Costa Rica');
insert into lookup_countries (country_desc) values ('Cote d Ivoire (Ivory Coast)');
insert into lookup_countries (country_desc) values ('Croatia, Democratic Republic of the');
insert into lookup_countries (country_desc) values ('Cuba');
insert into lookup_countries (country_desc) values ('Cyprus');
insert into lookup_countries (country_desc) values ('Czech Republic');
insert into lookup_countries (country_desc) values ('Denmark');
insert into lookup_countries (country_desc) values ('Djibouti');
insert into lookup_countries (country_desc) values ('Dominica');
insert into lookup_countries (country_desc) values ('Dominican Republic');
insert into lookup_countries (country_desc) values ('Ecuador');
insert into lookup_countries (country_desc) values ('Egypt');
insert into lookup_countries (country_desc) values ('El Salvador');
insert into lookup_countries (country_desc) values ('Equatorial Guinea');
insert into lookup_countries (country_desc) values ('Eritrea');
insert into lookup_countries (country_desc) values ('Estonia');
insert into lookup_countries (country_desc) values ('Ethiopia');
insert into lookup_countries (country_desc) values ('Falkland Islands (Islas Makvinas)');
insert into lookup_countries (country_desc) values ('Fiji');
insert into lookup_countries (country_desc) values ('Finland');
insert into lookup_countries (country_desc) values ('France');
insert into lookup_countries (country_desc) values ('French Guiana');
insert into lookup_countries (country_desc) values ('French Polynesia');
insert into lookup_countries (country_desc) values ('Gabon Republic');
insert into lookup_countries (country_desc) values ('Gambia');
insert into lookup_countries (country_desc) values ('Georgia');
insert into lookup_countries (country_desc) values ('Germany');
insert into lookup_countries (country_desc) values ('Ghana');
insert into lookup_countries (country_desc) values ('Gibraltar');
insert into lookup_countries (country_desc) values ('Greece');
insert into lookup_countries (country_desc) values ('Greenland');
insert into lookup_countries (country_desc) values ('Grenada');
insert into lookup_countries (country_desc) values ('Guadeloupe');
insert into lookup_countries (country_desc) values ('Guam');
insert into lookup_countries (country_desc) values ('Guatemala');
insert into lookup_countries (country_desc) values ('Guernsey');
insert into lookup_countries (country_desc) values ('Guinea');
insert into lookup_countries (country_desc) values ('Guinea-Bissau');
insert into lookup_countries (country_desc) values ('Guyana');
insert into lookup_countries (country_desc) values ('Haiti');
insert into lookup_countries (country_desc) values ('Honduras');
insert into lookup_countries (country_desc) values ('Hong Kong');
insert into lookup_countries (country_desc) values ('Hungary');
insert into lookup_countries (country_desc) values ('Iceland');
insert into lookup_countries (country_desc) values ('Japan');
insert into lookup_countries (country_desc) values ('Jersey');
insert into lookup_countries (country_desc) values ('Jordan');
insert into lookup_countries (country_desc) values ('Kazakhstan');
insert into lookup_countries (country_desc) values ('Kenya Coast Republic');
insert into lookup_countries (country_desc) values ('Kiribati');
insert into lookup_countries (country_desc) values ('Korea, North');
insert into lookup_countries (country_desc) values ('Korea, South');
insert into lookup_countries (country_desc) values ('Kuwait');
insert into lookup_countries (country_desc) values ('Kyrgyzstan');
insert into lookup_countries (country_desc) values ('Laos');
insert into lookup_countries (country_desc) values ('Latvia');
insert into lookup_countries (country_desc) values ('Lebanon, South');
insert into lookup_countries (country_desc) values ('Lesotho');
insert into lookup_countries (country_desc) values ('Liberia');
insert into lookup_countries (country_desc) values ('Libya');
insert into lookup_countries (country_desc) values ('Liechtenstein');
insert into lookup_countries (country_desc) values ('Lithuania');
insert into lookup_countries (country_desc) values ('Luxembourg');
insert into lookup_countries (country_desc) values ('Macau');
insert into lookup_countries (country_desc) values ('Macedonia');
insert into lookup_countries (country_desc) values ('Madagascar');
insert into lookup_countries (country_desc) values ('Malawi');
insert into lookup_countries (country_desc) values ('Malaysia');
insert into lookup_countries (country_desc) values ('Maldives');
insert into lookup_countries (country_desc) values ('Mali');
insert into lookup_countries (country_desc) values ('Malta');
insert into lookup_countries (country_desc) values ('Marshall Islands');
insert into lookup_countries (country_desc) values ('Martinique');
insert into lookup_countries (country_desc) values ('Mauritania');
insert into lookup_countries (country_desc) values ('Mauritius');
insert into lookup_countries (country_desc) values ('Mayotte');
insert into lookup_countries (country_desc) values ('Mexico');
insert into lookup_countries (country_desc) values ('Moldova');
insert into lookup_countries (country_desc) values ('Monaco');
insert into lookup_countries (country_desc) values ('Mongolia');
insert into lookup_countries (country_desc) values ('Montserrat');
insert into lookup_countries (country_desc) values ('Morocco');
insert into lookup_countries (country_desc) values ('Mozambique');
insert into lookup_countries (country_desc) values ('Namibia');
insert into lookup_countries (country_desc) values ('Nauru');
insert into lookup_countries (country_desc) values ('Nepal');
insert into lookup_countries (country_desc) values ('Netherlands');
insert into lookup_countries (country_desc) values ('Netherlands Antilles');
insert into lookup_countries (country_desc) values ('New Caledonia');
insert into lookup_countries (country_desc) values ('New Zealand');
insert into lookup_countries (country_desc) values ('Nicaragua');
insert into lookup_countries (country_desc) values ('Niger');
insert into lookup_countries (country_desc) values ('Nigeria');
insert into lookup_countries (country_desc) values ('Niue');
insert into lookup_countries (country_desc) values ('Norway');
insert into lookup_countries (country_desc) values ('Oman');
insert into lookup_countries (country_desc) values ('Pakistan');
insert into lookup_countries (country_desc) values ('Palau');
insert into lookup_countries (country_desc) values ('Panama');
insert into lookup_countries (country_desc) values ('Papua New Guinea');
insert into lookup_countries (country_desc) values ('Paraguay');
insert into lookup_countries (country_desc) values ('Peru');
insert into lookup_countries (country_desc) values ('Philippines');
insert into lookup_countries (country_desc) values ('Poland');
insert into lookup_countries (country_desc) values ('Portugal');
insert into lookup_countries (country_desc) values ('Puerto Rico');
insert into lookup_countries (country_desc) values ('Qatar');
insert into lookup_countries (country_desc) values ('Romania');
insert into lookup_countries (country_desc) values ('Russian Federation');
insert into lookup_countries (country_desc) values ('Rwanda');
insert into lookup_countries (country_desc) values ('Saint Helena');
insert into lookup_countries (country_desc) values ('Saint Kitts-Nevis');
insert into lookup_countries (country_desc) values ('Saint Lucia');
insert into lookup_countries (country_desc) values ('Saint Pierre and Miquelon');
insert into lookup_countries (country_desc) values ('Saint Vincent and the Grenadines');
insert into lookup_countries (country_desc) values ('San Marino');
insert into lookup_countries (country_desc) values ('Saudi Arabia');
insert into lookup_countries (country_desc) values ('Senegal');
insert into lookup_countries (country_desc) values ('Seychelles');
insert into lookup_countries (country_desc) values ('Sierra Leone');
insert into lookup_countries (country_desc) values ('Singapore');
insert into lookup_countries (country_desc) values ('Slovakia');
insert into lookup_countries (country_desc) values ('Slovenia');
insert into lookup_countries (country_desc) values ('Solomon Islands');
insert into lookup_countries (country_desc) values ('Somalia');
insert into lookup_countries (country_desc) values ('South Africa');
insert into lookup_countries (country_desc) values ('Spain');
insert into lookup_countries (country_desc) values ('Sri Lanka');
insert into lookup_countries (country_desc) values ('Sudan');
insert into lookup_countries (country_desc) values ('Suriname');
insert into lookup_countries (country_desc) values ('Svalbard');
insert into lookup_countries (country_desc) values ('Swaziland');
insert into lookup_countries (country_desc) values ('Sweden');
insert into lookup_countries (country_desc) values ('Switzerland');
insert into lookup_countries (country_desc) values ('Syria');
insert into lookup_countries (country_desc) values ('Tahiti');
insert into lookup_countries (country_desc) values ('Taiwan');
insert into lookup_countries (country_desc) values ('Tajikistan');
insert into lookup_countries (country_desc) values ('Tanzania');
insert into lookup_countries (country_desc) values ('Thailand');
insert into lookup_countries (country_desc) values ('Togo');
insert into lookup_countries (country_desc) values ('Tonga');
insert into lookup_countries (country_desc) values ('Trinidad and Tobago');
insert into lookup_countries (country_desc) values ('Tunisia');
insert into lookup_countries (country_desc) values ('Turkey');
insert into lookup_countries (country_desc) values ('Turkmenistan');
insert into lookup_countries (country_desc) values ('Turks and Caicos Islands');
insert into lookup_countries (country_desc) values ('Tuvalu');
insert into lookup_countries (country_desc) values ('Uganda');
insert into lookup_countries (country_desc) values ('Ukraine');
insert into lookup_countries (country_desc) values ('United Arab Emirates');
insert into lookup_countries (country_desc) values ('Uruguay');
insert into lookup_countries (country_desc) values ('Uzbekistan');
insert into lookup_countries (country_desc) values ('Vanuatu');
insert into lookup_countries (country_desc) values ('Vatican City, State');
insert into lookup_countries (country_desc) values ('Venezuela');
insert into lookup_countries (country_desc) values ('Vietnam');
insert into lookup_countries (country_desc) values ('Virgin Islands (U.S.)');
insert into lookup_countries (country_desc) values ('Wallis and Futuna');
insert into lookup_countries (country_desc) values ('Western Sahara');
insert into lookup_countries (country_desc) values ('Western Samoa');
insert into lookup_countries (country_desc) values ('Yemen');
insert into lookup_countries (country_desc) values ('Yugoslavia');
insert into lookup_countries (country_desc) values ('Zambia');
insert into lookup_countries (country_desc) values ('Zimbabwe');

CREATE TABLE lookup_states (
       state_id             varchar(2) primary key,
       state_desc           varchar(25) NULL
);

insert into lookup_states (state_id, state_desc) values ('AK', 'Alaska');
insert into lookup_states (state_id, state_desc) values ('AL', 'Alabama');
insert into lookup_states (state_id, state_desc) values ('AR', 'Arkansas');
insert into lookup_states (state_id, state_desc) values ('AS', 'American');
insert into lookup_states (state_id, state_desc) values ('AZ', 'Arizona');
insert into lookup_states (state_id, state_desc) values ('CA', 'California');
insert into lookup_states (state_id, state_desc) values ('CO', 'Colorado');
insert into lookup_states (state_id, state_desc) values ('CT', 'Connecticut');
insert into lookup_states (state_id, state_desc) values ('DC', 'District of Columbia');
insert into lookup_states (state_id, state_desc) values ('DE', 'Delaware');
insert into lookup_states (state_id, state_desc) values ('FL', 'Florida');
insert into lookup_states (state_id, state_desc) values ('GA', 'Georgia');
insert into lookup_states (state_id, state_desc) values ('GU', 'Guam');
insert into lookup_states (state_id, state_desc) values ('HI', 'Hawaii');
insert into lookup_states (state_id, state_desc) values ('IA', 'Iowa');
insert into lookup_states (state_id, state_desc) values ('ID', 'Idaho');
insert into lookup_states (state_id, state_desc) values ('IL', 'Illinois');
insert into lookup_states (state_id, state_desc) values ('IN', 'Indiana');
insert into lookup_states (state_id, state_desc) values ('KS', 'Kansas');
insert into lookup_states (state_id, state_desc) values ('KY', 'Kentucky');
insert into lookup_states (state_id, state_desc) values ('LA', 'Louisiana');
insert into lookup_states (state_id, state_desc) values ('MA', 'Massachusetts');
insert into lookup_states (state_id, state_desc) values ('MD', 'Maryland');
insert into lookup_states (state_id, state_desc) values ('ME', 'Maine');
insert into lookup_states (state_id, state_desc) values ('MI', 'Michigan');
insert into lookup_states (state_id, state_desc) values ('MN', 'Minnesota');
insert into lookup_states (state_id, state_desc) values ('MO', 'Missouri');
insert into lookup_states (state_id, state_desc) values ('MP', 'Northern');
insert into lookup_states (state_id, state_desc) values ('MS', 'Missippi');
insert into lookup_states (state_id, state_desc) values ('MT', 'Montana');
insert into lookup_states (state_id, state_desc) values ('NC', 'North Carolina');
insert into lookup_states (state_id, state_desc) values ('ND', 'North Dakota');
insert into lookup_states (state_id, state_desc) values ('NE', 'Nebraska');
insert into lookup_states (state_id, state_desc) values ('NH', 'New Hampshire');
insert into lookup_states (state_id, state_desc) values ('NJ', 'New Jersey');
insert into lookup_states (state_id, state_desc) values ('NM', 'New Mexico');
insert into lookup_states (state_id, state_desc) values ('NV', 'Nevada');
insert into lookup_states (state_id, state_desc) values ('NY', 'New York');
insert into lookup_states (state_id, state_desc) values ('OH', 'Ohio');
insert into lookup_states (state_id, state_desc) values ('OK', 'Oklahoma');
insert into lookup_states (state_id, state_desc) values ('OR', 'Oren');
insert into lookup_states (state_id, state_desc) values ('PA', 'Pennsylvania');
insert into lookup_states (state_id, state_desc) values ('PR', 'Puerto Rico');
insert into lookup_states (state_id, state_desc) values ('PW', 'Palau');
insert into lookup_states (state_id, state_desc) values ('RI', 'Rhode Island');
insert into lookup_states (state_id, state_desc) values ('SC', 'South Carolina');
insert into lookup_states (state_id, state_desc) values ('SD', 'South Dakota');
insert into lookup_states (state_id, state_desc) values ('TN', 'Tennessee');
insert into lookup_states (state_id, state_desc) values ('TX', 'Texas');
insert into lookup_states (state_id, state_desc) values ('UT', 'Utah');
insert into lookup_states (state_id, state_desc) values ('VA', 'Virginia');
insert into lookup_states (state_id, state_desc) values ('VI', 'Virgin Island');
insert into lookup_states (state_id, state_desc) values ('VT', 'Vermont');
insert into lookup_states (state_id, state_desc) values ('WA', 'Washington');
insert into lookup_states (state_id, state_desc) values ('WI', 'Wisconsin');
insert into lookup_states (state_id, state_desc) values ('WV', 'West Virginia');
insert into lookup_states (state_id, state_desc) values ('WY', 'Wyoming');

CREATE TABLE members (
       member_id            int auto_increment primary key,
       first_name           varchar(20) NULL,
       last_name            varchar(20) NULL,
       member_login         varchar(15) NULL,
       member_password      varchar(15) NULL,
       email                varchar(30) NULL,
       country_id           int NULL,
       state_id             varchar(2) NULL,
       city                 varchar(30) NULL,
       zip                  varchar(10) NULL,
       address1             varchar(50) NULL,
       address2             varchar(50) NULL,
       address3             varchar(50) NULL,
       phone_day            varchar(20) NULL,
       phone_evn            varchar(20) NULL,
       fax                  varchar(20) NULL,
       date_created         datetime NULL,
       ip_insert            varchar(50) NULL,
       ip_update            varchar(50) NULL,
       last_login_date      datetime NULL,
       security_level_id    smallint NULL DEFAULT 0
);

insert into members (first_name, last_name, member_login  , member_password, email, country_id, state_id, city, zip, security_level_id)
  values('Guest', 'Member', 'guest', 'guest', 'guest@nowhere.com', 1, 'CA', 'South San Francisco', '94080', 1);
insert into members (first_name, last_name, member_login  , member_password, email, country_id, state_id, city, zip, security_level_id)
  values('Admin', 'User', 'admin', 'admin', 'admin@nowhere.com', 1, 'CA', 'South San Francisco', '94080', 3);

CREATE TABLE officers (
       officer_id           int auto_increment primary key,
       officer_name         varchar(50) NULL,
       officer_position     varchar(50) NULL,
       officer_email        varchar(30) NULL
);

insert into officers (officer_name, officer_position, officer_email)
  values('James Morton', 'President  ', 'jmorton@nowhere.com');
insert into officers (officer_name, officer_position, officer_email)
  values('Adam Bryzek', 'Vice President', 'adam@nowhere.com');
insert into officers (officer_name, officer_position, officer_email)
  values('Matt Vaughn', 'Vice President', 'mvaughn@nowhere.com');
insert into officers (officer_name, officer_position, officer_email)
  values('Bob Newell', 'Secretary', 'bnewell@nowhere.com');
insert into officers (officer_name, officer_position, officer_email)
  values('Sabrina Rek', 'Treasurer', 'srek@nowhere.com');
insert into officers (officer_name, officer_position, officer_email)
  values('Jamie McCants', 'Webmaster', 'jmccants@nowhere.com');

CREATE TABLE news (
       news_id int auto_increment primary key,
       news_html text
);

insert into news (news_html)
values('<style>
  .meerkatTitle       {background-color: #FFCC66; font-size: 10pt; color: #000000; font-weight: bold; font-family: Arial}
  .meerkatDescription {background-color: #FFFFEE; font-size: 10pt; color: black}
  .meerkatCategory    {background-color: #FFFFEE; font-size: 9pt; font-weight: bold; color: brown}
  .meerkatChannel     {background-color: #FFFFEE; font-size: 9pt; color: brown}
  .meerkatDate        {background-color: #FFFFEE; font-size: 9pt; color: blue}
</style>
<script language="JavaScript"
src="http://meerkat.oreillynet.com/?m=296&_fl=js">
</script>
');