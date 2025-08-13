/*	$Id: clseBayUserWidget.h,v 1.2 1998/10/16 01:01:21 josh Exp $	*/
//
//	File:	clseBayUserWidget.h
//
//	Class:	clseBayUserWidget
//
//	Author:	Chad Musick
//
//	Note: NumItems in the widget refers to the number of comments to print
//		mNumItems will be 1 greater than this, to accomodate the header
//
//  Modifications:
//				- 10/14/97	Poon - Created
//				- 10/17/97	Chad - Copied for clseBayUserWidget
//

#ifndef CLSEBAYUSERWIDGET_INCLUDED
#define CLSEBAYUSERWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsFeedback.h"
#include <vector.h>

class clsUser;

class clseBayUserWidget : public clseBayTableWidget
{

public:

	// Superfeatured widget requires having access to the marketplace
	clseBayUserWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayUserWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayUserWidget(pMarketPlace); }

	void SetTargetUser(char *targetUser)	{strncpy(mTargetUser, targetUser, sizeof(mTargetUser) - 1);}

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);
	

protected:
	// Choose the items from the database and put them into mvItems
	virtual bool Initialize();

	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	virtual bool EmitCell(ostream *pStream, int n);


private:

	clsUser						*mpUser; // the user to talk about
	FeedbackItemVector			*mpvFeedback; // what to say
	vector<int>					mvFeedbackIndices; // which to print
	int							mAdjustForHeader; // Whether or not we have
												  // saved header space.
	char						mTargetUser[64];	// substring that username needs to contain
												//  (e.g. "aol.com"), default = ""
};

#endif // CLSEBAYSUPERFEATUREDWIDGET_INCLUDED
