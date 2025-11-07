<?php
require_once __DIR__ . '/../core/db_connect.php';
require_once __DIR__ . '/../templates/admin_header.php';

$sql = "SELECT id, username, created_at FROM admins";
$result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">Admin Users</h1>
        <a href="add_user.php" class="btn btn-primary">Add New User</a>
    </div>
    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>";
                            echo "<a href='edit_user.php?id=". $row['id'] ."' class='btn btn-primary btn-sm'>Edit</a>";
                            echo "&nbsp;";
                            echo "<a href='delete_user.php?id=". $row['id'] ."' class='btn btn-danger btn-sm'>Delete</a>";
                        echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
mysqli_close($conn);
require_once __DIR__ . '/../templates/admin_footer.php';
?>
