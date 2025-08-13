/*	$Id: clsMessages.cpp,v 1.2 1999/05/19 02:34:58 josh Exp $	*/
//	File:		clsMessages.cpp
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


#include "eBayKernel.h"

#include "clsMessage.h"
#include "clsMessages.h"
#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif


//
// AddMessage
//

bool clsMessages::AddMessage(clsMessage *pMessage)
{
	bool	success = false;

	if (pMessage != NULL)
	{
		if (pMessage->GetId() == 0)
			pMessage->SetId(gApp->GetDatabase()->GetNextMessageId());

		success = gApp->GetDatabase()->AddMessage(pMessage);
		mDirtyCache = success;
	}

	return success;
}


//
// DeleteMessage
//

void clsMessages::DeleteMessage(MessageId id)
{
	gApp->GetDatabase()->DeleteMessage(id);
	mDirtyCache = true;
}


//
// DeleteMessage
//

void clsMessages::DeleteMessage(const char *pName)
{
	if (pName != NULL)
	{
		gApp->GetDatabase()->DeleteMessage(pName);
		mDirtyCache = true;
	}
}


//
// UpdateMessage
//

bool clsMessages::UpdateMessage(MessageId id, clsMessage *pMessage)
{
	bool	success = false;

	success = gApp->GetDatabase()->UpdateMessage(id, pMessage);
	mDirtyCache = success;

	return success;
}


//
// UpdateMessage
//

bool clsMessages::UpdateMessage(const char *pName, clsMessage *pMessage)
{
	bool	success = false;

	if (pName != NULL && pMessage != NULL)
	{
		success = gApp->GetDatabase()->UpdateMessage(pName, pMessage);
		mDirtyCache = success;
	}

	return success;
}


//
// GetMessage
//

clsMessage * clsMessages::GetMessage(MessageId id, bool useCache)
{
	clsMessage *	pMessage;

	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		pMessage = mpMessageCacheById[id];
	}
	else
		pMessage = gApp->GetDatabase()->GetMessage(id);

	return pMessage;
}


//
// GetMessage
//
clsMessage * clsMessages::GetMessage(const char *pName, bool useCache)
{
	clsMessage *pMessage = NULL;
	char *		pMessageName = NULL;
	int			i;

	if (pName == NULL)
		return NULL;

	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		for (i = 1; i < mMessageCacheByIdSize; i++)
		{
			if (mpMessageCacheById[i] != NULL)
			{
				pMessageName = mpMessageCacheById[i]->GetName();
				if (pMessageName != NULL &&
					strcasecmp(pMessageName, (char *)pName) == 0)
				{
					pMessage = mpMessageCacheById[i];
					break;
				}
			}
		}
	}
	else
		pMessage = gApp->GetDatabase()->GetMessage(pName);

	return pMessage;
}


//
// GetMessages
//
void clsMessages::GetMessages(CategoryId categoryId,
							  MessageType messageType,
							  MessageVector * const pvMessages,
							  bool useCache /* = false */)
{
	MessageVector::iterator	i;
	bool                    found = false;
	clsCategories *         pCategories = NULL;
	clsCategory *           category;
	int                     level;

	if (pvMessages == NULL)
		return;

	// Use cache till we get the database access code in place.
	useCache = true;

	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		pCategories = mpMarketPlace->GetCategories();
		category    = pCategories->GetCategory(categoryId, true);
		for (level = category->catLevel(); level > 0; level--)
		{
			for (i = mCategoryMessageMap[categoryId].begin();
				 i != mCategoryMessageMap[categoryId].end();
				 i++)
			{
				if ((*i) != NULL && (*i)->GetMessageType() == messageType)
				{
					found = true;
					pvMessages->push_back(*i);
				}
			}

			if (found == true)
			{
				return;
			}

			categoryId = category->GetParent();
			category   = pCategories->GetCategory(categoryId, true);
		}
	}
//	else
//		gApp->GetDatabase()->GetMessages(categoryId, messageType, pvMessages);
}


//
// GetMessageText
//
void clsMessages::GetMessageText(CategoryId categoryId,
								 MessageType messageType,
								 vector<char *> * const pvText,
								 bool useCache /* = false */)
{
	MessageVector::iterator	i;
	char *					pMsgText = NULL;
	char *					pText = NULL;
	bool                    found = false;
	clsCategories *         pCategories = NULL;
	clsCategory *           category;
	int                     level;

	if (pvText == NULL)
		return;

	// Use cache till we get the database access code in place.
	useCache = true;

	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		pCategories = mpMarketPlace->GetCategories();
		category    = pCategories->GetCategory(categoryId, true);
		for (level = category->catLevel(); level > 0; level--)
		{
			for (i = mCategoryMessageMap[categoryId].begin();
				 i != mCategoryMessageMap[categoryId].end(); 
				 i++)
			{
				if ((*i) != NULL && (*i)->GetMessageType() == messageType)
				{
					pMsgText = (*i)->GetText();
					if (pMsgText != NULL)
					{
						found = true;
						pText = new char[strlen(pMsgText) + 1];
						strcpy(pText, pMsgText);
						pvText->push_back(pText);
					}
				}
			}
			
			if (found == true)
			{
				return;
			}

			categoryId = category->GetParent();
			category   = pCategories->GetCategory(categoryId, true);
		}
	}
//	else
//		gApp->GetDatabase()->GetMessageText(categoryId, messageType, pvText);
}


//
// GetMessagesByMessageType
//

void clsMessages::GetMessagesByMessageType(MessageType messageType,
										   MessageVector *pvMessages,
										   bool useCache)
{
	unsigned int	i;

	if (pvMessages == NULL)
		return;
	
	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		// allocate memory ahead of time for speed
		pvMessages->reserve(mMessageCacheByIdSize);

		for (i = 1; i < mMessageCacheByIdSize; i++)
		{
			if (mpMessageCacheById[i] != NULL &&
				mpMessageCacheById[i]->GetMessageType() == messageType)
			{
				pvMessages->push_back(mpMessageCacheById[i]);
			}
		}
	}
	else
		gApp->GetDatabase()->GetMessages(MessageQueryGetByMessageType,
										 pvMessages,
										 (unsigned int)messageType);
}


//
// GetMessagesByCategoryId
//

void clsMessages::GetMessagesByCategoryId(CategoryId categoryId,
										  MessageVector *pvMessages,
										  bool useCache)
{
	MessageVector *			pvMsgs = NULL;
	MessageVector::iterator	i;

	if (pvMessages == NULL)
		return;
	
	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		// select the bucket to iterate through
		pvMsgs = &mCategoryMessageMap[categoryId];

		// allocate memory ahead of time for speed
		pvMessages->reserve(pvMsgs->size());

		// iterate through the bucket
		for (i = pvMsgs->begin(); i != pvMsgs->end(); i++)
		{
			pvMessages->push_back(*i);
		}
	}
	else
		gApp->GetDatabase()->GetMessages(MessageQueryGetByCategoryId,
										 pvMessages,
										 (unsigned int)categoryId);
}


//
// GetMessagesByFilterId
//

void clsMessages::GetMessagesByFilterId(FilterId filterId,
										MessageVector *pvMessages,
										bool useCache,
										bool dummy /* = false */)
{
//	MessageVector::iterator	i;

	if (pvMessages == NULL)
		return;
#if 0
	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		// XXX need to create message cache by filter id before doing this!!!
	}
	else
#endif
		gApp->GetDatabase()->GetMessages(MessageQueryGetByFilterId,
										 pvMessages,
										 (unsigned int)filterId);
}


//
// GetAllMessages
//

void clsMessages::GetAllMessages(MessageVector *pvMessages,
								 bool useCache)
{
	unsigned int	i;

	if (pvMessages == NULL)
		return;
	
	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateMessageCaches();

		// allocate memory ahead of time for speed
		pvMessages->reserve(mMessageCacheByIdSize);

		// fill up the vector
		for (i = 1; i < mMessageCacheByIdSize; i++)
		{
			if (mpMessageCacheById[i] != NULL)
				pvMessages->push_back(mpMessageCacheById[i]);
		}
	}
	else
		gApp->GetDatabase()->GetMessages(MessageQueryGetAll,
										 pvMessages);
}


//
// GetMaxMessageId
//
MessageId clsMessages::GetMaxMessageId() const
{
	return gApp->GetDatabase()->GetMaxMessageId();
}


//
// IsThisOrParentCategoryMessage
//
bool clsMessages::IsThisOrParentCategoryMessage(CategoryId categoryId,
									            MessageId messageId)
{
	MessageVector *			pvMsgs = NULL;
	MessageVector::iterator	i;
	clsCategories *         pCategories = NULL;
	clsCategory   *         category;
	int                     level;

	// get it from the cache, only way to get messages of ancestors also
	if (mDirtyCache)
		PopulateMessageCaches();

	pCategories = mpMarketPlace->GetCategories();
	category    = pCategories->GetCategory(categoryId, true);
	for (level = category->catLevel(); level > 0; level--)
	{
		// select the bucket to iterate through
		pvMsgs = &mCategoryMessageMap[categoryId];

		// iterate through the bucket
		for (i = pvMsgs->begin(); i != pvMsgs->end(); i++)
		{
			// Check if message id already exists
			if ((*i)->GetId() == messageId)
			{
				return true;
			}
		}
		categoryId = category->GetParent();
		category   = pCategories->GetCategory(categoryId, true);
	}

	return false;
}

//
// PopulateMessageCaches
//
void clsMessages::PopulateMessageCaches()
{
	unsigned int						i;

	MessageVector						vMessages;
	MessageVector::iterator				iMessage;
	clsMessage *						pMessage = NULL;

	CategoryVector						vCategories;

	CategoryId							categoryId;
	clsCategory *						pCategory;
	clsCategories *						pCategories;

	CategoryMessageVector				vCategoryMessages;
	CategoryMessageVector::iterator		iMin;


	// First purge the current caches
	PurgeMessageCaches();

	// Get all messages from the database
	gApp->GetDatabase()->GetMessages(MessageQueryGetAll, &vMessages);
	mMessageCacheByIdSize = GetMaxMessageId() + 1;

	// Allocate memory for message cache indexed by message ID
	mpMessageCacheById = new clsMessage *[mMessageCacheByIdSize];
	memset(mpMessageCacheById, 0, mMessageCacheByIdSize * sizeof(clsMessage *));

	// Populate a cache of messages indexed by message ID, and a
	// cache indexed by message name
	for (iMessage = vMessages.begin(); iMessage != vMessages.end(); iMessage++)
	{
		// Populate the message cache ordered by message ID and
		// the message cache ordered by name
		if ((*iMessage) != NULL)
		{
			mpMessageCacheById[(*iMessage)->GetId()] = (*iMessage);
			mMessageCacheByName[(*iMessage)->GetName()] = (*iMessage);
		}		
	}
	mMessageCacheByNameSize = mMessageCacheByName.size();

	// We're done with those caches, so on to the category-message mappings...

	pCategories = mpMarketPlace->GetCategories();

	// Get all categories from the category cache
	pCategories->All(&vCategories, true);
	mCategoryMessageMapSize = vCategories.size();

#ifdef _MSC_VER
	mpCategoryMessageBuckets = new MessageVector[mCategoryMessageMapSize];
#else
	MessageVector *tmp = new MessageVector[mCategoryMessageMapSize];
	mpCategoryMessageBuckets = tmp;
#endif

	memset(mpCategoryMessageBuckets, 0, mCategoryMessageMapSize * sizeof(MessageVector));

	// For each category, map the category ID at index i in the
	// category vector to the filter bucket at index i in the filter
	// buckets vector
	for (i = 0; i < mCategoryMessageMapSize; i++)
	{
		if (vCategories[i] != NULL)
		{
			categoryId = vCategories[i]->GetId();
			mCategoryMessageMap[categoryId] = mpCategoryMessageBuckets[i];
		}
	}

	// Now we're ready to populate the buckets...

	// Get all category messages from the database
	gApp->GetDatabase()->GetAllCategoryMessages(&vCategoryMessages);

	for (iMin = vCategoryMessages.begin(); iMin != vCategoryMessages.end(); iMin++)
	{
		if ((*iMin) == NULL)
			continue;

		// Get the category ID so we know which bucket we want
		categoryId = (*iMin)->GetCategoryId();

		pCategory = pCategories->GetCategory(categoryId, true);
		pMessage = mpMessageCacheById[(*iMin)->GetMessageId()];
		if ((pMessage != NULL) && (categoryId != 0))
		{
			// Drop the message in the appropriate bucket
			mCategoryMessageMap[categoryId].push_back(pMessage);
		}

		// Delete the category message cuz we're done with it
		delete (*iMin);

		// Don't delete pCategory 'cuz it points directly into the category cache!!!
	}

	// Clear up vectors
	vMessages.erase(vMessages.begin(), vMessages.end());
	vCategories.erase(vCategories.begin(), vCategories.end());
	vCategoryMessages.erase(vCategoryMessages.begin(), vCategoryMessages.end());

	// Now we have a vector of messages ordered by ID, plus an array of category
	// IDs, each with its own message bucket. (mila 4/10/99)

	mDirtyCache = false;
}


//
// PurgeMessageCaches
//
void clsMessages::PurgeMessageCaches()
{
	unsigned int					i;

	// Purge vector ordered by message ID
	for (i = 0; i < mMessageCacheByIdSize; i++)
	{
		if (mpMessageCacheById[i] != NULL)
		{
			delete mpMessageCacheById[i];
			mpMessageCacheById[i] = NULL;
		}
	}
	if (mpMessageCacheById != NULL)
	{
		delete [] mpMessageCacheById;
		mpMessageCacheById = NULL;
		mMessageCacheByIdSize = 0;
	}

	mMessageCacheByName.erase(mMessageCacheByName.begin(), mMessageCacheByName.end());
	mMessageCacheByNameSize = 0;

	if (mpCategoryMessageBuckets != NULL)
	{
		// Delete memory allocated for message buckets
		for (i = 0; i < mCategoryMessageMapSize; i++)
		{
			mpCategoryMessageBuckets[i].erase(mpCategoryMessageBuckets[i].begin(),
											 mpCategoryMessageBuckets[i].end());
		}
		delete [] mpCategoryMessageBuckets;
	}

	// Delete memory allocated for category-message map
	mCategoryMessageMap.erase(mCategoryMessageMap.begin(), mCategoryMessageMap.end());
	mCategoryMessageMapSize = 0;

	mDirtyCache = true;
}


