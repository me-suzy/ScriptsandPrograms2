/*	$Id: clseBayAppAdminShillHunter1.cpp,v 1.9 1999/02/21 02:31:51 josh Exp $	*/
//
//	File:		clseBayAppAdminShillHunter1.cpp
//
//	Author:	Josh Gordon
//
//	Function:
//		View the relationships between bidders.
//
//
// We're invoked with either a list of userids, via AdminShillRelationshipsByUsers,
// or with an item number via AdminShillRelationshipsByItem, in which case the users
// are the seller of the item and all the bidders on the item.
//

#include "ebihdr.h"
#include "clsUserRelationships.h"


#define MAXUSERSTRING 1024		// Maximum length of the user string.

#ifndef _MSC_VER
// Define strdup() for platforms that don't support it.
char *strdup(const char *src)
{
	char *tmp = malloc(strlen(src) + 1);
	strcpy(tmp, src);
	return tmp;
}
#endif



const char *ShillToolTitle = "Shill Relationships Tool";


static void userlist_to_vector(const char *userlist, CharPVector &vec)
{
	// Ripple through the list of users, picking them apart
	// and jamming them into the useridVector.
	// We strdup userlist because strtok is non-const.
	char *ouruserlist = strdup(userlist);
	char *p = strtok(ouruserlist, " \t,\r\n");
	while(p)
	{
		vec.push_back(strdup(p));
		p = strtok(NULL, " ,\r\n");
	}
	free(ouruserlist);
}

// Translate a userid list to a clsUser* vector. Stop when an invalid userid
// is provided; callers will compare vUserIds.size() to vUsers.size() to determine
// where the failure was. Return false on failure.
//
bool clseBayApp::UseridListToUserVector(CharPVector vUserIds, UserVector& vUsers)
{
	int usercount = vUserIds.size();

	// Get the user object for each of these. 
	for (int i = 0; i < usercount; i++)
	{
		clsUser *pUser = mpUsers->GetUser(vUserIds[i]);
		if (pUser == NULL)
			return false;
		vUsers.push_back(pUser);
	}
	return true;
}

#if 0
// I'd really like to figure out how to get a hash_map with an vector<int> as key.
struct eqIntVector
{
	bool operator()(const IntVector& v1, const IntVector& v2) const
	{
		return v1 == v2;
	}
};

bool operator<(IntVector v1, IntVector v2)
{
	if (v1.size() < v2.size())
		return true;

	if (v1.size() > v2.size())
		return false;

	for (int i = 0; i < v1.size(); i++)
	{
		if (v1[i] < v2[i])
			return true;
		if (v1[i] > v2[i])
			return false;
	}
	return false;
}

typedef hash_map<IntVector, IntVector, hash<IntVector>, eqIntVector> IntVIntVMap;

IntVIntVMap vMap;
#endif

// BuildShillToolButton simply builds a shill tool button for the list of userids
// passed to it (as well as one more bidder).
void BuildShillToolButton(ostream *mpStream,
						  clsMarketPlace* mpMarketPlace,
						  const char *bidder,
						  CharPVector vUserIds)
{
	*mpStream << "<p><FORM method=post action=\""
		<< mpMarketPlace->GetAdminPath()
		<< "eBayISAPI.dll\"><INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\""
		"VALUE=\"AdminShillRelationshipsByUsers\">\n"
		"<INPUT TYPE=HIDDEN name=userlist rows=10 cols=60 "
		"value =\"";
	if (bidder)
		*mpStream << bidder;

	
	// Now output the users
	CharPVector::iterator iUsers;
	for (iUsers = vUserIds.begin(); iUsers != vUserIds.end(); iUsers++)
		*mpStream << " " << *iUsers;
	
	*mpStream << "\"></TEXTAREA>\n"
		"<INPUT value=on name=details type=hidden>"
		"<INPUT type=submit value=\"Run shill tool on these users\"></FORM>";
}



// AdminShowCommonAuctions shows the auctions that have been bid on by two or more of the users
// listed. 
//
void clseBayApp::AdminShowCommonAuctions(const char * userlist, eBayISAPIAuthEnum authLevel)
{
	CharPVector vUserids;
	CharPVector::iterator iCpv;
	UserVector vUsers;
	int usercount;
	int i;

	SetUp();
	EmitHeader("Show Common Auctions");
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}
	userlist_to_vector(userlist, vUserids);

	usercount = vUserids.size();

	if (usercount < 2)
	{
		
		*mpStream << "<p>Please enter at least two user names\n";
		*mpStream << mpMarketPlace->GetFooter()
			<< flush;
		CleanUp();
		return;
	}

	*mpStream << "<hr><center><h2>Common auctions for";
	for (i = 0; i < usercount; i++)
		*mpStream << " " << vUserids[i];
	*mpStream << "</center></h2><br>\n";


	clsUserRelationships userRelationships(mpMarketPlace, mpUsers, mpStream, gApp);
	
	if (userRelationships.UseridListToUserVector(vUserids, vUsers))
	{
		assert(usercount == vUsers.size());

		// Set up our vector of bidders...
		userRelationships.setBidders(vUsers);
		// Set up the bidder Ids, we'll need them later
		userRelationships.buildBidderIdVector();
		// Build up each of their bid vectors
		userRelationships.buildBiddersBidVectors();


		IntVIntMap vAuctions;
		if (!userRelationships.buildCommonAuctions(vAuctions))
			*mpStream << "<b>There were no common auctions among these users.</b><br>\n";
		else
		{
			CharPIntMap output_stuff;
			IntVIntMap::iterator iAuctions;
			CharPVector strings;
			for (iAuctions = vAuctions.begin(); iAuctions != vAuctions.end(); iAuctions++)
			{
				if ((*iAuctions).second.size() > 1)	// Don't bother with one-bidder auctions
				{

					// Build a char * with the user ids for this thing. 
					char *p = new char[MAXUSERSTRING];
					strings.push_back(p);
					p[0] = '\0';
					

					vector<int>::iterator iI;
					for (iI = (*iAuctions).second.begin(); iI != (*iAuctions).second.end(); iI++)
					{
						if (p[0])
							strcat(p, " ");
						strcat(p, vUserids[*iI]);
					}
					output_stuff[p].push_back((*iAuctions).first);
				}
			}
			CharPIntMap::iterator iCPI;
			for (iCPI = output_stuff.begin(); iCPI != output_stuff.end(); iCPI++)
			{
				*mpStream << (*iCPI).first << " ";
				
				IntVector::iterator iI;
				for (iI = (*iCPI).second.begin(); iI != (*iCPI).second.end(); iI++)
					*mpStream << *iI << " ";

				
				*mpStream << "<br>\n";
				
				
			}
			// I couldn't figure out how to make sure the strings got
			// deleted, so this monstrosity is necessary.
			int n = strings.size();
			for (i = 0; i < n; i++)
				delete strings[i];
		}
				
	}
	else
	{
		int n = vUsers.size();
		*mpStream << "<p>Could not find information for user "
			<< vUserids[n]
			<< ". Please try again.<br>\n";
	}
	
	// Kill vUserids

	for (iCpv = vUserids.begin(); iCpv != vUserids.end(); iCpv++)
		free(*iCpv);

	*mpStream <<  mpMarketPlace->GetFooter()
		<< flush;
	
	CleanUp();


}



// AdminShowBidderSellers simply lists all the sellers this bidder has bid on. It's
// accessed through a link in ViewShillRelationships -- click on a number in 
// the "unique sellers" and you'll get here.

void
clseBayApp::AdminShowBiddersSellers(const char *bidder, eBayISAPIAuthEnum authLevel)
{
	SetUp();
	EmitHeader("Show Bidder's Sellers");
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}
	
	*mpStream << "<hr><center><h1>Sellers bid on by "
		<< bidder
		<< "</h1></center><p>\n";

	CharPVector vUserids;
	UserVector vUsers;

	userlist_to_vector(bidder, vUserids);		// Yeah, I know there's just one. But I'm being pretty.
	
	clsUserRelationships userRelationships(mpMarketPlace, mpUsers, mpStream, gApp);
	if (userRelationships.UseridListToUserVector(vUserids, vUsers))
	{
		userRelationships.setBidders(vUsers);
		userRelationships.buildBidderIdVector();
		userRelationships.buildBiddersBidVectors();

		ItemList &itemBidList = userRelationships.getBidderListVector()[0];
		

		if (itemBidList.empty())
		{
			*mpStream << "User <b>"
				<< bidder
				<< "</b> has no recorded bids.\n";
		}
		else
		{
			CharPVector vUserIds;		// These are the userids of the bid-upon sellers.
			// Grab all the user ids and count their usage.
			IntIntMap sellerMap;
			ItemList::iterator iter;

			// Build a table with this stuffola.
			*mpStream << "<table border>"
				<<	"<tr>"
					"<th>User</th>"
					"<th>Number<br>of<br>bids</th></tr>\n";


			// Count how many times this seller occurs in this bidder's list of
			// auctions.
			for (iter = itemBidList.begin(); iter != itemBidList.end(); iter++)
				sellerMap[(*iter).mpItem->GetSeller()]++;

			// Now riffle through the items on the bid list and list the sellers' names and counts
			IntIntMap::iterator citer;
			for (citer = sellerMap.begin(); citer != sellerMap.end(); citer++)
			{
				clsUserIdWidget userIdWidget(mpMarketPlace, this);
				// Output the UserIdWidget in a cell.
				clsUser *pUser = mpUsers->GetUser((*citer).first);
				// Put the userid in the userid vector.
				char *pId = strdup(pUser->GetUserId());
				vUserIds.push_back(pId);

				userIdWidget.SetUserInfo(pId,
					pUser->GetEmail(),
					pUser->GetUserState(),
					mpMarketPlace->UserIdRecentlyChanged(pUser->GetUserIdLastModified()),
					pUser->GetFeedback()->GetScore());
				userIdWidget.SetShowFeedback(true);
				userIdWidget.SetShowUserStatus(true);
				userIdWidget.SetShowMask(false);
				userIdWidget.SetShowStar(false);
				userIdWidget.SetIncludeEmail(true);

				*mpStream << "<tr><td><pre>";
				userIdWidget.EmitHTML(mpStream);
				*mpStream << "</pre></td>";

				// And the count. 
				*mpStream << "<td><pre>" << (*citer).second << "</pre></td></tr>\n";
				delete pUser;
			}
			*mpStream << "</table>";

			BuildShillToolButton(mpStream, mpMarketPlace, bidder, vUserIds);
			CharPVector::iterator iCpv;	
			for (iCpv = vUserIds.begin(); iCpv != vUserIds.end(); iCpv++)
				free(*iCpv);

		}

	
	}
	else
	{
		int n = vUsers.size();
		*mpStream << "<p>Could not find information for user "
			<< vUserids[n]
			<< ". Please try again.<br>\n";
	}
	// Kill vUserids

	CharPVector::iterator iCpv;	
	for (iCpv = vUserids.begin(); iCpv != vUserids.end(); iCpv++)
		free(*iCpv);

	*mpStream <<  mpMarketPlace->GetFooter()
		<< flush;
	
	CleanUp();
}

// clseBayApp::AdminShillRelationshipsByFeedback
// 
// Given a user name, determine shill relationships by either feedback
// or feedback left. If age is specified, use only feedback of particular
// age; otherwise, limit by count.
void
clseBayApp::AdminShillRelationshipsByFeedback(const char *details,
											  const char *user,
											  const char *left,
											  int count,
											  int age,
											  int limit,
											  eBayISAPIAuthEnum authLevel)
{
	SetUp();
	EmitHeader(ShillToolTitle);
	
	if (CheckAuthorization(authLevel))
	{
		bool bLefting = (strcmp(left, "on") == 0);
		*mpStream << "<p><center><H1>" << ShillToolTitle << " for feedback "
			<< ((bLefting) ? "left by " : "given to ")
			<< "user "
			<< user
			<< "</h1></center>\n";


		clsUserRelationships userRelationships(mpMarketPlace, mpUsers, mpStream, gApp);
		userRelationships.ShillRelationshipsByFeedback(details, user, left, count, age, limit);
	}
	CleanUp();
}


	


// clseBayApp::AdminShillRelationshipsByItem
// Given an item number "item", determine buying/selling/feedback relationships.
// Calls AdminShillRelationshipsKernel() to do the real work.
//
// "details" specifies whether to provide names, addresses, etc.
//
void 
clseBayApp::AdminShillRelationshipsByItem(const char *details,
										  int item,
										  int limit,
										  eBayISAPIAuthEnum authLevel)
{
	SetUp();
	EmitHeader(ShillToolTitle);
	
	if (CheckAuthorization(authLevel))
	{

		// Include the item number in the header for this one.
		*mpStream << "<p><center><H1>" << ShillToolTitle << " for item "
			<< "<a href=\""
			<< mpMarketPlace->GetCGIPath(PageViewItem)
			<< "eBayISAPI.dll?ViewItem&item="
			<< item
			<< "\">"
			<< item
			<< "</a>"
			<< "</h1></center>\n";


		
		clsUserRelationships userRelationships(mpMarketPlace, mpUsers, mpStream, gApp);
		userRelationships.ShillRelationshipsByItem(details, item, limit);
		
		// Put in a little box on the bottom to ask for another prompt.
		*mpStream << "<p><hr><b>Run another item:&nbsp;</b>"
			"<form method=get action=\""
			<< mpMarketPlace->GetAdminPath()
			<< "eBayISAPI.dll\">"
			"<input TYPE=HIDDEN NAME=MfcISAPICommand VALUE=AdminShillRelationshipsByItem>"
			"<input name=item size=12><br>"
			"<input type=checkbox name=details checked type=hidden>Include details"
			"<br><input name=limit size=5 value="<< limit << ">"
			"Time limit (up to the limit stored in the database)"
			"<br><input type=submit value=submit>"
			"<input type=reset value=clear></form>";

		*mpStream << mpMarketPlace->GetFooter() << flush;
	}
	CleanUp();
}

//
// clseBayApp::AdminShillRelationshipsByUsers
// Analyze a list of userids for bidding/selling/feedback relationships.
// Calls AdminShillRelationshipsKernel() to do the real work.
// "details" specifies whether to provide names, addresses, etc.
//
void 
clseBayApp::AdminShillRelationshipsByUsers(const char *details,
										   const char *userlist,
										   int limit,
										   eBayISAPIAuthEnum authLevel)
{
	SetUp();
	EmitHeader(ShillToolTitle);
	
	if (CheckAuthorization(authLevel))
	{
		
		*mpStream << "<p><center><H1>" << ShillToolTitle << "</h1></center>\n";
		
		clsUserRelationships userRelationships(mpMarketPlace, mpUsers, mpStream, gApp);
		userRelationships.ShillRelationshipsByUsers(details, userlist, limit);
		*mpStream << mpMarketPlace->GetFooter()
			<< flush;
	}

	CleanUp();
}

// clseBayApp::AdminShillRelationshipsKernel:: the heart of the 
// shill analyzer system.
//
// For each user supplied, get all of their auctions, and every auction they've 
// bid on; and report on their intersections: in other words:
//		for each seller in list-of-users
//			for each bidder in list-of-users
//				tell us of the overlap between bidders and sellers
//	Also provide some helpful information such as name, address, etc.
//  Also do the same sort of overlap test for feedback.
//
// Input: useridVector is a vector of char *, all userids
//		  vUsers is a vector of clsUser
//			These are guaranteed to be the same length, and are
//			positionally equivalent -- that is, useridVector[N],
//			and vUsers[N] all refer to the same user.
//	      details, if "on", says "display name, address, etc. info"

void 
clseBayApp::AdminShillRelationshipsKernel(CharPVector &useridVector,
										  UserVector &vUsers,
										  const char *details,
										  int limit)
{
	clsUserRelationships userRelationships(mpMarketPlace, mpUsers, mpStream, gApp);
	userRelationships.ShillRelationshipsKernel(useridVector, vUsers, details, limit);
	*mpStream << mpMarketPlace->GetFooter()	<< flush;
	CleanUp();
}
	
void clseBayApp::AdminGetShillCandidates(eBayISAPIAuthEnum authLevel)
{
	SetUp();
	EmitHeader("Shill Candidates");
	
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// First, get the list of hot auctions.
	
	vector<int> vItems;
	time_t endDate = time(0);

	mpItems->GetHotNonDutchItemIds(&vItems, endDate);

	// Just pump out the numbers.
	for (int i = 0; i < vItems.size(); i++)
		*mpStream << vItems[i] << " ";
	*mpStream << "<br>";

	CleanUp();
}

void clseBayApp::AdminShowBiddersRetractions(eBayISAPIAuthEnum authLevel, 
											 int id,
											 int limit)
{
	SetUp();
	EmitHeader("Show Bidder's Retractions");
	
	if (CheckAuthorization(authLevel))
	{
		
		*mpStream << "<p><center><H1>Bidder Retractions</h1></center>\n";
		clsUserRelationships userRelationships(mpMarketPlace, mpUsers, mpStream, gApp);
		
		clsUser *pUser = mpUsers->GetUser(id);
		if (pUser == NULL)
		{
			*mpStream << "<p>Could not find information for user number"
				<< id
				<< ". Please try again.<br>\n";
		}
		else
		{
			userRelationships.ShowBiddersRetractions(id, pUser->GetUserId(), limit);
			delete pUser;
		}
		*mpStream << mpMarketPlace->GetFooter()
			<< flush;

	}
	
	CleanUp();
}

