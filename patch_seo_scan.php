<?php
$content = file_get_contents('app/Console/Commands/AutonomousSeoScan.php');

$startMock = strpos($content, '$mockParsed = [');
if ($startMock !== false) {
    // We want to replace everything in the catch block inside the loop
    // Let's just use string replacement carefully.
}
