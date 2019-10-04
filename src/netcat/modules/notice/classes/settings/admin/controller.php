<?php


class notice_settings_admin_controller extends notice_admin_controller{

  protected $ui_config;

  protected $ui_config_class = 'notice_settings_admin_ui';

  protected function init(){
    parent::init();
    $this->bind('settings_save', array('notice', 'next_action'));
  }

  /**
   * @return nc_ui_view
   */
  protected function action_index(){
    $user_name = nc_core()->get_settings('User_Name', 'notice');
    if(!empty($user_name)) $user_name = json_encode(explode(",", $user_name));
    
    $this->ui_config->actionButtons[] = array(
      "id" => "submit",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_SAVE,
      "action" => "mainView.submitIframeForm('add')"
    );

    return $this->view('index', array(
      'user_name' => $user_name
    ));
  }

  protected function action_save(){
    $nc_core = nc_Core::get_object();

    $nc_core->set_settings('Email', $_POST['f_Email'], 'notice');
    $nc_core->set_settings('Name', $_POST['f_Name'], 'notice');
    $nc_core->set_settings('Subject', $_POST['f_Subject'], 'notice');
    $nc_core->set_settings('Date', $_POST['f_Date'], 'notice');
    $nc_core->set_settings('User_Name', join(",", $_POST['f_User_Name']), 'notice');

    $this->redirect("admin/");
  }

}