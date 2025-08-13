/////////////////////////////////////
//	File:	clsTimeScale.h
//
//	Class:	clsTimeScale
//
//	Author:	Gurinder Grewal (ggrewal@ebay.com)
//
//	Function:
//			This class is used to measure the time scaling of the 
//			calling function
//
// Modifications:
//
//

#ifndef EBAY_CLSTIMESCALE_H
#define EBAY_CLSTIMESCALE_H 1

class clsTimeScale  
{
public:
	clsTimeScale(char* function_name, clsLogging* pLogging);
	virtual ~clsTimeScale();

	char*				mpFunctionName; //calling function name
	time_t				mBirthTime;		//time this class was instantiated
	clsLogging*			mpLogging;		//logging object pointer, logging object 
									    //writes log to the log file	
};

#endif 
