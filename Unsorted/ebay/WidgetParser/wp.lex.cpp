/*	$Id: wp.lex.cpp,v 1.5 1999/05/19 02:34:06 josh Exp $	*/
%{
// Lex source code that will replace <eBay> tags with
//  output from appropriate widgets
#include "eBayTypes.h"
#include "clseBayWidget.h"
#include "clsWidgetHandler.h"
#include "clsWidgetParserApp.h"
#include "assert.h"

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <stream.h>
#endif

#include <fstream.h>

bool eBayTagMode = false;		// true if "<eBay" tag found and haven't yet reached ">"
char *cParam;					// for storing a parameter
vector<char *> vArgs;			// for storing the parameter list
int i;							// for iterating the parameter list
clseBayWidget *pWidget = NULL;	// for emiting the HTML
char *cLastToken;				// holds previous token
int lastTokenBufLength = 1024;	// how much space cLastToken has
int x;							// strlen
bool debugMode;					// if true, print debugging information
ostream *loggingFile;			// for logging stats

FILE *inFile;					// the FILE we are currently reading
FILE *outFile;					// the FILE we are currently writing
char **fileList = NULL;			// the list of files to process
int nFiles = 0;					// number of files to process
int currentFile = 0;			// the file we are currently processing 0...nfiles-1

// store last token, allocating more space if necessary
void storeLastToken()
{
	int x;
	x = strlen(yytext)+1;
	if (x > lastTokenBufLength)
	{
		delete [] cLastToken;
		lastTokenBufLength=x;
		cLastToken = new char[lastTokenBufLength];
	}
	strcpy(cLastToken, yytext);
}
%}

eBayBeginTag \<eBay[A-Za-z0-9]+
eBayEndTag \>
word [^\<\>\t\n\" ]+
quotedString \"[^\"]*\"
junkChar [^\>]

%%
{eBayBeginTag}		{	/*---------- begin of eBay tag ----------*/
						if (!eBayTagMode)
						{
							eBayTagMode = true;			// signify finding ebay tag
							assert(vArgs.size()==0);	// should be no parameters yet
							cParam = new char[strlen(yytext+1)+1];
							strcpy(cParam, yytext+1);	// save away the tag name
							vArgs.push_back(cParam);	// add tagname to the list

							if (debugMode)
								cerr << "\nTag found: " << cParam << ".";
						}
						storeLastToken();
					}

{eBayEndTag}		{	/*---------- end of eBay tag ----------*/
						if (eBayTagMode)
						{

							if (debugMode)
							{
								// show parameters
								if (vArgs.size() > 1)
								{
									for (i=1; i<vArgs.size(); i++)
										cerr << "\n  " << vArgs[i];
		
								}
							}

							eBayTagMode = false;
							pWidget = ((clsWidgetParserApp*)gApp)->mpWidgetHandler->GetWidget(vArgs[0]);	// use tagname to get widget
							if (pWidget)
							{
								ostrstream theStream;
								char *theStr;
								pWidget->SetLoggingStream(loggingFile);		// for stats logging
								pWidget->SetParams(&vArgs);					// set parameters of widget
								pWidget->EmitHTML(&theStream);				// do it!
								theStream << '\0';
								theStr = theStream.str();
								fwrite(theStr, sizeof(char), strlen(theStr), yyout);  // printf mangles the percent symbols
								delete [] theStr;
								delete pWidget;

							}
							else
							{
								// report unknown ebay tag
								cerr << "\nWarning: Ignoring tag \"" << vArgs[0] << ".\"";

							}
							
							// check for unhandled parameters and report them
							for (i=1; i<vArgs.size(); i++)
								if (vArgs[i] && strlen(vArgs[i])) cerr << "\nUnhandled parameter: " << vArgs[0] << " " << vArgs[i];


							// reset for the next widget
							for (i=0; i<vArgs.size(); i++)
								delete [] vArgs[i];
							vArgs.erase(vArgs.begin(), vArgs.end());

						}
						else
						{
							ECHO;
						}
						storeLastToken();
					}


{quotedString}	|		/*---------- any phrase bounded by quotes ----------*/
{word}				{	/*---------- any word without a >, tab, newline or space ----------*/
						if (eBayTagMode)
						{
							// decide whether or not this token is a new parameter or
							//  part of the previous parameter
							x = strlen(cLastToken); if (!x) x=1;
							if ((cLastToken[x-1]==' ') || (cLastToken[x-1]=='\t'))
							{
								// this is a new parameter
								cParam = new char[strlen(yytext)+1];
								strcpy(cParam, yytext);		// save away the parameter
								vArgs.push_back(cParam);	// add it to the list
							}
							else
							{
								// add this to the previous parameter
								if (vArgs.size() != 0)
								{
									cParam = new char[strlen(vArgs[vArgs.size()-1])+strlen(yytext)+1];
									strcpy(cParam, vArgs[vArgs.size()-1]);
									strcat(cParam, yytext);
									delete [] vArgs[vArgs.size()-1];
									vArgs[vArgs.size()-1] = cParam;
								}
							}
						}
						else
						{
							ECHO;
						}
						storeLastToken();
					}


{junkChar}			{
						if (!eBayTagMode) ECHO;
						if ((yytext[0]=='\"'))
							cerr << "\nFound unmatched " << yytext << ".";
						storeLastToken();
					}


%%

char *makeOutFileFromInFile(char *pInFile)
{
  char *p;
  p = strrchr(pInFile, '/');

  if (!p) 
    p = pInFile;  // no /, so just start at beginning
  else
    p++;         // advance over the /

  if (strlen(p) > 4)
    p += 4;  // skip over the eBay
  else
    p = NULL;

  return p;
}


int setupNextFile()
{
	char *pOutFile = NULL;

	// close out last files
	if (yyin != stdin) fclose(yyin);
	if (yyout != stdout) fclose(yyout);

	// setup the next file
	while (currentFile < nFiles)
	{
		inFile = fopen(fileList[currentFile], "r");
		currentFile++;

		// try to open the input and output files
		if (inFile)
		{
			pOutFile = makeOutFileFromInFile(fileList[currentFile-1]);
			if (pOutFile) outFile = fopen(pOutFile, "w");
			if (pOutFile && outFile)
			{
				// success!!
				yyin = inFile;
				yyout = outFile;
				cerr << "\n\nInput file: " << fileList[currentFile-1] << "\n";
				cerr << "Output file: " << pOutFile << "\n";
				return 1;
			}

		}
		if (!inFile) cerr << "\n\nError opening input file " << fileList[currentFile-1] << ". Skipping.\n";
		if (!outFile && pOutFile) cerr << "Error opening output file " << pOutFile << ". Skipping.\n";
	}

	return 0;		// no more files to process

}

int main(int argc, char *argv[ ])
{

#ifdef _MSC_VER
	g_tlsindex = 0;			// needed to avoid GPF in NT Oracle console apps
#endif
	bool status;
	clsWidgetParserApp* pApp;

	// display matched tags and errors
	debugMode = true;

	// make sure partner id was specified (for cobranding compatibility)
	if (argc<2)
	{
		cerr <<	"Usage: " << argv[0] << " partnerID file1 file2 ...\n";
		return 1;
	}

	// get the file list
	nFiles = argc-2;	// number of files is number of args minus partner and command
	fileList = argv+2;	

	// setup the first file
	setupNextFile();

	// initialization of cLastToken
	cLastToken = new char[lastTokenBufLength];
	strcpy(cLastToken, " ");

	// for stats logging
	loggingFile = new ofstream("widgets.log", ios::app);

	// create the app and let the parsing begin!
	pApp = new clsWidgetParserApp(atoi(argv[1]));	// pass in partner id
	status = pApp->Run();

	// cleanup
	delete [] cLastToken;
	delete loggingFile;

	return status;

}

// redefining yywrap
int yywrap()
{
	if (setupNextFile())
		return 0;			// continue with more parsing

	return 1;				// we're done
}


