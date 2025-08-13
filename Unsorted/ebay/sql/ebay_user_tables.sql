/*	$Id: ebay_user_tables.sql,v 1.9 1999/04/17 20:22:55 wwen Exp $	*/
/*
 * user answers are stored in ebay_user_survey instead of ebay_user_info.
 * ebay_user_code are questions and its corresponding answers 
 * of user demographic survey;
 * question id: id of the question, 
 * question_code: 0 for question, number for its values *                				
 * order_no: used to order the questions as well as answers
 * type_code: 
 *		   (when question_code =0, question) 
 *			0 = SELECT, 1 = INPUT, 2 = RADIOBUTTON 
 *			9 = top level category list;
 *		   (when question_code =1, answer) 
 *			3 = SELECTION 4= DEFAULT SELECTION					   
 *									 
 */
alter table ebay_user_info
add (partner_id number(3,0));
/*
create table ebay_user_code
(	question_id number(3,0)
		constraint	user_code_attr_nn
		not null,
	question_code number(3,0)
		constraint user_code_code_nn
		not null,
	order_no number(6,0)
		default 0,
	type_code number(3,0)
		not null,
	question varchar(255)
		constraint	contact_desc_nn
		not null,
	constraint		user_attr_code_pk
      	primary key(question_id, question_code)
      	using index storage(initial 100K next 100K)
        tablespace useri01)
tablespace userd01
storage(initial 100K next 100K);
*/

create table ebay_user_code
(	question_id number(3,0)
		constraint	user_code_attr_nn
		not null,
	question_code number(3,0)
		constraint user_code_code_nn
		not null,
	order_no number(6,0)
		default 0,
	type_code number(3,0)
		not null,
	question varchar(255)
		constraint	contact_desc_nn
		not null)
tablespace statmiscd
storage(initial 100K next 100K);

alter table ebay_user_code
	add constraint		user_attr_code_pk
      	primary key(question_id, question_code)
      	using index storage(initial 100K next 100K)
        tablespace statmisci  unrecoverable;
commit;

/* these are constants - never ever change */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,0, 100, 0,'How did you first hear about eBay?');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (2,0, 700, 0,'Preferred Activity?');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (14,0, 240, 9,'I am most interested in:');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (15,0, 760, 9,'I am also interested in:');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (3,0, 300, 0,'Age');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (4,0, 400, 0,'Education');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (5,0, 500, 0,'Annual Household Income');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (6,0, 600, 0,'Modem Speed');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (7,0, 200, 0,'Do you use eBay for individual purposes or for business purposes?');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (8,0, 800, 0,'Do you access eBay from home or at work?');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (9,0, 10000, 0,'Have you been abducted by aliens?');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (10,0, 10100, 0,'If so, were experiments performed on you?');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (11,0, 10200, 0,'Have you used any alien technology recently?');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (12,0, 10300, 1,'How many people do you think were on the Grassy Knoll?');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (13,0, 10400, 1,'When and where was your most recent Elvis sighting?');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (16,0, 580, 0,'Are you interested in participating in an eBay survey?');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (17,0, 10500, 0,'If you have a referral code, enter it');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,0, 10600, 0,'tradeshow middle code');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (19,0, 10700, 1,'tradeshow last code');

/* referred by a friend */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (20,0, 11000, 1,'If a friend referred you to eBay, please enter your friend's email address: ');

/* selection values */
/* Referral */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,1, 904, 3,'Netscape NetSearch Page');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,2, 908, 3,'Microsoft Start Page');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,3, 912, 3,'Excite');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,4, 916, 3,'Yahoo!');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,5, 920, 3,'Infoseek');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,6, 924, 3,'Lycos');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,7, 928, 3,'AOL');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,8, 932, 3,'WhoWhere?');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,9, 936, 3,'Four11');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,10, 940, 3,'InfoSpace');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,11, 944, 3,'Search.com');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,12, 948, 3,'Planet Direct');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,13, 952, 3,'DejaNews');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,14, 956, 3,'AngelFire');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,15, 960, 3,'USA Today');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,16, 964, 3,'Other Search engine');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,17, 168, 3,'Friend or Family Member');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,18, 172, 3,'Business associate');



 insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,19, 176, 3,'Internet search engine');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,20, 180, 3,'Radio, Television, Newspaper or Magazine Article');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,21, 184, 3,'Trade Show or Event');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,22, 188, 3,'Advertisement');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,23, 192, 3,'Other');

 /* added dealer */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,24, 974, 3,'Dealer');

 /* added more from steve westly */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,25, 957, 3,'Tripod');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,26, 998, 3,'GeoCities');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,27, 959, 3,'Netcom');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,28, 961, 3,'First Auction');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,29, 962, 3,'Z Auction');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,30, 963, 3,'NetNoir');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,31, 965, 3,'Sports Card Depot');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,32, 966, 3,'CNET');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,33, 967, 3,'WBS');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,34, 969, 3,'100Hot List');

 /* added Internet advertisement */
 insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,35, 173, 3,'Internet Advertisement');

/* added internet link */
 insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,36, 174, 3,'Ad in a collecting magazine');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,37, 180, 3,'Radio Ad');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,38, 182, 3,'TV Ad');
/*added for markting */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,39, 181, 3,'Sports Illustrated');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,40, 183, 3,'People magazine');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,41, 185, 3,'Parade magazine');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,42, 187, 3,'Newsweek');
/* added select here as default */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,43, 199, 4,'Select here');
/*talk show. Note: the default value still 43, do not change it*/
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (1,44, 188, 3,'Talk Show');

/* Preferred Activity */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (2,1, 710, 4,'Decline to state');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (2,2, 720, 3,'Buyer');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (2,3, 730, 3,'Seller');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (2,4, 740, 3,'Both');


/* age */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (3,1, 310, 4,'Select an age range');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (3,2, 320, 3,'18-24');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (3,3, 330, 3,'25-34');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (3,4, 340, 3,'35-50');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (3,5, 350, 3,'51-65');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (3,6, 360, 3,'over 65');

/* education level */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (4,1, 410, 4,'Select an education');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (4,2, 420, 3,'High School');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (4,3, 430, 3,'College');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (4,4, 440, 3,'Graduate School');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (4,5, 450, 3,'Other');

/* Annual Household Income */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (5,1, 520, 3,'Under $25,000');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (5,2, 530, 3,'$25,000-$35,000');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (5,3, 540, 3,'$36,000-$49,000');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (5,4, 550, 3,'$50,000-$75,000');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (5,5, 560, 3,'Over $75,000');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (5,6, 570, 4,'Select an income range');


/* connection speed */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (6,1, 620, 3,'14.4 kbps');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (6,2, 630, 3,'28.8 kbps');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (6,3, 640, 3,'33.6 - 56.6 kbps');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (6,4, 650, 3,'ISDN');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (6,5, 660, 3,'T1');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (6,6, 670, 4,'Do not know.');


/*Purpose*/
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (7,1, 205, 3,'Individual');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (7,2, 210, 3,'Business');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (7,3, 215, 3,'Both');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (7,4, 220, 4,'Select here');

/* from home or work */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (8,1, 810, 4,'Not Selected');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (8,2, 820, 3,'Home');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (8,3, 830, 3,'Office');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (8,4, 840, 3,'Both');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (8,5, 850, 3,'Decline to State');


/*Abducted by aliens */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (9,1, 10020, 3,'Yes');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (9,2, 10030, 3,'No');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (9,3, 10040, 4,'Decline to state');

/* Experiments performed */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (10,1, 10120, 3,'Yes');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (10,2, 10130, 3,'No');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (10,3, 10140, 4,'Decline to State');


/* Alien technology used */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (11,1, 10220, 3,'Yes');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (11,2, 10230, 3,'No');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (11,3, 10240, 4,'Decline to State');
 
/* survery */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (16,1, 582, 3,'Yes');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (16,2, 584, 3,'No');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (16,3, 586, 4,'Select here');
 
/* referral source code, first part */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (17,1, 10510, 3,'AD');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (17,2, 10520, 3,'AM');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (17,3, 10530, 3,'TR');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (17,4, 10540, 4,'Select here');

insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (17,5, 10550, 3,'EM');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (17,6, 10560, 3,'BD');


/* referral source code middle code */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,1, 10605, 4,'00');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,2, 10610, 3,'01');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,3, 10615, 3,'02');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,4, 10620, 3,'03');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,5, 10625, 3,'04');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,6, 10630, 3,'05');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,7, 10635, 3,'06');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,8, 10640, 3,'07');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,9, 10645, 3,'08');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,10, 10650, 3,'09');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,11, 10670, 3,'10');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,12, 10675, 3,'11');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,13, 10680, 3,'12');

/* eCamp code */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,14, 10681, 3,'41');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,15, 10682, 3,'71');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,16, 10683, 3,'51');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,17, 10684, 3,'82');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,18, 10685, 3,'74');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,19, 10686, 3,'81');
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,20, 10687, 3,'84');

/* Books&Magazine */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,21, 10688, 3,'12');
/* Music */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,22, 10689, 3,'13');
/* Consumer electronics */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,23, 10690, 3,'14');
/* Beanie babies */
insert into ebay_user_code (question_id, question_code, order_no, type_code, question) 
 values (18,24, 10691, 3,'15');


/* referral source code last part */














