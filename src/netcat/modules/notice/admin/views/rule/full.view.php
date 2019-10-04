<?php if(!class_exists('nc_core')){
  die;
}

/**
 * @var array $rule
 * @var array $messages
 * @var array $status
 */

$nc_core = nc_Core::get_object();

$ADMIN_PATH = $nc_core->ADMIN_PATH;
$MODULE_PATH = nc_module_path('notice');

get_style_and_script();
?>
<div id='notice_wrapper'>

  <!-- Правило -->
  <div class="component rule full">

    <div class="wrapper">
      <div class="shell">

        <div class="main">
          <?
          // Если найдены письма
          if(!empty($messages)){
          ?>
          <h2><?= OCTOCORP_MODULE_NOTICE_RULE ?></h2>

          <table class="nc-table nc--wide nc--striped nc--bordered nc--hovered">
            <tbody>
            <?
            $id = $rule->get_id();

            // Включен ли объект
            if($rule['checked'] == 1) $check_css_class = "nc--green";
            else  $check_css_class = "nc--red";
            if($rule['checked'] == 1) $check_name = "Вкл";
            else $check_name = "Выкл";

            // Составить название
            $name = $rule['name'];
            if(empty($name)) $name = $nc_core->event->event_name($rule['event']);
            if(empty($name)) $name = $rule['event'];
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
              <td><?= $name ?></td>
              <td class="nc--compact">
                <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.rule.remove(<?= $id ?>)"><i class="nc-icon nc--remove"></i></a>
              </td>
            </tr>
            </tbody>
          </table>

          <h2><?= OCTOCORP_MODULE_NOTICE_MESSAGES ?></h2>

          <table class="nc-table nc--wide nc--striped nc--bordered nc--hovered">
            <table class="nc-table nc--wide nc--striped nc--bordered nc--hovered">
              <thead>
              <tr>
                <th class="nc--compact">&nbsp;</th>
                <th class="nc--compact">&nbsp;</th>
                <th><?= OCTOCORP_MODULE_NOTICE_MESSAGE_INDEX ?></th>
                <th class="nc--compact">&nbsp;</th>
              </tr>
              </thead>
              <tbody>
              <tbody>
              <?
              // Цикл. Вывод правил
              foreach($messages as $message){
                $id = $message['id'];

                // Включен ли объект
                if($message['checked'] == 1) $check_css_class = "nc--green";
                else  $check_css_class = "nc--red";
                if($message['checked'] == 1) $check_name = "Вкл";
                else $check_name = "Выкл";

                // Составить название
                $name = $message['name'];
                ?>
                <tr>
                  <td class="nc--compact">
                    <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.message.check(<?= $id ?>)" class="nc-label <?= $check_css_class ?>"><?= $check_name ?></a>
                  </td>
                  <td class="nc--compact">
                    <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.message.update(<?= $id ?>)"><i class="nc-icon nc--edit"></i></a>
                  </td>
                  <td><?= $name ?></td>
                  <td class="nc--compact">
                    <a target="_top" href="<?= $ADMIN_PATH ?>#module.notice.message.remove(<?= $id ?>)"><i class="nc-icon nc--remove"></i></a>
                  </td>
                </tr>
              <? } ?>
              </tbody>
            </table>
            <?
            }else{
              nc_print_status(OCTOCORP_MODULE_NOTICE_MESSAGE_NOT_EXIST, "info");
            }
            ?>
        </div>

      </div>
    </div>

  </div>
</div>