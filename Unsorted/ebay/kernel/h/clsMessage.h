//	File:		clsMessage.h
//
// Class:		clsMessage
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a text message stored in the database
//
// Modifications:
//				- 04/13/99 mila		- Created
//

#ifndef CLSMESSAGE_INCLUDED

#include "eBayTypes.h"
#include "vector.h"


// class forward
class clsMessage;

#define CMSG_VARIABLE(name)					\
private:									\
	MessageId		m##name;				\
public:										\
	MessageId		Get##name();			\
	void			Set##name(MessageId new_value);

#define CUINT_VARIABLE(name)				\
private:									\
	unsigned int	m##name;				\
public:										\
	unsigned int	Get##name();			\
	void			Set##name(unsigned int new_value);

#define CSTRING_VARIABLE(name)				\
private:									\
	char	*mp##name;						\
public:										\
	char	*Get##name();					\
	void	Set##name(char *pNew);


class clsMessage
{
public:

	// Default constructor
	clsMessage() :
		mId(0),
		mpName(NULL),
		mType(MessageTypeUnknown),
		mpText(NULL),
		mTextLength(0)
	{
	}

	// Destructor
	virtual ~clsMessage()
	{
		delete [] mpName;
		delete [] mpText;
 	}

	// Constructor
	clsMessage(MessageId id,
			   const char *pName,
			   MessageType type,
			   const char *pText,
			   unsigned int textLen) :
		mId(id),
		mpName(NULL),
		mType(type),
		mpText(NULL),
		mTextLength(textLen)
	{
		int length = 0;

		// initialize message name
		if (pName != NULL)
		{
			length = strlen((char *)pName) + 1;
			mpName = new char[length];
			strcpy(mpName, (char *)pName);
		}

		// initialize message text
		if (pText != NULL)
		{
			if (mTextLength == 0)
				mTextLength = strlen((char *)pText);
			mpText = new char[mTextLength + 1];
			strcpy(mpText, (char *)pText);
		}
	}

	//
	// SetMessageType
	//
	void		SetMessageType(MessageType type) { mType = type; }

	//
	// GetMessageType
	//
	MessageType	GetMessageType() const	{ return mType; }

	CMSG_VARIABLE(Id);
	CSTRING_VARIABLE(Name);
	CSTRING_VARIABLE(Text);
	CUINT_VARIABLE(TextLength);

private:

	MessageType		mType;

};

typedef vector<clsMessage *> MessageVector;

#define CLSMESSAGE_INCLUDED
#endif /* CLSMESSAGE_INCLUDED */

