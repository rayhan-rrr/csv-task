<?php

$filepath = realpath(dirname(__FILE__));

spl_autoload_register(function ($classes) {

    include 'classes/'.$classes.".php";
});

$data = new Data();
