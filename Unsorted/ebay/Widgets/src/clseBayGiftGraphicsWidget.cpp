/*	$Id: clseBayGiftGraphicsWidget.cpp,v 1.2.434.1 1999/08/01 02:51:25 barry Exp $	*/
//
//	File:	clseBayGiftGraphicsWidget.cpp
//
//	Class:	clseBayGiftGraphicsWidget
//
//	Author:	Mila Bird
//
//	Function:
//			Widget that emits gift card.
//			This widget was derived from clseBayWidget by overriding
//			the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/24/98	mila	- Created
//


#include "widgets.h"
#include "clseBayGiftGraphicsWidget.h"
#include "clsUserValidation.h"
#include <stdio.h>

// TODO - remove, unused?
static const char *sNewURL = "<img height=11 width=28 alt=\"[NEW!]\" src=\"http://pics.ebay.com/aw/pics/new.gif\">";

clseBayGiftGraphicsWidget::clseBayGiftGraphicsWidget(clsMarketPlace *pMarketPlace) : 
	clseBayWidget(pMarketPlace)
{
		mpFilename = NULL;
}


void clseBayGiftGraphicsWidget::SetParams(vector<char *> *pvArgs)
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

		if ((!handled) && (strcmp("filename", cName)==0))
		{
			this->SetImageFilename(cValue);
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
	clseBayWidget::SetParams(pvArgs);

}

bool clseBayGiftGraphicsWidget::EmitHTML(ostream *pStream)
{
	if (mpFilename == NULL)
		return false;

	// Center the image on the page
	*pStream  <<	"<center>";

	// Table tag for image
	*pStream  <<	"<table border=\"0\" cellspacing=\"0\" "
					"width=\"100%\">\n";

	// The image
	*pStream  <<	"<tr>\n"
					"<td align=\"center\" width=\"100%\">"
					"<img src=\""
			  <<	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetPicsPath()
			  <<	mpFilename
			  <<	"\">"
					"</td></tr>\n";

	// End table
	*pStream  <<	"</table></center>\n";

	*pStream << flush;
	
	return true;
}

