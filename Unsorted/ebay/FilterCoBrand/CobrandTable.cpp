/*	$Id: CobrandTable.cpp,v 1.2 1999/02/21 02:21:52 josh Exp $	*/

//
//	File:	CobrandTable.cpp
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
const char	szAllPartners[] = "c:\\eBay\\cobrandhtml\\debug\\part*";
char	szReloadAll[] = "aw-reload-co";
const char	szeBayAwDirectory[] = "c:\\eBay\\cobrandhtml\\debug\\";
#define DEFAULTHEADER "DefaultHeader.htm"
#define DEFAULTFOOTER "DefaultFooter.htm"
#define STARTPAGEID 4
#define	IDLENGTH 4
#define FILETYPEINDEX 8
#define HANDFCOUNT 2
#define HEADERFILETYPECHAR 'h'
#define FOOTERFILETYPECHAR 'f'

FILE	*mpLog;

static const char *eBayHeader	=
	"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
		"<tr>"
			"<td width=\"120\"><a href=\"http://www.ebay.com\"><img "
			"src=\"http://cayman.ebay.com/aw-part000/pics/logo_lower_tb.gif\" width=\"96\" hspace=\"0\" vspace=\"0\" "
			"height=\"42\" alt=\"eBay logo\" border=\"0\"></a></td>"
			"<td><strong><font size=\"3\"><a "
			"href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/listings/list\">Listings</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/ps.html\">Buyers</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/seller-services.html\">Sellers</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/search.html\">Search</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/contact.html\">Help</a>&nbsp; <a "
			"href=\"http://komodo.ebay.com/aw-part000-cgi/UserISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/sitemap.html\">Site Map</a></font></strong>"
			"</td>"
		"</tr>"
		"<tr>"
			"<td width=\"120\">&nbsp;</td>"
			"<td><font size=\"2\"><font color=\"green\">Holiday shipping:</font>&nbsp; Need answers to your holiday shipping questions? Click <a "
			"href=\"http://cayman.ebay.com/aw-part000/ship.html\">here</a>!</font><br>"
			"<font size=\"2\"><font color=\"green\">Holiday season:</font>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/xmas97\">Celebrate</a> the Holidays with eBay.</font><br>"
			"<font size=\"2\"><font color=\"green\">Advertising:</font>&nbsp; Click <a "
			"href=\"http://cayman.ebay.com/aw-part000/cookies.html\">here</a> for eBay's feelings about advertising.</font></td>"
		"</tr>"
	"</table><br>";


static const char *eBayFooter =
	"<hr>"
	"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
		"<tr>"
			"<td width=\"120\"><a href=\"http://www.ebay.com\"><img "
			"src=\"http://cayman.ebay.com/aw-part000/pics/logo_lower_tb.gif\" width=\"96\" hspace=\"0\" vspace=\"0\""
			"height=\"42\" alt=\"eBay logo\" border=\"0\"></a></td>"
			"<td><strong><font size=\"3\"><a "
			"href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/listings/list\">Listings</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/ps.html\">Buyers</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/seller-services.html\">Sellers</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/search.html\">Search</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/contact.html\">Help</a>&nbsp; <a "
			"href=\"http://komodo.ebay.com/aw-part000-cgi/UserISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
			"href=\"http://cayman.ebay.com/aw-part000/sitemap.html\">Site Map</a></font></strong>"
			"</td>"
		"</tr>"
	"</table>"
	"\n"
	"<font size=2>"
	"Thank you for using eBay!"
	"</font>"
	"<br>"
	"<br>"
	"<address>"
	"<font size=2>"
	"Copyright &copy; 1995-1997 "
	"<a href=\"http://cayman.ebay.com/aw-part000/contact.html\">eBay Inc.</a>"
	" All Rights Reserved. "
	"</font>"
	"</address>"
	"<font size=2>"
	"All information contained on this web site may only be used in "
	"accordance with eBay\'s "
	"<a href=\"http://cayman.ebay.com/aw-part000/rules.html\">"
	"terms and conditions"
	"</a>"
	"." 
	"</font>"
	"</body></html>\n";


#ifdef _DEBUG	
		#define TRACEIT TRACE		
#endif


DWORD CobrandTable::CleanUpCoBrandTable()
{	
	if( mpCoBrandTable )
		delete	[] mpCoBrandTable;
	return 0;
}

DWORD CobrandTable::ReadHFFileIntoCoBrandTable(char *szCurrentFile, char ** ppStream, DWORD *pLength)
{
	fpos_t		totalLength;
	FILE	*fp;
	char *pBuffer;
	size_t numread;
	DWORD dwRet;


	if(( fp = fopen(szCurrentFile, "r" )) != NULL )
	{			
		fseek(fp, 0, SEEK_END);
		// get length
		fgetpos(fp, &totalLength );
		fseek(fp, 0, SEEK_SET);
		if( totalLength != 0 )
		{
			pBuffer = new char [(int)totalLength+1];
			if( pBuffer == NULL)
			{
				dwRet = 0;
				return dwRet;
			}
			memset(pBuffer , '\0', (int)totalLength+1);			
			numread = fread( pBuffer, sizeof( char ), (size_t)totalLength, fp );
			*ppStream =	 pBuffer; 
			*pLength = numread;
		}
		else
		{
			*ppStream =	 NULL; 
			*pLength = 0;
		}
		fclose( fp );
		dwRet = 1;
	}	
	else
	{
		dwRet = 0;
#ifdef _DEBUG
	TRACEIT("Can not open %s\n", szCurrentFile);
#endif
	}
	return dwRet;
}

DWORD CobrandTable::ReadAllHeadersAndFooters()
{
	struct  _finddata_t file;    
	long	hFile;	
	int count = 0;
	char szPageID[32];
	int iPageID;	
	char szCurrentFile[MAX_PATH_LENGHT];
	char szDefaultHeaderFile[MAX_PATH_LENGHT];
	char szDefaultFooterFile[MAX_PATH_LENGHT];
	DWORD dwRet = 1;
	char szCurrentDir[128];

	GetCurrentDirectory(128, szCurrentDir);
	GetModuleFileName(NULL , szCurrentDir , 128);
 if( (hFile = _findfirst( szAllPartners , &file )) == -1L ) 
 {
	
#ifdef _DEBUG
	TRACEIT("All partner header and footer are missing\n" );
#endif
	dwRet = 0;	
	mHeaderMissing = true;
	return dwRet;
 }
 else
 {
	mHeaderMissing = false;
	do{
		count++;
	 }
	 while( (hFile != -1L) && _findnext( hFile, &file ) == 0 ); 			
 }

// Create the CoBrand Structure Array
 mpCoBrandTable = new CoBrandRec [count]; 
 if( mpCoBrandTable == NULL )
 {
#ifdef _DEBUG
	TRACEIT("Can not allocate memory for CoBrandRec\n" );
#endif
	dwRet = 0;
	return dwRet;
 }
	
 count = 0;

// Read from directory and stick into CoBrand Structure Array 
 if( (hFile = _findfirst( szAllPartners , &file )) == -1L ) 
	{
	dwRet = 0;	
	return dwRet;
	}	 
 else
	{	 
	 do {	
		memcpy( szPageID , &file.name[STARTPAGEID], IDLENGTH);
		szPageID[STARTPAGEID] = '\0';
		iPageID= atoi(szPageID);
		mpCoBrandTable[count].iID = iPageID;
		if(file.name[FILETYPEINDEX] == HEADERFILETYPECHAR)
			mpCoBrandTable[count].fType = COHEADER;
		else if(file.name[FILETYPEINDEX] == FOOTERFILETYPECHAR)
			mpCoBrandTable[count].fType = COFOOTER;
		sprintf(szCurrentFile, "%s%s", szeBayAwDirectory, file.name);
		dwRet = ReadHFFileIntoCoBrandTable( szCurrentFile, &(mpCoBrandTable[count].pStream), &mpCoBrandTable[count].dwlength);		
		if ( ! dwRet )
			return dwRet;
		count++;
	 }	 while( (hFile != -1L) && _findnext( hFile, &file ) == 0 ); 
 }

 mTotalCobrandRec = count;	  

// Read the default header and footer from file to stream
	sprintf ( szDefaultHeaderFile, "%s%s", szeBayAwDirectory, DEFAULTHEADER);	 
	sprintf ( szDefaultFooterFile, "%s%s", szeBayAwDirectory, DEFAULTFOOTER);	 
	dwRet = ReadHFFileIntoCoBrandTable( szDefaultHeaderFile, &mpDefaultHeaderStream, &mDefaultHeaderLength);
	if ( ! dwRet )
	{	
		mDefaultHeaderLength = strlen( eBayHeader );	
		mpDefaultHeaderStream = new char [ mDefaultHeaderLength + 1 ];
		if( mpDefaultHeaderStream )
		{
			memset( mpDefaultHeaderStream, '\0', mDefaultHeaderLength );
			memcpy( mpDefaultHeaderStream, eBayHeader, mDefaultHeaderLength);
			dwRet = 1;
		}
		else
		{
			dwRet = 0;			
			mHeaderMissing = true;
			return dwRet;
		}
	}
	dwRet = ReadHFFileIntoCoBrandTable( szDefaultFooterFile, &mpDefaultFooterStream, &mDefaultFooterLength);
	if ( ! dwRet )
	{	
		mDefaultFooterLength = strlen( eBayFooter );	
		mpDefaultFooterStream = new char [ mDefaultFooterLength + 1 ];
		if( mpDefaultFooterStream )
		{
			memset( mpDefaultFooterStream, '\0', mDefaultFooterLength );
			memcpy( mpDefaultFooterStream, eBayFooter, mDefaultFooterLength);
			dwRet = 1;
		}
		else
		{
			dwRet = 0;			
			mHeaderMissing = true;
			return dwRet;
		}
	}
 return dwRet;
}


bool CobrandTable::GetHeaderAndFooterStream(clsHtmlExpand *pclsHtmlExpand, int iPartnerID, int iPageType)
{	
	int i;
	int iPageID;
	int cnt = 0;

	if( iPageType == -1)
	{
		pclsHtmlExpand->mpHeaderStream = mpDefaultHeaderStream;
		pclsHtmlExpand->mpFooterStream = mpDefaultFooterStream;		
		pclsHtmlExpand->SetHeaderLength( mDefaultHeaderLength );
		pclsHtmlExpand->SetFooterLength( mDefaultFooterLength );	
		return false;
	}
	iPageID = iPartnerID * NUMBERTEN + iPageType;
	for ( i = 0; i < mTotalCobrandRec ; i++ )
	{
		if( mpCoBrandTable[i].iID == iPageID )
		{
			if ( mpCoBrandTable[i].fType == COHEADER )
			{
				pclsHtmlExpand->mpHeaderStream = mpCoBrandTable[i].pStream;		
				pclsHtmlExpand->SetHeaderLength(mpCoBrandTable[i].dwlength);  
				
#ifdef _DEBUG
				if( pclsHtmlExpand->mpHeaderStream )				
					TRACEIT("header length=%d\n", strlen(pclsHtmlExpand->mpHeaderStream));
#endif			
				cnt ++;
			}
			else if ( mpCoBrandTable[i].fType == COFOOTER )
			{
				pclsHtmlExpand->mpFooterStream = mpCoBrandTable[i].pStream;
				pclsHtmlExpand->SetFooterLength(mpCoBrandTable[i].dwlength);  				
#ifdef _DEBUG	
				TRACEIT("Footer length=%d\n", mpCoBrandTable[i].dwlength);
#endif	
				cnt ++;
			}
		}
	}
	if ( cnt == HANDFCOUNT )
		return true;
	else
	{
		if( pclsHtmlExpand->mpHeaderStream == NULL)
		{
			pclsHtmlExpand->mpHeaderStream = mpDefaultHeaderStream;
			pclsHtmlExpand->SetHeaderLength( mDefaultHeaderLength );
		}
		if ( pclsHtmlExpand->mpFooterStream == NULL)
		{
			pclsHtmlExpand->mpFooterStream = mpDefaultFooterStream;			
			pclsHtmlExpand->SetFooterLength( mDefaultFooterLength );	
		}
	}
			return false;
}

DWORD CobrandTable::ForceReload()
{
	CleanUpCoBrandTable();
	return ReadAllHeadersAndFooters();	
}

CobrandTable::CobrandTable()
{
	mpDefaultFooterStream = NULL;
	mpDefaultHeaderStream = NULL;
	mpCoBrandTable = NULL;
}

CobrandTable::~CobrandTable()
{
	if ( mpDefaultHeaderStream )
		delete mpDefaultHeaderStream;
	if ( mpDefaultFooterStream )
		delete mpDefaultFooterStream;
	CleanUpCoBrandTable(); 
}
