/*	$Id: CobrandTable.h,v 1.2 1999/02/21 02:21:54 josh Exp $	*/
//
//	File:	CobrandTable.h
//
//	Class:	CobrandTable
//
//	Author:	Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 11/20/97 Craig Huang - Created
//
#ifndef COBRANDTABLE_INCLUDED






typedef enum	
{
	COHEADER,
	COFOOTER,		
} eCOFILETYPE;

struct CoBrandRec 
{
	int	iID;
	char * pStream;		
	eCOFILETYPE fType;	
	DWORD dwlength;
	CoBrandRec(){ pStream = NULL; dwlength=0;}
	~CoBrandRec() { if( pStream ) delete pStream; }	
} ;

typedef CoBrandRec *CoBrandTable;

class clsHtmlExpand;
class CobrandTable
{   
  public:	
	  CobrandTable();
	  ~CobrandTable();
	  DWORD ReadAllHeadersAndFooters();	
	  DWORD CleanUpCoBrandTable();
	  DWORD ForceReload();
	  bool IsHeaderMissing() {return mHeaderMissing;}
	  bool	GetHeaderAndFooterStream( clsHtmlExpand *pClsExpand, int iPartnerID, int iPageType);	

  protected:	
	  DWORD ReadHFFileIntoCoBrandTable(char *szCurrentFile, char ** ppStream, DWORD *pLength);
	  

  private:
	  CoBrandTable	mpCoBrandTable;
	  char * mpDefaultHeaderStream;
	  char * mpDefaultFooterStream;
	  DWORD	 mDefaultHeaderLength;
	  DWORD	 mDefaultFooterLength;
	  int	mTotalCobrandRec;
	  bool	mHeaderMissing;
};

#define COBRANDTABLE 1
#endif
