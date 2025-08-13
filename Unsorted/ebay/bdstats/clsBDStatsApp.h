/*	$Id: clsBDStatsApp.h,v 1.2 1999/02/21 02:30:31 josh Exp $	*/
//
// Class Name:		clsBDStatsApp
//
// Description:		The app class for the BDStats project
//
// Author:			Chad Musick
//

#ifndef CLSBDSTATSAPP_INCLUDE
#define CLSBDSTATSAPP_INCLUDE

#include "clsApp.h"

#include <time.h>

class clsBDStatsApp : public clsApp
{
public:
	
	clsBDStatsApp(unsigned char *pRequestRec);
	~clsBDStatsApp();
	
	void Run() { }
	void Run(time_t startTime, time_t endTime);
};

extern "C" void make_testapp(unsigned char *pRequest);

#endif /* CLSBDSTATSAPP_INCLUDE */
