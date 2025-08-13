/*	$Id: clseBayAppAdminAddNoteAboutItem.cpp,v 1.4.204.1.34.1 1999/08/01 02:51:38 barry Exp $	*/
//
//	File:		clseBayAppAdminAddNoteAboutItem.cpp
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
//				- 07/07/99 amanda   Modifications to user accessibility and userId 
//					       		    functionality.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsBulletinBoards.h"

void clseBayApp::AdminAddNoteAboutItem(CEBayISAPIExtension *pThis, 
									   char *pUserid,
									   char *pPass,
									   char *pAboutItem,
									   char *pSubject,
									   int type,
									   char *pText,
									   eBayISAPIAuthEnum authLevel)
	{
	bool		error					= false;
	clsItem		*pTheAboutItem			= NULL;
	clsUser		*pTheAboutItemSeller	= NULL;

	UserId		fromUserId		= 0;
	UserId		aboutItemId		= 0;
	UserId		aboutUserId		= 0;

	time_t		nowTime;

	clsNotes	*pNotes;
	clsNote		*pNote;

	// Setup
	SetUp();

	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Adding a note about a user"
			  <<	"</TITLE>"
					"</head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Title using black on darkgrey table
	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>"
					"Adding an eBay Note about a user"
					"</strong></font></td>\n"
					"</tr>\n"
					"</table>"
					"</center>\n";


	// Spacer
	*mpStream <<	"<br>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	//
	// Validate the user posting the note
	//
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserid, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	fromUserId	= mpUser->GetId();
	
	//Invalidate the Magic Password for Posting eNote page
 
 	for (int i=0; i<NUM_SPECIAL_PASS; i++) 
 	{
 		if (strcmp(pPass, 
 				   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(i)) == 0)
 		{
 			*mpStream <<  "You may not add an eNote using MagicPassword. Please use your personal userid and password." 	
 					  <<     "<br>"
 		   			  <<	mpMarketPlace->GetFooter();

			CleanUp();
 			return;
 		}
 	}
 	
 	
 
     //Keep non-eBay persons from posting eNotes
 		
 	if(strstr(mpUser->GetEmail(), "@ebay.com") == 0) 
 	{
     *mpStream <<	"<font color=red>Not Authorized</font>"
					"You are not authorized to use this "
					  <<	mpMarketPlace->GetName()
					  <<	" function. "
					  <<	"<p>"
					  <<	mpMarketPlace->GetFooter();
 		CleanUp();
 		return;
 	}
    
	//
	// Ok, now let's see if the "about" user is valid
	//
	if (pAboutItem != NULL						&&
		strcmp(pAboutItem, "default") == 0		&&
		atoi(pAboutItem) != 0)
	{
		*mpStream <<	"<h2>Missing \"About\" Item</h2>"
						"Sorry, but you must fill in the \"About\" field!"
				  <<	"<br>";
		error		= true;
		pAboutItem	= NULL;
		aboutItemId	= 0;
	}
	else
	{
		pTheAboutItem= mpItems->GetItem(atoi(pAboutItem));
		if (!pTheAboutItem)
		{
			*mpStream <<	"<h2>Unknown Item</h2>"
							"The item, "
					  <<	pAboutItem
					  <<	", which this note is \"about\", is invalid. It may "
					  <<	"no longer be on the system. Please try again."
							"<br><br>";
			
			error	= true;		
		}
		else
		{
			aboutItemId	= pTheAboutItem->GetId();
			aboutUserId	= pTheAboutItem->GetSeller();
		}
	}

	//
	// Here, we could do some validation of the note type. However, since
	// ALL notes are support notes, and there's no "multi-type" notes, we
	// can just accept the value "as-is", and or in the "Support" type. 
	// 
	// One day, this will change
	//

	//
	// Make sure there's text here
	//
	if (pText == NULL					||
		strcmp(pText, "default") == 0		)
	{
		*mpStream <<	"<h2>No Text!</h2>"
						"Sorry, but you must include some text for your note";
		error	= true;
	}


	//
	// If we had an error, reshow...
	//
	if (error)
	{
		ShowAddNoteAboutItem(pUserid, pPass, pAboutItem,
							 pSubject, type, pText);

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}


	//
	// Well! Everything's hunky dory. Let's create a note object
	//

	//
	// The "To" user here is always support
	//

	nowTime	= time(0);

	pNotes	= mpMarketPlace->GetNotes();

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),
						  fromUserId,
						  0,
						  aboutItemId,
						  aboutUserId,
						  eClsNoteFromTypeeBay,
						  1,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  pSubject,
						  pText);


	pNotes->AddNote(pNote);

	// Now, we need the User
	pTheAboutItemSeller	= mpUsers->GetUser(aboutUserId);

	if (pTheAboutItemSeller != NULL)
		pTheAboutItemSeller->SetHasANote(true);

	delete pTheAboutItemSeller;

	// 
	// Tell them it worked
	//
	*mpStream <<	"<font color=green size=+1>"
					"eNote added!"
					"</font>"
					"<br>"
					"<br>";

	ShowNotes(pAboutItem, 0);

	*mpStream <<	"<br>"
					"<br>"
			  <<	mpMarketPlace->GetFooter();



	// Clean up
	delete	pNote;
	delete	pTheAboutItem;

	CleanUp();
	return;

}

