<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/creation_time/creation_time.inc,v $
## $Revision: 1.1 $
## $Author: achadszinow $
## $Date: 2003/12/18 23:41:06 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Role_Creation_Time extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array(
			'datetime'
		)
	);
}
?>