/* $Id: clsUserPage.h,v 1.3 1999/02/21 02:46:52 josh Exp $ */
#ifndef clsUserPage_h
#define clsUserPage_h

// This class encapsulates information about a user page.

class clsUserPage
{
private:
	// The user information.
	long mUserId;
	clsUser *mpUser; // In case we need it, hmm?

	// Some information about which page we're viewing.
	int	mPageNumber;
	char mTitle[256];
	long mCategoryNumber;

	// Some statistics about the page.
	time_t mLastUpdate;
	time_t mLastViewed;
	long mNumViews;
	long mPageSize;
	long mPageTextSize;

	// The data dictionary.
	char *mpDataDictionary;
	bool mMine; // If true, I own it.

public:
	clsUserPage() : mUserId(0L), mpUser(NULL), mPageNumber(0),
		mCategoryNumber(0L), mLastUpdate((time_t) 0), mLastViewed((time_t) 0), mNumViews(0L),
		mPageSize(0L), mPageTextSize(0L), mpDataDictionary(NULL), mMine(true)
	{ mTitle[0] = '\0'; }
	~clsUserPage() { if (mMine) delete [] mpDataDictionary; }

	// Setters.
	void SetUserId(long userId) { mUserId = userId; }
	void SetUser(clsUser *pUser) { mpUser = pUser; }

    // The = 0 here is a specific hack to definitively prevent pages other than '0' from
    // being created (most likely by miscreant users!)
    // You _MUST_ remove this in order to allow saving/viewing of multiple pages.
	void SetPage(int num) { mPageNumber = 0/*num*/; }
	void SetTitle(const char *pTitle) { strncpy(mTitle, pTitle, 255); mTitle[255] = '\0'; }
	void SetCategory(long category) { mCategoryNumber = category; }
	
	void SetLastUpdate(time_t lastUpdate) { mLastUpdate = lastUpdate; }
	void SetLastView(time_t lastViewed) { mLastViewed = lastViewed; }
	void SetNumViews(long numViews) { mNumViews = numViews; }
	void SetPageSize(long pageSize) { mPageSize = pageSize; }
	void SetPageTextSize(long pageTextSize) { mPageTextSize = pageTextSize; }

	// We own the dictionary unless you tell us otherwise.
	void SetDataDictionary(char *pBlob) { mpDataDictionary = pBlob; mMine = true; }

	void OwnUserPage() { mMine = true; }
	void DisownUserPage() { mMine = false; }

	// Getters.
	long GetUserId() { return mUserId; }
	clsUser *GetUser() { return mpUser; }

	int GetPage() { return mPageNumber; }
	const char *GetTitle() { return mTitle; }
	long GetCategory() { return mCategoryNumber; }

	time_t GetLastUpdate() { return mLastUpdate; }
	time_t GetLastView() { return mLastViewed; }
	long GetNumViews() { return mNumViews; }
	long GetPageSize() { return mPageSize; }
	long GetPageTextSize() { return mPageTextSize; }

	char *GetDataDictionary() { return mpDataDictionary; }

    // To save the page.
    void SavePage();
    void RemovePage();
    void SaveCategory();
    void DeleteFromCategory();
    void LoadPage(bool withDictionary);
    void AddView();
};

#endif /* clsUserPage_h */
