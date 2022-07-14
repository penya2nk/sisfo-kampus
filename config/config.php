<?php
define("VERSION", "3.5");

if (!defined("DS")) {
    define("DS", DIRECTORY_SEPARATOR);
}

if (!defined("HOME_FOLDER")) {
    $home = $_SERVER["SCRIPT_FILENAME"];
    $path_home = str_replace("index.php", '', $home);
    define("HOME_FOLDER", trim($path_home));
    set_include_path(get_include_path() . PATH_SEPARATOR . HOME_FOLDER);
}

if (!defined("MODULES_FOLDER")) {
    define("MODULES_FOLDER", HOME_FOLDER . DS . "modules");
}

if (!defined("IMAGES")) {
    define ("IMAGES", HOME_FOLDER . DS . "themes" . DS . $themes . DS . "icon");
}

include_once(HOME_FOLDER . DS . "library" . DS . "sg.lib.php");
if (!checkInstallation()) {
    header("Location: install.php");
    exit;
}

$folder = array("config", "script", "include", "library");
includeFolder($folder);

include_once("pengembang.lib.php");

includeModules(MODULES_FOLDER);
?>