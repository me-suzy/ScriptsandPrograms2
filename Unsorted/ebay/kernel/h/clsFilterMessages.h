//	File:		clsFilterMessages.h
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


#include "clsFilter.h"
#include "clsMessage.h"
#include "clsFilterMessage.h"

class clsMinFilterMessages {

public:

	// Default constructor
	clsMinFilterMessages()
	{
	}

	// Destructor
	virtual ~clsMinFilterMessages()
	{
	}

	//
	// AddMinFilterMessage
	//
	bool		AddMinFilterMessage(clsMinFilterMessage * const pMinFilterMessage) const;

	bool		AddMinFilterMessage(FilterId filterId,
									const char *pMessageName) const;

	//
	// DeleteMinFilterMessage
	//
	void		DeleteMinFilterMessage(FilterId categoryId,
									   MessageId messageId,
									   MessageType messageType) const;

	//
	// UpdateMinFilterMessage
	//
	bool		UpdateMinFilterMessage(FilterId categoryId,
									   MessageId messageId,
									   MessageType messageType,
									   clsMinFilterMessage * const pMinFilterMessage) const;

	//
	// GetMinFilterMessage
	//
	clsMinFilterMessage *GetMinFilterMessage(FilterId categoryId,
											 MessageId messageId,
											 MessageType messageType) const;

	//
	// GetMinFilterMessagesByFilterId
	//
	void		GetMinFilterMessagesByFilterId(FilterId categoryId,
											   MinFilterMessageVector * const pvMinFilterMessages) const;

	//
	// GetMinFilterMessagesByMessageId
	//
	void		GetMinFilterMessagesByMessageId(MessageId messageId,
												MinFilterMessageVector * const pvMinFilterMessages) const;
/*
	//
	// GetMessagesByFilterId
	//
	void		GetMessagesByFilterId(FilterId categoryId,
									  MessageVector * const pvMessages) const;
*/
protected:

private:

};

