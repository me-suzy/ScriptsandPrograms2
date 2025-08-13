/*	$Id: eBayDebug.cpp,v 1.4 1999/02/21 02:48:04 josh Exp $	*/
//
//	File:		eBayDebug.cc
//
// Class:	None
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		eBay Debugging functions
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#include "eBayKernel.h"

// Apache Stuff
//extern "C"
//{
//#include "httpd.h"
//#include "http_log.h"
//}

#include <time.h>
#include <string.h>
#include <stdio.h>
#include <stdarg.h>

// ISAPI Stuff
#ifdef IS_ISAPI
#ifdef _MSC_VER
#include "AFXISAPI.H"
#endif /* _MSC_VER */
#else
#define ISAPITRACE()
#endif // IS_ISAPI

// Are we set up?
static bool				gDebugSetup = false;

// A copy of the application type
static AppTypeEnum	gAppType;

//
// DebugSetup
//		"First time" routine to set things up

void DebugSetup()
{
	// Let's make a copy of the application
	// type so we don't have to look it up 
	// each time.
	gAppType		= gApp->GetAppType();
	gDebugSetup	= true;
	return;
}

void eBayWhere(char flag, char *pFile, const int line)
{
	time_t		theTime;
	struct tm	*pLocalTime;
	char		whereString[256];

	// request_rec	*pApacheRequest;

	if (!gDebugSetup)
		DebugSetup();

	// Make sure it's 0 length
	whereString[0]	= '\0';

	// Let's build up the string. Apache doesn't need the time,
	// since it logs it for us!
	if (gAppType != APP_APACHE_MODULE)
	{	
		time(&theTime);

		pLocalTime = localtime( &theTime );

		strftime(whereString, sizeof(whereString),
					"%m/%d/%y %H:%M:%S",
					pLocalTime);
	}

	sprintf(whereString + strlen(whereString),
		     	" %s:%d\n",
				pFile, line);

	// Now...do the right thing to get it out
	switch(gAppType)
	{
		case APP_APACHE_MODULE:
			// pApacheRequest	= 
			//		(request_rec *)gApp->GetApacheRequest();
			// log_printf(pApacheRequest->server,
			//			  "%s",
			//			  whereString);
			break;

		case APP_CGI:
			cerr << whereString;
			break;

		case APP_SHELL:
			cerr << whereString;

		default:
			break;
	}

	return;
}

//
// eBayDebug
//
void eBayDebug(char flag, char *pFormat, ...)
{
	char	msg[1024];
	// request_rec	*pApacheRequest;

	// Make sure we're all setup 
	if (!gDebugSetup)
		DebugSetup();

	// We always do this..
	va_list args;
   	va_start(args, pFormat);


	// Do the right thing
	switch(gAppType)
	{
		case APP_APACHE_MODULE:
			// pApacheRequest	= 
			//		(request_rec *)gApp->GetApacheRequest();
			// log_printf(pApacheRequest->server,
			// 			  pFormat,
			//			  args);
			break;

		case APP_CGI:
		case APP_SHELL:
			vsprintf(msg, pFormat, args);
			vfprintf(stderr, pFormat, args);
			break;

		case APP_ISAPI:
#ifdef _MSC_VER
			sprintf(msg, "%x/%x: ", 
					GetCurrentProcessId(),
					GetCurrentThreadId());

			vsprintf(msg + strlen(msg), pFormat, args);

			// Log the mesasge to event log
			gApp->LogEvent(msg);
#endif // _MSC_VER

		default:
			break;
	}

	va_end(args);

	return;
}
