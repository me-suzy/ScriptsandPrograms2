/*	$Id: clsCategoryOverview.cpp,v 1.4.390.1 1999/08/01 02:51:18 barry Exp $	*/
//
//	File:	clsCategoryOverview.cpp
//
//	Class:	clsCategoryOverview
//
//	Author:	Wen Wen
//
//	Function:
//			Create a category overview HTML page
//
// Modifications:
//				- 08/01/97	Wen - Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "clsRebuildListApp.h"
#include "clsCategories.h"
#include "clsItems.h"
#include "clsCategory.h"
#include "clsFileName.h"
#include "clsDatabaseOracle.h"
#include "clsMarketPlace.h"
#include "clsCategoryOverview.h"

#define	COLUMN 3

clsCategoryOverview::clsCategoryOverview()
{
	mpApp = (clsRebuildListApp*) gApp;

	mpCategories = mpApp->GetCategories();
	mpItems = mpApp->GetItems();

	mpFileName = mpApp->GetFileName();

	mCategoryCount = mpCategories->GetCategoryCount();
	
	mTime = mpApp->GetCreatingTime();
}

clsCategoryOverview::~clsCategoryOverview()
{
}

bool clsCategoryOverview::CreatePage()
{
	CategoryVector	TopCategories;
	char*		pOutFileName;
	ofstream	OutputStream;
	char		Msg[256];
	time_t		ExpiringTime;
	struct tm*	pGMTime;

	// create output file stream
	pOutFileName = mpFileName->GetCatOverviewFileName();
	OutputStream.open(pOutFileName, ios::out /*, filebuf::sh_none*/);

	if (OutputStream.fail())
	{
		sprintf(Msg, "Failed during opening %s due to error: %d", pOutFileName, OutputStream.rdstate());
		mpApp->LogMessage(Msg);
		return false;
	}

	// Print title
	OutputStream << "<html><head><TITLE>"
				 << mpApp->GetMarketPlace()->GetCurrentPartnerName()
				 << " Listings : Category Overview</TITLE>\n";
	
	// Expired the page in one and a half hour
	// i.e. pages are built every hour and it took at 
	// lease 10 minutes to build
	ExpiringTime = mpApp->GetCreatingTime() + 70*60;
	pGMTime = gmtime(&ExpiringTime);
	OutputStream << "<meta http-equiv=\"Expires\" content=\""
				 << asctime(pGMTime)
				 << "\">\n"
				 << "</head>\n";

	// use the default header
	//OutputStream << mpApp->GetMarketPlace()->GetRelativeHeader();
	OutputStream << mpApp->GetMarketPlace()->GetHeader();

	// title
	OutputStream << "<center><h2>eBay Category Overview</h2></center>\n";

	// Reserve space for vectors
	TopCategories.reserve(15);

	// Get the top level categories
	mpCategories->TopLevel(&TopCategories);

	// start output
	OutputStream << "<p><table border=1  width=\"100%\">\n<tr><td align=left valign=top>\n";

	// Print the category
	mCurrentColumn = 1;
	mLineNumber = 0;
	PrintCategory(&OutputStream, &TopCategories, 0);

	// end output
	OutputStream << "</td></tr></table></p>\n";

	// CleanUp
	CleanUp(&TopCategories);

	// use the default footer
	//OutputStream << mpApp->GetMarketPlace()->GetRelativeFooter();
	OutputStream << mpApp->GetMarketPlace()->GetFooter();

	OutputStream.close();

	if (OutputStream.fail())
	{
		sprintf(Msg, "Failed during close %s due to error: %d", pOutFileName, OutputStream.rdstate());
		mpApp->LogMessage(Msg);
		return false;
	}

	return true;
}


void clsCategoryOverview::PrintCategory(ostream* pOutStream, 
										CategoryVector* pCategories, 
										int PrevLevel)
{
	CategoryVector::iterator	iCategory;
	CategoryVector				ChildCategories;

	int		Level;

	// reserve space
	ChildCategories.reserve(20);

	for (iCategory = pCategories->begin(); iCategory != pCategories->end(); iCategory++)
	{
		mLineNumber++;
		Level = (*iCategory)->catLevel();
		if (Level == 1)
		{
			if (mLineNumber >= mCategoryCount / COLUMN && 
				mCurrentColumn < COLUMN)
			{
				*pOutStream << "</td><td align=left valign=top>\n";
				mCurrentColumn++;
				mLineNumber = 1;
			}
			else
			{
				if (mLineNumber != 1)
					*pOutStream << "<hr width=\"100%\">";
			}
			PrevLevel = Level;
		}

		if (PrevLevel < Level)
		{
			*pOutStream << "<UL>";
			PrevLevel = Level;
		}

		*pOutStream << "<a href=\"";
/*		if ((*iCategory)->isAdult())
		{
			*pOutStream << mpFileName->GetAdultLinkName(*iCategory, LISTING);
		}
		else
*/		{
			*pOutStream << mpFileName->GetLinkName(*iCategory, LISTING);
		}
		*pOutStream << "\">";

		if ((*iCategory)->isLeaf())
		{
			*pOutStream << "<font size=\"-1\">"
				<< (*iCategory)->GetName()
				<< " ("
				<< mpItems->GetNumberOfListingItemsInCategory((*iCategory)->GetId())
				<< ")</font></a><br>\n";
		}
		else
		{
			*pOutStream << (*iCategory)->GetName()
				    << "</a><br>\n";

			mpCategories->Children(&ChildCategories, *iCategory);
			PrintCategory(pOutStream, &ChildCategories, Level);
			CleanUp(&ChildCategories);
			*pOutStream << "</UL>\n";
		}
	}
}

char* clsCategoryOverview::FormatName(clsCategory* pCategory)
{
	switch(pCategory->catLevel())
	{
	case 1:
		sprintf(mFormatedName, "<strong>%s</strong>", pCategory->GetName());
		break;

	case 2:
		sprintf(mFormatedName, "%s", pCategory->GetName());
		break;

	case 3:
		sprintf(mFormatedName, "<font size=\"-1\"><strong>%s</strong></font>", pCategory->GetName());
		break;

	case 4:
		sprintf(mFormatedName, "<font size=\"-1\">%s</font>", pCategory->GetName());
		break;
	}

	return mFormatedName;
}


void clsCategoryOverview::CleanUp(CategoryVector* pCatVector)
{
	CategoryVector::iterator iCategory;

	for (iCategory = pCatVector->begin(); iCategory != pCatVector->end(); iCategory++)
	{
		delete *iCategory;
	}
	pCatVector->erase(pCatVector->begin(), pCatVector->end());
}


