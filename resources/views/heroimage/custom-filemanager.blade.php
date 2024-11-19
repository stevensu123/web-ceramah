<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <script>
        window.onload = function() {
            // Ambil parameter folder dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const folder = urlParams.get('folder');

            // Jika ada folder, redirect ke URL file manager dengan folder
            if (folder) {
                window.location.href = '/laravel-filemanager?folder=' + folder;
            } else {
                // Jika tidak ada folder, redirect ke file manager default
                window.location.href = '/laravel-filemanager';
            }
        };
    </script>
</head>
<body>
    <p>Redirecting to File Manager...</p>
</body>
</html>
