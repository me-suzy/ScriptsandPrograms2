<?php
//Japanese (UTF-8)

//the above line is needed so that pivot knows how to display it in the user info.
//it also needs to be on the 2rd line.

// Japanese translation of Pivot lang file
// Created by Davilin(Yuji Takenaka) (webmaster@davilin.com)
// Last updated by Davilin(Yuji Takenaka) (www.davilin.com/pivot_jpn/) 2005.05.02

// allow for different encoding for non-western languages
$encoding="UTF-8";
$langname="ja_utf8";


//		General		\\
$lang['general'] = array (
	'yes' => 'はい',	//affirmative
	'no' => 'いいえ',	//negative
	'go' => 'GO!',		//proceed

	'minlevel' => 'アクセス権限がありません。',	
	'email' => 'Email',			
	'url' => 'URL',
	'further_options' => "追加オプション",
	'basic_view' => "標準ビュー",
	'basic_view_desc' => "必要最小限のフィールドのみ表示",
	'extended_view' => "拡張ビュー",
	'extended_view_desc' => "編集可能なすべてのフィールドを表示",
	'select' => "選択",
	'cancel' => "キャンセル",
	'delete' => '削除',
	'welcome' => "ようこそ %build%",
	'write' => "書き込み",
	'write_open_error' => "書き込みエラー。ファイルを開けません。",
	'write_write_error' => "書き込みエラー。ファイルに書き込めません。",
	'done' => "完了",
	'shortcuts' => "ショートカット",
	'cantdelete' => "エントリ  %title% を削除することは許可されていません。",
	'cantdothat' => "エントリ %title% を編集することは許可されていません。",
);


$lang['userlevels'] = array (
		'Superadmin', 'Administrator', 'Advanced', 'Normal', 'Moblogger'
		//  this one might be a bit hard to translate, but basically it's an order of
		//  power or trust.  Superadmin would be the person in charge - no one can do
		//  anything about his decisions. Admin is only regulated by the Superadmin, 
		//  Advanced by the Admin and Superadmin, etc..
		//  Just get the idea of it.
);


$lang['numbers'] = array (
	'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16'
);


$lang['months'] = array (
	'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
);	

		
$lang['months_abbr'] = array (
	'Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
);


$lang['days'] = array (
	'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
);


$lang['days_abbr'] = array (
	'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'	
);


$lang['days_calendar'] = array (
	'S', 'M', 'T', 'W', 'T', 'F', 'S'
); 


$lang['datetime_words'] = array (
	'年', '月', '週', '日', '時', '分', '秒'	//the actual words for them.
);


//		Login Page		\\
$lang['login'] = array (
	'title' => 'ログイン',
	'name' => 'ユーザー名',
	'pass' => 'パスワード',
	'remember' => 'ユーザー名とパスワードを記憶',
	'rchoice' => array (
		'0' => '記憶しない',
		'1' => 'ユーザー名とパスワード',
		'2' => 'オートログイン'
	),
	'delete_cookies_desc' => '正しいユーザー名とパスワードでログインできない場合はクッキーを削除してみて下さい:',
	'delete_cookies' => 'クッキーの削除',
	'retry' => 'ユーザー名もしくはパスワードが違います。',
	'banned' => '10回以上ログインに失敗したため、今から12時間はログインすることは出来ません。',

);


//		Main Bar		\\
	$lang['userbar'] = array (
	'main' => '概要',
	'entries' => 'エントリ',
	'submit' => '新規エントリ',
	'comments' => 'コメント',
	'trackbacks' => 'トラックバック',
	'modify' => 'エントリ編集',
	'userinfo' => 'ユーザー情報',
	'u_settings' => 'ユーザー設定',
	'u_marklet' => 'ブックマーク設定',
	'files' => 'メディア管理',
	'upload' => 'アップロード',
	'stats' => '統計データ',
	'admin' => '管理者メニュー',

	'main_title' => 'システム管理',
	'entries_title' => 'エントリ管理',
	'submit_title' => '新規エントリ管理',
	'comments_title' => 'コメント管理',		
	'trackbacks_title' => 'トラックバック管理',		
	'modify_title' => 'エントリ編集',
	'userinfo_title' => 'ユーザー情報管理',
	'u_settings_title' => 'ユーザー設定管理',
	'u_marklet_title' => 'ブックマーク管理',
	'files_title' => 'メディア管理',
	'upload_title' => 'ファイルアップロード',
	'uploaded_success' => 'ファイルアップロード完了',
	'stats_title' => '統計データ管理',
	'updatetitles_title' => 'ログ・統計データ閲覧',
	'admin_title' => '管理者メニュー',
	'recent_entries' => '最近のエントリ',
	'recent_comments' => '最近のコメント',
);


$lang['adminbar'] = array (
	//		Admin Bar		\\
	//'trebuild' => 'すべてのファイルを再構築', rolled into maintenance
	'seeusers' => 'ユーザー',
	'seeconfig' => '設定',
	'filemappings' => 'ファイルマッピング',
	'templates' => 'テンプレート',
	'maintenance' => 'メンテナンス',
	'regen' => 'すべてのファイルを再構築',
	'blogs' => 'ウェブログ',
	'categories' => 'カテゴリ',
	'verifydb' => 'データベース検証',
	'buildindex' => 'インデックスファイル構築',
	'buildsearchindex' => '検索用インデックス構築',
	'buildfrontpage' => 'フロントページ構築',
	'sendping' => 'ピング送信',


	'backup' => 'バックアップ',
	'description' => '解説',
	'conversion' => '変換',
	'seeusers_title' => 'ユーザの作成・編集・削除',
	'userfields' => '公開するユーザー情報',
	'userfields_title' => 'ユーザー情報の作成・編集・削除',
	'seeconfig_title' => '設定ファイルの編集',
	'filemappings_title' => 'Pivotによって作成されたファイルの概要',
	'templates_title' => 'テンプレートの作成・編集・削除',
	'maintenance_title' => 'ファイルメンテナンスの実行',
	'regen_title' => 'Pivotによって作成されたファイルとアーカイブの再構築',
	'blogs_title' => 'ウェブログの作成・編集・削除',
	'blogs_edit_title' => '次のウェブログの設定を編集 ',
	'categories_title' => 'カテゴリの作成・編集・削除',	
	'verifydb_title' => 'データベースの整合性を検証',
	'buildindex_title' => 'データベースインデックスの再構築',
	'buildsearchindex_title' => 'エントリ検索用インデックスの再構築',
	'buildfrontpage_title' => 'フロントページ・最終アーカイブ・RSSフィードの再構築',
	'backup_title' => 'エントリのバックアップ作成',
	'backup_config' => '設定ファイルのバックアップ作成',
	'backup_config_desc' => 'zip形式で圧縮された設定ファイルをダウンロードすることが出来ます。',
	'ipblocks' => 'IPアドレスブロック設定',
	'ipblocks_title' => 'ブロックするIPアドレスの閲覧・編集',
	'ipblocks_stored' => 'ブロックするIPアドレスリストを更新しました。',
	'ipblocks_store' => 'IPアドレスの保存',
	'ignoreddomains' => '無効ドメイン設定',
	'ignoreddomains_title' => '無効なドメインの閲覧・編集',
	'ignoreddomains_stored' => '無効なドメインリストを更新しました。',
	'ignoreddomains_store' => '無効なドメインの保存',
	'fileexplore' => 'ファイルエクスプローラー',
	'fileexplore_title' => 'ファイル閲覧（テキストファイル、データベースファイル）',
	'sendping_title' => 'アップデートトラッカーにピング送信',
	'buildindex_start' => 'インデックスを構築中... 中断しないで下さい。',
	'buildsearchindex_start' => '検索インデックス構築中... 中断しないで下さい。',
	'buildindex_finished' => 'インデックス収集完了! 構築時間：%num% 秒',

	'filemappings_desc' => 'それぞれのウェブログに対する概要を確認することが出来ます。各ウェブログに対して作成されたファイルとテンプレートも同時に表示されますので、ファイル関連のトラブルシューティングにとても有効です。',
	
	'debug' => 'デバッグウィンドウを開く',

);


$lang['templates'] = array (
	'rollback' => 'ロールバック',
	'create_template' => 'テンプレート作成',
	'create_template_info' => '一からテンプレートを作成',
	'no_comment' => 'コメントはありません。',
	'comment' => 'コメント*',
	'comment_note' => '(*注意：コメントは<b>一回だけ</b>保存できます)',
	'create' => 'テンプレート作成',
	'editing' => '編集中',
	'filename' => 'ファイル名',
	'save_changes' => '変更を保存',
	'save_template' => 'テンプレートの変更を保存しました。',		
	'aux_template' => '補助テンプレート',
	'sub_template' => 'サブテンプレート',
	'standard_template' => '標準テンプレート',
	'feed_template' => 'XMLフィードテンプレート',
	'css_template' => 'CSSファイル',
	'txt_template' => 'テキストファイル',	
	'php_template' => 'PHPファイル',	
);


//		Admin			\\
// bob notes: Mark made these, i think they should be replaced by the 'adminbar']['xxx_title'] ones
$lang['admin'] = array (
	'seeusers' => 'ユーザーの作成・編集・削除',
	'seeconfig' => '設定ファイルの編集',
	'templates' => 'テンプレートの作成・編集・削除',
	'maintenance' => 'ファイルの定期メンテナンス（ファイルの再構築、データベースの検証、インデックスの再構築およびバックアップ）の実行',
	'regen' => 'Pivotによって作成されたすべてのページを再構築',
	'blogs' => 'ウェブログの作成・編集・削除',
);


//		Maintenace		\\	
$lang['maint'] = array (
	'title' => 'メンテナンス',	
	'gen_arc_title' => 'アーカイブ作成', /* bob notes: redundant, see 'regen' */
	'gen_arc_text' => 'アーカイブファイルの再作成', /* bob notes: redundant, see 'regen' */
	'xml_title' => 'XMLフィード検証', /* bob notes: replace with more general 'Verify DB' */
	'xml_text' => 'XMLフィードファイルの整合性を検証（必要に応じて修復）', /* bob notes: replace with more general 'Verify DB' */
	'backup_title' => 'バックアップ',
	'backup_text' => 'Pivotの主要なファイルのバックアップ',
);


//		Stats and referers		\\
$lang['stats'] = array (
	'show_last' => "参照元を表示",
	'20ref' => "最近の20件",
	'50ref' => "最近の50件",
	'allref' => "すべての参照元",
	'showunblocked' => "許可された参照元のみ表示",
	'showall' => "全ての参照元を表示",
	'updateref' => "参照元とエントリのマッピングを更新",
	'hostaddress' => 'ホストアドレス（IPアドレス）', 
	'whichpage' => 'ページ',

	'getting' => '新しいエントリを反映中...',
	'awhile' => '多少時間がかかります... 中断しないで下さい。',
	'firstpass' => '最初のパス',
	'secondpass' => '二回目のパス',
	'nowuptodate' => '参照元とエントリのマッピングを更新中...',
	'finished' => 'マッピングの更新完了',
);


//		User Info		\\
	$lang['userinfo'] = array (
	'editfields' => 'ユーザー情報の編集',
	'desc_editfields' => 'ユーザーに関する情報を編集',
	'username' => 'ユーザー名',
	'pass1' => 'パスワード',
	'pass2' => 'パスワード（確認）',
	'email' => 'Email',
	'userlevel' => '権限レベル',	
	'userlevel_desc' => 'ユーザーが実行できる項目は、権限レベルによって決定されます。',
	'language' => '使用言語',	
	'lastlogin' => '最終ログイン',
	'edituser' => '編集',  //the link to.. well, edit the user (also the title)
	'edituserinfo' => 'ユーザー情報の編集',
	'newuser' => '新規ユーザーの作成',
	'desc_newuser' => '新しいユーザー（ログインアカウント）を作成します。',
	'newuser_button' => '作成',
	'edituser_button' => '更新',
	'pass_too_short' => 'パスワードは4文字以上にして下さい。',
	'pass_equal_name' => 'ユーザー名と同じパスワードは使用できません。',
	'pass_dont_match' => 'パスワードと確認用パスワードが適合しません。',
	'username_in_use' => 'ユーザー名が既に使用されています。',
	'username_too_short' => 'ユーザー名は3文字以上にして下さい。',
	'username_not_valid' => 'ユーザー名には次の文字のみが使用できます。A-Z, 0-9, アンダースコア（_）',
	'not_good_email' => 'Emailアドレスが適切な形ではありません。',	
	'c_admin_title' => 'Administator作成',
	'c_admin_message' => $lang['userlevels']['1'].' は、Pivotに対するすべての操作を行うことが出来ます. このユーザーを作成してもよろしいですか？',
);


//		Config Page		\\		
	$lang['config'] = array (
	'save' => '設定を保存',

	'sitename' => 'サイト名',
	'defaultlanguage' => 'デフォルトの使用言語',
	'defaultencoding' => '使用する文字コード',
	'defaultencoding_desc' => '使用する文字コード（例えば、utf-8やiso-8859-1）を決定します。空欄にしておけば、言語ファイルより適切な文字コードが選択されます。',
	'siteurl' => 'サイトアドレス',
	'header_fileinfo' => 'ファイル情報',
	'localpath' => 'ローカルパス',
	'debug_options' => 'デバッグオプション',
	'debug' => 'デバッグモード',
	'debug_desc' => 'デバッグ情報を表示します。',
	'log' => '作業ログ',
	'log_desc' => '行った作業のログを記録します。',

	'unlink' => 'Unlinkモード',
	'unlink_desc' => '通常は何の影響もありません。ただし、PHPがセーフモードで動作しているサーバーでは、Pivotが正しく動作しない可能性があります。その場合にこの機能を有効にすることで解決する場合があります。',
	'chmod' => 'ファイル属性（chmod）',
	'chmod_desc' => 'Pivot（PHP）により作成されるファイルの属性を決定します（通常は\'0644\'もしくは\'0755\'）。意味が分からない場合は変更しないで下さい。',
	'header_uploads' => 'ファイルアップロード設定',
	'upload_path' => 'アップロードディレクトリ',	
	'upload_accept' => 'アップロード可能なファイル形式',			
	'upload_extension' => 'デフォルトの拡張子',
	'upload_save_mode' => 'ファイルの上書き',
	'make_safe' => '安全なファイル名',
	'c_upload_save_mode' => '増分値を使用',
	'max_filesize' => '最大ファイルサイズ',
	'header_datetime' => '日付と時刻',
	'timeoffset_unit' => '時刻オフセット単位',
	'timeoffset' => '時刻オフセット',
	'header_extra' => 'その他の設定',
	'wysiwyg' => 'Wysiwygエディタの使用',
	'wysiwyg_desc' => 'デフォルトでWysiwygエディタを使用するかどうかを決定します。ユーザー毎に設定が可能です。',
	'basic_view' => '標準ビューの使用',
	'basic_view_desc' => '新規エントリ作成の際にデフォルトで使用するビューを決定します。',
	'def_text_processing' => 'デフォルトのテキスト処理', 
	'text_processing' => 'テキスト処理',
	'text_processing_desc' => 'Wysiwygエディタを<b>使用しない</b>場合の、デフォルトでのテキスト処理を設定します。\'改行を変換\' は、改行を&lt;br&gt;タグに変換します。<a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> はパワフルで簡単なマークアップ言語です。',
	'none' => 'テキスト処理無し',
	'convert_br' => '改行を &lt;br /&gt; に変換',
	'textile' => 'Textileを使用',

	'allowed_cats' => '投稿を許可するカテゴリ',
	'allowed_cats_desc' => '投稿することの出来るカテゴリを設定します。',
	'delete_user' => "ユーザの削除",
	'delete_user_desc' => "ユーザーを削除します。削除してもこのユーザーによって投稿されたエントリは残ります。",
	'delete_user_confirm' => 'ユーザー %s を削除します。よろしいですか？',
	
	'setup_ping' => 'ピング設定',
	'ping_use' => 'アップデートトラッカーにピングを送信する',
	'ping_use_desc' => '新規エントリの投稿時に、自動でピングを送信するかどうかを設定します。blogrolling.com などのサービスには、ピングを送信する必要があります。',	
	'ping_urls' => 'ピング送信先URL',
	'ping_urls_desc' => '改行もしくは|で区切ることで複数の送信先を指定することが出来ます。アドレスに http:// は含めない様、注意してください。<br /><b>rpc.weblogs.com/RPC2</b>（広く使用されているピンガーです）<br /><b>pivotlog.net/pinger</b>（Pivotlog ピンガーですが、未だ稼動していません）<br /><b>rcs.datashed.net/RPC2</b>（euro.weblogs.com ピンガー）<br /><b>ping.blo.gs</b>（blo.gs ピンガー）<br />',

	'setup_tb' => 'トラックバック設定',
	'tb_password' => 'パスワード',
	'tb_password_desc' => 'このパスワードはトラックバックを削除するときに使用します。設定されていない場合、トラックバックの削除は出来ません。',
	'tb_email' => 'Email',
	'tb_email_desc' => 'トラックバックが付けられたときに、登録したEmailに通知メールを送信します。',
	'tb_rss' => 'トラックバック用RSSフィード',

	'new_window' => 'リンクを新規ウィンドウで開く',
	'emoticons' => 'Emoticonを使用する',
	'javascript_email' => 'Emailアドレスのエンコード',	
	'new_window_desc' => 'エントリ内に記載されているリンクを新しいウィンドウで開くかどうかを設定します。',

	'mod_rewrite' => 'Mod_rewriteオプション',
	'mod_rewrite_desc' => 'Apache\のMod_rewriteオプションを使用するかどうかを設定します。エントリのURLを www.mysite.com/pivot/entry.php?id=134 から www.mysite.com/archive/2003/05/30/nice_weather へ変更することが可能です. 詳しくはサーバーの取扱説明書を確認して下さい。',
	'mod_rewrite_1' => '/archive/2005/04/28/エントリタイトル',
	'mod_rewrite_2' => '/archive/2005-04-28/エントリタイトル',
	'mod_rewrite_3' => '/entry/1234',
	'mod_rewrite_4' => '/entry/1234/エントリタイトル',

	'search_index' => '検索インデックスの自動更新',
	'search_index_desc' => 'エントリが作成もしくは変更された際に、自動で検索用のインデックスを更新するかどうかを設定します。',

	'default_allow_comments' => 'コメントを許可',
	'default_allow_comments_desc' => 'デフォルトでエントリにコメントを許可するかどうかを設定します。',	

  'maxhrefs' => '最大ハイパーリンク数',
  'maxhrefs_desc' => '一つのコメントに含めることが出来るハイパーリンクの最大値を設定します。迷惑なコメントスパムに対して有効です。0は制限なしを意味します。',
  'rebuild_threshold' => '再構築時の制限時間（秒）',
  'rebuild_threshold_desc' => 'Pivotがページを再構築する際に必要とする時間を制限します（デフォルトは28秒）。再構築時に問題が発生するようであれば、この時間を10秒まで短くしてみて下さい。',
	'default_introduction' => '本文（イントロダクション・ボディ）の初期値',
	'default_introduction_desc' => '新規エントリフォームのイントロダクションとボディにあらかじめ入力されている値を設定します。通常は空欄です。',

	'upload_autothumb'	=> '自動サムネール作成',
	'upload_thumb_width' => 'サムネール幅',
	'upload_thumb_height' => 'サムネール高さ',
	'upload_thumb_remote' => '画像クロップ用スクリプト',
	'upload_thumb_remote_desc' => 'サーバーに画像クロップに必要なライブラリが無い場合に、代替のスクリプトを設定することが出来ます。',

	'extensions_header' => 'エクステンション用ディレクトリ',
	'extensions_desc'   => 'Pivot用のエクステンションを保存しておく場所を設定します。詳細はドキュメントを確認して下さい。',
	'extensions_path'   => 'ディレクトリパス',

);


//		Weblog Config	\\
$lang['weblog_config'] = array (
	'edit_weblog' => 'ウェブログの編集',
	'edit_blog' => 'ウェブログを編集します。',
	'new_weblog' => '新規ウェブログ',
	'new_weblog_desc' => '新しくウェブログを作成します。',
	'del_weblog' => 'ウェブログの削除',
	'del_this_weblog' => 'ウェブログを削除します。',
	'create_new' => '新規ウェブログの作成',
	'subw_heading' => 'ウェブログに使用するサブウェブログと、ウェブログに発行するカテゴリを設定します。',
	'create' => '作成',
	
	'create_1' => 'ウェブログの作成・変更 ステップ 1/3',
	'create_2' => 'ウェブログの作成・変更 ステップ 2/3',
	'create_3' => 'ウェブログの作成・変更 ステップ 3/3',

	'name' => 'ウェブログ名',
	'payoff' => 'ペイオフ',
	'payoff_desc' => 'サブタイトルもしくはウェブログを簡単に表す説明として使用します。',
	'url' => 'ウェブログのURL',
	'url_desc' => '通常は空欄です。もし、このウェブログがフレームセットの一部として使用される場合やサーバーサイドでインクルードされる場合は、該当するファイルのURLを設定します。',
	'index_name' => 'フロントページのファイル名',
	'index_name_desc' => 'フロントページ（インデックスページ）のファイル名を設定します。通常は、\'index.html\' や \'index.php\' が使用されます。',

	'ssi_prefix' => 'SSI プレフィックス',
	'ssi_prefix_desc' => 'SSIでウェブログを動作させる場合（推奨はしませんが・・・）、SSI用のプレフィックスを設定することが出来ます。例えば、\'index.shtml?p=\'。意味が分からない場合は、空欄にしておいて下さい。',

	'front_path' => 'フロントページのパス',
	'front_path_desc' => 'フロントページを設置する場所を絶対もしくは相対パスで指定します。',
	'file_format' => 'ファイル名',
	'entry_heading' => 'エントリの設定',
	'entry_path' => 'エントリのパス',
	'entry_path_desc' => 'ライブエントリを<b>使用しない</b>場合のみ、エントリ毎のページを保存する場所を絶対もしくは相対パスで指定します。',
	'live_comments' => 'ライブエントリ',
	'live_comments_desc' => '\'ライブエントリ\'を使用すると、Pivotはエントリ毎のファイルを作成しません（推奨設定）。',
	'readmore' => '\'続きを読む\' 用のテキスト',
	'readmore_desc' => 'エントリに続きがある場合に、フロントページに表示するテキストを設定します。空欄の場合は言語ファイルに設定されたテキストが使用されます。',
	
	'arc_heading' => 'アーカイブページ',
	'arc_index' => 'インデックスファイル',
	'arc_path' => 'アーカイブのパス',
	'archive_amount' => 'アーカイブの量',
	'archive_unit' => '作成の頻度',
	'archive_format' => 'ファイルのフォーマット',
	'archive_none' => 'アーカイブページを作成しない',
	'archive_weekly' => '週毎にアーカイブページを作成',
	'archive_monthly' => '月毎にアーカイブページを作成',
	'archive_yearly' => '年毎にアーカイブページを作成',

	'archive_link' => 'アーカイブへのリンク',
	'archive_linkfile' => 'リンクフォーマット',	
	'archive_order' => 'リンク表示順序',
	'archive_ascending' => '昇順（古いものから）',
	'archive_descending' => '降順（新しいものから）',

	'templates_heading' => 'テンプレート',
	'frontpage_template' => 'フロントページ用テンプレート',
	'frontpage_template_desc' => 'フロントページのレイアウトを決定するテンプレートファイルを指定します。',
	'archivepage_template' => 'アーカイブページ用テンプレート',
	'archivepage_template_desc' => 'アーカイブページのレイアウトを決定するテンプレートファイルを指定します。フロントページと同じでも構いません。',	
	'entrypage_template' => 'エントリページ用テンプレート',
	'entrypage_template_desc' => 'エントリページのレイアウトを決定するテンプレートファイルを指定します。',	
	'extrapage_template' => 'エクストラページ用テンプレート',
	'extrapage_template_desc' => '検索結果を表示するページなどのレイアウトを決定するテンプレートファイルを指定します。',

	'shortentry_template' => 'ショートエントリ用テンプレート',
	'shortentry_template_desc' => 'フロントページやアーカイブページに表示するエントリのレイアウトを決定するテンプレートファイルを指定します。',	
	'num_entries' => 'エントリ数',
	'num_entries_desc' => 'フロントページやカテゴリ別ページに表示されるエントリ数を指定します。',	
	'offset' => 'オフセット',
	'offset_desc' => 'オフセットを指定することで、その数だけ表示されるエントリがスキップされます。\'以前のエントリ\'リストを作成する場合などに使用します。',
	'comments' => 'コメントを許可',
	'comments_desc' => 'エントリにコメントを許可するかどうかを設定します。',	

	'publish_cats' => '発行するカテゴリ',

	'setup_rss_head' => 'RSS/Atomフィード',
	'rss_use' => 'フィードの作成',
	'rss_use_desc' => 'このウェブログに対して自動的にRSS/ATOMフィードを作成するかどうかを設定します。',
	'rss_filename' => 'RSSファイル名',
	'atom_filename' => 'Atomファイル名',
	'rss_path' => 'フィードのパス',
	'rss_path_desc' => 'これらのフィードファイルを保存する場所を、相対もしくは絶対パスで指定します。',
//	'rss_size' => 'Feed Entry Length',	/* DEPRECATED */
//	'rss_size_desc' => 'The length (in characters) of an entry in the Feed files', /* DEPRECATED */
	'rss_full' => '完全なフィードを作成',
	'rss_full_desc' => '\'いいえ\'を設定することで、簡単な概要のみを含んだフィードを作成します。これらのフィードはあまり有効ではありません。',
	'rss_link' => 'フィードリンク',
	'rss_link_desc' => 'フィードに含まれるメインページへのリンクを指定します。空欄の場合はフロントページへのリンクが指定されます。',
	'rss_img' => 'フィードイメージ', 
	'rss_img_desc' => '特定のイメージファイルをフィードファイルに含めます（RSSリーダーによっては、イメージの表示が可能）。空欄か完全なURLを指定します。',
	
	'lastcomm_head' => '最近のコメント',
	'lastcomm_amount' => '表示数',
	'lastcomm_length' => '表示文字数',
	'lastcomm_format' => '表示フォーマット',
	'lastcomm_format_desc' => 'フロントページなどの\'最近のコメント\'に表示されるコメントに関する設定です。',
	'lastcomm_nofollow' => '\'Nofollow\'属性の使用',
	'lastcomm_nofollow_desc' => 'Google PageRankの向上を目的としたスパム対策として、コメントリンクおよびリファラーリンクにrel="nofollow" 属性を追加します。',

	'lastref_head' => '最近の参照元',
	'lastref_amount' => '表示数',
	'lastref_length' => '表示文字数',
	'lastref_format' => '表示フォーマット',
	'lastref_format_desc' => 'フロントページなどの\'最近の参照元\'に表示される参照元リンクの設定です。',
	'lastref_graphic' => 'アイコンの使用',
	'lastref_graphic_desc' => '参照元が有名なサーチエンジンの場合に小さなアイコンを表示します。',
	'lastref_redirect' => 'リダイレクト',
	'lastref_redirect_desc' => 'Google PageRankの向上を目的としたリファラースパムに対して、その効果を無くします。',

	'various_head' => 'その他の設定',
	'emoticons' => 'Emoticonの使用',
	'emoticons_desc' => 'Emoticons, 例えば :-), を使用した場合、対応するアイコンに変換します。日本式の顔文字を使用する場合は\'いいえ\'を選択したほうが無難です。',
	'encode_email_addresses' => 'Emailアドレスのエンコード',
	'encode_email_addresses_desc' => 'メールスパム対策として、JavaScriptでEmailアドレスをエンコードするかどうかを設定します。',
	'target_blank' => '新規ウィンドウ',
	'xhtml_workaround' => 'XHTML Workaround',
	'target_blank_desc' => '\'はい\'に設定した場合、エントリ内のリンクに target="_blank" が追加され、リンク先を新しいウィンドウで開きます。\'XHTML workaround\'は、ウェルフォームドXHTMLを満足するため、rel="external" 属性が付加されます。',

	'date_head' => '日付表示',
	'full_date' => '完全な日付形式',
	'full_date_desc' => 'エントリページに表示される年月日と時刻を含んだ完全な日付のフォーマットを指定します。',
	'entry_date' => '時刻形式',
	'diff_date' => '投稿間隔',
	'diff_date_desc' => '前回の投稿と日付が異なる場合に、その間隔を表す日付形式を指定します。',
	'language' => '使用言語',
	'language_desc' => '使用する日付や数の形式を決定する言語を指定します。またそのページの文字コードも設定します。',	

	'comment_head' => 'コメント',
	'comment_sendmail' => 'メールを送信',
	'comment_sendmail_desc' => 'コメントが付けられた時に、ウェブログの管理者にメールを送信するかどうかを指定します。',
	'comment_emailto' => 'あて先',
	'comment_emailto_desc' => '送信先のメールアドレスを設定します。複数の送信先はカンマで区切って指定します。',
	'comment_texttolinks' => 'ハイパーリンク化',
	'comment_texttolinks_desc' => 'コメントに単なるテキストとして記載されたURLやメールアドレスを、ハイパーリンクにするかどうかを指定します。',
	'comment_wrap' => '強制改行',
	'comment_wrap_desc' => '長い文字列によってサイトのレイアウトが崩れる防ぐために、強制的に改行するための文字数を指定します。',
	'comments_text_0' => 'コメントが無い場合',
	'comments_text_1' => 'コメントが一つの場合',
	'comments_text_2' => 'コメントが二つ以上の場合',
	'comments_text_2_desc' => 'コメントの数に応じて表示させるテキストを指定します。空欄の場合は言語ファイルの初期値が使用されます。',

	'comment_pop' => 'コメントポップアップ',
	'comment_pop_desc' => 'コメントのページやエントリ毎のページをポップアップウィンドウに表示させるかどうかを指定します。',
	'comment_width' => 'ウィンドウの幅',
	'comment_height' => 'ウィンドウの高さ',
	'comment_height_desc' => 'ポップアップウィンドウの幅と高さをピクセルで指定します。',
			
	'comment_format' => 'コメントのフォーマット',
	'comment_format_desc' => 'エントリページに表示されるコメントのフォーマットを指定します。',

	'comment_reply' => '返信元リンクのフォーマット',
	'comment_reply_desc' => '訪問者が特定のコメントに対してコメントできるようにするための、その特定のコメントを示すリンクのフォーマットを指定します。',
	'comment_forward' => '返信元のフォーマット',
	'comment_forward_desc' => 'コメントが<b>付けられた</b>コメントテキストに対するフォーマットを指定します。',
	'comment_backward' => '返信先のフォーマット',
	'comment_backward_desc' => '他のコメントにコメントを<b>付けた</b>コメントテキストに対するフォーマットを指定します。',
	
	'comment_textile' => 'Textileの使用',
	'comment_textile_desc' => '\'はい\'を指定することで、訪問者はコメント内に<a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a>を使用することが出来ます。',
	'save_comment' => 'コメントを保存',

        'trackback_head' => 'トラックバック',
	'trackback_sendmail' => 'メールを送信',
	'trackback_sendmail_desc' => 'トラックバックが送信された場合に、ウェブログの管理者にメールを送信するかどうかを指定します。',
	'trackback_emailto' => 'あて先',
	'trackback_emailto_desc' => '送信先のメールアドレスを設定します。複数の送信先はカンマで区切って指定します。',
	'trackbacks_text_0' => 'トラックバックが無い場合',
	'trackbacks_text_1' => 'トラックバックが一つの場合',
	'trackbacks_text_2' => 'トラックバックが二つ以上の場合',
	'trackbacks_text_2_desc' => 'トラックバックの数に応じて表示させるテキストを指定します。空欄の場合は言語ファイルの初期値が使用されます。',
	'trackback_pop' => 'トラックバックポップアップ',
	'trackback_pop_desc' => 'トラックバックのページやエントリ毎のページをポップアップウィンドウに表示させるかどうかを指定します。',
	'trackback_width' => 'ウィンドウの幅',
	'trackback_height' => 'ウィンドウの高さ',
	'trackback_height_desc' => 'ポップアップウィンドウの幅と高さをピクセルで指定します。',
	'trackback_format' => "トラックバックのフォーマット",
	'trackback_format_desc' => "エントリページに表示される送信されたトラックバックの表示フォーマットを指定します。",
	'trackback_link_format' => "トラックバックURLのフォーマット",
        'save_trackback' => 'トラックバックを保存',

	'saved_create' => '新しいウェブログが作成されました。',
	'saved_update' => 'ウェブログの設定が更新されました。',
	'deleted' => 'ウェブログは削除されました。',
	'confirm_delete' => 'ウェブログ %1 を削除します。よろしいですか？',	

	'blogroll_heading' => 'Blogroll',
	'blogroll_id' => 'Blogrolling ID #',
	'blogroll_id_desc' => 'オプションとして、<a href="http://www.blogrolling.com" target="_blank">blogrolling.com</a> blogroll をウェブログに含めることが出来ます。Blogrollingはリンクの管理をする優れたサービスです。Blogrollingを使用しない場合は先に進んでください。興味がある場合は blogrolling.com にログインして\'2ef8b42161020d87223d42ae18191f6d\'のようなID#を入手してください。',
	'blogroll_fg' => 'Text Color',
	'blogroll_bg' => 'Background Color',
	'blogroll_line1' => 'Line Color 1',
	'blogroll_line2' => 'Line Color 2',
	'blogroll_c1' => 'Color 1',
	'blogroll_c2' => 'Color 2',
	'blogroll_c3' => 'Color 3',
	'blogroll_c4' => 'Color 4',
	'blogroll_c4_desc' => 'これらの色は blogroll に使用されます。Color1 から Color4 はどの程度最近更新されたかを示します。',
);


$lang['upload'] = array (
	//		File Upload		\\
	'preview' => 'リスト表示',
	'thumbs' => 'サムネール表示',
	'create_thumb' => '（サムネール作成）',
	'title' => 'ファイル',
	'thisfile' => 'ファイルのアップロード:',
	'button' => 'アップロード',
	'filename' => 'ファイル名',
	'thumbnail' => 'サムネール',
	'date' => '日付',
	'filesize' => 'ファイルサイズ',
	'dimensions' => '幅×高さ',		
	'delete_title' => 'イメージの削除',
	'areyousure' => 'ファイル %s を削除します。よろしいですか？',
	'picheader' => 'このイメージをを削除します。',
	'create' => '作成',
	'edit' => '編集',

	'insert_image' => 'イメージの挿入',
	'insert_image_desc' => 'イメージをアップロードするか、既にアップロードしてあるイメージを選択してください。',
	'insert_image_popup' => 'ポップアップイメージの挿入',
	'insert_image_popup_desc' => 'イメージをアップロードするか、既にアップロードしてあるイメージを選択した後、ポップアップのトリガーとなるテキストもしくはサムネールを選択して下さい。',
	'choose_upload' => 'アップロード',
	'choose_select' => '選択',
	'imagename' => 'イメージ名',
	'alt_text' => '代替テキスト',
	'align' => '配置',
	'border' => '枠線',
	'pixels' => '太さ（ピクセル）',
	'uploaded_as' => 'ファイルは \'%s\' としてアップロードされました。',
	'not_uploaded' => 'ファイルのアップロードに失敗しました。エラーは次の通りです。',
	'center' => '中央（デフォルト）',
	'left' => '左寄せ',
	'right' => '右寄せ',
	'inline' => 'インライン',		
	'notice_upload_first' => '最初にイメージを選択もしくはアップロードして下さい。',
	'select_image' => 'イメージの選択',
	'select_file' => 'ファイルの選択',

	'for_popup' => 'ポップアップ設定',		
	'use_thumbnail' => 'サムネールを使用',		
	'edit_thumbnail' => 'サムネールの編集',		
	'use_text' => 'テキストを使用',		
	'insert_download' => 'ダウンロードリンクを挿入',
	'insert_download_desc' => 'ファイルをアップロードするか、既にアップロードしてあるファイルを選択した後、ダウンロードのトリガーとなるアイコン、テキストもしくはサムネールを選択して下さい。',
	'use_icon' => 'アイコンを使用',
);


$lang['link'] = array (
	//		Link Insertion \\
	'insert_link' => 'ハイパーリンクを挿入',
	'insert_link_desc' => '以下のフィールドにアドレスを入力してリンクを挿入します。訪問者はマウスオーバー時にタイトルを確認することが出来ます。',
	'url' => 'アドレス',
	'title' => 'タイトル',
	'text' => 'テキスト',
);


//		Categories		\\
$lang['category'] = array (
	'edit_who' => 'カテゴリ \'%s\' に投稿できるユーザーを設定します。',
	'name' => 'カテゴリ名',
	'users' => '投稿ユーザー',
	'make_new' => '新規カテゴリの作成',
	'create' => 'カテゴリを作成',
	'canpost' => 'このカテゴリに投稿を許可するユーザーを選択して下さい。',
	'same_name' => 'この名前のカテゴリは既に存在します。',
	'need_name' => 'カテゴリ名を入力して下さい。',
	
	'allowed' => '投稿可能なユーザー',
	'allow' => '許可する',
	'denied' => '投稿不可能なユーザー',
	'deny' => '許可しない',
	'edit' => 'カテゴリの編集',
	
	'delete' => 'カテゴリの削除',
	'delete_desc' => 'カテゴリを削除するには、\'はい\'を選択して下さい。',

	'delete_message' => '現在のPivotのバージョンでは、カテゴリ名だけが削除されます。将来、エントリに対する作業も選択することができる予定です。',
	'search_index_newctitle'   => 'インデックス',
	'search_index_newcdesc'    => '\'いいえ\'を選択することで、このカテゴリのエントリは検索インデックスに加えられなくなります。',
	'search_index_editcheader' => 'カテゴリのインデックス',
	
	'order' => 'ソート番号',
	'order_desc' => 'この番号が少ない順にカテゴリがソートされます。番号が同じ場合はアルファベット順になります。',
	'public' => '公開カテゴリ',
	'public_desc' => '\'いいえ\'を設定することで、このカテゴリに属するエントリは登録されている訪問者のみ公開されます（ライブページでのみ有効）。',
	'hidden' => '隠しカテゴリ',
	'hidden_desc' => '\'はい\'を設定することで、このカテゴリに属するエントリはアーカイブに表示されなくなります（ライブページでのみ有効）。',
		
);


$lang['entries'] = array (
	//		Entries			\\
	'post_entry' => "エントリの投稿",
	'preview_entry' => "エントリの確認",

	'first' => '最初のエントリ',
	'last' => '最新のエントリ',
	'next' => '次のエントリ',
	'previous' => '前のエントリ',

	'jumptopage' => 'ページ %num% にジャンプ',
	'filteron' => '（%name%）で抽出',
	'filteroff' => 'フィルターオフ',
	'title' => 'タイトル',
	'subtitle' => 'サブタイトル',
	'introduction' => 'イントロダクション',
	'body' => 'ボディ',
	'publish_on' => '発行する',
	'status' => 'ステータス',
	'post_status' => '投稿ステータス',
	'category' => 'カテゴリ',
	'select_multi_cats' => '（Ctrl+クリックで複数のカテゴリを選択）',
	'last_edited' => "最終更新日",
	'created_on' => "作成日",		
	'date' => '日付',
	'author' => '投稿者',
	'code' => 'エントリID',
	'comm' => 'コメント数',
	'track' => 'トラックバック数',
	'name' => '名前',
	'allow_comments' => 'コメントを許可',

	'delete_entry' => "エントリの削除",
	'delete_entry_desc' => "エントリと付随するコメントの削除",
	'delete_one_confirm' => "このエントリを削除してもよろしいですか？",
	'delete_multiple_confirm' => "これらのエントリを削除してもよろしいですか？",
	
	'convert_lb' => '改行を変換',
	'always_off' => '（Wysiwygエディタでは無効）',
	'be_careful' => '（取り扱いに注意！）',
	'edit_comments' => 'コメントの編集',
	'edit_comments_desc' => 'このエントリに保存されたコメントを編集します。',
	'edit_comment' => '編集',
	'delete_comment' => '削除',
	'edit_trackback' => 'トラックバックの編集',
	'delete_trackback' => '削除',
	'block_single' => '%s をブロック',
	'block_range' => '%s をブロック',
	'unblock_single' => '%s をブロック解除',
	'unblock_range' => '%s をブロック解除',
	'trackback' => 'トラックバック送信',
	'trackback_desc' => 'トラックバックを次のURLに送信します。一つの行に一つのURLを指定して下さい。',
	'keywords' => 'キーワード',
	'keywords_desc' => 'エントリ検索用のキーワードを入力するか、理解しやすいURLを入力して下さい。',
	'vialink' => "参考にしたサイトのURL",
	'viatitle' => "参考にしたサイトのタイトル",
	'via_desc' => 'このエントリの参考となったサイトの情報を入力して下さい。',
	'entry_catnopost' => 'カテゴリ \'%s\' に投稿することはできません。',
	'entry_saved_ok' => 'エントリ \'%s\' は保存されました。',
	'entry_ping_sent' => 'トラックバックは \'%s\' に送信されました。',
);


//		Form Fun		\\
$lang['forms'] = array (
	'c_all' => '全てチェック',
	'c_none' => '全て外す',
	'choose' => 'オプションを選択して下さい。',
	'publish' => 'ステータスを \'publish\'',
	'hold' => 'ステータスを \'hold\'',
	'delete' => '削除',
	'generate' => '発行・再構築',

	'with_checked_entries' => 'チェックしたエントリに対して：',
	'with_checked_files' => 'チェックしたファイルに対して：',
	'with_checked_templates' => 'チェックしたテンプレートに対して：',
);


//		Errors			\\
$lang['error'] = array (
	'path_open' => 'ディレクトリを開けません。権限を確認して下さい。',
	'path_read' => 'ディレクトリを読めません。権限を確認して下さい。',
	'path_write' => 'ディレクトリに書き込めません。権限を確認して下さい。',

	'file_open' => 'ファイルを開けません。権限を確認して下さい。',
	'file_read' => 'ファイルを読み込めません。権限を確認して下さい。',
	'file_write' => 'ファイルに書き込めません。権限を確認して下さい。',
);


//		Notices			\\
$lang['notice'] = array (		
	'comment_saved' => 'コメントは保存されました。',
	'comment_deleted' => 'コメントは削除されました。',
	'comment_none' => 'コメントはありません。',
	'trackback_saved' => 'トラックバックは保存されました。',
	'trackback_deleted' => 'トラックバックは削除されました。',
	'trackback_none' => 'トラックバックはありません。',
);


// Comments, Karma and voting \\
$lang['karma'] = array (
	'vote' => 'このエントリに対する評価 \'%val%\' を投票する。',
	'good' => '良い',
	'bad' => '悪い',
	'already' => '既に投票済みです。',
	'register' => '評価 \'%val%\' は投票されました。',
);


$lang['comment'] = array (
	'register' => 'コメントは保存されました。',
	'preview' => '現在プレビュー中です。確定して保存するには\'コメントを保存\'をクリックして下さい。',
	'duplicate' => '以前のコメントと重複するため、コメントは保存されませんでした。',
	'no_name' => '名前（もしくはハンドル名）を入力して下さい。',
	'no_comment' => 'コメントを入力して下さい。',
	'too_many_hrefs' => 'コメントに入力できる最大リンク数を超えています。',
);


$lang['comments_text'] = array (
	'0' => 'コメント（0）',
	'1' => 'コメント（%num%）',
	'2' => 'コメント（%num%）',
);

$lang['trackbacks_text'] = array (
	'0' => 'トラックバック（0）',
	'1' => 'トラックバック（%num%）',
	'2' => 'トラックバック（%num%）',
);

$lang['weblog_text'] = array (
	// these are used in the weblogs, for the labels related to archives
	'archives' => 'アーカイブ',
	'next_archive' => '次のアーカイブ',
	'previous_archive' => '前のアーカイブ',
	'last_comments' => '最近のコメント',
	'last_referrers' => '最近の参照元',
	'calendar' => 'カレンダー',
	'links' => 'お気に入り',
	'xml_feed' => 'XMLフィード（RSS 1.0）',
	'atom_feed' => 'Atomフィード',
	'powered_by' => 'Powered by',
	'blog_name' => 'ウェブログ名',
	'title' => 'タイトル',
	'excerpt' => '抜粋',
	'name' => '名前',
	'email' => 'Email',
	'url' => 'URL',
	'date' => '日付',		
	'comment' => 'コメント',
	'ip' => 'IPアドレス',		
	'yes' => 'はい',
	'no' => 'いいえ',
	'emoticons' => 'Emoticons',
	'emoticons_reference' => 'Emoticonsのリファレンス',
	'textile' => 'Textile',
	'textile_reference' => 'Textileのリファレンス',
	'post_comment' => 'コメントを保存',
	'preview_comment' => 'コメントを確認',
	'remember_info' => '情報を記憶する',
	'notify' => '通知',
	'notify_yes' => 'このエントリへコメントがあった場合、自分宛に通知する。',
	'register' => 'ユーザー登録 / ログイン',
	'disclaimer' => '<b>注意：</b> 使用できるタグは &lt;b&gt; と &lt;i&gt; のみです。URLやメールアドレスはそのまま記述すればリンクになります。',	
	'search_title' => '検索結果',
	'search' => 'ウェブログ内検索',
	'nomatches' => '\'%name%\' に該当するエントリは見つかりませんでした。他の語句で再検索して下さい。',
	'matches' => '\'%name%\' に対する検索結果：',
	'about' => 'About',
	'stuff' => 'Stuff',
	'linkdump' => "Linkdump",
);


$lang['ufield_main'] = array (
	//		Userfields		\\
	'title' => 'ユーザー定義項目',
	'edit' => '編集',
	'create' => '作成',

	'dispname' => '表示名',
	'intname' => '内部名',
	'intname_desc' => '内部名はテンプレート内で使用されます。',
	'size' => 'サイズ',
	'rows' => '行数',
	'cols' => '列数',
	'maxlen' => '最大文字数',
	'minlevel' => '最低ユーザーレベル',	
	'filter' => 'フィルタリング',
	'filter_desc' => 'フィルタリングすることで入力を制限することが出来ます。',
	'no_filter' => 'フィルター無し',
	'del_title' => '削除の確認',
	'del_desc' => 'ユーザー定義項目を削除します。（<b>%s</b>）同時に関連する全てのデータも削除され、テンプレート内のインスタンスは空白になります。',	
	
	'already' => 'この名前はすでに使用されています。',
	'int' => '内部名は3文字以上にして下さい。',
	'short_disp' => '表示名は3文字以上にして下さい。',
);


$lang['bookmarklets'] = array (
	'bookmarklets' => 'ブックマーク',
	'bm_add' => 'ブックマークの追加',
	'bm_withlink' => '新規エントリ（リンク付）',
	'bm_withlink_desc' => '現在開いているページのリンクを、イントロダクションに含んだ新規エントリ画面を開きます。',

	'bm_nolink' => '新規エントリ（ブランク）',
	'bm_nolink_desc' => 'ブランクの新規エントリ画面を開きます。',

	'bookmarklets_info' => 'ブックマークを利用することで、新規エントリを素早く作成できます。ブックマークを追加する方法はブラウザによりますが、主な方法は以下の通りです。',
	'bookmarklets_info_1' => 'ブックマークの対象をツールバーの\'リンク\'へドラッグする。もしくはブラウザの\'ブックマーク\'ボタンを使用する。',
	'bookmarklets_info_2' => 'ブックマークの対象上で右クリック－\'ブックマークに追加\'を使用する。',
);

// Accessibility - These are used for form fields, labels, fieldsets etc.
// for Web Content Accessibility Guidelines & 508 compliancy issues.
// see: http://bobby.watchfire.com/bobby/html/en/index.jsp
// JM =*=*= 2004/10/04
// 2004/11/25 =*=*= JM - minor correction for tim
$lang['accessibility'] = array(
	'search_idname'      => '検索',
	'search_formname'    => 'このウェブサイトのエントリ内で使用されている語句を検索する。',
	'search_fldname'     => '検索する語句：',
	'search_placeholder' => '検索語句を入力',

	'calendar_summary'   => 'この表は、エントリが投稿された日とそのエントリへのリンクを表示するカレンダーです。',
	'calendar_noscript'  => 'カレンダーは、このウェブログ内のエントリにアクセスする手段を提供しています。',
	/* 
	2-letter language code, used to designate the principal language used on the site
	see: http://www.oasis-open.org/cover/iso639a.html
	*/

	'lang' => $langname,
) ;


$lang['snippets_text'] = array (
    'word_plural'     => '語句',
    'image_single'    => 'イメージ',
    'image_plural'    => 'イメージ',
    'download_single' => 'ファイル',
    'download_plural' => 'ファイル',
); 

$lang['trackback'] = array (
    'register' => 'トラックバックは保存されました。',
    'duplicate' => '以前のトラックバックと重複するため、トラックバックは保存されませんでした。',
    'too_many_hrefs' => '入力できる最大リンク数を超えています。',
    'noid'      => 'トラックバックID(tb_id)がありません。',
    'nourl'     => 'URLがありません。',
    'tracked'   => 'トラックバックを受信しました。',
    'email_subject' => '[Trackback] トラックバックを受信しました',
);

$lang['commentuser'] = array (
    'title'             => 'Pivot ユーザーログイン',
    'header'            => '登録済みのビジターとしてログイン',
    'logout'            => 'ログアウト',
    'loggedout'         => 'ログアウトしました。',
    'login'             => 'ログイン',
    'loggedin'          => 'ログインしました。',
    'loggedinas'        => '次のユーザー名でログインしました。',
    'pass_forgot'       => 'パスワードをお忘れですか？',
    'register_new'      => '新しいユーザー名を登録する。',
    'register'          => 'ビジターとして登録する。',
    'register_info'     => '以下の項目を入力して下さい。ユーザー認証のためのメールを送信しますので、<strong>メールアドレスが有効</strong>であることをご確認下さい。',
    'pass_note'         => '注意：このサイトの管理者はあなたのユーザー名とパスワードを閲覧することが可能です。他の重要なアカウントのユーザー名やパスワードを、ここで使用しないで下さい。',
    'show_email'        => 'コメントにメールアドレスを表示する。',
    'notify'            => '新しいエントリが作成された時にメールで通知する。',
    'def_notify'        => 'コメントの通知をデフォルトで行う。',
    'register'          => '登録',
    'pass_invalid'      => 'パスワードが正しくありません。',
    'nouser'            => 'ユーザー名が正しくありません。',
    'change_info'       => 'ユーザー情報の変更が可能です。',
    'pref_edit'         => 'ユーザー設定の編集',
    'pref_change'       => 'ユーサー設定の変更',
    'options'           => 'オプション',
    'user_exists'       => 'そのユーザー名は既に使用されています。他の名前を使用してください。',
    'email_note'        => 'ユーザーアカウント認証のためにメールアドレスを入力して下さい。コメント投稿時にメールアドレスの表示・非表示を選択できます。',
    'stored'            => 'ユーザー情報が更新されました。',
    'verified'          => 'ユーザーアカウントが認証されました。ログインしてください。',
    'not_verified'      => '申し訳ありませんが、認証できませんでした。そのコードは正しくないようです。',
    'pass_sent'         => 'パスワードを登録されているメールアドレスに送信しました。',
    'user_pass_nomatch' => 'ユーザー名とメールアドレスが適合しません。',
    'pass_send'         => 'パスワードを送信',
    'pass_send_desc'    => 'パスワードを忘れた場合は、ユーザー名とメールアドレスを入力して下さい。Pivotがパスワードをメールで再送信します。',
    'oops'              => 'おっと。',
    'back'              => '戻る',
    'back_login'        => 'ログイン画面に戻る',
    'forgotten_pass_mail' => "ユーザー名 '%name%' のパスワードは以下の通りです。\n\n%pass%\n\nログインするには以下のリンクをクリックして下さい。\n\n %link%"
);

// A little tool to help you check if the file is correct..
if (count(get_included_files())<2) {

	$groups = count($lang);
	$total = 0;
	foreach ($lang as $langgroup) {
		$total += count($langgroup);
	}
	echo "<h2>おめでとうございます。言語ファイルは正しく認識されました！</h2>";
	echo "このファイルには $groups グループと $total ラベルが含まれています。";

}

?>

