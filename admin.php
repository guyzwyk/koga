<?php
	ob_start();
	ob_implicit_flush();
?>
<?php
	session_save_path("../_sessions/");
	session_start();
?>
<?php	
	//Global Libraries Import
	require_once("../scripts/incfiles/config.inc.php"); //Config
	require_once("../plugins/phpmailer/_lib/class.phpmailer.php"); //Mails
	require_once("../member/library/member.inc.php");
?>
<?php
	//Global initialisations
	$system			= 	new cwd_system();
	$member			= 	new cwd_member();
	$myFiletransact	=	new cwd_system();
	$modules		=	new cwd_module();
	$myRss			= 	new cwd_rss091();
	$myPage			=	new cwd_page();
	
	//1 - Connect to the data base
	$system->db_connect($thewu32_host, $thewu32_db, $thewu32_user, $thewu32_pass);

	//2 - Secure users sessions
	$system->session_checker($_SESSION["CONNECTED_ADMIN"], $_SESSION["CONNECTED_EDIT"]);

	//3 - Site settings
	$settings 	=	$system->get_site_settings();

	//4 - Language settings
	$langFile	= 	isset($_SESSION[LANG]) ? ($_SESSION[LANG]) : ($settings["LANG"]);

	//5 - Call the language file pack
	require_once("../scripts/langfiles/".$langFile.".php");  //Global language pack

	//6 - Call all modules and their language packs
	$site_modules	=	$myFiletransact->list_dir($thewu32_site_dir.'modules/'); //Lister tous les  sous-r�pertoires directs du r�pertoire /modules/
	foreach($site_modules as $module){
		require('../modules/'.$module.'/langfiles/'.$langFile.'.php');
		require('../modules/'.$module.'/library/'.$module.'.inc.php');
	}

	//Modules to be skipped --> System modules
	//$arr_toSkip     =   array('user', 'page', 'search', 'contact');
	$arr_toSkip		=	$modules->get_module_lib_by_cat_display(3);

	//Page exceptions
	$arr_pageExceptions	=	array('settings');
?>

<?php

?>


<?php
	$my_userStats		=	new cwd_user();
	$my_pagesStats		=	new cwd_page();
	$nbUsers			=	$my_userStats->count_users();
	$nbPages			=	$my_pagesStats->count_pages();
	/* $my_memberStats		=	new cwd_member();

	//$nbMembers			=	$my_memberStats->count_members();
	//$nb_payStarted		=	$my_memberStats->count_tuitions_started();
	*/
?>

<?php
	//Clear log file
	//Log file...
	$logFile	=	'../log/log.gzk';
	if(isset($_SESSION['CONNECTED_ADMIN'])){
		if($_REQUEST['what']	==	'clearLog'){
			$system->clear_file($logFile);
			$system->set_log('LOG CLEARED');
		}
	}
	
	//Disconnect member
	if($_REQUEST[action]	==	'memberDisconnect'){
		$member->set_connected_member($_REQUEST[mcId], 0);
		$memberName			=	$member->get_member_name_by_id($_REQUEST[mId]);
		$system->set_log("MEMBER DISCONNECTED - ".$memberName);
	}
?>

<?php
	//Get active modules only for admin side-menu
	$arr_activeModulesAll		=	$modules->get_module_lib_display(1); //Load all active modules
	$arr_activeCommonModules	=	$modules->get_module_lib_by_cat_display(1); //Load common modules only
	$arr_activeCustomerModules	=	$modules->get_module_lib_by_cat_display(2); //Load customer modules only

	//Active modules = Common active modules + Customer active modules(DB)
	$arr_activeModules			=	array_merge($arr_activeCommonModules, $arr_activeCustomerModules);

	//Get System module
	$arr_activeSystemModules	=	$modules->get_module_lib_by_cat_display(3); //Load System modules only

	//Avoid displaying errors in case a fake module or no module is choosen.
	$choosen_module		=	(in_array($_REQUEST['page'], $arr_activeModulesAll)) ?	($_REQUEST['page'])	:	('none');

	//Module initialisation
	$module_require		=	isset($choosen_module) && in_array($choosen_module, $arr_activeModules)	?	(require_once('../modules/'.$choosen_module.'/admin/admin_header-'.$choosen_module.'.php'))	:	('');
	//require($module_require);
?>
<?php
	ob_end_flush();
?>
<?php 
	//print_r($system->digitra_get_mod_dir('news', '1'));
?>
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
	<?php 
		require_once 'admin_metadata.php';
	?>	
	<head>
		<?php 
			require_once 'admin_header.php';
		?>
		<title>DIGITRA CMS 4.0 | Home :: Welcome to your CPanel :: <?php print ucfirst($_REQUEST['page']); ?> Module Manager</title>	
	</head>

	<body>
		<div class="wrapper">
			<!-- Navigation -->
			<?php 
				require_once('responsive_menu.php');
			?>
			
			<div class="page-wrapper">
				<div class="graphs">
					<?php
						//Go to dashboard if no module has been choosen
						if ($choosen_module == 'none') { 
					?>
					
					<?php }	?>

					<!-- <div class="col_1"> -->
					
						<?php
							//Main content require :: show settings or page content
							if(in_array($_REQUEST['page'], $arr_pageExceptions)){
								$main_page_require	=	'admin_'.$_REQUEST['page'].'.php';
							}
							//elseif(isset($_REQUEST['page']) && in_array($_REQUEST['page']))
							else{
								$main_page_require		=	isset($_REQUEST['page']) && in_array($_REQUEST['page'], $arr_activeModulesAll)	?	('../modules/'.$choosen_module.'/admin/admin_content-'.$choosen_module.'.php')	:	('admin_content.php');
							}
							require($main_page_require);
						?>
							
					<!--	<div class="clearfix"> </div>
					 </div> -->
					
					
				</div>

				<?php 
					//print $system->load_modal_window('open_modal', 'annonce_manager.php');
					//print_r($modules->get_module_lib_by_cat_display(3));
				?>

				<p>&nbsp;</p>
				<div class="copy">
					<p>Copyright &copy; 2016 DIGITRA. All Rights Reserved | Design by <a href="http://www.digitra.com/" target="_blank">Digitra</a> </p>
				</div>
			</div>
		</div>
		<!-- /#page-wrapper -->
		
		<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
														Modal dialogs here...
		::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->

		<?php
			//Footer require
			$active_modals_require	=	isset($_REQUEST['page']) && in_array($_REQUEST['page'], $arr_activeModules)	?	('../modules/'.$choosen_module.'/admin/admin_modal-'.$choosen_module.'.php')	:	('admin_modal.php');
			require($active_modals_require);

			//$modal_require		=	isset($choosen_module) && in_array($choosen_module, $arr_activeModules)	?	(require_once('../modules/'.$choosen_module.'/admin/admin_modal-'.$choosen_module.'.php'))	:	('');
		?>
		
		<?php //print_r($mod_lang_output);?>
		
		
		

		<!-- Custom CSS
		<link href="css/style.css" rel='stylesheet' type='text/css' /> -->

		<!-- Bootstrap Core JavaScript 
		<script src="js/bootstrap.min.js"></script> -->
		
		<!-- Metis Menu Plugin JavaScript -->
		<script src="js/metisMenu.min.js"></script>
		<script src="js/custom.js"></script>

		<!-- JS for Modal
		<script src="../plugins/modal/modal.js"></script>
		<script src="../plugins/modal/plugins/html5video.js"></script>
		<script src="../plugins/modal/plugins/modal-maxwidth.js"></script>
		<script src="../plugins/modal/plugins/modal-resize.js"></script>
		<script src="../plugins/modal/plugins/gallery.js"></script>	 -->
	</body>
</html>
