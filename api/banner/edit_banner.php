<?php
include("../config.php");
include("../function.inc.php");
include("../auth.php");

header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

$user = authRequired();

// Get form-data instead of JSON
$edit_id = get_safe_value($_POST['edit_id']);
$heading = get_safe_value($_POST['heading']);
$sub_heading = get_safe_value($_POST['sub_heading'] );
$button_txt = get_safe_value($_POST['button_txt'] );
$link = get_safe_value($_POST['link'] );

// Handle file upload
$dish_image = $_FILES['image'] ;

// Validate required fields 
if (empty($edit_id) || $edit_id == '0' || empty($heading) || empty($sub_heading) || empty($button_txt) || empty($link)) {
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
$query = "UPDATE manage_banner SET 
    heading = '$heading',
    sub_heading = '$sub_heading',
    button_txt = '$button_txt',
    link = '$link',
    status = '1',
    added_on = NOW(),
    image = '$dish_image_name'
    WHERE id = '$edit_id'";

$result = mysqli_query($conn, $query);


if ($result) {
    echo json_encode([
        "success" => "true",
        "message" => "Banner Updated Successfully!"
    ]);
} else {
    echo json_encode([
        "error" => "true",
        "message" => "Failed to update Banner: " . mysqli_error($conn)
    ]);
}
?>