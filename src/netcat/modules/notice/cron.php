<?

$NETCAT_FOLDER = join(strstr(__FILE__, "/") ? "/" : "\\", array_slice(preg_split("/[\/\\\]+/", __FILE__), 0, -4)) . (strstr(__FILE__, "/") ? "/" : "\\");
include_once($NETCAT_FOLDER . "vars.inc.php");
require($INCLUDE_FOLDER . "index.php");
require_once($MODULE_FOLDER . "notice/notice.class.php");

$nc_core = nc_Core::get_object();
$notice = notice::get_object();

// Получение всех Крон событий
$sql = "SELECT *  FROM `Notice_Cron` WHERE `Date` <= NOW()";
$nc_data = $nc_core->db->get_results($sql, ARRAY_A);
if(!empty($nc_data)){
  foreach($nc_data as $cron){
    $arguments = unserialize($cron['Arguments']);

    $notice->load_sql($arguments);
    $notice->load_fields();

    $rule = new notice_rule();
    $rule->load($cron['Notice_Rule_ID']);
    $rule_id = $rule->get_id();
    
    if($rule_id){
      $rule->send($arguments, $notice);
      $nc_core->db->query("DELETE FROM `Notice_Cron` WHERE `Notice_Cron_ID` = " . $cron['Notice_Cron_ID']);
    }
  }
}