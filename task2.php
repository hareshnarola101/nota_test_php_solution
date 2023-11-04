<?php

try {
    // Define the URL of the web page to download
    $url = 'https://www.wikipedia.org/';

    // Create a new DOMDocument instance to parse the HTML content
    $dom = new DOMDocument();
    @$dom->loadHTMLFile($url); // '@' symbol suppresses warnings for potentially malformed HTML

    // Define the database connection details
    $dsn = 'mysql:host=localhost;dbname=your_database';
    $username = 'your_username';
    $password = 'your_password';

    // Create a PDO instance for the database connection
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define and execute SQL query to create the wiki_sections table
    $sqlCreateTable = "CREATE TABLE IF NOT EXISTS wiki_sections (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date_created DATETIME,
        title VARCHAR(230),
        url VARCHAR(240) UNIQUE,
        picture VARCHAR(240) UNIQUE,
        abstract VARCHAR(256) UNIQUE
    )";
    $pdo->exec($sqlCreateTable);

    // Extract data from the web page using DOMDocument
    $headings = $dom->getElementsByTagName('h2'); // Assuming headings are in h2 tags
    $abstracts = $dom->getElementsByTagName('p');  // Assuming abstracts are in p tags
    $images = $dom->getElementsByTagName('img');   // Assuming images are in img tags
    $links = $dom->getElementsByTagName('a');      // Assuming links are in a tags

    // Initialize arrays to store the extracted data
    $extractedHeadings = [];
    $extractedAbstracts = [];
    $extractedImages = [];
    $extractedLinks = [];

    // Loop through the DOM elements and extract data
    foreach ($headings as $heading) {
        $extractedHeadings[] = $heading->textContent;
    }

    foreach ($abstracts as $abstract) {
        $extractedAbstracts[] = $abstract->textContent;
    }

    foreach ($images as $image) {
        $extractedImages[] = $image->getAttribute('src');
    }

    foreach ($links as $link) {
        $extractedLinks[] = $link->getAttribute('href');
    }

    // Insert the extracted data into the database table using prepared statements
    foreach ($extractedHeadings as $key => $heading) {
        $dateCreated = date("Y-m-d H:i:s");
        $title = $heading;
        $url = isset($extractedLinks[$key]) ? $extractedLinks[$key] : '';
        $picture = isset($extractedImages[$key]) ? $extractedImages[$key] : '';
        $abstract = isset($extractedAbstracts[$key]) ? $extractedAbstracts[$key] : '';

        $sqlInsert = "INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sqlInsert);
        $stmt->execute([$dateCreated, $title, $url, $picture, $abstract]);
    }

    // Close the database connection
    $pdo = null;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

?>
