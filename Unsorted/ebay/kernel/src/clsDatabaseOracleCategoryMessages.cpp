/*	$Id: clsDatabaseOracleCategoryMessages.cpp,v 1.2 1999/05/19 02:34:52 josh Exp $	*/
//	File:		clsDatabaseOracleCategoryMessages.cpp
//
// Class:	clsDatabaseOracle
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Methods to access information in ebay_category_messages table
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsCategoryMessages.h"

//-------------------------------------------------------------------------------------
// Category messages
//-------------------------------------------------------------------------------------

//
// AddCategoryMessage
//

static char *SQL_AddCategoryMessage =
"insert into ebay_category_messages "
"	( "
"		category_id, "
"		message_id "
"	) "
"	values "
"	( "
"		:cat_id, "
"		:msg_id "
"	)";

bool clsDatabaseOracle::AddCategoryMessage(clsCategoryMessage *pCategoryMessage)
{
	int		categoryId;
	int		messageId;

	bool	success = false;

	if (pCategoryMessage == NULL)
		return false;

	categoryId = (int)pCategoryMessage->GetCategoryId();
	messageId = (int)pCategoryMessage->GetMessageId();

	OpenAndParse(&mpCDAAddCategoryMessage, SQL_AddCategoryMessage);

	Bind(":cat_id", &categoryId);
	Bind(":msg_id", &messageId);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAAddCategoryMessage);
	SetStatement(NULL);

	return success;
}


//
// DeleteCategoryMessage
//

static char *SQL_DeleteCategoryMessage =
"delete from ebay_category_messages "
"	where	category_id = :cat_id "
"	and		message_id = :msg_id ";

void clsDatabaseOracle::DeleteCategoryMessage(CategoryId categoryId,
											  MessageId messageId)
{
	OpenAndParse(&mpCDADeleteCategoryMessage, SQL_DeleteCategoryMessage);

	Bind(":cat_id", (int *)&categoryId);
	Bind(":msg_id", (int *)&messageId);

	Execute();
	Commit();

	Close(&mpCDADeleteCategoryMessage);
	SetStatement(NULL);

	return;
}


//
// UpdateCategoryMessage
//

static char *SQL_UpdateCategoryMessage =
"update ebay_category_messages "
"	set		category_id = :new_cat_id, "
"			message_id = :new_msg_id "
"	where	category_id = :cat_id "
"	and		message_id = :msg_id ";

bool clsDatabaseOracle::UpdateCategoryMessage(CategoryId categoryId,
											  MessageId messageId,
											  clsCategoryMessage *pCategoryMessage)
{
	bool		success = false;

	CategoryId	newCatId;
	MessageId	newMsgId;

	if (pCategoryMessage == NULL)
		return false;

	newCatId = pCategoryMessage->GetCategoryId();
	newMsgId = pCategoryMessage->GetMessageId();

	OpenAndParse(&mpCDAUpdateCategoryMessage, SQL_UpdateCategoryMessage);

	Bind(":new_cat_id", (int *)&newCatId);
	Bind(":new_msg_id", (int *)&newMsgId);
	Bind(":cat_id", (int *)&categoryId);
	Bind(":msg_id", (int *)&messageId);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAUpdateCategoryMessage);
	SetStatement(NULL);

	return success;
}


//
// GetCategoryMessage
//

static char *SQL_GetCategoryMessage =
"select	category_id, "
"		message_id "
"	from ebay_category_messages "
"	where	category_id = :cat_id "
"	and		message_id = :msg_id ";

clsCategoryMessage * clsDatabaseOracle::GetCategoryMessage(CategoryId categoryId,
														   MessageId messageId)
{
	int		catId = 0;
	int		msgId = 0;

	clsCategoryMessage *pCategoryMessage = NULL;

	OpenAndParse(&mpCDAGetCategoryMessage, SQL_GetCategoryMessage);

	Bind(":cat_id", (int *)&categoryId);
	Bind(":msg_id", (int *)&messageId);

	Define(1, &catId);
	Define(2, &msgId);

	ExecuteAndFetch();

	if (!CheckForNoRowsFound())
		pCategoryMessage = new clsCategoryMessage((CategoryId)catId,
												  (MessageId)msgId);

	Close(&mpCDAGetCategoryMessage);
	SetStatement(NULL);

	return pCategoryMessage;
}


#define ORA_CATMSG_ARRAYSIZE	 100

//
// GetCategoryMessagesByCategoryId
//

static char *SQL_GetCategoryMessagesByCategoryId =
"select	message_id "
"	from ebay_category_messages "
"	where	category_id = :cat_id";

bool clsDatabaseOracle::GetCategoryMessagesByCategoryId(CategoryId categoryId,
										CategoryMessageVector *pvCategoryMessages)
{
	int		messageId[ORA_CATMSG_ARRAYSIZE];
	int		rowsFetched = 0;
	int		i, n;
	int		rc = 0;

	clsCategoryMessage *pCategoryMessage = NULL;

	OpenAndParse(&mpCDAGetCategoryMessagesByCategoryId,
				 SQL_GetCategoryMessagesByCategoryId);

	Bind(":cat_id", (int *)&categoryId);

	Define(1, &messageId[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCategoryMessagesByCategoryId, true);
		SetStatement(NULL);
		return false;
	}

	// do array fetch...
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATMSG_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCategoryMessagesByCategoryId, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATMSG_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pCategoryMessage = new clsCategoryMessage(categoryId,
													  (MessageId)messageId[i]);
			pvCategoryMessages->push_back(pCategoryMessage);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetCategoryMessagesByCategoryId);
	SetStatement(NULL);

	return true;
}


//
// GetCategoryMessageCountByCategoryId
//

static char *SQL_GetCategoryMessageCountByCategoryId =
"select	count (*) "
"	from ebay_category_messages "
"	where	category_id = :cat_id";

unsigned int clsDatabaseOracle::GetCategoryMessageCountByCategoryId(CategoryId categoryId)
{
	int	count = 0;

	OpenAndParse(&mpCDAGetCategoryMessageCountByCategoryId,
				 SQL_GetCategoryMessageCountByCategoryId);

	Bind(":cat_id", (int *)&categoryId);

	Define(1, &count);

	ExecuteAndFetch();

//	if (CheckForNowRowsFound())
//		count = 0;

	Close(&mpCDAGetCategoryMessageCountByCategoryId);
	SetStatement(NULL);

	return (unsigned int)count;
}


//
// GetCategoryMessagesByMessageId
//

static char *SQL_GetCategoryMessagesByMessageId =
"select	category_id "
"	from ebay_category_messages "
"	where	message_id = :msg_id";


bool clsDatabaseOracle::GetCategoryMessagesByMessageId(MessageId messageId,
													   CategoryMessageVector *pvCategoryMessages)
{
	int		categoryId[ORA_CATMSG_ARRAYSIZE];
	int		rowsFetched = 0;
	int		i, n;
	int		rc = 0;

	clsCategoryMessage *pCategoryMessage = NULL;

	OpenAndParse(&mpCDAGetCategoryMessagesByMessageId,
				 SQL_GetCategoryMessagesByMessageId);

	Bind(":msg_id", (int *)&messageId);

	Define(1, &categoryId[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCategoryMessagesByMessageId, true);
		SetStatement(NULL);
		return false;
	}

	// do array fetch...
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATMSG_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCategoryMessagesByMessageId, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATMSG_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pCategoryMessage = new clsCategoryMessage(categoryId[i],
													  (MessageId)messageId);
			pvCategoryMessages->push_back(pCategoryMessage);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetCategoryMessagesByMessageId);
	SetStatement(NULL);

	return true;
}


//
// GetCategoryMessageCountByMessageId
//

static char *SQL_GetCategoryMessageCountByMessageId =
"select	count (*) "
"	from ebay_category_messages "
"	where	message_id = :msg_id";

unsigned int clsDatabaseOracle::GetCategoryMessageCountByMessageId(MessageId messageId)
{
	int	count = 0;

	OpenAndParse(&mpCDAGetCategoryMessageCountByMessageId,
				 SQL_GetCategoryMessageCountByMessageId);

	Bind(":msg_id", (int *)&messageId);

	Define(1, &count);

	ExecuteAndFetch();

//	if (CheckForNowRowsFound())
//		count = 0;

	Close(&mpCDAGetCategoryMessageCountByMessageId);
	SetStatement(NULL);

	return (unsigned int)count;
}


//
// GetAllCategoryMessages
//

static char *SQL_GetAllCategoryMessages =
"select	category_id, "
"		message_id "
"	from ebay_category_messages";

bool clsDatabaseOracle::GetAllCategoryMessages(
										CategoryMessageVector *pvCategoryMessages)
{
	CategoryId	categoryId[ORA_CATMSG_ARRAYSIZE];
	MessageId	messageId[ORA_CATMSG_ARRAYSIZE];
	int			rowsFetched = 0;
	int			i, n;
	int			rc = 0;

	clsCategoryMessage *pCategoryMessage = NULL;

	OpenAndParse(&mpCDAGetAllCategoryMessages,
				 SQL_GetAllCategoryMessages);

	Define(1, (int *)&categoryId[0]);
	Define(2, (int *)&messageId[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetAllCategoryMessages, true);
		SetStatement(NULL);
		return false;
	}

	// do array fetch...
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATMSG_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetAllCategoryMessages, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATMSG_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pCategoryMessage = new clsCategoryMessage(categoryId[i],
													  messageId[i]);
			pvCategoryMessages->push_back(pCategoryMessage);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllCategoryMessages);
	SetStatement(NULL);

	return true;
}


