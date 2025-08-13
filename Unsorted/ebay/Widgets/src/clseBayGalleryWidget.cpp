/*	$Id: clseBayGalleryWidget.cpp,v 1.1.4.1.78.1 1999/08/01 02:51:24 barry Exp $	*/
//
//	File:	clseBayGalleryWidget.cpp
//
//	Class:	clseBayGalleryWidget
//
//	Author:	Bill Wang
//
//	Function:
//			Widget that displays a random set of Gallery pictures.
//
// Modifications:
//				- 05/25/99	Bill - Created
//				- 07/01/99	nsacco - use GetPicsPath() for urls
//
#include "widgets.h"
#include "clseBayGalleryWidget.h"

#define	ImagesURL	"http://thumbs.ebay.com/pict/"

clseBayGalleryWidget::clseBayGalleryWidget(clsMarketPlace *pMarketPlace) :
	clseBayItemWidget(pMarketPlace)
{
	mCatId = 0;
	mPictureHeight = 50;
	mPictureWidth = 50;
}

clseBayGalleryWidget::~clseBayGalleryWidget()
{
}

void clseBayGalleryWidget::SetParams(vector<char *> *pvArgs)
{

	int p;
	char *cArg;
	char cArgCopy[256];
	char *cName;
	char *cValue;
	bool handled = false;
	int x;

	// reverse through these so that deletions are safe.
	//  stop at 1, because we don't care about the tagname
	for (p=pvArgs->size()-1; p>=1; p--)
	{
		cArg = (*pvArgs)[p];
		handled = false;

		// separate the name from the value
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

		// remove start & end quotes if they were provided
		x = strlen(cValue);
		if ((x>1) && (cValue[0]=='\"' && cValue[x-1]=='\"'))
		{
			cValue[x-1]='\0';		// remove ending "
			cValue++;				// remove beginning "
		}

		// try to handle this arameter
		if ((!handled) && (strcmp("categoryid", cName)==0))
		{
			SetCategoryId(atoi(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("pictureheight", cName)==0))
		{
			SetPictureHeight(atoi(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("picturewidth", cName)==0))
		{
			SetPictureWidth(atoi(cValue));
			handled=true;
		}

		// if this parameter was handled, remove (and delete the char*) it from the vector
		if (handled)
		{
			pvArgs->erase(pvArgs->begin()+p);	
			delete [] cArg;	// don't need the parameter anymore
		}

	}

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayItemWidget::SetParams(pvArgs);

}

// Retrieve ids of all current items and stuff them into pvItemIds.
void clseBayGalleryWidget::GetItemIds(vector<int> *pvItemIds)
{

	clsItems	*pItems = NULL;
	time_t		CurrentTime;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	CurrentTime = time(0);

	if (mpMarketPlace) pItems = mpMarketPlace->GetItems();

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " Start Getting Gallery Item Ids " << mCatId << "\n";
	}

	// if (pItems) pItems->GetActiveItemIds(pvItemIds, CurrentTime, mCatId);
	// there is an optional parameter for item count at the end, though it's currently disabled in the kernel
	if (pItems) 
		pItems->GetGalleryListItemIds(pvItemIds, CurrentTime, mCatId) ;

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " End Getting Gallery Ids " << mCatId << "\n\n";
	}

}


// This will be called NumItems times n=0..NumItems-1
bool clseBayGalleryWidget::EmitCell(ostream *pStream, int n)
{
	clsItem		*pItem;

	char		*cSuperCleanText = NULL;
	char		*cDelimitedSuperCleanText = NULL;
	char		*cSafeDelimitedSuperCleanText = NULL;
	char		cName[255];

	// get the item from the vector that Initialize() prepared for me
	pItem = mvItems[n];


	// check if we want to use the generic name
	if (strlen(GetItemName()) > 0)
	{
		sprintf(cName, "%s%d", GetItemName(), n+1);
	}
	else
	{
		// make the text super-clean
		cSuperCleanText = clsUtilities::SuperClean(pItem->GetTitle());

		// delimit the text in case it's one big word
		cDelimitedSuperCleanText = clsUtilities::Delimit(cSuperCleanText);

		// make the text safe
		cSafeDelimitedSuperCleanText = clsUtilities::StripHTML(cDelimitedSuperCleanText);

		strncpy(cName, cSafeDelimitedSuperCleanText, sizeof(cName)-1);
	}

	// output the item
	*pStream <<		"<td valign=\"TOP\">";
	
	*pStream <<		"<a href=\""
			 <<		mpMarketPlace->GetCGIPath(PageViewItem)
			 <<		"eBayISAPI.dll?ViewItem&item="
			 <<		pItem->GetId()			// Item ID
			 <<		"\">"
			 <<		"<img src=\""
			 <<		ImagesURL				// Imgae URL
			 <<		pItem->GetId()
			 <<		".jpg\" border=0 alt=\""
			 <<		cName					// Item Name
			 <<		"\" width="
			 <<		mPictureWidth			// Width of  the thumbnail
			 <<		" height="
			 <<		mPictureHeight			// Height of the thumbnail
			 <<		"></a><br>";

	*pStream <<		"</td>\n";

	// output the spacer gif picture
	*pStream <<		"<td>";
	
	*pStream <<		"<img src=\""
			 <<		mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			 <<		"home/spacer.gif\" "
			 <<		"width=25 height=1 alt=\"\" border=\"0\"><br>";

	*pStream <<		"</td>\n";


	// delete the new strings
	delete [] cSuperCleanText;
	delete [] cDelimitedSuperCleanText;
	delete [] cSafeDelimitedSuperCleanText;

	return true;
}
	