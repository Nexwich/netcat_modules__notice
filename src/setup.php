<?
$NETCAT_FOLDER = join(strstr(__FILE__, "/") ? "/" : "\\", array_slice(preg_split("/[\/\\\]+/", __FILE__), 0, -4)).( strstr(__FILE__, "/") ? "/" : "\\" );
include_once ($NETCAT_FOLDER."vars.inc.php");
require_once ($ADMIN_FOLDER."function.inc.php");
require_once ($ADMIN_FOLDER."modules/ui.php");
BeginHtml();
$nc_core = nc_core();
if ($nc_core->db->query("UPDATE `Module` SET `Inside_Admin` = 1, `Installed` = 1  WHERE `Keyword` = 'notice'")){
  nc_print_status("Модуль успешно установлен. Перезагрузите страницу", "ok");
}

EndHtml();