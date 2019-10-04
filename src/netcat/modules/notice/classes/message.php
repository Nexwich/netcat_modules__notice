<?php

/**
 * Class notice_message
 */
class notice_message extends nc_record{
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
    "rule_id" => null,
    "checked" => 1,
    "email_to" => null,
    "email_from" => null,
    "email_reply" => null,
    "name_from" => null,
    "subject" => null,
    "message" => null,
  );

  /**
   * Имя таблицы в бд
   * @var string
   */
  protected $table_name = "Notice_Message";

  /**
   * php => MySQL
   * @var array
   */
  protected $mapping = array(
    "id" => "Notice_Message_ID",
    "name" => "Notice_Message_Name",
    "rule_id" => 'Notice_Rule_ID',
    "checked" => 'Checked',
    "email_to" => 'Email_To',
    "email_from" => 'Email_From',
    "email_reply" => 'Email_Reply',
    "name_from" => 'Name',
    "subject" => 'Subject',
    "message" => 'Message'
  );

  /**
   * Проверка входныйх данных
   *
   * @return bool
   */
  public function validate(){

  }

  /**
   * Отправка уведомления
   * @param $arguments
   * @param notice $notice
   * @internal param notice_message $message
   */
  public function send($arguments = array(), notice $notice){
    global $current_catalogue, $current_sub, $current_cc, $current_user;
    $nc_core = nc_Core::get_object();
    $system_env = $nc_core->get_settings();

    if(empty($notice)){
      $notice = notice::get_object();
      $notice->load_sql($arguments);
      $notice->load_fields();
    }

    // Цикл обработки полей сущности
    foreach($notice->fields['query'] as $key => $value){
      ${'' . $key} = $value;
    }

    // Составление шаблон по умолчанию
    switch($notice->essence){
      case 'catalogue':
        $sql = "SELECT `Field_Name`, `Description` FROM `Field` WHERE `System_Table_ID` = 1 AND `TypeOfData_ID` NOT IN(6,11) ORDER BY `Priority`";
        break;
      case 'subdivision':
        $sql = "SELECT `Field_Name`, `Description` FROM `Field` WHERE `System_Table_ID` = 2 AND `TypeOfData_ID` NOT IN(6,11) ORDER BY `Priority`";
        break;
      case 'message':
        $sql = "SELECT `Field_Name`, `Description` FROM `Field` WHERE `Class_ID` = " . $arguments['class_id'] . " AND `TypeOfData_ID` NOT IN(6,11) ORDER BY `Priority`";
        break;
      case 'user':
        $sql = "SELECT `Field_Name`, `Description` FROM `Field` WHERE `System_Table_ID` = 3 AND `TypeOfData_ID` NOT IN(6,11) ORDER BY `Priority`";
        break;
    }

    if(!empty($sql)){
      $fields = $nc_core->db->get_results($sql, ARRAY_A);

      $mail_template_default = "<p>";
      foreach($fields as $field){
        $mail_template_default .= "<strong>" . $field['Description'] . ":</strong> {" . $notice->essence . "." . $field['Field_Name'] . "}<br>";
      }
      $mail_template_default .= "</p>";
    }

    // Если тело письма пустое то использовать шаблон по умолчанию
    if(empty($this['message'])){
      $this->set_values(array('message' => '{DEFAULT}'));
    }

    $notice->set_property("{scheme}", nc_get_scheme());

    // Сменить значение полей согласно полученным свойствам если те были переданы
    if($notice->replace['email_to']) $this->set("email_to", $notice->replace['email_to']);
    if($notice->replace['email_from']) $this->set("email_from", $notice->replace['email_from']);
    if($notice->replace['email_reply']) $this->set("email_reply", $notice->replace['email_reply']);
    if($notice->replace['name_from']) $this->set("name_from", $notice->replace['name_from']);
    if($notice->replace['subject']) $this->set("subject", $notice->replace['subject']);
    if($notice->replace['message']) $this->set("message", $notice->replace['message']);

    // Обработать шаблон по умолчанию
    if(!empty($mail_template_default)) $this->set_values(array('message' => str_replace('{DEFAULT}', $mail_template_default, $this['message'])));

    $emails = array(
      "email_to" => $this['email_to'],
      "email_from" => $this['email_from'],
      "email_reply" => $this['email_reply']
    );
    foreach($emails as $key => $email){
      if(!empty($email)){
        $email = explode(",", $email);
        $array = array();
        foreach($email as $e){
          $temp_user = null;
          if(is_numeric($e)){
            $temp_user = $nc_core->user->get_by_id($e);
            $e = $temp_user[($nc_core->get_settings('Email_User', 'notice') ? $nc_core->get_settings('Email_User', 'notice') : 'Email')];
          }
          $array[] = $e;
        }
        $this->set($key, join(",", $array));
      }
    }

    // Подставноква значений по умолчанию если те пустые
    $notice_settings['email_to'] = (!empty($this['email_to']) ? $this['email_to'] : ($nc_core->get_settings('Email', 'notice') ? $nc_core->get_settings('Email', 'notice') : $system_env['SpamFromEmail']));
    $notice_settings['email_from'] = (!empty($this['email_from']) ? $this['email_from'] : ($nc_core->get_settings('Email', 'notice') ? $nc_core->get_settings('Email', 'notice') : $system_env['SpamFromEmail']));
    $notice_settings['email_reply'] = (!empty($this['email_reply']) ? $this['email_reply'] : ($nc_core->get_settings('Email', 'notice') ? $nc_core->get_settings('Email', 'notice') : $system_env['SpamFromEmail']));
    $notice_settings['name_from'] = (!empty($this['name_from']) ? $this['name_from'] : ($nc_core->get_settings('Name', 'notice') ? $nc_core->get_settings('Name', 'notice') : $system_env['SpamFromName']));
    $notice_settings['subject'] = (!empty($this['subject']) ? $this['subject'] : ($nc_core->get_settings('Subject', 'notice') ? $nc_core->get_settings('Subject', 'notice') : $system_env['ProjectName']));
    $notice_settings['message'] = $this['message'];

    // Подмена полей в сообщении
    foreach($notice_settings as $key => $value){
      $search = $notice->get_properties(true);
      $replace = $notice->get_properties();
      $this->set($key, str_replace($search, $replace, $value));
    }

    // Обработать php код
    ob_start();
    eval(' ?>' . nc_check_eval($this['message']) . '<? ');
    $this['message'] = ob_get_contents();
    ob_end_clean();

    $mail_values = array(
      "notice_id" => $this['id'],
      "email_to" => $this['email_to'],
      "email_from" => $this['email_from'],
      "email_reply" => $this['email_reply'],
      "name_from" => $this['name_from'],
      "subject" => $this['subject'],
      "message" => $this['message'],
      "attach" => ''
    );

    // Отправка
    $mailer = new CMIMEMail();
    $mailer->mailbody(strip_tags($this['message']), $this['message']);
    if(!empty($notice->attach) and !$arguments['ignore_attach']){
      foreach($notice->attach as $file){
        $mailer->attachFile($nc_core->DOCUMENT_ROOT . $file['Path'], $file['Name'], $file['Type']);
      }
    }
    $send = $mailer->send($this['email_to'], $this['email_from'], $this['email_reply'], $this['subject'], $this['name_from']);

    // Сохранить письмо
    if($send){
      $history = new notice_history();
      $history->set_values($mail_values);
      $history->save();
    }
  }
}
