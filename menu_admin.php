<?php
	session_save_path('../sessions/');
    session_start();
    
?>
<?php
    foreach($arr_activeModules as $module_menu){
        if(in_array($module_menu, $arr_toSkip)  || $_REQUEST['page'] != 'settings'){
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
    <nav class=\"top1 navbar navbar-default navbar-static-top\" role=\"navigation\" style=\"margin-bottom: 0\">
    		
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
            		<li class=\"m_2\"><a href=\"user_manager.php?what=userUpdate&action=userUpdate&userId=".$_SESSION['uId']."\"><i style=\"color:#669933;\" class=\"fa fa-user nav_icon\"></i>Modifier mon compte</a></li>
            		<li class=\"m_2\"><a href=\"../logout.php\"><i style=\"color:#993300;\" class=\"fa fa-power-off fa-fw nav_icon\"></i>Logout</a></li>
            	</ul>
            </li>
            
			<li><a href=\"admin.php\"><i class=\"fa fa-dashboard fa-fw nav_icon\"></i>Dashboard</a></li>
				
			<li class=\"dropdown\">
				<a class=\"dropdown-toggle\" aria-expanded=\"false\" href=\"#\" data-toggle=\"dropdown\"><i class=\"fa fa-question nav_icon\"></i>Help</a>
            	<ul class=\"dropdown-menu\">
            		<li class=\"dropdown-menu-header text-center\">Documentation</li>
            		<li class=\"m_2\"><a href=\"#\"><i class=\"fa fa-download\"></i>Download the Admin manual</a></li>
            		<li class=\"dropdown-menu-header text-center\">Settings... Toto</li>
            		<li class=\"m_2\"><a href=\"admin.php?page=settings&what=settings2\"><i class=\"fa fa-server\"></i>Server settings</a></li>
            		<li class=\"m_2\"><a href=\"config.php\"><i class=\"fa fa-wrench\"></i>General settings</a></li>
            		<li class=\"m_2\"><a href=\"update_settings.php\"><i class=\"fa fa-refresh\"></i>Update settings</a></li>
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
                            <a href=\"admin.php\"><i class=\"fa fa-dashboard fa-fw nav_icon\"></i>Dashboard</a>
                        </li>

                        <hr />
                        
                        <!-- Users manager-->
                        <li>
                            <a href=\"#\"><i class=\"fa fa-user nav_icon\"></i>Users Manager<span class=\"badge badge-primary\">$nbUsers</span><span class=\"fa arrow\"></span></a>
                            <ul class=\"nav nav-second-level\">
                                <li>
                                    <a href=\"user_manager.php?what=userDisplay\"><i class=\"fa fa-eye nav_icon\"></i>Display users</a>
                                </li>
                                <li>
                                    <a href=\"user_manager.php?what=userInsert\"><i class=\"fa fa-plus nav_icon\"></i>Create users</a>
                                </li>
                                <li>
                                    <a href=\"user_manager.php?what=userCatDisplay\"><i class=\"fa fa-eye nav_icon\"></i>Display categories</a>
                                </li>
								<li>
                                    <a href=\"user_manager.php?what=userCatInsert\"><i class=\"fa fa-plus nav_icon\"></i>Create category</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                        <!-- Pages manager-->
                        <li>
                            <a href=\"#\"><i class=\"fa fa-sitemap nav_icon\"></i>Pages Manager<span class=\"badge badge-primary\">$nbPages</span><span class=\"fa arrow\"></span></a>
                            <ul class=\"nav nav-second-level\">
                                <li>
                                    <a href=\"page_manager.php?what=pageDisplay\"><i class=\"fa fa-eye nav_icon\"></i>Display pages</a>
                                </li>
                                <li>
                                    <a href=\"page_manager.php?what=pageInsert\"><i class=\"fa fa-plus nav_icon\"></i>Create pages</a>
                                </li>
                                <li>
                                    <a href=\"page_manager.php?what=contentEdit\"><i class=\"fa fa-edit nav_icon\"></i>Edit page contents</a>
                                </li>
                                <li>
                                    <a href=\"page_manager.php?what=pageModuleAssign\"><i class=\"fa fa-exchange nav_icon\"></i>Assign module</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <hr />";
    
    
    $menu   .=      $left_menu;                    
                        
    $menu   .=          "<hr />
                        
                        <!-- Log out call -->
                        <li>
                            <a href=\"../logout.php\"><i style=\"color:#993300;\" class=\"fa fa-power-off fa-fw nav_icon\" ></i>Log out</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
    </nav>";		
?>