/*	$Id: smsmtp.cpp,v 1.12 1999/04/28 05:35:22 josh Exp $	*/
#include <ctype.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "tcpstuff.h"
#include "smsmtp.h"
#include "clsApp.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsMailControl.h"
#ifdef _MSC_VER
#include <winsock.h>
#else
#include <unistd.h>
#endif


//
// MAIL POOL
//
#ifdef _MSC_VER

// mail pool for NT/CGI boxes
char *sMailmachines[] = {
	  "boa.ebay.com",         // 166 MHz FreeBSD 2.2.5
	  "diamondback.ebay.com", // 200 MHz FreeBSD 2.2.5
	  "skink.ebay.com",       // 166 MHz FreeBSD 2.2.5
	  "moccasin.ebay.com",     // 200 MHz FreeBSD 2.2.6
	  "slowworm.ebay.com"
};

#else

// mail pool for UNIX batch jobs
char *sMailmachines[] = {
	  "turtle.ebay.com", // 200 MHz FreeBSD 2.2.5
	  "viper.ebay.com",   // 200 MHz FreeBSD 2.2.5
	  "collaris.ebay.com",
	  "gharial.ebay.com"
};

#endif // end of mail pool

char **smtp::mailmachines = sMailmachines;
int smtp::nummachines = sizeof (sMailmachines) / sizeof (char *);

char *sMailmachines_Reg[] = {
/*	"turtle.ebay.com"
*/
	"pelagic.ebay.com",
	"hognose.ebay.com"

}; 

int smtp::nummachines_Reg = sizeof (sMailmachines_Reg) / sizeof (char *);

char *sMailmachines_Help[] = {
	"kuhli.ebay.com"
}; 

int smtp::nummachines_Help = sizeof (sMailmachines_Help) / sizeof (char *);


#ifdef _MSC_VER
static bool getmailmachine(MailPoolTypeEnum poolType,
						  clsMarketPlace *pMarketPlace,
						  std::string& machineAddress);
#else
static bool getmailmachine(MailPoolTypeEnum poolType,
						  clsMarketPlace *pMarketPlace,
						  string& machineAddress);
#endif


// function:
// 		int checkstring(char *s)
// description:
//		checks a string <s> on character codes under 32
// returns:
// 		1 if string contains a character code under 32
//		0 otherwise

int
checkstring(const char *s)
{
	while (*s)
	{
		if (*s < 32)
			return 1;
		s++;
	}
	return 0;
}

// function:
// 		int waitforstatus(int socket)
// description:
// 		waits for a smtp status on socket <socket>,
// returns:
// 		a smtp status, or -1 if the socket was closed

int
waitforstatus(int socket)
{
	char recvBuffer[200];

	do {
		getline(socket,recvBuffer);
#ifdef DEBUG_SMTP
		fprintf(stderr, "Received response: \"%s\\r\\n\"\n", recvBuffer);
#endif
	} while (strlen(recvBuffer) > 3 && recvBuffer[3] == '-');
	return atoi(recvBuffer);
}

// function:
// 		smtp::smtp()
// description:
//		constructor for the smtp class
//		initializes all variables
//		allocates memory
// returns:
//		nothing

smtp::smtp()
{
	message = new char[S_MESSAGE_LEN];
	header = NULL;
	recipients = NULL;
	strcpy(sender, "");
	strcpy(subject, "");
	strcpy(message, "");
	linebreak = STANDARD_LINEBREAK;
}

// function:
//		void smtp::setmailmachine(const char *mach)
// description:
//		sets the mail machine for the current smtp class instance
// returns:
//		nothing

void
smtp::setmailmachine(const char *mach)
{
	if (mach == NULL)
		;
}

#ifdef _MSC_VER
static bool getmailmachine(MailPoolTypeEnum poolType,
						  clsMarketPlace *pMarketPlace,
						  std::string& machineAddress)
#else
static bool getmailmachine(MailPoolTypeEnum poolType,
						  clsMarketPlace *pMarketPlace,
						  string& machineAddress)
#endif
{
#ifdef _MSC_VER
	bool foundMachine = false;

	int machineCount = pMarketPlace->GetMailControl()->GetMailMachineCount(poolType);

	if (machineCount)
	{
		int machineIndex = rand() % machineCount;

		foundMachine = pMarketPlace->GetMailControl()->GetMailMachine(poolType, machineIndex, machineAddress);
	}

	if (foundMachine)
		return true;
#else
	int machineCount = 0;
#endif

	char** pMachineArray = NULL;

	switch (poolType)
	{
	case pool_general:
		pMachineArray = sMailmachines;
		machineCount = smtp::nummachines;
		break;
	case pool_registration:
		pMachineArray = sMailmachines_Reg;
		machineCount = smtp::nummachines_Reg;
		break;
	case pool_help:
		pMachineArray = sMailmachines_Help;
		machineCount = smtp::nummachines_Help;
		break;
	default:
		return false;
	}

	int machineIndex = rand() % machineCount;

	machineAddress = pMachineArray[machineIndex];

	return true;
}


// function:
//		int smtp::sendmail()
// description:
//		sends the mail 
// returns:
//		1 if all went well
//		0 if mail was not correctly sent

int
smtp::sendmail(int machine_type)
{
//	const char *machine;
	int status, s;
	int sent = 0;
	int n;
//	int machineidx;
//	char** pMachineArray;
//	int  num_machine;

	clsMarketPlace *pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();

#if 0
/*	if (machine_type == 1 ) // registration
	{
		pMachineArray = sMailmachines_Reg;
		num_machine = nummachines_Reg;
	}
	else if (machine_type == 2 ) // help
	{
		pMachineArray = sMailmachines_Help;
		num_machine = nummachines_Help;
	}
	else
	{
		pMachineArray = sMailmachines;
		num_machine = nummachines;
	}

	clsMarketPlace *pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	machineidx = pMarketPlace->GetMailMachineIndex(machine_type);
	if (machineidx >= num_machine)		// sanity check
		machineidx = 0;

	if (header == NULL)
	{
		if (checkstring(sender))
			return 0;
		if (checkstring(subject))
			return 0;
	}
	if (recipients == NULL)
		return 0;
	
	for (n = 0, s = -1; s < 0;)
	{
		machine = pMachineArray[machineidx];
		machineidx++;
		if (machineidx >= num_machine)
		{
			machineidx = 0;
			if (++n >= MAX_RETRIES)
			{
				pMarketPlace->SetMailMachineIndex(machine_type,
								  machineidx);
				return 0;
			}
		}

#ifdef DEBUG_SMTP
		fprintf(stderr, "Connecting to %s\n", machine);
#endif
		s = init_connection(machine, "smtp", 5);	// Try to connect for 5 seconds
	}
*/
#endif

#ifdef _MSC_VER
	std::string mailMachine;
#else
	string mailMachine;
#endif
	MailPoolTypeEnum poolType = pool_general;

	if (machine_type == 1)
		poolType = pool_registration;
	else if (machine_type == 2)
		poolType = pool_help;
	
	for (n = 0, s = -1; s < 0;)
	{
		bool getMailResult = getmailmachine(poolType, pMarketPlace, mailMachine);

		if (!getMailResult)
			return 0;

#ifdef DEBUG_SMTP
		fprintf(stderr, "Connecting to %s\n", mailMachine.c_str());
#endif
		s = init_connection(mailMachine.c_str(), "smtp", 5);	// Try to connect for 5 seconds
	}

//	pMarketPlace->SetMailMachineIndex(machine_type, machineidx);

	do
	{
		char domainname[100];
		char machinename[100];
#ifdef _MSC_VER
		DWORD namelen = 99;
#endif

		// check if smtp server is there
		status = waitforstatus(s);
		if (status != 220) 
			break;
		
		// send HELO and wait for response
		getmachinename(machinename);
#ifdef DEBUG_SMTP
		fprintf(stderr, "Sending: \"HELO %s\\r\\n\"\n", machinename);
#endif
		sendstring(s, 0, "HELO %s\r\n", machinename);

		status = waitforstatus(s);
		if (status != 250) 
			break;
		
		// send MAIL FROM and wait for response
		if (sender[0] == 0)
		{
			char username[100];

			getdomainname(domainname);
#ifdef _MSC_VER
			if (!GetUserName(username, &namelen))
				break;
			username[namelen] = 0;
#else
			strcpy(username, "unknown");
#endif
			sprintf(sender,"%s@%s",username,domainname);
		}
#ifdef DEBUG_SMTP
		fprintf(stderr, "Sending: \"MAIL FROM:<%s>\\r\\n\"\n", sender);
#endif
		sendstring(s, 0, "MAIL FROM:<%s>\r\n", sender);
		
		status = waitforstatus(s);
		if (status != 250 && status != 251)
			break;

		// send RCPT TO and wait for response		
		for (n = 0; recipients[n] != NULL ; n++)
		{
#ifdef DEBUG_SMTP
			fprintf(stderr, "Sending: \"RCPT TO:<%s>\\r\\n\"\n", recipients[n]);
#endif
			sendstring(s, 0, "RCPT TO:<%s>\r\n", recipients[n]);
			status = waitforstatus(s);
			if (status != 250 && status != 251)
				break;
		}
		if (status != 250 && status != 251)
			break;

		// DATA sending
#ifdef DEBUG_SMTP
		fprintf(stderr, "Sending: \"DATA\\r\\n\"\n");
#endif
		sendstring(s, "DATA\r\n", 0);
		status = waitforstatus(s);
		if (status != 354)
			break;

#ifdef DEBUG_SMTP
		fprintf(stderr, "Sending: ... message header and body\n");
#endif

		sendstring(s, "Mime-Version: 1.0\r\n", 0);
		sendstring(s, "Content-Type: text/plain; charset=\"us-ascii\"\r\n", 0);

		if (header != NULL)
			sendstring(s, header, 0);
		else
		{
			// make subject and date fields
			for (n = 0; recipients[n] != NULL; n++)
			{
				if (n == 0)
					sendstring(s, 0, "To: %s\r\n", recipients[n]);
				else
					sendstring(s, 0, "Cc: %s\r\n", recipients[n]);
			}
			sendstring(s, 0, "From: %s\r\n", sender);
			sendstring(s, 0, "Subject: %s\r\n", subject);
			sendstring(s, 0, "X-Mailer: <smsmtp>\r\n");
		}

		sendstring(s, "\r\n", 0);		
		sendstring(s, message, 0);
		sendstring(s, "\r\n", 0);		
#ifdef DEBUG_SMTP
		fprintf(stderr, "Sending: \".\\r\\n\"\n");
#endif
		sendstring(s, ".\r\n", 0);

		status = waitforstatus(s);
		if (status != 250) 
			break;
		sent = 1;
#ifdef DEBUG_SMTP
		fprintf(stderr, "Sending: \"QUIT\\r\\n\"\n");
#endif
		sendstring(s, "QUIT\r\n", 0);
		waitforstatus(s);
	} while (0);
	if (s >= 0)
#ifdef DEBUG_SMTP
		fprintf(stderr, "Disconnecting.\n\n");
#endif
#ifdef _MSC_VER
		closesocket(s);
#else
		close(s);
#endif
	return sent;		
}

// function:
//		void smtp::setsubject()
// description:
//		sets the subject for the current instance of the smtp class
// returns:
//		nothing

void
smtp::setsubject(const char *sub)
{
	subject[S_SUBJECT_LEN] = 0;
	strncpy(subject, sub, S_SUBJECT_LEN);
}

// function:
//		void smtp::setreceiver()
// description:
//		sets the receiver for the current instance of the smtp class
// returns:
//		nothing

void
smtp::setreceiver(const char *rec)
{
	char * const *pv;

	if (recipients != NULL)
	{
		pv = recipients;
		while (*pv)
		{
		    delete *pv;
		    pv++;
		}
		delete recipients;
		recipients = NULL;
	}

	recipients = new char *[2];
	recipients[0] = strdup(rec);
	recipients[1] = NULL;
}

// function:
//		void smtp::setrecipients()
// description:
//		sets the receiver for the current instance of the smtp class
// returns:
//		nothing

void
smtp::setrecipients(char * const * recs)
{
	char * const * pv;
	int n;

	if (recipients != NULL)
	{
		pv = recipients;
		while (*pv)
		{
		    delete *pv;
		    pv++;
		}
		delete recipients;
		recipients = NULL;
	}

	for (n = 0; recs[n] != NULL; n++)
		;

	recipients = new char *[n+1];
	for (n = 0; recs[n] != NULL; n++)
	    recipients[n] = strdup(recs[n]);
	recipients[n] = NULL;
}

// function:
//		void smtp::setsender()
// description:
//		sets the sender for the current instance of the smtp class
// returns:
//		nothing

void
smtp::setsender(const char *sen)
{
	sender[S_SENDER_LEN] = 0;
	strncpy(sender, sen, S_SENDER_LEN);
}


// function:
//		void smtp::setlinebreak()
// description:
//		sets the linebreak for the message
// returns:
//		nothing

void
smtp::setlinebreak(char linebr)
{
	linebreak = linebr;	
}

// function:
//		void smtp::setmessage()
// description:
//		sets the message and replaces the linebreaks by \r\n
// returns:
//		nothing

void
smtp::setmessage(const char *mes)
{
	int pos = 0;
	int linepos = 0;
	int last;

	while (*mes)
	{
		if (*mes == linebreak)
		{
			
			message[pos++] = '\r';
			message[pos++] = '\n';			
			linepos = 0;
			mes++;
		}
		else if (*mes == '\r')
		{
			mes++;
		}
		else if (*mes == '\n')
		{
			mes++;			
		}
		else
		{
			last = message[pos++] = *mes++;
			if (last=='.' && linepos == 0)
				message[pos++] = '.';
			linepos++;
		}
		if (pos > S_MESSAGE_LEN-3)
			break;
	}
	message[pos] = 0;
}

// function:
//		void smtp::setheader()
// description:
//		sets the header and replaces the linebreaks by \r\n
// returns:
//		nothing
// function:

void
smtp::setheader(const char *hdr)
{
	char tmp[2048];
	int pos = 0;
	int linepos = 0;
	int last;

	while (*hdr)
	{
		if (*hdr == linebreak)
		{
			
			tmp[pos++] = '\r';
			tmp[pos++] = '\n';			
			linepos = 0;
			hdr++;
		}
		else if (*hdr == '\r')
		{
			hdr++;
		}
		else if (*hdr == '\n')
		{
			hdr++;			
		}
		else
		{
			last = tmp[pos++] = *hdr++;
			if (last=='.' && linepos == 0)
				tmp[pos++] = '.';
			linepos++;
		}
		if (pos > sizeof(tmp) - 2)
			break;
	}
	tmp[pos] = 0;

	if (header != NULL)
		delete header;
	header = new char[strlen(tmp) + 1];
	strcpy(header, tmp);
}

//		smtp::~smtp()
// description
// 		cleans things up
// returns:
//		nothing
smtp::~smtp()
{
	char **pv;

	if (header != NULL)
		delete header;
	delete message;
	if (recipients != NULL)
	{
		pv = recipients;
		while (*pv)
		{
		    free (*pv);
		    pv++;
		}
		delete recipients;
		recipients = NULL;
	}
}
