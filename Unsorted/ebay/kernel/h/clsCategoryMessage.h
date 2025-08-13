//	File:		clsCategoryMessage.h
//
// Class:	clsCategoryMessage
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a category-message relationship
//
// Modifications:
//				- 04/13/99 mila		- Created
//




#ifndef CLSCATEGORYMESSAGE_INCLUDED

#include "eBayTypes.h"



#define CMCAT_VARIABLE(name)				\
private:									\
	CategoryId		m##name;				\
public:										\
	CategoryId		Get##name();			\
	void	Set##name(CategoryId new_value);

#define CMMSG_VARIABLE(name)				\
private:									\
	MessageId		m##name;				\
public:										\
	MessageId		Get##name();			\
	void	Set##name(MessageId new_value);


class clsCategoryMessage
{
public:

	// Default constructor
	clsCategoryMessage() :
		mCategoryId(0),
		mMessageId(0)
	{
	}

	// Constructor
	clsCategoryMessage(CategoryId categoryId,
					   MessageId messageId) :
		mCategoryId(categoryId),
		mMessageId(messageId)
	{
	}

	// Destructor
	virtual ~clsCategoryMessage()
	{
	}

	CMCAT_VARIABLE(CategoryId);
	CMCAT_VARIABLE(MessageId);

protected:

private:

};

typedef vector<clsCategoryMessage *> CategoryMessageVector;

#define CLSCATEGORYMESSAGE_INCLUDED
#endif /* CLSCATEGORYMESSAGE_INCLUDED */

