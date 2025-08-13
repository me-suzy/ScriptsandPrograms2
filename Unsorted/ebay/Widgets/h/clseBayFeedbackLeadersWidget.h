/*	$Id: clseBayFeedbackLeadersWidget.h,v 1.2 1999/02/21 02:28:33 josh Exp $	*/
//
//	File:	clseBayFeedbackLeadersWidget.h
//
//	Class:	clseBayFeedbackLeadersWidget
//
//	Author:	Barry Boone
//
//
//  Modifications:
//

#ifndef CLSEBAYFEEDBACKLEADERSWIDGET_INCLUDED
#define CLSEBAYFEEDBACKLEADERSWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsFeedback.h"
#include <vector.h>

class clsUser;


class clseBayFeedbackLeadersWidget : public clseBayWidget
{

public:

	// Superfeatured widget requires having access to the marketplace
	clseBayFeedbackLeadersWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	~clseBayFeedbackLeadersWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayFeedbackLeadersWidget(pMarketPlace); }

	// Set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// The convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// This widget handles all parameters specified above in the Set# routines.
	// Each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

	void SetNumUsers(int NumUsers) { mNumUsers = NumUsers; }
	void SetThreshold(int Threshold) { mThreshold = Threshold; }

	// Emit the HTML 
	bool EmitHTML(ostream *pStream);

private:
	int mNumUsers;
	int mThreshold;
};

#endif // CLSEBAYFEEDBACKLEADERSWIDGET_INCLUDED
