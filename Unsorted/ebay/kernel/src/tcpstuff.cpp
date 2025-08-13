/*	$Id: tcpstuff.cpp,v 1.2 1998/06/23 04:31:21 josh Exp $	*/
#include <stdarg.h>
#include <stdio.h>
#include <string.h>
#ifdef _MSC_VER
#include <winsock.h>
#else
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <fcntl.h>
#include <netdb.h>
#include <unistd.h>
#include <errno.h>
#endif
#include "tcpstuff.h"

#if defined(__sun__) && defined(__svr4__)
extern "C" int gethostname(char *, int);
#endif

// NOT documented ......

#ifdef _MSC_VER
int
startwinsockets()
{
	int status;
	WSADATA WSAData;
	char szTemp[80];

	status = WSAStartup(MAKEWORD(1, 1), &WSAData);
	if (status != 0) 
	{
		sprintf(szTemp, "%d is the err", status);
		return -1;
	}
	return 0;
}

void
stopwinsockets()
{
	WSACleanup();
}
#endif

int
init_connection(const char *machine, 
				const char *service,
				int timeout)
{
	int s, nl, rv, repeat_count = 0;
	struct sockaddr_in sockname;
	struct hostent *hostname;
	struct servent *serv_rec;
	struct timeval tv;
	fd_set fds;
#ifdef _MSC_VER
	unsigned long on;
#endif

	do 
	{
		memset(&sockname, 0, sizeof(struct sockaddr));
		s=socket(AF_INET, SOCK_STREAM, 0);
		if (s < 0) 
			return -1;

		sockname.sin_family = PF_INET;
		serv_rec = getservbyname(service,"tcp");
		if (serv_rec == NULL) {
#ifdef _MSC_VER
			closesocket(s);
#else
			close(s);
#endif
			return -4;
		}
		sockname.sin_port = serv_rec->s_port;

		hostname = gethostbyname(machine);

		if (hostname == NULL)
		{
#ifdef _MSC_VER
			closesocket(s);
#else
			close(s);
#endif
			return -2;
		}
		memcpy(&sockname.sin_addr, hostname->h_addr, hostname->h_length);

		if (timeout > 0)
		{
#ifdef _MSC_VER
			on = 1;
			ioctlsocket(s, FIONBIO, &on);
#else
			fcntl(s, F_SETFL, fcntl(s, F_GETFL, 0) | O_NONBLOCK);
#endif
		}

		nl = sizeof(struct sockaddr_in);
		rv = connect(s, (struct sockaddr *)&sockname, nl);
		if (rv < 0 &&
#ifdef _MSC_VER
			WSAGetLastError() == WSAEWOULDBLOCK)
#else
#ifdef EINPROGRESS
			errno == EINPROGRESS ||
#endif
			errno == EWOULDBLOCK || errno == EAGAIN)
#endif
		{
			tv.tv_sec = timeout;
			tv.tv_usec = 0;
			FD_ZERO(&fds);
			FD_SET(s, &fds);

			rv = select(s + 1, NULL, &fds, NULL, &tv);

			if (rv == 0) {
#ifdef _MSC_VER
				closesocket(s);
#else
				close(s);
#endif
				return -3;
			}

			if (rv > 0)
				rv = 0;
		}

		if (rv < 0)
		{
#ifdef DEBUGGING
#ifdef _MSC_VER
			printf("Attempting to connect\n");
#else
			fprintf(stderr, "Error during connect to `%s': %s\n", machine, strerror(errno));
#endif
#endif
#ifdef _MSC_VER
			closesocket(s);
			Sleep(1000);
#else
			close(s);
			sleep(1);
#endif

			repeat_count++;
			if (repeat_count > 1) 
				return -4;
		}

	} while (rv != 0);

	if (timeout > 0)
	{
#ifdef _MSC_VER
		on = 0;
		ioctlsocket(s, FIONBIO, &on);
#else
		fcntl(s, F_SETFL, fcntl(s, F_GETFL, 0) & ~O_NONBLOCK);
#endif
	}

	return s;
}

void
getmachinename(char *name)
{
	struct hostent *host;
	char temp[200];

	// get host name
	gethostname(temp, sizeof(temp) - 1);
	host = gethostbyname(temp);
	if (host != NULL)
		strcpy(name, host->h_name);
	else
		strcpy(name, "unknown.host.name");
}

void
getdomainname(char *name)
{
	char temp[200];
	char *p;

	// get host information
	
	getmachinename(temp);
	if ((p = strchr(temp, '.')) != NULL)
		p++;
	else
		p = temp;
 	strcpy(name, p);
}

int
sendstring(int socket, int options, const char *format,...)
{
	char temp[26000]; // !#@#%$^%*& (aargh, many space on stack)
	va_list argptr;

	va_start(argptr, format);
	vsprintf(temp, format, argptr);
	va_end(argptr);
	return sendstring(socket, temp, options);
}

int sendstring(int socket, const char *name, int options)
{
	return send(socket, name, strlen(name), options);
}

#define tempLen 50
int
getline(int socket, char *buffer)
{
	char temp[tempLen+1];
	int c, len, linelen = 0;

	buffer[0]=0;
	for (;;)
	{
		len = recv(socket, temp, sizeof(temp) - 1, MSG_PEEK);

		// If we got a -ve socket return code, we've
		// got an error. fake a status of -1
		if (len <= 0)
		{
			strcpy(buffer, "-1");
			return -1;
		}
		
		c = 0;
		while ((temp[c] != '\n') && c < len)
			c++;
		recv(socket, temp, c, 0);
		linelen = c;

		for (c = 0; c < linelen; c++)
		{
			if (temp[c] == '\r') 
			{
				memmove(temp + c, temp + c + 1, linelen - (c + 1));
				linelen--;
			}
		}
		temp[linelen] = 0;
		strcat(buffer, temp);
		if (c != len)
		{
			recv(socket, temp, 1, 0);
			return 0;
		}
	}
}
