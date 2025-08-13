//	File:		clsMessage.cpp
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


#include "eBayKernel.h"
#include "clsMessage.h"


// Some convienent macros

#define IMSG_METHODS(variable)						\
MessageId clsMessage::Get##variable()				\
{													\
	return m##variable;								\
}													\
void clsMessage::Set##variable(MessageId newval)	\
{													\
	m##variable	= newval;							\
	return;											\
}

#define ISTRING_METHODS(variable)					\
char *clsMessage::Get##variable()					\
{													\
	return mp##variable;							\
}													\
void clsMessage::Set##variable(char *pNew)			\
{													\
	delete[] mp##variable;							\
	mp##variable = new char[strlen(pNew) + 1];		\
	strcpy(mp##variable, pNew);						\
	return;											\
}

#define IUINT_METHODS(variable)						\
unsigned int clsMessage::Get##variable()			\
{													\
	return m##variable;								\
}													\
void clsMessage::Set##variable(unsigned int newval)	\
{													\
	m##variable	= newval;							\
	return;											\
}

IMSG_METHODS(Id);
ISTRING_METHODS(Name);
ISTRING_METHODS(Text);
IUINT_METHODS(TextLength);

