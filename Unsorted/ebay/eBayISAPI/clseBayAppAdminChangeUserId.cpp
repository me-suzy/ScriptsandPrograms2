/*	$Id: clseBayAppAdminChangeUserId.cpp,v 1.7.204.1.102.1 1999/08/01 02:51:42 barry Exp $	*/
//
//	File:	clseBayAppAdminChangeUserId.cpp
//
//	Class:	clseBayApp
//
//	Author:	Charles Manga (charles@ebay.com)
//
//	Function:
//
//		Handle a change User ID request
//
// Modifications:
//				- 01/06/98 charles	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// Error Messages
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry! You have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. If you did not receive this "
"e-mail, or if you have lost it, please return to the registration page and "
"re-register (with the same User ID and e-mail address) to have it sent to "
"you again."
"<br><P>"
"Please contact <a href=mailto:support@ebay.com>Customer Support</a> if you have any questions.<P>";

static const char *ErrorMsgUserIdUsed =
"<H2>User ID Rejected</H2>"
"<h3>Unique User ID</h3>"
"Sorry, your User ID must be unique.<BR>"
"Someone at eBay has already selected the User ID you requested. If you're having trouble coming up "
"with a unique User ID, you might try the following."
"<UL>"
"<LI>Refer to your favorite collecting area.<BR> If your name is Roger and you collect Rabbit figurines, "
"you might request a User ID such as \"Roger-Rabbit.\""
"<LI>Refer to your business or occupation.<BR> If you use %s to sell pet supplies, "
"you might request a User ID such as \"ACME_Roadrunner_kits\" or \"Roadrunner_Hunter.\""
"<LI>Refer to your hometown or state.<BR> If your name is Casper and you happen to live in Wyoming, "
"you might request a User ID such as \"Casper_Wyoming.\""
"<LI>Refer to your own sense of style.<BR> If you consider yourself a person of tremendous strength, "
"you might request a User ID such as \"Man-of-Steel.\""
"<LI>Add a number to the end of the userid you selected. <br> If you wanted \"Man-of-Steel.\" and its taken, "
"you might request a User ID such as \"Man-of-Steel-2000.\""
"</UL>"
"When all else fails, use your registered e-mail address as your User ID. "
"Or ask your friends at %s to help you select a User ID.<P>";

static const char *ErrorMsgUserIdHaveAmpercent =
"<H2>User ID Rejected</H2>"
"<h3>Illegal symbols</h3>"
"Sorry! The \"&\" sign is not allowed to be used in the User ID.<BR>"
"Acceptable characters are: "
"<UL>"
"<LI>Letters <I>a-zA-Z</I>"
"<LI>Numbers <I>0-9</I>"
"<LI>Asterisks <I>*</I>"
"<LI>Dollar signs <I>$</I>"
"<LI>Exclamation point <I>!</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"<LI>Hyphens <I>-</I>"
"</UL><P>";

static const char *ErrorMsgUserIdHaveAtSign =
"<H2>User ID Rejected</H2>"
"<h3>Illegal symbols</h3>"
"Sorry! The \"@\" sign is not allowed to be used in the User ID.<BR>"
"Acceptable characters are: "
"<UL>"
"<LI>Letters <I>a-zA-Z</I>"
"<LI>Numbers <I>0-9</I>"
"<LI>Asterisks <I>*</I>"
"<LI>Dollar signs <I>$</I>"
"<LI>Exclamation point <I>!</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"<LI>Underscores <I>_</I>"
"</UL><P>";

static const char *ErrorMsgUserIdNotChanged =
"<h2>Your User ID hasn't changed.</h2>"
"Sorry, the new User ID you requested is already your current User ID. "
"If you really want to change it, please try again. If you have any questions, "
"contact <a href=mailto:support@ebay.com>Customer Support</a> with the relevant information.<P>";

static const char *ErrorMsgUnknown =
"<h2>Unknown Registration Error</h2>"
"There has been an unknown error validating your registration. Please "
"report this error, along with all pertinent information (your selected "
"User ID, e-mail, name, address, etc.) to <a href=mailto:support@ebay.com>Customer Support</a>.<P>";

static const char *ErrorMsgDatabaseUpdate =
"<h2>Error during database update</h2>"
"There has been an error during the database update of your new User ID. Please "
"report this error, along with all pertinent information (your selected "
"User ID, e-mail, name, address, etc.) to <a href=mailto:support@ebay.com>Customer Support</a>.<P>";

static const char *ErrorMsgMail =
"<h2>Error Sending Confirmation Notice</h2>"
"Sorry, we could not send you your Change of User ID confirmation "
"notice via electronic mail. This is probably because your e-Mail "
"address was invalid. Please go back and check it again.<P>";

static const char *MsgUserConfirmIsNo =
"<h2>Thank you for respecting %s %s format</h2>"
"Please go back to change your %s and try again or contact the %s"
" Technical Support with the relevant information "
"if you have any questions.<P>";

static const char *MsgUserShortHistory =
"<P>The %s you entered is <B>%s</B>,<BR>"
"Your current %s is <B>%s</B><BR>"
"and your new %s will be <B>%s</B>"
"<H4>Do you want to confirm the change even though it's not the correct %s %s format?<BR>"
"Please choose Yes or No.</H4>";

static const char *ErrorMsgUserIdOrPasswordNotSpecified =
"<H2>%s or password invalid</H2>"
"Either the %s \"%s\" is not a registered %s user, or the password is incorrect. "
"Please go back and try again. Make sure you are not using any uppercase "
"characters or allowing blank space before, after or inside the %s or password.<P>"
"If you are not a registered %s user, you can proceed to our <B>Free</B> ";

void clseBayApp::AdminChangeUserId(CEBayISAPIExtension *pServer,
									char *pOldUserId, 
									char *pPass, 
									char *pNewUserId,
									int  confirm,
									eBayISAPIAuthEnum authLevel)
{
	char	*pBlock;
	clsUser *pMyUser;
	bool	isACorrectUserId = true;
	bool	isConfirm = false;
	bool	myAnswer  = false;
	int		mailRc;

	// Let's figure out which step we are !!!!
	switch(confirm)
	{
		case 0:
			{
				isConfirm = false;
				myAnswer  = true;
				break;
			}
			

		case 1:
			{
				isConfirm = true;
				myAnswer  = false;
				break;
			}

		case 2:
			{
				isConfirm = true;
				myAnswer  = true;
				break;
			}

		default:
			{
				isConfirm = false;
				myAnswer  = false;
				break;
			}
	}


	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</TITLE>"
			  <<	"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// For safety, we have to check that there is
	// an old User ID, a password and a new User ID
	if(FIELD_OMITTED(pOldUserId) || FIELD_OMITTED(pPass))
	{
		pBlock	 = new char[strlen(ErrorMsgUserIdOrPasswordNotSpecified) + 
							(3 * strlen(mpMarketPlace->GetCurrentPartnerName())) +
						    (3 * strlen(mpMarketPlace->GetLoginPrompt())) +
							strlen(pOldUserId) + 1];

		sprintf(pBlock,ErrorMsgUserIdOrPasswordNotSpecified,
				mpMarketPlace->GetLoginPrompt(),
				mpMarketPlace->GetLoginPrompt(),
				pOldUserId,
				mpMarketPlace->GetCurrentPartnerName(), 
				mpMarketPlace->GetLoginPrompt(),
				mpMarketPlace->GetCurrentPartnerName(), 
				mpMarketPlace->GetCurrentPartnerName());

		*mpStream	<<	pBlock;
		
		*mpStream	<<	"<A HREF="
					<<	"\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"services/registration/register-by-country.html"
					<<	"\">"
					<<	"registration page"
					<<	"</a> to become a registered "
					<<	mpMarketPlace->GetCurrentPartnerName()
					<<	" user.<P>";

		*mpStream	<<	"<BR>"	
					<<	mpMarketPlace->GetFooter();

		delete [] pBlock;
		CleanUp();
		return;
	}

	// The new User ID have to be specified !!!!
	if(FIELD_OMITTED(pNewUserId))
	{
		pBlock	 = new char[strlen(ErrorMsgUserIdOrPasswordNotSpecified) + 
							strlen(mpMarketPlace->GetCurrentPartnerName()) +
						    (4 * strlen(mpMarketPlace->GetLoginPrompt())) +
							(2 * strlen("new")) + 1];

		sprintf(pBlock,ErrorMsgUserIdOrPasswordNotSpecified,
				mpMarketPlace->GetCurrentPartnerName(), 
				mpMarketPlace->GetLoginPrompt(),
				mpMarketPlace->GetLoginPrompt(),
				"new",
				mpMarketPlace->GetLoginPrompt(),
				"new",
				mpMarketPlace->GetLoginPrompt());

		*mpStream	<<	pBlock << "<br>";
		*mpStream	<<	mpMarketPlace->GetFooter();

		delete [] pBlock;
		CleanUp();
		return;
	}

	if(isConfirm && !myAnswer)
	{
		// It's a confirmation and the user don't want to
		// his change his User ID
		pBlock	 = new char[strlen(MsgUserConfirmIsNo) + 
							(2 * strlen(mpMarketPlace->GetCurrentPartnerName())) + 
							(2 * strlen(mpMarketPlace->GetLoginPrompt())) + 1];

		sprintf(pBlock,MsgUserConfirmIsNo,
				mpMarketPlace->GetCurrentPartnerName(), mpMarketPlace->GetLoginPrompt(),
				mpMarketPlace->GetLoginPrompt(),mpMarketPlace->GetCurrentPartnerName());

		*mpStream	<<	pBlock << "<BR>";
		*mpStream	<<	mpMarketPlace->GetFooter();
		delete [] pBlock;
		CleanUp();
		return;
	}

#ifdef _MSC_VER
		strlwr(pOldUserId);
		strlwr(pNewUserId);
#endif

	// Check that the old User ID and the new user ID are differents
	// if it is the first step, It is not a confirmation
	if(!isConfirm && !strcmp(pOldUserId,pNewUserId))
	{
		*mpStream <<	ErrorMsgUserIdNotChanged
				  <<	"<BR><BR>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// get and check the old User ID and password
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pOldUserId, pPass, mpStream); 
	if (!mpUser)
	{
		// the User ID is unknown, bad news
		*mpStream	<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if(!isConfirm && strcmp(pNewUserId,mpUser->GetEmail()))
	{
		if(strchr(pNewUserId,'@'))
		{
			// The new User ID has an '@'
			// The new user ID is not the e-mail address
			// so we have get out of here
			*mpStream	<<	ErrorMsgUserIdHaveAtSign	<<	"<BR>";
			isACorrectUserId = false;
		}

		if(strchr(pNewUserId,'&'))
		{
			// The new User ID has an '&'
			// so we have get out of here
			*mpStream	<<	ErrorMsgUserIdHaveAmpercent	<<	"<BR>";
			isACorrectUserId = false;
		}

		if(isACorrectUserId)
		{
			// It is the first step, It is not a confirmation
			// Remove the space in pUserId and convert it to lower case
			// and check the ebay format rules
			isACorrectUserId = ValidateUserIdChange(pNewUserId,mpStream);
		}

		if(isACorrectUserId)
		{
			// Let's see if the userid is already taken. We 
			// can't use GetAndCheckUser, since it emits
			// error messages.
			// Basically, userid cannot be someone else's E-mail
			// or someone else userid !!!!
			// pNewUserId have to be unique we will have to check it
			// in the EBAY_USERS and the EBAY_USER_PAST_ALIASES tables  
			// The User ID is not the user's e-mail address
			// allow admin change userid back to old id -- added on 11-04-98
			pMyUser	=	mpUsers->GetUser(pNewUserId);

			if (pMyUser && (pMyUser->GetId() != mpUser->GetId()))
			{
				// UserId already seems to exist. Cannot rename.
				*mpStream	<<	ErrorMsgUserIdUsed
							<<	"<br>"
							<<	mpMarketPlace->GetFooter();

				delete pMyUser;
				isACorrectUserId = false;
				CleanUp();
				return;
			}

			// We got the user. Let's ensure he is in the right state
			if(!mpUser->IsConfirmed())
			{
				*mpStream	<<	ErrorMsgNotConfirmed
							<<	"<br>";
				isACorrectUserId = false;
			}


		}
	}

	//
	// Here we have to update the EBAY_USERS and EBAY_USER_PAST_ALIASES tables in the database
	// Don't forget to update the modified field by the sysdate
	// And insert the change of the user id in the 30 days history table
	//
	// This have to be done if every thing is OK witch means
	// isACorrectUserId = true
	//
	if(isACorrectUserId && myAnswer)
	{
		// We don't want no upper case to be store in the database
#ifdef _MSC_VER
		strlwr(pNewUserId);
#endif

		if(!mpUser->ChangeUserId(pNewUserId) )
		{
			*mpStream <<	ErrorMsgDatabaseUpdate
					  <<	"<br>"
					  <<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		// send a confirmation email to the User
		mailRc = MailUserChangeUserIdNotice(pOldUserId,
											pNewUserId,
											mpUser->GetEmail());
		if (!mailRc)
		{
			*mpStream	<<	ErrorMsgMail
						<<	"<br>"
						<<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
		}
	
		// Now, we can finally tell the user what happened
		// after checking the new User ID
		TellTheUserWhatHappen(pOldUserId,pNewUserId,mpStream);

	}
	else
	{
		if(!isConfirm && !isACorrectUserId)
		{
			// Open a confirmation page where we will do the change
			// if the user is OK. Otherwise, we will exit.
			// Let's make a short story of this User ID
			pBlock	 = new char[strlen(MsgUserShortHistory) + strlen(mpMarketPlace->GetCurrentPartnerName()) +
								(4 * strlen(mpMarketPlace->GetLoginPrompt())) +
								strlen(pOldUserId) + strlen(mpUser->GetUserId()) +
								strlen(pNewUserId) + 1];

			sprintf(pBlock,MsgUserShortHistory,
					mpMarketPlace->GetLoginPrompt(),pOldUserId,
					mpMarketPlace->GetLoginPrompt(),mpUser->GetUserId(),
					mpMarketPlace->GetLoginPrompt(),pNewUserId,
					mpMarketPlace->GetCurrentPartnerName(),mpMarketPlace->GetLoginPrompt());
			*mpStream	<<	pBlock;
			delete [] pBlock;
					
			// Now, the rest of the goop
			*mpStream	<<	"<form method=post action="
						<<	"\""
						<<	mpMarketPlace->GetCGIPath(PageAdminChangeUserId)
						<<	"eBayISAPI.dll"
						<<	"\""
						<<	">"
						<<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AdminChangeUserId\">"
						<<	"<INPUT TYPE=HIDDEN NAME=\"olduserid\" VALUE="
						<<	"\""
						<<	pOldUserId
						<<	"\""
						<<	">"
						<<	"\n"
						<<	"<INPUT TYPE=HIDDEN NAME=\"newuserid\" VALUE="
						<<	"\""
						<<	pNewUserId
						<<	"\""
						<<	">"
						<<	"\n"
						<<	"<INPUT TYPE=HIDDEN NAME=\"pass\" VALUE="
						<<	"\""
						<<	pPass
						<<	"\""
						<<	">"
						<<	"\n"
						<<	"<p>";

			*mpStream	<<	"<TABLE>"
						<<	"<TR>"
						<<	"<TD>Yes</TD>"
						<<	"<TD><INPUT TYPE=\"radio\" NAME=\"confirm\" VALUE=\"2\"></TD>"
						<<	"<TD>No</TD>"
						<<	"<TD><INPUT TYPE=\"radio\" NAME=\"confirm\" VALUE=\"1\" CHECKED></TD>"
						<<	"</TR>"
						<<	"</TABLE><P>";

			// And now, for the closing
			*mpStream	<<	"<strong>Press this button to confirm your answer.</strong>" 
						<<	"<p>"
						<<	"<blockquote><input type=submit value=\"Confirm Change "
						<<	mpMarketPlace->GetLoginPrompt()
						<<	"\"></blockquote>"
						<<	"<p>"
						<<	"\n"
						<<	"Press this button to clear the form if you made a mistake:"
						<<	"<p>"
						<<	"<blockquote><input type=reset value=\"clear form\"></blockquote>"
						<<	"\n"
						<<	"</form>";


			*mpStream	<<	"<BR>"
						<<	mpMarketPlace->GetFooter();

			CleanUp();
			return;

		}
	}

	// End of the movie
	CleanUp();
	return;
}

