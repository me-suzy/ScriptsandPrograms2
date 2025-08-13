/*	$Id: clsHtmlExpand.h,v 1.2 1999/02/21 02:21:59 josh Exp $	*/
//
//	File:	clsHtmlExpand.h
//
//	Class:	clsHtmlExpand
//
//	Author:	Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 11/20/97 Craig Huang - Created
//
#ifndef CLSHTMLEXPAND_INCLUDED

#define STRING_VARIABLE(name)		\
private:							\
	char	*mp##name;				\
public:								\
	char	*Get##name();			\
	void	Set##name(char *pNew);	
#define INT_VARIABLE(name)			\
private:							\
	int		m##name;				\
public:								\
	int		Get##name();			\
	void	Set##name(int new_value);

extern FILE	*mpLog;



struct DataRec 
{	
	char * pData;		
	DWORD dwLen;	
	DataRec(){ pData = NULL; dwLen = 0;}
	~DataRec() {}	
};






int TRACEIT(char *format,...);

class CobrandTable;
class clsHtmlExpand
{
   
  public:	
	clsHtmlExpand(); 
	bool  NeedCoBrandProcessing(){ return mNeedBoBrandProcessing; }	
	void  SetNeedCoBrandProcessing(bool bProcess) { mNeedBoBrandProcessing = bProcess; }	
	bool ProcessContent(CHttpFilterContext* pCtxt , PHTTP_FILTER_RAW_DATA pRawData);	
	bool SetPartnerIDString(CHttpFilterContext* pCtxt,PHTTP_FILTER_URL_MAP pMapInfo);
	~clsHtmlExpand();		
	bool SaveHeader(CHttpFilterContext* pCtxt , PHTTP_FILTER_RAW_DATA pRawData);		
	bool ProcessHeaderAndFirstChunkContent(CHttpFilterContext* pCtxt , PHTTP_FILTER_RAW_DATA pRawData);
	INT_VARIABLE(HeaderLength);
	INT_VARIABLE(FooterLength);
	char * mpHeaderStream;
	char * mpFooterStream;
	void SetCobrandTable(CobrandTable *pCobrandTable) { mpCobrandTable = pCobrandTable;}
	CobrandTable *mpCobrandTable;
	bool Initialize(); 

protected:    
	bool	RecalculateContentLength(CHttpFilterContext* pCtxt);
	bool	ReplaceWithPartnerString(char * pSource, DWORD dwLen);	
	bool	CobrandAppendedBuffer( CHttpFilterContext* pCtxt, PHTTP_FILTER_RAW_DATA pRawData);
	bool	SaveToAppendBuffer( CHttpFilterContext* pCtxt, PHTTP_FILTER_RAW_DATA pRawData, char *pChunk, DWORD dwLen );

private:	
	void TagConst(char *pcName,char *pcValue,char *pcTagName,const char *pcTagValue);
	INT_VARIABLE(ContentLength);
	INT_VARIABLE(PartnerID);
	INT_VARIABLE(PageType);	
	INT_VARIABLE(ContentHeaderLength);

	bool	mFirstChunkDone, mNeedBoBrandProcessing;  		
	DWORD	mOldContentLength;	
	char	* mpContentHeader;				
	int		mDiff1;		
	bool	mbPageTypeTagExist;
	bool	mbHeaderTagExist;	
	bool	mContentLengthFieldMissing;	
	int		mDataRecCnt;	
	DataRec	*DataTable;
	int		mNumberOfChunk;	
};


#define CLSHTMLEXPAND_INCLUDED 1
#endif
