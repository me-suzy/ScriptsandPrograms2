/*	$Id: clseBayAppAddToBoard.cpp,v 1.9.324.1 1999/08/01 02:51:37 barry Exp $	*/
//
//	File:	clseBayAppAddToBoard.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	A TEMPORARY method to handle the bulletin
//	boards. It's temporary because:
//
//	- The current boards are rather primitive
//	- We're putting it in thie DLL because we 
//	  haven't solved the "multiple DLLs sharing
//	  one set to thread local storage" problem.
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 02/23/99 anoop	- Check to see if the user verification completes properly.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsBulletinBoards.h"

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif /* _MSC_VER */

//
// TransformInput
//
//	Scans the user's input for special strings, and replaces
//	them.
//

typedef struct
{
	char	*pSource;
	char	*pTarget;
	bool	htmlTransform;
} StringTransform;

static const StringTransform SpecialString[] =
{
	"&",	"&amp;",	true,
	"<",	"&lt;",		true,
	">",	"&gt;",		true,
	"\"",	"&quot;",	true,
	"\n\r",	"<p>",		false,
	"\r\n", "<p>",		false,
	"\n",	"<p>",		false,
	"\r",	"<p>",		false,
	"\0",	"\0",		false
};


char *clseBayApp::TransformInput(char *pString,
								 bool transformHTML)
{
	char			*pNewString;
	char			*pIn;
	char			*pOut;

	const StringTransform *pCurrentTransform;
	int				checkLen;
	int				targetLen;
	
	bool			gotOne;

	int				newStringLen;
	char			*pRealNewString;

	// At the very worst, the new string will
	// be 5X the current string in size.
	pNewString	= new char[strlen(pString) * 5];

	// Let's hobble along now
	for (pIn = pString,
		 pOut = pNewString;
		 *pIn;)
	{
		// Ok, let's see if this string matches
		// any of the source transforms
		gotOne	= false;
		for (pCurrentTransform = &SpecialString[0];
			 *(pCurrentTransform->pSource) != '\0';
			 pCurrentTransform++)
		{
			// Try for the first character
			if (*pIn != *(pCurrentTransform->pSource))
				continue;

			// Avoid length problems
			checkLen	= strlen(pCurrentTransform->pSource);
			
			if (strlen(pIn) < checkLen)
				continue;

			// Try for the whole string
			if (strncmp(pCurrentTransform->pSource,
						pIn,
						checkLen) == 0)
			{
				if (!transformHTML)
				{
					if (pCurrentTransform->htmlTransform)
					{
						continue;
					}
				}

				gotOne	= true;
				targetLen	= strlen(pCurrentTransform->pTarget);
				memcpy(pOut, pCurrentTransform->pTarget,
					   targetLen);
				
				pOut	= pOut + targetLen;
				pIn		= pIn + checkLen;

				break;
			}
		}

		// If we're here, we either found a transform, in which case
		// pIn and pOut have been bumped, or we haven't, in which case
		// we need to do the work
		if (!gotOne)
		{
			*pOut	= *pIn;
			pOut++;
			pIn++;
		}
	}

	// Make sure there's a trailing null!
	*pOut	= '\0';

	// Make the new line a little saner
	newStringLen	= strlen(pNewString);
	pRealNewString	= new char[newStringLen + 1];

	memcpy(pRealNewString, pNewString, newStringLen);
	*(pRealNewString + newStringLen) = '\0';

	delete	pNewString;

	return pRealNewString;

}


	
//
// LeaveFeedback
//
bool clseBayApp::AddToBoard(CEBayISAPIExtension *pThis,
							char *pUserId,
							char *pPass,
							char *pString,
							char *pBoardName,
							char *pLimit,
							char *pRedirectURL,
							bool FromEssayBoard/*=flase*/)
{
	clsBulletinBoard	*pBoard;

	const char			*pNewString;

	int					badWordClass;
	char				*pBadWord;

	// used for kludge to emit header etc.
	ostrstream			badStream;
	char				*pBad;

	SetUp();

	// See if Legit
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, &badStream, false,
												 NULL, true);

	badStream	<< ends;
	pBad = badStream.str();
	if (!mpUser)
	{
		*mpStream <<	"<HTML>"
						"<HEAD>"
				  <<	"<TITLE>"
				  <<	mpMarketPlace->GetCurrentPartnerName() 
				  <<	" Post to Board "
				  <<	"</TITLE>"
						"</HEAD>"
				  <<	flush;
		*mpStream <<	pBad;

		*mpStream <<	"<b>"
				  <<	mpMarketPlace->GetFooter();

		delete pBad;
		CleanUp();

		return false;
	}

	delete pBad;
	// See if confirmed

	// New from Mar 15, 1999
	// Check to see if the user verification completes properly.
	if (ValidateOrBlockAction() == FALSE)
	{
		*mpStream <<	mpMarketPlace->GetHeader()
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return false;
	}


	// Look for empty posts. We look for the ISAPI
	// "default" string
	if (strcmp(pString, "default") == 0)
	{
		*mpStream <<	"<HTML>"
						"<HEAD>"
				  <<	"<TITLE>"
				  <<	mpMarketPlace->GetCurrentPartnerName() 
				  <<	" Post to Board "
				  <<	"</TITLE>"
						"</HEAD>"
				  <<	flush;

		*mpStream <<	mpMarketPlace->GetHeader()
				  <<	"<h2>Empty Message!</h2>"
						"You attempted to make an empty message to the "
						"bulletin board. Please go back and fill in "
						"the message box."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return false;
	}


	// Now, let's add the entry to the board
	pBoard	= mpMarketPlace->GetBulletinBoards()->GetBulletinBoard(pBoardName);

	if (!pBoard)
	{
		*mpStream <<	mpMarketPlace->GetHeader()
				  <<	"<h2>Invalid board name!</h2>"
						"Sorry, the bulletin board name "
				  <<	pBoardName
				  <<	" is invalid. Please go back and try again."
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return false;
	}

	if (pBoard->IsRestricted()						&&
		strstr(mpUser->GetEmail(), "@ebay.com") == 0	)
	{
		*mpStream <<	mpMarketPlace->GetHeader()
				  <<	"<h2>Posting not allowed</h2>"
						"Sorry, but only eBay employees may post to this "
						"board."
						"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return false;
	}


	// use max length of vulgar words ok.
	pBadWord = new char[EBAY_MAX_USERID_SIZE + 1];

	// Before we do anything, let's look for vulgarity
	pNewString	= clsUtilities::RemoveHTMLTag(pString);

	//only block user outside ebay
	if (!strstr(mpUser->GetEmail(), "@ebay.com"))
	{
		if (clsUtilities::TooVulgar((char *)pNewString, &badWordClass, pBadWord))
		{
			if (badWordClass == clsUtilities::VULGAR)
			{
				*mpStream <<	mpMarketPlace->GetHeader()
						  <<	"<h2>Comment too vulgar.</h2>"
								"Sorry, our vulgarity-checking program has determined that "
								"your comment may contain the word <font color=\"red\">\""
						  <<	pBadWord
						  <<	".\" </font>"
								"Sometimes the program "
								"is wrong, however, and will piece together perfectly friendly words "
								"to make a word that sounds dirty.<p>For example, let's pretend that "
								"<font color=\"red\">\"dingo\"</font> is a vulgar word. If you leave "
								"a post that reads \"Merchandise received in good order,\" the "
								"program will piece together \"receive<font color=\"red\">d in "
								"go</font>od\" and give you this warning message. If this happened "
								"to you, just change your post a little (e.g., \"Merchandise received "
								"in fine order\") and the program will let it through. Sorry for the "
								"inconvenience, but we strive to ensure a pleasant experience for all "
								"of those in the eBay community, and in some cases, we may be overly "
								"protective just to be safe."
								"\n"
						  <<	mpMarketPlace->GetFooter();

				delete [] pBadWord;
				delete (char *)pNewString;
				CleanUp();
				return	false;
			}
		}
	}

	delete [] pBadWord;
	delete (char *)pNewString;

	// Transform special strings. If HTML is ENABLED for the
	// board (IsHTMLEnabled == true) then we DON'T want to 
	// transform HTML, and vice versa
	if (!pBoard->IsHTMLEnabled())
	{
		pNewString	= clsUtilities::RemoveHTMLTag(pString);
	}
	else
	{
		pNewString	= clsUtilities::DrawSafeHTML((const char *)pString);
	}

	pBoard->AddEntry(mpUser, (char *)pNewString);

	delete	(char *)pNewString;

	// Let's tell our caller where to go
	if (FromEssayBoard)
	{
		sprintf(pRedirectURL,
				"%seBayISAPI.dll?ViewEssay&name=%s",
				mpMarketPlace->GetCGIPath(PageViewEssay),
				pBoardName);
	}
	else
	{
		sprintf(pRedirectURL,
				"%seBayISAPI.dll?ViewBoard&name=%s&limit=%s",
				mpMarketPlace->GetCGIPath(PageViewBoard),
				pBoardName,
				pLimit);
	}

	CleanUp();

	return true;
}


