<?php
$csvFile = 'books.csv';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Recommendation System</title>
    <link rel="stylesheet" href="style.css"> <!-- For custom styling -->
</head>
<body>
    <div class="container">
        <h1>Book Recommendation System</h1>
        <p>Enter a book keyword or genre to get recommendations.</p>
        
        <form id="recommendationForm" method="POST">
            <input type="text" id="bookInput" name="book" placeholder="Enter Keyword or Genre" required>
            <button type="button" id="submitBtn">Get Recommendation</button>
        </form>

        <div id="recommendations">
            <h2>Recommendations:</h2>
            <table id="resultsTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Author</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Results will appear here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById("submitBtn").addEventListener("click", function() {
            const bookInput = document.getElementById("bookInput").value;
            
            if (bookInput.trim() === "") {
                alert("Please enter a keyword or genre.");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "recommendation.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const recommendations = JSON.parse(xhr.responseText);
                    const tableBody = document.querySelector("#resultsTable tbody");

                    tableBody.innerHTML = ""; // Clear previous results

                    if (Array.isArray(recommendations) && recommendations.length > 0) {
                        recommendations.forEach(function(book) {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${book.title}</td>
                                <td>${book.genre}</td>
                                <td>${book.author}</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        const noResultRow = document.createElement("tr");
                        noResultRow.innerHTML = `<td colspan="3" class="no-result">No recommendations found for '${bookInput}'.</td>`;
                        tableBody.appendChild(noResultRow);
                    }
                }
            };

            xhr.send("book=" + encodeURIComponent(bookInput));
        });
    </script>
</body>
</html>
