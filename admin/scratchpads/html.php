<?php
$_POST['html'] = '<script>alert("yay");</script><b>Hello</b>';
$_POST['cars']['cats'] = '<script>alert("hello");</script><b>Hello</b>';
print_r($_POST);
require 'bootstrap.php'; //it gets sanitized automatically through index.php
print_r($_POST);