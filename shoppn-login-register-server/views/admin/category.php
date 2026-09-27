<?php

require_once '../../core/core.php';

require_admin();

require_once '../../controllers/ProductController.php';

$controller = new ProductController();

$categories = $controller->getAllCategories();

$editCategory = null;

if (isset($_GET['edit_id'])) {
    foreach ($categories as $category) {
        if ($category['cat_id'] == intval($_GET['edit_id'])) {
            $editCategory = $category;
            break;
        }
    }
}

require_once '../layout/header.php';

?>

<h2>Manage Categories</h2>

<?php

if (isset($_SESSION['success'])) {
    echo '<p>' . htmlspecialchars($_SESSION['success']) . '</p>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<p>' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}

?>

<?php if ($editCategory): ?>

<h3>Edit Category</h3>

<form action="../../actions/update_category_action.php" method="POST">

    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editCategory['cat_id']); ?>">

    <label for="edit_name">Category Name:</label>
    <input type="text" name="name" id="edit_name" value="<?php echo htmlspecialchars($editCategory['cat_name']); ?>" required>

    <button type="submit">Update Category</button>

</form>

<?php else: ?>

<h3>Add Category</h3>

<form action="../../actions/add_category_action.php" method="POST">

    <label for="name">Category Name:</label>
    <input type="text" name="name" id="name" required>

    <button type="submit">Add Category</button>

</form>

<?php endif; ?>

<h3>Existing Categories</h3>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th>Action</th>
    </tr>

    <?php foreach ($categories as $category): ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($category['cat_id']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($category['cat_name']); ?>
            </td>

            <td>
                <a href="category.php?edit_id=<?php echo $category['cat_id']; ?>">
                    Edit
                </a>
            </td>

        </tr>

    <?php endforeach; ?>

</table>

<?php

require_once '../layout/footer.php';

?>