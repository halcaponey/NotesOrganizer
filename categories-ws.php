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
    break;
  default:
    handle_error($request);
    break;
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
