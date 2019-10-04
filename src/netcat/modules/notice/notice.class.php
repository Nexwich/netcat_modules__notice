<?php

class notice{

  public $send = true;

  public $sql = array();
  public $fields = array();
  public $attach = array();
  public $date_format = "d.m.Y H:i:s";

  // Переменная подмены из вне
  public $replace = array('email_to', 'email_from', 'email_reply', 'name', 'subject', 'message');

  public $arguments;
  public $rule_id;
  public $essence;
  public $elements;

  /**
   * notice constructor.
   */
  protected function __construct(){
    $nc_core = nc_Core::get_object();

    $date_format = $nc_core->get_settings('Date', 'notice');
    if($date_format) $this->date_format = $date_format;

    $rules = nc_record_collection::load_all('notice_rule');
    if($rules){
      foreach($rules as $rule){
        $cron = '';
        if($rule['cron']) $cron = 'cron';
        $bind_from = $rule['event'];
        $bind_to = array(
          $rule['event'],
          $rule['id'],
          $cron,
        );
        $bind_to = join("_", $bind_to);
        $nc_core->event->bind($this, array($bind_from => $bind_to));
      }
    }
  }

  /**
   * @param $method
   * @param null $val
   */
  public function __call($method, $val = null){
    $nc_core = nc_Core::get_object();

    list($event, $rule_id, $cron) = explode("_", $method);

    $this->rule_id = $rule_id;


    $rule = new notice_rule();
    $rule->load($this->rule_id);
    $rule_id = $rule->get_id();


    if ($rule->get('checked')){
      if(is_array($val[0]) and !empty($val[0])){
        $arguments = $val[0];

        $this->arguments['catalogue_id'] = $arguments['catalogue_id'];
        $this->arguments['subdivision_id'] = $arguments['subdivision_id'];
        $this->arguments['sub_class_id'] = $arguments['sub_class_id'];
        $this->arguments['message_id'] = $arguments['message_id'];
        $this->arguments['class_id'] = $arguments['class_id'];
        $this->arguments['class_template_id'] = $arguments['class_template_id'];
        $this->arguments['template_id'] = $arguments['template_id'];
        $this->arguments['system_table_id'] = $arguments['system_table_id'];
        $this->arguments['user_id'] = $arguments['user_id'];
        $this->arguments['comment_id'] = $arguments['comment_id'];
        $this->arguments['module_id'] = $arguments['module_id'];
        $this->elements = $val[1];
      }else{
        switch($event){
          case 'addCatalogue':
          case 'updateCatalogue':
          case 'dropCatalogue':
          case 'checkCatalogue':
          case 'uncheckCatalogue':
            $this->essence = 'catalogue';
            $this->arguments['catalogue_id'] = ($val[0] ? $val[0] : 0);
            break;
          case 'addSubdivision':
          case 'updateSubdivision':
          case 'dropSubdivision':
          case 'checkSubdivision':
          case 'uncheckSubdivision':
            $this->essence = 'subdivision';
            $this->arguments['catalogue_id'] = ($val[0] ? $val[0] : 0);
            $this->arguments['subdivision_id'] = ($val[1] ? $val[1] : 0);
            break;
          case 'addSubClass':
          case 'updateSubClass':
          case 'dropSubClass':
          case 'checkSubClass':
          case 'uncheckSubClass':
            $this->essence = 'sub_class';
            $this->arguments['catalogue_id'] = ($val[0] ? $val[0] : 0);
            $this->arguments['subdivision_id'] = ($val[1] ? $val[1] : 0);
            $this->arguments['sub_class_id'] = ($val[2] ? $val[2] : 0);
            break;
          case 'addMessage':
          case 'updateMessage':
          case 'dropMessage':
          case 'checkMessage':
          case 'uncheckMessage':
            $this->essence = 'message';
            $this->arguments['catalogue_id'] = ($val[0] ? $val[0] : 0);
            $this->arguments['subdivision_id'] = ($val[1] ? $val[1] : 0);
            $this->arguments['sub_class_id'] = ($val[2] ? $val[2] : 0);
            $this->arguments['class_id'] = ($val[3] ? $val[3] : 0);
            $this->arguments['message_id'] = ($val[4] ? $val[4] : 0);
            break;
          case 'addClass':
          case 'updateClass':
          case 'dropClass':
            $this->essence = 'class';
            $this->arguments['class_id'] = ($val[0] ? $val[0] : 0);
            break;
          case 'addClassTemplate':
          case 'updateClassTemplate':
          case 'dropClassTemplate':
            $this->essence = 'class';
            $this->arguments['class_id'] = ($val[0] ? $val[0] : 0);
            $this->arguments['class_template_id'] = ($val[1] ? $val[1] : 0);
            break;
          case 'addTemplate':
          case 'updateTemplate':
          case 'dropTemplate':
            $this->essence = 'template';
            $this->arguments['template_id'] = ($val[0] ? $val[0] : 0);
            break;
          case 'updateSystemTable':
            $this->arguments['system_table_id'] = ($val[0] ? $val[0] : 0);
            break;
          case 'addUser':
          case 'updateUser':
          case 'dropUser':
          case 'checkUser':
          case 'uncheckUser':
          case 'authorizeUser':
            $this->essence = 'user';
            $this->arguments['user_id'] = ($val[0] ? $val[0] : 0);
            break;
          case 'addComment':
          case 'updateComment':
          case 'dropComment':
          case 'checkComment':
          case 'uncheckComment':
            $this->essence = 'comment';
            $this->arguments['catalogue_id'] = ($val[0] ? $val[0] : 0);
            $this->arguments['subdivision_id'] = ($val[1] ? $val[1] : 0);
            $this->arguments['sub_class_id'] = ($val[2] ? $val[2] : 0);
            $this->arguments['class_id'] = ($val[3] ? $val[3] : 0);
            $this->arguments['message_id'] = ($val[4] ? $val[4] : 0);
            $this->arguments['comment_id'] = ($val[5] ? $val[5] : 0);
            break;
          case 'checkModule':
          case 'uncheckModule':
            $this->essence = 'module';
            $this->arguments['module_keyword'] = ($val[0] ? $val[0] : 0);
            $this->arguments['catalogue_id'] = ($val[1] ? $val[1] : 0);
            break;

          default:
            break;
        }
      }


      if(!empty($rule)){
        if($cron == 'cron'){
          $sql = "INSERT INTO `Notice_Cron` SET
        `Notice_Rule_ID` = '" . $rule_id . "',
        `Argumments` = '" . serialize($this->arguments) . "',
        `Date` = '" . date("Y-m-d H:i:s", time() + 30) . "'";
          $nc_core->db->query($sql);
        }else{
          $this->load_sql($this->arguments);
          $this->load_fields();
          $rule->send($this->arguments, $this);
        }
      }
    }
  }

  /**
   * Получить экземпляр объекта
   * @return notice object
   */
  public static function get_object(){
    static $storage;
    // check cache
    if(!isset($storage)){
      // init object
      $storage = new self();
    }
    // return object
    return is_object($storage) ? $storage : false;
  }

  /**
   * Получить значения псевдопеременной
   * @param $property
   * @return mixed
   */
  public function get_property($property){
    return $this->fields['result']['value'][$property];
  }

  /**
   * Получить значения псевдопеременной
   * @param null $values
   * @return array
   */
  public function get_properties($values = null){
    $result = array();

    if(is_array($values)){
      foreach($values as $property){
        $result[$property] = $this->get_property($property);
      }
      $result = array_filter($result);
    }elseif($values === true){
      $result = $this->fields['result']['column'];
    }else{
      $result = $this->fields['result']['value'];
    }

    return $result;
  }

  /**
   * Задать значения псевдопеременной
   * @param string $property
   * @param mixed $value
   * @return notice
   */
  public function set_property($property, $value){
    $this->fields['result']['value'][$property] = $value;
    $this->fields['result']['column'][$property] = $property;
    return $this;
  }

  /**
   * Задать значения псевдопеременных
   * @param array $values
   * @return static
   */
  public function set_properties(array $values){
    foreach($values as $k => $v){
      $this->set_property($k, $v);
    }
    return $this;
  }

  /**
   * Загрузить запросы на получение списка макропеременных
   * @param array - id запаршиваемых сущнностей
   */
  public function load_sql(array $arguments){
    if($arguments['catalogue_id']) $this->sql["catalogue"] = "SELECT * FROM `Catalogue` WHERE `Catalogue_ID` = " . $arguments['catalogue_id'];
    if($arguments['subdivision_id']) $this->sql["subdivision"] = "SELECT * FROM `Subdivision` WHERE `Subdivision_ID` = " . $arguments['subdivision_id'];
    if($arguments['sub_class_id']) $this->sql["sub_class"] = "SELECT * FROM `Sub_Class` WHERE `Sub_Class_ID` = " . $arguments['sub_class_id'];
    if($arguments['class_id'] and $arguments['message_id']){
      $this->sql["message"] = "SELECT * FROM `Message" . $arguments['class_id'] . "` WHERE `Message_ID` = " . $arguments['message_id'];
    };
    if($arguments['class_id']) $this->sql["class"] = "SELECT * FROM `Class` WHERE `Class_ID` = " . $arguments['class_id'];
    if($arguments['class_template_id']) $this->sql["class_template"] = "SELECT * FROM `Class` WHERE `Class_ID` = " . $arguments['class_template_id'];
    if($arguments['template_id']) $this->sql["template"] = "SELECT * FROM `Template` WHERE `Template_ID` = " . $arguments['template_id'];
    if($arguments['user_id']) $this->sql["user"] = "SELECT * FROM `User` WHERE `User_ID`" . (is_array($arguments['user_id']) ? " IN (" . implode(",", $arguments['user_id']) . ")" : " = " . $arguments['user_id']);
    if($arguments['module_id']) $this->sql["module"] = "SELECT * FROM `Module` WHERE `Module_ID` = " . $arguments['module_id'];
  }


  /**
   * Загрузить список псевдопеременных
   * @return array
   */
  public function load_fields(){
    $nc_core = nc_Core::get_object();

    // Цикл обработки сущностей
    foreach($this->sql as $essence => $sql){
      // Если имеется сущность "Сообщение" то добавить запрос на пользователя
      if($this->sql["message"] AND !$this->sql["User"]){
        $this->fields['query']['message'] = $nc_core->db->get_row($this->sql["message"], ARRAY_A);
        $this->load_sql(array("user_id" => $this->fields['query']['message']['User_ID']));
      }
      // Запрос на поля сущности
      $this->fields['query'][$essence] = $nc_core->db->get_row($sql, ARRAY_A);
      // Цикл обработки полей сущности
      foreach($this->fields['query'][$essence] as $name => $value){
        $this->handle_fields($essence, $name, $value, $this->arguments['class_id'], mb_strtolower($essence));
      }
    }

    // Если включен модуль "Маршрутизация"
    if($nc_core->modules->get_by_keyword('routing')){
      $this->set_properties(array(
        "{subdivision.Path}" => nc_folder_path($this->fields['query']['subdivision']["Subdivision_ID"]),
        "{sub_class.Path}" => nc_infoblock_path($this->fields['query']['sub_class']["Sub_Class_ID"]),
        "{message.Path}" => nc_object_path($this->arguments['class_id'], $this->fields['query']['message']["Message_ID"]),
        "{message.Path.Edit}" => nc_object_path($this->arguments['class_id'], $this->fields['query']['message']["Message_ID"], "edit"),
        "{message.Path.Edit.Admin}" => "/netcat/message.php?inside_admin=1&sub=" . $this->fields['query']['subdivision']["Subdivision_ID"] . "&cc=" . $this->fields['query']['sub_class']["Sub_Class_ID"] . "&message=" . $this->fields['query']['message']["Message_ID"] . ""
      ));

      // Если включен модуль "Рассылки"
      if($nc_core->modules->get_by_keyword('subscriber')){
        $this->set_properties(array(
          "{sub_class.Subscribe.Path}" => nc_infoblock_path($this->fields['query']['sub_class']["Sub_Class_ID"], "subscribe"),
          "{message.Subscribe.Path}" => nc_object_path($this->arguments['class_id'], $this->fields['query']['message']["Message_ID"], "subscribe")
        ));
      }
    }

    // Если включен модуль "Личный кабинет"
    if($nc_core->modules->get_by_keyword('auth')){
      $this->set_properties(array(
        "{user.Path}" => nc_auth_profile_url($this->fields['query']['user']["User_ID"])
      ));
    }

    return $this->fields;
  }

  /**
   * Обработать поля по типу
   * @param null $essence
   * @param null $field_name
   * @param null $field_value
   * @param null $class_id
   * @param null $prefix
   */
  protected function handle_fields($essence, $field_name = null, $field_value = null, $class_id = null, $prefix = null){
    $nc_core = nc_Core::get_object();

    switch($essence){
      case 'catalogue':
        $field = $nc_core->db->get_row("SELECT `Field_Name`, `Field_ID`, `TypeOfData_ID`, `Format`, `Extension` FROM `Field` WHERE `System_Table_ID` = 1 AND `Field_Name`='" . $field_name . "'", ARRAY_A);
        break;
      case 'subdivision':
        $field = $nc_core->db->get_row("SELECT `Field_Name`, `Field_ID`, `TypeOfData_ID`, `Format`, `Extension` FROM `Field` WHERE `System_Table_ID` = 2 AND `Field_Name`='" . $field_name . "'", ARRAY_A);
        break;
      case 'message':
        $field = $nc_core->db->get_row("SELECT `Field_Name`, `Field_ID`, `TypeOfData_ID`, `Format`, `Extension` FROM `Field` WHERE `Class_ID`=" . $class_id . " AND `Field_Name`='" . $field_name . "'", ARRAY_A);
        break;
      case 'user':
        $field = $nc_core->db->get_row("SELECT `Field_Name`, `Field_ID`, `TypeOfData_ID`, `Format`, `Extension` FROM `Field` WHERE `System_Table_ID` = 3 AND `Field_Name`='" . $field_name . "'", ARRAY_A);
        break;
    }

    // Если не нашли поля в таблице Field базы данных то не обрабатывать значение и оставить как есть
    if(!empty($field)){
      switch($field['TypeOfData_ID']){
        case 4: // Список
          $field['Format'] = explode(":", $field['Format']);
          $sql = "SELECT * FROM `Classificator_" . $field['Format'][0] . "` WHERE `" . $field['Format'][0] . "_ID` = " . $field_value;
          $classificator = $nc_core->db->get_row($sql, ARRAY_A);

          $this->set_properties(array(
            "{" . $prefix . "." . $field_name . ".ID}" => $field_value,
            "{" . $prefix . "." . $field_name . ".Name}" => $classificator[$field['Format'][0] . "_Name"],
            "{" . $prefix . "." . $field_name . ".Value}" => $classificator["Value"]
          ));

          // Заменить значение на обработанное
          $field_value = $classificator[$field['Format'][0] . "_Name"];
          break;

        case 5: // Логическая переменная
          if($field_value == 1) $field_value = 'Да';
          else $field_value = 'Нет';
          break;

        case 6: // Файл
          $value = explode(":", $field_value);
          $file_path = nc_file_path($this->arguments['class_id'], $this->arguments['message_id'], $field['Field_ID'], '_h');

          $this->set_properties(array(
            "{" . $prefix . "." . $field_name . ".Path}" => $file_path,
            "{" . $prefix . "." . $field_name . ".Name}" => $value[0],
            "{" . $prefix . "." . $field_name . ".Type}" => $value[1],
            "{" . $prefix . "." . $field_name . ".Size}" => $value[2]
          ));

          if(is_file($_SERVER['DOCUMENT_ROOT'] . $file_path)){
            // Прикрепить к письму
            $this->attach[] = array("Path" => $file_path, "Type" => $value[1], "Name" => $value[0]);
          }
          $field_value = null;
          break;

        case 8: // Дата и время
          $date = explode(" ", $field_value);
          $date['Date'] = explode("-", $date[0]);
          $date['Time'] = explode(":", $date[1]);

          $this->set_property("{" . $prefix . "." . $field_name . "}", date($this->date_format, strtotime($date)));
          $this->set_properties(array(
            "{" . $prefix . "." . $field_name . ".Year}" => $date['Date'][0],
            "{" . $prefix . "." . $field_name . ".Month}" => $date['Date'][1],
            "{" . $prefix . "." . $field_name . ".Day}" => $date['Date'][2],
            "{" . $prefix . "." . $field_name . ".Hours}" => $date['Time'][0],
            "{" . $prefix . "." . $field_name . ".Minutes}" => $date['Time'][1],
            "{" . $prefix . "." . $field_name . ".Seconds}" => $date['Time'][2]
          ));
          break;

        case 9: // Связь с другой сущностью
          $field['Format'] = explode(":", $field['Format']);
          // Ссылки на объект
          if(is_numeric($field['Format'][0])){
            $Table = "Message";
            $class_id = $field['Format'][0];
            $this->set_properties(array(
              "{" . $prefix . "." . $field_name . ".Path}" => nc_object_path($field['Format'][0], $field_value),
              "{" . $prefix . "." . $field_name . ".URL}" => nc_object_url($field['Format'][0], $field_value)
            ));
          }else{
            $Table = $field['Format'][0];
            $class_id = null;
            switch($field['Format'][0]){
              case 'Subdivision':
                $this->set_properties(array(
                  "{" . $prefix . "." . $field_name . ".Path}" => nc_folder_path($field_value),
                  "{" . $prefix . "." . $field_name . ".URL}" => nc_folder_url($field_value)
                ));
                break;
              case 'Sub_Class':
                $this->set_properties(array(
                  "{" . $prefix . "." . $field_name . ".Path}" => nc_infoblock_path($field_value),
                  "{" . $prefix . "." . $field_name . ".URL}" => nc_infoblock_url($field_value)
                ));
                break;
            }
          }
          // Рекурсивно обработать поля объекта
          $sql = "SELECT * FROM `" . $Table . $class_id . "`WHERE `" . $Table . "_ID` = " . $field_value;
          $bound = $nc_core->db->get_row($sql, ARRAY_A);
          foreach($bound as $bound_Field_Name => $bound_Field_value){
            $this->handle_fields($Table, $bound_Field_Name, $bound_Field_value, $class_id, $prefix . "." . $field['Field_Name']);
          }
          break;

        case 10: // Множественный выбор
          $field['Format'] = explode(":", $field['Format']);
          $field_value = implode(",", array_filter(explode(",", $field_value)));
          $sql = "SELECT `" . $field['Format'][0] . "_Name` as name FROM `Classificator_" . $field['Format'][0] . "` WHERE `" . $field['Format'][0] . "_ID` IN(" . $field_value . ")";
          $classificator = $nc_core->db->get_results($sql, ARRAY_A);
          $field_value = array();
          foreach($classificator as $row){
            $field_value[] = $row["name"];
          }
          // Заменить значение на обработанное
          $field_value = implode(", ", $field_value);
          break;

        case 11: // Множественная зугрузка файлов
          $sql = "SELECT `Name`, `Path` FROM `Multifield` WHERE `Field_ID` = " . $field['Field_ID'] . " AND `" . $essence . "_ID` = " . $this->message_id;
          $Files = $nc_core->db->get_results($sql, ARRAY_A);
          foreach($Files as $File){
            $File_Name = explode(":", $File['Path']);
            if(empty($File_Name[0])) $File_Name = $field_name[0];
            else $File_Name = array_pop(explode("/", $File['Path']));
            $this->attach[] = array("Path" => $File['Path'], "Type" => 'application/octet-stream', "Name" => $File_Name);
          }
          // Заменить значение на обработанное
          $field_value = null;
          break;
      }
    }

    // Записать в массив имена и значения
    if(preg_match("/(<\?|\?>|<\%|\%>)/g", $field_value)) $field_value = htmlentities($field_value);
    $this->set_property("{" . $prefix . "." . $field_name . "}", $field_value);
  }
}
