<?php

class notice_rule_admin_ui extends notice_admin_ui{

  /**
   * @param int $catalogue_id
   * @param string $active_tab
   */
  public function __construct($catalogue_id, $active_tab){
    parent::__construct('rule', constant('OCTOCORP_MODULE_NOTICE_RULE'.(defined('OCTOCORP_MODULE_NOTICE_RULE_'.mb_strtoupper($active_tab)) ? '_'.mb_strtoupper($active_tab) : null)));
  }

}