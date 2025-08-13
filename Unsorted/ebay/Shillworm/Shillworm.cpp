/*	$Id: Shillworm.cpp,v 1.4.390.1 1999/08/01 02:51:20 barry Exp $	*/
//
//	shillworm.cpp
//
//	Shillworm console app. Gather a list of hot non-dutch auctions, and generate
//	shill analyses for each of them.
//
//	Created: 1-Dec-1998
//	Josh Gordon
//
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include <fstream.h>
#include "clsApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUserRelationships.h"
#include "clsItems.h"

class clsShillwormApp : public clsApp
{
public:
	int Run();
	clsShillwormApp();
private:
	clsDatabase *mpDatabase;
	clsMarketPlaces *mpMarketPlaces;
	clsMarketPlace *mpMarketPlace;
	clsUsers *mpUsers;
	clsItems *mpItems;
    void Previous_and_Next(int i, const vector<int>&vItems, ofstream& ofile);
};

clsShillwormApp::clsShillwormApp() : mpDatabase(NULL), mpMarketPlaces(NULL),
mpMarketPlace(NULL), mpUsers(NULL), mpItems(NULL)
{
	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();
	mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	mpUsers = mpMarketPlace->GetUsers();
	mpItems = mpMarketPlace->GetItems();
#ifdef _MSC_VER
	g_tlsindex = 0;
#endif
	SetApp(this);
}

#define DESTINATION_PATH "."

// This is the directory where the output goes
const char *output_directory = "/oracle04/export/home/www/other/shillworm";
const char *wormhost = "http://python.ebay.com/shillworm/";
bool bDoBigTicket = false;
const char *indexFile = "shills.html";
static char *auctionsFile = NULL;
static int processLimit = -1;
static int itemOnly = -1;		// if a single item is wanted

static void usage()
{
	cout << 
		"Usage:  Shillworm [flags]\n"
		"  where flags include:\n"
		"    -f filename    Take the auction numbers from filename (default is current hot non-Dutch auctions)\n"
		"    -n ##          Only generate ## notices\n"
		"    -d directory   Where to put the results (default is "
		<< output_directory
		<< ")\n"
		"    -i ##i         Run once for a single item -- no index created\n"
		"    -b             Run the big ticket auctions (default is hot non-dutch)\n"
		"    -I filename    Put the index in filename (default is " << indexFile << "\n"
		"                   for hot items run, bigshills.html for Big Ticket auctions)\n";
		;
}

#ifdef _MSC_VER
// These things aren't declared for MSC.
extern "C" int getopt(int, char**, char*);
extern "C" char *optarg;
extern "C" int optind;
#endif


void clsShillwormApp::Previous_and_Next(int i, const vector<int>&vItems, ofstream& ofile)
{
	// Put in a previous and next link. It's grey for the first and last item.
	if (i == 0)
		ofile << "<font color=gray>Previous</font>";
	else
		ofile << "<a href=\""
			<< wormhost
			<< vItems[i - 1] << ".html"
			<< "\">Previous</a>";

	ofile << " <a href=\""
			<< wormhost
			<< "shills.html\">"
			<< "Index"
			<< "</a> ";

	if (i == processLimit - 1)
		ofile << "<font color=gray>Next</font>";
	else
		ofile << "<a href=\""
			<< wormhost
			<< vItems[i + 1] << ".html"
			<< "\">Next</a>";
}


int clsShillwormApp::Run()
{
	char filename[_MAX_PATH];
	vector<int> vItems;
	time_t endDate = time(0);
	int i;


	if (auctionsFile != NULL)
	{
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
	else if (itemOnly != -1)
		vItems.push_back(itemOnly);
	else if (bDoBigTicket)
		mpItems->GetHighTicketItems(&vItems, time_t(0), 5000.0);
	else
		mpItems->GetHotNonDutchItemIds(&vItems, endDate);

	int size = vItems.size();

	cout << "Processing ";
	if (processLimit != -1)
		cout << processLimit << " of ";
	cout << size << " shill report"
		<< ((size != 1) ? "s" : "")
		<< ".\n" << flush;
	if (processLimit == -1)
		processLimit = size;
	else
		processLimit = min(size, processLimit);

	if (itemOnly == -1)
	{
		cout << "\nBuilding index..." << flush;

		sprintf(filename, "%s/%s", output_directory, indexFile);
		ofstream indexfile(filename);
		if (!indexfile)
		{
			perror(filename);
			return -1;
		}

		indexfile << "<html><head>"
				"<title>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	" " << "Shill Candidate Index"
				<< "</title></head><body>"
				<< mpMarketPlace->GetHeader()
				<< "\n";
		
		indexfile << "<hr>";
		indexfile << "<p><center><H1>" << "Shill Candidate Index" << "</H1></center><p>\n";

		for (i = 0; i < processLimit; i++)
		{
			int item = vItems[i];
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
	}
	
	// Now create a web page for each of these.
	int processed = 0;
	for (i = 0; i < processLimit; i++)
	{
	
		int item = vItems[i];
		cout << "Processing item " << item << "\n" << flush;

		sprintf(filename, "%s/%d.html", output_directory, item);
		ofstream ofile(filename);
		if (!ofile)
		{
			perror(filename);
			return -1;
		}

		ofile  << "<html><head>"
			"<title>"
			<<	mpMarketPlace->GetCurrentPartnerName()
			<<	" " << "Shill Candidate " << item
			<< "</title></head><body>"
			<< mpMarketPlace->GetHeader()
			<< "\n";
		
		ofile << "<hr>";
		if (itemOnly == -1)
		{
			Previous_and_Next(i, vItems, ofile);
			ofile << "<hr>";
		}
		// Include the item number in the header for this one.
		ofile << "<p><center><H1>" << "Shill Relationships Tool" << " for item "
			<< "<a href=\""
			<< mpMarketPlace->GetCGIPath(PageViewItem)
			<< "eBayISAPI.dll?ViewItem&item="
			<< item
			<< "\">"
			<< item
			<< "</a>"
			<< "</h1></center>\n";

		clsUserRelationships userRelationships(mpMarketPlace, mpUsers, &ofile, gApp);
		userRelationships.ShillRelationshipsByItem("on", item, 30);

		if (itemOnly == -1)
		{
			ofile << "<hr>";
			Previous_and_Next(i, vItems, ofile);
		}

		ofile << mpMarketPlace->GetFooter();
		ofile << "\n\n</body></html>\n" << flush;
	}
	cout << "\nDone!\n\n" << flush;

	return 0;
}

int main(int argc, char **argv)
{
	// Parse the command line arguments.
	int c;

	while ((c = getopt(argc, argv, "f:n:d:i:I:b")) != EOF)
	{
		switch(c)
		{
		case 'f':
			auctionsFile = optarg;
			break;
		case 'n':
			processLimit = atoi(optarg);
			break;
		case 'd':
			output_directory = optarg;
			break;
		case 'i':
			itemOnly = atoi(optarg);
			break;
		case 'b':
			bDoBigTicket = true;
			indexFile = "bigshills.html";
			break;
		case 'I':
			indexFile = optarg;
			break;
		case '?':
		default:
			usage();
			return 0;
		}
	}

	if (++optind < argc)
	{
		usage();
		return -1;
	}



	return clsShillwormApp().Run();

}

