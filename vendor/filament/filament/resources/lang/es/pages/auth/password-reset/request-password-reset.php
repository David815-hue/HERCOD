<?php

return [

    'title' => 'Restablecer tu contraseña',

    'heading' => '¿Olvidaste tu contraseña?',

    'actions' => [

        'login' => [
            'label' => 'Volver al inicio de sesión',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Correo electrónico',
        ],

        'actions' => [

            'request' => [
                'label' => 'Enviar email',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Demasiadas solicitudes',
            'body' => 'Por favor, inténtelo de nuevo en :seconds segundos.',
        ],

        'sent' => [
            'title' => 'Correo enviado',
            'body' => 'Te hemos enviado un correo con instrucciones para restablecer tu contraseña.',
        ],

        'trouble' => [
            'title' => 'Problema al enviar',
            'body' => 'Hubo un problema al enviar el correo de restablecimiento. Por favor, inténtalo nuevamente más tarde.',
        ],

    ],

];
