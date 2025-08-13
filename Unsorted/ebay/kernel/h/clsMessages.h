//	File:		clsMessages.h
//
// Class:		clsMessages
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Manages clsMessage objects and caches
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#ifndef CLSMESSAGES_INCLUDED

#include "map.h"

#include "eBayTypes.h"
#include "clsMessage.h"


typedef map<const char *, clsMessage *, less<const char *> >	MessageNameMap;
typedef map<unsigned int, MessageVector, less<unsigned int> >	CategoryMessageMap;


//
// enum MessageQueryType
//
typedef enum
{
	MessageQueryGetAll	= 0,
	MessageQueryGetByCategoryId,
	MessageQueryGetByFilterId,
	MessageQueryGetByMessageType,
	MessageQueryGetByCategoryIdAndMessageType,
	// don't add any entries below this line
	MessageQueryLast
} MessageQueryType;


class clsMessages
{
public:

	// Default constructor
	clsMessages(clsMarketPlace *pMarketPlace) :
		mpMarketPlace(pMarketPlace),
		mDirtyCache(true),
		mpMessageCacheById(NULL),
		mMessageCacheByIdSize(0),
		mMessageCacheByNameSize(0),
		mpCategoryMessageBuckets(NULL)
	{
	}

	// Destructor
	virtual ~clsMessages()
	{
		PurgeMessageCaches();
	}

	//
	// AddMessage
	//
	bool		AddMessage(clsMessage *pMessage);

	//
	// DeleteMessage
	//
	void		DeleteMessage(MessageId id);
	void		DeleteMessage(const char *pName);

	//
	// UpdateMessage
	//
	bool		UpdateMessage(MessageId id, clsMessage *pMessage);
	bool		UpdateMessage(const char *pName, clsMessage *pMessage);

	//
	// GetMessage
	//
	clsMessage *GetMessage(MessageId id, bool useCache = false);
	clsMessage *GetMessage(const char *pName, bool useCache = false);

	//
	// GetMessages
	//
	void		GetMessages(CategoryId categoryId,
							MessageType messageType,
							MessageVector * const pvMessages,
							bool useCache = false);
	//
	// GetMessageText
	// Gets all messages of the type 'messageType' in category 'categoryId'. If none
	// found, then continues up the category hierarchy looking for one with messages
	// of this type.
	//
	void		GetMessageText(CategoryId categoryId,
							   MessageType messageType,
							   vector<char *> * const pvText,
							   bool useCache = false);


	//
	// GetMessagesByMessageType
	//
	void		GetMessagesByMessageType(MessageType type,
										 MessageVector *pvMessages,
										 bool useCache = false);

	//
	// GetMessagesByCategoryId
	// Gets all messages in category 'categoryId'.
	//
	void		GetMessagesByCategoryId(CategoryId categoryId,
										MessageVector *pvMessages,
										bool useCache = false);

	//
	// GetMessagesByFilterId
	//
	void		GetMessagesByFilterId(FilterId filterId,
									  MessageVector *pvMessages,
									  bool useCache = false,
									  bool dummy = false);

	//
	// GetAllMessages
	//
	void		GetAllMessages(MessageVector *pvMessages,
							   bool useCache = false);

	//
	// GetMaxMessageId
	//
	MessageId	GetMaxMessageId() const;

	//
	// SetDirtyCache
	//
	void		SetDirtyCache() { mDirtyCache = true; }

	//
	// IsThisOrParentCategoryMessage
	//
	bool		IsThisOrParentCategoryMessage(CategoryId categoryId, 
									          MessageId messageId);

protected:

	//
	// PopulateMessageCaches
	//
	void		PopulateMessageCaches();

	//
	// PurgeMessageCaches
	//
	void		PurgeMessageCaches();

private:

	clsMarketPlace *	mpMarketPlace;
	bool				mDirtyCache;

	clsMessage **		mpMessageCacheById;
	unsigned int		mMessageCacheByIdSize;

	MessageNameMap		mMessageCacheByName;
	unsigned int		mMessageCacheByNameSize;

	CategoryMessageMap	mCategoryMessageMap;
	unsigned int		mCategoryMessageMapSize;

	MessageVector *		mpCategoryMessageBuckets;
};

#define CLSMESSAGES_INCLUDED
#endif /* CLSMESSAGES_INCLUDED */

