<?php
    //Traitement de settings...
	$txtSiteName	= addslashes($_POST['txtSiteName']);
	$taSiteDescr	= addslashes($_POST['taSiteDescr']);
	$txtSiteEmail	= addslashes($_POST['txtSiteEmail']);
	$selSiteLang	= $_POST['selSiteLang'];
	$selSiteState	= $_POST['selSiteState'];
	$hdSiteLogo		= $_POST['hdSiteLogo'];
	$btnSiteOk		= $_POST['btnSiteOk'];
	
	//Gestion des images
	$mySettingsImg		= new cwd_gallery("../img/logos/", "../img/logos/");
	//Definition du repertoire d'envoi des images et des imagettes
	$mySettingsImg->set_fileDirectory("../img/logos/");
	$fileSiteLogo_name	= $_FILES['fileSiteLogo']['name'];
	$fileSiteLogo_temp	= $_FILES['fileSiteLogo']['tmp_name'];
	$fileSiteLogo_size	= $_FILES['fileSiteLogo']['size'];

	if(isset($btnSiteOk)){
		$mySettingsImg->set_fileTempName($fileSiteLogo_temp);
		if($fileSiteLogo_name == ""){
			if($system->update_site_settings($txtSiteName, $taSiteDescr, $txtSiteEmail, $selSiteLang, $hdSiteLogo, $selSiteState))
				$cfrm_msg = "Settings successfully updated!";
			else
				$err_msg	= "Error while updating the website global settings";
		}
		else{
			if($system->update_site_settings($txtSiteName, $taSiteDescr, $txtSiteEmail, $selSiteLang, $fileSiteLogo_name, $selSiteState)){
				$mySettingsImg->set_fileDirectory("../img/logos/");
				$mySettingsImg->fileSend($fileSiteLogo_name);
				$cfrm_msg	= "Settings successfully updated!";
			}
			else
				$err_msg	= "Error while updating the website global settings";
		}
	}
?>


<?php 
	//Traitement de update settings...
	$file_orig				=	'../scripts/incfiles/config.inc.php';
	$file					=	file($file_orig);
	
	$btn_settings_update	=	$_POST['btn_settings_update'];
	$ta_settings_update		=	$_POST['ta_settings_update'];
	
	if(isset($btn_settings_update)){
		$open = fopen("../scripts/incfiles/config.inc.php","w+");
		fwrite($open, $ta_settings_update);
		fclose($open);
		$msg_update_ok	=	"<div class=\"form-group\">
								<div class=\"col-sm-1\"></div>
								<div class=\"col-sm-10 ADM_cfrm_msg\">Fichier modifi&eacute; avec succ&egrave;s!</div>
								<div class=\"col-sm-1\"></div>
							</div>";
	}
	else{
		
	}
?>

<?php 
	//Traitement des fichiers de langue (Dictionnary) ...
	$file_orig_en					=	'../scripts/langfiles/EN.php';
	$file_orig_fr					=	'../scripts/langfiles/FR.php';
	$file_lang_en					=	file($file_orig_en);
	$file_lang_fr					=	file($file_orig_fr);
	
	$btn_settings_lang_en_update	=	$_POST['btn_settings_lang_en_update'];
	$btn_settings_lang_fr_update	=	$_POST['btn_settings_lang_fr_update'];
	$ta_settings_lang_en_update		=	$_POST['ta_settings_lang_en_update'];
	$ta_settings_lang_fr_update		=	$_POST['ta_settings_lang_fr_update'];
	
	if(isset($btn_settings_lang_en_update)){
		$open = fopen('../scripts/langfiles/EN.php',"w+");
		fwrite($open, $ta_settings_lang_en_update);
		fclose($open);
		$msg_lang_en_update_ok	=	"<div class=\"form-group\">
										<div class=\"col-sm-1\"></div>
										<div class=\"col-sm-10 ADM_cfrm_msg\">Fichier de langue EN modifi&eacute; avec succ&egrave;s!</div>
										<div class=\"col-sm-1\"></div>
									</div>";
	}
	elseif(isset($btn_settings_lang_fr_update)){
		$open = fopen('../scripts/langfiles/FR.php',"w+");
		fwrite($open, $ta_settings_lang_fr_update);
		fclose($open);
		$msg_lang_fr_update_ok	=	"<div class=\"form-group\">
										<div class=\"col-sm-1\"></div>
										<div class=\"col-sm-10 ADM_cfrm_msg\">Fichier de langue FR modifi&eacute; avec succ&egrave;s!</div>
										<div class=\"col-sm-1\"></div>
									</div>";
	}
?>

<div class="col_1">

    <!-- :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
                                Loading setting content
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->

    <?php
        if($_REQUEST['what']    ==  'settings2'){
    ?>
        <h3><i class="fa fa-help"></i>Help :: Server settings</h3>
        	<div class="col-sm-4">
        	<h4>Document root</h4>
        	<p><?php print $_SERVER['DOCUMENT_ROOT']?></p>
        	</div>
        	<div class="col-sm-4">
        	<h4>Adresse IP du serveur</h4>
        	<p><?php print $_SERVER['SERVER_ADDR']?></p>
        	</div>
        	<div class="col-sm-4">
        	<h4>Adresse distante</h4>
        	<p><?php print $_SERVER['PATH']?></p>
        	</div>
        	<p>&nbsp;</p>
        	<p>&nbsp;</p>
        	<h4>PHP Info</h4>
        	<p>Below is the configuration of your online server</p>
        	<?php 
        		//@mail('guyzwyk@gmail.com', 'Test', 'Test from MIDENO Servers');
        	?>
            <p>&nbsp;</p>
            <iframe src="php_info.php" height="800" width="100%"></iframe>
    <?php
        }
    ?>


    <?php
        if($_REQUEST['what']    ==  'config') {
            $currentSettings	= 	$system->get_site_settings();
	?>
		<h3>Help :: Your website global configuration below : </h3>
		<div class="but_list">
			<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">

				<!-- Tab Navigator -->
				<ul id="myTab" class="nav nav-tabs" role="tablist">

					<!-- Nav 1 -->
					<li role="presentation" class="active"><a href="#pageSettings" id="pageSettings-tab" role="tab" data-toggle="tab" aria-controls="pageSettings" aria-expanded="true">Page Settings</a></li>
					
					<!-- Nav 2 -->
					<li role="presentation"><a href="#moduleSettings" role="tab" id="moduleSettings-tab" data-toggle="tab" aria-controls="moduleSettings">Modules settings</a></li>
					
					<!-- Nav 3 -->
					<li role="presentation" class="dropdown">
						<a href="#" id="dropDictionnary" class="dropdown-toggle" data-toggle="dropdown" aria-controls="dropDictionnary-contents" aria-expanded="false">Dictionnary <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropDictionnary" id="dropDictionnary-contents">
							<li class=""><a href="#EN" tabindex="-1" role="tab" id="EN-tab" data-toggle="tab" aria-controls="EN" aria-expanded="false">@English</a></li>
							<li class=""><a href="#FR" tabindex="-1" role="tab" id="FR-tab" data-toggle="tab" aria-controls="FR" aria-expanded="false">@French</a></li>
						</ul>
					</li>
				</ul>

				<!-- Tab Contents -->
				<div id="myTabContent" class="tab-content">
					<!-- Content 1 -->
					<div role="tabpanel" class="tab-pane fade active in" id="pageSettings" aria-labelledby="pageSettings-tab">
						
						<?php if(isset($err_msg)) {  ?>
						<div class="ADM_err_msg"><?php print $err_msg; ?></div>
						<?php } ?>
						<?php if(isset($cfrm_msg)) {  ?>
						<div class="ADM_cfrm_msg"><?php print $cfrm_msg; ?></div>
						<?php } ?>
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="txtSiteName">Site name :</label>
								<div class="col-sm-8">
									<input class="form-control1" name="txtSiteName" type="text" id="txtSiteName" value="<?php print $currentSettings[NAME] ?>" />
								</div>
								<div class="col-sm-2"></div>
							</div>
							<div class="form-group">
								<label for="taSiteDescr" class="col-sm-2 control-label">Site description :</label>
								<div class="col-sm-8">
									<textarea class="form-control1 ta-wide" name="taSiteDescr" cols="40" rows="4" id="taSiteDescr"><?php print $currentSettings[DESCR] ?></textarea>
								</div>
								<div class="col-sm-2">
									<p class="help-block redStar"></p>
								</div>
							</div>
							<div class="form-group">
								<label for="txtSiteEmail" class="col-sm-2 control-label">Administrator e-mail :</label>
								<div class="col-sm-8">
									<input class="form-control1" name="txtSiteEmail" type="text" id="txtSiteEmail" size="50" value="<?php print $currentSettings[EMAIL] ?>" />
								</div>
								<div class="col-sm-2">
									<p class="help-block redStar"></p>
								</div>
							</div>

							<div class="form-group">
								<label for="selSiteLang" class="col-sm-2 control-label">Default language :</label>
								<div class="col-sm-8">
									<select class="form-control" name="selSiteLang" id="selSiteLang">
										<?php print $system->cmb_showLang($currentSettings[LANG]); ?>
									</select>
								</div>
								<div class="col-sm-2">
									<p class="help-block redStar"><a href="#" onclick="<?php print $system->popup("config_lang_param.php", 350, 220); ?>">Language parameters</a></p>
								</div>
							</div>
							
							<div class="form-group">
								<label for="txtSiteEmail" class="col-sm-2 control-label">Logo :</label>
								<div class="col-sm-8">
									<input class="form-control1" name="fileSiteLogo" type="file" id="fileSiteLogo" />
									<input name="hdSiteLogo" type="hidden" id="hdSiteLogo" value="<?php print $currentSettings[LOGO] ?>" />
								</div>
								<div class="col-sm-2">
									<p class="help-block redStar">(JPG, PNG, or GIF format)</p>
								</div>
							</div>

							<div class="form-group">
								<label for="selSiteState" class="col-sm-2 control-label">Site state :</label>
								<div class="col-sm-8">
									<select class="form-control" name="selSiteState" id="selSiteState">
										<?php print $system->cmb_showSiteState($currentSettings['STATE']); ?>
									</select>
								</div>
								<div class="col-sm-2">
									
								</div>
							</div>
							
							<div class="panel-footer">
								<div class="row">
									<div class="col-sm-8 col-sm-offset-2">
										<button type="submit" name="btnSiteOk" id="btnSiteOk" class="btn-success btn">Submit configuration</button>
									</div>
								</div>
							</div>
						</form>
					</div>

					<!-- Content 2 -->
					<div role="tabpanel" class="tab-pane fade" id="moduleSettings" aria-labelledby="moduleSettings-tab">
						<?php
							//print_r($modules->arr_moduleDirs);
							$arr_moduleCat	=	$modules->load_id($modules->tbl_moduleCat, $modules->fld_modCatId);
							foreach($arr_moduleCat as $value){
								print $modules->admin_load_modules_by_cat($value, $_SESSION['LANG']);
							}
						?>
					</div>

					<!-- Content 3 -->
					<div role="tabpanel" class="tab-pane fade" id="EN" aria-labelledby="EN-tab">
					<!-- Load english lang file -->
					<?php
						if($_POST['btn_settings_lang_en_update']){
							$open = fopen("../scripts/langfiles/EN.php","w+");
							$textEN = $_POST['ta_settings_lang_en_update'];
							fwrite($open, $textEN);
							fclose($open);
							$msg_lang_en_update_ok	=	 "English dictionnary successfuly updated.";
							//echo "File:<br />";
							$fileEN = file("../scripts/langfiles/EN.php");
							foreach($fileEN as $textEN) {
								echo $textEN."<br />";
							}
						}else{
							$fileEN = file("../scripts/langfiles/EN.php");
							echo 	"<div class=\"tab-content\">
										<div class=\"tab-pane active\" id=\"horizontal-form\">
											<form class=\"form-horizontal\" action=\"\" method=\"post\">";
							echo "<div class=\"form-group\">
									<div class=\"col-sm-1\"><input type=\"checkbox\" onClick=\"javascript:document.getElementById('ta_settings_lang_en_update').disabled = false;\" /></div>
									<div class=\"col-sm-10 ADM_alert_msg\">Attention!<br />A l'usage du super admin uniquement!</div>	
									<div class=\"col-sm-1\"></div>
								</div>
									$msg_lang_en_update_ok
								<div class=\"form-group\">
									<div class=\"col-sm-12\">
											<textarea onChange=\"javascript:document.getElementById('btn_settings_lang_en_update').disabled = false;\" disabled=\"disabled\" class=\"form-control1\" style=\"height:550px;\" name=\"ta_settings_lang_en_update\" id=\"ta_settings_lang_en_update\">";
							foreach($fileEN as $textEN) {
								echo $textEN;
							}
							echo "</textarea></div></div>";
							echo "<div class=\"panel-footer\">
									<div class=\"row\">
										<div class=\"col-sm-8 col-sm-offset-2\">	
											<input name=\"hd_langId\" type=\"hidden\" value=\"en\" />
											<button disabled=\"disabled\" type=\"submit\" name=\"btn_settings_lang_en_update\" id=\"btn_settings_lang_en_update\" class=\"btn-success btn\">Update dictionnary</button>
										</div>
									</div>
								</div>
							</form></div></div>";
						}
					?>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="FR" aria-labelledby="FR-tab">
						
						<!-- Load french lang file -->
					<?php
						if($_POST['btn_settings_lang_fr_update']){
							$open = fopen("../scripts/langfiles/FR.php","w+");
							$textFR = $_POST['ta_settings_lang_fr_update'];
							fwrite($open, $textFR);
							fclose($open);
							$msg_lang_fr_update_ok	=	 "French dictionnary successfuly updated.";
							//echo "File:<br />";
							$fileFR = file("../scripts/langfiles/FR.php");
							foreach($fileFR as $textFR) {
								echo $textFR."<br />";
							}
						}else{
							$fileFR = file("../scripts/langfiles/FR.php");
							echo 	"<div class=\"tab-content\">
										<div class=\"tab-pane active\" id=\"horizontal-form\">
											<form class=\"form-horizontal\" action=\"\" method=\"post\">";
							echo "<div class=\"form-group\">
									<div class=\"col-sm-1\"><input type=\"checkbox\" onClick=\"javascript:document.getElementById('ta_settings_lang_fr_update').disabled = false;\" /></div>
									<div class=\"col-sm-10 ADM_alert_msg\">Attention!<br />A l'usage du super admin uniquement!</div>	
									<div class=\"col-sm-1\"></div>
								</div>
									$msg_lang_fr_update_ok
								<div class=\"form-group\">
									<div class=\"col-sm-12\">
											<textarea onChange=\"javascript:document.getElementById('btn_settings_lang_fr_update').disabled = false;\" disabled=\"disabled\" class=\"form-control1\" style=\"height:550px;\" name=\"ta_settings_lang_fr_update\" id=\"ta_settings_lang_fr_update\">";
							foreach($fileFR as $textFR) {
								echo $textFR;
							}
							echo "</textarea></div></div>";
							echo "<div class=\"panel-footer\">
									<div class=\"row\">
										<div class=\"col-sm-8 col-sm-offset-2\">
											<input type=\"hidden\" name=\"hd_langId\" value=\"fr\" />
											<button disabled=\"disabled\" type=\"submit\" name=\"btn_settings_lang_fr_update\" id=\"btn_settings_lang_fr_update\" class=\"btn-success btn\">Update dictionnary</button>
										</div>
									</div>
								</div>
							</form></div></div>";
						}
					?>
					</div>
				</div>
			</div>
		</div>
   
    <?php
        }
	?>	
	<!-- Fix the Tabs  -->
	<?php if(isset($_POST['btnSiteOk'])) { ?>
		<script> $('a[href="#pageSettings"]').click(); </script>
	<?php } ?>
	<?php if(isset($_POST['btn_settings_lang_en_update'])) { ?>
		<script> $('a[href="#EN"]').click(); </script>
	<?php } ?>
	<?php if(isset($_POST['btn_settings_lang_fr_update'])) { ?>
		<script> $('a[href="#FR"]').click(); </script>
	<?php } ?>

    


    <?php
        if($_REQUEST['what']    ==  'update'){
    ?>
        <h3><i class="fa fa-question"></i>&nbsp;Aide :: Mise &agrave; jour du fichier de configuration de l'application</h3>

        <?php
            if($_POST['btn_settings_update']){
                $open = fopen("../scripts/incfiles/config.inc.php","w+");
                $text = $_POST['ta_settings_update'];
                fwrite($open, $text);
                fclose($open);
                $msg_update_ok	=	 "Configuration file successfuly updated.";
                //echo "File:<br />";
                $file = file("../scripts/incfiles/config.inc.php");
                foreach($file as $text) {
                    echo $text."<br />";
                }
            }else{
                $file = file("../scripts/incfiles/config.inc.php");
                echo 	"<div class=\"tab-content\">
                            <div class=\"tab-pane active\" id=\"horizontal-form\">
                                <form class=\"form-horizontal\" action=\"\" method=\"post\">";
                echo "<div class=\"form-group\">
                        <div class=\"col-sm-1\"><input type=\"checkbox\" onClick=\"javascript:document.getElementById('ta_settings_update').disabled = false;\" /></div>
                        <div class=\"col-sm-10 ADM_alert_msg\">Attention!<br />A l'usage du super admin uniquement!</div>	
                        <div class=\"col-sm-1\"></div>
                    </div>
                        $msg_update_ok
                    <div class=\"form-group\">
                        <div class=\"col-sm-12\">
                                <textarea onChange=\"javascript:document.getElementById('btn_settings_update').disabled = false;\" disabled=\"disabled\" class=\"form-control1\" style=\"height:550px;\" name=\"ta_settings_update\" id=\"ta_settings_update\">";
                foreach($file as $text) {
                    echo $text;
                }
                echo "</textarea></div></div>";
                echo "<div class=\"panel-footer\">
                        <div class=\"row\">
                            <div class=\"col-sm-8 col-sm-offset-2\">
                                <button disabled=\"disabled\" type=\"submit\" name=\"btn_settings_update\" id=\"btn_settings_update\" class=\"btn-success btn\">Update settings</button>
                            </div>
                        </div>
                    </div>
                        </form></div></div>";
            }
        ?>
    <?php
        }
    ?>

    <div class="clearfix"> </div>
</div>




