<?php
require_once("actions/CommonAction.php");
$page = new CommonAction("Error(s)");
$page->execute();

require_once("header.php");
require_once("errors.php");
require_once("footer.php");