<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Search</title>
</head>
<body>

    <form action="/visual-search" method="post" enctype="multipart/form-data">
        @csrf
        <label for="image">Upload Image:</label>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Search</button>
    </form>

</body>
</html>
