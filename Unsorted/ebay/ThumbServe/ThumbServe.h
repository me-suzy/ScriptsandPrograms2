#if !defined(AFX_THUMBSERVE_H__1FDE563A_1F7B_11D2_8EA5_006008267A09__INCLUDED_)
#define AFX_THUMBSERVE_H__1FDE563A_1F7B_11D2_8EA5_006008267A09__INCLUDED_

// THUMBSERVE.H - Header file for your Internet Server
//    ThumbServe Extension

#include "clsThumbDB.h"

/*
#ifdef _MSC_VER
#undef min
#undef max
#endif

#include <algo.h>
#include <vector.h>


#include <algorithm>
#include <vector>
using namespace std;
*/

//#include <vector>
//#include <algorithm>
//using namespace std;

#include "resource.h"

class clsThumbDB;

class CThumbServeFilter : public CHttpFilter
{
public:
	CThumbServeFilter();
	~CThumbServeFilter();

// Overrides
	// ClassWizard generated virtual function overrides
		// NOTE - the ClassWizard will add and remove member functions here.
		//    DO NOT EDIT what you see in these blocks of generated code !
	//{{AFX_VIRTUAL(CThumbServeFilter)
	public:
	virtual BOOL GetFilterVersion(PHTTP_FILTER_VERSION pVer);
	virtual DWORD OnPreprocHeaders(CHttpFilterContext* pCtxt, PHTTP_FILTER_PREPROC_HEADERS pHeaders);
	//}}AFX_VIRTUAL

	//{{AFX_MSG(CThumbServeFilter)
	//}}AFX_MSG
};

class CThumbServeExtension : public CHttpServer
{
public:
	CThumbServeExtension();
	~CThumbServeExtension();

// Overrides
	// ClassWizard generated virtual function overrides
		// NOTE - the ClassWizard will add and remove member functions here.
		//    DO NOT EDIT what you see in these blocks of generated code !
	//{{AFX_VIRTUAL(CThumbServeExtension)
	public:
	virtual BOOL GetExtensionVersion(HSE_VERSION_INFO* pVer);
	//}}AFX_VIRTUAL

	int WriteHeader(CHttpServerContext* pCtxt, int contentLength);

	// TODO: Add handlers for your commands here.
	// For example:

	void Default(CHttpServerContext* pCtxt);
	int Thumb(CHttpServerContext* pCtxt, int item);
	int SwitchDB(CHttpServerContext* pCtxt, const char* newDB);
	int Head(CHttpServerContext* pCtxt, int item);
	int ModifiedSince(CHttpServerContext* pCtxt, int item, const char* date);
	int MakeNoisy(CHttpServerContext* pCtxt, int item);
	int MakeUnNoisy(CHttpServerContext* pCtxt, int item);
	int GetNoisyThumbCount(CHttpServerContext* pCtxt);
	int EbayRedirect(CHttpServerContext *pCtxt, const char* pURL);

	DECLARE_PARSE_MAP()

	//{{AFX_MSG(CThumbServeExtension)
	//}}AFX_MSG
private:
//	vector<int> mNoisyThumbs;

	clsThumbDB* mpThumbDB;

	void NewDBCheck();
	const char* const GetThumb(int itemID, int& size);

};


//{{AFX_INSERT_LOCATION}}
// Microsoft Developer Studio will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_THUMBSERVE_H__1FDE563A_1F7B_11D2_8EA5_006008267A09__INCLUDED)
