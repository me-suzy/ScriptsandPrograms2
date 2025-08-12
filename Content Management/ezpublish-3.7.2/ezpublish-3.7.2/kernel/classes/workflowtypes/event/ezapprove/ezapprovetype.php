<?php
//
// Definition of eZApproveType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZApproveType ezapprovetype.php
  \brief Event type for user approvals

*/

include_once( "kernel/classes/ezworkflowtype.php" );
include_once( 'kernel/classes/collaborationhandlers/ezapprove/ezapprovecollaborationhandler.php' );

define( "EZ_WORKFLOW_TYPE_APPROVE_ID", "ezapprove" );

define( "EZ_APPROVE_COLLABORATION_NOT_CREATED", 0 );
define( "EZ_APPROVE_COLLABORATION_CREATED", 1 );

class eZApproveType extends eZWorkflowEventType
{
    function eZApproveType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_APPROVE_ID, ezi18n( 'kernel/workflow/event', "Approve" ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'before' ) ) ) );
    }

    function &attributeDecoder( &$event, $attr )
    {
        switch ( $attr )
        {
            case 'selected_sections':
            {
                $returnValue = explode( ',', $event->attribute( 'data_text1' ) );
                return $returnValue;
            } break;

            case 'selected_users':
            {
                if ( $event->attribute( 'data_int1' ) == '0' )
                {
                    $returnValue = array();
                    return $returnValue;
                }
                $returnValue = array( $event->attribute( 'data_int1' ) );
                return $returnValue;
            } break;

            case 'selected_usergroups':
            {
                if ( $event->attribute( 'data_text2' ) == '' )
                {
                    $returnValue = array();
                    return $returnValue;
                }
                $returnValue = explode( ',', $event->attribute( 'data_text2' ) );
                return $returnValue;
            } break;
        }
        $retValue = null;
        return $retValue;
    }

    function typeFunctionalAttributes( )
    {
        return array( 'selected_sections',
                      'selected_users',
                      'selected_usergroups' );
    }

    function attributes()
    {
        return array_merge( array( 'sections',
                                   'users',
                                   'usergroups' ),
                            eZWorkflowEventType::attributes() );

    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'sections':
            {
                include_once( 'kernel/classes/ezsection.php' );
                $sections = eZSection::fetchList( false );
                foreach ( array_keys( $sections ) as $key )
                {
                    $section =& $sections[$key];
                    $section['Name'] = $section['name'];
                    $section['value'] = $section['id'];
                }
                return $sections;
            }break;
        }
        $eventValue =& eZWorkflowEventType::attribute( $attr );
        return $eventValue;
    }

    function execute( &$process, &$event )
    {
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $process, 'eZApproveType::execute' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'eZApproveType::execute' );
        $parameters = $process->attribute( 'parameter_list' );
        $versionID =& $parameters['version'];
        $object =& eZContentObject::fetch( $parameters['object_id'] );

        /*
          If we run event first time ( when we click publish in admin ) we do not have user_id set in workflow process,
          so we take current user and store it in workflow process, so next time when we run event from cronjob we fetch
          user_id from there.
         */
        if ( $process->attribute( 'user_id' ) == 0 )
        {
            $user =& eZUser::currentUser();
            $process->setAttribute( 'user_id', $user->id() );
        }
        else
        {
            $user =& eZUser::instance( $process->attribute( 'user_id' ) );
        }

        $userGroups = array_merge( $user->attribute( 'groups' ), $user->attribute( 'contentobject_id' ) );
        $workflowSections = explode( ',', $event->attribute( 'data_text1' ) );
        $workflowGroups = explode( ',', $event->attribute( 'data_text2' ) );
        $editor = $event->attribute( 'data_int1' );

        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $user, 'eZApproveType::execute::user' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $userGroups, 'eZApproveType::execute::userGroups' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $editor, 'eZApproveType::execute::editor' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowSections, 'eZApproveType::execute::workflowSections' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowGroups, 'eZApproveType::execute::workflowGroups' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $object->attribute( 'section_id'), 'eZApproveType::execute::section_id' );

        $section = $object->attribute( 'section_id');
        $correctSection = false;

        if ( !in_array( $section, $workflowSections ) && !in_array( -1, $workflowSections ) )
        {
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            if ( $assignedNodes )
            {
                foreach( $assignedNodes as $assignedNode )
                {
                    $parent =& $assignedNode->attribute( 'parent' );
                    $parentObject =& $parent->object();
                    $section = $parentObject->attribute( 'section_id');

                    if ( in_array( $section, $workflowSections ) )
                    {
                        $correctSection = true;
                        break;
                    }
                }
            }
        }
        else
            $correctSection = true;

        $inExcludeGroups = count( array_intersect( $userGroups, $workflowGroups ) ) != 0;

        $userIsEditor = $user->id() == $editor;

        if ( !$userIsEditor and
             !$inExcludeGroups and
             $correctSection )
        {

            $collaborationID = false;
            $db = & eZDb::instance();
            $taskResult = $db->arrayQuery( 'select workflow_process_id, collaboration_id from ezapprove_items where workflow_process_id = ' . $process->attribute( 'id' )  );
            if ( count( $taskResult ) > 0 )
                $collaborationID = $taskResult[0]['collaboration_id'];
//             if( $process->attribute( 'event_state') == EZ_APPROVE_COLLABORATION_NOT_CREATED )
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $collaborationID, 'approve collaborationID' );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $process->attribute( 'event_state'), 'approve $process->attribute( \'event_state\')' );
            if ( $collaborationID === false )
            {
                $this->createApproveCollaboration( $process, $event, $user->id(), $object->attribute( 'id' ), $versionID, $editor );
                $this->setInformation( "We are going to create approval" );
                $process->setAttribute( 'event_state', EZ_APPROVE_COLLABORATION_CREATED );
                $process->store();
                eZDebugSetting::writeDebug( 'kernel-workflow-approve', $this, 'approve execute' );
                return EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT;
            }
            else if ( $process->attribute( 'event_state') == EZ_APPROVE_COLLABORATION_NOT_CREATED )
            {
                eZApproveCollaborationHandler::activateApproval( $collaborationID );
                $process->setAttribute( 'event_state', EZ_APPROVE_COLLABORATION_CREATED );
                $process->store();
                eZDebugSetting::writeDebug( 'kernel-workflow-approve', $this, 'approve re-execute' );
                return EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT;
            }
//             else if ( $process->attribute( 'event_state') == EZ_APPROVE_COLLABORATION_CREATED )
            else
            {
                $this->setInformation( "we are checking approval now" );
                eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'check approval' );
                return $this->checkApproveCollaboration(  $process, $event );
            }
        }
        else
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowSections , "we are not going to create approval " . $object->attribute( 'section_id') );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $userGroups, "we are not going to create approval" );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowGroups,  "we are not going to create approval" );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $user->id(), "we are not going to create approval $editor "  );
            return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
        }
    }

    function initializeEvent( &$event )
    {
    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
        $sectionsVar = $base . "_event_ezapprove_section_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $sectionsVar ) )
        {
            $sectionsArray = $http->postVariable( $sectionsVar );
            if ( in_array( '-1', $sectionsArray ) )
            {
                $sectionsArray = array( -1 );
            }
            $sectionsString = implode( ',', $sectionsArray );
            $event->setAttribute( "data_text1", $sectionsString );
        }

        if ( $http->hasSessionVariable( 'BrowseParameters' ) )
        {
            $browseParameters = $http->sessionVariable( 'BrowseParameters' );
            if ( isset( $browseParameters['custom_action_data'] ) )
            {
                $customData = $browseParameters['custom_action_data'];
                if ( isset( $customData['event_id'] ) &&
                     $customData['event_id'] == $event->attribute( 'id' ) )
                {
                    switch( $customData['browse_action'] )
                    {
                        case 'AddUser':
                        {
                            if ( $http->hasPostVariable( 'SelectedObjectIDArray' ) )
                            {
                                $userID = '';
                                if ( is_array( $http->postVariable( 'SelectedObjectIDArray' ) ) &&
                                     count( $http->postVariable( 'SelectedObjectIDArray' ) ) > 0 )
                                {
                                    $userIDArray = $http->postVariable( 'SelectedObjectIDArray' );
                                    $userID = $userIDArray[0];
                                    if ( !eZUser::isUserObject( eZContentObject::fetch( $userID ) ) )
                                    {
                                        $userID = '';
                                    }
                                }
                                $event->setAttribute( 'data_int1', $userID );
                            }
                        } break;

                        case 'AddExcludeUser':
                        {
                            if ( $http->hasPostVariable( 'SelectedObjectIDArray' ) )
                            {
                                if ( is_array( $http->postVariable( 'SelectedObjectIDArray' ) ) &&
                                     count( $http->postVariable( 'SelectedObjectIDArray' ) ) > 0 )
                                {
                                    $userIDArray = $http->postVariable( 'SelectedObjectIDArray' );
                                    $event->setAttribute( 'data_text2', implode( ',',
                                                                                array_unique( array_merge( $this->attributeDecoder( $event, 'selected_usergroups' ),
                                                                                                           $userIDArray ) ) ) );
                                }
                            }
                        } break;

                    }

                    $http->removeSessionVariable( 'BrowseParameters' );
                }
            }
        }
    }

    function createApproveCollaboration( &$process, &$event, $userID, $contentobjectID, $contentobjectVersion, $editor )
    {
        if ( $editor === null )
            return false;
        $authorID = $userID;
        $collaborationItem = eZApproveCollaborationHandler::createApproval( $contentobjectID, $contentobjectVersion,
                                                                            $authorID, $editor );

        $db = & eZDb::instance();
        $db->query( 'INSERT INTO ezapprove_items( workflow_process_id, collaboration_id )
                       VALUES(' . $process->attribute( 'id' ) . ',' . $collaborationItem->attribute( 'id' ) . ' ) ' );
    }

    /*
     \reimp
    */
    function customWorkflowEventHTTPAction( &$http, $action, &$workflowEvent )
    {
        $eventID = $workflowEvent->attribute( "id" );
        $module =& $GLOBALS['eZRequestedModule'];

        switch ( $action )
        {
            case "AddUser" :
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                eZContentBrowse::browse( array( 'action_name' => 'SelectSingleUser',
                                                'from_page' => '/workflow/edit/' . $workflowEvent->attribute( 'workflow_id' ),
                                                'custom_action_data' => array( 'event_id' => $eventID,
                                                                               'browse_action' => $action ),
                                                'class_array' => array ( 'user' ) ),
                                         $module );
            } break;

            case "RemoveUser" :
            {
                if ( $http->hasPostVariable( 'DeleteUserIDArray_' . $eventID ) )
                {
                    if ( count( array_intersect( $this->attributeDecoder( $workflowEvent, 'selected_users' ), $http->postVariable( 'DeleteUserIDArray_' . $eventID ) ) ) )
                    {
                        $workflowEvent->setAttribute( 'data_int1', '' );
                    }
                }
            } break;

            case "AddExcludeUser" :
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                eZContentBrowse::browse( array( 'action_name' => 'SelectMultipleUsers',
                                                'from_page' => '/workflow/edit/' . $workflowEvent->attribute( 'workflow_id' ),
                                                'custom_action_data' => array( 'event_id' => $eventID,
                                                                               'browse_action' => $action ) ),
                                         $module );
            } break;

            case "RemoveExcludeUser" :
            {
                if ( $http->hasPostVariable( 'DeleteExcludeUserIDArray_' . $eventID ) )
                {
                    $workflowEvent->setAttribute( 'data_text2', implode( ',', array_diff( $this->attributeDecoder( $workflowEvent, 'selected_usergroups' ),
                                                                                          $http->postVariable( 'DeleteExcludeUserIDArray_' . $eventID ) ) ) );
                }
            } break;

        }
    }

    function checkApproveCollaboration( &$process, &$event )
    {
        $db = & eZDb::instance();
        $taskResult = $db->arrayQuery( 'select workflow_process_id, collaboration_id from ezapprove_items where workflow_process_id = ' . $process->attribute( 'id' )  );
        $collaborationID = $taskResult[0]['collaboration_id'];
        $collaborationItem = eZCollaborationItem::fetch( $collaborationID );
        $contentObjectVersion = eZApproveCollaborationHandler::contentObjectVersion( $collaborationItem );
        $approvalStatus = eZApproveCollaborationHandler::checkApproval( $collaborationID );
        if ( $approvalStatus == EZ_COLLABORATION_APPROVE_STATUS_WAITING )
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'approval still waiting' );
            return EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT;
        }
        else if ( $approvalStatus == EZ_COLLABORATION_APPROVE_STATUS_ACCEPTED )
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'approval was accepted' );
            $status = EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
        }
        else if ( $approvalStatus == EZ_COLLABORATION_APPROVE_STATUS_DENIED or
                  $approvalStatus == EZ_COLLABORATION_APPROVE_STATUS_DEFERRED )
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'approval was denied' );
//             $contentObjectVersion->setAttribute( 'status', EZ_VERSION_STATUS_REJECTED );
            $contentObjectVersion->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
            $status = EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED;
        }
//         else if ( $approvalStatus == EZ_COLLABORATION_APPROVE_STATUS_DEFERRED )
//         {
//             eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'approval was deferred' );
//             $contentObjectVersion->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
//             $status = EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_RESET;
//         }
        else
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, "approval unknown status '$approvalStatus'" );
            $contentObjectVersion->setAttribute( 'status', EZ_VERSION_STATUS_REJECTED );
            $status = EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED;
        }
        $contentObjectVersion->sync();
        if ( $approvalStatus != EZ_COLLABORATION_APPROVE_STATUS_DEFERRED )
            $db->query( 'DELETE FROM ezapprove_items WHERE workflow_process_id = ' . $process->attribute( 'id' )  );
        return $status;
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_APPROVE_ID, "ezapprovetype" );

?>
