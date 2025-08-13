/*	$Id: clseBayAppAdminMailHandling.cpp,v 1.2 1999/04/17 20:22:06 wwen Exp $	*/
//
//	File:		clseBayAppAdminMailHandling.cpp
//
//	Class:		clseBayApp
//
//	Author:		pete helme (pete@ebay.com)
//
//	Function:
//
//				This method draws a "form" for support to move one of more
//				auctions to a new category
//
//	Modifications:
//				- 03/31/99 pvh	Created.
//
#include "ebihdr.h"
#include "tcpstuff.h"
// #include <string>

void clseBayApp::ShowBidMailStatus(CEBayISAPIExtension *pThis, 
								   char *bidtype, bool oldstatus, bool newstatus,
								   eBayISAPIAuthEnum authLevel)
{
	char host[64];
	
	SetUp();
	
	// We'll need a title here
	EmitHeader("ShowBidMailStatus");	
	
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		
		return;
	}
	
	getmachinename(host);
	
	*mpStream <<	"<h2>Bid Mail status for ";
	*mpStream <<	host;
	*mpStream <<	"</h2>"
		"<br>";
	
	*mpStream <<	"<br>"
		<<	mpMarketPlace->GetFooter();
	
	CleanUp();
	return;
}

void clseBayApp::ToggleMailMachineBidStatus(CEBayISAPIExtension *pThis, 
								   int bidType, int state,
								   eBayISAPIAuthEnum authLevel)
{
	char host[64];
	bool showState;

	SetUp();
	
	EmitHeader("ToggleMailMachineBidStatus");	
	
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		
		return;
	}
	
	getmachinename(host);
	
	*mpStream <<	"<h2>Bid Mail status for ";
	*mpStream <<	host;
	*mpStream <<	"</h2>";
	
	// to get status only
	if(state != -1) {
		mpMarketPlace->GetMailControl()->SetMailBidNoticesState((MailBidNoticeTypeEnum) bidType, state == 1 ? true : false);
	} else {
		*mpStream << "[status only]<br><br>";
	}

	showState = mpMarketPlace->GetMailControl()->GetMailBidNoticesState(bidNoticesChinese);
	*mpStream	<<	"<b>Chinese Bid Notices:</b> ";
	if(showState == true)
		*mpStream << "enabled";
	else
		*mpStream << "disabled";
	if((MailBidNoticeTypeEnum) bidType == bidNoticesChinese && state != -1) {
		if(state == 1)
			*mpStream << "<font color=red> [changed to enabled]</font>";
		else
			*mpStream << "<font color=red> [changed to disabled]</font>";
	}

	showState = mpMarketPlace->GetMailControl()->GetMailBidNoticesState(bidNoticesDutch);
	*mpStream	<<	"<br><b>Dutch Bid Notices:</b> ";
	if(showState == true)
		*mpStream << "enabled";
	else
		*mpStream << "disabled";
	if((MailBidNoticeTypeEnum) bidType == bidNoticesDutch && state != -1) {
		if(state == 1)
			*mpStream << "<font color=red> [changed to enabled]</font>";
		else
			*mpStream << "<font color=red> [changed to disabled]</font>";
	}

	showState = mpMarketPlace->GetMailControl()->GetMailBidNoticesState(outBidNoticesChinese);
	*mpStream	<<	"<br><b>Chinese OutBid Notices:</b> ";
	if(showState == true)
		*mpStream << "enabled";
	else
		*mpStream << "disabled";
	if((MailBidNoticeTypeEnum) bidType == outBidNoticesChinese && state != -1) {
		if(state == 1)
			*mpStream << "<font color=red> [changed to enabled]</font>";
		else
			*mpStream << "<font color=red> [changed to disabled]</font>";
	}
				
	*mpStream <<	"<br><br>"
		<<	mpMarketPlace->GetFooter();
	
	CleanUp();
	return;
}

void clseBayApp::ShowMailMachineStatus(CEBayISAPIExtension *pThis, 
								   eBayISAPIAuthEnum authLevel)
{
	char host[64];
	
	SetUp();
	
	// We'll need a title here
	EmitHeader("Show MailMachine Status");	
	
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		
		return;
	}
	
	getmachinename(host);
	
	*mpStream <<	"<h2>MailMachine status for ";
	*mpStream <<	host;
	*mpStream <<	"</h2>"
		"<br>";
	
	DrawMailMachineStatus(host);

	*mpStream <<	"<br>"
		<<	mpMarketPlace->GetFooter();
	
	CleanUp();
	return;
}


void clseBayApp::InstallNewMailMachineList(CEBayISAPIExtension *pThis, 
										   char *machines, int poolType,
										   eBayISAPIAuthEnum authLevel)
{
	char host[64]; // tempStr[256];
	vector<char *> vMachine;
	//	vector<char *>::iterator iMachine;
	//MailMachine *bob;
//	int i;
	std::string machineAddress;
	MailMachineVector *mailMachines;
	MailMachineVector::iterator theMachine;
	//	MailPoolVector::iterator thePool;
	
	SetUp();
	
	// We'll need a title here
	EmitHeader("Install New MailMachine List");	
	
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
			CleanUp();
	
			return;
	}
	
	getmachinename(host);
	
	*mpStream <<	"<h2>Update mail pool handling for ";
	*mpStream <<	host;
	*mpStream <<	"</h2>"
		"<br>";
	
	*mpStream <<	"The requested new machine list for pool "
		<<	poolType	
		<<	" is: " 
		<<	machines;
	
	
	// look for any text at all
	if(strcmp(machines, "default")) {	
		{
			// parse text into a vector
			char seps[]   = " ";
			char *token;
			
			/* Establish string and get the first token: */
			token = strtok( machines, seps );   
			
			// put in the array
			if(token)
				vMachine.push_back(token);
			
			while( token != NULL )   {
				/* While there are tokens in "string" */      
				/* Get next token: */      
				token = strtok( NULL, seps );   
				
				// put in the array
				if(token)
					vMachine.push_back(token);
			}
			
		}
		
		// add these machines
		mpMarketPlace->GetMailControl()->AddMailMachinesToPool((MailPoolTypeEnum) poolType, vMachine);
		
#if 0
		for (iMachine = vMachine.begin(); iMachine != vMachine.end(); iMachine++) {
			{
				char seps[]   = ",";
				char *token;
				/* Establish string and get the first token: */
				token = strtok( *iMachine, seps );   
				
				while( token != NULL )   {
					bob = new MailMachine;
					bob->machine = new char[64];
					bob->machine[0] = 0;
					
					strcpy(bob->machine, token);
					/* While there are tokens in "string" */      
					/* Get next token: */      
					token = strtok( NULL, seps );  
					
					// if we have the weighting, use it. else assign '10'
					if(token) {
						bob->weighting = atoi(token);
					} else {
						bob->weighting = 10;
					}
					
					mpMarketPlace->GetMailControl()->AddMailMachineToPool((MailPoolTypeEnum) poolType, bob);
				}
				
			}
		}
#endif
		// write the changes to disk
		mpMarketPlace->GetMailControl()->WriteoutMailMachines();
		
/*		
testing

  for(i=1;i<4; i++) { 
			mpMarketPlace->GetMailControl()->GetMailMachinesForType((MailPoolTypeEnum)i);
			
		}
		
		i = mpMarketPlace->GetMailControl()->GetMailMachineCount((MailPoolTypeEnum)1, true);
		
		{
			bool test;
			
			test = mpMarketPlace->GetMailControl()->GetMailMachine((MailPoolTypeEnum)1,
				2,
				machineAddress, 
				true);
		}
*/
		
		*mpStream <<	"<br><br>"
			<<	"The new machine list for pool "
			<<	poolType
			<<	" is:<br>";
		
		mailMachines = mpMarketPlace->GetMailControl()->GetMailMachinesForType((MailPoolTypeEnum) poolType);
		for(theMachine = mailMachines->begin(); theMachine != mailMachines->end(); theMachine++) {
			if((**theMachine).machine) {
				*mpStream << (**theMachine).machine;
				*mpStream << " ";
				*mpStream << (**theMachine).weighting;
				*mpStream << "<br>";
			}
		}
		
		DrawMailMachineStatus(host);
		
		*mpStream <<	"<br>"
			<<	mpMarketPlace->GetFooter();
		
		CleanUp();
		}
}



void clseBayApp::DrawMailMachineStatus(char *host)
{
	int i;
	MailMachineVector *mailMachines;
	MailMachineVector::iterator theMachine;
	//	MailPoolVector::iterator thePool;
	
	*mpStream <<	"<br>"
		<<	"The complete mail machine list for <b>"
		<<	host
		<<	":</b><br>";
	
	for(i=1;i < 4; i++) {
		mailMachines = mpMarketPlace->GetMailControl()->GetMailMachinesForType( (MailPoolTypeEnum) i);
		*mpStream << "<br>";
		*mpStream << "Pool: "
			<< i;
		
		*mpStream << "<br>";
		for(theMachine = mailMachines->begin(); theMachine != mailMachines->end(); theMachine++) {
			if((**theMachine).machine) {
				*mpStream << (**theMachine).machine;
				*mpStream << " ";
				*mpStream << (**theMachine).weighting;
				*mpStream << "<br>";
			}
		}
		
	}	
}
