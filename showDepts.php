<?php
session_start();
include 'script/db_connection.php';     // adjust path as needed

/* ────────── SECURITY ────────── */
if (!isset($_SESSION['admin_Id']) || !isset($_SESSION['hospital_Id'])) {
    die("Please log in first.");
}
$hospitalId = $_SESSION['hospital_Id'];

/* ────────── FETCH ALL DEPARTMENTS FOR THIS HOSPITAL ────────── */
$stmt = $conn->prepare(
    "SELECT dept_Id, dept_Name, dept_Email, dept_Phone, '' AS dept_Password,
            dept_Description
     FROM departments
     WHERE hospital_Id = ?
     ORDER BY dept_Name"
);
$stmt->bind_param("i", $hospitalId);
$stmt->execute();
$departments = $stmt->get_result();

?>

<?php if ($departments->num_rows === 0): ?>
    <p>No departments yet. <a href="../add_department_form.php">Add the first one</a>.</p>
<?php endif; ?>

<?php while ($row = $departments->fetch_assoc()): ?>
    <form class="dept-card" action="update_department.php" method="post">
        <input type="hidden" name="dept_id" value="<?= $row['dept_Id']; ?>">

        <label>Department Name:</label>
        <input type="text" name="dept_name" value="<?= htmlspecialchars($row['dept_Name']); ?>" required>

        <label>Department Email:</label>
        <input type="email" name="dept_email" value="<?= htmlspecialchars($row['dept_Email']); ?>" required>

        <label>Phone:</label>
        <input type="text" name="dept_phone" value="<?= htmlspecialchars($row['dept_Phone']); ?>" required>

        <label>New Password (leave blank to keep existing):</label>
        <input type="password" name="dept_password" placeholder="••••••••">

        <label>Description:</label>
        <input type="text" name="dept_description"
               value="<?= htmlspecialchars($row['dept_Description']); ?>" required>

        <button type="submit">Update Department</button>
    </form>
<?php endwhile; $stmt->close(); $conn->close(); ?>