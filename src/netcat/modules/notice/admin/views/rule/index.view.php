<?php if(!class_exists('nc_core')){
  die;
}

/**
 * @var array $rules
 * @var array $classificator
 */

$nc_core = nc_Core::get_object();

$ADMIN_PATH = $nc_core->ADMIN_PATH;
$MODULE_PATH = nc_module_path('notice');

get_style_and_script();
?>
<script type="text/javascript" src="<?= $MODULE_PATH ?>template/application.js?time=<?= filemtime($DOCUMENT_ROOT . $MODULE_PATH . 'template/application.js') ?>"></script>

<div id="notice_wrapper">

  <!-- Правила: список -->
  <div class="component rule list">

    <div class="wrapper">
      <div class="shell">

        <div class="main">
          <?
          // Если найдены правила
          if(empty($rules)){
            nc_print_status(OCTOCORP_MODULE_NOTICE_RULE_NOT_EXIST, "info");
          }else{
            ?>
            <table class="nc-table nc--wide nc--striped nc--bordered nc--hovered">
              <thead>
              <tr>
                <th class="nc--compact">&nbsp;</th>
                <th class="nc--compact">&nbsp;</th>
                <th class="nc--compact">&nbsp;</th>
                <th><?= OCTOCORP_MODULE_NOTICE_RULE_INDEX ?></th>
                <th class="nc--compact">&nbsp;</th>
              </tr>
              </thead>
              <tbody>
              <?
              // Цикл. Вывод правил
              foreach($rules as $rule){
                $id = $rule->get_id();

                // Включен ли объект
                if($rule['checked'] == 1) $check_css_class = "nc--green";
                else  $check_css_class = "nc--red";
                if($rule['checked'] == 1) $check_name = "Вкл";
                else $check_name = "Выкл";

                // Составить название
                $name = $rule['name'];
                if(empty($name)) $name = $nc_core->event->event_name($rule['event']);
                ?>
                <tr>
                  <td class="nc--compact">
                    <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.rule.check(<?= $id ?>)" class="nc-label <?= $check_css_class ?>"><?= $check_name ?></a>
                  </td>
                  <td class="nc--compact">
                    <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.rule.update(<?= $id ?>)"><i class="nc-icon nc--edit"></i></a>
                  </td>
                  <td class="nc--compact">
                    <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.message.add(<?= $id ?>)"><i class="nc-icon nc--file-add"></i></a>
                  </td>
                  <td><a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.rule.full(<?= $id ?>)"><?= $name ?></a></td>
                  <td class="nc--compact">
                    <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.rule.remove(<?= $id ?>)"><i class="nc-icon nc--remove"></i></a>
                  </td>
                </tr>
                <?
              }
              ?>
              </tbody>
            </table>
            <?
          }
          ?>
        </div>

      </div>
    </div>

  </div>

</div>