/*	$Id: clsEnvironment.cpp,v 1.8.500.1 1999/08/01 03:02:26 barry Exp $	*/
//
//	File:		clsEnvironment.cc
//
// Class:	clsEnvironment
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Encapsulates all the little nasty thing
//				in the environment, like parameters, etc.
//
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 05/25/99 nsacco	- New mServerName to determine site
//
#include "eBayKernel.h"
#include "clsEnvironment.h"

#include <string.h>


// A nice little macro
#define CHAR_METHODS(variable, size)			\
char *clsEnvironment::Get##variable()			\
{												\
	return (char *) m##variable;				\
}												\
void clsEnvironment::Set##variable(char *pNew)	\
{												\
	strncpy((char *)m##variable, pNew, size);	\
	m##variable[size - 1] = '\0';				\
	return;										\
}

//
// Constructor
//
//		Stashes away some important information
//
clsEnvironment::clsEnvironment()
{
	mRemoteAddr[0]='\0';
	mRemoteHost[0]='\0';
	mRemoteUser[0]='\0';
	mScriptName[0]='\0';
	mCookie[0]='\0';
	mBrowser[0]='\0';
	mReferrer[0]='\0';
	// nsacco 05/25/99
	mServerName[0]='\0';
	return;
};

//
// Destructor
//
clsEnvironment::~clsEnvironment()
{
	return;
}

//
// Getters, Setters
//
CHAR_METHODS(RemoteAddr, 16)
CHAR_METHODS(RemoteHost, 256)
CHAR_METHODS(RemoteUser, 256)
CHAR_METHODS(ScriptName, 512)
CHAR_METHODS(Cookie, 4096)
CHAR_METHODS(Browser, 256)
CHAR_METHODS(Referrer, 256)
// nsacco 05/25/99
CHAR_METHODS(ServerName, 256)
