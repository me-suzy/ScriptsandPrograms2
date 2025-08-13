/*MAN
MODULE
    nmssl.c
DESCRIPTION
AUTHOR
    Santosh Rau
UPDATES
     12 Sep 1998  SR  Created
*/

#ifndef _NMSSL_H_
#define _NMSSL_H_

#ifdef __cplusplus
extern "C" {
#endif

#include "nmtypes.h"
#include "ssl.h"

#define SSL_DEFAULT_VERSION 	SSL_Version_Undetermined
#define SSL_IO_SEMANTICS 			SSL_PartialIO


typedef struct {
	B_ALGORITHM_OBJ bsafeRandom;
} RandomObj;

typedef struct {
  char* certs ;
  char* privateKey ;
  char* exportKey ;
  char* pkeyPassword ;
  char* trustedCerts ;
  NmBool clientAuth ;
  SSLContext* sslCtx ;
  RandomObj *randomObj ;
	char *randomPoolFile ;
} SSLResources ;
 
NMEXPORT SSLErr nm_init_sslcontext (SSLResources* sslRes) ;
NMEXPORT NmBool nm_done_sslcontext (SSLResources* ssl_res) ;

/* callback functions */ 
SSLErr socketReadcb (SSLBuffer buffer, uint32* processed, void* connref) ;
SSLErr socketWritecb (SSLBuffer buffer, uint32* processed, void* connref) ;


/* configure SSL Context */
SSLErr configContextExport (SSLContext *ctx, RandomObj *randomObj);
SSLErr configureContextForRandom (SSLContext *ctx, const char *randomPoolFile, 
	RandomObj **randomObj );


/* Configure random object */
int CreateRandomObj(RandomObj **rand);
int DestroyRandomObj(RandomObj *rand);
int UpdateRandomObject(void *data, unsigned int length, RandomObj *rand);
SSLErr RandomCallback(SSLBuffer data, void *randomRef);

/* Certificate checks */
SSLErr CertificateCallback (SSLCertificateChain *certs, uint32 trustedCert, 
	SSLErr validateErr, void *checkCertificateRef) ;

#ifdef __cplusplus
}
#endif

#endif /*_NMSSL_H_*/
