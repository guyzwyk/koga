<!--  Top badges at dashboard page -->
<div class="col_3">
						<div class="col-md-3 widget widget1">
							<div class="r3_counter_box">
								<i class="pull-left fa fa-user icon-rounded"></i>
								<div class="stats">
								<h5><strong><?php //print $member->count_member_connected(); ?></strong></h5>
								<span><a href="#openModalConnected">Utilisateur(s) connect&eacute;s</a></span>
								</div>
							</div>
						</div>
						<div class="col-md-3 widget widget1">
							<div class="r3_counter_box">
								<i class="pull-left fa fa-globe user1 icon-rounded"></i>
								<div class="stats">
								
								<!-- hitwebcounter Code START -->
									<a href="#" target="_blank">
										<img src="http://hitwebcounter.com/counter/counter.php?page=6521975&style=0003&nbdigits=5&type=ip&initCount=0" title="Hit Web Stats" Alt="Hit Web Stats"   border="0" >
									</a>
									<br/>
									<!-- hitwebcounter.com -->
									<a href="#" title="Total Count" target="_blank" style="font-family: sans-serif, Arial, Helvetica; font-size: 6px; color: #6D6C72; text-decoration: none ;"></a> 
								<span>Nouveaux Visiteurs</span>
								</div>
							</div>
						</div>
						<div class="col-md-3 widget widget1">
							<div class="r3_counter_box">
								<i class="pull-left fa fa-users user2 icon-rounded"></i>
								<div class="stats">
								<h5><strong><?php //print $member->count_members()?></strong></h5>
								<span><a href="#openModalMembers">Utilisateurs NWR Database</a></span>
								</div>
							</div>
						</div>
						<div class="col-md-3 widget">
							<div class="r3_counter_box">
								<i class="pull-left fa fa-terminal dollar1 icon-rounded"></i>
								<div class="stats">
								<h5><strong><?php print ($system->count_lines_in_file('../log/log.gzk') - 1) ;?></strong></h5>
								<span><a title="<?php print $lang_output['LABEL_ADMIN_LOG_ACTIVITY_ALT']; ?>" href="#openModalLog"><?php print $lang_output['LABEL_ADMIN_LOG_ACTIVITY']; ?></a></span>
								</div>
							</div>
						</div>
						<div class="clearfix"> </div>
					</div>




<div class="col-md-6 span_8">
	<div class="activity_box">
       	<h4 class="admin_module_title"><?php print $lang_output["USER_ADMIN_MODULE_TITLE"];?></h4>
	    <div class="scrollbar" id="style-2">
            <img class="img-responsive img-wide" src="images/db_user_manager.jpg" />
            <hr />
            <div class="activity-row">      
       	        <div class="col-xs-1"><i class="fa fa-user text-info icon_11"></i></div>
	            <div class="col-xs-11 activity-desc">
	                <?php print $lang_output["USER_ADMIN_MODULE_DESCR"];?>
	                <!-- <ul><?php print $system->admin_set_menu('eye', 'Settings', 'setting.php'); ?></ul> -->
	            </div>
	            <div class="clearfix"> </div>
            </div>
            <div class="buttons">
                <a class="<?php print $link = (isset($_SESSION['CONNECTED_EDIT']) ? 'disabled ' : '') ?>btn btn-primary btn-lg" href="<?php print $system->digitra_get_mod_admin('user');?>"><?php print $lang_output["LABEL_MODULE_BUTTON_ACCESS"]?></a>
            </div>
	    </div>
    </div>
</div>

<div class="col-md-6 span_8">
	<div class="activity_box">
        <h4 class="admin_module_title"><?php print $lang_output["PAGE_ADMIN_MODULE_TITLE"];?></h4>
		<div class="scrollbar" id="style-2">
            <img class="img-responsive img-wide" src="images/db_page_manager.jpg" />
            <hr />
            <div class="activity-row">
                <div class="col-xs-1"><i class="fa fa-sitemap text-info icon_11"></i></div>
	            <div class="col-xs-11 activity-desc">
	                <?php print $lang_output["PAGE_ADMIN_MODULE_DESCR"];?>
	            </div>
	            <div class="clearfix"> </div>
            </div>
            <div class="buttons">
                <a class="btn btn-primary btn-lg" href="<?php print $system->digitra_get_mod_admin('page');?>"><?php print $lang_output["LABEL_MODULE_BUTTON_ACCESS"]?></a>
            </div>
	  	</div>
	</div>
</div>

<!-- :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
                            Loading dashboard content
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
	$arr_modules    =   $system->get_modules_lib("WHERE display = '1'");
	//$arr_toSkip     =   array('user', 'page', 'search', 'contact'); --> See admin.php
		      
	foreach($arr_modules as $module){
	    if(in_array($module, $arr_toSkip)){
	        continue;
	    }
	    else{
	        print $system->load_module_dashboard($module, $lang_output['LABEL_MODULE_BUTTON_ACCESS'], $_SESSION['LANG']);
	    }		          
	}			  
?>

<!-- :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
                            My Tests here
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php

?>

<?php
	print $system->load_module_actions_icons(21);
?>
