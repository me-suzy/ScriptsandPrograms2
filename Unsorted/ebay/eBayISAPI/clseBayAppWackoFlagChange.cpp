/*	$Id: clseBayAppWackoFlagChange.cpp,v 1.3 1998/12/06 05:25:42 josh Exp $	*/
//
//	File:		clseBayAppWackoFlagChange.cpp
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


void clseBayApp::WackoFlagChange(CEBayISAPIExtension *pServer,
							 char *pItemNo,
							 bool wackoFlag)
{
	clsItem	*pItem;

	SetUp();

	//let's get header first
	*mpStream <<	mpMarketPlace->GetHeader();

	//update item wacko flag
	// Let's get the item; I need to populate market place and id 
	pItem= new clsItem;
	pItem->SetId(atoi(pItemNo));
	//market place is set to 0 in constructor;
	//mStatus is set to 0 in constructor;

	pItem->SetItemWackoFlag((wackoFlag));

    //let them know that it worked
	*mpStream <<	"<p>"
			  <<	"<font color=red size=+1>"
			  <<	"The Wacko on the Item \""
			  <<	pItemNo
			  <<	"\" has been updated.</font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();
	*mpStream <<	flush;

	CleanUp();
	return;
}

   