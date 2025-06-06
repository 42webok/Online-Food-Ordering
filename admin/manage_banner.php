<?php 
include('top.php');
$msg="";
$heading="";
$sub_heading="";
$button_txt="";
$link="";
$image="";
$id="";
$required = 'required';


if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($con,"select * from manage_banner where id='$id'"));
	$heading=$row['heading'];
	$sub_heading=$row['sub_heading'];
	$button_txt=$row['button_txt'];
	$link=$row['link'];
    $image = $row['image'];

    $required = '';

}

if(isset($_POST['submit'])){
	$heading=get_safe_value($_POST['heading']);
	$sub_heading=get_safe_value($_POST['sub_heading']);
	$button_txt=get_safe_value($_POST['button_txt']);
	$link=get_safe_value($_POST['link']);
	$added_on=date('Y-m-d h:i:s');
    $image_name = '';
	if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
		$image_name = uniqid() . '_' . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], 'assets/uploads/' . $image_name);
	}
	
	if($id==''){
		$sql="select * from manage_banner where heading='$heading'";
	}else{
		$sql="select * from manage_banner where heading='$heading' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($con,$sql))>0){
		$msg="Banner already Exist";
	}else{
		if($id==''){
			mysqli_query($con,"insert into manage_banner(heading,sub_heading,button_txt,link,image,status,added_on) values('$heading','$sub_heading','$button_txt','$link','$image_name',1,'$added_on')");

		}else{
      $upQuery = "update manage_banner set heading='$heading', sub_heading='$sub_heading', button_txt='$button_txt' , link='$link' ";
      if($image_name != ''){
        $upQuery .= " , image = '$image_name'";
      }
      $upQuery .= "WHERE id = '$id'";
			mysqli_query($con,$upQuery);
		}
		
		redirect('banner.php');
	}
}
?>
<div class="row">
    <h1 class="grid_title ml10 ml15">Manage Banner</h1>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputName1">Heading</label>
                        <input type="text" class="form-control" placeholder="Heading" name="heading" required
                            value="<?php echo $heading?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName1">Sub Heading</label>
                        <input type="text" class="form-control" placeholder="Sub Heading" name="sub_heading" required
                            value="<?php echo $sub_heading?>">
                        <div class="error mt8"><?php echo $msg?></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3" required>Button Text</label>
                        <input type="text" class="form-control" placeholder="Button Text" name="button_txt" required
                            value="<?php echo $button_txt?>">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail3" required>Button Link</label>
                        <input type="text" class="form-control" placeholder="Button Link" name="link" required
                            value="<?php echo $link?>">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail3" required>Image</label>
                        <input type="file" name="image" class="form-control" <?php echo $required; ?>>
                    </div>


                    <button type="submit" class="btn btn-primary mr-2 mt-3" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

</div>
<?php include('footer.php');?>

