<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Knowledge Base Module
// >>
// >> KB . PHP File - A Knowledge Base for ExoPHPDesk
// >> Started : November 27, 2003
// >> Edited  : June 11, 2004
// << -------------------------------------------------------------------- >>

class KB
{
	// -- START VARIABLES -- //
	var $ID;
	var $TITLE;
	var $MESSAGE;
	var $GROUP;
	var $VIEW;
	// -- END OF VARIABLES -- //
	
	
	/*********************************************************************
	*  KB_VIEW()
	*  Views the entire thing, all the faqs categorized, or as per request.
	*  @access public
	*********************************************************************/
	function kb_view()
	{
		GLOBAL $db,$tpl_dir,$class,$general;

		_parse($tpl_dir.'kb.tpl');
		$READ = getBlock( $class->read, 'KB_MAIN' );
		
		$TOPS = substr($READ, 0, strpos($READ, '[#'));
		
		$MIDDLE = substr($READ, strpos($READ, '[#')+2);
		$MIDDLE = substr($MIDDLE, 0, strpos($MIDDLE, '/#]'));
		
		$DOWN = substr($READ, strpos($READ, '/#]')+3);
		
		if($this->GROUP != '')
		{
			$CHECK = $db->query("SELECT * FROM `phpdesk_kbgroups` WHERE name='".$this->GROUP."'");
			if($db->num($CHECK))
			{
				$GROUPS = array(ucfirst($this->GROUP));
			}
			else
			{
				$DO = 'DO';
			}
		}
		
		if(empty($this->GROUP) || $DO == 'DO')
		{
			$GROUP = $db->query("SELECT * FROM `phpdesk_kbgroups`");
			$GROUPS = array();
			while($GF = $db->fetch($GROUP))
			{
				array_push($GROUPS, $GF['name']);
			}
		}
		
		if ( empty ( $GROUPS ) )
		{
			return "NO GROUPS";
		}
		
		foreach($GROUPS AS $GF)
		{

			$TOP .= str_replace('^group^', $GF, $TOPS);
			$EXT = (empty($this->VIEW) || $this->VIEW == 'staff' || $this->VIEW == 'member') ? "" : " AND view='ALL'";
	
			$Q1 = $db->query("SELECT * FROM phpdesk_kb WHERE `group`='".$GF."'");
			$Q = $db->query("SELECT * FROM phpdesk_kb WHERE `group`='".$GF."'".$EXT." ORDER by posted DESC");
			
			if(!$db->num($Q))
			{
				$TOP .= ($db->num($Q1)) ? $general['kb_noaccess'] : $general['kb_empty'];
			}			
			else
			{
				$Z = 0;
				while($F = $db->fetch($Q))
				{
					$Z++;
					$BG = (is_float($Z/2)) ? 'tdbg1' : 'tdbg2';	

					if($this->VIEW == 'staff')
					{
						$QY  =  $db->query ( "SELECT * FROM `phpdesk_kbgroups` WHERE `name` = '" . $F['group'] ."' AND `staff` = '1'" );
						if ( strtolower ( $F['owner'] ) == strtolower ( USER ) && $db->num ( $QY ) )
						{
							$MOVE = 'DO';
						}
						else
						{
							$MOVE = NULL;
						}
					}
					
					global $SID;
					
					if($this->VIEW == '' || $this->VIEW == 'staff')
					{
						if($this->VIEW == '' || $MOVE == 'DO')
						{
							$LINK = " [ <a href='".SELF."?action=kb&type=edit&id=".$F['id']."&s={$SID}'>Edit</a> ] ";
						}
						
						if($this->VIEW == '' || $MOVE == 'DO')
						{
							$LINK .= " [ <a href='".SELF."?action=kb&type=delete&id=".$F['id']."&s={$SID}'>Delete</a> ]";
						}
					}
					
					$OUT = str_replace('^title^', $F['title'], str_replace('^ids^', $F['id'], $MIDDLE));
					$OUT = str_replace('^id^', $F['id'].$LINK, $OUT);
					$TOP .= str_replace('^added^', date('d-m-y',$F['posted']), str_replace('^tdbg^', $BG, $OUT));
					
				}
			}
		}

		return $TOP.$DOWN;		
		
	} // -- END OF FUNCTION -- //

	/*********************************************************************
	*  KB_VIEW_IN()
	*  Returns the F.A.Q requested by the user.
	*  @access public
	*********************************************************************/
	function kb_view_in()
	{
		GLOBAL $db,$tpl_dir,$class,$error,$tpl,$_GET;

		$EXT = (empty($this->VIEW) || $this->VIEW == 'staff' || $this->VIEW == 'member') ? "" : " AND view='ALL'";
		$FAQ = $db->query("SELECT * FROM phpdesk_kb WHERE id = '".$this->ID."'".$EXT);
		
		if(!$db->num($FAQ))
		{
			echo $error['no_such_faq'];
		}
		else
		{
			$F = $db->fetch($FAQ);
			
			$QY   =  $db->query ( "SELECT * FROM `phpdesk_ratings` WHERE `type` = 'faq' AND `uid` = '" . $this->ID ."'" );
			$CT   =  $db->num ( $QY );
			$RATE =  0;
			
			while ( $FY  =  $db->fetch ( $QY ) )
			{
				$RATE += $FY['rating'];
			}
			
			$RATING  =  ( $RATE / ( $CT * 5 ) * 100 );
			$RATING  =  number_format ( ( 5 * $RATING / 100 ), 2 );
		
			$RATING = ( $CT > 0 ) ? $RATING . $tpl['out_five'] : $tpl['none_yet'];
			$IMAGE  = round ( $RATING );
			
			_parse($tpl_dir.'kb.tpl');
			$READ = getBlock( $class->read, 'KB_VIEW' );
			
			// NEW!! Highlight Text
			if( !empty($_GET['highlight']) )
			{
				$_GET['highlight'] = str_replace( '%', NULL, $_GET['highlight'] );
				$F['message'] = preg_replace( '/('. $_GET['highlight'] .')/i', '<font color="red">\\1</font>', $F['message'] );
			}
			
			$READ = str_replace( '^f_owner^', $F['owner'], str_replace('^f_added^', date('d-m-y', $F['posted']), $READ));
			$READ = str_replace( '^f_group^', $F['group'], str_replace('^f_text^', str_replace("\n", "<br>", $F['message']), $READ));
			$READ = str_replace( '^RATING^', $RATING, str_replace( '^id^', $F['id'], str_replace( '^IMAGE^', $IMAGE, $READ ) ));
			
			return str_replace('^title^', $F['title'], $READ);
						
		}
	} // -- END OF FUNCTION -- //

	/*********************************************************************
	*  KB_ADD_FORM()
	*  Returns the show/edit form for a F.A.Q.
	*  @access public
	*********************************************************************/
	function kb_add_form()
	{
		GLOBAL $db,$tpl_dir,$class,$_GET,$TITLE,$MESSAGE,$error;

		$MESSAGE = $this->MESSAGE;
		$TITLE = $this->TITLE;

		if ( $this->VIEW == 'staff' )
		{

			if ( !empty ( $this->ID ) )
			{
				$KQ  =  $db->query ( "SELECT * FROM `phpdesk_kb` WHERE `id` = '" . $this->ID . "'" );
				$KF  =  $db->fetch ( $KQ );

				$CQ  =  $db->query ( "SELECT * FROM `phpdesk_kbgroups` WHERE `staff` = '1'" );

				if ( $db->num ( $CQ ) && strtolower ( $KF['owner'] ) == strtolower ( USER ) )
				{
					$HAVE = 'YES';
				}
				else
				{
					return $error['no_auth_or_record'];
				}				
			}
			else
			{
				$HAVE = 'YES';
			}
			
			if ( $HAVE == 'YES' )
			{
				$GROUPS = array();

				$CQ  =  $db->query ( "SELECT * FROM `phpdesk_kbgroups` WHERE `staff` = '1'" );
				while ( $CF = $db->fetch ( $CQ ) )
				{
					array_push ( $GROUPS, $CF['name'] );
				}
				
				// FIX (19/10/04) - if we dont have any group, then die!
				if (!$db->num($CQ)) {
					return $error['no_kb_groups'];
				}
				
			}
		}
		else
		{
			$GROUPS = array();
			
			$CQ  =  $db->query ( "SELECT * FROM `phpdesk_kbgroups`" );
			while ( $CF = $db->fetch ( $CQ ) )
			{
				array_push ( $GROUPS, $CF['name'] );
			}		
		}
				
		if(!empty($this->GROUP))
		{
			$OPTIONS = "<option value='".$this->GROUP."'>".$this->GROUP."</option>\n";
		}
		
		foreach($GROUPS AS $FG)
		{
			$OPTIONS .= "<option value='".$FG."'>".$FG."</option>\n";
		}
		
		$Q = $db->query ( "SELECT * FROM phpdesk_kb WHERE id = '" . $this->ID . "'" );
		if( $db->num ( $Q ) )
		{
			$GKB = $db->fetch($Q);
			$VIEW = "<option value='".$GKB['view']."'>".$GKB['view']."</option>\n";
		}

		$VIEW .= "<option value='Registered'>Registered</option>\n"
				."<option value='ALL'>ALL</option>\n";
				
		_parse($tpl_dir.'add.tpl');
		$READ = $class->read;

		$SPLIT = substr($READ, strpos($READ, '[#kb')+4);
		$SPLIT = substr($SPLIT, 0, strpos($SPLIT, '/#kb]'));
		
		$READ = substr($READ, 0, strpos($READ, '[#'));
		
		return $READ . str_replace('^options^', $OPTIONS, str_replace('^view^', $VIEW, $SPLIT));
		
	} // -- END OF FUNCTION -- //

	/*********************************************************************
	*  KB_ADD()
	*  Add the F.A.Q. using the infromation submitted.
	*  @access public
	*********************************************************************/
	function kb_add()
	{
		GLOBAL $db,$class,$_POST,$success;
		
		$VALIDATE = validate('kb', $_POST);
		
		if($VALIDATE != '')
		{
			return $VALIDATE;
		}
		
		$SQL = "INSERT INTO phpdesk_kb (`title`,`message`,`posted`,`view`,`owner`,`group`)
				VALUES( '".$_POST['title']."', '".$_POST['message']."',
				'".time()."', '".$_POST['view']."', '".USER."', 
				'".$_POST['group']."' )";
		
		if($db->query($SQL))
		{
			return $success['added_kb'];
		}
		
	} // -- END OF FUNCTION -- //
	
	/*********************************************************************
	*  KB_EDIT()
	*  Edit a FAQ using the information submitted.
	*  @access public
	*********************************************************************/
	function kb_edit()
	{
		GLOBAL $db,$class,$_POST,$success;
		
		$VALIDATE = validate('kb', $_POST, 'edit');
		
		if($VALIDATE != '')
		{
			return $VALIDATE;
		}

		$SQL = "UPDATE phpdesk_kb SET
				`title` = '". $_POST['title'] ."',
				`message` = '".$_POST['message']."',
				`view` = '".$_POST['view']."',
				`group` = '".$_POST['group']."'
				WHERE `id` = '".$_GET['id']."'";
	
		if($db->query($SQL))
		{
			return $success['update_kb'];
		}
		
	} // -- END OF FUNCTION -- //
	
	/*********************************************************************
	*  KB_DELETE()
	*  Delete a FAQ as requested, using the ID specified.
	*  @access public
	*********************************************************************/
	function kb_delete()
	{
		GLOBAL $error,$db,$success,$sel_staff;
			
		// IF STAFF CHECK AUTH
		if( $this->VIEW == 'staff' )
		{
			$KQ  =  $db->query ( "SELECT * FROM `phpdesk_kb` WHERE `id` = '" . $this->ID . "'" );
			$KF  =  $db->fetch ( $KQ );

			$CQ  =  $db->query ( "SELECT * FROM `phpdesk_kbgroups` WHERE `name` = '" . $KF['group'] . "' AND `staff` = '1'" );
			
			if ( $db->num ( $CQ ) && strtolower ( $KF['owner'] ) == strtolower ( USER ) )
			{
				$HAVE = 'YES';
			}
			else
			{
				$HAVE = NULL;
			}
		}
		else
		{
			$HAVE = 'YES';
		}
	
		if($HAVE != 'YES')
		{
			return $error['no_auth_or_record'];
		}

		if(empty($this->ID))
		{
			return $error['no_such_faq'];
		}
		
		if($db->query("DELETE FROM phpdesk_kb WHERE id='".$this->ID."'"))
		{
			return $success['kb_deleted'];
		}
		
	} // -- END OF FUNCTION -- //

	/*********************************************************************
	*  KB_VIEW()
	*  An extra function, to list all the groups.
	*  @access public
	*********************************************************************/
	function kb_list_group()
	{
		GLOBAL $db,$tpl_dir,$class,$general,$_GET;
		
		$GROUPS = $db->query("SELECT * FROM `phpdesk_kbgroups`");
		
		_parse($tpl_dir.'kb.tpl');
		$READ = getBlock( $class->read, 'KB_CAT' );
		
		$TOPS = substr($READ, 0, strpos($READ, '[#'));
		
		$MIDDLE = substr($READ, strpos($READ, '[#')+2);
		$MIDDLE = substr($MIDDLE, 0, strpos($MIDDLE, '/#]'));
		
		$DOWN = substr($READ, strpos($READ, '/#]')+3);
		
		if(!$db->num($GROUPS))
		{
			echo $general['no_categories'];
		}
		$TOP = $TOPS;		
		while($F = $db->fetch($GROUPS))
		{
			$Z++;
			$BG = (is_float($Z/2)) ? 'tdbg1' : 'tdbg2';	
			$Q = $db->query("SELECT * FROM phpdesk_kb WHERE `group`='".$F['name']."' ORDER by posted DESC");
			$COUNT = $db->num($Q);

			$_F = $db->fetch($Q);
			$LAST = (!empty($_F['posted'])) ? date('d-m-y', $_F['posted']) : "Never";
	
			if($F['name'] == 'EMAIL') { CONTINUE; }
			$OUT = str_replace('^name^', $F['name'], str_replace('^no^', $COUNT, str_replace('^add^', $LAST, $MIDDLE)));
			$TOP .= str_replace('^tdbg^', $BG, $OUT);
		}
		return $TOP.$DOWN;		
		
	} // -- END OF FUNCTION -- //
	
	/*********************************************************************
	*  KB_GROUP_FORM()
	*  A function used to list the Group Add Form.
	*  @access public
	*********************************************************************/
	function kb_group_form()
	{
		GLOBAL $db,$tpl_dir,$class,$T_ST;
		
		if ( !empty ( $this->ID ) )
		{
			$QY  =  $db->query ( "SELECT * FROM `phpdesk_kbgroups` WHERE `id` = '" . $this->ID . "'" );
			
			if ( !$db->num ( $QY ) )
			{
				return $error['no_auth_or_record'];
			}
			
			$QF  =  $db->fetch ( $QY );
			
			$NAME  =  $QF['name'];
			$STAFF =  $QF['staff'];
		}
		
		_parse ( $tpl_dir . 'add.tpl' );
		
		$READ  =  $class->read;
		$TOP   =  template ( $READ, NULL, $T_ST );
		$MIDD  =  template ( $READ, $T_ST . 'kb_group', '/#kb_group]' );
		
		$CHECK =  ( $STAFF == '1' ) ? " checked" : NULL;
		
		$CBOX  =  '<input type="checkbox" name="staff" value="1"' . $CHECK . ">\n";
		
		$MIDD  =  str_replace ( '^staff^', $CBOX, $MIDD );
		$MIDD  =  str_replace ( '^NAME^', $NAME, $MIDD );
		
		return $TOP . $MIDD;
		
	} // -- END OF FUNCTION -- //	

	/*********************************************************************
	*  KB_GROUP_ADD()
	*  Add/Edit the Knowledge Base Group
	*  @access public
	*********************************************************************/
	function kb_group_add()
	{
		GLOBAL $db,$class,$_POST,$success, $_GET;
		
		$VALIDATE = validate( 'groups', $_POST );
		
		if( $VALIDATE != '' )
		{
			return $VALIDATE;
		}
		
		$STAFF = ( $_POST['staff'] == '1' ) ? '1' : '0';

		$SQL = "INSERT INTO `phpdesk_kbgroups` SET
				`name` = '". $_POST['group_name'] ."',
				`staff` = '". $STAFF ."'";

		if ( !empty ( $_GET['id'] ) )
		{
			$KQ  = $db->query ( "SELECT * FROM `phpdesk_kbgroups` WHERE `id` = '" . $_GET['id'] ."'" );
			$KF  = $db->fetch ( $KQ );
			
			$db->query ( "UPDATE `phpdesk_kb` SET `group` = '" . $_POST['group_name'] ."' WHERE `group` = '" . $KF['name'] ."'" );
			
			$SQL = "UPDATE `phpdesk_kbgroups` SET
					`name` = '". $_POST['group_name'] ."',
					`staff` = '". $STAFF ."'
					WHERE `id` = '".$_GET['id']."'";
		}
	
		if($db->query($SQL))
		{
			return $success['update_kbg'];
		}
		
	} // -- END OF FUNCTION -- //

	/*********************************************************************
	*  KB_GROUP_DELETE()
	*  Deletes a group for the Knowledge Base
	*  @access public
	*********************************************************************/
	function kb_group_delete()
	{
		GLOBAL $error,$db,$success;
		
		if(empty($this->ID))
		{
			return $error['id_missing'];
		}
		
		$QY = $db->query ( "SELECT * FROM `phpdesk_kbgroups` WHERE `id` = '" . $this->ID . "'" );
		$QF = $db->fetch ( $QY );
		
		if($db->query("DELETE FROM phpdesk_kbgroups WHERE id='".$this->ID."'"))
		{
			$db->query ( "DELETE FROM phpdesk_kb WHERE `group` = '" . $QF['name'] ."'" );
			return $success['kbg_deleted'];
		}
		
	} // -- END OF FUNCTION -- //		
	
	/*********************************************************************
	*  KB_GROUP_LIST()
	*  Lists the Knowledge Base Groups for Admins
	*  @access public
	*********************************************************************/
	function kb_group_list()
	{
		GLOBAL $db,$class,$tpl_dir, $T_ST, $T_ED, $tpl, $SID;
		
		$VALIDATE = validate( 'groups', $_POST );
		
		_parse ( $tpl_dir . 'list.tpl' );
		
		$READ  =  $class->read;
		$TOP   =  template ( $READ, NULL, $T_ST );
		$MIDD  =  template ( $READ, $T_ST . 'kb_groups', '/#kb_groups]' );
		$LIST  =  template ( $MIDD, $T_ST, $T_ED );
		$MIDD  =  template ( $MIDD, NULL, $T_ST );
		
		$QY  =  $db->query ( "SELECT * FROM `phpdesk_kbgroups`" );
		$OUT =  NULL;
		
		while ( $QF = $db->fetch ( $QY ) )
		{
			$X++;
			
			$STAFF   =  ( $QF['staff'] == '1' ) ? $tpl['allowed'] : $tpl['disallowed'];
			$ACTIONS =  '[ <a href="admin.php?action=kb&type=add_group&id='. $QF['id'] .'&s='.$SID.'">Edit</a> ] '
						.'[ <a href="admin.php?action=kb&type=del_group&id='. $QF['id'] .'&s='.$SID.'">Delete</a> ] ';
					
			$BG   =  ( is_float ( $X / 2 ) ) ? "tdbg1" : "tdbg2";
			$TMP  =  str_replace ( '^NAME^', $QF['name'], $LIST );
			$TMP  =  str_replace ( '^STAFF^', $STAFF, $TMP );
			$TMP  =  str_replace ( '^ACTIONS^', $ACTIONS, $TMP );
			$TMP  =  str_replace ( '^td_bg^', $BG, $TMP );

			$OUT .=  $TMP;
		}
		
		return $TOP . $MIDD . $OUT;
		
	} // -- END OF FUNCTION -- //	
			

} // -- END OF CLASS -- //


?>