<?php
//
// Definition of eZBasket class
//
// Created on: <04-Jul-2002 15:28:58 bf>
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
  \class eZBasket ezbasket.php
  \brief eZBasket handles shopping baskets
  \ingroup eZKernel

  \sa eZProductCollection
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezuserdiscountrule.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/ezorder.php" );

class eZBasket extends eZPersistentObject
{
    /*!
    */
    function eZBasket( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZCard class.
    */
    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "session_id" => array( 'name' => "SessionID",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "productcollection_id" => array( 'name' => "ProductCollectionID",
                                                                          'datatype' => 'integer',
                                                                          'default' => '0',
                                                                          'required' => true ),
                                         "order_id"             => array( 'name'     => "OrderID",
                                                                          'datatype' => 'integer',
                                                                          'default'  => 0,
                                                                          'required' => false ) ),
                      'function_attributes' => array( 'items' => 'items',
                                                      'total_ex_vat' => 'totalExVAT',
                                                      'total_inc_vat' => 'totalIncVAT',
                                                      'is_empty' => 'isEmpty' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZBasket",
                      "name" => "ezbasket" );
    }

    function &items( $asObject = true )
    {
        $productItems = eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null, array( "productcollection_id" => $this->ProductCollectionID
                                                                    ),
                                                       array( 'contentobject_id' => 'desc' ),
                                                       null,
                                                       $asObject );
        $addedProducts = array();
        foreach ( $productItems as  $productItem )
        {
            $discountPercent = 0.0;
            $isVATIncluded = true;
            $id = $productItem->attribute( 'id' );
            $contentObject = $productItem->attribute( 'contentobject' );

            if ( $contentObject !== null )
            {
                $vatValue = $productItem->attribute( 'vat_value' );
                $count = $productItem->attribute( 'item_count' );
                $discountPercent = $productItem->attribute( 'discount' );
                $nodeID = $contentObject->attribute( 'main_node_id' );
                $objectName = $contentObject->attribute( 'name' );

                $isVATIncluded = $productItem->attribute( 'is_vat_inc' );
                $price = $productItem->attribute( 'price' );

                if ( $isVATIncluded )
                {
                    $priceExVAT = $price / ( 100 + $vatValue ) * 100;
                    $priceIncVAT = $price;
                    $totalPriceExVAT = $count * $priceExVAT * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }
                else
                {
                    $priceExVAT = $price;
                    $priceIncVAT = $price * ( 100 + $vatValue ) / 100;
                    $totalPriceExVAT = $count * $priceExVAT  * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }

/*
                $attributes = $contentObject->contentObjectAttributes();
                foreach ( $attributes as $attribute )
                {
                    $dataType =& $attribute->dataType();
                    if ( $dataType->isA() == "ezprice" )
                    {
                        $priceObj =& $attribute->content();
                        $discountPercent = $priceObj->discount();
                        $vatValue = $priceObj->attribute( 'vat_percent' );
                        $isVATIncluded = $priceObj->attribute( 'is_vat_included' );
                        eZDebug::writeDebug( $vatValue, 'VAT Value' . $contentObject->attribute( 'name' ) );

                        $classAttribute =& $attribute->attribute( 'contentclass_attribute' );
                        $VATID =  $classAttribute->attribute( EZ_DATATYPESTRING_VAT_ID_FIELD );
                        $VATIncludeValue = $classAttribute->attribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD );
                        if ( $VATIncludeValue==0 or $VATIncludeValue==1 )
                            $isVATIncluded = true;
                        else
                            $isVATIncluded = false;
                        $vatType = eZVatType::fetch( $VATID );
                        if ( get_class( $vatType ) == 'ezvattype' )
                        {
                            $vatValue = $vatType->attribute( 'percentage' );
                        }
                        else
                        {
                            $vatValue = 0.0;
                        }

                        break;

                    }
                }
                $nodeID = $contentObject->attribute( 'main_node_id' );
                $objectName = $contentObject->attribute( 'name' );
                $count = $productItem->attribute( 'item_count' );
                $price = $productItem->attribute( 'price' );

                if ( $isVATIncluded )
                {
                    $priceExVAT = $price / ( 100 + $vatValue ) * 100;
                    $priceIncVAT = $price;
                    $totalPriceExVAT = $count * $priceExVAT * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }
                else
                {
                    $priceExVAT = $price;
                    $priceIncVAT = $price * ( 100 + $vatValue ) / 100;
                    $totalPriceExVAT = $count * $priceExVAT  * ( 100 - $discountPercent ) / 100;
                    $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                }

*/
                $addedProduct = array( "id" => $id,
                                       "vat_value" => $vatValue,
                                       "item_count" => $count,
                                       "node_id" => $nodeID,
                                       "object_name" => $objectName,
                                       "price_ex_vat" => $priceExVAT,
                                       "price_inc_vat" => $priceIncVAT,
                                       "discount_percent" => $discountPercent,
                                       "total_price_ex_vat" => $totalPriceExVAT,
                                       "total_price_inc_vat" => $totalPriceIncVAT,
                                       'item_object' =>$productItem );
                $addedProducts[] = $addedProduct;
            }
        }
        return $addedProducts;
    }

    function &totalIncVAT()
    {
        $items =& $this->items();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item['total_price_inc_vat'];
        }
        return $total;
    }

    function &totalExVAT()
    {
        $items =& $this->items();

        $total = 0.0;
        foreach ( $items as $item )
        {
            $total += $item['total_price_ex_vat'];
        }
        return $total;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeItem( $itemID )
    {
        $item = eZProductCollectionItem::fetch( $itemID );
        if ( is_object( $item ) )
        {
            $item->remove();
        }
    }

    function &isEmpty()
    {
        $items = eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null,
                                                       array( "productcollection_id" => $this->ProductCollectionID ),
                                                       null,
                                                       null,
                                                       false );
        if ( count( $items ) > 0 )
        {
            $result = false;
        }
        else
        {
            $result = true;
        }

        return $result;
    }

    /*!
     Will return the basket for the current session. If a basket does not exist one will be created.
     \return current eZBasket object
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &currentBasket( $asObject=true, $byOrderID=-1 )
    {
        $basketList = array();

        if( $byOrderID != -1 )
        {
            $basketList = eZPersistentObject::fetchObjectList( eZBasket::definition(),
                                                                null, array( "order_id" => $byOrderID ),
                                                                null, null,
                                                                $asObject );
        }
        else
        {
            $http =& eZHTTPTool::instance();
            $sessionID = $http->sessionID();

            $basketList = eZPersistentObject::fetchObjectList( eZBasket::definition(),
                                                                null, array( "session_id" => $sessionID ),
                                                                null, null,
                                                                $asObject );
        }

        $currentBasket = false;
        if ( count( $basketList ) == 0 )
        {
            $db =& eZDB::instance();
            $db->begin();
            $collection = eZProductCollection::create();
            $collection->store();

            $currentBasket = new eZBasket( array( "session_id" => $sessionID,
                                              "productcollection_id" => $collection->attribute( "id" ) ) );
            $currentBasket->store();
            $db->commit();
        }
        else
        {
            $currentBasket =& $basketList[0];
        }
        return $currentBasket;
    }

    /*!
     Creates a temporary order for the current basket.
     The order object is returned.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function createOrder()
    {
        // Make order
        $productCollectionID = $this->attribute( 'productcollection_id' );

        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        include_once( 'kernel/classes/ezorderstatus.php' );
        $time = mktime();
        $order = new eZOrder( array( 'productcollection_id' => $productCollectionID,
                                     'user_id' => $userID,
                                     'is_temporary' => 1,
                                     'created' => $time,
                                     'status_id' => EZ_ORDER_STATUS_PENDING,
                                     'status_modified' => $time,
                                     'status_modifier_id' => $userID
                                     ) );

        $db =& eZDB::instance();
        $db->begin();
        $order->store();

        $orderID = $order->attribute( 'id' );
        $this->setAttribute( 'order_id', $orderID );
        $this->store();
        $db->commit();

        return $order;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function updatePrices()
    {
        $items =& $this->items();

        $db =& eZDB::instance();
        $db->begin();
        foreach( array_keys( $items ) as $key )
        {
            $itemArray =& $items[ $key ];
            $item =& $itemArray['item_object'];
            $productContentObject =& $item->attribute( 'contentobject' );
            $priceObj = null;
            $price = 0.0;
            $attributes =&  $productContentObject->contentObjectAttributes();
            foreach ( $attributes as $attribute )
            {
                $dataType =& $attribute->dataType();
                if ( $dataType->isA() == "ezprice" )
                {
                    $priceObj =& $attribute->content();
                    $price += $priceObj->attribute( 'price' );
                    break;
                }
            }
            if ( $priceObj == null )
                break;
            $optionsPrice = $item->calculatePriceWithOptions();

            $price += $optionsPrice;
            $item->setAttribute( "price", $price );
            if ( $priceObj->attribute( 'is_vat_included' ) )
            {
                $item->setAttribute( "is_vat_inc", '1' );
            }
            else
            {
                $item->setAttribute( "is_vat_inc", '0' );
            }
            $item->setAttribute( "vat_value", $priceObj->attribute( 'vat_percent' ) );
            $item->setAttribute( "discount", $priceObj->attribute( 'discount_percent' ) );
            $item->store();
        }
        $db->commit();
    }

    /*!
     \static
     Removes all baskets for all users.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $rows = $db->arrayQuery( "SELECT productcollection_id FROM ezbasket" );

        $db->begin();
        if ( count( $rows ) > 0 )
        {
            $productCollectionIDList = array();
            foreach ( $rows as $row )
            {
                $productCollectionIDList[] = $row['productcollection_id'];
            }
            eZProductCollection::cleanupList( $productCollectionIDList );
        }
        $db->query( "DELETE FROM ezbasket" );
        $db->commit();
    }
}

?>
