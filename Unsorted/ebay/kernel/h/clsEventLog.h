/*	$Id: clsEventLog.h,v 1.2 1998/06/23 04:28:07 josh Exp $	*/
//
//	File:		clsEventLog.h
//
// Class:	clsEventLog
//
//	Author:	Wen Wen (wen@ebay.com)
//
//	Function:
//
//				Provide functions for event logging
//
// Modifications:
//				- 06/13/97 Wen	- Created

#ifdef _MSC_VER

#ifndef CLSEVENTLOG_INCLUDED
#define CLSEVENTLOG_INCLUDED

class clsEventLog
{
	public:

		// Constructor & Destructor
		clsEventLog() {;}
		~clsEventLog() {;}

		// Create Source in Registry
		void CreateRegistrySource();

		// log an event
		void LogAnEvent(unsigned int eventType, unsigned int category,
						unsigned int evetId, int numStrings, char** pStrings);
		void LogInformationEvent(char* pStrings);
		void LogWarningEvent(char* pStrings);
		void LogErrorEvent(char* pStrings);

};

#endif // CLSEVENTLOG_INCLUDED

#endif // _MSC_VER
