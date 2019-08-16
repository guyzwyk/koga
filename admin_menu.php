<?php
	session_save_path('../sessions/');
    session_start();
    require('../modules/user/admin/admin_menu-user.php');
    require('../modules/page/admin/admin_menu-page.php');
    
?>
<?php
    foreach($arr_activeModules as $module_menu){
        if(in_array($module_menu, $arr_toSkip)){
	        continue; //To skip active modules not concerned --> See admin.php to define modules to be skipped
        }
        else{
            require_once('../modules/'.$module_menu.'/admin/admin_menu-'.$module_menu.'.php');
            $left_menu   .=  $admin_menu;
        }   
    }
?>


<?php
    $menu = "
    <nav id=\"navbar\" class=\"top1 navbar navbar-default navbar-static-top\" role=\"navigation\" style=\"margin-bottom: 0;\">
    		
    	<div class=\"navbar-header\">
        	<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">
            	<span class=\"sr-only\">Toggle navigation</span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
            </button>
            <a class=\"navbar-brand\" href=\"admin.php\">DIGITRA CMS 4.0</a>
        </div>
            
    	<!-- /.navbar-header -->
  
        <ul class=\"nav navbar-nav navbar-right\">
			<li class=\"dropdown\">
				<a class=\"dropdown-toggle\" aria-expanded=\"false\" href=\"#\" data-toggle=\"dropdown\"><i class=\"fa fa-user fa-fw nav_icon\"></i>".$system->greet_by_lang($_SESSION['LANG']).", $userName</a>
           		<ul class=\"dropdown-menu\">
            		<li class=\"m_2\"><a href=\"admin.php?what=userUpdate&action=userUpdate&userId=".$_SESSION['uId']."\"><i style=\"color:#669933;\" class=\"fa fa-user nav_icon\"></i>Modifier mon compte</a></li>
            		<li class=\"m_2\"><a href=\"../logout.php\"><i style=\"color:#993300;\" class=\"fa fa-power-off fa-fw nav_icon\"></i>Logout</a></li>
            	</ul>
            </li>
            
			<li><a href=\"admin.php\"><i class=\"fa fa-dashboard fa-fw nav_icon\"></i>".$lang_output['MENU_ADMIN_DASHBOARD']."</a></li>
				
			<li class=\"dropdown\">
				<a class=\"dropdown-toggle\" aria-expanded=\"false\" href=\"#\" data-toggle=\"dropdown\"><i class=\"fa fa-question nav_icon\"></i>".$lang_output['MENU_ADMIN_HELP']."</a>
            	<ul class=\"dropdown-menu\">
            		<li class=\"dropdown-menu-header text-center\">".$lang_output['MENU_ADMIN_HELP_DOCUMENTATION_LABEL']."</li>
            		<li class=\"m_2\"><a href=\"#\"><i class=\"fa fa-download\"></i>".$lang_output['MENU_ADMIN_HELP_MANUAL_DOWNLOAD']."</a></li>
            		<li class=\"dropdown-menu-header text-center\">".$lang_output['MENU_ADMIN_HELP_SETTINGS_LABEL']."</li>
            		<li class=\"m_2\"><a href=\"admin.php?page=settings&what=settings2\"><i class=\"fa fa-server\"></i>".$lang_output['MENU_ADMIN_HELP_SERVER_SETTINGS']."</a></li>
            		<li class=\"m_2\"><a href=\"admin.php?page=settings&what=config\"><i class=\"fa fa-wrench\"></i>".$lang_output['MENU_ADMIN_HELP_GENERAL_SETTINGS']."</a></li>
            		<li class=\"m_2\"><a href=\"admin.php?page=settings&what=update\"><i class=\"fa fa-refresh\"></i>".$lang_output['MENU_ADMIN_HELP_UPDATE_SETTINGS']."</a></li>
            	</ul>
            </li>
        </ul>
			
		<!-- <form class=\"navbar-form navbar-right\">
              <input type=\"text\" class=\"form-control\" value=\"Search...\" onFocus=\"this.value = '';\" onBlur=\"if (this.value == '') {this.value = 'Search...';}\">
        </form> -->
            
        <!-- Our side bar starts here-->
            <div class=\"navbar-default sidebar\" role=\"navigation\">
                <div class=\"sidebar-nav navbar-collapse\">
                    <ul class=\"nav\" id=\"side-menu\">
                    	
                    	<!-- Dashboard call -->
                        <li>
                            <a href=\"admin.php\"><i class=\"fa fa-dashboard fa-fw nav_icon\"></i>".strtoupper($lang_output['MENU_ADMIN_DASHBOARD'])."</a>
                        </li>

                        <hr />
                        
                        <!-- Users manager-->
                        ".$admin_menu_user."
                        
                        <!-- Pages manager-->
                        ".$admin_menu_page."

                        <hr />";
    
    
    $menu   .=      $left_menu;                    
                        
    $menu   .=          "<hr />
                        
                        <!-- Log out call -->
                        <li>
                            <a href=\"../logout.php\"><i style=\"color:#993300;\" class=\"fa fa-power-off fa-fw nav_icon\"></i>Log out</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
    </nav>";		
?>