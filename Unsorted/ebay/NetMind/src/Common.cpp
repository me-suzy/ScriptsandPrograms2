#include "common.h"

/* 
 * NMString::strcat
 * cat the string and re-alloc if necessary
 * 
 * Author mk : 12/29/98
 *
 */
NMException::NMException(char *s) {
	strcpy(msg, s);
}
char * NMException::getMessage() {return msg;}

char * LoadException::getMessage() {
	sprintf(msg2, "%s %s", EXCEPTION_LOAD_PREFIX, msg);
	return msg2;
}

/* 
 * BrowseException::getMessage
 * Return the message in the exception
 * 
 * Author mk : 12/29/98
 *
 */
char * BrowseException::getMessage() {
	sprintf(msg2, "%s %s", EXCEPTION_BROWSE_PREFIX, msg);
	return msg2;
}

SearchListElement::SearchListElement(char *u, char *t, char *r, char *m, char *d) {
  strcpy(url, u);
  strcpy(title, t);
  strcpy(regID, r);
  strcpy(mnemonic, m);
  strcpy(date, d);
}
char *SearchListElement::getDate() {return date;}
char *SearchListElement::getUrl() {return url;}
char *SearchListElement::getTitle() {return title;}
char *SearchListElement::getRegID() {return regID;}
char *SearchListElement::getMnemonic() {return mnemonic;}
char *SearchListElement::getUpdatePeriod() {return updatePeriod;}
char *SearchListElement::getDuration() {return duration;}
char *SearchListElement::getRegDisabled() {return disabled;}
void SearchListElement::addUpdatePeriod(char *up) {strcpy(updatePeriod, up);}
void SearchListElement::addDuration(char *d) {strcpy(duration, d);}
void SearchListElement::addRegDisabled(char *a) {strcpy(disabled, a);}

SearchList::~SearchList() {
  unsigned i;
	for (i=0; i<numElements; i++) {
		if (list[i] != 0) {
			delete list[i];
		}
	}
}

void SearchList::load(char *replyData) {
  char url[MAX_URL];
  char title[MAX_TITLE];
  char regID[MAX_DIGITS];
  char mnemonic[MAX_MNEMONIC];
  char date[MAX_DIGITS];
  char updatePeriod[MAX_DIGITS];
  char duration[MAX_DIGITS];
  char disabled[MAX_DIGITS];
  unsigned j = 0;
	unsigned numElements2 = 0;
  int subElement = 0;
  unsigned inQuote = 0;
	unsigned i;
  char *regPtr;

  numElements = 0;
  memset(date, 0, MAX_DIGITS);
  memset(disabled, 0, MAX_DIGITS);
	for (i=0; i<MAX_SEARCH_LIST; i++) {
		list[i] = 0;
	}

	if ((regPtr = strstr(replyData, "REG_LIST=\"")) != 0) {
    regPtr += strlen("REG_LIST=\"");

    while (*regPtr != '\0') {
			//is this a complete REG_LIST line (registration)
      if (*regPtr == '\n') {
				if (numElements >= MAX_SEARCH_LIST) {
					break;
				}
        list[numElements++] = new SearchListElement(url, title, regID, mnemonic, date);
        j=0;
        subElement++;
        inQuote = 0;
        //we're done
        if (*(++regPtr) == 0x0A)
          break;
        continue;
      }

      //skip all literal quotes and determine inQuote level
      if (*regPtr == '"') {
        regPtr++;
        continue;
      }

      if (*regPtr == '\\') {
        if (*(regPtr+1) == '\"') {
          inQuote = inQuote ? 0 : 1;
          regPtr += 2;
          continue;
        }
        while (*regPtr == '\\') {
          regPtr++;
        }
        //to preserve the quotes within mnemonic remove these three lines
        if (*regPtr == '\"') {
          regPtr++;
        }
        continue;
      }

      switch(subElement%5) {
      case 0:
        regID[j++] = (*regPtr == ',') ? '\0' : *regPtr;
	      break;
      case 1:
        url[j++] = ((*regPtr == ',') && !inQuote) ? '\0' : *regPtr;
	      break;
      case 2:
        title[j++] = (*regPtr == ',') ? '\0' : *regPtr;
	      break;
      case 3:
        mnemonic[j++] = ((*regPtr == ',') && !inQuote) ? '\0' : *regPtr;
	      break;
      case 4:
        date[j++] = (*regPtr == ',') ? '\0' : *regPtr;
	      break;
      default:
	      return;
      }

      regPtr++;
      if (!inQuote && (*(regPtr-1) == ',')) {
        j=0;
        subElement++;
      } 
    }
  }

  subElement = 0;
  j = 0;
	if ((regPtr = strstr(replyData, "PREF_LIST=\"")) != 0) {
    regPtr += strlen("PREF_LIST=\"");

    while (*regPtr != '\0') {
      if (*regPtr == '\n') {
				if (numElements2 > numElements) {
					break;
				}
        list[numElements2]->addUpdatePeriod(updatePeriod);
        list[numElements2]->addDuration(duration);
        list[numElements2]->addRegDisabled(disabled);
        subElement++; numElements2++; j=0;
        //we're done
        if (*(++regPtr) == 0x0A)
          break;
        continue;
      }

      if (*regPtr == '"') {
        regPtr++;
        continue;
      }

      switch(subElement%11) {
      case 0:
        updatePeriod[j++] = (*regPtr == ',') ? '\0' : *regPtr;
	      break;
      case 1:
        duration[j++] = (*regPtr == ',') ? '\0' : *regPtr;
	      break;
      case 2:
      case 3:
      case 4:
      case 5:
      case 6:
      case 7:
      case 8:
      case 9:
	      break;
      case 10:
        disabled[j++] = (*regPtr == ',') ? '\0' : *regPtr;
	      break;
      default:
	      return;
      }

      regPtr++;
      if (*(regPtr-1) == ',') {
        j=0;
        subElement++;
      } 
    }
  }

}

char *SearchList::getUrl(unsigned i) {return list[i]->getUrl();}
char *SearchList::getTitle(unsigned i) {return list[i]->getTitle();}
char *SearchList::getRegID(unsigned i) {return list[i]->getRegID();}
char *SearchList::getMnemonic(unsigned i) {return list[i]->getMnemonic();}
char *SearchList::getDate(unsigned i) {return list[i]->getDate();}
char *SearchList::getUpdatePeriod(unsigned i) {return list[i]->getUpdatePeriod();}
char *SearchList::getDuration(unsigned i) {return list[i]->getDuration();}
char *SearchList::getRegDisabled(unsigned i) {return list[i]->getRegDisabled();}

/* 
 * Element::Element
 * Construct the element with the static sizes defined in .h
 * 
 * TBD ... Dynamically allocate the memory as exact fit
 * 
 * Author mk : 12/29/98
 *
 */
Element::Element (char *nameX="", char *valueX="") {
	strcpy(name,nameX);
	strcpy(value,valueX);
} 
void Element::putName(char *nameX) {strcpy(name, nameX);}
void Element::putValue(char *valueX) {strcpy(value, valueX);}
char *Element::getName() {return (name);}
char *Element::getValue() {return (value);}

/* 
 * NMProps::~NMProps
 * Deconstructor
 * 
 * Author mk : 12/29/98
 *
 */
NMProps::~NMProps() {
  unsigned i;
	for (i=0; i<numElements; i++) {
    delete elements[i];
	}
}

/* 
 * NMProps::addProperty
 * Add a property to the object
 * 
 * Author mk : 12/29/98
 *
 */
void NMProps::addProperty(char *name, char *value) {
	elements[numElements] = new Element(name, value);
	numElements++;
}

/* 
 * NMString::strcat
 * Add a new property as an element
 * 
 * Author mk : 12/29/98
 *
 */
void NMProps::addElement(Element *elem) {
	elements[numElements] = new Element(elem->getName(), elem->getValue());
	numElements++;
}

/* 
 * NMProps::put
 * put the value into the key
 * 
 * Author mk : 12/29/98
 *
 */
void NMProps::put(char *name, char *value) {
	unsigned i;

	/* replace an element value if the elemeny, by name, already exists */
	for (i=0; i<numElements; i++) {
		if (strstr(elements[i]->getName(), name)) {
			elements[i]->putValue(value);
			return;
		}
	}

	/* otherwise add a new element to the end */
	elements[numElements] = new Element(name, value);
	numElements++;
}

/* 
 * NMProps::getKey
 * Get a property key by index
 * 
 * Author mk : 12/29/98
 *
 */
char *NMProps::getKey(unsigned i) {
	if (i<numElements)
		return (elements[i]->getName());
	return 0;
}

/* 
 * NMProps::getProperty
 * Get a property value by index
 * 
 * Author mk : 12/29/98
 *
 */
char *NMProps::getProperty(unsigned i) {
	if (i<numElements)
		return (elements[i]->getValue());
	return 0;
}

/* 
 * NMProps::getProperty
 * Get a property from given a key
 * 
 * Author mk : 12/29/98
 *
 */
char *NMProps::getProperty(char *name) {
	unsigned i;
	for (i=0; i<numElements; i++) {
		if (strcmp(elements[i]->getName(), name) == 0) {
			return (elements[i]->getValue());
			break;
		}
	}
	return 0;
}

/* 
 * NMProps::getProperty
 * Get a property from given a key
 * 
 * Author mk : 12/29/98
 *
 */
char *NMProps::getProperty(char *name, char *defaultVal) {
	unsigned i;
	for (i=0; i<numElements; i++) {
		if (strcmp(elements[i]->getName(), name) == 0) {
			return (elements[i]->getValue());
			break;
		}
	}
	return defaultVal;
}

/* 
 * NMProps::init
 * 
 * Author mk : 12/29/98
 *
 */
void NMProps::init(char * fileName) {}

/* NMProps::deleteProperty
 * Add all the properties form props into the existing properties
 * 
 * Author mk : 12/29/98
 *
 */
void NMProps::deleteProperty(char * key) {}

/* 
 * NMProps::append
 * Add all the properties form props into the existing properties
 * 
 * Author mk : 12/29/98
 *
 */
void NMProps::append(NMProps *props) {
	unsigned i;

	for (i=0; i<props->numElements; i++) {
		if (i > numElements) {
			//TBD...double the vector for more elements	
		}
		addElement(props->elements[i]);
	}
}
