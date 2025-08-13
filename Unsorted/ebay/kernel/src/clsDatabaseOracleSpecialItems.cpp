/*	$Id: clsDatabaseOracleSpecialItems.cpp,v 1.1.6.1 1999/06/04 19:26:14 jpearson Exp $	*/
//	File:		clsDatabaseOracleSpecialItems.cpp
//
//  Class:	clsDatabaseOracle
//
//	Author:	Jennifer Pearson (jen@ebay.com)
//
//	Function:
//
//			Methods to access information in ebay_special_items table
//
// Modifications:
//				- 05/18/99 jennifer		- Created
//


#include "eBayKernel.h"

// AddSpecialItem

static const char *SQL_AddSpecialItem = 
 "insert into ebay_special_items				\
  (	marketplace,								\
	id,											\
	add_date,									\
	who_added,									\
	kind,										\
	sale_end_date								\
  )												\
  values										\
  (	0,											\
	:id,										\
	sysdate,									\
	1,											\
	:kind,										\
	TO_DATE(:sale_end_date,						\
			'YYYY-MM-DD HH24:MI:SS')			\
  )";


void clsDatabaseOracle::AddSpecialItem(int item_id, int kind, time_t endDate)
{
	struct tm	*pendDate;
	char		cendDate[32] = {0};

	// convert date
	pendDate = localtime(&endDate);
	TM_STRUCTToORACLE_DATE(pendDate, cendDate);

	// open and parse
	OpenAndParse(&mpCDAAddSpecialItem, SQL_AddSpecialItem);

	// bind
	Bind(":id", &item_id);
	Bind(":kind", &kind);
	Bind(":sale_end_date", (char *)cendDate);

	// execute
	Execute();
	Commit();

	// bye
	Close(&mpCDAAddSpecialItem);
	SetStatement(NULL);

	return;

}

//
// DeleteSpecialItem
//

static char *SQL_DeleteSpecialItem =
"delete from ebay_special_items		\
	where id = :id";

void clsDatabaseOracle::DeleteSpecialItem(int item_id)
{
	OpenAndParse(&mpCDADeleteSpecialItem, SQL_DeleteSpecialItem);

	// do the bind
	Bind(":id", (int *)&item_id);

	// do it
	Execute();

	Commit();

	Close(&mpCDADeleteSpecialItem);
	SetStatement(NULL);

	return;
}

//
// DeleteSpecialItem
//

static char *SQL_FlushSpecialItem =
"delete from ebay_special_items		\
	where sale_end_date <= sysdate";

void clsDatabaseOracle::FlushSpecialItem()
{
	OpenAndParse(&mpCDAFlushSpecialItem, SQL_FlushSpecialItem);

	// do it
	Execute();

	Commit();

	Close(&mpCDAFlushSpecialItem);
	SetStatement(NULL);

	return;
}
