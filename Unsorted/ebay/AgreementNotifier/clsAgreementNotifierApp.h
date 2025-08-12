/*	$Id: clsAgreementNotifierApp.h,v 1.3 1999/02/21 02:20:56 josh Exp $	*/
#ifndef CLSAGREEMENTNOTIFIERAPP_INCLUDED
#define	CLSAGREEMENTNOTIFIERAPP_INCLUDED



#include <time.h>

#include "clsApp.h"


// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;


class clsAgreementNotifierApp : public clsApp
{
public:
	clsAgreementNotifierApp();
	~clsAgreementNotifierApp();

	void Run();

protected:
	clsDatabase			*mpDatabase;
	clsMarketPlaces		*mpMarketPlaces;
	clsMarketPlace		*mpMarketPlace;
	clsUsers			*mpUsers;


};

#endif // CLSAGREEMENTNOTIFIERAPP_INCLUDED
