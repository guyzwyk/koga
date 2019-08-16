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
	require_once("../modules/interview/library/interview.inc.php");
	$oSmarty 	= new Smarty();
	$system		= new cwd_system();
	$system->session_checker($_SESSION["CONNECTED_ADMIN"]);
	$myInterview	= new cwd_interview();
	$myRss		= new cwd_rss091();
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
	//Modifier une interview
	$btn_interview_upd	= 	$_POST[btn_interview_upd];
	$txt_nameUpd		= 	$_POST[txt_nameUpd];
	$hd_interview_id	= 	$_POST[hd_interview_id];
	$txt_rankUpd		= 	$_POST[txt_rankUpd];
	$ta_subjectUpd 		= 	stripslashes($_POST[ta_subjectUpd]);
	$txt_emailUpd		= 	$_POST[txt_emailUpd];
	$txt_telUpd			= 	$_POST[txt_telUpd];
	$txt_cniUpd			= 	$_POST[txt_cniUpd];
	$dateUpd			= 	$_POST[cmbYearUpd]."-".$_POST[cmbMonthUpd]."-".$_POST[cmbDayUpd];
	
	if(isset($btn_interview_upd)){
		if(empty($txt_interview_title_upd) || empty($ta_interview_content_upd))
			$interview_update_err_msg 	= "Veuillez remplir <strong>tous</strong> les champs obligatoires svp...";
		elseif($myInterview->interview_update(
									$hd_interview_id,
								  	$interview_selCatUpd, 
								  	$_SESSION['uId'], 
								  	$txt_interview_title_upd,
								  	$ta_interview_content_upd,
								  	$txt_interview_signature_upd,
								  	$datePubUpd,
								  	$interview_selLangUpd
								  	)){
			$interview_update_cfrm_msg 	= "interview modifi&eacute;e avec succ&egrave;s";
			//Creer le Data Set Correspondant
			//$myInterview->create_xml_interview("../modules/interview/xml/interview.xml");
			$myRss->makeRSS("../rss/interviews.xml");
		}
	}
?>

<?php
	//Ajouter une cat&eacute;gorie
	$btn_cat_insert	= $_POST[btn_cat_insert];
	$selLang		= $_POST[selLang];
	$txt_cat_lib 	= addslashes($_POST[txt_cat_lib]);
	
	if(isset($btn_cat_insert)){
		if(empty($txt_cat_lib))
			$rub_insert_err_msg 	= "You must specify a category...";
		elseif($myInterview->chk_entry_twice($myInterview->tbl_interviewCat, "interview_cat_lib", "lang", $txt_cat_lib, $selLang))
			$rub_insert_err_msg 	= "Sorry!<br />That category still exists.<br />Please try again...!";
		elseif($myInterview->interview_cat_insert($txt_cat_lib, $selLang))
			$rub_insert_cfrm_msg 	= "Category successfully inserted";
	}
?>

<?php
	//Modifier une cat&eacute;gorie d'interview
	$hd_cat_id			= $_POST[hd_cat_id];
	$btn_cat_upd		= $_POST[btn_cat_upd];
	$interview_selLangUpd	= $_POST[interview_selLangUpd];
	$txt_cat_lib_upd 	= addslashes($_POST[txt_cat_lib_upd]);
	
	if(isset($btn_cat_upd)){
		if(empty($txt_cat_lib_upd))
			$rub_update_err_msg 	= "Please you must specify a category...";
		elseif($myInterview->update_interview_cat($hd_cat_id, $txt_cat_lib_upd, $interview_selLangUpd))
			$rub_update_cfrm_msg 	= "Category successfully modified";
	}
?>

<?php
	//Ajouter une interview
	$btn_interview_insert		= $_POST[btn_interview_insert];
	$interview_selCat			= $_POST[interview_selCat];
	$interview_selLang		= $_POST[interview_selLang];
	$txt_interview_title 		= addslashes($_POST[txt_interview_title]);
	$txt_interview_signature	= addslashes($_POST[txt_interview_signature]);
	$ta_interview_content		= addslashes($_POST[ta_interview_content]);
	$datePub				= $_POST[cmbYear]."-".$_POST[cmbMonth]."-".$_POST[cmbDay];
	$dateInsert				= $myInterview->get_date();
	
	
	//Les pieces-jointes
	$interviewPJ_name = $_FILES[interviewPJ][name];
	$interviewPJ_size = $_FILES[interviewPJ][size];
	
	
	//Extensions acceptes
	$tabExt = array("gif", "jpg", "png", "pdf", "docx", "rtf", "doc", "odt");
	
	//Rpertoire de destination des CV
	$uploaddir = "../dox/";
	
	
	//Validations *****************************************************************************
	if(isset($btn_interview_insert)){
		$interview_img='no_interview_img';
		if(empty($txt_interview_title) || empty($ta_interview_content))
			$interview_insert_err_msg 	= "Veuillez remplir <strong>tous</strong> les champs obligatoires svp...";
		elseif($myInterview->chk_entry($myInterview->tbl_interview, "interview_title", $txt_interview_title))
			$interview_insert_err_msg 	= "D&eacute;sol&eacute;!<br />Ce titre existe d&eacute;j&agrave;.<br />Veuillez reessayer ult&eacute;rieurement svp...!";
		elseif($myInterview->chk_entry($myInterview->tbl_interview, "interview_lib", $ta_interview_content))
			$interview_insert_err_msg 	= "D&eacute;sol&eacute;!<br />Cette interview a d&eacute;j&agrave; &eacute;t&eacute; enregistr&eacute;!<br />";
		elseif($insertId = $myInterview->interview_insert($interview_selCat, $_SESSION['uId'], $txt_interview_title,  $ta_interview_content, $txt_interview_signature, $interview_img, $datePub, $dateInsert, $interviewPJ, $interview_selLang)){
			$interview_insert_cfrm_msg 	= "interview ajout&eacute; avec succ&egrave;s";
			if($interviewPJ_name != ""){
				//Envoyer le fichier et Mettre la table a jour
				if(move_uploaded_file($_FILES[interviewPJ]['tmp_name'], $uploaddir . $interviewPJ_name) && ($myInterview->interview_element_update("interview_pj", $interviewPJ_name, $insertId)))
					$interview_insert_cfrm_msg .= "<br />Une pi&egrave;ce-jointe est ajout&eacute;e &agrave; l'interview.";
			}
		}
	}
?>

<?php
	//Actions sur les interviews et leurs rubriques
	$what 			  = $_REQUEST[what];
	$action			  = $_REQUEST[action];
	$interviewId	= $_REQUEST[interviewId];
	$icId			    = $_REQUEST[icId];
	
	switch($action){
		case "interviewDelete" : $toDo 					= $myInterview->del_interview($interviewId);
							$interview_display_cfrm_msg	= "News supprim&eacute; avec succ&egrave;s";
							//Cr&eacute;er le Data Set Correspondant
							//$myInterview->create_xml_interview("../modules/interview/xml/interview.xml");
							$myRss->makeRSS("../rss/interviews.xml");
		break;
		case "interviewPrivate": $toDo					= $myInterview->set_interview_state($interviewId, "0");
							$interview_display_cfrm_msg	= "Vous avez rendu la news inaccessibe aux visiteurs du site.";
							//Cr&eacute;er le Data Set Correspondant
							//$myInterview->create_xml_interview("../modules/interview/xml/interview.xml");
							$myRss->makeRSS("../rss/interviews.xml");
		break;
		case "interviewPublish":	$toDo					= $myInterview->set_interview_state($interviewId, "1");
							$interview_display_cfrm_msg	= "Vous avez publi&eacute; la news aux visiteurs du site.";
							//Cr&eacute;er le Data Set Correspondant
							//$myInterview->create_xml_interview("../modules/interview/xml/interview.xml");
							$myRss->makeRSS("../rss/interviews.xml");
		break;
		case "interviewUpdate" : $tabUpd			= $myInterview->get_interview($interviewId); //Ce tableau sera utilis pour remplir les champs du formulaire de modification
							$interview_displayUpd = true;
		break;
		case "interviewCatDelete" : $toDo					= $myInterview->del_interview_cat($acId);
							   $rub_display_cfrm_msg	= "Cat&eacute;gorie supprim&eacute;e avec succ&egrave;s";
							  
		break;
		case "interviewCatUpdate" : $tabCatUpd 				= $myInterview->get_interview_cat($acId);
							   $rub_displayUpd				= true;
		break;
		case "interviewCatPublish" : $toDo 					= $myInterview->set_interviewcat_state($acId, 1);
								$rub_display_cfrm_msg	= "Cat&eacute;gorie rendue publique.<br />Toutes les interviews appartenant &agrave; cette cat&eacute;gorie seront dor&eacute;navant accessibles au visiteur!";
		case "interviewCatPrivate" : $toDo					= $myInterview->set_interviewcat_state($acId, 0);
								$rub_display_cfrm_msg	= "Cat&eacute;gorie rendue priv&eacute;.<br />Toutes les interviews appartenant &agrave; cette cat&eacute;gorie seront inaccessibles au visiteur!";
		break;
	}
?>
<?php 
	ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Espace d'Administration :: Gestion des demandes d'audiences</title>
<link rel="shortcut icon" href="../img/dzine/icons/favicon.png" type="image/x-png" />
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="css/admin.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="wysiwyg.js"></script>
<script language="JavaScript" type="text/javascript" src="js/cms_admin.js"></script>
</head>

<body>
<center>
<div id="wrapper">
  <div id="topHead"><img src="img/dzine/topHead.jpg" /></div>
  <div id="headContent">
    <div class="content">
      <div class="appTitle">Espace d'administration cwd MyAdmin 2.0</div>
      <div class="appDescr">Interface de gestion intuitive du contenu de votre site web.</div>
      
    </div>
  </div>
  <div id="headSeparator"><img src="img/dzine/headSeparator.jpg" width="996" height="12" /></div>
  <div id="menuContent">
    <div class="content">
    	<?php print $myInterview->admin_main_menu_display(); ?>
    	<div style="float:right;"><a href="#">Modifier mon compte</a></div>
    </div>
    <div class="clear_both"></div>
  </div>  
  <div id="headSub">
  	<img src="img/dzine/headSub.jpg" height="9" width="996" />
    <div class="clear_both"></div>
  </div>
  <div id="mainContent">
    <div class="content"> 
	  <?php print $myInterview->admin_get_menu(); ?>
      
      
      <?php if($_REQUEST[what]=="interviewCatInsert"){ ?>
      <h1>Nouvelle cat&eacute;gorie d'interview</h1>
      
      <?php if(isset($rub_insert_err_msg)) {  ?>
      <div class="ADM_err_msg"><?php print $rub_insert_err_msg; ?></div>
      <?php } ?>
      <?php if(isset($rub_insert_cfrm_msg)) {  ?>
      <div class="ADM_cfrm_msg"><?php print $rub_insert_cfrm_msg; ?></div>
      <?php } ?>
      <form id="frm_rub_insert" name="frm_rub_insert" method="post" action="">
        <table class="ADM_form">
          <tr>
            <th align="right">Choisir la langue : </th>
            <td><select name="interview_selLang" id="interview_selLang">
                <?php print $myInterview->cmb_showLang($_POST[interview_selLang]); ?>
              </select>            </td>
          </tr>
          <tr>
            <th align="right">Intitul&eacute; : </th>
            <td><input name="txt_cat_lib" type="text" id="txt_cat_lib" value="" size="40" /></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td><input type="submit" value="Ajouter la cat&eacute;gorie" name="btn_cat_insert" id="btn_cat_insert" /></td>
          </tr>
        </table>
      </form>
      <?php } ?>
      
      <!-- ********************************* Afficher les demandes d'audiences  ******************************************************
      ****************************************************************************************************************-->
      <?php if(isset($rub_displayUpd)){ ?>
      <h1>Modifier une cat&eacute;gorie</h1>
      
      <?php if(isset($rub_update_err_msg)) {  ?>
      <div class="ADM_err_msg"><?php print $rub_update_err_msg; ?></div>
      <?php } ?>
      <?php if(isset($rub_update_cfrm_msg)) {  ?>
      <div class="ADM_cfrm_msg"><?php print $rub_update_cfrm_msg; ?></div>
      <?php } ?>
      <form id="frm_cat_update" name="frm_cat_update" method="post" action="">
        <table class="ADM_form">
          <tr>
            <th align="right">Choisir la langue : </th>
            <td><select name="interview_selLangUpd" id="interview_selLangUpd">
                <?php print $myInterview->cmb_showLang($tabCatUpd[interviewCATLANG]); ?>
              </select>
            </td>
          </tr>
          <tr>
            <th align="right">Libell&eacute;: </th>
            <td><input name="txt_cat_lib_upd" value="<?php print $tabCatUpd[interviewCATLIB]; ?>" type="text" id="txt_cat_lib_upd" size="30" /></td>
          </tr>
          <tr>
            <th align="right"><input type="hidden" name="hd_cat_id" value="<?php print $_REQUEST[acId]; ?>" /></th>
            <td><input type="submit" value="Modifier la cat&eacute;gorie" name="btn_cat_upd" id="btn_cat_upd" /></td>
          </tr>
        </table>
      </form>
      <?php } ?>
      
      
      <?php if($_REQUEST[what]=="interviewCatDisplay") { ?>
          <h1>Afficher les cat&eacute;gories</h1>
          
          <?php if(isset($rub_display_err_msg)) {  ?>
          <div class="ADM_err_msg"><?php print $rub_display_err_msg; ?></div>
          <?php } ?>
          <?php if(isset($rub_display_cfrm_msg)) {  ?>
          <div class="ADM_cfrm_msg"><?php print $rub_display_cfrm_msg; ?></div>
          <?php } ?>
          
          <?php print $myInterview->admin_load_interviews_cat(); ?>
      <?php } ?>
      
      
      <?php if($_REQUEST[what]=="interviewInsert") { ?>
      <h1>Nouvelle demande d'audience</h1>
      
      <?php if(isset($interview_insert_err_msg)) {  ?>
      <div class="ADM_err_msg"><?php print $interview_insert_err_msg; ?></div>
      <?php } ?>
      <?php if(isset($interview_insert_cfrm_msg)) {  ?>
      <div class="ADM_cfrm_msg"><?php print $interview_insert_cfrm_msg; ?></div>
      <?php } ?>
      <form action="" method="post" enctype="multipart/form-data" name="frm_interview_insert" id="frm_interview_insert">
        <table class="ADM_form">
          <tr>
            <th align="right">Choisir la langue : </th>
            <td><select name="interview_selLang" id="interview_selLang">
                <?php print $myInterview->cmb_showLang($_POST[interview_selLang]); ?>
              </select></td>
          </tr>
          <tr>
            <th align="right">Choisir la cat&eacute;gorie : </th>
            <td><select name="interview_selCat" id="interview_selCat">
                <?php print $myInterview->admin_cmb_show_rub($_POST[interview_selCat]); ?>
              </select>            </td>
          </tr>
          <tr>
            <th align="right">Titre : </th>
            <td><input name="txt_interview_title" type="text" value="<?php print $myInterview->show_content($interview_insert_err_msg, $txt_interview_title) ?>" id="txt_interview_title" size="40" /></td>
          </tr>
          <tr>
            <th align="right">Contenu de l'interview: </th>
            <td><textarea name="ta_interview_content" cols="40" rows="5" id="ta_interview_content"><?php print $myInterview->show_content($interview_insert_err_msg, $ta_interview_content) ?></textarea>
            	<script language="JavaScript1.2" type="text/javascript">
					generate_wysiwyg('ta_interview_content');
	  			</script>
	  		</td>
          </tr>
          <tr>
            <th align="right">Signature : </th>
            <td><input name="txt_interview_signature" type="text" value="<?php print $myInterview->show_content($interview_insert_err_msg, $txt_interview_signature) ?>" id="txt_interview_signature" size="35" /></td>
          </tr>
          <tr>
            <th align="right">Date de publication : </th>
            <td><?php print $myInterview->combo_dateFr($_POST[cmbDay], $_POST[cmbMonth], $_POST[cmbYear]); ?></td>
          </tr>
          <tr>
            <th align="right">Pi&egrave;ce - Jointe : </th>
            <td><input type="file" name="interviewPJ" id="interviewPJ" /></td>
          </tr>
          <tr>
            <th align="right">&nbsp;</th>
            <td><input type="submit" value="Ajouter l'interview" name="btn_interview_insert" id="btn_interview_insert" /></td>
          </tr>
        </table>
      </form>
      <?php } ?>
      <?php if(!isset($_REQUEST[what]) || ($_REQUEST[what]=="interviewDisplay")) { ?>
          <h1>Afficher les demandes d'audience</h1>
		  <?php $myInterview->limit = $_REQUEST[limite]; ?>
          
          <?php if(isset($itw_display_err_msg)) {  ?>
          <div class="ADM_err_msg"><?php print $itw_display_err_msg; ?></div>
          <?php } ?>
          <?php if(isset($itw_display_cfrm_msg)) {  ?>
          <div class="ADM_cfrm_msg"><?php print $itw_display_cfrm_msg; ?></div>
          <?php } ?>
          
          <?php print stripslashes($myInterview->admin_load_interviews()); ?>
      <?php } ?>
      
      
      <?php 
      	if($_REQUEST[what] == "interviewUpdate") { // Si on a clique sur modifier l'interview... 
	  		$tabUpd	= $myInterview->get_interview($_REQUEST[$myInterview->mod_queryKey]);
	  ?>
      <h1>Modifier une interview</h1>
      <?php if(isset($interview_update_err_msg)) { ?>
      <div class="ADM_err_msg"><?php print $interview_update_err_msg; ?></div>
      <?php } ?>
      <?php if(isset($interview_update_cfrm_msg)) { ?>
      <div class="ADM_cfrm_msg"><?php print $interview_update_cfrm_msg; ?></div>
      <?php } ?>
      <form id="frm_interview_update" name="frm_interview_update" method="post" action="">
        <table class="ADM_form">
          <tr>
            <th align="right">Nom(s) et Pr&eacute;nom(s) : </th>
            <td><input name="txt_nameUpd" id="txt_nameUpd" type="text" value="<?php print $tabUpd["NAME"]; ?>" /></td>
          </tr>
          <tr>
            <th align="right">Qualit&eacute; / Rang : </th>
            <td><input name="txt_nameUpd" id="interview_nameUpd" type="text" value="<?php print $tabUpd["RANK"]; ?>" /></td>
          </tr>
          <tr>
            <th align="right">Objet : </th>
            <td><textarea name="ta_subjectUpd" cols="40" rows="5" id="ta_subjectUpd"><?php print stripslashes($tabUpd["SUBJECT"]); ?></textarea></td>
          </tr>
            <th align="right">Email : </th>
            <td><input name="txt_emailUpd" type="text" value="<?php print stripslashes($tabUpd["EMAIL"]); ?>" id="txt_emailUpd" size="40" /></td>
          </tr>
          <tr>
            <th align="right">Numero de T&eacute;l&eacute;phone : </th>
            <td><input name="txt_telUpd" type="text" value="<?php print stripslashes($tabUpd["TELEPHONE"]); ?>" id="txt_telUpd" size="35" /></td>
          </tr>
          <tr>
            <th align="right">Numero CNI : </th>
            <td><input name="txt_cniUpd" type="text" value="<?php print stripslashes($tabUpd["IDNUM"]); ?>" id="txt_cniUpd" size="35" /></td>
          </tr>
          <tr>
            <th align="right">Date pr&eacute;vue : </th>
            <td><?php print $myInterview->combo_datetimeFrUpd($tabUpd[DATE], 1910, ''); ?></td>
          </tr>
          <tr>
            <th align="right"><input type="hidden" name="hd_interview_id" value="<?php print $_REQUEST[interviewId]; ?>" /></th>
            <td><input type="submit" value="Modifier la demande d'audience" name="btn_interview_upd" id="btn_interview_upd" /></td>
          </tr>
        </table>
      </form>
      <?php } ?>
</div>
  <div class="clear_both"></div>
  </div>
  
  <div id="subSeparator"><img src="img/dzine/subSeparator.jpg" /></div>
  <div id="subContent">
    <div class="content">&nbsp;Copyright &copy; cawad Labs 2008<br />
     - All rights reserved - </div>
  </div>
</div>
</center>
</body>
</html>
