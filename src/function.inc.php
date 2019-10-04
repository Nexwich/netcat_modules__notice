<?php
$CURRENT_FOLDER = dirname(__FILE__);

require_once $CURRENT_FOLDER . "/notice.class.php";
nc_core()->register_class_autoload_path('notice_', $CURRENT_FOLDER . "/classes");

$notice = notice::get_object();

/** Подгружает js-скрипты и стили к ним
 * @param array $array // Имена загружаемых js-скриптов (selectize, ckeditor)
 */
function get_style_and_script($array = array()){
  $nc_core = nc_core::get_object();
  $MODULE_PATH = nc_module_path('notice');
  ?>

  <!-- Стили -->
  <link rel='stylesheet' href='<?= $MODULE_PATH ?>template/style/project.css?time=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . $MODULE_PATH . 'template/style/project.css') ?>'/>
  <? if(!empty($array)){ ?>
    <? foreach($array as $arr){ ?>
      <? if($arr == 'selectize'){ ?>
        <!-- Выпадающие списки -->
        <link rel="stylesheet" href="<?= $MODULE_PATH ?>template/lib/selectize/css/selectize.css?time=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . $MODULE_PATH . 'template/lib/selectize/css/selectize.css') ?>"/>
        <script type='text/javascript' src="<?= $MODULE_PATH ?>template/lib/selectize/js/standalone/selectize.min.js"></script>
      <? } ?>
      <? if($arr == 'ckeditor'){ ?>
        <!-- Визуальный редактор -->
        <script src='<?= $nc_core->HTTP_ROOT_PATH ?>editors/ckeditor4/ckeditor.js'></script>
        <script>var CKEDITOR_BASEPATH = '<?= $nc_core->HTTP_ROOT_PATH ?>editors/ckeditor4/';</script>
        <script src='<?= $MODULE_PATH ?>template/editor.js?time=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . $MODULE_PATH . 'template/editor.js') ?>'></script>
      <? } ?>
    <? } ?>
  <? } ?>
  
  <?
}