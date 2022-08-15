<?php

// Functions below are global

function view($view, $data = null)
{
    include_once('view/layout/header.php');
    include_once("view/$view.php");
    include_once('view/layout/footer.php');
}

function view_include($view, $data = null)
{
    include("view/$view.php");
}

function removeSlashesFromStartAndEnd(&$str)
{
    // if $route starts in /, then remove it
    if (substr($str, 0, 1) == '/') {
        $str = substr($str, 1);
    }

    // if $str ends in /, then remove it
    if (substr($str, -1) == '/') {
        $str = substr($str, 0, -1);
    }
}