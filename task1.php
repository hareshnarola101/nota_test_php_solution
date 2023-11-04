<?php

// Directory where the files are located
$directory = '/datafiles';

// Regular expression pattern to match files with names consisting of numbers and letters
$pattern = '/^[a-zA-Z0-9]+\.ixt$/';

// Get the list of files in the directory
$files = scandir($directory);

// Filter files based on the pattern
$filteredFiles = preg_grep($pattern, $files);

// Sort the filtered files by name
sort($filteredFiles);

// Display the names of the filtered files
foreach ($filteredFiles as $file) {
    echo $file . "\n";
}
?>
