/*	$Id: clsHtmlExpand.cpp,v 1.2 1999/02/21 02:21:58 josh Exp $	*/
//
//	File:	clsHtmlExpand.cpp
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
#include "stdafx.h"
#include <afxtempl.h>
#include <stdio.h>
#include <httpfilt.h> // From ISAPI SDK
#include <httpext.h>  // From ISAPI SDK
#include <search.h>
#include <string.h>
#include <stdio.h>
#include <DIRECT.h>
#include <io.h>
#include <errno.h>
#include "CobrandTable.h"
#include "clsHtmlExpand.h"


#define MAX_WRITEBUFF 8192 // Magic number...

#define MAX_EXPAND    8192 // Filtered HTM max. 8k longer than the original!
#define MAX_TAG         64 // Place for upto 64 different HTMLEx tags
#define MAX_TAGNAME     32 // Max length of tag's name
#define MAX_TAGVALUE  2048 // Max length of tag's expanded value
#undef  OFFSETWAY		
#define MAXOFFSETARRAY  500
#define CHUNKSIZE	32768
#define MAX_PATH_LENGHT 256
#define NUMBERTEN	10
#define EXTRALEN 16
char	PARTPREFIX[]= "aw-part";
char	CGIPARTPREFIX[]= "cgi";
#define SIZEOFPARTPREFIX 7
#define SIZEOFPARTPOSTFIX 3
//const char	szAllPartners[] = "c:\\eBay\\cobrandhtml\\debug\\part*";
//char	szReloadAll[] = "aw-reload-co";
//const char	szeBayAwDirectory[] = "c:\\eBay\\cobrandhtml\\debug\\";
//#define DEFAULTHEADER "DefaultHeader.htm"
//#define DEFAULTFOOTER "DefaultFooter.htm"
#define STARTPAGEID 4
#define	IDLENGTH 4
#define FILETYPEINDEX 8
#define HEADERFILETYPECHAR 'h'
#define FOOTERFILETYPECHAR 'f'
#define SIDEBARFILETYPECHAR 's'
#define PAGETYPETOKEN		"<!--page type "
#define FULLPAGETYPETOKEN	"<!--page type x-->"
#define HEADERTOKEN			"<!--header-->"
#define FOOTERTOKEN			"<!--footer-->"
#define SPACEFOOTERTOKEN	"             "
#define CONTENTLENGTHTOKEN  "Content-Length:"
//#define HANDFCOUNT 2

//#define _DEBUG 
//#undef _DEBUG 
#ifdef _DEBUG
#undef _DEBUGWIN
//#define _DEBUGWIN
#endif


#ifdef _DEBUG
	#ifdef _DEBUGWIN
		#define TRACEIT TRACE	
	#else
		int TRACEIT(char *format,...)
		{
/*			char temp[1000]; 
			va_list argptr;
			va_start(argptr,format);
			vsprintf(temp,format,argptr);
			va_end(argptr);
			int i= strlen(temp);
			mpLog = fopen("c:\\cofilter.log", "a+");
			fwrite( temp, sizeof( char ), i, mpLog );
			fclose(mpLog);*/
			return true;
		}
	#endif
#endif




#define STRING_METHODS(variable)				\
char *clsHtmlExpand::Get##variable()					\
{												\
	return mp##variable;						\
}												\
void clsHtmlExpand::Set##variable(char *pNew)			\
{												\
	if (mp##variable)							\
		delete mp##variable;					\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	return;										\
}
#define INT_METHODS(variable)					\
int clsHtmlExpand::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsHtmlExpand::Set##variable(int newval)	\
{												\
	m##variable	= newval;						\
	return;										\
} 

INT_METHODS(ContentLength);			// Question ID
INT_METHODS(PartnerID);			// Question Description
INT_METHODS(PageType);			// Question Description
INT_METHODS(HeaderLength);
INT_METHODS(FooterLength);
INT_METHODS(ContentHeaderLength);





void clsHtmlExpand::TagConst(char *pcName,char *pcValue,char *pcTagName,const char *pcTagValue)
{
 strcpy(pcName,pcTagName);
 strcpy(pcValue,pcTagValue);
};


bool clsHtmlExpand::SaveHeader(CHttpFilterContext* pCtxt , PHTTP_FILTER_RAW_DATA pRawData)
{
	DWORD iLen;	
	char * pSrc;	


	if ( !pCtxt || !pRawData)
	{
#ifdef _DEBUG
	TRACEIT("pCtxt or pRawData is NULL");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;	
	}
	
	if (!AfxIsValidAddress(pRawData->pvInData, pRawData->cbInData, false))
	{
#ifdef _DEBUG
	TRACEIT("bad pRawData pointer found in header\n");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;	
	}	
	
	
	iLen =		pRawData->cbInData;			
	mpContentHeader = (char *) pCtxt->AllocMem( iLen+1, (DWORD) NULL );
	if( mpContentHeader == NULL )
	{
		SetNeedCoBrandProcessing(false);	
		return false;
	}
	memset( mpContentHeader , '\0', iLen+1 );
	memcpy( mpContentHeader , pRawData->pvInData, iLen);	
	// If no content header token, can't do it
	pSrc = strstr(mpContentHeader, CONTENTLENGTHTOKEN );
	if( !pSrc )
	{
		mContentLengthFieldMissing = true;
		SetContentHeaderLength( iLen );
	}
	else
	{
		mContentLengthFieldMissing = false;
	}

#ifdef _DEBUG
	TRACEIT("Header Content=%s\n", mpContentHeader);
#endif
	
	return true;
}





bool clsHtmlExpand::RecalculateContentLength(CHttpFilterContext* pCtxt)
{
	char *pOrigin, *pCurrent;	
	char *pBeforeContentLen;
	char *pAfterContentLen;
	char	szCurrentLen[32];	
	int		ConLength=0;
	int		iIncreaseLen;
	int		i=0;
	char	*pNewContentHeaderBuffer;
	int		iOldHeaderLength, iNewHeaderLength;
#ifdef _DEBUG
	TRACEIT("In recalculate content length\n");
#endif
	if ( !pCtxt )
	{
#ifdef _DEBUG
	TRACEIT("pCtxt is NULL");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;	
	}

	if ( ! mpContentHeader )
	{
#ifdef _DEBUG
	TRACEIT("mpContentHeader is NULL");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;
	}
	if ( mContentLengthFieldMissing )
	{	
		return true;
	}

	
#ifdef _DEBUG
	TRACEIT(" in recalculate old header=%s\n", mpContentHeader);
#endif
	iOldHeaderLength = strlen ( mpContentHeader ); 
	
	if ( ! mbPageTypeTagExist )
		mDiff1 = 0;
	if ( ! mbHeaderTagExist )
		SetHeaderLength(0);	
	iIncreaseLen = GetHeaderLength() + GetFooterLength()- mDiff1- ((mbHeaderTagExist == true) ? strlen(HEADERTOKEN) : 0);	
	pOrigin = strstr ( mpContentHeader , CONTENTLENGTHTOKEN );
	memset( szCurrentLen, '\0', 32);
	pCurrent = szCurrentLen;
	if( pOrigin )
	{
		pBeforeContentLen = pOrigin ;
		pAfterContentLen = pBeforeContentLen;
		while ( pAfterContentLen && *pAfterContentLen && *pAfterContentLen >= ' ' )
		{
				if( isdigit(*pAfterContentLen) )
					szCurrentLen[i++] = *pAfterContentLen;				
				pAfterContentLen++;
		}
		ConLength = atoi (szCurrentLen);
		mNumberOfChunk = ( ConLength / 8192 )+1;
		ConLength += iIncreaseLen;
		mOldContentLength = ConLength;				

#ifdef _DEBUG
		TRACEIT("Old Header=%s\nnumber of chunk= %d", mpContentHeader, mNumberOfChunk);
#endif
		if( pBeforeContentLen && *pBeforeContentLen )
			*pBeforeContentLen = '\0';		
		pNewContentHeaderBuffer = (char *) pCtxt->AllocMem( iOldHeaderLength+32, (DWORD) NULL );		
		if ( !pNewContentHeaderBuffer )
		{
#ifdef _DEBUG
	TRACEIT("Can not allocate content header");
#endif
			SetNeedCoBrandProcessing(false);	
			return false;
		}
		if( pAfterContentLen )
			sprintf (pNewContentHeaderBuffer , "%s%s %d%s", mpContentHeader, CONTENTLENGTHTOKEN, ConLength, pAfterContentLen);		

#ifdef _DEBUG
		
		TRACEIT(" in recalculate New Header=%s\n new content header length=%d", pNewContentHeaderBuffer, strlen(pNewContentHeaderBuffer));
		TRACEIT("old length=%d\n", iOldHeaderLength);		
		TRACEIT("increase length=%d after content=%s \n", iIncreaseLen, pAfterContentLen);
		
#endif
#ifdef _DEBUG
	TRACEIT("Finish recal\n");
#endif
		if (pNewContentHeaderBuffer)
		{
			mpContentHeader = pNewContentHeaderBuffer;
			iNewHeaderLength = strlen(mpContentHeader);
			SetContentHeaderLength( iNewHeaderLength );
			mOldContentLength += iNewHeaderLength;
		}
		return true;
	}
	else
	{
		SetNeedCoBrandProcessing(false);			
		return false;
	}
}



bool clsHtmlExpand::ProcessHeaderAndFirstChunkContent(CHttpFilterContext* pCtxt , PHTTP_FILTER_RAW_DATA pRawData)
{
	DWORD dwLen;	
	char * pDestBuffer;
	char * pSrc;		
	char	szPageType[5];	
	char *	pTotalBuffer;
	int		iPageType;
	int		totalLength;
	char	*pContentBeforeHeaderStart, *pContentAfterHeaderStart, *pContentAfterFooterStart;	
	DWORD		dwContentBeforeHeader, dwContentHeaderLength, dwHeaderLength, dwContentAfterHeader;
	DWORD	dwCurrentLength, dwFooterLength, dwContentAfterFooter = 0;
	int		iSpaceCnt = 0;
	bool	bLessThan8K;
	bool	bFooterTagExist = false;
	int		iHeaderToken = 0;
	int		iFooterToken = 0;
	
#ifdef _DEBUG
	TRACEIT("Get in first chunk process content\n");
#endif	

	
	if ( !pCtxt || !pRawData)
	{
#ifdef _DEBUG
	TRACEIT("pCtxt or pRawData is NULL");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;	
	}

	if (!AfxIsValidAddress(pRawData->pvInData, pRawData->cbInData, false))
	{
#ifdef _DEBUG
	TRACEIT("bad pRawData pointer found in first chunk\n");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;	
	}

	// init some of the data process variable
	
	// Allocate the memory for input buffer
	dwLen =		pRawData->cbInData;			
	if ( dwLen < MAX_WRITEBUFF )
		bLessThan8K = true;
	else
		bLessThan8K = false;
	pDestBuffer = (char *) pCtxt->AllocMem( dwLen+1, (DWORD) NULL );
	if( pDestBuffer == NULL )
	{
#ifdef _DEBUG
		TRACEIT("Can not allocate first chunk data");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;
	}	
	memset( pDestBuffer , '\0', dwLen+1 );
	memcpy( pDestBuffer, pRawData->pvInData, dwLen);	

	// Search for the page type token, if not found, set it to the start
	pSrc= strstr( pDestBuffer, PAGETYPETOKEN);
	if( ! pSrc )
	{
#ifdef _DEBUG
		TRACEIT("Can not find page type token in first chunk data");
#endif	
		pSrc = pDestBuffer;			
		mbPageTypeTagExist = false;	
	}
	else
		mbPageTypeTagExist = true;	
	if( mbPageTypeTagExist )
		pContentBeforeHeaderStart = pSrc + strlen (FULLPAGETYPETOKEN);						
	else
		pContentBeforeHeaderStart = pSrc;						
	if( pContentBeforeHeaderStart == NULL )
	{
		SetNeedCoBrandProcessing(false);	
		return false;
	}		


	while ( pContentBeforeHeaderStart && *pContentBeforeHeaderStart && *pContentBeforeHeaderStart != '<' )
		pContentBeforeHeaderStart++;		

	// read page type from first chunk of memory
	if ( mbPageTypeTagExist )
	{
		memset( szPageType , '\0', 5);
		memcpy( szPageType, pDestBuffer + strlen(PAGETYPETOKEN), 1 );
		iPageType = atoi( szPageType );
		SetPageType ( iPageType );
	}
	else
		SetPageType ( -1 );

	// Get specified stream from partner id and page type
	mpCobrandTable->GetHeaderAndFooterStream( this, GetPartnerID(), GetPageType());

	// Calculate the diff like cr and lf
	mDiff1 = pContentBeforeHeaderStart - pSrc;		

	// Search for the header token
	pSrc= strstr( pDestBuffer, HEADERTOKEN);
	if( pSrc == NULL )
	{
#ifdef _DEBUG
		TRACEIT("Header token not found");
#endif
		mbHeaderTagExist = false;
		pContentAfterHeaderStart = NULL;
		iHeaderToken = 0;
	}
	else
	{
		mbHeaderTagExist = true;
		pContentAfterHeaderStart = pSrc + strlen(HEADERTOKEN);
		iHeaderToken = strlen(HEADERTOKEN);
	}
	if( mbHeaderTagExist && pContentAfterHeaderStart == NULL )
	{
		SetNeedCoBrandProcessing(false);	
		return false;	
	}

	// Search for the footer token, only do it when it's less than 8 k		
	pSrc= strstr( pDestBuffer, FOOTERTOKEN);
	if( pSrc == NULL )
	{	
		iFooterToken = 0;
		bFooterTagExist = false;
		if ( pDestBuffer + dwLen )
			pContentAfterFooterStart = pDestBuffer + dwLen;						
	}
	else
	{			
		iFooterToken = 	strlen(FOOTERTOKEN);			
		bFooterTagExist = true;						
		if ( pSrc + iFooterToken ) 
			pContentAfterFooterStart = pSrc + iFooterToken;				
	}	
	// Calculate all the string len for memcpy
	if( bFooterTagExist )
		dwFooterLength = GetFooterLength();	
	else
		dwFooterLength = 0;
	if ( mbHeaderTagExist )
	{
		if ( pContentAfterHeaderStart && pContentBeforeHeaderStart )
			dwContentBeforeHeader = pContentAfterHeaderStart - iHeaderToken - pContentBeforeHeaderStart ;
		if ( dwContentBeforeHeader < 0 )
			dwContentBeforeHeader = 0;
		if ( pContentAfterHeaderStart && pContentAfterFooterStart )
			dwContentAfterHeader = pContentAfterFooterStart - iFooterToken - pContentAfterHeaderStart;
		if ( dwContentAfterHeader < 0 )
			dwContentAfterHeader = 0;
		if ( pDestBuffer + dwLen && pContentAfterFooterStart)
			dwContentAfterFooter = pDestBuffer + dwLen - pContentAfterFooterStart;
		if ( dwContentAfterFooter < 0 )
			dwContentAfterFooter = 0;
	}		
	else if ( ! mbHeaderTagExist )
	{			
		dwContentBeforeHeader = dwLen - mDiff1 - iFooterToken;
		if( dwContentBeforeHeader < 0 )
			dwContentBeforeHeader = 0;
		dwContentAfterHeader = 0;						
		dwContentAfterFooter = 0;
	}		
	
	
	// Get new content length with added header and footer
	RecalculateContentLength( pCtxt );				
	// Get all content length and total content length	
	dwContentHeaderLength = GetContentHeaderLength();
	
	if ( mbHeaderTagExist )
		dwHeaderLength = GetHeaderLength();	
	else
		dwHeaderLength = 0;
	totalLength = dwContentHeaderLength + dwContentBeforeHeader + dwHeaderLength 
		+ dwContentAfterHeader + dwFooterLength + dwContentAfterFooter + iFooterToken;		
	pRawData->cbInBuffer = totalLength;	
	pRawData->cbInData = totalLength;	
	
	pTotalBuffer = (char *) pCtxt->AllocMem( totalLength+1, (DWORD) NULL );
	memset(pTotalBuffer, totalLength + 1 , '\0');
	if( pTotalBuffer == NULL )
	{
#ifdef _DEBUG
	TRACEIT("contentheader=%d dwContentBeforeHeader =%d dwHeaderLength =%d dwContentAfterHeader =%d dwFooterLength =%d dwContentAfterFooter =%d iFooterToken=%d\n",dwContentHeaderLength,dwContentBeforeHeader , dwHeaderLength , dwContentAfterHeader , dwFooterLength , dwContentAfterFooter , iFooterToken);
	TRACEIT("Can not allocate first combined chunk memory");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;
	}			
	// Copy string to the total buffer which contain the first chunk
	/****/
	// Copy the HTTP header
	if ( mpContentHeader ) 
	{
		memcpy( pTotalBuffer, mpContentHeader , dwContentHeaderLength);
		dwCurrentLength = dwContentHeaderLength;
	}

	// Copy the content before header
	if ( pTotalBuffer + dwCurrentLength && pContentBeforeHeaderStart )
	{
		memcpy( pTotalBuffer + dwCurrentLength, pContentBeforeHeaderStart, dwContentBeforeHeader);
		dwCurrentLength += dwContentBeforeHeader;
	}
	// Only if mpHeaderStream exists
	if( mbHeaderTagExist && pTotalBuffer + dwCurrentLength && mpHeaderStream && dwHeaderLength )
	{
		memcpy( pTotalBuffer + dwCurrentLength, mpHeaderStream , dwHeaderLength);
		dwCurrentLength += dwHeaderLength;
	}
	// Copy the content after header
	if ( mbHeaderTagExist && (pTotalBuffer + dwCurrentLength) && pContentAfterHeaderStart )
	{
		memcpy( pTotalBuffer + dwCurrentLength, pContentAfterHeaderStart, dwContentAfterHeader);
		dwCurrentLength += dwContentAfterHeader;
	}
	
	if( bFooterTagExist )
	{		
		if ( pTotalBuffer + dwCurrentLength && mpFooterStream && dwFooterLength >0 
			&& AfxIsValidAddress(mpFooterStream, dwFooterLength, false))
		{
		memcpy( pTotalBuffer + dwCurrentLength, mpFooterStream, dwFooterLength);
		dwCurrentLength += dwFooterLength;		
		}
		if ( pTotalBuffer + dwCurrentLength && pContentAfterFooterStart && dwContentAfterFooter >0 
			&& AfxIsValidAddress(pContentAfterFooterStart, dwContentAfterFooter, false))
		{
		memcpy( pTotalBuffer + dwCurrentLength, pContentAfterFooterStart, dwContentAfterFooter);
		dwCurrentLength += dwContentAfterFooter;		
		}
		if( pTotalBuffer + dwCurrentLength && iFooterToken >0 )
		{
		memcpy( pTotalBuffer + dwCurrentLength, SPACEFOOTERTOKEN, iFooterToken);						
		dwCurrentLength += iFooterToken;		
		}
#ifdef _DEBUG
		
		if( ! mContentLengthFieldMissing )
		{
			if ( mOldContentLength != dwCurrentLength )		
				TRACEIT("last chunk size error\n");
			else
				TRACEIT("last chunk size correct\n");
		}
		else 
			TRACEIT("Finish process content");		
#endif			
	}
	else if( ! mContentLengthFieldMissing )
		mOldContentLength -= dwCurrentLength;
	// Get first chunk with replaced string		
	pRawData->pvInData = (void *) pTotalBuffer;		
	mFirstChunkDone = true;
	if ( bLessThan8K )
	{
		ReplaceWithPartnerString( pTotalBuffer,  totalLength); 		
#ifdef _DEBUG
		TRACEIT("Finish first one and replace len=%d\n", totalLength);
#endif
		SetNeedCoBrandProcessing(false);		
		return true;
	}	
	else
	{			
		if ( mContentLengthFieldMissing )
			ReplaceWithPartnerString( pTotalBuffer,  totalLength); 		
		else
			SaveToAppendBuffer( pCtxt, pRawData, pTotalBuffer, totalLength );	
	}
	
#ifdef _DEBUG
	TRACEIT("dwLen=%d\n", dwLen);
	TRACEIT("after ProcessHeaderAndFirstChunkContent, total len= %d content h=%d before h=%d header=%d af=%d\n", totalLength, dwContentHeaderLength, dwContentBeforeHeader, dwHeaderLength, dwContentAfterHeader);	
#endif
	return true;
}


bool	clsHtmlExpand::SaveToAppendBuffer( CHttpFilterContext* pCtxt, PHTTP_FILTER_RAW_DATA pRawData, char *pChunk, DWORD dwLen )
{		
	if( DataTable == NULL)
	{
		DataTable = (DataRec *) pCtxt->AllocMem( ((mNumberOfChunk+1) * sizeof(DataRec)) , (DWORD) NULL );		
	}

	pRawData->pvInData = NULL;	
	pRawData->cbInBuffer = 0;	
	pRawData->cbInData = 0;		
	
	DataTable[mDataRecCnt].pData = pChunk;
	DataTable[mDataRecCnt].dwLen = dwLen;
	mDataRecCnt ++;		
	
#ifdef _DEBUG
	TRACEIT("append to buffer chunk no %d len=%d\n", mDataRecCnt, dwLen);
#endif	
	return true;
	
}

bool	clsHtmlExpand::CobrandAppendedBuffer( CHttpFilterContext* pCtxt, PHTTP_FILTER_RAW_DATA pRawData )
{
	char	*pTotalBuffer;
	DWORD	dwTotalLength = 0;
	DWORD	dwCurrentLength = 0;
	int i;

	if( mDataRecCnt <= 0 )
	{
		SetNeedCoBrandProcessing(false);
		return false;
	}	
	
	for(i = 0; i < mDataRecCnt; i++)
	{
		dwTotalLength += DataTable[i].dwLen;
	}	

	if( dwTotalLength < 0 )
	{
		SetNeedCoBrandProcessing(false);
		return false;
	}

	pTotalBuffer = (char *) pCtxt->AllocMem( dwTotalLength+1, (DWORD) NULL );	
	if( pTotalBuffer == NULL )
	{		
		SetNeedCoBrandProcessing(false);	
		return false;
	}	
	else
	{	
		if(pTotalBuffer)
			memset(pTotalBuffer, dwTotalLength +1 , '\0');
		for( i = 0; i < mDataRecCnt; i++)
		{			
			if(pTotalBuffer + dwCurrentLength && DataTable[i].pData)
			{
				if (!AfxIsValidAddress(pTotalBuffer + dwCurrentLength, DataTable[i].dwLen, true)
					|| !AfxIsValidAddress(DataTable[i].pData, DataTable[i].dwLen, true))	
					{
#ifdef _DEBUG
					TRACEIT("bad pRawData pointer found in second chunk and after\n");
#endif
					pRawData->pvInData = pTotalBuffer;	
					pRawData->cbInData = pRawData->cbInBuffer = dwCurrentLength;	
					SetNeedCoBrandProcessing(false);	
					return false;	
					}
				else
				{
	
					memcpy(pTotalBuffer + dwCurrentLength, DataTable[i].pData, DataTable[i].dwLen);		
				}
			}
			dwCurrentLength += DataTable[i].dwLen;			
		}			

		if( pTotalBuffer )
			ReplaceWithPartnerString( pTotalBuffer,  dwTotalLength); 		
		pRawData->pvInData = pTotalBuffer;	
		pRawData->cbInData = pRawData->cbInBuffer = dwTotalLength;								

#ifdef _DEBUG
		TRACEIT("cobrand buffer len=%d\n", dwTotalLength);
#endif			
		SetNeedCoBrandProcessing(false);
		return true;
	}
}

bool	clsHtmlExpand::ReplaceWithPartnerString(char * pSource, DWORD dwLen)
{
	char cTagName[32];
	char cTagValue[32];
	char *pcS, *pcF1 ;
	int nameLen , valueLen;
	bool bCont=true;	
	DWORD iCat= 0;			
	int iPartnerID;

	if ( ! pSource	)
	{
#ifdef _DEBUG
		TRACEIT("pSource is NULL");
#endif
		return false;
	}

	
	//EnterCriticalSection(&m_cs);			
	iPartnerID = GetPartnerID();
	//LeaveCriticalSection(&m_cs);

	sprintf(cTagName, "%s%.3d", PARTPREFIX,	0);	
		sprintf(cTagValue,"%s%.3d", PARTPREFIX,	iPartnerID);	
	nameLen = strlen(cTagName);
	valueLen = strlen(cTagValue);

	pcS = pSource;	
	while (bCont)
	{ 
		pcF1 =	strstr(pcS, cTagName);		
		if (pcF1)
		{
		// Concatenate the string before poison tag to dest
		iCat =	pcF1-pcS;			
		// Concatenate the tag want to replace to dest	
		if(pcS + iCat)
			memcpy(pcS + iCat, cTagValue, valueLen);				
		// Update the source pointer
		pcS	=	pcF1 + valueLen;
		if( !pcS )
			bCont= false;
		}
		else
		{	
		bCont= false;
		}
	}		
	return true;
}




bool clsHtmlExpand::ProcessContent(CHttpFilterContext* pCtxt , PHTTP_FILTER_RAW_DATA pRawData)
{			
	DWORD dwLen, dwLastChunk, dwBeforeFooter, dwAfterFooter =0 ;	
	char * pLastChunk, *pTemp, *pSrc;		
	int		dwFooter, iFooterToken = 0;	
	DWORD	dwCurrentLength=0;
	bool	bFooterTagExist;
	bool	bIsLastChunk;

#ifdef _DEBUG
	TRACEIT("Get in process content\n");
#endif	

	if ( !pCtxt || !pRawData)
	{
#ifdef _DEBUG
	TRACEIT("pCtxt or pRawData is NULL");
#endif	
		SetNeedCoBrandProcessing(false);	
		return false;	
	}

	if (!AfxIsValidAddress(pRawData->pvInData, pRawData->cbInData, false))	
	{
#ifdef _DEBUG
	TRACEIT("bad pRawData pointer found in second chunk and after\n");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;	
	}
	
	if ( !mFirstChunkDone )
	{
		return ProcessHeaderAndFirstChunkContent(pCtxt , pRawData);		
	}
	else 
	{			
		dwLen =		pRawData->cbInData;					
		dwFooter = GetFooterLength();
		if( dwLen < MAX_WRITEBUFF )
			bIsLastChunk = true;
		else if( dwLen == MAX_WRITEBUFF )
			bIsLastChunk = false;		

		pTemp = (char *) pCtxt->AllocMem( dwLen+1, (DWORD) NULL );
		if( pTemp == NULL )
		{		
			SetNeedCoBrandProcessing(false);	
			return false;
		}	
		else			
		{
			memset( pTemp , '\0', dwLen );
			memcpy ( pTemp	, pRawData->pvInData, dwLen);					
		}
		pSrc= strstr( pTemp, FOOTERTOKEN);
		if(  !pSrc )
		{
			if ( bIsLastChunk )
			{
	#ifdef _DEBUG
			TRACEIT("Can not find footer token in last chunk\n");
	#endif
			}
			bFooterTagExist = false;				
			
		}
		else 
		{
			bFooterTagExist = true;	
		}

		if( bFooterTagExist )
			iFooterToken = 	strlen(FOOTERTOKEN);			
		else
			iFooterToken = 0;

		if( bIsLastChunk && bFooterTagExist )
		{
			 dwBeforeFooter = pSrc - pTemp;				 
			 if( dwBeforeFooter <0 )
				dwBeforeFooter = 0;
			 dwAfterFooter = dwLen - dwBeforeFooter - iFooterToken;
			 if( dwAfterFooter <0 )
				dwAfterFooter = 0;
		}	
		else
		{
			 dwBeforeFooter = dwLen;				 
			 dwAfterFooter = 0;
		}
#ifdef _DEBUG
		if( bFooterTagExist )
		{
		if ( dwLen + dwFooter - iFooterToken != dwBeforeFooter + dwFooter + dwAfterFooter )
			 TRACEIT("Footer size count error\n");
		else
			 TRACEIT("Footer size count correct\n");
		}
#endif
		 // Prepare the destination buffer, remember to deduct the footer token
		// This size does not need to be changed, because if footer token it's missing 
		// the size gap will be filled up by blank	
		if( bIsLastChunk )
	  		dwLastChunk = dwLen + dwFooter;			
		else
			dwLastChunk = dwLen;
		 
		pLastChunk = (char *) pCtxt->AllocMem( dwLastChunk+1, (DWORD) NULL );
		if( pLastChunk == NULL )
		{				
#ifdef _DEBUG
		TRACEIT("Can not allocate last chunk\n");
#endif
			SetNeedCoBrandProcessing(false);	
			return false;
		} 				
		 else if( pTemp )
		 {
			memcpy( pLastChunk, pTemp, dwBeforeFooter);		
			dwCurrentLength += dwBeforeFooter;
		 }
		 if( bIsLastChunk && pLastChunk + dwCurrentLength && mpFooterStream && dwFooter )
		 {
			memcpy( pLastChunk + dwCurrentLength, mpFooterStream, dwFooter);
			dwCurrentLength += dwFooter;
		 }
		 if( bIsLastChunk && pLastChunk + dwCurrentLength && pSrc + iFooterToken )
		 {
			memcpy( pLastChunk + dwCurrentLength, pSrc + iFooterToken, dwAfterFooter);
			dwCurrentLength += dwAfterFooter;		
		 	memcpy( pLastChunk + dwCurrentLength, SPACEFOOTERTOKEN, iFooterToken);				
		 }
		 pRawData->pvInData = (void *) pLastChunk;	
		 pRawData->cbInData = pRawData->cbInBuffer = dwLastChunk;	
		 if ( mContentLengthFieldMissing )
			ReplaceWithPartnerString( pLastChunk,  dwLastChunk); 		
#ifdef _DEBUG	
		if( ! mContentLengthFieldMissing )
		{
			 if( !bIsLastChunk )
				mOldContentLength -= dwLen;
			 else
			 {
				if ( mOldContentLength != dwBeforeFooter + dwFooter + dwAfterFooter + iFooterToken)
					TRACEIT("last chunk size error\n");
				else
					TRACEIT("last chunk size correct\n");

			 }		
		}
		else if( bIsLastChunk )
		{
			TRACEIT("Finish process content\n");		
		}	
#endif		

		if ( !mContentLengthFieldMissing )
		{
			if( bIsLastChunk )
			{			
				SaveToAppendBuffer( pCtxt, pRawData, pLastChunk, dwLastChunk );
				CobrandAppendedBuffer( pCtxt, pRawData );
			}
			else
			{
				SaveToAppendBuffer( pCtxt, pRawData, pLastChunk, dwLastChunk );
			}
		}
	}			
	return true;
}


clsHtmlExpand::clsHtmlExpand() 
{
}

bool clsHtmlExpand::Initialize() 
{
	mpHeaderStream = NULL;
	SetHeaderLength(0);
	mpFooterStream = NULL;	
	SetFooterLength(0);
	SetNeedCoBrandProcessing( false );
	DataTable = NULL;
	mDataRecCnt = 0;	
	mFirstChunkDone = false;
	return true;				
}

clsHtmlExpand::~clsHtmlExpand()
{		
	return;
}

bool clsHtmlExpand::SetPartnerIDString(CHttpFilterContext* pCtxt, PHTTP_FILTER_URL_MAP pMapInfo)
{
	char buffer[MAX_PATH_LENGHT];	
	char idBuf[MAX_PATH_LENGHT];
	char ext[10];
	char * pSrc, *pDest, *pStrPath;		
	CString strPath;
	int iID;	
	int cnt=0;
	int partTokenLen=0;

#ifdef _DEBUG
	TRACEIT("Get in set partner id\n");
#endif	

	if ( !pCtxt || !pMapInfo)
	{
#ifdef _DEBUG
	TRACEIT("pCtxt or pMapInfo is NULL");
#endif
		SetNeedCoBrandProcessing(false);
		return false;	
	}

	if (!AfxIsValidAddress(pMapInfo, sizeof(HTTP_FILTER_URL_MAP), false)
		|| (!AfxIsValidAddress(pMapInfo->pszURL, strlen(pMapInfo->pszURL), false))
		|| (!AfxIsValidAddress(pMapInfo->pszPhysicalPath, strlen(pMapInfo->pszPhysicalPath), false)))
	{
#ifdef _DEBUG
	TRACEIT("invalid pointer found in SetPartnerIDString\n");
#endif
		SetNeedCoBrandProcessing(false);	
		return false;	
	}

	if ( _access( pMapInfo->pszPhysicalPath , 0) == -1 )
	{		
		SetNeedCoBrandProcessing(false);
		return false;
	}

	strPath = pMapInfo->pszURL;
	strPath.MakeLower();
	pStrPath = (LPSTR)(LPCSTR)strPath;
	pSrc = strrchr( pStrPath, '.');
	if( pSrc )
	{
		pSrc++;
		pDest = ext;
		while (pDest && pSrc && *pSrc && isalpha(*pSrc))
		{
			*pDest++ =  *pSrc++;	
		}
		if ( pDest )
			*pDest = '\0';
		if( strcmp(ext, "html") 
			&& strcmp(ext, "htm") 
			&& strcmp(ext, "asp") 
			&& strcmp(ext, "idq") 
			&& strcmp(ext, "shtml"))
		{
			SetNeedCoBrandProcessing(false);
			return false;
		}
	}

	pSrc= strstr(pStrPath, PARTPREFIX);
	if( ! pSrc )
	{		
		SetNeedCoBrandProcessing(false);
		return false;
	}
	else 
	{
		pDest = buffer;		
		while ( pSrc && pDest && *pSrc && cnt < 10)
		{
			*pDest++ = *pSrc++;
			cnt++;
		}
		if ( pDest )
			*pDest = '\0';		
		memcpy(idBuf, &buffer[SIZEOFPARTPREFIX], SIZEOFPARTPOSTFIX);  
		idBuf[3]= '\0';
		iID= atoi(idBuf);
		mPartnerID	= iID;		

		if( isdigit(idBuf[0]) )
		{
#ifdef _DEBUG
	TRACEIT("partner id=%d\n", mPartnerID);
#endif	
			SetNeedCoBrandProcessing(true);
		}
		return true;
	}	
}



