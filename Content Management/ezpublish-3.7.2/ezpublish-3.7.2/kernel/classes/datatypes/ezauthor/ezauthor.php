<?php
//
// Definition of eZAuthor class
//
// Created on: <19-Aug-2002 10:52:01 bf>
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
  \class eZAuthor ezauthor.php
  \ingroup eZDatatype
  \brief eZAuthor handles author lists

  \code

  include_once( "kernel/classes/datatypes/ezauthor/ezauthor.php" );

  $author = new eZAuthor( "Colour" );
  $author->addValue( "Red" );
  $author->addValue( "Green" );

  // Serialize the class to an XML document
  $xmlString =& $author->xmlString();

  \endcode
*/

include_once( "lib/ezxml/classes/ezxml.php" );

class eZAuthor
{
    /*!
    */
    function eZAuthor( )
    {
        $Authors = array();
        $this->AuthorCount = 0;
    }

    /*!
     Sets the name of the author
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Returns the name of the author set.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     Adds an author
    */
    function addAuthor( $id, $name, $email )
    {
        if ( $id == -1 )
            $id = $this->Authors[$this->AuthorCount - 1]['id'] + 1;

        $this->Authors[] = array( "id" => $id,
                                  "name" => $name,
                                  "email" => $email,
                             "is_default" => false );

        $this->AuthorCount ++;
    }

    function removeAuthors( $array_remove )
    {
        $authors =& $this->Authors;

        if ( count( $array_remove ) > 0 )
            foreach ( $array_remove as $id )
            {
                foreach ( $authors as $authorKey => $author )
                {
                    if ( $author['id'] == $id )
                    {
                        array_splice( $authors, $authorKey, 1 );
                        $this->AuthorCount --;
                    }
                }
            }
    }

    function attributes()
    {
        return array( 'author_list',
                      'name',
                      'is_empty' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

   function &attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "is_empty" :
            {
                $count = count( $this->Authors ) == 0 ;
                return $count;
            }break;
            case "author_list" :
            {
                return $this->Authors;
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZAuthor::attribute' );
                $retValue = null;
                return $retValue;
            }
            break;
        }
    }

    /*!
     \return a string which contains all the interesting meta data.

     The result of this method can passed to the search engine or other
     parts which work on meta data.

     The string will contain all the authors with their name and email.

     Example:
     \code
     'John Doe john@doe.com'
     \endcode
    */
    function metaData()
    {
        $data = '';
        foreach ( $this->Authors as $author )
        {
            $data .= $author['name'] . ' ' . $author['email'] . "\n";
        }
        return $data;
    }

    /*!
     Will decode an xml string and initialize the eZ author object
    */
    function decodeXML( $xmlString )
    {
        $xml = new eZXML();
        $dom =& $xml->domTree( $xmlString );

        if ( $dom )
        {
            $authorArray =& $dom->elementsByName( 'author' );
            if ( is_array( $authorArray ) )
            {
                foreach ( $authorArray as $author )
                {
                    $this->addAuthor( $author->attributeValue( "id" ), $author->attributeValue( "name" ), $author->attributeValue( "email" ) );
                }
            }
        }
        else
        {
        }
    }

    /*!
     Will return the XML string for this author set.
    */
    function &xmlString( )
    {
        $doc = new eZDOMDocument( "Author" );

        $root = $doc->createElementNode( "ezauthor" );
        $doc->setRoot( $root );

        $authors = $doc->createElementNode( "authors" );

        $root->appendChild( $authors );
        $id=0;
        if ( is_array( $this->Authors ) )
        {
            foreach ( $this->Authors as $author )
            {
                unset( $authorNode );
                $authorNode = $doc->createElementNode( "author" );
                $authorNode->appendAttribute( $doc->createAttributeNode( "id", $id++ ) );
                $authorNode->appendAttribute( $doc->createAttributeNode( "name", $author["name"] ) );
                $authorNode->appendAttribute( $doc->createAttributeNode( "email", $author["email"] ) );

                $authors->appendChild( $authorNode );
            }
        }

        $xml = $doc->toString();

        return $xml;
    }

    /// Contains the Authors
    var $Authors;

    /// Contains the author counter value
    var $AuthorCount;
}

?>
