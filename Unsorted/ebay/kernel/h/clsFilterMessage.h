//	File:		clsFilterMessage.h
//
// Class:	clsFilterMessage
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a filter-message relationship
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#ifndef CLSFILTERMESSAGE_INCLUDED

#include "eBayTypes.h"


#define CMFILT_VARIABLE(name)				\
private:									\
	FilterId		m##name;				\
public:										\
	FilterId		Get##name();			\
	void	Set##name(FilterId new_value);

#define CMMSG_VARIABLE(name)				\
private:									\
	MessageId		m##name;				\
public:										\
	MessageId		Get##name();			\
	void	Set##name(MessageId new_value);


class clsMinFilterMessage {

public:

	// Default constructor
	clsMinFilterMessage() :
		mFilterId(0),
		mMessageId(0),
		mMessageType(MessageTypeUnknown)
	{
	}

	// Constructor
	clsMinFilterMessage(FilterId categoryId,
						MessageId messageId,
						MessageType messageType = MessageTypeUnknown) :
		mFilterId(categoryId),
		mMessageId(messageId),
		mMessageType(messageType)
	{
	}

	// Destructor
	virtual ~clsMinFilterMessage()
	{
	}

	//
	// SetMessageType
	//
	void		SetMessageType(MessageType messageType) { mMessageType = messageType; }

	//
	// GetMessageType
	//
	MessageType	GetMessageType() { return mMessageType; }

	CMFILT_VARIABLE(FilterId);
	CMMSG_VARIABLE(MessageId);

protected:

private:

	MessageType	mMessageType;

};

typedef vector<clsMinFilterMessage *> MinFilterMessageVector;

#define CLSFILTERMESSAGE_INCLUDED
#endif /* CLSFILTERMESSAGE_INCLUDED */

