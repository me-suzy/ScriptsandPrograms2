/*	$Id: clseBayAppiEscrow.cpp,v 1.2.166.2.86.1 1999/08/01 03:01:42 barry Exp $	*/

//	File:		clseBayAppiEscrow.cpp
//
//	Class:		clseBayApp
//
//	Author:		inna markov
//
//	Function:
//
//
//	Modifications:
//				- 10/23/98 inna	- Created
//				- 02/20/99 sam  - Changed the look completely to conform to MRD.
//				- 03/01/99 sam  - Added check for Private Auctions
//				- 07/19/99 nsacco - fixed title and error messages.
//
#include "ebihdr.h"
#include "clseBayItemDetailWidget.h"


static const char *iEscrowEmailNotify = 
"Dear %s,\n\n"
"eBay User \"%s\" is interested in using i-Escrow for the following transaction:\n\n"
"auction #: %d\n"
"Title: %s\n"
"Final Value: %.2f\n\n"
"An escrow service helps to safely facilitate the transfer of merchandise "
"and money between the buyer and seller. i-Escrow provides this service to "
"eBay users for a small fee.  Generally, the buyer pays the escrow fees for "
"a transaction, but that can be negotiated between the buyer and seller.\n\n"
"If you need to learn more about how the escrow process works, "
"go to:\n"
" %s\n"
"or, read the frequently asked questions at:\n"
"http://www.iescrow.com/ebay/genfaq.html"
"\n\n"
"You can view the status of this transaction and take action on it by "
"clicking on the link below or copying it to your web browser: \n"
"%s"
"\n\n\n"
"If you do not wish to use escrow for this transaction, you should\n"
"inform eBay User: %s (%s) of your decision.\n\n"
"Please email support@iescrow.com with any questions";


void clseBayApp::IEscrowLogin(CEBayISAPIExtension *pServer,
							  char *pItemNo, char *ptype,
							  int	bidderno)
{
	bool	isGeneric=false;
	int		item=0;
	bool	error=false;

	SetUp();

	//let's get header first
	*mpStream	<<	mpMarketPlace->GetHeader();

	// Let's try and get the item, this creates mpItem
	if (pItemNo)
		item	= atoi(pItemNo);
	else
		error = true;

	if (!error && item == 0)
		isGeneric = true;
	else 
	{
		if (!error)
		{
			mpItem	= mpItems->GetItem(item, true);
			if (mpItem==NULL) // error getting item
				error = true;
		}
	}

	if (error)
	{
		*mpStream <<	"<p>"
						"<H2>"
						"Error Getting Auction Item."
						"</H2>"
						"<p>";
		*mpStream <<	mpMarketPlace->GetHeader();
		CleanUp();
		return;
	}

	// Informational Text Header

	*mpStream	<<	"<TABLE BORDER=\"0\" CELLPADDING=\"5\" CELLSPACING=\"0\" "
				<<	"WIDTH=\"580\">\n"
				<<	"<TR>"	
				<<  "<TD VALIGN=TOP> <FONT SIZE=\"5\"><B>Escrow Services</B></FONT>"
				<<	"<FONT SIZE=\"3\" color=\"green\"><b> Step 1</b></FONT><BR>"
				<<	"<B><font color=\"#FF0000\">Login</font>"
				<<	"<P></P>"
				<<  "</TD>"
				<<	"</TR></TABLE>";


	// Check for initial, accept or nothing
	if (strcmpi(ptype, "initial") ==0)
		*mpStream	<<	"Thanks for your interest in using escrow services. To begin "
					<<	"the process, login with your User ID and password and follow "
					<<	"the instructions. To learn more, please read below."
					<<	"<p><p>";
	else if (strcmpi(ptype, "accept") ==0)
		*mpStream	<<	"The other user for auction #<b>"
					<<	pItemNo
					<<	"</b> is interested in using escrow and initiated the escrow "
					<<	"process. To accept, login with your User Id and password and "
					<<	"follow the instructions. To learn more, please read below."
					<<	"<p><p>";

	*mpStream	<<	"In any transaction, you want to be certain that you get what "
				<<	"was agreed upon. <b>i-Escrow</b>, a third party that holds payment "
				<<	"from the buyer in trust until the seller sends the merchandise "
				<<	"to the buyer, can help ensure a safe and pleasant transaction "
				<<	"for both the buyer and seller. Once the buyer accepts the "
				<<	"merchandise, i-Escrow forwards the payment to the seller. "
				<<	"i-Escrow charges a fee that is 5% of the Transaction price with a "
				<<	"<b>$5</b> minimum. The <b>buyer</b> typically pays for the i-Escrow "
				<<	"fees but that could be part of the negotiation between the buyer and "
				<<	"seller."
				<<	"<p>"
				<<	"There are many advantages in using an escrow service:<p>";

	*mpStream	<<	"<table border=0 cellspacing=0 width=\"100%\">"
				<<	"<tr>"
				<<	"<td width=\"60%\">"
				<<	"<b>Buyers:</b>"
				<<	"<ul>"
				<<	"<li>Can receive and inspect the merchandise before i-Escrow sends "
				<<	"the payment to the seller."
				<<	"<li>Can send credit card information to a trusted source rather "
				<<	"than a stranger."
				<<	"</ul>"
				<<	"<b>Sellers:</b>"
				<<	"<ul>"
				<<	"<li>Have the ability to sell to buyers who prefer paying by credit cards."
				<<	"<li>Have a great way to overcome a buyer's hesitation in dealing with "
				<<	"an unknown source or in purchasing an expensive item."
				<<	"</ul>"
				<<	"</td>"
				<<	"<td width=\"5%\">"
				<<	"</td>"
				<<  "<td width=\"35%\" align=top>"
				<<	"<IMG SRC=\"http://www.iescrow.com/images/process.gif\" " 
				<<	"usemap=\"#ProcessMap\" WIDTH=\"200\" HEIGHT=\"200\" BORDER=\"0\">"
				<<	"<map name=\"ProcessMap\">"
				<<	"<area shape=\"RECT\" coords=\"6,4,77,70\" "
				<<	"href=\"http://www.iescrow.com/ebay/step1.html\">"
				<<	"<area shape=\"RECT\"  coords=\"114,3,194,74\" "
				<<	"href=\"http://www.iescrow.com/ebay/step3.html\">"
				<<	"<area shape=\"RECT\"  coords=\"140,102,197,166\" "
				<<	"href=\"http://www.iescrow.com/ebay/step4.html\">"
				<<	"<area shape=\"RECT\"  coords=\"74,147,128,195\" "
				<<	"href=\"http://www.iescrow.com/ebay/step5.html\">"
				<<	"<area shape=\"RECT\"  coords=\"4,84,57,144\" "
				<<	"href=\"http://www.iescrow.com/ebay/step5.html\">"
				<<	"</map>"
				<<	"</td>"
				<<	"</tr></table>"
				<<	"<br>"
				<<	"<b>Review</b> the "
				<<  "<A HREF=\""
				<<   mpMarketPlace->GetHTMLPath()
				<<	"help/community/iescrow-help.html\">"
				<<	"escrow process "
				<<  "</A>"
				<<	"or visit i-Escrow's "
				<<	"<A HREF=\"http://www.iescrow.com/ebay/genfaq.html\">"
				<<	"FAQ</A>.<br>"
				<<	"Please contact the other user and agree to use escrow prior to "
				<<	"beginning this process.<br><br>"
				<<	"Enter your User ID and Password to begin your transaction:<br><br>";

	
    //make my login form
	*mpStream	<<	"<form method=post action="
					"\""
				<<	mpMarketPlace->GetCGIPath(PageIEscrowShowData)
				<<	"eBayISAPI.dll"
				<<	"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\""
					" VALUE=\"IEscrowShowData\""
					">"
	//input fields
					"<pre>"
					"<b>Your registered</b> "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":             "
					"<input type=text name=userid "
					"size=" << 40 << " "
					"maxlength=" << 40 << " "
					">"
					"\n"
					"<b>Your</b> "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":"
					"                       "
					"<input type=password name=pass size=40>"
					"\n\n"
	//deal with item numbers, show and hidden
				 	"<b>Item #:</b> "
				 	"                             "
				<<  "<i>";
	if (!isGeneric)
	{
	 	*mpStream	<<  "<A HREF=\""
					<<   mpMarketPlace->GetCGIPath(PageViewItem)
					<<	"eBayISAPI.dll?ViewItem&item="
					<<	pItemNo
					<<	"\">"
					<<	pItemNo
					<<  "</A>"
					<<	"</i>"
					<<	"<input type=hidden name=item value="
					<<	pItemNo 
					<<	"><br><br>"
					<<	"<b>Title:</b>"
				 		"                               "
					<<	"<i>"
					<<	mpItem->GetTitle()
					<<	"</i>";
	}
	else
	{
	 	*mpStream	<<  "<input type=text name=item "
						"size=" << 30 << " "
						"maxlength=" << 30 << " "
						">"
						"\n";
	}
	// type
	*mpStream		<<	"<input type=hidden name=type value="
					<<	ptype
					<<	"><br><br>";

	*mpStream		<<	"<input type=hidden name=bidderno value="
					<<	bidderno
					<<	"><br><br>";
	//buttons	
	*mpStream	<<	"\n\n\n</PRE>"
				<<	"<input type=submit value=Proceed>"
				<<	"</FORM><p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();
	return;
}

void clseBayApp::IEscrowShowData(CEBayISAPIExtension *pServer,
								char *pUserId,
								char *pPass,
								char *pItemNo,
								char *ptype,
								int	  bidderno)
{
	bool					ValidationError = false;
	BidVector				vBidders;
	BidVector::iterator		iBidder;
	clsUser					*pSecondPartyUser, *pBidder;
	int						thisBidderQty = 1;
	float					fsp=0;
	int						n=0, bidder_no=0;


	SetUp();

	// Let's try and get the item, this creates mpItem
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	// nsacco 07/19/99 
	// output a title
	*mpStream	<< "<HTML>"
				<< "<TITLE>"
				<< mpMarketPlace->GetCurrentPartnerName()
				<< " i-Escrow"
				<< "</TITLE>"
				<< "</HEAD>";


	//let's get header first
	*mpStream	<<	mpMarketPlace->GetHeader();

	//validate input parametrs, do ALL checks before exiting this routine
	//build error output page, if needed.
	
	//user id and password, created mpUser
	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) == 0)
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												true);			// Admin Query
	}
	else
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												false);			// Admin Query
	}


	if (!mpUser)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}
	
	//item must have at least one bid
	if(mpItem->GetBidCount() == 0)
	{ 
		*mpStream	<<	"<p><H2>"
					<<	"Item "
					<<	pItemNo
					<<	" has no bids!"
					<<	"</H2><p>"
					<<	"You can not initiate or accept i-Escrow transaction "
					// nsacco 07/19/99
					<<	" for an unsuccessful auction."
					<<	"<br>";
		ValidationError = true;
	}
	//auction must end
	if (mpItem->GetEndTime() > time(0))
	{ 
		*mpStream	<<	"<p><H2>"
					<<	"Auction for item "
					<<	pItemNo
					<<	" is not over yet!"
					<<	"</H2><p>"
					<<	"You can not initiate or accept i-Escrow transaction "
					// nsacco 07/19/99
					<<	" for an uncompleted auction."
					<<	"<br>";
		ValidationError = true;
	}
	//reserve should be met
	if (mpItem->GetPrice() < mpItem->GetReservePrice())
	{	 
		*mpStream	<<	"<p><H2>"
					<<	"Reserve price for item "
					<<	pItemNo
					<<	" has not been met!"
					<<	"</H2><p>"
					<<	"You can not initiate or accept i-Escrow transaction "
					// nsacco 07/19/99
					<<	" for an unsuccefull reserved price auction."
					<<	"<br>";
		ValidationError = true;
	}
	// user maybe a bidder
	if (mpItem->GetSeller() != mpUser->GetId())
	{
		//chinese auction bidder?
		if (mpItem->GetQuantity() == 1)
		{
			if (mpItem->GetHighBidder() != mpUser->GetId())
			{
				//is a bidder at all? only happens if hight bidder backs out
//				if(!(gApp->GetDatabase()->IsABidderForThisItem(mpItem->GetId(),
//															mpUser->GetId())))
				mpItem->GetHighBidsForItem(false, &vBidders);

				for (iBidder = vBidders.begin(), n=1;
					iBidder != vBidders.end();
					iBidder++, n++)
				{
					//create HighBidder Object
					pBidder = mpUsers->GetUser((*iBidder)->mUser);
					if (strcmpi(pBidder->GetEmail(), mpUser->GetEmail()) == 0)
					{
						bidder_no = n;
						delete pBidder;
						break;
					}
					delete pBidder;		
				}

				if (bidder_no == 0)
				{
					*mpStream	<<	"<p><H2>"
								<<	"You are not a bidder or seller for item "
								<<	pItemNo
								<<	"!"
								<<	"</H2><p>"
								<<	"You can not initiate or accept i-Escrow transaction "
								<<	" if you are not a bidder or seller."
								<<	"<br>";
					ValidationError = true;
				}
				//clean up this vector
				for (iBidder = vBidders.begin();
					iBidder != vBidders.end();
					iBidder++)
				{
						delete (*iBidder);
				}
				vBidders.erase(vBidders.begin(), vBidders.end());
			}
		}
		else //dutch
		{
			//get sucessfull bids
			mpItem->GetDutchHighBidders(&vBidders);
			if (vBidders.size() == 0)
			//this actualy just a safety condition- should never happen 
			{
					*mpStream	<<	"<p><H2>"
								<<	"You are not a bidder or seller for item "
								<<	pItemNo
								<<	"!"
								<<	"</H2><p>"
								<<	"You can not initiate or accept i-Escrow transaction "
								<<	" if you are not a bidder or seller."
								<<	"<br>";
					ValidationError = true;
			}
			else //vBidders in NOT empty
			{
				for (iBidder = vBidders.begin();
					 iBidder != vBidders.end();
					 iBidder++)
				{
					if((*iBidder)->mUser ==  mpUser->GetId())
					{
						thisBidderQty = (*iBidder)->mQuantity;
						break;
					}
				}

				//if  not a bidder display error
				if(!(*iBidder) || ((*iBidder)->mUser !=  mpUser->GetId()))
				{
					*mpStream	<<	"<p><H2>"
								<<	"You are not a successful bidder on a dutch auction for item "
								<<	pItemNo
								<<	"!"
								<<	"</H2><p>"
								<<	"You can not initiate or accept i-Escrow transaction "
								<<	" if you are not a bidder."
								<<	"<br>";
					ValidationError = true;
				}

			}
			//clean up this vector
			for (iBidder = vBidders.begin();
				iBidder != vBidders.end();
				iBidder++)
			{
					delete (*iBidder);
			}
			vBidders.erase(vBidders.begin(), vBidders.end());
		}
	}

	if (ValidationError)
	{
		*mpStream	<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	//everything looks good:
	//create a page that will send data to iescrow.
	// Title 
	*mpStream	<<	"<TABLE BORDER=\"0\" CELLPADDING=\"5\" CELLSPACING=\"0\" "
				<<	"WIDTH=\"580\">\n"
				<<	"<TR>"	
				<<  "<TD VALIGN=TOP> <FONT SIZE=\"5\"><B>Escrow Services</B></FONT>"
				<<	"<FONT SIZE=\"3\" color=\"green\"><b> Step 2</b></FONT><BR>"
				<<	"<B><font color=\"#FF0000\">Review Information</font></B>"
				<<	"<P></P>"
				<<  "</TD>"
				<<	"</TR></TABLE>";

	*mpStream	<<	"i-Escrow needs the information below for your transaction in order to "
				<<	"begin escrow. With your permission, eBay can "
				<<	"pass this information to i-Escrow so that you will avoid "
				<<	"having to re-input this data on i-Escrow's site. "
				<<	"Please review the information to confirm its accuracy.<p><p>";

	//start my form
	*mpStream	<<	"<form method=post action="
				<<	"\""
				<<	mpMarketPlace->GetCGIPath(PageIEscrowSendData)
				<<	"eBayISAPI.dll\">"
				<<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\""
				<<	" VALUE=\"IEscrowSendData\""
				<<	">"
				<<	"<INPUT TYPE=HIDDEN NAME=\"partyone\""
				<<	" VALUE=\""
				<<	pUserId
				<<	"\">"
				<<	"<INPUT TYPE=HIDDEN NAME=\"item\""
				<<	" VALUE=\""
				<<	pItemNo
				<<	"\">"
				<<	"<INPUT TYPE=HIDDEN NAME=\"type\""
				<<	" VALUE=\""
				<<	ptype
				<<	"\">";

	//item info show
	*mpStream 	<<	"<center>"
				<<	"<table border=1 cellspacing=0 width=\"100%\" bgcolor=\"#99CCCC\">"
				<<	"<tr>"
				<<	"<td align=center width=\"100%\"><font size=4 color=\"#000000\">"
				<<	"<b>"
				<<	mpItem->GetTitle()
				<<	"</b></font>"
				<<	"</td></tr><tr>"
				<<	"<td align=center width=\"100%\"><font size=3 color=\"#000000\">"
	 			<<  "<A HREF=\""
				<<   mpMarketPlace->GetCGIPath(PageViewItem)
				<<	"eBayISAPI.dll?ViewItem&item="
				<<	pItemNo
				<<	"\">"
				<<	"Item #"
				<<	pItemNo
				<<  "</A>"
				<<	"</b></font></td>"
				<<	"</tr></table></center><br>"
				<<	"<b>Final Sale Price:</b> "
				<<	"<i>$"
				<<	mpItem->GetPrice();


	*mpStream	<<	"</i>"
				<<	"<INPUT TYPE=HIDDEN NAME=qty"
				<<	" VALUE="
				<<	thisBidderQty
				<<	">";

	*mpStream	<<	"<input type=hidden name=\"bidderno\" value="
				<<	bidderno
				<<	">";

	// Seller 
	if (mpItem->GetSeller() == mpUser->GetId())
	{
		//this is a seller 
		if(mpItem->GetQuantity()==1)
		{
			// Private Auction, sam, 01/03/99, display only high bidder
			if (mpItem->GetPrivate())
			{
				*mpStream	<<	"<BR>"
								"<b>E-Mail of the high bidder:</b>  "
								"<input type=hidden name=\"partytwo\" value="
							<<	mpItem->GetHighBidderEmail()
							<<	">"
							<<	"<i>"
							<<	mpItem->GetHighBidderEmail()
							<<	"</i>";
			}
			else
			{
				//chinese auciton
				mpItem->GetHighBidsForItem(false, &vBidders);

				if (strcmpi(ptype, "initial") == 0)
				{
					*mpStream	<<	"<BR>"
								<<	"<b>E-Mail of the high bidder:</b>  "
								<<	"<SELECT NAME=\"partytwo\">";
				}
				else
				{
					*mpStream	<<	"<BR>"
								<<	"<b>E-Mail of the bidder:</b>  "
								<<	"<input type=\"hidden\" NAME=\"partytwo\" ";
				}
				for (iBidder = vBidders.begin(), n=1;
					iBidder != vBidders.end();
					iBidder++, n++)
				{
					//create HighBidder Object
					pSecondPartyUser = mpUsers->GetUser((*iBidder)->mUser);
					if (strcmpi(ptype, "initial") == 0)
					{

						*mpStream	<<	"<OPTION ";
						//put selected HERE
						if (mpItem->GetHighBidder() == (*iBidder)->mUser)
								*mpStream	<<	"selected";

						*mpStream	<<	"VALUE="
									<<	pSecondPartyUser->GetEmail()
									<<	">"
									<<	pSecondPartyUser->GetEmail()
									<<	"</OPTION>";
				
						delete pSecondPartyUser;
						pSecondPartyUser = NULL; 
					}
					else
					{
						if (bidderno == n)
						{
							*mpStream	<<	"VALUE=\""
										<<	pSecondPartyUser->GetEmail()
										<<	"\">"
										<<	pSecondPartyUser->GetEmail();
							delete pSecondPartyUser;
							pSecondPartyUser = NULL; 
							break;
						}
					}
				}
				if (strcmpi(ptype, "initial") == 0)
					*mpStream	<<"<br></select>";
			}
		} 
		else
		{
			//this is dutch auction
			mpItem->GetDutchHighBidders(&vBidders);
			if (strcmpi(ptype, "initial") == 0)
			{
				*mpStream	<<	"<BR><BR>"
							<<	"<b>E-Mail's of dutch auction bidders:</b> "
							<<  "<font color=\"red\" size=\"2\">"
							<<	"(Select the email of a bidder from the dropdown box)</font><br>"
							<<	"<SELECT NAME=\"partytwo\">";
			}
			else
			{
				*mpStream	<<	"<BR>"
							<<	"<b>E-Mail of the bidder:</b>  "
							<<	"<input type=\"hidden\" NAME=\"partytwo\" ";
			}

			for (iBidder = vBidders.begin(), n=1;
			iBidder != vBidders.end();
			iBidder++, n++)
			{		
				pSecondPartyUser	= mpUsers->GetUser((*iBidder)->mUser);
				if (strcmpi(ptype, "initial") == 0)
				{				
					*mpStream	<<	"<OPTION VALUE="
								<<	"\""
								<<	pSecondPartyUser->GetEmail()
								<<	"\""
								<<	">"
								<<	pSecondPartyUser->GetEmail()
								<<	"</OPTION>";
			
					delete pSecondPartyUser;
				}
				else
				{
					if (n == bidderno)
					{
						*mpStream	<<	"VALUE=\""
									<<	pSecondPartyUser->GetEmail()
									<<	"\">"
									<<	pSecondPartyUser->GetEmail();
						delete pSecondPartyUser;
						pSecondPartyUser = NULL; 
						break;
					}
				}
			}
			if (strcmpi(ptype, "initial") == 0)
				*mpStream	<<	"</select>"
							<<	"<p>For information on how to add more than one bidder, click "
							<<  "<A HREF=\""
							<<   mpMarketPlace->GetHTMLPath()
							<<	"help/community/iescrow-help.html\">"
							<<	"here"
							<<  "</A>.";

			*mpStream	<<	"<p>";
		}

		//clean up bidders vector
		for (iBidder = vBidders.begin();
		iBidder != vBidders.end();
		iBidder++)
		{
			delete (*iBidder);
		}
		vBidders.erase(vBidders.begin(), vBidders.end());

	}
	else
	{
		//this is a bidder - no difference for dutch and chinese aucitons
		//create Seller Object
		pSecondPartyUser = mpUsers->GetUser(mpItem->GetSeller());
		//make output NOT editable
		*mpStream	<<	"<BR>"
					<<	"<b>Email of the seller: </b>"
					<<	"<i>"
					<<	pSecondPartyUser->GetEmail()
					<<	"</i>";
	
		//since this is not ediable email we need hidden field

		*mpStream	<<	"<input type=hidden name=\"partytwo\" value="
					<<	pSecondPartyUser->GetEmail()
					<<	">";

		delete pSecondPartyUser;
	}

	//user logged in contact informaiton
	*mpStream	<<	"<p><b>Your contact infomation:</b>"
				<<	"<table border=\"1\" width=\"590\" cellspacing=\"0\" "
				<<	"cellpadding=\"4\"> <tr><td width=\"35%\" "
				<<	"bgcolor=\"#EFEFEF\"><strong><font size=\"3\" color=\"#006600\">"
				<<	"Name</font></strong></td><td width=\"65%\">"
				<<	mpUser->GetName()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>E-Mail</strong></font></td><td width=\"65%\">"
				<<	mpUser->GetEmail()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>Company</strong></font></td><td width=\"65%\">";

	if (!mpUser->GetCompany())
		*mpStream << "<i>-na-</i>";
	else
		*mpStream <<	mpUser->GetCompany();
	*mpStream	<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>Address</strong></font></td><td width=\"65%\">"
				<<	mpUser->GetAddress()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>City</strong></font></td><td width=\"65%\">"
				<<	mpUser->GetCity()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>State/Region</strong></font></td><td width=\"65%\">"
				<<	mpUser->GetState()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>Zip</strong></font></td><td width=\"65%\">"
				<<	mpUser->GetZip()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>Country</strong></font></td><td width=\"65%\">"
				<<	mpUser->GetCountry()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>Phone Day</strong></font></td><td width=\"65%\">"
				<<	mpUser->GetDayPhone()
				<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>Phone Night</strong></font></td><td width=\"65%\">";

	if (!mpUser->GetNightPhone())
		*mpStream << "<i>-na-</i>";
	else
		*mpStream <<	mpUser->GetNightPhone();

	*mpStream	<<	"</td></tr>";

	*mpStream	<<	"<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font color=\"#006600\">"
				<<	"<strong>Fax</strong></font></td><td width=\"65%\">";

	if (!mpUser->GetFaxPhone())
		*mpStream << "<i>-na-</i>";
	else
		*mpStream <<	mpUser->GetFaxPhone();

	*mpStream	<<	"</td></tr>"
				<<	"</table>";

	*mpStream	<<	"<p>Click "
				<<  "<A HREF=\""
				<<   mpMarketPlace->GetHTMLPath()
				<<	"/services/myebay/change-registration.html"
				<<	"\">"
				<<	"here"
				<<  "</A>"
				<<	" to update your contact information.";

	//buttons
	*mpStream	<<	"<BR>"
				<<	"<b><p>"
				<<	"<input type=submit value=\"Proceed\"> "
				<<	"to Step 3.</b>"
				<<	"</form><p>"
				<<	mpMarketPlace->GetFooter();


	*mpStream	<<	flush;


	CleanUp();
	return;
}



void clseBayApp::IEscrowSendData(CEBayISAPIExtension *pServer,
								 char *pPartyOne,
								 char *pItemNo,
								 char *ptype,
								 int   Qty,
								 char *pPartyTwo,
								 int   bidderno)


{
	char					bidder_email[128];
	clsUser					*pBidder=NULL;
	BidVector				vBidders;
	BidVector::iterator		iBidder;
	int						n=0;
	char					buf[2048];
	char					acceptStr[256];
	char					iEscrowEmailTitle[128];
	clsMail					*pMail=NULL;
	ostream					*pMStream=NULL;
	clsUser					*pAcceptor=NULL;
	char					str[128];
	char					helpURL[128];


	SetUp();

	// Let's try and get the item, this creates mpItem
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	//let's get header first
	*mpStream	<<	mpMarketPlace->GetHeader();

	//validate input parametrs, do ALL checks before exiting this routine
	//build error output page, if needed.
	
	mpUser  = mpUsers->GetUser(pPartyOne);	

	
	if (!mpUser)
	{
		*mpStream	<<	"Error obtaining user information<br>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}
	

	//everything looks good:
	//create a page that will send data to iescrow.

	//start my form
	// test url commented out
	*mpStream	<<	"<form method=post action="
				<<	"\""
//				<<	"http://ns1.iescrow.com/cgi-bin/ebay/StartTransaction"
				<<	"http://ie9.iescrow.com/cgi-bin/ebay/StartTransaction"
				<<	"\""
				<<	">";

	//item info - hidden 
	*mpStream	<<	"<input type=hidden name=\"item\" value="
				<<	pItemNo 
				<<	">"
				<<	"<input type=hidden name=\"title\" value= "
				<<	"\""
				<<	mpItem->GetTitle()
				<<  "\""
				<<	">"
				<<	"<input type=hidden name=\"fsp\" value="
				<<	mpItem->GetPrice()
				<<	">";

	//let's go ahead and gather logged user information into hidden
	*mpStream	<<	"<input type=hidden name=\"name\" value="
				<<	"\""
				<<	mpUser->GetName()
				<<  "\""
				<<	">"
				<<	"<input type=hidden name=\"company\" value="
				<<	"\"";

	if (!mpUser->GetCompany())
		*mpStream << "";
	else
		*mpStream <<	mpUser->GetCompany();

	*mpStream	<<  "\""
				<<	">"
				<<	"<input type=hidden name=\"address\" value="
				<<	"\""
				<<	mpUser->GetAddress()
				<<  "\""
				<<	">";

	*mpStream	<<	"<input type=hidden name=city value="
				<<	"\""
				<<	mpUser->GetCity()
				<<  "\""
				<<	">";

	*mpStream	<<	"<input type=hidden name=state value="
				<<	"\""
				<<	mpUser->GetState()
				<<  "\""
				<<	">";

	*mpStream	<<	"<input type=hidden name=zip value="
				<<	"\""
				<<	mpUser->GetZip()
				<<  "\""
				<<	">";

	*mpStream	<<	"<input type=hidden name=country value="
				<<	"\""
				<<	mpUser->GetCountry()
				<<  "\""
				<<	">";

	*mpStream	<<	"<input type=hidden name=phoneday value="
				<<	"\""
				<<	mpUser->GetDayPhone()
				<<  "\""
				<<	">";
	
	*mpStream	<<	"<input type=hidden name=phonenight value="
				<<	"\"";

	if (!mpUser->GetNightPhone())
		*mpStream << "";
	else
		*mpStream <<	mpUser->GetNightPhone();

	*mpStream	<<  "\""
				<<	">";

	*mpStream	<<	"<input type=hidden name=fax value="
				<<	"\"";

	if (!mpUser->GetFaxPhone())
		*mpStream << "";
	else
		*mpStream <<	mpUser->GetFaxPhone();

	*mpStream	<<  "\""
				<<	">";

	*mpStream	<<	"<input type=hidden name=partyone value="
				<<	mpUser->GetEmail()
				<<	">";

	// compute bidder ordinal
	// bidder_no will be zero only if transaction state is initial
	if (strcmpi(ptype, "initial")==0)
	{
		memset(bidder_email, 0x00, sizeof(bidder_email));
		bidderno = 1; // if only one bidder or first bidder chosen, default
		if (mpItem->GetSeller() != mpUser->GetId()) // partyone is bidder, partytwo is seller
			strcpy(bidder_email, mpUser->GetEmail());
		else
			strcpy(bidder_email, pPartyTwo);

		// Dutch Auctions
		if (mpItem->GetQuantity() > 1)
		{
			mpItem->GetDutchHighBidders(&vBidders);
			// Now get the bidderno
			for (iBidder = vBidders.begin(), n=1;
				iBidder != vBidders.end();
				iBidder++, n++)
			{
				//create HighBidder Object
				pBidder = mpUsers->GetUser((*iBidder)->mUser);
				if (strcmpi(pBidder->GetEmail(), bidder_email) == 0)
				{
					bidderno = n;
					delete pBidder;
					break;
				}
				delete pBidder;
			}
		}
		else // Chinese auction
		{

			mpItem->GetHighBidsForItem(false, &vBidders);

			for (iBidder = vBidders.begin(), n=1;
				iBidder != vBidders.end();
				iBidder++, n++)
			{
				//create HighBidder Object
				pBidder = mpUsers->GetUser((*iBidder)->mUser);
				if (strcmpi(pBidder->GetEmail(), bidder_email) == 0)
				{
					bidderno = n;
					delete pBidder;
					break;
				}
				delete pBidder;		
			}
		}
		// Cleanup
		for (iBidder = vBidders.begin();
		iBidder != vBidders.end();
		iBidder++)
		{
			delete (*iBidder);
		}
		vBidders.erase(vBidders.begin(), vBidders.end());			
	}

	*mpStream	<<	"<input type=hidden name=bidder_no value="
				<<	bidderno
				<<	">";


	if (mpItem->GetSeller() != mpUser->GetId())
	{
		memset(str, 0x00, sizeof(str));
		//buyer is logged in
		*mpStream	<<	"<input type=hidden name=partytwo value=";
		sprintf(str, "0+%d", Qty);
		*mpStream	<<	str
					<<	">";

		memset(str, 0x00, sizeof(str));
		*mpStream	<<	"<input type=hidden name=whoisone value=";
		sprintf(str, "B+%s", ptype);
		*mpStream	<<	str
					<<	">";

	}
	else
	{
		//seller is logged in
		memset(str, 0x00, sizeof(str));
		*mpStream	<<	"<input type=hidden name=partytwo value=";
		sprintf(str, "%d+%d", bidderno, Qty);
		*mpStream	<<	str
					<<	">";

		memset(str, 0x00, sizeof(str));
		*mpStream	<<	"<input type=hidden name=whoisone value=";
		sprintf(str, "S+%s", ptype);
		*mpStream	<<	str
					<<	">";
	}

	// Send email to second party only if this is initial transaction
	if (strcmpi(ptype, "initial")==0)
	{
		pMail	  = new clsMail;
		pMStream  = pMail->OpenStream();
		pAcceptor = mpUsers->GetUser(pPartyTwo);	

		memset(buf, 0x00, sizeof(buf));
		memset(acceptStr, 0x00, sizeof(acceptStr));
		memset(helpURL, 0x00, sizeof(helpURL));

		sprintf(helpURL, "%shelp/community/iescrow-help.html", mpMarketPlace->GetHTMLPath());

		sprintf(iEscrowEmailTitle, "Escrow is requested for auction #%d", 
									mpItem->GetId());	
		sprintf(acceptStr, "%seBayISAPI.dll?iescrowlogin&item=%d&type=accept&bidderno=%d", 
							mpMarketPlace->GetCGIPath(PageIEscrowLogin),
							mpItem->GetId(),
							bidderno);

		sprintf(buf, iEscrowEmailNotify, pAcceptor->GetUserId(), mpUser->GetUserId(), 
					 mpItem->GetId(), mpItem->GetTitle(), mpItem->GetPrice(),
					 helpURL, acceptStr, mpUser->GetUserId(), mpUser->GetEmail());

		*pMStream	<<	buf;

		// Test, 
//		strcpy(pPartyTwo, "sam@ebay.com");

		pMail->Send(pPartyTwo,
					"aw-confirm@ebay.com",
					iEscrowEmailTitle);

		delete pMail;
	}

	*mpStream	<<	"<TABLE BORDER=\"0\" CELLPADDING=\"5\" CELLSPACING=\"0\" "
				<<	"WIDTH=\"580\">\n"
				<<	"<TR>"	
				<<  "<TD VALIGN=TOP> <FONT SIZE=\"5\"><B>Escrow Services</B></FONT>"
				<<	"<FONT SIZE=\"3\" color=\"green\"><b> Step 3</b></FONT><BR>"
				<<	"<B><font color=\"#FF0000\">Forward Information To i-Escrow</font></B>"
				<<	"<P></P>"
				<<  "</TD>"
				<<	"</TR></TABLE>";

	*mpStream	<<	"By clicking on \"I agree\" below you acknowledge that your information "
					"as provided in Step 2 will be passed on to i-Escrow. i-Escrow is a "
					"third party that eBay has chosen to provide escrow services for eBay "
					"users. As a third party, your information will be used in accordance "
					"with <b><i>their</i></b> terms and conditions, and not eBay's."
					"<p>To view i-Escrow's terms and conditions for eBay users, click "
					"<a href=\"http://www.iescrow.com/ebay/userinfo.html\">"
					"here</a>. "
					"The major clauses of i-Escrow's policy read as follows:"
					"<ul>"
					"<li>i-Escrow may communicate (by email, physical mail, telephone. or otherwise) "
					"with eBay Users only as directly related to the (Escrow) Services and not other ways."
					"<li>i-Escrow may not disclose any eBay User's contact information to any "
					"third party except as directly related to the (Escrow) Services."
					"</ul>";

	*mpStream <<	"<p><input type=submit value=\"I agree\"> "
			  <<	"<b>to pass the information in Step 2 to i-Escrow.</b>"
			  <<	"</form><p>";

	*mpStream	<<	mpMarketPlace->GetFooter()
				<<	flush;
	CleanUp();
	return;
}
   