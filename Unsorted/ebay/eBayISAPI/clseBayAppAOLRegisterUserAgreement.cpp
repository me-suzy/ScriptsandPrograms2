/*	$Id: clseBayAppAOLRegisterUserAgreement.cpp	*/
//
//	File:	clseBayAppAOLRegisterUserAgreement.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		Registration User Agreement
//
// Modifications:
//				- 06/08/99 Lou	- Created
//				- 07/07/99 nsacco - added siteId and coPartnerId
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

//
// Support for our own personal crypt
#include "malloc.h"		// Crypt uses malloc
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

//Error color param
static const char *ErrorColor =
"color=\"#FF0000\"";

// Header text
static const char *HeaderText =
" User Agreement";

// Title text
static const char *TitleText =
"<p><font size=\"4\"><strong>Accept the eBay User Agreement</strong></font></p>\n"
"<p><strong>You're almost finished!&nbsp; Review eBay's</strong>"
"<font size=\"3\"> <strong>User Agreement below and then click the &quot;"
"I accept&quot; button at the very bottom of the page.&nbsp; </strong></font></p>\n"
"<p><strong><font size=\"3\">This agreement helps keep eBay a safe place to buy and sell, and"
"promotes trust among our community members.</font><small> </small></strong></p>\n"
"<table border=\"1\" cellspacing=\"1\" width=\"590\"><tr><td width=\"590%\">"
"<p align=\"center\"><font size=\"5\"><strong><b>eBay User Agreement for U.S. "
"Members</b></strong></font></td></tr></table><hr>\n";

// Registration Banner
static const char *RegistrationBannerPart1 =
"<table border=\"1\" width=\"669\" cellspacing=\"0\" bgcolor=\"#99CCCC\" cellpadding=\"2\">"
"<tr>"
"<td width=\"663\"><p align=\"center\"><strong><font size=\"5\">"
"eBay at AOL Registration</font>&nbsp;&nbsp;</strong>\n";

//Name of Image
static const char *RegBannerImage =
"flag-us.gif";

//Leave space for the image that will go along with the text
static const char *RegistrationBannerPart2 =
"</td></tr></table>\n";

//User agreement text in List Box

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *RegistrationUAText =
"<p><textarea rows=\"15\" name=\"S1\" cols=\"74\">THE FOLLOWING DESCRIBES THE TERMS "
"ON WHICH EBAY OFFERS YOU ACCESS TO OUR SERVICES. BY PRESSING THE "
"&quot;I ACCEPT&quot; BUTTON, YOU ACCEPT THE TERMS AND CONDITIONS BELOW.\n"
"Welcome to eBay Inc.&#146;s User Agreement. This Agreement describes the terms "
"and conditions applicable to your use of our services at eBay.com. By clicking "
"the &quot;I Accept&quot; button, you accept the terms and conditions of this "
"Agreement. If you do not accept these terms and conditions or have any questions "
"that our User Agreement Frequently Asked Questions or our User Agreement Revision "
"Frequently Asked Questions cannot answer, please contact agree-questions@ebay.com. \n"
"We may amend this Agreement at any time by posting the amended terms on our site. "
"If you wish to receive an email update for each amendment to this Agreement "
"please click the checkbox at the bottom of this page. The amended terms shall "
"automatically be effective 30 days after they are initially posted on our site. "
"This Agreement may not be otherwise amended except in a writing signed by both "
"parties. This agreement was revised on March 31, 1999.\n"
"Eligibility for Membership. Our services are available only to individuals who "
"can form legally binding contracts under applicable law. Without limiting the "
"foregoing, our services are not available to minors. If you do not qualify, "
"please do not use our services. eBay may refuse our services (such as, but not "
"limited to listings, chat, and bidding) to anyone at any time, in our sole discretion. "
"Fees. Joining eBay is free. Our then-current Fees and Credit Policies for "
"selling items on eBay are available at http://www.ebay.com/agreement-fees.html, "
"and are incorporated herein by reference. Unless otherwise stated, all fees are "
"quoted in U.S. Dollars. You are responsible for paying all applicable taxes and "
"for all hardware, software, service and other costs you incur to bid, buy, "
"procure a listing from us or access our servers. We may in our sole discretion "
"add, delete or change some or all of our services at any time.\n"
"eBay is Only a Venue. Our site acts as the venue for sellers to conduct "
"auctions and for bidders to bid on sellers&#146; auctions. We are not involved "
"in the actual transaction between buyers and sellers. As a result, we have no "
"control over the quality, safety or legality of the items advertised, the truth "
"or accuracy of the listings, the ability of sellers to sell items or the ability "
"of buyers to buy items. We cannot and do not control whether or not sellers will "
"complete the sale of items they offer or buyers will complete the purchase of "
"items they have bid on. In addition, note that there are risks of dealing with "
"foreign nationals, underage persons or people acting under false pretense.\n" 
"Because user authentication on the Internet is difficult, eBay cannot and does "
"not confirm that each user is who they claim to be. Therefore, because you must "
"be careful in dealing with other users to avoid fraud, we have established a "
"user-initiated feedback system to help you evaluate who you are dealing with. "
"We also encourage you to communicate directly with a trading partner to help you "
"evaluate with whom you are dealing. Please practice safe trading. If you have "
"any concerns about your trading partner, please consider the use of a third "
"party escrow service or services that provide additional user verification. \n"
"Such services are available on our helpful Services page. Because we do not "
"and cannot be involved in user-to-user dealings, in the event that you have a "
"dispute with one or more users, you release eBay (and our agents and employees) "
"from claims, demands and damages (actual and consequential) of every kind and "
"nature, known and unknown, suspected and unsuspected, disclosed and undisclosed, "
"arising out of or in any way connected with such disputes. If you are a "
"California resident, you waive California Civil Code §1542, which says: "
"&quot;A general release does not extend to claims which the creditor does not "
"know or suspect to exist in his favor at the time of executing the release, "
"which if known by him must have materially affected his settlement with the "
"debtor.&quot; For legal reasons, we cannot nor do we try to control the "
"information provided by other users which is made available through our system. \n"
"By its very nature, other people&#146;s information may be offensive, harmful "
"or inaccurate, and in some cases will be mislabeled or deceptively labeled. "
"We expect that you will use caution -- and common sense -- when using our site. \n"
"Bidding and Buying. As a bidder, if you have the highest bid at the end of the "
"auction, at or above the minimum bid price (or in the case of reserve auctions, "
"at or above the reserve price), and your bid is accepted by the seller, you are "
"obligated to complete the transaction. Bids are not retractable except in "
"exceptional circumstances such as the seller materially changing the description "
"of the item after your bid or clear typographical errors, or when you can not "
"authenticate the identity of the seller. You may not bid in a way that pulls "
"other bidders to their maximum bid, retract the high bid, and then rebid at a "
"small increment above the legitimate high bidder (bid manipulation). \n"
"If you choose to bid on adult items, you are certifying that you have the legal "
"right to purchase items intended for adults only. \n"
"Listing and Selling. Listings are text descriptions, graphics and pictures on "
"eBay's web site supplied by you that either; (a) textually describe the item you "
"are listing for auction, or (b) link to the text, graphics and picture(s) "
"describing the item you are listing for auction. You may post on eBay's site "
"either or both of these listing types, provided that you place such listings in "
"an appropriate category. All Dutch auction items must be identical (the size, "
"color, make, and model all must be the same for each item). At any given time "
"you may not promote identical items in more than seven listings (whether Dutch "
"or Regular auction style) on the website. \n"
"If you receive one or more bids at or above your stated minimum price "
"(or in the case of reserve auctions, at or above the reserve price), "
"then you are obligated to complete the transaction with the highest bidder, "
"unless there is an exceptional circumstance, such as; (x) the buyer fails to "
"meet the terms of your listing (such as payment method), or (y) you cannot "
"authenticate the identity of the buyer. You may not email bidders in a "
"currently open auction being run by a different seller, offering similar or "
"the same items at any price level (bid siphoning), nor may you use an alias to "
"place bids on your auction for any reason. \n"
"Without limiting any other remedies, eBay may suspend or terminate your account "
"if you are found (by conviction, settlement, insurance or escrow investigation, "
"or otherwise) to have engaged in fraudulent activity in connection with our site. \n"
"eBay's Legal Buddy Program works to ensure that the items listed for auction do "
"not infringe upon the copyright, trademark or other rights of those third parties. \n"
"Legal Buddy Program Members have the ability to report infringing auction items, "
"which are thereby expeditiously removed. Legal Buddy Program Members have direct "
"access to some of your personally identifiable information as described in the "
"Privacy Policy. eBay cooperates with Legal Buddy Program Members and with local, "
"state and federal law enforcement in enforcement actions. Without limiting other "
"remedies, eBay will suspend or terminate your account if you repeatedly infringe "
"third party intellectual property rights. Your Information. Your information "
"includes any information you provide to us or other users during the registration, "
" bidding or listing process, in any public message area (including the Café or "
"the feedback area) or through any email feature (defined herein as &quot;Your "
"Information&quot;). With respect to Your Information: \n"
"6.1 You are solely responsible for Your Information, and we act as a passive "
"conduit for your online distribution and publication of Your Information. "
"However, we may take any action with respect to such information we deem "
"necessary or appropriate in our sole discretion if we believe it may create "
"liability for us or may cause us to lose (in whole or in part) the services of "
"our ISPs or other suppliers. \n"
"6.2 Your Information and your items for sale on eBay: (a) shall not be fraudulent "
"or involve the sale of counterfeit or stolen items; (b) shall not infringe any "
"third party's copyright, patent, trademark, trade secret or other proprietary "
"rights or rights of publicity or privacy; (c) shall not violate any law, statute, "
"ordinance or regulation (including without limitation those governing export "
"control, consumer protection, unfair competition, antidiscrimination or false "
"advertising); (d) shall not be defamatory, trade libelous, unlawfully threatening "
"or unlawfully harassing; (e) shall not be obscene or contain child pornography "
"or, if otherwise harmful to minors, shall be posted only in the Erotica, "
"Adults Only section and shall be distributed only to people legally permitted "
"to receive such content; (f) shall not contain any viruses, Trojan horses, "
"worms, time bombs, cancelbots or other computer programming routines that are "
"intended to damage, detrimentally interfere with, surreptitiously intercept or "
"expropriate any system, data or personal information; and (g) shall not link "
"directly or indirectly to or include descriptions of goods or services that: "
"(i) are prohibited under this Agreement; (ii) are identical to other items "
"you have up for auction but are priced lower than your auction item's reserve "
"or minimum bid amount; (iii) are concurrently listed for auction on a web site "
"other than eBay's; or (iv) you do not have a right to link to or include. "
"Furthermore, you may not post on our site or sell through our site any: "
"(x) item that, by paying to us the listing fee or the final value fee, could "
"cause us to violate any applicable law, statute, ordinance or regulation, or "
"(y) item that is currently on eBay's Prohibited Items List, and incorporated "
"herein, which may be updated from time to time.\n"
"6.3 Solely to enable eBay to use Your Information you supply us with, so that "
"we are not violating any rights you might have in that information, you agree "
"to grant us a non-exclusive, worldwide, perpetual, irrevocable, royalty-free, "
"sublicenseable (through multiple tiers) right to exercise the copyright and "
"publicity rights (but no other rights) you have in Your Information, in any "
"media now known or not currently known, with respect to Your Information. "
"eBay will only use Your Information in accordance with our Privacy Policy.\n" 
"No Price Manipulation. Sellers may not manipulate the price of their item, "
"either by using a shill (a secondary account or third party) or by bidding "
"themselves. System Integrity. You may not use any device, software or routine "
"to interfere or attempt to interfere with the proper working of the eBay site "
"or any auction being conducted on our site. You may not take any action which "
"imposes an unreasonable or disproportionately large load on our infrastructure. \n"
"You may not disclose or share your password to any third parties or use your "
"password for any unauthorized purpose. Feedback. You may not take any actions "
"which may undermine the integrity of the feedback system, such as: leaving "
"positive feedback for yourself using secondary User IDs or third parties; "
"leaving negative feedback for other users using secondary accounts or third "
"parties (feedback bombing); or leaving negative feedback if a user fails to "
"perform some action that is outside the scope of the auction (feedback extortion). "
"If you earn a net feedback rating of -4 (minus four), your membership will "
"automatically suspend, and you will be unable to list or bid. \n"
"Because feedback ratings are not designed for any purpose other than for "
"facilitating trading between eBay users, we may suspend or terminate your "
"account if you choose to market or promote your eBay feedback rating in any "
"venue other than eBay. Breach. We may immediately issue a warning, temporarily "
"suspend, indefinitely suspend or terminate your membership, any of your current "
"auctions, and any other information you place on the site if you breach this "
"Agreement or if we are unable to verify or authenticate any information you "
"provide to us. \n"
"Privacy. Our then-current privacy policies, available at "
"http://www.ebay.com/privacy-policy.html, are incorporated herein by "
"reference. No Warranty. WE AND OUR SUPPLIERS PROVIDE THE EBAY WEBSITE AND OUR "
"SERVICES &quot;AS IS&quot; AND WITHOUT ANY WARRANTY OR CONDITION, EXPRESS OR "
"IMPLIED. WE AND OUR SUPPLIERS SPECIFICALLY DISCLAIM THE IMPLIED WARRANTIES OF "
"TITLE, MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. \n"
"Some states do not allow the disclaimer of implied warranties, so the foregoing "
"disclaimer may not apply to you. This warranty gives you specific legal rights "
"and you may also have other legal rights which vary from state to state. \n"
"Limit of Liability. IN NO EVENT SHALL WE OR OUR SUPPLIERS BE LIABLE FOR LOST "
"PROFITS OR ANY SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES (HOWEVER ARISING, "
"INCLUDING NEGLIGENCE) ARISING OUT OF OR IN CONNECTION WITH THIS AGREEMENT. \n"
"OUR LIABILITY, AND THE LIABILITY OF OUR SUPPLIERS, TO YOU OR ANY THIRD PARTIES "
"IN ANY CIRCUMSTANCE IS LIMITED TO THE GREATER OF (A) THE AMOUNT OF FEES YOU PAY "
"TO US IN THE 12 MONTHS PRIOR TO THE ACTION GIVING RISE TO LIABILITY, AND (B) "
"$100. Some states do not allow the limitation of liability, so the foregoing "
"limitation may not apply to you. \n"
"General Compliance with Laws. You shall comply with all applicable laws, statutes, "
"ordinances and regulations regarding your use of our service and your bidding on, "
"listing, purchase and sale of items. \n"
"No Agency. You and eBay are independent contractors, and no agency, partnership, "
"joint venture, employee-employer or franchisor-franchisee relationship is "
"intended or created by this Agreement. Notices. Except as explicitly stated "
"otherwise, any notices shall be given by email to agree-questions@ebay.com "
"(in the case of eBay) or to the email address you provide to eBay during the "
"registration process (in your case), or such other address as the party shall "
"specify. Notice shall be deemed given 24 hours after email is sent, unless the "
"sending party is notified that the email address is invalid. Alternatively, "
"we may give you notice by certified mail, postage prepaid and return receipt "
"requested, to the address provided to eBay during the registration process. In "
"such case, notice shall be deemed given 3 days after the date of mailing. \n"
"Arbitration. Any controversy or claim arising out of or relating to this "
"Agreement shall be settled by binding arbitration in accordance with the "
"commercial arbitration rules of the American Arbitration Association. Any such "
"controversy or claim shall be arbitrated on an individual basis, and shall not "
"be consolidated in any arbitration with any claim or controversy of any other "
"party. The arbitrary shall be conducted in San Jose, California, and judgment "
"on the arbitration award may be entered into any court having jurisdiction "
"thereof. Either you or eBay may seek any interim or preliminary relief from a "
"court of competent jurisdiction in San Jose, California necessary to protect "
"the rights or property of you or eBay pending the completion of arbitration. \n"
"General. This Agreement shall be governed in all respects by the laws of the "
"State of California as such laws are applied to agreements entered into and to "
"be performed entirely within California between California residents. We do not "
"guarantee continuous, uninterrupted or secure access to our services, and "
"operation of our site may be interfered with by numerous factors outside of "
"our control. If any provision of this Agreement is held to be invalid or "
"unenforceable, such provision shall be struck and the remaining provisions shall "
"be enforced. Headings are for reference purposes only and in no way define, "
"limit, construe or describe the scope or extent of such section. Our failure to "
"act with respect to a breach by you or others does not waive our right to act "
"with respect to subsequent or similar breaches. This Agreement sets forth the "
"entire understanding and agreement between us with respect to the subject matter "
"hereof. Disclosures. The services hereunder are offered by eBay, Inc., located "
"at 2005 Hamilton Ave., Suite 350, San Jose, CA 95125. If you are a California "
"resident, you may have this same information emailed to you by sending a letter "
"to the foregoing address with your email address and a request for this "
"information. Fees for our services are described at "
"http://www.ebay.com/agreement-fees.html. Parental control protections (such "
"as computer hardware, software, or filtering services) are commercially "
"available that may assist you in limiting access to material that is harmful "
"to minors. If you are interested in learning more about these protections, "
"information is available at http://www.worldvillage.com/wv/school/html/control.html "
"or other analogous sites providing information on such protections. "
"The Complaint Assistance Unit of the Division of Consumer Services of the "
"Department of Consumer Affairs may be contacted in writing at 400 R Street, "
"Sacramento, CA 95814, or by telephone at (800) 952-5210.</textarea>\n";
*/

//Bottom text and buttons
// petra make button names unique
static const char *BottomTextAndButton =
"<p>I affirm that I am at least 18 years old.&nbsp; "
"<input type=\"radio\" name=\"agreementQ1\" value=\"1\">"
"Yes&nbsp; <input type=\"radio\" name=\"agreementQ1\" value=\"0\">"
"No<font color=\"#FF0000\"> (Required)</font></p>\n"
"<p>I understand eBay's <font color=\"#0000FF\"><u>listing policies</u></font>"
"and agree to them.&nbsp; <input type=\"radio\" name=\"agreementQ2\" value=\"1\"> "
"Yes&nbsp; <input type=\"radio\" name=\"agreementQ2\" value=\"0\">"
"No&nbsp; <font color=\"#FF0000\">(Required)</font></p>\n"
"<p><input type=\"checkbox\" name=\"notify\" value=\"1\"> "
"Please send me an email update if this agreement is ever amended. "
"Otherwise, I understand that amendments will be effective 30 days after such "
"amendment(s) is posted on the site..</p> \n"
"<p><input type=\"submit\" value=\"I Accept This Agreement\" name=\"buttonPressed1\">\n"
"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n"
"<input type=\"submit\" value=\"I Decline\" name=\"buttonPressed2\"></p></form>\n";

// Email Register link
static const char *EmailRegisterText =
"<p><br><br><br>If you have any problems registering, send an email to "
"<a href=\"mailto:register@ebay.com\">register@ebay.com</a>\n";

// nsacco 07/07/99 added siteId and coPartnerId
void clseBayApp::AOLRegisterUserAgreement(CEBayISAPIExtension *pServer,
							char * pUserId,
							char * pEmail,
							char * pName,
							char * pCompany,
							char * pAddress,
							char * pCity,
							char * pState,
							char * pZip,
							char * pCountry,
							int countryId,
							char * pDayPhone1,
							char * pDayPhone2,
							char * pDayPhone3,
							char * pDayPhone4,
							char * pNightPhone1,
							char * pNightPhone2,
							char * pNightPhone3,
							char * pNightPhone4,
							char * pFaxPhone1,
							char * pFaxPhone2,
							char * pFaxPhone3,
							char * pFaxPhone4,
							char * pGender,
							int referral,
						    char * pTradeshow_source1,
						    char * pTradeshow_source2,
						    char * pTradeshow_source3,
						    char * pFriend_email,
						    int purpose,
						    int interested_in,
						    int age,
						    int education,
						    int income,
						    int survey,
							LPTSTR pNewPass,
							int nPartnerId,
							int siteId,
							int coPartnerId,
							int UsingSSL,
							int nVerify
							)
{

	// Setup
	SetUp();	

	// Whatever happens, we need a title and a standard header
	*mpStream	<<	"<HTML>"
				<<	"<HEAD>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	HeaderText
				<<	"</TITLE>"
				<<	"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	*mpStream  <<	"\n";


	//Display the Registration Banner with image
	*mpStream	<<	RegistrationBannerPart1
				<<	"	<strong><img src=\""
				<<	mpMarketPlace->GetImagePath()
				<<	RegBannerImage
				<<	"\" width=\"60\" height=\"37\" align=\"absmiddle\">"
				<<	"</strong>\n"
				<<	RegistrationBannerPart2
				<<	TitleText;

	//Display the Title
	// *mpStream	<<	RegistrationUAText;

	// kakiyama 07/09/99

	*mpStream << clsIntlResource::GetFResString(-1,
											"<p><textarea rows=\"15\" name=\"S1\" cols=\"74\">THE FOLLOWING DESCRIBES THE TERMS "
											"ON WHICH EBAY OFFERS YOU ACCESS TO OUR SERVICES. BY PRESSING THE "
											"&quot;I ACCEPT&quot; BUTTON, YOU ACCEPT THE TERMS AND CONDITIONS BELOW.\n"
											"Welcome to eBay Inc.&#146;s User Agreement. This Agreement describes the terms "
											"and conditions applicable to your use of our services at eBay.com. By clicking "
											"the &quot;I Accept&quot; button, you accept the terms and conditions of this "
											"Agreement. If you do not accept these terms and conditions or have any questions "
											"that our User Agreement Frequently Asked Questions or our User Agreement Revision "
											"Frequently Asked Questions cannot answer, please contact agree-questions@ebay.com. \n"
											"We may amend this Agreement at any time by posting the amended terms on our site. "
											"If you wish to receive an email update for each amendment to this Agreement "
											"please click the checkbox at the bottom of this page. The amended terms shall "
											"automatically be effective 30 days after they are initially posted on our site. "
											"This Agreement may not be otherwise amended except in a writing signed by both "
											"parties. This agreement was revised on March 31, 1999.\n"
											"Eligibility for Membership. Our services are available only to individuals who "
											"can form legally binding contracts under applicable law. Without limiting the "
											"foregoing, our services are not available to minors. If you do not qualify, "
											"please do not use our services. eBay may refuse our services (such as, but not "
											"limited to listings, chat, and bidding) to anyone at any time, in our sole discretion. "
											"Fees. Joining eBay is free. Our then-current Fees and Credit Policies for "
											"selling items on eBay are available at %{1:GetHTMLPath}agreement-fees.html, "
											"and are incorporated herein by reference. Unless otherwise stated, all fees are "
											"quoted in U.S. Dollars. You are responsible for paying all applicable taxes and "
											"for all hardware, software, service and other costs you incur to bid, buy, "
											"procure a listing from us or access our servers. We may in our sole discretion "
											"add, delete or change some or all of our services at any time.\n"
											"eBay is Only a Venue. Our site acts as the venue for sellers to conduct "
											"auctions and for bidders to bid on sellers&#146; auctions. We are not involved "
											"in the actual transaction between buyers and sellers. As a result, we have no "
											"control over the quality, safety or legality of the items advertised, the truth "
											"or accuracy of the listings, the ability of sellers to sell items or the ability "
											"of buyers to buy items. We cannot and do not control whether or not sellers will "
											"complete the sale of items they offer or buyers will complete the purchase of "
											"items they have bid on. In addition, note that there are risks of dealing with "
											"foreign nationals, underage persons or people acting under false pretense.\n" 
											"Because user authentication on the Internet is difficult, eBay cannot and does "
											"not confirm that each user is who they claim to be. Therefore, because you must "
											"be careful in dealing with other users to avoid fraud, we have established a "
											"user-initiated feedback system to help you evaluate who you are dealing with. "
											"We also encourage you to communicate directly with a trading partner to help you "
											"evaluate with whom you are dealing. Please practice safe trading. If you have "
											"any concerns about your trading partner, please consider the use of a third "
											"party escrow service or services that provide additional user verification. \n"
											"Such services are available on our helpful Services page. Because we do not "
											"and cannot be involved in user-to-user dealings, in the event that you have a "
											"dispute with one or more users, you release eBay (and our agents and employees) "
											"from claims, demands and damages (actual and consequential) of every kind and "
											"nature, known and unknown, suspected and unsuspected, disclosed and undisclosed, "
											"arising out of or in any way connected with such disputes. If you are a "
											"California resident, you waive California Civil Code §1542, which says: "
											"&quot;A general release does not extend to claims which the creditor does not "
											"know or suspect to exist in his favor at the time of executing the release, "
											"which if known by him must have materially affected his settlement with the "
											"debtor.&quot; For legal reasons, we cannot nor do we try to control the "
											"information provided by other users which is made available through our system. \n"
											"By its very nature, other people&#146;s information may be offensive, harmful "
											"or inaccurate, and in some cases will be mislabeled or deceptively labeled. "
											"We expect that you will use caution -- and common sense -- when using our site. \n"
											"Bidding and Buying. As a bidder, if you have the highest bid at the end of the "
											"auction, at or above the minimum bid price (or in the case of reserve auctions, "
											"at or above the reserve price), and your bid is accepted by the seller, you are "
											"obligated to complete the transaction. Bids are not retractable except in "
											"exceptional circumstances such as the seller materially changing the description "
											"of the item after your bid or clear typographical errors, or when you can not "
											"authenticate the identity of the seller. You may not bid in a way that pulls "
											"other bidders to their maximum bid, retract the high bid, and then rebid at a "
											"small increment above the legitimate high bidder (bid manipulation). \n"
											"If you choose to bid on adult items, you are certifying that you have the legal "
											"right to purchase items intended for adults only. \n"
											"Listing and Selling. Listings are text descriptions, graphics and pictures on "
											"eBay's web site supplied by you that either; (a) textually describe the item you "
											"are listing for auction, or (b) link to the text, graphics and picture(s) "
											"describing the item you are listing for auction. You may post on eBay's site "
											"either or both of these listing types, provided that you place such listings in "
											"an appropriate category. All Dutch auction items must be identical (the size, "
											"color, make, and model all must be the same for each item). At any given time "
											"you may not promote identical items in more than seven listings (whether Dutch "
											"or Regular auction style) on the website. \n"
											"If you receive one or more bids at or above your stated minimum price "
											"(or in the case of reserve auctions, at or above the reserve price), "
											"then you are obligated to complete the transaction with the highest bidder, "
											"unless there is an exceptional circumstance, such as; (x) the buyer fails to "
											"meet the terms of your listing (such as payment method), or (y) you cannot "
											"authenticate the identity of the buyer. You may not email bidders in a "
											"currently open auction being run by a different seller, offering similar or "
											"the same items at any price level (bid siphoning), nor may you use an alias to "
											"place bids on your auction for any reason. \n"
											"Without limiting any other remedies, eBay may suspend or terminate your account "
											"if you are found (by conviction, settlement, insurance or escrow investigation, "
											"or otherwise) to have engaged in fraudulent activity in connection with our site. \n"
											"eBay's Legal Buddy Program works to ensure that the items listed for auction do "
											"not infringe upon the copyright, trademark or other rights of those third parties. \n"
											"Legal Buddy Program Members have the ability to report infringing auction items, "
											"which are thereby expeditiously removed. Legal Buddy Program Members have direct "
											"access to some of your personally identifiable information as described in the "
											"Privacy Policy. eBay cooperates with Legal Buddy Program Members and with local, "
											"state and federal law enforcement in enforcement actions. Without limiting other "
											"remedies, eBay will suspend or terminate your account if you repeatedly infringe "
											"third party intellectual property rights. Your Information. Your information "
											"includes any information you provide to us or other users during the registration, "
											" bidding or listing process, in any public message area (including the Café or "
											"the feedback area) or through any email feature (defined herein as &quot;Your "
											"Information&quot;). With respect to Your Information: \n"
											"6.1 You are solely responsible for Your Information, and we act as a passive "
											"conduit for your online distribution and publication of Your Information. "
											"However, we may take any action with respect to such information we deem "
											"necessary or appropriate in our sole discretion if we believe it may create "
											"liability for us or may cause us to lose (in whole or in part) the services of "
											"our ISPs or other suppliers. \n"
											"6.2 Your Information and your items for sale on eBay: (a) shall not be fraudulent "
											"or involve the sale of counterfeit or stolen items; (b) shall not infringe any "
											"third party's copyright, patent, trademark, trade secret or other proprietary "
											"rights or rights of publicity or privacy; (c) shall not violate any law, statute, "
											"ordinance or regulation (including without limitation those governing export "
											"control, consumer protection, unfair competition, antidiscrimination or false "
											"advertising); (d) shall not be defamatory, trade libelous, unlawfully threatening "
											"or unlawfully harassing; (e) shall not be obscene or contain child pornography "
											"or, if otherwise harmful to minors, shall be posted only in the Erotica, "
											"Adults Only section and shall be distributed only to people legally permitted "
											"to receive such content; (f) shall not contain any viruses, Trojan horses, "
											"worms, time bombs, cancelbots or other computer programming routines that are "
											"intended to damage, detrimentally interfere with, surreptitiously intercept or "
											"expropriate any system, data or personal information; and (g) shall not link "
											"directly or indirectly to or include descriptions of goods or services that: "
											"(i) are prohibited under this Agreement; (ii) are identical to other items "
											"you have up for auction but are priced lower than your auction item's reserve "
											"or minimum bid amount; (iii) are concurrently listed for auction on a web site "
											"other than eBay's; or (iv) you do not have a right to link to or include. "
											"Furthermore, you may not post on our site or sell through our site any: "
											"(x) item that, by paying to us the listing fee or the final value fee, could "
											"cause us to violate any applicable law, statute, ordinance or regulation, or "
											"(y) item that is currently on eBay's Prohibited Items List, and incorporated "
											"herein, which may be updated from time to time.\n"
											"6.3 Solely to enable eBay to use Your Information you supply us with, so that "
											"we are not violating any rights you might have in that information, you agree "
											"to grant us a non-exclusive, worldwide, perpetual, irrevocable, royalty-free, "
											"sublicenseable (through multiple tiers) right to exercise the copyright and "
											"publicity rights (but no other rights) you have in Your Information, in any "
											"media now known or not currently known, with respect to Your Information. "
											"eBay will only use Your Information in accordance with our Privacy Policy.\n" 
											"No Price Manipulation. Sellers may not manipulate the price of their item, "
											"either by using a shill (a secondary account or third party) or by bidding "
											"themselves. System Integrity. You may not use any device, software or routine "
											"to interfere or attempt to interfere with the proper working of the eBay site "
											"or any auction being conducted on our site. You may not take any action which "
											"imposes an unreasonable or disproportionately large load on our infrastructure. \n"
											"You may not disclose or share your password to any third parties or use your "
											"password for any unauthorized purpose. Feedback. You may not take any actions "
											"which may undermine the integrity of the feedback system, such as: leaving "
											"positive feedback for yourself using secondary User IDs or third parties; "
											"leaving negative feedback for other users using secondary accounts or third "
											"parties (feedback bombing); or leaving negative feedback if a user fails to "
											"perform some action that is outside the scope of the auction (feedback extortion). "
											"If you earn a net feedback rating of -4 (minus four), your membership will "
											"automatically suspend, and you will be unable to list or bid. \n"
											"Because feedback ratings are not designed for any purpose other than for "
											"facilitating trading between eBay users, we may suspend or terminate your "
											"account if you choose to market or promote your eBay feedback rating in any "
											"venue other than eBay. Breach. We may immediately issue a warning, temporarily "
											"suspend, indefinitely suspend or terminate your membership, any of your current "
											"auctions, and any other information you place on the site if you breach this "
											"Agreement or if we are unable to verify or authenticate any information you "
											"provide to us. \n"
											"Privacy. Our then-current privacy policies, available at "
											"%{2:GetHTMLPath}privacy-policy.html, are incorporated herein by "
											"reference. No Warranty. WE AND OUR SUPPLIERS PROVIDE THE EBAY WEBSITE AND OUR "
											"SERVICES &quot;AS IS&quot; AND WITHOUT ANY WARRANTY OR CONDITION, EXPRESS OR "
											"IMPLIED. WE AND OUR SUPPLIERS SPECIFICALLY DISCLAIM THE IMPLIED WARRANTIES OF "
											"TITLE, MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. \n"
											"Some states do not allow the disclaimer of implied warranties, so the foregoing "
											"disclaimer may not apply to you. This warranty gives you specific legal rights "
											"and you may also have other legal rights which vary from state to state. \n"
											"Limit of Liability. IN NO EVENT SHALL WE OR OUR SUPPLIERS BE LIABLE FOR LOST "
											"PROFITS OR ANY SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES (HOWEVER ARISING, "
											"INCLUDING NEGLIGENCE) ARISING OUT OF OR IN CONNECTION WITH THIS AGREEMENT. \n"
											"OUR LIABILITY, AND THE LIABILITY OF OUR SUPPLIERS, TO YOU OR ANY THIRD PARTIES "
											"IN ANY CIRCUMSTANCE IS LIMITED TO THE GREATER OF (A) THE AMOUNT OF FEES YOU PAY "
											"TO US IN THE 12 MONTHS PRIOR TO THE ACTION GIVING RISE TO LIABILITY, AND (B) "
											"$100. Some states do not allow the limitation of liability, so the foregoing "
											"limitation may not apply to you. \n"
											"General Compliance with Laws. You shall comply with all applicable laws, statutes, "
											"ordinances and regulations regarding your use of our service and your bidding on, "
											"listing, purchase and sale of items. \n"
											"No Agency. You and eBay are independent contractors, and no agency, partnership, "
											"joint venture, employee-employer or franchisor-franchisee relationship is "
											"intended or created by this Agreement. Notices. Except as explicitly stated "
											"otherwise, any notices shall be given by email to agree-questions@ebay.com "
											"(in the case of eBay) or to the email address you provide to eBay during the "
											"registration process (in your case), or such other address as the party shall "
											"specify. Notice shall be deemed given 24 hours after email is sent, unless the "
											"sending party is notified that the email address is invalid. Alternatively, "
											"we may give you notice by certified mail, postage prepaid and return receipt "
											"requested, to the address provided to eBay during the registration process. In "
											"such case, notice shall be deemed given 3 days after the date of mailing. \n"
											"Arbitration. Any controversy or claim arising out of or relating to this "
											"Agreement shall be settled by binding arbitration in accordance with the "
											"commercial arbitration rules of the American Arbitration Association. Any such "
											"controversy or claim shall be arbitrated on an individual basis, and shall not "
											"be consolidated in any arbitration with any claim or controversy of any other "
											"party. The arbitrary shall be conducted in San Jose, California, and judgment "
											"on the arbitration award may be entered into any court having jurisdiction "
											"thereof. Either you or eBay may seek any interim or preliminary relief from a "
											"court of competent jurisdiction in San Jose, California necessary to protect "
											"the rights or property of you or eBay pending the completion of arbitration. \n"
											"General. This Agreement shall be governed in all respects by the laws of the "
											"State of California as such laws are applied to agreements entered into and to "
											"be performed entirely within California between California residents. We do not "
											"guarantee continuous, uninterrupted or secure access to our services, and "
											"operation of our site may be interfered with by numerous factors outside of "
											"our control. If any provision of this Agreement is held to be invalid or "
											"unenforceable, such provision shall be struck and the remaining provisions shall "
											"be enforced. Headings are for reference purposes only and in no way define, "
											"limit, construe or describe the scope or extent of such section. Our failure to "
											"act with respect to a breach by you or others does not waive our right to act "
											"with respect to subsequent or similar breaches. This Agreement sets forth the "
											"entire understanding and agreement between us with respect to the subject matter "
											"hereof. Disclosures. The services hereunder are offered by eBay, Inc., located "
											"at 2005 Hamilton Ave., Suite 350, San Jose, CA 95125. If you are a California "
											"resident, you may have this same information emailed to you by sending a letter "
											"to the foregoing address with your email address and a request for this "
											"information. Fees for our services are described at "
											"%{3:GetHTMLPath}agreement-fees.html. Parental control protections (such "
											"as computer hardware, software, or filtering services) are commercially "
											"available that may assist you in limiting access to material that is harmful "
											"to minors. If you are interested in learning more about these protections, "
											"information is available at http://www.worldvillage.com/wv/school/html/control.html "
											"or other analogous sites providing information on such protections. "
											"The Complaint Assistance Unit of the Division of Consumer Services of the "
											"Department of Consumer Affairs may be contacted in writing at 400 R Street, "
											"Sacramento, CA 95814, or by telephone at (800) 952-5210.</textarea>\n",
											clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
											clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
											clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
											NULL);

	//1st time here, so just show the info to the user so they can confirm
	*mpStream << "<br><form method=\"POST\" action=\"";

	//Do we need a SSL
	if (UsingSSL == 0)
		*mpStream	<<  mpMarketPlace->GetCGIPath(PageAOLRegisterUserAcceptAgreement);
	else
		*mpStream	<<	mpMarketPlace->GetSSLCGIPath(PageAOLRegisterUserAcceptAgreement);


	//Setup the call
	// nsacco 07/07/99 added siteId and coPartnerId
	*mpStream	<<	"eBayISAPI.dll?\">\n"
			<<	"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AOLRegisterUserAcceptAgreement\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"UsingSSL\" VALUE=\""
				<<	UsingSSL
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
				<<	pUserId
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"newpass\" VALUE=\""
				<<	pNewPass
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"email\" VALUE=\""
				<<	pEmail
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"name\" VALUE=\""
				<<	pName
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"company\" VALUE=\""
				<<	 pCompany
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"address\" VALUE=\""
				<<	pAddress
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"city\" VALUE=\""
				<<	pCity
				<<	 "\">\n<input TYPE=\"hidden\" NAME=\"state\" VALUE=\""
				<<	pState
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"zip\" VALUE=\""
				<<	pZip
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"country\" VALUE=\""
				<<	pCountry
				<<	 "\">\n<input TYPE=\"hidden\" NAME=\"countryid\" VALUE=\""
				<<	countryId
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone1\" VALUE=\""
				<<	pDayPhone1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone2\" VALUE=\""
				<<	pDayPhone2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone3\" VALUE=\""
				<<	pDayPhone3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"dayphone4\" VALUE=\""
				<<	pDayPhone4
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone1\" VALUE=\""
				<<	pNightPhone1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone2\" VALUE=\""
				<<	pNightPhone2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone3\" VALUE=\""
				<<	pNightPhone3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"nightphone4\" VALUE=\""
				<<	pNightPhone4
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone1\" VALUE=\""
				<<	pFaxPhone1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone2\" VALUE=\""
				<<	pFaxPhone2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone3\" VALUE=\""
				<<	pFaxPhone3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"faxphone4\" VALUE=\""
				<<	pFaxPhone4
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"gender\" VALUE=\""
				<<	pGender
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q1\" VALUE=\""
				<<	referral
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q17\" VALUE=\""
				<<	pTradeshow_source1
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q18\" VALUE=\""
				<<	pTradeshow_source2
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q19\" VALUE=\""
				<<	pTradeshow_source3
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q20\" VALUE=\""
				<<	pFriend_email
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q7\" VALUE=\""
				<<	purpose
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q14\" VALUE=\""
				<<	interested_in
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q3\" VALUE=\""
				<<	age
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q4\" VALUE=\""
				<<	education
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q5\" VALUE=\""
				<<	income
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"Q16\" VALUE=\""
				<<	survey
				<<	"\">\n<input TYPE=\"hidden\" NAME=\"partnerId\" VALUE=\""
				<<	nPartnerId
				<<	"\">\n"
				<<  "<input TYPE=\"hidden\" NAME=\"siteid\" VALUE=\""
				<<	siteId
				<<	"\">\n"
				<<  "<input TYPE=\"hidden\" NAME=\"copartnerid\" VALUE=\""
				<<	coPartnerId
				<<	"\">\n";



	//Add bottom note and confirm button
	*mpStream	<<	BottomTextAndButton;

	//Add link to register email
	*mpStream	<<	EmailRegisterText;

	//Add the footer
	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();
	
	CleanUp();
	return;
}

