#include <afx.h>
//#include "stdafx.h"
//#include <fstream.h>
#include <nmsizes.h>

#define MAX_SEARCH_LIST 20
#define MAX_MSG 100
#define MAX_STR 2048
#define MAX_NAME 64
#define MAX_VALUE 4096
#define MAX_PROPERTY MAX_NAME+MAX_VALUE
#define MAX_ELEMENTS 100
#define MAX_PATH2 256
#define MAX_FILE 8192
#define EXCEPTION_BROWSE_PREFIX "Unable to browse location - "
#define EXCEPTION_LOAD_PREFIX "Can not open the input file - "
#define MAX_MSG_PREFIX 64
#define MAX_MNEMONIC 256

//Error Messages
#define LIST_INIT_FAILED "Internal Error : Failed to inintialize name/value pairs list"
#define UNDEFINED_NONCE_SECRET    "The Nonce secret is undefined in the config file"
#define UNDEFINED_RESPONDER_URL   "The Responder URL is undefined in the config file"
#define UNDEFINED_WEBSERVER       "The Web Server URL is undefined in the config file"
#define NONEXISTENT_USER          "The user doesn't exist in the Minder database"
#define NO_REG                    "No searches have been saved yet!."

class NMException {
public:
	char msg[MAX_MSG];
	char *getMessage();
	NMException(char *);
};

class LoadException : public NMException {
  char msg2[MAX_MSG+MAX_MSG_PREFIX];
public:
  LoadException(char *s) : NMException(s) {};
	char *getMessage();
};

class BrowseException : public NMException {
  char msg2[MAX_MSG+MAX_MSG_PREFIX];
public:
  BrowseException(char *s) : NMException(s) {};
	char *getMessage();
};

class EbayException : public NMException {
public:
  EbayException(char *s) : NMException(s) {};
	friend char *getMessage();
};

class SearchListElement {
	char url[MAX_URL];
	char regID[MAX_DIGITS];
	char title[MAX_TITLE];
	char date[MAX_DIGITS];
	char updatePeriod[MAX_DIGITS];
	char duration[MAX_DIGITS];
	char mnemonic[MAX_MNEMONIC];
	char disabled[MAX_DIGITS];
public:
	SearchListElement (char *u, char *t, char *r, char *m, char *d);
	char *getUrl();
	char *getTitle();
	char *getRegID();
	char *getDate();
	char *getUpdatePeriod();
	char *getDuration();
	char *getMnemonic();
	char *getRegDisabled();
  void addUpdatePeriod(char *up);
  void addDuration(char *d);
  void addRegDisabled(char *);
};

class SearchList {
  SearchListElement *list[MAX_SEARCH_LIST];
  unsigned numElements;
public:
  SearchList() {};
  ~SearchList();
	void load(char *);
	char *getUrl(unsigned);
	char *getTitle(unsigned);
	char *getRegID(unsigned);
	char *getDate(unsigned);
	char *getUpdatePeriod(unsigned);
	char *getDuration(unsigned);
	char *getMnemonic(unsigned);
	char *getRegDisabled(unsigned);
  unsigned length() {return numElements;}
};

class Element {
	char name[MAX_NAME];
	char value[MAX_VALUE];
public:
	Element (char *, char *);
	char *getName();
	char *getValue();
	void putName(char *);
	void putValue(char *);
};

class NMProps  {
	char fileName[MAX_PATH2];
	unsigned numElements;
	Element *elements[MAX_ELEMENTS];
public: 
	NMProps() {numElements=0;}
  ~NMProps();
	void init(char *);
  void deleteProperty(char *);
  void addProperty(char *, char *);
  void addElement(Element *);
  void append(NMProps *);
  char *getProperty(char *);
  char *getProperty(char *, char *);
  void put(char *, char *);
  char *getProperty(unsigned);
  unsigned getNumElements() {return numElements;}
	char *getKey(unsigned);
};

