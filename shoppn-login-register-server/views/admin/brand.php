<?php

require_once '../../core/core.php';

require_admin();

require_once '../../controllers/ProductController.php';

$controller = new ProductController();

$brands = $controller->getAllBrands();

$editBrand = false;

if (isset($_GET['edit_id'])) {

    $edit_id = $_GET['edit_id'];

    if (ctype_digit($edit_id) && (int)$edit_id > 0) {

        $editBrand = $controller->getBrandById((int)$edit_id);
    }
}

require_once '../layout/header.php';

?>

<h2>Manage Brands</h2>

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

<?php if ($editBrand): ?>

    <h3>Edit Brand</h3>

    <form action="../../actions/update_brand_action.php" method="POST">

        <input
            type="hidden"
            name="brand_id"
            value="<?php echo htmlspecialchars($editBrand['brand_id']); ?>"
        >

        <label for="name">Brand Name:</label>

        <input
            type="text"
            name="name"
            id="name"
            value="<?php echo htmlspecialchars($editBrand['brand_name']); ?>"
            required
        >

        <button type="submit">Update Brand</button>

        <a href="brand.php">Cancel</a>

    </form>

<?php else: ?>

    <h3>Add Brand</h3>

    <form action="../../actions/add_brand_action.php" method="POST">

        <label for="name">Brand Name:</label>

        <input
            type="text"
            name="name"
            id="name"
            required
        >

        <button type="submit">Add Brand</button>

    </form>

<?php endif; ?>


<h3>Existing Brands</h3>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Brand Name</th>
        <th>Action</th>
    </tr>

    <?php foreach ($brands as $brand): ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($brand['brand_id']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($brand['brand_name']); ?>
            </td>

            <td>
                <a href="brand.php?edit_id=<?php echo $brand['brand_id']; ?>">
                    Edit
                </a>
            </td>

        </tr>

    <?php endforeach; ?>

</table>

<?php

require_once '../layout/footer.php';

?>