<?php
define("RECAPCTHA_PUB_KEY", "6LeY1d4ZAAAAAFTGQQ43NlOaQpGjOrz3N_1u2OCq");
define("RECAPCTHA_SEC_KEY", "6LeY1d4ZAAAAAHEcMDB8-zLj4hzs3a9N0MMfbQD6");

AddEventHandler("main", "OnBeforeUserLogin", Array("CustomAuth", "checkCaptcha"));
AddEventHandler("main", "OnBeforeUserLogin", Array("CustomAuth", "phoneAuth"));

class CustomAuth
{
    function checkCaptcha(&$arFields)
    {
        global $APPLICATION;
        if ($APPLICATION->GetCurPage()=="/bitrix/admin/site_admin.php")
            return true;
        if ($_POST['g-recaptcha-response']) {
            $httpClient = new \Bitrix\Main\Web\HttpClient;
            $result = $httpClient->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => RECAPCTHA_SEC_KEY,
                    'response' => $_POST['g-recaptcha-response'],
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            );
            $result = json_decode($result, true);
            if ($result['success'] !== true) {
                $APPLICATION->throwException("Подтвердите, что вы не робот");
                return false;
            }
        } else {
            $APPLICATION->throwException("Подтвердите, что вы не робот");
            return false;
        }
    }

    function phoneAuth(&$arFields)
    {
        $userLogin = $_POST["USER_LOGIN"];
        if (isset($userLogin))
        {
            $isPhone = preg_match("/\+7\d{10}/",$userLogin);
            if ($isPhone)
            {
                $phone = Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($userLogin);
                $res = \Bitrix\Main\UserPhoneAuthTable::getList($parameters = [
                    'filter'=>['PHONE_NUMBER' =>$phone] //Можно добавить 'CONFIRMED'=>'Y', сделав авторизацию только для подтвержденных номеров
                ]);
                if($user = $res->Fetch())
                {
                    $rsUser = CUser::GetByID($user['USER_ID']);
                    if ($arUser = $rsUser->Fetch())
                        $arFields['LOGIN'] = $arUser['LOGIN'];
                }
            }
        }
    }
}
?>