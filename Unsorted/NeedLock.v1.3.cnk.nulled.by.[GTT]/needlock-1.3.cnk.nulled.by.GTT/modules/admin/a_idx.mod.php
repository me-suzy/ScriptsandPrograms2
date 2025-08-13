<?php

/*
+--------------------------------------------------------------------------
|   > $$A_IDX.MOD.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

$obj = new Idx();

class Idx {

   function Idx() {
     global $needsecure,$std,$sess,$DB,$tpl,$INFO;

     $tpl->load_file("idx.tpl","main");

     $tpl->set_var("page_title","{$INFO['SITE_NAME']} :: {$needsecure->words['page_title']}");
     $tpl->set_var("base_url",$needsecure->base_url);
     $tpl->set_var("img_url",$INFO['IMG_URL']);
     $tpl->set_var("session_id",$needsecure->session_id);

     $tpl->set_var("name",$needsecure->admin['name']);

     $tpl->set_var("words_welcome",$needsecure->words['welcome']);
     $tpl->set_var("words_logout",$needsecure->words['logout']);
     $tpl->set_var("words_fields_not_filled",$needsecure->words['fields_not_filled']);

     // Admin menu:

        $tpl->set_var("words_part_system_info",$needsecure->words['part_system_info']);
        $tpl->set_var("words_part_global_setup",$needsecure->words['part_global_setup']);
        $tpl->set_var("words_subpart_global_setup_status",$needsecure->words['subpart_global_setup_status']);
        $tpl->set_var("words_subpart_global_setup_global",$needsecure->words['subpart_global_setup_global']);
        $tpl->set_var("words_subpart_global_setup_sql",$needsecure->words['subpart_global_setup_sql']);
        $tpl->set_var("words_subpart_global_setup_email",$needsecure->words['subpart_global_setup_email']);
        $tpl->set_var("words_subpart_global_setup_users",$needsecure->words['subpart_global_setup_users']);
	    $tpl->set_var("words_subpart_global_setup_dirs",$needsecure->words['subpart_global_setup_dirs']);
        $tpl->set_var("words_part_anouncements",$needsecure->words['part_anouncements']);
	    $tpl->set_var("words_subpart_anouncements_list",$needsecure->words['subpart_anouncements_list']);
        $tpl->set_var("words_subpart_anouncements_new",$needsecure->words['subpart_anouncements_new']);
        $tpl->set_var("words_part_user_control",$needsecure->words['part_user_control']);
        $tpl->set_var("words_subpart_user_control_search",$needsecure->words['subpart_user_control_search']);
        $tpl->set_var("words_subpart_user_control_prereg",$needsecure->words['subpart_user_control_prereg']);
        $tpl->set_var("words_subpart_user_control_approve",$needsecure->words['subpart_user_control_approve']);
        $tpl->set_var("words_subpart_user_control_unsuspend",$needsecure->words['subpart_user_control_unsuspend']);
        $tpl->set_var("words_subpart_user_control_unban",$needsecure->words['subpart_user_control_unban']);
        $tpl->set_var("words_part_admins_control",$needsecure->words['part_admins_control']);
        $tpl->set_var("words_subpart_admins_control_list",$needsecure->words['subpart_admins_control_list']);
        $tpl->set_var("words_subpart_admins_control_create",$needsecure->words['subpart_admins_control_create']);
        $tpl->set_var("words_part_backups",$needsecure->words['part_backups']);
        $tpl->set_var("words_subpart_backups_create",$needsecure->words['subpart_backups_create']);
        $tpl->set_var("words_subpart_backups_restore",$needsecure->words['subpart_backups_restore']);
        $tpl->set_var("words_part_statistic",$needsecure->words['part_statistic']);
        $tpl->set_var("words_part_admin_logs",$needsecure->words['part_admin_logs']);


     // Redfine menu for different levels:
     if ( $needsecure->admin['level'] == 1 ) {
        $tpl->set_var("MenuLevel_2",false);
	    $tpl->set_var("MenuLevel_3",false);
	    $tpl->set_var("MenuLevel_4",false);
	    $tpl->set_var("MenuLevel_5",false);
     } elseif ( $needsecure->admin['level'] == 2 ) {
        $tpl->set_var("MenuLevel_1",false);
	    $tpl->set_var("MenuLevel_3",false);
	    $tpl->set_var("MenuLevel_4",false);
	    $tpl->set_var("MenuLevel_5",false);
     } elseif ( $needsecure->admin['level'] == 3 ) {
        $tpl->set_var("MenuLevel_1",false);
	    $tpl->set_var("MenuLevel_2",false);
	    $tpl->set_var("MenuLevel_4",false);
	    $tpl->set_var("MenuLevel_5",false);
     } elseif ( $needsecure->admin['level'] == 4 ) {
        $tpl->set_var("MenuLevel_1",false);
	    $tpl->set_var("MenuLevel_2",false);
	    $tpl->set_var("MenuLevel_3",false);
	    $tpl->set_var("MenuLevel_5",false);
     } elseif ( $needsecure->admin['level'] == 5 ) {
        $tpl->set_var("MenuLevel_1",false);
	    $tpl->set_var("MenuLevel_2",false);
	    $tpl->set_var("MenuLevel_3",false);
	    $tpl->set_var("MenuLevel_4",false);
     } else {
        $tpl->set_var("MenuLevel_1",false);
	    $tpl->set_var("MenuLevel_2",false);
	    $tpl->set_var("MenuLevel_3",false);
	    $tpl->set_var("MenuLevel_4",false);
	    $tpl->set_var("MenuLevel_5",false);
     }



     switch ( $needsecure->input['code'] ) {
     case '00':
//    $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
	  $tpl->set_var("AdminLogs",false);
	  $this->systemInfo();
	break;
	case '01':
      $tpl->set_var("SystemInfo",false);
//	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
	  $tpl->set_var("AdminLogs",false);
	  if ( $needsecure->admin['level'] == 1 ) {
             $this->systemConfig();
	  } else {
	     $std->Error("You don't have permissions to access System Configuration");
	  }
	break;
	case '02':
	  $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
//	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
	  $tpl->set_var("AdminLogs",false);
	  if ( ($needsecure->admin['level'] == 1) or ($needsecure->admin['level'] == 2) or ($needsecure->admin['level'] == 3) ) {
             $this->systemAnounces();
	  } else {
	     $std->Error("You don't have permissions to access Anouncement System");
	  }
	break;
	case '03':
      $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
//	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
	  $tpl->set_var("AdminLogs",false);
      if ( ($needsecure->admin['level'] == 1) or ($needsecure->admin['level'] == 2) ) {
             $this->usersManagement();
      } else {
        $std->Error("You don't have permissions to access User's Management");
      }
	break;
	case '04':
      $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
//	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
	  $tpl->set_var("AdminLogs",false);
      if ( $needsecure->admin['level'] == 1 ) {
             $this->adminsManagement();
	  } else {
	     $std->Error("You don't have permissions to access Admin's Management");
	  }
	break;
	case '05':
      $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
//	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
	  $tpl->set_var("AdminLogs",false);
      if ( ($needsecure->admin['level'] == 1) or ($needsecure->admin['level'] == 2) or ($needsecure->admin['level'] == 3) or ($needsecure->admin['level'] == 4) ) {
          $this->systemBackup();
      } else {
	     $std->Error("You don't have permissions to access Backup System");
	  }
	break;
	case '06':
          $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("AdminLogs",false);
//	  $tpl->set_var("SystemStat",false);
          $this->systemStat();
	break;
        case '07':
          $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
//        $tpl->set_var("AdminLogs",false);
          if ( $needsecure->admin['level'] == 1 ) {
             $this->adminLogs();
          } else {
	     $std->Error("You don't have permissions to access Admin Logs");
	  }

	break;
      default:
//        $tpl->set_var("SystemInfo",false);
	  $tpl->set_var("SystemConfig",false);
	  $tpl->set_var("SystemAnounces",false);
	  $tpl->set_var("UsersManagement",false);
	  $tpl->set_var("AdminsManagement",false);
	  $tpl->set_var("SystemBackup",false);
	  $tpl->set_var("SystemStat",false);
	  $tpl->set_var("AdminLogs",false);
	  $this->systemInfo();
	break;
     }

     $tpl->pparse("main",false);

   }

   function systemInfo() {
     global $needsecure,$std,$tpl,$DB,$INFO;

     // Pars words
     $tpl->set_var("words_sysinfo_header",$needsecure->words['sysinfo_header']);
     $tpl->set_var("words_server_load",$needsecure->words['server_load']);
     $tpl->set_var("words_system_status",$needsecure->words['system_status']);
     $tpl->set_var("words_signup_status",$needsecure->words['signup_status']);
     $tpl->set_var("words_total_users",$needsecure->words['total_users']);
     $tpl->set_var("words_not_activated",$needsecure->words['users_not_activated']);
     $tpl->set_var("words_not_approved",$needsecure->words['users_not_approved']);
     $tpl->set_var("words_suspended",$needsecure->words['users_suspended']);

     /*
       @ Define values
     */

     // System status
     if ( $INFO['IS_OFFLINE'] == 0 ) {
        $is_online = "<span style='color: green;'>{$needsecure->words['system_status_online']}</span>";
     } elseif ( $INFO[''] == 1 ) {
        $is_online = "<span style='color: red;'>{$needsecure->words['system_status_offline']}</span>";
     } else {
        $is_online = "<span style='color: gray;'>{$needsecure->words['system_status_unknown']}</span>";
     }

     // Registrations status
     if ( $INFO['NEW_REGS_ALLOW'] == 1 ) {
        $new_regs = "<span style='color: green;'>{$needsecure->words['new_regs_allow']}</span>";
     } elseif ( $INFO['NEW_REGS_ALLOW'] == 0 ) {
        $new_regs = "<span style='color: red;'>{$needsecure->words['new_regs_disallow']}</span>";
     } else {
        $new_regs = "<span style='color: gray;'>{$needsecure->words['new_regs_unknown']}</span>";
     }



     $DB->query("SELECT id,suspended,approved,authcode FROM ns_members");
     $total = 0;
     $suspended = 0;
     $not_approved = 0;
     $not_activated = 0;
     while ( $member = $DB->fetch_row() ) {
        $total++;
	    if ( $member['suspended'] == 1 ) {
	       $suspended++;
	    }
	    if ( $member['approved'] != 1 ) {
	       $not_approved++;
	    }
	    if ( !empty($member['authcode']) ) {
	       $not_activated++;
	    }
     }


     // Parse values
     $tpl->set_var("server_load",$std->getServerLoad());
     $tpl->set_var("IS_OFFLINE",$is_online);
     $tpl->set_var("NEW_REGS_ALLOW",$new_regs);
     $tpl->set_var("total_users",$total);
     $tpl->set_var("not_activated_users",$not_activated);
     $tpl->set_var("not_approved_users",$not_approved);
     $tpl->set_var("suspended_users",$suspended);

   }

   function systemConfig() {
     global $needsecure,$std,$tpl,$DB,$INFO,$_SERVER,$mailer;

     require ("{$needsecure->dirs['INC']}htaccess.class{$INFO['PHP_EXT']}");
	 $ht = new htaccess;

     if ( $needsecure->input['step'] == 'protect' ) {

        if ( is_writable( $needsecure->input['dir'] ) ) {
		   $ht->setUseDefaultUser($INFO['DEFAULT_HTA_USED']);
           $ht->setHtaccess( $needsecure->input['dir'] . "/.htaccess" );
           $ht->setHtpasswd( $needsecure->input['dir'] . "/.htpasswd" );
           $ht->ProtectDir();
        } else {
           $std->Error("Directory doesn't exists or not writeable. Please, check if directory exists and set permissions to 0777 for it.");
        }

        $url = "{$needsecure->base_url}&act=idx&code=01&part=dirs";
        @flush();
        $std->redirectPage($url, "Directory protected.");

     } elseif ( $needsecure->input['step'] == 'unprotect' ) {

        $input_dir_parts = pathinfo($needsecure->input['dir']);
        $proceed_dir_name = $input_dir_parts['basename'];

        if ( is_writable( $needsecure->input['dir'] ) ) {
           $ht->setHtaccess( $needsecure->input['dir'] . "/.htaccess" );
           $ht->setHtpasswd( $needsecure->input['dir'] . "/.htpasswd" );
           $check = $ht->UnProtectDir();

            if ( $check ) {
             // We need to remove this directory from all users access
             $DB->query("SELECT id,name,email,access_dirs FROM ns_members");
             $x=0;
               while ( $member = $DB->fetch_row() ) {
                 $member_dirs = explode("|",$member['access_dirs']);

                    for ( $i=0; $i<count($member_dirs); $i++) {

                       if ( $proceed_dir_name == $member_dirs[$i] ) {
                          next;
                       } elseif ( $proceed_dir_name != $member_dirs[$i] ) {
                          $new_member_dirs[] = $member_dirs[$i];
                       }

                    }
                  if ( count($new_member_dirs) > 0 ) {
                     $new_dirs = implode("|",$new_member_dirs);
                  } else {
                     $new_dirs = '';
                  }

				  $upd_members_list[$x]['id'] = $member['id'];
                  $upd_members_list[$x]['name'] = $member['name'];
                  $upd_members_list[$x]['email'] = $member['email'];
                  $upd_members_list[$x]['access_dirs'] = $new_dirs;

                  $x++;

                  unset($member);
                  unset($member_dirs);
                  unset($new_member_dirs);
                  unset($new_dirs);

               }

               for ( $i=0;$i<count($upd_members_list); $i++ ) {
                   $DB->query("UPDATE ns_members SET access_dirs='{$upd_members_list[$i]['access_dirs']}' WHERE id='{$upd_members_list[$i]['id']}'");
               }
            }

        } else {
           $std->Error("Directory doesn't exists or not writeable. Please, check if directory exists and set permissions to 0777 for it.");
        }

        $url = "{$needsecure->base_url}&act=idx&code=01&part=dirs";
        @flush();
        $std->redirectPage($url, "Directory unprotected. All members updated");

     } elseif ( $needsecure->input['step'] == 'proceed' ) {

       // first, create backup
       if ( file_exists("{$INFO['BASE_PATH']}inf.php.bak") ) {
          @unlink("{$INFO['BASE_PATH']}inf.php.bak");
       }
       @rename( "{$INFO['BASE_PATH']}inf.php", "{$INFO['BASE_PATH']}inf.php.bak" );
       @chmod("{$INFO['BASE_PATH']}inf.php.bak", 0666 );

       if ( $needsecure->input['part'] == 'status' ) {
          $new_info['IS_OFFLINE'] = $needsecure->input['IS_OFFLINE'];
          $new_info['OFFLINE_MSG'] = $needsecure->input['OFFLINE_MSG'];
          // Some replacements :)
          $new_info['OFFLINE_MSG'] = preg_replace("/\n/","<br>",$new_info['OFFLINE_MSG']);
		  $new_info['OFFLINE_MSG'] = preg_replace("/'/","&#39;",$new_info['OFFLINE_MSG']);
          $new_info['OFFLINE_MSG'] = preg_replace('/"/',"&#34;",$new_info['OFFLINE_MSG']);
       } elseif ( $needsecure->input['part'] == 'global' ) {
          $new_info['SITE_NAME'] = $needsecure->input['SITE_NAME'];
          $new_info['BASE_URL'] = $needsecure->input['BASE_URL'];
          $new_info['BASE_PATH'] = $needsecure->input['BASE_PATH'];
          $new_info['IMG_URL'] = $needsecure->input['IMG_URL'];
          $new_info['IMG_PATH'] = $needsecure->input['IMG_PATH'];
          $new_info['HTA_TOP_DIR'] = $needsecure->input['HTA_TOP_DIR'];
          $new_info['PHP_EXT'] = $needsecure->input['PHP_EXT'];
          $new_info['cookie_id'] = $needsecure->input['cookie_id'];
          $new_info['cookie_domain'] = $needsecure->input['cookie_domain'];
          $new_info['cookie_path'] = $needsecure->input['cookie_path'];
       } elseif ( $needsecure->input['part'] == 'sql' ) {
          $new_info['SQL_DRIVER'] = $needsecure->input['SQL_DRIVER'];
          $new_info['SQL_HOST'] = $needsecure->input['SQL_HOST'];
          $new_info['SQL_PORT'] = $needsecure->input['SQL_PORT'];
          $new_info['SQL_USER'] = $needsecure->input['SQL_USER'];
          $new_info['SQL_PSWD'] = $needsecure->input['SQL_PSWD'];
          $new_info['SQL_NAME'] = $needsecure->input['SQL_NAME'];
          $new_info['SQL_PREFIX'] = $needsecure->input['SQL_PREFIX'];
       } elseif ( $needsecure->input['part'] == 'email' ) {
          $new_info['EMAIL_SENDER'] = $needsecure->input['EMAIL_SENDER'];
          $new_info['ADMIN_EMAIL'] = $needsecure->input['ADMIN_EMAIL'];
          $new_info['EMAIL_IN'] = $needsecure->input['EMAIL_IN'];
          $new_info['EMAIL_OUT'] = $needsecure->input['EMAIL_OUT'];
          $new_info['EMAIL_FORMAT'] = $needsecure->input['EMAIL_FORMAT'];
          $new_info['EMAIL_CHARSET'] = $needsecure->input['EMAIL_CHARSET'];
       } elseif ( $needsecure->input['part'] == 'users' ) {
          $new_info['NEW_REGS_ALLOW'] = $needsecure->input['NEW_REGS_ALLOW'];
          $new_info['REG_AUTH_CODES'] = $needsecure->input['REG_AUTH_CODES'];
          $new_info['NOTIFY_NEW_REGS'] = $needsecure->input['NOTIFY_NEW_REGS'];
          $new_info['REG_ADMIN_APPROVE'] = $needsecure->input['REG_ADMIN_APPROVE'];
	      $new_info['SESS_EXPIRE'] = $needsecure->input['SESS_EXPIRE'];
          $new_info['DEFAULT_HTA_USED'] = $needsecure->input['DEFAULT_HTA_USED'];
          $new_info['DEFAULT_HTA_USER'] = $needsecure->input['DEFAULT_HTA_USER'];
          $new_info['DEFAULT_HTA_PASS'] = $needsecure->input['DEFAULT_HTA_PASS'];
          $new_logins_decline = $needsecure->input['logins_decline'];
          $ffh = @fopen("{$INFO['BATH_PATH']}denied_logins.dat", "w" );
          @fputs( $ffh, trim($new_logins_decline) );
          @fclose($ffh);

       }

       // Now update our config
       $std->saveConfig($new_info);

       /*
        @ Write admin log
        @
       */
       $std->writeAdminLog("Global system configuration updated. Old configuration backuped.");


       // Done, redirect back to sysconf page
       $url = "{$needsecure->base_url}&act=idx&code=01&part={$needsecure->input['part']}";
       @flush();
       $std->redirectPage($url, "Configuration updated");

     } else {

       $tpl->set_var("words_save_config",$needsecure->words['save_config']);

       if ( $needsecure->input['part'] == 'dirs' ) {
          $tpl->set_var("GlobalConfig",false);
          $tpl->set_var("StatusConfig",false);
	      $tpl->set_var("DatabaseConfig",false);
	      $tpl->set_var("EmailConfig",false);
	      $tpl->set_var("UsersConfig",false);


          $tpl->set_var("words_protection",$needsecure->words['protection']);
          $tpl->set_var("words_directory_protect_unprotect",$needsecure->words['directory_protect_unprotect']);

          $tpl->set_var("words_protected_dirs",$needsecure->words['protected_dirs']);
          $tpl->set_var("words_notprotected_dirs",$needsecure->words['notprotected_dirs']);
          $tpl->set_var("words_protect",$needsecure->words['protect']);
          $tpl->set_var("words_unprotect",$needsecure->words['unprotect']);

          require ("{$needsecure->dirs['INC']}directory.class{$INFO['PHP_EXT']}");
           $d = new dir;
           $d->get_dirs($INFO['HTA_TOP_DIR']);
	       $hta_protected_opts = "";
           $hta_unprotected_opts = "";
           $path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
           $needsecure_dir = $path_parts["dirname"];

           for ($i=0;$i<count($d->dirs);$i++) {
             if ( file_exists( $d->dirs[$i] . "/.htaccess" ) && file_exists( $d->dirs[$i] . "/.htpasswd" ) && ( $d->dirs[$i] != $needsecure_dir ) ) {
               $hta_protected_opts .= "\t<option value='{$d->dirs[$i]}'>{$d->dirs[$i]}</option>\n";
             } elseif ( !file_exists( $d->dirs[$i] . "/.htaccess" ) && !file_exists( $d->dirs[$i] . "/.htpasswd" ) && ( $d->dirs[$i] != $needsecure_dir ) ) {
                $hta_unprotected_opts .= "\t<option value='{$d->dirs[$i]}'>{$d->dirs[$i]}</option>\n";
             } else {
               next;
            }
	      }

          if ( $hta_protected_opts == '' ) {
             $hta_protected_opts = "\t<option value=''>{$needsecure->words['no_protected_dirs']}</option>\n";
             $tpl->set_var("protected_disabled","disabled");
          }

          if ( $hta_unprotected_opts == '' ) {
             $hta_unprotected_opts = "\t<option value=''>{$needsecure->words['no_unprotected_dirs']}</option>\n";
             $tpl->set_var("unprotected_disabled","disabled");
          }

          $tpl->set_var("hta_protected_opts",$hta_protected_opts);
          $tpl->set_var("hta_unprotected_opts",$hta_unprotected_opts);


       } elseif ( $needsecure->input['part'] == 'global' ) {

           $tpl->set_var("StatusConfig",false);
	       $tpl->set_var("DatabaseConfig",false);
	       $tpl->set_var("EmailConfig",false);
	       $tpl->set_var("UsersConfig",false);
           $tpl->set_var("DirProtection",false);


	       $tpl->set_var("words_global_settings",$needsecure->words['global_settings']);
	       $tpl->set_var("words_pathes",$needsecure->words['pathes']);
	       $tpl->set_var("words_cookies",$needsecure->words['cookies']);

           $tpl->set_var("words_site_name",$needsecure->words['site_name']);
           $tpl->set_var("SITE_NAME",$INFO['SITE_NAME']);
           $tpl->set_var("words_base_url",$needsecure->words['base_url']);
           $tpl->set_var("BASE_URL",$INFO['BASE_URL']);
           $tpl->set_var("words_base_path",$needsecure->words['base_path']);
           $tpl->set_var("BASE_PATH",$INFO['BASE_PATH']);
           $tpl->set_var("words_img_url",$needsecure->words['img_url']);
           $tpl->set_var("IMG_URL",$INFO['IMG_URL']);
           $tpl->set_var("words_img_path",$needsecure->words['img_path']);
           $tpl->set_var("IMG_PATH",$INFO['IMG_PATH']);
           $tpl->set_var("words_php_ext",$needsecure->words['php_ext']);
           $tpl->set_var("PHP_EXT",$INFO['PHP_EXT']);
	       $tpl->set_var("words_cookie_id",$needsecure->words['cookie_id']);
           $tpl->set_var("cookie_id",$INFO['cookie_id']);
	       $tpl->set_var("words_cookie_domain",$needsecure->words['cookie_domain']);
           $tpl->set_var("cookie_domain",$INFO['cookie_domain']);
	       $tpl->set_var("words_cookie_path",$needsecure->words['cookie_path']);
           $tpl->set_var("cookie_path",$INFO['cookie_path']);

	   // Compile HTA_OPTS
           $tpl->set_var("words_hta_top_dir",$needsecure->words['hta_top_dir']);

           require ("{$needsecure->dirs['INC']}directory.class{$INFO['PHP_EXT']}");
           $d = new dir;
           $d->get_dirs($_SERVER['DOCUMENT_ROOT']);
	       $hta_opts = "\n";
           $hta_opts .= "\t<option value='{$_SERVER['DOCUMENT_ROOT']}'>{$_SERVER['DOCUMENT_ROOT']}</option>\n";

           $path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
           $needsecure_dir = $path_parts["dirname"];

           for ($i=0;$i<count($d->dirs);$i++) {
             if ( $d->dirs[$i] == $needsecure_dir ) {
                next;
             } else {
               if ( $d->dirs[$i] == $INFO['HTA_TOP_DIR'] ) {
                  $selector = "selected";
	           } else {
                  $selector = "";
	           }
	           $hta_opts .= "\t<option value='{$d->dirs[$i]}' {$selector}>{$d->dirs[$i]}</option>\n";
            }
	      }

	       $hta_opts .= "\n";

	   $tpl->set_var("HTA_OPTS",$hta_opts);

       } elseif ( $needsecure->input['part'] == 'status' ) {

       $tpl->set_var("GlobalConfig",false);
	   $tpl->set_var("DatabaseConfig",false);
	   $tpl->set_var("EmailConfig",false);
	   $tpl->set_var("UsersConfig",false);
       $tpl->set_var("DirProtection",false);

	   $tpl->set_var("words_status_settings",$needsecure->words['status_settings']);
	   $tpl->set_var("words_sys_status",$needsecure->words['sys_status']);
	   $tpl->set_var("words_sys_status_off_msg",$needsecure->words['sys_status_off_msg']);

	   if ( $INFO['IS_OFFLINE'] == '1' ) {
              $checked_offline = "selected";
	   } else {
              $checked_online = "selected";
	   }

	   $sys_status_opts = "
       \t<option value='1' {$checked_offline}>{$needsecure->words['sys_status_offline']}</option>\n
	   \t<option value='0' {$checked_online}>{$needsecure->words['sys_status_online']}</option>\n
	   ";

	   $tpl->set_var("sys_status_opts",$sys_status_opts);
       $tpl->set_var("OFFLINE_MSG",$INFO['OFFLINE_MSG']);

       } elseif ( $needsecure->input['part'] == 'sql' ) {

       $tpl->set_var("GlobalConfig",false);
	   $tpl->set_var("StatusConfig",false);
	   $tpl->set_var("EmailConfig",false);
	   $tpl->set_var("UsersConfig",false);
       $tpl->set_var("DirProtection",false);

       $tpl->set_var("words_sql_settings",$needsecure->words['sql_settings']);
	   $tpl->set_var("words_database_set_up",$needsecure->words['database_set_up']);
	   $tpl->set_var("words_sql_driver",$needsecure->words['sql_driver']);

        $sql_drivers = explode( "|" , $INFO['SUPPORTED_SQLS'] );
	    for ($i=0;$i<count($sql_drivers);$i++) {
               $SQL_DRIVER_OPTS .= "\t<option alue='{$sql_drivers[$i]}'>{$sql_drivers[$i]}</option>\n";
	    }
       $tpl->set_var("SQL_DRIVER_OPTS",$SQL_DRIVER_OPTS);
	   $tpl->set_var("words_sql_host",$needsecure->words['sql_host']);
	   $tpl->set_var("SQL_HOST",$INFO['SQL_HOST']);
	   $tpl->set_var("words_sql_port",$needsecure->words['sql_port']);
       $tpl->set_var("SQL_PORT",$INFO['SQL_PORT']);
	   $tpl->set_var("words_sql_user",$needsecure->words['sql_user']);
	   $tpl->set_var("SQL_USER",$INFO['SQL_USER']);
	   $tpl->set_var("words_sql_pswd",$needsecure->words['sql_pswd']);
       $tpl->set_var("SQL_PSWD",$INFO['SQL_PSWD']);
	   $tpl->set_var("words_sql_name",$needsecure->words['sql_name']);
	   $tpl->set_var("SQL_NAME",$INFO['SQL_NAME']);
	   $tpl->set_var("words_sql_prefix",$needsecure->words['sql_prefix']);
	   $tpl->set_var("SQL_PREFIX",$INFO['SQL_PREFIX']);

       } elseif ( $needsecure->input['part'] == 'email' ) {

       $tpl->set_var("GlobalConfig",false);
	   $tpl->set_var("StatusConfig",false);
	   $tpl->set_var("DatabaseConfig",false);
	   $tpl->set_var("UsersConfig",false);
       $tpl->set_var("DirProtection",false);

       $tpl->set_var("words_email_settings",$needsecure->words['email_settings']);
	   $tpl->set_var("words_email_set_up",$needsecure->words['email_set_up']);

	   $tpl->set_var("words_email_sender",$needsecure->words['email_sender']);
       $EMAIL_SENDER_OPTS = "\t<option value='mail'>php mail()</option>\n";
	   $tpl->set_var("EMAIL_SENDER_OPTS",$EMAIL_SENDER_OPTS);
	   $tpl->set_var("words_admin_email",$needsecure->words['admin_email']);
	   $tpl->set_var("ADMIN_EMAIL",$INFO['ADMIN_EMAIL']);
	   $tpl->set_var("words_email_in",$needsecure->words['email_in']);
       $tpl->set_var("EMAIL_IN",$INFO['EMAIL_IN']);
	   $tpl->set_var("words_email_out",$needsecure->words['email_out']);
	   $tpl->set_var("EMAIL_OUT",$INFO['EMAIL_OUT']);
	   $tpl->set_var("words_email_format",$needsecure->words['email_format']);
       if ( $INFO['EMAIL_FORMAT'] == 'text/plain' ) {
              $selected_plain = "selected";
	   } elseif ( $INFO['EMAIL_FORMAT'] == 'text/html' ) {
              $selected_html = "selected";
	   }
	   $EMAIL_FORMAT_OPTS = "
       \t<option value='text/plain' {$selected_plain}>text/plain</option>\n
	   \t<option value='text/html' {$selected_html}>text/html</option>\n
	   ";
       $tpl->set_var("EMAIL_FORMAT_OPTS",$EMAIL_FORMAT_OPTS);
	   $tpl->set_var("words_email_charset",$needsecure->words['email_charset']);
	   $tpl->set_var("EMAIL_CHARSET",$INFO['EMAIL_CHARSET']);

       } elseif ( $needsecure->input['part'] == 'users' ) {

       $tpl->set_var("GlobalConfig",false);
	   $tpl->set_var("StatusConfig",false);
	   $tpl->set_var("DatabaseConfig",false);
       $tpl->set_var("EmailConfig",false);
       $tpl->set_var("DirProtection",false);

       $tpl->set_var("words_users_settings",$needsecure->words['users_settings']);
	   $tpl->set_var("words_users_set_up",$needsecure->words['users_set_up']);

	   $tpl->set_var("words_is_regs_allowed",$needsecure->words['is_regs_allowed']);
	   $tpl->set_var("words_use_authcodes",$needsecure->words['use_authcodes']);
	   $tpl->set_var("words_regs_notify",$needsecure->words['regs_notify']);
	   $tpl->set_var("words_regs_approve",$needsecure->words['regs_approve']);
       $tpl->set_var("words_sess_expire",$needsecure->words['sess_expire']);

       $tpl->set_var("words_default_hta_used",$needsecure->words['default_hta_used']);
       if ( $INFO['DEFAULT_HTA_USED'] == 1 ) {
              $checked_yes = "selected";
	   } else {
              $checked_no  = "selected";
	   }
	   $DEFAULT_HTA_USED_OPTS = "
       \t<option value='1' {$checked_yes}>{$needsecure->words['yes']}</option>\n
	   \t<option value='0' {$checked_no}>{$needsecure->words['no']}</option>\n
	   ";
       $tpl->set_var("DEFAULT_HTA_USED_OPTS",$DEFAULT_HTA_USED_OPTS);
       $tpl->set_var("words_default_hta_user",$needsecure->words['default_hta_user']);
       $tpl->set_var("DEFAULT_HTA_USER",$INFO['DEFAULT_HTA_USER']);
       $tpl->set_var("words_default_hta_pass",$needsecure->words['default_hta_pass']);
       $tpl->set_var("DEFAULT_HTA_PASS",$INFO['DEFAULT_HTA_PASS']);
	   $tpl->set_var("words_logins_decline",$needsecure->words['logins_decline']);

       $tpl->set_var("SESS_EXPIRE",$INFO['SESS_EXPIRE']);

       if ( $INFO['NEW_REGS_ALLOW'] == 1 ) {
              $checked_yes = "selected";
	   } else {
              $checked_no  = "selected";
	   }
	   $NEW_REGS_ALLOW_OPTS = "
       \t<option value='1' {$checked_yes}>{$needsecure->words['yes']}</option>\n
	   \t<option value='0' {$checked_no}>{$needsecure->words['no']}</option>\n
	   ";
       $tpl->set_var("NEW_REGS_ALLOW_OPTS",$NEW_REGS_ALLOW_OPTS);
       unset($checked_yes);
	   unset($checked_no);

	   if ( $INFO['REG_AUTH_CODES'] == 1 ) {
              $checked_yes = "selected";
	   } else {
              $checked_no  = "selected";
	   }
	   $REG_AUTH_CODES_OPTS = "
       \t<option value='1' {$checked_yes}>{$needsecure->words['yes']}</option>\n
	   \t<option value='0' {$checked_no}>{$needsecure->words['no']}</option>\n
	   ";
	   $tpl->set_var("REG_AUTH_CODES_OPTS",$REG_AUTH_CODES_OPTS);
	   unset($checked_yes);
	   unset($checked_no);

       if ( $INFO['NOTIFY_NEW_REGS'] == 1 ) {
              $checked_yes = "selected";
	   } else {
              $checked_no  = "selected";
	   }
	   $NOTIFY_NEW_REGS_OPTS = "
       \t<option value='1' {$checked_yes}>{$needsecure->words['yes']}</option>\n
	   \t<option value='0' {$checked_no}>{$needsecure->words['no']}</option>\n
	   ";
	   $tpl->set_var("NOTIFY_NEW_REGS_OPTS",$NOTIFY_NEW_REGS_OPTS);
	   unset($checked_yes);
	   unset($checked_no);

       if ( $INFO['REG_ADMIN_APPROVE'] == 1 ) {
              $checked_yes = "selected";
	   } else {
              $checked_no  = "selected";
	   }
	   $REG_ADMIN_APPROVE_OPTS = "
       \t<option value='1' {$checked_yes}>{$needsecure->words['yes']}</option>\n
	   \t<option value='0' {$checked_no}>{$needsecure->words['no']}</option>\n
	   ";
       $tpl->set_var("REG_ADMIN_APPROVE_OPTS",$REG_ADMIN_APPROVE_OPTS);
	   unset($checked_yes);
	   unset($checked_no);

       $fh = @fopen("{$INFO['BATH_PATH']}denied_logins.dat", "r");
       $logins_decline = trim( @fgets($fh) );
	   @fclose($fh);
	   $tpl->set_var("logins_decline",$logins_decline);
       }
     }
   }

   function systemAnounces() {
     global $needsecure,$std,$tpl,$DB,$INFO,$mailer;

      $tpl->set_var("words_anounces_header",$needsecure->words['anounces_header']);
      $tpl->set_var("words_add_anounce_link",$needsecure->words['add_anounce_link']);
      $tpl->set_var("words_anounce_date",$needsecure->words['anounce_date']);
      $tpl->set_var("words_anounce_header",$needsecure->words['anounce_header']);
      $tpl->set_var("words_anounces_subheader",$needsecure->words['anounces_subheader']);
      $tpl->set_var("words_anounce_body",$needsecure->words['anounce_body']);
      $tpl->set_var("words_delete_anounce",$needsecure->words['delete_anounce']);
      $tpl->set_var("words_spam_anounce",$needsecure->words['spam_anounce']);
      $tpl->set_var("words_edit",$needsecure->words['edit']);
      $tpl->set_var("words_delete",$needsecure->words['delete']);

      if ( (!$needsecure->input['id']) or (empty($needsecure->input['id'])) ) {

          if (  $needsecure->input['step'] == 'new' ) {

             $tpl->set_var("NoAnounces",false);
             $tpl->set_var("AnounceRow",false);

             if ( $needsecure->input['substep'] == 'proceed' ) {

                $tpl->set_var("NewAnounce",false);

                // Add it! But first check something
                if ( empty($needsecure->input['date']) or empty($needsecure->input['header']) or empty($needsecure->input['body']) ) {
                   $std->Error($needsecure->words['fields_not_filled']);
                }

                if ( !$std->validate_date($needsecure->input['date']) ) {
                   $std->Error($needsecure->words['incorrect_date_format']);
                }

                $new_anounce['id'] = time();
                $new_anounce['date'] = $needsecure->input['date'];
                $new_anounce['header'] = $needsecure->input['header'];
                $new_anounce['body'] = $needsecure->input['body'];

                $DB->query("INSERT INTO ns_anounces (id,date,header,body) VALUES ('{$new_anounce['id']}','{$new_anounce['date']}','{$new_anounce['header']}','{$new_anounce['body']}')");

                $log_record = "New anounce added [ Anounce ID: {$new_anounce['id']} ].";

		if ( $needsecure->input['spam'] and !empty($needsecure->input['spam']) ) {

                	// Spam to our members :)

                	$DB->query("SELECT realname,email FROM ns_members WHERE suspended <> '1' and approved='1'");
                	while ( $mail_member = $DB->fetch_row() ) {
                    	$new_anounce['realname'] = $mail_member['realname'];
        				$mailer->setFrom($INFO['EMAIL_OUT']);
        				$mailer->setTo($mail_member['email']);
        				$mailer->setMessage("anounce",$new_anounce);
        				$mailer->setSubj("{$INFO['SITE_NAME']}: Latest anouncement");
        				$check = $mailer->send_mail();

                    	     if ( $check ) {
                       		$success++;
                    	     } else {
                       		$failure++;
                    	     }

               	        }

		   $log_record .= "New anounce sent to all members. Successes: {$success}. Failures: {$failure}";

                }

		/*
                 @ Write admin log
                 @
                 */
                 $std->writeAdminLog($log_record);

                $url = "{$needsecure->base_url}&act=idx&code=02&id={$new_anounce['id']}";
                @flush();
                $std->redirectPage($url,$needsecure->words['anounce_added_successfully']);

             } else {
                // Just show new anounce form
                $tpl->set_var("words_add_anounce",$needsecure->words['add_anounce']);
                $tpl->set_var("anounce_date",date('Y-m-d'));
                $tpl->set_var("OneAnounce",false);
            	$tpl->set_var("NoAnounces",false);
             	$tpl->set_var("AnounceRow",false);
             }

          } else {
          	// No id and this is no new anounce form? Show list!
          	$DB->query("SELECT id,date,header FROM ns_anounces ORDER BY date");
          	if ( $DB->get_num_rows() < 1 ) {
             	$tpl->set_var("AnounceRow",false);
             	$tpl->set_var("OneAnounce",false);
             	$tpl->set_var("NewAnounce",false);
          	} else {
             	$tpl->set_var("OneAnounce",false);
             	$tpl->set_var("NoAnounces",false);
             	$tpl->set_var("NewAnounce",false);
          		while ( $anounce = $DB->fetch_row() ) {
	   				$tpl->set_var("anounce_id",$anounce['id']);
	   				$tpl->set_var("anounce_date",$anounce['date']);
            		$tpl->set_var("anounce_header",$anounce['header']);
	   	 			$tpl->parse("AnounceRow",true);
		 		}
         	}
          }

      } else {

          $tpl->set_var("AnounceRow",false);
          $tpl->set_var("NoAnounce",false);
          $tpl->set_var("NewAnounce",false);

          // We have an anounce id. Check for step:
          if ( $needsecure->input['step'] == 'delete' ) {

            $DB->query("DELETE FROM ns_anounces WHERE id='{$needsecure->input['id']}'");

               /*
                 @ Write admin log
                 @
               */
               $std->writeAdminLog("Anounce [ ID: {$needsecure->input['id']} ] removed.");

            $url = "{$needsecure->base_url}&act=idx&code=02";
            @flush();
            $std->redirectPage($url,$needsecure->words['anounce_deleted']);

          } elseif ( $needsecure->input['step'] == 'edit' ) {

            // Some checks
            if ( empty($needsecure->input['date']) or empty($needsecure->input['header']) or empty($needsecure->input['body']) ) {
               $std->Error($needsecure->words['fields_not_filled']);
            }

            if ( !$std->validate_date($needsecure->input['date']) ) {
               $std->Error($needsecure->words['incorrect_date_format']);
            }

            $edited_anounce['id'] = $needsecure->input['id'];
            $edited_anounce['date'] = $needsecure->input['date'];
            $edited_anounce['header'] = $needsecure->input['header'];
            $edited_anounce['body'] = $needsecure->input['body'];

            // Update it in database
            $DB->query("UPDATE ns_anounces SET date='{$edited_anounce['date']}', header='{$edited_anounce['header']}', body='{$edited_anounce['body']}' WHERE id='{$edited_anounce['id']}'");

               /*
                 @ Write admin log
                 @
               */
               $std->writeAdminLog("Anounce [ ID: {$needsecure->input['id']} ] updated.");

	    $url = "{$needsecure->base_url}&act=idx&code=02";
            @flush();
            $std->redirectPage($url,$needsecure->words['anounce_edited']);

          } else {

            $DB->query("SELECT id,date,header,body FROM ns_anounces WHERE id='{$needsecure->input['id']}'");

            if ( $DB->get_num_rows() < 1 ) {
               $std->Error($needsecure->words['incorrect_anounce_id']);
            } else {
               $anounce = $DB->fetch_row();
            }

            // We dont need to update it, just display it
            $tpl->set_var("anounce_id",$anounce['id']);
            $tpl->set_var("anounce_date",$anounce['date']);
            $tpl->set_var("anounce_header",$anounce['header']);
            $tpl->set_var("anounce_body",$anounce['body']);
            $tpl->set_var("words_edit_anounce",$needsecure->words['edit_anounce']);

          }

      }

   }

   function usersManagement() {
     global $needsecure,$std,$tpl,$DB,$INFO,$mailer;

     if ( $needsecure->input['step'] == 'proceed' ) {

          if ( $needsecure->input['part'] == 'control' ) {

// CONTROLL BLOCK START //////////////////////////////////////////////////////////////////////

           if ( $needsecure->input['substep'] == 'delete' ) {

              $DB->query("SELECT id,realname,email,access_dirs FROM ns_members WHERE id='{$needsecure->input['id']}'");
               if ( $DB->get_num_rows() > 0 ) {

                 $member = $DB->fetch_row();

                 if ( !empty($member['access_dirs']) ) {

		        // Delete user from all .htpasswd in it's accessible dirs
		        require ("{$needsecure->dirs['INC']}htaccess.class{$INFO['PHP_EXT']}");
	 	        $ht = new htaccess;
	 	        $ht->setWorkDir("{$INFO['BASE_PATH']}{$INFO['HTA_TOP_DIR']}/");
		        $dirs = explode("|",$member['access_dirs']);
        	        for ($i=0;$i<count($dirs);$i++) {
          	                $ht->setHtaccess($INFO['BASE_PATH'] . $INFO['HTA_TOP_DIR'] . "/" . $dirs[$i] . "/.htaccess");
	  	                $ht->setHtpasswd($INFO['BASE_PATH'] . $INFO['HTA_TOP_DIR'] . "/" . $dirs[$i] . "/.htpasswd");
                                $ht->remUser($username);
       		        }
        	        unset($dirs);

		 }

	         $DB->query("DELETE FROM ns_members WHERE id='{$member['id']}'");

                 $member['admin_email'] = $INFO['ADMIN_EMAIL'];

                 $mailer->setFrom($INFO['EMAIL_OUT']);
                 $mailer->setTo($member['email']);
                 $mailer->setMessage("account_removed",$member );
                 $mailer->setSubj("{$INFO['SITE_NAME']}: Your account removed");
                 $mailer->send_mail();

                 /*
                   @ Write admin log
                   @
                 */
                 $std->writeAdminLog("Member [ ID: {$member['id']} ] removed from database. All it&#39;s records in .htpasswd files removed. Member notified via email.");

		 $url = "{$needsecure->base_url}&act=idx&code=03&part=control";
		 @flush();
		 $std->redirectPage($url,$needsecure->words['member_deleted']);

               } else {
                  $std->Error("No member found with such MemberID.");
               }

           } elseif ( $needsecure->input['substep'] == 'ban' ) {

               $DB->query("SELECT id,email,member_ip FROM ns_members WHERE id='{$needsecure->input['id']}'");
	       if ( $DB->get_num_rows() > 0 ) {

                  $member = $DB->fetch_row();

                  if ( !empty($member['member_ip']) ) {

		        $fh = @fopen("{$needsecure->dirs['TOP']}ip.ban","a");
                        @fputs($fh,"{$member['member_ip']}|");
		        @fclose($fh);

		  }

		  $fh = @fopen("{$needsecure->dirs['TOP']}id_name_email.ban","a");
                  @fputs($fh,"{$member['id']}|{$member['email']}\x0A");
		  @fclose($fh);

                  /*
                   @ Write admin log
                   @
                  */
                  $std->writeAdminLog("Member [ ID: {$member['id']} ] baned.");


		  $url = "{$needsecure->base_url}&act=idx&code=03&part=ban";
		  @flush();
		  $std->redirectPage($url,$needsecure->words['member_baned']);

	       } else {
                  $std->Error("No member found with such MemberID.");
               }



	   } elseif ( $needsecure->input['substep'] == 'update' ) {

              require ("{$needsecure->dirs['INC']}htaccess.class{$INFO['PHP_EXT']}");
	      $ht = new htaccess;

	      if ( in_array("no",$needsecure->input['access_dirs']) ) {
                 $access_dirs = "";
              } else {
                 $access_dirs = implode("|",$needsecure->input['access_dirs']);
	      }

              $DB->query("UPDATE ns_members SET realname='{$needsecure->input['realname']}',email='{$needsecure->input['email']}',regdate='{$needsecure->input['regdate']}',expire='{$needsecure->input['expire']}',lang='{$needsecure->input['lang']}',access_dirs='{$access_dirs}' WHERE id='{$needsecure->input['id']}'");


	      /****************************
               @  Rebuild .htpasswd files @
              *****************************/
              $DB->query("SELECT id,name,plain_password,access_dirs FROM ns_members WHERE id='{$needsecure->input['id']}'");
	      $member = $DB->fetch_row();

              // Remove member from all .htpasswd files:
	      require ("{$needsecure->dirs['INC']}directory.class{$INFO['PHP_EXT']}");
              $d = new dir;
              $d->get_dirs($INFO['HTA_TOP_DIR']);
	      for ($cnt=0;$cnt<count($d->dirs);$cnt++) {
                 if ( file_exists($d->dirs[$cnt] . "/.htpasswd") ) {
                    $ht->setHtpasswd($d->dirs[$cnt] . "/.htpasswd");
                    $ht->remUser($member['name']);
                 }
	      }
              // Now add it only to it's protected dirs:
	      if ( !empty($member['access_dirs']) ) {
	         $dirs = explode("|",$member['access_dirs']);
                 for ($i=0;$i<count($dirs);$i++) {
					 $ht->setHtaccess($INFO['HTA_TOP_DIR'] . "/" . $dirs[$i] . "/.htaccess");
    	             $ht->setHtpasswd($INFO['HTA_TOP_DIR'] . "/" . $dirs[$i] . "/.htpasswd");
        	         if ( !file_exists($INFO['HTA_TOP_DIR'] . "/" . $dirs[$i] . "/.htaccess") ) {
            	        $ht->ProtectDir();
                     }
	               $ht->addUser($member['name'],$member['plain_password']);
                 }
                 unset($dirs);
	      }
	      /*****************************/

                  /*
                   @ Write admin log
                   @
                  */
                  $std->writeAdminLog("Member [ ID: {$member['id']} ] profile updated. All .htpasswd files rebuilt.");


	      $url = "{$needsecure->base_url}&act=idx&code=03&part=control&subpart=edit&id={$member['id']}";
	      @flush();
	      $std->redirectPage($url,$needsecure->words['member_edited']);

	   }



// CONTROLL BLOCK END ////////////////////////////////////////////////////////////////////////

          } elseif ( $needsecure->input['part'] == 'prereg' ) {

          // Check for empty fields
	   if ( (empty($needsecure->input['realname'])) or (empty($needsecure->input['name'])) or (empty($needsecure->input['plain_password'])) or (empty($needsecure->input['email'])) ) {
              $std->Error($needsecure->words['fields_not_filled']);
	   }


	  // Check if email format correct
	      $new_member['email'] = $std->clean_email($needsecure->input['email']);
             if ( !$new_member['email'] ) {
                $std->Error($needsecure->words['email_invalid']);
	         }

	   	   // Check if such email already exists
	       $DB->query("SELECT id FROM ns_members WHERE email='{$new_member['email']}'");
	       if ( $DB->get_num_rows() ) {
              $std->Error($needsecure->words['email_exists']);
	       }

	       $new_member['realname'] = $needsecure->input['realname'];
               $new_member['name'] = $needsecure->input['name'];

               $new_member['plain_password'] = $needsecure->input['plain_password'];
	       $new_member['password'] = md5($new_member['plain_password']);

               $new_member['lang'] = $needsecure->input['lang'];

               $new_member['regdate'] = date('Y-m-d');
               $new_member['id'] = time();
               $new_member['member_ip'] = $needsecure->input['IP_ADDRESS'];

	       $new_member['user_agent'] = substr($_SERVER["HTTP_USER_AGENT"],0,50);

	       $SQL = "INSERT INTO ns_members
	       (id,name,password,plain_password,regdate,email,realname,lang,member_ip,member_browser,count_visits,suspended,approved,authcode,access_dirs)
	       VALUES
	       ('{$new_member['id']}','{$new_member['name']}','{$new_member['password']}','{$new_member['plain_password']}','{$new_member['regdate']}','{$new_member['email']}','{$new_member['realname']}','{$new_member['lang']}','{$new_member['member_ip']}','{$new_member['user_agent']}','1','0','1','','')";

	       $DB->query($SQL);

              $mailer->setFrom($INFO['EMAIL_OUT']);
              $mailer->setTo($new_member['email']);
              $mailer->setMessage("reg_login_details",$new_member );
              $mailer->setSubj("{$INFO['SITE_NAME']}: Registration successfull");
              $mailer->send_mail();

              /*
               @ Write admin log
               @
              */
              $std->writeAdminLog("Member [ ID: {$new_member['id']}, Name: {$new_member['name']} ] registered. New member notified via email.");

	   $url = "{$needsecure->base_url}&act=idx&code=03&part=control&subpart=edit&id={$new_member['id']}";
           @flush();
           $std->redirectPage($url,$needsecure->words['member_registered']);

       } elseif ( $needsecure->input['part'] == 'approve' ) {

          if ( $needsecure->input['substep'] == 'decline' ) {

             // For some security reasons, check id first :)
             $DB->query("SELECT name FROM ns_members WHERE id='{$needsecure->input['id']}' and approved <> '1'");
             if ( $DB->get_num_rows() > 0 ) {

	             // Bluh, just delete it from database :)
                 $DB->query("DELETE FROM ns_members WHERE id='{$needsecure->input['id']}'");

             } else {
                 $std->Error("Sorry, user with <strong>ID <span style='color: red;'>{$needsecure->input['id']}</span></strong> approved and can't deleted this way.");
             }

             $log_result = "declined";
	     $result_word = $needsecure->words['member_declined'];

          } elseif ( $needsecure->input['substep'] == 'approve' ) {

             $DB->query("SELECT id,realname,email FROM ns_members WHERE id='{$needsecure->input['id']}' and approved <> '1'");
             if ( $DB->get_num_rows() > 0 ) {

             	 $member = $DB->fetch_row();

    	         $DB->query("UPDATE ns_members SET approved='1' WHERE id='{$member['id']}'");

                 $mailer->setFrom($INFO['EMAIL_OUT']);
                 $mailer->setTo($member['email']);
                 $mailer->setMessage("account_approved",$member );
                 $mailer->setSubj("{$INFO['SITE_NAME']}: Your account approved");
                 $mailer->send_mail();

             } else {
                 $std->Error("Sorry, user with <strong>ID <span style='color: red;'>{$needsecure->input['id']}</span></strong> approved and can't be approved again.");
             }

             $log_result = "approved";
	     $result_word = $needsecure->words['member_approved'];

          }

	  /*
           @ Write admin log
           @
          */
          $std->writeAdminLog("Member [ ID: {$member['id']} ] registration {$log_result}. New member notified via email.");


          $url = "{$needsecure->base_url}&act=idx&code=03&part=approve";
          @flush();
          $std->redirectPage($url,$result_word);

       } elseif ( $needsecure->input['part'] == 'suspend' ) {

          if ( $needsecure->input['substep'] == 'unsuspend' ) {

             $DB->query("SELECT id,realname,email FROM ns_members WHERE id='{$needsecure->input['id']}' and suspended='1'");
             if ( $DB->get_num_rows() > 0 ) {

             	 $member = $DB->fetch_row();

    	         $DB->query("UPDATE ns_members SET suspended='0' WHERE id='{$member['id']}'");

                 $mailer->setFrom($INFO['EMAIL_OUT']);
                 $mailer->setTo($member['email']);
                 $mailer->setMessage("account_unsuspended",$member );
                 $mailer->setSubj("{$INFO['SITE_NAME']}: Your account unsuspended");
                 $mailer->send_mail();

             } else {
                 $std->Error("Sorry, user with <strong>ID <span style='color: red;'>{$needsecure->input['id']}</span></strong> not suspended and can't be unsuspended.");
             }

             $log_result = "unsuspended";
	     $result_word = $needsecure->words['member_unsuspended'];

          } elseif ( $needsecure->input['substep'] == 'suspend' ) {

            $DB->query("SELECT id,realname,email FROM ns_members WHERE id='{$needsecure->input['id']}' and suspended='0'");
             if ( $DB->get_num_rows() > 0 ) {

             	 $member = $DB->fetch_row();

    	         $DB->query("UPDATE ns_members SET suspended='1' WHERE id='{$member['id']}'");

                 $member['admin_email'] = $INFO['ADMIN_EMAIL'];

                 $mailer->setFrom($INFO['EMAIL_OUT']);
                 $mailer->setTo($member['email']);
                 $mailer->setMessage("account_suspended",$member );
                 $mailer->setSubj("{$INFO['SITE_NAME']}: Your account suspended");
                 $mailer->send_mail();

             } else {
                 $std->Error("Sorry, user with <strong>ID <span style='color: red;'>{$needsecure->input['id']}</span></strong> currently suspended and can't be suspended again.");
             }

              $log_result = "suspended";
	      $result_word = $needsecure->words['member_suspended'];
          }

          /*
           @ Write admin log
          */
          $std->writeAdminLog("Member [ ID: {$member['id']} ] account {$log_result}. New member notified via email.");


	 $url = "{$needsecure->base_url}&act=idx&code=03&part=suspend";
         @flush();
         $std->redirectPage($url,$result_word);

       } elseif ( $needsecure->input['part'] == 'ban' ) {

         $DB->query("SELECT id,email,member_ip FROM ns_members WHERE id='{$needsecure->input['id']}'");
	 if ( $DB->get_num_rows() > 0 ) {

            $member = $DB->fetch_row();

	    if ( file_exists("{$needsecure->dirs['TOP']}ip.ban") ) {
                $fh = @fopen("{$needsecure->dirs['TOP']}ip.ban","r");
                $line = @fgets($fh);
                $ips = explode("|",chop($line));
                @fclose($fh);

                foreach ($ips as $ip) {
	                $ip = preg_replace( "/\*/", '.*' , $ip );
	                if (preg_match( "/$ip/", $member['member_ip'] )) {
	                    next;
	                } else {
                            $new_ips .= "{$ip}|";
			}
                }
            }

	    $fh = @fopen("{$needsecure->dirs['TOP']}ip.ban","w");
            @fputs($fh,$new_ips);
	    @fclose($fh);

	    if ( file_exists("{$needsecure->dirs['TOP']}id_name_email.ban") ) {
                $fh = @fopen("{$needsecure->dirs['TOP']}id_name_email.ban","r");
                while ( $line = @fgets($fh) ) {
                     $lineArr = explode("|",chop($line));
	             if (  preg_match( "/$lineArr[0]/",$member['id'] ) and preg_match( "/$lineArr[1]/",$member['email'] )  ) {
                        next;
		     } else {
                        $new_bans .= "{$lineArr[0]}|{$lineArr[1]}\x0A";
		     }
	        }
                @fclose($fh);
            }

	    $fh = @fopen("{$needsecure->dirs['TOP']}id_name_email.ban","w");
            @fputs($fh,$new_bans);
	    @fclose($fh);

	 } else {
            $std->Error("Sorry, user with <strong>ID <span style='color: red;'>{$needsecure->input['id']}</span></strong> not found");
	 }

          /*
           @ Write admin log
          */
          $std->writeAdminLog("Member [ ID: {$member['id']} ] account unbaned. New member notified via email.");


	 $url = "{$needsecure->base_url}&act=idx&code=03&part=ban";
         @flush();
         $std->redirectPage($url,$needsecure->words['member_undaned']);

       } else {

       }

     } else {

       $tpl->set_var("words_users_management",$needsecure->words['users_management']);

       if ( $needsecure->input['part'] == 'control' ) {

         $tpl->set_var("PreregForm",false);
         $tpl->set_var("MemberApprove",false);
         $tpl->set_var("MemberAppRow",false);
         $tpl->set_var("MemberSuspend",false);
         $tpl->set_var("MemberSuspRow",false);
         $tpl->set_var("GlobalMemsRow",false);
	 $tpl->set_var("MemberBaning",false);
	 if ( $needsecure->input['subpart'] != 'edit' or empty($needsecure->input['subpart']) or !$needsecure->input['subpart'] ) {
            $tpl->set_var("OneMember",false);
	 }

         /*

           # Main block rules

           @ We need to show user-searxh form on every step;
           @ if search done, show list of results;
           @ if full list picked, display it;
           @ if one member picked, show it's profile and
           @ it's options

         */

         $tpl->set_var("words_control_member_h",$needsecure->words['control_member_h']);
         $tpl->set_var("words_search",$needsecure->words['search']);
         $tpl->set_var("words_in",$needsecure->words['in']);
         $tpl->set_var("words_search_go",$needsecure->words['search_go']);

         $search_in_opts = "
         \t\t<option value='all'>All</option>\n
		 \t\t<option value='name'>Name</option>\n
         \t\t<option value='realname'>RealName</option>\n
         \t\t<option value='email'>Email</option>\n
         ";

         if ( $INFO['ADD_FIELD_1_ON'] == 1 ) {
            $search_in_opts .= "\t\t<option value='extra1'>{$INFO['ADD_FIELD_1_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_2_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra2'>{$INFO['ADD_FIELD_2_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_3_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra3'>{$INFO['ADD_FIELD_3_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_4_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra4'>{$INFO['ADD_FIELD_4_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_5_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra5'>{$INFO['ADD_FIELD_5_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_6_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra6'>{$INFO['ADD_FIELD_6_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_7_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra7'>{$INFO['ADD_FIELD_7_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_8_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra8'>{$INFO['ADD_FIELD_8_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_9_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra9'>{$INFO['ADD_FIELD_9_NM']}</option>\n";
         }
         if ( $INFO['ADD_FIELD_10_ON'] == 1 ) {
         	$search_in_opts .= "\t\t<option value='extra10'>{$INFO['ADD_FIELD_10_NM']}</option>\n";
         }

         $tpl->set_var("search_in_opts",$search_in_opts);


         // Additional routines

         if ( $needsecure->input['subpart'] == 'search' ) {

                $tpl->set_var("PreregForm",false);
         	$tpl->set_var("MemberApprove",false);
        	$tpl->set_var("MemberAppRow",false);
         	$tpl->set_var("MemberSuspend",false);
         	$tpl->set_var("MemberSuspRow",false);
		$tpl->set_var("MemberBaning",false);
		$tpl->set_var("OneMember",false);

                   $qStr = $needsecure->input['q'];
           		   $wFld = $needsecure->input['w'];



                    if ( $wFld == 'all' ) {

              			$where = " WHERE name LIKE '%{$qStr}%' or realname LIKE '%{$qStr}%' or email LIKE '%{$qStr}%' ";

              			// Add our active extra fields to where routine:
              			if ( $INFO['ADD_FIELD_1_ON'] == 1 ) {
                 			$where .= "or extra1 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_2_ON'] == 1 ) {
                 			$where .= "or extra2 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_3_ON'] == 1 ) {
                 			$where .= "or extra3 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_4_ON'] == 1 ) {
                 			$where .= "or extra4 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_5_ON'] == 1 ) {
                 			$where .= "or extra5 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_6_ON'] == 1 ) {
                 			$where .= "or extra6 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_7_ON'] == 1 ) {
                 			$where .= "or extra7 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_8_ON'] == 1 ) {
                 			$where .= "or extra8 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_9_ON'] == 1 ) {
                 			$where .= "or extra9 LIKE '%{$qStr}%' ";
              			}
              			if ( $INFO['ADD_FIELD_10_ON'] == 1 ) {
                 			$where .= "or extra10 LIKE '%{$qStr}%' ";
              			}

           		   } else {
                        $where = " WHERE {$wFld} LIKE '%{$qStr}%'";
                   }

                    if ( $qStr == '' ) {
                       $where = "";
                    }

           		    $tpl->set_var("words_control_users_h",$needsecure->words['control_member_h']);
           			$tpl->set_var("words_edit",$needsecure->words['edit']);
           			$tpl->set_var("words_delete",$needsecure->words['delete']);
           			$tpl->set_var("words_suspend",$needsecure->words['suspend']);
           			$tpl->set_var("words_ban",$needsecure->words['ban']);


           			$DB->query("SELECT id,name,realname,email,suspended,approved FROM ns_members {$where} ORDER BY realname");
           			if ( $DB->get_num_rows() > 0 ) {

                      $tpl->set_var("GlobalMemsSrchFailed",false);

               			while ( $member = $DB->fetch_row() ) {

                            if ( $member['suspended'] == 1 or $member['approved'] != 1 ) {
                               next;
                            } else {

                            	$tpl->set_var("member_id",$member['id']);
                  				$tpl->set_var("member_name",$member['name']);
                  				$tpl->set_var("member_realname",$member['realname']);
                  				$tpl->set_var("member_email",$member['email']);

                  				$tpl->parse("GlobalMemsRow",true);
                            }
               		   }

           		   } else {
                       $tpl->set_var("GlobalMemsRow",false);
                       $tpl->set_var("words_search_no_results",$needsecure->words['search_no_results']);
                   }


              } elseif ( $needsecure->input['subpart'] == 'edit' ) {

                   $tpl->set_var("PreregForm",false);
         	   $tpl->set_var("MemberApprove",false);
        	   $tpl->set_var("MemberAppRow",false);
         	   $tpl->set_var("MemberSuspend",false);
         	   $tpl->set_var("MemberSuspRow",false);
		   $tpl->set_var("MemberBaning",false);
		   $tpl->set_var("GlobalMemsRow",false);

                   $tpl->set_var("words_edit_member_h",$needsecure->words['edit_member_h']);
		   $tpl->set_var("words_id",$needsecure->words['id']);
		   $tpl->set_var("words_username",$needsecure->words['username']);
		   $tpl->set_var("words_realname",$needsecure->words['realname']);
		   $tpl->set_var("words_regdate",$needsecure->words['regdate']);
		   $tpl->set_var("words_expire",$needsecure->words['expire']);
		   $tpl->set_var("words_email",$needsecure->words['email']);
		   $tpl->set_var("words_interface_lang",$needsecure->words['interface_lang']);
		   $tpl->set_var("words_access_dirs",$needsecure->words['access_dirs']);
		   $tpl->set_var("words_last_login",$needsecure->words['last_login']);
		   $tpl->set_var("words_save",$needsecure->words['save']);


		   $DB->query("SELECT id,name,realname,email,regdate,expire,lang,last_login,access_dirs
		               FROM ns_members WHERE id='{$needsecure->input['id']}'");

                   if ( $DB->get_num_rows() > 0 ) {

                       $member = $DB->fetch_row();

                        $tpl->set_var("member_id",$member['id']);
		        $tpl->set_var("member_name",$member['name']);
		        $tpl->set_var("member_realname",$member['realname']);
		        $tpl->set_var("member_regdate",$member['regdate']);
		        $tpl->set_var("member_expire",$member['expire']);
		        $tpl->set_var("member_email",$member['email']);

                        if ( empty($member['last_login']) ) {
                           $tpl->set_var("member_last_login",$needsecure->words['never']);
			} else {
			   $tpl->set_var("member_last_login",$member['last_login']);
                        }

                        $langs = explode("|",$INFO['INSTALLED_LANGS']);
	                for ($i=0;$i<count($langs);$i++) {
                            $lang_data = explode(":",$langs[$i]);
	                    if ( $lang_data[0] == $member['lang'] ) {
                               $selected = "selected";
	                    } else {
                               $selected = "";
	                    }
	                    $member_lang_opts .= "<option value='{$lang_data[0]}' {$selected}>{$lang_data[1]}</option>\n";
                        }

			$tpl->set_var("member_lang_opts",$member_lang_opts);

            if ( empty($member['access_dirs']) ) {
               $empty_selector = "selected";
			} else {
               $empty_selector = "";
			}

			$dirs = explode("|",$member['access_dirs']);

			$member_access_dirs_opts .= "\t<option value='no' {$empty_selector}>no one</option>\n";

			require ("{$needsecure->dirs['INC']}directory.class{$INFO['PHP_EXT']}");
                        $d = new dir;
                        $d->get_dirs($INFO['HTA_TOP_DIR']);
			for ($cnt=0;$cnt<count($d->dirs);$cnt++) {

                $ns_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
                $needsecure_dir = $ns_parts["dirname"];

                if ( $d->dirs[$cnt] == $needsecure_dir ) {
                   next;
                } elseif ( file_exists( $d->dirs[$cnt] . "/.htaccess" ) && file_exists( $d->dirs[$cnt] . "/.htpasswd" ) && $d->dirs[$cnt] != $needsecure_dir ) {

               	 	$dirname_ws = preg_replace("#/$#","",$d->dirs[$cnt]);
			    	$path_parts = pathinfo($dirname_ws);
                	$dir_name = $path_parts['basename'];

			    	if ( in_array($dir_name,$dirs) ) {
                        $selector = "selected";
			    	} else {
                        $selector = "";
			    	}

			    	$member_access_dirs_opts .= "\t<option value='{$dir_name}' {$selector}>{$d->dirs[$cnt]}</option>\n";

                }
			}

			$tpl->set_var("member_access_dirs_opts",$member_access_dirs_opts);

		   } else {
                      $std->Error("No such member");
		   }

	      }


       } elseif ( $needsecure->input['part'] == 'prereg' ) {

         $tpl->set_var("MemberApprove",false);
         $tpl->set_var("MemberAppRow",false);
         $tpl->set_var("MemberSuspend",false);
         $tpl->set_var("MemberSuspRow",false);
         $tpl->set_var("GlobalMembers",false);
	 $tpl->set_var("MemberBaning",false);
	 $tpl->set_var("OneMember",false);

	 $tpl->set_var("words_prereg_member_h",$needsecure->words['prereg_member_h']);
	 $tpl->set_var("words_realname",$needsecure->words['realname']);
         $tpl->set_var("words_email",$needsecure->words['email']);
         $tpl->set_var("words_username",$needsecure->words['username']);
         $tpl->set_var("words_password",$needsecure->words['password']);
         $tpl->set_var("words_interface_lang",$needsecure->words['interface_lang']);
         $tpl->set_var("words_prereg_member",$needsecure->words['prereg_member']);

         $langs = explode("|",$INFO['INSTALLED_LANGS']);
	     for ($i=0;$i<count($langs);$i++) {
            $lang_data = explode(":",$langs[$i]);
  	        $lang_select_options .= "<option value='{$lang_data[0]}'>{$lang_data[1]}</option>\n";
         }
         $tpl->set_var("lang_select_options",$lang_select_options);

       } elseif ( $needsecure->input['part'] == 'approve' ) {

         $tpl->set_var("PreregForm",false);
         $tpl->set_var("MemberSuspend",false);
         $tpl->set_var("MemberSuspRow",false);
         $tpl->set_var("GlobalMembers",false);
	 $tpl->set_var("MemberBaning",false);
	 $tpl->set_var("OneMember",false);

         $tpl->set_var("words_approve_member_h",$needsecure->words['approve_member_h']);
         $tpl->set_var("words_approve",$needsecure->words['approve']);
         $tpl->set_var("words_decline",$needsecure->words['decline']);

         $DB->query("SELECT id,name,realname,email FROM ns_members WHERE approved <> '1'");

         if ( $DB->get_num_rows() > 0 ) {
         	while ( $member = $DB->fetch_row() ) {
            	$tpl->set_var("member_id",$member['id']);
            	$tpl->set_var("member_realname",$member['realname']);
            	$tpl->set_var("member_name",$member['name']);
            	$tpl->set_var("member_email",$member['email']);

            	$tpl->parse("MemberAppRow",true);
         	}
         } else {
            $tpl->set_var("MemberAppRow",false);
         }

       } elseif ( $needsecure->input['part'] == 'suspend' ) {

         $tpl->set_var("PreregForm",false);
         $tpl->set_var("MemberApprove",false);
         $tpl->set_var("MemberAppRow",false);
         $tpl->set_var("GlobalMembers",false);
	 $tpl->set_var("MemberBaning",false);
	 $tpl->set_var("OneMember",false);

         $tpl->set_var("words_suspended_member_h",$needsecure->words['suspended_member_h']);
         $tpl->set_var("words_unsuspend",$needsecure->words['unsuspend']);

         $DB->query("SELECT id,name,realname,email FROM ns_members WHERE suspended='1'");

         if ( $DB->get_num_rows() > 0 ) {
         	while ( $member = $DB->fetch_row() ) {
            	$tpl->set_var("member_id",$member['id']);
            	$tpl->set_var("member_realname",$member['realname']);
            	$tpl->set_var("member_name",$member['name']);
            	$tpl->set_var("member_email",$member['email']);

            	$tpl->parse("MemberSuspRow",true);
         	}
         } else {
            $tpl->set_var("MemberSuspRow",false);
         }

       } elseif ( $needsecure->input['part'] == 'ban' ) {

	     $tpl->set_var("PreregForm",false);
         $tpl->set_var("MemberApprove",false);
         $tpl->set_var("MemberAppRow",false);
	     $tpl->set_var("MemberSuspend",false);
         $tpl->set_var("MemberSuspRow",false);
         $tpl->set_var("GlobalMembers",false);
	     $tpl->set_var("OneMember",false);

         $tpl->set_var("words_baned_member_h",$needsecure->words['baned_member_h']);
	     $tpl->set_var("words_unban",$needsecure->words['unban']);


	 if ( file_exists("{$needsecure->dirs['TOP']}id_name_email.ban") ) {
            $fh = @fopen("{$needsecure->dirs['TOP']}id_name_email.ban","r");
            $i = 0;
            while ( $line = @fgets($fh) ) {
              $lineArr = explode("|",chop($line));
	          $banData[$i]['id'] = $lineArr[0];
	          $banData[$i]['email'] = $lineArr[1];
	          $i++;
            }
            @fclose($fh);

              for ( $i=0; $i<count($banData); $i++ ) {

	          	$DB->query("SELECT id,name,realname,email FROM ns_members WHERE id='{$banData[$i]['id']}'");
		      	if ( $DB->get_num_rows() > 0 ) {
                 	$member = $DB->fetch_row();

                 	$tpl->set_var("member_id",$member['id']);
                 	$tpl->set_var("member_realname",$member['realname']);
                 	$tpl->set_var("member_name",$member['name']);
                 	$tpl->set_var("member_email",$member['email']);

                 	$tpl->parse("MemberBanRow",true);

		      	} else {
                 	$tpl->set_var("MemberBanRow",false);
		      	}

	        }
	        if ( $i == 0 ) {
               $tpl->set_var("MemberBanRow",false);
	        }
     }


       } else {

         $tpl->set_var("PreregForm",false);
       }


     }

   }

   function adminsManagement() {
     global $needsecure,$std,$tpl,$DB,$INFO,$mailer;

     $tpl->set_var("words_admins_management",$needsecure->words['admins_management']);

     if ( $needsecure->input['step'] == 'proceed' ) {

     /*
       @ Actins block
       @ all operations exept output
     */

        if ( $needsecure->input['part'] == 'reg' ) {

           // Check for empty fields
	   if ( (empty($needsecure->input['name'])) or (empty($needsecure->input['password'])) or (empty($needsecure->input['email'])) ) {
              $std->Error($needsecure->words['fields_not_filled']);
	   }

	   // Check for correct email
	   $new_admin['email'] = $std->clean_email($needsecure->input['email']);
           if ( !$new_admin['email'] ) {
              $std->Error($needsecure->words['email_invalid']);
	   }

	   // Check if name exists and email
	   $DB->query("SELECT id FROM ns_admins WHERE name='{$needsecure->input['name']}' or email='{$needsecure->input['name']}}'");
           if ( $DB->get_num_rows() > 0 ) {
	      $std->Error("Sorry, administrator with such name or email exists. Try another, please.");
	   }

           // All looks good, register
	   $new_admin['id'] = time();
	   $new_admin['plain_password'] = $needsecure->input['password'];
	   $new_admin['password'] = md5($new_admin['plain_password']);
	   $new_admin['name'] = $needsecure->input['name'];
	   $new_admin['level'] = $needsecure->input['level'];
	   $new_admin['lang'] = $needsecure->input['lang'];
	   $DB->query("INSERT INTO ns_admins (id,name,password,email,level,lang) VALUES ('{$new_admin['id']}','{$new_admin['name']}','{$new_admin['password']}','{$new_admin['email']}','{$new_admin['level']}','{$new_admin['lang']}')");

           /*
             @ ADDED:
	     @
	     @ Distribute access right according it's access level for mail information
	     @
	   */

if ( $upd_admin['level'] == 1 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_global_setup']}
{$needsecure->words['part_anouncements']}
{$needsecure->words['part_user_control']}
{$needsecure->words['part_admins_control']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
{$needsecure->words['part_admin_logs']}
";
} elseif ( $upd_admin['level'] == 2 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_anouncements']}
{$needsecure->words['part_user_control']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
";
} elseif ( $upd_admin['level'] == 3 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_anouncements']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
";
} elseif ( $upd_admin['level'] == 4 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
";
} elseif ( $upd_admin['level'] == 5 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_statistic']}
";
}

	   $mailer->setFrom($INFO['EMAIL_OUT']);
           $mailer->setTo($new_admin['email']);
           $mailer->setMessage("admin_created",$new_admin );
           $mailer->setSubj("{$INFO['SITE_NAME']}: Your administrator account created");
           $mailer->send_mail();

           /*
            @ Write admin log
           */
           $std->writeAdminLog("New administrator ( ID: {$new_admin['id']}, Level: {$new_admin['level']} ) created. New administrator notified via email.");


	   $url = "{$needsecure->base_url}&act=idx&code=04&part=edit&id={$new_admin['id']}";
	   @flush();
	   $std->redirectPage($url,$needsecure->words['admin_created']);

	} elseif ( $needsecure->input['part'] == 'edit' ) {

           // Check for empty fields
	   if ( (empty($needsecure->input['email'])) ) {
              $std->Error($needsecure->words['fields_not_filled']);
	   }

	   // Check if name exists and email
	   $DB->query("SELECT id,name,email,level,lang FROM ns_admins WHERE id='{$needsecure->input['id']}'");
           $res = $DB->fetch_row();

           // All looks good, update info
           $upd_admin['name'] = $res['name'];
	   // Check for correct email
	   $upd_admin['email'] = $std->clean_email($needsecure->input['email']);
           if ( !$upd_admin['email'] ) {
              $std->Error($needsecure->words['email_invalid']);
	   }
	   $upd_admin['level'] = $needsecure->input['level'];
	   $upd_admin['lang'] = $needsecure->input['lang'];

	   $DB->query("UPDATE ns_admins SET email='{$upd_admin['email']}',level='{$upd_admin['level']}',lang='{$upd_admin['lang']}' WHERE id='{$needsecure->input['id']}'");

	   /*
             @ DONE:
	     @
	     @ Distribute access right according it's access level for mail information
	     @
	   */

if ( $upd_admin['level'] == 1 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_global_setup']}
{$needsecure->words['part_anouncements']}
{$needsecure->words['part_user_control']}
{$needsecure->words['part_admins_control']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
{$needsecure->words['part_admin_logs']}
";
} elseif ( $upd_admin['level'] == 2 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_anouncements']}
{$needsecure->words['part_user_control']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
";
} elseif ( $upd_admin['level'] == 3 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_anouncements']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
";
} elseif ( $upd_admin['level'] == 4 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_backups']}
{$needsecure->words['part_statistic']}
";
} elseif ( $upd_admin['level'] == 5 ) {
$upd_admin['access_rights'] = "
{$needsecure->words['part_system_info']}
{$needsecure->words['part_statistic']}
";
}

	   $mailer->setFrom($INFO['EMAIL_OUT']);
           $mailer->setTo($upd_admin['email']);
           $mailer->setMessage("admin_updated",$upd_admin );
           $mailer->setSubj("{$INFO['SITE_NAME']}: Your administrator account updated");
           $mailer->send_mail();

           /*
            @ Write admin log
           */
           $std->writeAdminLog("Administrator [ ID: {$needsecure->input['id']}, Level: {$upd_admin['level']} ] profile updated. Administrator notified via email.");


	   $url = "{$needsecure->base_url}&act=idx&code=04&part=edit&id={$needsecure->input['id']}";
	   @flush();
	   $std->redirectPage($url,$needsecure->words['admin_updated']);

	} elseif ( $needsecure->input['part'] == 'rem' ) {

           // First check for admin existance
	   $DB->query("SELECT id,name,email,level FROM ns_admins WHERE id='{$needsecure->input['id']}'");
	   if ( $DB->get_num_rows() < 1 ) {
               $std->Error("Sorry, no record for this ID found.");
	   } else {
               $rm_admin = $DB->fetch_row();
	   }

	   // For some secure reasons check for logged admin rights ...
	   if ( $needsecure->admin['level'] != 1 ) {
              $std->Error("Sorry, You don't have parmissions for this action!");
	   } elseif ( $needsecure->admin['level'] == 1 ) {
              $DB->query("DELETE FROM ns_admins WHERE id='{$needsecure->input['id']}'");

              $rm_admin['admin_email'] = $INFO['ADMIN_EMAIL'];

	      $mailer->setFrom($INFO['EMAIL_OUT']);
              $mailer->setTo($rm_admin['email']);
              $mailer->setMessage("admin_removed",$rm_admin );
              $mailer->setSubj("{$INFO['SITE_NAME']}: Your administrator account removed");
              $mailer->send_mail();

              /*
               @ Write admin log
              */
              $std->writeAdminLog("Administrator [ ID: {$needsecure->input['id']}, Level: {$rm_admin['level']} ] account removed. Administrator notified via email.");


	      $url = "{$needsecure->base_url}&act=idx&code=04";
	      @flush();
	      $std->redirectPage($url,$needsecure->words['admin_removed']);

	   }

	}

     // End of actions block

     } else {

     /*
       @ Output block
       @ all output, no actions
     */

        if ( $needsecure->input['part'] == 'reg' ) {

           $tpl->set_var("AdmListAll",false);
	   $tpl->set_var("AdmListRow",false);
	   $tpl->set_var("AdmProfile",false);

	   $tpl->set_var("words_admin_reg_h",$needsecure->words['admin_reg_h']);
	   $tpl->set_var("words_username",$needsecure->words['username']);
	   $tpl->set_var("words_password",$needsecure->words['password']);
	   $tpl->set_var("words_email",$needsecure->words['email']);
	   $tpl->set_var("words_admin_level",$needsecure->words['admin_level']);
	   $tpl->set_var("words_interface_lang",$needsecure->words['interface_lang']);
	   $tpl->set_var("words_create_admin",$needsecure->words['create_admin']);

           // alevel_select_options
           $alevel_select_options = "";
	   for ($i=1;$i<=5;$i++) {
             if ( $i == 5 ) {
	        $selected = 'selected';
	     } else {
                $selected = '';
	     }
	     $alevel_select_options .= "\t<option value='{$i}' {$selected}>{$needsecure->words['level']} {$i}</option>\n";
	   }
           $tpl->set_var("alevel_select_options",$alevel_select_options);

	   // lang_select_options
	   $langs = explode("|",$INFO['INSTALLED_LANGS']);
	     for ($i=0;$i<count($langs);$i++) {
                $lang_data = explode(":",$langs[$i]);
  	        $lang_select_options .= "<option value='{$lang_data[0]}'>{$lang_data[1]}</option>\n";
             }
           $tpl->set_var("lang_select_options",$lang_select_options);

	   $tpl->set_var("words_admins_levels_info_h",$needsecure->words['admins_levels_info_h']);
           $tpl->set_var("words_admin_rights",$needsecure->words['admins_rights']);

           $admin_level_1_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_global_setup']}<br>{$needsecure->words['part_anouncements']}<br>{$needsecure->words['part_user_control']}<br>{$needsecure->words['part_admins_control']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>{$needsecure->words['part_admin_logs']}<br>";
           $admin_level_2_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_anouncements']}<br>{$needsecure->words['part_user_control']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>";
	   $admin_level_3_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_anouncements']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>";
	   $admin_level_4_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>";
	   $admin_level_5_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_statistic']}<br>";

	   $tpl->set_var("admin_level_1_rights",$admin_level_1_rights);
	   $tpl->set_var("admin_level_2_rights",$admin_level_2_rights);
	   $tpl->set_var("admin_level_3_rights",$admin_level_3_rights);
	   $tpl->set_var("admin_level_4_rights",$admin_level_4_rights);
	   $tpl->set_var("admin_level_5_rights",$admin_level_5_rights);

	} elseif ( $needsecure->input['part'] == 'edit' ) {

           $tpl->set_var("AdmListAll",false);
	   $tpl->set_var("AdmListRow",false);
	   $tpl->set_var("AdmRegForm",false);

           $tpl->set_var("words_admin_edit_h",$needsecure->words['admin_edit_h']);
	   $tpl->set_var("words_username",$needsecure->words['username']);
	   $tpl->set_var("words_password",$needsecure->words['password']);
	   $tpl->set_var("words_email",$needsecure->words['email']);
	   $tpl->set_var("words_admin_level",$needsecure->words['admin_level']);
	   $tpl->set_var("words_interface_lang",$needsecure->words['interface_lang']);
	   $tpl->set_var("words_update_admin",$needsecure->words['update_admin']);

	   $DB->query("SELECT id,name,email,level,lang FROM ns_admins WHERE id='{$needsecure->input['id']}'");
	   if ( $DB->get_num_rows() < 1 ) {
              $std->Error("Sorry, not administrator with such ID found.");
	   } else {
              $ed_admin = $DB->fetch_row();
	   }

	   $tpl->set_var("admin_id",$ed_admin['id']);
	   $tpl->set_var("admin_name",$ed_admin['name']);
	   $tpl->set_var("admin_email",$ed_admin['email']);

           // alevel_select_options
           $alevel_select_options = "";
	   for ($i=1;$i<=5;$i++) {
             if ( $ed_admin['level'] == $i ) {
	        $selected = 'selected';
	     } else {
                $selected = '';
	     }
	     $alevel_select_options .= "\t<option value='{$i}' {$selected}>{$needsecure->words['level']} {$i}</option>\n";
	   }
           $tpl->set_var("alevel_select_options",$alevel_select_options);


	   // lang_select_options
	   $langs = explode("|",$INFO['INSTALLED_LANGS']);
	     for ($i=0;$i<count($langs);$i++) {
                $lang_data = explode(":",$langs[$i]);
		if ( $ed_admin['lang'] == $lang_data[0] ) {
                   $selected = "selected";
		} else {
                   $selected = "";
		}
  	        $lang_select_options .= "<option value='{$lang_data[0]}' {$selected}>{$lang_data[1]}</option>\n";
             }
           $tpl->set_var("lang_select_options",$lang_select_options);


	   $tpl->set_var("words_admins_levels_info_h",$needsecure->words['admins_levels_info_h']);
           $tpl->set_var("words_admin_rights",$needsecure->words['admins_rights']);

           $admin_level_1_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_global_setup']}<br>{$needsecure->words['part_anouncements']}<br>{$needsecure->words['part_user_control']}<br>{$needsecure->words['part_admins_control']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>{$needsecure->words['part_admin_logs']}<br>";
           $admin_level_2_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_anouncements']}<br>{$needsecure->words['part_user_control']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>";
	   $admin_level_3_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_anouncements']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>";
	   $admin_level_4_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_backups']}<br>{$needsecure->words['part_statistic']}<br>";
	   $admin_level_5_rights = "{$needsecure->words['part_system_info']}<br>{$needsecure->words['part_statistic']}<br>";

	   $tpl->set_var("admin_level_1_rights",$admin_level_1_rights);
	   $tpl->set_var("admin_level_2_rights",$admin_level_2_rights);
	   $tpl->set_var("admin_level_3_rights",$admin_level_3_rights);
	   $tpl->set_var("admin_level_4_rights",$admin_level_4_rights);
	   $tpl->set_var("admin_level_5_rights",$admin_level_5_rights);



	} else {

        // Else just display a full list

	   $tpl->set_var("AdmRegForm",false);
	   $tpl->set_var("AdmProfile",false);

	   $tpl->set_var("words_adm_list_h",$needsecure->words['adm_list_h']);
	   $tpl->set_var("words_username",$needsecure->words['username']);
	   $tpl->set_var("words_email",$needsecure->words['email']);
	   $tpl->set_var("words_admin_level",$needsecure->words['admin_level']);
	   $tpl->set_var("words_level",$needsecure->words['level']);
	   $tpl->set_var("words_edit",$needsecure->words['edit']);
	   $tpl->set_var("words_delete",$needsecure->words['delete']);

           $DB->query("SELECT id,name,email,level FROM ns_admins ORDER BY level");
	   while ( $adm = $DB->fetch_row() ) {
	         $tpl->set_var("admin_id",$adm['id']);
		 $tpl->set_var("admin_name",$adm['name']);
		 $tpl->set_var("admin_email",$adm['email']);
		 $tpl->set_var("admin_level",$adm['level']);

		 $tpl->parse("AdmListRow",true);
	   }
	}

     // End of output block

     }

   }

   function systemBackup() {
     global $needsecure,$std,$tpl,$DB,$INFO;





   }

   function systemStat() {
     global $needsecure,$std,$tpl,$DB,$INFO;

        $tpl->set_var("words_stat_system",$needsecure->words['stat_system']);
	$tpl->set_var("words_stat_sys_h",$needsecure->words['stat_sys_h']);
	$tpl->set_var("words_order_by",$needsecure->words['order_by']);
	$tpl->set_var("words_sort_key",$needsecure->words['sort_key']);
	$tpl->set_var("words_ascending",$needsecure->words['ascending']);
	$tpl->set_var("words_descending",$needsecure->words['descending']);
	$tpl->set_var("words_username",$needsecure->words['username']);
	$tpl->set_var("words_count_visits",$needsecure->words['count_visits']);
	$tpl->set_var("words_used_lang",$needsecure->words['used_lang']);
	$tpl->set_var("words_ip_address",$needsecure->words['ip_address']);
	$tpl->set_var("words_user_agent",$needsecure->words['user_agent']);
	$tpl->set_var("words_sort_now",$needsecure->words['sort_now']);


	if ( empty($needsecure->input['order']) or !$needsecure->input['order'] ) {
            $order = 'count_visits';
	} else {
            $order = $needsecure->input['order'];
	}

        if ( empty($needsecure->input['sort']) or !$needsecure->input['sort'] ) {
            $sort = 'DESC';
	} else {
	    $sort = $needsecure->input['sort'];
	}



	$DB->query("SELECT id,name,lang,count_visits,member_ip,member_browser FROM ns_members ORDER BY {$order} {$sort}");

          if ( $DB->get_num_rows() > 0 ) {
	        while ( $stat = $DB->fetch_row() ) {

	                $tpl->set_var("stat_member_id",$stat['id']);
	                $tpl->set_var("stat_member_name",$stat['name']);
	                $tpl->set_var("stat_member_lang",$stat['lang']);
	                $tpl->set_var("stat_member_count_visits",$stat['count_visits']);
	                $tpl->set_var("stat_member_ip",$stat['member_ip']);
	                $tpl->set_var("stat_member_browser",$stat['member_browser']);

	                $tpl->parse("StatRow",true);

	        }
          } else {
             $tpl->set_var("StatRow",false);
	  }

   }

   function adminLogs() {
     global $needsecure,$std,$tpl,$DB,$INFO;

     if ( $needsecure->input['step'] == 'rem' ) {

        $DB->query("DELETE FROM ns_admin_logs WHERE id='{$needsecure->input['id']}'");

        $url = "{$needsecure->base_url}&act=idx&code=07";
	@flush();
	$std->redirectPage($url,"Admin log record removed successfully");

     } else {

        $tpl->set_var("words_logging_system",$needsecure->words['logging_system']);
	$tpl->set_var("words_admin_logs_h",$needsecure->words['admin_logs_h']);

        if ( !$needsecure->input['id'] or $needsecure->input['id'] == '' or empty($needsecure->input['id']) ) {

           $tpl->set_var("AdminLogRow",false);

	   $DB->query("SELECT id,ctime,admin_name,admin_id,admin_action FROM ns_admin_logs ORDER BY ctime DESC LIMIT 0,20");
           if ( $DB->get_num_rows() < 1 ) {
               $tpl->set_var("AdminLogListRow",false);
	   } else {

            while ( $log = $DB->fetch_row() ) {

	                $tpl->set_var("log_id",$log['id']);
	                $tpl->set_var("log_ctime",$log['ctime']);
	                $tpl->set_var("log_admin_id",$log['admin_id']);
                    $tpl->set_var("log_admin_name",$log['admin_name']);
		            $log_admin_action = "".substr($log['admin_action'],0,30)."...";
	                $tpl->set_var("log_admin_action",$log_admin_action);

	                $tpl->parse("AdminLogListRow",true);

	        }
	   }

	} else {

           $tpl->set_var("AdminLogListRow",false);

	       $DB->query("SELECT id,ctime,admin_id,admin_name,admin_level,admin_email,admin_action,admin_ip FROM ns_admin_logs WHERE id='{$needsecure->input['id']}'");

	        while ( $log = $DB->fetch_row() ) {

	                $tpl->set_var("log_id",$log['id']);
	                $tpl->set_var("log_ctime",$log['ctime']);
	                $tpl->set_var("log_admin_id",$log['admin_id']);
	                $tpl->set_var("log_admin_name",$log['admin_name']);
	                $tpl->set_var("log_admin_level",$log['admin_level']);
	                $tpl->set_var("log_admin_email",$log['admin_email']);
	                $tpl->set_var("log_admin_action",$log['admin_action']);
	                $tpl->set_var("log_admin_ip",$log['admin_ip']);

	                $tpl->parse("AdminLogRow",true);

	        }
	}

     }

   }

} // End of class Idx;


?>