<?PHP
// Sending Rules.
// This file is used to change how messages are sent.

// Type of sending
// 1 = popup window with sending in background.  You may close the browser client in this type of sending.
// 2 = sends in increments on screen. (Reccomended if your sending keeps timeing out as with this method you specify how many messages are sent in periods of time.)
$sa_type = "1";

##########################################################################
// You only have to modify below if you are using sending type 2
##########################################################################

// Amount of messages to send per process / refresh
// We suggest to keep this under 100
$sa_amt = "50";

// Amount of time allowed per sending process.
// EX: if you have the above number set to 40, and the allowed time set to 30 it will send 40 messages every 30 seconds.
$sa_time = "4";

?>