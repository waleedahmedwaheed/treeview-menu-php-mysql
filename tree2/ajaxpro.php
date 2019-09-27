<?php
   error_reporting(0);
    require 'db_config.php';
  
    $parentKey = '0';
    $sql = "SELECT * FROM item";
  
    $result = $mysqli->query($sql);
   
      if(mysqli_num_rows($result) > 0)
      {
          $data = membersTree($parentKey);
      }else{
          $data=["id"=>"0","name"=>"No Members present in list","text"=>"No Members is present in list","nodes"=>[]];
      }
   
      function membersTree($parentKey)
      {
          require 'db_config.php';
  
          $sql = 'SELECT id, name from item WHERE parent_id="'.$parentKey.'"';
  
          $result = $mysqli->query($sql);
  
          while($value = mysqli_fetch_assoc($result)){
             $id = $value['id'];
             $row1[$id]['id'] = $value['id'];
             $row1[$id]['name'] = $value['name'];
             $row1[$id]['text'] = $value['name'];
             $row1[$id]['nodes'] = array_values(membersTree($value['id']));
          }
  
          return $row1;
      }
		
      echo json_encode(array_values($data));
  
?>