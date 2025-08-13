/*	$Id: clsVoidSuspendedFeedback.cpp,v 1.2 1999/02/21 02:25:08 josh Exp $	*/
//
//	File:	clsVoidSuspendedFeedback.cpp
//
//	Class:	clsVoidSuspendedFeedbackApp
//
//	Author:	Chad Musick (chad@ebay.com)
//          10/2/97
//
//	Function: Void the value of any feedback
//            left by suspended users
//
//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsVoidSuspendedFeedback.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAccount.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>


clsVoidSuspendedFeedbackApp::clsVoidSuspendedFeedbackApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsVoidSuspendedFeedbackApp::~clsVoidSuspendedFeedbackApp()
{
	return;
}


void clsVoidSuspendedFeedbackApp::Run()
{
	// This is the vector of userids who've been
	// suspended
	vector<int>						vUsers;
	vector<int>::iterator			i;


	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	// First, let's get the items
	gApp->GetDatabase()->GetSuspendedUsers(&vUsers);

	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		mpDatabase->VoidFeedbackLeftByUser(*i);
	}
	
	vUsers.erase(vUsers.begin(), vUsers.end());
	return;
}

static clsVoidSuspendedFeedbackApp *pTestApp = NULL;

int main()
{

	if (!pTestApp)
	{
		pTestApp	= new clsVoidSuspendedFeedbackApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
