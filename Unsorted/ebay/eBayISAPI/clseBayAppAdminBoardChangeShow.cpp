/*	$Id: clseBayAppAdminBoardChangeShow.cpp,v 1.5.396.1 1999/08/01 02:51:40 barry Exp $	*/
//
//	File:		clseBayAppAdminBoardChangeShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Displays all the cool stuff about a board
//				in a form so it can be modified.
//
//	Modifications:
//				- 04/03/98 michael	Created.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clsBulletinBoards.h"

//
// Common routine (usedby AdminBoardChange) to show a board.
// These are input fields, so YOU must emit the <FORM> and
// </FORM> tags before and after calling this routine.
//
void clseBayApp::AdminBoardShow(clsBulletinBoard *pBoard)
{
	// Name
	*mpStream <<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>Board Name</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>"
					"<center>\n"
			  <<	pBoard->GetName()
			  <<	"</center>\n"
					"<center>"
					"<table border=0 cellspacing=0 "
					"width=50% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=2 face=Arial color=#000000>"
					"<strong>New Name</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>"
					"<center>"
			  <<	"<INPUT TYPE=TEXT SIZE=45 NAME=\"BoardName\" VALUE=\""
			  <<	pBoard->GetName()
			  <<	"\""
					">\n"
					"</center>\n"
					"<br>";

	// Short name
	*mpStream <<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>Board Short Name</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>"
					"<center>\n"
			  <<	pBoard->GetShortName()
			  <<	"</center>\n";
/*					"<center>"
					"<table border=0 cellspacing=0 "
					"width=50% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=2 face=Arial color=#000000>"
					"<strong>New Short Name</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>"
*/
	*mpStream <<	"<center>"
			  <<	"<INPUT TYPE=hidden NAME=\"BoardShortName\" VALUE=\""
			  <<	pBoard->GetShortName()
			  <<	"\""
					">\n"
					"</center>\n"
					"<br>";

	// Short Description
	*mpStream <<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>Board Short Description</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"<center>\n"
					"</center>\n"
			  <<	pBoard->GetShortDescription()
			  <<	"</center>\n"
					"<center>"
					"<table border=0 cellspacing=0 "
					"width=50% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=2 face=Arial color=#000000>"
					"<strong>New Short Description</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>"
					"<center>"
			  <<	"<INPUT TYPE=TEXT SIZE=45 NAME=\"BoardShortDesc\" VALUE=\""
			  <<	pBoard->GetShortDescription()
			  <<	"\""
					">\n"
					"</center>\n"
					"<br>";
/*
	// Picture. Since this should just be an HTTP reference, we just show it
	// in an input box
	*mpStream <<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>Board Picture</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>\n";

	if (pBoard->GetPicture() != NULL)
	{
		*mpStream <<	"<center>\n"
				  <<	pBoard->GetPicture()
				  <<	"</center>\n"
						"<center>\n"
						"<IMG SRC=\""
				  <<	pBoard->GetPicture()
				  <<	"\">\n"
						"</center>";
	}
	else
	{
		*mpStream <<	"<center>\n"
						"(no Board Picture provided)"
						"</center>\n";
	}
	
	*mpStream <<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=50% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=2 face=Arial color=#000000>"
					"<strong>New Board Picture</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>"
					"<center>"
			  <<	"<INPUT TYPE=TEXT SIZE=45 NAME=\"PicURL\"";
	
	if (pBoard->GetPicture() != NULL)
	{
		*mpStream <<	"VALUE=\""
				  <<	pBoard->GetPicture()
				  <<	"\"";
	}
					
	*mpStream <<	">\n"
					"</center>\n"
					"<br>";
*/

	*mpStream <<	"<INPUT TYPE=HIDDEN "
					"NAME=\"MaxPostAge\" "
					"VALUE="
					"\""
			  <<	pBoard->GetMaxPostAge()
			  <<	"\""
					">";

	*mpStream <<	"<INPUT TYPE=HIDDEN "
					"NAME=\"MaxPostCount\" "
					"VALUE="
					"\""
			  <<	pBoard->GetMaxPostCount()
			  <<	"\""
					">";

					

	// And, the description
	*mpStream <<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>Board Description</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>\n"
			  <<	pBoard->GetDescription()
			  <<	"<center>"
					"<table border=0 cellspacing=0 "
					"width=50% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=2 face=Arial color=#000000>"
					"<strong>New Description</strong>"
					"</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"
					"</center>"
					"<center>"
			  <<	"<TEXTAREA NAME=\"BoardDesc\" COLS=60 ROWS=8>"
			  <<	pBoard->GetDescription()
			  <<	"</TEXTAREA>"
					"\n"
					"</center>\n"
					"<br>";

	// show the board current status if it is essay board
	if (pBoard->IsEssay())
	{
		*mpStream <<	"<center>"
						"<table border=0 cellspacing=0 "
						"width=100% bgcolor=#CCCCCC>\n"
						"<tr>\n"
						"<td align=center width=100%>"
						"<font size=4 face=Arial color=#000000>"
						"<strong>Board Status</strong>"
						"</font>"
						"</td>\n"
						"</tr>\n"
						"</table>\n"
						"</center>"
						"<center>\n";
		if (pBoard->IsPostable())
		{
			*mpStream	<< "Postable";
		}
		else
		{
			*mpStream	<< "Not Postable";
		}
		*mpStream	<<	"<br>\n";
		if (pBoard->IsAvailable())
		{
			*mpStream	<< "Available";
		}
		else
		{
			*mpStream	<<	"Not Available";
		}
		*mpStream	<<	"</center>\n"
						"<center>"
						"<table border=0 cellspacing=0 "
						"width=50% bgcolor=#CCCCCC>\n"
						"<tr>\n"
						"<td align=center width=100%>"
						"<font size=2 face=Arial color=#000000>"
						"<strong>New Status</strong>"
						"</font>"
						"</td>\n"
						"</tr>\n"
						"</table>\n"
						"</center>"
						"<center>"
					<<	"<INPUT TYPE=CHECKBOX NAME=\"BoardPostable\" VALUE=1 ";
		if (pBoard->IsPostable())	*mpStream << "checked";
		*mpStream	<<	">Postable<br>\n"
					<<	"<INPUT TYPE=CHECKBOX NAME=\"BoardAvailable\" VALUE=1 ";
		if (pBoard->IsAvailable())	*mpStream << "checked";
		*mpStream	<<	">Available\n"
						"</center>\n"
						"<br>";
	}
	else
	{
		*mpStream	<<	"<input type=\"hidden\" name=\"BoardPostable\" value=\"";
		if (pBoard->IsPostable())
		{
			*mpStream	<<	"1";
		}
		else
		{
			*mpStream	<<	"0";
		}
		*mpStream	<<	"\">\n"
					<<	"<input type=\"hidden\" name=\"BoardAvailable\" value=\"";
		if (pBoard->IsAvailable())
		{
			*mpStream	<<	"1";
		}
		else
		{
			*mpStream	<<	"0";
		}
		*mpStream	<<	"\">\n";
	}

}

void clseBayApp::AdminBoardChangeShow(CEBayISAPIExtension *pServer,
									  const char *pName,
									  eBayISAPIAuthEnum authLevel)
{
	clsBulletinBoard	*pBoard;
	
	// Setup
	SetUp();

	// We'll need a title here
	// expire the page to make sure it reflect the current status of the board
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Bulletin Board Administration"
			  <<	"</TITLE>"
					"<meta http-equiv=\"Expires\" "
					"content=\"Mon, 05 Oct 1998, 00:00:00 GMT\">"
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
					"</table></center>\n";

	// Let's see if we can get it. 
	pBoard	= mpMarketPlace->GetBulletinBoards()->GetBulletinBoard((char *)pName);

	if (!pBoard)
	{
		*mpStream <<	"<h2>Invalid Board Name</h2>"
						"Sorry, the board name \""
				  <<	pName
				  <<	"\" is invalid. Please go back and try again."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Spacer
	*mpStream <<	"<br>";

	*mpStream <<	"<p>This page is used to administer the names, short names, and "
					"descriptions of an eBay Bulletin Board. Below, you will see the "
					"various fields in text and HTML format. To change a field, update "
					"it in the input box, and hit submit below.\n"
					"<br>"
					"<br>";
		
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

