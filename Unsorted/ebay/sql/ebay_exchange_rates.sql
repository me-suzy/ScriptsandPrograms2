/*
 * ebay_exchange_rates.sql
 *
 * This table contains our knowledge about different
 * exchange_rates indexed by day. 
 */

drop table ebay_exchange_rates;

create table ebay_exchange_rates
(
day_of_rate 	date
    constraint   exchange_rates_day_of_rate_nn
          not null,
from_currency	number(3,0)
	constraint   exchange_rates_currency_nn
		  not null,
rate			float(126)
)
tablespace tmiscd01
storage (initial 15K next 15K pctincrease 0);

//tablespace qexchangeratesd01
//storage (initial 15K next 15K pctincrease 0);

/* Space calculation: */
/* key:   8 bytes (???) x 365 days/year  =  3 K  */
/* table: 32 bytes (???) x 365 days/year = 12 K  */

alter table ebay_exchange_rates
	add constraint exchange_rates_pk
		primary key (day_of_rate, from_currency)
		using index tablespace tmiscd01
		storage (initial 3K next 3K);

// rexchangeratesi01

commit;

// Some test data
// 1 = USD
// 2 = CAD
// 3 = GBP

insert into ebay_exchange_rates (day_of_rate, from_currency, rate)
 values (TO_DATE('04/14/1999', 'MM/DD/YYYY'), 2, 0.6702);

insert into ebay_exchange_rates (day_of_rate, from_currency, rate)
 values (TO_DATE('04/14/1999', 'MM/DD/YYYY'), 3, 1.6186);

commit;


