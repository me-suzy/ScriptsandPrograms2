/*	$Id: clseBayAppWackoFlagChangeConfirm.cpp,v 1.3 1998/12/06 05:25:43 josh Exp $	*/
//
//	File:		clseBayAppWackoFlagChangeConfirm.cpp
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
//

#include "ebihdr.h"
#include "clseBayItemDetailWidget.h"


void clseBayApp::WackoFlagChangeConfirm(CEBayISAPIExtension *pServer,
							 char *pItemNo,
							 bool wackoFlag,
							 eBayISAPIAuthEnum authLevel)
{
	// Item details
	clseBayItemDetailWidget *idw;
	SetUp();

   	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Let's get the item
    if (!GetAndCheckItem(pItemNo))
	{
	    *mpStream <<	"ERROR MESSAGE";
	    CleanUp();
		return;
	}

	//let's get header first
	*mpStream <<	mpMarketPlace->GetHeader();

	// Use clseBayItemDetailWidget to show auction properties
	idw = new clseBayItemDetailWidget(mpMarketPlace);
	idw->SetItem(mpItem);
	idw->SetShowTitleBar(false);
	idw->SetShowDescription(false);
	idw->SetColor("#99CCCC");
	idw->SetMode(clseBayItemDetailWidget::Generic);
	idw->EmitHTML(mpStream);
	delete idw;


	//display current status:
	if(mpItem->IsItemWacko())
	{
		if (!wackoFlag)
		{
			//this item is wacko and was asked to be unset
			*mpStream	<< 	"<font size=5 color=RED>"
						<< "Currently, this item IS a WACKO item."
						<< 	"</font> <BR>"
						<< 	"<font size=5>"
						<< "You have requested to change this item from "
						<< 	"</font>"
						<< 	"<font size=5 color=RED>"
						<<  " WACKO </font>"
						<<	"<font size=5> to </font>";
			*mpStream	<<  "<font size=5 color=GREEN>NOT Wacko</font>."
						<< 	"<BR><font size=5>"
						<<	"Press Confirm to update this item"
						<< 	"</font><BR>";

			*mpStream	<<  "<form method=get action="
							"\""
						<<	"eBayISAPI.dll"
							"\""
							">"
							"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"WackoFlagChange\">"
							"\n"
							"<input type=hidden name=item value=\""
						<<	pItemNo
						<<	"\">\n"
							"<input type=hidden name=wackoFlag value=\""
						<<	wackoFlag
						<<	"\">\n";

			
			*mpStream	<< "<p><input type=\"submit\" value=\"Confirm\"></p>\n"
    					<< "</form>\n";
		}
		else
		{
			//this item is wacko and was asked to be Set to Wacko; makes no sence!
			*mpStream	<<  "<font size=5> Item already has WACKO flag turned ON!"
						<< 	"</font><BR>";
		}
	}
	else
	//item is not set to wacko in the database
	{

		if (wackoFlag)
		{
			//this item is NOT wacko and was asked to be Set
			*mpStream	<< 	"<font size=5 color=GREEN>"
						<< "Currently, this item is NOT a WACKO item."
						<< 	"</font> <BR>"
						<< 	"<font size=5>"
						<<  "You have requested to change this item from "
						<< 	"</font>"
						<< 	"<font size=5 color=GREEN>"
						<<  " NOT Wacko </font>"
						<<	"<font size=5> to </font>";
			*mpStream	<<  "<font size=5 color=RED> Wacko</font>."
						<< 	"<BR><font size=5>"
						<<	"Press Confirm to update this item"
						<< 	"</font><BR>";

		 	*mpStream	<<  "<form method=get action="
							"\""
						<<	"eBayISAPI.dll"
							"\""
							">"
							"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"WackoFlagChange\">"
							"\n"
							"<input type=hidden name=item value=\""
						<<	pItemNo
						<<	"\">\n"
							"<input type=hidden name=wackoFlag value=\""
						<<	wackoFlag
						<<	"\">\n";

			*mpStream	<< "<p><input type=\"submit\" value=\"Confirm\"></p>\n"
    					<< "</form>\n"; 
		}
		else
		{
			//this item is wacko and was asked to be Set to Wacko; makes no sence!
			*mpStream	<<	"<font size=5> Item already has WACKO flag turned OFF!"
						<< 	"</font><BR>";
		}
		
	}

	//use boolean to figure out status;


	 *mpStream	  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	CleanUp();
	return;
}