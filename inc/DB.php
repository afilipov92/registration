<?php

class DB{

    /**
     * экзмепляр соединения с базой данных
     * @var PDO
     */
    private $db;

    /**
     * соединение с базой данных
     */
    function __construct(){
        try{
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8;', DB_USER, DB_PASSWORD);
        } catch(PDOException $e){
            echo 'Подключение не удалось'.$e->getMessage();
        }
    }

    /**
     * добавляет новую категорию или обновляет
     * @param FormData $Data
     * @return bool
     */
    public function saveUser(FormData $Data){
        $ins = $this->db->prepare("INSERT INTO users (userName, userEmail, password, id_status, hash) VALUES (:userName, :userEmail, :password, :id_status, :hash)");
        if($ins->execute(array('userName' => $Data->userName, 'userEmail' => $Data->userEmail, 'password' => md5($Data->password), 'id_status' => 1, 'hash' => $Data->hash))){
            return true;
        } else{
            return false;
        }
    }

    /**
     * выборка из таблицы users по userName
     * @param $userName
     * @return bool|mixed
     */
    public function requestSelectUserName($userName){
		$sth = $this->db->prepare("SELECT * FROM users WHERE userName=:userName");
        $sth->execute(array('userName' => $userName));
		$mas = $sth->fetch(PDO::FETCH_ASSOC);
        if(!empty($mas)){
            return $mas;
        } else{
            return false;
        }
    }

    /**
     * выборка из таблицы users по userEmail
     * @param $userEmail
     * @return bool|mixed
     */
    public function requestSelectUserEmail($userEmail){
        $sth = $this->db->prepare("SELECT * FROM users WHERE userEmail=:userEmail");
        $sth->execute(array('userEmail' => $userEmail));
        $mas = $sth->fetch(PDO::FETCH_ASSOC);
        if(!empty($mas)){
            return $mas;
        } else{
            return false;
        }
    }

    public function getHashDB($userName){
        $sth = $this->db->prepare("SELECT * FROM users WHERE userName = :userName");
        $sth->execute(array('userName' => $userName));
        $mas = $sth->fetch(PDO::FETCH_ASSOC);
        if(!empty($mas)){
            return $mas;
        } else{
            return false;
        }
    }

    public function updateHashDB($id){
        $sth = $this->db->prepare("UPDATE users SET hash=:hash WHERE id=:id");
        if($sth->execute(array('hash' => 'actived', 'id' => $id))){
            return true;
        } else{
            return false;
        }
    }
}