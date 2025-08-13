/*	$Id: clsDatabaseOracleFilterMessages.cpp,v 1.2 1999/05/19 02:34:52 josh Exp $	*/
//	File:		clsDatabaseOracleFilterMessages.cpp
//
// Class:	clsDatabaseOracle
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Methods to access information in ebay_filter_messages table
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsFilterMessages.h"

//-------------------------------------------------------------------------------------
// Filter messages
//-------------------------------------------------------------------------------------

//
// AddMinFilterMessage
//

static char *SQL_AddMinFilterMessage =
"insert into ebay_filter_messages "
"	( "
"		filter_id, "
"		message_id, "
"		message_type "
"	) "
"	values "
"	( "
"		:cat_id, "
"		:msg_id, "
"		:msg_type "
"	)";

bool clsDatabaseOracle::AddMinFilterMessage(clsMinFilterMessage *pMinFilterMessage)
{
	int		filterId;
	int		messageId;
	int		messageType;

	bool	success = false;

	if (pMinFilterMessage == NULL)
		return false;

	filterId = (int)pMinFilterMessage->GetFilterId();
	messageId = (int)pMinFilterMessage->GetMessageId();
	messageType = (int)pMinFilterMessage->GetMessageType();

	OpenAndParse(&mpCDAAddMinFilterMessage, SQL_AddMinFilterMessage);

	Bind(":cat_id", &filterId);
	Bind(":msg_id", &messageId);
	Bind(":msg_type", &messageType);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAAddMinFilterMessage);
	SetStatement(NULL);

	return success;
}


//
// DeleteMinFilterMessage
//

static char *SQL_DeleteMinFilterMessage =
"delete from ebay_filter_messages "
"	where	filter_id = :cat_id "
"	and		message_id = :msg_id "
"	and		message_type = :msg_type ";

void clsDatabaseOracle::DeleteMinFilterMessage(FilterId filterId,
											   MessageId messageId,
											   MessageType messageType)
{
	OpenAndParse(&mpCDADeleteMinFilterMessage, SQL_DeleteMinFilterMessage);

	Bind(":cat_id", (int *)&filterId);
	Bind(":msg_id", (int *)&messageId);
	Bind(":msg_type", (int *)&messageType);

	Execute();
	Commit();

	Close(&mpCDADeleteMinFilterMessage);
	SetStatement(NULL);

	return;
}


//
// UpdateMinFilterMessage
//

static char *SQL_UpdateMinFilterMessage =
"update ebay_filter_messages "
"	set		filter_id = :new_cat_id, "
"			message_id = :new_msg_id, "
"			message_type = :new_msg_type "
"	where	filter_id = :cat_id "
"	and		message_id = :msg_id "
"	and		message_type = :msg_type";

bool clsDatabaseOracle::UpdateMinFilterMessage(FilterId filterId,
											   MessageId messageId,
											   MessageType messageType,
											   clsMinFilterMessage *pMinFilterMessage)
{
	bool		success = false;

	FilterId	newCatId;
	MessageId	newMsgId;
	MessageType	newMsgType;

	if (pMinFilterMessage == NULL)
		return false;

	newCatId = pMinFilterMessage->GetFilterId();
	newMsgId = pMinFilterMessage->GetMessageId();
	newMsgType = pMinFilterMessage->GetMessageType();

	OpenAndParse(&mpCDAUpdateMinFilterMessage, SQL_UpdateMinFilterMessage);

	Bind(":new_cat_id", (int *)&newCatId);
	Bind(":new_msg_id", (int *)&newMsgId);
	Bind(":new_msg_type", (int *)&newMsgType);
	Bind(":cat_id", (int *)&filterId);
	Bind(":msg_id", (int *)&messageId);
	Bind(":msg_type", (int *)&messageType);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAUpdateMinFilterMessage);
	SetStatement(NULL);

	return success;
}


//
// GetFilterMessage
//

static char *SQL_GetMinFilterMessage =
"select	filter_id, "
"		message_id, "
"		message_type "
"	from ebay_filter_messages "
"	where	filter_id = :cat_id "
"	and		message_id = :msg_id "
"	and		message_type = :msg_type";

static char *SQL_GetMinFilterMessageNoMsgIdQuery =
"select	filter_id, "
"		message_id, "
"		message_type "
"	from ebay_filter_messages "
"	where	filter_id = :cat_id "
"	and		message_type = :msg_type";

clsMinFilterMessage * clsDatabaseOracle::GetMinFilterMessage(FilterId filterId,
															 MessageId messageId,
															 MessageType messageType)
{
	int		catId = 0;
	int		msgId = 0;
	int		msgType = 0;

	clsMinFilterMessage *pMinFilterMessage = NULL;

	// copy from clsDatabaseOracle::GetItem

	if (messageId == 0)
		OpenAndParse(&mpCDAGetMinFilterMessage, SQL_GetMinFilterMessageNoMsgIdQuery);
	else
		OpenAndParse(&mpCDAGetMinFilterMessage, SQL_GetMinFilterMessage);

	Bind(":cat_id", (int *)&filterId);
	if (messageId > 0)
		Bind(":msg_id", (int *)&messageId);
	Bind(":msg_type", (int *)&messageType);

	Define(1, &catId);
	Define(2, &msgId);
	Define(3, &msgType);

	ExecuteAndFetch();

	if (!CheckForNoRowsFound())
		pMinFilterMessage = new clsMinFilterMessage((FilterId)catId,
													(MessageId)msgId,
													(MessageType)msgType);

	Close(&mpCDAGetMinFilterMessage);
	SetStatement(NULL);

	return pMinFilterMessage;
}


#define ORA_CATMSG_ARRAYSIZE	 100

//
// GetMinFilterMessagesByFilterId
//

static char *SQL_GetMinFilterMessagesByFilterId =
"select	message_id, "
"		message_type "
"	from ebay_filter_messages "
"	where	filter_id = :cat_id";

bool clsDatabaseOracle::GetMinFilterMessagesByFilterId(FilterId filterId,
										MinFilterMessageVector *pvMinFilterMessages)
{
	int		messageId[ORA_CATMSG_ARRAYSIZE];
	int		messageType[ORA_CATMSG_ARRAYSIZE];
	int		rowsFetched = 0;
	int		i, n;
	int		rc = 0;

	clsMinFilterMessage *pMinFilterMessage = NULL;

	OpenAndParse(&mpCDAGetMinFilterMessagesByFilterId,
				 SQL_GetMinFilterMessagesByFilterId);

	Bind(":cat_id", (int *)&filterId);

	Define(1, &messageId[0]);
	Define(2, &messageType[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetMinFilterMessagesByFilterId, true);
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
			Close(&mpCDAGetMinFilterMessagesByFilterId, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATMSG_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pMinFilterMessage = new clsMinFilterMessage(filterId,
														(MessageId)messageId[i],
														(MessageType)messageType[i]);
			pvMinFilterMessages->push_back(pMinFilterMessage);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetMinFilterMessagesByFilterId);
	SetStatement(NULL);

	return true;
}


//
// GetMinFilterMessageCountByFilterId
//

static char *SQL_GetMinFilterMessageCountByFilterId =
"select	count (*) "
"	from ebay_filter_messages "
"	where	filter_id = :cat_id";

unsigned int clsDatabaseOracle::GetMinFilterMessageCountByFilterId(FilterId filterId)
{
	int	count = 0;

	OpenAndParse(&mpCDAGetMinFilterMessageCountByFilterId,
				 SQL_GetMinFilterMessageCountByFilterId);

	Bind(":cat_id", (int *)&filterId);

	Define(1, &count);

	ExecuteAndFetch();

//	if (CheckForNowRowsFound())
//		count = 0;

	Close(&mpCDAGetMinFilterMessageCountByFilterId);
	SetStatement(NULL);

	return (unsigned int)count;
}


//
// GetMinFilterMessagesByMessageId
//

static char *SQL_GetMinFilterMessagesByMessageId =
"select	filter_id, "
"		message_type "
"	from ebay_filter_messages "
"	where	message_id = :msg_id";


bool clsDatabaseOracle::GetMinFilterMessagesByMessageId(MessageId messageId,
														MinFilterMessageVector *pvMinFilterMessages)
{
	int		filterId[ORA_CATMSG_ARRAYSIZE];
	int		messageType[ORA_CATMSG_ARRAYSIZE];
	int		rowsFetched = 0;
	int		i, n;
	int		rc = 0;

	clsMinFilterMessage *pMinFilterMessage = NULL;

	OpenAndParse(&mpCDAGetMinFilterMessagesByMessageId,
				 SQL_GetMinFilterMessagesByMessageId);

	Bind(":msg_id", (int *)&messageId);

	Define(1, &filterId[0]);
	Define(2, &messageType[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetMinFilterMessagesByMessageId, true);
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
			Close(&mpCDAGetMinFilterMessagesByMessageId, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATMSG_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pMinFilterMessage = new clsMinFilterMessage((FilterId)filterId[i],
														messageId,
														(MessageType)messageType[i]);
			pvMinFilterMessages->push_back(pMinFilterMessage);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetMinFilterMessagesByMessageId);
	SetStatement(NULL);

	return true;
}


//
// GetMinFilterMessageCountByMessageId
//

static char *SQL_GetMinFilterMessageCountByMessageId =
"select	count (*) "
"	from ebay_filter_messages "
"	where	message_id = :msg_id";

unsigned int clsDatabaseOracle::GetMinFilterMessageCountByMessageId(MessageId messageId)
{
	int	count = 0;

	OpenAndParse(&mpCDAGetMinFilterMessageCountByMessageId,
				 SQL_GetMinFilterMessageCountByMessageId);

	Bind(":msg_id", (int *)&messageId);

	Define(1, &count);

	ExecuteAndFetch();

//	if (CheckForNowRowsFound())
//		count = 0;

	Close(&mpCDAGetMinFilterMessageCountByMessageId);
	SetStatement(NULL);

	return (unsigned int)count;
}

