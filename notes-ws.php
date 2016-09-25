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
    if ($data->title != null){
       add($data->title, $data->description, $data->categories);
    }
    break;
  case 'PUT':
    $data = json_decode(file_get_contents("php://input"));
    if ($data->id != null && $data->title != null){
       modify($data->id, $data->title, $data->description, $data->categories);
    }
    break;
  case 'DELETE':
    if (array_key_exists("id", $_GET)){
      delete_by_id($_GET['id']);
    }
    break;
  default:
    handle_error($request);
    break;
}

function modify($id, $title, $description, $categories){
  if ($description == null){
    $description = "";
  }

  try {
    $dbconn = DbConnection::getConnection();
    $conn = $dbconn->_pdo;

    $stmt = $conn->prepare("UPDATE note SET title=:title, description=:description WHERE id=:id;");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    if($categories != null){

      $stmt = $conn->prepare("DELETE FROM note_categorie WHERE id_note = :id");
      $stmt->bindParam(':id', $id);
      $stmt->execute();

      foreach ($categories as $cat) {
        $stmt = $conn->prepare("INSERT INTO note_categorie (id_note, id_categorie) VALUES (:id_note, :id_categorie)");
        $stmt->bindParam(':id_note', $id);
        $stmt->bindParam(':id_categorie', $cat);
        $stmt->execute();
      }
    }

    $conn = null;
  }catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
}

function add($title, $description, $categories){
  if ($description == null){
    $description = "";
  }

  try {
    $dbconn = DbConnection::getConnection();
    $conn = $dbconn->_pdo;

    $stmt = $conn->prepare("INSERT INTO note (title, description) VALUES (:title, :description)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    if($categories != null){
      $id_note = $conn->lastInsertId();
      foreach ($categories as $cat) {
        $stmt = $conn->prepare("INSERT INTO note_categorie (id_note, id_categorie) VALUES (:id_note, :id_categorie)");
        $stmt->bindParam(':id_note', $id_note);
        $stmt->bindParam(':id_categorie', $cat);
        $stmt->execute();
      }
    }

    $conn = null;
  }catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
}

function delete_by_id($id){
  try {
    $dbconn = DbConnection::getConnection();
    $conn = $dbconn->_pdo;

    $stmt = $conn->prepare("DELETE FROM note_categorie WHERE id_note = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM note WHERE id = :id");
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

    $stmt = $conn->prepare("SELECT * FROM note");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($result); $i++) {
      $stmt = $conn->prepare("SELECT categorie.id, categorie.name FROM note_categorie
        INNER JOIN categorie ON note_categorie.id_categorie = categorie.id
        WHERE note_categorie.id_note = :idnote");
      $stmt->bindParam(':idnote', $result[$i]['id']);
      $stmt->execute();
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //var_dump($res);
      $result[$i]['categorie'] = $res;
    }
    echo json_encode($result);
  }catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }
}
?>
