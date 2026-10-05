<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new ProductClass();
    }

    public function addBrand($name)                  { return $this->product->addBrand($name); }
    public function brandNameExists($name, $exclude = 0) { return $this->product->brandNameExists($name, $exclude); }
    public function getAllBrands()                   { return $this->product->getAllBrands(); }
    public function getBrandsWithCount($search = '') { return $this->product->getBrandsWithCount($search); }
    public function getBrandById($id)                { return $this->product->getBrandById($id); }
    public function updateBrand($id, $name)          { return $this->product->updateBrand($id, $name); }
    public function getProductsByBrand($brandId)     { return $this->product->getProductsByBrand($brandId); }
    public function countProductsByBrand($id)        { return $this->product->countProductsByBrand($id); }
    public function deleteBrand($id)                 { return $this->product->deleteBrand($id); }

    public function addCategory($name)               { return $this->product->addCategory($name); }
    public function categoryNameExists($name, $exclude = 0) { return $this->product->categoryNameExists($name, $exclude); }
    public function getAllCategories()               { return $this->product->getAllCategories(); }
    public function updateCategory($id, $name)       { return $this->product->updateCategory($id, $name); }
    public function getCategoriesWithCount()         { return $this->product->getCategoriesWithCount(); }
    public function countProductsByCategory($id)     { return $this->product->countProductsByCategory($id); }
    public function deleteCategory($id)              { return $this->product->deleteCategory($id); }
}