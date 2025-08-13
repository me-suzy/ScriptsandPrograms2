/////////////////////////////////////
//	File:	clsLogging.cpp
//
//	Class:	clsLogging
//
//	Author:	Gurinder Grewal (ggrewal@ebay.com)
//
//	Function:
//			This class is used to write time scale data
//			to the log file
//
// Modifications:
//
//

#include "ebayKernel.h"
#include "clsLogging.h"

//For multiple thread protection
CRITICAL_SECTION LogCriticalSection;


clsLogging::clsLogging()
{
	char file_name[30];
	time_t now;
	time(&now);

	InitializeCriticalSection(&LogCriticalSection);

	//File name will created using current date/time
	sprintf(file_name, "%d.log", now);

	//open for writing and shared read mode
	mLogFile = _open(file_name, _O_WRONLY|_O_CREAT, _S_IREAD);

}

clsLogging::~clsLogging()
{	
	if (mLogFile != -1)
		_close(mLogFile);

}


/////////////////////////////////////////////////////////////////////////////////////
//Write time scale data to the log file
void clsLogging::WriteLog(char * pFunctionName, unsigned int * secs)
{
//Multiple thread safety
// Critical sections only on NT
#ifdef _MSC_VER
	EnterCriticalSection(&LogCriticalSection);	
#endif
		
	if (mLogFile != -1)
	{		
		char buffer[512];
		sprintf(buffer, "%s,%d\r\n", pFunctionName, *secs);

		_write(mLogFile, buffer, strlen(buffer));
		_commit(mLogFile);		
	}
// Critical sections only on NT
#ifdef _MSC_VER
	LeaveCriticalSection(&LogCriticalSection);
#endif
}
