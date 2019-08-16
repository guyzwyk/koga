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
	require_once("../modules/exaction/library/exaction.inc.php");
	require_once('../plugins/chartphp/lib/inc/chartphp_dist.php');
	$system            =   new cwd_system();
	$myRss	           =   new cwd_rss091();
	$exaction	       =   new cwd_exaction();
	$myExaction        =   new cwd_exaction();
	$p                 =   new chartphp();
	$system->db_connect($thewu32_host, $thewu32_db, $thewu32_user, $thewu32_pass);
	$system->session_checker($_SESSION["CONNECTED_ADMIN"], $_SESSION["CONNECTED_EDIT"]);
?>
<?php
	//Site settings
	$settings 	        =   $system->get_site_settings();

	//Language settings
	$langFile	= isset($_SESSION[LANG]) ? ($_SESSION[LANG]) : ($settings["LANG"]);
	
	//Call the language file pack
	require_once("../scripts/langfiles/".$langFile.".php");
	require_once("../modules/exaction/langfiles/".$langFile.".php"); //Module language pack
	
	//Page name
	$admin_pageTitle	=	$mod_lang_output['MODULE_DESCR'];
	
	//Spry
	//$exaction->spry_ds_create();
?>

<?php 
	//Data for stats
	$nbExaction		=	$myExaction->count_exactions();		//All
	$nbKillings		=	$myExaction->count_exactions('1');	//Killings
	$nbAbductions	=	$myExaction->count_exactions('2');	//Kidnappings
	$nbBurnings		=	$myExaction->count_exactions('3');	//Burning and lootings
	$nbInjuries		=	$myExaction->count_exactions('4');	//Injuries
	$nbRapes		=	$myExaction->count_exactions('5');	//Rapes
	$nbExtorsions	=	$myExaction->count_exactions('6');	//Extorsions
	$mostDangerous	=	$myExaction->get_exaction_most_dangerous_area();
?>

<?php 
	//Pie chart	
	 $p->data 			  =   array(array(
	    array($mod_lang_output['EXACTION_STATS_KILLINGS_LABEL'], $nbKillings),
	    array($mod_lang_output['EXACTION_STATS_ABDUCTIONS_LABEL'], $nbAbductions),
	    array($mod_lang_output['EXACTION_STATS_BURNINGS_LABEL'], $nbBurnings),
	    array($mod_lang_output['EXACTION_STATS_INJURIES_LABEL'], $nbInjuries),
	    array($mod_lang_output['EXACTION_STATS_RAPES_LABEL'], $nbRapes),
	    array($mod_lang_output['EXACTION_STATS_EXTORTIONS_LABEL'], $nbExtorsions)
	)); 
	$p->chart_type 		= 	'pie';
	
	// Common Options
	$p->title	=   $mod_lang_output['EXACTION_GRAPH_TITLE']." : $nbExaction";
	$chartOut	= 	$p->render('c1');
?>

<?php 
	require_once('../modules/exaction/inc/exaction_validations.php');
?>

<?php
	//ob_end_flush();
?>


<?php include_once('inc/admin_header.php');?>


					      
	<!-- ********************************* Afficher les exactions  ***************************************************
	****************************************************************************************************************-->
	
	
	<!-- ************ Listing des exactions  *****************-->
	<?php if(!isset($_REQUEST['what']) || ($_REQUEST['what']=="exactionDisplay") || ($_REQUEST['what']=="exactionDelete")) { ?>
			      
		<div class="xs">
			
			<h3>
				<?php print $mod_lang_output['MODULE_NAME']; ?> :: <?php print $mod_lang_output['PAGE_HEADER_LIST_EXACTION']; ?>
				<span style="float:right;">
					<?php if($_POST['exaction_txtSearch'] != ''){ ?><a href="<?php print $myExaction->admin_modPage; ?>" title="<?php print $mod_lang_output['PAGE_HEADER_LIST_EXACTION']; ?>"><?php print $system->admin_button_crud('back'); ?></a><?php } ?>
					<a title="<?php print $mod_lang_output['FORM_BUTTON_ADD_EXACTION']; ?>" data-toggle="modal" data-target="#modal-exactionInsert"><?php print $system->admin_button_crud('create'); ?></a>
					<a title="<?php print $mod_lang_output['FORM_BUTTON_LIST_EXACTIONS']; ?>" data-toggle="modal" data-target="#modal-exactionDisplay"><?php print $system->admin_button_crud('read'); ?></a>
				</span>
			</h3>
			
    		<div class="col-md-9">
					
				<!--  Exactions search form  -->
        		<form method="post" name="exaction_frmSearch" id="exaction_frmSearch" action="">
							<div class="input-group">
								<input type="text" name="exaction_txtSearch" class="form-control1 input-search" placeholder="Search..." />
								<span class="input-group-btn">
									<button class="btn btn-success" type="submit" name="exaction_btnSearch"><i class="fa fa-search"></i></button>
								</span>
						</div>
				</form>
				
				<?php if(!empty($_POST['exaction_txtSearch'])) { ?>
				
				<!--  Exactions search results -->
								
        		<div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
        			<div class="panel-body no-padding">
        				<?php 
        				   print $myExaction->search_exaction($_POST['exaction_txtSearch'], $mod_lang_output['EXACTION_SEARCH_MSG_ERROR'], $_SESSION['LANG']);
        				?>
        			</div>
        		</div>
				<?php } ?>
				
				<?php if($_REQUEST["what"]=="exactionDelete") { ?>
				
				<!--  Exations delete msg -->
								
        		<div class="alert alert-success">
        			<?php 
        			     print $mod_lang_output["EXACTION_DELETE_MSG_OK"];
        			?>
        		</div>
				<?php } ?>
				
    				<?php if(!isset($_POST['exaction_btnSearch']))  { //Hide exaction list when search is active ?> 
    				
    				<!--  Exations preview list -->
            		
            		<div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
            			<div class="panel-body no-padding">
    						<?php $exaction->limit = $_REQUEST['limite']; ?>
            			          
    						<?php if(isset($exaction_display_err_msg)) {  ?>
    							<div class="ADM_err_msg"><?php print $exaction_display_err_msg; ?></div>
    						<?php } ?>
    								
    						<?php if(isset($exaction_display_cfrm_msg)) {  ?>
    							<div class="ADM_cfrm_msg"><?php print $exaction_display_cfrm_msg; ?></div>
    						<?php } ?>
    						
            			    <div class="table-responsive" style="height:600px; overflow-y:auto;">
            					<?php print $exaction->admin_load_exactions(500); ?>
            				</div>
            			</div>
            		</div>
            		
            		<?php } ?>
            		
            			
			
				<div class="panel panel-warning" data-widget-static="">
					<div class="panel-body">
						<div class="table-responsive">
							<table class="stats-exactions table table-bordered">
								<tr>
									<th colspan="2"><?php print $mod_lang_output['PAGE_HEADER_REPORTED_EXACTION_STATS']; ?></th>
								</tr>
								<tr>
									<td><?php print $mod_lang_output['EXACTION_STATS_KILLINGS_LABEL']?></td>
									<td><?php print $nbKillings.'('.round(($nbKillings * 100)/$nbExaction, 1).' %)'; ?></td>
								</tr>
								<tr>
									<td><?php print $mod_lang_output['EXACTION_STATS_ABDUCTIONS_LABEL']?></td>
									<td><?php print $nbAbductions.'('.round(($nbAbductions * 100)/$nbExaction, 1).' %)'; ?></td>
								</tr>
								<tr>
									<td><?php print $mod_lang_output['EXACTION_STATS_BURNINGS_LABEL']?></td>
									<td><?php print $nbBurnings.'('.round(($nbBurnings * 100)/$nbExaction, 1).' %)'; ?></td>
								</tr>
								<tr>
									<td><?php print $mod_lang_output['EXACTION_STATS_INJURIES_LABEL']?></td>
									<td><?php print $nbInjuries.'('.round(($nbInjuries * 100)/$nbExaction, 1).' %)'; ?></td>
								</tr>
								<tr>
									<td><?php print $mod_lang_output['EXACTION_STATS_RAPES_LABEL']?></td>
									<td><?php print $nbRapes.'('.round(($nbRapes * 100)/$nbExaction, 1).' %)'; ?></td>
								</tr>
								<tr>
									<td><?php print $mod_lang_output['EXACTION_STATS_EXTORTIONS_LABEL']?></td>
									<td><?php print $nbExtorsions.'('.round(($nbExtorsions * 100)/$nbExaction, 1).' %)'; ?></td>
								</tr>
								<tr style="text-align:center; font-weight:bold;">
									<td><?php print $mod_lang_output['EXACTION_STATS_TOTAL_LABEL']?></td>
									<td><?php print $nbExaction; ?></td>
								</tr>					
							</table>
						</div>
					</div>
				</div>
				
				
				<!-- Graph here -->
				
				<div class="panel panel-warning" data-widget-static="">
					<div class="panel-body">
        				<!-- <iframe src="../modules/exaction/inc/piechart.php" width="100%" height="300" border="0"></iframe> -->
        				<style> 
            		        /* white color data labels */ 
            		        .jqplot-data-label{color:white;} 
            		    </style>
                     	<?php print $chartOut; ?>
                    </div>
               	</div>
        			            			      
    		</div>
    	
							
			<!-- ********************************* Statistiques des exactions  ******************************************************
			**********************************************************************************************************************-->
					
			<div class="col-md-3">

				<div class="panel panel-warning" data-widget-static=""  style="margin-top:0;">
					<div class="panel-body">
						<table class="table table-bordered">
							<tr>
								<th><?php print $mod_lang_output['EXACTION_STATS_TOWN_HEADER']; ?></th>
							</tr>
						<?php
							foreach($mostDangerous as $value){
								print "<tr><td>$value</td></tr>";
							}
						?>
						</table>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		
		</div>
		
	<?php } ?>				
	
		<!-- ************ Exaction global list in modal ************ -->
		<div tabindex='-1' class='modal fade' id='modal-exactionDisplay' role='dialog' aria-hidden='true' aria-labelledby='modal-exactionDisplayLabel' style='display: none;'>
			<div class='modal-dialog' style="width:95%;">
				<div class='modal-content' style="background-color:#eee;">
					<div class='modal-header'>
						<button class='close' aria-hidden='true' type='button' data-dismiss='modal'>x</button>
						<h2 class='modal-title'><?php print $mod_lang_output['MODULE_NAME']; ?> :: <?php print $mod_lang_output['PAGE_HEADER_LIST_EXACTION']; ?></h2>
					</div>
					<div class='modal-body'>
						<?php print $exaction->admin_load_exactions(3000, 250); ?>
					</div>
					<div class='modal-footer'>
						<button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
					</div>
				</div>
			</div>
		</div>
    
	
	<!-- ********************************* Ajouter les exactions  ******************************************************
	****************************************************************************************************************-->
	<div tabindex='-1' class='modal fade' id='modal-exactionInsert' role='dialog' aria-hidden='true' aria-labelledby='modal-exactionInsertLabel' style='display: none;'>
		<div class='modal-dialog' style="width:60%;">
			<div class='modal-content' style="background-color:#eee;">

				<div class='modal-header panel-heading'>
					<button class='close' aria-hidden='true' type='button' data-dismiss='modal'>x</button>
					<h2 class='modal-title'><?php print $mod_lang_output['MODULE_NAME']; ?> :: <?php print $mod_lang_output['PAGE_HEADER_ADD_EXACTION']; ?></h2>
				</div>
				
				<div class='modal-body'>
					<div>
						<?php //if($_REQUEST['task']=="exactionInsert") { ?>
							<?php $currentLang = (($_REQUEST['langId'] != '') ? ($_REQUEST['langId']) : ($_SESSION['LANG'])); ?>
							<?php if(isset($exaction_insert_err_msg)) {  ?>
								<div class="ADM_err_msg"><?php print $exaction_insert_err_msg; ?></div>
							<?php } ?>
							<?php if(isset($exaction_insert_cfrm_msg)) {  ?>
								<div class="ADM_cfrm_msg"><?php print $exaction_insert_cfrm_msg; ?></div>
							<?php } ?>
						<div class="tab-content">
							<div class="tab-pane active" id="horizontal-form">
								<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="frm_exaction_insert" id="frm_exaction_insert">
        						        
									<div class="form-group">    
										<label class="col-sm-4 control-label" for="exaction_selType"><?php print $mod_lang_output['FORM_LABEL_EXACTION_TYPE']; ?></label>
										<div class="col-sm-8">
											<select class="form-control" name="exaction_selType" id="exaction_selType">
												<?php print $exaction->admin_cmb_show_cat($exaction->tbl_exactionType, $exaction->fld_exactionTypeId, $_POST['exaction_selType']); ?>
											</select>
										</div>
									</div>
        								
									<div class="form-group">    
										<label class="col-sm-4 control-label" for="exaction_selNature"><?php print $mod_lang_output['FORM_LABEL_EXACTION_NATURE']; ?></label>
										<div class="col-sm-8">
											<select class="form-control" name="exaction_selNature" id="exaction_selNature">
												<?php print $exaction->admin_cmb_show_cat($exaction->tbl_exactionNature, $exaction->fld_exactionNatureId, $_POST['exaction_selNature']); ?>
											</select>
										</div>
									</div>
        								
									<div class="form-group">    
										<label class="col-sm-4 control-label" for="exaction_selTown"><?php print $mod_lang_output['FORM_LABEL_EXACTION_TOWN']; ?></label>
										<div class="col-sm-8">
											<select class="form-control" name="exaction_selTown" id="exaction_selTown">
												<?php print $exaction->cmb_load_exaction_place($_POST['exaction_selTown']); ?>
											</select>
										</div>
									</div>
        								
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php print $mod_lang_output['FORM_LABEL_EXACTION_DATE']; ?></label>
										<div class="col-sm-8">
											<?php print $exaction->combo_date($_POST['cmbDay'], $_POST['cmbMonth'], $_POST['cmbYear'], '1', $_SESSION['LANG']); ?>
										</div>
									</div>
        								
									<div class="form-group">
										<label class="col-sm-4 control-label" for="exaction_taVictim"><?php print $mod_lang_output['FORM_LABEL_EXACTION_VICTIM']; ?></label>
										<div class="col-sm-8">
											<textarea style="width:100%;" name="exaction_taVictim" cols="40" rows="3" id="exaction_taVictim"><?php print $exaction->show_content($exaction_insert_err_msg, $_POST['exaction_taVictim']) ?></textarea>
										</div>
									</div>
											
									<div class="form-group">
										<label class="col-sm-4 control-label" for="exaction_taDescr"><?php print $mod_lang_output['FORM_LABEL_EXACTION_DESCR']; ?></label>
										<div class="col-sm-8">
											<textarea style="width:100%;" name="exaction_taDescr" cols="40" rows="5" id="exaction_taDescr"><?php print $exaction->show_content($exaction_insert_err_msg, $_POST['exaction_taDescr']) ?></textarea>
										</div>
									</div>
        						    <!--     
									<div class="form-group">
										<label class="col-sm-4 control-label" for="exaction_filePj"><?php print $lang_output['FORM_LABEL_PJ']; ?></label>
										<div class="col-sm-8">
											<input class="form-control1" type="file" name="exaction_filePj" id="exaction_filePj" />
										</div>
									</div>
        						     -->   	
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-8 col-sm-offset-4">
												<button name="exaction_btnInsert" id="exaction_btnInsert" type="submit" class="btn-success btn" data-target="#modal-exactionInsert"><?php print $mod_lang_output['FORM_BUTTON_ADD_EXACTION']; ?></button>
												<input type="hidden" />
											</div>
										</div>
									</div>
        
								</form>
							</div>
						</div>
						<?php //} ?>
					</div>
				</div>
				<div class='modal-footer'>
					<button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Fix the Modal  -->
		<?php if(isset($_POST['exaction_btnInsert'])) { ?>
			<script> $('a[data-target="#modal-exactionInsert"]').click(); </script>
		<?php } ?>
    <!-- ****************************************** -->
               
			   <!-- <div class="clearfix"></div> -->
				
        	<!-- ********************************* Modifier les exactions  ******************************************************
            ****************************************************************************************************************-->
	
				<?php if($_REQUEST['what']=="exactionUpdate") {  $tabExaction = $myExaction->get_exaction($_REQUEST[$myExaction->URI_exactionVar]); ?>
					<?php if (in_array($_REQUEST[$myExaction->URI_exactionVar], $tabExaction)) {?>
    					<h3>
        					<?php print $mod_lang_output['MODULE_NAME']; ?> :: <?php print $mod_lang_output['PAGE_HEADER_UPDATE_EXACTION']; ?>
        					<span style="float:right;">	
        						<?php print $myExaction->admin_load_exaction_nav($_REQUEST[$myExaction->URI_exactionVar], '?what=exactionUpdate'); ?>
        					</span>
        				</h3>
    					<div class="panel panel-default">
    						<div class="panel-heading">
        						<?php print $mod_lang_output['PAGE_HEADER_UPDATE_EXACTION']; ?>
        						<span style='float:right;'>
        							<a href="<?php print $myExaction->admin_modPage; ?>" title="<?php print $mod_lang_output['PAGE_HEADER_LIST_EXACTION']; ?>"><?php print $system->admin_button_crud('back'); ?></a>
        							<a title="<?php print $mod_lang_output['FORM_BUTTON_ADD_EXACTION_PJ']; ?>" data-toggle="modal" data-target="#modal-exactionPjInsert"><i class="fa fa-paperclip"></i></a>
        						</span>
    						</div>
    						
    						<div class="panel-body">
        						<?php $currentLang = (($_REQUEST['langId'] != '') ? ($_REQUEST['langId']) : ($_SESSION['LANG'])); ?>
        			      
        						<?php if(isset($exaction_update_err_msg)) {  ?>
        							<div class="ADM_err_msg"><?php print $exaction_update_err_msg; ?></div>
        						<?php } ?>
        						<?php if(isset($exaction_update_cfrm_msg)) {  ?>
        							<div class="ADM_cfrm_msg"><?php print $exaction_update_cfrm_msg; ?></div>
        						<?php } ?>
        						<div class="tab-content">
        							<div class="tab-pane active" id="horizontal-form">
        								<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="frm_exaction_update" id="frm_exaction_update">
        						        
        									<div class="form-group">    
        										<label class="col-sm-2 control-label" for="exaction_selTypeUpd"><?php print $mod_lang_output['FORM_LABEL_EXACTION_TYPE']; ?></label>
        										<div class="col-sm-8">
        											<select class="form-control" name="exaction_selTypeUpd" id="exaction_selTypeUpd">
        												<?php print $exaction->admin_cmb_show_cat($exaction->tbl_exactionType, $exaction->fld_exactionTypeId, $tabExaction['TYPE']); ?>
        											</select>
        										</div>
        										<div class="col-sm-2">
        											<!-- <p class="help-block">Your help text!</p> -->
        										</div>
        									</div>
        								
        									<div class="form-group">    
        										<label class="col-sm-2 control-label" for="exaction_selNatureUpd"><?php print $mod_lang_output['FORM_LABEL_EXACTION_NATURE']; ?></label>
        										<div class="col-sm-8">
        											<select class="form-control" name="exaction_selNatureUpd" id="exaction_selNatureUpd">
        												<?php print $exaction->admin_cmb_show_cat($exaction->tbl_exactionNature, $exaction->fld_exactionNatureId, $tabExaction['NATURE']); ?>
        											</select>
        										</div>
        										<div class="col-sm-2">
        											<!-- <p class="help-block">Your help text!</p> -->
        										</div>
        									</div>
        								
        									<div class="form-group">    
        										<label class="col-sm-2 control-label" for="exaction_selTownUpd"><?php print $mod_lang_output['FORM_LABEL_EXACTION_TOWN']; ?></label>
        										<div class="col-sm-8">
        											<select class="form-control" name="exaction_selTownUpd" id="exaction_selTownUpd">
        												<?php print print $exaction->cmb_load_exaction_place($tabExaction['TOWN']); ?>
        											</select>
        										</div>
        										<div class="col-sm-2">
        											<!-- <p class="help-block">Your help text!</p> -->
        										</div>
        									</div>
        								
        									<div class="form-group">
        										<label class="col-sm-2 control-label"><?php print $lang_output['FORM_LABEL_PUB-DATE']; ?></label>
        										<div class="col-sm-8">
        											<?php print $exaction->combo_load_date_update($tabExaction['DATE'], 'Upd'); ?>
        										</div>
        										<div class="col-sm-2">
        											<!-- <p class="help-block">Your help text!</p> -->
        										</div>
        									</div>
        								
        									<div class="form-group">
        										<label class="col-sm-2 control-label" for="exaction_taVictimUpd"><?php print $mod_lang_output['FORM_LABEL_EXACTION_VICTIM']; ?></label>
        										<div class="col-sm-8">
        											<textarea style="width:100%;" name="exaction_taVictimUpd" cols="40" rows="3" id="exaction_taVictimUpd"><?php print $tabExaction['VICTIM']; ?></textarea>
        										</div>
        										<div class="col-sm-2">
        											<!-- <p class="help-block">Your help text!</p> -->
        										</div>
        									</div>
        								
        									<div class="form-group">
        										<label class="col-sm-2 control-label" for="exaction_taDescrUpd"><?php print $mod_lang_output['FORM_LABEL_EXACTION_DESCR']; ?></label>
        										<div class="col-sm-8">
        											<textarea style="width:100%;" name="exaction_taDescrUpd" cols="40" rows="5" id="exaction_taDescrUpd"><?php print $tabExaction['DESCR']; ?></textarea>
        										</div>
        										<div class="col-sm-2">
        											<!-- <p class="help-block">Your help text!</p> -->
        										</div>
        									</div>
        						       	
        									<div class="panel-footer">
        										<div class="row">
        											<div class="col-sm-8 col-sm-offset-2">
        												<button type="submit" name="exaction_btnUpdate" id="exaction_btnUpdate" class="btn-success btn"><?php print $mod_lang_output['FORM_BUTTON_UPDATE_EXACTION']; ?></button>
        												<input type="hidden" name="exaction_hdUpdate" value="<?php print $tabExaction['ID']; ?>" />
        											</div>
        										</div>
        									</div>
        
        								</form>
        							</div>
        						</div>
        					</div> <!-- Fin Panel Body -->
        				</div> <!-- Fin Panel  -->
        			<?php } ?>
				<?php } ?>
					

                <!-- ****************************************** -->
                
                <!-- **********************  Exaction au detail ******************************** -->
                <?php
                if($_REQUEST['what'] == 'exactionDetail'){
                    $tab_exactionDetail =   $myExaction->get_exaction($_REQUEST[$myExaction->URI_exactionVar]);
                ?>
                <div class="xs">
                	<h3>
        				<?php print $mod_lang_output['MODULE_NAME']; ?> :: <?php print $mod_lang_output['PAGE_HEADER_LIST_EXACTION']; ?>
        				<span style="float:right;">
        					<a href="<?php print $myExaction->admin_modPage; ?>" title="<?php print $mod_lang_output['PAGE_HEADER_LIST_EXACTION']; ?>"><?php print $system->admin_button_crud('back'); ?></a>
        					<a title="<?php print $mod_lang_output['FORM_BUTTON_ADD_EXACTION']; ?>" data-toggle="modal" data-target="#modal-exactionInsert"><?php print $system->admin_button_crud('create'); ?></a>
        					<a title="<?php print $mod_lang_output['FORM_BUTTON_LIST_EXACTIONS']; ?>" data-toggle="modal" data-target="#modal-exactionDisplay"><?php print $system->admin_button_crud('read'); ?></a>
        					<a title="<?php print $mod_lang_output['FORM_BUTTON_UPDATE_EXACTIONS'];?>" href="?what=exactionUpdate&action=exactionUpdate&<?php print $myExaction->URI_exactionVar; ?>=<?php print $_REQUEST[$myExaction->URI_exactionVar];?>"><?php print $myExaction->admin_button_crud('update'); ?></a>
        				</span>
        			</h3>  
                    <div class="col-md-8">
                        <div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
                			<div class="panel-body no-padding">
                				<h4>
                					<i class="fa fa-info-circle"></i>&nbsp;Detail de l'exaction
                					<span style="float:right;">
        							 	<?php print $myExaction->admin_load_exaction_nav($_REQUEST[$myExaction->URI_exactionVar]); ?>
        							</span>
                				</h4>
                				<p>&nbsp;</p>
                				<hr />
                				<h3><?php print $myExaction->get_exaction_type_by_id($tab_exactionDetail['TYPE']); ?></h3>
                				<p><strong><u><i class="fa fa-map-marker"></i>&nbsp;Lieu de l'exaction :</u></strong> <?php print $myExaction->get_exaction_town_by_id($tab_exactionDetail['TOWN']); ?></p>
                				<p><strong><u>Nature de l'exaction :</u></strong> <?php print $myExaction->get_exaction_nature_by_id($tab_exactionDetail['NATURE']); ?></p>
                				
                				<p><strong><u><i class="fa fa-accessible-icon"></i>&nbsp;Victime :</u></strong> <?php print $tab_exactionDetail['VICTIM']; ?></p>
                				<p><strong><u><i class="fa fa-calendar"></i>&nbsp;Date :</u></strong> <?php print $myExaction->set_date_by_lang($tab_exactionDetail['DATE'], $_SESSION['LANG']); ?></p>
                				<hr />
                				<p style="font-weight:bold; text-decoration:underline;">Circonstances :</p>
                				<p><?php print $tab_exactionDetail['DESCR']; ?></p>
                				<p><hr /></p>
                				<span style="float:right;">
        							 	<?php print $myExaction->admin_load_exaction_nav($_REQUEST[$myExaction->URI_exactionVar]); ?>
        						</span>
                				
                			</div>
                		</div> 
                	</div>
                	<div class="col-md-4">
                		<div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
                			<div class="panel-body no-padding">
                				<h4><i class="fa fa-paperclip"></i>&nbsp;Pieces jointes</h4>
                				<hr />
                				<?php print $myExaction->admin_load_exactions_pj($_REQUEST[$myExaction->URI_exactionVar]); ?>
                				<a title="<?php print $mod_lang_output['FORM_BUTTON_ADD_EXACTION_PJ']; ?>" data-toggle="modal" data-target="#modal-exactionPjInsert"><?php print $system->admin_button_crud('create'); ?></a>
                			</div>
                		</div>
                	</div>
                </div>   
			      <?php      
                    }
                  ?>
                
                <!-- ********************************* Ajouter les pieces-jointes des exactions  ******************************************************
                	****************************************************************************************************************-->
                	<div tabindex='-1' class='modal fade' id='modal-exactionPjInsert' role='dialog' aria-hidden='true' aria-labelledby='modal-exactionPjInsertLabel' style='display: none;'>
                		<div class='modal-dialog' style="width:60%;">
                			<div class='modal-content' style="background-color:#eee;">
                				<div class='modal-header panel-heading'>
                					<button class='close' aria-hidden='true' type='button' data-dismiss='modal'><i class="fa fa-times"></i></button>
                					<h2 class='modal-title'><?php print $mod_lang_output['MODULE_NAME']; ?> :: <?php print $mod_lang_output['PAGE_HEADER_ADD_EXACTION_PJ']; ?></h2>
                				</div>
                				<div class='modal-body'>
                					<div>
                						<?php //if($_REQUEST['task']=="exactionInsert") { ?>
                							<?php $currentLang = (($_REQUEST['langId'] != '') ? ($_REQUEST['langId']) : ($_SESSION['LANG'])); ?>
                							<?php if(isset($exaction_pj_insert_err_msg)) {  ?>
                								<div class="ADM_err_msg"><?php print $exaction_pj_insert_err_msg; ?></div>
                							<?php } ?>
                							<?php if(isset($exaction_pj_insert_cfrm_msg)) {  ?>
                								<div class="ADM_cfrm_msg"><?php print $exaction_pj_insert_cfrm_msg; ?></div>
                							<?php } ?>
                						<div class="tab-content">
                							<div class="tab-pane active" id="horizontal-form">
                								<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="frm_exaction_pj_insert" id="frm_exaction_pj_insert">
				
                									<div class="form-group">
                										<label class="col-sm-4 control-label" for="exaction_filePj"><?php print $mod_lang_output['FORM_LABEL_EXACTION_PJ']; ?></label>
                										<div class="col-sm-8">
                											<input class="form-control1" type="file" name="exaction_filePj" id="exaction_filePj" />
                										</div>
                									</div>
                        								
                									<div class="form-group">
                										<label class="col-sm-4 control-label" for="exaction_taPjTitle"><?php print $mod_lang_output['FORM_LABEL_EXACTION_PJ_TITLE']; ?></label>
                										<div class="col-sm-8">
                											<input class="form-control1" type="text" name="exaction_txtPjTitle" id="exaction_txtPjTitle" value="<?php print $exaction->show_content($exaction_pj_insert_err_msg, $_POST['exaction_txtPjTitle']) ?>" />
                										</div>
                									</div>
                											
                									<div class="form-group">
                										<label class="col-sm-4 control-label" for="exaction_taPjDescr"><?php print $mod_lang_output['FORM_LABEL_EXACTION_PJ_DESCR']; ?></label>
                										<div class="col-sm-8">
                											<textarea style="width:100%;" name="exaction_taDescr" cols="40" rows="15" id="exaction_taPjDescr"><?php print $exaction->show_content($exaction_pj_insert_err_msg, $_POST['exaction_taPjDescr']) ?></textarea>
                										</div>
                									</div>
                        						        
                									
                        						       	
                									<div class="panel-footer">
                										<div class="row">
                											<div class="col-sm-8 col-sm-offset-4">
                												<button name="exaction_btnPjInsert" id="exaction_btnPjInsert" type="submit" class="btn-success btn" data-target="#modal-exactionPjInsert"><?php print $mod_lang_output['FORM_BUTTON_ADD_EXACTION_PJ']; ?></button>
                												<input type="hidden" name="exaction_hdId" value="<?php print $_REQUEST[$myExaction->URI_exactionVar]; ?>" />
                											</div>
                										</div>
                									</div>
                        
                								</form>
                							</div>
                						</div>
                						<?php //} ?>
                					</div>
                				</div>
                				<div class='modal-footer'>
                					<button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                				</div>
                			</div>
                		</div>
                	</div>
                	<!-- Fix the Modal  -->
                		<?php if(isset($_POST['exaction_btnPjInsert'])) { ?>
                			<script> $('a[data-target="#modal-exactionPjInsert"]').click(); </script>
                		<?php } ?>
                    <!-- ****************************************** -->
					
<?php include_once('inc/admin_footer.php');?>