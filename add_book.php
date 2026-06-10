<?php
include "db.php";

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];

    $query = "INSERT INTO books (title, author, isbn)
              VALUES ('$title', '$author', '$isbn')";

    if (mysqli_query($conn, $query)) {
        echo "Book added successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<form method="POST">
    <input type="text" name="title" placeholder="Book Title" required><br><br>
    <input type="text" name="author" placeholder="Author" required><br><br>
    <input type="text" name="isbn" placeholder="ISBN" required><br><br>
    <button type="submit" name="submit">Add Book</button>
</form>
