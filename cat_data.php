<?php
include( 'admin/database.inc.php' );
?>

<?php
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    $arr = [];
    $query = 'SELECT * FROM dish WHERE status = 1 ';

    if ( !empty( $_POST[ 'cat_id' ] ) ) {
        $cat_id = $_POST[ 'cat_id' ];
        $data = implode( ',', array_map( 'intval', $cat_id ) );
        // safe conversion
        $query .= " AND category_id IN ($data)";
    }

    $result = mysqli_query( $con, $query );

    if ( mysqli_num_rows( $result ) > 0 ) {
        while ( $rows = mysqli_fetch_assoc( $result ) ) {
            $dish_id = $rows[ 'id' ];
            $rows['attributes'] = [];
            $att_query = "SELECT attribute , price FROM dish_details WHERE dish_id = $dish_id AND status = 1 ";
            $res = mysqli_query( $con, $att_query );
            if ( mysqli_num_rows( $res ) > 0 ) {
                while ( $row = mysqli_fetch_assoc( $res ) ) {
                    $rows['attributes'][] = $row;
                }
            }
            $arr[] = $rows;
        }
    }

    echo json_encode( $arr );
    // always return an array
}

?>