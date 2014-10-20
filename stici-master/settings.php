<?php
require_once("actions/SettingsAction.php");
$page = new SettingsAction();
$page->execute();

require_once("header.php");
require_once("settings_sidemenu.php");
?>

<?php
require_once("footer.php");