/*	$Id: clsLog.cpp,v 1.3 1998/06/30 09:11:36 josh Exp $	*/
//
//	File:		clsLog.cc
//
// Class:	clsLog
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#include "eBayKernel.h"

//
// CTOR
//
clsLog::clsLog()
{
	return;
}

// 
// DTOR
// 
clsLog::~clsLog()
{
	return;
}

//
// Log
//
void clsLog::Log(LogTypeEnum type, 
				LogCode code,
				char *pModule,
				char *pMsg)
{

	// First, let's translate the type
	switch (type)
	{
		case LOG_NORMAL_EVENT:
			*(gApp->mpStream)	<< "NORMAL:		";
			break;
		case LOG_WARNING_EVENT:
			*(gApp->mpStream)	<< "WARNING:	";
			break;
		case LOG_ERROR_EVENT:
			*(gApp->mpStream)	<< "ERROR:		";
			break;
		default:
			*(gApp->mpStream)	<< "UNKNOWN:	";
			break;
	}

	// Now, code, module, message
	*(gApp->mpStream)	<< code
							<< " "
							<< pModule
							<< "\t"
							<< pMsg
							<< "\n"
							<< flush;

	return;
}

				
