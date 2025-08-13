/*	$Id	*/
//
//	File:		clseBayAppUserVerification.cpp
//
//	Class:		clseBayApp
//
//	Author:		Anoop Goyal (anoop@ebay.com)
//
//	Function:
//				Does everything to do with User Verification in clseBayApp.
//
//	Modifications:
//				- 02/22/99 anoop	- Created
//

#include "ebihdr.h"

//
// Validate (and allow) or block certain actions such as bidding, selling, 
// leaving feedback, posting to board, etc.
// mpUser should be valid when this method is called.
//
bool clseBayApp::ValidateOrBlockAction(bool printHeader /*=false*/)
{
	bool retVal = FALSE;

	if (!mpUser)
	{
		return false;
	}
	
	retVal = mpUser->IsValidUVRating();
	if (retVal == FALSE) 
	{
		if (printHeader)
			*mpStream <<	mpMarketPlace->GetHeader();

		*mpStream <<	"<p>"
						"We are sorry but you cannot place a bid, list an item, "
						"leave feedback, or post to the boards at this time. "
						"According to our records, your contact information contains "
						"some discrepancies. We would very much appreciate it if you "
						"could spend a few minutes updating this information at "
						"<A HREF=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"/services/myebay/change-registration.html\">"
						"Change Registered Information Page"
						"</A>"
						"</p>"
						"\n"
						"<p>"
						"You will be able to bid, list new items, leave feedback, "
						"and post to the boards once your contact information has "
						"been updated."
						"</p>"
						"\n"
						"<p>"
						"Thank you for helping us make eBay a safer place!"
						"</p>";
	}

	return retVal;
}  /* clseBayApp::ValidateOrBlockAction */
