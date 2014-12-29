<?php
require_once('inc/inc.php');

$db = new DB();
$templ = new Template();
$ob = new FormData();

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
        if(Mail::goMail($ob)){
            if($db->saveUser($ob)) {
                header('Location: '.$_SERVER['PHP_SELF'].'?success=1');
                die;
            } else {
                $msg .= 'Ошибка сохранения';
            }
        } else {
            $msg = 'Сообщение не было отправлено. Mailer ошибка';
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