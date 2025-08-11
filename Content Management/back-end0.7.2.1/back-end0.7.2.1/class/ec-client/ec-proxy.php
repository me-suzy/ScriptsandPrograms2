<?php
//** Open issues
//** - how to pass error information from $response back to consumers of the proxy

require_once('kd_xmlrpc.php');

$ec_err = '';

class ECproxy {


   function district_details_from_address($jurisdiction, $street_number, $street_name, $street_type, $city, $province, &$district_id, &$district_name_eng, &$district_name_fre, &$adjacent_districts, &$district_map_url) {

         global $ec_err;
         
         $args['jurisdiction'] = $jurisdiction;
         $args['street_number'] = $street_number;
         $args['street_name'] = $street_name;
         $args['street_type'] = $street_type;
         $args['city'] = $city;
         $args['province'] = $province;

         //** There seems to be an extra level or two of nesting when XMLRPC_prepare proceses $args(), and it is treated as a struct. Why isn't it an array?
         // , 'array'
         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'DistrictDetailsFromAddress', array(XMLRPC_prepare($args)) );

         if ($success) {
            $district_id = $response['ID'];
             $district_name_eng = $response['NameEng'];
             $district_name_fre = $response['NameFre'];
            $adjacent_districts = $response['AdjacentDistricts']; 
            $district_map_url = $response['MapURL'];
            return True;
         } 
         else {
            $ec_err = $response;
            return False;
         }
   }

   function district_details_from_district_id($jurisdiction, $district_id, &$district_name_eng, &$district_name_fre, &$adjacent_districts, &$district_map_url) {

         global $ec_err;
         
         $args['jurisdiction'] = $jurisdiction;
         $args['district_id'] = $district_id;

         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'DistrictDetailsFromDistrictID', array(XMLRPC_prepare($args)) );

         if ($success) {
            $district_id = $response['ID'];
             $district_name_eng = $response['NameEng'];
             $district_name_fre = $response['NameFre'];
            $adjacent_districts = $response['AdjacentDistricts']; 
            $district_map_url = $response['MapURL'];
            return True;
         }
         else {
            $ec_err = $response;
            return False;
         }
   }

   function district_details_from_postal_code($jurisdiction, $postal_code, &$district_id, &$district_name_eng, &$district_name_fre, &$adjacent_districts, &$district_map_url) {

         global $ec_err;
         
         $args['jurisdiction'] = $jurisdiction;
         $args['postal_code'] = $postal_code;

         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'DistrictDetailsFromPostalCode', array(XMLRPC_prepare($args)) );

         if ($success) {
            $district_id = $response['ID'];
             $district_name_eng = $response['NameEng'];
             $district_name_fre = $response['NameFre'];
            $adjacent_districts = $response['AdjacentDistricts']; 
            $district_map_url = $response['MapURL'];
            return True;
         }
         else {
            $ec_err = $response;
            return False;
         }
   }

   function district_details_from_postal_code_qc($postal_code, $street_number, &$district_id, &$district_name_eng, &$district_name_fre, &$adjacent_districts, &$district_map_url) {

         global $ec_err;
         
         $args['postal_code'] = $postal_code;
         $args['street_number'] = $street_number;

         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'DistrictDetailsFromPostalCodeQC', array(XMLRPC_prepare($args)) );

         if ($success) {
            $district_id = $response['ID'];
             $district_name_eng = $response['NameEng'];
             $district_name_fre = $response['NameFre'];
             $adjacent_districts = $response['AdjacentDistricts'];
            $district_map_url = $response['MapURL'];
            return True;
         }
         else {
            $ec_err = $response;
            return False;
         }
   }

   function district_id_from_address($jurisdiction, $street_number, $street_name, $street_type, $city, $province, &$district_id) {

         global $ec_err;
         
         $args['jurisdiction'] = $jurisdiction;
         $args['street_number'] = $street_number;
         $args['street_name'] = $street_name;
         $args['street_type'] = $street_type;
         $args['city'] = $city;
         $args['province'] = $province;

         // , 'array'
         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'DistrictIDFromAddress', array(XMLRPC_prepare($args)) );

         if ($success) {
            $district_id = $response['ID'];
            return True;
         }
         else {
            $ec_err = $response;
            return False;
         }
   }
    
   function district_id_from_postal_code($jurisdiction, $postal_code, &$district_id) {

         global $ec_err;
         
         $args['jurisdiction'] = $jurisdiction;
         $args['postal_code'] = $postal_code;

         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'DistrictIDFromPostalCode', array(XMLRPC_prepare($args)) );

         if ($success) {
            $district_id = $response['ID'];
            return True;
         }
         else {
            $ec_err = $response;
            return False;
         }
   }

   function parl_details_from_postalcode( $jurisdiction, $postal_code, &$MP_name, &$MP_district, &$MP_photo_URL, &$MP_hill_address, &$MP_district_address ) {

         global $ec_err;
         
         $args['jurisdiction'] = $jurisdiction;
         $args['postal_code'] = $postal_code;

         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'ParlDetailsFromPostalCode', array(XMLRPC_prepare($args)) );

         if ($success) {
            $MP_name = $response['Name'];
             $MP_district = $response['DistrictName'];
             $MP_photo_URL = $response['PhotoURL'];
            $MP_hill_address = $response['ParlAddress'];
            $MP_district_address = $response['DistrictAddress'];
            return True;
         } 
         else {
            $ec_err = $response;
            return False;
         }

   }

   function postal_code_from_address($street_number, $street_name, $street_type, $city, $province, &$postal_code) {

         global $ec_err;
         
         $args['street_number'] = $street_number;
         $args['street_name'] = $street_name;
         $args['street_type'] = $street_type;
         $args['city'] = $city;
         $args['province'] = $province;

         // , 'array'
         list($success, $response) = XMLRPC_request(XML_RPC_SITE, XML_RPC_LOCATION, 'PostalCodeFromAddress', array(XMLRPC_prepare($args)) );

         if ($success) {
            $postal_code = $response;
            return True;
         }
         else {
            $ec_err = $response;
            return False;
         }
   }

} //ec-proxy

?>
