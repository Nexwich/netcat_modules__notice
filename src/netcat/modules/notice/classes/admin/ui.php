<?php

class notice_admin_ui extends ui_config{

  protected $catalogue_id;

  /**
   * @param $tree_node
   * @param $sub_header_text
   */
  public function __construct($tree_node, $sub_header_text){
    $this->headerText = OCTOCORP_MODULE_NOTICE;
    $this->subheaderText = $sub_header_text;

    $this->locationHash = "module.notice.$tree_node";

    $this->treeMode = "modules";
    $this->treeSelectedNode = "notice-$tree_node";
  }

  /**
   * @param $catalogue_id
   */
  public function set_catalogue_id($catalogue_id){
    $this->catalogue_id = $catalogue_id;
  }

  /**
   * @param string $caption
   */
  public function add_submit_button($caption = OCTOCORP_MODULE_NOTICE_BUTTON_SAVE){
    $this->actionButtons[] = array(
      "id" => "submit_form",
      "caption" => $caption,
      "action" => "mainView.submitIframeForm()"
    );
  }

  public function add_create_button($location){
    $this->actionButtons[] = array(
      "id" => "add",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_ADD,
      "location" => "#module.notice.$location",
      "align" => "left");
  }

  /**
   * Для форм редактирования
   * @param $save_button_caption
   */
  public function add_save_and_cancel_buttons($save_button_caption = OCTOCORP_MODULE_NOTICE_BUTTON_SAVE){
    $this->actionButtons[] = array(
      "id" => "history_back",
      "caption" => OCTOCORP_MODULE_NOTICE_BUTTON_BACK,
      "action" => "history.back(1)",
      "align" => "left"
    );
    $this->add_submit_button($save_button_caption);
  }

  public function set_location_hash($hash){
    $this->locationHash = "module.notice.$hash";
  }

}