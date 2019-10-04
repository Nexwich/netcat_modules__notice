<?

$NETCAT_FOLDER = realpath(dirname(__FILE__) . "/../../../../") . "/";
require_once $NETCAT_FOLDER . "vars.inc.php";
require_once $ADMIN_FOLDER . "function.inc.php";

/** @var Permission $perm */
$perm->ExitIfNotAccess(NC_PERM_MODULE, 0, 0, 0, 1);

BeginHtml(OCTOCORP_MODULE_NOTICE, '', '');