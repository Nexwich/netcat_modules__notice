<?php


class notice_select_admin_controller extends notice_admin_controller{

  protected $ui_config;

  protected function init(){
    parent::init();
    $this->bind('settings_save', array('notice', 'next_action'));
  }
  
  protected function action_list(){
    $nc_core = nc_Core::get_object();
    $data = $nc_core->input->fetch_post();
    $result = null;

    switch($data['type']){
      case 'event':
        $options = array();
        $items = array($data['value']);

        // Все системные события за исключением предсобытий
        foreach($nc_core->event->get_all_events() as $event){
          $event_name = $nc_core->event->event_name($event);
          if(!strripos($event, 'Prep')) $options[] = array("id" => $event, "name" => $event_name);
        }

        if($options) $result['options'] = $options;
        if($items) $result['items'] = $items;
        break;

      case 'class':
        $result = $this->get_list_class();
        break;
      case 'sub_class':
        $result = $this->get_list_sub_class();
        break;
      case 'user':
        $options = $this->get_list_user();
        if(is_array($data['value'])) $items = $data['value'];
        else $items = array($data['value']);
        
        if($options) $result['options'] = $options;
        if($items) $result['items'] = $items;
        break;
      case 'user_fields':
        $options = array();
        if(is_array($data['value'])) $items = $data['value'];
        else $items = array($data['value']);

        $fields = $nc_core->db->get_results("SELECT `Field_ID`, `Description` FROM `Field` WHERE `System_Table_ID` = 3 ORDER BY `Priority`", ARRAY_A);
        if($fields){
          foreach($fields as $field){
            $options[] = array("id" => $field['Field_ID'], "name" => $field['Description']);
          }
        }

        if($options) $result['options'] = $options;
        if($items) $result['items'] = $items;
        break;
    }

    return $this->view('index', array(
      "result" => $result
    ));
  }


  protected function action_json(){
    $nc_core = nc_Core::get_object();

    $result = array();

    $result['system'] = array(
      "{DEFAULT}" => "Сообщение по умолчанию",
      "{scheme}" => "Протокол http/https"
    );
    $result['catalogue'] = array(
      "{catalogue.Catalogue_ID}" => "ID",
      "{catalogue.Catalogue_Name}" => "Название",
      "{catalogue.Domain}" => "Домен"
    );
    $result['subdivision'] = array(
      "{subdivision.Path}" => "Ссылка",
      "{subdivision.Subdivision_ID}" => "ID",
      "{subdivision.Catalogue_ID}" => "ID Сайта",
      "{subdivision.Parent_Sub_ID}" => "ID родительского раздела",
      "{subdivision.Subdivision_Name}" => "Название",
      "{subdivision.Template_ID}" => "ID макета",
      "{subdivision.ExternalURL}" => "Внешняя ссылка",
      "{subdivision.EnglishName}" => "Ключевое слово",
      "{subdivision.Hidden_URL}" => "Ссылка от корня",
      "{subdivision.Description}" => "Мета. Описание",
      "{subdivision.Keywords}" => "Мета. Ключевые слова",
      "{subdivision.Title}" => "Мета. Заголовок"
    );
    $result['sub_class'] = array(
      "{sub_class.Path}" => "Ссылка на инфоблок",
      "{sub_class.Path.Subscribe}" => "Ссылка на пподписку на инфоблок",
      "{sub_class.Sub_Class_ID}" => "ID",
      "{sub_class.Subdivision_ID}" => "ID раздела",
      "{sub_class.Class_ID}" => "ID компонента",
      "{sub_class.Sub_Class_Name}" => "Название",
      "{sub_class.EnglishName}" => "Ключевое слово"
    );
    $result['message'] = array(
      "{message.Path}" => "Ссылка на объект на сайте",
      "{message.Path.Edit}" => "Ссылка на редактирование объекта на сайте",
      "{message.Path.Subscribe}" => "Ссылка на подписку на объект",
      "{message.Message_ID}" => "ID",
      "{message.User_ID}" => "ID пользователя",
      "{message.Subdivision_ID}" => "ID раздела",
      "{message.Sub_Class_ID}" => "ID инфоблока",
      "{message.Priority}" => "Приоритет",
      "{message.Keyword}" => "Ключевое слово",
      "{message.ncTitle}" => "Мета. Заголовок",
      "{message.ncKeywords}" => "Мета. Ключевые слова",
      "{message.ncDescription}" => "Мета. Описание",
      "{message.IP}" => "IP пользователя",
      "{message.Created}" => "Дата создания"
    );
    $result['user'] = array(
      "{user.Path}" => "Ссылка на объект на сайте",
      "{user.User_ID}" => "ID",
      "{user.Created}" => "Дата регистрации",
      "{user.Keyword}" => "Ключевое слово"
    );

    $sql = "SELECT `Field_Name`, `Description` FROM `Field` WHERE `System_Table_ID` = 1 ORDER BY `Priority`";
    $catalogue = $nc_core->db->get_results($sql);
    foreach($catalogue as $Filed){
      $result['catalogue']["{catalogue." . $Filed->Field_Name . "}"] = $Filed->Description;
    }
    $sql = "SELECT `Field_Name`, `Description` FROM `Field` WHERE `System_Table_ID` = 2 ORDER BY `Priority`";
    $subdivision = $nc_core->db->get_results($sql);
    foreach($subdivision as $Filed){
      $result['subdivision']["{subdivision." . $Filed->Field_Name . "}"] = $Filed->Description;
    }
    $sql = "SELECT `Field_Name`, `Description` FROM `Field` WHERE `System_Table_ID` = 3 ORDER BY `Priority`";
    $user = $nc_core->db->get_results($sql);
    foreach($user as $Filed){
      $result['user']["{user." . $Filed->Field_Name . "}"] = $Filed->Description;
    }

    return $this->view('index', array(
      "result" => $result
    ));
  }

  protected function after_action(){
    return true;
  }

}
