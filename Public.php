<?php
// sender.php (Server-side upload handler)
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';  // Directory to store uploaded files
    $uploadFile = $uploadDir . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        echo json_encode(['filePath' => $uploadFile]);
    } else {
        echo 'File upload failed';
    }
}
?>
