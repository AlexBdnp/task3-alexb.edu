<?php

session_start();

require_once(PATH_ROOT . 'system/Helpers.php');
require_once(PATH_ROOT . 'system/Route.php');
require_once(PATH_ROOT . 'system/database.php');

require_once(PATH_ROOT . 'model/Comment.php');
require_once(PATH_ROOT . 'model/Post.php');

require_once(PATH_ROOT . 'routes/web.php');
require_once(PATH_ROOT . 'routes/404.php');
