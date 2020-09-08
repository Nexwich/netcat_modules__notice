<?

$NETCAT_FOLDER = realpath(dirname(__FILE__) . "/../../../../") . "/";
include_once $NETCAT_FOLDER . "vars.inc.php";
require_once __DIR__ . "/function.inc.php";

/** @var Permission $perm */
$perm->ExitIfNotAccess(NC_PERM_MODULE, 0, 0, 0, 1);

BeginHtml(OCTOCORP_MODULE_NOTICE, '', '');
