/*	$Id: clseBayAppAdminAnnouncementUpdate.cpp,v 1.6.376.1 1999/08/01 02:51:39 barry Exp $	*/
//
//	File:		clseBayAppAdminAnnouncementUpdate.cc
//
//	Class:		clseBayAppAdminAnnouncementUpdate
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function: does the actual add of a new announcement
//
//
//	Modifications:
//				- 11/04/97 tini	- Created
//

#include "ebihdr.h"
#define CHECKED(x)	(!strcmp(x,"on"))

// CheckAnnouncementData
//
// Common routine to validate category data.
//
bool clseBayApp::CheckAnnouncementData(
								char *pEmail,
								char *pPass,
								char *pId,
								char *pLoc,
								char *pCode,
								char *pDesc,
								char *pSiteId,
								char *pPartnerId
							  )
{
	// check email and pass and user can admin stuff here

	bool		error	= true;

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pEmail, pPass, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{   *mpStream <<    "Not a valid user or password.";

		return false;
	}

	if (FIELD_OMITTED(pSiteId))
	{
		*mpStream <<	"<h2>"
						"Site ID missing"
						"</h2>\n"
						"You must provide a site id. ";
		return false;
	}

	if (FIELD_OMITTED(pPartnerId))
	{
		*mpStream <<	"<h2>"
						"Partner ID missing"
						"</h2>\n"
						"You must provide a partner id. ";
		return false;
	}

	if (FIELD_OMITTED(pId))
	{
		*mpStream <<	"<h2>"
						"Enter a unique number for the announcement id and description"
						"</h2>\n"
						"You must provide a unique number for id of the announcement. "
						"Please go back and use the next consecutive number in the sequence.<p>\n ";

		return false;
	}

	if (FIELD_OMITTED(pLoc))
	{
		*mpStream <<	"<h2>"
						"Announcement Header or Footer?"
						"</h2>\n"
						"You must choose to select header or footer as the location of the announcement. "
						"Please go back and try again.<p>\n ";

		return false;
	}


	if (FIELD_OMITTED(pCode))
	{
		*mpStream <<	"<h2>"
						"Announcement Description"
						"</h2>\n"
						"You must enter a unique description of where the announcement will be used. "
						"e.g. End of Auction, Daily Status. "
						"Note: do not add new announcements unless you know where it will be used."
						"Please go back and try again.<p>\n ";

		return false;
	}

	// Let's see if the user can administer categories
	if (!mpUser->HasAdmin(Announcement))
	{
		*mpStream <<	"<p>"
						"You do not have Announcement Administration privileges.";

		return false;
	}

	return error;	
}


void clseBayApp::UpdateAnnouncement(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pId,
						  char *pLoc,
						  eBayISAPIAuthEnum authLevel,
						  char *pSiteId,
						  char *pPartnerId)
{
	bool		error = false;
	int			id;
	int			loc;
	int			siteid;
	int			partnerid;

	clsAnnouncement *pAnnounce;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a title here
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Update Announcement "
			  <<	"</TITLE></head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Spacer
	*mpStream <<	"<br>";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
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
					"eBay Announcement Update"
					"</strong></font></td>\n"
					"</tr>\n"
					"</table></center>\n";
	// Spacer
	*mpStream <<	"<br>";

	// convert and check id, loc
	id = atoi(pId);
	loc = atoi(pLoc);
	siteid = atoi(pSiteId);
	partnerid = atoi(pPartnerId);

	pAnnounce = mpAnnouncements->GetAnnouncement((AnnouncementEnum)id, (AnnounceLocEnum)loc, partnerid, siteid);

	AnnouncementEntry(pAnnounce, siteid, partnerid);

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;
		

	CleanUp();
}

//
// this function does the actual add category action to the database
//
void clseBayApp::AddAnnouncement(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pId,
						  char *pLoc,
						  char *pCode,
						  char *pDesc,
						  eBayISAPIAuthEnum authLevel,
						  char *pSiteId,
						  char *pPartnerId
							)
{

//	clsCategory *pCategory;
	bool		ok	= true;
	int			siteid;
	int			partnerid;
	int			id;
	int			loc;

	clsAnnouncement *pAnnounce;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Announcement Confirmation"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// start the page blurb
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Announcement Administration</h2>"
			  <<	"<br>\n";
	
	// Let's revalidate the data
	ok	= CheckAnnouncementData(pUserId,
								pPass,
								pId,
								pLoc,
								pCode,
								pDesc,
								pSiteId,
								pPartnerId
				);

	// Let's see if we need to leave now
	if (!ok)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// convert and check id, loc
	siteid = atoi(pSiteId);
	partnerid = atoi(pPartnerId);
	id = atoi(pId);
	loc = atoi(pLoc);

	if (FIELD_OMITTED(pDesc))
		pDesc = "";


	pAnnounce = mpAnnouncements->GetAnnouncement((AnnouncementEnum)id, 
				(AnnounceLocEnum)loc, partnerid, siteid);

	// update or add the announcement
	// done by update announcement; if its not updated, it adds.
	if (pAnnounce == 0)
	{
		pAnnounce = new clsAnnouncement(mpMarketPlace->GetId(),
				(AnnouncementEnum)id, (AnnounceLocEnum)loc, 
				time(0), pCode, pDesc, partnerid, siteid);
	}

	// update description to new description
	pAnnounce->SetDesc(pDesc);
	mpAnnouncements->UpdateAnnouncement(pAnnounce);
//	}
//	else
//		mpAnnouncements->AddAnnouncement(
//								(AnnouncementEnum)id, 
//								(AnnounceLocEnum)loc,
//								pCode, 
//								pDesc);

	 // blurbs
 	*mpStream <<	"<h3>Announcement Added/Modified.</h3>";

	*mpStream	  <<	"<p>"
				  <<	"<A href=\""
				  <<	mpMarketPlace->GetCGIPath(PageAdminAnnouncement)
				  <<	"eBayISAPI.dll?AdminAnnouncement"
				  <<	"\">"
						"Return to Announcement Administration page"
						"</A>"
						"<br>\n";

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	delete pAnnounce;
	CleanUp();

}
