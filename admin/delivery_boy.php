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
		mysqli_query($con,"update delivery_boy set status='$status' where id='$id'");
		redirect('delivery_boy.php');
	}

}

$sql="select * from delivery_boy order by id desc";
$res=mysqli_query($con,$sql);

?>
<div class="card">
    <div class="card-body">
        <h1 class="grid_title">Delivery Boy Master</h1>
        <a href="manage_delivery_boy.php" class="add_link  btn btn-sm btn-danger">Add Delivery Boy</a>
        <div class="row grid_box">

            <div class="col-12">
                <div class="table-responsive">
                    <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th width="10%">S.No #</th>
                                <th width="20%">Name</th>
                                <th width="20%">Mobile</th>
                                <th width="20%">Added On</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($res)>0){ 
						$i=1;
						while($row=mysqli_fetch_assoc($res)){
						?>
                            <tr>
                                <td><?php echo $i?></td>
                                <td><?php echo $row['name']?></td>
                                <td><?php echo $row['mobile']?></td>
                                <td><?php
                              $strDate = strtotime($row['added_on']);
                              $newDate = date('d-m-Y', $strDate);
                              echo $newDate;
                             ?>
                                </td>
                                <td>
                                    <a href="manage_delivery_boy.php?id=<?php echo $row['id']?>"><label
                                            class="badge badge-success">Edit</label></a>&nbsp;
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