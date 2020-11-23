<?php if(!class_exists('nc_core')){
  die;
}

/**
 * @var string $user_name
 */

$nc_core = nc_Core::get_object();

$ADMIN_PATH = $nc_core->ADMIN_PATH;
$MODULE_PATH = nc_module_path('notice');

get_style_and_script(array("selectize"));
?>
<!-- Скрипт -->
<script type='text/javascript' src='<?= $MODULE_PATH ?>admin/views/settings/application.js?time=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . $MODULE_PATH . 'admin/views/settings/application.js') ?>'></script>

<div id='notice_wrapper'>

  <!-- Настройки -->
  <div class="component settings form">

    <div class="wrapper">
      <div class="shell">

        <div class="form">
          <form enctype='multipart/form-data' method='post' action='<?= $MODULE_PATH ?>admin/?controller=settings&action=save'>
            <div class="nc-form-row">
              <label for="f_Email"><?= OCTOCORP_MODULE_NOTICE_SETTINGS_EMAIL ?></label>
              <? if($nc_core->get_settings('SpamUseTransport', 'system') == 'Smtp'){ ?>
                <input placeholder="<?= $nc_core->get_settings('SpamFromEmail', 'system') ?>" disabled="disabled" id="f_Email" name="f_Email" type="text">
              <? }else{ ?>
                <input placeholder="<?= $nc_core->get_settings('SpamFromEmail', 'system') ?>" id="f_Email" name="f_Email" type="text" value="<?= $nc_core->get_settings('Email', 'notice') ?>">
              <? } ?>
            </div>
            <div class="nc-form-row">
              <label for="f_Name"><?= OCTOCORP_MODULE_NOTICE_SETTINGS_NAME ?></label>
              <input placeholder="<?= $nc_core->get_settings('SpamFromName', 'system') ?>" id="f_Name" name="f_Name" type="text" value="<?= $nc_core->get_settings('Name', 'notice') ?>">
            </div>
            <div class="nc-form-row">
              <label for="f_Subject"><?= OCTOCORP_MODULE_NOTICE_SETTINGS_SUBJECT ?></label>
              <input placeholder="<?= $nc_core->get_settings('ProjectName', 'system') ?>" id="f_Subject" name="f_Subject" type="text" value="<?= $nc_core->get_settings('Subject', 'notice') ?>">
            </div>
            <div class="nc-form-row">
              <label for="f_Date"><?= OCTOCORP_MODULE_NOTICE_SETTINGS_DATE ?></label>
              <input placeholder="d.m.Y H:i:s" id="f_Date" name="f_Date" type="text" value="<?= $nc_core->get_settings('Date', 'notice') ?>">
            </div>
            <div class="nc-form-row">
              <label for="f_User_Name"><?= OCTOCORP_MODULE_NOTICE_SETTINGS_USER_NAME ?></label>
              <select id="f_User_Name" name='f_User_Name[]' class="js-selectize" multiple='multiple' data-select='{"persist":false}' data-options='{"load":{"type":"user_fields", "value":<?= ($user_name ? $user_name : '""') ?>}, "essence":"user_fields"}'></select>
            </div>
          </form>
        </div>

        <div>
          <p>Модуль "Почтовые уведомления" версия 3.1.1 Все права защищены.</p>
          <p>Разработчик: «Панасин Александр»<br>
            Email: <a href="mailto:alterfall@mail.ru">alterfall@mail.ru</a><br>
            Телефон: <a href="tel:79307234990">+7 930 723-49-90</a></p>
          <p><a target="_blank" href="https://github.com/Nexwich/netcat_modules__notice/wiki">Документация по модулю</a><br>
            <a target="_blank" href="http://creativecommons.org/licenses/by-nd/3.0/deed.ru">Лицензия CC BY-ND 3.0</a>
          </p>
        </div>

      </div>
    </div>

  </div>

</div>
