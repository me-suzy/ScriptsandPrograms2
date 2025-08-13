/*	$Id: clsUserCodes.cpp,v 1.4 1998/12/06 05:32:25 josh Exp $	*/
//
//	File:	clsUserCodes.cpp
//
//	Class:	clsUserCodes
//
//	Author:	Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 11/20/97 Craig Huang - Created
//
#include "eBayKernel.h"
#include <stdio.h>

clsUserCodes::clsUserCodes() 
{
	mpUserCodeVector = NULL;
}

clsUserCodes::~clsUserCodes()
{
	// And an iterator for vector
	vector<clsUserCode *>::iterator	i;

	if(mpUserCodeVector)
	{
		// Clean up
		for (i = mpUserCodeVector->begin();
			 i != mpUserCodeVector->end();
			 i++)
		{
			delete	(*i);
		}
		mpUserCodeVector->erase(mpUserCodeVector->begin(), mpUserCodeVector->end());
	};
}


UserCodeVector	*clsUserCodes::GetUserCodeVector()
{
	if(! mpUserCodeVector )
	{
		mpUserCodeVector= new UserCodeVector;
		// mpUserCodeVector->reserve(200);
		mpUserCodeVector->reserve(20);

		// Call the database api to get the user codes
		gApp->GetDatabase()->GetUserCodeVector(mpUserCodeVector);
		return mpUserCodeVector;		
	}
	else		
		return mpUserCodeVector ;
}