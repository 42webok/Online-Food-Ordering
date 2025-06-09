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
		mysqli_query($con,"update manage_banner set status='$status' where id='$id'");
		redirect('banner.php');
	}

}
if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	
	mysqli_query($con,"delete from manage_banner where id='$id'");
	redirect('banner.php');	
}

$sql="select * from manage_banner";
$res=mysqli_query($con,$sql);

?>
<div class="card">
    <div class="card-body">
        <h1 class="grid_title">Banner Master</h1>
        <a href="manage_banner.php" class="add_link btn btn-sm btn-danger">Add Banner</a>
        <div class="row grid_box">

            <div class="col-12">
                <div class="table-responsive">
                    <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th width="10%">S.No #</th>
                                <th width="10%">Heading</th>
                                <th width="10%">Sub Heading</th>
                                <th width="10%">Link</th>
                                <th width="10%">Button Text</th>
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
                                        echo $row['heading'];
                                    ?>
                                </td>
                                <td><?php echo $row['sub_heading']?></td>
                                <td><?php echo $row['button_txt']?></td>
                                <td><?php echo $row['link']?></td>
                                <td><img src="assets/uploads/<?php echo $row['image']?>" class="img-fluid" alt="Food"></td>
                                <td><?php
                              $strDate = strtotime($row['added_on']);
                              $newDate = date('d-m-Y', $strDate);
                              echo $newDate;
                             ?>
                                </td>
                                <td>
                                    <a href="banner.php?id=<?php echo $row['id']?>"><label
                                            class="badge badge-success">Edit</label></a>&nbsp;
                                    <a href="banner.php?id=<?php echo $row['id']?>"><label
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