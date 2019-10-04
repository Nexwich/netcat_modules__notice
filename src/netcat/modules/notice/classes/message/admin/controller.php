<?php


class notice_message_admin_controller extends notice_admin_controller{

  /** @var */
  protected $ui_config;

  protected $ui_config_class = 'notice_message_admin_ui';

  protected function init(){
    parent::init();
    $this->bind('settings_save', array('notice', 'next_action'));
  }

  /**
   * @return nc_ui_view
   */
  protected function action_add(){
    $nc_core = nc_Core::get_object();

    $rule_id = $nc_core->input->fetch_get('rule_id');

    $rule = new notice_rule();
    $rule->load($rule_id);
    $rule_id = $rule->get_id();

    $this->ui_config->actionButtons[] = array(
      "id" => "back",
      "caption" => OCTOCORP_MODULE_NOTICE_RULE_UPDATE,
      "location" => "#module.notice.rule.update(" . $rule_id . ")",
      "align" => "left"
    );
    $this->ui_config->actionButtons[] = array(
      "id" => "submit",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_SAVE,
      "action" => "mainView.submitIframeForm('add')"
    );

    return $this->view('add', array(
      'rule' => $rule
    ));
  }

  protected function action_update(){
    $nc_core = nc_core::get_object();

    $fetch_get = $nc_core->input->fetch_get();
    $id = $fetch_get['id'];

    $message = new notice_message();
    $message->load($id);

    $rule = new notice_rule();
    $rule->load($message['rule_id']);
    $rule_id = $rule->get_id();

    if(!empty($message['email_to'])) $message['email_to__json'] = json_encode(explode(",", $message['email_to']));

    $this->ui_config->actionButtons[] = array(
      "id" => "back",
      "caption" => OCTOCORP_MODULE_NOTICE_RULE_UPDATE,
      "location" => "#module.notice.rule.update(" . $rule_id . ")",
      "align" => "left"
    );
    $this->ui_config->actionButtons[] = array(
      "id" => "submit",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_SAVE,
      "action" => "mainView.submitIframeForm('add')"
    );

    return $this->view('add', array(
      'rule' => $rule,
      "message" => $message,
    ));
  }

  protected function action_save(){
    $nc_core = nc_core::get_object();

    $data = $nc_core->input->fetch_post();
    $rule_id = $data["f_Notice_Rule_ID"];
    $id = $data["f_Notice_Message_ID"];

    $message = new notice_message();

    if(!empty($id)) $message->load($id);
    $message->set_values(array(
      "rule_id" => $data['f_Notice_Rule_ID'],
      "name" => $data['f_Notice_Message_Name'],
      "email_to" => join(",", $data['f_Email_To']),
      "email_from" => $data['f_Email_From'],
      "email_reply" => $data['f_Email_Reply'],
      "name_from" => $data['f_Name'],
      "subject" => $data['f_Subject'],
      "message" => $data['f_Message']
    ));
    if($data['f_Checked']) $message->set('checked', $data['f_Checked']);

    $message->save();

    // Редирект в правило
    $this->redirect("admin/?controller=rule&action=full&id=" . $rule_id);
  }

  protected function action_check(){
    $id = (int) nc_core()->input->fetch_get('id');

    $message = new notice_message();

    $message->load($id);
    $message->set('checked', ($message->get('checked') == 1 ? 0 : 1));

    $message->save();

    $this->redirect("admin/?controller=rule&action=full&id=" . $message->get('rule_id'));
    return null;
  }

  protected function action_remove(){
    $id = (int) nc_core()->input->fetch_get('id');

    $notice = new notice_message();

    $notice->load($id);
    $notice->delete();

    $this->redirect("admin/?controller=rule&action=full&id=" . $notice['rule_id']);
    return null;
  }
}