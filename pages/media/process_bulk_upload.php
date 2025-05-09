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
    // Load existing tags
    $tags_data = [];
    if (file_exists($tags_file)) {
        $tags_data = json_decode(file_get_contents($tags_file), true) ?? [];
    }

    // Process tags once for all images
    $tags = explode(",", $_POST["bulk_tags"]);
    $tags = array_map('trim', $tags);
    $year = $_POST["bulk_year"];
    $type = $_POST["bulk_type"];

    // Process each uploaded file
    $files = $_FILES["images"];
    $success_count = 0;
    $error_count = 0;

    for ($i = 0; $i < count($files["name"]); $i++) {
        $target_file = $upload_dir . basename($files["name"][$i]);
        
        // Check if image file is valid
        $check = getimagesize($files["tmp_name"][$i]);
        if($check !== false) {
            // Move uploaded file
            if (move_uploaded_file($files["tmp_name"][$i], $target_file)) {
                // Add new image data
                $tags_data[] = [
                    "filename" => basename($files["name"][$i]),
                    "tags" => $tags,
                    "year" => $year,
                    "type" => $type,
                    "uploaded" => date("Y-m-d H:i:s")
                ];
                $success_count++;
            } else {
                $error_count++;
            }
        } else {
            $error_count++;
        }
    }

    // Save updated tags
    if ($success_count > 0) {
        file_put_contents($tags_file, json_encode($tags_data, JSON_PRETTY_PRINT));
    }

    // Redirect with status
    header("Location: media.php?success=$success_count&errors=$error_count");
}
?>