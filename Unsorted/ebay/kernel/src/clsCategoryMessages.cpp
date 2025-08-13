//	File:		clsCategoryMessages.cpp
//
// Class:	clsCategoryMessages
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//			Manages clsCategoryMessage objects
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsCategoryMessages.h"
#include "clsMessages.h"

//
// AddCategoryMessage
//

bool clsCategoryMessages::AddCategoryMessage(clsCategoryMessage * const pCategoryMessage) const
{
	bool	success = false;

	if (pCategoryMessage != NULL)
	{
		success = gApp->GetDatabase()->AddCategoryMessage(pCategoryMessage);
		if (success)
		{
			mpMarketPlace->GetMessages()->SetDirtyCache();
			mpMarketPlace->GetCategories()->FlagCategory(pCategoryMessage->GetCategoryId(), true);
		}
	}

	return success;
}


//
// AddCategoryMessage
//

bool clsCategoryMessages::AddCategoryMessage(CategoryId categoryId,
											 MessageId messageId) const
{
	bool					success = false;
	clsCategoryMessage *	pCategoryMessage = NULL;

	pCategoryMessage = new clsCategoryMessage(categoryId, messageId);
	success = gApp->GetDatabase()->AddCategoryMessage(pCategoryMessage);
	if (success)
	{
		mpMarketPlace->GetMessages()->SetDirtyCache();
		mpMarketPlace->GetCategories()->FlagCategory(pCategoryMessage->GetCategoryId(), true);
	}

	delete pCategoryMessage;

	return success;
}


//
// AddCategoryMessage
//

bool clsCategoryMessages::AddCategoryMessage(CategoryId categoryId,
											 const char *pMessageName) const
{
	bool					success = false;
	clsMessage *			pMessage = NULL;
	clsMarketPlace *		pMarketPlace = NULL;
	clsCategoryMessage *	pCategoryMessage = NULL;

	if (pMessageName == NULL)
		return false;

	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (pMarketPlace == NULL)
		return false;

	pMessage = pMarketPlace->GetMessages()->GetMessage(pMessageName);
	if (pMessage != NULL)
	{
		pCategoryMessage = new clsCategoryMessage(categoryId, pMessage->GetId());
		success = gApp->GetDatabase()->AddCategoryMessage(pCategoryMessage);
		if (success)
		{
			mpMarketPlace->GetMessages()->SetDirtyCache();
			mpMarketPlace->GetCategories()->FlagCategory(pCategoryMessage->GetCategoryId(), true);
		}

		delete pCategoryMessage;
		delete pMessage;
	}

	return success;
}


//
// DeleteCategoryMessage
//

void clsCategoryMessages::DeleteCategoryMessage(CategoryId categoryId,
												MessageId messageId) const
{
	gApp->GetDatabase()->DeleteCategoryMessage(categoryId, messageId);
	mpMarketPlace->GetMessages()->SetDirtyCache();
}


//
// UpdateCategoryMessage
//

bool clsCategoryMessages::UpdateCategoryMessage(CategoryId categoryId,
												MessageId messageId,
												clsCategoryMessage * const pCategoryMessage) const
{
	bool	success = false;

	if (pCategoryMessage != NULL)
	{
		success = gApp->GetDatabase()->UpdateCategoryMessage(categoryId,
															 messageId,
															 pCategoryMessage);
		if (success)
		{
			mpMarketPlace->GetMessages()->SetDirtyCache();
			mpMarketPlace->GetCategories()->FlagCategory(pCategoryMessage->GetCategoryId(), true);
		}
	}

	return success;
}


//
// GetCategoryMessage
//

clsCategoryMessage *
clsCategoryMessages::GetCategoryMessage(CategoryId categoryId,
										MessageId messageId) const
{
	return gApp->GetDatabase()->GetCategoryMessage(categoryId, messageId);
}


//
// GetCategoryMessagesByCategoryId
//

void clsCategoryMessages::GetCategoryMessagesByCategoryId(CategoryId categoryId,
									CategoryMessageVector * const pvCategoryMessages) const
{
	if (pvCategoryMessages != NULL)
		gApp->GetDatabase()->GetCategoryMessagesByCategoryId(categoryId, pvCategoryMessages);
}


//
// GetCategoryMessagesByMessageId
//

void clsCategoryMessages::GetCategoryMessagesByMessageId(MessageId messageId,
									CategoryMessageVector * const pvCategoryMessages) const
{
	if (pvCategoryMessages != NULL)
		gApp->GetDatabase()->GetCategoryMessagesByMessageId(messageId,
															pvCategoryMessages);
}


//
// GetMessages
//
void clsCategoryMessages::GetMessages(CategoryId categoryId,
									  MessageType messageType,
									  MessageVector * const pvMessages,
									  bool useCache /* = false */) const
{
	if (pvMessages == NULL)
		return;

	mpMarketPlace->GetMessages()->GetMessages(categoryId,
											  messageType,
											  pvMessages,
											  useCache);
}


//
// GetMessageText
//
void clsCategoryMessages::GetMessageText(CategoryId categoryId,
										 MessageType messageType,
										 vector<char *> *pvText,
										 bool useCache /* = false */) const
{
	if (pvText == NULL)
		return;

	mpMarketPlace->GetMessages()->GetMessageText(categoryId,
												 messageType,
												 pvText,
												 useCache);
}



#if 0

//
// GetCategoryMessages
//
void clsCategoryMessages::GetCategoryMessages(CategoryId categoryId,
											  MessageType messageType,
											  vector<char *> *pvMessageText) const
{
	MessageVector			vMessages;
	MessageVector::iterator	i;
	char *					pText = NULL;

	// get vector of messages
	mpCategoryMessages->GetMessages(categoryId, messageType, &vMessages);

	// allocate memory ahead of time
	pvMessageText->reserve(vMessages.size());

	for (i = vMessages.begin(); i != vMessages.end(); ++i)
	{
		// make a copy of the text
		pText = new char[strlen((*i)->GetText())];
		strcpy(pText, (*i)->GetText());

		// add the copy to the vector
		pvMessageText->push_back(pText);
	}

	vMessages.erase(vMessages.begin(), vMessages.end());
}

#endif
