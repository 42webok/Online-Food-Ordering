<?php
include( '../config.php' );
include( '../function.inc.php' );
include( '../auth.php' );

$user = authRequired();

// Initialize variables
$heading = $sub_heading = $button_txt = $link = $targetPath = '';
$response = [];
$heading = get_safe_value( $_POST[ 'heading' ] );
$sub_heading = get_safe_value( $_POST[ 'sub_heading' ] );
$button_txt = get_safe_value( $_POST[ 'button_txt' ] );
$link = get_safe_value( $_POST[ 'link' ] );


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

if ( empty( $heading )  || empty( $sub_heading ) || empty( $button_txt ) || empty($link) ) {
    echo json_encode( [
        'status' => 'error',
        'message' => 'Please fill all fields!'
    ] );
    exit;
}

// Check if dish already exists
$check = "SELECT * FROM manage_banner WHERE heading = '$heading'";
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
        'message' => 'Heading already exists!'
    ] );
    exit;
}

// Insert the dish
$added_on = date( 'Y-m-d h:i:s' );
$status = 1;
$query = "INSERT INTO manage_banner (heading, sub_heading, button_txt, link , image, added_on, status) 
          VALUES ('$heading', '$sub_heading', '$button_txt', '$link' , '$imagePath', '$added_on', '$status')";
$result = mysqli_query( $conn, $query );


if ( $result ) {
    echo json_encode( [
        'status' => 'success',
        'message' => 'Banner Inserted Successfully!',
    ] );
} else {
    echo json_encode( [
        'status' => 'error',
        'message' => 'Banner not Inserted: ' . mysqli_error( $conn )
    ] );

}

?>