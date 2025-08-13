/*	$Id: clsDatabaseOracleWidgetCache.cpp,v 1.2 1999/03/07 08:16:52 josh Exp $	*/
//
//	File:	clsDatabaseOracleWidgetCache.cc
//
//	Class:	clsDatabaseOracle
//
//
//	Function: Store and retrieve items from a database cache.
//
// Modifications:

#include "eBayKernel.h"

#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <errno.h>
#include <time.h>
#include "clsEnvironment.h"

#define ItemCacheRandom 0

void clsDatabaseOracle::GetCachedCategoryIds(list<int> *pStore)
{
	int category;

	static const char *SQL_GetCachedCategoryIds =
		"select unique(category) from ebay_item_list_cache";

	OpenAndParse(&mpCDAOneShot, SQL_GetCachedCategoryIds);

	Define(1, &category);

	Execute();

	do
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		pStore->push_back(category);
	} while (1);

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

void clsDatabaseOracle::StoreItemList(int active,
				      int kind, 
				      CategoryId category,
					  char* scope,
				      int country, 
				      vector<unsigned long> *pStore)
{
	int item_count;
	int item_list_size;
	int print_length;
	unsigned long max_id;
	vector<unsigned long>::iterator i;
	char *pItemsString;
	char *pOut;

	static const char *SQL_RemoveItemList =
		"delete from ebay_item_list_cache where "
		"active = :active and "
		"kind = :kind and "
		"category = :category and "
		"country = :country";

	static const char *SQL_StoreItemList =
		"insert into ebay_item_list_cache "
		"(active, kind, category, scope, country, last_update, "
		"item_count, item_list_size, item_numbers) "
		"values "
		"(:active, :kind, :category, :scope, :country, sysdate, "
		":item_count, :item_list_size, :item_numbers)";

	OpenAndParse(&mpCDAOneShot, SQL_RemoveItemList);

	Bind(":active", &active);
	Bind(":kind", &kind);
	Bind(":category", &category);
	Bind(":country", &country);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	item_count = pStore->size();
	if (!item_count)
		return;

	max_id = *(max_element(pStore->begin(), pStore->end()));
	// Calculate how long it would be to print this -- the extra 1 is for a comma.
	for (print_length = 1; max_id; max_id /= 10, ++print_length) ;

	item_list_size = print_length * item_count;
	pItemsString = new char [item_list_size];

	for (i = pStore->begin(), pOut = pItemsString; i != pStore->end(); ++i)
	{
		sprintf(pOut, "%u", *i);
		pOut += strlen(pOut);
		*pOut = ',';
		++pOut;
	}

	// We don't use a comma for the last element.
	--pOut;
	*pOut = '\0';

	item_list_size = strlen(pItemsString) + 1;

	OpenAndParse(&mpCDAOneShot, SQL_StoreItemList);

	Bind(":active", (int *) &active);
	Bind(":kind", (int *) &kind);
	Bind(":category", (int *) &category);
	Bind(":country", &country);
	Bind(":scope", scope);
	Bind(":item_count", &item_count);
	Bind(":item_list_size", &item_list_size);
	BindLongRaw(":item_numbers", (unsigned char *) pItemsString,
		item_list_size);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

void clsDatabaseOracle::RetrieveItemList(int active, 
					 int kind, 
					 CategoryId category,
					 int country, 
					 vector<unsigned long> *pStore)
{
	int item_count;
	int item_list_size;
	long item;
	char rowid[20];

	char *pIn;
	char *pNext;
	char *pItemsString;

	// NOTE: the cache is currently set to expire after 1 day (noted by "sysdate - 1")
	static const char *SQL_RetrieveItemList =
		"select item_count, item_list_size, rowid "
		"from ebay_item_list_cache where "
		"active = :active and "
		"kind = :kind and "
		"category = :category and "
		"country = :country and "
		"last_update > sysdate - 1";

	static const char *SQL_RetrieveItemListText =
		"select item_numbers from ebay_item_list_cache where "
		"rowid = CHARTOROWID(:row_id)";

	rowid[0] = '\0';

	OpenAndParse(&mpCDAOneShot, SQL_RetrieveItemList);

	Bind(":active", (int *) &active);
	Bind(":kind", (int *) &kind);
	Bind(":category", (int *) &category);
	Bind(":country", &country);

	Define(1, &item_count);
	Define(2, &item_list_size);
	Define(3, rowid, sizeof (rowid));

	ExecuteAndFetch();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (rowid[0] == '\0')
		return;

	pStore->reserve(pStore->size() + item_count);

	pItemsString = new char [item_list_size + 1];

	OpenAndParse(&mpCDAOneShot, SQL_RetrieveItemListText);

	Bind(":row_id", rowid);

	DefineLongRaw(1, (unsigned char *) pItemsString, item_list_size);

	ExecuteAndFetch();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	pItemsString[item_list_size] = '\0';

	// Now that we have the whole string, let's break it up into its numbers.
	for (pIn = pNext = pItemsString; *pNext; pIn = pNext + 1)
	{
		item = strtol(pIn, &pNext, 0);
		pStore->push_back((unsigned long) item);
	}

	return;
}
