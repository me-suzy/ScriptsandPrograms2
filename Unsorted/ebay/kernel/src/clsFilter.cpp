//	File:		clsFilter.cpp
//
// Class:		clsFilter
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a filter for screening items to be listed
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsFilter.h"
#include "clsMessages.h"


// Some convienent macros

#define IFILT_METHODS(variable)						\
FilterId clsFilter::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsFilter::Set##variable(FilterId newval)		\
{													\
	m##variable	= newval;							\
	return;											\
}													\

#define IMSG_METHODS(variable)						\
MessageId clsFilter::Get##variable()				\
{													\
	return m##variable;								\
}													\
void clsFilter::Set##variable(MessageId newval)		\
{													\
	m##variable	= newval;							\
	return;											\
}													\

#define ISTRING_METHODS(variable)					\
char *clsFilter::Get##variable()					\
{													\
	return mp##variable;							\
}													\
void clsFilter::Set##variable(char *pNew)			\
{													\
	delete[] mp##variable;							\
	mp##variable = new char[strlen(pNew) + 1];		\
	strcpy(mp##variable, pNew);						\
	return;											\
}													\

#define IBOOL_METHODS(variable)						\
bool clsFilter::Get##variable()						\
{													\
	return m##variable;								\
}													\
void clsFilter::Set##variable(bool newval)			\
{													\
	m##variable	= newval;							\
	return;											\
} 

IFILT_METHODS(Id);
ISTRING_METHODS(Name);
ISTRING_METHODS(Expression);
IBOOL_METHODS(FlagItem);
IMSG_METHODS(BlockingMessageId);
IMSG_METHODS(FlaggingMessageId);
IMSG_METHODS(FilteringMessageId);
IMSG_METHODS(BuddyMessageId);
ISTRING_METHODS(FilteringEmailAddresses);
ISTRING_METHODS(BuddyEmailAddresses);

//
// SetBlockListing
//
void clsFilter::SetBlockListing(bool on)
{
	if (on)
		mActionType = (ActionType)((unsigned int)mActionType | ActionTypeBlockListing);
	else
		mActionType = (ActionType)((unsigned int)mActionType & ~ActionTypeBlockListing);
}

//
// SetFlagListing
//
void clsFilter::SetFlagListing(bool on)
{
	if (on)
		mActionType = (ActionType)((unsigned int)mActionType | ActionTypeFlagListing);
	else
		mActionType = (ActionType)((unsigned int)mActionType &~ActionTypeFlagListing);
}

//
// SetWarnUser
//
void clsFilter::SetWarnUser(bool on)
{
	if (on)
		mActionType = (ActionType)((unsigned int)mActionType | ActionTypeWarnUser);
	else
		mActionType = (ActionType)((unsigned int)mActionType & ~ActionTypeWarnUser);
}

//
// SetBlockListingAndWarnUser
//
void clsFilter::SetBlockListingAndWarnUser(bool on)
{
	SetBlockListing(on);
	SetWarnUser(on);
}

//
// SetFlagListingAndWarnUser
//
void clsFilter::SetFlagListingAndWarnUser(bool on)
{
	SetFlagListing(on);
	SetWarnUser(on);
}

///
// SetNotifyFilteringEmails
//
void clsFilter::SetNotifyFilteringEmails(bool on)
{
	if (on)
		mNotifyType = (NotifyType)((unsigned int)mNotifyType | NotifyTypeFilteringEmailAddresses);
	else
		mNotifyType = (NotifyType)((unsigned int)mNotifyType & ~NotifyTypeFilteringEmailAddresses);
}

//
// SetNotifyBuddyEmails
//
void clsFilter::SetNotifyBuddyEmails(bool on)
{
	if (on)
		mNotifyType = (NotifyType)((unsigned int)mNotifyType | NotifyTypeBuddyEmailAddresses);
	else
		mNotifyType = (NotifyType)((unsigned int)mNotifyType & ~NotifyTypeBuddyEmailAddresses);
}

//
// SetNotifyFilteringAndBuddyEmails
//
void clsFilter::SetNotifyFilteringAndBuddyEmails(bool on)
{
	if (on)
		mNotifyType = (NotifyType)((unsigned int)mNotifyType | (NotifyTypeFilteringEmailAddresses & NotifyTypeBuddyEmailAddresses));
	else
		mNotifyType = (NotifyType)((unsigned int)mNotifyType & ~(NotifyTypeFilteringEmailAddresses & NotifyTypeBuddyEmailAddresses));
}

//
// GetMessageText
//
// Return the message text corresponding to the given message type
//
const char * clsFilter::GetMessageText(MessageType messageType, bool useCache) const
{
	char *			pText = NULL;
	clsMessage *	pMessage = NULL;
	MessageId		messageId = 0;
	clsMarketPlace *pMarketPlace = NULL;

	// Which message do we want?
	switch (messageType)
	{
		case MessageTypeItemBlockedWhenListing:
			messageId = mBlockingMessageId;
			break;
		case MessageTypeItemFlaggedWhenListing:
			messageId = mFlaggingMessageId;
			break;
		case MessageTypeFilteringEmailText:
			messageId = mFilteringMessageId;
			break;
		case MessageTypeBuddyEmailText:
			messageId = mBuddyMessageId;
			break;
		default:
			break;
	}

	// If we have the id for the message, get the clsMessage object from the
	// message cache and extract the message text
	if (messageId != 0)
	{
		pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
		if (pMarketPlace != NULL)
		{
			pMessage = pMarketPlace->GetMessages()->GetMessage(messageId, true);
			if (pMessage != NULL)
				pText = pMessage->GetText();
		}
	}

	return pText;
}

//
// GetCurrentMessageText
//
// Return the message text corresponding to the current action type
//
const char * clsFilter::GetCurrentMessageText(bool useCache) const
{
	if (FlagListingAndWarnUser())
		return GetMessageText(MessageTypeItemFlaggedWhenListing, useCache);
	else if (BlockListingAndWarnUser())
		return GetMessageText(MessageTypeItemBlockedWhenListing, useCache);
	else
		return NULL;
}

//
// GetNotifyEmailAddresses
//
// Return a vector of email addresses of folks to be notified, based on
// the current setting of mNotifyType
//
const char * clsFilter::GetNotifyEmailAddresses()
{
	char *pNotifyEmails;

	// Allocate enough memory for filtering and buddy emails
	if (NotifyFilteringAndBuddyEmails() &&
			mpFilteringEmailAddresses != NULL &&
			mpBuddyEmailAddresses != NULL)
	{
		pNotifyEmails = new char[strlen(mpFilteringEmailAddresses) + strlen(mpBuddyEmailAddresses) + 2];
	}
	else if (NotifyFilteringEmails() && mpFilteringEmailAddresses != NULL)
	{
		pNotifyEmails = new char[strlen(mpFilteringEmailAddresses) + 1];
	}
	else if (NotifyBuddyEmails() && mpBuddyEmailAddresses != NULL)
	{
		pNotifyEmails = new char[strlen(mpBuddyEmailAddresses) + 1];
	}
	else
		return NULL;

	// Initialize memory
	memset(pNotifyEmails, 0, sizeof(pNotifyEmails));

	if (NotifyFilteringEmails())
	{
		// Get email addresses for filtering folks
		strcat(pNotifyEmails, mpFilteringEmailAddresses);
	}
		
	if (NotifyBuddyEmails())
	{
		if (pNotifyEmails[0] != '\0')
			strcat(pNotifyEmails, " ");

		// Get email addresses for buddies
		strcat(pNotifyEmails, mpBuddyEmailAddresses);
	}

	// Return whatever we have
	return pNotifyEmails;
}

//
// DeleteFilteringEmailAddresses
//
void clsFilter::DeleteFilteringEmailAddresses()
{
	delete [] mpFilteringEmailAddresses;
}

//
// DeleteBuddyEmailAddresses
//
void clsFilter::DeleteBuddyEmailAddresses()
{
	delete [] mpBuddyEmailAddresses;
}

