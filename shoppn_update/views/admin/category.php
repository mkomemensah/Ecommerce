<?php

require_once '../../core/core.php';

require_admin();

require_once '../../controllers/ProductController.php';

$controller = new ProductController();

$categories = $controller->getCategoriesWithCount();

$editCategory = null;

if (isset($_GET['edit_id'])) {
    foreach ($categories as $category) {
        if ($category['cat_id'] == intval($_GET['edit_id'])) {
            $editCategory = $category;
            break;
        }
    }
}

$page_title = 'Manage categories';
require_once '../layout/header.php';

?>

<div class="page-head">
    <div>
        <h1>Manage categories</h1>
        <p class="muted">Categories you add here appear in the store sidebar.</p>
    </div>
</div>

<?php flash(); ?>

<div class="admin-grid">

    <section class="card">
        <?php if ($editCategory): ?>

            <h2>Edit category</h2>

            <form action="../../actions/update_category_action.php" method="POST" class="form">
                <input type="hidden" name="id" value="<?php echo e($editCategory['cat_id']); ?>">

                <div class="field">
                    <label for="edit_name">Category name</label>
                    <input type="text" name="name" id="edit_name" value="<?php echo e($editCategory['cat_name']); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a class="btn btn-ghost" href="category.php">Cancel</a>
                </div>
            </form>

        <?php else: ?>

            <h2>Add a category</h2>

            <form action="../../actions/add_category_action.php" method="POST" class="form">
                <div class="field">
                    <label for="name">Category name</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <button type="submit" class="btn btn-primary">Add category</button>
            </form>

        <?php endif; ?>
    </section>

    <section class="card">
        <h2>Existing categories <span class="count"><?php echo count($categories); ?></span></h2>

        <?php if ($categories): ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="num">Products</th>
                            <th class="num">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr<?php echo ($editCategory && $editCategory['cat_id'] == $category['cat_id']) ? ' class="is-editing"' : ''; ?>>
                            <td><?php echo e($category['cat_name']); ?></td>
                            <td class="num"><?php echo (int) $category['product_count']; ?></td>
                            <td class="num">
                                <div class="row-actions">
                                    <a class="btn btn-small btn-ghost" href="category.php?edit_id=<?php echo (int) $category['cat_id']; ?>">Edit</a>
                                    <form action="../../actions/delete_category_action.php" method="POST"
                                          onsubmit="return confirm('Delete the category \'' + this.dataset.name + '\'? This cannot be undone.');"
                                          data-name="<?php echo e($category['cat_name']); ?>">
                                        <input type="hidden" name="id" value="<?php echo (int) $category['cat_id']; ?>">
                                        <button type="submit" class="btn btn-small btn-danger"
                                            <?php if ($category['product_count'] > 0): ?>disabled title="Has products. Move or remove them first."<?php endif; ?>>Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="muted">No categories yet. Add your first one to get started.</p>
        <?php endif; ?>
    </section>

</div>

<?php require_once '../layout/footer.php'; ?>
