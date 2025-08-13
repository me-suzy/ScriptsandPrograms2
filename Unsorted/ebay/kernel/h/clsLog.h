/*	$Id: clsLog.h,v 1.2 1998/06/23 04:28:17 josh Exp $	*/
//
//	File:		clsLog.h
//
// Class:	clsLog
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				This class should be used to log "occasional"
//				events and errors. It is NOT intended to log
//				high volume information.
//				
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSLOG_INCLUDED
#include "eBayTypes.h"

//
// LogTypeEnum indicates the "severity" of the event. clsLog
// may use this internally to control the routing of the 
// event
//
typedef enum
{
	LOG_NORMAL_EVENT			= 1,
	LOG_WARNING_EVENT			= 2,
	LOG_ERROR_EVENT			= 3
} LogTypeEnum;

//
// LogCode is a numeric code indicating the event code
//
typedef int LogCode;

// Macros for you and me
#define LOG_NORMAL(code, pText) gApp->GetLog()->Log(LOG_NORMAL_EVENT, code, __FILE__, pText)

#define LOG_WARNING(code, pText) gApp->GetLog()->Log(LOG_WARNING_EVENT, code, __FILE__, pText)

#define LOG_ERROR(code, pText) gApp->GetLog()->Log(LOG_ERROR_EVENT, code, __FILE__, pText)



class clsLog
{
	public:
	
		//
		// CTOR, DTOR
		//
		clsLog();
		~clsLog();

		//
		// Log
		//
		void Log(LogTypeEnum type,
					LogCode code,
					char *pModule,
					char *pMessage);
};

#define CLSLOG_INCLUDED 1
#endif /* CLSLOG_INCLUDED */