#**
#* populate_bibliography_values.sql
#*
#* Population tables with initial values for Back-End Bibliographies
#* 
#* @package   Back-End on phpSlash
#* @author    Peter Bojanic
#* @copyright Copyright (C) 2003 OpenConcept Consulting
#* @version   $Id: populate_bibliography_values.sql,v 1.1.1.1 2003/11/06 02:21:37 mgifford Exp $ 
#*
#
# This file is part of Back-End.
#
# Back-End is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Back-End is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Back-End; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


#**
#* Table descriptions
#* 
# see the script add_bibliography_tables.sql for details on these tables


# 
# Populate values for tables `be_bib_country`
# 
INSERT INTO be_bib_country
  (countryID, languageID, name)
  VALUES
  ('AFG', 'en', 'Afghanistan'),
  ('ALB', 'en', 'Albania'),
  ('DZA', 'en', 'Algeria'),
  ('ASM', 'en', 'American Samoa'),
  ('AND', 'en', 'Andorra'),
  ('AGO', 'en', 'Angola'),
  ('AIA', 'en', 'Anguilla'),
  ('ATG', 'en', 'Antigua and Barbuda'),
  ('ARG', 'en', 'Argentina'),
  ('ARM', 'en', 'Armenia'),
  ('ABW', 'en', 'Aruba'),
  ('AUS', 'en', 'Australia'),
  ('AUT', 'en', 'Austria'),
  ('AZE', 'en', 'Azerbaijan'),
  ('BHS', 'en', 'Bahamas'),
  ('BHR', 'en', 'Bahrain'),
  ('BGD', 'en', 'Bangladesh'),
  ('BRB', 'en', 'Barbados'),
  ('BLR', 'en', 'Belarus'),
  ('BEL', 'en', 'Belgium'),
  ('BLZ', 'en', 'Belize'),
  ('BEN', 'en', 'Benin'),
  ('BMU', 'en', 'Bermuda'),
  ('BTN', 'en', 'Bhutan'),
  ('BOL', 'en', 'Bolivia'),
  ('BIH', 'en', 'Bosnia and Herzegovina'),
  ('BWA', 'en', 'Botswana'),
  ('BRA', 'en', 'Brazil'),
  ('VGB', 'en', 'British Virgin Islands'),
  ('BRN', 'en', 'Brunei Darussalam'),
  ('BGR', 'en', 'Bulgaria'),
  ('BFA', 'en', 'Burkina Faso'),
  ('BDI', 'en', 'Burundi'),
  ('KHM', 'en', 'Cambodia'),
  ('CMR', 'en', 'Cameroon'),
  ('CAN', 'en', 'Canada'),
  ('CPV', 'en', 'Cape Verde'),
  ('CYM', 'en', 'Cayman Islands'),
  ('CAF', 'en', 'Central African Republic'),
  ('TCD', 'en', 'Chad'),
  ('CHL', 'en', 'Chile'),
  ('CHN', 'en', 'China'),
  ('HKG', 'en', 'Hong Kong Special Administrative'),
  ('MAC', 'en', 'Macao Special Administrative Region of China'),
  ('COL', 'en', 'Colombia'),
  ('COM', 'en', 'Comoros'),
  ('COG', 'en', 'Congo'),
  ('COK', 'en', 'Cook Islands'),
  ('CRI', 'en', 'Costa Rica'),
  ('CIV', 'en', 'Cote d\'Ivoire'),
  ('HRV', 'en', 'Croatia'),
  ('CUB', 'en', 'Cuba'),
  ('CYP', 'en', 'Cyprus'),
  ('CZE', 'en', 'Czech Republic'),
  ('PRK', 'en', 'Democratic People\'s Republic of Korea'),
  ('COD', 'en', 'Democratic Republic of the Congo'),
  ('DNK', 'en', 'Denmark'),
  ('DJI', 'en', 'Djibouti'),
  ('DMA', 'en', 'Dominica'),
  ('DOM', 'en', 'Dominican Republic'),
  ('TMP', 'en', 'East Timor'),
  ('ECU', 'en', 'Ecuador'),
  ('EGY', 'en', 'Egypt'),
  ('SLV', 'en', 'El Salvador'),
  ('GNQ', 'en', 'Equatorial Guinea'),
  ('ERI', 'en', 'Eritrea'),
  ('EST', 'en', 'Estonia'),
  ('ETH', 'en', 'Ethiopia'),
  ('FRO', 'en', 'Faeroe Islands'),
  ('FLK', 'en', 'Falkland Islands (Malvinas)'),
  ('FJI', 'en', 'Fiji'),
  ('FIN', 'en', 'Finland'),
  ('FRA', 'en', 'France'),
  ('GUF', 'en', 'French Guiana'),
  ('PYF', 'en', 'French Polynesia'),
  ('GAB', 'en', 'Gabon'),
  ('GMB', 'en', 'Gambia'),
  ('GEO', 'en', 'Georgia'),
  ('DEU', 'en', 'Germany'),
  ('GHA', 'en', 'Ghana'),
  ('GIB', 'en', 'Gibraltar'),
  ('GRC', 'en', 'Greece'),
  ('GRL', 'en', 'Greenland'),
  ('GRD', 'en', 'Grenada'),
  ('GLP', 'en', 'Guadeloupe'),
  ('GUM', 'en', 'Guam'),
  ('GTM', 'en', 'Guatemala'),
  ('GIN', 'en', 'Guinea'),
  ('GNB', 'en', 'Guinea-Bissau'),
  ('GUY', 'en', 'Guyana'),
  ('HTI', 'en', 'Haiti'),
  ('VAT', 'en', 'Holy See'),
  ('HND', 'en', 'Honduras'),
  ('HUN', 'en', 'Hungary'),
  ('ISL', 'en', 'Iceland'),
  ('IND', 'en', 'India'),
  ('IDN', 'en', 'Indonesia'),
  ('IRN', 'en', 'Iran (Islamic Republic of)'),
  ('IRQ', 'en', 'Iraq'),
  ('IRL', 'en', 'Ireland'),
  ('ISR', 'en', 'Israel'),
  ('ITA', 'en', 'Italy'),
  ('JAM', 'en', 'Jamaica'),
  ('JPN', 'en', 'Japan'),
  ('JOR', 'en', 'Jordan'),
  ('KAZ', 'en', 'Kazakhstan'),
  ('KEN', 'en', 'Kenya'),
  ('KIR', 'en', 'Kiribati'),
  ('KWT', 'en', 'Kuwait'),
  ('KGZ', 'en', 'Kyrgyzstan'),
  ('LAO', 'en', 'Lao People\'s Democratic Republic'),
  ('LVA', 'en', 'Latvia'),
  ('LBN', 'en', 'Lebanon'),
  ('LSO', 'en', 'Lesotho'),
  ('LBR', 'en', 'Liberia'),
  ('LBY', 'en', 'Libyan Arab Jamahiriya'),
  ('LIE', 'en', 'Liechtenstein'),
  ('LTU', 'en', 'Lithuania'),
  ('LUX', 'en', 'Luxembourg'),
  ('MDG', 'en', 'Madagascar'),
  ('MWI', 'en', 'Malawi'),
  ('MYS', 'en', 'Malaysia'),
  ('MDV', 'en', 'Maldives'),
  ('MLI', 'en', 'Mali'),
  ('MLT', 'en', 'Malta'),
  ('MHL', 'en', 'Marshall Islands'),
  ('MTQ', 'en', 'Martinique'),
  ('MRT', 'en', 'Mauritania'),
  ('MUS', 'en', 'Mauritius'),
  ('MEX', 'en', 'Mexico'),
  ('FSM', 'en', 'Micronesia Federated States of,'),
  ('MCO', 'en', 'Monaco'),
  ('MNG', 'en', 'Mongolia'),
  ('MSR', 'en', 'Montserrat'),
  ('MAR', 'en', 'Morocco'),
  ('MOZ', 'en', 'Mozambique'),
  ('MMR', 'en', 'Myanmar'),
  ('NAM', 'en', 'Namibia'),
  ('NRU', 'en', 'Nauru'),
  ('NPL', 'en', 'Nepal'),
  ('NLD', 'en', 'Netherlands'),
  ('ANT', 'en', 'Netherlands Antilles'),
  ('NCL', 'en', 'New Caledonia'),
  ('NZL', 'en', 'New Zealand'),
  ('NIC', 'en', 'Nicaragua'),
  ('NER', 'en', 'Niger'),
  ('NGA', 'en', 'Nigeria'),
  ('NIU', 'en', 'Niue'),
  ('NFK', 'en', 'Norfolk Island'),
  ('MNP', 'en', 'Northern Mariana Islands'),
  ('NOR', 'en', 'Norway'),
  ('PSE', 'en', 'Occupied Palestinian Territory'),
  ('OMN', 'en', 'Oman'),
  ('PAK', 'en', 'Pakistan'),
  ('PLW', 'en', 'Palau'),
  ('PAN', 'en', 'Panama'),
  ('PNG', 'en', 'Papua New Guinea'),
  ('PRY', 'en', 'Paraguay'),
  ('PER', 'en', 'Peru'),
  ('PHL', 'en', 'Philippines'),
  ('PCN', 'en', 'Pitcairn'),
  ('POL', 'en', 'Poland'),
  ('PRT', 'en', 'Portugal'),
  ('PRI', 'en', 'Puerto Rico'),
  ('QAT', 'en', 'Qatar'),
  ('KOR', 'en', 'Republic of Korea'),
  ('MDA', 'en', 'Republic of Moldova'),
  ('REU', 'en', 'Réunion'),
  ('ROM', 'en', 'Romania'),
  ('RUS', 'en', 'Russian Federation'),
  ('RWA', 'en', 'Rwanda'),
  ('SHN', 'en', 'Saint Helena'),
  ('KNA', 'en', 'Saint Kitts and Nevis'),
  ('LCA', 'en', 'Saint Lucia'),
  ('SPM', 'en', 'Saint Pierre and Miquelon'),
  ('VCT', 'en', 'Saint Vincent and the Grenadines'),
  ('WSM', 'en', 'Samoa'),
  ('SMR', 'en', 'San Marino'),
  ('STP', 'en', 'Sao Tome and Principe'),
  ('SAU', 'en', 'Saudi Arabia'),
  ('SEN', 'en', 'Senegal'),
  ('SYC', 'en', 'Seychelles'),
  ('SLE', 'en', 'Sierra Leone'),
  ('SGP', 'en', 'Singapore'),
  ('SVK', 'en', 'Slovakia'),
  ('SVN', 'en', 'Slovenia'),
  ('SLB', 'en', 'Solomon Islands'),
  ('SOM', 'en', 'Somalia'),
  ('ZAF', 'en', 'South Africa'),
  ('ESP', 'en', 'Spain'),
  ('LKA', 'en', 'Sri Lanka'),
  ('SDN', 'en', 'Sudan'),
  ('SUR', 'en', 'Suriname'),
  ('SJM', 'en', 'Svalbard and Jan Mayen Islands'),
  ('SWZ', 'en', 'Swaziland'),
  ('SWE', 'en', 'Sweden'),
  ('CHE', 'en', 'Switzerland'),
  ('SYR', 'en', 'Syrian Arab Republic'),
  ('TWN', 'en', 'Taiwan Province of China'),
  ('TJK', 'en', 'Tajikistan'),
  ('THA', 'en', 'Thailand'),
  ('MKD', 'en', 'The former Yugoslav Republic of Macedonia'),
  ('TGO', 'en', 'Togo'),
  ('TKL', 'en', 'Tokelau'),
  ('TON', 'en', 'Tonga'),
  ('TTO', 'en', 'Trinidad and Tobago'),
  ('TUN', 'en', 'Tunisia'),
  ('TUR', 'en', 'Turkey'),
  ('TKM', 'en', 'Turkmenistan'),
  ('TCA', 'en', 'Turks and Caicos Islands'),
  ('TUV', 'en', 'Tuvalu'),
  ('UGA', 'en', 'Uganda'),
  ('UKR', 'en', 'Ukraine'),
  ('ARE', 'en', 'United Arab Emirates'),
  ('GBR', 'en', 'United Kingdom'),
  ('TZA', 'en', 'United Republic of Tanzania'),
  ('USA', 'en', 'United States'),
  ('VIR', 'en', 'United States Virgin Islands'),
  ('URY', 'en', 'Uruguay'),
  ('UZB', 'en', 'Uzbekistan'),
  ('VUT', 'en', 'Vanuatu'),
  ('VEN', 'en', 'Venezuela'),
  ('VNM', 'en', 'Viet Nam'),
  ('WLF', 'en', 'Wallis and Futuna Islands'),
  ('ESH', 'en', 'Western Sahara'),
  ('YEM', 'en', 'Yemen'),
  ('YUG', 'en', 'Yugoslavia'),
  ('ZMB', 'en', 'Zambia'),
  ('ZWE', 'en', 'Zimbabwe');


# 
# Populate values for tables `be_bib_language`
# 
INSERT INTO be_bib_language
  (languageID, name)
  VALUES
  ('eng', 'English'),
  ('fra', 'French'),
  ('ara', 'Arabic'),
  ('esl', 'Spanish'),
  ('sve', 'Swedish'),
  ('per', 'Persian'),
  ('pst', 'Pushto'),
  ('PRT', 'Portuguese'),
  ('AMH', 'Amharic'),
  ('mal', 'Malay'),
  ('may', 'Malay'),
  ('hau', 'Hausa'),
  ('urd', 'Urdu'),
  ('bng', 'Bengali'),
  ('Uzb', 'Uzbek'),
  ('Hin', 'Hindi');


# 
# Populate values for tables `be_profession`
# 
INSERT INTO be_profession
  (professionID, languageID, name)
  VALUES
  (1, 'eng', 'Lawyer'),
  (2, 'eng', 'Professor/Teacher'),
  (28, 'eng', 'Entrepreneur'),
  (5, 'eng', 'Activist'),
  (6, 'eng', 'Scholar/Writer'),
  (7, 'eng', 'Philanthropist'),
  (8, 'eng', 'Policy-maker'),
  (10, 'eng', 'Parliamentarian'),
  (11, 'eng', 'Journalist/Media'),
  (29, 'eng', 'Artist'),
  (22, 'eng', 'Consultant'),
  (24, 'eng', 'Social Scientist'),
  (25, 'eng', 'Development Economist'),
  (30, 'eng', 'Physician'),
  (31, 'eng', 'Judge'),
  (32, 'eng', 'Psychotherapist'),
  (33, 'eng', 'Administrator');

# Update the sequence entry
UPDATE db_sequence SET nextid=34 WHERE seq_name = 'professionID_seq';


# 
# Populate values for tables `be_publisher`
# 
INSERT INTO be_publisher
  (publisherID, languageID, name, alias4publisherID, countryID, email, URL, address1, address2, city, state, postalCode)
  VALUES
  (19, 'eng', 'Syracuse University Press', 0, 'USA', '', '', '', '', 'Syracuse', 'New York', ''),
  (3, 'eng', 'African Women\'s Media Center', 0, 'SEN', '', '', '', '', 'Dakar', '', ''),
  (4, 'eng', 'The Centre for Development and Population Activities', 0, 'USA', '', '', '', '', 'Washington', 'District of Columbia', ''),
  (5, 'eng', 'Commonwealth Secretariat', 0, 'GBR', '', '', '', '', 'London', '', ''),
  (6, 'eng', 'Constitutional Rights Foundation', 0, 'USA', '', '', '', '', 'Los Angeles', 'California', ''),
  (7, 'eng', 'Constitutional Rights Foundation, and Alexandria, Virginia: Close Up Foundation', 0, 'USA', '', '', '', '', 'Los Angeles', 'California', ''),
  (8, 'eng', 'Sage', 0, 'USA', '', '', '', '', 'Thousand Oaks', 'California', ''),
  (9, 'eng', 'Sisterhood Is Global Institute', 0, 'USA', '', '', '', '', 'Bethesda', 'Maryland', ''),
  (10, 'eng', 'Refugee Women in Development', 0, 'USA', '', '', '', '', 'Washington', 'District of Columbia', ''),
  (11, 'eng', 'Jossey-Bass', 0, 'USA', '', '', '', '', 'San Francisco', 'California', ''),
  (20, 'eng', 'Council of Europe and European Commission', 0, 'FRA', '', '', '', '', 'Strasbourg', '', ''),
  (13, 'eng', 'Center for Creative Leadership', 0, 'USA', '', '', '', '', 'Greensboro', 'North Carolina', ''),
  (14, 'eng', 'Winrock International', 0, 'USA', '', '', '', '', 'Arlington', 'Virginia', ''),
  (28, 'eng', 'Continuum', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (16, 'eng', 'The Asia Foundation', 0, 'USA', '', '', '', '', 'Washington', 'District of Columbia', ''),
  (17, 'eng', 'McGraw-Hill', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (18, 'eng', 'West NIS Women\'s Consortium', 0, 'UKR', '', '', '', '', 'Kyiv', '', ''),
  (21, 'eng', 'Irwin', 0, 'USA', '', '', '', '', 'Burr Ridge', 'Illinois', ''),
  (22, 'eng', 'Oxfam', 0, 'GBR', '', '', '', '', 'Dorset', '', ''),
  (23, 'eng', 'United Nations Development Fund for Women (UNIFEM)', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (24, 'eng', 'Indiana University Press', 0, 'USA', '', '', '', '', 'Bloomington', 'Indiana', ''),
  (25, 'eng', 'Catalyst', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (26, 'eng', 'CIVICUS', 0, 'USA', '', 'http://www.civicus.org', '', '', 'Washington', 'District of Columbia', ''),
  (27, 'eng', 'Simon & Schuster', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (29, 'eng', 'Basic Books', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (30, 'eng', 'Harvard University Press', 0, 'USA', '', '', '', '', 'Cambridge', 'Massachusetts', ''),
  (31, 'eng', 'Doubleday', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (32, 'eng', 'National Commission for Women', 0, 'IND', '', '', '', '', 'New Delhi', '', ''),
  (33, 'eng', 'Interaction', 0, 'USA', '', '', '', '', 'Edina', 'Minnesota', ''),
  (34, 'eng', 'University of Pennsylvania Press', 0, 'USA', '', '', '', '', 'Philadelphia', 'Pennsylvania', ''),
  (35, 'eng', 'Seven Stories Press', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (36, 'eng', 'Teachers College Press', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (37, 'eng', 'Duke University Press', 0, 'USA', '', '', '', '', 'Durham', 'North Carolina', ''),
  (38, 'eng', 'Simorgh, Women\'s Resource and Publications Centre', 0, 'USA', '', '', '', '', 'Lahore', '', ''),
  (39, 'eng', 'Claus M. Halle Institute for Global Learning, Emory', 0, 'USA', '', '', '', '', 'Atlanta', 'Georgia', ''),
  (40, 'eng', 'Prentice Hall', 0, 'USA', '', '', '', '', 'Upper Saddle River', 'New Jersey', ''),
  (41, 'eng', 'Kumarian Press', 0, 'USA', '', '', '', '', 'Bloomfield', 'Connecticut', ''),
  (42, 'eng', 'Praeger', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (43, 'eng', 'Martinus Nijhoff', 0, 'USA', '', '', '', '', 'Boston', 'Massachusetts', ''),
  (100, 'eng', 'Musasa Project', NULL, 'ZWE', NULL, '', NULL, NULL, 'Harare', '', NULL),
  (45, 'eng', 'UNESCO', 0, 'FRA', '', '', '', '', 'Paris', '', ''),
  (46, 'eng', 'Mireva', 0, 'MLT', '', '', '', '', 'Msida', '', ''),
  (47, 'eng', 'Centre National de Documentation Pedagogique', 47, 'FRA', '', '', '', '', 'Paris', '', ''),
  (48, 'eng', 'Allen and Unwin in association with the Peace Research Centre', 0, 'AUS', '', '', '', '', 'Canberra', '', ''),
  (49, 'eng', 'Zed Books', 0, 'GBR', '', '', '', '', 'London', '', ''),
  (50, 'eng', 'University of Brunei Darussalam', 0, 'BRN', '', '', '', '', 'Bandar Seri Begawan', '', ''),
  (51, 'eng', 'The Fund for Peace', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (52, 'eng', 'UNHCR', NULL, 'CHE', NULL, '', NULL, NULL, 'Geneva', '', NULL),
  (53, 'eng', 'Center for Sustainable Human Rights Action', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (54, 'eng', 'Human Rights Resource Center', NULL, 'USA', NULL, '', NULL, NULL, 'Minneapolis', 'Minnesota', NULL),
  (55, 'eng', 'Nisaa Institute for Women\'s Development', NULL, 'ZAF', NULL, '', NULL, NULL, 'Lenasia', '', NULL),
  (56, 'eng', 'American Association for Advancement of Science', NULL, 'USA', NULL, '', NULL, NULL, 'Washington', 'District of Columbia', NULL),
  (57, 'eng', 'UNIFEM and The Center for Women\'s Global Leadership', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (58, 'eng', 'United Nations', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (59, 'eng', 'United Nations High Commissioner for Refugees', NULL, 'CHE', NULL, '', NULL, NULL, 'Geneva', '', NULL),
  (60, 'eng', 'Human Right Internet and International Center for Humanitarian Reporting', NULL, 'CAN', NULL, '', NULL, NULL, 'Ottawa', '', NULL),
  (61, 'eng', 'NIS Women\'s Consortium and Women\'s Information Consultative Center', NULL, 'UKR', NULL, '', NULL, NULL, 'Kyiv', '', NULL),
  (62, 'eng', 'Women, Law & Development International', NULL, 'USA', NULL, '', NULL, NULL, 'Washington', 'District of Columbia', NULL),
  (63, 'eng', 'Rutgers University Press', NULL, 'USA', NULL, '', NULL, NULL, 'New Brunswick', 'New Jersey', NULL),
  (64, 'eng', 'Cairo Institute for Human Rights', NULL, 'EGY', NULL, '', NULL, NULL, 'Cairo', '', NULL),
  (65, 'eng', 'Transnational', NULL, 'USA', NULL, '', NULL, NULL, 'Ardsley', 'New York', NULL),
  (66, 'eng', 'The People\'s Movement for Human Rights Education (PDHRE)', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (67, 'eng', 'Cornell University Press', NULL, 'USA', NULL, '', NULL, NULL, 'Ithica', 'New York', NULL),
  (68, 'eng', 'Human Rights Education Associates', NULL, 'USA', NULL, '', NULL, NULL, 'Cambridge', 'Massachusetts', NULL),
  (69, 'eng', 'Human Rights Educators\' Network of Amnesty International USA', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (70, 'eng', 'St. Martin\'s Press', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (71, 'eng', 'Lawyers Committee for Human Rights', NULL, 'USA', NULL, '', NULL, NULL, 'Washington', 'District of Columbia', NULL),
  (72, 'eng', 'Lutheran Office for Governmental Affairs', NULL, 'USA', NULL, '', NULL, NULL, 'Washington', 'District of Columbia', NULL),
  (73, 'eng', 'Westview Press', 0, 'USA', '', '', '', '', 'Boulder', 'Colorado and San Francisco, California', ''),
  (74, 'eng', 'Women Living Under Muslim Laws', NULL, 'FRA', NULL, '', NULL, NULL, 'Grabels', '', NULL),
  (82, 'eng', 'Oxford University Press', 0, 'GBR', '', '', '', '', 'Oxford', '', ''),
  (76, 'eng', 'Addison-Wesley', NULL, 'USA', NULL, '', NULL, NULL, 'Reading', 'Massachusetts', NULL),
  (77, 'eng', 'University Press', NULL, 'BGD', NULL, '', NULL, NULL, 'Dhaka', '', NULL),
  (78, 'eng', 'Anderson', NULL, 'USA', NULL, '', NULL, NULL, 'Cincinnati', 'Ohio', NULL),
  (79, 'eng', 'West', NULL, 'USA', NULL, '', NULL, NULL, 'Minneapolis/St. Paul', 'Minnesota', NULL),
  (80, 'eng', 'International Helsinki Federation for Human Rights', NULL, 'AUT', NULL, '', NULL, NULL, 'Vienna', '', NULL),
  (81, 'eng', 'Physicians for Human Rights', NULL, 'USA', NULL, '', NULL, NULL, 'Boston', 'Massachusetts', NULL),
  (83, 'eng', 'New York University Press', 0, 'USA', '', '', '', '', 'New York City', 'New York', ''),
  (84, 'eng', 'Harcourt Brace Jovanovich', NULL, 'USA', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (85, 'eng', 'Violence Against Women in Zimbabwe Research Project', NULL, 'ZWE', NULL, '', NULL, NULL, 'Harare', '', NULL),
  (86, 'eng', 'Asia Watch', NULL, '', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (87, 'eng', 'BAOBAB for Women\'s Human Rights', NULL, 'NGA', NULL, '', NULL, NULL, 'Lagos', '', NULL),
  (88, 'eng', 'The World Organizations Against Torture (OMCT)', NULL, 'CHE', NULL, '', NULL, NULL, 'Geneva', '', NULL),
  (89, 'eng', 'Attic Press', NULL, 'IRL', NULL, '', NULL, NULL, 'Dublin', '', NULL),
  (90, 'eng', 'Center for Women\'s Global Leadership', NULL, '', NULL, '', NULL, NULL, 'New Brunswick', 'New Jersey', NULL),
  (91, 'eng', 'Routledge', 0, '', '', '', '', '', 'New York City', 'New York', ''),
  (92, 'eng', 'Court of Women: The Permanent Arab Court to Resist Violence Against Women', NULL, 'LBN', NULL, '', NULL, NULL, 'Beirut', '', NULL),
  (93, 'eng', 'Court of Women: The Permanent Arab Court to Resist Violence Against Women', NULL, 'LBN', NULL, '', NULL, NULL, 'Beirut', '', NULL),
  (94, 'eng', 'El Taller', NULL, 'TUN', NULL, '', NULL, NULL, 'Tunis', '', NULL),
  (95, 'eng', 'Minority Rights Group', NULL, 'GBR', NULL, '', NULL, NULL, 'London', '', NULL),
  (96, 'eng', 'World Bank', NULL, '', NULL, '', NULL, NULL, 'Washington', 'District of Columbia', NULL),
  (97, 'eng', 'Human Rights Watch', NULL, '', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (98, 'eng', 'Women for Women\'s Human Rights - New Ways & Women Living Under Muslim Laws', NULL, 'TUR', NULL, '', NULL, NULL, 'Istanbul', '', NULL),
  (99, 'eng', 'The Israel Women\'s Network', NULL, 'ISR', NULL, '', NULL, NULL, 'Jerusalem', '', NULL),
  (101, 'eng', 'Kali for Women', NULL, 'IND', NULL, '', NULL, NULL, 'New Delhi', '', NULL),
  (102, 'eng', 'Asian Center for Women\'s Human Rights', NULL, 'PHL', NULL, '', NULL, NULL, 'Quezon City', '', NULL),
  (103, 'eng', 'First Run/Icarus Films', NULL, '', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (104, 'eng', 'Deep and Deep', NULL, 'IND', NULL, '', NULL, NULL, 'New Delhi', '', NULL),
  (105, 'eng', 'Sisters in Islam, United Selangor Press Sdn Bhd', 0, 'MYS', '', '', '', '', 'Kuala Lampur', '', ''),
  (106, 'eng', 'The Legal Research and Resource Center for Human Rights', NULL, 'EGY', NULL, '', NULL, NULL, 'Cairo', '', NULL),
  (107, 'eng', 'Rainbow', NULL, '', NULL, '', NULL, NULL, 'New York City', 'New York', NULL),
  (108, 'eng', 'UNICEF Innocenti Reseach Centre', NULL, 'ITA', NULL, '', NULL, NULL, 'Florence', '', NULL),
  (109, 'eng', 'World Health Organization', NULL, 'CHE', NULL, '', NULL, NULL, 'Geneva', '', NULL),
  (110, 'eng', 'Close Up Foundation', NULL, '', NULL, '', NULL, NULL, 'Alexandria', 'Virginia', NULL),
  (111, 'eng', 'NIS Women\'s Consortium', NULL, 'UKR', NULL, '', NULL, NULL, 'Kyiv', '', NULL),
  (112, 'eng', 'Leadership Library of America', NULL, '', NULL, '', NULL, NULL, 'West Orange', 'New Jersey', NULL),
  (113, 'eng', 'United Nations University Press', NULL, 'JPN', NULL, '', NULL, NULL, 'Tokyo', '', NULL),
  (114, 'eng', 'NGO/UNESCO Standing Committee', NULL, 'FRA', NULL, '', NULL, NULL, 'Paris', '', NULL),
  (115, 'eng', 'Centre UNESCO de Catalunya', NULL, 'ESP', NULL, '', NULL, NULL, 'Barcelona', '', NULL),
  (116, 'eng', 'UNESCO Principal Regional Office for Asia and the Pacific', NULL, 'THA', NULL, '', NULL, NULL, 'Bangkok', '', NULL),
  (117, 'eng', 'Editions Universitaires', NULL, 'CHE', NULL, '', NULL, NULL, 'Fribourg', '', NULL),
  (118, 'eng', 'BEL CORP for UNESCO and the Centre for Peace and Conflict Research in Copenhagen, Denmark', NULL, 'POL', NULL, '', NULL, NULL, 'Warsaw', '', NULL),
  (119, 'eng', '00test', NULL, 'CAN', NULL, 'http://openconcept.on.ca', NULL, NULL, 'ottawa', 'ontario', NULL),
  (120, 'eng', 'Center for African Studies, University of Illinois', NULL, 'USA', NULL, '', NULL, NULL, '', 'Illinois', NULL),
  (124, 'eng', 'UNRWA', NULL, 'JOR', NULL, '', NULL, NULL, 'Amman', '', NULL),
  (123, 'eng', 'Women\'s Learning Partnership for Rights, Development, and Peace (WLP)', NULL, 'USA', NULL, 'www.learningpartnership.org', NULL, NULL, 'Bethesda', 'MD', NULL),
  (125, 'eng', 'Center for Social and Legal Guidance', NULL, 'ISR', NULL, '', NULL, NULL, 'Jerusalem', '', NULL),
  (126, 'eng', 'CODESRIA', NULL, 'SEN', NULL, '', NULL, NULL, 'Dakar', '', NULL),
  (127, 'eng', 'IAR/ABU', NULL, 'NGA', NULL, '', NULL, NULL, 'Zaria', '', NULL),
  (128, 'eng', 'World Neighbors', NULL, 'USA', NULL, 'www.wn.org', NULL, NULL, 'Oklahoma City', 'Oklahoma', NULL),
  (129, 'eng', 'Independent Production Fund', 0, 'USA', '', 'www.theislamproject.org', '', '', 'New York City', 'New York', '');

# Update the sequence entry
UPDATE db_sequence SET nextid = 130 WHERE seq_name='publisherID_seq';


# 
# Populate values for tables `be_bib_region`
# 
INSERT INTO be_bib_region
  (regionID, languageID, name)
  VALUES
  (1, 'eng', 'North Africa'),
  (2, 'eng', 'Middle East'),
  (3, 'eng', 'South Asia'),
  (4, 'eng', 'East Asia & Pacific'),
  (5, 'eng', 'West Africa'),
  (6, 'eng', 'East & Southern Africa'),
  (7, 'eng', 'Latin America & Caribbean'),
  (8, 'eng', 'North America'),
  (9, 'eng', 'Central Asia'),
  (10, 'eng', 'Europe');

#Update the sequence entry
UPDATE db_sequence SET nextid=11 WHERE seq_name='regionID_seq';


# 
# Populate values for tables `be_profile_role`
# 
INSERT INTO be_profile_role
  (roleID, languageID, name, bibName)
  VALUES
  (1, 'eng', 'Editor', 'ed'),
  (2, 'eng', 'Translator', 'Translated by'),
  (0, 'eng', 'Author', ''),
  (3, 'eng', 'Compiler', 'comp');


# 
# Populate values for tables `be_bib_types`
# 
INSERT INTO be_bib_types
  (typeID, languageID, name)
  VALUES
  (0, 'eng', 'Book'),
  (1, 'eng', 'Journal'),
  (2, 'eng', 'Workshop Kit'),
  (3, 'eng', 'Multi-media'),
  (4, 'eng', 'Internet');
