/*	$Id: ebay_eom_account_balances.sql,v 1.3 1999/02/21 02:53:35 josh Exp $	*/
	create table ebay_eom_account_balances
	(
		id							number(38) 
			constraint		eom_account_balances_id_nn
			not null,
		last_modified			date 
			constraint		eom_account_balance_last_nn
			not null,
		balance					number(10,2),
		pastduebase				date,
		pastdue30days			number(10,2),
		pastdue60days			number(10,2),
		pastdue90days			number(10,2),
		pastdue120days			number(10,2),
		pastdueover120days	number(10,2)
	)
	storage (initial 30M next 30M)
		tablespace ACCOUNTD05;

create index ebay_eom_id_index
   on ebay_eom_account_balances(id)
   tablespace accounti05
   storage(initial 30M next 30K);
