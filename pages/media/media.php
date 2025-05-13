<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../backend/meta-include.php'; ?>
    <?php include '../../backend/media-nav.php'; ?>
    <title>Media Gallery</title>
    <style>
        .gallery {
            display: flex;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .image-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }
        .image-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .image-info {
            margin-top: 10px;
        }
        .tags {
            color: #666;
            font-size: 0.9em;
        }
        .edit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .edit-form input[type="text"],
        .edit-form input[type="number"],
        .edit-form select {
            width: 100%;
            padding: 5px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .hidden {
            display: none;
        }
        .toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 1000;
        }
        .info-hidden .image-info {
            display: none;
        }
    </style>
</head>
<body>
    <button id="toggleInfo" class="toggle-btn">Show Info</button>
    <div class="gallery">
        <?php
        $tags_file = "https://emacs-expressions-be-customers.trycloudflare.com/media/tags.json";
        $upload_dir = "https://emacs-expressions-be-customers.trycloudflare.com/media/uploads/";
        
        if (file_exists($tags_file)) {
            $tags_data = json_decode(file_get_contents($tags_file), true);
            
            foreach ($tags_data as $index => $image) {
                echo '<div class="image-card">';
                echo '<img src="' . $upload_dir . $image['filename'] . '" alt="' . $image['filename'] . '">';
                
                // Display info
                echo '<div class="image-info" id="info-' . $index . '">';
                echo '<p>Year: ' . htmlspecialchars($image['year']) . '</p>';
                echo '<p>Type: ' . htmlspecialchars($image['type']) . '</p>';
                echo '<p class="tags">Tags: ' . htmlspecialchars(implode(", ", $image['tags'])) . '</p>';
                echo '<button class="edit-btn" onclick="showEdit(' . $index . ')">Edit</button>';
                echo '</div>';
                
                // Edit form
                echo '<div class="edit-form hidden" id="edit-' . $index . '">';
                echo '<form action="update_media.php" method="POST">';
                echo '<input type="hidden" name="index" value="' . $index . '">';
                echo '<input type="hidden" name="filename" value="' . $image['filename'] . '">';
                echo '<input type="text" name="tags" value="' . htmlspecialchars(implode(", ", $image['tags'])) . '" placeholder="Tags">';
                echo '<input type="number" name="year" value="' . htmlspecialchars($image['year']) . '" min="1900" max="2025">';
                echo '<select name="type">';
                echo '<option value="real" ' . ($image['type'] == 'real' ? 'selected' : '') . '>Real</option>';
                echo '<option value="digital" ' . ($image['type'] == 'digital' ? 'selected' : '') . '>Digital</option>';
                echo '</select>';
                echo '<button type="submit" class="edit-btn">Save</button>';
                echo '<button type="button" class="edit-btn" onclick="hideEdit(' . $index . ')">Cancel</button>';
                echo '</form>';
                echo '</div>';
                
                echo '</div>';
            }
        }
        ?>
    </div>

    <script>
        const gallery = document.querySelector('.gallery');
        const toggleBtn = document.getElementById('toggleInfo');
        let infoVisible = false;

        toggleBtn.addEventListener('click', () => {
            infoVisible = !infoVisible;
            gallery.classList.toggle('info-hidden');
            toggleBtn.textContent = infoVisible ? 'Hide Info' : 'Show Info';
        });

        // Initialize with info hidden
        gallery.classList.add('info-hidden');

        function showEdit(index) {
            document.getElementById('info-' + index).classList.add('hidden');
            document.getElementById('edit-' + index).classList.remove('hidden');
        }

        function hideEdit(index) {
            document.getElementById('info-' + index).classList.remove('hidden');
            document.getElementById('edit-' + index).classList.add('hidden');
        }
    </script>
</body>
</html>