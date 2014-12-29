<?php
class FormRegistration{
    /**
     * Проверяет была ли отправлена форма
     * @return bool
     */
    public  static function isFormSubmitted(){
        return (isset($_POST) AND !empty($_POST));
    }

    /**
     * @return bool
     */
    public static function isSuccess(){
        return (isset($_GET['success']) AND !empty($_GET['success']));
    }

    /**
     * устанавливает значения массива из формы
     * @param FormData $ob
     */
    public static function getFormData(FormData $ob){
        $ob->userName = isset($_POST['userName'])? trim(strip_tags($_POST['userName'])): "";
        $ob->userEmail = isset($_POST['userEmail'])? trim(strip_tags($_POST['userEmail'])): "";
        $ob->password = isset($_POST['password'])? trim(strip_tags($_POST['password'])): "";
        $ob->passwordConfirm = isset($_POST['passwordConfirm'])? trim(strip_tags($_POST['passwordConfirm'])): "";
        $ob->captcha = isset($_POST['captcha'])? trim(htmlspecialchars($_POST['captcha'])): "";
    }

    /**
     * Проверяет правильность введенной капчи
     * @param $answ
     * @return bool
     */
    public static function checkCaptchaAnswer($answ){
        $rightAnsw = isset($_SESSION['captcha'])? $_SESSION['captcha']: '';
        return $answ == $rightAnsw;
    }

    /**
     * Проверяет валидность заполлнения полей формы
     * @param FormData $ob
     * @param DB $db
     * @return array|bool
     */
    public static function isFormVaild(FormData $ob, DB $db){
        $resp = true;
        $errors = array();
        if(preg_match('/^[a-zA-Z][a-zA-Z0-9-_\.]{5,20}$/', $ob->userName) == 0){
            $resp = false;
            $errors['userName'] = 'Логин должен быть от 5 до 20 символов';
        }
        if($db->requestSelectUserName($ob->userName) != false){
            $resp = false;
            $errors['userName'] = 'Пользователь с таким логином уже существует';
        }
        if($db->requestSelectUserEmail($ob->userEmail) != false){
            $resp = false;
            $errors['userEmail'] = 'Пользователь с таким E-mail уже существует';
        }
        if(preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', $ob->userEmail) == 0){
            $resp = false;
            $errors['userEmail'] = 'Проверьте ввод email';
        }
        if(preg_match('/(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $ob->password) == 0){
            $resp = false;
            $errors['password'] = "Проверьте ввод пароля (пароль должен быть от 6 символов, должны присутствовать:\n
            загланвые буквы, цифры, допускаются спец символы)";
        }
        if($ob->password != $ob->passwordConfirm){
            $resp = false;
            $errors['password'] = 'Пароли не совпадают';
        }
        if(!FormRegistration::checkCaptchaAnswer($ob->captcha)){
            $resp = false;
            $errors['captcha'] = 'Неправильный ответ';
        }
        if(!$resp){
            return $errors;
        } else {
            return $resp;
        }
    }

    /**
     * Генерирует капчу. Возвращает вопрос. Ответ устанавливает в сессию
     * @return string
     */
    public static function generateCaptcha(){
        $answ = rand(1, 20);
        $marker = rand(0,1)? '+': '-';
        $b = rand(1,$answ);
        switch($marker){
            case '+':
                $a = $answ - $b;
                break;
            case '-':
                $a = $answ + $b;
                break;
        }
        $_SESSION['captcha'] = $answ;
        return $a.' '.$marker.' '.$b;
    }

    /**
     * устанавливает значения userName и hash из письма активации
     * @param FormData $ob
     */
    public static function  getActivateData(FormData $ob){
        $ob->userName = isset($_GET['userName'])? trim(htmlspecialchars($_GET['userName'])): "";
        $ob->hash = isset($_GET['hash'])? trim(htmlspecialchars($_GET['hash'])): "";
    }

}
