<?php
	ob_start();
	ob_implicit_flush();
?>
<?php
	session_save_path("../_sessions/");
	session_start();
?>
<?php	
	//Libraries Import
	require_once("../scripts/incfiles/config.inc.php");
	require_once("../modules/user/library/user.inc.php");
	$system		= new cwd_system();
	$myRss		= new cwd_rss091();
	$system->db_connect($thewu32_host, $thewu32_db, $thewu32_user, $thewu32_pass);
	$system->session_checker($_SESSION["CONNECTED_ADMIN"], $_SESSION["CONNECTED_EDIT"]);
?>
<?php
	//Site settings
	$settings 	= $system->get_site_settings();
	
	//Language settings
	$langFile	= isset($_SESSION[LANG]) ? ($_SESSION[LANG]) : ($settings["LANG"]);
	//Call the language file pack
	require_once("../scripts/langfiles/".$langFile.".php");
?>

<?php 
	ob_end_flush();
?>
<?php include_once('inc/admin_header.php');?>


<!-- ****************************************** -->
<p>All the page content here...</p>
<?php 
    $tab = array(100, 99, 98, 97, 90, 96, 94, 93, 95, 91, 92);
   print "<p>Tableau initial : ";
   print_r($tab);
   print "</p>";
   print "<p>Tableau final : ";
   print_r(array_flip($tab));
   print "</p>";
   
?>

<!-- ****************************************** -->


<?php include_once('inc/admin_footer.php');?>
