<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../backend/meta-include.php'; ?>
    <?php include '../../backend/media-nav.php'; ?>
    <title>Upload Media</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .upload-form {
            width: 500px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Single Upload Form -->
        <div class="upload-form">
            <h2 class="form-title">Single Upload</h2>
            <form action="process_upload.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Select Image:</label>
                    <input type="file" name="image" id="image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="tags">Tags (comma separated):</label>
                    <input type="text" name="tags" id="tags" placeholder="show, landscape, mask, portrait, solo, duo, etc..." required>
                </div>

                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" name="year" id="year" min="1900" max="2025" required>
                </div>

                <div class="form-group">
                    <label for="type">Type:</label>
                    <select name="type" id="type" required>
                        <option value="">Select type</option>
                        <option value="real">Real</option>
                        <option value="digital">Digital</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" value="Upload Single">
                </div>
            </form>
        </div>

        <!-- Bulk Upload Form -->
        <div class="upload-form">
            <h2 class="form-title">Bulk Upload</h2>
            <form action="process_bulk_upload.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="images">Select Multiple Images:</label>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple required>
                </div>

                <div class="form-group">
                    <label for="bulk_tags">Tags (comma separated):</label>
                    <input type="text" name="bulk_tags" id="bulk_tags" placeholder="show, landscape, mask, portrait, solo, duo, etc..." required>
                </div>

                <div class="form-group">
                    <label for="bulk_year">Year:</label>
                    <input type="number" name="bulk_year" id="bulk_year" min="1900" max="2025" required>
                </div>

                <div class="form-group">
                    <label for="bulk_type">Type:</label>
                    <select name="bulk_type" id="bulk_type" required>
                        <option value="">Select type</option>
                        <option value="real">Real</option>
                        <option value="digital">Digital</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" value="Upload All">
                </div>
            </form>
        </div>
    </div>
</body>
</html>