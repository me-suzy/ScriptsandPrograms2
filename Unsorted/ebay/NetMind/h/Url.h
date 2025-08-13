// URL wrapper class for easier access to for C library routines

#include "nmtypes.h"
#include "nmhttp.h"
#include "nmlist.h"
//#include "stdafx.h"

#define MAX_HOST 1024
#define MAX_ERR  128

class CUrl {

public:
  CUrl(int TimeOut);//in minutes
  ~CUrl();
  BOOL SetLocation(const char * url);
  BOOL SetProxyServerLocation(const char * url);
  BOOL SetMethod(const char * method, const char * type,const char * data, ulong dataLen);
  BOOL SetAcceptEncoding(const char * encoding);
  BOOL SetContentEncoding(const char * encoding);
  BOOL SetUserAgent(const char * agent);
  BOOL Disconnect();
  BOOL Invoke(char ** reply, ulong * replyLen);
  const char * GetStatus();
  const char * GetReplyHeader();
// NmError & GetError();
  NmError GetError();

protected:
	const char * URL_;
	const char * ProxyServerLocation_;
	
  // location attributes
  // byte-array strings work fine here, since that's what the
  // C library expects 
  char * m_url;
  char * m_scheme;
  char * ProxyServerScheme_;
  char *  m_host;
  char * ProxyServerHost_;
  ushort m_port;
  ushort ProxyServerPort_;
  char * m_path;
  char * ProxyServerPath_;
  ulong  timeout ;
  ulong retried ;
  // connection attributes
  // CStrings work nicely for optional attributes
  // except for 'm_data' which is not necessarily a string
  CString m_method;
  NmBuffer * m_post_data;
  ulong m_dataLen;
  CString m_acceptEncoding;
  CString m_contentEncoding;
  CString m_contentType;
  CString m_userAgent;
  // state and results
  HttpObject  m_server;
  CString m_status;
  CString m_replyHeader;
  char    m_err[MAX_ERR];
  NmError m_error;
};

