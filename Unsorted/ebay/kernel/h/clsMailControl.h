/*	$Id: clsMailControl.h,v 1.3 1999/04/28 05:35:17 josh Exp $	*/
//
//	File:		clsMailControl.h
//
// Class:	clsMailControl
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//
//		Representation of a mail control
//
// Modifications:
//				- 04/1/99 pvh	- Created
//

#ifndef CLSMAILCONTROL_INCLUDED



// #include "eBayKernel.h"
#include "eBayTypes.h"
#include "eBayPageTypes.h"
#include <vector.h>
#ifdef _MSC_VER
#include "clsSynchronize.h"
#endif
#include <string>


typedef enum
{
	bidNoticesChinese = 0,
	bidNoticesDutch = 1,
	outBidNoticesChinese = 2
} MailBidNoticeTypeEnum;

typedef enum
{
	all_pools = -1,
	pool_general = 1,
	pool_registration = 2,
	pool_help = 3
} MailPoolTypeEnum;

// the above is needed for UNIX
#ifdef _MSC_VER

typedef struct
{
	char *machine;
	int	weighting;
} MailMachine;
typedef vector<MailMachine *> MailMachineVector;

typedef struct
{
	int poolType;
	MailMachineVector *machines;
} MailPool;
typedef vector<MailPool *> MailPoolVector;

class clsMailControl
{
	public://
		//
		// Vanilla CTOR/DTOR
		//
		clsMailControl(clsMarketPlace *pMarketPlace);


		// DTOR
		~clsMailControl();

		// GetMailMachine
		//		Returns a pointer to the current MailMachine object
		//
		MailMachineVector *GetMailMachinesForType(MailPoolTypeEnum mailPoolType);
		void ClearMailPools(MailPoolTypeEnum whichPool = all_pools, bool obeyLock = true, MailPoolVector *theMailPools = NULL);
	
		void AddMailMachinesToPool(MailPoolTypeEnum mailPoolType, vector<char *>& vMachine, bool obeyLock = true);
		void AddMailMachineToPool(MailPoolTypeEnum mailPoolType, MailMachine *machine, 
			bool obeyLock, MailPoolVector *theMailPools = NULL);
		void SetupMailMachines();
		void WriteoutMailMachines();

		// Returns the number of machines in a give pool
		int GetMailMachineCount(MailPoolTypeEnum poolType, bool obeyLock = true);

		// Returns true if machine is found
		bool GetMailMachine(MailPoolTypeEnum poolType,
			int machineIndex,
			std::string& machineAddress, 
			bool obeyLock = true);
/*
		void SetBidNoticesChinese(bool value);
		void SetBidNoticesDutch(bool value);
		void SetOutBidNoticesChinese(bool value);
		bool GetBidNoticesChinese();
		bool GetBidNoticesDutch();
		bool GetOutBidNoticesChinese();
*/
		void SetupMailPoolsSyncLock();
		void SetupMailBidSyncLock();
		void SetMailBidNoticesState(MailBidNoticeTypeEnum type, bool value);
		bool GetMailBidNoticesState(MailBidNoticeTypeEnum type);
private:
			int mMailIndex[MAIL_CLASSES]; // Index of the current mailserver.

		//
		// Mail relay machine array(s)
		//
//		MailPoolVector *mpMailPools;
		clsSynchronizeable *mpSyncMailPools;
		clsSynchronizeable *mpSyncMailBid;

		bool mpMailBidNoticeArray[3];
		
		bool doBidNoticesChinese;
		bool doBidNoticesDutch;
		bool doOutBidNoticesChinese;

		// Parent MarketPlace
		clsMarketPlace	*mpMarketPlace;
};
		// default email machine list

// mail pool for NT/CGI boxes
//
// machine:		is the machine name
// weighting:	is a number from 1 - 10 of it's relative 'performance.' higher is a better performer.
// this number will be used to determine frequency of the machine being used in the loop
//

// mail pool for NT/CGI boxes
static const MailMachine gStatMailGenMachines[] =
{
	{	"boa.ebay.com"			, 10	},		// 166 MHz FreeBSD 2.2.5
	{	"skink.ebay.com"		, 10	},		// 166 MHz FreeBSD 2.2.5
	{	"slowworm.ebay.com"		, 10	},
	{	"diamondback.ebay.com"	, 10	},
	{	"moccasin.ebay.com"		, 10	}
};

//	{	"turtle.ebay.com"		, 10	}, 		// 200 MHz FreeBSD 2.2.5
//	{	"collaris.ebay.com"		, 10	}, 
//	{	"diamondback.ebay.com"	, 10	},		// 200 MHz FreeBSD 2.2.5
//	{	"moccasin.ebay.com"		, 10	}		// 200 MHz FreeBSD 2.2.6

static const MailMachine gStatMailRegMachines[] =
{
	{	"pelagic.ebay.com"		, 10	}, 
	{	"hognose.ebay.com"		, 10	}
}; 

static const MailMachine gStatMailHelpMachines[] =
{
	{	"kuhli.ebay.com"		, 10	}		
}; 

const char * const MAIL_MACHINES = "c:\\mail_machines.txt";

#endif // _MSC_VER



#define CLSMAILCONTROL_INCLUDED 1
#endif CLSMAILCONTROL_INCLUDED
