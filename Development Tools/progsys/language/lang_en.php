<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
$l_noentries = "no entries";
$l_heading = "ProgSys";
$l_undefined = "undefined";
$l_generated = "generated";
$l_back = "back";
$l_submit = "submit";
$l_callingerror = "calling error";
$l_function_disabled = "function disabled";
$l_timezone_note = "all times are in";
$l_changelog_heading = "Changelog";
$l_version = "Version";
$l_changes = "Changes";
$l_programm = "Program";
$l_bugtraq_heading = "Bugtracking";
$l_state = "State";
$l_states = array ("new","open","work in progress","fixed","deferred");
$l_fixversion = "Fixed on version";
$l_bug = "Bug";
$l_fix = "Fix";
$l_statefilter = "only show bugs with following state";
$l_ok = "OK";
$l_all = "all";
$l_enternewbug = "enter new bug";
$l_yourname = "Your name";
$l_bugreport = "Bugreport";
$l_noname = "Please provide Your name";
$l_nobugreport = "Please provide a bugreport";
$l_bugadded = "Your bugreport was entered";
$l_buglist = "Buglist";
$l_noversion = "Please provide a program version";
$l_sendermail = "Your email";
$l_usedversion = "used program version";
$l_invalidemail = "Please provide a valid email";
$l_progundefined = "no such program defined";
$l_newsletter_heading = "Newsletter";
$l_references_heading = "References";
$l_inputprelude = "Please fill in this form,<br>if You are using this program on Your website.<br>If you agree to publish Your entry,<br>only website name and URL will be visible.";
$l_sitename = "Name of website";
$l_siteurl = "URL of website";
$l_contact = "Contact person";
$l_email = "Email";
$l_publish = "publish entry";
$l_heardfrom = "Where did You find this program";
$l_nourl = "Please provide an URL";
$l_nositename = "Please provide name of website";
$l_noemail = "Please provide a valid email";
$l_inputerrors = "There were errors in input:";
$l_entryadded = "Entry added.";
$l_reflist = "Referencelist";
$l_pin1 = "Your PIN is";
$l_pin2 = "please write down this PIN, because You will need it to update Your entry by Yourself.";
$l_reflistprelude = "Websites using this program:";
$l_reportbroken = "report broken link";
$l_alsousing = "Are You also using it?";
$l_addsite = "add Your site";
$l_updateentry = "update Your entry";
$l_brokenprelude = "Report following link as broken:";
$l_sendreport = "send report";
$l_reason = "Reason";
$l_brokendone = "Thanks for Your report.<br>We will check this soon.";
$l_pin = "PIN";
$l_pinlost = "PIN lost";
$l_nosuchentry = "No such entry.";
$l_pinmail = "Your PIN for the referencelist is:\r\n";
$l_pinsubject = "PIN for referencelist";
$l_pinsent = "Your PIN was sent by email.";
$l_salutation1 = "Dear";
$l_salutation2 = "Dear Ladies and Gentlemen";
$l_greeting = "With kind regards";
$l_entryupdated = "Entry updated";
$l_note2us = "additional note to us";
$l_broken_reasons = array("DNS: Name Not Found","No Reply (Cannot Connect)","Moved","401 Authorization Required","404 Not Found","500 Server Error","Other");
$l_todo_heading = "planned features";
$l_todo_states = array ("planned","working on","deferred","finished");
$l_functiondisabled = "function disabled";
$l_subscriptionprelude = "subscribe to newsletter for {progname}";
$l_nofreemailer = "(no addresses at freemailers)";
$l_emailtype = "Format of email";
$l_htmlmail = "HTML";
$l_ascmail = "plain text";
$l_subscribe = "subscribe";
$l_forbidden_freemailer = "An address at this freemailer is not allowed for subscribing newsletters";
$l_allready_subscribed = "A subscription for this email still exists.";
$l_allready_pending = "There allready is an unconfirmed subscription request for this email.<br>Please wait for confirmation request beeing sent to you by email.";
$l_hours = "hours";
$l_subscriptionconfirmmail = "Hello,\nyou have requested to subscribe to the newsletter for \"{progname}\".\n
To ensure this request really was done by we send this confirmation rquest.\n
To activate your subscription please visit this URL within {confirmtime} hours:\n{confirmurl}\n
If the request was not done by you, just ignore this mail and do nothing.\n\n
This is an automatic generated mail, don't reply on it.";
$l_subscriptionconfirmsubject = "Newsletter ({progname}) - confirmation request";
$l_subscriptiondone = "Thank you for subscribing to this newsletter";
$l_missingemail = "No email provided";
$l_missingid = "No ID provided";
$l_noconfirmentry = "No unconfirmed subscription request found for you.<br>maybe the maximum time to answer the request has passed<br>or you allready confirmed the request.";
$l_subscriptionconfirmed = "Your subscription for the newsletter now is active.";
$l_noremoveentry = "No subscription found to remove.";
$l_unsubscribed = "Subscription removed.";
$l_subscriptionremoveprelude = "Do you reallay want to cancel the subscription for {email}?";
$l_yes = "yes";
$l_ratingprelude = "How important would this functionality be to you?";
$l_rate = "rate";
$l_ratings = array("unimportant","less important","rather important","very important");
$l_ratingdone = "Your rating has been entered.";
$l_subscriptionconfirminfo = "To ensure you are the owner of the entered email<br>a confirmation request has been sent to this email.<br>
Please follow the instructions in the email to activate your subscription.";
$l_featurerequests = "featurerequests";
$l_newrequest = "request feature";
$l_request = "request";
$l_norequesttext = "Please enter text for request";
$l_requestadded = "your request was submitted";
$l_page = "Page";
$l_of = "of";
$l_entries = "entries";
$l_releasestates = array("-----","maybe","probably","most probably ","surely","rather not","sure not","implemented");
$l_releasestat = "will be implemented";
$l_modcomment = "comment of admin";
$l_unsubscribe = "unsubscribe";
$l_unsubscriptionconfirmmail = "Hello,\nyou have requeste to unsubscribe from the newsletter for \"{progname}\".\n
To ensure the request really was done by you, this confirmation request has been sent by us.\n
To remove your subscription, please go to this URL:\n{confirmurl}\n
If you haven't requested tu unsubscribe, you need not to do anything.";
$l_unsubscriptionconfirmsubject = "Newsletter ({progname}) - unsubscribe request";
$l_unsubscribesent = "An email containing the URL to confirm your unsubscription has been sent.<br>Please follow the instructions in this email to remove your subscription.";
$l_unsubscriptionprelude = "Unsubscribe from Newsletters for {progname}";
$l_rating = "actual rating";
$l_votes = "votes";
$l_powered_by = "Powered by";
$l_surename = "Surename";
$l_firstname = "Firstname";
$l_mandatory_fields = "mandatory fields";
$l_online = "online";
$l_tempoffline = "temporary offline";
$l_notdefined = "not defined";
?>