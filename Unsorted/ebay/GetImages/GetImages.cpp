/* $Id: GetImages.cpp,v 1.2 1999/02/21 02:22:13 josh Exp $ */
//
// File: main
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: Reads a file of item IDs and urls to pictures for those items
//	 and downloads those items. The items are stored in a heirarchy of directories
//	 structured by item IDs. The file itself is named after the item number.
//


#include "eBayKernel.h"
#include "clsGetImagesApp.h"
#include "Options.h"

#include <excpt.h>

void KlugeFailOnce()
{
	__try
	{
		unsigned char *mpLDA       = (unsigned char *)new Lda_Def;
		memset(mpLDA, '\0', sizeof (Lda_Def));

		unsigned char *mpHDA			= new unsigned char[512];
		memset(mpHDA, '\0', 512);

		int rc = olog((Lda_Def *)mpLDA, 
				  (ub1 *)mpHDA, 
				  (text *)"ebayqa", -1, 
				  (text *)"pipsky", -1,
				  (text *)"test", -1,
				  OCI_LM_DEF);  

	}
	__except(1)
	{
		int err = _exception_code();

		cout << "Caught exception" << endl;
	}
}


int main(int argc, void* argv)
{
#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

	KlugeFailOnce();

	Options options;

	int result = options.GetOptions(argc, reinterpret_cast<char**>(argv) );
	if (result != 0) 
		return result;

	clsGetImagesApp myApp(options);
	
	myApp.InitShell();
	myApp.Run();

	return 0;
}





