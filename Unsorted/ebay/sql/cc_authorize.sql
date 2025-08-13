/*	$Id: cc_authorize.sql,v 1.3 1999/02/21 02:52:41 josh Exp $	*/
/*
 * cc_authorize.sql
 *
 * ** NOTE **
 * The table is for keeping the credit card information
 * on the secure server before verifying them 
 * ** NOTE **
 */

/* added last modified field; do we really need it? */

drop table cc_authorize;

create table cc_authorize
(
	REFID                         int			
		constraint	rcc_authorize_refid_nn	NOT NULL,
	ID                            int,
	ACCHOLDER_NAME                char(64),
	CC                            char(32)		
		constraint	rcc_authorize_cc_nn	NOT NULL,
	CC_EXPIRY_DATE                DATE			
		constraint	rcc_authorize_expiry_nn	NOT NULL,
	STATE                         int			
		constraint	rcc_authorize_state_nn	NOT NULL,
	PRIORITY                      int			
		constraint	rcc_authorize_priority_nn	NOT NULL,
	TIMESTAMP                     DATE			
		constraint	rcc_authorize_time_nn	NOT NULL,
	AMOUNT                        number(10,2)	
		constraint	rcc_authorize_amount_nn	NOT NULL,
	INV_BATCH_ID                  int,
	TRANS_ID                      char(15),
	TRANS_TIMESTAMP               DATE,
	VAL_CODE                      char(4),
	AUTHOR_CODE                   char(6),
	RESP_CODE                     char(2),
	AVS_RESP_CODE                 char(1),
	TRANS_TYPE                    int			
		constraint	rcc_authorize_trans_type_nn	NOT NULL,
	ST_BILL_ADDR                  char(64),
	CITY_BILL_ADDR                char(32),
	STATE_BILL_ADDR               char(32),
	COUNTRY_BILL_ADDR             char(32),
	ZIP_BILL_ADDR                 char(16),
	ACCOUNT_TYPE				  char(1)
)
tablespace billingd01
storage (initial 50k next 50k);

commit;

