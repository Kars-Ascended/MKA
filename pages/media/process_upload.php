<?php
$upload_dir = "../../assets/media/uploads/";
$tags_file = "../../backend/tags.json";

// Create directories if they don't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
if (!file_exists(dirname($tags_file))) {
    mkdir(dirname($tags_file), 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_file = $upload_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is valid
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        die("File is not an image.");
    }
    
    // Move uploaded file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Process tags
        $tags = explode(",", $_POST["tags"]);
        $tags = array_map('trim', $tags);
        
        // Load existing tags
        $tags_data = [];
        if (file_exists($tags_file)) {
            $tags_data = json_decode(file_get_contents($tags_file), true) ?? [];
        }
        
        // Add new image data
        $tags_data[] = [
            "filename" => basename($target_file),
            "tags" => $tags,
            "year" => $_POST["year"],
            "type" => $_POST["type"],
            "uploaded" => date("Y-m-d H:i:s")
        ];
        
        // Save tags
        file_put_contents($tags_file, json_encode($tags_data, JSON_PRETTY_PRINT));
        
        header("Location: media.php?success=1");
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>