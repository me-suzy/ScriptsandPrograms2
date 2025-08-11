<?php
   /**
   This file provides some functions to facilitate handling of
   participant cookies
   * @author Marc-Antoine Parent <maparent@acm.org>, based on work by
   * @author Ian Clysdale <ian@clysdale.ca>, based on work by
   * @author Mike Gifford <mike@openconcept.ca>
   * @copyright   Copyright (C) 2005 OpenConcept Consulting
   * @version $Id: BE_ParticipantFunctions.inc,v 1.9 2005/05/26 17:53:27 mgifford Exp $
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


require_once($_PSL['classdir'] . '/DO/Be_contact.php');


/**
 * Returns a Random value for URL/user identification.  One of the
 * very few functions lifted wholesale from the original PET_Petition
 * implementation.
 *
 * @param int      $length The length of the value - the default is 8 characters.
 * @return string          The generated random string.
 */
function generateRandomKey($length = 8) {
   mt_srand((double)microtime() * 1000000);
   $possible = '0123456789abcdefghjiklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $str = '';
   while (strlen($str) < $length) {
      $str .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
   }
   return $str;
}

/**
 * Fill in a DO_Be_participant object from the database,
 * according to the randomKey stored in the participant_info cookie.
 * Returns a boolean indicating whether the information was found
 */
function contactFromCookie(&$participant) {
   if (array_key_exists('participant_info', $_COOKIE)) {
      $partKey = $_COOKIE['participant_info'];
      if (!$partKey)
         return FALSE;
      if (is_int($partKey)) {
         // old-style, insecure integer cookies
         setcookie('participant_info', FALSE);
         return FALSE;
      }
      $participant->randomKey = $partKey;
      // Note: What if duplicated? Answer: Use latest.
      $participant->orderBy("dateModified desc");
      $found = $participant->find(TRUE);
      // clean search
      $participant->orderBy();
      if ($found) {
        return TRUE;
      }
      // The cookie must be invalid: fail silently
      $expiry = 180 * 24 * 60 * 60; // 180 days... Should it expire at all?
      setcookie('participant_info', "", time()+$expiry,
                '/', str_replace('www.','.', $_SERVER['SERVER_NAME']));
      unset($participant->randomKey);
      return FALSE;
   }
   return FALSE;
}

/**
 * Fill in a DO_Be_participant object that will correspond to the
 * logged-in author. The author may already have a participant tied in;
 * otherwise get as much as possible from the author fields
 * Returns a boolean indicating whether any information was found
 */
function contactFromAuthor(&$participant) {
   // TODO: Get it from session object?
   if (!array_key_exists('user_info', $_COOKIE))
      return FALSE;
   $author = new DO_Psl_author;
   $authorID = clean(unserialize(base64_decode($_COOKIE['user_info'])));

   if ($author->get('author_name',$authorID[1])) {
      $participant->author_id = $author->author_id;
      $participant->orderBy("dateModified desc");
      if ($participant->find(TRUE)) {
         return TRUE;
      }
      //extract data from author
      $participant->displayName = $author->author_realname;
      $nameParts = explode(" ",$author->author_realname);
      $participant->firstName = array_shift($nameParts);
      $participant->lastName = array_pop($nameParts);
      $participant->author_id = $author->author_id;
      $participant->email = $author->email;
      return TRUE;
   }
   return FALSE;
}

/** Does the participant have enough information for storage in the database?
  * returns a boolean
  */
function canStoreContact($participant) {
   return ($participant->firstName
       and $participant->lastName
       and is_valid_email($participant->email));
}

/** Create or reuse a random value for the participant.
 * (do not use the ID for security reasons)
 * Set it as the cookie.
 */
function setParticipantCookie(&$participant) {
   $randomKey = $participant->randomKey;
   if (!$randomKey) {
      $randomKey = generateRandomKey(8);
      if ($participant->contactID) {
         $old = clone($participant);
         $participant->randomKey = $randomKey;
         $participant->update($old);
      } else {
         $participant->randomKey = $randomKey;
      }
   }
   // FOR TESTING PURPOSES ONLY
   setcookie('participant_info', $randomKey,
      time()+15552000, '/', str_replace('www.','', $_SERVER['SERVER_NAME']));
}

/** Are these two contacts similar?
  * Define similar as same name and email, modulo case, spacing and punctuation
  */
function similarContacts($old, $new) {
   return (  (strtolower($new->email)
           == strtolower($old->email))
         and (strtolower(preg_replace('/\W/','',$new->lastName))
           == strtolower(preg_replace('/\W/','',$old->lastName)))
         and (strtolower(preg_replace('/\W/','',$new->firstName))
           == strtolower(preg_replace('/\W/','',$old->firstName))));
}

/** Are these two contacts equivalent?
  * I.e. do they correspond to the same information,
  * modulo case, spacing and punctuation
  */
function equivalentContacts($old, $new) {
   $table = $old->table();
   $keys = $old->keys();
   foreach ($keys as $key) {
      unset($table[$key]);
   }
   foreach (array_keys($table) as $column) {
      if (($table[$column] & 2) == 0) {
         // only look at string arguments
         continue;
      }
      if (isset($new->$column) and isset($old->$column)) {
         // see if they differ once stripped to lower-case alphanums.
         if (  strtolower(preg_replace('/\W/','',$new->$column))
            != strtolower(preg_replace('/\W/','',$old->$column))) {
            return false;
         }
      } elseif (!isset($new->$column) and !isset($old->$column)) {
         //ok
      } else {
         // if one is set and not the other: consider different.
            return false;
      }
   }
   return true;
}

/**
 * reuse the old row for a contact if it is equivalent to the new one
 */
function reuseContactIfUnchanged($contact) {
   //returns DO_Be_contact (old or new)
   if (!$contact->email)
      return $contact;
   $old = new DO_Be_contact();
   $old->email = $contact->email;
   $count = $old->find(FALSE);
   if (!$count)
      return $contact;
   $table = $contact->table();
   unset($table['contactID']);
   $columns = array_keys($table);
   foreach ($columns as $column) {
      if (($table[$column] & 2) == 0) {
         // only look at string arguments
         unset($table[$column]);
         continue;
      }
      if (isset($contact->$column)) {
         $table[$column] = strtolower(preg_replace('/\W/','',$contact->$column));
      } else {
         unset($table[$column]);
      }
   }
   while ($old->fetch()) {
      $same = true;
      foreach (array_keys($table) as $column) {
         if (isset($table[$column]) and isset($old->$column)) {
            // see if they differ once stripped to lower-case alphanums.
            if ($table[$column] != strtolower(preg_replace('/\W/','',$old->$column))) {
               $same = false;
               break;
            }
         } elseif (!isset($table[$column]) and !isset($old->$column)) {
            //ok
         } else {
            // if one is set and not the other: consider different.
            $same = false;
            break;
         }
      }
      if ($same) {
         // use old row; but actuall use new values.
         $contact->contactID = $old->contactID;
         $contact->update($old);
         return $contact;
      }
   }
   return $contact;
}


/**
 * Fill in a DO_Be_participant object according to either the
 * author information, the participant_info cookie, or the $ary (GET/POST)
 * fields. If the new participant is very similar, update and reuse the
 * table row; otherwise unset the key, so a new participant will be created,
 * but set a field which will link the new participant instance
 * to the previous one.
 * Rationale: A signature in a petition should not change, so new instance
 * author should take priority over cookie if both exist, to better allow
 * multi-user/single-machine scenarios
 */
function getParticipant(&$participant, $ary) {
   $isKnown = contactFromAuthor($participant);
   if (!$isKnown) {
      $isKnown = contactFromCookie($participant);
   }
   $knownParticipant = clone($participant);
   $participant->fillFromAry($ary);
   if ($isKnown) {
      if (equivalentContacts($knownParticipant, $participant)) {
         $participant->update($knownParticipant);
      } else {
         // we will make it a new contact
         $logicalId = $knownParticipant->sameContactAs or $knownParticipant->contactID;
         unset($participant->contactID);
         $participant->sameContactAs = $logicalId;
      }
   } else {
      $participant = reuseContactIfUnchanged($participant);
   }
   //  Additionally set variables that can't be
   // read directly from the information array.
   //  The new data object class currently does not seem
   // to allow default values, which is rather annoying
   // and should be fixed.  (At which point someone will
   // hopefully do me the kindness of removing this comment.)
   // MAP: You can put default values in the constructor, but
   // it is a bad idea. A new object should be pristine, to allow for
   // searches etc.
   // Also, many of your cases were unwarranted: Date created/modified
   // are handled by BE_DataObject:insert/update.
   if(!isset($participant->contactType)) {
      $participant->contactType = 1;
   }
   if(!isset($participant->displayName) || empty($participant->displayName)) {
      $participant->displayName = $participant->firstName . ' ' . $participant->lastName;
   }
   if(!isset($participant->followupGlobal)) {
      $participant->followupGlobal = 0;
   }
   return $participant;
}

?>
