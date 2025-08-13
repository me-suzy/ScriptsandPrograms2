<?php

class skin_mod {


function modtopicview_start($tid,$forumname, $fid, $title) {
global $ibforums;
return <<<EOF

<form name='ibform' action='{$ibforums->base_url}' method='POST'>
 <input type='hidden' name='s' value='{$ibforums->session_id}'>
 <input type='hidden' name='act' value='ModCP'>
 <input type='hidden' name='CODE' value='domodposts'>
 <input type='hidden' name='f' value='{$fid}'>
 <input type='hidden' name='tid' value='{$tid}'>
 
<tr>
  <td colspan='2'>
    <table cellpadding='2' cellspacing='1' border='0' width='100%' class='fancyborder' align='center'>
     <tr>
       <td><span class='pagetitle'>{$ibforums->lang['cp_mod_posts_title2']} $forumname</span>
       <br>$pages
       </td>
     </tr>
	<tr>
	 <td colspan='2'>
	  <table width='100%' cellpadding='4' cellspacing='1' bgcolor='<{tbl_border}>'>
		<tr>
			<td valign='middle' class='titlemedium' align='left' colspan='2'>$title</td>
		</tr>
                
EOF;
}


function modpost_topicstart($forumname, $fid) {
global $ibforums;
return <<<EOF

<tr>
  <td colspan='2'>
    <table cellpadding='2' cellspacing='1' border='0' width='100%' class='fancyborder' align='center'>
     <tr>
       <td><span class='pagetitle'>{$ibforums->lang['cp_mod_posts_title2']} $forumname</span>
       </td>
     </tr>
     <tr>
	 <td>
	  <table width='100%' cellpadding='4' cellspacing='1' bgcolor='<{tbl_border}>'>
	  <tr>
	    <td class='titlemedium' width='40%'>{$ibforums->lang['cp_3_title']}</td>
	    <td class='titlemedium' width='20%' align='center'>{$ibforums->lang['cp_3_replies']}</td>
	    <td class='titlemedium' width='20%' align='center'>{$ibforums->lang['cp_3_approveall']}</td>
	    <td class='titlemedium' width='20%' align='center'>{$ibforums->lang['cp_3_viewall']}</td>
	  </tr>
	 
EOF;
}

function modpost_topicentry($title, $tid, $replies, $fid) {
global $ibforums;
return <<<EOF

	  <tr>
	    <td class='row1' width='40%' align='left'><b><a href='{$ibforums->base_url}&act=ST&f=$fid&t=$tid' target='_blank'>$title</a></b></td>
	    <td class='row1' width='20%' align='center'>$replies</td>
	    <td class='row1' width='20%' align='center'><a href='{$ibforums->base_url}&act=ModCP&f=$fid&tid=$tid&CODE=modtopicapprove'>{$ibforums->lang['cp_3_approveall']}</a></td>
	    <td class='row1' width='20%' align='center'><a href='{$ibforums->base_url}&act=ModCP&f=$fid&tid=$tid&CODE=modtopicview'>{$ibforums->lang['cp_3_viewall']}</a></td>
	  </tr>
	 
EOF;
}

function modpost_topicend() {
global $ibforums;
return <<<EOF

	  </table>
	 </td>
	</tr>
   </table>
  </td>
 </tr>
	 
EOF;
}



function modtopics_start($pages,$forumname, $fid) {
global $ibforums;
return <<<EOF

<form name='ibform' action='{$ibforums->base_url}' method='POST'>
 <input type='hidden' name='s' value='{$ibforums->session_id}'>
 <input type='hidden' name='act' value='ModCP'>
 <input type='hidden' name='CODE' value='domodtopics'>
 <input type='hidden' name='f' value='{$fid}'>
 
<tr>
  <td colspan='2'>
    <table cellpadding='2' cellspacing='1' border='0' width='100%' class='fancyborder' align='center'>
     <tr>
       <td><span class='pagetitle'>{$ibforums->lang['cp_mod_topics_title2']} $forumname</span>
       <br>$pages
       </td>
     </tr>
	 
EOF;
}

function modtopics_end() {
global $ibforums;
return <<<EOF

	<tr>
	 <td class='row2' align='center'><select name='type' class='forminput'><option value='approve'>{$ibforums->lang['cp_1_approve']}</option><option value='remove'>{$ibforums->lang['cp_1_remove']}</option></select>&nbsp;&nbsp;{$ibforums->lang['cp_1_selected']}&nbsp;&nbsp;<input type='submit' value='{$ibforums->lang['cp_1_go']}' class='forminput'></td>
	</tr>
	</table>
   </td>
  </tr>
  </form>
	 
EOF;
}


function mod_topic_title($title, $topic_id) {
global $ibforums;
return <<<EOF

			<tr>
             <td colspan='2'>
			  <table width='100%' cellpadding='4' cellspacing='1' bgcolor='<{tbl_border}>'>
                <tr>
                	<td valign='middle' class='titlemedium' align='left' colspan='2'><input type='checkbox' name='TID_$topic_id' value='1'>&nbsp;&nbsp; $title</td>
                </tr>
                
EOF;
}


function mod_postentry($data) {
global $ibforums;
return <<<EOF
			
                <tr>
        		    <td valign='top' class='row1' width='25%'><span class='normalname'>{$data['member']['name']}</span><br><br>{$data['member']['avatar']}<span class='postdetails'><br>{$data['member']['MEMBER_GROUP']}<br>{$data['member']['MEMBER_POSTS']}<br>{$data['member']['MEMBER_JOINED']}</span></td>
                    <td valign='top' height='100%' class='row1' width='75%'>
                    	<b>{$ibforums->lang['posted_on']} {$data['msg']['post_date']}</b><br><br>
            		    <span class='postcolor'>
           			     {$data['msg']['post']}
                        </span>
                    </td>
                 </tr>
			  

EOF;
}

function mod_postentry_checkbox($pid) {
global $ibforums;
return <<<EOF

			<tr>
			 <td align='left' colspan='2' class='category'><input type='checkbox' name='PID_$pid' value='1'>&nbsp;&nbsp;{$ibforums->lang['cp_3_postno']}&nbsp;$pid</td>
			</tr>

EOF;
}


function mod_topic_spacer() {
global $ibforums;
return <<<EOF

			</table>
			</td>
		    </tr>
			<tr>
             <td colspan='2'>
			  &nbsp;
			</td>
		    </tr>

EOF;
}

function results($text) {
global $ibforums;
return <<<EOF

<tr>
  <td colspan='2'>
    <table cellpadding='2' cellspacing='1' border='0' width='100%' class='fancyborder' align='center'>
     <tr>
       <td><span class='pagetitle'>{$ibforums->lang['cp_results']}</span>
       </td>
     </tr>
	  <tr>
	    <td colspan='2'><b>$text</b></td>
	  </tr>
	 </table>
   </td>
  </tr>

EOF;
}


function prune_confirm($tcount, $count, $link, $link_text) {
global $ibforums;
return <<<EOF

<tr>
  <td colspan='2'>
    <table cellpadding='2' cellspacing='1' border='0' width='100%' class='fancyborder' align='center'>
     <tr>
       <td><span class='pagetitle'>{$ibforums->lang['cp_check_result']}</span>
           <br>{$ibforums->lang['cp_check_text']}
       </td>
     </tr>
	  <tr>
	    <td><b>{$ibforums->lang['cp_total_topics']}</b></td>
	    <td>$tcount</td>
	  </tr>
	  <tr>
	    <td><b><span style='color:red'>{$ibforums->lang['cp_total_match']}</span></b></td>
	    <td><span style='color:red'>$count</span></td>
	  </tr>
	  <tr>
	    <td colspan='2' align='center' class='row2'><b><a href='{$ibforums->base_url}$link'>$link_text</a></b></td>
	  </tr>
	 </table>
   </td>
  </tr>

EOF;
}

function prune_splash($forum, $forums, $select) {
global $ibforums;
return <<<EOF

 <form name='ibform' action='{$ibforums->base_url}' method='POST'>
 <input type='hidden' name='s' value='{$ibforums->session_id}'>
 <input type='hidden' name='act' value='ModCP'>
 <input type='hidden' name='CODE' value='prune'>
 <input type='hidden' name='f' value='{$forum['id']}'>
 <input type='hidden' name='check' value='1'>
 
 <!-- IBF.CONFIRM -->
 
 <tr>
  <td colspan='2' class='pagetitle'>{$ibforums->lang['cp_prune']} {$forum['name']}</td>
 </tr>
 <tr>
  <td colspan='2'>{$ibforums->lang['cp_prune_text']}</td>
 </tr>
 
 <tr>
  <td width='40%' class='row2'>{$ibforums->lang['cp_action']}</td>
  <td class='row2'><select name='df' class='forminput'>$forums</select></td>
 </tr>
 
 <tr>
  <td width='40%'>{$ibforums->lang['cp_prune_days']}</td>
  <td><input type='text' size='40' name='dateline' value='{$ibforums->input['dateline']}' class='forminput'></td>
 </tr>
 
 <tr>
  <td width='40%'>{$ibforums->lang['cp_prune_type']}</td>
  <td>$select</td>
 </tr>
 
 <tr>
  <td width='40%'>{$ibforums->lang['cp_prune_replies']}</td>
  <td><input type='text' size='40' name='posts' value='{$ibforums->input['posts']}' class='forminput'></td>
 </tr>
 
 <tr>
  <td width='40%'>{$ibforums->lang['cp_prune_member']}</td>
  <td><input type='text' size='40' name='member' value='{$ibforums->input['member']}' class='forminput'></td>
 </tr>
 <tr>
  <td colspan='2' align='center'><input type='submit' value='{$ibforums->lang['cp_prune_sub1']}' class='forminput'></td>
 </tr>
 </form>

EOF;
}




function edit_user_form($profile) {
global $ibforums;
return <<<EOF

 <form name='ibform' action='{$ibforums->base_url}' method='POST'>
 <input type='hidden' name='s' value='{$ibforums->session_id}'>
 <input type='hidden' name='act' value='ModCP'>
 <input type='hidden' name='CODE' value='compedit'>
 <input type='hidden' name='f' value='{$ibforums->input['f']}'>
 <input type='hidden' name='memberid' value='{$profile['id']}'>
 <tr>
  <td colspan='2' class='pagetitle'>{$ibforums->lang['cp_edit_user']}: {$profile['name']}</td>
 </tr>
 <tr>
  <td width='40%'>{$ibforums->lang['cp_remove_av']}</td>
  <td><select name='avatar' class='forminput'><option value='0'>{$ibforums->lang['no']}</option><option value='1'>{$ibforums->lang['yes']}</option></select></td>
 </tr>
 
 <tr>
  <td width='40%'>{$ibforums->lang['cp_edit_website']}</td>
  <td><input type='text' size='40' name='website' value='{$profile['website']}' class='forminput'></td>
 </tr>
 
 <tr>
  <td width='40%'>{$ibforums->lang['cp_edit_location']}</td>
  <td><input type='text' size='40' name='location' value='{$profile['location']}' class='forminput'></td>
 </tr>
 
 <tr>
  <td width='40%'>{$ibforums->lang['cp_edit_interests']}</td>
  <td><textarea cols='50' rows='3' name='interests' class='forminput'>{$profile['interests']}</textarea></td>
 </tr>
 
  <tr>
  <td width='40%'>{$ibforums->lang['cp_edit_signature']}</td>
  <td><textarea cols='50' rows='5' name='signature' class='forminput'>{$profile['signature']}</textarea></td>
 </tr>
 
 <tr>
  <td colspan='2' align='center'><input type='submit' value='{$ibforums->lang['cp_find_2_submit']}' class='forminput'></td>
 </tr>
 </form>

EOF;
}


function find_two($select) {
global $ibforums;
return <<<EOF

 <form name='ibform' action='{$ibforums->base_url}' method='POST'>
 <input type='hidden' name='s' value='{$ibforums->session_id}'>
 <input type='hidden' name='act' value='ModCP'>
 <input type='hidden' name='CODE' value='doedituser'>
 <input type='hidden' name='f' value='{$ibforums->input['f']}'>
 <tr>
  <td colspan='2' class='pagetitle'>{$ibforums->lang['cp_edit_user']}</td>
 </tr>
 <tr>
  <td width='40%'>{$ibforums->lang['cp_find_2_user']}</td>
  <td>$select</td>
 </tr>
 <tr>
  <td colspan='2' align='center'><input type='submit' value='{$ibforums->lang['cp_find_2_submit']}' class='forminput'></td>
 </tr>
 </form>

EOF;
}


function find_user() {
global $ibforums;
return <<<EOF

 <form name='ibform' action='{$ibforums->base_url}' method='POST'>
 <input type='hidden' name='s' value='{$ibforums->session_id}'>
 <input type='hidden' name='act' value='ModCP'>
 <input type='hidden' name='CODE' value='dofinduser'>
 <input type='hidden' name='f' value='{$ibforums->input['f']}'>
 <tr>
  <td colspan='2' class='pagetitle'>{$ibforums->lang['cp_edit_user']}</td>
 </tr>
 <tr>
  <td width='40%'>{$ibforums->lang['cp_find_user']}</td>
  <td><input type='text' size='40' name='name' value='' class='forminput'></td>
 </tr>
 <tr>
  <td colspan='2' align='center'><input type='submit' value='{$ibforums->lang['cp_find_submit']}' class='forminput'></td>
 </tr>
 </form>

EOF;
}


function splash($tcount, $pcount, $forum) {
global $ibforums;
return <<<EOF

 <tr>
  <td class='pagetitle'>{$ibforums->lang['cp_welcome']}</td>
 </tr>
 <tr>
  <td>{$ibforums->lang['cp_welcome_text']}</td>
 </tr>
 <tr>
  <td>
    <table cellpadding='2' cellspacing='1' border='0' width='75%' class='fancyborder' align='center'>
	  <tr>
	    <td><b>{$ibforums->lang['cp_mod_in']}</b></td>
	    <td>$forum</td>
	  </tr>
	  <tr>
	    <td><b>{$ibforums->lang['cp_topics_wait']}</b></td>
	    <td>$tcount</td>
	  </tr>
	  <tr>
	    <td><b>{$ibforums->lang['cp_posts_wait']}</b></td>
	    <td>$pcount</td>
	  </tr>
	 </table>
   </td>
  </tr>

EOF;
}







function mod_exp($words) {
global $ibforums;
return <<<EOF



                <tr>
                <td class='row1' colspan='2'>$words</td>
                </tr>


EOF;
}

function end_form($action) {
global $ibforums;
return <<<EOF


                <tr>
                <td class='row2' align='center' colspan='2'>
                <input type="submit" name="submit" value="$action" class='forminput'>
                </td></tr></table>
                </td></tr></table>
                </form>


EOF;
}

function move_form($jhtml, $forum_name) {
global $ibforums;
return <<<EOF


                <tr>
                <td class='row1'>{$ibforums->lang[move_from]} <b>$forum_name</b> {$ibforums->lang[to]}:</td>
                <td class='row1'><select name='move_id' class='forminput'>$jhtml</select></td>
                </tr>
                <tr>
                <td class='row1'><b>{$ibforums->lang['leave_link']}</b></td>
                <td class='row1'>
                  <select name='leave' class='forminput'>
                  <option value='y'  selected>{$ibforums->lang['yes']}</option>
                  <option value='n'>{$ibforums->lang['no']}</option>
                  </select>
                </td>
                </tr>


EOF;
}

function delete_js() {
global $ibforums;
return <<<EOF

          <script language='JavaScript'>
          <!--
          function ValidateForm() {
             document.REPLIER.submit.disabled = true;
             return true;
          }
          //-->
          </script>
          
EOF;
}

function topictitle_fields($title, $desc) {
global $ibforums;
return <<<EOF


                <tr>
                <td class='row1'><b>{$ibforums->lang[edit_f_title]}</b></td>
                <td class='row1'><input type='text' size='40' maxlength='50' name='TopicTitle' value='$title'></td>
                </tr>
                <tr>
                <td class='row1'><b>{$ibforums->lang[edit_f_desc]}</b></td>
                <td class='row1'><input type='text' size='40' maxlength='40' name='TopicDesc' value='$desc'></td>
                </tr>


EOF;
}

function poll_entry($id, $entry) {
global $ibforums;

return <<<EOF

				<tr>
				<td class='row1'><b>{$ibforums->lang['pe_option']} $id</b></td>
                <td class='row1'><input type='text' size='60' maxlength='250' name='POLL_$id' value='$entry'></td>
                </tr>
                
EOF;

}


function poll_select_form() {
global $ibforums;

return <<<EOF

				<tr>
				<td class='row1'><b>{$ibforums->lang['pe_pollonly']} $id</b></td>
                <td class='row1'><select name='pollonly' class='forminput'><option value='0'>{$ibforums->lang['pe_no']}</option><option value='1'>{$ibforums->lang['pe_yes']}</option></select></td>
                </tr>
                
EOF;

}


function table_top($posting_title) {
global $ibforums;
return <<<EOF
	<br>
     <table cellpadding='0' cellspacing='0' border='0' width='<{tbl_width}>' bgcolor='<{tbl_border}>' align='center'>
        <tr>
            <td>
                <table cellpadding='5' cellspacing='1' border='0' width='100%'>
                <tr>
                <td valign='left' colspan='2' class='titlemedium'>$posting_title</td>
                </tr>


EOF;
}


function topic_history($data) {
global $ibforums;
return <<<EOF
	<br>
     <table cellpadding='0' cellspacing='0' border='0' width='<{tbl_width}>' bgcolor='<{tbl_border}>' align='center'>
        <tr>
          <td>
            <table cellpadding='5' cellspacing='1' border='0' width='100%'>
            <tr>
            <td valign='left' colspan='2' class='titlemedium'>{$ibforums->lang['th_title']}</td>
            </tr>
            <tr>
             <td class='row1' width='40%'><b>{$ibforums->lang['th_topic']}</b></td>
             <td class='row1' width='60%'>{$data['th_topic']}</td>
            </tr>
			<tr>
             <td class='row1'><b>{$ibforums->lang['th_desc']}</b></td>
             <td class='row1'>{$data['th_desc']}</td>
            </tr>
            <tr>
             <td class='row1'><b>{$ibforums->lang['th_start_date']}</b></td>
             <td class='row1'>{$data['th_start_date']}</td>
            </tr>
            <tr>
             <td class='row1'><b>{$ibforums->lang['th_start_name']}</b></td>
             <td class='row1'>{$data['th_start_name']}</td>
            </tr>
            <tr>
             <td class='row1'><b>{$ibforums->lang['th_last_date']}</b></td>
             <td class='row1'>{$data['th_last_date']}</td>
            </tr>
            <tr>
             <td class='row1'><b>{$ibforums->lang['th_last_name']}</b></td>
             <td class='row1'>{$data['th_last_name']}</td>
            </tr>
            <tr>
             <td class='row1'><b>{$ibforums->lang['th_avg_post']}</b></td>
             <td class='row1'>{$data['th_avg_post']}</td>
            </tr>
            </table>
           </td>
          </tr>
         </table>
EOF;
}

function mod_log_start() {
global $ibforums;
return <<<EOF
	<br>
     <table cellpadding='0' cellspacing='0' border='0' width='<{tbl_width}>' bgcolor='<{tbl_border}>' align='center'>
        <tr>
          <td>
            <table cellpadding='5' cellspacing='1' border='0' width='100%'>
            <tr>
            <td valign='left' colspan='3' class='titlemedium'>{$ibforums->lang['ml_title']}</td>
            </tr>
            <tr>
             <td class='category' width='30%'><b>{$ibforums->lang['ml_name']}</b></td>
             <td class='category' width='50%'><b>{$ibforums->lang['ml_desc']}</b></td>
             <td class='category' width='20%'><b>{$ibforums->lang['ml_date']}</b></td>
            </tr>

EOF;

}

function mod_log_none() {
global $ibforums;
return <<<EOF
            <tr>
             <td class='row1' colspan='3' align='center'><i>{$data['ml_none']}</i></td>
            </tr>

EOF;

}

function mod_log_row($data) {
global $ibforums;
return <<<EOF
            <tr>
             <td class='row1'>{$data['member']}</td>
             <td class='row1'>{$data['action']}</td>
             <td class='row1'>{$data['date']}</td>
            </tr>

EOF;

}

function mod_log_end() {
global $ibforums;
return <<<EOF
             </table>
           </td>
          </tr>
         </table>

EOF;

}

function forum_jump($data, $menu_extra="") {

global $ibforums;
return <<<EOF

<br>
<table cellpadding='0' cellspacing='0' border='0' width='<{tbl_width}>' align='center'>
	<tr>
		<td align='right'>{$data}</td>
	</tr>
</table>
<br>
EOF;

}



function split_body($jump="") {
global $ibforums;
return <<<EOF


                <tr>
                <td class='row1'><b>{$ibforums->lang['mt_new_title']}</b></td>
                <td class='row1'><input type='text' size='40' maxlength='50' name='title' value=''></td>
                </tr>
                <tr>
                <td class='row1'><b>{$ibforums->lang['mt_new_desc']}</b></td>
                <td class='row1'><input type='text' size='40' maxlength='40' name='desc' value=''></td>
                </tr>
                <tr>
                <td class='row1'>{$ibforums->lang['st_forum']}</td>
                <td class='row1'><select name='fid' class='forminput'>$jump</select></td>
                </tr>
                <tr>
                <td class='row1' colspan='2'>
                 <table width='100%' cellpadding='4' cellspacing='1' border='0' style='border:1px solid <{tbl_border}>'>
                  <tr>
                    <td class='titlemedium'>{$ibforums->lang['st_post']}</td>
                  </tr>


EOF;
}

function split_row($row) {
global $ibforums;
return <<<EOF

				<tr>
				 <td style='border-bottom:1px solid <{tbl_border}>' class='{$row['post_css']}'>{$row['st_top_bit']}
				 <hr noshade size=1 color="<{tbl_border}>">
				 <br>{$row['post']}
				 <br><div align='right'><b>{$ibforums->lang['st_split']}</b>&nbsp;&nbsp;<input type='checkbox' name='post_{$row['pid']}' value='1'></div>
				 </td>
				</tr>


EOF;
}


function split_end_form($action) {
global $ibforums;
return <<<EOF

				</table>
				</td>
                <tr>
                <td class='row2' align='center' colspan='2'>
                <input type="submit" name="submit" value="$action" class='forminput'>
                </td></tr></table>
                </td></tr></table>
                </form>


EOF;
}

function merge_body($title="", $desc="") {
global $ibforums;
return <<<EOF


                <tr>
                <td class='row1'><b>{$ibforums->lang['mt_new_title']}</b></td>
                <td class='row1'><input type='text' size='40' maxlength='50' name='title' value='$title'></td>
                </tr>
                <tr>
                <td class='row1'><b>{$ibforums->lang['mt_new_desc']}</b></td>
                <td class='row1'><input type='text' size='40' maxlength='40' name='desc' value='$desc'></td>
                </tr>
                <tr>
                <td class='row1'>{$ibforums->lang['mt_tid']}</td>
                <td class='row1'><input type='text' size='80' name='topic_url' value=''></td>
                </tr>


EOF;
}


	} // end class

?>