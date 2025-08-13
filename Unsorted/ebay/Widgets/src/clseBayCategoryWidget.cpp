/*	$Id: clseBayCategoryWidget.cpp,v 1.5.54.5.4.1 1999/08/05 18:58:46 nsacco Exp $	*/
//
//	File:	clseBayCategoryWidget.cpp
//
//	Class:	clseBayCategoryWidget
//
//	Author:	Chad Musick
//
//	Function:
//			Widget that shows categories.
//
// Modifications:
//				- 10/14/97	Chad - Created
//				- 12/18/98  poon - began changes to support category home pages
//

#include "widgets.h"
#include <stdio.h>
#include <time.h>
#include "clseBayCategoryWidget.h"


clseBayCategoryWidget::clseBayCategoryWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mId = 0;
	memset(mCategoryList, 0, sizeof(mCategoryList));
	memset(mMoreLink, 0, sizeof(mMoreLink));
	memset(mMoreText, 0, sizeof(mMoreText));
	mHowDeepToGo = 0;
	mIncludeParent = false;
	mAutoMoreLinks = true;
	memset(mFont, 0, sizeof(mFont));
	mFontSize = 0;
	mLinkNonLeaves = true;
	mShowItemCounts = true;
	memset(mLinkPrefix, 0, sizeof(mLinkPrefix));

}

clseBayCategoryWidget::~clseBayCategoryWidget()
{
	CategoryVector::iterator i;

	for (i = mvCategories.begin(); i != mvCategories.end(); ++i)
	{
		delete (*i);
	}

	mvCategories.erase(mvCategories.begin(),
		mvCategories.end());
}

void clseBayCategoryWidget::SetParams(vector<char *> *pvArgs)
{
	const char *pV;	// don't need to delete because it's just pointing to inside the vector

	if ((pV = GetParameterValue("categoryid", pvArgs)))
		SetCategoryId(atoi(pV));

	if ((pV = GetParameterValue("categorylist", pvArgs)))
		SetCategoryList(pV);

	if ((pV = GetParameterValue("moretext", pvArgs)))
		SetMoreText(pV);

	if ((pV = GetParameterValue("morelink", pvArgs)))
		SetMoreLink(pV);

	if ((pV = GetParameterValue("howdeeptogo", pvArgs)))
		SetHowDeepToGo(atoi(pV));

	if ((pV = GetParameterValue("includeparent", pvArgs)))
		SetIncludeParent(strcmp(pV,"true")==0);

	if ((pV = GetParameterValue("automorelinks", pvArgs)))
		SetAutoMoreLinks(strcmp(pV,"true")==0);

	if ((pV = GetParameterValue("font", pvArgs)))
		SetFont(pV);

	if ((pV = GetParameterValue("fontsize", pvArgs)))
		SetFontSize(atoi(pV));

	if ((pV = GetParameterValue("linknonleaves", pvArgs)))
		SetLinkNonLeaves(strcmp(pV,"true")==0);

	if ((pV = GetParameterValue("showitemcounts", pvArgs)))
		SetShowItemCounts(strcmp(pV,"true")==0);

	if ((pV = GetParameterValue("linkprefix", pvArgs)))
		SetLinkPrefix(pV);

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayTableWidget::SetParams(pvArgs);

}


// Get the categories
bool clseBayCategoryWidget::Initialize()
{

	// - all right, here's the deal:
	// - mId contains the category id of the root category that this
	//    widget should anchor from (it will be 0 if all we want to do is show
	//    top-level categories). 
	// - mNumItems will contain the total number of categories that the
	//    client wants us to show. 
	// - so what we'll do here is first get ALL descendants of the root.
	//    Then, then we need to randomly discard categories starting from the
	//    lowest level categories until we have the right number of items.
	// - in the case that there just aren't enough
	//    categories to satisfy mNumItems, we'll just show what we've got
	//    and adjust mNumItems down accordingly.

	clsCategories*		pCategories;
	clsCategory*		pParent = NULL;
	clsCategory*		pCategory = NULL;
	int					j,k,counts[5];
	CategoryVector::iterator i;
	time_t		CurrentTime;
	int x;
	int maxDepth = 4;	// maximum depth in category tree to consider

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	// get the clsCategories structure (but don't delete it of course, because as
	//  always, it's cached in mpMarketPlace, who will delete it eventually).
	pCategories = mpMarketPlace->GetCategories();
	if (!pCategories)
		return false;

	// NEW: if the caller has specified a list of categoryids (space delimited), then
	//  use that list instead of retrieving the list from the database
	if (mCategoryList[0] != '\0')
	{
		char* cCatId;
		char cCategoryList[256];
		const char cSeps[] = " ,;/\t\r\n";	// valid delimiters

		if (mpLoggingStream)
		{
			t = time(0);
			clsUtilities::GetDateAndTime(t, pDate, pTime);
			*mpLoggingStream << pDate << " " << pTime << " Start Getting Categories from Static List\n";
		}

		// make a copy so we that strtok doesn't alter mCategoryList
		strcpy(cCategoryList, mCategoryList);

		// get the first 
		cCatId = strtok(cCategoryList, cSeps);

		// for each category id, add the category to the vector
		while (cCatId != NULL)
		{
			pCategory = pCategories->GetCategory(atoi(cCatId));
			if (pCategory) 
				mvCategories.push_back(pCategory);
			cCatId = strtok(NULL, cSeps);	// next
		}

		// pCategories->ClearOpenItemCounts();		// clear item count cache
		mNumItems = mvCategories.size();
		if (strlen(mMoreText)) 
			++mNumItems;

		if (mpLoggingStream)
		{
			t = time(0);
			clsUtilities::GetDateAndTime(t, pDate, pTime);
			*mpLoggingStream << pDate << " " << pTime << " End Getting Categories from Static List\n\n";
		}

		return true;
	}

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " Start Getting Category Hierarchy from Category " << mId << "\n";
	}

	// get the current time
	CurrentTime = time(0);

	// seed the random number generator with the current time
	srand((unsigned int)CurrentTime);

	// get the root category
	if (mId) 
		pParent = pCategories->GetCategory(mId);

	// add the parent itself if asked too
	if ((pParent) && (mIncludeParent))
		mvCategories.push_back(pParent);

	// get all the descendants, ordered & depth-first
	pCategories->DescendantsOrdered(&mvCategories, pParent);

	// ok, now we have all descendants of the root category, ordered in
	//  a depth-first manner. now we've got to elminate some of the categories,
	//  starting from the leaves, until we have the count specified in mNumItems.

	// first let's count 'em all up by level
	for (j=0; j<5; j++) 
		counts[j]=0;
	for (i = mvCategories.begin(); i != mvCategories.end(); ++i)
		counts[(*i)->catLevel()]++;

	// in case we don't have enough categories, or if mNumItems==0, let's
	//  reset mNumItems to be all of 'em
	if ((mvCategories.size() < mNumItems) || (mNumItems==0)) 
		mNumItems = mvCategories.size();

	// let's calculate how deep we should be allowed to go (if there a restriction has been speicfied at all)
	if (mHowDeepToGo)
	{
		if (pParent)
			maxDepth = pParent->catLevel() + mHowDeepToGo;
		else 
			maxDepth = 0 + mHowDeepToGo;
	}

	// now let's go level-by-level, deleting randomly until we've deleted enough,
	//  starting with the lowest level.
	for (j=4; j>=0; j--)
	{
		// check to see if we should just get rid of all categories on this level.
		// this will be the case if
		//  without them, we'd still have too many -or-
		//  maxDepth says to not go this deep
		if (((mvCategories.size() - counts[j]) >= mNumItems) ||
			(j > maxDepth))
		{
			// ok, none at this level will be retained, so kill 'em
			for (k=mvCategories.size()-1; k>=0; k--)
			{
				pCategory = mvCategories[k];

				// kill it if it's the right category level
				if (pCategory->catLevel() == j)
				{
					mvCategories.erase(mvCategories.begin()+k);		// remove it from the vector
					delete pCategory;	// don't need the category anymore
				}
			}
		}
		else
		{
			// ok, so only *some* of the categories at this level need to be ridded.

			// randomly get rid of them until we've killed enough of 'em
			while (mvCategories.size() > mNumItems)
			{
				x = rand() % mvCategories.size();

				pCategory = mvCategories[x];

				// kill it if it's the right category level
				if (pCategory->catLevel() == j)
				{
					mvCategories.erase(mvCategories.begin()+x);		// remove it from the vector
					delete pCategory;	// don't need the category anymore
				}
			}

			break;	// don't bother checking higher levels

		}
	}

	// pCategories->ClearOpenItemCounts();
	mNumItems = mvCategories.size();
	if (strlen(mMoreText)) 
		++mNumItems;

	// delete pParent if it wasn't added to the category vector
	if ((pParent) && (!mIncludeParent)) 
		delete pParent;

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " End Getting Category Hierarchy from Category " << mId << "\n\n";
	}

	return true;
}

// This will be called mNumItems times n=0..mNumItems-1
bool clseBayCategoryWidget::EmitCell(ostream *pStream, int n)
{
	const char *linkPath = NULL;
	char *customLinkPath = NULL;
	int i, j, indentLevel, fontSize;
	bool linkCategory;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	// hack to hide firearms
	if (mvCategories[n]->GetId()==2037)
	  return true;

	// check to see if this is the last cell and if it should be just the morelink for the whole widget
	if (strlen(mMoreText) && (n == (mNumItems - 1)))
	{

		*pStream	 <<	"<TD>";

		// emit begin tags if user supplied them
		if (mBeginTags[0]!='\0')
			*pStream	<<	mBeginTags;

		*pStream	 <<	"<FONT size = \"2\">"
						"<b><EM>&nbsp;"
						"<a href=\""
					 << mMoreLink
					 << "\">"
					 <<	mMoreText
					 <<	"</A></EM></b></FONT>";

		// emit end tags if user supplied them
		if (mEndTags[0]!='\0')
			*pStream	<<	mEndTags;	

		*pStream	 <<	"</TD>\n";

		return true;
	}

	*pStream	<<	"<TD valign=\"top\">";	// begin the cell

	// emit begin tags if user supplied them
	if (mBeginTags[0]!='\0')
		*pStream	<<	mBeginTags;

	// indent subcategories (this code assumes the first category listed
	//  is the "top" level category)
	indentLevel = mvCategories[n]->catLevel()-mvCategories[0]->catLevel();

	for (i=0; i<indentLevel; i++)
		*pStream << "&nbsp;&nbsp;";

	// font size
	fontSize = mFontSize ? mFontSize : 3;

	// determine whether or not should link the category
	linkCategory = false;
	if ((mvCategories[n]->isLeaf()) || ((!mvCategories[n]->isLeaf()) && (mLinkNonLeaves)))
		linkCategory = true;

	if (linkCategory)
	{
		// HACK to get some categories to point to the category index pages rather
		//  than the normal listings page. 
		switch (mvCategories[n]->GetId())
		{
		// kakiyama 07/18/99
		char tempLinkPath[512];

		case 220:       // toys
		//	linkPath="http://pages.ebay.com/toys-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "toys-index.html");
			linkPath = tempLinkPath;
			break;
		case 160:       // computers
		//	linkPath="http://pages.ebay.com/computer-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "computer-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 237:       // dolls
		//	linkPath="http://pages.ebay.com/dolls-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "dolls-index.html");
			linkPath = tempLinkPath;
			break;
		case 281:       // jewelry
		//	linkPath="http://pages.ebay.com/jewelry-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "jewelry-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 866:       // coins and stamps
		//	linkPath="http://pages.ebay.com/coins-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "coins-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 353:       // antiques
		//	linkPath="http://pages.ebay.com/antiques-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "antiques-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 266:       // books
		//	linkPath="http://pages.ebay.com/books-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "books-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 1: // collectibles
		//	linkPath="http://pages.ebay.com/collectibles-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "collectibles-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 1047:      // photo
		//	linkPath="http://pages.ebay.com/photo-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "photo-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 888:       // sports
		//	linkPath="http://pages.ebay.com/sports-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "sports-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 870:       // pottery
		//	linkPath="http://pages.ebay.com/pottery-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "pottery-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 99:        // misc
		//	linkPath="http://pages.ebay.com/misc-index.html";
		// kakiyama 07/18/99

			break;
			
			
		default:        // the NORMAL way to get the linkpath
			
			if (mLinkPrefix[0] == '\0')
			{
				// Note: don't delete linkPath, because mpListingFileName owns the memory.
				// linkPath = mpMarketPlace->GetCategories()->GetRelativeLinkPath(mvCategories[n]);
				linkPath = mpMarketPlace->GetCategories()->GetLinkPath(mvCategories[n]);
			}
			else
			{
				// use mLinkPrefix instead of the mpListingFileName method
				customLinkPath = new char[512];
				sprintf(customLinkPath, "%scategory%d/index.html", mLinkPrefix, mvCategories[n]->GetId());
				linkPath = customLinkPath;
			}
			break;
		}
	}

	// Emit font size and font
	*pStream	<<	"<FONT size=\""
				<<	fontSize
				<<	"\"";

	if 	(mFont[0] != '\0')
	{
		*pStream	<<	" face=\""
					<<	mFont
					<<	"\"";
	}

	*pStream	<<	">";	// end font tag

	// print the ahref link
	if (linkCategory)
	{
		*pStream	<<	"<a href=\""
					<<	linkPath
					<<	"\">";
	}

	// draw the category name
	*pStream	<<	((indentLevel == 0) ? "<b>" : "")
				<<	mvCategories[n]->GetName();

	if (linkCategory)
		*pStream	<<	"</a>";

	*pStream	<<	((indentLevel == 0) ? "</b>" : "")
				<<	"</FONT>";

	// show item counts if asked for
	if (mShowItemCounts)
	{
		if (mpLoggingStream)
		{
			t = time(0);
			clsUtilities::GetDateAndTime(t, pDate, pTime);
			*mpLoggingStream << pDate << " " << pTime << " Start Getting Item Count for Category " << mvCategories[n]->GetId() << "\n";
		}

		*pStream	<<	"<FONT size = \"1\">"
						" ("
					<<	mvCategories[n]->GetItemCountStillOpen()
					<<	")"
					<<	"</FONT>";

		if (mpLoggingStream)
		{
			t = time(0);
			clsUtilities::GetDateAndTime(t, pDate, pTime);
			*mpLoggingStream << pDate << " " << pTime << " End Getting Item Count for Category " << mvCategories[n]->GetId() << "\n\n";
		}

	}

	// decide if we should provide a more... link by peeking at the next line's indention level
	if (mAutoMoreLinks && mLinkNonLeaves)
	{
		if ((n+1 >= mvCategories.size()) || (mvCategories[n+1]->catLevel() < mvCategories[n]->catLevel()))	// next line is a higher level category
		{
			// don't print the more... link if this is a 0 indention category
			if (indentLevel > 0)
			{
				// look back up the list to see what the link path should be of the more... link
				for (j=n; j>=0; j--)
					if (mvCategories[j]->catLevel() == (mvCategories[n]->catLevel()-1)) 
						break;
			
				*pStream	<<	"<br>";

				for (i=0; i<indentLevel; i++)
					*pStream << "&nbsp;&nbsp;";	// indent

				*pStream	<<	"<FONT size = \"2\">"
							<<	"<a href=\""
							<<	mpMarketPlace->GetCategories()->GetLinkPath(mvCategories[j])
							<<	"\">"
							<<	"more..."
							<<	"</a></font>";
			}
		}
	}

	// emit end tags if user supplied them
	if (mEndTags[0]!='\0')
		*pStream	<<	mEndTags;					
					
	*pStream	<<	"</TD>\n";	// finish off this cell

	// deallocate if we made our own
	delete [] customLinkPath;

	return true;
}

