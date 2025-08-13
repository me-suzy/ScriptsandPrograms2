/*	$Id: clseBayGiftItemWidget.cpp,v 1.2.434.1 1999/08/01 02:51:25 barry Exp $	*/
//
//	File:	clseBayGiftItemWidget.cpp
//
//	Class:	clseBayGiftItemWidget
//
//	Author:	Mila Bird
//
//	Function:
//			Widget that emits gift item title and description.
//			This widget was derived from clseBayWidget by overriding
//			the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/24/98	mila	- Created
//


#include "widgets.h"
#include "clseBayGiftItemWidget.h"
#include "clsUserValidation.h"
#include <stdio.h>

// TODO - remove - unused?
static const char *sNewURL = "<img height=11 width=28 alt=\"[NEW!]\" src=\"http://pics.ebay.com/aw/pics/new.gif\">";

clseBayGiftItemWidget::clseBayGiftItemWidget(clsMarketPlace *pMarketPlace) : 
	clseBayWidget(pMarketPlace)
{
		mpItem = NULL;
		mColor[0] = '\0';
		mShowTitleBar = true;
		mShowDescription = true;
}


void clseBayGiftItemWidget::SetParams(vector<char *> *pvArgs)
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

		// try to handle this parameter
		if ((!handled) && (strcmp("color", cName)==0))
		{
			this->SetColor(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("showtitlebar", cName)==0))
		{
			this->SetShowTitleBar(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("showdescription", cName)==0))
		{
			this->SetShowDescription(strcmp(cValue,"true")==0);
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

bool clseBayGiftItemWidget::EmitHTML(ostream *pStream)
{
	char	*cleanTitle;
	char	*pNewDescription = NULL;

	
	// safety
	if (!mpItem) return false;
	if (!mpMarketPlace) return false;

	// Finalize the item
	mpItem->Finalize();

	*pStream  <<	"<p>";

	if (mShowTitleBar)
	{
		// Table tag for title and item #
		if (mColor[0]=='\0')
		{
			*pStream  <<	"<table border=\"1\" cellspacing=\"0\" "
							"width=\"100%\">\n";
		}
		else
		{
			*pStream  <<	"<table border=\"1\" cellspacing=\"0\" "
							"width=\"100%\" bgcolor=\""
					  <<	mColor
					  <<	"\">\n";
		}

		// The title and item #
		cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());
		if (!cleanTitle) return false;

		*pStream  <<	"  <tr>\n"
						"    <td align=\"center\" width=\"100%\">"
						"      <font color=\"#000000\">"
						"<b>"
				  <<	cleanTitle
				  <<	"</b></font>\n"
						"    </td>\n"
						"  </tr>\n"
						"</table>\n";

		delete [] cleanTitle;
	}
	else
	{
		// Title using black on darkgrey table

		// Begin table for title
		*pStream <<	"<center>"
					"<table border=\"0\" cellspacing=\"0\" "
					"width=\"100%\" bgcolor=\"#99CCCC\">\n"
					"  <tr>\n"
					"    <td align=\"center\" width=\"100%\">"
					"      <font size=\"4\" color=\"#000000\">"
					"<b>"
				 <<	mpItem->GetTitle()
				 <<	"</b></font>\n"
					"    </td>\n"
					"  </tr>\n";

		// end the table
		*pStream <<	"</table>\n"
					"</center>\n";
	}

	// Optional description
	if (mShowDescription)
	{
		if (mpItem->GetDescription() != NULL)
			pNewDescription	= clsUtilities::ChangeHTMLQuoteToQuote(mpItem->GetDescription());
		else
			pNewDescription	= NULL;

		// Table tag for description headline
		*pStream  <<	"<p><p><center>\n"
						"<table border=\"0\" cellspacing=\"0\" "
						"width=\"100%\">\n";

		*pStream  <<	"<blockquote>\n";

		if (pNewDescription)
			*pStream << pNewDescription;

		*pStream  <<	"\n</blockquote>\n";

		// end the table
		*pStream  <<	"</table>\n"
						"</center>\n";

		// We can get rid of these paranoid close tags once we put DrawSafeHTML back in
		*pStream  <<	"</blockquote>"
						"</blockquote>"
						"</center>"
						"</center>"
						"</strong>"
						"</pre>"
						"</em>"
						"</font>"
						"</dl>"
						"</ul>"
						"</li>"
						"</h1>"
						"</h2>"
						"</h3>"
						"</h4>"
						"</h5>"
						"</h6>"
						"\n";

		if (mpItem->GetPictureURL() != NULL)
		{
			*pStream <<		"<hr><img src="
							"\""
					  <<	mpItem->GetPictureURL()
					  <<	"\""
							">"
							"<p>";
		}

		delete [] pNewDescription;
	}
	
	*pStream  <<	"<p>";

	*pStream << flush;
	
	return true;
}

