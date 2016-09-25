<?php
class DbConnection
{
  private static$servername = "localhost";
  private static $username = "root";
  private static $password = "";
  private static $bdname = "note_organizer";
  private static $_instance;
  public $_pdo;

  private function __construct()
  {//private constructor:
      $this->_pdo = new PDO("mysql:host=".self::$servername.";dbname=".self::$bdname, self::$username, self::$password);
      //You set attributes like so:
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //not setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);<-- PHP can't know which setAttribute method to call on what object
  }
  public static function getConnection()
  {
      if (self::$_instance === null)//don't check connection, check instance
      {
          self::$_instance = new DbConnection();
      }
      return self::$_instance;
  }
  //to TRULY ensure there is only 1 instance, you'll have to disable object cloning
  public function __clone()
  {
      return false;
  }
  public function __wakeup()
  {
      return false;
  }
}

?>
