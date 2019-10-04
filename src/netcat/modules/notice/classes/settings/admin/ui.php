<?php

class notice_settings_admin_ui extends notice_admin_ui{

  /**
   * @param int $catalogue_id
   * @param string $active_tab
   */
  public function __construct($catalogue_id, $active_tab){
    parent::__construct('settings', OCTOCORP_MODULE_NOTICE_SETTINGS);

    $this->locationHash = "module.notice.settings";

    $this->catalogue_id = $catalogue_id;
    $this->activeTab = $active_tab;
  }

}