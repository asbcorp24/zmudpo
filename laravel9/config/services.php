<?php
return [
    'robokassa'=>[
        'url'=>env('ROBOKASSA_URL','https://auth.robokassa.ru/Merchant/Index.aspx'),
        'login'=>env('ROBOKASSA_LOGIN'),
        'password1'=>env('ROBOKASSA_PASSWORD1'),
        'password2'=>env('ROBOKASSA_PASSWORD2'),
        'test'=>(bool)env('ROBOKASSA_TEST',true),
    ],
];
