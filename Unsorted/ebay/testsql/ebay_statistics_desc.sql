/*	$Id: ebay_statistics_desc.sql,v 1.2 1999/02/21 02:56:46 josh Exp $	*/
	drop table ebay_statistics_desc;

	create table ebay_statistics_desc
	(
		id				number(38)
			constraint	stats_xaction_id_nn
			not null,
		statistics_type	number(38)
			constraint	stats_xaction_type_nn
			not null,
		description		VARCHAR(50)
			constraint	stats_xaction_description_nn
			not null,
		query			varchar(500),
		constraint		stats_xaction_pk
			primary key (id, statistics_type)
			using index tablespace	tstatsi01
			storage (initial 1K next 1K)
	)
	tablespace tstatsd01
	storage (initial 10K next 1K);

	INSERT into ebay_statistics_desc values (0, 0, 
	'Daily Statistics on bids',
	'INSERT into ebay_dailystatistics ' ||
	'select :marketplace, ' ||
	'TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS''), ' ||
	':xactionid, 0, 0, 0, count(*) ' ||
	'FROM ebay_bids WHERE marketplace = :marketplace and ' ||
	'created >= TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'created <  TO_DATE(:end_time,   ''YYYY-MM-DD HH24:MI:SS'')');

	INSERT into ebay_statistics_desc values (1, 0, 
	'Daily Statistics on new chinese Auction',
	'INSERT into ebay_dailystatistics ' ||
	'select /*+ index(ebay_items ebay_items_starting_index ) */ :marketplace, ' ||
	'TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS''), ' ||
	':xactionid, category, count(*), sum(start_price), sum(bidcount) ' ||
	'FROM ebay_items WHERE marketplace = :marketplace and ' ||
	'sale_start >= TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'sale_start <  TO_DATE(:end_time,   ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'quantity = 1 and start_price < 10000 GROUP BY category');

	INSERT into ebay_statistics_desc values (2, 0, 
	'Daily Statistics on completed chinese Auction',
	'INSERT into ebay_dailystatistics ' ||
	'select /*+ index(ebay_items ebay_items_ending_index ) */ :marketplace, ' ||
	'TO_DATE(:start_time, ''YYYY-MM-DD, HH24:MI:SS''), ' ||
	':xactionid, category, count(*), sum(current_price), sum(bidcount) ' ||
	'FROM ebay_items WHERE marketplace = :marketplace and ' ||
	'sale_end >= TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'sale_end <  TO_DATE(:end_time,   ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'quantity = 1 and current_price >=reserve_price and ' ||
	'current_price < 10000 GROUP BY category');

	INSERT into ebay_statistics_desc values (3, 0, 
	'Daily Statistics on new dutch Auction',
	'INSERT into ebay_dailystatistics ' ||
	'select /*+ index(ebay_items ebay_items_starting_index ) */ :marketplace, ' ||
	'TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS''), ' ||
	':xactionid, category, count(*), sum(start_price), sum(bidcount) ' ||
	'FROM ebay_items WHERE marketplace = :marketplace and ' ||
	'sale_start >= TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'sale_start <  TO_DATE(:end_time,   ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'quantity > 1 and start_price < 10000 GROUP BY category');

	INSERT into ebay_statistics_desc values (4, 0, 
	'Daily Statistics on completed dutch Auction',
	'INSERT into ebay_dailystatistics ' ||
	'select /*+ index(ebay_items ebay_items_ending_index ) */ :marketplace, ' ||
	'TO_DATE(:start_time, ''YYYY-MM-DD, HH24:MI:SS''), ' ||
	':xactionid, category, count(*), sum(current_price), sum(bidcount) ' ||
	'FROM ebay_items WHERE marketplace = :marketplace and ' ||
	'sale_end >= TO_DATE(:start_time, ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'sale_end <  TO_DATE(:end_time,   ''YYYY-MM-DD HH24:MI:SS'') and ' ||
	'quantity > 1 and ' ||
	'current_price < 10000 GROUP BY category');

	commit;
