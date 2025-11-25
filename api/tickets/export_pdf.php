<?php
require "../../config.php";
header("Content-Type: application/pdf");

if (!isset($_GET["id"])) die("NO_TICKET_ID");

$ticket_id = intval($_GET["id"]);

$stmt = $db->prepare("SELECT * FROM ticket_logs WHERE ticket_id=? ORDER BY id ASC");
$stmt->execute([$ticket_id]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* PDF START */
$pdf = "%PDF-1.4\n";
$content = "Ticket #$ticket_id Log Export\n\n";

foreach ($logs as $l) {
    $content .= 
        "[".$l["created_at"]."] ".
        $l["user_id"]." – ".
        $l["action"].": ".
        $l["details"]."\n\n";
}

$stream = "<< /Length " . strlen($content) . " >>\nstream\n$content\nendstream\n";

$pdf .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
$pdf .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
$pdf .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R >>\nendobj\n";
$pdf .= "4 0 obj\n$stream\nendobj\nxref\n0 5\n0000000000 65535 f \n";
$pdf .= "trailer << /Size 5 /Root 1 0 R >>\nstartxref\n" . strlen($pdf) . "\n%%EOF";

echo $pdf;
