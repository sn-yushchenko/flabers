<?php
function __autoload($class){
    include($class.".php");
}
?>