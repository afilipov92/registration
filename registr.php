<?php
require_once('inc/inc.php');

$db = new DB();
$templ = new Template();
$ob = new FormData();

FormRegistration::getActivateData($ob);
$mas = $db->getHashDB($ob->userName, $ob->hash);
if($mas){
    $db->updateHashDB($mas['id']);
    echo "Ваша учетная записать активирована";
}
else{
    echo "Ошибка активации учетной записи";
}