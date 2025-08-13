/* $Id: Options.h,v 1.2 1999/02/21 02:22:16 josh Exp $ */
//
// File: Options
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: Parses a command line for program options
//	 for the GetURLs tool.
//

#ifndef Options_h
#define Options_h

#pragma warning(disable:4786)

#include <string>
#include <vector.h>

//using namespace std;

struct Options
{
	std::string mUpdates;
		// The data file with the items to download

	std::string mImageDB;
		// The location of where to store the items

	std::string mBadImages;
		// Where to stick bad images

	std::string mCompleted;
		// Where successfully processed input files are
		// moved to when done

	std::string mFailed;
		// Where input files that have failed once
		// are put

	std::string mBad;
		// Where failed input files are moved to

	std::string mThumbDB;
		// Where to put the resulting thumbnail database

	vector<std::string> mNotify;
		// Who to send progress reports to

	std::string mImages;
		// Temporary location for images as they are downloaded

	std::string mIDExceptions;
		// Item IDs that we are not going to bother downloading
		// Used when an item crashes our image processing code

	vector<std::string> mThumbnailDBDestination;
		// Where the complete thumbnail databases are copied to

	std::string mBackupDB;
		// Where the backup dbs are copied to

	vector<std::string> mThumbDBNotify;
		// The thumb servers to notify of a new thumb database

	int mMaxDownloads;
		// The maximum number of simultaneous downloads

	int mImageSize;
		// Maximum size of the thumbnail images

	int mQuality;
		// Compression quality for the image

	int mMaxConnectWait;
		// Maximum time to wait before timing out a connection. In seconds.
	
	int mMaxRequestWaitTime;
		// Maximum time to wait for a GET response. In seconds.

	int mMaxReadWaitTime;
		// Maximum time to wait for a read. In seconds.

	int mMaxRedirects;
		// Maximum number of times to follow redirects

	int mMaxAgeHours;
		// Maximum number of hours that an image is kept in the thumb database.

	int mMaxItems;
		// Maximum number of item IDs kept in database

	enum { 
		kDefaultMaxDownloads = 64,
		kDefaultImagesize = 96,
		kDefaultQuality = 80,
		kDefaultMaxConnectWait = 45,
		kDefaultMaxRequestWaitTime = 45, // sec
		kDefaultMaxReadWaitTime = 45,	// sec
		kDefaultMaxRedirects = 3,		// The maximum number of times we will redirect before giving up
		kDefaultMaxAgeHours = 192,		// 8 days (24*8)
		kDefaultMaxItems = 1000000
	};

	Options();
	int GetOptions(int argc, char** argv);
	int ReadConfiguration(std::string& configFileName);
};

#endif // Options