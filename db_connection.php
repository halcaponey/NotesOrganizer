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
  {
      $this->_pdo = new PDO("mysql:host=".self::$servername.";dbname=".self::$bdname, self::$username, self::$password);
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  public static function getConnection()
  {
      if (self::$_instance === null)
      {
          self::$_instance = new DbConnection();
      }
      return self::$_instance;
  }
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
