<?php
	$userName = $_SESSION['ud_Name'];
	if(isset($_SESSION['CONNECTED_ADMIN']))
		include('admin_menu.php');
	elseif(isset($_SESSION['CONNECTED_EDIT']))
		include('menu_user.php');
?>
<?php 
	print $menu;
?>