<?php 
include('top.php');

if(isset($_GET['type']) && $_GET['type']!=='' && isset($_GET['id']) && $_GET['id']>0){
	$type=get_safe_value($_GET['type']);
	$id=get_safe_value($_GET['id']);
	if($type=='active' || $type=='deactive'){
		$status=1;
		if($type=='deactive'){
			$status=0;
		}
		mysqli_query($con,"update dish set status='$status' where id='$id'");
		redirect('dish.php');
	}

}
if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	
	mysqli_query($con,"delete from dish where id='$id'");
	redirect('dish.php');	
}

$sql="select dish.* , category.category from dish
LEFT JOIN category ON dish.category_id = category.id";
$res=mysqli_query($con,$sql);

?>
<div class="card">
    <div class="card-body">
        <h1 class="grid_title">Dish Master</h1>
        <a href="manage_dish.php" class="add_link">Add Dish</a>
        <div class="row grid_box">

            <div class="col-12">
                <div class="table-responsive">
                    <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th width="10%">S.No #</th>
                                <th width="10%">Category</th>
                                <th width="10%">Dish</th>
                                <th width="20%">Dish Detail</th>
                                <th width="15%">Image</th>
                                <th width="10%">Added On</th>
                                <th width="25%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($res)>0){ 
						$i=1;
						while($row=mysqli_fetch_assoc($res)){
						?>
                            <tr>
                                <td><?php echo $i?></td>
                                <td>
                                    <?php 
                                        echo $row['category'];
                                    ?>
                                </td>
                                <td><?php echo $row['dish']?></td>
                                <td><?php echo $row['dish_detail']?></td>
                                <td><img src="assets/uploads/<?php echo $row['image']?>" class="img-fluid" alt="Food"></td>
                                <td><?php
                              $strDate = strtotime($row['added_on']);
                              $newDate = date('d-m-Y', $strDate);
                              echo $newDate;
                             ?>
                                </td>
                                <td>
                                    <a href="manage_dish.php?id=<?php echo $row['id']?>"><label
                                            class="badge badge-success">Edit</label></a>&nbsp;
                                    <a href="dish.php?id=<?php echo $row['id']?>"><label
                                            class="badge badge-primary">Delete</label></a>&nbsp;
                                    <?php
								if($row['status']==1){
								?>
                                    <a href="?id=<?php echo $row['id']?>&type=deactive"><label
                                            class="badge badge-danger">Active</label></a>
                                    <?php
								}else{
								?>
                                    <a href="?id=<?php echo $row['id']?>&type=active"><label
                                            class="badge badge-info">Deactive</label></a>
                                    <?php
								}
								?>

                                </td>

                            </tr>
                            <?php 
						$i++;
						} } else { ?>
                            <tr>
                                <td colspan="5">No data found</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php');?>