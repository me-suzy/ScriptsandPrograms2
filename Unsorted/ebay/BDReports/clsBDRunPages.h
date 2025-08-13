/*	$Id: clsBDRunPages.h,v 1.2 1999/02/21 02:21:03 josh Exp $	*/
//
// Class Name:		clsBDRunPages
//
// Description:		Does the 'work' for the BDReports project.
//
// Author:			Chad Musick
//

#ifndef CLSBDRUNPAGES_INCLUDED
#define CLSBDRUNPAGES_INCLUDED

#include <time.h>

#include "vector.h"

class ofstream;
class clseBayWidget;
class clsWidgetHandler;

class clsBDRunPages
{
public:
	clsBDRunPages(time_t startTime, time_t endTime);
	~clsBDRunPages();

	void Run();

private:
	bool OpenFileForCategory(int partnerId,	int categoryId,
										ofstream *pStream);
	bool OpenFileForPartner(int partnerId, ofstream *pStream);
	void clsBDRunPages::SetUpCategoryPages(vector<clseBayWidget *> *pvWidgets,
									   clsWidgetHandler *pHandler);
	void clsBDRunPages::SetUpSummaryPages(vector<clseBayWidget *> *pvWidgets,
										clsWidgetHandler *pHandler);

	time_t mStartTime;
	time_t mEndTime;

	vector<const char *> *mpvPartners;
};

#endif /* CLSBDRUNPAGES_INCLUDED */
