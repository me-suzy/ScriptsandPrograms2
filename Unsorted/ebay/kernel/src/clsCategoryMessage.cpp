//	File:		clsCategoryMessage.cpp
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



#include "eBayKernel.h"
#include "clsCategoryMessage.h"

// Some convienent macros

#define IMCAT_METHODS(variable)									\
CategoryId clsCategoryMessage::Get##variable()					\
{																\
	return m##variable;											\
}																\
void clsCategoryMessage::Set##variable(CategoryId newval)		\
{																\
	m##variable	= newval;										\
	return;														\
}																\

#define IMMSG_METHODS(variable)									\
MessageId clsCategoryMessage::Get##variable()					\
{																\
	return m##variable;											\
}																\
void clsCategoryMessage::Set##variable(MessageId newval)		\
{																\
	m##variable	= newval;										\
	return;														\
}																\


IMCAT_METHODS(CategoryId);
IMMSG_METHODS(MessageId);

