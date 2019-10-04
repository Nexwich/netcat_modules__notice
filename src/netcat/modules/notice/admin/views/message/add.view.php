<?php if(!class_exists('nc_core')){
  die;
}

/**
 * @var array $rule
 * @var array $message
 */

$nc_core = nc_Core::get_object();

$ADMIN_FOLDER = $nc_core->ADMIN_PATH;
$MODULE_PATH = nc_module_path('notice');

get_style_and_script(array("selectize", "ckeditor"));
?>
<!-- Скрипт -->
<script type='text/javascript' src='<?= $MODULE_PATH ?>admin/views/message/application.js?time=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . $MODULE_PATH . 'admin/views/message/application.js') ?>'></script>

<div id='notice_wrapper'>

  <!-- Сообщение -->
  <div class="component message form">

    <div class="wrapper">
      <div class="shell">

        <div class="form">
          <form enctype='multipart/form-data' method='post' action='<?= $MODULE_PATH ?>admin/?controller=message&action=save'>
            <div>
              <? if(!empty($message)){ ?>
                <input type='hidden' name='f_Notice_Message_ID' value='<?= $message['id'] ?>'>
              <? } ?>
              <input type='hidden' name='f_Notice_Rule_ID' value='<?= $rule['id'] ?>'>
            </div>

            <div class="nc-form-row">
              <label><?= OCTOCORP_MODULE_NOTICE_MESSAGE_NAME ?></label>
              <input id="f_Notice_Message_Name" name="f_Notice_Message_Name" placeholder="" type='text' value="<?= $message['name'] ?>">
            </div>
            <div class="nc-form-row">
              <label for="f_Email_To"><?= OCTOCORP_MODULE_NOTICE_MESSAGE_EMAIL_TO ?></label>
              <select id="f_Email_To" name='f_Email_To[]' class="js-selectize" multiple='multiple' data-select='{"persist":false, "create": true}' data-options='{"load":{"type":"user", "value":<?= ($message['email_to__json'] ? $message['email_to__json'] : '""') ?>}, "essence":"user"}' required='required'></select>
            </div>
            <div class="nc-form-row">
              <label for="f_Email_From"><?= OCTOCORP_MODULE_NOTICE_MESSAGE_EMAIL_FROM ?></label>
              <select id="f_Email_From" name='f_Email_From' class="js-selectize" data-select='{"persist":false, "create": true}' data-options='{"load":{"type":"user", "value":["<?= $message['email_from'] ?>"]}, "essence":"user"}' required='required' <? if($nc_core->get_settings('SpamUseTransport', 'system') == 'Smtp'){ ?> disabled="disabled"<? } ?>></select>
            </div>
            <div class="nc-form-row">
              <label for="f_Email_Reply"><?= OCTOCORP_MODULE_NOTICE_MESSAGE_EMAIL_REPLY ?></label>
              <select id="f_Email_Reply" name='f_Email_Reply' class="js-selectize" data-select='{"persist":false, "create": true}' data-options='{"load":{"type":"user", "value":["<?= $message['email_reply'] ?>"]}, "essence":"user"}' required='required' <? if($nc_core->get_settings('SpamUseTransport', 'system') == 'Smtp'){ ?> disabled="disabled"<? } ?>></select>
            </div>
            <div class="nc-form-row">
              <label><?= OCTOCORP_MODULE_NOTICE_MESSAGE_NAME_FROM ?></label>
              <input id="f_Name" name="f_Name" type='text' placeholder="" value="<?= $message['name_from'] ?>">
            </div>
            <div class="nc-form-row">
              <label><?= OCTOCORP_MODULE_NOTICE_MESSAGE_SUBJECT ?></label>
              <input id="f_Subject" name="f_Subject" type='text' placeholder="" value="<?= $message['subject'] ?>">
            </div>
            <div class="nc-form-row">
              <label for="f_Message"><?= OCTOCORP_MODULE_NOTICE_MESSAGE_MESSAGE ?></label>
              <textarea id='f_Message' name='f_Message' cols="30" rows="5" class="no_cm"><?= $message['message'] ?></textarea>
              <script type='text/javascript'>nc_notice_editor('f_Message')</script>
            </div>
          </form>
        </div>

      </div>
    </div>

  </div>

</div>
