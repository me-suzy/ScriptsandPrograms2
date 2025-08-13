/*MAN
MODULE 
     http.h
DESCRIPTION
     HTTP header file.
COPYRIGHT
     Copyright Netmind Services 1996-1997
UPDATES
  06 Apr 1998  SR  Added credentials to the NmUrl object 
  13 Jan 1999  SR  Added keep-alive flag to the request object 
*/

#ifndef _NM_HTTP_H_
#define _NM_HTTP_H_

#include "nmtcp.h"
#include "nmlist.h"
#include "nmbuffer.h"

#if defined(WIN32) && !defined(WIN32_GUI)
#include "nmntlm.h"
#endif

#ifdef __cplusplus
extern "C" {
#endif

/*
 * the HTTP object simply encapsulates a TCP object
 */
typedef struct {
  TcpObject conn;
} HttpObject;

typedef struct {
  char scheme[MAX_SCHEME];
  char username[MAX_EMAIL];
  char password[MAX_PASSWORD];
  NmHttpStatus status;
} NmHttpAuthorization;

typedef struct {
  char* method ;          /* GET/POST etc */
  char* postcont_type ;   /* eg., application/x-www-form-urlencoded */
  char* user_agent ;      /* MINDER_USER_AGENT or something else */
  char* encoding ;        /* eg., xdr */
  NmBuffer* post_data ;   /* POST data */
  char* creds ;           /* user credentials */
  char *username ;
  char *password ;
  char *domain ;
  char* proxy_creds ;     /* application credentials for proxy auth*/
  char* cookie ;          /* cookies for the request */
  char* referer ;         /* referer for the request */
	NmBool keep_alive ;			/* if set, use the corresponding header */
} HttpRequest ;

/* SR - Support for URL ip address caching */
typedef struct nmurl {
  char * loc;              /* full location, e.g., http://www.netmind.com/  */
  NmHost * server;         /* holds ip information  */
  NmList * filters;        /* filters, if any */
  NmList * old_filters;    /* old filters, if any */
  ushort status;           /* will track user status */
  ushort fails;            /* used to track user fails count */
  NmHost *proxy_server;    /* server is a proxy */
  void * poll_data;        /* data passed to tcp_poll, if any */

  ulong flags;		   /* flags for the URL object, see URL_ defs below */
  ulong times[4];          /* used to return timing information if URL_TIME */

  ulong total_csum ;       /* checksum for all urls */
  ulong top_csum;          /* top checksum */
  ushort passes ;          /* recursion passes */
  NmList *frame_url;       /* all the urls under the main "loc" */
  NmList *name_servers;    /* all the name servers */
  ulong last_modified;     /* latest date in recursive frames*/

  HttpRequest*  request ;  /* HTTP request information */

#ifdef BUILD_SSL
  SSLResources* ssl_res ;
#endif

#if defined(WIN32) && !defined(WIN32_GUI)
  NTLMSecInfo* ntlmsec_info ;
  AuthSeq* auth_seq ;
#endif

} NmUrl;

/*
 * ENUM used to index into the NmUrl.times array
 */
enum {
  URL_NET_CPU,
  URL_NET_TOTAL,
  URL_FILTER_CPU,
  URL_FILTER_TOTAL,
};

/*
 *  Flags for the URL object
 */
#define URL_TIME       0x00000001 /* capture and retain timing information */
#define URL_IS_FRAMED  0x00000002 /* set to true if it fetched a framed page */

typedef struct {
  char * scheme;
  char * host;
  char * path;
  ushort port;
} NmParsedUrl;

typedef struct {
  NmParsedUrl source ;
  NmParsedUrl dest ;
} NmUrlMapping ;

#define HTTP_PORT       80
#define HTTPS_PORT      443
#define HTTP_TIMEOUT    60
#define HTTP_CHUNKED_TIMEOUT    15
#define HTTP_CHUNKED_TRIES    1
#define HTTP_VERSION    "HTTP/1.0"
#define HTTP_VERSION_1_1    "HTTP/1.1"
#define HTTP_MAX_HEADER 100
#define HTTP_CLIENT_REQUEST_HEADERS 50

#ifdef SOLARIS
extern int gethostname(char *name, int namelen);
#endif

NMEXPORT extern  time_t http_timeout;
NMEXPORT extern  ulong  http_retries;

#if defined(WIN32) && !defined(WIN32_GUI)
NMEXPORT Boolean nm_client_ntlm_auth (HttpObject* server, NmUrl* url, 
  NmParsedUrl* purl, NmList* header, NmBuffer* body, NTLMSecInfo* secInfo, 
  AuthSeq* pAS) ;
#endif 


NMEXPORT void httprequest_init (HttpRequest* request, char* method, 
  char* postcont_type, char* user_agent,  char* encoding) ;
NMEXPORT void nmurl_init (NmUrl* urlobj, HttpRequest* request, char* loc, 
  NmHost* host) ;
NMEXPORT void    http_start(time_t timeout, ulong retries);
NMEXPORT NmBool http_send_request(HttpObject * self, const char * method, 
  const char * path, NmList * head, const char * body, ulong len) ;
NMEXPORT NmBool http_read_header(HttpObject * self, NmBool reply, NmList * head);
NMEXPORT NmBool http_read_body(HttpObject * self, char ** body, ulong * len);
NMEXPORT NmBool http_invoke (HttpObject* server, NmUrl * url, 
  NmParsedUrl* purl, NmList * header, NmBuffer * body) ;

NMEXPORT NmBool http_create_request (NmUrl* url, NmList* request_header,
  NmParsedUrl* purl, NmNamedValue *request_mem, short reqmem_size) ;
NMEXPORT NmBool http_connect (NmUrl * url, NmParsedUrl* purl, HttpObject *server, NmList * header) ;
NMEXPORT NmBool http_disconnect (HttpObject *server, NmUrl* url) ;
NMEXPORT NmBool http_read_chunked_body (NmList* result, NmBuffer* header) ;
NMEXPORT char* http_guess_body_type (NmList* header, NmBuffer* body) ;

NMEXPORT NmBool http_get(NmUrl * url, NmList * header, NmBuffer * body);
NMEXPORT NmBool http_get_file(NmUrl *url, NmList * header, NmBuffer * body);
NMEXPORT NmBool http_filter(NmList * url, char* url_loc, NmBuffer * in, NmBuffer * out);
NMEXPORT NmBool http_get_and_filter(NmUrl* url, NmList * header, NmBuffer * body);
NMEXPORT NmBool http_parse_url(const char * url, NmParsedUrl * purl);
NMEXPORT NmBool http_parse_framesets (NmUrl* url, NmList * header, NmBuffer * body) ; 
NMEXPORT void    http_free_parsed_url(NmParsedUrl * purl);

NMEXPORT NmBool http_parse_authorization(const char *, NmHttpAuthorization *);
NMEXPORT void http_free_parsed_authorization(NmHttpAuthorization *);
NMEXPORT NmBool http_authenticate (NmList* header, ulong status, 
  char* authBuffer) ;
NMEXPORT NmBool http_build_auth (NmHttpAuthorization* httpauth, char* auth) ;
NMEXPORT NmBool http_create_auth (NmBuffer* auth, char* account, char* password)
;
NMEXPORT NmBool http_set_authorization (NmUrl* urlobj, NmList* header) ;
NMEXPORT NmBool http_set_proxy_authorization (NmUrl* obj, NmBuffer* auth) ;
NmBool http_setup_tunnel (HttpObject* server, NmUrl* url, NmParsedUrl* purl,
  NmList* header) ;


NMEXPORT NmBool test_proxy (char* hostname, int port) ;
NMEXPORT NmBool http_is_local(ulong ip_addr);
NMEXPORT NmBool http_recache (NmUrl* urlobj);
NMEXPORT NmBool http_recache_urlhost (NmHost* urlhost, NmList* name_servers,
  void* poll_data) ;

#ifdef __cplusplus
}
#endif

#endif /* _NM_HTTP_H_ */

