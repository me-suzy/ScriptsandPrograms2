/*	$Id: clseBayAppPSNetMindTC.cpp,v 1.2.2.4.20.1 1999/08/05 18:58:59 nsacco Exp $	*/
//
//	File:	clseBayAppPSNetMindTC.cpp
//
//	Class:	clseBayApp
//
//	Author:	Wen Wen
//
//	Function:
//
//		Contains the method to display NetMind terms and conditions
//
// Modifications:
//				- 02/03/99 wen		- Created
//

#include "ebihdr.h"
#include "clsNamevalue.h"
#include "clsPSHeaderWidget.h"

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
const char MSG_TERMS[] =
"<p>Because this is the first time you are accessing Personal Shopper, "
"we would like to give you a little background about this feature. "
"After you review and accept this page, you will no longer need to see "
"this page when you use Personal Shopper."
"<p>We are working with NetMind Technologies, Inc. to provide you the Personal Shopper feature. "
"When you use Personal Shopper, you will send your email address and search "
"criteria information to NetMind. NetMind will use your search criteria to "
"find what you are looking for on eBay and will email the results to you "
"via our Personal Shopper."
"<p>NetMind will not knowingly reveal your email address to any third party. "
"To read NetMind's complete Terms & Conditions, please click "
"<a href=\"http://pages.ebay.com/search/ps/netmindtc.html\">here</a>."
"<p>In order to use Personal Shopper, you must accept NetMind's Terms & Conditions. "
"Do you accept NetMinds Terms & Conditions?";
*/

void clseBayApp::DisplayPSNetMindTC(char *pAction,
							 int Pairs, 
							 clsNameValuePair* pNameValue)
{
	int i;
	clsPSHeaderWidget	PSHeaderWidget(mpMarketPlace);

	PSHeaderWidget.EmitHTML(mpStream, "NetMind Terms & Conditions");

	// emit prompt
	//  *mpStream <<	MSG_TERMS;

	// kakiyama 07/09/99

	*mpStream << clsIntlResource::GetFResString(-1,
											"<p>Because this is the first time you are accessing Personal Shopper, "
											"we would like to give you a little background about this feature. "
											"After you review and accept this page, you will no longer need to see "
											"this page when you use Personal Shopper."
											"<p>We are working with NetMind Technologies, Inc. to provide you the Personal Shopper feature. "
											"When you use Personal Shopper, you will send your email address and search "
											"criteria information to NetMind. NetMind will use your search criteria to "
											"find what you are looking for on eBay and will email the results to you "
											"via our Personal Shopper."
											"<p>NetMind will not knowingly reveal your email address to any third party. "
											"To read NetMind's complete Terms & Conditions, please click "
											"<a href=\"%{1:GetHTMLPath}/search/ps/netmindtc.html\">here</a>."
											"<p>In order to use Personal Shopper, you must accept NetMind's Terms & Conditions. "
											"Do you accept NetMinds Terms & Conditions?",
											clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
											NULL);


	// form
	*mpStream	<<	"<p><table boarder=0><tr><td>"
					"<form method=\"POST\" action=\""
				<<	pAction
				<<	"\">\n";

	// print out the hidden name value pairs
	for (i = 0; i < Pairs; i++)
	{
		*mpStream << "<input type=\"hidden\" name=\""
				  << pNameValue[i].GetName()
				  << "\" value=\""
				  << pNameValue[i].GetValue()
				  << "\">\n";
	}

	// print out the agree button
	*mpStream	<<	"<input type=\"hidden\" name=\"agree\" value=\"y\">"
					"<p>&nbsp;&nbsp;&nbsp;"
					"<input type=\"submit\" value=\"Yes\">"
					"</form></td>\n";

	// print out the decline button
	*mpStream	<<	"<td><form method=\"get\" action=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"search/ps/psdecline.html\">\n"
					"<input type=\"submit\" value=\"No\">\n"
					"</form></td></tr></table>\n";


	return;
}
