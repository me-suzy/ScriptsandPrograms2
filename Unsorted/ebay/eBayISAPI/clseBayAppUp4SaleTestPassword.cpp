/* $Id: clseBayAppUp4SaleTestPassword.cpp,v 1.2 1999/02/21 02:32:54 josh Exp $ */
/*
 * clseBayAdultLogin -- login to view adult pages.
 */

#include "ebihdr.h"
#include "clsUtilities.h"
#include "clsBase64.h" 


void clseBayApp::Up4SaleTestPassword(char *pUserId,
									 char *pPassword)
{
	SetUp();

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, NULL);

	if (!mpUser)
    {
		*mpStream <<	"valid=0";
	}
	else
	{
		*mpStream <<	"valid=1";
	}

    CleanUp();
}
