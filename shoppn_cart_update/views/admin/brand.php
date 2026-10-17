<?php

require_once '../../core/core.php';

require_admin();

require_once '../../controllers/ProductController.php';

$controller = new ProductController();

$brands = $controller->getBrandsWithCount();

$editBrand = false;

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    if (ctype_digit($edit_id) && (int)$edit_id > 0) {
        $editBrand = $controller->getBrandById((int)$edit_id);
    }
}

$page_title = 'Manage brands';
require_once '../layout/header.php';

?>

<div class="page-head">
    <div>
        <h1>Manage brands</h1>
        <p class="muted">Brands you add or rename here show up for customers straight away.</p>
    </div>
    <a class="btn btn-ghost" href="<?php echo url('views/brands.php'); ?>">View as customer</a>
</div>

<?php flash(); ?>

<div class="admin-grid">

    <section class="card">
        <?php if ($editBrand): ?>

            <h2>Edit brand</h2>

            <form action="../../actions/update_brand_action.php" method="POST" class="form">
                <input type="hidden" name="brand_id" value="<?php echo e($editBrand['brand_id']); ?>">

                <div class="field">
                    <label for="name">Brand name</label>
                    <input type="text" name="name" id="name" maxlength="100"
                           value="<?php echo e($editBrand['brand_name']); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a class="btn btn-ghost" href="brand.php">Cancel</a>
                </div>
            </form>

        <?php else: ?>

            <h2>Add a brand</h2>

            <form action="../../actions/add_brand_action.php" method="POST" class="form">
                <div class="field">
                    <label for="name">Brand name</label>
                    <input type="text" name="name" id="name" maxlength="100" required>
                </div>

                <button type="submit" class="btn btn-primary">Add brand</button>
            </form>

        <?php endif; ?>
    </section>

    <section class="card">
        <h2>Existing brands <span class="count"><?php echo count($brands); ?></span></h2>

        <?php if ($brands): ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th class="num">Products</th>
                            <th class="num">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($brands as $brand): ?>
                        <tr<?php echo ($editBrand && $editBrand['brand_id'] == $brand['brand_id']) ? ' class="is-editing"' : ''; ?>>
                            <td>
                                <span class="table-brand">
                                    <span class="monogram monogram-xs" style="--h: <?php echo brand_hue($brand['brand_name']); ?>">
                                        <?php echo e(brand_initials($brand['brand_name'])); ?>
                                    </span>
                                    <?php echo e($brand['brand_name']); ?>
                                </span>
                            </td>
                            <td class="num"><?php echo (int) $brand['product_count']; ?></td>
                            <td class="num">
                                <div class="row-actions">
                                    <a class="btn btn-small btn-ghost" href="brand.php?edit_id=<?php echo (int) $brand['brand_id']; ?>">Edit</a>
                                    <form action="../../actions/delete_brand_action.php" method="POST"
                                          onsubmit="return confirm('Delete the brand \'' + this.dataset.name + '\'? This cannot be undone.');"
                                          data-name="<?php echo e($brand['brand_name']); ?>">
                                        <input type="hidden" name="brand_id" value="<?php echo (int) $brand['brand_id']; ?>">
                                        <button type="submit" class="btn btn-small btn-danger"
                                            <?php if ($brand['product_count'] > 0): ?>disabled title="Has products. Move or remove them first."<?php endif; ?>>Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="muted">No brands yet. Add your first one to get started.</p>
        <?php endif; ?>
    </section>

</div>

<?php require_once '../layout/footer.php'; ?>
