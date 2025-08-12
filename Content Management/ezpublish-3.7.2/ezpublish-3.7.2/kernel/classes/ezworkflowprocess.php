<?php
//
// Definition of eZWorkflowProcess class
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
  \class eZWorkflowProcess ezworkflowprocess.php
  \brief

*/

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezworkflowevent.php' );

class eZWorkflowProcess extends eZPersistentObject
{
    function eZWorkflowProcess( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'process_key' => array( 'name' => 'ProcessKey',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'workflow_id' => array( 'name' => 'WorflowID',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'content_id' => array( 'name' => 'ContentID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'content_version' => array( 'name' => 'ContentVersion',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         'session_key' => array( 'name' => 'SessionKey',
                                                                 'datatype' => 'string',
                                                                 'default' => '0',
                                                                 'required' => true ),
                                         'node_id' => array( 'name' => 'NodeID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'event_id' => array( 'name' => 'EventID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'event_position' => array( 'name' => 'EventPosition',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         'event_state' => array( 'name' => 'EventState',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'last_event_id' => array( 'name' => 'LastEventID',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         'last_event_position' => array( 'name' => 'LastEventPosition',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ),
                                         'last_event_status' => array( 'name' => 'LastEventStatus',
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true ),
                                         'event_status' => array( 'name' => 'EventStatus',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'activation_date' => array( 'name' => 'ActivationDate',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'parameters' => array( 'name' => 'Parameters',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'memento_key' => array( 'name' => 'MementoKey',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'user' => 'user',
                                                      'content' => 'content',
                                                      'node' => 'node',
                                                      'workflow' => 'workflow',
                                                      'workflow_event' => 'workflowEvent',
                                                      'last_workflow_event' => 'lastWorkflowEvent',
                                                      'parameter_list' => 'parameterList' ),
                      "increment_key" => "id",
                      'class_name' => 'eZWorkflowProcess',
                      'sort' => array( 'user_id' => 'asc' ),
                      'name' => 'ezworkflow_process' );
    }

    function &create( $processKey, $parameters )
//                      $workflowID, $userID,
//                      $contentID, $contentVersion, $nodeID, $sessionKey = '' )
    {
        $dateTime = time();
        if ( !isset( $parameters['user_id'] ) )
            $parameters['user_id'] = 0;
        $row = array( 'process_key' => $processKey,
                      'workflow_id' => $parameters['workflow_id'],
                      'user_id' => $parameters['user_id'],
                      'content_id' => 0,
                      'content_version' => 0,
                      'node_id' => 0,
                      'session_key' => 0,
                      'event_id' => 0,
                      'event_position' => 0,
                      'last_event_id' => 0,
                      'last_event_position' => 0,
                      'last_event_status' => 0,
                      'event_status' => 0,
                      'created' => $dateTime,
                      'modified' => $dateTime,
                      'parameters' => serialize( $parameters ) );
        $newWorkflowProccess = new eZWorkflowProcess( $row );
        return $newWorkflowProccess;
    }

    function reset()
    {
        $this->EventID = 0;
        $this->EventPosition = 0;
        $this->LastEventID = 0;
        $this->LastEventPosition = 0;
        $this->LastEventStatus = 0;
        $this->ActivationDate = 0;
        $this->EventStatus = 0;
        $this->EventState = 0;
    }

    function advance( $next_event_id = 0, $next_event_pos = 0, $status = 0 )
    {
        $this->LastEventID = $this->EventID;
        $this->LastEventPosition = $this->EventPosition;
        $this->LastEventStatus = $status;
        $this->EventID =  $next_event_id;
        $this->EventPosition = $next_event_pos;
        $this->ActivationDate = 0;
    }


    function run( &$workflow, &$workflowEvent, &$eventLog )
    {
        $eventLog = array();
        eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent, "workflowEvent in process->run beginning" );

        $runCurrentEvent = true;
        $done = false;
        $workflowStatus = $this->attribute( 'status' );
        eZDebugSetting::writeDebug( 'workflow-process', $workflowStatus , 'workflowStatus' );
        $lastEventStatus = $this->attribute( 'last_event_status' );

// just temporary. needs to be removed from parameters
        if ( $workflowEvent == null )
        {
            $workflowEvent = eZWorkflowEvent::fetch( $this->attribute( 'event_id' ) );
        }

        switch( $lastEventStatus )
        {
            case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON:
            case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT:
            case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE:
            case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT:
            case EZ_WORKFLOW_TYPE_STATUS_REDIRECT:
            case EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT:
            case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_RESET:
            {
                if ( $workflowEvent !== null )
                {
                    $activationDate = 0;
                    if ( $this->hasAttribute( 'activation_date' ) )
                    {
                        $activationDate = $this->attribute( "activation_date" );
                    }
                    eZDebugSetting::writeDebug( 'workflow-process', "Checking activation date" );
                    if ( $activationDate == 0  )
                    {
                        $eventType =& $workflowEvent->eventType();
                        $eventLog[] = array( "status" => $lastEventStatus,
                                             "status_text" => eZWorkflowType::statusName( $lastEventStatus ),
                                             "information" => $eventType->attribute( "information" ),
                                             "description" => $workflowEvent->attribute( "description" ),
                                             "type_name" => $eventType->attribute( "name" ),
                                             "type_group" => $eventType->attribute( "group_name" ) );
                        if ( $lastEventStatus == EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON ||
                             $lastEventStatus == EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE   ||
                             $lastEventStatus == EZ_WORKFLOW_TYPE_STATUS_REDIRECT )
                        {
                            $runCurrentEvent = false;
                        }
                    }
                    else if ( time() < $activationDate )
                    {
                        eZDebugSetting::writeDebug( 'workflow-process', "Date failed, not running events" );
                        $eventType =& $workflowEvent->eventType();
                        $eventLog[] = array( "status" => $lastEventStatus,
                                             "status_text" => eZWorkflowType::statusName( $lastEventStatus ),
                                             "information" => $eventType->attribute( "information" ),
                                             "description" => $workflowEvent->attribute( "description" ),
                                             "type_name" => $eventType->attribute( "name" ),
                                             "type_group" => $eventType->attribute( "group_name" ) );
                        $done = true;
                    }
                    else
                    {
                        eZDebugSetting::writeDebug( 'workflow-process', "Date ok, running events" );
                        eZDebugSetting::writeDebug( 'workflow-process', $lastEventStatus, 'WORKFLOW_TYPE_STATUS' );
                        if ( $lastEventStatus == EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON ||
                             $lastEventStatus == EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE   ||
                             $lastEventStatus == EZ_WORKFLOW_TYPE_STATUS_REDIRECT )
                        {
                            $runCurrentEvent = false;
                        }
                    }
                }
            } break;
            default:
                break;
        }

//         eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent, "$done , $runCurrentEvent , fooooooooooooooo" );
//         eZDebugSetting::writeDebug( 'workflow-process', $done , "$done , $runCurrentEvent , fooooooooooooooo" );
//         eZDebugSetting::writeDebug( 'workflow-process', $runCurrentEvent, "$done , $runCurrentEvent , fooooooooooooooo" );
        while ( !$done )
        {
//            var_dump( $done );
//            var_dump( $runCurrentEvent );
            if ( $runCurrentEvent )
            {
                eZDebugSetting::writeDebug( 'workflow-process', "runCurrentEvent is true" );
            }
            else
            {
                eZDebugSetting::writeDebug( 'workflow-process', "runCurrentEvent is false" );
            }
            if ( $workflowEvent != null )
            {
//                 eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent ,"workflowEvent  is not null" );
            }
            else
            {
//                 eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent ,"workflowEvent  is  null" );
            }
            if ( get_class( $workflowEvent ) == "ezworkflowevent")
            {
                eZDebugSetting::writeDebug( 'workflow-process', get_class( $workflowEvent )  ,"workflowEvent class  is ezworkflowevent " );
            }
            else
            {
                eZDebugSetting::writeDebug( 'workflow-process', get_class( $workflowEvent )  ,"workflowEvent class  is not ezworkflowevent " );
            }
            eZDebugSetting::writeDebug( 'workflow-process', $done , "in while" );
            if ( $runCurrentEvent and
                 $workflowEvent !== null and
                 get_class( $workflowEvent ) == "ezworkflowevent" )
            {

//                 eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent , "workflowEvent line 173 " );
                $eventType =& $workflowEvent->eventType();
//                 eZDebugSetting::writeDebug( 'workflow-process', $eventType, "eventType line 176 " );
                if ( is_subclass_of( $eventType, "ezworkflowtype" ) )
                {
                    $lastEventStatus = $eventType->execute( $this, $workflowEvent );
// code idea :
                    $workflowParameters =& $this->attribute( 'parameter_list' );

                    if ( isset( $workflowParameters['cleanup_list'] ) )
                    {
                        $cleanupList =& $workflowParameters['cleanup_list'];
                    }
                    else
                    {
                        unset( $cleanupList );
                        $cleanupList = array();
                    }

                    if ( $eventType->needCleanup() )
                    {
                        $cleanupList[] = $workflowEvent->attribute( 'id' );
                        $workflowParameters['cleanup_list'] = $cleanupList;
                        $this->setAttribute( 'parameters', serialize( $workflowParameters ) );
                    }
//                    print( "<br>lastEventStatus" . $lastEventStatus );
                    eZDebugSetting::writeDebug( 'workflow-process', $lastEventStatus, "lastEventStatus" );
                    switch( $lastEventStatus )
                    {
                        case EZ_WORKFLOW_TYPE_STATUS_ACCEPTED:
                        {
                            $done = false;
                            $workflowStatus = EZ_WORKFLOW_STATUS_DONE;
                        }break;
                        case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_DONE:
                        {
                            $done = true;
                            $workflowStatus = EZ_WORKFLOW_STATUS_DONE;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_REJECTED:
                        {
                            $done = true;
                            $workflowStatus = EZ_WORKFLOW_STATUS_FAILED;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON:
                        case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT:
                        {
                            if ( $eventType->hasAttribute( "activation_date" ) )
                            {
                                $date = $eventType->attribute( "activation_date" );
                                $this->setAttribute( "activation_date", $date );

                            }
                            $workflowStatus = EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON;
                            $done = true;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE:
                        case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT:
                        {
                            $workflowStatus = EZ_WORKFLOW_STATUS_FETCH_TEMPLATE;
                            $done = true;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_REDIRECT:
                        case EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT:
                        {
                            $workflowStatus = EZ_WORKFLOW_STATUS_REDIRECT;
                            $done = true;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_RUN_SUB_EVENT:
                        {
                            eZDebug::writeWarning( "Run sub event not supported yet", "eZWorkflowProcess::run" );
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED:
                        {
                            $done = true;
                            $this->advance();
                            $workflowStatus = EZ_WORKFLOW_STATUS_CANCELLED;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_RESET:
                        {
                            $done = true;
                            $this->reset();
                            $workflowStatus = EZ_WORKFLOW_STATUS_RESET;
                        } break;
                        default:
                        {
                            eZDebug::writeWarning( "Unknown status '$lastEventStatus'", "eZWorkflowProcess::run" );
                        } break;
                    }
                    $eventLog[] = array( "status" => $lastEventStatus,
                                         "status_text" => eZWorkflowType::statusName( $lastEventStatus ),
                                         "information" => $eventType->attribute( "information" ),
                                         "description" => $workflowEvent->attribute( "description" ),
                                         "type_name" => $eventType->attribute( "name" ),
                                         "type_group" => $eventType->attribute( "group_name" ) );
                }
                else
                    eZDebug::writeError( "Expected an eZWorkFlowType object", "eZWorkflowProcess::run" );
            }
            else
            {
                eZDebugSetting::writeDebug( 'workflow-process', "Not running current event. Trying next" );
            }
            $runCurrentEvent = true;
            // still not done
            if ( !$done )
            {
                // fetch next event
                $event_pos = $this->attribute( "event_position" );
//                 eZDebugSetting::writeNotice( 'workflow-process', $this , 'workflow_process' );
                $next_event_pos = $event_pos + 1;
                if ( !is_object( $workflow ) )
                {
//                    eZDebug::printReport();
                }
                $next_event_id = $workflow->fetchEventIndexed( $next_event_pos );
                if ( $next_event_id !== null )
                {
                    eZDebugSetting::writeDebug( 'workflow-process', $event_pos , "workflow  not done");
                    $this->advance( $next_event_id, $next_event_pos, $lastEventStatus );
                    $workflowEvent = eZWorkflowEvent::fetch( $next_event_id );
                }
                else
                {
                    $done = true;
                    unset( $workflowEvent );
                    eZDebugSetting::writeDebug( 'workflow-process', $event_pos , "workflow done");
//                     eZDebugSetting::writeNotice( 'workflow-process', $this, "workflow done");
                    $workflowStatus = EZ_WORKFLOW_STATUS_DONE;
                    $this->advance();
                }
            }

        }

        $this->setAttribute( "last_event_status", $lastEventStatus );
        $this->setAttribute( "status", $workflowStatus );
        $this->setAttribute( "modified", time() );
        return $workflowStatus;
    }


    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function store()
    {
        eZPersistentObject::store();
    }

    function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZWorkflowProcess::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    function fetchList( $conds = null, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    function createKey( $parameters, $keys = null )
    {

        $string = '';
        if ( $keys != null )
        {
            foreach ( $keys as $key )
            {
                $value = $parameters[$key];
                $string .= $key . $value;
            }
        }else
        {
            foreach ( array_keys( $parameters ) as $key )
            {
                $value =& $parameters[$key];
                $string .= $key . $value;
            }
        }
        return md5( $string );
    }



    function fetchListByKey( $searchKey, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null,
                                                    array( 'process_key' => $searchKey ), null, null,
                                                    $asObject );
    }

    function fetchUserList( $userID, $asObject = true )
    {
        $conds = array( 'user_id' => $userID );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    function fetchForContent( $workflowID, $userID,
                               $contentID, $content_version, $nodeID,
                               $asObject = true )
    {
        $conds = array( 'workflow_id' => $workflowID,
                        'user_id' => $userID,
                        'content_id' => $contentID,
                        'content_version' => $contentVersion,
                        'node_id' => $nodeID );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }
    function fetchForStatus( $status = EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON,  $asObject = true )
    {
        $conds = array( 'status' => $status );

        $db = eZDB::instance();
        if ( $db->databaseName() == 'oracle' )
            $conds['LENGTH(memento_key)'] = array( '!=', 0 );
        else
            $conds['memento_key'] = array( '!=', '' );

        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    function fetchForSession( $sessionKey, $workflowID, $asObject = true )
    {
        $conds = array( 'workflow_id' => $workflowID,
                        'session_key' => $sessionKey );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

//     function &fetchListCount( $version = 0 )
//     {
//         $custom = array( array( 'name' => 'count',
//                                 'operation' => 'count( id )' ) );
//         $lst = eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
//                                                      array(), array( 'version' => $version ), null, null,
//                                                      false, null,
//                                                      $custom );
//         return $lst[0]['count'];
//     }

    function currentEvent()
    {
    }

    function advanceToNext()
    {
    }

    function &setParameters( $parameterList = null )
    {
        if ( !is_null( $parameterList ) )
        {
            $this->Parameters =& $parameterList;
            unset( $this->ParameterList );
        }
        $this->setAttribute( 'parameters', serialize( $this->Parameters ) );
        $parameterList = $this->attribute( 'parameter_list' );
        return $parameterList;
    }

    function &user()
    {
        if ( isset( $this->UserID ) and $this->UserID )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $user =& eZUser::instance( $this->UserID );
        }
        else
            $user = null;
        return $user;
    }

    function &content()
    {
        if ( isset( $this->ContentID ) and $this->ContentID )
        {
            include_once( 'kernel/classes/ezcontentobject.php' );
            $content =& eZContentObject::fetch( $this->ContentID );
        }
        else
            $content = null;
        return $content;
    }

    function &node()
    {
        if ( isset( $this->NodeID ) and $this->NodeID )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $node = eZContentObjectTreeNode::fetch( $this->NodeID );
        }
        else
            $node = null;
        return $node;
    }

    function &workflow()
    {
        if ( isset( $this->WorkflowID ) and $this->WorkflowID )
        {
            include_once( 'kernel/classes/ezworkflow.php' );
            $workflow = eZWorkflow::fetch( $this->WorkflowID );
        }
        else
            $workflow = null;
        return $workflow;
    }

    function &workflowEvent()
    {
        if ( isset( $this->EventID ) and $this->EventID )
        {
            include_once( 'kernel/classes/ezworkflowevent.php' );
            $event = eZWorkflowEvent::fetch( $this->EventID );
        }
        else
            $event = null;
        return $event;
    }

    function &lastWorkflowEvent()
    {
        if ( isset( $this->LastEventID ) and $this->LastEventID )
        {
            include_once( 'kernel/classes/ezworkflowevent.php' );
            $lastEvent = eZWorkflowEvent::fetch( $this->LastEventID );
        }
        else
            $lastEvent = null;
        return $lastEvent;
    }

    function &parameterList()
    {
        if ( !isset( $this->ParameterList ) )
        {
            $this->ParameterList = unserialize( $this->attribute( 'parameters' ) );
        }
        return $this->ParameterList;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove()
    {
        $workflowParameters = $this->attribute( 'parameter_list' );
        $cleanupList = array();

        $db =& eZDB::instance();
        $db->begin();
        if ( isset( $workflowParameters['cleanup_list'] ) && is_array( $workflowParameters['cleanup_list'] ) )
        {
            $cleanupList = $workflowParameters['cleanup_list'];
            foreach ( array_keys( $cleanupList ) as $key )
            {
                $workflowEventID = $cleanupList[$key];
                $workflowEvent = eZWorkflowEvent::fetch( $workflowEventID );
                $workflowType =& $workflowEvent->eventType();
                $workflowType->cleanup( $this, $workflowEvent );
            }
        }
        eZPersistentObject::removeObject( eZWorkflowProcess::definition(), array( 'id' => $this->attribute( 'id' ) ) );
        $db->commit();
    }

    /*!
     \static
     Removes all workflow processes from database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezworkflow_process" );
    }

    /// \privatesection
    var $ID;
    var $WorkflowID;
    var $UserID;
    var $ContentID;
    var $NodeID;
    var $EventID;
    var $EventPosition;
    var $LastEventID;
    var $LastEventPosition;
    var $LastEventStatus;
    var $EventStatus;
    var $Created;
    var $Modified;
    var $ActivationDate;
}

?>
