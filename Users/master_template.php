<?php
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=master.csv");
$data = array("Keyword", "Volume");
$output = fopen("php://output", "wb");
fputcsv($output, $data); // here you can change delimiter/enclosure
fclose($output);
exit;
?>