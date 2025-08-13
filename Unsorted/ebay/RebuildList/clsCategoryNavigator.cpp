/*	$Id: clsCategoryNavigator.cpp,v 1.3.388.3 1999/08/06 02:26:56 nsacco Exp $	*/
//
//	File:	clsCategoryNavigator.cpp
//
//	Class:	clsCategoryNavigator
//
//	Author:	Wen Wen
//
//	Function:
//			Create hot links to parents and sibling of the category
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#include "clsRebuildListApp.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsCategoryNavigator.h"
#include "clsFileName.h"
// nsacco 08/05/99
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"

static const char*  pSiblingLinkFormat =
"<tr><td align=left valign=bottom>%s</td>\n"
"<td align=center valign=bottom><strong><font size=\"+1\">%s</font></strong></td>\n"
"<td align=right valign=bottom>%s</td>\n"
"</tr></table>\n";

static const char*  pTimeLinkFormat =
"<p align=center><small>%s || %s || %s || %s || %s</small></p>";

static const char* pJumpStrings[] = {
"Jump to a list of all items in ",
"Jump to a list of all new items in ",
"Jump to a list of all items ending today in ",
"Jump to a list of all completed itmes in ",
"Jump to a list of all items ending in 5 hours in "
};

// kakiyama 07/19/99 - commented out
// resourced using getPicsPath()
/*
//#define PreviousIconURL	"<img height=18 width=12 border=0 alt=\"[Previous]\" src=\"http://pics.ebay.com/aw/pics/greyleft.gif\">"
//#define NextIconURL		"<img height=18 width=12 border=0 alt=\"[Next]\" src=\"http://pics.ebay.com/aw/pics/greyright.gif\">"
*/

clsCategoryNavigator::clsCategoryNavigator(clsCategory* pCategory, 
										   TimeCriterion TimeStamp,
										   bool			 HasTimeLink/*=true*/)
{
	mpCategory = pCategory;
	mTimeStamp = TimeStamp;
	mHasTimeLink = HasTimeLink;

	mpApp = (clsRebuildListApp*) gApp;
	mpCategories = mpApp->GetCategories();

	mpQualifiedName = NULL;
}

clsCategoryNavigator::~clsCategoryNavigator()
{
	delete mpQualifiedName;

}

void clsCategoryNavigator::Initialize()
{
	if (mpCategory)
	{
		// Get Qualified Name
		mpQualifiedName = mpApp->GetCategories()->GetQualifiedName(mpCategory);
	}

	// Get file name object
	mpFileName = mpApp->GetFileName();

	// Get Completed heading
	if (mTimeStamp == COMPLETED)
		sprintf(mCompletedHeading, "%s %s", COMPLETED_HEADING, mpApp->GetTodayString());
}

void clsCategoryNavigator::Print(ostream* pOutputFile, bool PrintJump)
{

	// Print links to other time frame
	if (!mHasTimeLink)
	{
		PrintLinks(pOutputFile);
	}
	else
	{
		PrintTitle(pOutputFile);
		PrintTimeLinks(pOutputFile);
	}

	if (mpCategory)
	{
		// Print the jump
		if (mpCategory->isLeaf() == false && PrintJump) // && mTimeStamp != GOING)
		{
			//Set jump to the listings if it is not first level category
			*pOutputFile << "<p align=center><small><a href=\"#eBayListings\">"
						<< pJumpStrings[mTimeStamp]
						<< (mpCategory)->GetName()
						<< "</a></small></p>\n";
		}

		// Print links to ancestors
//		PrintAncestorLinks(pOutputFile);

		// Print links to sibling
		// PrintSiblingLinks(pOutputFile, PrintJump);
	}
	else
	{
		if (mTimeStamp == GOING)
		{
			if (PrintJump)
			{
				*pOutputFile << "<p align=center><small><a href=\"#eBayListings\"><strong>Jump to a list of all items ending in 5 hours</strong></a></small></p>\n";
			}
		}
	}

	*pOutputFile << "<p>";

/*
	// print link to the overview page
	*pOutputFile << "<p><center><font size=\"-1\"><a href=\""
				 << mpFileName->GetOverviewLinkName()
				 << "\">Go to the category overview</a></font></center><p>";
*/
}

void clsCategoryNavigator::GetAncestorLinks(char* pAncestor)
{
	int		i;

	//
	// Ancestor Links
	//
	// Make strong and larger font
	sprintf(pAncestor, "<a href=\"%s\">Top</a>\n", mpFileName->GetRelativeLinkName((clsCategory*) NULL, mTimeStamp, 1, mpApp->GetBuildDay()));

	// Create links to Ancestors
	for (i = 4; i >= 1; i--)
	{
		if (mpQualifiedName->ids[i])
		{
			strcat(pAncestor, " : <a href=\"");
			strcat(pAncestor, mpFileName->GetRelativeLinkName(mpQualifiedName->ids[i], mTimeStamp, 1, mpApp->GetBuildDay()));
			strcat(pAncestor, "\">");
			strcat(pAncestor, mpQualifiedName->names[i]);
			strcat(pAncestor, "</a>\n");
		}
	}
	strcat(pAncestor, " : ");
	strcat(pAncestor, mpQualifiedName->names[0]);
				 
}

void clsCategoryNavigator::GetSiblingLinks(char* pPrevious, char* pNext)
{
	//
	// Sibling link
	//
	// Link to previous category
	if (mpCategory->GetPrevSibling())
	{
//		sprintf(pPrevious, "<a href=\"%s\">%s</a>", 
//			mpFileName->GetRelativeLinkName(mpCategory->GetPrevSibling(), mTimeStamp, 1, mpApp->GetBuildDay()),
//			PreviousIconURL);
		

		sprintf(pPrevious, "<a href=\"%s\"><img height=18 width=12 border=0 alt=\"[Previous]\" src=\"%sgreyleft.gif\"></a>",
			mpFileName->GetRelativeLinkName(mpCategory->GetPrevSibling(), mTimeStamp, 1, mpApp->GetBuildDay()),
			mpApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetPicsPath());
	}
	else
	{
		strcpy(pPrevious, "&nbsp;");
	}

	// Link to next category
	if (mpCategory->GetNextSibling())
	{
//		sprintf(pNext, "<a href=\"%s\">%s</a>", 
//			mpFileName->GetRelativeLinkName(mpCategory->GetNextSibling(), mTimeStamp, 1, mpApp->GetBuildDay()),
//			NextIconURL);
// kakiyama 07/19/99

		sprintf(pNext, "<a href=\"%s\"><img height=18 width=12 border=0 alt=\"[Next]\" src=\"%sgreyright.gif\"></a>",
			mpFileName->GetRelativeLinkName(mpCategory->GetNextSibling(), mTimeStamp, 1, mpApp->GetBuildDay()),
			mpApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetPicsPath());
	}
	else
	{
		strcpy(pNext, "&nbsp;");
	}
}

void clsCategoryNavigator::PrintTimeLinks(ostream* pOutputFile)
{
	char*	pFileLinks[GOING+1];
	int		Index;
	char	Links[1000];

	// get the file names for each time period
	for (Index = LISTING; Index <= GOING; Index++)
	{
		pFileLinks[Index] = new char[_MAX_PATH+100];

		switch(Index)
		{
		case LISTING:
			sprintf(pFileLinks[Index], "<a href=\"%s\">Current</a>", 
				mpFileName->GetLinkName(mpCategory, (TimeCriterion)Index));
			break;

		case NEW_TODAY:
			sprintf(pFileLinks[Index], "<a href=\"%s\">New Today</a>",
                mpFileName->GetLinkName(mpCategory, (TimeCriterion)Index));
                break;

		case END_TODAY:
			sprintf(pFileLinks[Index], "<a href=\"%s\">Ending Today</a>",
                mpFileName->GetLinkName(mpCategory, (TimeCriterion)Index));
                break;

		case COMPLETED:
			sprintf(pFileLinks[Index], "<a href=\"%s\">Completed</a>",
                		mpFileName->GetLinkName(mpCategory, (TimeCriterion)Index, 1, mpApp->GetBuildDay()));
                break;

		case GOING:
			sprintf(pFileLinks[Index], "<a href=\"%s\">Going, Going, Gone</a>",
                mpFileName->GetLinkName(mpCategory, (TimeCriterion)Index));
                break;
		}
	}

	// print the links
	switch (mTimeStamp)
	{
	case LISTING:
		sprintf(Links, pTimeLinkFormat, "<strong>Current</strong>", pFileLinks[NEW_TODAY], pFileLinks[END_TODAY], pFileLinks[COMPLETED], pFileLinks[GOING]);
		break;

    case NEW_TODAY:
            sprintf(Links, pTimeLinkFormat, pFileLinks[LISTING], "<strong>New Today</strong>", pFileLinks[END_TODAY], pFileLinks[COMPLETED], pFileLinks[GOING]);
            break;

    case END_TODAY:
            sprintf(Links, pTimeLinkFormat, pFileLinks[LISTING], pFileLinks[NEW_TODAY], "<strong>Ending Today</strong>", pFileLinks[COMPLETED], pFileLinks[GOING]);
            break;

    case COMPLETED:
            sprintf(Links, pTimeLinkFormat, pFileLinks[LISTING], pFileLinks[NEW_TODAY], pFileLinks[END_TODAY], "<strong>Completed</strong>", pFileLinks[GOING]);
            break;

    case GOING:
            sprintf(Links, pTimeLinkFormat, pFileLinks[LISTING], pFileLinks[NEW_TODAY], pFileLinks[END_TODAY], pFileLinks[COMPLETED], "<strong>Going, Going, Gone</strong>");
            break;

	}

	*pOutputFile << Links;

	// clean up
	for (Index = LISTING; Index <= GOING; Index++)
	{
		delete [] pFileLinks[Index];
	}
}

void clsCategoryNavigator::PrintTitle(ostream* pOutputFile)
{
	char	Title[700];
	char	Previous[500];
	char	Next[500];
	char*	pListingType;

	Title[0] = '\0';

	switch (mTimeStamp)
	{
	case LISTING:
		pListingType = LIST_HEADING;
		break;

	case NEW_TODAY:
		pListingType = NEWTODAY_HEADING;
		break;

	case END_TODAY:
		pListingType = ENDTODAY_HEADING;
		break;

	case COMPLETED:
		pListingType = mCompletedHeading;
		break;

	case GOING:
		pListingType = GOING_HEADING;
		break;
	}

	if (mpCategory)
	{
		GetAncestorLinks(Title);
		GetSiblingLinks(Previous, Next);

		*pOutputFile << "<p><table border=1 cellspacing=0 width=\"100%\" bgcolor=\"#cccccc\">"
					 << "<tr><td align=center width=\"5%\">"
					 << Previous
					 << "</td>"
					 << "<td align=center width=\"90%\">"
					 << "<font size=4 face=\"arial, helvetica\"><strong>"
					 << Title
					 << "</strong></font></td>"
					 << "<td align=center width=\"5%\">"
					 << Next
					 << "</td></tr>"
					 << "<tr><td align=center width=\"100%\" colspan=3><font size=2>"
					 << pListingType
					 << "</font></td></tr></table>";
	}
	else
	{
		*pOutputFile << "<p><table border=1 cellspacing=0 width=\"100%\" bgcolor=\"#cccccc\">"
					 << "<tr><td align=center width=\"100%\">"
					 << "<font size=4 face=\"arial, helvetica\"><strong>"
					 << "<a name=categories>eBay Categories</a>"
					 << "</strong></font></td></tr>"
					 << "<tr><td align=center width=\"100%\"><font size=2>"
					 << pListingType
					 << "</font></td></tr></table>";
	}

}

void clsCategoryNavigator::PrintLinks(ostream* pOutputFile)
{
	char	Title[700];
	char	Previous[500];
	char	Next[500];

	GetAncestorLinks(Title);
	GetSiblingLinks(Previous, Next);

	*pOutputFile << "<p><table border=0 cellspacing=2 width=\"100%\">"
				 << "<tr><td align=center width=\"5%\">"
				 << Previous
				 << "</td>"
				 << "<td align=center width=\"90%\">"
				 << "<font size=4 face=\"arial, helvetica\"><strong>"
				 << Title
				 << "</strong></font></td>"
				 << "<td align=center width=\"5%\">"
				 << Next
				 << "</td></tr></table>";
}
