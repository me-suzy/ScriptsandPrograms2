/*	$Id: clsAgreementNotifierApp.cpp,v 1.5.248.1 1999/08/01 02:51:06 barry Exp $	*/
//
//	Modifications:
//		07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "eBayDebug.h"
#include "eBayTypes.h"

#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsAgreementNotifierApp.h"

#include "clsUsers.h"
#include "clsUser.h"

#include "clsUtilities.h"
#include "clsMail.h"

#include "iterator.h"

#include <sys/types.h>
#include <sys/stat.h>

#include <stdio.h>
#include <errno.h>
#include <time.h>

//static const char *LetterBody =
//"User agreement Update Notifier    ";

clsAgreementNotifierApp::clsAgreementNotifierApp()
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	
	return;
}

clsAgreementNotifierApp::~clsAgreementNotifierApp()
{
}

void clsAgreementNotifierApp::Run() 
{

	// This is the vector of ids who've signed notify
	vector<int>		vUsers;
	vector<int>::iterator	vI;

	time_t			nowTime;  
	time_t			sendTime;
  
	FILE			*pLogFile;
	FILE			*pLetterFile;

	int			letter_size;
	struct	stat st;

	// Mailer
	clsMail							*pMail;
	ostrstream						*pM;
	char							subject[512];
	int							mailRc = 0;

	clsUser* pUser;

	// Initialize
	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();
	mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	mpUsers	= mpMarketPlace->GetUsers();
	mpUsers->GetActiveUsers(&vUsers);

	// time
	time( &nowTime );

	// Read letter body fron AgreementLetter.txt
	//open letter body
	pLetterFile	= fopen("AgreementLetter.txt", "r");
	if (!pLetterFile)
	{
		fprintf(stderr, "cannot open AgreementLetter.txt\n");
		return;
	}
	
	// get the  file size
	stat("AgreementLetter.txt", &st);
	letter_size = st.st_size + 1;

	char* pLetterBody = new char[letter_size];

    fread(pLetterBody, sizeof(char), letter_size, pLetterFile);
	fclose(pLetterFile);
	pLetterBody[letter_size-1] = '\0';

	pLogFile	= fopen("NotifyAgreementlog.txt", "w+");

	if (!pLogFile)
	{
		fprintf(stderr, "cannot create log file\n");
		// cleanup?
		return;
	}

	fprintf(pLogFile, "NotifyAgreement log file on: %s\n", ctime(&nowTime) );

	// Now, we loop through all active users who signed the notify
	for (vI = vUsers.begin();
		 vI	!= vUsers.end();
		 vI++)
	{
		time( &sendTime );
		pUser	= mpUsers->GetUser((*vI));
printf("%d\n", *vI);
		if (!pUser)
		{
			printf("** Error ** Can not get user %d\n", (*vI));
			continue;
		}
//for testing
//if (strcmp (pUser->GetEmail(), "vicki@ebay.com") ==0)
//{
		//mailer
		pMail	= new clsMail();
		pM	= pMail->OpenStream();

		//mail it
		//
		// Make a nice in-memory stream
		sprintf(subject,
			"%s User Agreement Revision Notice ",
			 mpMarketPlace->GetCurrentPartnerName());

		// send the letterletter body is a file
		*pM <<	"To Registered eBay User "
		   <<	pUser->GetUserId()
		   <<	":\n\n"
		   <<	pLetterBody
		   <<	ends;

		mailRc = 1;
		mailRc = pMail->Send(pUser->GetEmail(), 
				(char *)mpMarketPlace->GetConfirmEmail(),
				subject);
	

		delete pMail;

		if (!mailRc)
		{
			fprintf(pLogFile,
				"ERROR for sending mail to: %d  %s, %s", 
				(*vI), pUser->GetEmail(), ctime(&sendTime));
		
		}
		else
		{
			//log it
			fprintf(pLogFile,
				"%d  %s, %s", 
				(*vI), pUser->GetEmail(), ctime(&sendTime));
		}

		delete pUser;
//} //testing end
	}
	
//	delete	[] pLetterBody;

	vUsers.erase(vUsers.begin(), vUsers.end());

	fclose(pLogFile);
}


void main()
{
    clsAgreementNotifierApp app;
    app.Run();
}


