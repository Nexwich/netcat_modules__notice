urlDispatcher.addRoutes({
  'module.notice':NETCAT_PATH + 'modules/notice/admin.php',

  'module.notice.settings':NETCAT_PATH + 'modules/notice/admin/?controller=settings&action=index',

  'module.notice.rule':NETCAT_PATH + 'modules/notice/admin.php',
  // Действия
  'module.notice.rule.add':NETCAT_PATH + 'modules/notice/admin/?controller=rule&action=add',
  'module.notice.rule.update':NETCAT_PATH + 'modules/notice/admin/?controller=rule&action=update&id=%1',
  'module.notice.rule.check':NETCAT_PATH + 'modules/notice/admin/?controller=rule&action=check&id=%1',
  'module.notice.rule.remove':NETCAT_PATH + 'modules/notice/admin/?controller=rule&action=remove&id=%1',
  'module.notice.rule.full':NETCAT_PATH + 'modules/notice/admin/?controller=rule&action=full&id=%1',

  'module.notice.message':NETCAT_PATH + 'modules/notice/admin.php',
  // Действия
  'module.notice.message.add':NETCAT_PATH + 'modules/notice/admin/?controller=message&action=add&rule_id=%1',
  'module.notice.message.update':NETCAT_PATH + 'modules/notice/admin/?controller=message&action=update&id=%1',
  'module.notice.message.check':NETCAT_PATH + 'modules/notice/admin/?controller=message&action=check&id=%1',
  'module.notice.message.remove':NETCAT_PATH + 'modules/notice/admin/?controller=message&action=remove&id=%1'
});