/*	$Id: clseBayAppUserSearch.cpp,v 1.10.22.3 1999/06/11 04:57:55 poon Exp $	*/
//
//	File:	clseBayAppUserSearch.cc
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	Does a wildcard search of all users, and emits them
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 06/29/98 inna		- add account id to the output
//				- 08/28/98 josh     - lowercased the thing for single-user searches.

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )

#include "ebihdr.h"
#include "clsUserVerificationServices.h"

void clseBayApp::UserSearch(CEBayISAPIExtension* pCtxt,
							 char *pString,
							 int how,
							 eBayISAPIAuthEnum authLevel)
{
	SetUp();
	EmitHeader("Administrative User Search");
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}



	// The vector of users
	vector<clsUser *>			vUsers;

	// Itcherator
	vector<clsUser *>::iterator	vI;

	// AccountId and User (for account searches)
	int						awAccountId;
	int						eBayAccountId;
	clsUser					*pUser	= NULL;

	// Account
	clsAccount				*pAccount;

	bool					colorSwitch	= false;
	char					*pColor;

	// Interesting User things
	int						listCount;
	int						bidCount;

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Ok, let's get them
	switch ((UserSearchTypeEnum)how)
	{
		case UserSearchByUserIdSubstring:
			mpUsers->GetUsersBySubstring((UserSearchTypeEnum)how,
										 pString, &vUsers);
			break;

		case UserSearchByNameSubstring:
			mpUsers->GetUsersBySubstring((UserSearchTypeEnum)how,
										 pString, &vUsers);
			break;

		case UserSearchByAddressSubstring:
			mpUsers->GetUsersBySubstring((UserSearchTypeEnum)how,
										 pString, &vUsers);
			break;

		case UserSearchByAllSubstring:
			mpUsers->GetUsersBySubstring(UserSearchByUserIdSubstring, 
										 pString, &vUsers);
			mpUsers->GetUsersBySubstring(UserSearchByNameSubstring,
										 pString, &vUsers);
			mpUsers->GetUsersBySubstring(UserSearchByAddressSubstring,
										 pString, &vUsers);
			break;

		case UserSearchByUserIdExact:
			{
				// Lowercase the thing, and strip out the leading spaces. There's no
				// reason for exact searches to be case-specific. We make a copy
				// rather than messing with it in place, since the owner might
				// not like their string changed. It really should be const...
				char *tbuf = new char[strlen(pString) + 1];
				strcpy(tbuf, pString);
				strlwr(tbuf);
				char *p = tbuf;
				while(isspace(*p))
					++p;

				mpUsers->GetUsersBySubstring(UserSearchByUserIdExact,
										 p, &vUsers);
				delete[] tbuf;
			}
			break;
        case UserSearchByCitySubstring:
			mpUsers->GetUsersBySubstring((UserSearchTypeEnum)how,
										 pString, &vUsers);
			break;
        case UserSearchByStateSubstring:
			mpUsers->GetUsersBySubstring((UserSearchTypeEnum)how,
										 pString, &vUsers);
		//
		// Account searches are handled differently
		//
		case UserSearchByAccountId:

			// First, try an AW account
			awAccountId	= atoi(pString);

			if (awAccountId != 0)
			{
				mpDatabase->GeteBayAccountCrossReference(awAccountId,
													     &eBayAccountId);
			}
			else
			{
				// If not, try an eBay account
				if (*pString == 'e' ||
					*pString == 'E')
				{
					eBayAccountId	= atoi(pString + 1);
				}
			}

			if (eBayAccountId != 0)
				pUser	= mpUsers->GetUser(eBayAccountId);

			if (pUser)
				vUsers.push_back(pUser);
			
			break;
		default:
			*mpStream <<	"<h2>Internal error!</h2>"
							"The search type "
					  <<	(int)how
					  <<	" for was unrecognized. Please report this "
							"problem to Product Development!"
							"<p>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
	}


	// Let's see if there WERE any
	if (vUsers.size() < 1)
	{
		*mpStream <<	"<h2>No Users!</h2>"
						"Sorry, there were no users with the string "
						"\'"
				  <<	pString
				  <<	"\'"
						" in their ";
		switch (how)
		{
			case UserSearchByUserIdSubstring:
				*mpStream <<	"userid";
				break;

			case UserSearchByNameSubstring:
				*mpStream <<	"name";
				break;

			case UserSearchByAddressSubstring:
				*mpStream <<	"address";
				break;
            case UserSearchByAllSubstring:
				*mpStream <<	"userid"
                          <<	"name"
				          <<	"address";
				break;

			case UserSearchByUserIdExact:
				*mpStream <<	"userid/email";
				break;

            case UserSearchByCitySubstring:
				*mpStream <<	"city";
				break;
			case UserSearchByStateSubstring:
				*mpStream <<	"state";
				break;

			case UserSearchByAccountId:
				*mpStream << "account id";
				break;

			default:
				*mpStream <<	"<h2>Internal error!</h2>"
								"The search type "
						  <<	(int)how
						  <<	" for was unrecognized. Please report this "
								"problem to Product Development!"
								"<p>"
						  <<	mpMarketPlace->GetFooter();

				CleanUp();
				return;
		}

		*mpStream <<	". "
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}


	// Ok, let's start the emissions. We'll we using tables ;-)
	*mpStream <<	"<p>"
					"<H2>User search returned "
			  <<	vUsers.size();

	if (vUsers.size() > 1)
		*mpStream <<	" matches";
	else
		*mpStream <<	" match";
	
	*mpStream <<	"!</h2>"
					"<p>";

	*mpStream <<	"<TABLE BORDER=1 BGCOLOR=\"#FFFFFF\" WIDTH=100%>"
					"<TR>"
					"<TH WIDTH=40% BGCOLOR=\"#FFFFCC\">UserId / Email</TH>"
					"<TH WIDTH=7% BGCOLOR=\"#FFFFCC\">Account</TH>"
					"<TH WIDTH=7% BGCOLOR=\"#FFFFCC\">Auctions</TH>"
					"<TH WIDTH=5% BGCOLOR=\"#FFFFCC\">Bids</TH>"
					"<TH WIDTH=5% BGCOLOR=\"#FFFFCC\">All</TH>"
					"<TH WIDTH=10% BGCOLOR=\"#FFFFCC\">Alias</TH>"
					"<TH WIDTH=18% BGCOLOR=\"#FFFFCC\">Actions</TH>"
					"<TH WIDTH=5% BGCOLOR=\"#FFFFCC\">Top Seller</TH>"
					"</TR>"
					"\n";

	for (vI = vUsers.begin();
		 vI	!= vUsers.end();
		 vI++)
	{
		// Get Statistics
		listCount	= (*vI)->GetListedItemsCount();
		bidCount	= (*vI)->GetBidItemsCount();


		if (colorSwitch)
		{
			pColor		= "#CCCCCC";
			colorSwitch	= false;
		}
		else
		{
			pColor		= "#FFFFFF";
			colorSwitch	= true;
		}

		*mpStream <<	"<TR>"
						"<TD WIDTH=40% "
						"BGCOLOR=\"" << pColor << "\""
						">";

		if ((*vI)->HasANote())
		{
			*mpStream <<	"<A HREF=\""
					  <<	mpMarketPlace->GetAdminPath()
					  <<	"eBayISAPI.dll"
							"?AdminShowNoteShow"
							"&userid="
							"&pass="
							"&aboutfilter="
					  <<	(*vI)->GetUserId()
					  <<	"\">"
							"<img align=\"top\" border=0 alt=\"eNote\" "
							"height=13 "
							"width=18 "
							"src=\""
					  <<	mpMarketPlace->GetHTMLPath()
					  <<	"pics/has-enote.gif"
							"\""
							">"
							"</A>";
		}

		*mpStream <<	"<B>"
				  <<	(*vI)->GetUserId()
				  <<	" / "
				  <<	(*vI)->GetEmail()
				  <<	"</B>"
						" ";

		switch ((*vI)->GetUserState())
		{
			case	UserSuspended:
				*mpStream <<	"(<i>suspended</i>)";
				break;
			case	UserConfirmed:
				*mpStream <<	"(confirmed)";
				break;
			case	UserUnconfirmed:
				*mpStream <<	"(unconfirmed)";
				break;
			case	UserCCVerify:
				*mpStream <<	"(unconfirmed; need CC)";
				break;
			case	UserGhost:
				*mpStream <<	"(ghost)";
				break;
			case	UserUnknown:
				*mpStream <<	"(unknown - <b>report to Engineering!</b>)";
				break;
			default:
				*mpStream <<	"(default - <b>report to Engineering!</b>)";
				break;
		}

		if ((*vI)->HasDetail())
		{
			if ((*vI)->HasCreditCardOnFile())
				*mpStream <<	" <b>CC</b>";

			if ((*vI)->HasGoodCredit())
				*mpStream <<	" <b>Blessed</b>";
		}

		// inna add Account Number here:	
		if ((*vI)->HasDetail())
		{
			*mpStream <<	" E"
					  <<	(*vI)->GetId();
		}
		// end inna

		*mpStream <<	"</TD>"
						"<TD WIDTH=7% "
						"BGCOLOR=\"" << pColor << "\""
						">"
						"<CENTER>";

		pAccount	= NULL;
		pAccount	= (*vI)->GetAccount();

		if (!pAccount->Exists())
		{
			*mpStream <<	"None";
		}
		else
		{
			*mpStream <<	"<A HREF=\""
					  <<	mpMarketPlace->GetCGIPath(PageViewAccount)
					  <<	"eBayISAPI.dll?ViewAccount"
							"&userid="
					  <<	(*vI)->GetUserId()
					  <<	"&pass="
					  <<	mpMarketPlace->GetSpecialPassword()
					  <<	"&entire=0&sinceLastInvoice=0&daysback=30"
					  <<	"\""
							">"
					  <<	pAccount->GetBalance()
					  <<	"</A>";
		}

		delete pAccount;

		*mpStream <<	"</CENTER>"
						"</TD>"
						"<TD WIDTH=7% "
						"BGCOLOR=\"" << pColor << "\""
						">"
						"<CENTER>"
						"<A HREF=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewListedItems)
				  <<	"eBayISAPI.dll?ViewListedItems"
						"&userid="
				  <<	(*vI)->GetUserId()
				  <<	"\""
						">"
						"auctions"
						"</A>"
						"</CENTER>"
						"</TD>"
						"<TD WIDTH=5% "
						"BGCOLOR=\"" << pColor << "\""
						">"
						"<CENTER>"
						"<A HREF=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewBidItems)
				  <<	"eBayISAPI.dll?ViewBidItems"
						"&userid="
				  <<	(*vI)->GetUserId()
				  <<	"&completed=0&all=1"
				  <<	"\""
						">"
						"bids"
						"</A>"
						"</CENTER>"
						"</TD>"
						"<TD WIDTH=5% "
						"BGCOLOR=\"" << pColor << "\""
						">"
						"<CENTER>"
						"<A HREF=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewAllItems)
				  <<	"eBayISAPI.dll?ViewAllItems"
						"&userid="
				  <<	(*vI)->GetUserId()
				  <<	"\""
						">"
						"all"
						"</A>"
						"</CENTER>"
						"</TD "
						"BGCOLOR=\"" << pColor << "\""
						">"
						"<TD WIDTH=10% "
						"BGCOLOR=\"" << pColor << "\""
						">"
						"<CENTER>"
						"<A HREF=\""
				  <<	mpMarketPlace->GetCGIPath(PageViewAliasHistory)
				  <<	"eBayISAPI.dll?ViewAliasHistory"
						"&userid="
				  <<	(*vI)->GetUserId()
				  <<	"&pass="
				  <<	mpMarketPlace->GetSpecialPassword()
				  <<	"\""
						">"
						"History"
						"</A>"
						"</CENTER>"
						"</TD "
						"BGCOLOR=\"" << pColor << "\""
						">"
						"<TD WIDTH=23% "
						"BGCOLOR=\"" << pColor << "\""
						">";

		//
		// Appropriate Actions
		//
		if ((*vI)->GetUserState() != UserGhost)
		{
			*mpStream <<	"<A HREF="
							"\""
					  <<	mpMarketPlace->GetAdminPath()
					  <<	"eBayISAPI.dll?";
			switch ((*vI)->GetUserState())
			{
				case	UserSuspended:
					*mpStream <<	"AdminReinstateUserShow"
							  <<	"&userid="
							  <<	"&pass="
							  <<	"&target="
							  <<	(*vI)->GetUserId()
							  <<	"\""
							  <<	">"
							  <<	"Reinstate"
							  <<	"</A>";
					break;
				case	UserConfirmed:
					*mpStream <<	"AdminSuspendUserShow"
							  <<	"&userid="
							  <<	"&pass="
							  <<	"&target="
							  <<	(*vI)->GetUserId()
							  <<	"\""
							  <<	">"
							  <<	"Suspend"
							  <<	"</A>";
					break;
				case	UserUnconfirmed:
				case	UserCCVerify:
					*mpStream <<	"ConfirmUser"
							  <<	"&userid="
							  <<	(*vI)->GetUserId()
							  <<	"\""
							  <<	">"
							  <<	"Confirm"
							  <<	"</A>";
					break;
				default:
					*mpStream <<	"</A>";		// added AlexP 06/10/99
					break;
			}

			//
			// If they're Confirmed, or Suspended, End Auction link
			//
			if (listCount > 0)
			{
				*mpStream <<	"     "
						  <<	"<A HREF="
								"\""
						  <<	mpMarketPlace->GetAdminPath()
						  <<	"eBayISAPI.dll?"
						  <<	"AdminEndAllAuctionsShow"
						  <<	"&userid="
						  <<	"&pass="
						  <<	"&targetuser="
						  <<	(*vI)->GetUserId()
						  <<	"&suspended=1"
						  <<	"&creditfees=1"
						  <<	"\""
								">"
								"<b>U</b>n<b>A</b>uction"
								"</A>";
			}

			if (bidCount > 0)
			{
				*mpStream <<	"     "
						  <<	"<A HREF="
								"\""
						  <<	mpMarketPlace->GetAdminPath()
						  <<	"eBayISAPI.dll?"
						  <<	"RetractAllBids"
						  <<	"&userid="
						  <<	(*vI)->GetUserId()
						  <<	"\""
								">"
								"<b>U</b>n<b>B</b>id"
								"</A>";
			}

			if ((*vI)->HasABlockedItem())
			{
				*mpStream <<	"     "
						  <<	"<A HREF="
								"\""
						  <<	mpMarketPlace->GetAdminPath()
						  <<	"eBayISAPI.dll?"
						  <<	"AdminUnflagUserShow"
						  <<	"&userid="
						  <<	(*vI)->GetUserId()
						  <<	"\""
								">"
								"<b>U</b>n<b>F</b>lag"
								"</A>";
			}
		}
		else
		{
			*mpStream <<	"<i>"
							"Not available for ghosts"
							"</i>";
		}


	// end of actions
	*mpStream <<	"</TD>";

	*mpStream <<	"<TD><center>";
		// top seller
	if((*vI)->IsTopSeller()) {
		// show level
		*mpStream <<	"<A HREF="
			<<	"\""
			<<	mpMarketPlace->GetAdminPath()
			<<	"eBayISAPI.dll?"
			<<	"ShowTopSellerStatus"
			<<	"&userid="
			<<	(*vI)->GetUserId()
			<<	"\""
			<<	">Lvl "
			<<	(*vI)->GetTopSellerLevel()
			<<	"</A>";
	} else {
		*mpStream <<	"<A HREF="
			"\""
			<<	mpMarketPlace->GetAdminPath()
			<<	"eBayISAPI.dll?"
			<<	"ShowTopSellerStatus"
			<<	"&userid="
			<<	(*vI)->GetUserId()
			<<	"\""
			<<	">"
			<<	"No"
			<<	"</A>";
		}
		
			
			
		
		
		*mpStream <<	"</center></TD>"
						"</TR>\n";


		// User Info
		*mpStream <<	"<TR>"
						"<TD COLSPAN=8 "
						"BGCOLOR=\"" << pColor << "\""
						">";

		if ((*vI)->GetUserState() != UserGhost)
		{
			if ((*vI)->HasDetail())
			{
				*mpStream 	  <<	(*vI)->GetName()
							  <<	", "
							  <<	(*vI)->GetAddress()
							  <<	", "
							  <<	(*vI)->GetCity()
							  <<	", "
							  <<	(*vI)->GetState()
							  <<	", "
							  <<	(*vI)->GetCountry()
							  <<	", "
							  <<	(*vI)->GetZip()
							  <<	"<br>";

				*mpStream <<	"(Primary) "
						  <<	(((*vI)->GetDayPhone()) ? (*vI)->GetDayPhone() : "<font color=red>none</font>");

				if ((*vI)->GetNightPhone())
				{
					*mpStream <<	", "
									"(Secondary) "
							  <<	(*vI)->GetNightPhone();
				}

				if ((*vI)->GetFaxPhone())
				{
					*mpStream <<	", "
									"(Fax) "
							  <<	(*vI)->GetFaxPhone();
				}

				if ((*vI)->GetHost())
				{
					*mpStream <<	", "
									"(Host) "
							  <<	(*vI)->GetHost();
				}

			}
			else
			{
				*mpStream << "<b>Error!</b> No User detail! "
							 "<font color=red>Report to Engineering</font>";
			}
		}
		else
		{
			*mpStream	  <<	"<i>"
								"no information for ghost user"
								"</i>";
		}
		
		*mpStream <<	"</TD>"
						"</TR>\n";

		// UV rating and detail
		*mpStream <<	"<TR><TD colspan=8>\n";
		*mpStream <<	"UV Rating = <b>"
				  << (*vI)->GetUVRating()
				  << ((*vI)->GetUVRating()==clsUserVerificationServices::UV_RATING_NOT_CALCULATED ? " (not yet calculated)" : "")
				  << ((*vI)->GetUVRating()==clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE ? " (not available for country)" : "")
				  << "</b>"
				  <<	"<br>";

		mpUserVerificationServices->TranslateUVDetailToText((*vI)->GetUVDetail(), mpStream);

		*mpStream <<	"</TD></TR>\n";

		*mpStream <<	"</TABLE>\n";

		*mpStream <<	"<p><TABLE BORDER=1 BGCOLOR=\"" << pColor << "\" WIDTH=100%>\n"
				  <<	flush;
	}

	*mpStream	<<	"</TABLE>"
					"<p>"
					"<p>"
			    <<	mpMarketPlace->GetFooter();


	// Clean up the list
	for (vI = vUsers.begin();
	     vI != vUsers.end();
	     vI++)
	{
		// Delete the User
		delete	(*vI);
	}

	vUsers.erase(vUsers.begin(), vUsers.end());
			
	// Clean

	CleanUp();

	return;
}
