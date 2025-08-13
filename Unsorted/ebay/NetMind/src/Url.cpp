#include <afx.h>
//#include "stdafx.h"
#include "url.h"
#include "nmdefs.h"
#include "nmstring.h"


CUrl::CUrl(int TimeOut) : m_method("GET"), m_acceptEncoding("*/*"),
    m_contentEncoding("application/x-www-form-urlencoded"),
    m_contentType("text/plain"), m_userAgent(WIN_CLIENT_USER_AGENT),
    m_replyHeader(""),
	  timeout(TimeOut),
	  retried(1) {
  m_url = 0;
  m_post_data = 0;
  m_error.major = 0 ;
  m_error.minor = 0 ;
  m_error.data = 0 ;

	m_host = 0 ;
	m_path = 0 ;
	m_scheme = 0 ;

	ProxyServerScheme_=0;
	ProxyServerHost_=0;
	ProxyServerPath_=0;
	ProxyServerPort_=0;
	URL_ =0;
	ProxyServerLocation_=0;
}

CUrl::~CUrl() {
  if(m_url != 0) {
    free(m_url);
  }
  if(m_post_data != 0) {
    buf_done(m_post_data);
  }
	if (m_scheme) free (m_scheme) ;
	if (m_host) free (m_host) ;
	if (m_path) free (m_path) ;
	if (ProxyServerScheme_) free (ProxyServerScheme_) ;
	if (ProxyServerHost_) free (ProxyServerHost_) ;
	if (ProxyServerPath_) free (ProxyServerPath_) ;

}

BOOL
CUrl::SetLocation(const char * url) {
	URL_ = url;

  NmParsedUrl ParsedURL ;


  if(!http_parse_url(url, &ParsedURL)) {
	  if (m_error.major) {
		  free(m_error.major);
			m_error.major = 0 ;
		}
	  m_error.major = strdup("HOST_LOCATION_INVALID") ;
		http_free_parsed_url(&ParsedURL);
    return FALSE;
  }

	if (m_scheme) free (m_scheme) ;
	if (ParsedURL.scheme) {
	  m_scheme = ::strdup(ParsedURL.scheme);
	}
	else m_scheme = 0 ;

	if (m_host) free (m_host) ;
  if (ParsedURL.host){
	  m_host = ::strdup(ParsedURL.host);
	}
	else m_host = 0 ;

	if (m_path) free (m_path) ;
	if (ParsedURL.path) {
	  m_path = ::strdup(ParsedURL.path);
	}
	else m_path = 0 ;
		
  m_port = ParsedURL.port ;
	http_free_parsed_url(&ParsedURL);
  return TRUE;
}

BOOL
CUrl::SetProxyServerLocation(const char * url) {
	ProxyServerLocation_ = url;

  NmParsedUrl ParsedURL ;
	char ProxyURL[MAX_URL];
	ProxyURL[0] = '\0';
	
	nm_fix_url(url,ProxyURL);
	
  if(!http_parse_url(ProxyURL, &ParsedURL)) {
	  if (m_error.major) {
		  free(m_error.major);
			m_error.major = 0 ;
		}
	  m_error.major = strdup("HOST_LOCATION_INVALID") ;
		http_free_parsed_url(&ParsedURL);
    return FALSE;
  }

	if (ProxyServerScheme_) free (ProxyServerScheme_) ;
	if (ParsedURL.scheme) {
	  ProxyServerScheme_ = ::strdup(ParsedURL.scheme);
	}
	else ProxyServerScheme_ = 0 ;

	if (ProxyServerHost_) free (ProxyServerHost_) ;
  if (ParsedURL.host){
	  ProxyServerHost_ = ::strdup(ParsedURL.host);
	}
	else ProxyServerHost_ = 0 ;

	if (ProxyServerPath_) free (ProxyServerPath_) ;
	if (ParsedURL.path) {
	  ProxyServerPath_ = ::strdup(ParsedURL.path);
	}
	else ProxyServerPath_ = 0 ;
		
  m_port = ParsedURL.port ;
  ProxyServerPort_=ParsedURL.port ;

	http_free_parsed_url(&ParsedURL);
  return TRUE;
}

// data is post data--may be binary
BOOL
CUrl::SetMethod(const char * method, const char * type,const char * data, ulong dataLen) {
  m_contentType = type;
  m_method = method;
  m_post_data = NEW(NmBuffer) ;

  if (strstr(type,"text/plain")) {
		buf_init(m_post_data,"post_data",TRUE);
	} else {
	  buf_init(m_post_data,"post_data",FALSE);
	}
  buf_append(m_post_data,data,dataLen);
  return TRUE;
}

BOOL CUrl::SetAcceptEncoding(const char * encoding) {
  m_acceptEncoding = encoding;
  return TRUE;
}

BOOL CUrl::SetContentEncoding(const char * encoding) {
  m_contentEncoding = encoding;
  return TRUE;
}

BOOL
CUrl::SetUserAgent(const char * agent) {
  m_userAgent = agent;
  return TRUE;
}

BOOL
CUrl::Invoke(char ** reply, ulong * replyLen) {
  NmList requestHeader;
  char cReplyHeaderStr[MAX_STRING];
  char * replyHeaderStr;
  NmList replyHeader;
  char * contentLenStr;
  ulong contentLen;
  DbHost server;
  NmBool result;
	HttpRequest request ;
	NmUrl url;
  NmParsedUrl purl;

  memset(&server, 0, sizeof(NmHost));
  memset(&url, 0, sizeof(NmUrl));
  http_start (timeout, 0);			
	httprequest_init (&request, 0, 0, 0, 0) ;
	nmurl_init (&url, &request, 0, &server) ;

	url.request->method = (char *)(const char *)m_method;             //method POST//
	url.loc = (char *)(const char *)URL_;                    //Responder URL//


  url.request->post_data = m_post_data ;

	url.request->postcont_type = (char *)(const char *)m_contentType; //content type like plain/text//
  url.request->creds = 0;

	//need to set the user agent//
	url.request->user_agent = (char *)(const char *)m_userAgent;
	url.request->encoding = (char *) (const char *)m_acceptEncoding;

	NmBuffer body;//used for data return//

	////if proxy server is defined/////
	if (ProxyServerHost_) {
	  url.proxy_server = &server;
	  strcpy(url.proxy_server->host_name,ProxyServerHost_) ;
		url.proxy_server->host_port = ProxyServerPort_ ;
	} else {
	  url.server = &server;
	  strcpy(url.server->host_name,m_host) ;
		url.server->host_port = m_port ;
	}

  purl.scheme = "http";
  purl.host = url.server->host_name;
  purl.port = url.server->host_port;
  purl.path = m_path;

	list_init(&replyHeader,0) ;	
	if (!http_connect (&url, &purl, &m_server, &replyHeader)) {
		//    http_free_parsed_url(&purl);
	  m_error.major = strdup("HTTP_CONNECTION_ERROR") ;
    m_error.minor = m_server.conn.error.minor;
    if (m_post_data) {
			buf_done(m_post_data);
			m_post_data = 0;
		}
		return FALSE ;
	}
  /* read reply header */
	list_done(&replyHeader) ;
	list_init(&replyHeader,0) ;	
  if(!http_invoke(&m_server,&url,&purl,&replyHeader, &body)) {
			if (m_error.major) {
		    free(m_error.major);
			  m_error.major = 0 ;
		  }
	    m_error.major = strdup("HTTP_REQUEST_ERROR") ;
	

    m_error.minor = m_server.conn.error.minor;
    list_done(&requestHeader);
		buf_done(&body) ;
    Disconnect() ;
    if (m_post_data) {
			buf_done(m_post_data);
			m_post_data = 0;
		}
    return FALSE;
  }

  /* save the reply header */
	replyHeaderStr = cReplyHeaderStr;

  list_format(&replyHeader, " ", "\n", 0, &replyHeaderStr);
  m_replyHeader = replyHeaderStr;
//  free(replyHeaderStr);
  
  /* get Status and Content-length */
	char * type ;
	BOOL TextType = FALSE;
	type = list_find(&replyHeader,"Content-Type") ;
	if (type !=0 && strstr(type,"text") != 0) {
	  TextType = TRUE ;
	}
	
  m_status = list_find(&replyHeader, "Status");
  contentLenStr = list_find(&replyHeader, "Content-Length");
  contentLen = contentLenStr != 0 ? atol(contentLenStr) : 0;
  list_done(&replyHeader);

  if(m_status.IsEmpty()) {
	  if (m_error.major) {
		  free(m_error.major);
			m_error.major = 0 ;
		}
	  m_error.major = strdup("HTTP_NO_REPLY_STATUS") ;
    Disconnect() ;
    if (m_post_data) {
			buf_done(m_post_data);
			m_post_data = 0;
		}
    return FALSE;
  }
	
  if(atol(m_status) / 100 == 2) {
    result = TRUE;
  } else {
    strncpy(m_err, m_status, MAX_ERR);
    if (m_error.major) {
		  free(m_error.major);
			m_error.major = 0 ;
		}
		m_error.major = strdup(m_err) ;
		
    if(contentLen == 0) {
      Disconnect() ;
      if (m_post_data) {
			  buf_done(m_post_data);
			  m_post_data = 0;
		  }
      return FALSE;
    }
    result = FALSE;
  }
	*reply = buf_data(&body) ;
	*replyLen = buf_length(&body) ;
	
  ulong len = *replyLen ;
	
	if (TextType) {
	  (*reply)[len] = '\0' ;
	}

  if(!result) {
    // extract the 'ERR' error code
    char * err = strstr(*reply, "ERR=");
    if(err != 0) {
      sscanf(err, "ERR=%s", m_err);
			if (m_error.major) {
		    free(m_error.major);
			  m_error.major = 0 ;
		  }
			m_error.major = strdup(m_err) ;
			
    }
  }
  Disconnect() ;
  if (m_post_data) {
		buf_done(m_post_data);
		m_post_data = 0;
	}
  return result;
}

BOOL
CUrl::Disconnect() {
  tcp_done(&m_server.conn);
  return TRUE;
}

const char *
CUrl::GetReplyHeader() {
  return m_replyHeader;
}

NmError 
CUrl::GetError() {
  return m_error;
}

const char *
CUrl::GetStatus() {
  return m_status;  
}
