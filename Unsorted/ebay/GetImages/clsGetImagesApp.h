/*	$Id: clsGetImagesApp.h,v 1.2 1999/02/21 02:22:20 josh Exp $	*/
//
// File: clsGetImagesApp.h
//
// Class: clsGetImagesApp
//

#ifndef CLSGETIMAGESAPP_H
#define CLSGETIMAGESAPP_H

#include "clsHTTPDownload.h"
#include <string>

class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;
class clsItems;
class clsMyHTTPCompletion;
struct Options;
class clsGroupThumbDB;
class clsGetImagesReport;
class clsGetImagesApp;

struct TimeRecord
{
	time_t mStartTime;
	time_t mEndTime;
};

class clsStopWatch
{
public:
	clsStopWatch(TimeRecord& time);
	~clsStopWatch();
private:
	TimeRecord& mTimeRecord;
};

class clsGetImagesApp : public clsApp
{
public:
	enum State
	{
		kProcessingItems,
		kPropagatingDB,
		kNotifying,
		kCleanOutCompletedItems,
		kMaxState
	};

	class clsGetImagesReport
	{
	public:
		clsGetImagesReport();

		void Reset();

		TimeRecord& GetStateTimeRecord(State state);
		int GetResultCodeCount(GalleryResultCode result)
		{
			return mResultHistogram[result];
		}

		void IncrementResultCode(GalleryResultCode result);

	private:
		TimeRecord mStateTimes[kMaxState];
		int mResultHistogram[kGalleryMaxResultCode];
	};

	clsGetImagesApp(Options& options);
	~clsGetImagesApp();

	void Run();

protected:

	friend clsMyHTTPCompletion;
	friend clsGetImagesReport;

	class clsIDExceptions 
	{
	public:
		clsIDExceptions(const char* idExceptionFileName);

		bool IsException(unsigned long id);

	private:
		vector<unsigned long> mExceptionIDs;
	};

	clsDatabase* mpDatabase;
	clsMarketPlaces* mpMarketPlaces;
	clsMarketPlace* mpMarketPlace;
	clsUsers* mpUsers;
	clsItems* mpItems;
	Options& mOptions;
	FILE* mCurrentStateFile;
	clsHTTPDownload::Options mHTTPDownloadOptions;
	clsIDExceptions* mIDExceptions;
	clsGroupThumbDB* mGroupThumbDB;
	clsMyHTTPCompletion* mCompletion;
	int mCachedInputSequence;
	int mCachedLastInputSequence;
	State mState;
	int mLastSequence;
	clsGetImagesReport mReport;

	void RecordState(State state, const char* value);
	void RecoverState(State& state, std::string& value);

	void PostItemGalleryState(int sequence, GalleryResultCode result, int xSize, int ySize);
	void PostToErrorList(int sequence, GalleryResultCode result);
	void PostSuccess(int sequence, int xSize, int ySize, GalleryResultCode result);

	void DoProcessingItems(std::string& value);
	int DoBackupDB();
	int DoRecoverDB();
	void DoPropagateDB(std::string& value);
	void DoNotify(std::string& value);
	void DoCleanOutCompletedItems(std::string& value);

	void SendCompletionReport();

	int GetNextInputSequence();
	int GetCurrentOutputSequence();

	void Complete(int result, int httpResult, const char* location, int callbackParam);
	void Fail(char* error);

	int ReportErrorToUser(const char* to,
						  const char* from,       
						  const char* subject,       
						  int itemID,        
						  const char* itemTitle,       
						  const char* url,       
						  GalleryResultCode failType);
};

class clsMyHTTPCompletion : public clsHTTPCompletion
{
public:
	clsMyHTTPCompletion(clsGetImagesApp& app);
	void Complete(int result, int httpResult, const char* location, int callbackParam);

private:
	clsGetImagesApp& mApp;
};


inline clsMyHTTPCompletion::clsMyHTTPCompletion(clsGetImagesApp& app) :
	mApp(app)
{
}

inline void clsMyHTTPCompletion::Complete(int result, int httpResult, const char* location, int callbackParam)
{
	mApp.Complete(result, httpResult, location, callbackParam);
}

#endif // CLSGETIMAGESAPP_H
