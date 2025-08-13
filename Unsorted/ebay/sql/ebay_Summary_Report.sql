/*
 * ebay_Summary_Report.sql
 *
 * Daily Summary By Categpry
 *
 */

 drop table ebay_Summary_Report;

 create table ebay_Summary_Report 
 (
	effective_date	date,
	category_name	varchar2(85),
	AllCount		number(38),	
	RCount			number(38),	
	RSoldCount		number(38),	
	RNotCount		number(38),	
	NRCSoldCount	number(38),		
	NRCNotCount		number(38),	
	DSoldCount		number(38),		
	DNotCount		number(38),	
	AllSoldCount	number(38),		
	SumSoldPrice	number(12,2),	
	RSoldPrice		number(12,2),	
	NRCSoldPrice	number(12,2),		
	DSoldPrice		number(12,2),	
	SumBoldFees		number(12,2),	
	SumFeatFees		number(12,2),	
	SumSuperFeatFees	number(12,2),		
	SumListFees		number(12,2),	
	SumFVFees		number(12,2),	
	RSoldFees		number(12,2),	
	RNotSoldFees	number(12,2),		
	NRCSoldFees		number(12,2),	
	NRCNotSoldFees	number(12,2),	
	DSoldFees		number(12,2),	
	DNotSoldFees	number(12,2),		
	SumFees			number(12,2)
)
tablespace tmiscd01;


	
