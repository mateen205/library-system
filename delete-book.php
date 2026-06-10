<?php
include "db.php";

$id = $_GET['id'];

$query = "DELETE FROM books WHERE id = $id";

if (mysqli_query($conn, $query)) {
    header("Location: view_books.php");
} else {
    echo "Error deleting record";
}
?>
