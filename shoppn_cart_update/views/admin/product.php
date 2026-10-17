<?php

require_once '../../core/core.php';

require_admin();

require_once '../../controllers/ProductController.php';

$controller = new ProductController();

$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();
$products   = $controller->getAllProducts();

$editProduct = false;

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    if (ctype_digit($edit_id) && (int) $edit_id > 0) {
        $editProduct = $controller->getProductById((int) $edit_id);
    }
}

$page_title = 'Manage products';
require_once '../layout/header.php';

$imgUrl = function ($file) {
    return url('images/products/' . rawurlencode($file));
};

?>

<div class="page-head">
    <div>
        <h1>Manage products</h1>
        <p class="muted">Products you add, edit or delete here show up for customers straight away.</p>
    </div>
    <a class="btn btn-ghost" href="<?php echo url('index.php'); ?>">View as customer</a>
</div>

<?php flash(); ?>

<?php if (!$categories || !$brands): ?>
    <div class="alert alert-error">
        You need at least one
        <?php echo !$categories ? '<a href="category.php">category</a>' : ''; ?>
        <?php echo (!$categories && !$brands) ? 'and' : ''; ?>
        <?php echo !$brands ? '<a href="brand.php">brand</a>' : ''; ?>
        before you can add a product.
    </div>
<?php endif; ?>

<div class="admin-grid">

    <section class="card">
        <?php if ($editProduct): ?>
            <h2>Edit product</h2>
            <form id="productForm" action="../../actions/update_product_action.php" method="POST"
                  enctype="multipart/form-data" class="form" novalidate>
                <input type="hidden" name="product_id" value="<?php echo (int) $editProduct['product_id']; ?>">
                <input type="hidden" name="existing_image" value="<?php echo e($editProduct['product_image']); ?>">
        <?php else: ?>
            <h2>Add a product</h2>
            <form id="productForm" action="../../actions/add_product_action.php" method="POST"
                  enctype="multipart/form-data" class="form" novalidate>
        <?php endif; ?>

            <div class="field">
                <label for="product_title">Product title</label>
                <input type="text" name="product_title" id="product_title" maxlength="200" required
                       value="<?php echo $editProduct ? e($editProduct['product_title']) : ''; ?>">
                <span class="field-error" id="titleError"></span>
            </div>

            <div class="field">
                <label for="product_cat">Category</label>
                <select name="product_cat" id="product_cat" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?php echo (int) $c['cat_id']; ?>"
                            <?php echo ($editProduct && (int) $editProduct['product_cat'] === (int) $c['cat_id']) ? 'selected' : ''; ?>>
                            <?php echo e($c['cat_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="catError"></span>
            </div>

            <div class="field">
                <label for="product_brand">Brand</label>
                <select name="product_brand" id="product_brand" required>
                    <option value="">Select a brand</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?php echo (int) $b['brand_id']; ?>"
                            <?php echo ($editProduct && (int) $editProduct['product_brand'] === (int) $b['brand_id']) ? 'selected' : ''; ?>>
                            <?php echo e($b['brand_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="brandError"></span>
            </div>

            <div class="field">
                <label for="product_price">Price (GH&#8373;)</label>
                <input type="number" name="product_price" id="product_price" min="0" step="0.01" required
                       value="<?php echo $editProduct ? e($editProduct['product_price']) : ''; ?>">
                <span class="field-error" id="priceError"></span>
            </div>

            <div class="field">
                <label for="product_desc">Description <span class="optional">(optional)</span></label>
                <textarea name="product_desc" id="product_desc" rows="4" maxlength="500"><?php echo $editProduct ? e($editProduct['product_desc']) : ''; ?></textarea>
            </div>

            <div class="field">
                <label for="product_keywords">Keywords <span class="optional">(optional)</span></label>
                <input type="text" name="product_keywords" id="product_keywords" maxlength="100"
                       value="<?php echo $editProduct ? e($editProduct['product_keywords']) : ''; ?>">
            </div>

            <div class="field">
                <label for="product_image">Product image<?php echo $editProduct ? ' <span class="optional">(leave empty to keep current)</span>' : ''; ?></label>
                <?php if ($editProduct && !empty($editProduct['product_image'])): ?>
                    <p><img src="<?php echo $imgUrl($editProduct['product_image']); ?>" alt="Current image"
                            style="max-width:160px;border-radius:8px;"></p>
                <?php endif; ?>
                <input type="file" name="product_image" id="product_image"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       <?php echo $editProduct ? '' : 'required'; ?>>
                <span class="field-hint">JPG, PNG, GIF or WebP, up to 2 MB.</span>
                <span class="field-error" id="imageError"></span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $editProduct ? 'Save changes' : 'Add product'; ?></button>
                <?php if ($editProduct): ?>
                    <a class="btn btn-ghost" href="product.php">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section class="card">
        <h2>Existing products <span class="count"><?php echo count($products); ?></span></h2>

        <?php if ($products): ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th class="num">Price</th>
                            <th class="num">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr<?php echo ($editProduct && $editProduct['product_id'] == $p['product_id']) ? ' class="is-editing"' : ''; ?>>
                            <td>
                                <?php if (!empty($p['product_image'])): ?>
                                    <img src="<?php echo $imgUrl($p['product_image']); ?>" alt=""
                                         style="width:36px;height:36px;object-fit:cover;border-radius:6px;vertical-align:middle;margin-right:8px;">
                                <?php endif; ?>
                                <?php echo e($p['product_title']); ?>
                            </td>
                            <td><?php echo e($p['cat_name']); ?></td>
                            <td><?php echo e($p['brand_name']); ?></td>
                            <td class="num"><?php echo number_format((float) $p['product_price'], 2); ?></td>
                            <td class="num">
                                <div class="row-actions">
                                    <a class="btn btn-small btn-ghost" href="product.php?edit_id=<?php echo (int) $p['product_id']; ?>">Edit</a>
                                    <form action="../../actions/delete_product_action.php" method="POST"
                                          data-name="<?php echo e($p['product_title']); ?>"
                                          onsubmit="return confirm('Delete \'' + this.dataset.name + '\'? This cannot be undone.');">
                                        <input type="hidden" name="product_id" value="<?php echo (int) $p['product_id']; ?>">
                                        <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="muted">No products yet. Add your first one to get started.</p>
        <?php endif; ?>
    </section>

</div>

<script>
// Client-side checks are for convenience only. The PHP actions re-check everything.
(function () {
    var form = document.getElementById('productForm');
    var isEdit = <?php echo $editProduct ? 'true' : 'false'; ?>;
    var allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    var maxSize = 2 * 1024 * 1024;

    function set(id, msg) { document.getElementById(id).textContent = msg; return msg === ''; }

    form.addEventListener('submit', function (ev) {
        var ok = true;
        ok = set('titleError', form.product_title.value.trim() === '' ? 'Title is required.' : '') && ok;
        ok = set('catError', form.product_cat.value === '' ? 'Choose a category.' : '') && ok;
        ok = set('brandError', form.product_brand.value === '' ? 'Choose a brand.' : '') && ok;
        var price = parseFloat(form.product_price.value);
        ok = set('priceError', (isNaN(price) || price < 0) ? 'Enter a valid price.' : '') && ok;

        var file = form.product_image.files[0];
        var imgMsg = '';
        if (!file && !isEdit) imgMsg = 'Please choose an image.';
        else if (file && allowed.indexOf(file.type) === -1) imgMsg = 'Image must be JPG, PNG, GIF or WebP.';
        else if (file && file.size > maxSize) imgMsg = 'Image must be 2 MB or smaller.';
        ok = set('imageError', imgMsg) && ok;

        if (!ok) ev.preventDefault();
    });
})();
</script>

<?php require_once '../layout/footer.php'; ?>
