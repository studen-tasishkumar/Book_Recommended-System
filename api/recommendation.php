<?php
$csvFile = 'Book11.csv';

function readBooksFromCSV($csvFile) {
    $books = [];
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $books[] = [
                'title' => $data[0],
                'genre' => $data[1],
                'author' => $data[2],
                'keywords' => $data[3]
            ];
        }
        fclose($handle);
    }
    return $books;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookInput = strtolower(htmlspecialchars($_POST['book']));
    $booksData = readBooksFromCSV($csvFile);

    $matchedRecommendations = array_filter($booksData, function($book) use ($bookInput) {
        return stripos($book['genre'], $bookInput) !== false || stripos($book['keywords'], $bookInput) !== false;
    });

    header('Content-Type: application/json');
    echo json_encode(array_values($matchedRecommendations));
}
