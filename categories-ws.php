<?php
require('db_connection.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    if($_GET == null){
      get_all();
    }
    break;
  case 'POST':
    $data = json_decode(file_get_contents("php://input"));
    if ($data->name != null){
       add($data->name, $data->id_parent);
    }
    break;
  case 'PUT':
    $data = json_decode(file_get_contents("php://input"));
    //var_dump($data);
    if ($data->id != null && $data->name != null){
       modify($data->id, $data->name, $data->id_parent);
    }
    break;
  case 'DELETE':
    if (array_key_exists("id", $_GET)){
      delete_by_id($_GET['id'], $_GET['id_parent']);
    }
    break;
  default:
    handle_error($request);
    break;
}

function modify($id, $name, $id_parent){
  if ($id_parent == 'null') {
    $id_parent = null;
  }

  try {
    $dbconn = DbConnection::getConnection();
    $conn = $dbconn->_pdo;

    $stmt = $conn->prepare("UPDATE categorie SET name=:name, id_parent=:id_parent WHERE id=:id;");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id_parent', $id_parent);
    $stmt->execute();

    $conn = null;
  }catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
}

function add($name, $id_parent){
  if ($id_parent == 'null') {
    $id_parent = null;
  }
  try {
    $dbconn = DbConnection::getConnection();
    $conn = $dbconn->_pdo;

    $stmt = $conn->prepare("INSERT INTO categorie (name, id_parent) VALUES (:name, :id_parent)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id_parent', $id_parent);
    $stmt->execute();

    $conn = null;
  }catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
}


function delete_by_id($id, $id_parent){
  if ($id_parent == 'null') {
    $id_parent = null;
  }
  try {
    $dbconn = DbConnection::getConnection();
    $conn = $dbconn->_pdo;

    $stmt = $conn->prepare("DELETE FROM note_categorie WHERE id_categorie = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE categorie SET id_parent=:id_parent WHERE id_parent=:id;");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':id_parent', $id_parent);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM categorie WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $conn = null;
  }catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
}

function get_all(){
  try {
    $dbconn = DbConnection::getConnection();
    $conn = $dbconn->_pdo;

    $stmt = $conn->prepare("SELECT * FROM categorie ORDER BY id_parent DESC");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i=0; $i < count($result); $i++) {
      for ($j=0; $j < count($result); $j++) {
        if ($result[$i]['id_parent'] == $result[$j]['id']){
          //echo 'oui';
          if(!isset($result[$j]['children'])){
            $result[$j]['children'][0] = $result[$i];
          }else{
            $result[$j]['children'][count($result[$j]['children'])] = $result[$i];
          }
        }
      }
    }

    $res = array();
    $j = 0;
    for ($i=0; $i < count($result); $i++) {
      if ($result[$i]['id_parent'] == null){
        $res[$j]=$result[$i];
        $j++;
      }
    }


    //var_dump($result);
    echo json_encode($res);

    $conn = null;
  }catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
}
?>
