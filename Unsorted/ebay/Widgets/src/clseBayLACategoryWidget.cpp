/*<tab>$Id: clseBayLACategoryWidget.cpp,v 1.1.4.3.96.3 1999/08/09 18:45:04 nsacco Exp $<tab>*/
//
//	File:	clseBayLACategoryWidget.cpp
//
//	Class:	clseBayLACategoryWidget
//
//	Author:	Janet Nace
//
//	Function:
//			Widget that shows categories and item count per category for
//			the L.A. region.
//			The main trick here is to handle the L.A.-specific top level
//			categories. These are different than the standard categories in
//			the database, so we doctor them up here.
//			For all other categories, the only thing we do differently than
//			clseBayCategoryWidget is that we limit the item count to the L.A. region.
//
// Modifications:
//				- 04/23/99 jnace	- Created

#include "widgets.h"
#include "clseBayLACategoryWidget.h"

// TODO - should the GetPath functions really be used here? How will the browsing of LA pages
// work from say the UK site?

//
// Constructor
//
clseBayLACategoryWidget::clseBayLACategoryWidget(clsMarketPlace *pMarketPlace) :
	clseBayCategoryWidget(pMarketPlace)
{
	LATopLevelCategory category;

	category.mId = 1254;						// Automotive
	category.mName = "Automotive";
	category.mChildCategoryIds.push_back(292);
	category.mChildCategoryIds.push_back(1258);
	category.mChildCategoryIds.push_back(1259);
	category.mChildCategoryIds.push_back(1260);
	category.mChildCategoryIds.push_back(1255);
	category.mChildCategoryIds.push_back(1256);
	category.mChildCategoryIds.push_back(422);
	category.mChildCategoryIds.push_back(2029);
	mvLATopLevelCategories.push_back(category);
	// clean up our temp storage
	category.mChildCategoryIds.erase(category.mChildCategoryIds.begin(), category.mChildCategoryIds.end());

	category.mId = 1305;						// Entertainment
	category.mName = "Entertainment";
	category.mChildCategoryIds.push_back(1305);	// standard Tickets category
	category.mChildCategoryIds.push_back(233);
	category.mChildCategoryIds.push_back(304);
	category.mChildCategoryIds.push_back(380);
	category.mChildCategoryIds.push_back(1310);
	mvLATopLevelCategories.push_back(category);
	category.mChildCategoryIds.erase(category.mChildCategoryIds.begin(), category.mChildCategoryIds.end());

	category.mId = 2032;						// Garden
	category.mName = "Garden";
	category.mChildCategoryIds.push_back(519);
	category.mChildCategoryIds.push_back(1509);
	category.mChildCategoryIds.push_back(2034);
	category.mChildCategoryIds.push_back(2035);
	mvLATopLevelCategories.push_back(category);
	category.mChildCategoryIds.erase(category.mChildCategoryIds.begin(), category.mChildCategoryIds.end());

	category.mId = 1607;						// Real Estate & Home Furnishings
	category.mName = "Real Estate & Home Furnishings";
	category.mChildCategoryIds.push_back(1607);	// standard Real Estate category
	category.mChildCategoryIds.push_back(1280);
	category.mChildCategoryIds.push_back(12);
	category.mChildCategoryIds.push_back(293);
	category.mChildCategoryIds.push_back(13);
	category.mChildCategoryIds.push_back(27);
	mvLATopLevelCategories.push_back(category);
	category.mChildCategoryIds.erase(category.mChildCategoryIds.begin(), category.mChildCategoryIds.end());

	category.mId = 382;							// Sporting Goods
	category.mName = "Sporting Goods";
	category.mChildCategoryIds.push_back(310);
	category.mChildCategoryIds.push_back(1291);
	category.mChildCategoryIds.push_back(2022);
	category.mChildCategoryIds.push_back(2023);
	category.mChildCategoryIds.push_back(1292);
	category.mChildCategoryIds.push_back(2020);
	category.mChildCategoryIds.push_back(1521);
	category.mChildCategoryIds.push_back(1492);
	category.mChildCategoryIds.push_back(2024);
	category.mChildCategoryIds.push_back(1513);
	category.mChildCategoryIds.push_back(2021);
	category.mChildCategoryIds.push_back(383);
	category.mChildCategoryIds.push_back(1299);
	category.mChildCategoryIds.push_back(1300);
	category.mChildCategoryIds.push_back(1301);
	category.mChildCategoryIds.push_back(1302);
	category.mChildCategoryIds.push_back(1303);
	mvLATopLevelCategories.push_back(category);
	category.mChildCategoryIds.erase(category.mChildCategoryIds.begin(), category.mChildCategoryIds.end());
}

//
// Destructor
//
clseBayLACategoryWidget::~clseBayLACategoryWidget()
{
	mvLATopLevelCategories.erase(mvLATopLevelCategories.begin(), mvLATopLevelCategories.end());
}

// This will be called mNumItems times n=0..mNumItems-1
// This overrides the EmitCell routine in clseBayCategoryWidget such that:
//   - we use the L.A. URLs for the category index pages
//   - some of the category item counts are assembled from multiple categories (e.g. Automotive)
//   - some of the category names on the L.A. home page are doctored up
bool clseBayLACategoryWidget::EmitCell(ostream *pStream, int n)
{
	const char *linkPath = NULL;
	char *customLinkPath = NULL;
	int i, j, indentLevel, fontSize;
	int itemCount;
	bool linkCategory;
	bool bLATopLevelCategory;						// whether an L.A.-specific top level category
	vector<LATopLevelCategory>::iterator catIter;	// category iterator
	vector<short>::iterator childCatIter;			// child category iterator
	clsCategories *pCategories;
	clsCategory *pCategory;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	// check to see if this is the last cell and if it should be just the morelink for the whole widget
	if (strlen(mMoreText) && (n == (mNumItems - 1)))
	{

		*pStream	 <<	"<TD>"
						"<FONT size = \"2\">"
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

	bLATopLevelCategory = false;
	// mIncludeParent is always 0 for the home page, else non-0 for the cat index pages
	if (!mIncludeParent)
	{
		// see if this is a top level category specific to L.A.
		for (catIter = mvLATopLevelCategories.begin();
		     catIter != mvLATopLevelCategories.end();
			 catIter++)
		{
			if (catIter->mId == mvCategories[n]->GetId())
			{
				bLATopLevelCategory = true;
				break;
			}
		}
	}

	// set indent level to 0 for L.A. top level categories (or cat 1258, etc.)
	if (bLATopLevelCategory ||
		mvCategories[n]->GetId() == 1258 ||
		mvCategories[n]->GetId() == 1259 ||
		mvCategories[n]->GetId() == 1260 ||
		(mvCategories[n]->GetId() == 1280 && n == 1)) // only on realestate cat index page
		indentLevel = 0;
	else
	{
		// indent subcategories (this code assumes the first category listed
		//  is the "top" level category)
		indentLevel = mvCategories[n]->catLevel()-mvCategories[0]->catLevel();
	}

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
		//	linkPath="http://pages.ebay.com/la/la-toys-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-toys-index.html");
			linkPath = tempLinkPath;
			break;

		case 160:       // computers
		//	linkPath="http://pages.ebay.com/la/la-computers-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-computers-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 237:       // dolls
		//	linkPath="http://pages.ebay.com/la/la-dolls-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-dolls-index.html");
			linkPath = tempLinkPath;
			break;

		case 281:       // jewelry
		//	linkPath="http://pages.ebay.com/la/la-jewelry-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-jewelry-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 866:       // coins and stamps
		//	linkPath="http://pages.ebay.com/la/la-coins-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-coins-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 353:       // antiques
		//	linkPath="http://pages.ebay.com/la/la-antiques-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-antiques-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 266:       // books
		//	linkPath="http://pages.ebay.com/la/la-books-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-books-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 1:         // collectibles
		//	linkPath="http://pages.ebay.com/la/la-collectibles-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-collectibles-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 1047:      // photo
		//	linkPath="http://pages.ebay.com/la/la-photo-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-photo-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 888:       // sports
		//	linkPath="http://pages.ebay.com/la/la-sports-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-sports-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 870:       // pottery
		//	linkPath="http://pages.ebay.com/la/la-pottery-index.html";
		// kakiyama 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-pottery-index.html");
			linkPath = tempLinkPath;
			break;
			
		case 99:        // misc
		//	linkPath="http://pages.ebay.com/la/la-misc-index.html";
		// kakiyam 07/18/99
			strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
			strcat(tempLinkPath, "la/la-misc-index.html");
			linkPath = tempLinkPath;
			break;


		default:
			if (bLATopLevelCategory)
			{
				switch (mvCategories[n]->GetId())
				{
				// kakiyama 07/18/99
				char tempLinkPath[512];

				case 1254:
				//	linkPath="http://pages.ebay.com/la/la-auto-index.html";
				// kakiyama 07/18/99
					strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
					strcat(tempLinkPath, "la/la-auto-index.html");
					linkPath = tempLinkPath;
					break;

				case 1305:
				//	linkPath="http://pages.ebay.com/la/la-entertainment-index.html";
				// kakiyama 07/18/99
					strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
					strcat(tempLinkPath, "la/la-entertainment-index.html");
					linkPath = tempLinkPath;
					break;

				case 2032:
				//	linkPath="http://pages.ebay.com/la/la-garden-index.html";
				// kakiyama 07/18/99
					strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
					strcat(tempLinkPath, "la/la-garden-index.html");
					linkPath = tempLinkPath;
					break;

				case 1607:
				//	linkPath="http://pages.ebay.com/la/la-realestate-index.html";
				// kakiyama 07/18/99
					strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
					strcat(tempLinkPath, "la/la-realestate-index.html");
					linkPath = tempLinkPath;
					break;

				case 382:
				//	linkPath="http://pages.ebay.com/la/la-sporting-index.html";
				// kakiyama 07/18/99
					strcpy(tempLinkPath, mpMarketPlace->GetHTMLPath());
					strcat(tempLinkPath, "la/la-sporting-index.html");
					linkPath = tempLinkPath;
					break;
				}
			}
			else if (mLinkPrefix[0] == '\0')
			{
				// use local-listings instead of listings for L.A.
				// TODO - fix this?
				customLinkPath = new char[512];
				sprintf(customLinkPath, "http://local-listings.ebay.com/aw/listings/list/category%d/index.html", mvCategories[n]->GetId());
				linkPath = customLinkPath;
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
	*pStream	<<	"<FONT size = \""
				<<	fontSize
				<<	"\"";

	if 	(mFont[0] != '\0')
	{
		*pStream	<<	" font = \""
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

	// for L.A. home page, we don't want <b>, I assume to save space on the page.
	// mIncludeParent is always 0 for the home page, else non-0 for the cat index pages
	*pStream	<<	(((indentLevel == 0) && mIncludeParent) ? "<b>" : "");

	// draw the category name
	// For L.A. top level categories, use the name defined above
	if (bLATopLevelCategory)
		*pStream	<<	catIter->mName;
	else if (strcmp(mvCategories[n]->GetName(), "Toys & Beanies Plush") == 0)
		*pStream	<<	"Toys & Beanies";
	else
		*pStream	<<	mvCategories[n]->GetName();

	if (linkCategory)
		*pStream	<<	"</a>";

	*pStream	<<	(((indentLevel == 0) && mIncludeParent) ? "</b>" : "")
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

		if (!bLATopLevelCategory)
		{
			// get the open item count for this category & its subcategories
			itemCount = mvCategories[n]->GetItemCountStillOpen(1);	// 1 = L.A. region
		}
		else
		{
			// get the global categories in case we need to count categories
			//  that aren't part of mvCategories
			pCategories = gApp->GetMarketPlaces()->
				GetCurrentMarketPlace()->GetCategories();

			itemCount = 0;
			// for L.A.-specific top level categories, add the item counts
			//  for the child categories defined above
			for (childCatIter = catIter->mChildCategoryIds.begin();
				 childCatIter != catIter->mChildCategoryIds.end();
				 childCatIter++)
			{
				// 1st parm = id of child category
				// 2nd parm = true to use cache (faster & don't have to delete pCategory)
				pCategory = pCategories->GetCategory(*childCatIter, true);
				// get the open item count for this category & its subcategories
				itemCount += pCategory->GetItemCountStillOpen(1);	// 1 = L.A. region
			}
		}

		*pStream	<<	"<FONT size = \"1\">"
						" ("
					<<	itemCount
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
			if (indentLevel)
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
