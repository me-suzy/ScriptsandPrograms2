/*	$Id: clseBayAppAdminShowNote.cpp,v 1.6.2.1.34.1 1999/08/01 03:01:02 barry Exp $	*/
//
//	File:		clseBayAppAdminShowNote.cpp
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
//					       		    functionality
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "eBayKernel.h"

//
// ShowNote
//
//	This method (which is used in a number of places), shows
//	ONE clsNote. 
//
void clseBayApp::ShowNote(clsNote *pNote)
{
	clsUserIdWidget		*pUserIdWidget			= NULL;

	clsUser				*pTheFromUser			= NULL;
	clsFeedback			*pTheFromUserFeedback	= NULL;
	clsUser				*pTheToUser				= NULL;
	clsFeedback			*pTheToUserFeedback		= NULL;
	clsUser				*pTheAboutUser			= NULL;
	clsFeedback			*pTheAboutUserFeedback	= NULL;

	time_t				theTime;
	struct tm			*pTheLocalTime;
	char				cTheDate[32];
	char				cTheTime[32];

	UserId				aboutUser;
	ItemId				aboutItem;
	clsItem				*pTheAboutItem;
	const char			*pTypeDescription;


	// Well need a widget..
	pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);

	if (!pUserIdWidget)
	{
		*mpStream	  <<  "INTERNAL ERROR" 	
 					  <<     "<br>"
 		   			  <<	mpMarketPlace->GetFooter();
		return;
	}	
		// Here we go!
	*mpStream <<	"<TABLE WIDTH=100% BORDER=1>"
					"<TR>"	
					"<TD>";

	// The actual post now and guard tags to prevent
	// badness
	*mpStream <<	"<TABLE WIDTH=100% BORDER=0 bgcolor=\"#CCCCCC\">";
	
	// From information
	*mpStream <<	"</TD>"
					"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=15%>";
	
	pTheFromUser			= mpUsers->GetUserFromCache(pNote->GetUserIdFrom());
	pTheFromUserFeedback	= pTheFromUser->GetFeedback();

	pUserIdWidget->SetUserInfo(pTheFromUser->GetUserId(), 
							   pTheFromUser->GetEmail(),
							   pTheFromUser->GetUserState(),
							   pTheFromUser->UserIdRecentlyChanged(),
							   pTheFromUserFeedback->GetScore());
	
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->EmitHTML(mpStream);
	
	*mpStream <<	"</TD>";

	// "About information
	pTypeDescription	= clsNote::GetNoteTypeDescription(pNote->GetType());

	if (!pTypeDescription)
		pTypeDescription	= "(Unknown!)";

	*mpStream <<	"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=45%>"
					"<b>"
					"Action: "
					"</b>"
			<<	pTypeDescription
			<<	"</TD>";


	// Date and such
	theTime			= pNote->GetWhen();
	pTheLocalTime	= localtime(&theTime);

	strftime(cTheDate, sizeof(cTheDate),
			 "%m/%d/%y", pTheLocalTime);
	strftime(cTheTime, sizeof(cTheTime),
			 "%H:%M:%S PDT", pTheLocalTime);

	*mpStream <<	"<TD BGCOLOR=\"#EFEFEF\" ALIGN=right WIDTH=15%>"
			  <<	cTheDate
			  <<	" "
					"<B>"
					" "
					"</B>"
			  <<	cTheTime
			  <<	"</TD>";
		
	*mpStream <<	"</TR>"
					"</TABLE>";


	aboutUser	= pNote->GetAboutUser();
	aboutItem	= pNote->GetAboutItem();

	if (aboutUser != 0 || aboutItem != 0)
	{
		*mpStream <<	"<TABLE WIDTH=100% BORDER=0 bgcolor=\"#CCCCCC\">"
						"<TR>";				
		if (aboutUser != 0)
		{
			*mpStream <<	"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=15%>About User:</TD>"
							"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=45%>";

			pTheAboutUser			= mpUsers->GetUserFromCache(aboutUser);
			pTheAboutUserFeedback	= pTheAboutUser->GetFeedback();

			pUserIdWidget->SetUserInfo(pTheAboutUser->GetUserId(), 
									   pTheAboutUser->GetEmail(),
									   pTheAboutUser->GetUserState(),
									   pTheAboutUser->UserIdRecentlyChanged(),
									   pTheAboutUserFeedback->GetScore());

			pUserIdWidget->SetShowUserStatus(false);
			pUserIdWidget->EmitHTML(mpStream);

		}
		else if (aboutItem != 0)
		{
			*mpStream <<	"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=15%>About Item:</TD>"
							"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=45%>"			
							"<b>"
							"#"
							"</b>";

			// ** NOTE **
			// Do we need an Item widget?
			// ** NOTE **
			pTheAboutItem	= mpItems->GetItem(aboutItem);

			if (pTheAboutItem != NULL)
			{
				*mpStream <<	"<A HREF=\""
    					  <<	mpMarketPlace->GetCGIPath(PageViewItem)
						  <<	"eBayISAPI.dll?ViewItem&item="
						  <<	pTheAboutItem->GetId()
						  <<	"\""
								">"
						  <<	pTheAboutItem->GetId()
						  <<	"</A>"
								" - "
						  <<	pTheAboutItem->GetTitle();

				delete pTheAboutItem;
			}
			else
			{
				*mpStream <<	pTheAboutItem->GetId();
			}
		}

		*mpStream <<	"</TD>"
						"<TD BGCOLOR=\"#EFEFEF\" ALIGN=right WIDTH=15%>.</TD>"
						"</TR>"
						"</TABLE>";
	}


	// If there was an about user AND an about item, then we did the
	// user on the line above, and now it's time to do the item on
	// it's own line.
	if (aboutUser != 0 && aboutItem != 0)
	{
		*mpStream <<	"<TABLE WIDTH=100% BORDER=0 bgcolor=\"#CCCCCC\">"
						"<TR>"
						"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=15%>About Item:</TD>"
						"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left WIDTH=45%>";
		
		*mpStream <<	"<b>"
						"#"
						"</b>";	
		
		
		// ** NOTE **
		// Do we need an Item widget?
		// ** NOTE **
		pTheAboutItem	= mpItems->GetItem(aboutItem);

		if (pTheAboutItem != NULL)
		{
			*mpStream <<	"<A HREF=\""
	 				  <<	mpMarketPlace->GetCGIPath(PageViewItem)
   					  <<	"eBayISAPI.dll?ViewItem&item="
					  <<	pTheAboutItem->GetId()
					  <<	"\""
							">"
					  <<	pTheAboutItem->GetId()
					  <<	"</A>"
							" - "
					  <<	pTheAboutItem->GetTitle();

			delete pTheAboutItem;
		}
		else
		{
			*mpStream <<	aboutItem;
		}
		
		*mpStream <<	"</TD>"
						"<TD BGCOLOR=\"#EFEFEF\" ALIGN=right WIDTH=15%>.</TD>"
						"</TR>"
						"</TABLE>";
	}


	*mpStream <<	"<TABLE WIDTH=100% BORDER=0 bgcolor=\"#CCCCCC\">"
					"<TR>"
					"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left>"
					"<b>Subject: </b>"
				<<	pNote->GetSubject()
				<<	"</TD>"
					"</TR>"
					"</TABLE>";

	// The actual post now and guard tags to prevent
	// badness

	*mpStream <<	"<TABLE WIDTH=100%>"
					"<TR>"
					"<TD>"
			<<	pNote->GetText()
			<<	"</TD>"
					"</TR>"
					"</TABLE>\n";

	*mpStream <<	"</TD></TR></TABLE>";

	delete	pUserIdWidget;
  
	return;
	
}


//
// ShowNotes
//
//	An internal method, used lots of places, to show notes
//	with filters applied.
//
//	Does some rudimentry error checking, but fails SILENTLY
//	if a problem is detected.
//
void clseBayApp::ShowNotes(char *pAboutFilter,
					       int categoryFilter,
						   clsUser *pAboutUser)
{
	clsUser				*pTheAboutUser			= NULL;


	unsigned int		addressFilter			= 0;
	UserId				addresseeUserId			= 0;
	UserId				aboutUserId				= 0;
	ItemId				aboutItemId				= 0;

	unsigned int		notesAddressFilter		= 0;
	unsigned int		notesAboutFilter		= 0;

	clsNoteAddressList	*pNoteToAddressList		= NULL;
	clsNoteAddress		*pNoteToAddress			= NULL;
	clsNoteAddressList	*pNoteCCAddressList		= NULL;
	clsNoteAddress		*pNoteCCAddress			= NULL;
	clsNoteAddressList	*pNoteFromAddressList	= NULL;
	clsNoteAddress		*pNoteFromAddress		= NULL;
	clsNoteAddressList	*pNoteAboutAddressList	= NULL;
	clsNoteAddress		*pNoteAboutAddress		= NULL;


	clsNotes				*pNotes				= NULL;
	clsNoteList				*pNoteList			= NULL;
	clsNoteList::iterator	i;

	char					*pHeadColor = "#99CCCC";

	UserId					aboutUser;

	// Used to interface with the user cache
	list<UserId>			lRequestedUserIds;
	list<UserId>			lMissingUserIds;
			
	// We'll need this (duh)
	pNotes	= mpMarketPlace->GetNotes();

	//
	// The "to", or addressee is always the Support User
	//

	if(pNotes == NULL)   
	{
		*mpStream <<	"Internal Error"
			      <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		return;
	}

	addresseeUserId	= pNotes->GetSupportUser()->GetId();

	
	//
	// Now, we need to figure out if the user provided an "About" user
	// or an "About" item, or neither. It's pretty simple: If the field
	// doesn't contain "default", and translates to an integer, it's 
	// assumed to be an item number. Otherwise, it's a userid/email
	// address.
	//
	if (pAboutFilter != NULL					&&
		strcmp(pAboutFilter, "default") != 0		)
	{
		aboutItemId	= atoi(pAboutFilter); 

		// If it's an item, then we're done. No sense in looking
		// it up, since it may not be here any more. If it's not
		// an item, let's validate the use
		// Checks to make sure the item number isn't a userId of all digits. 
		// If it is, change the item number to be the user id number. aaw
   
	if (aboutItemId == 0 || (mpUsers->GetUser(pAboutFilter) != NULL))
		{
			if (pAboutUser == NULL)
				pTheAboutUser	= mpUsers->GetUser(pAboutFilter);
			else
				pTheAboutUser	= pAboutUser;

			if (pTheAboutUser == NULL)
			{
				return;
			}
			else
			{
				aboutUserId	= pTheAboutUser->GetId();
			    aboutItemId = 0; 
			}
		}
	}


	//
	// Let's validate the type filter
	//
	//
	// ** NOTE **
	//	One day, soon, we'll store these values in a nice table which
	//	ShowNoteShow can use to emit them, and we can validate them.
	//	For now, make sure these checks correspond to ShowNoteShow.
	//
	if (categoryFilter != 0						&&
		!clsNote::CheckNoteType(categoryFilter)		)
	{
		if (pTheAboutUser != NULL && pAboutUser == NULL)
			delete	pAboutUser;

		return;
	}

	//
	// Let's get them!
	//

	// Setup address filter
	notesAddressFilter =	clsNotes::eClsNotesFilterNotesTo +
							clsNotes::eClsNotesFilterNotesCC;

	pNotes->SetAddressFilter(notesAddressFilter);

	// clsNotes "About" type refers to whether the note(s) are
	// "about" a user or "about" an item.  
   
	if (aboutUserId != 0)
	{
		pNoteAboutAddressList	= new clsNoteAddressList();
		pNoteAboutAddress		= new clsNoteAddress();
		pNoteAboutAddress->SetAddressUser(aboutUserId);
		pNoteAboutAddressList->push_back(*pNoteAboutAddress);
		delete pNoteAboutAddress;
		pNotes->SetAboutFilter(eClsNoteAboutUser);
	}
	else if (aboutItemId != 0)
	{
		pNoteAboutAddressList	= new clsNoteAddressList();
		pNoteAboutAddress		= new clsNoteAddress();
		pNoteAboutAddress->SetAddressItem(aboutItemId);
		pNoteAboutAddressList->push_back(*pNoteAboutAddress);
		delete pNoteAboutAddress;
		pNotes->SetAboutFilter(eClsNoteAboutItem);

	}

	pNotes->SetAbout(pNoteAboutAddressList);

	// "To" and "CC" filters
	pNoteToAddressList		= new clsNoteAddressList();
	pNoteToAddress			= new clsNoteAddress();
	pNoteToAddress->SetAddressUser(addresseeUserId);
	pNoteToAddressList->push_back(*pNoteToAddress);
	delete pNoteToAddress;
	pNotes->SetTo(pNoteToAddressList);

	pNoteCCAddressList		= new clsNoteAddressList();
	pNoteCCAddress			= new clsNoteAddress();
	pNoteCCAddress->SetAddressUser(addresseeUserId);
	pNoteCCAddressList->push_back(*pNoteCCAddress);
	delete pNoteCCAddress;
	pNotes->SetCC(pNoteToAddressList);


	pNotes->SetCategoryFilter(categoryFilter);

	pNotes->Load();
	pNoteList = pNotes->GetNotes();


	// Ok, let's see if we got anything
	*mpStream <<	"<br>"
					"<b>"
			  <<	pNoteList->size()
			  <<	"</b>"
					" eNotes were found."
					"<br><br>";

	if (pNoteList->size() == 0)
	{
		CleanUp();

		return;
	}

	// Ok, we got some, let's run through the list and get
	// a list of the users mentioned.
	for (i = pNoteList->begin();
		 i != pNoteList->end();
		 i++)
	{
		// First, who it's from
		lRequestedUserIds.push_back((*i)->GetUserIdFrom());

		// About...
		aboutUser	= (*i)->GetAboutUser();

		if (aboutUser != 0)
			lRequestedUserIds.push_back(aboutUser);
	}

	// Signal caching.
	mpUsers->BuildCache(&lRequestedUserIds,
						&lMissingUserIds);
		
	// We got some notes, let's display them!
	*mpStream <<	"<A NAME=messages>";
	*mpStream <<	"<TABLE WIDTH=100% BORDER=1>"
					"<TR>"
					"<TD BGCOLOR=\"#FFFFCC\" ALIGN=left WIDTH=15%>"
					"<center><B>From</B></center>"
					"</TD>"
					"<TD BGCOLOR=\"#FFFFCC\" ALIGN=left WIDTH=45%>"
					"<center><B>About</B></center>"
					"</TD>"
					"<TD BGCOLOR=\"#FFFFCC\" ALIGN=right WIDTH=15%>"
					"<center><B>When</B></center>"
					"</TD>"
					"</TR>"
					"</TABLE>";


	for (i = pNoteList->begin();
		 i != pNoteList->end();
		 i++)
	{
		ShowNote(*i);
	}

	// Clean up
	mpUsers->ClearCache();

	if (pTheAboutUser != NULL && pAboutUser == NULL)
		delete	pAboutUser;

	return;
}

void clseBayApp::AdminShowNote(CEBayISAPIExtension *pThis, 
							   char *pUserid,
							   char *pPass,
							   char *pAboutFilter,
							   int categoryFilter,
							   eBayISAPIAuthEnum authLevel)
{
	bool				error					= false;
	clsUser				*pTheAboutUser			= NULL;


	UserId				aboutUserId				= 0;
	ItemId				aboutItemId				= 0;

			
	// Setup
	SetUp();


	// We'll need a title here
	*mpStream <<	"<html>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Show eNotes"
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
					"Show eNotes"
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


	//Invalidate the Magic Password for Show eNote page

	for (int i=0; i<NUM_SPECIAL_PASS; i++) 
	{
		if (strcmp(pPass, 
				   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(i)) == 0)
		{
			*mpStream <<  "You may not view eNotes using MagicPassword. Please use your personal userid and password." 	
					  <<     "<br>"
		   			  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
		}
	}
	
	

    //Keep non-eBay persons from viewing eNotes
		
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
	// Now, we need to figure out if the user provided an "About" user
	// or an "About" item, or neither. It's pretty simple: If the field
	// doesn't contain "default", and translates to an integer, it's 
	// assumed to be an item number. Otherwise, it's a userid/email
	// address.
	//
	if (pAboutFilter != NULL					&&
		strcmp(pAboutFilter, "default") != 0		)
	{
		aboutItemId	= atoi(pAboutFilter);

		// If it's an item, then we're done. No sense in looking
		// it up, since it may not be here any more. If it's not
		// an item, let's validate the use
		if (aboutItemId == 0)
		{
			pTheAboutUser	= mpUsers->GetUser(pAboutFilter);
			if (pTheAboutUser == NULL)
			{
				*mpStream <<	"<h2>Invalid About User</h2>"
								"The \"About\" user you specified is not a valid eBay "
								"user. Please correct it and try again.";
				error	= true;
			}
			else
			{
				aboutUserId	= pTheAboutUser->GetId();
			}
		}
	}

	//
	// Let's validate the type filter
	//
	//
	// ** NOTE **
	//	One day, soon, we'll store these values in a nice table which
	//	ShowNoteShow can use to emit them, and we can validate them.
	//	For now, make sure these checks correspond to ShowNoteShow.
	//
	if (categoryFilter != 0						&&
		!clsNote::CheckNoteType(categoryFilter)		)
	{
		*mpStream <<	"<h2>Invalid \"Note category\" value</h2>"
						"This shouldn't happen unless you\'re using an old "
						"bookmark or typing in the URL directly. Please go back "
						"and make sure you\'re using the right form!";
		error	= true;
	}

	//
	// If we had an error, reshow...
	//
	if (error)
	{
		AdminInternalShowNoteShow(pUserid, pPass, pAboutFilter,
								  categoryFilter);

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	//
	// Let's get them!
	//
	ShowNotes(pAboutFilter, categoryFilter, pTheAboutUser);


	//
	// All done!
	//
	delete	pTheAboutUser;
	
	CleanUp();
	return;

}

