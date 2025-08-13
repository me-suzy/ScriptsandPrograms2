#ifndef CLSUNCONFIRMEDNOTIFIERAPP_INCLUDED
#define	CLSUNCONFIRMEDNOTIFIERAPP_INCLUDED


#include "clsApp.h"

class clsUnconfirmedNotifierApp : public clsApp
{
public:
	clsUnconfirmedNotifierApp();
	~clsUnconfirmedNotifierApp();

	void Run(int argc, char **argv);

protected:
	clsDatabase			*mpDatabase;
	clsMarketPlaces		*mpMarketPlaces;
	clsMarketPlace		*mpMarketPlace;
	clsUsers			*mpUsers;


};

#endif // CLSUNCONFIRMEDNOTIFIERAPP_INCLUDED
