<?php
    /*Нет подключения - прерываем скрипт*/
    if (!defined('BASEPATH'))exit('No direct script access allowed');

    /*Собственно, параметры модуля*/
    $com_info = array(
        /*Имя, которое будет выводится в списке всех модулей*/
        'menu_name' => lang('Универсальная форма обратной связи', 'feedback_uni'),
        /*Описание, которое будет выводится в списке всех модулей*/
        'description' => 'Создание и обработка форм обратной связи',
        'admin_type' => 'window', // Open admin class in new window or not. Possible values window/inside
        'window_type' => 'xhr', // Load method. Possible values xhr/iframe
        'w' => 600, // Window width
        'h' => 550, // Window height
        'version' => '0.1 dev.', // Module version
        'author' => 'jamanphonehtc@gmail.com', // Author info
        /*Это, как я подозреваю, иконка, которая будет выводиться в меню*/
        'icon_class' => 'icon-envelope'
    );

    /* End of file module_info.php */