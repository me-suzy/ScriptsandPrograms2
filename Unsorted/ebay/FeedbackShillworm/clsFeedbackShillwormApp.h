// clsFeedbackShillwormApp.h: interface for the clsFeedbackShillwormApp class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_CLSFEEDBACKSHILLWORMAPP_H__84C94CC2_B964_11D2_96E1_00C04F990638__INCLUDED_)
#define AFX_CLSFEEDBACKSHILLWORMAPP_H__84C94CC2_B964_11D2_96E1_00C04F990638__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

class clsFeedbackShillwormApp : public clsApp  
{
public:
	static void usage();
	int Run(const char *auctionsFile);
	clsFeedbackShillwormApp(int minimumFeedback, const char *outputDirectory, int processLimit, const char *indexFile);
	virtual ~clsFeedbackShillwormApp();

private:
    clsDatabase *mpDatabase;
    clsMarketPlaces *mpMarketPlaces;
    clsMarketPlace *mpMarketPlace;
    clsUsers *mpUsers;
    clsItems *mpItems;
	
	static const char *mOutputDirectory;
	static int mMinimumFeedback;
	static const char *mIndexFile;

	int mProcessLimit;
	void Previous_and_Next(int i, const vector<char *>&vUserids, ofstream& ofile);
};

#endif // !defined(AFX_CLSFEEDBACKSHILLWORMAPP_H__84C94CC2_B964_11D2_96E1_00C04F990638__INCLUDED_)
