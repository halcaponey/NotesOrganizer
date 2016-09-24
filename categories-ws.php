<?php

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    if($_GET == null){
      get_all();
    }
    break;
  case 'POST':
    break;
  case 'PUT':
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


function delete_by_id($id, $id_parent){
  if ($id_parent == 'null') {
    $id_parent = null;
  }
  $servername = "localhost";
  $username = "root";
  $password = "";
  $bdname = "note_organizer";
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$bdname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $bdname = "note_organizer";

    $conn = new PDO("mysql:host=$servername;dbname=$bdname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            $result[$j]['children'][count($val->children)] = $result[$i];
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
