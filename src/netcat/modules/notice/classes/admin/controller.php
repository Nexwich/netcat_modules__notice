<?php

/**
 * Типовой контроллер страниц административного интерфейса модуля.
 */
abstract class notice_admin_controller extends nc_ui_controller{

  protected $use_layout = true;
  protected $field = array();
  protected $classificator = array();
  protected $variables = array();

  protected $ui_config;

  /** @var string  Должен быть задан, или должен быть переопределён метод before_action() */
  protected $ui_config_class = null;

  protected function init(){
    $this->ui_config = new ui_config();
  }

  protected function get_list_class(){
    $nc_core = nc_Core::get_object();

    $sql = "SELECT `Class_ID` AS `ID`, `Class_Name` AS `Name`, `Class_Group` AS `Group_Name` FROM `Class` WHERE `ClassTemplate` = 0 ORDER BY `Group_Name`, `Priority`";
    $items = $nc_core->db->get_results($sql, ARRAY_A);

    $options = $group = array();
    foreach($items as $key => $item){
      $array = array(
        "id" => $item['ID'],
        "name" => $item['Name']
      );
      if(!empty($item['Group_Name'])){
        $array['group'] = $item['Group_Name'];
      }
      $options[] = $array;
      if(!empty($item['Group_Name']) and (empty($group_array) or $group_array['name'] != $item['Group_Name'])){
        $group_array = array("name" => $item['Group_Name']);
        $group[] = $group_array;
      }
    }

    if(!empty($group)) $group = array_filter(unique_multidim_array($group, 'name'));

    return array(
      "options" => $options,
      "group" => $group
    );
  }

  protected function get_list_sub_class(){
    $nc_core = nc_Core::get_object();

    $sql = "SELECT `Sub_Class_ID` AS `ID`, `Sub_Class_Name` AS `Name`, (SELECT `Subdivision_Name` FROM `Subdivision` WHERE `Subdivision_ID` = cc.`Subdivision_ID` ORDER BY `Parent_Sub_ID`, `Priority`) AS `Group_Name` FROM `Sub_Class` AS cc ORDER BY `Group_Name`, `Priority`";
    $items = $nc_core->db->get_results($sql, ARRAY_A);

    $options = $group = array();
    foreach($items as $key => $item){
      $array = array(
        "id" => $item['ID'],
        "name" => $item['Name']
      );
      if(!empty($item['Group_Name'])){
        $array['group'] = $item['Group_Name'];
      }
      $options[] = $array;
      if(!empty($item['Group_Name']) and (empty($group_array) or $group_array['name'] != $item['Group_Name'])){
        $group_array = array("name" => $item['Group_Name']);
        $group[] = $group_array;
      }
    }

    if(!empty($group)) $group = array_filter(unique_multidim_array($group, 'name'));

    return array(
      "options" => $options,
      "group" => $group
    );
  }

  protected function get_list_user(){
    $nc_core = nc_Core::get_object();

    $sql = "SELECT * FROM `User` ORDER BY `Login`";
    $users = $nc_core->db->get_results($sql, ARRAY_A);

    $options = array();

    $user_name = nc_core()->get_settings('User_Name', 'notice');
    if(empty($user_name)) $user_name = $nc_core->db->get_var("SELECT Field_ID FROM `Field` WHERE `Field_Name` = 'Login'");
    $fields = $nc_core->db->get_results("SELECT `Field_Name` FROM `Field` WHERE `Field_ID` IN(" . $user_name . ") ORDER BY FIELD(`Field_ID`, " . $user_name . ")", ARRAY_A);

    foreach($users as $key => $user){
      // Составить название
      $name = array();
      foreach($fields as $field){
        $name[] = $user[$field['Field_Name']];
      }

      $options[] = array(
        "id" => $user['User_ID'],
        "full_name" => join(" ", $name),
        "name" => $name[0],
      );
    }

    return $options;
  }

  protected function field_select($essence, $id = null, $attr = null){
    switch($essence){
      case 'class':
        $items = $this->get_list_class();
        break;
      case 'sub_class':
        $items = $this->get_list_sub_class();
        break;
      case 'user':
        $items = $this->get_list_user();
        break;
    }
    ?>
    <select <?= $attr ?>>
      <? foreach($items as $item){ ?>
    <? if(!empty($item['Group_Name']) and (empty($group) or $item['Group_Name'] != $group)){ ?>
    <? $group = $item['Group_Name']; ?>
    <? if($group != $items[0]['Group_Name']){ ?></optgroup><? } ?>
      <optgroup label='<?= $group ?>'>
        <? } ?>
        <option value="<?= $item['id'] ?>"<?= ($id == $item['ID'] ? " selected='selected'" : null) ?>><?= $item['name'] ?></option>
        <? } ?>
        <? if(!empty($group)){ ?></optgroup><? } ?>
    </select>
    <?
  }

  protected function select_subdivision($name, $value = ''){
    $nc_core = nc_Core::get_object();
    static $subs;

    if(is_array($value)) $value = $value[$name];
    if(!$subs){
      $subs = $nc_core->db->get_results("SELECT s.`Subdivision_ID` as `value`,
        CONCAT(s.`Subdivision_ID`, '. ', s.`Subdivision_Name`) as  `description`,
        c.`Catalogue_Name` as `optgroup`,
        s.`Parent_Sub_ID` as `parent`
        FROM `Catalogue` AS `c`, `Subdivision` AS `s`
        WHERE s.`Catalogue_ID` = c.`Catalogue_ID`
        ORDER BY c.`Priority`, s.`Priority` ", ARRAY_A);
    }

    $res = $this->select_options($subs, $value);
    return $res;
  }

  protected function select_component($name, $value = ''){
    $nc_core = nc_Core::get_object();
    static $classes;

    if(is_array($value)) $value = $value[$name];
    if(!$classes){
      $classes = $nc_core->db->get_results("SELECT `Class_ID` as value,
        CONCAT(`Class_ID`, '. ', `Class_Name`) as description,
        `Class_Group` as optgroup
        FROM `Class`
        WHERE `ClassTemplate` = 0
        ORDER BY `Class_Group`, `Priority`, `Class_ID`", ARRAY_A);
    }

    $res = $this->select_options($classes, $value);
    return $res;
  }

  protected function select_options(&$data, $selected_value = "", $level = 0, $current_parent = 0, $null_value = 0){

    if(!is_array($data)){
      trigger_error("nc_select_options: first parameter is not an array", E_USER_WARNING);
      return "";
    }

    $str = "";
    if(!$level){ // первый вызов функции
      if(array_key_exists('parent', $data[0])){ // перегруппировать по parent
        foreach((array) $data as $row){
          $values[$row['parent']][] = $row;
        }
      }else{ // чтобы не делить циклы для случаев с группировкой и без нее
        $values = array(&$data);
      }
    }else{ // рекурсивный вызов функции
      $values = &$data;
    }

    if($null_value){
      $str .= "<option value=\"0\">" . NETCAT_MODERATION_LISTS_CHOOSE . "</option>\n";
    }
    $optgroup = null;
    foreach((array) $values[$current_parent] as $row){
      if(!$level && $optgroup !== null && (!isset($row['optgroup']) || $optgroup != $row['optgroup'])){
        $optgroup = null;
        $str .= "</optgroup>\n";
      }
      if(!$level && $row['optgroup'] && ($optgroup != $row['optgroup'])){
        $optgroup = $row['optgroup'];
        $str .= "<optgroup label='" . $optgroup . "'>\n";
      }
      $str .= "<option " . ($row['without_cc'] ? 'style=\'color: #cccccc;\'' : '') . " value=\"" . htmlspecialchars($row['value']) . "\"" .
        ($row['value'] == $selected_value ? ' selected' : '') .
        ">" .
        str_repeat("&nbsp; &nbsp; &nbsp;", $level) .
        htmlspecialchars($row['description']) . "</option>\n";

      if($values[$row['value']]){
        $str .= $this->select_options($values, $selected_value, $level + 1, $row['value']);
      }
    }

    if($optgroup !== null){
      $str .= "</optgroup>\n";
    }

    return $str;
  }


  protected function before_action(){
    if($this->ui_config_class){
      $ui_config_class = $this->ui_config_class;
      $this->ui_config = new $ui_config_class($this->site_id, $this->current_action);
    }
  }

  protected function after_action($result){
    if(!$this->use_layout){
      return $result;
    }

    BeginHtml(OCTOCORP_MODULE_NOTICE, '', '');
    echo $result;
    EndHtml();
    return '';
  }

  protected function redirect($path = 'admin/index.php'){
    ob_clean();
    header("Location: " . nc_module_path('notice') . $path);
    die;
  }

}