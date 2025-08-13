#ifndef _TCP_H_
#define _TCP_H_

#include <sys/types.h>
#include <stdarg.h>

#ifdef WIN32
#include <windows.h>
#include <winsock.h>

typedef unsigned char uchar;
typedef unsigned short ushort;
typedef unsigned long ulong;

#else
#include <sys/types.h>
#include <sys/socket.h>		/* for socket */
#include <netinet/in.h>
#include <arpa/inet.h>
#include <netdb.h>		    /* for gethostbyname_r */

#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>

#endif

#include "nmtypes.h"
#include "nmport.h"

#ifdef BUILD_SSL
#include "nmssl.h"
#endif

#ifndef BOOLEAN_DEFINED
#define BOOLEAN_DEFINED
typedef int Boolean;
#endif
#ifndef TRUE
#define TRUE            1
#endif
#ifndef FALSE
#define FALSE           0
#endif

#ifdef TCP_MAIN
#define TCP_SEND_BUF_SIZE   8
#define TCP_RECV_BUF_SIZE   8
#else
#define TCP_SEND_BUF_SIZE   2048
#define TCP_RECV_BUF_SIZE   2048
#endif

#define TCP_TIMEOUT         10
#define TCP_RETRIES         1

#ifdef __cplusplus
extern "C" {
#endif

enum tcp_log_level {
  TCP_ERROR,
  TCP_WARNING,
  TCP_INFO,
  TCP_DEBUG
};

enum tcp_error {
  TCP_BAD_PARAM  = 1001,
  TCP_TIMED_OUT  = 1002,
  TCP_NO_REPLY = 1003,
  TCP_BUFFER_TOO_SMALL = 1004,
  TCP_MX_LOOKUP_FAILED = 1006,
  TCP_IP_LOOKUP_FAILED = 1007,
  TCP_INVALID_EMAIL = 1113,
  TCP_INVALID_DOMAIN = 1114,
  TCP_CLOSED = 1120
};

/* TCP object */
typedef struct {
  SOCKET socket;								/* socket */
  SOCKADDR_IN addr;							/* internet address of server */
  char send_buf[TCP_SEND_BUF_SIZE];
  char recv_buf[TCP_RECV_BUF_SIZE + 1];
  char * recv_ptr;							/* received data */
  char * recv_next;							/* unread received data */ 
  ulong recv_len;								/* length of received data */
  Boolean recv_realloc;					/* automatically realloc if TRUE */
  time_t timeout;								/* timeout in seconds */
  ulong  tries;									/* retry attempts */
  NmError error;								/* error, if any */
  void * poll_data;             /* poll data, if any */
	Boolean sec_socket ;					/* true indicates secure socket */

#ifdef BUILD_SSL
	SSLContext* context ;					/* ssl context for every socket */
	SSLResources* ssl_res ;				/* ssl resources */
#endif

} TcpObject;

typedef void (*TcpPollFunc)(void *);

/*
 * Macros
 */
#ifdef WIN32
#define CONNECTION_REFUSED() (WSAGetLastError() == WSAECONNREFUSED)
#define WOULD_BLOCK()        (WSAGetLastError() == WSAEWOULDBLOCK)
#define ERRORNO              WSAGetLastError()
#else
#define CONNECTION_REFUSED() (errno == ECONNREFUSED)
#define WOULD_BLOCK()        (errno == EWOULDBLOCK || errno == EINPROGRESS || errno == EAGAIN)
#define ERRORNO              errno
#endif

#ifndef DO
#define DO(x)   if(!(x)) { return FALSE; }
#endif

/*
 * TCP routines
 */

/* startup routines */
NMEXPORT Boolean tcp_start(void (*log)(int, const char *, unsigned long),
	void (*poll)(void *));
NMEXPORT Boolean tcp_finish();
/* per-connection routines */
NMEXPORT Boolean tcp_init(TcpObject * self, ulong ip_addr, ushort port, ulong timeout, ulong retries, void* app, Boolean sec_socket, void* ssl_res);
NMEXPORT Boolean tcp_done(TcpObject * self);
NMEXPORT void    tcp_recv_init(TcpObject * self, SOCKET socket, ulong timeout, ulong retries, void* poll_data, Boolean sec_socket, void* ssl_res);
NMEXPORT void    tcp_recv_done(TcpObject * self);
NMEXPORT Boolean tcp_socket_done(TcpObject * self);
NMEXPORT Boolean tcp_send(TcpObject * self, const char * buf, ulong len, 
	ulong timeout);
Boolean tcp_send1(TcpObject * self, const char * buf, ulong len, 
	ulong* processed, ulong timeout);
Boolean tcp_recv1(TcpObject * self, char ** buf, ulong size, 
	ulong * processed, ulong timeout) ; 
NMEXPORT Boolean tcp_recv(TcpObject * self, char ** buf, ulong size, 
	ulong * len, ulong timeout);
NMEXPORT Boolean tcp_sendln(TcpObject * self, const char * msg);
NMEXPORT Boolean tcp_sendlnf(TcpObject * self, const char * format, ...);
NMEXPORT Boolean tcp_recvln(TcpObject * self, char ** line);
NMEXPORT Boolean tcp_recvall(TcpObject * self, char ** buf, ulong * size);
NMEXPORT Boolean tcp_recvsz(TcpObject * self, char * buf, ulong * size);
NMEXPORT Boolean tcp_writable(TcpObject * self, time_t timeout);
NMEXPORT Boolean tcp_readable(TcpObject * self, time_t timeout);
NMEXPORT void tcp_set_error(TcpObject * self, const char * major, ulong minor);
NMEXPORT void tcp_set_poll_data(TcpObject * self, void * poll_data);
/* miscellenous routines */
NMEXPORT NmBool  tcp_ioctl(SOCKET socket, int request, void * arg);
NMEXPORT Boolean tcp_address(const char * host, NmHost * nmhost);
NMEXPORT ulong   tcp_msglen(const char * msg);
NMEXPORT void    tcp_tokenize(char * line, char ** key, char ** value);
NMEXPORT char *  tcp_strln(const char * str, ulong * len, char ** next);
/* globals */
extern time_t tcp_timeout;
extern ulong tcp_tries;

#ifdef BUILD_SSL
NMEXPORT Boolean tcp_create_secure_context (SSLContext* src, SSLContext** dest,
  void* connRef) ;
NMEXPORT Boolean tcp_server_secure_handshake (TcpObject* self) ;
#endif


#ifdef __cplusplus
}
#endif

#endif /* _TCP_H_ */
