<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tags_file = "../../backend/tags.json";
    
    // Load existing data
    if (file_exists($tags_file)) {
        $tags_data = json_decode(file_get_contents($tags_file), true);
        
        // Get form data
        $index = $_POST['index'];
        $filename = $_POST['filename'];
        $tags = array_map('trim', explode(",", $_POST['tags']));
        $year = $_POST['year'];
        $type = $_POST['type'];
        
        // Update the image data
        if (isset($tags_data[$index]) && $tags_data[$index]['filename'] === $filename) {
            $tags_data[$index]['tags'] = $tags;
            $tags_data[$index]['year'] = $year;
            $tags_data[$index]['type'] = $type;
            
            // Save updated data
            file_put_contents($tags_file, json_encode($tags_data, JSON_PRETTY_PRINT));
        }
    }
    
    // Redirect back to media page
    header("Location: media.php?updated=1");
    exit();
}
?>