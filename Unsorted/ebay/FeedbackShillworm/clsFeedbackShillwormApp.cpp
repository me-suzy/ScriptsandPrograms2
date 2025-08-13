// clsFeedbackShillwormApp.cpp: implementation of the clsFeedbackShillwormApp class.
//
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
////////////////////////////////////////////////////////////////////////

#include <fstream.h>
#include "clsApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUserRelationships.h"
#include "clsItems.h"
#include "clsFeedbackShillwormApp.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////
int clsFeedbackShillwormApp::mMinimumFeedback = 20;
const char *clsFeedbackShillwormApp::mOutputDirectory = "/oracle04/export/home/www/other/feedback_shillworm";
const char *clsFeedbackShillwormApp::mIndexFile = "feedback_shills.html";

clsFeedbackShillwormApp::clsFeedbackShillwormApp(int minimumFeedback, 
												 const char *outputDirectory, 
												 int processLimit,
												 const char *indexFile): 
	mpDatabase(NULL), mpMarketPlaces(NULL),
	mpMarketPlace(NULL), mpUsers(NULL), mpItems(NULL),mProcessLimit(processLimit)
{
    mpDatabase = GetDatabase();
    mpMarketPlaces = GetMarketPlaces();
    mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
    mpUsers = mpMarketPlace->GetUsers();
    mpItems = mpMarketPlace->GetItems();
	if (minimumFeedback)
		mMinimumFeedback = minimumFeedback;
	if (outputDirectory)
		mOutputDirectory = outputDirectory;
	if (indexFile)
		mIndexFile = indexFile;

#ifdef _MSC_VER
    g_tlsindex = 0;
#endif
    SetApp(this);
}

clsFeedbackShillwormApp::~clsFeedbackShillwormApp()
{

}

const char *wormhost = "http://python.ebay.com/feedback_shillworm/";

void clsFeedbackShillwormApp::Previous_and_Next(int i, const vector<char *>&vUserids, ofstream& ofile)
{
	// Put in a previous and next link. It's grey for the first and last item.
	if (i == 0)
		ofile << "<font color=gray>Previous</font>";
	else
		ofile << "<a href=\""
			<< wormhost
			<< vUserids[i - 1] << ".html"
			<< "\">Previous</a>";

	ofile << " <a href=\""
			<< wormhost
			<< "feedback_shills.html\">"
			<< "Index"
			<< "</a> ";

	if (i == mProcessLimit - 1)
		ofile << "<font color=gray>Next</font>";
	else
		ofile << "<a href=\""
			<< wormhost
			<< vUserids[i + 1] << ".html"
			<< "\">Next</a>";
}




int clsFeedbackShillwormApp::Run(const char *auctionsFile)
{
	IntVector vItems;
	int i;
	char filename[_MAX_PATH];

	// Either read a file of auction ids, or get all the super-featured auctions.
	if (auctionsFile != NULL)
	{
		// suck in the auction numbers
        // For testing, suck in some auction numbers since it takes so long to do it for real.
        ifstream ifile(auctionsFile, ios::in | ios::nocreate);
        if (ifile.fail())
        {
            perror(auctionsFile);
            return -1;
        }
        copy(istream_iterator<int>(ifile), istream_iterator<int>(), back_inserter(vItems));
        ifile.close();
	}
	else
	{
		mpItems->GetSuperFeaturedItemIds(&vItems, time(0));
	}

	// Now get the user ids for all the sellers of these things.
	IntIntMap mSellersAndAuctions;
	mpDatabase->GetSellersOfAuctions(vItems, mSellersAndAuctions, 0);

	// And get the feedback score for each of these guys.
	// First, copy the sellers into a vector
	IntVector vSellers;

	IntIntMap::iterator iM;
	for (iM = mSellersAndAuctions.begin(); iM != mSellersAndAuctions.end(); ++iM)
		vSellers.push_back((*iM).second);

	// Now get their scores.
	IntIntMap mSellersAndFeedbackScores;
	mpDatabase->GetFeedbackScores(vSellers, mSellersAndFeedbackScores);

	IntVector vCandidates;

	// Now remove all the people from the list whose feedback score is greater
	// than the minimum.
	for (iM = mSellersAndFeedbackScores.begin(); iM != mSellersAndFeedbackScores.end(); ++iM)
	{
		if ((*iM).second <= mMinimumFeedback)
			vCandidates.push_back((*iM).first);
	}

	// Now we have the list of candidates to pass through Shill Tool By Feedback. But actually,
	// we need their user ids...Put all the UserIds into a vector. We strdup the things so we can
	// get rid of the user objects in a hurry.
	vector<char *> vUserids;
	int size = vCandidates.size();
	if (mProcessLimit == -1)
		mProcessLimit = size;
	else
		mProcessLimit = min(size, mProcessLimit);

	for (i = 0; i < mProcessLimit; ++i)
	{
		clsUser *pUser = mpUsers->GetUser(vCandidates[i]);
		if (pUser == NULL)
		{
			cerr << "Couldn't get user info for user id " << vCandidates[i] << endl;
			vUserids.push_back((char *)NULL);
			continue;
		}
		vUserids.push_back(strdup(pUser->GetUserId()));
		delete pUser;
	}


	size = vUserids.size();

	cout << "Processing ";
	if (mProcessLimit != -1)
		cout << mProcessLimit << " of ";
	cout << size << " shill report"
		<< ((size != 1) ? "s" : "")
		<< ".\n" << flush;
	mProcessLimit = min(size, mProcessLimit);

	cout << "\nBuilding index..." << flush;
	
	sprintf(filename, "%s/%s", mOutputDirectory, mIndexFile);
	ofstream indexfile(filename);
	if (!indexfile)
	{
		perror(filename);
		return -1;
	}
	
	indexfile << "<html><head>"
		"<title>"
		<<	mpMarketPlace->GetCurrentPartnerName()
		<<	" " << "Feedback Shill Candidate Index"
		<< "</title></head><body>"
		<< mpMarketPlace->GetHeader()
		<< "\n";
	
	indexfile << "<hr>";
	indexfile << "<p><center><H1>" << "Feedback Shill Candidate Index" << "</H1></center><p>\n";
	
	for (i = 0; i < mProcessLimit; i++)
	{
		char *item = vUserids[i];

		indexfile << "<a href=\""
			<< wormhost
			<< item << ".html"
			<< "\">"
			<< item
			<< "<a> ";
	}
	indexfile << mpMarketPlace->GetFooter();
	indexfile << "\n\n</body></html>\n" << flush;
	indexfile.close();
	cout << endl;

	for (i = 0; i < mProcessLimit; ++i)
	{
		char *pUserid = vUserids[i];
		cout << "Processing userid " << pUserid << endl << flush;
	
		sprintf(filename, "%s/%s.html", mOutputDirectory, pUserid);
		ofstream ofile(filename);
		if (!ofile)
		{
			perror(filename);
			return -1;
		}

		ofile  << "<html><head>"
			"<title>"
			<<	mpMarketPlace->GetCurrentPartnerName()
			<<	" " << "Feedback Shill Candidate " << pUserid
			<< "</title></head><body>"
			<< mpMarketPlace->GetHeader()
			<< "\n";
		
		ofile << "<hr>";
		Previous_and_Next(i, vUserids, ofile);
		ofile << "<hr>";

		// Include the item number in the header for this one.
		ofile << "<p><center><H1>" << "Feedback Shill Relationships Tool" << " for user "
			<< pUserid
			<< "</h1></center>\n";

		clsUserRelationships userRelationships(mpMarketPlace, mpUsers, &ofile, gApp);
		userRelationships.ShillRelationshipsByFeedback("on", 
											  pUserid,
											  "off",
											  30,
											  0,
											  30);

		ofile << "<hr>";
		Previous_and_Next(i, vUserids, ofile);
		ofile << mpMarketPlace->GetFooter();
		ofile << "\n\n</body></html>\n" << flush;


	}

	// Clear out vUserIds
	for (i = 0; i < vUserids.size(); i++)
		free(vUserids[i]);

	return 0;
}

#ifdef _MSC_VER
// These things aren't declared for MSC.
extern "C" int getopt(int, char**, char*);
extern "C" char *optarg;
extern "C" int optind;
#endif

void clsFeedbackShillwormApp::usage()
{
	cout << 
		"Usage:  FeedbackShillworm [flags]\n"
		"  where flags include:\n"
		"    -f filename    Take the auction numbers from filename (default is current hot non-Dutch auctions)\n"
		"    -n ##          Only generate ## notices\n"
		"    -d directory   Where to put the results (default is "
		<< mOutputDirectory
		<< ")\n"
		"    -I filename    Put the index in filename (default is " << mIndexFile << "\n"
		;

}

int main(int argc, char **argv)
{
    int c;
	char *auctionFile = NULL;
	int minimumFeedback = 0;
	char *outputDirectory = NULL;
	int processLimit = -1;
	char *indexFile = NULL;

    while ((c = getopt(argc, argv, "f:m:d:I:n:")) != EOF)
    {
		switch(c)
		{
		case 'f':
			auctionFile = optarg;
			break;
		case 'm':
			minimumFeedback = atoi(optarg);
			break;
		case 'd':
			outputDirectory = optarg;
			break;
		case 'n':
			processLimit = atoi(optarg);
			break;
		case 'I':
			indexFile = optarg;
			break;
		case '?':
		default:
			clsFeedbackShillwormApp::usage();
			return 0;
		}
	}
	
	if (++optind < argc)
	{
		clsFeedbackShillwormApp::usage();
		return -1;
	}

	return clsFeedbackShillwormApp(minimumFeedback,
		outputDirectory,
		processLimit,
		indexFile).Run(auctionFile);
}


