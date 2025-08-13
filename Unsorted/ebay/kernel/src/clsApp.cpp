/*	$Id: clsApp.cpp,v 1.8.392.2 1999/08/03 00:58:33 nsacco Exp $	*/
//
//	File:	clsApp.cc
//
//	Class:	clsApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Superclass for all eBay applications. It has two purposes:
//				1. Shield the application from the differences in various
//					environments (apache modules, shell applications, etc).
//				2. Act as a central provider for access to other classes
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 06/19/97 wen		- added EventLog, and set & get app name
//				- 07/23/97 wen		- added GetListingFileName
//
#define __PUT_STATIC_DATA_MEMBERS_HERE

char *dummyvar;

#include "eBayKernel.h"
#include "clsEnvironment.h"
#include "clsEnvironmentISAPI.h"

#ifdef _MSC_VER
// We only use this file under windows
#include "clsEventLog.h"
#endif // _MSC_VER

#ifdef IS_ISAPI
#include "clsISAPIStreamBuf.h"
#endif // IS_ISAPI

// 
// The following is needed for STL
// under winddows
//
#ifdef IS_MULTITHREADED
#ifdef _MSC_VER
    CRITICAL_SECTION __node_allocator_lock;
    bool __node_allocator_lock_initialized;
	  alloc __node_allocator_dummy_instance;
#endif /* _MSC_VER */
#endif // IS_MULTITHREADED

// The global which is us
// clsApp *gApp = (clsApp *)0;


clsApp::clsApp()
{
	mpEnvironment		= NULL;
	mpDatabase			= NULL;
	mpMarketPlaces		= NULL;
	mAppType			= APP_UNKNOWN;
	mpStream			= NULL;
#ifdef _MSC_VER
	mpEventLog			= NULL;
#endif // _MSC_VER
#ifdef IS_ISAPI
	mpISAPIStreamBuf	= NULL;
	mpStreamBuffer		= NULL;
#endif // IS_ISAPI

	SetApp(this);
	return;
}


clsApp::~clsApp()
{

/*
#ifdef _MSC_VER
	EDEBUG('*', "~clsApp() Begin");
#endif
*/

	if (mpStream != &cout)
		delete	mpStream;

#ifdef _MSC_VER
	delete  mpEventLog;
#endif /* _MSC_VER */
#ifdef IS_ISAPI
	delete	mpStreamBuffer;
	delete	mpISAPIStreamBuf;
#endif // IS_ISAPI

	delete	mpMarketPlaces;
	delete	mpDatabase;
	delete	mpEnvironment;

/*
#ifdef _MSC_VER
	EDEBUG('*', "~clsApp() End");
#endif
*/

	mpRequestRec= NULL;
	mpLog = NULL;
}

//
// InitISAPI
//
void clsApp::InitISAPI(unsigned char *pCtx)
{
	mAppType	= APP_ISAPI;

#ifdef IS_ISAPI
	if (!mpISAPIStreamBuf)
	{
		mpStreamBuffer		= new char[16 * 1024];
		mpISAPIStreamBuf	= new clsISAPIStreamBuf(mpStreamBuffer, 16 * 1024);
		// mpISAPIStreamBuf->setbuf(mpStreamBuffer, 16 * 1024);
		mpISAPIStreamBuf->SetContext(pCtx);
		mpStream			= new ostream(mpISAPIStreamBuf);
	}
	else
		mpISAPIStreamBuf->SetContext(pCtx);

	if (!mpEnvironment)
	{
		mpEnvironment	= new clsEnvironmentISAPI;
	}

	mpEnvironment->Reset(pCtx);
#else
	mpISAPIStreamBuf	= NULL;
	mpEnvironment		= NULL;
#endif // IS_ISAPI

	return;
}
	
//
//
// InitShell
//
//	This little method sets things up for Shell apps
//
void clsApp::InitShell()
{
	if (!mpStream)
		mpStream	= &cout;

	mAppType		= APP_SHELL;

	return;
}
//
// Trace
//
//	A Stupid default
//
void clsApp::Trace(char * /* pFormat */, ...)
{
	return;
}


//
// LogEvent
//
#ifdef _MSC_VER
void clsApp::LogEvent(char* pMsg)
{
	if (mpEventLog == NULL)
	{
		mpEventLog = new clsEventLog;
	}

	mpEventLog->LogInformationEvent(pMsg);
}
#endif // _MSC_VER

//
// GetAppType
//
AppTypeEnum clsApp::GetAppType()
{
	return mAppType;
}


//
// GetLog
//
clsLog *clsApp::GetLog()
{
	if (!mpLog)
	{
		mpLog	= new clsLog;
	}
	return mpLog;
}

//
// GetDatabase
//
// Returns a pointer to the database object, creating
// it if necessary.
//
//
clsDatabase *clsApp::GetDatabase()
{
	if (!mpDatabase)
	{
 		mpDatabase	= new clsDatabaseOracle((char *)0);
	}
	return mpDatabase;
}

void clsApp::CancelDBTransactions()
{
	if (mpDatabase)
		mpDatabase->CancelPendingTransactions();

}

//
// GetMarketPlaces
//
clsMarketPlaces *clsApp::GetMarketPlaces()
{
	if (!mpMarketPlaces)
	{
		mpMarketPlaces	= new clsMarketPlaces;
	}

	return mpMarketPlaces;
}

//
// GetEnvironment
//
clsEnvironment *clsApp::GetEnvironment()
{
	return mpEnvironment;
}
