<?php
include( '../config.php' );
include( '../function.inc.php' );
include( '../auth.php' );

$user = authRequired();

// Initialize variables
$attribute_array = [];
$category_id = $dish = $dish_detail = $targetPath = '';
$response = [];
$category_id = get_safe_value( $_POST[ 'category_id' ] );
$dish_type = get_safe_value( $_POST[ 'dish_type' ] );
$dish = get_safe_value( $_POST[ 'dish' ] );
$dish_detail = get_safe_value( $_POST[ 'dish_detail' ] );
$attribute = $_POST[ 'attribute' ];
$price = $_POST[ 'price' ];

if ( strlen( $dish_detail ) > 10000 ) {
    echo json_encode( [ 'status' => 'error', 'message' => 'Dish details too long. Max 1000 characters allowed.' ] );
    exit;
}

// Handle image upload
$uploadDir = realpath( __DIR__ . '/../../admin/assets/uploads' ) . '/';

if ( !is_dir( $uploadDir ) ) {
    if ( !mkdir( $uploadDir, 0777, true ) ) {
        echo json_encode( [ 'status' => 'error', 'message' => 'Failed to create upload directory.' ] );
        exit;
    }
}
if ( !is_writable( $uploadDir ) ) {
    echo json_encode( [ 'status' => 'error', 'message' => 'Upload directory is not writable.' ] );
    exit;
}

$image = $_FILES[ 'image' ];

// Check for upload errors
if ( $image[ 'error' ] !== UPLOAD_ERR_OK ) {
    echo json_encode( [ 'status' => 'error', 'message' => 'File upload error: ' . $image[ 'error' ] ] );
    exit;
}

$imageName = basename( $image[ 'name' ] );
$imageTmpName = $image[ 'tmp_name' ];
$imageSize = $image[ 'size' ];
$imageType = mime_content_type( $imageTmpName );
$allowedTypes = [ 'image/jpeg', 'image/png', 'image/jpg' ];

// Check type
if ( !in_array( $imageType, $allowedTypes ) ) {
    echo json_encode( [ 'status' => 'error', 'message' => 'Invalid image type. Only JPG/PNG allowed.' ] );
    exit;
}

// Check file size ( 2MB max )
if ( $imageSize > 2097145452 ) {
    echo json_encode( [ 'status' => 'error', 'message' => 'Image too large. Max 2MB allowed.' ] );
    exit;
}

// Create a unique name to avoid overwriting
$extension = pathinfo( $imageName, PATHINFO_EXTENSION );
$uniqueName = uniqid() . '.' . $extension;
$targetPath = $uploadDir . $uniqueName;

// ...existing code...
// Debug: Print resolved target path and CWD
error_log( 'Target path: ' . realpath( dirname( $targetPath ) ) );
error_log( 'CWD: ' . getcwd() );
error_log( 'Target full path: ' . $targetPath );
// ...existing code...
if ( !move_uploaded_file( $imageTmpName, $targetPath ) ) {
    error_log( "move_uploaded_file failed: TMP: $imageTmpName, TARGET: $targetPath" );
    echo json_encode( [ 'status' => 'error', 'message' => 'Failed to move uploaded file.' ] );
    exit;
}

// Store relative path for web access
$imagePath = $uniqueName;

if ( empty( $category_id ) || $category_id == 0 || empty( $dish ) || empty( $dish_detail ) || empty($dish_type)) {
    echo json_encode( [
        'status' => 'error',
        'message' => 'Please fill all fields!'
    ] );
    exit;
}

// Check if dish already exists
$check = "SELECT * FROM dish WHERE dish = '$dish'";
$check_result = mysqli_query( $conn, $check );
if ( !$check_result ) {
    echo json_encode( [
        'status' => 'error',
        'message' => 'Database error: ' . mysqli_error( $conn )
    ] );
    exit;
}

if ( mysqli_num_rows( $check_result ) > 0 ) {
    echo json_encode( [
        'status' => 'error',
        'message' => 'Dish already exists!'
    ] );
    exit;
}

// Insert the dish
$added_on = date( 'Y-m-d h:i:s' );
$status = 1;
$query = "INSERT INTO dish (category_id, dish, dish_detail,dish_type, image, added_on, status) 
          VALUES ('$category_id', '$dish', '$dish_detail', '$dish_type' , '$imagePath', '$added_on', '$status')";
$result = mysqli_query( $conn, $query );
$last_id = mysqli_insert_id( $conn );

// adding dish details

if ( $result && !empty( $attribute ) && !empty( $price ) ) {
    foreach ( $attribute as $key => $attr ) {
        $attr = get_safe_value( $attr );
        $price_value = get_safe_value( $price[ $key ] );

        // Check if attribute already exists for this dish
        $check_attr = "SELECT * FROM dish_details WHERE attribute = '$attr' AND dish_id = '$last_id'";
        $check_attr_result = mysqli_query( $conn, $check_attr );

        if ( mysqli_num_rows( $check_attr_result ) > 0 ) {
            echo json_encode( [
                'status' => 'error',
                'message' => 'Dish attribute already exists!'
            ] );
            exit;
        }

        // Insert dish details
        $detail_query = "INSERT INTO dish_details (dish_id, attribute, price, status) 
                         VALUES ('$last_id', '$attr', '$price_value', '1')";
        if ( !mysqli_query( $conn, $detail_query ) ) {
            echo json_encode( [
                'status' => 'error',
                'message' => 'Dish attribute not inserted: ' . mysqli_error( $conn )
            ] );
            exit;
        }
    }
}
// Check if dish was inserted successfully
if ( $result ) {
    echo json_encode( [
        'status' => 'success',
        'message' => 'Dish Inserted Successfully!',
    ] );
} else {
    echo json_encode( [
        'status' => 'error',
        'message' => 'Dish not Inserted: ' . mysqli_error( $conn )
    ] );

}

?>