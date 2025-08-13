/*	$Id: clsGetImagesApp.cpp,v 1.2.390.1 1999/08/05 18:58:39 nsacco Exp $	*/
//
// File: clsGetImagesApp.cpp
//
// Class: clsGetImagesApp
//

#pragma warning(disable:4786)

#include "eBayKernel.h"

#ifdef _MSC_VER
#undef min
#undef max
#endif

#include <iostream>
#include <string>
#include <stdexcept>
#include <fstream>

#include <direct.h>
#include <io.h>
#include <stdio.h>
#include <time.h>
#include <process.h>
#include <time.h>
#include <signal.h>

#include "Gear.h"
#include "GearEStr.h"

#include "clsGalleryChangedItem.h"
#include "clsMail.h"
#include "clsURL.h"
#include "clsHTTPDownload.h"
#include "clsThumbDB.h"
//#include "clsImageDB.h"
#include "clsDirectoryWalker.h"
#include "clsGetImagesApp.h"
#include "Options.h"


static char* gAOLName = "members.aol.com";
static const char* gOldBackup = "OldBackup";
static const char* gLatestBackup = "LatestBackup";
static const char* gNewDB = "NewDB";
static vector<std::string*>* gAOLAddresses = NULL;
static int currentAOLItem = 0;
static int exitASAP = 0;
static const char* kProcessingLabel = "Processing";
static const char* kPropagatingDBLabel = "Propagating";
static const char* kNotifyingLabel = "Notifying";
static const char* kCleanOutCompletedItemsLabel = "Cleaning";

static const char* gStateReportLabels[] =
{
	kProcessingLabel,
	kPropagatingDBLabel,
	kNotifyingLabel,
	kCleanOutCompletedItemsLabel
};

static const char* gGalleryResultLabels[] =
{
	"NotProcessed",
	"Success",
	"BadURL",
	"BadProtocol",
	"PermanentBadImage",
	"HostNameDoesNotExist",
	"ServerNotAvailable",
	"ItemNotFound",
	"FailedDownload",
	"ImageFormat",
	"BadImage",
	"InternalError"
};


#define DESIRED_WINSOCK_VERSION	0x0101  /* we'd like winsock ver 1.1... */
#define MINIMUM_WINSOCK_VERSION	0x0101  /* ...but we'll take ver 1.1 :) */

#if 1

void clsGetImagesApp::clsGetImagesReport::IncrementResultCode(GalleryResultCode result)
{
	if (result < 0 || result >= kGalleryMaxResultCode)
		throw std::runtime_error("clsGetImagesReport::IncrementResultCode: request for invalid result");

	++mResultHistogram[result];
}

clsGetImagesApp::clsGetImagesReport::clsGetImagesReport()
{
	Reset();
}

void clsGetImagesApp::clsGetImagesReport::Reset()
{
	for (int i = 0; i < clsGetImagesApp::kMaxState; i++)
	{
		mStateTimes[i].mStartTime = 0;
		mStateTimes[i].mEndTime = 0;
	}

	for (int j = 0; j < kGalleryMaxResultCode; j++)
	{
		mResultHistogram[j] = 0;
	}
}

clsStopWatch::clsStopWatch(TimeRecord& time) :
	mTimeRecord(time)
{
	mTimeRecord.mStartTime = ::time(NULL);
	mTimeRecord.mEndTime = 0;
}

clsStopWatch::~clsStopWatch()
{
	mTimeRecord.mEndTime = time(NULL);
}

TimeRecord& clsGetImagesApp::clsGetImagesReport::GetStateTimeRecord(clsGetImagesApp::State state)
{
	if (state < 0 || state >= clsGetImagesApp::kMaxState)
		throw std::runtime_error("clsGetImagesReport::GetTimeRecord: request for invalid time record");

	return mStateTimes[state];
}

#endif

void HandleSignalTerminate(int signal)
{
	exitASAP = 1;
}

static int MakeDirectory(std::string& dir)
{
	std::string command("mkdir ");
	command += dir;
	system(command.c_str());

	return 0;
}

vector<std::string*>* MakeIPAddressList(char** addresses)
{
	vector<std::string*>* ipList = new vector<std::string*>();

	unsigned char* ip = reinterpret_cast<unsigned char*>(*addresses++);

	while (ip)
	{
		std::string* ipString = new std::string();
		char buf[30];

		ipString->append(itoa(ip[0], buf, 10));
		ipString->append(".");
		ipString->append(itoa(ip[1], buf, 10));
		ipString->append(".");
		ipString->append(itoa(ip[2], buf, 10));
		ipString->append(".");
		ipString->append(itoa(ip[3], buf, 10));

		ipList->push_back(ipString);

		ip = reinterpret_cast<unsigned char*>(*addresses++);
	}

	return ipList;
}

vector<std::string*>* GetAOLAddresses()
{
	WSADATA wsadata;
	int error;
	HOSTENT* ht = NULL;
	vector<std::string*>* returnValue = NULL;

	error = WSAStartup(DESIRED_WINSOCK_VERSION, &wsadata);
	if (error) goto BAD;

	if (wsadata.wVersion < MINIMUM_WINSOCK_VERSION)
		goto BAD;

	ht = gethostbyname(gAOLName);
	if (!ht) goto BAD;

	returnValue = MakeIPAddressList(ht->h_addr_list);

BAD:
	WSACleanup();
	return returnValue;
}

void MungeHost(std::string& itemURL)
{
	if (gAOLAddresses == NULL)
	{
		gAOLAddresses = GetAOLAddresses();
		currentAOLItem = gAOLAddresses->size();
	}

	std::string::size_type foundAt = itemURL.find(gAOLName);

	if (foundAt == std::string::npos) 
		return;

	if (--currentAOLItem < 0)
		currentAOLItem = gAOLAddresses->size() - 1;

	itemURL.replace(foundAt, strlen(gAOLName), *(*gAOLAddresses)[currentAOLItem]);
	
}


clsGetImagesApp::clsGetImagesApp(Options& options) :
	mOptions(options),
	mpDatabase(NULL),
	mpMarketPlaces(NULL),
	mpMarketPlace(NULL),
	mpUsers(NULL),
	mpItems(NULL),
	mIDExceptions(NULL),
	mGroupThumbDB(NULL),
	mCompletion(NULL),
	mCachedInputSequence(0),
	mCachedLastInputSequence(0),
	mLastSequence(0)
{
	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();
	mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	mpUsers	= mpMarketPlace->GetUsers();
	mpItems = new clsItems(mpMarketPlace);

	MakeDirectory(mOptions.mImageDB);
	MakeDirectory(mOptions.mBadImages);
	MakeDirectory(mOptions.mCompleted);
	MakeDirectory(mOptions.mFailed);
	MakeDirectory(mOptions.mBad);
	MakeDirectory(mOptions.mThumbDB);
	MakeDirectory(mOptions.mImages);
	MakeDirectory(mOptions.mBackupDB + gOldBackup);
	MakeDirectory(mOptions.mBackupDB + gLatestBackup);

	mHTTPDownloadOptions.mMaxSimultaneousDownloads = mOptions.mMaxDownloads;
	mHTTPDownloadOptions.mSelectWaitTime = clsHTTPDownload::kDefaultMaxSelectWaitTime;
	mHTTPDownloadOptions.mMaxConnectWaitTime = mOptions.mMaxConnectWait;
	mHTTPDownloadOptions.mMaxRequestWaitTime = mOptions.mMaxRequestWaitTime;
	mHTTPDownloadOptions.mMaxReadWaitTime = mOptions.mMaxReadWaitTime;
	mHTTPDownloadOptions.mMaxRedirects = mOptions.mMaxRedirects;
	
	mIDExceptions = new clsIDExceptions(mOptions.mIDExceptions.c_str());

	if (clsHTTPDownload::Startup(mHTTPDownloadOptions)) 
		Fail("Couldn't start httpDownload");

	mCompletion = new clsMyHTTPCompletion(*this);

	clsHTTPDownload::SetCompletionRoutine(mCompletion);

	mGroupThumbDB = new clsGroupThumbDB(mOptions.mThumbDB.c_str(), false, "c:\\StockImage1.jpg");

	// Have to call next before we can call current - oracle 7.3 limitation
	mpDatabase->GetNextGallerySequence();

	// Have to call next before we can call current - oracle 7.3 limitation
	// We subtract off a hundred because we might of lost some sequence
	// numbers if oracel crashed
	mCachedLastInputSequence = mpDatabase->GetNextGalleryReadSequence();
	mCachedInputSequence = mCachedLastInputSequence - 100;
	if (mCachedInputSequence < 0)
		mCachedInputSequence = 0;

	mCurrentStateFile = fopen("c:\\getimages.state", "r+c");
	if (!mCurrentStateFile)
	{
		mCurrentStateFile = fopen("c:\\getimages.state", "w+c");
		RecordState(kProcessingItems, "0");
	}

	if (!mCurrentStateFile)
		Fail("Couldn't open state file");

#if 0
	void* signalResult = signal(SIGTERM, HandleSignalTerminate);
	if (signalResult == SIG_ERR)
		Fail("Couldn't set signal handler");

	raise

	while (!exitASAP)
	{
		std::cout << "waiting" << "\n";
		Sleep(1000);
	}

	std::cout << "exiting cleanly" << "\n";
#endif

#if 0
	clsGalleryChangedItem readItem;
	bool itemFound = mpDatabase->GetGalleryChangedItem(95421, readItem);
#endif

#if 0
	clsItemGalleryInfo info;

	info.mState = 4;

//	strcpy(info.mURL, "http://www.ebay.com/picts/logo.gif");
	// kakiyama 07/16/99
	strcpy(info.mURL, mpMarketPlace->GetHTMLPath);
	strcat(info.mURL, "picts/logo.gif");

	info.mXSize = 20;
	info.mYSize = 30;

	bool setInfoResult = mpDatabase->SetItemGalleryInfo(13, info);

	clsItemGalleryInfo readInfo;

	bool getInfoResult = mpDatabase->GetItemGalleryInfo(13, readInfo);

	clsGalleryChangedItem item;
	item.mID = 2;
	item.mSequenceID = 1;

	strcpy(item.mURL, "http://efbrowninc.com/images/sl89.jpg");

	item.mState = 0;
	item.mStartTime = time(NULL);
	item.mEndTime = time(NULL);
	item.mAttempts = 0;
	item.mLastAttempt = time(NULL);

	bool appendResult = mpDatabase->AppendGalleryChangedItem(item);

	clsGalleryChangedItem readItem;
	bool itemFound = mpDatabase->GetGalleryChangedItem(item.mSequenceID, readItem);

	bool changeResult = mpDatabase->SetGalleryChangedItemState(item.mSequenceID, 79331, kGalleryPermanentBadImage);
	bool getResult = mpDatabase->GetGalleryChangedItem(79331, readItem);
	bool deleteResult = mpDatabase->DeleteGalleryChangedItem(79331);
#endif
	
#if 0
	for (int i = 0; i < 10000; i++)
	{
		int nextSequence = mpDatabase->GetNextGallerySequence();

		clsGalleryChangedItem item;
		item.mID = i;
		item.mSequenceID = nextSequence;
		strcpy(item.mURL, "http://members.geocities.com/~johndoe/ebay/somepicture.jpg");
		item.mState = 2;
		item.mStartTime = time(NULL);
		item.mEndTime = time(NULL);
	
		bool appendResult = mpDatabase->AppendGalleryChangedItem(0, item);
	}

	for (int i = 10000; i < 20000; i++)
	{
		int nextSequence = mpDatabase->GetNextGallerySequence();

		clsGalleryChangedItem item;
		item.mID = i;
		item.mSequenceID = nextSequence;
		strcpy(item.mURL, "http://members.geocities.com/~johndoe/ebay/somepicture.jpg");
		item.mState = 2;
		item.mStartTime = time(NULL);
		item.mEndTime = time(NULL);
	
		bool appendResult = mpDatabase->AppendGalleryChangedItem(0, item);

		int readSequence = GetNextInputSequence();

		clsGalleryChangedItem readItem;
		bool itemFound = mpDatabase->GetGalleryChangedItem(0, readSequence, readItem);

		bool deleteResult = mpDatabase->DeleteGalleryChangedItem(readSequence);
	}
#endif

}

clsGetImagesApp::~clsGetImagesApp()
{
	delete mIDExceptions;
	// FIXME: mlh 12/9/98 Shouldn't we delete the database accessors?
}

void clsGetImagesApp::SendCompletionReport()
{
	vector<std::string>::iterator begin = mOptions.mNotify.begin();
	vector<std::string>::iterator end = mOptions.mNotify.end();
	
	for (; begin != end; ++begin)
	{
		clsMail mailConnection;  

		ostrstream* mailStream = mailConnection.OpenStream();
		if (!mailStream)
			break;
 
		*mailStream 
			<< "GETIMAGES REPORT - \n\n"
			<< "STATE		DURATION\n"
			<< "=====		========\n"
			;

		for (int i = 0; i < kMaxState; ++i)
		{
			TimeRecord& timeRecord = mReport.GetStateTimeRecord(static_cast<State>(i));

			*mailStream
				<< gStateReportLabels[i]
				<< "\t\t"
				<< (timeRecord.mEndTime - timeRecord.mStartTime)
				<< "\n"
				;
		}

		*mailStream
			<< "\n"
			;

		*mailStream 
			<< "RESULT		COUNT\n"
			<< "======		=====\n"
			;

		for (int j = 0; j < kGalleryMaxResultCode; ++j)
		{
			*mailStream
				<< gGalleryResultLabels[j]
				<< "\t\t"
				<< mReport.GetResultCodeCount(static_cast<GalleryResultCode>(j))
				<< "\n"
				;
		}

		mailConnection.Send(
			const_cast<char*>(begin->c_str()), 
			"ops@ebay.com", 
			"GetImages Report");
	}

	// Clear out the report for the next cycle
	mReport.Reset();
}

void clsGetImagesApp::Run()
{
	std::string value;

	RecoverState(mState, value);

	if (mState == kProcessingItems)
		DoRecoverDB();

	while (true)
	{
		switch (mState)
		{
		case kProcessingItems:
			DoProcessingItems(value);
			break;
		case kPropagatingDB:
			DoPropagateDB(value);
			break;
		case kNotifying:
			DoNotify(value);
			break;
		case kCleanOutCompletedItems:
			DoCleanOutCompletedItems(value);
			SendCompletionReport();
			break;		
		default:
			break;
		}

		value = "";
	}
}

enum ImageFormat
{
	kUnknownImage,
	kJPEG,
	kGIF,
	kPNG,
	kBMP,
	kTIFF
};

ImageFormat IdentifyFile(char* const file)
{
	FILE* f = fopen(file, "rb");

	if (!f)
		return kUnknownImage;

	char buf[48];

	size_t readResult = fread(buf, sizeof(buf), 1, f);

	fclose(f);

	if (readResult != 1)
		return kUnknownImage;

	// JPEG
	if (memcmp(buf, "\xff\xd8", 2) == 0)
		return kJPEG;

	// GIF
	if (memcmp(buf, "GIF8", 4) == 0)
		return kGIF;

	// PNG
	if (memcmp(buf, "\x89PNG\r\n\x1a\n", 8) == 0)
		return kPNG;

	// BMP
	if (memcmp(buf, "BM", 2) == 0)
		return kBMP;

	// TIFF
	if (memcmp(buf, "MM\x00\x2a", 4) == 0
		|| memcmp(buf, "II\x2a\x00", 4) == 0)
		return kTIFF;

	return kUnknownImage;
}

void clsGetImagesApp::RecordState(State state, const char* value)
{
	const char* stateLabel = NULL;

	switch (state)
	{
	case kProcessingItems:
		stateLabel = kProcessingLabel;
		break;
	case kPropagatingDB:
		stateLabel = kPropagatingDBLabel;
		break;
	case kNotifying:
		stateLabel = kNotifyingLabel;
		break;
	case kCleanOutCompletedItems:
		stateLabel = kCleanOutCompletedItemsLabel;
		break;
		
	default:
		break;
	}

	rewind(mCurrentStateFile);
	fprintf(mCurrentStateFile, "%s\t%s\n", stateLabel, value);
	fflush(mCurrentStateFile);
}

void clsGetImagesApp::RecoverState(State& state, std::string& value)
{
	rewind(mCurrentStateFile);

	char stateString[256];
	char valueString[256];

	fscanf(mCurrentStateFile, "%s %s\n", stateString, valueString);

	if (!strcmp(stateString, kProcessingLabel))
		state = kProcessingItems;
	else if (!strcmp(stateString, kPropagatingDBLabel))
		state = kPropagatingDB;
	else if (!strcmp(stateString, kNotifyingLabel))
		state = kNotifying;
	else if (!strcmp(stateString, kCleanOutCompletedItemsLabel))
		state = kCleanOutCompletedItems;

	value = valueString;
}

void clsGetImagesApp::PostToErrorList(int sequence, GalleryResultCode result)
{
	clsGalleryChangedItem item;

	bool itemFound = mpDatabase->GetGalleryChangedItem(sequence, item);
	if (!itemFound)
	{
		std::cout << "Couldn't find sequence " << sequence << " to post to error list\n";
		return;
	}

	bool changeResult = mpDatabase->SetGalleryChangedItemState(item.mSequenceID, 
		item.mSequenceID, item.mAttempts + 1, result);
	if (!changeResult)
	{
		std::cout << "Couldn't change state " << sequence << "\n";
		return;
	}
}

void clsGetImagesApp::PostItemGalleryState(int sequence, GalleryResultCode result, int xSize, int ySize)
{
	clsGalleryChangedItem item;

	bool itemFound = mpDatabase->GetGalleryChangedItem(sequence, item);

	if (!itemFound)
	{
		std::cout << "PostItemGalleryState: Couldn't find sequence " << sequence << "\n";
		return;
	}

	clsItemGalleryInfo info;

	bool getInfoResult = mpDatabase->GetItemGalleryInfo(item.mID, info);

	if (!getInfoResult)
	{
		std::cout << "PostItemGalleryState: Couldn't get item " << item.mID << "\n";
		return;
	}

	if (result != kGallerySuccess)
	{
		clsItem* theItem = mpItems->GetItem(item.mID);
		if (!theItem)
		{
			std::cout << "PostItemGalleryState: Couldn't get item detail" << item.mID << "\n";
			return;
		}

		clsUser* theUser = mpUsers->GetUser(theItem->GetSeller());
		if (!theUser)
		{
			std::cout << "PostItemGalleryState: Couldn't get user " << item.mID << "\n";
			delete theItem;
			return;
		}

		const char* userEmailAddress = theUser->GetEmail();
		const char* fromEmailAddress = "support@ebay.com"; //mpMarketPlace->GetSupportEmail();
		const char* emailSubject = "Problem with getting gallery image for your eBay item";

//#define GALLERY_FAILMAIL_TEST
#ifdef GALLERY_FAILMAIL_TEST
		if (userEmailAddress && strstr(userEmailAddress, "@ebay.com"))
		{
#endif 

		int reportErrorToUserResult = ReportErrorToUser(
					userEmailAddress,
					fromEmailAddress,       
					emailSubject,       
					item.mID,        
					theItem->GetTitle(),
					info.mURL,
					result);

		if (!reportErrorToUserResult)
		{
			std::cout << "PostItemGalleryState: Couldn't email user " << item.mID << "\n";
		}

#ifdef GALLERY_FAILMAIL_TEST
		}
#endif 
					  
		info.mState = result;
		info.mXSize = 0;
		info.mYSize = 0;

		bool setInfoResult = mpDatabase->SetItemGalleryInfo(item.mID, info);

		if (!setInfoResult)
		{
			std::cout << "PostItemGalleryState: Couldn't set item failure " << item.mID << "\n";
		}

		delete theUser;
		delete theItem;
	}
	else
	{
		info.mState = result;
		info.mXSize = xSize;
		info.mYSize = ySize;

		bool setInfoResult = mpDatabase->SetItemGalleryInfo(item.mID, info);

		if (!setInfoResult)
		{
			std::cout << "PostItemGalleryState: Couldn't set item " << item.mID << "\n";
			return;
		}
	}
}

void clsGetImagesApp::PostSuccess(int sequence, int xSize, int ySize, GalleryResultCode result)
{
	bool setGalleryStateResult = mpDatabase->SetGalleryChangedItemState(sequence, sequence, 0, result);
	if (!setGalleryStateResult)
	{
		std::cout << "Couldn't set state of sequence " << sequence << "\n";
		PostItemGalleryState(sequence, kGalleryInternalError, xSize, ySize);
		return;
	}
//	PostItemGalleryState(sequence, result, xSize, ySize);
}

void clsGetImagesApp::DoCleanOutCompletedItems(std::string& value)
{
	clsStopWatch timer(mReport.GetStateTimeRecord(kCleanOutCompletedItems));

	int startRange;
	int endRange;

	mpDatabase->GetGallerySequenceRange(startRange, endRange);

	char numString[32];

	RecordState(kCleanOutCompletedItems, itoa(startRange, numString, 10));

	// If we have mLastSequence then we will use this as our last sequence to
	// process so that we don't waste time looking at sequence ids that
	// haven't been processed yet
	if (mLastSequence)
		endRange = mLastSequence;

	while (endRange != 0 && startRange <= endRange)
	{
		try 
		{
			clsGalleryChangedItem item;

			bool itemFound = mpDatabase->GetGalleryChangedItem(startRange, item);
			if (!itemFound)
			{
				++startRange;
				continue;
			}

			mReport.IncrementResultCode(static_cast<GalleryResultCode>(item.mState));

			switch (item.mState)
			{
			case kGalleryNotProcessed:
				break;
			case kGallerySuccess:
				{
					PostItemGalleryState(startRange, 
						static_cast<GalleryResultCode>(item.mState), 0, 0);
					bool deleteGalleryChangedItemResult = mpDatabase->DeleteGalleryChangedItem(startRange);
					if (!deleteGalleryChangedItemResult)
						std::cout << "Couldn't delete changed sequence " << startRange << "\n";
				}
				break;
			case kGalleryBadURL:
			case kGalleryBadProtocol:
			case kGalleryHostNameDoesNotExist:
			case kGalleryPermanentBadImage:
			case kGalleryImageFormat:
			case kGalleryInternalError:
			case kGalleryBadImage:
				{
					PostItemGalleryState(startRange, 
						static_cast<GalleryResultCode>(item.mState), 0, 0);
					bool deleteGalleryChangedItemResult = mpDatabase->DeleteGalleryChangedItem(startRange);
					if (!deleteGalleryChangedItemResult)
						std::cout << "Couldn't delete changed sequence " << startRange << "\n";
				}
				break;
			case kGalleryServerNotAvailable:
			case kGalleryItemNotFound:
			case kGalleryFailedDownload:
				if (item.mAttempts > 3)
				{
					PostItemGalleryState(startRange, 
						static_cast<GalleryResultCode>(item.mState), 0, 0);
					bool deleteGalleryChangedItemResult = mpDatabase->DeleteGalleryChangedItem(startRange);
					if (!deleteGalleryChangedItemResult)
						std::cout << "Couldn't delete changed sequence " << startRange << "\n";
				}
				break;
			default:
				{
					std::cout << "Unknown item state " << item.mState << " sequence " << startRange << "\n";
					bool deleteGalleryChangedItemResult = mpDatabase->DeleteGalleryChangedItem(startRange);
					if (!deleteGalleryChangedItemResult)
						std::cout << "Couldn't delete changed sequence " << startRange << "\n";
				}
				break;
			}
		}
		catch (...)
		{
			std::cout << "Caught error in DoCleanOutCompletedItems: Non Fatal - continuing" << "\n";

			// We are just going to continue processing
			// We skip the item, but it will get picked up on the next
			// time through DoCleanOutCompletedItems

#if 0
			char numString[32];

			RecordState(kCleanOutCompletedItems, itoa(startRange, numString, 10));

			exit(-1);
#endif
		}
			
		++startRange;
	}

	mState = kProcessingItems;
}


void clsGetImagesApp::DoProcessingItems(std::string& value)
{
	clsStopWatch timer(mReport.GetStateTimeRecord(kProcessingItems));

	mGroupThumbDB->Open();

	// We will terminate this process in 30 minutes.
	time_t endTime = time(NULL) + (60 * 1); 

	int startRange;
	int endRange;

	mpDatabase->GetGallerySequenceRange(startRange, endRange);

	char numString[32];

	RecordState(kProcessingItems, itoa(startRange, numString, 10));

	std::string mungedHost;
	std::string dstFileName;

	// We reset mLastSequence in case we don't process anything
	mLastSequence = 0;

	bool keepProcessing = true;

	while (keepProcessing)
	{
		while (endRange != 0 && startRange <= endRange)
		{
			try
			{
				if (endTime < time(NULL))
					goto FINISHED;

				std::cout << "Trying sequence " << startRange << "\n";

				clsGalleryChangedItem item;
			
				bool itemFound = mpDatabase->GetGalleryChangedItem(startRange, item);

				if (!itemFound)
				{
					++startRange;
					continue;
				}

				// This is the sequence that we found and tried to process
				mLastSequence = startRange;

				std::cout << "Found item " << item.mID << "\n";

				if (item.mID == 0)
				{
					// This is our signal to quit processing new images and to move
					// to the next stage which is database propagation
					bool deleteGalleryChangedItemResult =
						mpDatabase->DeleteGalleryChangedItem(startRange);
					continue;
				}

				// This is an admin signal to delete an image out of the thumbnail db
				if (item.mStartTime == 0 && item.mEndTime == 0)
				{
					mGroupThumbDB->ClearThumb(item.mID);

					bool deleteGalleryChangedItemResult =
						mpDatabase->DeleteGalleryChangedItem(startRange);
					continue;
				}

				clsUrlCracker cracker;

				int crackResult = cracker.Crack(item.mURL, strlen(item.mURL));

				if (crackResult || !cracker.host || !cracker.absolute)
				{
					PostToErrorList(startRange, kGalleryBadURL);
					++startRange;
					continue;
				}

				if (strcmp(cracker.access, "http"))
				{
					PostToErrorList(startRange, kGalleryBadProtocol);
					++startRange;
					continue;
				}

				if (mIDExceptions->IsException(item.mID))
				{
					PostToErrorList(startRange, kGalleryPermanentBadImage);
					++startRange;
					continue;
				}

				mungedHost = cracker.host;
				MungeHost(mungedHost);

				dstFileName = itoa(item.mID, numString, 10);

				clsHTTPDownload::StartDownload(cracker.host,
					mungedHost.c_str(), 
					cracker.absolute, 
					dstFileName.c_str(),
					"",
					startRange);

				while (!clsHTTPDownload::IsReady())
					clsHTTPDownload::Cycle();

			}
			catch (...)
			{
				std::cout << "Caught error in DoProcessingItems: Non Fatal - continuing" << "\n";

				// We are just going to continue processing
				// We skip the item, but it will get picked up on the next
				// time through DoProcessingItems
			}

			++startRange;
		}

		time_t startWaitTime = time(NULL);
		int lastProcessed = endRange;

		do
		{
			if (endTime < time(NULL))
				goto FINISHED;

			time_t endWaitTime = time(NULL);
			int seconds = endWaitTime - startWaitTime;

			std::cout << "Waiting for new items for last " << seconds << " seconds" << "\n";

			clsHTTPDownload::Cycle();
			Sleep(4000); // Wait 4 seconds
			mpDatabase->GetGallerySequenceRange(startRange, endRange);
			
			if (lastProcessed > 0)
				startRange = lastProcessed + 1;

		} while (endRange == 0 || endRange <= lastProcessed);

	}

FINISHED:

	while (clsHTTPDownload::Cycle()) 
		;

	mGroupThumbDB->Close();

	mState = kPropagatingDB;
}

int DeleteDirectoryContents(char* directory)
{
	clsDirectoryWalker directoryWalker(directory, "*.map");

	while (directoryWalker.GetNextItem())
	{
		char fullPath[1024];
		strcpy(fullPath, directory);
		strcat(fullPath, directoryWalker.GetName());

		int removeResult = remove(fullPath);
		
		if (removeResult)
		{
			std::cout << "Failed to remove " << fullPath << "\n";
		}
	}

	return 0;
}

int clsGetImagesApp::DoBackupDB()
{
	char commandString[1024];
	int systemReturnValue;

	// First delete the old backup
	strcpy(commandString, mOptions.mBackupDB.c_str());
	strcat(commandString, gOldBackup);
	strcat(commandString, "\\");
	int deleteResult = DeleteDirectoryContents(commandString);
	if (deleteResult)
		return systemReturnValue;

	// Delete the directory
	strcpy(commandString, "rmdir ");
	strcat(commandString, mOptions.mBackupDB.c_str());
	strcat(commandString, gOldBackup);
	systemReturnValue = system(commandString);
	if (systemReturnValue && systemReturnValue != 2)
		return systemReturnValue;

	// Rename the latest backup
	char oldName[512];
	char newName[512];

	strcpy(oldName, mOptions.mBackupDB.c_str());
	strcat(oldName, gLatestBackup);

	strcpy(newName, mOptions.mBackupDB.c_str());
	strcat(newName, gOldBackup);

	int moveResult = rename(oldName, newName);
	if (moveResult)
		return moveResult;

	// Backup the database - makes the directory
	strcpy(commandString, "xcopy /i ");
	strcat(commandString, mOptions.mThumbDB.c_str());
	if (commandString[strlen(commandString)-1] == '\\')
		commandString[strlen(commandString)-1] = '\0';
	strcat(commandString, " ");
	strcat(commandString, mOptions.mBackupDB.c_str());
	strcat(commandString, gLatestBackup);

	return system(commandString);
}

int clsGetImagesApp::DoRecoverDB()
{
	char commandString[1024];
	int systemReturnValue;

	// First delete the current db
	strcpy(commandString, mOptions.mThumbDB.c_str());
	int deleteResult = DeleteDirectoryContents(commandString);
	if (deleteResult)
		return systemReturnValue;

	// Restore the database
	strcpy(commandString, "xcopy ");
	strcat(commandString, mOptions.mBackupDB.c_str());
	strcat(commandString, gLatestBackup);
	strcat(commandString, " ");
	strcat(commandString, mOptions.mThumbDB.c_str());
	if (commandString[strlen(commandString)-1] == '\\')
		commandString[strlen(commandString)-1] = '\0';

	return system(commandString);
}

void clsGetImagesApp::DoPropagateDB(std::string& value)
{
	RecordState(kPropagatingDB, "none");
	clsStopWatch timer(mReport.GetStateTimeRecord(kPropagatingDB));

	DoBackupDB();

	vector<std::string>::iterator begin = mOptions.mThumbnailDBDestination.begin();
	vector<std::string>::iterator end = mOptions.mThumbnailDBDestination.end();
	
	for (; begin != end; ++begin)
	{
		char commandString[1024];

		strcpy(commandString, "rcp -b -r \\thumbdb ");
		strcat(commandString, begin->c_str());

		int systemReturn = system(commandString);
		if (systemReturn)
		{
			std::cout << "Failed thumb DB install " << commandString << "\n";
		}
	}

	mState = kNotifying;
}

// wget -t 10 -O dontcare -T 5 "http://marty.corp.ebay.com/aw-cgi/thumbServe.dll?SwitchDB?switchDB=now
void clsGetImagesApp::DoNotify(std::string& value)
{
	RecordState(kNotifying, "none");
	clsStopWatch timer(mReport.GetStateTimeRecord(kNotifying));

	vector<std::string>::iterator begin = mOptions.mThumbDBNotify.begin();
	vector<std::string>::iterator end = mOptions.mThumbDBNotify.end();
	
	for (; begin != end; ++begin)
	{
		char commandString[1024];

		strcpy(commandString, "c:\\bin\\wget -t 10 -O dontcare -T 5 http://");
		strcat(commandString, begin->c_str());
		strcat(commandString, "/ed/thumbServe.dll?SwitchDB?switchDB=now");
		
		int systemReturn = system(commandString);
		if (systemReturn)
		{
			std::cout << "Failed thumb DB install " << commandString << "\n";
		}
	}

	mState = kCleanOutCompletedItems;
}

int clsGetImagesApp::GetNextInputSequence()
{
	if (mCachedInputSequence < mCachedLastInputSequence)
		return ++mCachedInputSequence;
	else
		return mpDatabase->GetNextGalleryReadSequence();
}

int clsGetImagesApp::GetCurrentOutputSequence()
{
	return mpDatabase->GetCurrentGallerySequence();
}

void ReportImageGearErrors()
{
	int nErrCount = IG_error_check();

	for (int i = 0; i < nErrCount; i++)
	{
		char fileName[256];
		int lineNumber;
		int errorCode;
		long value1;
		long value2;
		char* errorString = NULL;

		IG_error_get(i, fileName, 256, &lineNumber, &errorCode,
						 &value1, &value2);

		for (int nFind = 0 ; ; nFind++)
		{
			if (ErrString[nFind].ErrCode == errorCode)
			{
				errorString = (LPSTR)ErrString[nFind].ErrString;
				break;
			}

			if (ErrString[nFind].ErrCode == IGE_LAST_ERROR_NUMBER)
			{
				errorString = "Error not found.";
				break;
			}
		}

		std::cout << i << " " 
			<< fileName << " "
			<< lineNumber << " "
			<< "Code=" << errorCode << " "
			<< "(" << value1 << ", " << value2 << ")" << "\n"
			<< errorString << "\n";
	}
}

int GetThumb(char* const file, int maxSize, int quality, char* buf, unsigned long& bufLength, int& newXSize, int& newYSize)
{

	ImageFormat imageFormat = IdentifyFile(file);

	if (imageFormat == kUnknownImage || imageFormat == kGIF || imageFormat == kPNG)
		return -1;

	HIGEAR sourceH = NULL;
	HIGEAR destinationH = NULL;

	try
	{
		int errCount = 0;

#if 0
		try
		{
#endif
			errCount = IG_load_file(file, &sourceH);
			if (errCount != 0)
				throw errCount;
#if 0
		}
		// Maybe it was a gif so lets try and get it another way.
		catch(...)
		{
			libungif::GifFileType* gifFileType = libungif::DGifOpenFileName(file);
			if (!gifFileType)
				throw "not a gif either";

			int slurpResult = libungif::DGifSlurp(gifFileType);
			if (slurpResult != GIF_OK)
				throw "couldn't read gif";

			int closeResult = libungif::DGifCloseFile(gifFileType);

		}
#endif

		AT_DIMENSION width;
		AT_DIMENSION height;
		UINT bitsPerPixel;

		errCount = IG_image_dimensions_get(sourceH, &width, &height, &bitsPerPixel);
		if (errCount != 0)
			throw errCount;

		if (bitsPerPixel < 24)
		{
			errCount = IG_IP_color_promote(sourceH, IG_PROMOTE_TO_24);
			if (errCount != 0)
				throw errCount;

			bitsPerPixel = 24;
		}

		if (width == 0 || height == 0)
			throw 0;

		AT_DIMENSION newWidth = maxSize - 2; // - 2 is to leave a one pixel border
		AT_DIMENSION newHeight = maxSize - 2;

		if (width > height)
		{
			newHeight = (((float) height) / ((float) width)) * maxSize;
		}
		else
		{
			newWidth = (((float) width) / ((float) height)) * maxSize;
		}

		errCount = IG_IP_resize(sourceH, newWidth, newHeight, IG_INTERPOLATION_BILINEAR);
		if (errCount != 0)
			throw errCount;

		errCount = IG_save_JPEG_quality_set(quality);
		if (errCount != 0)
			throw errCount;

		// Make square image with black background for overlaying the image
		// The black background is for free; it defaults to black
		errCount = IG_image_create_DIB(maxSize, maxSize, bitsPerPixel, NULL, &destinationH);
		if (errCount != 0)
			throw errCount;

#if 0
		// White out the background because it defaults to black
		// Accusoft says this is the only way to do this
		AT_PIXEL pattern[3];
		pattern[0] = 255;
		pattern[1] = 255;
		pattern[2] = 255;

		for (int yPos = 0; yPos < maxSize; ++yPos)
			for (int xPos = 0; xPos < maxSize; ++xPos)
				errCount = IG_DIB_pixel_set(destinationH, xPos, yPos, pattern);
#endif

		AT_DIMENSION xPosition = (maxSize - newWidth) / 2;
		AT_DIMENSION yPosition = (maxSize - newHeight) / 2;

		errCount = IG_IP_merge(destinationH, sourceH, NULL, xPosition, yPosition, IG_ARITH_OVER);
		if (errCount != 0)
			throw errCount;

		// We make all images square to maxSize
		newXSize = maxSize; 
		newYSize = maxSize;

		errCount = IG_save_mem(destinationH, buf, 0, bufLength, 1, 0, IG_SAVE_JPG, &bufLength);
		if (errCount != 0)
			throw errCount;
	}
	catch (int count)
	{
		if (count)
			ReportImageGearErrors();

		if (IG_image_is_valid(sourceH))
			IG_image_delete(sourceH);

		if (IG_image_is_valid(destinationH))
			IG_image_delete(destinationH);

		return -1;
	}
	catch (char* errorStr)
	{
		std::cout << errorStr << "\n";
	}
	catch (...)
	{
	}

	if (IG_image_is_valid(sourceH))
		IG_image_delete(sourceH);

	if (IG_image_is_valid(destinationH))
		IG_image_delete(destinationH);

	return 0;
}

void clsGetImagesApp::Complete(int result, int httpResult, const char* location, int callbackParam)
{
	char sourcePath[512];
	strcpy(sourcePath, location);

	if (result != kGallerySuccess || _access(sourcePath, 0) )
	{
		PostToErrorList(callbackParam, static_cast<GalleryResultCode>(result));
		return;
	}

	int maxImageSize = mOptions.mImageSize;

	clsGalleryChangedItem readItem;
	bool itemFound = mpDatabase->GetGalleryChangedItem(callbackParam, readItem);

	if (itemFound && readItem.mGalleryType == FeaturedGallery)
		maxImageSize = 140;

	char thumbBuffer[10000];
	unsigned long thumbBufferLength = sizeof(thumbBuffer);
	int newXSize;
	int newYSize;

	std::cout << "Thumbnailing image " << location << "\n";

	int getThumbResult = GetThumb(sourcePath,
		maxImageSize,
		mOptions.mQuality,
		thumbBuffer,
		thumbBufferLength,
		newXSize,
		newYSize);

	// We get rid of the source image even if it didn't thumbnail
	int removeResult = remove(sourcePath);

	if (removeResult)
	{
		int err = errno;

		std::cout << "Failed to remove source image " << sourcePath << "\n";
	}

	if (getThumbResult)
	{
		PostToErrorList(callbackParam, kGalleryImageFormat);
		return;
	}

	int addResult = 0;

	try
	{
		mGroupThumbDB->AddThumb(thumbBuffer, thumbBufferLength, atoi(location));
	}
	catch (...)
	{
		PostToErrorList(callbackParam, kGalleryInternalError);
		return;
	}

	PostSuccess(callbackParam, newXSize, newYSize, kGallerySuccess);
}

void clsGetImagesApp::Fail(char* error)
{
	int result = clsHTTPDownload::Shutdown();

	throw std::runtime_error(error);
}


clsGetImagesApp::clsIDExceptions::clsIDExceptions(const char* idExceptionFileName)
{
	std::ifstream in(idExceptionFileName, ios::in);
	if (!in.is_open()) 
		throw std::runtime_error("Couldn\'t open configuration file");

	while (!in.eof())
	{
		unsigned long id = 0;
		in >> id;

		if (id)
			mExceptionIDs.push_back(id);
	}
}

bool clsGetImagesApp::clsIDExceptions::IsException(unsigned long id)
{
	vector<unsigned long>::iterator begin = mExceptionIDs.begin();
	vector<unsigned long>::iterator end = mExceptionIDs.end();

	for (; begin != end; ++begin)
	{
		if (*begin == id)
			return true;
	}

	return false;
}


int clsGetImagesApp::ReportErrorToUser(const char* to,
					  const char* from,       
					  const char* subject,       
					  int itemID,        
					  const char* itemTitle,       
					  const char* url,       
					  GalleryResultCode failType)
{ 
	clsMail mailConnection;  
	ostrstream* mailStream = mailConnection.OpenStream();
	if (!mailStream)
		return 0;
 
	*mailStream 
		<< "Dear Seller:\n"     
		"\n"     
		"DO NOT REPLY TO THIS MESSAGE. SEE BELOW FOR INSTRUCTIONS.\n"     
		"\n";

	*mailStream 
		<< "Thank you for choosing to be part of eBay\'s Gallery! "
		<< "Unfortunately, your item\n"
		<< "#" << itemID << "\n"
		<< itemTitle << "\n"
		<< "has not yet been added to the gallery. \n\n";
 
	*mailStream 
		<< "The problem is:\n";
 
	switch (failType) 
	{ 
	case kGalleryBadURL:  
		*mailStream 
			<< "The URL for your image does not appear to valid.\n\""   
			<< url << "\n"
			<< "\"\n\n"   
			<< "Check your image URL to make sure that it is correct.\n"
			<< "You can correct your URL at: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 
	case kGalleryBadProtocol:  
		*mailStream 
			<< "The protocol you selected is not supported. Only HTTP is supported.\n\n"   
			<< url << "\n"
			<< "Check your image URL to make sure that it is correct and starts with "
			<< "\"http://\".\n"
			<< "You can correct your URL at: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99		
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 
	case kGalleryPermanentBadImage:  
		*mailStream 
			<< "Your image is corrupted.\n"   
			<< "Please contact customer support at support@ebay.com\n"
			<< "for more information about your Gallery image. "
			<< "If you would like to supply a different image right away, you "
			<< "can correct your URL at: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 
	case kGalleryHostNameDoesNotExist:  
		*mailStream 
			<< "The host in your url could not be found.\n"   
			<< url << "\n"
			<< "Check your image URL to make sure that it is correct.\n"
			<< "You can correct your URL at: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID 
			<< " \n\n";
		break; 
	case kGalleryServerNotAvailable:  
		*mailStream 
			<< "We could not connect to the Web server named specified by:\n"   
			<< url << "\n"
			<< "Check your image URL to make sure that it is correct.\n"
			<< "You can correct your URL at: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";			
		break; 
	case kGalleryItemNotFound:  
		*mailStream 
			<< "The Web server where we looked for your image says that it can't find the image:\n"   
			<< url << "\n"
			<< "\"\n\n"   
			<< "Check your image URL to make sure that it is correct.\n"
			<< "You can correct your URL at: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 
	case kGalleryFailedDownload:  
		*mailStream 
			<< "We were unable to download your image named:\n"   
			<< url << "\n"
			<< "Either the Internet was very busy when we tried to download "
			<< "your image and we could not get through, or the Web server "   
			<< "hosting your image is not "
			<< "functioning correctly. Contact your Web server administrator to make "
			<< "sure the server is okay. \n\n"
			<< "You can provide a new URL, or request that we try to download your "
			<< "image again, by going to: "
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 
	case kGalleryImageFormat:  
		*mailStream 
			<< "Your image is in a format we cannot process:\n"   
			<< url << "\n"
			<< "Convert the image into a jpeg and repost to your web site.\n"  
			<< "When you are done, you can tell us where your new image is located by going to: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 
	case kGalleryBadImage:  
		*mailStream 
			<< "Your image appears to be corrupted:\n"   
			<< url << "\n"
			<< "Try posting a new version of the image to your web site.\n" 
			<< "When you are done, you can tell us where your new image is located by going to: \n"
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 
	case kGalleryInternalError:  
		*mailStream 
			<< "We were unable to identify the exact problem with:\n"   
			<< url << "\n"
			<< "Please contact customer support at support@ebay.com \n"
			<< "for more information about your Gallery image. \n\n"
			<< "You can also provide a new URL, or request that we try to download your "
			<< "image again, by going to: "
	//		<< "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?DisplayGalleryImagePage?item="
	// kakiyama 07/16/99
			<< mpMarketPlace->GetCGIPath()
			<< "eBayISAPI.dll?DisplayGalleryImagePage?item="
			<< itemID
			<< " \n\n";
		break; 

	default:  
		break; 
	}
 

	*mailStream << "Thank you for using eBay! \n";

	return mailConnection.Send(
		const_cast<char*>(to), 
		const_cast<char*>(from), 
		const_cast<char*>(subject));
}
 
