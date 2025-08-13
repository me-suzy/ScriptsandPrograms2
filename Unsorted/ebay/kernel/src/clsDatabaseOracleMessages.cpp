/*	$Id: clsDatabaseOracleMessages.cpp,v 1.2 1999/05/19 02:34:54 josh Exp $	*/
//	File:	clsDatabaseOracleMessages.cpp
//
// Class:	clsDatabaseOracle
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Methods to access information in ebay_messages table
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsMessage.h"
#include "clsMessages.h"

//-------------------------------------------------------------------------------------
// Messages
//-------------------------------------------------------------------------------------

//
// AddMessage
//

static char *SQL_AddMessage =
"insert into ebay_messages "
"	( "
"		id, "
"		name, "
"		type, "
"		text, "
"		text_len "
"	) "
"	values "
"	( "
"		:id, "
"		:name, "
"		:type, "
"		:text, "
"		:text_len "
"	)";

bool clsDatabaseOracle::AddMessage(clsMessage *pMessage)
{
	MessageId		id;
	MessageType		type;
	unsigned int	textLen;

	// copy from clsDatabaseOracle::AddItemDesc

	if (pMessage == NULL)
		return false;

	id = pMessage->GetId();
	type = pMessage->GetMessageType();
	textLen = pMessage->GetTextLength();
	
	OpenAndParse(&mpCDAAddMessage, SQL_AddMessage);

	Bind(":id", (int *)&id);
	Bind(":name", pMessage->GetName());
	Bind(":type", (int *)&type);
	BindLongRaw(":text", 
				(unsigned char *)pMessage->GetText(),
				textLen);
	Bind(":text_len", (int *)&textLen);

	Execute();
	Commit();

	Close(&mpCDAAddMessage);
	SetStatement(NULL);

	return true;
}


//
// DeleteMessage(ById)
//

static char *SQL_DeleteMessageById =
"delete from ebay_messages "
"	where id = :id";

void clsDatabaseOracle::DeleteMessage(MessageId id)
{
	OpenAndParse(&mpCDADeleteMessageById, SQL_DeleteMessageById);

	Bind(":id", (int *)&id);

	Execute();
	Commit();

	Close(&mpCDADeleteMessageById);
	SetStatement(NULL);

	return;
}


//
// DeleteMessage(ByName)
//

static char *SQL_DeleteMessageByName =
"delete from ebay_messages "
"	where name = :name";

void clsDatabaseOracle::DeleteMessage(const char *pName)
{
	if (pName == NULL)
		return;

	OpenAndParse(&mpCDADeleteMessageByName, SQL_DeleteMessageByName);

	Bind(":name", (char *)pName);

	Execute();
	Commit();

	Close(&mpCDADeleteMessageByName);
	SetStatement(NULL);

	return;
}


//
// UpdateMessage(ById)
//

static char *SQL_UpdateMessageById =
"update ebay_messages "
"	set name = :name, "
"		type = :type, "
"		text = :text, "
"		text_len = :text_len "
"	where id = :id";

bool clsDatabaseOracle::UpdateMessage(MessageId id,
									  clsMessage *pMessage)
{
	bool	success = false;
	int		type;
	int		textLen;

	// copy from clsDatabaseOracle::UpdateItemDesc

	if (pMessage == NULL)
		return false;

	type = (int)pMessage->GetMessageType();
	textLen = (int)strlen(pMessage->GetText());
	
	OpenAndParse(&mpCDAUpdateMessageById, SQL_UpdateMessageById);

	Bind(":id", (int *)&id);
	Bind(":name", pMessage->GetName());
	Bind(":type", (int *)&type);
	BindLongRaw(":text", 
				(unsigned char *)pMessage->GetText(),
				textLen);
	Bind(":text_len", &textLen);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAUpdateMessageById);
	SetStatement(NULL);

	return success;
}


//
// UpdateMessage(ByName)
//

static char *SQL_UpdateMessageByName =
"update ebay_messages "
"	set "
"		id = :id, "
"		name = :newname, "
"		type = :type, "
"		text = :text, "
"		text_len = :text_len "
"	where name = :oldname";

bool clsDatabaseOracle::UpdateMessage(const char *pName,
									  clsMessage *pMessage)
{
	bool	success = false;
	int		id;
	int		type;
	int		textLen;

	// copy from clsDatabaseOracle::UpdateItemDesc

	if (pName == NULL || pMessage == NULL)
		return false;

	id = (int)pMessage->GetId();
	type = (int)pMessage->GetMessageType();
	textLen = (int)strlen(pMessage->GetText());
	
	OpenAndParse(&mpCDAUpdateMessageByName, SQL_UpdateMessageByName);

	Bind(":id", &id);
	Bind(":name", pMessage->GetName());
	Bind(":type", &type);
	BindLongRaw(":text", 
				(unsigned char *)pMessage->GetText(),
				textLen);
	Bind(":text_len", &textLen);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAUpdateMessageByName);
	SetStatement(NULL);

	return success;
}


//
// GetMessage(ById)
//

static char *SQL_GetMessageById =
"select id, "
"		name, "
"		type, "
"		text "
"	from ebay_messages "
"	where id = :id";

static char *SQL_GetMessageLengthById =
"select text_len "
"	from ebay_messages "
"	where id = :id";

clsMessage * clsDatabaseOracle::GetMessage(MessageId id)
{
	int					msgId;
	char				cName[64];
	int					type;
	unsigned char *		pText = NULL;
	int					textLen;

	clsMessage *pMessage = NULL;

	// Get the message text length first...
	OpenAndParse(&mpCDAGetMessageLengthById, SQL_GetMessageLengthById);

	Bind(":id", (int *)&id);

	Define(1, &textLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMessageLengthById);
		SetStatement(NULL);
		return NULL;
	}

	// Allocate memory for the message text
	pText = new unsigned char[textLen + 1];

	Close(&mpCDAGetMessageLengthById);
	SetStatement(NULL);

	// Now get everything else...
	OpenAndParse(&mpCDAGetMessageById, SQL_GetMessageById);

	Bind(":id", (int *)&id);

	Define(1, &msgId);
	Define(2, cName, sizeof(cName));
	Define(3, &type);
	DefineLongRaw(4, pText, textLen);

	ExecuteAndFetch();

	*(pText + textLen) = '\0';

	if (CheckForNoRowsFound())
	{		
		Close(&mpCDAGetMessageById);
		SetStatement(NULL);
		return NULL;
	}

	pMessage = new clsMessage((MessageId)msgId,
							  cName,
							  (MessageType)type,
							  (char *)pText,
							  (unsigned int)textLen);

	Close(&mpCDAGetMessageById);
	SetStatement(NULL);

	return pMessage;
}


//
// GetMessage(ByName)
//

static char *SQL_GetMessageByName =
"select id, "
"		name, "
"		type, "
"		text, "
"		text_len "
"	from ebay_messages "
"	where name = :name";

static char *SQL_GetMessageLengthByName =
"select text_len "
"	from ebay_messages "
"	where name = :name";

clsMessage * clsDatabaseOracle::GetMessage(const char *pName)
{
	MessageId		id;
	char			cName[64];
	MessageType		type;
	unsigned char *	pText = NULL;
	int				textLen;

	clsMessage *	pMessage = NULL;

	if (pName == NULL)
		return NULL;

	// Get the message text length first...
	OpenAndParse(&mpCDAGetMessageLengthByName, SQL_GetMessageLengthByName);

	Bind(":name", (char *)&pName);

	Define(1, &textLen);

	ExecuteAndFetch();

	// Allocate memory for the message text
	pText = new unsigned char[textLen + 1];

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMessageLengthByName);
		SetStatement(NULL);
		return NULL;
	}

	// Allocate memory for the message text
	pText = new unsigned char[textLen + 1];

	Close(&mpCDAGetMessageLengthByName);
	SetStatement(NULL);

	// Now get everything else...
	OpenAndParse(&mpCDAGetMessageByName, SQL_GetMessageByName);

	Bind(":name", pName);

	Define(1, (int *)&id);
	Define(2, cName, sizeof(cName));
	Define(3, (int *)&type);
	DefineLongRaw(4, pText, textLen);

	ExecuteAndFetch();

	*(pText + textLen) = '\0';

	if (CheckForNoRowsFound())
	{		
		Close(&mpCDAGetMessageByName);
		SetStatement(NULL);
		return NULL;
	}

	pMessage = new clsMessage((MessageId)id,
							  cName,
							  (MessageType)type,
							  (char *)pText,
							  (unsigned int)textLen);

	Close(&mpCDAGetMessageByName);
	SetStatement(NULL);

	return pMessage;
}


//
// GetMessage(ByName)
//

static char *SQL_GetMessageByCategoryIdAndMessageType =
"select messages.id, "
"		messages.name, "
"		messages.type, "
"		messages.text, "
"		messages.text_len "
"	from	ebay_messages messages, "
"			ebay_category_messages cat_messages "
"	where	messages.id = cat_messages.message_id (+) "
"	and		cat_messages.category_id = :cat_id "
"	and		cat_messages.message_type = :msg_type";

static char *SQL_GetMessageLengthByCategoryIdAndMessageType =
"select messages.text_len "
"	from	ebay_messages messages, "
"			ebay_category_messages cat_messages "
"	where	messages.id = cat_messages.message_id (+) "
"	and		cat_messages.category_id = :cat_id "
"	and		cat_messages.message_type = :msg_type";

clsMessage * clsDatabaseOracle::GetMessage(CategoryId categoryId,
										   MessageType messageType)
{
	MessageId		id;
	char			cName[64];
	MessageType		type;
	unsigned char *	pText = NULL;
	int				textLen;

	clsMessage *	pMessage = NULL;

	// Get the message text length first...
	OpenAndParse(&mpCDAGetMessageLengthByCategoryIdAndMessageType, SQL_GetMessageLengthByCategoryIdAndMessageType);

	Bind(":cat_id", (int *)&categoryId);
	Bind(":msg_type", (int *)&messageType);

	Define(1, &textLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMessageLengthByCategoryIdAndMessageType);
		SetStatement(NULL);
		return NULL;
	}

	// Allocate memory for the message text
	pText = new unsigned char[textLen + 1];

	Close(&mpCDAGetMessageLengthByCategoryIdAndMessageType);
	SetStatement(NULL);

	*(pText + textLen) = '\0';

	// Now get everything else...
	OpenAndParse(&mpCDAGetMessageByCategoryIdAndMessageType, SQL_GetMessageByCategoryIdAndMessageType);

	Bind(":cat_id", (int *)&categoryId);
	Bind(":msg_type", (int *)&messageType);

	Define(1, (int *)&id);
	Define(2, cName, sizeof(cName));
	Define(3, (int *)&type);
	DefineLongRaw(4, pText, textLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMessageByCategoryIdAndMessageType);
		SetStatement(NULL);
		return NULL;
	}

	pMessage = new clsMessage((MessageId)id,
							  cName,
							  (MessageType)type,
							  (char *)pText,
							  (unsigned int)textLen);

	Close(&mpCDAGetMessageByCategoryIdAndMessageType);
	SetStatement(NULL);

	return pMessage;
}

#define ORA_MESSAGE_ARRAYSIZE	100

//
// GetMessages
//

static const char *SQL_GetMaxTextLen =
"select MAX(text_len) "
"	from ebay_messages";

static const char *SQL_GetMaxTextLenByCategoryId =
"select MAX(text_len) "
"	from	ebay_messages messages, "
"			ebay_category_messages cat_messages "
"	where	messages.id = cat_messages.message_id (+) "
"	and		cat_messages.id = :category_id";

static const char *SQL_GetMaxTextLenByFilterId =
"select MAX(text_len) "
"	from	ebay_messages messages, "
"			ebay_filter_messages filter_messages "
"	where	messages.id = filter_messages.message_id (+) "
"	and		filter_messages.id = :filter_id";

static const char *SQL_GetMaxTextLenByMessageType =
"select MAX(text_len) "
"	from	ebay_messages "
"	where	type = :type";

static const char *SQL_GetMaxTextLenByCategoryIdAndMessageType =
"select MAX(text_len) "
"	from	ebay_messages messages, "
"			ebay_category_messages cat_messages "
"	where	messages.type = :type "
"	and		cat_messages.category_id = :cat_id "
"	and		messages.id = cat_messages.message_id (+)";

static char *SQL_GetAllMessages =
"select id, "
"		name, "
"		type, "
"		text, "
"		text_len "
"	from ebay_messages";

static char *SQL_GetMessagesByCategoryId =
"select messages.id, "
"		messages.name, "
"		messages.type, "
"		messages.text, "
"		messages.text_len "
"	from	ebay_messages messages, "
"			ebay_category_messages cat_messages "
"	where	messages.id = cat_messages.message_id (+) "
"	and		cat_messages.id = :category_id";

static char *SQL_GetMessagesByFilterId =
"select messages.id, "
"		messages.name, "
"		messages.type, "
"		messages.text, "
"		messages.text_len "
"	from	ebay_messages messages, "
"			ebay_filter_messages filter_messages "
"	where	messages.id = filter_messages.message_id (+) "
"	and		filter_messages.id = :filter_id";

static char *SQL_GetMessagesByMessageType =
"select id, "
"		name, "
"		type, "
"		text, "
"		text_len "
"	from	ebay_messages "
"	where	type = :type";

static char *SQL_GetMessagesByCategoryIdAndMessageType =
"select id, "
"		name, "
"		type, "
"		text, "
"		text_len "
"	from	ebay_messages messages, "
"			ebay_category_messages cat_messages "
"	where	messages.type = :type "
"	and		cat_messages.category_id = :cat_id "
"	and		messages.id = cat_messages.message_id (+)";

bool clsDatabaseOracle::GetMessages(MessageQueryType queryType,
									MessageVector *pvMessages,
									unsigned int value /* = 0 */)
{
	MessageId		id[ORA_MESSAGE_ARRAYSIZE];
	char			cName[ORA_MESSAGE_ARRAYSIZE][64];
	MessageType		type[ORA_MESSAGE_ARRAYSIZE];
	unsigned char	*pText = NULL;
	unsigned char	*pOriginalText = NULL;
	int				textLen[ORA_MESSAGE_ARRAYSIZE];
	int				i, n, rc;
	int				rowsFetched = 0;
	int				maxTextLen = 0;
	int				nTextBufferLen = 0;

	clsMessage *	pMessage = NULL;
	
	char *			pSQLStatement = NULL;
	unsigned char **ppCursor = NULL;


	if (pvMessages == NULL)
		return false;

	// Make sure we have a valid value to search on
	if (value == 0 &&
		(queryType == MessageQueryGetByCategoryId ||
		 queryType == MessageQueryGetByFilterId))
				return false;

	// Find out which query we're doing, and set the SQL statement
	// and cursor accordingly for getting the max text length
	switch (queryType)
	{
		case MessageQueryGetAll:
			pSQLStatement = (char *)SQL_GetMaxTextLen;
			ppCursor = &mpCDAGetMaxTextLen;
			break;

		case MessageQueryGetByCategoryId:
			pSQLStatement = (char *)SQL_GetMaxTextLenByCategoryId;
			ppCursor = &mpCDAGetMaxTextLenByCategoryId;
			break;

		case MessageQueryGetByFilterId:
			pSQLStatement = (char *)SQL_GetMaxTextLenByFilterId;
			ppCursor = &mpCDAGetMaxTextLenByFilterId;
			break;

		case MessageQueryGetByMessageType:
			pSQLStatement = (char *)SQL_GetMaxTextLenByMessageType;
			ppCursor = &mpCDAGetMaxTextLenByMessageType;
			break;

		case MessageQueryGetByCategoryIdAndMessageType:
			pSQLStatement = (char *)SQL_GetMaxTextLenByCategoryIdAndMessageType;
			ppCursor = &mpCDAGetMaxTextLenByCategoryIdAndMessageType;
			break;

		default:
			return false;
	}

	// Get the message text length first...
	OpenAndParse(ppCursor, (const char *)pSQLStatement);

	Define(1, &maxTextLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(ppCursor);
		SetStatement(NULL);
		return false;
	}

	// Calc buffer length, add 1 char for the NULL and then calc the total buffer size
	maxTextLen ++;
	nTextBufferLen = maxTextLen * ORA_MESSAGE_ARRAYSIZE;

	// Allocate memory for all the messages text
	pText = new unsigned char[nTextBufferLen];

	// Save original Text pointer for delete call
	pOriginalText = pText;

	Close(ppCursor);
	SetStatement(NULL);

	// Now set the SQL statement and cursor accordingly for getting
	// the rest
	switch (queryType)
	{
		case MessageQueryGetAll:
			pSQLStatement = (char *)SQL_GetAllMessages;
			ppCursor = &mpCDAGetAllMessages;
			break;

		case MessageQueryGetByCategoryId:
			pSQLStatement = (char *)SQL_GetMessagesByCategoryId;
			ppCursor = &mpCDAGetMessagesByCategoryId;
			break;

		case MessageQueryGetByFilterId:
			pSQLStatement = (char *)SQL_GetMessagesByFilterId;
			ppCursor = &mpCDAGetMessagesByFilterId;
			break;

		case MessageQueryGetByMessageType:
			pSQLStatement = (char *)SQL_GetMessagesByMessageType;
			ppCursor = &mpCDAGetMessagesByMessageType;
			break;

		case MessageQueryGetByCategoryIdAndMessageType:
			pSQLStatement = (char *)SQL_GetMessagesByCategoryIdAndMessageType;
			ppCursor = &mpCDAGetMessagesByCategoryIdAndMessageType;
			break;

		default:
			return false;
	}

	// Now get everything else...
	OpenAndParse(ppCursor, (const char *)pSQLStatement);

	// Bind input variables as necessary
	switch (queryType)
	{
		case MessageQueryGetByCategoryId:
			Bind(":category_id", (int *)&value);
			break;

		case MessageQueryGetByFilterId:
			Bind(":filter_id", (int *)&value);
			break;

		case MessageQueryGetByMessageType:
			Bind(":message_type", (int *)&value);
			break;

		default:
			break;
	}

	// Do any necessary binds

	Define(1, (int *)&id[0]);
	Define(2, cName[0], sizeof(cName[0]));
	Define(3, (int *)&type[0]);
	DefineLongRaw(4, pText, maxTextLen);
	Define(5, &textLen[0]);

	Execute();

	rowsFetched = 0;
	do 
	{
		// Clear message text buffer
		memset(pText, 0, nTextBufferLen);

		rc = ofen((struct cda_def *)mpCDACurrent, ORA_MESSAGE_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(ppCursor, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_MESSAGE_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{

			pMessage = new clsMessage((MessageId)id[i],
									  cName[i],
									  (MessageType)type[i],
									  (char *)pText,
									  (unsigned int)textLen[i]);

			pvMessages->push_back(pMessage);

			//Increment the pText pointer to the next buffer spot
			pText += maxTextLen;

		}

		//Reset pText for next fetch
		pText = pOriginalText;

	} while (!CheckForNoRowsFound());

	// Time to free the message text buffer
	if (pText)
	{
		delete [] pText;
	}

	Close(ppCursor);
	SetStatement(NULL);

	return true;
}


//
// GetNextMessageId
//
// Retrieves the next availible message id. Whether
// this is done with a sequence, or a column in
// a table is irrelevant
//
static const char *SQL_GetNextMessageId =
 "select ebay_messages_sequence.nextval from dual";

MessageId clsDatabaseOracle::GetNextMessageId()
{
	int			nextId;

	// Not used often, so we don't need a persistent
	// cursor
	OpenAndParse(&mpCDAGetNextMessageId, SQL_GetNextMessageId);
	Define(1, &nextId);

	// Execute
	ExecuteAndFetch();

	// Close and Clean
	Close(&mpCDAGetNextMessageId);
	SetStatement(NULL);

	return (MessageId)nextId;
}


//
// GetMaxMessageId
//
static const char *SQL_GetMaxMessageId =
"select	MAX(id)	"
"	from	ebay_messages";

MessageId clsDatabaseOracle::GetMaxMessageId()
{
	MessageId	id = 0;

	OpenAndParse(&mpCDAOneShot, SQL_GetMaxMessageId);

	Define(1, (int *)&id);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return id;

}


