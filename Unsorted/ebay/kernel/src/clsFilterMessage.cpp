//	File:		clsFilterMessage.cpp
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


#include "eBayKernel.h"
#include "clsFilterMessage.h"


// Some convienent macros

#define IMCAT_METHODS(variable)								\
FilterId clsMinFilterMessage::Get##variable()				\
{															\
	return m##variable;										\
}															\
void clsMinFilterMessage::Set##variable(FilterId newval)	\
{															\
	m##variable	= newval;									\
	return;													\
}															\

#define IMMSG_METHODS(variable)								\
MessageId clsMinFilterMessage::Get##variable()				\
{															\
	return m##variable;										\
}															\
void clsMinFilterMessage::Set##variable(MessageId newval)	\
{															\
	m##variable	= newval;									\
	return;													\
}															\


IMCAT_METHODS(FilterId);
IMMSG_METHODS(MessageId);

