<?php
require 'db.php';

$person = new Db();

var_dump($person);die;

if(isset($_POST['create'])){
    if(!empty($_POST['name']) || !empty($_POST['surname']) || !empty($_POST['age'])){
        $person->insert($_POST['name'],$_POST['surname'],$_POST['age']);
        if($person){
            echo json_encode(['success'=>'Успешно сохранен']);
        }
    }else{
        echo json_encode(['error'=>'Не все поля заполнены!']);
    }
}

if(isset($_POST['import'])){
       echo $person->importApi();
}