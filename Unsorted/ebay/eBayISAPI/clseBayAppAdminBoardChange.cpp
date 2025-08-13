/*	$Id: clseBayAppAdminBoardChange.cpp,v 1.6.396.1 1999/08/01 02:51:39 barry Exp $	*/
//
//	File:		clseBayAppAdminBoardChange.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Does all the work!
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clsBulletinBoards.h"

void clseBayApp::AdminBoardChange(CEBayISAPIExtension *pServer,
								  const char *pBoardName,
								  const char *pBoardShortName,
								  const char *pBoardShortDesc,
								  const char *pBoardPicture,
								  int maxPostCount,
								  int maxPostAge,
								  const char *pBoardDesc,
								  int boardPostable,
								  int boardAvailable,
								  eBayISAPIAuthEnum authLevel)

{
	clsBulletinBoard	*pBoard;
	clsBulletinBoard	*pNewBoard;

	const char			*pTheBoardPicture;
	unsigned int		BoardFlag;
	
	// Setup
	SetUp();

	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Bulletin Board Administration"
			  <<	"</TITLE>"
					"</head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Spacer
	*mpStream <<	"<br>";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		*mpStream <<	"<h2>Not Authorized</h2>"
						"You are not authorized to use this "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" function. "
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();

		return;
	}



	// Title using black on darkgrey table
	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>"
					"eBay Bulletin Board Administration"
					"</strong></font></td>\n"
					"</tr>\n"
					"</table>"
					"</center>\n";

	// Let's see if we can get it. 
	pBoard	= 
		mpMarketPlace->GetBulletinBoards()->GetBulletinBoard((char *)pBoardShortName);

	if (!pBoard)
	{
		*mpStream <<	"<h2>Invalid Board Name</h2>"
						"Sorry, the board name \""
				  <<	pBoardShortName
				  <<	"\" is invalid. Please go back and try again."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Some fields will come in as "default", which means they're 
	// empty
	if (strcmp(pBoardPicture, "default") == 0)
		pTheBoardPicture	= "";
	else
		pTheBoardPicture	= pBoardPicture;

	BoardFlag = pBoard->GetControlFlags();
	if (pBoard->IsEssay())
	{
		// update the board status if it is an essay board.
		if (boardPostable == 1)
		{
			// removing the not postable flag
			BoardFlag &= ~BULLETIN_BOARD_NOT_POSTABLE;
		}
		else
		{
			// make the board not postable
			BoardFlag |= BULLETIN_BOARD_NOT_POSTABLE;
		}

		if (boardAvailable == 1)
		{
			// remove not postable flag
			BoardFlag &= ~BULLETIN_BOARD_NOT_AVAILABLE;
		}
		else
		{
			// make the board not postable
			BoardFlag |= BULLETIN_BOARD_NOT_AVAILABLE;
		}
	}

	// 
	// Ok, this is easy, change it!
	//
	pNewBoard	= new clsBulletinBoard(pBoard->GetId(),
									   pBoardName,
									   pBoardShortName,
									   pBoardShortDesc,
									   pTheBoardPicture,
									   pBoardDesc,
									   maxPostCount,
									   maxPostAge,
									   BoardFlag,
									   pBoard->GetType(),
									   pBoard->GetLastPostTime());

	mpMarketPlace->GetBulletinBoards()->UpdateBulletinBoard(pNewBoard);
	mpMarketPlace->ResetBulletinBoards();
	delete pNewBoard;
	pBoard	= 
		mpMarketPlace->GetBulletinBoards()->GetBulletinBoard((char *)pBoardShortName);


	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>"
					"Board Updated!"
					"</strong></font></td>\n"
					"</tr>\n"
					"<tr>"
					"<td align=center width=100%>"
					"Updated values are shown below. You may leave them "
					"\'as-is\', or change them again. Updates will <b>not</b> "
					"show on the live boards until IIS is restarted on "
					"all machines."
					"</td>"
					"</tr>"
					"</table>"
					"</center>\n";

	// Ok, let's emit each bit, once we do the form
	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminBoardChange)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminBoardChange\">";

	AdminBoardShow(pBoard);



	// The buttons!
	*mpStream <<	"<input type=submit value=\"Save Changes\">"
					"<input type=reset value=\"Start again!\">";


	// All done!
	*mpStream <<	"</form>"
			  <<	mpMarketPlace->GetFooter();

	// Clean up
	CleanUp();
	return;

}

