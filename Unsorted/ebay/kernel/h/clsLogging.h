/////////////////////////////////////
//	File:	clsLogging.h
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

#ifndef EBAY_CLSLOGGING_H
#define EBAY_CLSLOGGING_H 1

#include <io.h>
#include <fcntl.h>
#include <sys/stat.h>

class clsLogging  
{
public:	
	clsLogging();
	virtual ~clsLogging();
	
	void WriteLog(char* pFunctionName, unsigned int* secs);		
	int mLogFile;
};

#endif 
