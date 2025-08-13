//	File:		clsFilterMessages.cpp
//
// Class:		clsMinFilterMessages
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Manages clsMinFilterMessage objects
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsFilterMessages.h"
#include "clsMessages.h"


//
// AddMinFilterMessage
//

bool clsMinFilterMessages::AddMinFilterMessage(clsMinFilterMessage * const pMinFilterMessage) const
{
	bool	success = false;

	if (pMinFilterMessage != NULL)
		success = gApp->GetDatabase()->AddMinFilterMessage(pMinFilterMessage);

	return success;
}


//
// AddMinFilterMessage
//

bool clsMinFilterMessages::AddMinFilterMessage(FilterId filterId,
											   const char *pMessageName) const
{
	bool					success = false;
	clsMessage *			pMessage = NULL;
	clsMarketPlace *		pMarketPlace = NULL;
	clsMinFilterMessage *	pMinFilterMessage = NULL;

	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (pMarketPlace == NULL)
		return false;

	if (pMessageName != NULL)
	{
		pMessage = pMarketPlace->GetMessages()->GetMessage(pMessageName, true);
		if (pMessage != NULL)
		{
			pMinFilterMessage = new clsMinFilterMessage(filterId, pMessage->GetId());
			success = gApp->GetDatabase()->AddMinFilterMessage(pMinFilterMessage);
			delete pMessage;
			delete pMinFilterMessage;
		}
	}

	return success;
}


//
// DeleteMinFilterMessage
//

void clsMinFilterMessages::DeleteMinFilterMessage(FilterId filterId,
												  MessageId messageId,
												  MessageType messageType) const
{
	gApp->GetDatabase()->DeleteMinFilterMessage(filterId, messageId, messageType);
}


//
// UpdateMinFilterMessage
//

bool clsMinFilterMessages::UpdateMinFilterMessage(FilterId filterId,
												  MessageId messageId,
												  MessageType messageType,
												  clsMinFilterMessage * const pMinFilterMessage) const
{
	bool	success = false;

	if (pMinFilterMessage != NULL)
		success = gApp->GetDatabase()->UpdateMinFilterMessage(filterId,
															  messageId,
															  messageType,
															  pMinFilterMessage);

	return success;
}


//
// GetMinFilterMessage
//

clsMinFilterMessage * clsMinFilterMessages::GetMinFilterMessage(FilterId filterId,
																MessageId messageId,
																MessageType messageType) const
{
	return gApp->GetDatabase()->GetMinFilterMessage(filterId, messageId, messageType);
}


//
// GetMinFilterMessagesByFilterId
//

void clsMinFilterMessages::GetMinFilterMessagesByFilterId(FilterId filterId,
									MinFilterMessageVector * const pvMinFilterMessages) const
{
	if (pvMinFilterMessages != NULL)
		gApp->GetDatabase()->GetMinFilterMessagesByFilterId(filterId,
															pvMinFilterMessages);
}


//
// GetMinFilterMessagesByMessageId
//

void clsMinFilterMessages::GetMinFilterMessagesByMessageId(MessageId messageId,
									MinFilterMessageVector * const pvMinFilterMessages) const
{
	if (pvMinFilterMessages != NULL)
		gApp->GetDatabase()->GetMinFilterMessagesByMessageId(messageId,
															 pvMinFilterMessages);
}

/*
//
// GetMessagesByFilterId
//

void clsMinFilterMessages::GetMessagesByFilterId(FilterId filterId,
											  MessageVector * const pvMessages) const
{
	if (pvMessages == NULL)
		return;

	// Get all the filter messages with the given filter ID
	gApp->GetDatabase()->GetMessagesByFilterId(filterId, pvMessages);
}

*/