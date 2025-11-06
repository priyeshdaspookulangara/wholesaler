<?php
require_once "core/db_connect.php";

echo "Starting database seeding...\n";

// Function to create slugs
function create_slug($string){
    return preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
}

// Clear existing data from tables in the correct order to avoid foreign key constraints
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("TRUNCATE TABLE product_images");
$conn->query("TRUNCATE TABLE enquiry_items");
$conn->query("TRUNCATE TABLE enquiries");
$conn->query("TRUNCATE TABLE products");
$conn->query("TRUNCATE TABLE sub_brands");
$conn->query("TRUNCATE TABLE sub_categories");
$conn->query("TRUNCATE TABLE brands");
$conn->query("TRUNCATE TABLE categories");
$conn->query("TRUNCATE TABLE admins");
$conn->query("TRUNCATE TABLE contact_messages");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");
echo "Cleared all existing data.\n";

// 1. Seed Admins
$admin_username = 'admin';
$admin_password = password_hash('password', PASSWORD_DEFAULT);
$conn->query("INSERT INTO admins (username, password) VALUES ('{$admin_username}', '{$admin_password}')");
echo "Admin user created (username: admin, password: password).\n";

// 2. Seed Categories and Sub-Categories
$categories = [
    'Electronics' => ['Smartphones', 'Laptops', 'Cameras'],
    'Home Appliances' => ['Refrigerators', 'Washing Machines', 'Microwaves'],
    'Power Tools' => ['Drills', 'Saws', 'Sanders']
];

foreach ($categories as $cat_name => $sub_cats) {
    $cat_slug = create_slug($cat_name);
    $conn->query("INSERT INTO categories (name, slug) VALUES ('{$cat_name}', '{$cat_slug}')");
    $category_id = $conn->insert_id;

    foreach ($sub_cats as $sub_cat_name) {
        $sub_cat_slug = create_slug($sub_cat_name);
        $conn->query("INSERT INTO sub_categories (category_id, name, slug) VALUES ({$category_id}, '{$sub_cat_name}', '{$sub_cat_slug}')");
    }
}
echo "Seeded Categories and Sub-Categories.\n";

// 3. Seed Brands and Sub-Brands
$brands = [
    'Samsung' => ['Galaxy', 'Note'],
    'Apple' => ['iPhone', 'MacBook'],
    'Sony' => ['Alpha', 'Bravia'],
    'Bosch' => ['Series 6', 'Series 8'],
    'DeWalt' => []
];

foreach ($brands as $brand_name => $sub_brands) {
    $brand_slug = create_slug($brand_name);
    $logo = $brand_slug . '.png';
    $conn->query("INSERT INTO brands (name, slug, logo) VALUES ('{$brand_name}', '{$brand_slug}', '{$logo}')");
    $brand_id = $conn->insert_id;

    foreach ($sub_brands as $sub_brand_name) {
        $sub_brand_slug = create_slug($sub_brand_name);
        $sub_logo = $sub_brand_slug . '.png';
        $conn->query("INSERT INTO sub_brands (brand_id, name, slug, logo) VALUES ({$brand_id}, '{$sub_brand_name}', '{$sub_logo}')");
    }
}
echo "Seeded Brands and Sub-Brands.\n";

// 4. Seed Products
$products = [
    ['name' => 'Galaxy S24 Ultra', 'category' => 'Electronics', 'sub_category' => 'Smartphones', 'brand' => 'Samsung', 'sub_brand' => 'Galaxy', 'featured' => 1, 'new' => 1, 'promo' => 0, 'specs' => "Screen: 6.8-inch Dynamic AMOLED\nCamera: 200MP Quad Camera\nStorage: 512GB"],
    ['name' => 'iPhone 15 Pro', 'category' => 'Electronics', 'sub_category' => 'Smartphones', 'brand' => 'Apple', 'sub_brand' => 'iPhone', 'featured' => 1, 'new' => 0, 'promo' => 1, 'specs' => "Screen: 6.1-inch Super Retina XDR\nCamera: 48MP Triple Camera\nStorage: 256GB"],
    ['name' => 'MacBook Pro 16-inch', 'category' => 'Electronics', 'sub_category' => 'Laptops', 'brand' => 'Apple', 'sub_brand' => 'MacBook', 'featured' => 0, 'new' => 1, 'promo' => 0, 'specs' => "Processor: M3 Max Chip\nMemory: 32GB RAM\nStorage: 1TB SSD"],
    ['name' => 'Alpha A7 IV', 'category' => 'Electronics', 'sub_category' => 'Cameras', 'brand' => 'Sony', 'sub_brand' => 'Alpha', 'featured' => 1, 'new' => 0, 'promo' => 0, 'specs' => "Sensor: 33MP Full-Frame\nVideo: 4K 60p\nAutofocus: Real-time Eye AF"],
    ['name' => 'Series 8 Smart Refrigerator', 'category' => 'Home Appliances', 'sub_category' => 'Refrigerators', 'brand' => 'Bosch', 'sub_brand' => 'Series 8', 'featured' => 0, 'new' => 0, 'promo' => 1, 'specs' => "Capacity: 600L\nEnergy Rating: 5 Star\nFeatures: Home Connect"],
    ['name' => '20V MAX Cordless Drill', 'category' => 'Power Tools', 'sub_category' => 'Drills', 'brand' => 'DeWalt', 'sub_brand' => null, 'featured' => 1, 'new' => 0, 'promo' => 0, 'specs' => "Voltage: 20V\nChuck Size: 1/2-inch\nBattery: 2Ah Lithium-Ion"]
];

foreach ($products as $p) {
    $cat_res = $conn->query("SELECT id FROM categories WHERE name = '{$p['category']}'");
    $category_id = $cat_res->fetch_assoc()['id'];

    $sub_cat_res = $conn->query("SELECT id FROM sub_categories WHERE name = '{$p['sub_category']}'");
    $sub_category_id = $sub_cat_res->fetch_assoc()['id'];

    $brand_res = $conn->query("SELECT id FROM brands WHERE name = '{$p['brand']}'");
    $brand_id = $brand_res->fetch_assoc()['id'];

    $sub_brand_id_val = 'NULL';
    if ($p['sub_brand']) {
        $sub_brand_res = $conn->query("SELECT id FROM sub_brands WHERE name = '{$p['sub_brand']}'");
        $sub_brand_id_val = $sub_brand_res->fetch_assoc()['id'];
    }

    $name = mysqli_real_escape_string($conn, $p['name']);
    $slug = create_slug($name);
    $description = mysqli_real_escape_string($conn, "This is a sample description for the {$p['name']}. It's a high-quality product from {$p['brand']}.");
    $specs = mysqli_real_escape_string($conn, $p['specs']);

    $sql = "INSERT INTO products (name, slug, category_id, sub_category_id, brand_id, sub_brand_id, description, specifications, is_featured, is_new_arrival, is_promotional)
            VALUES ('{$name}', '{$slug}', {$category_id}, {$sub_category_id}, {$brand_id}, {$sub_brand_id_val}, '{$description}', '{$specs}', {$p['featured']}, {$p['new']}, {$p['promo']})";

    $conn->query($sql);
    $product_id = $conn->insert_id;

    // Add 3 dummy images for each product
    for ($i = 1; $i <= 3; $i++) {
        $image_path = "{$slug}-{$i}.jpg";
        $conn->query("INSERT INTO product_images (product_id, image_path) VALUES ({$product_id}, '{$image_path}')");
    }
}
echo "Seeded Products and Product Images.\n";

echo "\nDatabase seeding completed successfully!\n";

mysqli_close($conn);
?>
