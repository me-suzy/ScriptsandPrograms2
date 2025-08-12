<?php

/** @brief The only application object */
$GLOBALS['APP'] = new SSApp;

/** @brief The object used to send notifications to users */
$GLOBALS['NOTIFY'] = new SSNotification;

/** @brief The object that handles engine events such as added stories, scene or forks, etc. */
$GLOBALS['EVENTS'] = new SSEventHandler;

/** @brief Singleton configuration object for the application
	The configuration object holds everything from database
	connection settings to website preferences.  Use this to 
	retrieve and set these properties.  To see a full list of 
	the possible properties, see the constructor for the class.
*/
$GLOBALS['CONFIG'] = new SSConfig;

/** @brief Initializes the database handler */
$GLOBALS['DB_HANDLER'] = new SSDBase;

/** @brief Caches storystream objects for reuse within this transaction */ 
$GLOBALS['CACHE'] = array ();

///////////////////////////////////////////////////
// CONNECT TO DATABASE
///////////////////////////////////////////////////
$GLOBALS['DBASE'] = $GLOBALS['DB_HANDLER']->connect ();
if (DB::isError ($GLOBALS['DBASE'])) {
	echo $GLOBALS['DBASE']->toString ();
}

?>
