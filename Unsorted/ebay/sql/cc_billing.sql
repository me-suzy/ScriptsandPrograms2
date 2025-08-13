/*	$Id: cc_billing.sql,v 1.3 1999/02/21 02:52:42 josh Exp $	*/
/*
 * cc_billing.sql
 *
 * ** NOTE **
 * The table is for keeping the credit card information
 * on the secure server 
 * ** NOTE **
 */

/* added last modified field; do we really need it? */

drop table cc_billing;

create table cc_billing
(
	id					int
		constraint	rcc_billing_id_nn	not null,
	cc					varchar(32)	
		constraint	rcc_billing_cc_nn	not null,
	cc_expiry_date		date
		constraint	rcc_billing_expiry_nn	not null,
	date_authorized		date
		constraint	rcc_billing_authorize_nn	not null,
	amount				number(10,2),
	accholder_name		varchar(64)
		constraint	rcc_billing_name_nn	not null,
	st_bill_addr		varchar(64),
	city_bill_addr		varchar(32),
	state_bill_addr		varchar(32),
	country_bill_addr	varchar(32),
	zip_bill_addr		varchar(16),
	account_type		char(1),
	auth_attempt_count	int default 0,

	constraint			id_pk
		primary key		(id)
		using index tablespace	billingi01
		storage (initial 500K next 500K),
	constraint	rcc_billing_id_unq
		unique (id)
)
tablespace billingd01
storage (initial 10M next 10M);

	
commit;
