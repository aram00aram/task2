<?php
require __DIR__.'/vendor/autoload.php';

class DB {

    private $conn;
    private $table_name = "person";

    public function __construct(){
        if(!isset($this->db)){
            $conn = $db = new PDO("mysql:host=localhost;dbname=excel-api","root",'root');
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->conn = $conn;
            }
        }
    }

    public function insert($name, $surname, $age){
        $query      = "INSERT INTO " . $this->table_name . " SET name=:name, surname=:surname, age=:age";
        $stmt       = $this->conn->prepare($query);

        $name       = htmlspecialchars(strip_tags($name));
        $surname    = htmlspecialchars(strip_tags($surname));
        $age        = htmlspecialchars(strip_tags($age));

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":age", $age);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    public function importApi($age = 18){
        $query = $query = "SELECT * FROM " . $this->table_name . " WHERE age >= ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $age);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->sheets($rows) ;
    }

    public function sheets($request){

        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(__DIR__.'/api-excel-2ca46aa70dcc.json');
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = "1VPEQ2a6vHWuUnhJEL4QjOHPlWnTkqBAsycYOyoAG-Kg";
        $options    = array('valueInputOption' => 'RAW');
        $values     = [];
        foreach ($request as $val){
            array_push($values, [$val['name'],$val['surname'],$val['age']]);
        }

        $body   = new Google_Service_Sheets_ValueRange(['values' => $values]);
        $res    = $service->spreadsheets_values->append($spreadsheetId, 'A1:C1', $body, $options);

        if($res){
            echo '<a href="https://docs.google.com/spreadsheets/d/1VPEQ2a6vHWuUnhJEL4QjOHPlWnTkqBAsycYOyoAG-Kg/edit#gid=0">Успех :-)   Посмотреть файл</a>';
        }
    }


}