/*	$Id: clsUnconfirmedNotifierApp.cpp,v 1.1.2.4.2.1.96.1 1999/08/01 02:51:20 barry Exp $	*/
//
//	File:	clsUnconfirmedNotifierApp.cpp
//
//	Class:	clsUnconfirmedNotifierApp
//
//	Author:	Josh Gordon Wilson (josh@ebay.com)
//
//	Function:
//
//	This thing sends mail to users who haven't confirmed
//	their eBay registrations. It avoids spamming users by keeping
//	a file of those we've sent to, and eliminating them before
//	starting it's run.
//
//	One EVIL little thing. The letter we send can / is in the form
//	of a printf string, for substituting things in the email. 
//
// Modifications:
//	11/12 1998 -- Implemented as specified by Buffy. 
//				There are a few experiments in this code;
//				besides implementing the desired functionality,
//				it presents some interesting food for code review discussion, as
//				well as some modern uses of STL and streams.
//	- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#define TESTING

#include <fstream.h>
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUnconfirmedNotifierApp.h"
#include "clsMail.h"

#include <sys/stat.h>

extern "C"
{
#include "crypt.h"
}

clsUnconfirmedNotifierApp::clsUnconfirmedNotifierApp()
{
	mpDatabase		= NULL;
	mpMarketPlaces	= NULL;
	mpMarketPlace	= NULL;
	mpUsers			= NULL;
	
	return;
}

clsUnconfirmedNotifierApp::~clsUnconfirmedNotifierApp()
{
	mpDatabase		= NULL;
	mpMarketPlaces	= NULL;
	mpMarketPlace	= NULL;
	mpUsers			= NULL;

}

void usage(void)
{
	cerr << "usage: UnconfirmedNotifier [flags] count\n"
		"    where count is the number of messages to send\n"
		"    and flags include:\n"
		"      -f                forward (default is reverse chronological order)\n"
		"      -d nn             nn is the number of days to skip (default is 21 days)\n"
		"      -b filename       file to use for boilerplate text, default is boilerplate.txt\n"
		;
}



void clsUnconfirmedNotifierApp::Run(int argc, char **argv)
{

	vector<int>				vUsers;		// all unconfirmed users
	vector<int>				vSentUsers;	// the ones we've already mailed
	vector<int> 			*newbies;	// the ones actually to be sent mail

	time_t					nowTime;  
	time_t					sendTime;
  
	char					*pLetterBoilerPlate;
	char					*pLetterBody;

	// Mailer
	ostrstream				*pM;
	char					subject[512];
	int						mailRc;

	clsUser					*pUser;
	int i;

	// Initialize
	mpDatabase			= GetDatabase();
	mpMarketPlaces		= GetMarketPlaces();
	mpMarketPlace		= mpMarketPlaces->GetCurrentMarketPlace();
	mpUsers				= mpMarketPlace->GetUsers();

	// Parse the command arguments.
	extern char *optarg;
	extern int optind;
	int c;
	bool bReverse = true;
	int days = 21;
	char *boilerplate = "boilerplate.txt";
	int numberToSend = 0;

	while ((c = getopt(argc, argv, "fd:b:")) != EOF)
	{
		switch(c)
		{
		case 'f':
			bReverse = false;
			break;
		case 'd':
			days = atoi(optarg);
			break;
		case 'b':
			boilerplate = optarg;
			break;
		case '?':
		default:
			usage();
			return;
		}
	}

	// Now there should be nothing left but the count.
	if (optind == argc)
	{
		usage();
		return;
	}

	numberToSend = atoi(argv[optind]);

	if (numberToSend == 0 || ++optind < argc)	// stuff left over
	{
		usage();
		return;
	}
			
	// Let's get a vector of those unconfirmed users.
	mpUsers->GetUnconfirmedUsers(vUsers, days);
	if (vUsers.empty())
	{
		cerr << "There were no unconfirmed users to process.\n";
		return;
	}
	sort(vUsers.begin(), vUsers.end(), less<int>());
	newbies = &vUsers;

	ifstream ifile( "sent.txt", ios::in | ios::nocreate );
	if (ifile.fail())
		perror("sent.txt");
	else
	{
		// OK, we have the file. Now use an input iterator to suck it in! If we knew for sure the
		// file was nothing but numbers, the entire while loop would be replaced by nothing but the
		// copy slurp.
		//
		while(1)
		{
			copy(istream_iterator<int>(ifile), istream_iterator<int>(), back_inserter(vSentUsers));
			int n = ifile.rdstate();
			if (n & ios::eofbit)	// EOF
				break;
			if (n & ios::failbit)	//  Probably have a line of trash.
			{
				ifile.clear(0);		// Clear the error bit...
				ifile.ignore(INT_MAX, '\n');	// Ignore this line.
				continue;
			}
			cerr << "Some unexpected error occurred: "
				<< n
				<< endl;

			return;		// failure
		}
		//
		ifile.close();

		// OK. We've got the sent ones and the new ones. Zap the already sent ones from the list.
		// We need both sequences sorted for this.

		if (!vSentUsers.empty())
		{
			sort(vSentUsers.begin(), vSentUsers.end(), less<int>());
			newbies = new vector<int>;
			set_difference(vUsers.begin(), vUsers.end(), vSentUsers.begin(), vSentUsers.end(), 
				back_inserter(*newbies));
		}
	}

	if (newbies->empty())
	{
		cerr << "There are no new unconfirmed users to process" << endl;
		return;
	}

	// Maybe reverse the order...
	if (bReverse)
		reverse(newbies->begin(), newbies->end());

	// time
	time( &nowTime );

	ifstream boilerfile( boilerplate, ios::in | ios::nocreate );
	if (boilerfile.fail())
	{
		perror(boilerplate);
		return;
	}
	struct stat sbuf;
	fstat(boilerfile.rdbuf()->fd(), &sbuf);
	int letter_size = sbuf.st_size + 1;

	pLetterBoilerPlate	= new char[letter_size];
	boilerfile.read(pLetterBoilerPlate, letter_size - 1);
	boilerfile.close();
	pLetterBoilerPlate[letter_size - 1] = '\0';


	ofstream sentFile("sent.txt", ios::app);
	if (!sentFile)
	{
		perror("sent.txt");
		return;
	}

	ofstream logFile("confirm.log", ios::app);
	if (!logFile)
	{
		perror("confirm.log");
		return;
	}

	srand((unsigned)time(NULL));

	// Now, we loop through all active users who aren't confirmed
	for (i = 0; i < numberToSend && i < newbies->size(); i++)
	{
		time( &sendTime );

		// Let's get the user so we can get some goodies
		// out of thier information/
		int userId = (*newbies)[i];
		pUser	= mpUsers->GetUser(userId);

		if (!pUser)
		{
			logFile << "** Error ** Can not get user "
			<< userId
			<< endl;
			continue;
		}


		// Make up a temporary password for this user.
		char    cPassword[16];
		char cSalt[16];

		int password = rand();
		int salt = rand();

		sprintf(cPassword, "%d", password);
		sprintf(cSalt, "%d", salt);

		//mailer
#ifndef TESTING
		clsMail mail;
		pM		= mail.OpenStream();
#endif

		//
		// The actual letter text is produced by sprintfing the
		// boilerplate into memory, including fun things from the
		// user's record.
		//
		pLetterBody	= new char[letter_size	+
							   strlen(pUser->GetEmail()) + 
							   1];

		sprintf(pLetterBody, pLetterBoilerPlate,
				pUser->GetEmail(), password);

		sprintf(subject,
			"%s Registration Confirmation Notice ",
			 mpMarketPlace->GetCurrentPartnerName());

		// send the letterletter body is a file
#ifndef TESTING
		*pM 
#else
		cout
#endif
			<<	"To Registered eBay User "
			<<	pUser->GetUserId()
			<<	":"
			<<	pLetterBody
			<<	endl;

#ifndef TESTING
		mailRc = mail.Send("josh@ebay.com", //pUser->GetEmail(),
				const_cast<char *>(mpMarketPlace->GetConfirmEmail()),
				subject);
#else
		mailRc = 1;
		cout << "Pretending to send mail to "
			<< pUser->GetEmail()
			<< ","
			<< mpMarketPlace->GetConfirmEmail()
			<< ", subject: "
			<< subject
			<< endl;
#endif
	

		if (!mailRc)
		{
			// Whine...
			logFile << 
				"ERROR for sending mail to: "
				<< userId
				<< ", "
				<< pUser->GetEmail()
				<< " "
				<< ctime(&sendTime);	// ctime has its own \n
		
		}
		else
		{
			// Log the results...
			logFile << userId
				<< " "
				<< pUser->GetEmail()
				<< ", "
				<< ctime(&sendTime);	// ctime has its own \n

			// and mark this guy as sent...
			sentFile << userId << endl;

			// and record this guys new password; he'll need it!
			// Let's crypt the password
			char *pCryptedPassword = crypt(cPassword, cSalt);
			pUser->SetSalt(cSalt);
			pUser->SetPassword(pCryptedPassword);
			pUser->UpdateUser();
			free(pCryptedPassword);


		}

		delete pUser;
		delete[] pLetterBody;
	}
	
	logFile.close();
	sentFile.close();

	cout << vUsers.size() << " total unconfirmed users\n"
		<< i << " mails sent\n"
		<< vSentUsers.size() + i 
		<< " processed to date.\n";
}


void main(int argc, char **argv)
{
    clsUnconfirmedNotifierApp().Run(argc, argv);
}


