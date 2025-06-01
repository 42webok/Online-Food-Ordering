<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");

$user = authRequired();

// Initialize variables
    $category_id = $dish = $dish_detail = $targetPath = '';
    $response = [];
    $category_id = get_safe_value($_POST['category_id']);
    $dish = get_safe_value($_POST['dish']);
    $dish_detail = get_safe_value($_POST['dish_detail']);

// Check if this is a file upload request

    // Get form data from POST
    

    // Validate dish_detail length (example: max 1000 characters)
    if (strlen($dish_detail) > 10000) {
        echo json_encode(['status' => 'error', 'message' => 'Dish details too long. Max 1000 characters allowed.']);
        exit;
    }

    // Handle image upload
    $uploadDir = realpath(__DIR__ . '/../../admin/assets/uploads') . '/';
    // Ensure upload directory exists and is writable
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory.']);
            exit;
        }
    }
    if (!is_writable($uploadDir)) {
        echo json_encode(['status' => 'error', 'message' => 'Upload directory is not writable.']);
        exit;
    }

    $image = $_FILES['image'];
    
    // Check for upload errors
    if ($image['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'File upload error: ' . $image['error']]);
        exit;
    }

    $imageName = basename($image['name']);
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageType = mime_content_type($imageTmpName);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    // Check type
    if (!in_array($imageType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid image type. Only JPG/PNG allowed.']);
        exit;
    }

    // Check file size (2MB max)
    if ($imageSize > 2097145452) {
        echo json_encode(['status' => 'error', 'message' => 'Image too large. Max 2MB allowed.']);
        exit;
    }

    // Create a unique name to avoid overwriting
    $extension = pathinfo($imageName, PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $extension;
    $targetPath = $uploadDir . $uniqueName;





// ...existing code...
// Debug: Print resolved target path and CWD
error_log("Target path: " . realpath(dirname($targetPath)));
error_log("CWD: " . getcwd());
error_log("Target full path: " . $targetPath);
// ...existing code...
if (!move_uploaded_file($imageTmpName, $targetPath)) {
    error_log("move_uploaded_file failed: TMP: $imageTmpName, TARGET: $targetPath");
    echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
    exit;
}








    
    // Store relative path for web access
    $imagePath = 'assets/uploads/' . $uniqueName;


// Validate required fields
if (empty($category_id) || $category_id == 0 || empty($dish) || empty($dish_detail)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please fill all fields!"
    ]);
    exit;
}

// Check if dish already exists
$check = "SELECT * FROM dish WHERE dish = '$dish'";
$check_result = mysqli_query($conn, $check);
if (!$check_result) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . mysqli_error($conn)
    ]);
    exit;
}

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Dish already exists!"
    ]);
    exit;
}

// Insert the dish
$added_on = date('Y-m-d h:i:s');
$status = 1;
$query = "INSERT INTO dish (category_id, dish, dish_detail, image, added_on, status) 
          VALUES ('$category_id', '$dish', '$dish_detail', '$imagePath', '$added_on', '$status')";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Dish Inserted Successfully!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Dish not Inserted: " . mysqli_error($conn)
    ]);
}




?>