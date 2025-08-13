/*
 * ebay_user_code.sql
 */

/*
 Name                            Null?    Type
 ------------------------------- -------- ----
 QUESTION_ID                     NOT NULL NUMBER(3)
 QUESTION_CODE                   NOT NULL NUMBER(3)
 ORDER_NO                                 NUMBER(6)
 TYPE_CODE                       NOT NULL NUMBER(3)
 QUESTION                        NOT NULL VARCHAR2(255)
*/


// UK inserts:

// education: id = 4, type = 3

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (4, 6, 460, 3, 'Secondary School');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (4, 7, 470, 3, 'Vocation/Technical School');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (4, 8, 480, 3, 'Other College');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (4, 9, 490, 3, 'University Graduate');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (4, 10, 4100, 3, 'Post Graduate');


// income: id = 5, type = 3

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (5, 7, 570, 3, 'less than 15,000 pounds');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (5, 8, 580, 3, '15,000 - 25,000 pounds');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (5, 9, 590, 3, '26,000 - 35,000 pounds');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (5, 10, 5100, 3, '36,000 - 50,000 pounds');

insert into ebay_user_code 
(question_id, question_code, order_no, type_code, question)
values (5, 11, 5110, 3, 'more than 50,000 pounds');
