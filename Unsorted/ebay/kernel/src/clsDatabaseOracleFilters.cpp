/*	$Id: clsDatabaseOracleFilters.cpp,v 1.2 1999/05/19 02:34:52 josh Exp $	*/
//	File:		clsDatabaseOracleFilters.cpp
//
// Class:	clsDatabaseOracle
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Methods to access information in ebay_filters table
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsFilters.h"

//-------------------------------------------------------------------------------------
// Filters
//-------------------------------------------------------------------------------------

//
// AddFilter
//

static char *SQL_AddFilter =
"insert into ebay_filters "
"	( "
"		id, "
"		name, "
"		pattern, "
"		action_type, "
"		flag_item, "
"		notify_type, "
"		blocked_msg_id, "
"		flagged_msg_id, "
"		filter_msg_id, "
"		buddy_msg_id, "
"		filter_emails, "
"		buddy_emails "
"	) "
"	values "
"	( "
"		:id, "
"		:name, "
"		:pattern, "
"		:action, "
"		:flag_item, "
"		:notify, "
"		:blocked_msg_id, "
"		:flagged_msg_id, "
"		:filter_msg_id, "
"		:buddy_msg_id, "
"		:filter_emails, "
"		:buddy_emails "
"	)";

bool clsDatabaseOracle::AddFilter(clsFilter *pFilter)
{
	FilterId	id;
	char		cFlagItem[2];
	ActionType	action;
	NotifyType	notify;
	MessageId	blockedMsgId;
	MessageId	flaggedMsgId;
	MessageId	filterEmailMsgId;
	MessageId	buddyEmailMsgId;

	if (pFilter == NULL)
		return false;

	id = pFilter->GetId();
	action = pFilter->GetActionType();
	notify = pFilter->GetNotifyType();
	blockedMsgId = pFilter->GetBlockingMessageId();
	flaggedMsgId = pFilter->GetFlaggingMessageId();
	filterEmailMsgId = pFilter->GetFilteringMessageId();
	buddyEmailMsgId = pFilter->GetBuddyMessageId();

	if (pFilter->GetFlagItem())
		strcpy(cFlagItem, "1");
	else
		strcpy(cFlagItem, "0");

	OpenAndParse(&mpCDAOneShot, SQL_AddFilter);

	// Bind it, baby
	Bind(":id",				(int *)&id);
	Bind(":name",			(char *)pFilter->GetName());
	Bind(":pattern",		(char *)pFilter->GetExpression());
	Bind(":action",			(int *)&action);
	Bind(":flag_item",		(char *)cFlagItem);
	Bind(":notify",			(int *)&notify);
	Bind(":blocked_msg_id", (int *)&blockedMsgId);
	Bind(":flagged_msg_id", (int *)&flaggedMsgId);
	Bind(":filter_msg_id",	(int *)&filterEmailMsgId);
	Bind(":buddy_msg_id",	(int *)&buddyEmailMsgId);
	Bind(":filter_emails",	(char *)pFilter->GetFilteringEmailAddresses());
	Bind(":buddy_emails",	(char *)pFilter->GetBuddyEmailAddresses());

	// Do it...
	Execute();

	Commit();

	// Leave it!
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return true;
}

//
// DeleteFilter
//

static char *SQL_DeleteFilterById =
"delete from ebay_filters "
"	where id = :id";

void clsDatabaseOracle::DeleteFilter(FilterId id)
{
	OpenAndParse(&mpCDADeleteFilterById, SQL_DeleteFilterById);

	// do the bind
	Bind(":id", (int *)&id);

	// do it
	Execute();

	Commit();

	Close(&mpCDADeleteFilterById);
	SetStatement(NULL);

	return;
}

//
// DeleteFilter
//

static char *SQL_DeleteFilterByName =
"delete from ebay_filters "
"	where name = :name";

void clsDatabaseOracle::DeleteFilter(const char *pName)
{
	OpenAndParse(&mpCDADeleteFilterByName, SQL_DeleteFilterByName);

	// do the bind
	Bind(":name", (char *)pName);

	// do it
	Execute();

	Commit();

	Close(&mpCDADeleteFilterByName);
	SetStatement(NULL);

	return;
}

//
// UpdateFilter(ById)
//

static char *SQL_UpdateFilterById =
"update ebay_filters "
"	set name = :name, "
"		pattern = :pattern, "
"		flag_item = :flag_item, "
"		action_type = :action, "
"		notify_type = :notify, "
"		blocked_msg_id = :blocked_msg_id, "
"		flagged_msg_id = :flagged_msg_id, "
"		filter_msg_id = :filter_msg_id, "
"		buddy_msg_id = :buddy_msg_id, "
"		filter_emails = :filter_emails, "
"		buddy_emails = :buddy_emails "
"	where id = :id";

bool clsDatabaseOracle::UpdateFilter(FilterId id, clsFilter *pFilter)
{
	bool			updated = false;

	char			cFlagItem[2];
	ActionType		action;
	NotifyType		notify;
	MessageId		blockedMsgId;
	MessageId		flaggedMsgId;
	MessageId		filterEmailMsgId;
	MessageId		buddyEmailMsgId;

	if (pFilter == NULL)
		return false;

	id = pFilter->GetId();
	action = pFilter->GetActionType();
	notify = pFilter->GetNotifyType();
	blockedMsgId = pFilter->GetBlockingMessageId();
	flaggedMsgId = pFilter->GetFlaggingMessageId();
	filterEmailMsgId = pFilter->GetFilteringMessageId();
	buddyEmailMsgId = pFilter->GetBuddyMessageId();

	if (pFilter->FlagListing())
		strcpy(cFlagItem, "1");
	else
		strcpy(cFlagItem, "0");

	OpenAndParse(&mpCDAUpdateFilterById, SQL_UpdateFilterById);

	// Bind it, baby
	Bind(":id",				(int *)&id);
	Bind(":name",			pFilter->GetName());
	Bind(":pattern",		pFilter->GetExpression());
	Bind(":flag_item",		(char *)cFlagItem);
	Bind(":action",			(int *)&action);
	Bind(":notify",			(int *)&notify);
	Bind(":blocked_msg_id", (int *)&blockedMsgId);
	Bind(":flagged_msg_id", (int *)&flaggedMsgId);
	Bind(":filter_msg_id",	(int *)&filterEmailMsgId);
	Bind(":buddy_msg_id",	(int *)&buddyEmailMsgId);
	Bind(":filter_emails",	(char *)pFilter->GetFilteringEmailAddresses());
	Bind(":buddy_emails",	(char *)pFilter->GetBuddyEmailAddresses());

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAUpdateFilterById);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAUpdateFilterById);
		SetStatement(NULL);
		updated = true;
	}

	return updated;
}

//
// UpdateFilter(ByName)
//

static char *SQL_UpdateFilterByName =
"update ebay_filters "
"	set id = :id, "
"		name = :newname, "
"		pattern = :pattern, "
"		flag_item = :flag_item, "
"		action_type = :action, "
"		notify_type = :notify, "
"		blocked_msg_id = :blocked_msg_id, "
"		flagged_msg_id = :flagged_msg_id, "
"		filter_msg_id = :filter_msg_id, "
"		buddy_msg_id = :buddy_msg_id, "
"		filter_emails = :filter_emails, "
"		buddy_emails = :buddy_emails "
"	where name = :oldname";

bool clsDatabaseOracle::UpdateFilter(const char *pName,
									 clsFilter *pFilter)
{
	bool			updated = false;

	FilterId		id;
	char			cFlagItem[2];
	ActionType		action;
	NotifyType		notify;
	MessageId		blockedMsgId;
	MessageId		flaggedMsgId;
	MessageId		filterEmailMsgId;
	MessageId		buddyEmailMsgId;

	if (pFilter == NULL)
		return false;

	id = pFilter->GetId();
	action = pFilter->GetActionType();
	notify = pFilter->GetNotifyType();
	blockedMsgId = pFilter->GetBlockingMessageId();
	flaggedMsgId = pFilter->GetFlaggingMessageId();
	filterEmailMsgId = pFilter->GetFilteringMessageId();
	buddyEmailMsgId = pFilter->GetBuddyMessageId();

	if (pFilter->FlagListing())
		strcpy(cFlagItem, "1");
	else
		strcpy(cFlagItem, "0");

	OpenAndParse(&mpCDAUpdateFilterByName, SQL_UpdateFilterByName);

	// Bind it, baby
	Bind(":id",				(int *)&id);
	Bind(":newname",		(char *)pFilter->GetName());
	Bind(":pattern",		(char *)pFilter->GetExpression());
	Bind(":flag_item",		(char *)cFlagItem);
	Bind(":action",			(int *)&action);
	Bind(":notify",			(int *)&notify);
	Bind(":blocked_msg_id", (int *)&blockedMsgId);
	Bind(":flagged_msg_id", (int *)&flaggedMsgId);
	Bind(":filter_msg_id",	(int *)&filterEmailMsgId);
	Bind(":buddy_msg_id",	(int *)&buddyEmailMsgId);
	Bind(":filter_emails",	(char *)pFilter->GetFilteringEmailAddresses());
	Bind(":buddy_emails",	(char *)pFilter->GetBuddyEmailAddresses());
	Bind(":oldname",		(char *)pName);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAUpdateFilterByName);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAUpdateFilterByName);
		SetStatement(NULL);
		updated = true;
	}

	return updated;
}

//
// GetFilter(ById)
//

static char *SQL_GetFilterById =
"select name, "
"		pattern, "
"		flag_item, "
"		action_type, "
"		notify_type, "
"		blocked_msg_id, "
"		flagged_msg_id, "
"		filter_msg_id, "
"		buddy_msg_id, "
"		filter_emails, "
"		buddy_emails "
"	from ebay_filters "
"	where id = :id";

clsFilter *	clsDatabaseOracle::GetFilter(FilterId id)
{
	char			cName[64];
	char *			pName;

	char			cExpression[255];
	char *			pExpression;

	char			cFlagItem[2];
	bool			flagItem;

	ActionType		action;
	NotifyType		notify;

	MessageId		blockedMsgId;

	MessageId		flaggedMsgId;
	sb2				flaggedMsgIdInd = 0;

	MessageId		filteringEmailMsgId;
	sb2				filteringEmailMsgIdInd = 0;

	MessageId		buddyEmailMsgId;
	sb2				buddyEmailMsgIdInd = 0;

	char			cFilteringEmails[255];
	sb2				filteringEmailsInd = 0;
	char *			pFilteringEmails;

	char			cBuddyEmails[255];
	sb2				buddyEmailsInd = 0;
	char *			pBuddyEmails;

	clsFilter *		pFilter = NULL;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAGetFilterById, SQL_GetFilterById);

	// Bind that input variable
	Bind(":id", &id);

	// Bind the output
	Define(1, cName, sizeof(cName));
	Define(2, cExpression, sizeof(cExpression));
	Define(3, cFlagItem, sizeof(cFlagItem));
	Define(4, (int *)&action);
	Define(5, (int *)&notify);
	Define(6, (int *)&blockedMsgId);
	Define(7, (int *)&flaggedMsgId, &flaggedMsgIdInd);
	Define(8, (int *)&filteringEmailMsgId, &filteringEmailMsgIdInd);
	Define(9, (int *)&buddyEmailMsgId, &buddyEmailMsgIdInd);
	Define(10, cFilteringEmails, sizeof(cFilteringEmails), &filteringEmailsInd);
	Define(11, cBuddyEmails, sizeof(cBuddyEmails), &buddyEmailsInd);

	// Let's get it.
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetFilterById);
		SetStatement(NULL);		
	}
	else
	{
		pName = new char[strlen(cName) + 1];
		strcpy(pName, cName);

		pExpression = new char[strlen(cExpression) + 1];
		strcpy(pExpression, cExpression);

		if (cFlagItem[0] == '1')
			flagItem = true;
		else
			flagItem = false;

		if (flaggedMsgIdInd == -1)
			flaggedMsgId = 0;

		if (filteringEmailMsgIdInd == -1)
			filteringEmailMsgId = 0;

		if (buddyEmailMsgIdInd == -1)
			buddyEmailMsgId = 0;

		if (filteringEmailsInd == -1)
			pFilteringEmails = NULL;
		else
		{
			pFilteringEmails = new char[strlen(cFilteringEmails) + 1];
			strcpy(pFilteringEmails, cFilteringEmails);
		}

		if (buddyEmailsInd == -1)
			pBuddyEmails = NULL;
		else
		{
			pBuddyEmails = new char[strlen(cBuddyEmails) + 1];
			strcpy(pBuddyEmails, cBuddyEmails);
		}

		pFilter = new clsFilter((FilterId)id,
								pName,
								pExpression,
								flagItem,
								(ActionType)action,
								(NotifyType)notify,
								(MessageId)blockedMsgId,
								(MessageId)flaggedMsgId,
								(MessageId)filteringEmailMsgId,
								(MessageId)buddyEmailMsgId,
								pFilteringEmails,
								pBuddyEmails);
			
		Close(&mpCDAGetFilterById);
		SetStatement(NULL);
	}

	return pFilter;
}

//
// GetFilter(ByName)
//

static char *SQL_GetFilterByName =
"select id, "
"		pattern, "
"		flag_item, "
"		action_type, "
"		notify_type, "
"		blocked_msg_id, "
"		flagged_msg_id, "
"		filter_msg_id, "
"		buddy_msg_id, "
"		filter_emails, "
"		buddy_emails "
"	from ebay_filters "
"	where name = :name";

clsFilter *	clsDatabaseOracle::GetFilter(const char *pName)
{
	FilterId	id;

	char		cExpression[255];
	char *		pExpression = NULL;

	char		cFlagItem[2];
	bool		flagItem;

	ActionType	action;
	NotifyType	notify;

	MessageId	blockedMsgId;
	MessageId	flaggedMsgId;
	sb2			flaggedMsgIdInd = 0;
	MessageId	filteringEmailMsgId;
	sb2			filteringEmailMsgIdInd = 0;
	MessageId	buddyEmailMsgId;
	sb2			buddyEmailMsgIdInd = 0;

	char		cFilteringEmails[255];
	sb2			filteringEmailsInd = 0;
	char *		pFilteringEmails = NULL;

	char		cBuddyEmails[255];
	sb2			buddyEmailsInd = 0;
	char *		pBuddyEmails = NULL;

	clsFilter *	pFilter = NULL;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAGetFilterByName, SQL_GetFilterByName);

	// Bind that input variable
	Bind(":name", (char *)pName);

	// Bind the output
	Define(1, (int *)&id);
	Define(2, cExpression, sizeof(cExpression));
	Define(3, cFlagItem, sizeof(cFlagItem));
	Define(4, (int *)&action);
	Define(5, (int *)&notify);
	Define(6, (int *)&blockedMsgId);
	Define(7, (int *)&flaggedMsgId, &flaggedMsgIdInd);
	Define(8, (int *)&filteringEmailMsgId, &filteringEmailMsgIdInd);
	Define(9, (int *)&buddyEmailMsgId, &buddyEmailMsgIdInd);
	Define(10, cFilteringEmails, sizeof(cFilteringEmails), &filteringEmailsInd);
	Define(11, cBuddyEmails, sizeof(cBuddyEmails), &buddyEmailsInd);

	// Let's get it.
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetFilterByName);
		SetStatement(NULL);		
	}
	else
	{
		pExpression = new char[strlen(cExpression) + 1];
		strcpy(pExpression, cExpression);

		if (cFlagItem[0] == '1')
			flagItem = true;
		else
			flagItem = false;

		if (flaggedMsgIdInd == -1)
			flaggedMsgId = 0;

		if (filteringEmailMsgIdInd == -1)
			filteringEmailMsgId = 0;

		if (buddyEmailMsgIdInd == -1)
			buddyEmailMsgId = 0;

		if (filteringEmailsInd == -1)
			pFilteringEmails = NULL;

		if (buddyEmailsInd == -1)
			pBuddyEmails = NULL;

		pFilter = new clsFilter((FilterId)id,
								pName,
								pExpression,
								flagItem,
								(ActionType)action,
								(NotifyType)notify,
								(MessageId)blockedMsgId,
								(MessageId)flaggedMsgId,
								(MessageId)filteringEmailMsgId,
								(MessageId)buddyEmailMsgId,
								pFilteringEmails,
								pBuddyEmails);
		
		Close(&mpCDAGetFilterByName);
		SetStatement(NULL);
	}

	return pFilter;
}


//
// GetFilters
//

#define ORA_FILTER_ARRAYSIZE	100

static char *SQL_GetFiltersByCategoryId =
"select	filters.id, "
"		filters.name, "
"		filters.pattern, "
"		filters.flag_item, "
"		filters.action_type, "
"		filters.notify_type, "
"		filters.blocked_msg_id, "
"		filters.flagged_msg_id, "
"		filters.filter_msg_id, "
"		filters.buddy_msg_id, "
"		filters.filter_emails, "
"		filters.buddy_emails "
"	from ebay_filters filters, ebay_category_filters catfilters "
"	where	catfilters.category_id = :cat_id "
"	and		catfilters.filter_id = filters.id (+) ";

void clsDatabaseOracle::GetFilters(CategoryId categoryId,
								   FilterVector *pvFilters)
{
	FilterId		id[ORA_FILTER_ARRAYSIZE];

	char			cName[ORA_FILTER_ARRAYSIZE][64];
	char *			pName = NULL;

	char			cExpression[ORA_FILTER_ARRAYSIZE][255];
	char *			pExpression = NULL;

	char			cFlagItem[ORA_FILTER_ARRAYSIZE][2];
	bool			flagItem = false;

	ActionType		action[ORA_FILTER_ARRAYSIZE];
	NotifyType		notify[ORA_FILTER_ARRAYSIZE];

	MessageId		blockedMsgId[ORA_FILTER_ARRAYSIZE];

	MessageId		flaggedMsgId[ORA_FILTER_ARRAYSIZE];
	sb2				flaggedMsgIdInd[ORA_FILTER_ARRAYSIZE];

	MessageId		filteringEmailMsgId[ORA_FILTER_ARRAYSIZE];
	sb2				filteringEmailMsgIdInd[ORA_FILTER_ARRAYSIZE];

	MessageId		buddyEmailMsgId[ORA_FILTER_ARRAYSIZE];
	sb2				buddyEmailMsgIdInd[ORA_FILTER_ARRAYSIZE];

	char			cFilteringEmails[ORA_FILTER_ARRAYSIZE][255];
	sb2				filteringEmailsInd[ORA_FILTER_ARRAYSIZE];
	char *			pFilteringEmails = NULL;

	char			cBuddyEmails[ORA_FILTER_ARRAYSIZE][255];
	sb2				buddyEmailsInd[ORA_FILTER_ARRAYSIZE];
	char *			pBuddyEmails = NULL;

	int				rowsFetched;
	int				rc;
	int				i, n;

	clsFilter *		pFilter = NULL;

	OpenAndParse(&mpCDAGetFiltersByCategoryId, SQL_GetFiltersByCategoryId);

	// Bind that input variable
	Bind(":cat_id", (int *)&categoryId);

	// Bind the output
	Define(1, (int *)&id[0]);
	Define(2, cName[0], sizeof(cName[0]));
	Define(3, cExpression[0], sizeof(cExpression[0]));
	Define(4, cFlagItem[0], sizeof(cFlagItem[0]));
	Define(5, (int *)&action[0]);
	Define(6, (int *)&notify[0]);
	Define(7, (int *)&blockedMsgId[0]);
	Define(8, (int *)&flaggedMsgId[0], flaggedMsgIdInd);
	Define(9, (int *)&filteringEmailMsgId[0], filteringEmailMsgIdInd);
	Define(10, (int *)&buddyEmailMsgId[0], buddyEmailMsgIdInd);
	Define(11, cFilteringEmails[0], sizeof(cFilteringEmails[0]), filteringEmailsInd);
	Define(12, cBuddyEmails[0], sizeof(cBuddyEmails[0]), buddyEmailsInd);

	// Let's get it.
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetFiltersByCategoryId, true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_FILTER_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetFiltersByCategoryId, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FILTER_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's make a copy of the filter
			// expression

			pName = new char[strlen(cName[i]) + 1];
			strcpy(pName, cName[i]);

			pExpression = new char[strlen(cExpression[i]) + 1];
			strcpy(pExpression, cExpression[i]);

			if (cFlagItem[i][0] == '1')
				flagItem = true;
			else
				flagItem = false;

			if (flaggedMsgIdInd[i] == -1)
				flaggedMsgId[i] = 0;

			if (filteringEmailMsgIdInd[i] == -1)
				filteringEmailMsgId[i] = 0;

			if (buddyEmailMsgIdInd[i] == -1)
				buddyEmailMsgId[i] = 0;

			if (filteringEmailsInd[i] == -1)
				pFilteringEmails = NULL;
			else
			{
				pFilteringEmails = new char[strlen(cFilteringEmails[i]) + 1];
				strcpy(pFilteringEmails, cFilteringEmails[i]);
			}

			if (buddyEmailsInd[i] == -1)
				pBuddyEmails = NULL;
			else
			{
				pBuddyEmails = new char[strlen(cBuddyEmails[i]) + 1];
				strcpy(pBuddyEmails, cBuddyEmails[i]);
			}

			pFilter = new clsFilter(id[i],
									pName,
									pExpression,
									flagItem,
									action[i],
									notify[i],
									blockedMsgId[i],
									flaggedMsgId[i],
									filteringEmailMsgId[i],
									buddyEmailMsgId[i],
									pFilteringEmails,
									pBuddyEmails);
			
			// set filtering/buddy emails

			pvFilters->push_back(pFilter);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetFiltersByCategoryId);
	SetStatement(NULL);

	return;
}


//
// GetFilters
//

#define ORA_FILTER_ARRAYSIZE	100

static char *SQL_GetAllFilters =
"select	id, "
"		name, "
"		pattern, "
"		flag_item, "
"		action_type, "
"		notify_type, "
"		blocked_msg_id, "
"		flagged_msg_id, "
"		filter_msg_id, "
"		buddy_msg_id, "
"		filter_emails, "
"		buddy_emails "
"	from ebay_filters";

void clsDatabaseOracle::GetAllFilters(FilterVector *pvFilters)
{
	FilterId		id[ORA_FILTER_ARRAYSIZE];

	char			cName[ORA_FILTER_ARRAYSIZE][64];
	char *			pName = NULL;

	char			cExpression[ORA_FILTER_ARRAYSIZE][255];
	char *			pExpression = NULL;

	char			cFlagItem[ORA_FILTER_ARRAYSIZE][2];
	bool			flagItem = false;

	ActionType		action[ORA_FILTER_ARRAYSIZE];
	NotifyType		notify[ORA_FILTER_ARRAYSIZE];

	MessageId		blockedMsgId[ORA_FILTER_ARRAYSIZE];

	MessageId		flaggedMsgId[ORA_FILTER_ARRAYSIZE];
	sb2				flaggedMsgIdInd[ORA_FILTER_ARRAYSIZE];

	MessageId		filteringEmailMsgId[ORA_FILTER_ARRAYSIZE];
	sb2				filteringEmailMsgIdInd[ORA_FILTER_ARRAYSIZE];

	MessageId		buddyEmailMsgId[ORA_FILTER_ARRAYSIZE];
	sb2				buddyEmailMsgIdInd[ORA_FILTER_ARRAYSIZE];

	char			cFilteringEmails[ORA_FILTER_ARRAYSIZE][255];
	sb2				filteringEmailsInd[ORA_FILTER_ARRAYSIZE];
	char *			pFilteringEmails = NULL;

	char			cBuddyEmails[ORA_FILTER_ARRAYSIZE][255];
	sb2				buddyEmailsInd[ORA_FILTER_ARRAYSIZE];
	char *			pBuddyEmails = NULL;

	int				rowsFetched;
	int				rc;
	int				i, n;

	clsFilter *		pFilter = NULL;

	OpenAndParse(&mpCDAGetAllFilters, SQL_GetAllFilters);

	// Bind the output
	Define(1, (int *)&id[0]);
	Define(2, cName[0], sizeof(cName[0]));
	Define(3, cExpression[0], sizeof(cExpression[0]));
	Define(4, cFlagItem[0], sizeof(cFlagItem[0]));
	Define(5, (int *)&action[0]);
	Define(6, (int *)&notify[0]);
	Define(7, (int *)&blockedMsgId[0]);
	Define(8, (int *)&flaggedMsgId[0], flaggedMsgIdInd);
	Define(9, (int *)&filteringEmailMsgId[0], filteringEmailMsgIdInd);
	Define(10, (int *)&buddyEmailMsgId[0], buddyEmailMsgIdInd);
	Define(11, cFilteringEmails[0], sizeof(cFilteringEmails[0]), filteringEmailsInd);
	Define(12, cBuddyEmails[0], sizeof(cBuddyEmails[0]), buddyEmailsInd);

	// Let's get it.
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetAllFilters, true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_FILTER_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetAllFilters, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FILTER_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's make a copy of the filter
			// expression

			pName = new char[strlen(cName[i]) + 1];
			strcpy(pName, cName[i]);

			pExpression = new char[strlen(cExpression[i]) + 1];
			strcpy(pExpression, cExpression[i]);

			if (cFlagItem[i][0] == '1')
				flagItem = true;
			else
				flagItem = false;

			if (flaggedMsgIdInd[i] == -1)
				flaggedMsgId[i] = 0;

			if (filteringEmailMsgIdInd[i] == -1)
				filteringEmailMsgId[i] = 0;

			if (buddyEmailMsgIdInd[i] == -1)
				buddyEmailMsgId[i] = 0;

			if (filteringEmailsInd[i] == -1)
				pFilteringEmails = NULL;
			else
			{
				pFilteringEmails = new char[strlen(cFilteringEmails[i]) + 1];
				strcpy(pFilteringEmails, cFilteringEmails[i]);
			}

			if (buddyEmailsInd[i] == -1)
				pBuddyEmails = NULL;
			else
			{
				pBuddyEmails = new char[strlen(cBuddyEmails[i]) + 1];
				strcpy(pBuddyEmails, cBuddyEmails[i]);
			}

			pFilter = new clsFilter(id[i],
									pName,
									pExpression,
									flagItem,
									action[i],
									notify[i],
									blockedMsgId[i],
									flaggedMsgId[i],
									filteringEmailMsgId[i],
									buddyEmailMsgId[i],
									pFilteringEmails,
									pBuddyEmails);
			
			// set filtering/buddy emails

			pvFilters->push_back(pFilter);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllFilters);
	SetStatement(NULL);

	return;
}


//
// GetNextFilterId
//
// Retrieves the next availible filter id. Whether
// this is done with a sequence, or a column in
// a table is irrelevant
//
static const char *SQL_GetNextFilterId =
 "select ebay_filters_sequence.nextval from dual";

FilterId clsDatabaseOracle::GetNextFilterId()
{
	FilterId	nextId;

	// Not used often, so we don't need a persistent
	// cursor
	OpenAndParse(&mpCDAGetNextFilterId, SQL_GetNextFilterId);
	Define(1, (int *)&nextId);

	// Execute
	ExecuteAndFetch();

	// Close and Clean
	Close(&mpCDAGetNextFilterId);
	SetStatement(NULL);

	return nextId;
}


//
// GetMaxFilterId
//
static const char *SQL_GetMaxFilterId =
"select	MAX(id) "
"	from	ebay_filters";

FilterId clsDatabaseOracle::GetMaxFilterId()
{
	FilterId	id = 0;

	OpenAndParse(&mpCDAOneShot, SQL_GetMaxFilterId);

	Define(1, (int *)&id);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return id;

}

