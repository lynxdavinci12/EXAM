<?php
include "./classes/Log.php";
$name = $_REQUEST['name'] ?? "";
$type = $_REQUEST['type'] ?? "";
$years = $_REQUEST['years'] ?? "";
$colors = $_REQUEST['colors'] ?? "";

Log::logWrite("Bonjour $type $name dont l'année est $years et la couleur $colors");
