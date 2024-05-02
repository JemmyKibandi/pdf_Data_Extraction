<?php session_start();

require '../../vendor/autoload.php';
use Smalot\PdfParser\Parser;
function extractTextFromPDF($pdfUrl)
{
    // Create an instance of the PDF parser
    $parser = new Parser();

    try {
        // Parse the PDF and extract text content
        $pdf = $parser->parseFile($pdfUrl);
        $text = $pdf->getText();

        // Return the extracted text content
        return $text;
    } catch (\Exception $e) {
        // Handle any exceptions (e.g., file not found, parsing errors)
        return 'Error: ' . $e->getMessage();
    }
}
function generateExcerpt($text, $searchTerm, $excerptLength = 100)
{
    // Find position of the search term in the text
    $pos = stripos($text, $searchTerm);

    // If search term not found, return empty excerpt
    if ($pos === false) {
        return '';
    }

    // Calculate start and end positions of the excerpt
    $startPos = max(0, $pos - ($excerptLength / 2));
    $endPos = min(strlen($text), $pos + ($excerptLength / 2));

    // Extract excerpt from the text
    $excerpt = substr($text, $startPos, $endPos - $startPos);

    // Add ellipsis if excerpt does not start at the beginning of the text
    if ($startPos > 0) {
        $excerpt = '...' . $excerpt;
    }

    // Add ellipsis if excerpt does not end at the end of the text
    if ($endPos < strlen($text)) {
        $excerpt .= '...';
    }
    // Highlight search term in the excerpt
    $excerpt = preg_replace('/' . preg_quote($searchTerm, '/') . '/i', '$0', $excerpt);

    // Return generated excerpt
    return $excerpt;
}
