/*	$Id: arcscript.sql,v 1.5 1999/03/22 00:09:45 josh Exp $	*/
/* archive scripts */

/* bid table archive first */
/* need to add host */
insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end < 
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

commit;
insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >= 
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-03-13 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

/* OR METHOD 2 */
insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason
 )
select ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason
	from ebay_bids
	where ebay_bids.item_id in
	(select id from ebay_items_to_archive);

commit;

/* item description */
/* cannot do this! illegal use of longraw!!! Do it in 2 or 3 steps:
1. insert selected ids to ebay_items_to_archive
2. make sure the ids are NOT in ebay_item_desc_arc
2. do ArchiveDesc
3. check to ensure quantity and records are copied over
4. cleanup ebay_items_to_archive
*/

/* was 11/22/97*/
/* was 12/07/97 */
/* for delete scripts */
/* do in several passes */
insert into ebay_items_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end <
	TO_DATE('1998-01-11 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/* run archiveDesc */
/* delete items in archiveDesc */
/* or */
delete from ebay_item_desc
where id in 
  (select id from ebay_items_to_archive)
  and marketplace = 0;
  commit;

delete from ebay_item_info where id in
  (select id from ebay_items_to_archive)
  and marketplace = 0;

delete from ebay_items where id in
  (select id from ebay_items_to_archive)
  and marketplace = 0;
commit;

insert into ebay_items_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end <
	TO_DATE('1997-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_items_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >=
	TO_DATE('1997-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <
	TO_DATE('1998-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

delete from ebay_items_to_archive where id in
(
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >=
	TO_DATE('1997-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <
	TO_DATE('1998-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

insert into ebay_items_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end <
	TO_DATE('1998-01-11 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_items_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end <
	TO_DATE('1998-01-21 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;


/* script to figure out which id was copied over if archiveDesc is aborted */
select id from ebay_item_desc_arc where
id in (select id from ebay_items_to_archive);

delete from ebay_items_to_archive where id in
(select id from ebay_item_desc_arc);

/* or: if all the items in ebay_item_desc_arc is in ebay_items_to_archive
insert into ebay_items_to_archive(id)
select id from ebay_item_desc_arc;
*/

/* then run ArchiveDest to copy destination description to ebay_item_desc_arc */

/* item_info in multiple passes */
insert into ebay_item_info_arc (
	MARKETPLACE	,
	ID,
	NOTICE_TIME,
	BILL_TIME
	)
	select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_item_info.marketplace,
	ebay_item_info.ID,
	ebay_item_info.NOTICE_TIME,
	ebay_item_info.BILL_TIME
	from ebay_item_info, ebay_items
	where ebay_item_info.id = ebay_items.id
	and ebay_items.sale_end <
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_item_info_arc (
	MARKETPLACE	,
	ID,
	NOTICE_TIME,
	BILL_TIME
	)
	select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_item_info.marketplace,
	ebay_item_info.ID,
	ebay_item_info.NOTICE_TIME,
	ebay_item_info.BILL_TIME
	from ebay_item_info, ebay_items
	where ebay_item_info.id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-03-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_item_info_arc (
	MARKETPLACE	,
	ID,
	NOTICE_TIME,
	BILL_TIME
	)
	select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_item_info.marketplace,
	ebay_item_info.ID,
	ebay_item_info.NOTICE_TIME,
	ebay_item_info.BILL_TIME
	from ebay_item_info, ebay_items
	where ebay_item_info.id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-01-21 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

/* ebay_items in multiple passes - 5 days' worth */
insert into ebay_items_arc (
	marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
)
	select /*+ index(ebay_items ebay_items_ending_index ) */ marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
	from ebay_items
	where ebay_items.sale_end <
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_items_arc (
	marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
)
	select /*+ index(ebay_items ebay_items_ending_index ) */ marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
	from ebay_items
	where ebay_items.sale_end >=
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-03-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_items_arc (
	marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
)
	select /*+ index(ebay_items ebay_items_ending_index ) */ marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
	from ebay_items
	where ebay_items.sale_end >=
	TO_DATE('1997-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1997-12-30 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_items_arc (
	marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
)
	select /*+ index(ebay_items ebay_items_ending_index ) */ marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
	from ebay_items
	where ebay_items.sale_end >=
	TO_DATE('1997-12-30 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-01-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

insert into ebay_items_arc (
	marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
)
	select /*+ index(ebay_items ebay_items_ending_index ) */ marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
	from ebay_items
	where ebay_items.sale_end >=
	TO_DATE('1998-01-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-01-21 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

/* delete scripts */
insert into ebay_items_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end <
	TO_DATE('1998-01-21 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

/* OR */

insert into ebay_item_info_arc (
	MARKETPLACE	,
	ID,
	NOTICE_TIME,
	BILL_TIME
	)
	select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_item_info.marketplace,
	ebay_item_info.ID,
	ebay_item_info.NOTICE_TIME,
	ebay_item_info.BILL_TIME
	from ebay_item_info
	where ebay_item_info.id in
	(select id from ebay_items_to_archive)
	and marketplace = 0;
commit;

insert into ebay_items_arc (
	marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
)
	select marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
	from ebay_items
	where ebay_items.id in
	(select id from ebay_items_to_archive)
	and marketplace = 0;

commit;


/* RUN deleteItems.sh script */

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-11 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

/* OR METHOD 2 */
delete from ebay_bids where item_id in
  (select id from ebay_items_to_archive)
  and marketplace = 0;

/* to get rid of those already archived in description */
delete from ebay_items_to_archive
  where id in 
  (select id from ebay_item_desc_arc);
commit;


delete from ebay_item_desc
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-11 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_item_desc
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_item_desc
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_item_desc
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

/* here */
delete from ebay_item_info
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_item_info
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-13 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_item_info
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_item_info
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-03-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_items
	where sale_end <
	TO_DATE('1998-03-11 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
delete from ebay_items
	where sale_end <
	TO_DATE('1998-03-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
delete from ebay_items
	where sale_end <
	TO_DATE('1998-03-13 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
delete from ebay_items
	where sale_end <
	TO_DATE('1998-03-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
delete from ebay_items
	where sale_end <
	TO_DATE('1998-03-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;


/* clean up ebay_item_desc_arc AT THE END */
truncate ebay_items_to_archive;

/* bug - when item has no description, it wasn't deleted; thus we have
duplicated item and bids in archive.
Now it is fixed by selecting all items to ebay_items_to_archive
again for deletion; But the script to fix is as follows.
*/

/* delete existing bid and item arc for the items concerned */

delete from ebay_bids_arc
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-11-22 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_item_desc_arc
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-11-22 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

/* here */
delete from ebay_item_info_arc
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-11-22 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));

delete from ebay_items_arc
	where id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end <
	TO_DATE('1997-11-22 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

/* archive again */

/* truncate the table ebay_items_to_archive */
/* insert the items */
/* deleteItems.sh */


/* export of archives for backup */
exp scott/tiger tables=ebay_bids_arc direct=Y indexes=N grants=Y constraints=N file=bidsarc.dmp
exp scott/tiger tables=ebay_item_desc_arc direct=Y indexes=N grants=Y constraints=N file=idescarc.dmp
exp scott/tiger tables=ebay_item_info_arc direct=Y indexes=N grants=Y constraints=N file=iinfoarc.dmp
exp scott/tiger tables=ebay_item_arc direct=Y indexes=N grants=Y constraints=N file=itemarc.dmp



/* NEW as of 2/20/98 */

/* clean up of bids table */

1 copy relevant bids to ebay_bids_arc;

insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end < 
	TO_DATE('1998-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

commit;
insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >= 
	TO_DATE('1998-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end < 
	TO_DATE('1998-01-05 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

commit;

insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >= 
	TO_DATE('1998-01-05 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end < 
	TO_DATE('1998-01-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

commit;

insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >= 
	TO_DATE('1998-01-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end < 
	TO_DATE('1998-01-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

commit;
/* time: 2:54 - 3:00 am - nothing else running */

/* get rid of duplicated bids in bids_arc because dean's script ran and bids
wasn't deleted from the bids table and I ran the bulk inserts again */
/* NOT DONE ! GET RID OF DUPS OTHER WAY */
1. create index on ebay_bids_arc (item_id)
create index ebay_bidsarc_itemid on ebay_bids_arc(item_id)
tablespace bidARCI1
storage (initial 200M next 200M minextents 1 maxextents 99 pctincrease 0)
unrecoverable;

2. delete bids on ebay_bids_arc that are in ebay_items_to_archive
delete from ebay_bids_arc where item_id in
  (select id from ebay_items_to_archive);
commit;

3. recopy bids from ebay_bids to ebay_bids_arc
insert into ebay_bids_arc (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason
 )
select ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason
	from ebay_bids
	where ebay_bids.item_id in
	(select id from ebay_items_to_archive)
	and marketplace = 0;

commit;
4. delete bids from ebay_bids
delete from ebay_bids where item_id in
  (select id from ebay_items_to_archive);
commit;
/* NOT DONE TILL HERE */

4B. /* in parallel clean up bids table */

a. drop indices except for item_id

drop index ebay_bids_item_user_index;
drop index ebay_bids_user_index;
commit;

b. delete bids by item numbers
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-09 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
/* time: 3:01 - 3:07 for 36,609 records */

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
/* 3:08 - 3:16 98,730 records */

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-12 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
/* 3:16 - 3:43 198,372 records */

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-14 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
/* 3:45 - 4:14 217,411 rows */

/* here */
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-16 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-17 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-18 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-19 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-21 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

/* up to here on script */

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-22 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-23 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-24 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-25 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-26 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-27 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-28 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-29 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-30 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1997-12-31 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;

/* here for script */
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-01-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-01-05 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-01-07 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-01-09 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;
delete from ebay_bids
	where item_id in
	(select /*+ index(ebay_items ebay_items_ending_index ) */ id from ebay_items
	where sale_end < 
	TO_DATE('1998-01-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
commit;


c. reinstate indices: coalesce tablespace, create indices;
alter tablespace bidi01 coalesce;
alter tablespace bidi02 coalesce;
alter tablespace bidi03 coalesce;

 create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace bidi01
	storage(initial 400M next 100M) unrecoverable parallel (degree 3);
commit;

 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace bidi03
   storage(initial 400M next 100M) unrecoverable parallel (degree 3);
commit;
/* 4:16 - 6:37!! */


5. copy ebay_item_info and ebay_items
insert into ebay_item_info_arc (
	MARKETPLACE	,
	ID,
	NOTICE_TIME,
	BILL_TIME
	)
	select ebay_item_info.marketplace,
	ebay_item_info.ID,
	ebay_item_info.NOTICE_TIME,
	ebay_item_info.BILL_TIME
	from ebay_item_info
	where ebay_item_info.id in
	(select id from ebay_items_to_archive)
	and marketplace = 0;
commit;

insert into ebay_items_arc (
	marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
)
	select marketplace,
	id,
	sale_type,
	title,
	location,
	seller,
	owner,
	password,
	category,
	quantity,
	bidcount,
	created,
	sale_start,
	sale_end,
	sale_status,
	current_price,
	start_price,
	reserve_price,
	high_bidder,
	featured,
	super_featured,
	bold_title,
	private_sale,
	registered_only,
	host,
	visitcount,			
	picture_url,
	last_modified
	from ebay_items
	where ebay_items.id in
	(select id from ebay_items_to_archive)
	and marketplace = 0;
commit;


6. delete ebay_item_info and ebay_items

rename ebay_items_to_archive to ebay_items_to_arc2;

delete from ebay_item_desc where id in
(select id from ebay_items_to_arc2)
and marketplace = 0;
commit;

delete from ebay_item_info where id in
  (select id from ebay_items_to_arc2)
  and marketplace = 0;

delete from ebay_items where id in
  (select id from ebay_items_to_arc2)
  and marketplace = 0;
commit;

7. truncate ebay_items_to_archive; (description is already copied over!)

create table ebay_items_to_archive
( id			number(38)
  constraint item_to_archive_id_nn
      not null)
tablespace itemarc1
storage (initial 5M next 5M);

8. Ready for next cleanup. Bids already copied and cleaned out, so its just
items.
/* use script above */
a. copy relevant items to ebay_items_to_archive 
b. copy ebay_item_info and ebay_items
c. run archive description
d. on completion, run deleteItems.sh



/* ANOTHER TRY AT ARCHIVING BIDS */

1. create new bid tablespace

create tablespace rbidd01
	datafile '/oracle-items/ebay/oradata/rbidd01.dbf'
	size 610M 
	autoextend on next 100M;

create tablespace rbidi01
	datafile '/oracle-items/ebay/oradata/rbidi01.dbf'
	size 401M 
	autoextend on next 100M;

create tablespace rbidi02
	datafile '/oracle-items/ebay/oradata/rbidi02.dbf'
	size 401M 
	autoextend on next 100M;

create tablespace rbidi03
	datafile '/oracle-items/ebay/oradata/rbidi03.dbf'
	size 401M 
	autoextend on next 100M;
	/* to be moved to /oracle07 */


2. create new bid table
 create table ebay_rbids 
 (
	marketplace	int
		constraint	rbid_marketplace_fk
		not null
		references	ebay_marketplaces(id),
 	item_id		int
		constraint	rbid_item_id_nn
		not null,
	user_id		int
		constraint	rbid_user_id_nn
		not null,
	quantity		int
		constraint	rbid_quantity_nn
		not null,
	amount		number(10,2)
		constraint	rbid_amount_nn
		not null,
	value			number(10,2)
		constraint	rbid_value_nn
		not null,
	type			int
		CONSTRAINT rbid_check_type
		CHECK (type IN (0, 1, 2, 3, 4, 5, 6)),
	created		date
		constraint	rbid_created_nn
		not null,
	reason		varchar2(255),
	host		varchar(16)
 )
 tablespace rbidd01
 storage (initial 600M next 100M);


3. copy valid bids to new table
/* have to do this in 5 day passes */

insert into ebay_rbids (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-01-21 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-01-26 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/* 872,176 records; ~11:00 - 12:24 jobs running; then shutdown */

insert into ebay_rbids (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-01-26 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-01-31 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/* 813,671 records; 12:24 - 12:33 */


insert into ebay_rbids (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-01-31 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-02-05 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/* 900,367 records; 12:34 - 12:44 */

insert into ebay_rbids (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-02-05 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-02-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/* 934,113 records; 12:44 - 12:55 */

insert into ebay_rbids (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-02-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-02-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/* 893,479 records; 12:56 - 1:07 */

insert into ebay_rbids (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-02-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and ebay_items.sale_end <
	TO_DATE('1998-02-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/* 974,548 records; 1:08 - 1:22 */

insert into ebay_rbids (
	marketplace,
 	item_id,
	user_id,
	quantity,
	amount,
	value,
	type,
	created,
	reason,
	host
 )
select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_bids.marketplace,
 	ebay_bids.item_id,
	ebay_bids.user_id,
	ebay_bids.quantity,
	ebay_bids.amount,
	ebay_bids.value,
	ebay_bids.type,
	ebay_bids.created,
	ebay_bids.reason,
	ebay_bids.host
	from ebay_bids, ebay_items 
	where ebay_bids.item_id = ebay_items.id
	and ebay_items.sale_end >=
	TO_DATE('1998-02-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
/*  1,066,282 records; 1:23 - 1:43 */

Verification:

select count(*) from ebay_bids, ebay_items
  where ebay_bids.item_id = ebay_items.id
  and ebay_items.sale_end >= 
	TO_DATE('1998-01-21 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

  COUNT(*)
----------
   6454636


5. create indices on new table
/* if can't rename indices, use ebay_rbids_... */
 create index ebay_bids_ritem_index
	on ebay_rbids(item_id)
	tablespace rbidi02
	storage(initial 400M next 100M) unrecoverable parallel (degree 3);
/* 1:53 - 2:46 */

 create index ebay_bids_ruser_index
   on ebay_rbids(user_id)
   tablespace rbidi03
   storage(initial 400M next 100M) unrecoverable parallel (degree 3);
/* 2:46 - 3:12 */

 create index ebay_bids_ritem_user_index
	on ebay_rbids(item_id, user_id)
   tablespace rbidi01
	storage(initial 400M next 100M) unrecoverable parallel (degree 4);
	commit;
/* 3:13 - 3:47 */


4. rename old table 
	rename ebay_bids to ebay_bids_arc2;

	rename ebay_rbids to ebay_bids;

6. add back the constraints

alter table ebay_bids
add	constraint		rbid_check_quantity
		check (quantity >= 0);
commit;

alter table ebay_bids
add	constraint		rbid_check_amount
		check (amount >= 0);
commit;

alter table ebay_bids 
add constraint	rbid_item_fk	
foreign key (marketplace, item_id)
			references	ebay_items(marketplace, id);
commit;

alter table ebay_bids
add	constraint		rbid_user_fk
			foreign key (user_id)
			references	ebay_users(id);
commit;


on old table:
alter table ebay_bids_arc2 drop constraint bid_check_quantity;
alter table ebay_bids_arc2 drop constraint bid_check_amount;
alter table ebay_bids_arc2 drop constraint bid_item_fk;
alter table ebay_bids_arc2 drop constraint bid_user_fk;
commit;

drop index ebay_bids_item_user_index;
drop index ebay_bids_user_index;
commit;

/* check to ensure no segments in bidi01 and bidi03 */
select substr(segment_name, 1,35), tablespace_name from dba_segments
where tablespace_name = 'BIDI01' or tablespace_name = 'BIDI03';

drop tablespace bidi01 including contents;
drop tablespace bidi03 including contents;

rm /oracle21/ebay/oradata/bidi01.dbf
rm /oracle21/ebay/oradata/bidi03.dbf

6. move tablespaces to final destination.
/* move bidd02 from /oracle03 to raid device /oracle-items */

alter tablespace bidd02 offline;

cp /oracle03/ebay/oradata/bidd02.dbf /oracle-items/ebay/oradata/.

alter tablespace bidd02 rename datafile 
 '/oracle03/ebay/oradata/bidd02.dbf' to 
 '/oracle-items/ebay/oradata/bidd02.dbf' ;

 alter tablespace bidd02   online;

/* move rbidd01 from /oracle-items to where bidd02 was /oracle03 */
alter tablespace rbidd01 offline;

cp /oracle-items/ebay/oradata/rbidd01.dbf /oracle03/ebay/oradata/.

alter tablespace rbidd01 rename datafile 
 '/oracle-items/ebay/oradata/rbidd01.dbf' to 
 '/oracle03/ebay/oradata/rbidd01.dbf' ;

 alter tablespace rbidd01   online;

/* move rbidi01 to where bidi01 was /oracle12 */
alter tablespace rbidi01 offline;

cp /oracle-items/ebay/oradata/rbidi01.dbf /oracle21/ebay/oradata/.

alter tablespace rbidi01 rename datafile 
 '/oracle-items/ebay/oradata/rbidi01.dbf' to 
 '/oracle21/ebay/oradata/rbidi01.dbf' ;

 alter tablespace rbidi01   online;


/* move rbidi03 to where bidi03 was /oracle21 */

alter tablespace rbidi03 offline;

cp /oracle-items/ebay/oradata/rbidi03.dbf /oracle21/ebay/oradata/.

/* not moved! not enough space */
alter tablespace rbidi03 rename datafile 
 '/oracle-items/ebay/oradata/rbidi03.dbf' to 
 '/oracle21/ebay/oradata/rbidi03.dbf' ;

 alter tablespace rbidi03   online;


/* done */

/* script to extend all auctions */

invalidate seller/bidder lists:
update ebay_seller_item_lists
set item_list_valid = 'N' where id in
(select distinct seller from ebay_items
where sale_end > TO_DATE('1998-07-24 7:15:00', 'YYYY-MM-DD HH24:MI:SS')
and sale_end < TO_DATE('1998-07-24 11:00:00', 'YYYY-MM-DD HH24:MI:SS'));

update ebay_bidder_item_lists
set item_list_valid = 'N' where id in
(select distinct high_bidder from ebay_items
where sale_end > TO_DATE('1998-07-24 7:15:00', 'YYYY-MM-DD HH24:MI:SS')
and sale_end < TO_DATE('1998-07-24 11:00:00', 'YYYY-MM-DD HH24:MI:SS'));

update ebay_items set 
last_modified = sysdate, sale_end = sale_end + 1
where sale_end > TO_DATE('1998-07-23 10:00:00', 'YYYY-MM-DD HH24:MI:SS')
and sale_end < TO_DATE('1998-07-23 11:00:00', 'YYYY-MM-DD HH24:MI:SS');


--- 01/24/99 Tini Widjojo
--- archiving data from ebay_item_info to ebay_item_info_arc earlier
--- do by multiple days at a time

create table ebay_items_to_archive (id number(32)) tablespace summary
storage (initial 5M next 5M pctincrease 0);

create table ebay_item_info_to_archive tablespace summary storage
(initial 5M next 5m pctincrease 0) unrecoverable as
 select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end <
	TO_DATE('1998-12-14 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
--- 1,310,654 records

insert into ebay_item_info_arc (
	MARKETPLACE	,
	ID,
	NOTICE_TIME,
	BILL_TIME
	)
	select ebay_item_info.marketplace,
	ebay_item_info.ID,
	ebay_item_info.NOTICE_TIME,
	ebay_item_info.BILL_TIME
	from ebay_item_info
	where ebay_item_info.id in
	(select id from ebay_item_info_to_archive)
	and marketplace = 0;
commit;

delete from ebay_item_info where id in
  (select id from ebay_item_info_to_archive)
  and marketplace = 0;
commit;

--- do this in passes of every 3 - 5 days?
truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1998-12-14 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1998-12-19 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
select count(*) from ebay_item_info_to_archive;
-- 627073 rows created.

truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1998-12-19 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1998-12-23 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 767325 rows created.

truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1998-12-23 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1998-12-27 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 393965 rows created.

truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1998-12-27 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1998-12-31 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 598383 rows created.

truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1998-12-31 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-01-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 317314 rows created.

truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1999-01-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-01-06 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 601080 rows created.

truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1999-01-06 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-01-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 716487 rows created.

truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1999-01-10 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-01-13 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 648258 rows created.

-- 5,980,539 total rows deleted.
-- 2,084,738 records remaining 

total in ebay_items 01/24/99:
-- 9954478

-- More cleanups Jan 31, 1999
rename ebay_item_info_arc to ebay_item_info_arc_111298;
 
truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1999-01-13 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-01-16 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 588,521

create table ebay_item_info_arc2 tablespace ITEMARCD02 storage
(initial 200m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info where 
  id in (select id from ebay_item_info_to_archive);
rename ebay_item_info_arc2 to ebay_item_info_arc;

delete from ebay_item_info where id in
  (select id from ebay_item_info_to_archive)
  and marketplace = 0;
commit;


create table ebay_item_info_to_archive2 tablespace playd01 storage
(initial 1M next 1M pctincrease 0) unrecoverable as
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1999-01-16 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-01-19 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 681644

insert into ebay_item_info_arc 
	select *
	from ebay_item_info
	where id in
	(select id from ebay_item_info_to_archive2)
	and marketplace = 0;
commit;

delete from ebay_item_info where id in
  (select id from ebay_item_info_to_archive2)
  and marketplace = 0;
commit;


truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1999-01-19 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-01-22 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;
-- 597575 rows created.
insert into ebay_item_info_arc 
	select *
	from ebay_item_info
	where id in
	(select id from ebay_item_info_to_archive)
	and marketplace = 0;
commit;

delete from ebay_item_info where id in
  (select id from ebay_item_info_to_archive)
  and marketplace = 0;
commit;

-- 01-22-99 to 01-23-99: 187276 rows created.
-- 01-23-99 to 01-24-99: 242620 rows created.
-- 01-24-99 to 01-25-99: 278205 rows created.
-- 01-25-99 to 01-26-99: 231935 rows created.
-- 01-26-99 to 01-27-99: 198410 rows created.
-- 01-27-99 to 01-28-99: 203807 rows created.
-- 01-28-99 to 01-29-99: 203710 rows created.
-- 01-29-99 to 01-30-99: 205240 rows created.
-- 01-30-99 to 01-31-99: 262680 rows created.
-- 02-01-99 to 02-02-99: 209469 rows created.
-- 02-02-99 to 02-03-99: 193622 rows created.
-- 02-03-99 to 02-04-99: 235795 rows created.
-- 02-04-99 to 02-05-99: 208752 rows created.
-- 02-05-99 to 02-06-99: 189259 rows created.
-- 02-06-99 to 02-07-99: 274359 rows created.
-- 02-07-99 to 02-08-99: 166630 rows created.
-- 02-08-99 to 02-09-99: 365683 rows created.
-- 02-09-99 to 02-10-99: 204587 rows created.
-- 02-10-99 to 02-11-99: 229260 rows created.
-- 02-11-99 to 02-12-99: 225058 rows created.
-- 02-12-99 to 02-13-99: 213657 rows created.
-- 02-13-99 to 02-14-99: 276950 rows created.
-- 02-14-99 to 02-15-99: 299325 rows created.
-- 02-15-99 to 02-16-99: 246480 rows created.
-- 02-16-99 to 02-17-99: 229781 rows created.
-- 02-17-99 to 02-18-99: 229404 rows created.


truncate table ebay_item_info_to_archive;
insert into ebay_item_info_to_archive (id)
select /*+ index(ebay_items ebay_items_ending_index ) */
	ID
	from ebay_items
	where sale_end >
	TO_DATE('1999-02-18 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
	and sale_end <=
	TO_DATE('1999-02-19 00:00:00', 'YYYY-MM-DD HH24:MI:SS')	;
commit;

insert into ebay_item_info_arc 
	select *
	from ebay_item_info
	where id in
	(select id from ebay_item_info_to_archive)
	and marketplace = 0;
commit;

delete from ebay_item_info where id in
  (select id from ebay_item_info_to_archive)
  and marketplace = 0;
commit;

-- archive items up to dec 31, 1998.
-- disable fk constraint we are not supposed to delete item
-- description (as per Mike).

----------------------
Parallel 1 - ENSURE CTAS of Bids arc has been done
---------------------
create table ebay_items_to_archive_1 tablespace playd01 storage 
       (initial 1M next 1M pctincrease 0) unrecoverable as
select /*+ index(ebay_items ebay_items_ending_index ) */
        ID
        from ebay_items
        where sale_end <
        TO_DATE('1998-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

insert into ebay_bids_arc
select *
        from ebay_bids
        where item_id in
        (select id from ebay_items_to_archive_1);

delete from ebay_bids
where item_id in
(select id from ebay_items_to_archive_1);
commit;

insert into ebay_items_arc
select * from ebay_items
where sale_end <
        TO_DATE('1998-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

delete from ebay_items
where sale_end <
        TO_DATE('1998-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

----------------------
Parallel 2 - ENSURE CTAS of Bids arc has been done
---------------------
create  table ebay_items_to_archive_2 tablespace playd01 storage 
       (initial 1M next 1M pctincrease 0) unrecoverable as
select /*+ index(ebay_items ebay_items_ending_index ) */
        ID
        from ebay_items
        where sale_end >=
            TO_DATE('1998-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1998-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

insert into ebay_bids_arc
select *
        from ebay_bids
        where item_id in
        (select id from ebay_items_to_archive_2);

delete from ebay_bids
where item_id in
(select id from ebay_items_to_archive_2);
commit;

insert into ebay_items_arc
select * from ebay_items
where sale_end >=
        TO_DATE('1998-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1998-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

delete from ebay_items
where sale_end >=
        TO_DATE('1998-12-15 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1998-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
----------------------
Parallel 3 - ENSURE CTAS of Bids arc has been done
---------------------
create table  ebay_items_to_archive_3 tablespace playd01 storage 
       (initial 1M next 1M pctincrease 0) unrecoverable as
select /*+ index(ebay_items ebay_items_ending_index ) */
        ID
        from ebay_items
        where sale_end >=
        TO_DATE('1998-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1998-12-25 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

insert into ebay_bids_arc
select *
        from ebay_bids
        where item_id in
        (select id from ebay_items_to_archive_3);

delete from ebay_bids
where item_id in
(select id from ebay_items_to_archive_3);
commit;

insert into ebay_items_arc
select * from ebay_items
where sale_end >=
        TO_DATE('1998-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1998-12-25 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

delete from ebay_items
where sale_end >=
        TO_DATE('1998-12-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1998-12-25 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;

----------------------
Parallel 4 - ENSURE CTAS of Bids arc has been done
---------------------
create table  ebay_items_to_archive_4 tablespace playd01 storage 
       (initial 1M next 1M pctincrease 0) unrecoverable as
select /*+ index(ebay_items ebay_items_ending_index ) */
        ID
        from ebay_items
        where sale_end >=
        TO_DATE('1998-12-25 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1999-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

insert into ebay_bids_arc
select *
        from ebay_bids
        where item_id in
        (select id from ebay_items_to_archive_4);

delete from ebay_bids
where item_id in
(select id from ebay_items_to_archive_4);
commit;

insert into ebay_items_arc
select * from ebay_items
where sale_end >=
        TO_DATE('1998-12-25 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1999-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS');

delete from ebay_items
where sale_end >=
        TO_DATE('1998-12-25 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        and sale_end <
        TO_DATE('1999-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;


-- CTAS for ebay_items_arc_1298
-- CTAS for ebay_bids_arc_1298
-- CTAS for ebay_item_info_arc_1298

