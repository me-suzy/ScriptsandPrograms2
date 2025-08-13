-- Copyright (C) 2001 YesSoftware. All rights reserved.
-- Portal_MSSQL.sql

if exists (select * from sysobjects where id = object_id(N'articles') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table articles
GO
if exists (select * from sysobjects where id = object_id(N'categories') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table categories
GO
if exists (select * from sysobjects where id = object_id(N'events') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table events
GO
if exists (select * from sysobjects where id = object_id(N'links') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table links
GO
if exists (select * from sysobjects where id = object_id(N'lookup_countries') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table lookup_countries
GO
if exists (select * from sysobjects where id = object_id(N'lookup_states') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table lookup_states
GO
if exists (select * from sysobjects where id = object_id(N'members') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table members
GO
if exists (select * from sysobjects where id = object_id(N'officers') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table officers
GO
if exists (select * from sysobjects where id = object_id(N'news') and OBJECTPROPERTY(id, N'IsUserTable') = 1) drop table news
GO

CREATE TABLE articles (
       article_id           int identity  primary key,
       article_title        varchar(125) NULL,
       category_id          int NULL,
       article_desc         text NULL,
       date_posted          datetime NULL,
       date_expire          datetime NULL
)
GO

insert into articles (article_title, category_id, article_desc)
	values('Silicon Valley Engineers Club', 9, '<font color="blue"><b>Silicon Valley Engineers Club is a technical and social organization. Our members are mostly, but not exclusively residing in the greater San Francisco Bay Area.</b></font><br>The Engineers Club has offices at the 215 South 16th Street in San Francisco. The Club office is manned four days a week by a volunteer staff. James Morton is the Executive Director and President. He is also the elected Secretary and Treasurer. Tom McDonald joined James at the office in 1991 and heads the education committee.')
GO

insert into articles (article_title, category_id, article_desc)
	values('A brief history', 10, 'The Centennial Exposition of 1876 in San Francisco drew engineers from around the world to view the technical advances of that century. It was apparent to engineers residing in California that engineers did not know each other. Charles E. Billin invited twenty or so engineers to his home the following winter for refreshments. This started a series of gatherings at the homes of various engineers. The Club formally organized in December of 1877 and Professor L M. Haupt was its first President. It limited its membership to fifty. The minutes of the first meeting stated: Its object shall be the professional improvement of its members, the encouragement of social intercourse among men of practical science, and the advancement of engineering in its several branches. The Club adopted as its motto a quotation from George Washington speech to the Federal Convention of 1787 accepting the role of president. It expresses the charge placed upon the Engineers Clubs members by its charter. The motto states: Let us raise a standard to which the wise and honest can repair.')
GO

insert into articles (article_title, category_id, article_desc)
	values('Education', 7, 'The Silicon Valley Engineers Club is offering courses which meet the needs of many practicing professionals. In todays rapidly changing world, it is mandatory that we maintain our skills. One effective and enjoyable way to accomplish this goal is the educational program offered by the Engineers Club. We can exchange ideas, share experiences and learn from experts in various fields. You can receive 20 hours of instruction for less than you would commonly pay for a one day seminar. One evening a week means that you do not have to take valuable time from the job, nor need you make great personal sacrifices to enhance your career. Make the commitment now to invest in your professional development.')
GO

insert into articles (article_title, category_id, article_desc)
	values('Tours of Silicon Valley', 6, 'Engineering Tours of the Silicon Valley are visits to facilities and sites with a wide variety of technical features to stimulate your interest. They are geared to you, the technical professional. <br>Attend one or more tours. Club membership is not required! You will be impressed by the knowledge and friendliness of the hosts. They are there for you! <br>WHO CAN PARTICIPATE? Anyone.<br>CAN I BRING A GUEST? Yes.<br>DO I HAVE TO BE AN ENGINEERS CLUB MEMBER? No. <br>WHAT DOES IT COST? Each tour is $10.00 per person. However, Engineers Club members and one guest may attend at no charge. <br>WHO PROVIDES TRANSPORTATION TO THE FACILITY? You do.')
GO

insert into articles (article_title, category_id, article_desc)
	values('Benefits', 8, '<ul><li>Career Enhancement - A place to meet others in your profession, share ideas and make valuable contacts.</li><li>Professional development programs, symposiums, and speeches.</li><li>Seminars, technical presentations, topical meetings with discounts on fees to members.</li><li>Meetings facilities including 400 seat auditorium, library and meetings rooms.</li><li>Engineers Club annual roster.</li><li>Silicon Valley Engineer monthly newsletter.</li></ul>')
GO

insert into articles (article_title, category_id, article_desc)
	values('Membership', 10, 'The requirements for becoming a member are liberal although applicants are carefully screened for sincerity of interests and whether or not they could benefits from Club membership. The qualifications are only that the applicant be associated with the engineering profession as a result of vocation, hobby or business contact. Each applicant must have two sponsors who are members of the Club. Membership in the Engineers Club of St. Louis is one of the best investments a technical man or woman can make.')
GO

insert into articles (article_title, category_id, article_desc)
	values('Monthly Meetings', 10, 'The General meetings are held on the third Thursday of each month at the Valley Caffe, corner of Berkeley and Columbus.<br>Park in the lot next to the building on Columbus.<br>Our meetings are also convenient to reach via public transportation. Nearest BART station is Powell.<br><br>Meetings begin at 19:00.')
GO

CREATE TABLE categories (
       category_id          int identity  primary key,
       category_desc        varchar(50) NULL
)
GO

insert into categories (category_desc) values ('Science')
GO
insert into categories (category_desc) values ('Museums')
GO
insert into categories (category_desc) values ('Computers')
GO
insert into categories (category_desc) values ('Business')
GO
insert into categories (category_desc) values ('Internet')
GO
insert into categories (category_desc) values ('Education')
GO
insert into categories (category_desc) values ('Carrer')
GO
insert into categories (category_desc) values ('History')
GO
insert into categories (category_desc) values ('Club Information')
GO

CREATE TABLE events (
       event_id             int identity  primary key,
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
)
GO

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
	values('2000-12-01 19:30:00', null, 'Marketing Strategy for High Technology Companies with Disruptive Technologies, or Why Smart Engineers Fail in Bringing Advanced Products to Market.', ' Many high technology developments qualify as discontinuous innovation, which is defined as one that requires a change of customer behavior. There are two generic types of discontinuous innovation: Paradigm shock (e.g., PC replacing a typewriter) Application breakthrough (e.g., voice recognition replacing service personnel)  On the opposite end there are continuous innovations, which do not require customer behavior change, e,g., using a new toothpaste. As Silicon Valley marketing guru Regis McKenna once wrote, "Marketing is Everything" (and not the technology, engineers).  It is thus marketing flow that most often defines a product (and often company) market failure.  Selection of a suitable marketing strategy is thus extremely important success factor for high-tech startups. ', ' Dr. Janusz Bryzek', 'Blue Star Coffee, 1071 S. DeAnza Blvd, Cupertino 408.863.0707', null)
GO

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
	values('2001-01-19 19:30:00', null, 'Helping schools develop students interest in science and technology: SCVSEFA and science fairs.', null, 'Kerry Veenstra', 'TBD', null)
GO

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
	values('2000-10-21 18:00:00', null, 'Performance of US economy in 2001. More about Dr. Belotti. Pictures.', 'Our guest speaker will be Prof. Mario Belotti. He will present "The forecast of the US economy performance in 2001" Dr Mario Belotti has been teaching at Santa Clara University since 1959.  He teaches economics in both the graduate and undergraduate programs. He served as Chair of the Economics Department from 1962 to 1984 and as the Director of the Institute of Agribusiness from 1988 to 1996. His fields of interest are macroeconomics theory, monetary theory and economic development. He organized and chaired 23 Santa Clara University Symposiums and 28 yearly Economic Forecasts. Dr. Belotti spent many summers as an economic consultant in developing countries: Brazil, Honduras, Sudan, Egypt, Iran, Uganda, Kenya, Tanzania, Thailand, Bulgaria, Hungry, Nepal, and Eritrea.  Dr. Belotti received a Bachelor of Science in Business Administration and a Master of Arts in Accounting from Midwestern University. He obtained a Ph.D. in Economics from the University of Texas in Austin.', 'Dr. Mario Belotti, Professor of Economics, Santa Clara University.', 'Hotel Embassy Suites (Diplomat Room), 2885 Lakeside Drive, Santa Clara', null)
GO

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
	values('2000-09-22 19:00:00', null, 'Restructuring of the International energy sector - a combination of economics and politics".', 'International market is undergoing a major restructuring of its energy sector. Many power plants were built to burn coal, which some countries claimed to have in unlimited supply. This system created inefficiency, mismanagement and environmental abuse. Piotr Moncarz talk will present the aspects of the International energy market restructuring with an emphasis on the privatization and modernization aspects of investment in small and medium size power plants. About the speaker: Piotr Moncarz was born and raised in Poznan, Poland. For last twenty years he has worked at a high profile consulting company: Exponent Failure Analysis, where he is a Principal Engineer and Corporate Vice President. For last seven years his efforts have included the development of a gas-fired electric power plant at Lake Zarnowiec near Gdansk, on the site of an abandoned project of the first Polish nuclear plant. The promotion of American investment into Polish economy has become his passion, as he believes this to be the most effective political independence insurance Poland can acquire while entering the new century. Piotr Moncarz has Ph.D. in Civil Engineering from Stanford University, where he teaches graduate classes a Consulting Professor.', 'Piotr D. Moncarz', 'ACUSON (bldg.I) 1393 Shorebird Way (corner of Shoreline Blvd.), Mountain View', null)
GO

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
	values('2000-06-23 19:00:00', null, 'On June 23,2000 during the Club monthly meeting, Dr. Zofia Rek presented an interesting lecture about her work experience at Stanford Synchotron Radiation Laboratory where she works since 1980.', 'On June 23,2000 during the Club monthly meeting, Dr. Zofia Rek presented an interesting lecture about her work experience at Stanford Synchotron Radiation Laboratory where she works since 1980. SSRL is multidisciplinary laboratory funded by Department of Energy and National Institute of Health.  Synchrotron radiation is the electromagnetic radiation emitted by electrons along curved trajectory.  Most of the experiments are in the X-ray energy range although higher energies in the alfa range can be obtained. Applications are very diversified: X-ray imaging of defects and strains for large crystals for semiconductor wafers to laser windows to study fusion. In biology studies are made to detect and  eleminate selenium areas in USA in order to improve the quality of soil. In medicine radiation is used in angiography to detect blockage of heart arteries without invasion of catater and also in studies of osteoporosis.  The large part of the lecture was dedicated to protein crytallography as a tool for solving protein structure so important for human genomic project. Zofia also discussed some other works done by SSRL like membrane protein which works as an intelligent guard against cholera toxin and Topoisomerase a protein that is targeted by anti-cancer drugs.  There was a very interesting discussion after the lecture and members of the Club had an opportunity to examine numerous models and sampels brought by Zofia.', 'Zofia Rek, Stanford Synchrotron Radiation Laboratory', 'TBD', null)
GO

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
	values('2000-05-19 19:00:00', null, 'Issues in Software Development Evolution - a Veteran Practitioners Point of View', 'On May meeting Dr. Zbigniew Sufleta has entertained PAEC club with the presentation on the subject of Software Development Process, the Practitioners point of view. His extensive industry experience (over 20 years) gave his listeners a rare opportunity to hear about the process itself, the principles and what applies in the real life. The speech has concluded with a round table discussion about the current market conditions and the impact it bears on the Bay area living conditions (tight labor market, escalating salaries, booming real estate, traffic congestions).', 'Dr. Zbigniew Sufleta', 'TBD', null)
GO

insert into events (date_start, date_end, event_name, event_desc, presenter, location, location_url)
	values('2000-03-24 19:00:00', null, 'Problems with the manpower in Hi-Tech industry in Silicon Valley.', '', 'Prof. Aleksander Liniecki, San Jose State University', 'The Clubhouse in "Sunset Oaks" townhouse complex. 675 Picasso Terrace, Sunnyvale.', 'http://sunsetoaks.org/location.html#http://sunsetoaks.org/location.html#')
GO

CREATE TABLE links (
       link_id              int identity  primary key,
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
)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-01', 'TheTech Museum of Innovation', 'http://www.thetech.org/', 'The Tech Museum of Innovation in San Jose, California is a hands-on technology museum devoted to inspiring the innovator in everyone.', '201 South Market Street San Jose, CA 95113 408-294-TECH', 1, 1)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-02', 'Lawrence Livermore National Laboratory', 'http://www.llnl.gov/', 'Lawrence Livermore National Laboratory ensures national security and applies science and technology to important problems of our time.', '7000 East Ave., Livermore, CA 94550-9234', 1, 1)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-03', 'Monterey Bay Aquarium', 'http://www.mbayaq.org/sg', 'The Monterey Bay Aquarium is dedicated to inspiring ocean conservation. Find out more about how we approach our mission.', null, 1, 1)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-04', 'Chabot Space & Science Center', 'http://www.cosc.org/', 'At Chabot Space & Science Center (CSSC), the universe is yours to experience. Set amid 13 trail-laced acres of East Bay parkland, with glorious views of San Francisco Bay and the Oakland foothills, CSSC is a hands-on celebration of sights, sounds, and sensations.', '10000 Skyline Blvd. Oakland, CA 94619', 1, 1)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-05', 'UC Observatories/Lick Observatory', 'http://www.ucolick.org/', 'University of California Observatories/Lick Observatory conducts leading-edge research to answer the most profound questions in observational astronomy.', 'University of California, Santa Cruz, CA 95064 Phone: 831/459-2513; Fax: 831/426-3115', 1, 1)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-06', 'San Francisco Cable Car Museum', 'http://www.cablecarmuseum.com/', 'The cable car was born in San Francisco at four oclock in the morning on August 2, 1873, when Andrew Smith Hallidie successfully tested the world first cable car. Operated by the nonprofit "Friends of the Cable Car Museum" the Cable Car Museum provides not only an historical perspective of the importance of the cable car to San Francisco, but an insight into the daily operations of todays system.', '1201 Mason Street at Washington, San Francisco.', 2, 1)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-07', 'NASA Ames Research Center', 'http://www.arc.nasa.gov/about_ames', 'Ames was founded December 20, 1939 as an aircraft research laboratory by the National Advisory Committee on Aeronautics (NACA) and in 1958 became part of National Aeronautics and Space Administration (NASA). Ames specializes in research geared toward creating new knowledge and new technologies that span the spectrum of NASA interests.', 'NASA Ames Research Center is located at Moffett Field, California in the heart of Silicon Valley', 1, 1)
GO

insert into links (date_added, link_name, link_url, link_desc, address	, category_id, approved)
	values('2001-01-08', 'Berkeley Lab', 'http://www.lbl.gov/', 'Berkeley Lab is a multidisciplinary national laboratory that conducts nonclassified research. We are located in Berkeley, California on a site in the hills directly above the campus of the University of California at Berkeley.  Overlooking both the campus and San Francisco Bay, the site consists of 76 buildings located on 183 acres.', null, 1, 1)
GO

CREATE TABLE lookup_countries (
       country_id           int identity  primary key,
       country_desc         varchar(40) NULL
)
GO

insert into lookup_countries (country_desc) values ('United States')
GO
insert into lookup_countries (country_desc) values ('Canada')
GO
insert into lookup_countries (country_desc) values ('United Kingdom')
GO
insert into lookup_countries (country_desc) values ('Afghanistan')
GO
insert into lookup_countries (country_desc) values ('Albania')
GO
insert into lookup_countries (country_desc) values ('Algeria')
GO
insert into lookup_countries (country_desc) values ('American Samoa')
GO
insert into lookup_countries (country_desc) values ('Andorra')
GO
insert into lookup_countries (country_desc) values ('Angola')
GO
insert into lookup_countries (country_desc) values ('Anguilla')
GO
insert into lookup_countries (country_desc) values ('Antigua and Barbuda')
GO
insert into lookup_countries (country_desc) values ('Argentina')
GO
insert into lookup_countries (country_desc) values ('Armenia')
GO
insert into lookup_countries (country_desc) values ('Aruba')
GO
insert into lookup_countries (country_desc) values ('Australia')
GO
insert into lookup_countries (country_desc) values ('Austria')
GO
insert into lookup_countries (country_desc) values ('Azerbaijan Republic')
GO
insert into lookup_countries (country_desc) values ('Bahamas')
GO
insert into lookup_countries (country_desc) values ('Bahrain')
GO
insert into lookup_countries (country_desc) values ('Bangladesh')
GO
insert into lookup_countries (country_desc) values ('Barbados')
GO
insert into lookup_countries (country_desc) values ('Belarus')
GO
insert into lookup_countries (country_desc) values ('Belgium')
GO
insert into lookup_countries (country_desc) values ('Belize')
GO
insert into lookup_countries (country_desc) values ('Benin')
GO
insert into lookup_countries (country_desc) values ('Bermuda')
GO
insert into lookup_countries (country_desc) values ('Bhutan')
GO
insert into lookup_countries (country_desc) values ('Bolivia')
GO
insert into lookup_countries (country_desc) values ('Bosnia and Herzegovina')
GO
insert into lookup_countries (country_desc) values ('Botswana')
GO
insert into lookup_countries (country_desc) values ('Brazil')
GO
insert into lookup_countries (country_desc) values ('British Virgin Islands')
GO
insert into lookup_countries (country_desc) values ('Brunei Darussalam')
GO
insert into lookup_countries (country_desc) values ('Bulgaria')
GO
insert into lookup_countries (country_desc) values ('Burkina Faso')
GO
insert into lookup_countries (country_desc) values ('Burma')
GO
insert into lookup_countries (country_desc) values ('Burundi')
GO
insert into lookup_countries (country_desc) values ('Cambodia')
GO
insert into lookup_countries (country_desc) values ('Cameroon')
GO
insert into lookup_countries (country_desc) values ('Cape Verde Islands')
GO
insert into lookup_countries (country_desc) values ('Cayman Islands')
GO
insert into lookup_countries (country_desc) values ('Central African Republic')
GO
insert into lookup_countries (country_desc) values ('Chad')
GO
insert into lookup_countries (country_desc) values ('Chile')
GO
insert into lookup_countries (country_desc) values ('China')
GO
insert into lookup_countries (country_desc) values ('Colombia')
GO
insert into lookup_countries (country_desc) values ('Comoros')
GO
insert into lookup_countries (country_desc) values ('Congo, Democratic Republic of')
GO
insert into lookup_countries (country_desc) values ('Cook Islands')
GO
insert into lookup_countries (country_desc) values ('Costa Rica')
GO
insert into lookup_countries (country_desc) values ('Cote d Ivoire (Ivory Coast)')
GO
insert into lookup_countries (country_desc) values ('Croatia, Democratic Republic of the')
GO
insert into lookup_countries (country_desc) values ('Cuba')
GO
insert into lookup_countries (country_desc) values ('Cyprus')
GO
insert into lookup_countries (country_desc) values ('Czech Republic')
GO
insert into lookup_countries (country_desc) values ('Denmark')
GO
insert into lookup_countries (country_desc) values ('Djibouti')
GO
insert into lookup_countries (country_desc) values ('Dominica')
GO
insert into lookup_countries (country_desc) values ('Dominican Republic')
GO
insert into lookup_countries (country_desc) values ('Ecuador')
GO
insert into lookup_countries (country_desc) values ('Egypt')
GO
insert into lookup_countries (country_desc) values ('El Salvador')
GO
insert into lookup_countries (country_desc) values ('Equatorial Guinea')
GO
insert into lookup_countries (country_desc) values ('Eritrea')
GO
insert into lookup_countries (country_desc) values ('Estonia')
GO
insert into lookup_countries (country_desc) values ('Ethiopia')
GO
insert into lookup_countries (country_desc) values ('Falkland Islands (Islas Makvinas)')
GO
insert into lookup_countries (country_desc) values ('Fiji')
GO
insert into lookup_countries (country_desc) values ('Finland')
GO
insert into lookup_countries (country_desc) values ('France')
GO
insert into lookup_countries (country_desc) values ('French Guiana')
GO
insert into lookup_countries (country_desc) values ('French Polynesia')
GO
insert into lookup_countries (country_desc) values ('Gabon Republic')
GO
insert into lookup_countries (country_desc) values ('Gambia')
GO
insert into lookup_countries (country_desc) values ('Georgia')
GO
insert into lookup_countries (country_desc) values ('Germany')
GO
insert into lookup_countries (country_desc) values ('Ghana')
GO
insert into lookup_countries (country_desc) values ('Gibraltar')
GO
insert into lookup_countries (country_desc) values ('Greece')
GO
insert into lookup_countries (country_desc) values ('Greenland')
GO
insert into lookup_countries (country_desc) values ('Grenada')
GO
insert into lookup_countries (country_desc) values ('Guadeloupe')
GO
insert into lookup_countries (country_desc) values ('Guam')
GO
insert into lookup_countries (country_desc) values ('Guatemala')
GO
insert into lookup_countries (country_desc) values ('Guernsey')
GO
insert into lookup_countries (country_desc) values ('Guinea')
GO
insert into lookup_countries (country_desc) values ('Guinea-Bissau')
GO
insert into lookup_countries (country_desc) values ('Guyana')
GO
insert into lookup_countries (country_desc) values ('Haiti')
GO
insert into lookup_countries (country_desc) values ('Honduras')
GO
insert into lookup_countries (country_desc) values ('Hong Kong')
GO
insert into lookup_countries (country_desc) values ('Hungary')
GO
insert into lookup_countries (country_desc) values ('Iceland')
GO
insert into lookup_countries (country_desc) values ('Japan')
GO
insert into lookup_countries (country_desc) values ('Jersey')
GO
insert into lookup_countries (country_desc) values ('Jordan')
GO
insert into lookup_countries (country_desc) values ('Kazakhstan')
GO
insert into lookup_countries (country_desc) values ('Kenya Coast Republic')
GO
insert into lookup_countries (country_desc) values ('Kiribati')
GO
insert into lookup_countries (country_desc) values ('Korea, North')
GO
insert into lookup_countries (country_desc) values ('Korea, South')
GO
insert into lookup_countries (country_desc) values ('Kuwait')
GO
insert into lookup_countries (country_desc) values ('Kyrgyzstan')
GO
insert into lookup_countries (country_desc) values ('Laos')
GO
insert into lookup_countries (country_desc) values ('Latvia')
GO
insert into lookup_countries (country_desc) values ('Lebanon, South')
GO
insert into lookup_countries (country_desc) values ('Lesotho')
GO
insert into lookup_countries (country_desc) values ('Liberia')
GO
insert into lookup_countries (country_desc) values ('Libya')
GO
insert into lookup_countries (country_desc) values ('Liechtenstein')
GO
insert into lookup_countries (country_desc) values ('Lithuania')
GO
insert into lookup_countries (country_desc) values ('Luxembourg')
GO
insert into lookup_countries (country_desc) values ('Macau')
GO
insert into lookup_countries (country_desc) values ('Macedonia')
GO
insert into lookup_countries (country_desc) values ('Madagascar')
GO
insert into lookup_countries (country_desc) values ('Malawi')
GO
insert into lookup_countries (country_desc) values ('Malaysia')
GO
insert into lookup_countries (country_desc) values ('Maldives')
GO
insert into lookup_countries (country_desc) values ('Mali')
GO
insert into lookup_countries (country_desc) values ('Malta')
GO
insert into lookup_countries (country_desc) values ('Marshall Islands')
GO
insert into lookup_countries (country_desc) values ('Martinique')
GO
insert into lookup_countries (country_desc) values ('Mauritania')
GO
insert into lookup_countries (country_desc) values ('Mauritius')
GO
insert into lookup_countries (country_desc) values ('Mayotte')
GO
insert into lookup_countries (country_desc) values ('Mexico')
GO
insert into lookup_countries (country_desc) values ('Moldova')
GO
insert into lookup_countries (country_desc) values ('Monaco')
GO
insert into lookup_countries (country_desc) values ('Mongolia')
GO
insert into lookup_countries (country_desc) values ('Montserrat')
GO
insert into lookup_countries (country_desc) values ('Morocco')
GO
insert into lookup_countries (country_desc) values ('Mozambique')
GO
insert into lookup_countries (country_desc) values ('Namibia')
GO
insert into lookup_countries (country_desc) values ('Nauru')
GO
insert into lookup_countries (country_desc) values ('Nepal')
GO
insert into lookup_countries (country_desc) values ('Netherlands')
GO
insert into lookup_countries (country_desc) values ('Netherlands Antilles')
GO
insert into lookup_countries (country_desc) values ('New Caledonia')
GO
insert into lookup_countries (country_desc) values ('New Zealand')
GO
insert into lookup_countries (country_desc) values ('Nicaragua')
GO
insert into lookup_countries (country_desc) values ('Niger')
GO
insert into lookup_countries (country_desc) values ('Nigeria')
GO
insert into lookup_countries (country_desc) values ('Niue')
GO
insert into lookup_countries (country_desc) values ('Norway')
GO
insert into lookup_countries (country_desc) values ('Oman')
GO
insert into lookup_countries (country_desc) values ('Pakistan')
GO
insert into lookup_countries (country_desc) values ('Palau')
GO
insert into lookup_countries (country_desc) values ('Panama')
GO
insert into lookup_countries (country_desc) values ('Papua New Guinea')
GO
insert into lookup_countries (country_desc) values ('Paraguay')
GO
insert into lookup_countries (country_desc) values ('Peru')
GO
insert into lookup_countries (country_desc) values ('Philippines')
GO
insert into lookup_countries (country_desc) values ('Poland')
GO
insert into lookup_countries (country_desc) values ('Portugal')
GO
insert into lookup_countries (country_desc) values ('Puerto Rico')
GO
insert into lookup_countries (country_desc) values ('Qatar')
GO
insert into lookup_countries (country_desc) values ('Romania')
GO
insert into lookup_countries (country_desc) values ('Russian Federation')
GO
insert into lookup_countries (country_desc) values ('Rwanda')
GO
insert into lookup_countries (country_desc) values ('Saint Helena')
GO
insert into lookup_countries (country_desc) values ('Saint Kitts-Nevis')
GO
insert into lookup_countries (country_desc) values ('Saint Lucia')
GO
insert into lookup_countries (country_desc) values ('Saint Pierre and Miquelon')
GO
insert into lookup_countries (country_desc) values ('Saint Vincent and the Grenadines')
GO
insert into lookup_countries (country_desc) values ('San Marino')
GO
insert into lookup_countries (country_desc) values ('Saudi Arabia')
GO
insert into lookup_countries (country_desc) values ('Senegal')
GO
insert into lookup_countries (country_desc) values ('Seychelles')
GO
insert into lookup_countries (country_desc) values ('Sierra Leone')
GO
insert into lookup_countries (country_desc) values ('Singapore')
GO
insert into lookup_countries (country_desc) values ('Slovakia')
GO
insert into lookup_countries (country_desc) values ('Slovenia')
GO
insert into lookup_countries (country_desc) values ('Solomon Islands')
GO
insert into lookup_countries (country_desc) values ('Somalia')
GO
insert into lookup_countries (country_desc) values ('South Africa')
GO
insert into lookup_countries (country_desc) values ('Spain')
GO
insert into lookup_countries (country_desc) values ('Sri Lanka')
GO
insert into lookup_countries (country_desc) values ('Sudan')
GO
insert into lookup_countries (country_desc) values ('Suriname')
GO
insert into lookup_countries (country_desc) values ('Svalbard')
GO
insert into lookup_countries (country_desc) values ('Swaziland')
GO
insert into lookup_countries (country_desc) values ('Sweden')
GO
insert into lookup_countries (country_desc) values ('Switzerland')
GO
insert into lookup_countries (country_desc) values ('Syria')
GO
insert into lookup_countries (country_desc) values ('Tahiti')
GO
insert into lookup_countries (country_desc) values ('Taiwan')
GO
insert into lookup_countries (country_desc) values ('Tajikistan')
GO
insert into lookup_countries (country_desc) values ('Tanzania')
GO
insert into lookup_countries (country_desc) values ('Thailand')
GO
insert into lookup_countries (country_desc) values ('Togo')
GO
insert into lookup_countries (country_desc) values ('Tonga')
GO
insert into lookup_countries (country_desc) values ('Trinidad and Tobago')
GO
insert into lookup_countries (country_desc) values ('Tunisia')
GO
insert into lookup_countries (country_desc) values ('Turkey')
GO
insert into lookup_countries (country_desc) values ('Turkmenistan')
GO
insert into lookup_countries (country_desc) values ('Turks and Caicos Islands')
GO
insert into lookup_countries (country_desc) values ('Tuvalu')
GO
insert into lookup_countries (country_desc) values ('Uganda')
GO
insert into lookup_countries (country_desc) values ('Ukraine')
GO
insert into lookup_countries (country_desc) values ('United Arab Emirates')
GO
insert into lookup_countries (country_desc) values ('Uruguay')
GO
insert into lookup_countries (country_desc) values ('Uzbekistan')
GO
insert into lookup_countries (country_desc) values ('Vanuatu')
GO
insert into lookup_countries (country_desc) values ('Vatican City, State')
GO
insert into lookup_countries (country_desc) values ('Venezuela')
GO
insert into lookup_countries (country_desc) values ('Vietnam')
GO
insert into lookup_countries (country_desc) values ('Virgin Islands (U.S.)')
GO
insert into lookup_countries (country_desc) values ('Wallis and Futuna')
GO
insert into lookup_countries (country_desc) values ('Western Sahara')
GO
insert into lookup_countries (country_desc) values ('Western Samoa')
GO
insert into lookup_countries (country_desc) values ('Yemen')
GO
insert into lookup_countries (country_desc) values ('Yugoslavia')
GO
insert into lookup_countries (country_desc) values ('Zambia')
GO
insert into lookup_countries (country_desc) values ('Zimbabwe')
GO

CREATE TABLE lookup_states (
       state_id             varchar(2) primary key,
       state_desc           varchar(25) NULL
)
GO

insert into lookup_states (state_id, state_desc) values ('AK', 'Alaska')
GO
insert into lookup_states (state_id, state_desc) values ('AL', 'Alabama')
GO
insert into lookup_states (state_id, state_desc) values ('AR', 'Arkansas')
GO
insert into lookup_states (state_id, state_desc) values ('AS', 'American')
GO
insert into lookup_states (state_id, state_desc) values ('AZ', 'Arizona')
GO
insert into lookup_states (state_id, state_desc) values ('CA', 'California')
GO
insert into lookup_states (state_id, state_desc) values ('CO', 'Colorado')
GO
insert into lookup_states (state_id, state_desc) values ('CT', 'Connecticut')
GO
insert into lookup_states (state_id, state_desc) values ('DC', 'District of Columbia')
GO
insert into lookup_states (state_id, state_desc) values ('DE', 'Delaware')
GO
insert into lookup_states (state_id, state_desc) values ('FL', 'Florida')
GO
insert into lookup_states (state_id, state_desc) values ('GA', 'Georgia')
GO
insert into lookup_states (state_id, state_desc) values ('GU', 'Guam')
GO
insert into lookup_states (state_id, state_desc) values ('HI', 'Hawaii')
GO
insert into lookup_states (state_id, state_desc) values ('IA', 'Iowa')
GO
insert into lookup_states (state_id, state_desc) values ('ID', 'Idaho')
GO
insert into lookup_states (state_id, state_desc) values ('IL', 'Illinois')
GO
insert into lookup_states (state_id, state_desc) values ('IN', 'Indiana')
GO
insert into lookup_states (state_id, state_desc) values ('KS', 'Kansas')
GO
insert into lookup_states (state_id, state_desc) values ('KY', 'Kentucky')
GO
insert into lookup_states (state_id, state_desc) values ('LA', 'Louisiana')
GO
insert into lookup_states (state_id, state_desc) values ('MA', 'Massachusetts')
GO
insert into lookup_states (state_id, state_desc) values ('MD', 'Maryland')
GO
insert into lookup_states (state_id, state_desc) values ('ME', 'Maine')
GO
insert into lookup_states (state_id, state_desc) values ('MI', 'Michigan')
GO
insert into lookup_states (state_id, state_desc) values ('MN', 'Minnesota')
GO
insert into lookup_states (state_id, state_desc) values ('MO', 'Missouri')
GO
insert into lookup_states (state_id, state_desc) values ('MP', 'Northern')
GO
insert into lookup_states (state_id, state_desc) values ('MS', 'Missippi')
GO
insert into lookup_states (state_id, state_desc) values ('MT', 'Montana')
GO
insert into lookup_states (state_id, state_desc) values ('NC', 'North Carolina')
GO
insert into lookup_states (state_id, state_desc) values ('ND', 'North Dakota')
GO
insert into lookup_states (state_id, state_desc) values ('NE', 'Nebraska')
GO
insert into lookup_states (state_id, state_desc) values ('NH', 'New Hampshire')
GO
insert into lookup_states (state_id, state_desc) values ('NJ', 'New Jersey')
GO
insert into lookup_states (state_id, state_desc) values ('NM', 'New Mexico')
GO
insert into lookup_states (state_id, state_desc) values ('NV', 'Nevada')
GO
insert into lookup_states (state_id, state_desc) values ('NY', 'New York')
GO
insert into lookup_states (state_id, state_desc) values ('OH', 'Ohio')
GO
insert into lookup_states (state_id, state_desc) values ('OK', 'Oklahoma')
GO
insert into lookup_states (state_id, state_desc) values ('OR', 'Oren')
GO
insert into lookup_states (state_id, state_desc) values ('PA', 'Pennsylvania')
GO
insert into lookup_states (state_id, state_desc) values ('PR', 'Puerto Rico')
GO
insert into lookup_states (state_id, state_desc) values ('PW', 'Palau')
GO
insert into lookup_states (state_id, state_desc) values ('RI', 'Rhode Island')
GO
insert into lookup_states (state_id, state_desc) values ('SC', 'South Carolina')
GO
insert into lookup_states (state_id, state_desc) values ('SD', 'South Dakota')
GO
insert into lookup_states (state_id, state_desc) values ('TN', 'Tennessee')
GO
insert into lookup_states (state_id, state_desc) values ('TX', 'Texas')
GO
insert into lookup_states (state_id, state_desc) values ('UT', 'Utah')
GO
insert into lookup_states (state_id, state_desc) values ('VA', 'Virginia')
GO
insert into lookup_states (state_id, state_desc) values ('VI', 'Virgin Island')
GO
insert into lookup_states (state_id, state_desc) values ('VT', 'Vermont')
GO
insert into lookup_states (state_id, state_desc) values ('WA', 'Washington')
GO
insert into lookup_states (state_id, state_desc) values ('WI', 'Wisconsin')
GO
insert into lookup_states (state_id, state_desc) values ('WV', 'West Virginia')
GO
insert into lookup_states (state_id, state_desc) values ('WY', 'Wyoming')
GO

CREATE TABLE members (
       member_id            int identity  primary key,
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
)
GO

insert into members (first_name, last_name, member_login	, member_password, email, country_id, state_id, city, zip, security_level_id)
	values('Guest', 'Member', 'guest', 'guest', 'guest@nowhere.com', 1, 'CA', 'South San Francisco', '94080', 1)
GO
insert into members (first_name, last_name, member_login	, member_password, email, country_id, state_id, city, zip, security_level_id)
	values('Admin', 'User', 'admin', 'admin', 'admin@nowhere.com', 1, 'CA', 'South San Francisco', '94080', 3)
GO

CREATE TABLE officers (
       officer_id           int identity  primary key,
       officer_name         varchar(50) NULL,
       officer_position     varchar(50) NULL,
       officer_email        varchar(30) NULL
)
GO

insert into officers (officer_name, officer_position, officer_email)
	values('James Morton', 'President	', 'jmorton@nowhere.com')
GO
insert into officers (officer_name, officer_position, officer_email)
	values('Adam Bryzek', 'Vice President', 'adam@nowhere.com')
GO
insert into officers (officer_name, officer_position, officer_email)
	values('Matt Vaughn', 'Vice President', 'mvaughn@nowhere.com')
GO
insert into officers (officer_name, officer_position, officer_email)
	values('Bob Newell', 'Secretary', 'bnewell@nowhere.com')
GO
insert into officers (officer_name, officer_position, officer_email)
	values('Sabrina Rek', 'Treasurer', 'srek@nowhere.com')
GO
insert into officers (officer_name, officer_position, officer_email)
	values('Jamie McCants', 'Webmaster', 'jmccants@nowhere.com')
GO

CREATE TABLE news (
       news_id int identity  primary key,
       news_html text
)
GO

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
')
GO