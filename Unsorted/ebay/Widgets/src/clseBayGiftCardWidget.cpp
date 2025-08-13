/*	$Id: clseBayGiftCardWidget.cpp,v 1.3.402.1 1999/08/01 02:51:25 barry Exp $	*/
//
//	File:	clseBayGiftCardWidget.cpp
//
//	Class:	clseBayGiftCardWidget
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
//				- 11/03/98	mila	- Changed type of mOccasion to int from
//									  GiftOccasionEnum
//				- 11/05/98 mila		- Added base64-encoding of item number and open date
//				- 01/28/99 mila		- Made sender's user ID and name that are included in
//									  gift item URL safe by replacing any '&' with '%26'
//


#include "widgets.h"
#include "clseBayGiftCardWidget.h"
#include "clsUserValidation.h"
#include "clsBase64.h"

#include <stdio.h>
#include <time.h>

// TODO - remove, unused?
static const char *sNewURL = "<img height=11 width=28 alt=\"[NEW!]\" src=\"http://pics.ebay.com/aw/pics/new.gif\">";

clseBayGiftCardWidget::clseBayGiftCardWidget(clsMarketPlace *pMarketPlace) : 
	clseBayWidget(pMarketPlace)
{
//		mpOccasion = NULL;
		memset(mpColor, 0, sizeof(mpColor));

		mOccasion = 0;
		mpGreeting = NULL;
		mpSenderUserId = NULL;
		mpSenderName = NULL;
		mpRecipientName = NULL;
		mItem = 0;
		mOpenDate = 0;
		mpFilename = NULL;
		mEncodeItemInURL = false;
		mEncodeOpenDateInURL = false;
}

void clseBayGiftCardWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("occasion", cName)==0))
		{
			this->SetGreeting(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("greeting", cName)==0))
		{
			this->SetGreeting(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("senderuserid", cName)==0))
		{
			this->SetSenderUserId(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("sendername", cName)==0))
		{
			this->SetSenderName(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("recipientname", cName)==0))
		{
			this->SetRecipientName(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("item", cName)==0))
		{
			this->SetItem(atoi(cValue));
			handled=true;
		}
		if ((!handled) && (strcmp("opendate", cName)==0))
		{
			this->SetOpenDate(atoi(cValue));
			handled=true;
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

bool clseBayGiftCardWidget::EmitHTML(ostream *pStream)
{
	struct tm *	pTmOpenDate;

	char		cItemNo[32];
	char		cOpenDate[32];

	char *		pSenderUserId;	// URL-safe string
	char *		pSenderName;	// URL-safe string

	clsBase64 *	pBase64 = NULL;

	if (mpGreeting != NULL)
	{
		*pStream  <<	"<center>\n";

		// Table tag for greeting
		if (mpColor[0]=='\0')
		{
			*pStream  <<	"<table border=\"0\" cellspacing=\"0\" "
							"width=\"100%\">\n";
		}
		else
		{
			*pStream  <<	"<table border=\"0\" cellspacing=\"0\" "
							"width=\"100%\" bgcolor=\""
					  <<	mpColor
					  <<	"\">\n";
		}

		// The to: info
		if (mpRecipientName != NULL)
		{
			*pStream  <<	"  <tr>\n"
							"    <td align=\"center\" width=\"100%\">"
							"      <font size=\"4\" color=\"#000000\">"
							"<b>"
							"to "
							"<br>";

			// Now put it on the stream
			*pStream <<	mpRecipientName;
		}

		*pStream  <<	"</b></font>\n"
						"    </td>\n"
						"  </tr>\n"
						"</table>\n"
						"</center>\n";
	}

	*pStream <<	"<p><p>\n";

	if (mpFilename != NULL)
	{
		// Begin table for gift pic
		*pStream <<	"<center>"
					"<table border=\"0\" cellspacing=\"0\" width=\"100%\">\n"
					"  <tr>\n"
					"    <td align=\"center\" width=\"100%\">\n";

		if (mItem > 0)
		{
			//Replace any '&' with "%26" in sender's user ID
			pSenderUserId = clsUtilities::MakeSafeString(mpSenderUserId);

			//Replace any '&' with "%26" in sender's name
			pSenderName = clsUtilities::MakeSafeString(mpSenderName);
			clsUtilities::ReplaceSpacesWithUnderscores(pSenderName);

			// Set up link for gift pic.
			*pStream <<	"      <a href=\""
					 <<	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCGIPath(PageViewGiftItem)
					 << "eBayISAPI.dll?ViewGiftItem&uid="
					 << pSenderUserId
					 << "&sn="
					 << pSenderName
					 << "&iid=";

			delete [] pSenderUserId;
			delete [] pSenderName;

			if (mEncodeItemInURL)
			{
				sprintf(cItemNo, "%d", mItem);
				if (pBase64 == NULL)
				{
					pBase64 = new clsBase64;
				}

				*pStream << pBase64->Encode(cItemNo, strlen(cItemNo));
			}
			else
			{
				*pStream << mItem;
			}

			*pStream << "&od=";

			if (mEncodeOpenDateInURL)
			{
				sprintf(cOpenDate, "%d", mOpenDate);
				if (pBase64 == NULL)
				{
					pBase64 = new clsBase64;
				}

				*pStream << pBase64->Encode(cOpenDate, strlen(cOpenDate));
			}
			else
			{
				*pStream << mOpenDate;
			}

			*pStream << "&occ="
					 << mOccasion;

			*pStream << "\">"
					 << "<img src=\""
					 <<	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetPicsPath()
//					 << "file:C:\\E108_gift_alert\\pics\\"
					 << mpFilename
					 << "\" alt=\"Wrapped Gift\">"
					 << "</a>\n";
		}
		else
		{
			// Set up link for gift pic.
			*pStream <<	"      <img src=\""
					 <<	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetPicsPath()
//					 << "file:C:\\E108_gift_alert\\pics\\"
					 << mpFilename
					 << "\" alt=\"Wrapped Gift\">\n";
		}

		*pStream << "    </td>\n"
					"  </tr>\n"
					"  <tr>\n"
					"    <td align=\"center\" width=\"100%\">\n"
					"      <font size=\"2\" color=\"#000000\">";

		// Open/wait message
		if (mOpenDate <= time(0))
		{
			*pStream << "(Click on the wrapped gift to open it!)";
		}
		else
		{
			pTmOpenDate = localtime(&mOpenDate);
			if (pTmOpenDate != NULL)
			{
				strftime(cOpenDate, sizeof(cOpenDate), "%B %d, %Y", pTmOpenDate);
				*pStream << "(But no peeking until "
						 << cOpenDate
						 << "!)";
			}
		}

		*pStream << "      </font>\n"
					"    </td>\n"
					"  </tr>\n";

		// End table for gift pic
		*pStream <<	"</table>\n"
					"</center>\n";
	}

	*pStream <<	"<p><p>\n";

	// Begin table for sender name
	*pStream <<	"<center>"
				"<table border=\"0\" cellspacing=\"0\" width=\"100%\">\n"
				"  <tr>\n"
				"    <td align=\"center\" width=\"100%\">"
				"      <font size=\"4\" color=\"#000000\">";

	if (mpSenderName != NULL)
	{
		// The from: info
		*pStream <<	"<b>"
					"from<br>"
				 <<	mpSenderName;
	}
	else
	{
		*pStream << "Anonymous";
	}

	// End sender name
	*pStream <<	"</b></font>\n"
				"    </td>\n"
				"  </tr>\n";

	// End sender name table
	*pStream <<	"</table>\n"
				"</center>\n";
	
	*pStream << flush;
	
	delete pBase64;

	return true;
}

