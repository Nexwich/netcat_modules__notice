<?php if(!class_exists('nc_core')){
  die;
}

/**
 * @var array $options // Список объектов
 * @var string $group // Название группы
 */

if($dataType == 'json'){
  header('Content-Type: application/json');
  echo json_encode($result);
}