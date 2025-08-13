/*	$Id: clsApp.h,v 1.2 1998/06/23 04:27:37 josh Exp $	*/
//
//	File:		clsApp.h
//
// Class:	clsApp
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
//				- 07/23/97 wen		- added clsListingFileName
//
#ifndef CLSAPP_INCLUDED
#include "eBayTypes.h"

#ifdef _MSC_VER
#include "iostream.h"
typedef int streamsize;
#else
#include "strstream.h"
#endif /* _MSC_VER */


// Class forward
class clsLog;
class clsEnvironment;
class clsDatabase;
class clsMarketPlaces;
class clsISAPIStreamBuf;

#ifdef _MSC_VER
class clsEventLog;
#endif // _MSC_VER

class clsApp
{
	public:
		
		// Constructor, Destructor
		clsApp();
		~clsApp();

		//
		// GetAppType
		// 
		// What KIND of an app are we?
		//
		AppTypeEnum GetAppType();

		//
		// Initialization functions
		//
		// Once clsApp is instantiated, one of the following initialization
		// methods needs to be invoked so the base class can set up various
		// abstractions. 
		//
		// *** Note ***
		// This could also be done by having different version of the ctor, 
		// OR the code could magically figure out what the environment is
		// and do the right thing (sure, it could, sure it could!).
		//
		void InitShell();
		void InitISAPI(unsigned char *pCtx);

		//
		// Trace
		//
		// Override this method to provide application or
		// environment specific trace functionality
		//
		void Trace(char *pFormat, ...);

		//
		// LogEvent
		//
#ifdef _MSC_VER
		void LogEvent(char *pMsg);
#endif // _MSC_VER

		//
		// GetLog
		//		Returns a pointer to the log object
		//
		clsLog *GetLog();

		//
		// GetEnvironment
		//		Returns a pointer to the environment
		//
		clsEnvironment *GetEnvironment();

		//
		// GetDatabase
		//		Returns a pointer to the database object
		//
		clsDatabase		*GetDatabase();

		// rollback transactions
		void CancelDBTransactions();

		//
		// GetMarketPlaces
		//		Returns a pointer to the MarketPlaces object
		//
		clsMarketPlaces *GetMarketPlaces();

		// 
		// Our common output stream. We went to a little trouble to 
		// be able to declare it this way (instead of as a pointer
		// to a stream), so code could just say:
		//
		//		mStream << Blah Blah
		//
		// char			mBuffer[256];
		char					*mpStreamBuffer;
		clsISAPIStreamBuf		*mpISAPIStreamBuf;
		ostream					*mpStream;

	private:
		AppTypeEnum				mAppType;
		char					mAppName[256];					
		unsigned char			*mpRequestRec;

#ifdef _MSC_VER
		clsEventLog				*mpEventLog;
#endif // _MSC_VER

		//
		// Log
		//
		clsLog					*mpLog;

		//
		// Environment
		//
		clsEnvironment			*mpEnvironment;

		//
		// Database object
		//
		clsDatabase				*mpDatabase;

		//
		// MarketPlaces
		//
		clsMarketPlaces			*mpMarketPlaces;
};

#define CLSAPP_INCLUDED 1
#endif /* CLSAPP_INCLUDED */
