<?php
include "db.php";

// -------------------- Add Book --------------------
if(isset($_POST['add_book'])){
    $title = $_POST['book_title'];
    $author = $_POST['book_author'];
    $category = $_POST['book_category'];
    mysqli_query($conn,"INSERT INTO books (title, author, category) VALUES ('$title','$author','$category')");
}

// -------------------- Add Member --------------------
if(isset($_POST['add_member'])){
    $name = $_POST['member_name'];
    $email = $_POST['member_email'];
    mysqli_query($conn,"INSERT INTO members (name,email) VALUES ('$name','$email')");
}

// -------------------- Delete Book --------------------
if(isset($_GET['delete_book'])){
    $id = $_GET['delete_book'];
    mysqli_query($conn,"DELETE FROM books WHERE id=$id");
}

// -------------------- Delete Member --------------------
if(isset($_GET['delete_member'])){
    $id = $_GET['delete_member'];
    mysqli_query($conn,"DELETE FROM members WHERE id=$id");
}

// -------------------- Issue Book --------------------
if(isset($_POST['issue_book'])){
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $issue_date = $_POST['issue_date'];
    mysqli_query($conn,"INSERT INTO issued_books (book_id, member_id, issue_date) VALUES ($book_id,$member_id,'$issue_date')");
}

// -------------------- Return Book --------------------
if(isset($_GET['return_book'])){
    $id = $_GET['return_book'];
    mysqli_query($conn,"DELETE FROM issued_books WHERE id=$id");
}

// -------------------- Fetch Data --------------------
$books_res = mysqli_query($conn,"SELECT * FROM books");
$books = [];
while($b = mysqli_fetch_assoc($books_res)){ $books[] = $b; }

$members_res = mysqli_query($conn,"SELECT * FROM members");
$members = [];
while($m = mysqli_fetch_assoc($members_res)){ $members[] = $m; }

$issued_res = mysqli_query($conn,"
    SELECT ib.id, b.title AS book, m.name AS member, ib.issue_date
    FROM issued_books ib
    JOIN books b ON ib.book_id=b.id
    JOIN members m ON ib.member_id=m.id
");
$issued_books = [];
while($ib = mysqli_fetch_assoc($issued_res)){ $issued_books[] = $ib; }

// Dashboard counts
$total_books = count($books);
$total_members = count($members);
$total_issued = count($issued_books);

// Active section
$active_section = $_GET['section'] ?? 'dashboard';
?>

<!-- HTML + CSS + Forms -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Management System</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
/* Add all your CSS here (same as previous template) */
*{box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{margin:0;background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;}
header{background:rgba(0,0,0,0.2);color:white;padding:20px;text-align:center;font-size:28px;font-weight:600;}
.layout{display:flex;}
aside{width:220px;background:#1f2937;color:white;min-height:calc(100vh-80px);padding-top:20px;}
aside button{width:100%;padding:15px;background:none;border:none;color:white;font-size:16px;text-align:left;cursor:pointer;transition:background 0.3s;}
aside button:hover{background:#374151;}
main{flex:1;padding:30px;}
.card{background:white;border-radius:12px;padding:25px;box-shadow:0 10px 25px rgba(0,0,0,0.2);animation:fadeIn 0.4s ease-in-out;}
@keyframes fadeIn{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}
h2{margin-top:0;color:#4f46e5;}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;}
.stat-box{padding:20px;border-radius:12px;color:white;font-size:18px;}
.stat-books{background:linear-gradient(135deg,#34d399,#059669);}
.stat-members{background:linear-gradient(135deg,#60a5fa,#2563eb);}
.stat-issued{background:linear-gradient(135deg,#fbbf24,#d97706);}
table{width:100%;border-collapse:collapse;margin-top:15px;}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;}
th{background:#eef2ff;color:#4338ca;text-align:left;}
input,select{width:100%;padding:10px;margin-top:8px;border-radius:8px;border:1px solid #c7d2fe;}
button.submit{margin-top:15px;padding:12px;background:linear-gradient(135deg,#6366f1,#4338ca);border:none;color:white;font-size:16px;border-radius:8px;cursor:pointer;transition:transform 0.2s;}
button.submit:hover{transform:scale(1.03);}
.hidden{display:none;}
footer{text-align:center;color:white;padding:15px;font-size:14px;}
.delete-btn,.return-btn{padding:5px 10px;border:none;border-radius:5px;cursor:pointer;color:white;}
.delete-btn{background-color:#ef4444;}
.return-btn{background-color:#10b981;}
</style>
</head>
<body>
<header>📚 Library Management System</header>
<div class="layout">
<aside>
<button onclick="window.location='?section=dashboard'">🏠 Dashboard</button>
<button onclick="window.location='?section=books'">📘 Books</button>
<button onclick="window.location='?section=members'">👥 Members</button>
<button onclick="window.location='?section=issue'">🔖 Issue Book</button>
</aside>
<main>

<!-- Dashboard -->
<div id="dashboard" class="card <?= $active_section=='dashboard'?'':'hidden' ?>">
<h2>Dashboard Overview</h2>
<div class="stats">
<div class="stat-box stat-books">📘 Total Books: <?= $total_books ?></div>
<div class="stat-box stat-members">👥 Total Members: <?= $total_members ?></div>
<div class="stat-box stat-issued">🔖 Books Issued: <?= $total_issued ?></div>
</div>
</div>

<!-- Books -->
<div id="books" class="card <?= $active_section=='books'?'':'hidden' ?>">
<h2>Books Management</h2>
<table>
<tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Action</th></tr>
<?php foreach($books as $b){ ?>
<tr>
<td><?= $b['id'] ?></td>
<td><?= $b['title'] ?></td>
<td><?= $b['author'] ?></td>
<td><?= $b['category'] ?></td>
<td><a class="delete-btn" href="?delete_book=<?= $b['id'] ?>&section=books">Delete</a></td>
</tr>
<?php } ?>
</table>
<h3>Add New Book</h3>
<form method="POST">
<input type="text" name="book_title" placeholder="Book Title" required>
<input type="text" name="book_author" placeholder="Author" required>
<input type="text" name="book_category" placeholder="Category" required>
<button class="submit" name="add_book">Add Book</button>
</form>
</div>

<!-- Members -->
<div id="members" class="card <?= $active_section=='members'?'':'hidden' ?>">
<h2>Members Management</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
<?php foreach($members as $m){ ?>
<tr>
<td><?= $m['id'] ?></td>
<td><?= $m['name'] ?></td>
<td><?= $m['email'] ?></td>
<td><a class="delete-btn" href="?delete_member=<?= $m['id'] ?>&section=members">Delete</a></td>
</tr>
<?php } ?>
</table>
<h3>Add New Member</h3>
<form method="POST">
<input type="text" name="member_name" placeholder="Member Name" required>
<input type="email" name="member_email" placeholder="Email" required>
<button class="submit" name="add_member">Add Member</button>
</form>
</div>

<!-- Issue Book -->
<div id="issue" class="card <?= $active_section=='issue'?'':'hidden' ?>">
<h2>Issue Book</h2>
<form method="POST">
<label>Book</label>
<select name="book_id" required>
<?php foreach($books as $b){ ?>
<option value="<?= $b['id'] ?>"><?= $b['title'] ?></option>
<?php } ?>
</select>
<label>Member</label>
<select name="member_id" required>
<?php foreach($members as $m){ ?>
<option value="<?= $m['id'] ?>"><?= $m['name'] ?></option>
<?php } ?>
</select>
<label>Issue Date</label>
<input type="date" name="issue_date" required>
<button class="submit" name="issue_book">Issue Book</button>
</form>

<h3>Issued Books</h3>
<table>
<tr><th>Book</th><th>Member</th><th>Issue Date</th><th>Action</th></tr>
<?php foreach($issued_books as $ib){ ?>
<tr>
<td><?= $ib['book'] ?></td>
<td><?= $ib['member'] ?></td>
<td><?= $ib['issue_date'] ?></td>
<td>
<a class="return-btn" href="?return_book=<?= $ib['id'] ?>&section=issue">Return</a>
</td>
</tr>
<?php } ?>
</table>
</div>

</main>
</div>
<footer>Library Management System © 2025</footer>
</body>
</html>
