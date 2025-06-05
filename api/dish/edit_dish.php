<?php
include("../config.php");
include("../function.inc.php");
include("../auth.php");

header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

$user = authRequired();

// Get form-data instead of JSON
$attribute_array = [];
$edit_id = get_safe_value($_POST['edit_id']);
$category_id = get_safe_value($_POST['category_id']);
$dish = get_safe_value($_POST['dish'] );
$dish_detail = get_safe_value($_POST['dish_detail'] );

$attribute = $_POST[ 'attribute' ];
$price = $_POST[ 'price' ];

// Handle file upload
$dish_image = $_FILES['image'] ;

// Validate required fields 
if (empty($edit_id) || empty($category_id) || empty($dish) || empty($dish_detail)) {
    echo json_encode([
        "error" => "true",
        "message" => "All fields are required!"
    ]);
    exit;
}

// Process image upload if provided
if ($dish_image) {
    $targetDir = realpath(__DIR__ . '/../../admin/assets/uploads') . '/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileExt = pathinfo($dish_image['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $fileExt;
    $targetPath = $targetDir . $newFileName;

    if (move_uploaded_file($dish_image['tmp_name'], $targetPath)) {
        $dish_image_name = $newFileName;
    } else {
        echo json_encode([
            "error" => "true",
            "message" => "Failed to upload image."
        ]);
        exit;
    }
}else{
    echo json_encode([
        "error" => "true",
        "message" => "Image file is required."
    ]);
    exit;
}




// Update dish in database
$query = "UPDATE dish SET 
    dish = '$dish',
    dish_detail = '$dish_detail',
    category_id = '$category_id',
    status = '1',
    added_on = NOW(),
    image = '$dish_image_name'
    WHERE id = '$edit_id'";

$result = mysqli_query($conn, $query);


if ($result) {
    // Update attributes
    foreach ($attribute as $key => $attr) {
        $price_value = isset($price[$key]) ? get_safe_value($price[$key]) : '';
        $attr = get_safe_value($attr);
        
        // Check if attribute already exists
        $checkQuery = "SELECT * FROM dish_details WHERE dish_id = '$edit_id' AND attribute = '$attr'";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            // Update existing attribute
            $updateQuery = "UPDATE dish_details SET price = '$price_value' WHERE dish_id = '$edit_id' AND attribute = '$attr'";
            mysqli_query($conn, $updateQuery);
        } else {
            // Insert new attribute
            $insertQuery = "INSERT INTO dish_details (dish_id, attribute, price , status) VALUES ('$edit_id', '$attr', '$price_value' , '1')";
            mysqli_query($conn, $insertQuery);
        }
    }
    echo json_encode([
        "success" => "true",
        "message" => "Dish Updated Successfully!"
    ]);
} else {
    echo json_encode([
        "error" => "true",
        "message" => "Failed to update dish: " . mysqli_error($conn)
    ]);
}
?>