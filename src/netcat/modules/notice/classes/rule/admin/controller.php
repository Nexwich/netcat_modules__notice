<?php


class notice_rule_admin_controller extends notice_admin_controller{

  protected $ui_config;

  protected $ui_config_class = 'notice_rule_admin_ui';

  protected function init(){
    parent::init();
    $this->bind('settings_save', array('notice', 'next_action'));
  }

  protected function action_index(){
    $this->ui_config->actionButtons[] = array(
      "id" => "add",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_ADD,
      "location" => "#module.notice.rule.add",
    );

    $query = "SELECT * FROM `%t%` ORDER BY `Notice_Rule_Name` DESC, `Event` DESC";
    try{
      $rules = nc_record_collection::load('notice_rule', $query);
    }catch(nc_record_exception $e){
      $rules = array();
    }

    return $this->view('index', array(
      'rules' => $rules
    ));
  }

  protected function action_add(){
    // Кнопки
    $this->ui_config->actionButtons[] = array(
      "id" => "submit",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_SAVE,
      "action" => "mainView.submitIframeForm('add')"
    );

    return $this->view('add', array(
      'SendCond' => "<? \$posting = 1; ?>"
    ));

  }

  protected function action_update(){
    $nc_core = nc_core::get_object();

    $fetch_get = $nc_core->input->fetch_get();
    $id = $fetch_get['id'];

    $rule = new notice_rule();
    $rule->load($id);
    $rule_id = $rule->get_id();

    // Кнопки
    $this->ui_config->actionButtons[] = array(
      "id" => "submit",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_SAVE,
      "action" => "mainView.submitIframeForm('add')"
    );

    $path = $nc_core->MODULE_TEMPLATE_FOLDER . 'notice/' . $rule_id . "/";
    return $this->view('update', array(
      'rule' => $rule,
      'SendCond' => file_get_contents($path . "SendCond.html"),
      'SendAction' => file_get_contents($path . "SendActionTemplate.html"),
    ));
  }

  protected function action_full(){
    $nc_core = nc_core::get_object();

    $fetch_get = $nc_core->input->fetch_get();
    $id = $fetch_get['id'];

    $rule = new notice_rule();
    $rule->load($id);
    $rule_id = $rule->get_id();

    // Кнопки
    $this->ui_config->actionButtons[] = array(
      "id" => "add",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_ADD,
      "location" => "#module.notice.message.add(" . $rule_id . ")"
    );
    
    $query = "SELECT * FROM `%t%` WHERE `Notice_Rule_ID` = " . $rule_id;
    try{
      $messages = nc_record_collection::load('notice_message', $query);
    }catch(nc_record_exception $e){
      $messages = array();
    }
    
    return $this->view('full', array(
      'rule' => $rule,
      'messages' => $messages
    ));
  }

  protected function action_save(){
    $nc_core = nc_core::get_object();

    $data = $nc_core->input->fetch_post();
    $rule_id = $data['f_Notice_Rule_ID'];

    $rule = new notice_rule();
    if(!empty($rule_id)) $rule->load($rule_id);
    $rule->set_values(array(
      "name" => $data['f_Notice_Rule_Name'],
      "event" => $data['f_Event'],
      "note" => $data['f_Note'],
      "cron" => $data['f_Cron']
    ));
    if($data['f_Checked']) $rule->set('checked', $data['f_Checked']);

    // Сохранить правило
    $rule->save();
    if(empty($rule_id)) $rule_id = $nc_core->db->insert_id;

    // Сохранить шаблоны
    $path = $nc_core->MODULE_TEMPLATE_FOLDER . "notice/";
    if(!is_dir($path)) nc_create_folder($path);
    
    $path = $nc_core->MODULE_TEMPLATE_FOLDER . "notice/" . $rule_id . "/";
    if(!is_dir($path)) nc_create_folder($path);

    $file = $path . 'SendCond.html';
    nc_save_file($file, $data['SendCond']);

    $file = $path . 'SendActionTemplate.html';
    nc_save_file($file, $data['SendAction']);

    // Редирект в правило
    $this->redirect("admin/?controller=rule&action=full&id=" . $rule_id);
    return null;
  }

  protected function action_check(){
    $id = (int) nc_core()->input->fetch_get('id');

    $rule = new notice_rule();

    $rule->load($id);
    $rule->set('checked', ($rule->get('checked') == 1 ? 0 : 1));

    $rule->save();

    $this->redirect();
    return null;
  }

  protected function action_remove(){
    $nc_core = nc_core::get_object();
    $id = (int) $nc_core->input->fetch_get('id');

    $rule = new notice_rule();
    $rule->load($id);
    $rule->delete();

    // Удалить папку
    $path = $nc_core->MODULE_TEMPLATE_FOLDER . 'notice/' . $rule->get_id() . "/";
    if(is_dir($path)) nc_delete_dir($path);

    // Удалить уведомления
    $query = "SELECT * FROM `%t%` WHERE `Notice_Rule_ID` = " . $rule->get_id();
    $notices = nc_record_collection::load('notice_message', $query);
    foreach($notices as $notice){
      $notice->delete();
    }

    // Редирект на главную
    $this->redirect();
    return null;
  }

}