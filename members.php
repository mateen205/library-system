<?php
include "db.php";

/* ADD MEMBER */
if (isset($_POST['add_member'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];

    $query = "INSERT INTO members (name, email)
              VALUES ('$name', '$email')";

    mysqli_query($conn, $query);
}

/* FETCH MEMBERS */
$result = mysqli_query($conn, "SELECT * FROM members");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Members</title>
</head>
<body>

<h2>Members</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['email']; ?></td>
    </tr>
    <?php } ?>
</table>

<h3>Add New Member</h3>

<form method="POST">
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <button type="submit" name="add_member">Add Member</button>
</form>

</body>
</html>
