<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // File handling
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileName = basename($_FILES['file']['name']);
    $targetDir = "uploads/";
    $targetFile = $targetDir . $fileName;

    // Move the uploaded file to the temp location
    move_uploaded_file($fileTmp, $targetFile);

    // Define paths
    $outputFile = $targetDir . "modified_" . $fileName;
    $coverArt = "Babysbyberry.png"; // Cover art file must exist in the same folder

    // Check if cover art exists
    if (!file_exists($coverArt)) {
        die("Cover art file not found.");
    }

    // Use FFmpeg to embed the image into the audio file
    $cmd = "ffmpeg -i " . escapeshellarg($targetFile) . 
           " -i " . escapeshellarg($coverArt) . 
           " -map 0 -map 1 -c copy -id3v2_version 3 -metadata:s:v title=\"Album cover\" -metadata:s:v comment=\"Cover (front)\" " . 
           escapeshellarg($outputFile);

    shell_exec($cmd);

    // Serve the modified file as a download
    if (file_exists($outputFile)) {
        header('Content-Type: audio/mpeg');
        header('Content-Disposition: attachment; filename="' . basename($outputFile) . '"');
        header('Content-Length: ' . filesize($outputFile));
        readfile($outputFile);

        // Optional: delete the temp files after download
        unlink($targetFile);
        unlink($outputFile);
        exit;
    } else {
        echo "Failed to process file.";
    }
}
?>