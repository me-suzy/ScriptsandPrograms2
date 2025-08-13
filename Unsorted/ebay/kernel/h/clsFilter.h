//	File:		clsFilter.h
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


#ifndef CLSFILTER_INCLUDED

#include "eBayTypes.h"


#define CFILT_VARIABLE(name)				\
private:									\
	FilterId	m##name;					\
public:										\
	FilterId	Get##name();				\
	void		Set##name(FilterId new_value);

#define CMSG_VARIABLE(name)					\
private:									\
	MessageId	m##name;					\
public:										\
	MessageId	Get##name();				\
	void		Set##name(MessageId new_value);

#define CSTRING_VARIABLE(name)				\
private:									\
	char	*mp##name;						\
public:										\
	char	*Get##name();					\
	void	Set##name(char *pNew);

#define  CBOOL_VARIABLE(name)				\
private:									\
	bool	m##name;						\
public:										\
	bool	Get##name();					\
	void	Set##name(bool new_value);

class clsFilter {

public:

	// Default constructor
	clsFilter() :
		mId(0),
		mpName(NULL),
		mpExpression(NULL),
		mFlagItem(false),
		mActionType(ActionTypeDoNothing),
		mNotifyType(NotifyTypeNone),
		mBlockingMessageId(0),
		mFlaggingMessageId(0),
		mFilteringMessageId(0),
		mBuddyMessageId(0)
	{
	}

	// Constructor
	clsFilter(FilterId id,
			  const char *pName,
			  const char *pExpression,
			  bool flagItem,
			  ActionType actionType,
			  NotifyType notifyType,
			  MessageId blockingMsgId,
			  MessageId flaggingMsgId,
			  MessageId filteringMsgId,
			  MessageId buddyMsgId,
			  char *pFilteringEmails,
			  char *pBuddyEmails) :
		mId(id),
		mpName(NULL),
		mpExpression(NULL),
		mFlagItem(flagItem),
		mActionType(actionType),
		mNotifyType(notifyType),
		mBlockingMessageId(blockingMsgId),
		mFlaggingMessageId(flaggingMsgId),
		mFilteringMessageId(filteringMsgId),
		mBuddyMessageId(buddyMsgId)
	{
		unsigned int	length = 0;
		char *			pEmails = NULL;

		// initialize filter name
		if (pName != NULL)
		{
			length = strlen((char *)pName) + 1;
			mpName = new char[length];
			strcpy(mpName, (char *)pName);
		}

		// initialize filter expression
		if (pExpression != NULL)
		{
			length = strlen((char *)pExpression) + 1;
			mpExpression = new char[length];
			strcpy(mpExpression, (char *)pExpression);
		}

		// initialize filtering emails
		if (pFilteringEmails != NULL)
		{
			// make a copy
			length = strlen((char *)pFilteringEmails) + 1;
			mpFilteringEmailAddresses = new char[length];
			strcpy(mpFilteringEmailAddresses, (char *)pFilteringEmails);
		}

		// initialize buddy emails
		if (pBuddyEmails != NULL)
		{
			// make a copy
			length = strlen((char *)pBuddyEmails) + 1;
			mpBuddyEmailAddresses = new char[length];
			strcpy(mpBuddyEmailAddresses, (char *)pBuddyEmails);
		}
	}

	// Destructor
	virtual ~clsFilter()
	{
		// delete filter name and filter expression
		delete [] mpName;
		delete [] mpExpression;

		DeleteFilteringEmailAddresses();
		DeleteBuddyEmailAddresses();
	}

	//
	// SetActionType
	//
	void		SetActionType(ActionType type) { mActionType = type; }

	//
	// GetActionType
	//
	ActionType	GetActionType() const { return mActionType; }

	//
	// SetDoNothing
	//
	void		SetDoNothing() { mActionType = ActionTypeDoNothing; }

	//
	// DoNothing
	//
	bool		DoNothing() const { return mActionType == ActionTypeDoNothing; }

	//
	// SetBlockListing
	//
	void		SetBlockListing(bool on);

	//
	// BlockListing
	//
	bool		BlockListing() const { return (mActionType & ActionTypeBlockListing) != 0; }

	//
	// SetFlagListing
	//
	void		SetFlagListing(bool on);

	//
	// FlagListing
	//
	bool		FlagListing() const { return (mActionType & ActionTypeFlagListing) != 0; }

	//
	// SetWarnUser
	//
	void		SetWarnUser(bool on);

	//
	// WarnUser
	//
	bool		WarnUser() const { return (mActionType & ActionTypeWarnUser) != 0; }

	//
	// SetBlockListingAndWarnUser
	//
	void		SetBlockListingAndWarnUser(bool on);

	//
	// BlockListingAndWarnUser
	//
	bool		BlockListingAndWarnUser() const { return BlockListing() && WarnUser(); }

	//
	// SetFlagListingAndWarnUser
	//
	void		SetFlagListingAndWarnUser(bool on);

	//
	// FlagListingAndWarnUser
	//
	bool		FlagListingAndWarnUser() const { return FlagListing() && WarnUser(); }

	//
	// SetNotifyType
	//
	void		SetNotifyType(NotifyType type) { mNotifyType = type; }

	//
	// GetNotifyType
	//
	NotifyType	GetNotifyType() const { return mNotifyType; }

	//
	// SetNotifyNone
	//
	void		SetNotifyNone() { mNotifyType = NotifyTypeNone; }

	//
	// NotifyNone
	//
	bool		NotifyNone() const { return mNotifyType == NotifyTypeNone; }

	//
	// SetNotifyFilteringEmails
	//
	void		SetNotifyFilteringEmails(bool on);

	//
	// NotifyFilteringEmails
	//
	bool		NotifyFilteringEmails() const { return (mNotifyType & NotifyTypeFilteringEmailAddresses) != 0; }

	//
	// SetNotifyBuddyEmails
	//
	void		SetNotifyBuddyEmails(bool on);

	//
	// NotifyBuddyEmails
	//
	bool		NotifyBuddyEmails() const { return (mNotifyType & NotifyTypeBuddyEmailAddresses) != 0; }

	//
	// SetNotifyFilteringAndBuddyEmails
	//
	void		SetNotifyFilteringAndBuddyEmails(bool on);

	//
	// NotifyFilteringAndBuddyEmails
	//
	bool		NotifyFilteringAndBuddyEmails() const { return  NotifyFilteringEmails() && NotifyBuddyEmails(); }

	//
	// GetMessageText
	//
	const char *GetMessageText(MessageType messageType, bool useCache) const;

	//
	// GetCurrentMessageText
	//
	const char *GetCurrentMessageText(bool useCache) const;

	//
	// GetNotifyEmailAddresses
	//
	const char *GetNotifyEmailAddresses();
	
	CFILT_VARIABLE(Id);
	CSTRING_VARIABLE(Name);
	CSTRING_VARIABLE(Expression);
	CBOOL_VARIABLE(FlagItem);
	CMSG_VARIABLE(BlockingMessageId);
	CMSG_VARIABLE(FlaggingMessageId);
	CMSG_VARIABLE(FilteringMessageId);
	CMSG_VARIABLE(BuddyMessageId);
	CSTRING_VARIABLE(FilteringEmailAddresses);
	CSTRING_VARIABLE(BuddyEmailAddresses);

protected:

	//
	// DeleteFilteringEmailAddresses
	//
	void		DeleteFilteringEmailAddresses();

	//
	// DeleteBuddyEmailAddresses
	//
	void		DeleteBuddyEmailAddresses();

private:

	ActionType		mActionType;
	NotifyType		mNotifyType;
};

typedef vector<clsFilter *> FilterVector;

#define CLSFILTER_INCLUDED
#endif /* CLSFILTER_INCLUDED */

