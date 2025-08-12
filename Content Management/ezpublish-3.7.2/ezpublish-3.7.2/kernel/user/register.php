<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "lib/ezutils/classes/ezmail.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/classes/ezcontentclass.php" );

$http =& eZHTTPTool::instance();
$Module =& $Params["Module"];

$Params['TemplateName'] = "design:user/register.tpl";
$EditVersion = 1;

include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$Params['TemplateObject'] =& $tpl;

// $http->removeSessionVariable( "RegisterUserID" );

// Create new user object if user is not logged in
if ( !$http->hasSessionVariable( "RegisterUserID" ) and !$http->hasPostVariable( "UserID" ) )
{
    $ini =& eZINI::instance();
    $errMsg = '';
    $checkErrNodeId = false;

    $defaultUserPlacement = $ini->variable( "UserSettings", "DefaultUserPlacement" );

    $db =& eZDB::instance();
    $sql = "SELECT count(*) as count FROM ezcontentobject_tree WHERE node_id = $defaultUserPlacement";
    $rows = $db->arrayQuery( $sql );
    $count = $rows[0]['count'];
    if ( $count < 1 )
    {
        $errMsg = ezi18n( 'design/standard/user', 'The node (%1) specified in [UserSettings].DefaultUserPlacement setting in site.ini does not exist!', null, array( $defaultUserPlacement ) );
        $checkErrNodeId = true;
        eZDebug::writeError( "$errMsg" );
        $tpl->setVariable( 'errMsg', $errMsg );
        $tpl->setVariable( 'checkErrNodeId', $checkErrNodeId );
    }
    $userClassID = $ini->variable( "UserSettings", "UserClassID" );
    $class = eZContentClass::fetch( $userClassID );

    $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
    $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );
    // Create object by user 14 in section 1
    $contentObject =& $class->instantiate( $userCreatorID, $defaultSectionID );
    $objectID = $contentObject->attribute( 'id' );

    // Store the ID in session variable
    $http->setSessionVariable( "RegisterUserID", $objectID );

    $userID = $objectID;

    $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                                                       'contentobject_version' => 1,
                                                       'parent_node' => $defaultUserPlacement,
                                                       'is_main' => 1 ) );
    $nodeAssignment->store();
}
else if ( $http->hasSessionVariable( "RegisterUserID" ) )
{
    $userID = $http->sessionVariable( "RegisterUserID" );
}
else if ( $http->hasPostVariable( "UserID" ) )
{
    $userID = $http->postVariable( "UserID" );
}

$Params['ObjectID'] = $userID;

$Module->addHook( 'post_publish', 'registerSearchObject', 1, false );

if ( !function_exists( 'checkContentActions' ) )
{
    function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion, $EditLanguage )
    {
        if ( $module->isCurrentAction( 'Cancel' ) )
        {
            include_once( 'kernel/classes/ezredirectmanager.php' );
            eZRedirectManager::redirectTo( $module, '/' );

            $objectID = $object->attribute( 'id' );
            $versionCount= $object->getVersionCount();
            $db =& eZDB::instance();
            $db->begin();
            $db->query( "DELETE FROM ezcontentobject_link
		                 WHERE from_contentobject_id=$objectID AND from_contentobject_version=$EditVersion" );
            $db->query( "DELETE FROM eznode_assignment
		                 WHERE contentobject_id=$objectID AND contentobject_version=$EditVersion" );
            $version->remove();
            foreach ( $contentObjectAttributes as $contentObjectAttribute )
            {
                $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
                $version = $contentObjectAttribute->attribute( 'version' );
                if ( $version == $EditVersion )
                {
                    $contentObjectAttribute->remove( $objectAttributeID, $version );
                }
            }
            if ( $versionCount == 1 )
            {
                $object->purge();
            }
            $db->commit();
            $http =& eZHTTPTool::instance();
            $http->removeSessionVariable( "RegisterUserID" );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            $http =& eZHTTPTool::instance();

            $user =& eZUser::currentUser();
            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                         'version' => $version->attribute( 'version') ) );

            $object = eZContentObject::fetch( $object->attribute( 'id' ) );

            // Check if user should be enabled and logged in
            unset($user);
            $user = eZUser::fetch( $object->attribute( 'id' ) );
            $user->loginCurrent();

            $receiver = $user->attribute( 'email' );
            $mail = new eZMail();
            if ( !$mail->validate( $receiver ) )
            {
            }
            include_once( "kernel/common/template.php" );
            include_once( 'lib/ezutils/classes/ezmail.php' );
            include_once( 'lib/ezutils/classes/ezmailtransport.php' );
            $ini =& eZINI::instance();
            $tpl =& templateInit();
            $tpl->setVariable( 'user', $user );
            $tpl->setVariable( 'object', $object );
            $hostname = eZSys::hostname();
            $tpl->setVariable( 'hostname', $hostname );
            $password = $http->sessionVariable( "GeneratedPassword" );

            $tpl->setVariable( 'password', $password );

            // Check whether account activation is required.
            $verifyUserEmail = $ini->variable( 'UserSettings', 'VerifyUserEmail' );

            if ( $verifyUserEmail == "enabled" ) // and if it is
            {
                // Disable user account and send verification mail to the user
                $userSetting = eZUserSetting::fetch( $user->attribute( 'contentobject_id' ) );
                $userSetting->setAttribute( 'is_enabled', 0 );
                $userSetting->store();
		    
                // Log out current user
                eZUser::logoutCurrent();

                // Create enable account hash and send it to the newly registered user
                $hash = md5( mktime( ) . $user->attribute( 'contentobject_id' ) );
                include_once( "kernel/classes/datatypes/ezuser/ezuseraccountkey.php" );
                $accountKey = eZUserAccountKey::createNew( $user->attribute( 'contentobject_id' ), $hash, mktime() );
                $accountKey->store();

                $tpl->setVariable( 'hash', $hash );
            }

            $templateResult =& $tpl->fetch( 'design:user/registrationinfo.tpl' );
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            if ( !$emailSender )
                $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );
            $mail->setSender( $emailSender );
            $mail->setReceiver( $receiver );
            $subject = ezi18n( 'kernel/user/register', 'Registration info' );
            if ( $tpl->hasVariable( 'subject' ) )
                $subject = $tpl->variable( 'subject' );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );

            $feedbackTypes = $ini->variableArray( 'UserSettings', 'RegistrationFeedback' );
            foreach ( $feedbackTypes as $feedbackType )
            {
                switch ( $feedbackType )
                {
                    case 'email':
                    {
                        $mail = new eZMail();
                        $tpl->resetVariables();
                        $tpl->setVariable( 'user', $user );
                        $tpl->setVariable( 'object', $object );
                        $tpl->setVariable( 'hostname', $hostname );
                        $templateResult =& $tpl->fetch( 'design:user/registrationfeedback.tpl' );

                        $feedbackReceiver = $ini->variable( 'UserSettings', 'RegistrationEmail' );
                        if ( !$feedbackReceiver )
                            $feedbackReceiver = $ini->variable( "MailSettings", "AdminEmail" );

                        $subject = ezi18n( 'kernel/user/register', 'New user registered' );
                        if ( $tpl->hasVariable( 'subject' ) )
                            $subject =& $tpl->variable( 'subject' );
                        if ( $tpl->hasVariable( 'email_receiver' ) )
                            $feedbackReceiver =& $tpl->variable( 'email_receiver' );

                        $mail->setReceiver( $feedbackReceiver );
                        $mail->setSubject( $subject );
                        $mail->setBody( $templateResult );
                        $mailResult = eZMailTransport::send( $mail );
                    } break;
                    default:
                    {
                        eZDebug::writeWarning( "Unknown feedback type '$feedbackType'", 'user/register' );
                    }
                }
            }



            $http->removeSessionVariable( "GeneratedPassword" );
            $http->removeSessionVariable( "RegisterUserID" );

            // check for redirectionvariable
            if ( eZHTTPTool::hasSessionVariable( 'RedirectAfterUserRegister' ) )
            {
                $module->redirectTo( eZHTTPTool::sessionVariable( 'RedirectAfterUserRegister' ) );
                eZHTTPTool::removeSessionVariable( 'RedirectAfterUserRegister' );
            }
            else if ( $http->hasPostVariable( 'RedirectAfterUserRegister' ) )
            {
                $module->redirectTo( $http->postVariable( 'RedirectAfterUserRegister' ) );
            }
            else
            {
                $module->redirectTo( '/user/success/' );
            }
        }
    }
}
$Module->addHook( 'action_check', 'checkContentActions' );

$OmitSectionSetting = true;

$includeResult = include( 'kernel/content/attribute_edit.php' );
if ( $includeResult != 1 )
{
    return $includeResult;
}
$ini =& eZINI::instance();
//eZDebug::writeDebug( $includeResult );

if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
    $Result['pagelayout'] = 'loginpagelayout.tpl';
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/user', 'User' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/user', 'Register' ) ) );


?>
