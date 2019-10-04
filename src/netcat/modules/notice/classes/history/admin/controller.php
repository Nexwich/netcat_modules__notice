<?php


class notice_history_admin_controller extends notice_admin_controller{

  /** @var  */
  protected $ui_config;

  protected $ui_config_class = 'notice_history_admin_ui';

  protected function init(){
    parent::init();
    $this->bind('settings_save', array('notice', 'next_action'));
  }

  /**
   * @param $data
   * @return nc_ui_view
   */
  protected function action_save($data){
    $nc_core = nc_core::get_object();

    if(!empty($data)) $data = $nc_core->input->fetch_post();
    $id = $data["f_Notice_History_ID"];

    $mail = new notice_history();

    if(!empty($id)) $mail->load($id);
    $mail->set_values(array(
      "message_id" => $data['f_Notice_History_ID'],
      "essence" => $data['f_Essence'],
      "essence_id" => $data['f_Essence_ID'],
      "email_to" => $data['f_Email_To'],
      "email_form" => $data['f_Email_From'],
      "email_reply" => $data['f_Email_Reply'],
      "name_from" => $data['f_Name'],
      "subject" => $data['f_Subject'],
      "message" => $data['f_Message']
    ));

    $mail->save();

    // Редирект в правило
    $this->redirect("admin/?controller=rule&action=full&id=" . $id);
  }
  
}