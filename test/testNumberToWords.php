<?php
function numberToWords($amount) {
    $units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
    $teens = ["Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
    $tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
    $scales = ["", "Thousand", "Million", "Billion"];


    if ($amount == 0) {
        return "Zero Dollars";
    }

    $dollars = floor($amount);
    $cents = round(($amount - $dollars) * 100);

    $words = "";
    $scaleIndex = 0;

    // Process dollars in chunks of 3 digits
    while ($dollars > 0) {
        $chunk = $dollars % 1000;
        if ($chunk > 0) {
            $chunkWords = convertChunk($chunk, $units, $teens, $tens);
            $chunkScale = $scales[$scaleIndex];
            $words = trim($chunkWords . " " . $chunkScale) . " " . $words;
        }
        $dollars = floor($dollars / 1000);
        $scaleIndex++;
    }

    $words = trim($words) . " Dollars";

    // Process cents
    if ($cents > 0) {
        $words .= " and " . convertChunk($cents, $units, $teens, $tens) . " Cents";
    }

    return $words;
}

    // Convert a three-digit chunk into words
    function convertChunk($number, $units, $teens, $tens) {
        $chunkText = "";

        if ($number >= 100) {
            $chunkText .= $units[floor($number / 100)] . " Hundred ";
            $number %= 100;
        }

        if ($number >= 11 && $number <= 19) {
            $chunkText .= $teens[$number - 11] . " ";
        } elseif ($number >= 10) {
            $chunkText .= $tens[floor($number / 10)] . " ";
            $number %= 10;
        }

        if ($number > 0) {
            $chunkText .= $units[$number] . " ";
        }

        return trim($chunkText);
    }


// Test example
$amount = 1217.56;
$amountWords = numberToWords($amount);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Printable Check</title>
</head>
<body>
    <div>
        <p>Amount: $<?= number_format($amount, 2) ?></p>
        <p>Amount in Words: <?= $amountWords ?></p>
    </div>
</body>
</html>
