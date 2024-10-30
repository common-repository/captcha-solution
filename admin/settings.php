<div class="wrap">
	<h1>Captcha Solution v1.0</h1>
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
	   <a href="admin.php?page=essitco_captcha_solution&tab=features" class="nav-tab <?php echo !isset($_GET['tab']) || @$_GET['tab'] =="features" ? "nav-tab-active" : "" ?>">Features</a>
	   <a href="admin.php?page=essitco_captcha_solution&tab=settings" class="nav-tab <?php echo @$_GET['tab'] =="settings" ? "nav-tab-active" : "" ?>">Settings</a>
	</nav>
	<?php
	$tab = isset($_GET['tab']) ? $_GET['tab'] : 'features';

	switch ($tab) {
		case "settings":
			include('inc/settings.php');
			break;
		case "features":
			include('inc/features.php');
			break;
		default:
			include('inc/features.php');
	}
	?>
</div>