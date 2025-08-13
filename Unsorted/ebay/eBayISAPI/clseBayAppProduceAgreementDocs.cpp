/*	$Id: clseBayAppProduceAgreementDocs.cpp,v 1.5.2.5.16.1 1999/08/05 18:59:00 nsacco Exp $	*/
//
//	File:		clseBayAppProduceAgreementDocs.cpp
//
//	Class:		clseBayApp
//
//	Author:		Barry Boone (barry@ebay.com)
//
//	Function:   Helper for converting existing users from the old
//              user agreement to the new user agreement. 
//
//	Modifications:
//				- 08/01/98 barry	- Created
//				- 04/18/99 kaz		- Change PageOptinChange to PageOptinLogin as we nixed PageOptionChange
//

#include "ebihdr.h"

void clseBayApp::ProduceUserAgreementIntroForBiddingAndSelling() 
{
	*mpStream <<
//		"<B> <img src=\"http://pics.ebay.com/aw/pics/hand-shake.GIF\" width=117 height=77 align=right vspace=10 hspace=10> \n"
// kakiyama 07/16/99
		"<B> <img src=\""
	<<	mpMarketPlace->GetPicsPath()
	<<  "hand-shake.GIF\" width=117 height=77 align=right vspace=10 hspace=10> \n"
		"<h2 ALIGN=left>User Agreement</h2> \n"
		"</B> \n"
		"<p ALIGN=left>This Agreement helps keep eBay a safe place to buy and sell. eBay  \n"
		"  is built on trust, and this Agreement helps promote that trust among all members  \n"
		"  of our community.</p> \n";

	*mpStream <<
		"<table border=\"0\" width=\"75%\" align=\"center\">\n"
		"<tr>\n"
		"<td bgcolor=\"#FFFFCC\">\n"
		"<p>This new User Agreement was placed here in response to user requests \n"
        "to eliminate the legal congestion from our site that appears when \n"
        "you bid and sell. With this User Agreement, all of that legalese \n"
        "will go away. At the same time, we can help promote trust between \n"
        "all members of our community by offering this clearer User Agreement.</p>\n"
		"<p>All you have to do is accept this User Agreement just this once; you \n"
        "will not need to accept this User Agreement again. In fact, accept this \n"
        "User Agreement now and <b>all of the legalese on the bidding and listing "
		"pages will vanish</b>! (You will always be able to review the User \n"
		"Agreement from the bottom of any page.) \n"
		"</p>\n"
		"</td>\n"
		"</tr>\n"
		"</table>";
}

void clseBayApp::ProduceUserAgreementIntroForAboutMe() 
{
	*mpStream <<
//		"<B> <img src=\"http://pics.ebay.com/aw/pics/hand-shake.GIF\" width=117 height=77 align=right vspace=10 hspace=10> \n"
// kakiyama 07/16/99
		"<B> <img src=\""
	<<  mpMarketPlace->GetPicsPath()
	<<  "hand-shake.GIF\" width=117 height=77 align=right vspace=10 hspace=10> \n"
		"<h2 ALIGN=left>User Agreement</h2> \n"
		"</B> \n"
		"<p ALIGN=left>This Agreement helps keep eBay a safe place to buy, sell, and visit. eBay  \n"
		"  is built on trust, and this Agreement helps promote that trust among all members  \n"
		"  of our community. <b>You must accept this User Agreement before creating \n"
		" your About Me page. </b></p> \n";
}

//
// Helper function to produce the HTML for the User Agreement
//
void clseBayApp::ProduceUserAgreementTopPart() 
{
	*mpStream <<
		"<P ALIGN=left>THE FOLLOWING DESCRIBES THE TERMS ON WHICH EBAY OFFERS YOU  \n"
		"  ACCESS TO OUR SERVICES. BY PRESSING THE \"I ACCEPT\" BUTTON, YOU ACCEPT  \n"
		"  THE TERMS AND CONDITIONS BELOW.</P> \n"
		"<P ALIGN=left>Welcome to eBay Inc.'s User Agreement. This Agreement describes  \n"
		"  the terms and conditions applicable to your use of our services at eBay.com.  \n"
		"  By clicking the \"I Accept\" button, you accept the terms and conditions  \n"
		"  of this Agreement. If you do not accept these terms and conditions or have any  \n"
		"  questions that our <a href=\""
	<<	mpMarketPlace->GetHTMLPath()
	<<	"help/basics/f-agreement.html\">User Agreement Frequently  \n"
		"  Asked Questions</a> cannot answer, please contact <a href=\"mailto:agree-questions@ebay.com\">agree-questions@ebay.com</a>.</P> \n"
		"<p align=left>We may amend this Agreement at any time by posting the amended  \n"
		"  terms on our site. If you wish to receive an email update for each amendment  \n"
		"  to this Agreement please click the checkbox at the bottom of this page. The  \n"
		"  amended terms shall automatically be effective 30 days after they are initially  \n"
		"  posted on our site. This Agreement may not be otherwise amended except in a  \n"
		"  writing signed by both parties.</p> \n";

	*mpStream <<
		"<ol> \n"
		"  <li><b>Eligibility for Membership.</b> Our services are available only to individuals  \n"
		"    who can form legally binding contracts under applicable law. Without limiting  \n"
		"    the foregoing, our services are not available to minors. If you do not qualify,  \n"
		"    please do not use our services. </li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n"
		"  <li><b>Fees.</b> Our then-current Fees and Credit Policies, available at <a href=\""
	<<  mpMarketPlace->GetHTMLPath()
	<<  "help/sellerguide/selling-fees.html\">"
	<<  mpMarketPlace->GetHTMLPath()
	<<  "/help/sellerguide/selling-fees.html</a>,  \n"
		"    are incorporated herein by reference. Unless otherwise stated, all fees are  \n"
		"    quoted in U.S. Dollars. You are responsible for paying all applicable taxes  \n"
		"    and for all hardware, software, service and other costs you incur to bid,  \n"
		"    buy, procure a listing from us or access our servers. We may in our sole discretion  \n"
		"    add, delete or change some or all of our services at any time. </li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n"
		"  <li><b>eBay is Only a Venue.</b> Our site acts as the venue for sellers to conduct  \n"
 		"  auctions and for bidders to bid on sellers auctions. We are not involved  \n"
		"    in the actual transaction between buyers and sellers. As a result, we have  \n"
		"    no control over the quality, safety or legality of the items advertised, the  \n"
		"    truth or accuracy of the listings, the ability of sellers to sell items or  \n"
		"    the ability of buyers to buy items. We cannot and do not control whether or  \n"
		"    not sellers will complete the sale of items they offer or buyers will complete  \n"
		"    the purchase of items they have bid on. In addition, note that there are risks  \n"
		"    of dealing with foreign nationals, underage persons or people acting under  \n"
		"    false pretense. We have established a user-initiated feedback system to help  \n"
		"    you evaluate who you are dealing with. We also encourage you to communicate  \n"
		"    directly with a trading partner to help you evaluate with whom you are dealing.</li> \n"
		"  <p></p> \n";

	*mpStream << 
		"  <p align=left>Because we do not and cannot be involved in user-to-user dealings,  \n"
		"    in the event that you have a dispute with one or more users, you release eBay  \n"
		"    (and our agents and employees) from claims, demands and damages (actual and  \n"
		"    consequential) of every kind and nature, known and unknown, suspected and  \n"
		"    unsuspected, disclosed and undisclosed, arising out of or in any way connected  \n"
		"    with such disputes. If you are a California resident, you waive California  \n"
		"    Civil Code §1542, which says: \"A general release does not extend to claims  \n"
		"    which the creditor does not know or suspect to exist in his favor at the time  \n"
		"    of executing the release, which if known by him must have materially affected  \n"
		"    his settlement with the debtor.\" </p> \n"
		"  <p align=left>For legal reasons, we cannot nor do we try to control the information provided  \n"
		"    by other users (including feedback) which is made available through our system. In particular,  \n"
		"    note that the pictures and graphics displayed in a seller's listing do not reside on our servers,  \n"
		"    and we do not and cannot control these. By its very nature, other peoples  \n"
		"    information may be offensive, harmful or inaccurate, and in some cases will  \n"
		"    be mislabeled or deceptively labeled. We expect that you will use caution -- and  \n"
		"    common sense -- when using our site. </p> \n"
		"  <p align=left>  \n";

	*mpStream <<
		"  <li><b>Bidding and Buying.</b> If you bid on an item and your bid is accepted  \n"
		"    by the seller, you are obligated to complete the transaction. Bids are not  \n"
		"    retractable except in exceptional circumstances such as the seller materially  \n"
		"    changing the description of the item after your bid or clear typographical  \n"
		"    errors. You may not bid in a way that pulls other bidders to their maximum  \n"
		"    bid, retract the high bid, and then rebid at a small increment above the legitimate  \n"
		"    high bidder. If you choose to bid on adult items, you are certifying that  \n"
		"    you have the legal right to purchase items intended for adults only.</li> \n"
		"  <p></p> \n";

	*mpStream <<
		"<p align=left>  \n"
		"  <li><b>Listing and Selling.</b> Listings are text descriptions, graphics and  \n"
		"    pictures on eBay's web site supplied by you of either (a) the text describing  \n"
		"    the item you are listing for auction or (b) links to the text, graphics and  \n"
		"    picture(s) describing the item you are listing for auction. You may post on  \n"
		"    eBay's site either or both of these listing types, provided that you place  \n"
		"    such listings in their appropriate category, as eBay may require. All Dutch  \n"
		"    auction items must be identical (the size, color, make, and model all must  \n"
		"    be the same for each item). In addition, we encourage you to check our <a href=\""
		<< mpMarketPlace->GetHTMLPath()
		<< "help/community/index.html\">community  \n"
		"    values and guidelines</a>.  \n"
		"    <p>If you receive one or more bids at or above your stated minimum or reserve price, then you  \n"
		"      are obligated to complete the transaction. You may not email bidders in  \n"
		"      a currently open auction being run by a different seller, offering similar  \n"
		"      or the same item at price levels below the current bid (bid siphoning),  \n"
		"      nor may you use an alias to place bids to make your auction a hot auction.  \n"
		"    </p> \n";

	*mpStream <<
		"    <p align=left>  \n"
		"  <li><b>Your Information.</b> Your information includes any information you provide  \n"
		"    to us or other users during the registration, bidding or listing process,  \n"
		"    in any public message area (including the Café or the feedback area) or through  \n"
		"    any email feature (defined herein as \"Your Information\"). With respect to  \n"
		"    Your Information:  \n"
		"    <p><b>6.1</b> You are solely responsible for Your Information, and we act  \n"
		"      as a passive conduit for your online distribution and publication of Your  \n"
		"      Information. However, we reserve the right to take any action with respect  \n"
		"      to such information we deem necessary or appropriate in our sole discretion  \n"
		"      if we believe it may create liability for us or may cause us to lose (in  \n"
		"      whole or in part) the services of our ISPs or other suppliers. </p> \n"
		"    <p><b>6.2</b> Your Information and the sale of your item(s) on eBay: (a) shall  \n"
		"      not infringe any third party's copyright, patent, trademark, trade secret  \n"
		"      or other proprietary rights or rights of publicity or privacy; (b) shall  \n"
		"      not violate any law, statute, ordinance or regulation (including without  \n"
		"      limitation those governing export control, consumer protection, unfair competition,  \n"
		"      antidiscrimination or false advertising); (c) shall not be defamatory, trade  \n"
		"      libelous, unlawfully threatening or unlawfully harassing; (d) shall not  \n"
		"      be obscene or contain child pornography or, if otherwise pornographic or  \n"
		"      indecent, shall be posted only in the Erotica, Adults Only section and shall \n"
	    "      be distributed only to people legally permitted to receive such content; (e) shall not contain any viruses,  \n"
		"      Trojan horses, worms, time bombs, cancelbots or other computer programming  \n"
		"      routines that are intended to damage, detrimentally interfere with, surreptitiously  \n"
		"      intercept or expropriate any system, data or personal information; and (f)  \n"
		"      shall not link directly to or include descriptions of goods or services  \n"
		"      that: (i) are identical to other items you have up for auction but are priced  \n"
		"      lower than your auction item's reserve or minimum bid amount; (ii) are concurrently  \n"
		"      listed for auction on a web site other than eBay's; or (iii) you do not  \n"
		"      have a right to link to or include. Furthermore, you may not post on our  \n"
		"      site or sell through our site any item that, by paying to us the listing  \n"
		"      fee or the final value fee, could cause us to violate any applicable law,  \n"
		"      statute, ordinance or regulation. </p> \n"
		"    <p><b>6.3</b> Solely to enable eBay to use Your Information you supply us  \n"
		"      with, so that we are not violating any rights you might have in that information,  \n"
		"      you agree to grant us a non-exclusive, worldwide, perpetual, irrevocable,  \n"
		"      royalty-free, sublicenseable (through multiple tiers) right to exercise  \n"
		"      the copyright and publicity rights (but no other rights) you have in Your  \n"
		"      Information, in any media now known or not currently known, with respect  \n"
		"      to Your Information. eBay will only use Your Information in accordance our  \n"
		"      Privacy Policy. </p> \n";

	*mpStream <<
		"  <p align=left>  \n"
		"  <li><b>No Price Manipulation.</b> Sellers may not manipulate the price of their  \n"
		"    item, either by using a shill (a secondary account or third party) or by bidding  \n"
		"    themselves (except that sellers in a non-reserve price auction may bid once  \n"
		"    in their own auction).</li> \n"
		"  <p></p> \n";

	*mpStream <<
		"  <p align=left>  \n"
		"  <li><b>System Integrity.</b> You may not use any device, software or routine  \n"
		"   to interfere or attempt to interfere with the proper working of the eBay site  \n"
		"    or any auction being conducted on our site. You may not take any action which  \n"
		"    imposes an unreasonable or disproportionately large load on our infrastructure.  \n"
		"    You may not disclose or share your password to any third parties or use your  \n"
		"    password for any unauthorized purpose.</li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n"
		"  <li><b>Feedback.</b> You may not take any actions which may undermine the integrity  \n"
		"    of the feedback system, such as: leaving positive feedback for yourself using  \n"
		"    secondary User IDs or third parties; leaving negative feedback for other users  \n"
		"    using secondary accounts or third parties (feedback bombing); or leaving negative  \n"
		"    feedback if a user fails to perform some action that is outside the scope  \n"
		"    of the auction (feedback extortion). If you earn a net feedback rating of  \n"
		"    -4 (minus four), your membership will automatically terminate, and you will  \n"
		"    be unable to list or bid.</li> \n"
		"  <p></p> \n"
		"  <p align=left>Because feedback ratings are not designed for any purpose other \n"
		"    than for facilitating trading between eBay users, we may terminate your account  \n"
		"    if you choose to market or promote your eBay feedback rating in any venue  \n"
		"    other than eBay. </p> \n"
		"  <p align=left>  \n"
		"  <li><b>Breach. </b>We may terminate your membership and any of your current  \n"
		"    auctions immediately if you breach this Agreement or if we are unable to verify  \n"
		"    or authenticate any information you provide to us.</li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n"
		"  <li><b>Privacy. </b>Our then-current privacy policies, available at <a href=\""
		<< mpMarketPlace->GetHTMLPath()
		<< "services/registration/privacy-policy.html\">"
		<< mpMarketPlace->GetHTMLPath()
		<< "services/registration/privacy-policy.html</a>,  \n"
		"    are incorporated herein by reference.</li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n";

	*mpStream <<
		"  <li><b>No Warranty.</b> WE AND OUR SUPPLIERS PROVIDE THE EBAY WEBSITE AND OUR  \n"
		"    SERVICES \"AS IS\" AND WITHOUT ANY WARRANTY OR CONDITION, EXPRESS  \n"
		"    OR IMPLIED. WE AND OUR SUPPLIERS SPECIFICALLY DISCLAIM THE IMPLIED WARRANTIES  \n"
		"    OF TITLE, MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT.  \n"
		"    Some states do not allow the disclaimer of implied warranties, so the foregoing  \n"
		"    disclaimer may not apply to you. This warranty gives you specific legal rights  \n"
		"    and you may also have other legal rights which vary from state to state.</li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n"
		"  <li><b>Limit of Liability.</b> IN NO EVENT SHALL WE OR OUR SUPPLIERS BE LIABLE  \n"
		"    FOR LOST PROFITS OR ANY SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES (HOWEVER  \n"
		"    ARISING, INCLUDING NEGLIGENCE) ARISING OUT OF OR IN CONNECTION WITH THIS AGREEMENT.</li> \n"
		"  <p></p> \n"
		"  <p align=left>OUR LIABILITY, AND THE LIABILITY OF OUR SUPPLIERS, TO YOU OR  \n"
		"    ANY THIRD PARTIES IN ANY CIRCUMSTANCE IS LIMITED TO THE GREATER OF (A) THE  \n"
		"    AMOUNT OF FEES YOU PAY TO US IN THE 12 MONTHS PRIOR TO THE ACTION GIVING RISE  \n"
		"    TO LIABILITY, AND (B) $100. Some states do not allow the limitation of liability,  \n"
		"    so the foregoing limitation may not apply to you.<b> </b></p> \n"
		"  <p align=left>  \n"
		"  <li><b>General Compliance with Laws.</b> You shall comply with all applicable  \n"
		"    laws, statutes, ordinances and regulations regarding your use of our service  \n"
		"    and your bidding on, listing, purchase and sale of items.</li> \n"
		"  <p></p> \n";

	*mpStream <<
		"  <p align=left>  \n"
		"  <li><b>No Agency.</b> You and eBay are independent contractors, and no agency,  \n"
		"    partnership, joint venture, employee-employer or franchisor-franchisee relationship  \n"
		"    is intended or created by this Agreement.</li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n"
		"  <li><b>Notices.</b> Except as explicitly stated otherwise, any notices shall  \n"
		"    be given by email to <a href=\"mailto:agree-questions@ebay.com\">agree-questions@ebay.com</a> (in  \n"
		"    the case of eBay) or to the email address you provide to eBay during the registration  \n"
		"    process (in your case), or such other address as the party shall specify.  \n"
		"    Notice shall be deemed given 24 hours after email is sent, unless the sending  \n"
		"    party is notified that the email address is invalid. Alternatively, we may  \n"
		"    give you notice by certified mail, postage prepaid and return receipt requested,  \n"
		"    to the address provided to eBay during the registration process. In such case,  \n"
		"    notice shall be deemed given 3 days after the date of mailing.</li> \n"
		"  <p></p> \n"
		"  <p align=left>  \n"
		"  <li><b>General. </b>This Agreement shall be governed in all respects by the  \n"
		"    laws of the State of California as such laws are applied to agreements entered  \n"
		"    into and to be performed entirely within California between California residents. \n" 
		"    Both parties submit to jurisdiction in California and further agree that any  \n"
		"    cause of action arising under this Agreement shall be brought exclusively  \n"
		"    in a court in San Jose, California. We do not guarantee continuous, uninterrupted  \n"
		"    or secure access to our services, and operation of our site may be interfered  \n"
		"    with by numerous factors outside of our control. If any provision of this  \n"
		"    Agreement is held to be invalid or unenforceable, such provision shall be  \n"
		"    struck and the remaining provisions shall be enforced. Headings are for reference  \n"
		"    purposes only and in no way define, limit, construe or describe the scope  \n"
		"    or extent of such section. Our failure to act with respect to a breach by  \n"
		"    you or others does not waive our right to act with respect to subsequent or  \n"
		"    similar breaches. This Agreement sets forth the entire understanding and agreement  \n"
		"    between us with respect to the subject matter hereof. </li> \n"
		"  <p></p> \n";

	*mpStream << 
		"  <p align=left>  \n"
		"  <li><b>Disclosures. </b>The services hereunder are offered by eBay, Inc., located  \n"
		"    at 2005 Hamilton Ave., Suite 350, San Jose, CA 95125.  \n"
		"	If you are a California resident, you may have this same information  \n"
		"    emailed to you by sending a letter to the foregoing address with your email  \n"
		"    address and a request for this information. Fees for our services are described  \n"
		"    at <a href=\""
	<<  mpMarketPlace->GetHTMLPath()
	<<  "help/sellerguide/selling-fees.html\">"
	<<  mpMarketPlace->GetHTMLPath()
	<<  "help/sellerguide/selling-fees.html</a>.</li> \n"
		"</ol> \n"
		"<p align=left>The Complaint Assistance Unit of the Division of Consumer Services  \n"
		"  of the Department of Consumer Affairs may be contacted in writing at 400 R Street,  \n"
		"  Sacramento, CA 95814, or by telephone at (800) 952-5210.</p> \n";
}

void clseBayApp::ProduceUserAgreementFormAfterAction() 
{
	*mpStream <<
		"  <p>  \n"
		"    <input type=\"checkbox\" name=\"notify\" value=1> \n"
		"    Please send me an email update if this agreement is ever amended. Otherwise,  \n"
		"    I understand that amendments will be effective when posted on the site for  \n"
		"    30 days.</p> \n"
		"  <table border=0 width=75%> \n"
		"    <tr>  \n"
		"      <td width=23%>  \n"
		"        <input type=\"submit\" name=\"accept\" value=\"I Accept\"> \n"
		"      </td> \n"
		"      <td width=77%>  \n"
		"        <input type=\"submit\" name=\"decline\" value=\"I Decline\"> \n"
		"      </td> \n"
		"    </tr> \n"
		"  </table> \n"
		"  </form> \n";
}

//
// Helper function to produce the HTML for the FAQ
//
void clseBayApp::ProduceUserAgreementFAQ() {
	*mpStream <<

	"<h2>User Agreement Frequently Asked Questions</h2>\n"
	"<P ALIGN=CENTER><b>New User Agreement for you!</b></P>\n"
	"</B> \n"
	"<P ALIGN=left>We are committed to improving your experience at eBay. Based \n"
	"  on suggestions from many of our users, we decided to revise the User Agreement. \n"
	"  This agreement clarifies and improves your rights and responsibilities while \n"
	"  bidding and selling at eBay.<B> </B></P>\n"
	"<B>\n"
	"<P ALIGN=CENTER>How do I benefit from this new agreement?</P>\n"
	"</B> \n"
	"<P ALIGN=left>This agreement addresses important issues like spam, feedback \n"
	"  and shill bidding. This agreement also eliminates that page congestion on the \n"
	"  bidding and listing pages. You wont have to read the agreement every time you \n"
	"  bid or sell! You only have to read and accept this agreement once.<B></B></P>\n"
	"<B>\n"
	"<P ALIGN=CENTER>Will the User Agreement change again?</P>\n"
	"</B> \n"
	"<P ALIGN=left>We hope not. But if we need to make major adjustments we will \n"
	" post the changes on the site for you to see 30 days prior to its effectiveness. \n"
	"  You also have the option to receive an email notice of changes should you prefer. \n"
	"  See our <a href=\""
	<< mpMarketPlace->GetHTMLPath(PageOptinLogin)
	<< "services/myebay/optin-login.html\">notification preferences page</a>, where \n"
	"  you can opt-in or opt-out of receiving these notifications once you are a registered \n"
	"  user.</P>\n"
	"<B>\n"
	"<P ALIGN=CENTER>What do I do now?</P>\n"
	"</B> \n"
	"<P ALIGN=left>To accept, simply read the new User Agreement and click &quot;I \n"
	"  Accept.&quot; Your account will reflect that you have accepted the User Agreement \n"
	"  and you will not see the Agreement again.</P>\n"
	"<P ALIGN=left>If you do not wish to accept the new User Agreement, click \n"
	"  &quot;I Decline.&quot; If you have any current auctions, these will continue \n"
	"  normally. However, you will not be able to access or create any new account \n"
	"  transactions.<B> </B></P>\n"
	"<B>\n"
	"<P ALIGN=CENTER>ATTENTION AUTOMATED SOFTWARE USERS!</P>\n"
	"</B> \n"
	"<P ALIGN=left>Although we do not sponsor third party automated bidding software, \n"
	"  we do recognize that many of you use such software. You may experience some \n"
	"  problems with your third party software when we implement the new user agreement \n"
	"  into the bid and list pages in late July, 1998. You may be able to avoid this \n"
	"  problem by agreeing to the User Agreement before then. Since we care about your \n"
	"  experience at eBay, we hope this notice and solution will ease any problems \n"
	"  you may encounter.</P>\n";
}


