/*	$Id: clsBDReportsApp.h,v 1.2 1999/02/21 02:21:01 josh Exp $	*/
//
// Class:		clsBDReportsApp
//
// Description:	The app class for the BDReports project
//
// Function:	Runs the project. Also includes main for the
//				project.
//
// Author:		Chad Musick
//

#ifndef CLSBDREPORTSAPP_INCLUDE
#define CLSBDREPORTSAPP_INCLUDE

#include "clsApp.h"

#include <time.h>

class clsBDReportsApp : public clsApp
{
public:
	
	clsBDReportsApp(unsigned char *pRequestRec);
	~clsBDReportsApp();

	// We don't run without some times.
	void Run() { }

	// startTime and endTime specify the bounding
	// values for the data we get. startTime is
	// inclusive, endTime is exclusive.
	void Run(time_t startTime, time_t endTime);
};

extern "C" void make_testapp(unsigned char *pRequest);

#endif /* CLSBDREPORTSAPP_INCLUDE */
