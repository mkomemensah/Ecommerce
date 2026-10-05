<?php

class ProductClass extends Database
{
    /* ---------- Brands ---------- */

    public function addBrand($name)
    {
        $stmt = $this->conn->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    /* True if another brand already uses this name (case-insensitive). */
    public function brandNameExists($name, $excludeId = 0)
    {
        $stmt = $this->conn->prepare(
            "SELECT brand_id FROM brands WHERE LOWER(brand_name) = LOWER(?) AND brand_id <> ?"
        );
        $stmt->bind_param("si", $name, $excludeId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getAllBrands()
    {
        $stmt = $this->conn->prepare("SELECT * FROM brands ORDER BY brand_name ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* Brands with how many products each has; optional name search. */
    public function getBrandsWithCount($search = '')
    {
        $like = '%' . $search . '%';
        $stmt = $this->conn->prepare(
            "SELECT b.brand_id, b.brand_name, COUNT(p.product_id) AS product_count
             FROM brands b
             LEFT JOIN products p ON p.product_brand = b.brand_id
             WHERE b.brand_name LIKE ?
             GROUP BY b.brand_id, b.brand_name
             ORDER BY b.brand_name ASC"
        );
        $stmt->bind_param("s", $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBrandById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows === 1 ? $result->fetch_assoc() : false;
    }

    public function updateBrand($id, $name)
    {
        $stmt = $this->conn->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function countProductsByBrand($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS n FROM products WHERE product_brand = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['n'];
    }

    public function deleteBrand($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function getProductsByBrand($brandId)
    {
        $stmt = $this->conn->prepare(
            "SELECT product_id, product_title, product_price, product_desc, product_image
             FROM products WHERE product_brand = ? ORDER BY product_title ASC"
        );
        $stmt->bind_param("i", $brandId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ---------- Categories ---------- */

    public function addCategory($name)
    {
        $stmt = $this->conn->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    /* True if another category already uses this name (case-insensitive). */
    public function categoryNameExists($name, $excludeId = 0)
    {
        $stmt = $this->conn->prepare(
            "SELECT cat_id FROM categories WHERE LOWER(cat_name) = LOWER(?) AND cat_id <> ?"
        );
        $stmt->bind_param("si", $name, $excludeId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getAllCategories()
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY cat_name ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoriesWithCount()
    {
        $stmt = $this->conn->prepare(
            "SELECT c.cat_id, c.cat_name, COUNT(p.product_id) AS product_count
             FROM categories c
             LEFT JOIN products p ON p.product_cat = c.cat_id
             GROUP BY c.cat_id, c.cat_name
             ORDER BY c.cat_name ASC"
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countProductsByCategory($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS n FROM products WHERE product_cat = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['n'];
    }

    public function deleteCategory($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function updateCategory($id, $name)
    {
        $stmt = $this->conn->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }
}