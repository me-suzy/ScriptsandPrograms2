<?
/**universal
 * Fichier pour vérifier la validité de la session
 * @author Thomas Pequet
 * @version 1.0
 */

// Démarrage de la session
session_start();

if (!session_is_registered("id_client")) {

	include($rep_par_rapport_racine."inc/config.inc.php");
	include($rep_par_rapport_racine."inc/fonctions.inc.php");	
	
	/**universal
	 * Fonction qui ouvre une boite de dialogue pour demander le login / mot de passe
 	 * @return True ou False
	 */	
	function auth()
	{
		global $nomSite, $urlSite, $rep_par_rapport_racine;
		
		header('status: 401 Unauthorized');
		header('HTTP/1.0 401 Unauthorized');
		include($rep_par_rapport_racine."inc/img.inc.php");
		header('WWW-authenticate:  basic realm="Login '.$nomSite.'"'); ?>
<HTML>
<HEAD>
<TITLE><?=$nomSite;?> - Erreur d'utilisateur/mot de passe - Accès refusé</TITLE>
<LINK REL="stylesheet" HREF="style.css">
</HEAD>
<BODY BGCOLOR="#FFFFFF">
<BR><BR>
<CENTER>
<H1>Erreur d'utilisateur/mot de passe<BR><FONT COLOR="#FF0000">Accès refusé</FONT></H1>
<BR>
<A HREF="http://<?=$urlSite;?>"><B>Retour à l'accueil</B><BR><?=$imgLogo;?></A>
</CENTER>
</BODY>
</HTML>
		<? exit;
	}
	
	if (empty($PHP_AUTH_PW)) {
		if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['PHP_AUTH_PW'])) {
			$PHP_AUTH_PW = $HTTP_SERVER_VARS['PHP_AUTH_PW'];
		} else if (isset($REMOTE_PASSWORD)) {
			$PHP_AUTH_PW = $REMOTE_PASSWORD;
		} else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['REMOTE_PASSWORD'])) {
			$PHP_AUTH_PW = $HTTP_ENV_VARS['REMOTE_PASSWORD'];
		} else if (@getenv('REMOTE_PASSWORD')) {
			$PHP_AUTH_PW = getenv('REMOTE_PASSWORD');
		}
	}

	if (empty($PHP_AUTH_USER)) {
		auth();
	} else {
		// Connexion à la base
		include($rep_par_rapport_racine.$ficConnBase);
		
		$query = "";
		$result = $bd->sql_query($query);
		
		if ($bd->sql_numrows($result)==1) { 
			
			// Fermeture de la connexion à la base
			if (isset($bd)) {
				$bd->sql_close();
				unset($bd);
			}

			rediriger("index.".$extension);
			
		} else {
			// Fermeture de la connexion à la base
			if (isset($bd)) {
				$bd->sql_close();
				unset($bd);
			}
			auth();
		}
	}
}
?>
