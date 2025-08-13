/*MAN
MODULE
    nmntlm.c
DESCRIPTION
    NTLM Security API.
AUTHOR
    Santosh Rau
UPDATES
  17 Nov 98 Created.
*/

#ifndef _NMNTLM_H_
#define _NMNTLM_H_

#include "nmdefs.h"
#include "nmsizes.h"
#include "nmtypes.h"
#include "nmlist.h"
#include "nmbuffer.h"

#define SECURITY_WIN32
#include "sspi.h"
#include "issperr.h"


#define PACKAGE_NAME  "NTLM"

typedef enum eauthtype {
  EBasic,
  ENTLM
} EAuthType ;
typedef enum eauthseq {
	ESEQ_NOT_STARTED,
	ESEQ_IN_PROGRESS,
	ESEQ_COMPLETED
} EAuthSeq ;

// general purpose dynamic buffer structure
typedef struct _NTLMBuffer {
  PBYTE pBuf;
  DWORD cLen;
} NTLMBuffer ;


// structure storing the state of the authentication sequence
typedef struct _AuthSeq {
  EAuthType _fAuthType ;
  EAuthSeq _fInAuthSeq ;
  BOOL _fNewConversation;
  CredHandle _hcred;
  BOOL _fHaveCredHandle;
  DWORD _cbMaxToken;
  BOOL _fHaveCtxtHandle;
  struct _SecHandle  _hctxt;
	char authorization [MAX_STRING] ;
	BOOL _fNeedMoreData ;
	char	_fUserName [MAX_EMAIL] ;
	NmBuffer	_fSessionKey ;
} AuthSeq;

// entry points in the security DLL
typedef struct _SEC_FUNC {
	REVERT_SECURITY_CONTEXT_FN pRevertSecurityContext ;
	IMPERSONATE_SECURITY_CONTEXT_FN pImpersonateSecurityContext ;
	ACCEPT_SECURITY_CONTEXT_FN pAcceptSecurityContext ;
  FREE_CREDENTIALS_HANDLE_FN pFreeCredentialsHandle;
  ACQUIRE_CREDENTIALS_HANDLE_FN pAcquireCredentialsHandle;
  QUERY_SECURITY_PACKAGE_INFO_FN pQuerySecurityPackageInfo;   // A
  FREE_CONTEXT_BUFFER_FN pFreeContextBuffer;
  INITIALIZE_SECURITY_CONTEXT_FN pInitializeSecurityContext;  // A
  COMPLETE_AUTH_TOKEN_FN pCompleteAuthToken;
  ENUMERATE_SECURITY_PACKAGES_FN pEnumerateSecurityPackages;  // A
} SecFuncs;

typedef struct ntlmsecinfo {
  HINSTANCE hSecLib ;
  SecFuncs sfProcs;
} NTLMSecInfo ;

NMEXPORT Boolean nm_init_ntlm (NTLMSecInfo* secInfo) ;
NMEXPORT Boolean nm_auth_seq_init (AuthSeq* pAS) ;
NMEXPORT Boolean nm_auth_seq_done (NTLMSecInfo* secInfo, AuthSeq* pAS) ;
NMEXPORT Boolean nm_revert_toself (NTLMSecInfo* secInfo, AuthSeq* pAS) ;
Boolean impersonateUser (NTLMSecInfo* secInfo, AuthSeq* pAS) ;

NMEXPORT Boolean nm_getserver_ntlm_auth (NmList* header,
	NTLMSecInfo* secInfo, AuthSeq *pAS, VOID* pBuffIn) ;
Boolean nm_getclient_ntlm_auth (NmList* header, NTLMSecInfo* secInfo, 
	AuthSeq* pAS, char* username, char* password, char* domain, VOID* pBuffIn) ;

// from MS local functions
BOOL CrackUserAndDomain(CHAR *   pszDomainAndUser, CHAR * * ppszUser, 
	CHAR * * ppszDomain);

// uuencode/decode routines declaration
// used to code the authentication blob

BOOL uudecode(char   * bufcoded, NTLMBuffer * pbuffdecoded, 
	DWORD  * pcbDecoded );
BOOL uuencode( BYTE *   bufin, DWORD    nbytes, NTLMBuffer * pbuffEncoded );

#endif
