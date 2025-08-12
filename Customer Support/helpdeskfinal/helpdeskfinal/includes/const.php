<?php
//
// Project: Help Desk support system
// Description: Constants
//

// Ticket categories
define("CAT_OTHER", 1);

// Ticket priorities
define("PRIORITY_CRITICAL", 1);
define("PRIORITY_HIGH", 2);
define("PRIORITY_NORMAL", 3);
define("PRIORITY_LOW", 4);

// Ticket status
define("STATUS_OPEN", 1);
define("STATUS_CLOSED", 2);
define("STATUS_ONHOLD", 3);
define("STATUS_RESOLVED", 4);

// User levels/ranks
define("RANK_ADMIN", 1);		// Administrator/Manager rank
define("RANK_SPECMANAGER", 2);	// Special manager rank
define("RANK_USER", 0);			// Plain user

?>