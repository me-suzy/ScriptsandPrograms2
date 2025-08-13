/*	$Id: clseBayAppChangeUserIdShow.cpp,v 1.12.66.5.4.2 1999/08/05 18:58:55 nsacco Exp $	*/
//
//	File:	clseBayAppChangeUserIdShow.cpp
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
//				- 12/16/97 charles	- Created
//				- 07/02/99 nsacco - removed use of mpMarketPlace->GetName() and wrote 'eBay'
//									into the static strings
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again.";
*/

// 07/02/99 nsacco - replaced %s with 'eBay'
static const char *ErrorMsgUserIdUsed =
"<H2>User ID Rejected</H2>"
"<h3>Unique User ID</h3>"
"Sorry! Your User ID must be unique.<BR>"
"Someone at eBay has already selected the User ID you requested. If you're having trouble coming up "
"with a unique User ID, you might try the following."
"<UL>"
"<LI>Refer to your favorite collecting area.<BR> If your name is Roger and you collect Rabbit figurines, "
"you might request a User ID such as \"Roger-Rabbit.\""
"<LI>Refer to your business or occupation.<BR> If you use eBay to sell pet supplies, "
"you might request a User ID such as \"ACME_Roadrunner_kits\" or \"Roadrunner_Hunter.\""
"<LI>Refer to your hometown or state.<BR> If your name is Casper and you happen to live in Wyoming, "
"you might request a User ID such as \"Casper_Wyoming.\""
"<LI>Refer to your own sense of style.<BR> If you consider yourself a person of tremendous strength, "
"you might request a User ID such as \"Man-of-Steel.\""
"<LI>Add a number to the end of the userid you selected. <br> If you wanted \"Man-of-Steel.\" and its taken, "
"you might request a User ID such as \"Man-of-Steel-2000.\""
"</UL>"
"When all else fails, use your registered e-mail address as your User ID. "
"Or ask your friends at eBay to help you select a User ID.<P>";

// 07/02/99 nsacco - replaced %s with 'eBay'
static const char *ErrorMsgUserIdLikeEmail =
"<H2>Your new User ID is not a correct eBay User ID</H2>"
"<B>eBay</B> correct User ID's:"
"<UL>"
"<LI>Must be unique."
"<LI>May be your registered user's E-mail address."
"<LI>May contain letters, numbers or symbols."
"<LI>May not contain the <B>@</B> or other illegal characters."
"<LI>May not contain the word <B>eBay</B>."
"<LI>Is case insensitive."
"<LI>Contains between <B>%d</B> to <B>%d</B> characters."
"<LI>Does not contains spaces."
"<LI>Cannot be changed more than once in <B>%d</B> days."
"<LI><B>Obscene, profane or hateful</B> User IDs are not permitted."
"</UL>"
"Please try again. <P>";

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
"<LI>Hyphens <I>-</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
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
"<LI>Hyphens <I>-</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"</UL><P>";

static const char *ErrorMsgUserIdHaveHyphens =
"<H2>User ID Rejected</H2>"
"<h3>Illegal symbols</h3>"
"Sorry! The \"_\" sign is not allowed to be used in the User ID.<BR>"
"Acceptable characters are: "
"<UL>"
"<LI>Letters <I>a-zA-Z</I>"
"<LI>Numbers <I>0-9</I>"
"<LI>Asterisks <I>*</I>"
"<LI>Dollar signs <I>$</I>"
"<LI>Exclamation point <I>!</I>"
"<LI>Hyphens <I>-</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"</UL><P>";

static const char *ErrorMsgOmittedEmail =
"<h2>The E-mail is omitted or invalid</h2>"
"Sorry, the e-mail is omitted or invalid. "
"Please go back, and try again.";

static const char *ErrorMsgUserIdHaveSpecialCharacter =
"<H2>User ID Rejected</H2>"
"<h3>Illegal symbols</h3>"
"Sorry! Your User ID may contain letters, numbers and symbols.<BR>"
"You may use :"
"<UL>"
"<LI>Letters <I>a-zA-Z</I>"
"<LI>Numbers <I>0-9</I>"
"<LI>Asterisks <I>*</I>"
"<LI>Dollar signs <I>$</I>"
"<LI>Exclamation point <I>!</I>"
"<LI>Hyphens <I>-</I>"
"<LI>Parentheses (left and right) <I>( )</I>"
"<LI>Periods <I>.</I>"
"</UL><P>"
"The User ID that you requested may contain characters which are not permitted. "
"Please return to the registration page and choose another User ID.<P>";

// 07/02/99 nsacco - removed %s and replaced with 'eBay'
static const char *ErrorMsgUserIdHaveEbay =
"<H2>User ID Rejected</H2>"
"<h3>eBay</h3>"
"Sorry! A User ID may not contain the word \"eBay.\"<BR>"
"This is to prevent anyone from impersonating an eBay employee. We regret that this means that "
"\"I-love-eBay\" cannot be a User ID. But thank you for understanding.<P>";

static const char *ErrorMsgUserIdHaveSpaces =
"<H2>User ID Rejected</H2>"
"<h3>Spaces</h3>"
"Sorry! Your User ID may not contain spaces.<BR>"
"Try using a dash symbol (-) instead.<P>";

// 07/02/99 nsacco - removed %s and replaced with 'eBay'
static const char *ErrorMsgUserIdHaveOnlySpaces =
"<H2>User ID Rejected</H2>"
"<B>eBay</B> correct User ID's may contain letters, numbers or symbols.<BR>"
"Please change it and try again. <P>";

// 07/02/99 nsacco - removed %s and replaced with 'eBay'
static const char *ErrorMsgUserIdHaveObsceneWords =
"<H2>User ID Rejected</H2>"
"<h3>Obscene, Profane or Hateful</h3>"
"Sorry! Your requested User ID has been rejected.<BR>"
"Think about it. Using a User ID doesn't mean that other eBay users don't know who you are!<P>"
"We suggest that you apply the \"Mom or Dad\" Rule of Decency when choosing a User ID.<BR>"
" What would your Mom or Dad say if they knew you were using obscenity, profanity or hateful language "
"in a public place? Better Yet. What would they do to you?<P>"
"If you think Mom or Dad wouldn't approve, you can probably be sure that your fellow eBay users won't be too "
"impressed either. Why do you care? If you are a seller and a potential buyer sees your User ID and it "
"doesn't say, \"I'm a trustworthy, honest person,\" he/she is less likely to bid on your item. "
"No trust, no bids. If you are a bidder and a seller sees your User ID and it doesn't say, "
"\"I'm a trustworthy, honest person,\" he/she is less likely to take your bid seriously. No trust, no sale."
"<P>Think about it.<P>";

static const char *ErrorMsgUserIdHaveIncorrectLength =
"<H2>User ID Rejected</H2>"
"<h3>%d-%d Characters</h3>"
"Sorry! Your User ID must be between %d and %d characters long.<P>";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUserIdChanged =
"<H2>User ID Rejected</H2>"
"<h3>Changed more than once in a %d-day period</h3>"
"Sorry! You cannot change your User ID more than once in any %d-day period. And you have already changed your "
"User ID in the last %d days. This is to prevent confusion - mostly for the rest of us.<P>"
"If you have questions, please go to our <a href=\"%shelp/index.html\">Help Overview</a> page. "
"<P>";
*/

static const char *ErrorMsgUserIdNotChanged =
"<h2>Your User ID hasn't changed.</h2>"
"Sorry! Your new User ID is already to your current User ID.<BR>"
"If you really want to change it, please try again. "
"<P>";

static const char *ErrorMsgUnknown =
"<h2>Unknown Registration Error</h2>"
"There has been an unknown error validating your registration. Please "
"report this error, along with all pertinent information (your selected "
"User ID, name, address, etc.) to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgDatabaseUpdate =
"<h2>Error during database update</h2>"
"There has been an error during the database update of your new User ID. Please "
"report this error, along with all pertinent information (original and selected User ID"
" e-mail, etc.) to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/


static const char *ErrorMsgMail =
"<h2>Error Sending Confirmation Notice</h2>"
"Sorry, we could not send you your Change of User ID confirmation "
"notice via electronic mail. This is probably because your e-mail "
"address was invalid. Please go back and check it again.";

// 07/02/99 nsacco - removed %s and replaced with 'eBay'
static const char *ErrorMsgNewUserIdNotSpecified =
"<H2>Your eBay %s cannot be changed.</H2>"
"Sorry! Your %s cannot be changed without knowing your %s %s.<BR>"
"Please go back, enter your %s %s and try again.";

// 07/02/99 nsacco - removed %s and replaced with 'eBay'
static const char *ErrorMsgUserIdOrPasswordNotSpecified =
"<H2>%s or password invalid</H2>"
"Either the %s \"%s\" is not a registered eBay user, or the password is incorrect. "
"If you have mistyped the User ID, please go back and try again. If you have "
"forgotten your User ID, you may use your e-mail address. <P>"
"Make sure you are not using any uppercase "
"characters or allowing blank space before, after or inside the %s or password.<P>"
"If you are not a registered eBay user, you can proceed to our <B>Free</B> ";

bool clseBayApp::ValidateUserIdChange(char *pUserId, ostream *pStream)
{
	char	*pBlock;
	char	*pBuff;
	char	*pLowerUserId;
	char	*pBadWord;
	int		i = 0;
	int		badWordClass = 0;
	int     badWordCombination = 0;

	// Is there something to test ????
	if( !pUserId || (strlen(pUserId) == 0 ) )
	{
		return false;
	}

	pBuff = new char[strlen(pUserId) + 1];
	strcpy(pBuff, pUserId);

	// Are all the caracters spaces ???
	// Remove the spaces before 
	while(pBuff[i])
	{
		if(pBuff[i] == ' ' || pBuff[i] == '\t')
		{
			i++; // Number of spaces
		}
		else
		{
			// All the caracter are not spaces
			strcpy(pUserId,&pBuff[i]);
			break;
		}
		
	}

	if( i == strlen(pBuff) )
	{
		// All the caracters are spaces
		// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
		pBlock	 = new char[strlen(ErrorMsgUserIdHaveOnlySpaces) + 1];

		sprintf(pBlock,ErrorMsgUserIdHaveOnlySpaces);
		
		if (pStream)
			*pStream <<	pBlock << "<br>";

		delete [] pBlock;
		delete [] pBuff;
		return false;
	}

	strcpy(pBuff,pUserId);
	i = strlen(pBuff) - 1;

	// Remove white spaces at the end
	while(pBuff[i])
	{
		if(pBuff[i] == ' ' || pBuff[i] == '\t')
		{
			pBuff[i] = '\0';
		}
		else
		{
			break;
		}

		i--;
	}

	if( i < 0 )
	{
		// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
		pBlock	 = new char[strlen(ErrorMsgUserIdHaveOnlySpaces) + 1];

		sprintf(pBlock,ErrorMsgUserIdHaveOnlySpaces);
		
		if (pStream)
			*pStream <<	pBlock << "<br>";

		delete [] pBlock;
		delete [] pBuff;
		return false;
	}

	strcpy(pUserId,pBuff);
	i = 0;

	// Is there any space inside ????
	while(pBuff[i])
	{
		if(pBuff[i] == ' ' || pBuff[i] == '\t')
		{	
			// There is space inside the User ID
			if (pStream)
				*pStream <<	ErrorMsgUserIdHaveSpaces << "<br>";
			delete [] pBuff;
			return false;
		}
		i++;
	}

	if(strstr(pUserId,"&"))
	{
		// The new User ID has an "&"
		if (pStream)
			*mpStream	<<	ErrorMsgUserIdHaveAmpercent << "<br>";
		delete [] pBuff;
		return false;
	}

	//Check if "_" is in Userid, usderscore is no longer allowed
	if(strchr(pUserId,'_'))
	{
		if (pStream)
			*mpStream << ErrorMsgUserIdHaveHyphens << "<BR>";
		delete [] pBuff;
		return false;
	}

	// is the length correct ???
	if( strlen(pUserId) > EBAY_MAX_USERID_SIZE ||
	    strlen(pUserId) < EBAY_MIN_USERID_SIZE  )
	{
		// Incorrect Length
		pBlock	 = new char[strlen(ErrorMsgUserIdHaveIncorrectLength) + (4 * 3) + 1];

		sprintf(pBlock,ErrorMsgUserIdHaveIncorrectLength,EBAY_MIN_USERID_SIZE,
				EBAY_MAX_USERID_SIZE,EBAY_MIN_USERID_SIZE,EBAY_MAX_USERID_SIZE);
		if (pStream)
			*pStream <<	pBlock << "<br>";

		delete [] pBlock;
		delete [] pBuff;
		return false;
	}

	// Let's check if the User ID have allowed characters who are
	//letters a-z, numbers 0-9, *, $, !, _, +, ., (, ), -
	i = 0;
	while(pBuff[i])
	{

		if(IseBayAlnum(pBuff[i]) || 
			(pBuff[i] == '*')	 ||
			(pBuff[i] == '$')	 ||
			(pBuff[i] == '!')	 ||
			(pBuff[i] == '-')	 ||
			(pBuff[i] == '(')	 ||
			(pBuff[i] == ')')	 ||
			(pBuff[i] == '.')	 )
//			(pBuff[i] == '_')	  )
		{
			i++;
		}
		else
		{
			// There is space inside the User ID
			if (pStream)
				*pStream <<	ErrorMsgUserIdHaveSpecialCharacter << "<br>";
			delete [] pBuff;
			return false;
		}
	}

	pLowerUserId = new char[strlen(pUserId) + 1];
	strcpy(pLowerUserId,pUserId);
	// convert to lower case
	strlwr(pLowerUserId);

	pBadWord = new char[EBAY_MAX_USERID_SIZE + 1];
	pBlock = NULL;

	// Check for the obscene or profane or hateful words in the User ID
	if( clsUtilities::TooVulgar(pLowerUserId,&badWordClass,pBadWord, &badWordCombination) )
	{
		
//		if ((badWordClass == clsUtilities::VULGAR) || (badWordClass == clsUtilities::RESERVED))
        if (badWordCombination & ( clsUtilities::VULGAR))
		{

			// There is obscene words in this User ID
			// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
			pBlock = new char[strlen(ErrorMsgUserIdHaveObsceneWords) + 1];

			sprintf(pBlock,ErrorMsgUserIdHaveObsceneWords);
			
		}
		else if (badWordCombination & ( clsUtilities::RESERVED))
		{ 
			// The User ID contains the word "eBay"
			// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
			pBlock	 = new char[strlen(ErrorMsgUserIdHaveEbay) + 1];

			sprintf(pBlock,ErrorMsgUserIdHaveEbay);

		}

		if(pBlock)
		{
			if (pStream)
				*pStream	<<	pBlock	<<	"<br>";
			delete [] pBlock;

			delete [] pBadWord;
			delete [] pLowerUserId;
			delete [] pBuff;
			return false;
		}
		
	}

	// convert to lower case
#ifdef _MSC_VER
	strlwr(pUserId);
#endif

	// delete and exit 
	delete [] pLowerUserId;
	delete [] pBuff;
	delete [] pBadWord;
	return true;
}


int clseBayApp::MailUserChangeUserIdNotice(char			*pOldUserId, 
										   char			*pNewUserId, 
										   const char	*pEmail)
{
	clsMail		*pMail;
	ostream		*pMailStream;
	char		subject[256];
	int			mailRc;
	clsAnnouncement			*pAnnouncement;
	char*		pTemp;

	// We need a mail object
	pMail		= new clsMail;
	pMailStream	= pMail->OpenStream();

	// Emit
	*pMailStream	<<	"Dear "
					<<	pOldUserId
					<<	",\n\n";

	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit change User ID announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ChgUserId,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMailStream	<<	"Your "
					<<	mpMarketPlace->GetCurrentPartnerName()
					<<	" User ID"
					<<	" change process is completed!\n";

	*pMailStream	<<	"Your User ID"
					<<	" before this change was \""
					<<	pOldUserId
					<<	"\".\n";

	*pMailStream	<<	"Your User ID"
					<<	" after this change will be \""
					<<	pNewUserId
					<<	"\".\n\n";

	// emit general footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit change User ID footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ChgUserId,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// hardcoded link buttons notice
	*pMailStream    << "NOTICE:\n"
			"Because you have changed your User ID, you may need to update certain eBay\n"
			"features which depend on it.  Features selected for use with your old Use\n"
			"ID will cease to work after 30 days.\n"
			"*  If you have bookmarked your My eBay page or a seller search results\n"
			"page, you'll need to re-bookmark those pages.\n"
			"*  If you use an eBay link button to display a list of your auctions from\n"
			"your own web page, The HTML tag provided for that link button contains\n"
			"your old User ID.  There are two ways to make this change:\n"
			"(1) Visit ";
	*pMailStream << mpMarketPlace->GetHTMLPath();
	*pMailStream << "services/buyandsell/link-buttons.html to request a new HTML tag.\n"
			"--OR--\n"
			"(2) Update the HTML in the source code on your web page by replacing your\n"
			"old User ID with your new one.\n";
	*pMailStream << "\n";
	

	*pMailStream	<<	mpMarketPlace->GetThankYouText()
					<<	"\n"
					<<	mpMarketPlace->GetHomeURL()
					<<	"\n";

	// Send
	// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
	sprintf(subject, "eBay Change User ID");

	mailRc =	pMail->Send((char *)pEmail, 
							(char *)mpMarketPlace->GetConfirmEmail(),
							subject);

	// All done!
	delete	pMail;

	return mailRc;

}


void clseBayApp::TellTheUserWhatHappen(char		*pOldUserId, 
									   char		*pNewUserId,
									   ostream	*pStream) 
{
	// Emit
	// Now, we can finally tell the user what happened
	*pStream	<<	"<H2>Change User ID Completed</H2>"
				<<	"<P>Congratulations! Your User ID has been changed "
				<<	"from \"<b><font color=\"red\">"
				<<	pOldUserId
				<<	"</font></b>\" to \"<b><font color=\"green\">"
				<<	pNewUserId
				<<	"</font></b>\". To alert other "
				<<	"eBay users of your new look, a \"shades\" icon "
				<<	"will appear after your User ID for "
				<<	EBAY_USERID_EMBARGO_OLD_USERID_DAYS
				<<	" days. During this time, your old User ID will be \"embargoed.\""
				<<	" No one else will be able to use your old User ID until the "
				<<	EBAY_USERID_EMBARGO_OLD_USERID_DAYS
				<<	"-day period has expired, and your current auctions and other "
				<<	"eBay activity will be updated immediately to reflect your new User ID.<P>\n"
				<<	mpMarketPlace->GetFooter();
	return;

}


void clseBayApp::ChangeUserIdShow(CEBayISAPIExtension *pServer,
							char *pOldUserId, char *pPass, char *pNewUserId)
{
	char	*pBlock;
	int		mailRc;
	clsUser *pMyUser = NULL;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change User ID "
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// For safety, we have to check that there is
	// an old User ID, a password and a new User ID before process
	if(FIELD_OMITTED(pOldUserId) || FIELD_OMITTED(pPass))
	{
		pBlock	 = new char[strlen(ErrorMsgUserIdOrPasswordNotSpecified) + 
							(3 * strlen(mpMarketPlace->GetLoginPrompt())) +
							strlen(pOldUserId) + 1];

		sprintf(pBlock,ErrorMsgUserIdOrPasswordNotSpecified,
				mpMarketPlace->GetLoginPrompt(),
				mpMarketPlace->GetLoginPrompt(),
				pOldUserId,
				mpMarketPlace->GetLoginPrompt());

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

		*mpStream	<<	"<BR>"	<<	mpMarketPlace->GetFooter();

		delete [] pBlock;
		CleanUp();
		return;
	}

	// The new User Id have to be specified !!!
	if(FIELD_OMITTED(pNewUserId))
	{
		pBlock	 = new char[strlen(ErrorMsgNewUserIdNotSpecified) + 
							(4 * strlen(mpMarketPlace->GetLoginPrompt())) +
							(2 * strlen("new")) + 1];

		sprintf(pBlock,ErrorMsgNewUserIdNotSpecified,
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

	// convert to lower case
#ifdef _MSC_VER
	strlwr(pOldUserId);
	strlwr(pNewUserId);
#endif

	// Check that the old User ID and the new user ID are differents
	if(!strcmp(pOldUserId,pNewUserId))
	{
		*mpStream <<	ErrorMsgUserIdNotChanged
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// get and check the old User ID and password
	mpUser = mpUsers->GetAndCheckUserAndPassword(pOldUserId, pPass, mpStream); 
	if (!mpUser)
	{
		*mpStream	<<	"<BR>"	<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Let's see if the userid is already taken. We 
	// can't use GetAndCheckUser, since it emits
	// error messages.
	// Basically, userid cannot be someone else's E-mail
	// or someone else userid !!!!
	// pNewUserId have to be unique we will have to check it
	// in the EBAY_USERS and the EBAY_USER_PAST_ALIASES tables
	if(strcmp(pNewUserId, mpUser->GetEmail()))
	{
		// Check that if the character "@" is in pNewUserId then,
		if(strchr(pNewUserId,'@'))
		{
			// the new User ID is not an e-mail address
			// pUserId has an @ and is not the same as email
			*mpStream << ErrorMsgUserIdHaveAtSign << "<BR>";
			*mpStream << mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		// Check that if the character "&" is in pNewUserId then,
		if(strchr(pNewUserId,'&'))
		{
			// the new User ID is not an e-mail address
			// pUserId has an @ and is not the same as email
			*mpStream << ErrorMsgUserIdHaveAmpercent << "<BR>";
			*mpStream << mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		// Remove the space in pNewUserId and convert it to lower case
		// and check if there is no space inside or the length is OK
		// also check if there a special not allowed character
		if (!ValidateUserIdChange(pNewUserId,mpStream))
		{
			*mpStream <<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		pMyUser	=	mpUsers->GetUser(pNewUserId);
	}

	if (pMyUser && (pMyUser->GetId() != mpUser->GetId()))
	{
		// UserId already seems to exist and belong to a different user. 
		// Cannot rename.
		// eBay User ID has to be unique
		// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
		pBlock	 = new char[strlen(ErrorMsgUserIdUsed)  + 1];
		sprintf(pBlock,ErrorMsgUserIdUsed);

		*mpStream	<<	pBlock	<< "<BR>";
		*mpStream	<<	mpMarketPlace->GetFooter();

		delete [] pBlock;
		delete pMyUser;
		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (!mpUser->IsConfirmed())
	{
	//	*mpStream <<	ErrorMsgNotConfirmed

	// kakiyama 07/07/99


		*mpStream << clsIntlResource::GetFResString(-1,
							"<h2>Unconfirmed Registration</h2>"
							"Sorry, you have not yet confirmed your registration."
							"You should have received an e-mail with instructions for "
							"confirming your registration. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">Registration</a>"
							" and re-register "
							"(with the same e-mail address) to have it sent to "
							"you again.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	//
	// Check the number of changes in a generic time (Ex: Once a month)
	// but we allow user change back their userid to email at anytime
	// in the User ID changes history table EBAY_USER_PAST_ALIASES
	//

	if (mpUser->CanUserChangeUserId() && strcmp (mpUser->GetEmail(), pNewUserId))
	{

	//	pBlock	 = new char[strlen(ErrorMsgUserIdChanged) + (5 * 3) + 1];

	// kakiyama 07/07/99

		pBlock   = new char[strlen(clsIntlResource::GetFResString(-1,
									"<H2>User ID Rejected</H2>"
									"<h3>Changed more than once in a %{1:EBAY_USERID_CHANGE_DAYS}-day period</h3>"
									"Sorry! You cannot change your User ID more than once in any %{2:EBAY_USERID_CHANGE_DAYS}-day period. And you have already changed your "
									"User ID in the last %{3:EBAY_USERID_CHANGE_DAYS} days. This is to prevent confusion - mostly for the rest of us.<P>"
									"If you have questions, please go to our <a href=\"%{4:GetHTMLPath}/help/index.html\">Help Overview</a> page. "
									"<P>",
									clsIntlResource::ToString(EBAY_USERID_CHANGE_DAYS),
									clsIntlResource::ToString(EBAY_USERID_CHANGE_DAYS),
									clsIntlResource::ToString(EBAY_USERID_CHANGE_DAYS),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									NULL))];

		sprintf(pBlock,clsIntlResource::GetFResString(-1,
							"<H2>User ID Rejected</H2>"
							"<h3>Changed more than once in a %{1:EBAY_USERID_CHANGE_DAYS}-day period</h3>"
							"Sorry! You cannot change your User ID more than once in any %{2:EBAY_USERID_CHANGE_DAYS}-day period. And you have already changed your "
							"User ID in the last %{3:EBAY_USERID_CHANGE_DAYS} days. This is to prevent confusion - mostly for the rest of us.<P>"
							"If you have questions, please go to our <a href=\"%{4:GetHTMLPath}/help/index.html\">Help Overview</a> page. "
							"<P>",
							clsIntlResource::ToString(EBAY_USERID_CHANGE_DAYS),
							clsIntlResource::ToString(EBAY_USERID_CHANGE_DAYS),
							clsIntlResource::ToString(EBAY_USERID_CHANGE_DAYS),
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL));

		*mpStream	<<	pBlock
					<<	"<br>"
					<<	mpMarketPlace->GetFooter();

		// We have to display a mask here and we have to link it with the history page
		// Link on the mask wich call the history page of the specific user
		CleanUp();
		delete [] pBlock;
		return;
	}

	//
	// Here we have to update the EBAY_USERS and EBAY_USER_PAST_ALIASES tables in the database
	// Don't forget to update the modified field by the sysdate
	// And insert the change of the user id in the 30 days history table
	//
	if(!mpUser->ChangeUserId(pNewUserId))
	{
	//	*mpStream	<<	ErrorMsgDatabaseUpdate

	// kakiyama 07/07/99

		*mpStream	<< clsIntlResource::GetFResString(-1,
								"<h2>Error during database update</h2>"
								"There has been an error during the database update of your new User ID. Please "
								"report this error, along with all pertinent information (original and selected User ID"
								" e-mail, etc.) to "
								"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
								clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
								NULL)
					<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// And mail the notice. We do this here because if we
	// have a problem, the user will never be able to 
	// confirm.
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
	TellTheUserWhatHappen(pOldUserId,pNewUserId,mpStream);

	CleanUp();
	return;
}

