/*	$Id: ebay_accounts_move.sql,v 1.2 1999/02/21 02:57:41 josh Exp $	*/
create table ebay_accounts_move(
 id                  number(8)
 tdate               date,
 action              number(3)
 amount              number(10,2)
 transaction_id      number(8)
 migration_batch_id  number(3))
insert into ebay_accounts_move(
           id,
           tdate,
           action,
           amount,
           transaction_id,
           migration_batch_id)
select id,
       when,
       action,
       amount,
       transaction_id,
       migration_batch_id
from   ebay_accounts
where  trunc(when) = trunc(sysdate - 1)
/
