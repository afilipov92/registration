<?php
require_once('inc/inc.php');

$db = new DB();
$templ = new Template();
$ob = new FormData();
$mail = new PHPMailer();

$pageTpl = Template::getTemplate('page');
$templ->setHtml(Template::getTemplate('form_registration'));

$msg = "";

FormRegistration::getFormData($ob);

if(FormRegistration::isFormSubmitted()){
    $validateFormResult = FormRegistration::isFormVaild($ob, $db);
    if($validateFormResult !== true) {
        $templ->setHtml($templ->processTemplateErrorOutput($validateFormResult));
    } else {
        $ob->hash = md5($ob->userName);
        if($db->saveUser($ob)){
            $message = "Уважаемый".$ob->userName.",<br/>
            Спасибо за то, что Вы  создали аккаунт у нас. Для того чтобы активировать Ваш профайл нажмите на ссылку ниже:<br/>
            <a href='http://localhost/registration/registr.php?userName=".$ob->userName."&hash=".$ob->hash."' target='_blank'>
            http://localhost/registration/registr.php</a>";
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPKeepAlive = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = 'smtp.yandex.ru';
            $mail->Port = 465;
            $mail->Username = 'al.oz2015@yandex.ru';
            $mail->Password = 'Paradise90';
            $mail->SetFrom('al.oz2015@yandex.ru');
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Title';
            $mail->MsgHTML($message);
            $mail->AddAddress($ob->userEmail);
            if(!$mail->send()) {
                $msg .= 'Сообщение не было отправлено. Mailer ошибка: ' . $mail->ErrorInfo;
            } else {
                header('Location: '.$_SERVER['PHP_SELF'].'?success=1');
                die;
            }
        } else {
            $msg = 'Ошибка сохранения';
        }
    }
}
if(FormRegistration::isSuccess()){
    $templ->setHtml('Письмо для подтверждения регистрации было отправленно на ваш E-mail');
} else{
    $templ->setHtml(Template::processTemplace($templ->getHtml(), array(
        'userName' => $ob->userName,
        'userEmail' => $ob->userEmail,
        'password' => "",
        'passwordConfirm' => ""
    )));
    $templ->setHtml(Template::processTemplace($templ->getHtml(), array('CAPTCHA' => FormRegistration::generateCaptcha())));
}

$page = Template::processTemplace($pageTpl, array(
    'FORM' => $templ->getHtml(),
    'MSG' => $msg
));
echo $page;