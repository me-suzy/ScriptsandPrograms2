/*	$Id: smsmtp.h,v 1.6 1999/03/07 08:16:46 josh Exp $	*/
// constants......

#define STANDARD_LINEBREAK	10
#define S_SENDER_LEN		100
#define S_SUBJECT_LEN		100
#define S_MAILMACHINE_LEN	200
#define S_MESSAGE_LEN		65530

#define	MAX_RETRIES			3

// class definition
// smtp class
// sends messages to a smtp-server

// function:
// 		smtp::smtp()
// description:
//		constructor for the smtp class
//		initializes all variables
//		allocates memory
// returns:
//		nothing

// function:
//		void smtp::setmailmachine(char *mach)
// description:
//		sets the mail machine for the current smtp class instance
// returns:
//		nothing

// function:
//		int smtp::sendmail()
// description:
//		sends the mail 
// returns:
//		1 if all went well
//		0 if mail was not correctly sent

// function:
//		void smtp::setsubject()
// description:
//		sets the subject for the current instance of the smtp class
// returns:
//		nothing

// function:
//		void smtp::setreceiver()
// description:
//		sets the receiver for the current instance of the smtp class
// returns:
//		nothing

// function:
//		void smtp::setsender()
// description:
//		sets the sender for the current instance of the smtp class
// returns:
//		nothing

// function:
//		void smtp::setlinebreak()
// description:
//		sets the linebreak for the message
// returns:
//		nothing

// function:
//		void smtp::setmessage()
// description:
//		sets the message and replaces the linebreaks by \r\n
// returns:
//		nothing

// function:
//		smtp::~smtp()
// description
// 		cleans things up
// returns:
//		nothing


class smtp
{
private:

	int linebreak;
	char sender[S_SENDER_LEN+1];
	char subject[S_SUBJECT_LEN+1];
	char *message;
	char *header;
	char **recipients;
	
public:
	smtp();
	~smtp();

	void setlinebreak(char linebreak);
	void setmessage(const char *);
	void setheader(const char *);
	void setsubject(const char *);
	void setreceiver(const char *);
	void setrecipients(char * const *);
	void setsender(const char *);
	void setmailmachine(const char *);

	int sendmail(int machine_type=0);

	static char **mailmachines;
	static int nummachines;
	static int nummachines_Reg;
	static int nummachines_Help;

};
