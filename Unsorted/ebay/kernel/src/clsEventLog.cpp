/*	$Id: clsEventLog.cpp,v 1.3 1998/06/30 09:11:27 josh Exp $	*/
//
//	File:		clsEventLog.cc
//
// Class:	clsEventLog
//
//	Author:	Wen Wen (wen@ebay.com)
//
//	Function:
//
//				Event Logging
//
// Modifications:
//				- 06/15/97 Wen	- Created


#include "eBayKernel.h"
#ifdef _MSC_VER
#include <afxwin.h>
#include "clsEventLog.h"
#include "eBayResource.h"

//
// Create a new Registery source
//
void clsEventLog::CreateRegistrySource()
{
	HKEY	hk;                      // registry key handle
	DWORD	data;
	char	pSubKey[256];
	char	pFilePath[256];

	// create the subkey
	strcpy(pSubKey, "SYSTEM\\CurrentControlSet\\Services\\EventLog\\Application\\");
	strcat(pSubKey, AfxGetAppName());

	// Get the path of the current module
	HMODULE hModule = GetModuleHandle(AfxGetAppName());
	if (GetModuleFileName(hModule, pFilePath, sizeof(pFilePath)))
	{
		char*	p = strrchr(pFilePath, '\\');
		*p = 0;
		strcat(pFilePath, "\\eBayResource.dll");
	}

	// Create a new key for our application
	RegCreateKey(HKEY_LOCAL_MACHINE, pSubKey, &hk);

	// Add the Event-ID message-file name to the subkey.
	RegSetValueEx(hk,						// subkey handle
				"EventMessageFile",			// value name
				0,							// must be zero
				REG_EXPAND_SZ,				// value type
				(LPBYTE) pFilePath,			// address of value data
				strlen(pFilePath) + 1);		// length of value data

	// Set the supported types flags and addit to the subkey.
	data = EVENTLOG_ERROR_TYPE | EVENTLOG_WARNING_TYPE | EVENTLOG_INFORMATION_TYPE;
	  
	RegSetValueEx(hk,					//subkey handle
				"TypesSupported",       // value name
				0,                      // must be zero
				REG_DWORD,              // value type
				(LPBYTE) &data,         // address of value data
				sizeof(DWORD));         // length of value data

	RegCloseKey(hk);
	return;
}

// 
// Log an event to the event log
//
void clsEventLog::LogAnEvent(unsigned int eventType, 
							 unsigned int category,
							 unsigned int evetId,
							 int numStrings, 
							 char** pStrings)
{
	HANDLE hAppLog;

	// Get a handle to the Application event log
	hAppLog = RegisterEventSource(NULL,				// use local machine
								  AfxGetAppName());	// source name      

	if (hAppLog == NULL)
	{
		// Create a key in Registry
		CreateRegistrySource();

		hAppLog = RegisterEventSource(NULL,				// use local machine
									  AfxGetAppName());	// source name      
	}

	// Now report the event, which will add this event to the event log
	ReportEvent(hAppLog,				 // event-log handle
				eventType,				 // event type
				category,                // category
				evetId,					 // event ID
				NULL,                    // no user SID
				numStrings,              // number of substitution strings
				0,                       // no binary data
				(LPCSTR*) pStrings,                // string array
				NULL);                   // address of data
	DeregisterEventSource(hAppLog);

	return;
}

// 
// Log an information event to the event log
//
void clsEventLog::LogInformationEvent(char* pStrings)
{
	LPSTR pMsg[] = { 
						pStrings, 
	}; 

	LogAnEvent(EVENTLOG_INFORMATION_TYPE, 0, MSG_INFORMATION, 1, pMsg);
}

// 
// Log a warning event to the event log
//
void clsEventLog::LogWarningEvent(char* pStrings)
{
	LPSTR pMsg[] = { 
						pStrings, 
	}; 

	LogAnEvent(EVENTLOG_WARNING_TYPE, 0, 0, 1, pMsg);
}

// 
// Log an error event to the event log
//
void clsEventLog::LogErrorEvent(char* pStrings)
{
	LPSTR pMsg[] = { 
						pStrings, 
	}; 

	LogAnEvent(EVENTLOG_ERROR_TYPE, 0, 0, 1, pMsg);
}

#endif // _MSC_VER