/*
 * ebay_countries.sql
 *
 * This table contains our knowledge about different
 * countries and how they relate to the product. 
 */

 
drop table ebay_countries;

commit;

create table ebay_countries
(
id				number(10)
	constraint	countries_id_pk primary key,
code			varchar2(2)
	constraint	countries_code_nn
		not null,
american_name	varchar2(63)
	constraint	countries_american_name_nn
		not null,
dir_name        varchar2(31)
    constraint  countries_dir_name_nn
	    not null,
slander_strict	char(1)
	constraint	slander_strict_nn
		not null,
name_res_id		number(5)
	constraint	name_res_id_nn
		not null,
observes_summertime char(1)
	constraint	observes_summertime_nn
		not null,
summertime_begins_first char(1)
summertime_begins_month number(2)
summertime_ends_first char(1)
summertime_ends_month number(2)
)
tablespace tmiscd01
storage (initial 100K next 100K pctincrease 0);


// qcountriesd01

/* Space calculation: */
/* key:   8 bytes (at most) x 200 countries =  1.6K */
/* table: 200 bytes x 200 countries         = 40K   */

commit;

/*
create index ebay_countries_id_index
   on ebay_countries(id)
   tablespace tmiscd01
   storage (initial 20K next 20K pctincrease 0);

commit;
*/

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (77, 'af', 'Afghanistan', 'Afghanistan', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (5, 'al', 'Albania', 'Albania', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (6, 'dz', 'Algeria', 'Algeria', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (7, 'as', 'American Samoa', 'Samoa', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (8, 'ad', 'Andorra', 'Andorra', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (9, 'ao', 'Angola', 'Angola', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (10, 'ai', 'Anguilla', 'Anguilla', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (11, 'ag', 'Antigua and Barbuda', 'Antigua', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (12, 'ar', 'Argentina', 'Argentina', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (13, 'am', 'Armenia', 'Armenia', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (14, 'aw', 'Aruba', 'Aruba', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (15, 'au', 'Australia', 'Australia', 'N', 0, 'Y', L, 3, L, 10);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (16, 'at', 'Austria', 'Austria', 'N', 0, 'Y', 'L', 3, 'L', 10);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (17, 'az', 'Azerbaijan Republic', 'Azerbaijan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (18, 'bs', 'Bahamas', 'Bahamas', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (19, 'bh', 'Bahrain', 'Bahrain', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (20, 'bd', 'Bangladesh', 'Bangladesh', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (21, 'bb', 'Barbados', 'Barbados', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (22, 'by', 'Belarus', 'Belarus', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (23, 'be', 'Belgium', 'Belgium', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (24, 'bz', 'Belize', 'Belize', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (25, 'bj', 'Benin', 'Benin', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (26, 'bm', 'Bermuda', 'Bermuda', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (27, 'bt', 'Bhutan', 'Bhutan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (28, 'bo', 'Bolivia', 'Bolivia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (29, 'ba', 'Bosnia and Herzegovina', 'Bosnia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (30, 'bw', 'Botswana', 'Botswana', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (31, 'br', 'Brazil', 'Brazil', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (32, 'vg', 'British Virgin Islands', 'Br-Virgin-Islands', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (33, 'bn', 'Brunei Darussalam', 'Brunei', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (34, 'bg', 'Bulgaria', 'Bulgaria', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (35, 'bf', 'Burkina Faso', 'Burkina', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (36, 'af', 'Burma', 'Burma', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (37, 'bi', 'Burundi', 'Burundi', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (38, 'kh', 'Cambodia', 'Cambodia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (39, 'cm', 'Cameroon', 'Cameroon', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (2, 'ca', 'Canada', 'Canada', 'N', 0, 'Y', 'F', 4, 'L', 10);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (40, 'cv', 'Cape Verde Islands', 'Cape-Verde', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (41, 'ky', 'Cayman Islands', 'Cayman', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (42, 'cf', 'Central African Republic', 'Central-African-Republic', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (43, 'td', 'Chad', 'Chad', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (44, 'cl', 'Chile', 'Chile', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (45, 'cn', 'China', 'China', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (46, 'co', 'Colombia', 'Colombia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (47, 'km', 'Comoros', 'Comoros', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (48, 'zr', 'Congo, Democratic Republic of the', 'Dem-Rep-Congo', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (49, 'cg', 'Congo, Republic of the', 'Rep-Congo', 'N', 0, 'N', null, null, null, null);

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (50, 'ck', 'Cook Islands', 'Cook', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (51, 'cr', 'Costa Rica', 'Costa-Rica', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (52, 'ci', 'Cote d Ivoire (Ivory Coast)', 'Ivory-Coast', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (53, 'hr', 'Croatia, Democratic Republic of the', 'Croatia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (54, 'cu', 'Cuba', 'Cuba', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (55, 'cy', 'Cyprus', 'Cyprus', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (56, 'cz', 'Czech Republic', 'Czech', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (57, 'dk', 'Denmark', 'Denmark', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (58, 'dj', 'Djibouti', 'Djibouti', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (59, 'dm', 'Dominica', 'Dominica', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (60, 'do', 'Dominican Republic', 'Dominican-Republic', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (61, 'ec', 'Ecuador', 'Ecuador', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (62, 'eg', 'Egypt', 'Egypt', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (63, 'sv', 'El Salvador', 'El-Salvador', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (64, 'gq', 'Equatorial Guinea', 'Guinea', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (65, 'qq', 'Eritrea', 'Eritrea', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (66, 'ee', 'Estonia', 'Estonia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (67, 'et', 'Ethiopia', 'Ethiopia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (68, 'fk', 'Falkland Islands (Islas Makvinas)', 'Falkland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (69, 'fj', 'Fiji', 'Fiji', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (70, 'fi', 'Finland', 'Finland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (71, 'fr', 'France', 'France', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (72, 'gf', 'French Guiana', 'Guiana', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (73, 'pf', 'French Polynesia', 'Polynesia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (74, 'ga', 'Gabon Republic', 'Gabon', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (75, 'gm', 'Gambia', 'Gambia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (76, 'ge', 'Georgia',  'Georgia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (4, 'de', 'Germany', 'DE', 'N', 0, 'Y', 'L', 3, 'L', 10);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (78, 'gh', 'Ghana', 'Ghana', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (79, 'gi', 'Gibraltar', 'Gibraltar', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (80, 'gr', 'Greece', 'Greece', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (81, 'gl', 'Greenland', 'Greenland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (82, 'gd', 'Grenada', 'Grenada', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (83, 'gp', 'Guadeloupe', 'Guadeloupe', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (84, 'gu', 'Guam', 'Guam', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (85, 'gt', 'Guatemala', 'Guatemala', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (86, 'qq', 'Guernsey', 'Guernsey', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (87, 'gn', 'Guinea', 'Guinea', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (88, 'gw', 'Guinea-Bissau', 'Guinea-Bissau', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (89, 'gy', 'Guyana', 'Guyana', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (90, 'ht', 'Haiti', 'Haiti', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (91, 'hn', 'Honduras', 'Honduras', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (92, 'hk', 'Hong Kong', 'Hong-Kong', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (93, 'hu', 'Hungary', 'Hungary', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (94, 'is', 'Iceland', 'Iceland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (95, 'in', 'India', 'India', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (96, 'id', 'Indonesia', 'Indonesia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (97, 'ir', 'Iran', 'Iran', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (98, 'iq', 'Iraq', 'Iraq', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (99, 'ie', 'Ireland', 'Ireland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (100, 'il', 'Israel', 'Israel', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (101, 'it', 'Italy', 'Italy', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (102, 'jm', 'Jamaica', 'Jamaica', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (103, 'sj', 'Jan Mayen', 'Jan-Mayen', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (104, 'jp', 'Japan', 'Japan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (105, 'uk', 'Jersey', 'Jersey', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (106, 'jo', 'Jordan', 'Jordan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (107, 'kz', 'Kazakhstan', 'Kazakhstan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (108, 'ke', 'Kenya Coast Republic', 'Kenya', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (109, 'ki', 'Kiribati', 'Kiribati', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (110, 'kp', 'Korea, North', 'North-Korea', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (111, 'kr', 'Korea, South', 'South-Korea', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (112, 'kw', 'Kuwait', 'Kuwait', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (113, 'kg', 'Kyrgyzstan', 'Kyrgyzstan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (114, 'la', 'Laos', 'Laos', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (115, 'lv', 'Latvia', 'Latvia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (116, 'lb', 'Lebanon, South', 'Lebanon', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (117, 'ls', 'Lesotho', 'Lesotho', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (118, 'lr', 'Liberia', 'Liberia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (119, 'ly', 'Libya', 'Libya', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (120, 'li', 'Liechtenstein', 'Liechtenstein', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (121, 'lt', 'Lithuania', 'Lithuania', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (122, 'lu', 'Luxembourg', 'Luxembourg', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (123, 'mo', 'Macau', 'Macau', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (124, 'mk', 'Macedonia', 'Macedonia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (125, 'mg', 'Madagascar', 'Madagascar', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (126, 'mw', 'Malawi', 'Malawi', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (127, 'my', 'Malaysia', 'Malaysia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (128, 'mv', 'Maldives', 'Maldives', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (129, 'ml', 'Mali', 'Mali', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (130, 'mt', 'Malta', 'Malta', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (131, 'mh', 'Marshall Islands', 'Marshall', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (132, 'mq', 'Martinique', 'Martinique', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (133, 'mr', 'Mauritania', 'Mauritania', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (134, 'mu', 'Mauritius', 'Mauritius', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (135, 'yt', 'Mayotte', 'Mayotte', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (136, 'mx', 'Mexico', 'Mexico', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (137, 'qq', 'Moldova', 'Moldova', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (138, 'mc', 'Monaco', 'Monaco', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (139, 'mn', 'Mongolia', 'Mongolia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (140, 'ms', 'Montserrat', 'Montserrat', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (141, 'ma', 'Morocco', 'Morocco', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (142, 'mz', 'Mozambique', 'Mozambique', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (143, 'na', 'Namibia', 'Namibia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (144, 'nr', 'Nauru', 'Nauru', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (145, 'np', 'Nepal', 'Nepal', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (146, 'nl', 'Netherlands', 'Netherlands', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (147, 'an', 'Netherlands Antilles', 'Antilles', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (148, 'nc', 'New Caledonia', 'New-Caledonia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (149, 'nz', 'New Zealand', 'New-Zealand', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (150, 'ni', 'Nicaragua', 'Nicaragua', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (151, 'ne', 'Niger', 'Niger', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (152, 'ng', 'Nigeria', 'Nigeria', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (153, 'nu', 'Niue', 'Niue', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (154, 'no', 'Norway', 'Norway', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (155, 'om', 'Oman', 'Oman', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (156, 'pk', 'Pakistan', 'Pakistan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (157, 'pw', 'Palau', 'Palau', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (158, 'pa', 'Panama', 'Panama', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (159, 'pg', 'Papua New Guinea', 'Papua-New-Guinea', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (160, 'py', 'Paraguay', 'Paraguay', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (161, 'pe', 'Peru', 'Peru', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (162, 'ph', 'Philippines', 'Philippines', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (163, 'pl', 'Poland', 'Poland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (164, 'pt', 'Portugal', 'Portugal', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (165, 'pr', 'Puerto Rico', 'Puerto-Rico', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (166, 'qa', 'Qatar', 'Qatar', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (167, 'ro', 'Romania', 'Romania', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (168, 'ru', 'Russian Federation', 'Russia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (169, 'rw', 'Rwanda', 'Rwanda', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (170, 'sh', 'Saint Helena', 'Helena', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (171, 'qq', 'Saint Kitts-Nevis', 'Kitts-Nevis', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (172, 'lc', 'Saint Lucia', 'Lucia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (173, 'pm', 'Saint Pierre and Miquelon', 'Pierre-and-Miquelon', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (174, 'vc', 'Saint Vincent and the Grenadines', 'Vincent', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (175, 'sm', 'San Marino',  'Marino', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (176, 'sa', 'Saudi Arabia', 'Saudi-Arabia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (177, 'sn', 'Senegal', 'Senegal', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (178, 'sc', 'Seychelles', 'Seychelles', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (179, 'sl', 'Sierra Leone', 'Sierra-Leone', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (180, 'sg', 'Singapore', 'Singapore', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (181, 'sk', 'Slovakia', 'Slovakia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (182, 'si', 'Slovenia', 'Slovenia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (183, 'sb', 'Solomon Islands', 'Solomon', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (184, 'so', 'Somalia', 'Somalia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (185, 'za', 'South Africa', 'South-Africa', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (186, 'es', 'Spain', 'Spain', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (187, 'lk', 'Sri Lanka', 'Sri-Lanka', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (188, 'sd', 'Sudan', 'Sudan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (189, 'sr', 'Suriname', 'Suriname', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (190, 'sj', 'Svalbard', 'Svalbard', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (191, 'sz', 'Swaziland', 'Swaziland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (192, 'se', 'Sweden', 'Sweden', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (193, 'ch', 'Switzerland', 'Switzerland', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (194, 'sy', 'Syria', 'Syria', 'N', 0, 'N', null, null, null, null);
// I *think* this is the same as French Polynesia
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (195, 'pf', 'Tahiti', 'Tahiti', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (196, 'tw', 'Taiwan', 'Taiwan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (197, 'tj', 'Tajikistan', 'Tajikistan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (198, 'tz', 'Tanzania', 'Tanzania', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (199, 'th', 'Thailand', 'Thailand', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (200, 'tg', 'Togo', 'Togo', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (201, 'to', 'Tonga', 'Tonga', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (202, 'tt', 'Trinidad and Tobago', 'Trinidad', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (203, 'tn', 'Tunisia', 'Tunisia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (204, 'tr', 'Turkey', 'Turkey', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (205, 'tm', 'Turkmenistan', 'Turkmenistan', 'N', 0, 'N', null, null, null, null);
// Same as US?
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (206, 'us', 'Turks and Caicos Islands', 'Turks', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (207, 'tv', 'Tuvalu', 'Tuvalu', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (208, 'ug', 'Uganda', 'Uganda', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (209, 'ua', 'Ukraine', 'Ukraine', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (210, 'af', 'United Arab Emirates', 'Emirates', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (3, 'uk', 'United Kingdom', 'UK', 'Y', 0, 'Y', 'L', 3, 'L', 10);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (1, 'us', 'United States', 'US', 'N', 0, 'Y', 'F',4, 'L', 10);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (211, 'uy', 'Uruguay', 'Uruguay', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (212, 'uz', 'Uzbekistan', 'Uzbekistan', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (213, 'vu', 'Vanuatu', 'Vanuatu', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (214, 'va', 'Vatican City State', 'Vatican', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (215, 've', 'Venezuela', 'Venezuela', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (216, 'vn', 'Vietnam', 'Vietnam', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (217, 'vi', 'Virgin Islands (U.S.)', 'US-Virgin-Islands', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (218, 'wf', 'Wallis and Futuna', 'Wallis', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (219, 'eh', 'Western Sahara', 'Western-Sahara', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (220, 'ws', 'Western Samoa', 'Western-Samoa', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (221, 'ye', 'Yemen', 'Yemen', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (222, 'yu', 'Yugoslavia', 'Yugoslavia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (223, 'zm', 'Zambia', 'Zambia', 'N', 0, 'N', null, null, null, null);
insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (224, 'zw', 'Zimbabwe', 'Zimbabwe', 'N', 0, 'N', null, null, null, null);

commit;

insert into ebay_countries (id, code, american_name, dir_name, slander_strict, name_res_id, observes_summertime, summertime_begins_first, summertime_begins_month, summertime_ends_first, summertime_ends_month)
 values (225, 'us', 'APO/FPO', 'APO', 'N', 0, 'N', null, null, null, null);
commit;