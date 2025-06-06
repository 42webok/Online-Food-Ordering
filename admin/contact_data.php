
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
		mysqli_query($con,"update contact_us set status='$status' where id='$id'");
		redirect('contact_data.php');
	}

}

$sql="select * from contact_us order by id desc";
$res=mysqli_query($con,$sql);

?>
  <div class="card">
            <div class="card-body">
              <h1 class="grid_title">Contact Us</h1>
              <div class="row grid_box">
				
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th width="5%">S.No #</th>
                            <th width="15%">Name</th>
                            <th width="10%">Email</th>
							<th width="15%">Subject</th>
							<th width="20%">Message</th>
                            <th width="10%">Created At</th>
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
                            <td><?php echo $row['name']?></td>
							<td><?php echo $row['email']?></td>
							<td><?php echo $row['subject']?></td>
							<td><?php echo $row['message']?></td>
							<td><?php
							 $strDate = strtotime($row['created_at']);
							 $newDate = date('d-m-Y', $strDate);
							 echo $newDate;
							 ?>
							 </td>
							<td>
								<?php
								if($row['status']==1){
								?>
								<a href="?id=<?php echo $row['id']?>&type=deactive"><label class="badge badge-danger">Active</label></a>
								<?php
								}else{
								?>
								<a href="?id=<?php echo $row['id']?>&type=active"><label class="badge badge-info">Deactive</label></a>
								<?php
								}
								
								?>
                                &nbsp;
								<a href="?id=<?php echo $row['id']?>&type=delete"><label class="badge badge-danger delete_red">Delete</label></a>
								
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