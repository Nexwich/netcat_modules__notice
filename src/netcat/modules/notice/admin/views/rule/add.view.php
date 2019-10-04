<?php if(!class_exists('nc_core')){
  die;
}

/**
 * @var array $rule
 * @var string $SendCond
 * @var string $SendAction
 */

$nc_core = nc_Core::get_object();

$ADMIN_PATH = $nc_core->ADMIN_PATH;
$MODULE_PATH = nc_module_path('notice');

get_style_and_script(array("selectize"));
?>
<!-- Скрипт -->
<script type='text/javascript' src='<?= $MODULE_PATH ?>admin/views/rule/application.js?time=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . $MODULE_PATH . 'admin/views/rule/application.js') ?>'></script>

<div id='notice_wrapper'>

  <!-- Правило -->
  <div class="component rule form">

    <div class="wrapper">
      <div class="shell">

        <div class="form">
          <form enctype='multipart/form-data' method='post' action='<?= $MODULE_PATH ?>admin/?controller=rule&action=save'>
            <div>
              <? if($rule){ ?><input type='hidden' name='f_Notice_Rule_ID' value='<?= $rule['id'] ?>'><? } ?>
            </div>

            <div class="nc-form-row">
              <label for="f_Notice_Rule_Name"><?= OCTOCORP_MODULE_NOTICE_RULE_NAME ?></label>
              <input id="f_Notice_Rule_Name" type='text' name='f_Notice_Rule_Name' value='<?= $rule['name'] ?>'>
            </div>
            <div class="nc-form-row">
              <label for="f_Event"><?= OCTOCORP_MODULE_NOTICE_RULE_EVENT ?></label>
              <select id="f_Event" name='f_Event' class="js-selectize" data-select='{"persist": false, "create": true, "searchField":"name"}' data-options='{"load":{"type":"event", "value":"<?= $rule['event'] ?>"}, "essence":"event"}' required='required'></select>
            </div>
            <div class="nc-form-row">
              <label class="inline" title="<?= OCTOCORP_MODULE_NOTICE_RULE_CRON_TITLE ?>">
                <input type="checkbox" name="f_Cron" id="f_Cron" value="1"<? if(!empty($rule['cron'])){ ?> checked<? } ?>>
                <?= OCTOCORP_MODULE_NOTICE_RULE_CRON ?>
              </label>
            </div>
            <div class="nc-form-row">
              <label for="SendCond"><?= OCTOCORP_MODULE_NOTICE_RULE_COND ?></label>
              <textarea id="SendCond" name="SendCond" cols="30" rows="5"><?= $SendCond ?></textarea>
            </div>
            <div class="nc-form-row">
              <label for="SendAction"><?= OCTOCORP_MODULE_NOTICE_RULE_ACTION_AFTER_SEND ?></label>
              <textarea id="SendAction" name="SendAction" cols="30" rows="5"><?= $SendAction ?></textarea>
            </div>
            <div class="nc-form-row">
              <label for="f_Note"><?= OCTOCORP_MODULE_NOTICE_RULE_NOTE ?></label>
              <textarea id="f_Note" name="f_Note" cols="30" rows="5"><?= $rule['note'] ?></textarea>
            </div>
          </form>
        </div>

      </div>
    </div>

  </div>

</div>