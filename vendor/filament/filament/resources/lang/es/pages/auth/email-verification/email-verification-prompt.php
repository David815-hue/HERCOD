<?php

return [

    'title' => 'Verifique su dirección de correo electrónico',

    'heading' => 'Verifique su dirección de correo electrónico',

    'actions' => [

        'resend_notification' => [
            'label' => 'Enviar',
        ],

    ],

    'messages' => [
        'notification_not_received' => 'Presiona',
        'notification_sent' => 'Enviaremos un correo electrónico a :email con instrucciones sobre cómo verificar su dirección de correo electrónico.',
    ],

    'notifications' => [

        'notification_resent' => [
            'title' => 'Hemos enviado el correo electrónico.',
        ],

        'notification_resend_throttled' => [
            'title' => 'Demasiados intentos de envío',
            'body' => 'Por favor, inténtelo de nuevo en :seconds segundos.',
        ],

    ],

];
