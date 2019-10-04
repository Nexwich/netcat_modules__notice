<?php

/**
 * Class notice_rule
 */
class notice_rule extends nc_record{
  /**
   * Ключ
   * @var string
   */
  protected $primary_key = "id";

  /**
   * Свойства
   * @var array
   */
  protected $properties = array(
    "id" => null,
    "name" => null,
    "checked" => 1,
    "event" => null,
    "cron" => 0,
    "note" => 0
  );

  /**
   * Имя таблицы в бд
   * @var string
   */
  protected $table_name = "Notice_Rule";

  /**
   * php => MySQL
   * @var array
   */
  protected $mapping = array(
    "id" => "Notice_Rule_ID",
    "name" => "Notice_Rule_Name",
    "checked" => 'Checked',
    "event" => 'Event',
    "cron" => 'Cron',
    "note" => 'Note',
  );


  /**
   * Отправка уведомления
   * @param $arguments
   * @param notice $notice
   * @throws Exception
   * @throws nc_Exception_Class_Doesnt_Exist
   * @throws nc_record_exception
   * @internal param octopost_notice $notice
   */
  public function send(array $arguments, notice $notice){
    global $current_catalogue, $current_sub, $current_cc, $current_user;
    $nc_core = nc_Core::get_object();
    
    if(empty($notice)){
      $notice = notice::get_object();
      $notice->load_sql($arguments);
      $notice->load_fields();
    }

    $rule_id = $this->get_id();

    if($arguments['catalogue_id']) $catalogue = $nc_core->catalogue->get_by_id($arguments['catalogue_id']);
    if($arguments['subdivision_id']) $subdivision = $nc_core->subdivision->get_by_id($arguments['subdivision_id']);
    if($arguments['sub_class_id']) $sub_class = $nc_core->sub_class->get_by_id($arguments['sub_class_id']);
    if($arguments['message_id'] and $arguments['class_id']) $item = $nc_core->message->get_by_id($arguments['class_id'], $arguments['message_id']);
    if($arguments['class_id']) $component = $nc_core->component->get_by_id($arguments['class_id']);
    if($arguments['class_template_id']) $class_template = $nc_core->component->get_by_id($arguments['class_template_id']);
    if($arguments['template_id']) $template = $nc_core->template->get_by_id($arguments['template_id']);
    if($arguments['system_table_id']) $system_table = $nc_core->get_system_table_name_by_id($arguments['system_table_id']);
    if($arguments['user_id']) $user = $nc_core->user->get_by_id($arguments['user_id']);
    if($arguments['module_keyword']) $module = $nc_core->modules->get_by_keyword($arguments['module_keyword']);

    $sql = "SELECT * FROM `%t%` WHERE `Notice_Rule_ID` = " . $rule_id . " AND `Checked` = 1";
    $messages = nc_record_collection::load('notice_message', $sql);

    // Проверка условий перед отправкой
    $posting = 0;
    eval(' ?>' . nc_check_eval(file_get_contents($nc_core->MODULE_TEMPLATE_FOLDER . "notice/" . $rule_id . "/SendCond.html")) . '<? ');

    if(!empty($ignore_attach)) $arguments['ignore_attach'] = $ignore_attach;
    
    if(isset($messages) and $posting == 1){
      foreach($messages as $message){
        $message->send($arguments, $notice);
      }
      
      // Выполнение действий после отправки
      eval(' ?>' . nc_check_eval(file_get_contents($nc_core->MODULE_TEMPLATE_FOLDER . "notice/" . $rule_id . "/SendActionTemplate.html")) . '<? ');

      // Не отсылать повторно
      $notice->send = false;

      // Очистить свойство
      unset($notice->class_id);
    }
  }
}
