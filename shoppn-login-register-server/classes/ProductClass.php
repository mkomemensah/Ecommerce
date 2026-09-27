<?php

class ProductClass extends Database {

    public function addBrand($name) {

        $stmt = $this->conn->prepare(
            "INSERT INTO brands (brand_name) VALUES (?)"
        );

        $stmt->bind_param("s", $name);

        return $stmt->execute();
    }

    public function getAllBrands() {

        $stmt = $this->conn->prepare(
            "SELECT * FROM brands ORDER BY brand_name ASC"
        );

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getBrandById($id) {

    $stmt = $this->conn->prepare(
        "SELECT * FROM brands WHERE brand_id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }

    return false;
}

public function updateBrand($id, $name) {

    $stmt = $this->conn->prepare(
        "UPDATE brands SET brand_name = ? WHERE brand_id = ?"
    );

    $stmt->bind_param("si", $name, $id);

    return $stmt->execute();
}
public function addCategory($name) {

    $stmt = $this->conn->prepare(
        "INSERT INTO categories (cat_name) VALUES (?)"
    );

    $stmt->bind_param("s", $name);

    return $stmt->execute();
}

public function getAllCategories() {

    $stmt = $this->conn->prepare(
        "SELECT * FROM categories ORDER BY cat_name ASC"
    );

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

public function updateCategory($id, $name) {

    $stmt = $this->conn->prepare(
        "UPDATE categories SET cat_name = ? WHERE cat_id = ?"
    );

    $stmt->bind_param("si", $name, $id);

    return $stmt->execute();

}

}

?>