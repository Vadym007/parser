<?php

// подключаем файлы ядра
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'components/Database.php';
require_once 'components/Parser.php';
require_once 'core/route.php';

Route::start(); // запускаем маршрутизатор
