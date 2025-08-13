//	File:		clseBayAppReInstateItem.cpp
//
//	Class:		clseBayApp
//
//	Blame:		Gurinder Grewal (ggrewal@ebay.com)
//
//	Function:
//				Provides admin functionality to bring the item back to live 
//				auction, which was removed from auction by admin before end of
//				auction
//
//	Modifications:
//				- 04/30/99 Gurinder - Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

void clseBayApp::AdminReInstateItem(CEBayISAPIExtension *pCtxt, 
								 char *pItemNo,eBayISAPIAuthEnum authLevel,
								 char* pUserId, char* pPass)
{
	SetUp();	
	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative ReInstate Item Request for "
			  <<	pItemNo
			  <<    " item."
			  <<	"</title>"
					"</head>"
			  <<	"<p>"
			  <<    mpMarketPlace->GetHeader();	

	mpUser = 
	mpUsers->GetAndCheckUserAndPassword(pUserId,	
										pPass,			
										mpStream,		
										true,			// Header sent alredy
										NULL,			// NO action
										false,			// Ghosts ok?
										false,			// Feedback needed?
										false,			// Account needed?
										true,			// Test Crypted?
										true);			// Admin Query

    if (!mpUser)
    {
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter()
					<<  flush;
		CleanUp();
		return;
    }

	if (mpUser && strstr(mpUser->GetEmail(), "@ebay.com") == 0)
	{
		*mpStream <<	"<font color=red size=+2>Not Authorized</font>"
						"You are not authorized to use this "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" function. "
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		*mpStream << "<FONT color=\"red\" size=6><br><br>\n"
				  << "Access denied! <br>"
				  << "Sorry! You do not have the privilege to use this option."
				  << "<br><br></FONT>";
		CleanUp(); 
		return;
	}

   	// Let's get the arc item	with description
	mpItem = mpItems -> GetItemArc(atoi(pItemNo));			
							
	if (!mpItem)
	{

		*mpStream <<	"<p>"
						"<H2><font color=red>"
						"Item \""
				  <<	pItemNo
				  <<	"\" is invalid or could not be found."
						"</H2></font>"
						"<p>"
						"Please go back and try again.";

		*mpStream <<	mpMarketPlace->GetFooter();
		*mpStream <<	flush;	
		
		CleanUp();
		return;

	}
	
	gApp->GetDatabase()->GetItemWithDescArc(mpItem->GetMarketPlaceId(),
											atoi(pItemNo),
											mpItem);

	//if the item exists then undo the remove item 	
	if (gApp->GetDatabase()->ReInstateItem(mpItem))
	{
		*mpStream <<	"<p>"
				  <<	"<font color=navy size=+1>"
				  <<	"Item \""
				  <<	pItemNo
				  <<	"\" has been reinstated successfully</font>"
			      <<	"<p>"
			      <<	mpMarketPlace->GetFooter();
		*mpStream <<	flush;
	}
	else
	{
		*mpStream <<	"<p>"
				  <<	"<font color=red size=+1>"
				  <<	"Item \""
				  <<	pItemNo
				  <<	"\" was not reinstated!</font>"
			      <<	"<p>"
			      <<	mpMarketPlace->GetFooter();
		*mpStream <<	flush;
	}

	CleanUp();
	return;
				
}

void clseBayApp::AdminReInstateItemLogin(char * pItemNo)
{
	SetUp();

	*mpStream << "<HTML><HEAD><TITLE>"
			  << mpMarketPlace->GetCurrentPartnerName()
			  << "Admin ReInstate Item Login"
			  << "</TITLE></HEAD>"
			  << mpMarketPlace->GetHeader();
			  

	*mpStream << "\n<FORM METHOD=POST ACTION=\""
			  << mpMarketPlace->GetCGIPath(PageAdminReInstateItem)
			  << "eBayISAPI.dll\">"
			  << "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdminReInstateItem\">";
			  
			  

	*mpStream	<<	"\n<table ><tr><td>Item# To ReInstate"				
				<<	":</td>\n"
				<<	"<td><font color=navy size=5>"
				<<  "<input type=\"text\" name=\"item\" value=\""
				<<  pItemNo
				<<  "\" size=40></td></font></tr>\n"					
				<<	"<tr><td>Your "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":</td>\n"
					"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
					"<tr><td>Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":</td>\n"
					"<td><input type=\"password\" name=\"password\" size=40></td></tr>\n"
					"</table>\n";
	*mpStream << "<INPUT TYPE=\"Submit\" VALUE=\"ReInstate the item!\"><br><br>";

	*mpStream << mpMarketPlace->GetFooter();
	CleanUp();
}
