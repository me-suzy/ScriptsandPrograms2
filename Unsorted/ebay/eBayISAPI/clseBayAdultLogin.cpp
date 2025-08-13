/* $Id: clseBayAdultLogin.cpp,v 1.7.54.6.38.3 1999/08/06 20:31:50 nsacco Exp $ */
/*
 * clseBayAdultLogin -- login to view adult pages.
 */
//
//		07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUtilities.h"
#include "clsBase64.h" 

static const char *sLegalText =
"<P ALIGN=\"CENTER\"><B><FONT SIZE=\"4\">Terms of Use</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">\"Adults Only\" Categories</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">(\"Terms of Use\")</FONT></B></P>\n"
"\n"
"<P>The pages for the  \"Adults Only\" categories may only be accessed and used by responsible adults over the age of 18 (or the age of consent in the jurisdiction from which it is being accessed). "
"By entering your UserID and Password to access this site, you are making the following statements:</P>\n"
"\n"
"<OL>\n"
"\n"
"<LI>I am a member of the eBay community and I will follow the eBay User Agreement governing my use of the eBay web site.</LI>\n"
"<LI>I am willing to provide eBay with my valid credit card number and expiration date, which will be left on file with eBay in order to verify that I am at least 18 years of age.</LI>\n"
"<LI>I will not permit any person(s) under 18 years of age to have access to any of the materials contained within this site.</LI>\n"
"<LI>I am voluntarily choosing to access this category, because I want to view, read and/or hear the various materials which are available.</LI>\n"
"<LI>I understand and agree to abide by the standards and laws of the community in which I live or from which I am accessing this site.</LI>\n"
"<LI>By entering my UserID and Password and viewing any part of this adult category, I agree that I shall not hold eBay or its employees responsible for any materials located in the adult category, and I waive all claims against eBay relating to materials found at this site.</LI>\n"
"<LI>If I use these services in violation of these Terms and Use, I understand I may be in violation of local and/or federal laws and am solely responsible for my actions.</LI>\n"
"<LI>By entering my UserID and Password at the bottom of these Terms and Use, and by entering the adult site, I agree to abide by these terms.</LI></OL>\n"
"\n"
"<P>Adults Only Category</P>\n"
"\n"
"<P>Materials available within this category include graphic visual depictions and descriptions of nudity and sexual activity."
"Federal, state or local laws may prohibit visiting this adult category if you are under 18 years of age.</P>\n"
"\n"
"<OL>\n"
"\n"
"<LI>I am an adult, at least 18 years of age, and I have a legal right to possess adult material in my community.</LI>\n"
"<LI>I do not find pornographic images of nude adults, adults engaged in sexual acts or other sexual material to be offensive or objectionable.</LI>\n"
"<LI>I will exit from this site immediately if I am in any way offended by the sexual nature of any materials on this site.</LI></OL>\n"
"\n"
"<P>Firearms, Adults Only Category</P>\n"
"\n"
"<P>Materials available within this category include guns, weapons, and ammunition.  Federal, State, or local laws regulate purchase, sale and use of these items.</P>\n"
"\n"
"<OL>\n"
"\n"
"<LI>I agree that I shall not hold eBay or its employees responsible for any items located in the Firearms, Adults Only category, and I waive all claims against eBay arising from the purchase, sale or use of such items.</LI>\n"
"<LI>I have a legal right and/or license to purchase, sell, and use firearms, ammunition, and other items from this Firearms, Adults Only category.</LI>\n"
"<LI>I understand that I am responsible for complying with such laws if I purchase items from this Firearms, Adults Only category.</LI></OL>\n"
"\n"
"<P>If you do not agree to these terms, click on the back button of your browser and exit this adult category page.</P>\n"
"\n";

static const char *sLegalEroticaText =
"<P ALIGN=\"CENTER\"><B><FONT SIZE=\"4\">Terms of Use</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">Adults Only Category</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">(\"Terms of Use\")</FONT></B></P>\n"
"\n"
"<p>You must be a responsible adult over the age of 18 "
"(or the age of consent in the jurisdiction from which this site is being accessed) "
"to view the Adults-Only category pages. Materials available in this category "
"include graphic visual depictions and descriptions of nudity and sexual activity. "
"Federal, state or local laws may prohibit visiting this adult category if "
"you are under 18 years of age. By entering your User ID and Password to "
"access this site, to list an item, or to bid on an item in this category, "
"you are making the following statements:\n"
"\n"
"<OL>\n"
"<LI>I am a member of the eBay community and I will follow the eBay User Agreement governing my use of the eBay web site.\n"
"<LI>I am willing to provide eBay with my valid credit card number and expiration date, which will be left on file with eBay in order to verify that I am at least 18 years of age.\n"
"<LI>I will not permit any person(s) under 18 years of age to have access to any of the materials contained within this site.\n"
"<LI>I am voluntarily choosing to access this category, because I want to view, read and/or hear the various materials that are available.\n"
"<LI>I understand and agree to abide by the standards and laws of the community in which I live or from which I am accessing this site.\n"
"<LI>By entering my User ID and Password and viewing any part of this adult category, I agree that I shall not hold eBay or its employees responsible for any materials located in the adult category, and I waive all claims against eBay relating to materials found at this site.\n"
"<LI>If I use these services in violation of these Terms and Use, I understand I may be in violation of local and/or federal laws and am solely responsible for my actions.\n"
"<LI>I am an adult, at least 18 years of age, and I have a legal right to possess adult material in my community.\n"
"<LI>I do not find pornographic images of nude adults, adults engaged in sexual acts or other sexual material to be offensive or objectionable.\n"
"<LI>I will exit from this site immediately if I am in any way offended by the sexual nature of any materials on this site.\n"
"<LI>By entering my User ID and Password at the bottom of these Terms of Use, and by listing, bidding an item, or entering the adult site, I agree to abide by these terms.\n"
"</OL>\n"
"\n"
"<P>If you do not agree to these terms, click on the back button of your browser and exit this adult category page.\n"
"\n";

static const char *sLegalFirearmsText =
"<P ALIGN=\"CENTER\"><B><FONT SIZE=\"4\">Terms of Use</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">Firearms Category</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">(\"Terms of Use\")</FONT></B></P>\n"
"\n"
"<P>The pages for the Firearms category may only be accessed and used by responsible "
"adults over the age of 18 (or the age of consent in the jurisdiction from which it is "
"being accessed). Materials available within this category include guns, weapons, and "
"ammunition. Federal, state, or local laws regulate purchase, sale and use of these "
"items.  By entering your User ID and Password to list or bid on an item in this "
"category, you are making the following statements:\n"
"\n"
"<OL>\n"
"<LI>I am a member of the eBay community and I will follow the eBay User Agreement governing my use of the eBay web site.\n"
"<LI>I am willing to provide eBay with my valid credit card number and expiration date, which will be left on file with eBay in order to verify that I am at least 18 years of age.\n"
"<LI>I will not permit any person(s) under 18 years of age to have access to any of the materials contained within this site.\n"
"<LI>I am voluntarily choosing to access this category, because I want to view, read and/or hear the various materials that are available.\n"
"<LI>I understand and agree to abide by the standards and laws of the community in which I live or from which I am accessing this site.\n"
"<LI>By entering my User ID and Password and viewing any part of this adult category, I agree that I shall not hold eBay or its employees responsible for any materials located in the adult category, and I waive all claims against eBay relating to materials found at this site.\n"
"<LI>If I use these services in violation of these Terms and Use, I understand I may be in violation of local and/or federal laws and am solely responsible for my actions.\n"
"<LI>I agree that I shall not hold eBay or its employees responsible for any items located in the Firearms category, and I waive all claims against eBay arising from the purchase, sale or use of such items.\n"
"<LI>I have a legal right and/or license to purchase, sell, and use firearms, ammunition, and other items from this Firearms category.\n"
"<LI>I understand that I am responsible for complying with such laws if I purchase items from this Firearms category.\n"
"<LI>By entering my User ID and Password at the bottom of these Terms of Use, and by listing or bidding on an item in the Firearms site, I agree to abide by these terms.\n"
"</OL>\n"
"\n"
"<P>If you do not agree to these terms, click on the back button of your browser and exit this adult category page.\n"
"\n";


// kakiyama 07/08/99 - commented out
// resourced using clsIntlResource::GetFResString

/*
static const char *sCongratsText =
"<P><b>Your information has been verified.</b>  You are now authorized to visit all of the listings on eBay.</P>\n"
"\n"
"<P>If you were attempting to view the listings, click <A HREF=\"http://listings.ebay.com/listings/list/category99/index.html\">here</A> to return to these pages.</P>\n"
"\n"
"<P>If you were attempting a search, click <A HREF=\"http://pages.ebay.com/search/items/search.html\">here</A> to redo the search.</P>\n"
"<P>Otherwise, use your browser's back button.</P></BODY>\n"
"</HTML>\n";
*/

// The page which leads to AdultLogin.
void clseBayApp::AdultLoginShow(int whichText /* =0 */)
{
	SetUp();

	// Headers
	*mpStream <<	"<HTML>"
					"<HEAD>";

	*mpStream << "<TITLE>"
			  << mpMarketPlace->GetCurrentPartnerName()
			  << " Adult Login"
				 "</TITLE>"
				 "</HEAD>"
			  << mpMarketPlace->GetHeader();

	switch (whichText)
	{
		case 0:
			*mpStream << sLegalText;
			break;
		case 1:
			*mpStream << sLegalEroticaText;
			break;
		case 2:
			*mpStream << sLegalFirearmsText;
			break;
		default:
			*mpStream << sLegalText;
			break;
	}

	*mpStream << "<FORM METHOD=POST ACTION=\""
			  << mpMarketPlace->GetCGIPath(PageAdultLogin)
			  << "eBayISAPI.dll\">"
				 "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdultLogin\">";

	// print out text input boxes for user id and password
	*mpStream	<<	"<table><tr><td>Your "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":</td>\n"
					"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
					"<tr><td>Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":</td>\n"
					"<td><input type=\"password\" name=\"password\" size=40></td></tr>\n"
					"</table>\n";
	*mpStream << "<INPUT TYPE=\"Submit\" VALUE=\"Authorize\"><br><br>";

	*mpStream << mpMarketPlace->GetFooter();
	CleanUp();
}

void clseBayApp::AdultLogin(char *pUserId,
							char *pPassword)
{
    clsBase64 theBase;
    unsigned char key[16];
    SetUp();

	// We need the user and their password, we haven't sent the header,
	// we don't have an action, ghost is not okay, we don't need feedback,
	// we do need the account.
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, NULL,
		false, NULL, false, false, false, true);

	if (mpUser && mpUser->IsVerifiedAsAdult() && mpUser->OkayToViewAdult())
    {
        clseBayCookie::BuildAdultCookie(key, gApp->GetEnvironment()->GetBrowser());
        SetCookie(CookieAdult, theBase.Encode((const char *) key, sizeof (key)), false);
    }

}

void clseBayApp::ShowAdultLogin(char *pUserId,
								char *pPassword)
{
    *mpStream << "<HTML><HEAD><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Adult Login"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	if (!mpUser)
    {
		*mpStream << "<P>";
        mpMarketPlace->GetUsers()->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream,
			true, NULL, false, false, false, true);
        *mpStream << mpMarketPlace->GetFooter();
        CleanUp();
        return;
    }

	else if (!mpUser->OkayToViewAdult())
	{
		*mpStream << "<P>In conformity with your national law, you "
			         "may not buy or sell in this category.";
	}

    else if (!mpUser->IsVerifiedAsAdult())
    {
        *mpStream << "<P>eBay requires a credit card to be placed on file in "
					 "order to be able to access the adult areas of our site.<br>"
					 "You may place a credit card on file by going "
					 "<A HREF=\""
				  << mpMarketPlace->GetSecureHTMLPath()
				  << "cc-adult.html\">here</A>."
					 "<p>"
				  << "<b>WebTV</b> users click "
				  << "<A HREF=\"" 
				  << mpMarketPlace->GetHTMLPath()
				  << "services/registration/secure-webtv-support.html\">here</A> "
				  << "for instructions on accessing secure pages."
				  << "<P>";
    }
    else
    {
		// kakiyama 07/07/99

		*mpStream << clsIntlResource::GetFResString(-1,
							"<P><b>Your information has been verified.</b>  You are now authorized to visit all of the listings on eBay.</P>\n"
							"\n"
							"<P>If you were attempting to view the listings, click <A HREF=\"%{1:GetListingPath}list/category99/index.html\">here</A> to return to these pages.</P>\n"
							"\n"
							"<P>If you were attempting a search, click <A HREF=\"%{2:GetHTMLPath}search/items/search.html\">here</A> to redo the search.</P>\n"
							"<P>Otherwise, use your browser's back button.</P></BODY>\n"
							"</HTML>\n",
							clsIntlResource::ToString(mpMarketPlace->GetListingPath()),
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL);
    }

    *mpStream << mpMarketPlace->GetFooter();

    CleanUp();
}
