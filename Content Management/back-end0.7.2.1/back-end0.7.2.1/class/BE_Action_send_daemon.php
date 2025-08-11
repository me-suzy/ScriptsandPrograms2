<?php
   // $Id: BE_Action_send_daemon.php,v 1.3 2005/03/11 16:00:15 mgifford Exp $
   /**
    * BE_Action_send_daemon.php
    *
    * Stand-alone PHP application to send outgoing Actions on behalf
    *  of action participants
    *
    * @package     Back-End on phpSlash
    * @author      Peter Bojanic
    * @copyright   Copyright (C) 2003 OpenConcept Consulting
    * @version     $Id: BE_Action_send_daemon.php,v 1.3 2005/03/11 16:00:15 mgifford Exp $
    *
    * This file is part of Back-End.
    *
    * Back-End is free software; you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation; either version 2 of the License, or
    * (at your option) any later version.
    *
    * Back-End is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with Back-End; if not, write to the Free Software
    * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    */

   chdir('../public_html');
   require_once('./config.php');

   $actionObj = pslNew('BE_Action_messaging');
   $actionObj->sendMessages();

?>
