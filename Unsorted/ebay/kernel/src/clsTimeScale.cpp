/////////////////////////////////////
//	File:	clsTimeScale.cpp
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



#include "eBayKernel.h"
#include "clsLogging.h"
#include "clsTimeScale.h"

#define EBAY_TIME_SCALING_ENABLED 1

clsTimeScale::clsTimeScale(char* function_name,  
						   clsLogging* pLogging)
{
#ifdef EBAY_TIME_SCALING_ENABLED
	if (function_name)
	{
		mpFunctionName = new char[strlen(function_name)+1];
		sprintf(mpFunctionName, function_name);
	}
	else
	{
		mpFunctionName = new char[strlen("Error") + 1];
		sprintf(mpFunctionName, "Error");
	}
	
	//Record the time of call
	time(&mBirthTime);
	
	//Logging class
	mpLogging = pLogging;
	
#endif
}

///////////////////////////////////////////////////////////////////////////
//Destructor will calculate the life time of this class and log it to log 
//file. Life time of this class is execution time of the function
clsTimeScale::~clsTimeScale()
{
#ifdef EBAY_TIME_SCALING_ENABLED
	time_t				death_time;
	unsigned int		life_time;

	time(&death_time);
	life_time = death_time - mBirthTime;
	
	if (mpLogging)
		mpLogging->WriteLog(mpFunctionName, &life_time);

	if (mpFunctionName)
		delete mpFunctionName;
#endif
}
