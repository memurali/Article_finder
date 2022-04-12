<?php
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=client_kw.csv");
$data = array("ranking_keyword", "ranking_position","ranking_page");
$output = fopen("php://output", "wb");
fputcsv($output, $data); // here you can change delimiter/enclosure
fclose($output);
exit;
?>