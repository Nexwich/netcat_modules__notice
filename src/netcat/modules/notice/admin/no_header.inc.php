<?php
$NETCAT_FOLDER = join(strstr(__FILE__, "/") ? "/" : "\\", array_slice(preg_split("/[\/\\\]+/", __FILE__), 0, -5)) . (strstr(__FILE__, "/") ? "/" : "\\");

require_once $NETCAT_FOLDER . "vars.inc.php";
require $INCLUDE_FOLDER . "index.php";
require_once $ADMIN_FOLDER . "function.inc.php";
require_once $ADMIN_FOLDER . "modules/ui.php";
require_once $MODULE_FOLDER . "/notice/function.inc.php";

/** @var Permission $perm */
$perm->ExitIfNotAccess(NC_PERM_MODULE, 0, 0, 0, 1);
