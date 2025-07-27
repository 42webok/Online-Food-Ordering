<?php
include( 'admin/database.inc.php' );
session_start();
if (isset( $_SESSION[ 'FOOD_ID' ] ) ) {
    function get_user_cart_data() {
        $cart_data_array = [];
        global $con;
        $user_id = $_SESSION[ 'FOOD_ID' ];
        $query = "SELECT * FROM dish_cart WHERE user_id = '$user_id'";
        $result = mysqli_query( $con, $query );
        if ( mysqli_num_rows( $result ) > 0 ) {
            while( $row = mysqli_fetch_assoc( $result ) ) {
                $cart_data_array[] = $row;
            }
        }
        return $cart_data_array;
    }

}
// print_r(get_user_cart_data());
// exit;

?>

<?php
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

    $cart_data_array = get_user_cart_data();
    $cart_ids = array_column($cart_data_array, 'qnty', 'dish_detail_id');

    $arr = [];
    $query = 'SELECT * FROM dish WHERE status = 1 ';

    if ( !empty( $_POST[ 'cat_id' ] ) ) {
        $cat_id = $_POST[ 'cat_id' ];
        $data = implode( ',', array_map( 'intval', $cat_id ) );
        // safe conversion
        $query .= " AND category_id IN ($data)";
    }
    if ( !empty( $_POST[ 'dish_type' ] ) ) {
        $dish_type = $_POST[ 'dish_type' ];
        $query .= " AND dish_type = '$dish_type'";
    }

    $result = mysqli_query( $con, $query );

    if ( mysqli_num_rows( $result ) > 0 ) {
        while ( $rows = mysqli_fetch_assoc( $result ) ) {
            $dish_id = $rows[ 'id' ];
            $rows[ 'attributes' ] = [];
            $att_query = "SELECT id as dish_detail_id ,  attribute , price FROM dish_details WHERE dish_id = $dish_id AND status = 1 ";
            $res = mysqli_query( $con, $att_query );
            if ( mysqli_num_rows( $res ) > 0 ) {
                while ( $row = mysqli_fetch_assoc( $res ) ) {
                    $rows[ 'attributes' ][] = $row;
                }
            }
            $arr[] = $rows;
        }
    }

    $response = [
        'products' => $arr,
        'cart_ids' => $cart_ids
    ];

    echo json_encode( $response );
}

?>