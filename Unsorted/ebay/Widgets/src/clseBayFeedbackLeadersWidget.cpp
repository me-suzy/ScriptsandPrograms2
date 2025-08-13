/*	$Id: clseBayFeedbackLeadersWidget.cpp,v 1.3 1999/04/07 05:42:20 josh Exp $	*/
//
//	File:	clseBayFeedbackLeadersWidget.cpp
//
//	Class:	clseBayFeedbackLeadersWidget
//
//	Author:	Barry
//
//	Function:
//			Widget that shows top feedback leaders.
//
// Modifications:
//		


#include "widgets.h"
#include "clsUserIdWidget.h"

clseBayFeedbackLeadersWidget::clseBayFeedbackLeadersWidget(clsMarketPlace *pMarketPlace) :
	clseBayWidget(pMarketPlace)
{
	mNumUsers  = 10;
	mThreshold = 2;  // FOR TESTING!!!
}

clseBayFeedbackLeadersWidget::~clseBayFeedbackLeadersWidget()
{
}

void clseBayFeedbackLeadersWidget::SetParams(vector<char *> *pvArgs)
{
	int   p;
	char *cArg;
	char  cArgCopy[256];
	char *cName;
	char *cValue;
	bool  handled = false;
	int   x;

	// Reverse through these so that deletions are safe.
	// Stop at 1, because we don't care about the tagname
	for (p=pvArgs->size()-1; p>=1; p--)
	{
		cArg = (*pvArgs)[p];
		handled = false;

		// Separate the name from the value
		strncpy(cArgCopy, cArg, sizeof(cArgCopy)-1);
		cName = cArgCopy;
		cValue = strchr(cArgCopy, '=');
		if (cValue) 
		{
			cValue[0]='\0';		// lock in cName
			cValue++;			// set cValue
		}
		else
			cValue="";

		// Remove start & end quotes if they were provided
		x = strlen(cValue);
		if ((x>1) && (cValue[0]=='\"' && cValue[x-1]=='\"'))
		{
			cValue[x-1]='\0';		// remove ending "
			cValue++;				// remove beginning "
		}

		// Try to handle this parameter
		if ((!handled) && (strcmp("numUsers", cName)==0))
		{
			this->SetNumUsers(atoi(cValue));
			handled=true;
		}
		if ((!handled) && (strcmp("threshold", cName)==0))
		{
			this->SetThreshold(atoi(cValue));
			handled=true;
		}

		// If this parameter was handled, remove (and delete the char*) it from the vector
		if (handled)
		{
			pvArgs->erase(pvArgs->begin()+p);	
			delete [] cArg;	// don't need the parameter anymore
		}
	}

	// Ok, now pass the rest of the parameters up to the parent to handle.
	// (eBayWidget will just return... not really necessary...)
	clseBayWidget::SetParams(pvArgs);

}

bool clseBayFeedbackLeadersWidget::EmitHTML(ostream *pStream)
{
	int					threshold = mThreshold; // min feedback
	int				    numUsers  = mNumUsers;  // to show

	vector<clsUserPtr>::iterator user;
	vector<clsUserPtr>::iterator firstUser;
	vector<clsUserPtr>			 vUsers;

	clsUsers		   *pUsers;
	clsUserIdWidget	   *pUserIdWidget;


	// Get the marketplace's clsItems object
	if (mpMarketPlace)
		pUsers = mpMarketPlace->GetUsers();
	else
		return false;

	// Get all feedback greater than the current threshold just to get at least 50
	// We sort in the SQL statement, descending by feedback score.
	pUsers->GetUserIdsAndFeedbackByFeedback(threshold, &vUsers);

	if (vUsers.size() < numUsers) 
		return false;	

	*pStream << "<ol>";

	firstUser = vUsers.begin();
	for (user = firstUser; user != firstUser + numUsers; user++)
	{
		pUserIdWidget = new clsUserIdWidget(mpMarketPlace, gApp);
		pUserIdWidget->SetUser(user->mpUser);

		pUserIdWidget->SetUserIdBold(false);
		pUserIdWidget->SetShowUserStatus(false);
		pUserIdWidget->SetShowMask(false);
		pUserIdWidget->SetShowFeedback(true);
		pUserIdWidget->SetShowStar(true);
		pUserIdWidget->SetUserIdLink(false);
		pUserIdWidget->SetShowAboutMe(true);

		*pStream << "<li>";

		pUserIdWidget->EmitHTML(pStream);

		*pStream << "</li>";

		delete pUserIdWidget; // Done with this widget.
	}

	*pStream << "</ol>\n";

	// Free all of the objects in the vector.

	for (user = vUsers.begin(); user != vUsers.end(); user++)
	{
		delete user->mpUser; // userptr objects don't delete the user they point to.
	}

	vUsers.erase(vUsers.begin(), vUsers.end());

	return true;
}


