<?php
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=client_page.csv");
$data = array("url","page_title","quality_score","internal_links","action");
$output = fopen("php://output", "wb");
fputcsv($output, $data); // here you can change delimiter/enclosure
fclose($output);
exit;
?>