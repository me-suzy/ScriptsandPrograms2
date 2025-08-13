//
//	File:	clseBayTopSeller.cpp
//
//	Class:	clseBayTopSeller
//
//	Author:	pete helme (pvh@ebay.com)
//
//	Function:
//
//	Handles all top seller functions
//
// Modifications:
//				- 11/19/98 pvh - Created
//

#include "ebihdr.h"


void clseBayApp::ShowTopSellerStatus(CEBayISAPIExtension *pServer, 
									 char *pUserId,
									 eBayISAPIAuthEnum authLevel)
{
	time_t		theTime;
	struct tm	*pTheTimeTM;
	char		cTheTime[40];
	int			level;

	SetUp();
	
	EmitHeader("Top Seller Status");
		
	// Let's see if we're allowed to do this
	if(!CheckAuthorization(authLevel)) {

		CleanUp();
		
		return;
	}
	
	// Get the user
	mpUser	= mpUsers->GetAndCheckUser(pUserId, mpStream);
	
	if (!mpUser)
	{
		*mpStream <<	"<p>This is not a valid eBay user."
			<<	mpMarketPlace->GetFooter();
		
		CleanUp();
		
		return;
	}
	
	// 
	level = mpUser->GetTopSellerLevel();
	
	*mpStream	<<	"Current Top Seller level for user <b>"
		<<	mpUser->GetUserId()
		<<	"</b> : "
		<<	level;		
	
	// date
	if(mpUser->GetTopSellerInitiatedDate() > 0) {
		theTime = mpUser->GetTopSellerInitiatedDate();
		pTheTimeTM	= localtime(&theTime);
		
		strftime(cTheTime, sizeof(cTheTime),
			"%m/%d/%y %H:%M",
			pTheTimeTM);
		
		*mpStream	<<	"<br>Date added to Top Seller program: "
			<<	cTheTime;		
	}
	
	
	*mpStream	<<	"<form method=post action="
		"\""
		<<	mpMarketPlace->GetCGIPath(PageAdminSetTopSellerLevelConfirmation)
		<<	"eBayISAPI.dll"
		"\""
		">"
		"Set new level: "
		"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"SetTopSellerLevelConfirmation\">";
	
	// level selector
	*mpStream	<<	" <select name=\"level\" size=\"1\">\n";
	
	if(level == 0) {
		*mpStream	<<	"<option selected value=\"0\">0 (remove from Top Seller program)</option>\n";
	} else {
		*mpStream	<<	"<option value=\"0\">0 (remove from Top Seller program)</option>\n";
	}
	
	if(level == 1) {
		*mpStream	<<	"<option selected value=\"1\">Level 1</option>\n";
	} else {
		*mpStream	<<	"<option value=\"1\">Level 1</option>\n";
	}
	
	if(level == 2) {
		*mpStream	<<	"<option selected value=\"2\">Level 2</option>\n";
	} else {
		*mpStream	<<	"<option value=\"2\">Level 2</option>\n";
	}
	
	if(level == 3) {
		*mpStream	<<	"<option selected value=\"3\">Level 3</option>\n";
	} else {
		*mpStream	<<	"<option value=\"3\">Level 3</option>\n";
	}
	
	if(level == 4) {
		*mpStream	<<	"<option selected value=\"4\">Level 4</option>\n";
	} else {
		*mpStream	<<	"<option value=\"4\">Level 4</option>\n";
	}
	
	if(level == 5) {
		*mpStream	<<	"<option selected value=\"5\">Level 5</option>\n";
	} else {
		*mpStream	<<	"<option value=\"5\">Level 5</option>\n";
	}
	
	*mpStream	<<	"</select> <input type=\"submit\" value=\"Submit\"\n>"
		"</p>"
		"<input type=hidden name=userid value="
		<<	(mpUser)->GetUserId()
		<<	">"
		"</form>\n";
	
	
	// Clean
	
	*mpStream <<	"<br>"
		<<	mpMarketPlace->GetFooter();
	
	CleanUp();
	
	return;
}


void clseBayApp::SetTopSellerLevelConfirmation(CEBayISAPIExtension *pServer, 
									char *pUserId,
									int level,
									eBayISAPIAuthEnum authLevel)
{
	SetUp();

	EmitHeader("Top Seller Level Change Confirmation");

	// Let's see if we're allowed to do this
	if(!CheckAuthorization(authLevel)) {

		CleanUp();

		return;
	}

	// Get the user
	mpUser	= mpUsers->GetAndCheckUser(pUserId, mpStream);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// 
	*mpStream	<<	"Do you really want to change the level of user <b>"
				<<	mpUser->GetUserId()
				<<	"</b> from "
				<<	mpUser->GetTopSellerLevel()		
				<<	" to "
				<<	level
				<<	"?<br><br>";		

	*mpStream	<<	"<table><tr><td>";
	*mpStream	<<	"<form method=post action="
					"\""
				<<	mpMarketPlace->GetCGIPath(PageAdminSetTopSellerLevel) 
				<<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"SetTopSellerLevel\">"
					"\n"
					"<input type=\"submit\" value=\"Yes\"\n>"
					"<input type=hidden name=userid value="
				<<	(mpUser)->GetUserId()
				<<	">\n"
					"<input type=hidden name=level value="
				<<	level
				<<	">\n</form>";

	*mpStream	<<	"</td><td>";

	*mpStream	<<	"<form method=post action="
					"\""
				<<	mpMarketPlace->GetCGIPath(PageAdminShowTopSellerStatus) 
				<<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ShowTopSellerStatus\">"
					"\n"
					"<input type=\"submit\" value=\"No, return to TopSeller status page \">"
					"</p>"
					"<input type=hidden name=userid value="
				<<	(mpUser)->GetUserId()
				<<	">\n"
					"</form>\n";

	*mpStream	<<	"</td></tr></table>";
	
	// Clean

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


void clseBayApp::SetTopSellerLevel(CEBayISAPIExtension *pServer, 
								   char *pUserId,
								   int level,
								   eBayISAPIAuthEnum authLevel)
{
	SetUp();
	
	EmitHeader("Top Seller Level Changed");
		
	// Let's see if we're allowed to do this
	if(!CheckAuthorization(authLevel)) {

		CleanUp();
		
		return;
	}
	
	// Get the user
	mpUser	= mpUsers->GetAndCheckUser(pUserId, mpStream);
	
	if (!mpUser)
	{
		*mpStream <<	"<p>This is not a valid ebay user."
			<<	mpMarketPlace->GetFooter();
		
		CleanUp();
		
		return;
	}
	
	// set the new level
	mpUser->SetTopSellerLevel((TopSellerLevelEnum)level);

	// update them!
	mpUser->UpdateUser();

	*mpStream	<<	"Top Seller level for user <b>"
				<<	mpUser->GetUserId()
				<<	"</b> is now: "
				<<	mpUser->GetTopSellerLevel()
				<<	"<br>";		
	
	// navigation
	*mpStream	<<	"<table><tr><td>";
	*mpStream	<<	"<form method=post action="
		"\""
		<<	mpMarketPlace->GetCGIPath(PageAdminSetTopSellerLevel) 
		<<	"eBayISAPI.dll"
		"\""
		">"
		"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"UserSearch\">"
		"\n"
		"<input type=\"submit\" value=\"Return to user account\"\n>"
		"<input type=hidden name=string value="
		<<	(mpUser)->GetUserId()
		<<	">\n"
		"<input type=hidden name=how value="
		<<	5
		<<	">\n</form>";
	
	*mpStream	<<	"</td><td>";
	
	*mpStream	<<	"<form method=post action="
		"\""
		<<	mpMarketPlace->GetCGIPath(PageAdminShowTopSellerStatus)
		<<	"eBayISAPI.dll"
		"\""
		">"
		"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ShowTopSellerStatus\">"
		"\n"
		"<input type=\"submit\" value=\"Return to TopSeller status page \">"
		"</p>"
		"<input type=hidden name=userid value="
		<<	(mpUser)->GetUserId()
		<<	">\n"
		"</form>\n";
	
	*mpStream	<<	"</td></tr></table>";
	
	// Clean
	
	*mpStream <<	"<br>"
		<<	mpMarketPlace->GetFooter();
	
	CleanUp();
	
	return;
}

void clseBayApp::SetMultipleTopSellers(CEBayISAPIExtension *pServer, 
									   char *text,
									   int level,
									   eBayISAPIAuthEnum authLevel)
{
	vector<char *> vUsers;
	vector<char *>::iterator iUser;
	clsUser *pUser;

	SetUp();

	EmitHeader("Multiple Top Seller Modification");
		
	// Let's see if we're allowed to do this
	if(!CheckAuthorization(authLevel)) {
		
		CleanUp();
	
		return;
	}

	// look for any text at all
	if(strcmp(text, "default")) {	
		// parse text into a vector
		{
			char seps[]   = "\x0D\x0A";
			char *token;
			
			/* Establish string and get the first token: */
			token = strtok( text, seps );   
			
			// put in the array
			if(token)
				vUsers.push_back(token);
			
			while( token != NULL )   {
				/* While there are tokens in "string" */      
				/* Get next token: */      
				token = strtok( NULL, seps );   
				
				// put in the array
				if(token)
					vUsers.push_back(token);
			}
		}
		
		// now check the users
		for (iUser = vUsers.begin(); iUser != vUsers.end(); iUser++) {
			
			pUser = mpUsers->GetAndCheckUser(*iUser, NULL);
			if (!pUser)
			{
				*mpStream	<<	"<h3>Aborting Top Seller batch load</h3>"
					<<	"Found an unknown user: <b>"
					<<	*iUser
					<<	"</b>.<br><br>Please hit the back button on your browser, check your entries and resubmit the Top Seller batch.<br>";
				
				goto leave;
			}
			
			delete pUser;
		}
		
		// ok, now that we're gotten this far really update them
		for (iUser = vUsers.begin(); iUser != vUsers.end(); iUser++) {
			pUser = mpUsers->GetAndCheckUser(*iUser, NULL);
		
			if (pUser) {
				// try to reset their level & update them
				pUser->SetTopSellerLevel((TopSellerLevelEnum)level);
				
				pUser->UpdateUser();

				delete pUser;
			}
		}

		// loop through them
		if (!vUsers.empty()) {
			*mpStream	<<	"The following users have been set to level <b>";
			*mpStream	<<	level;
			*mpStream	<<	"</b>:<br>";
			
			*mpStream	<<	"<table>";
			
			for (iUser = vUsers.begin(); iUser != vUsers.end(); iUser++) {
				
				*mpStream	<<	"<tr><td>";
				
				*mpStream	<<	"<A href=\""
					<<	mpMarketPlace->GetCGIPath(PageAdminSetTopSellerLevelMultiple)
					<<	"eBayISAPI.dll?UserSearch"
					<<	"&string="
					<<	*iUser
					<<	"&how=5"
					<<	"\">"
					<<	*iUser
					<<	"</a>";
				
				*mpStream	<<	"</td></tr>";
				
			}
			*mpStream	<<	"</table>";	
		}


	} else {	// check for any text
		*mpStream	<<	"<h3>Aborting Top Seller batch load</h3>"
			<<	"No users were entered.	Please hit the back button on your browser, check your entries and resubmit the Top Seller batch.<br>";		
		goto leave;
	}
	
leave:
	// return to admin batch
	*mpStream	<<	"<br><A href=\""
		<<	mpMarketPlace->GetHTMLRelativePath() 
		<<	"adminTopSeller.html"
		<<	"\">"
		<<	"Return to batch/Top Seller admin page."
		<<	"</a><br>";
	
	// Clean
	
	// empty vector
	if (!vUsers.empty())
		vUsers.clear();
	
	*mpStream <<	"<br>"
		<<	mpMarketPlace->GetFooter();
	
	CleanUp();
	
	return;
}


void clseBayApp::ShowTopSellers(CEBayISAPIExtension *pServer, 
									int level,
									 eBayISAPIAuthEnum authLevel)
{
	vector<int> pvIds;
	vector<int>::iterator iId;
	clsUser *pUser;
		
	SetUp();

	EmitHeader("ShowTopSellers");
		
	// Let's see if we're allowed to do this
	if(!CheckAuthorization(authLevel)) {

		CleanUp();
		
		return;
	}
		
	
	mpUsers->GetTopSellers(level, &pvIds);
	
	
	// loop through them
	if (!pvIds.empty()) {
		switch (level) {
			case -1:
				*mpStream	<<	"All current";
				break;
			case 1:
				*mpStream	<<	"Level 1";
				break;
			case 2:
				*mpStream	<<	"Level 2";
				break;
			case 3:
				*mpStream	<<	"Level 3";
				break;
			case 4:
				*mpStream	<<	"Level 4";
				break;
			case 5:
				*mpStream	<<	"Level 5";
				break;
		}
		
		*mpStream	<<	"  Top Sellers:";
		*mpStream	<<	"<br><br>";
		*mpStream	<<	"<table width=\"100%\" cellpadding=4 border=0 cellspacing=0>"
						"<TH WIDTH=\"30%\" ALIGN=\"LEFT\" VALIGN=\"TOP\">UserId</TH> \n"
						"<TH WIDTH=\"50%\" ALIGN=\"LEFT\" VALIGN=\"TOP\">email</TH> \n"
						"<TH WIDTH=\"20%\" ALIGN=\"LEFT\" VALIGN=\"TOP\">Level</TH> \n";
		
		for (iId = pvIds.begin(); iId != pvIds.end(); iId++) {
			
			pUser = mpUsers->GetUser(*iId);
				
			*mpStream	<<	"<tr><td width=30%>";
			
			*mpStream	<<	"<A href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminShowTopSellers)
				<<	"eBayISAPI.dll?UserSearch"
				<<	"&string="
				<<	pUser->GetUserId()
				<<	"&how=5"
				<<	"\">"
				<<	pUser->GetUserId()
				<<	"</a>";
			
			*mpStream	<<	"</td><td width=50%>"
						<<	pUser->GetEmail()
						<<	"</td><td width=20%>"
						<<	pUser->GetTopSellerLevel();

			*mpStream	<<	"</td></tr>";

			delete pUser;
			
		}
		*mpStream	<<	"</table>";	
	}
	
	
	// Clean
	
	if(!pvIds.empty()) 
		pvIds.clear();

	*mpStream <<	"<br>"
		<<	mpMarketPlace->GetFooter();
	
	CleanUp();
	
	return;
}