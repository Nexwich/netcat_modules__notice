<?php

/**
 * Class notice_history
 */
class notice_history extends nc_record{
  /**
   * Ключ
   * @var string
   */
  protected $primary_key = "id";

  /**
   * Свойства
   * @var array
   */
  protected $properties = array(
    "id" => null,
    "email_to" => null,
    "email_from" => null,
    "email_reply" => null,
    "name_from" => null,
    "subject" => null,
    "message" => null
  );

  /**
   * Имя таблицы в бд
   * @var string
   */
  protected $table_name = "Notice_History";

  /**
   * php => MySQL
   * @var array
   */
  protected $mapping = array(
    "id" => "Notice_History_ID",
    "email_to" => 'Email_To',
    "email_from" => 'Email_From',
    "email_reply" => 'Email_Reply',
    "name_from" => 'Name',
    "subject" => 'Subject',
    "message" => 'Message'
  );

  /**
   * Проверка входныйх данных
   *
   * @return bool
   */
  public function validate(){

  }

}