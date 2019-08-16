<div id="openModalConnected" class="modalDialog">
		<div style="width:80%;">
			<h1>Now connected</h1>
			<a href="#close" title="Close" class="close">X</a>
            <div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
                <div class="panel-body no-padding">
                	<div class="scrollbar" style="max-height:80%;">
	                	<?php isset($_SESSION['CONNECTED_ADMIN']) ? ($dbView = '100') : ($dbView='0'); ?>
	                    <?php print $member->admin_load_connected($dbView); ?>
	                </div>
                </div>
            </div>
			<p>&nbsp;</p><p>&nbsp;</p>
		</div>
	</div>
	
	

	<!-- ********************************* Afficher les membres  ******************************************************
      ****************************************************************************************************************-->
      <div id="openModalMembers" class="modalDialog">
      	<div style="width:80%;">
			<h1>Registered associations</h1>
			<a href="#close" title="Close" class="close">X</a>
			<div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
                <div class="panel-body no-padding">
                	<div class="scrollbar" style="max-height:80%;">
					  <?php $member->limit = $_REQUEST[limite]; ?>
			          <?php isset($_SESSION['CONNECTED_ADMIN']) ? ($dbView = '1') : ($dbView='0'); ?>
			          <?php print $member->admin_load_members(4000); ?>
		          	</div>
		        </div>
            </div>
			<p>&nbsp;</p><p>&nbsp;</p>
		</div>
	</div>
   
   
   <!-- ********************************* Afficher le contenu du fichier log  ******************************************************
      ****************************************************************************************************************-->
      <div id="openModalLog" class="modalDialog">
      	<div style="width:80%;">
			<h1>Connected users activity</h1>
			<a href="#close" title="Close" class="close">X</a>
			<div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
                <div class="panel-body no-padding">
		          <div class="scrollbar" style="max-height:80%;">
		          	<?php print $member->read_file_content('../log/log.gzk'); ?>
		          </div>
		          <?php if (isset($_SESSION['CONNECTED_ADMIN'])) { ?><div class="buttons"><a onClick="return confirm('Are you sure you want to clear the log file?');" class="btn btn-primary btn-lg" href="?what=clearLog">Clear the log file</a></div><?php } ?>
		        </div>
            </div>
			<p>&nbsp;</p><p>&nbsp;</p>
		</div>
	</div> 