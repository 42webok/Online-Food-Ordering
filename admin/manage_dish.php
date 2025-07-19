<?php 
include('top.php');
$msg="";
$category_id="";
$dish_type="";
$dish="";
$dish_detail="";
$image="";
$id="";
$required = 'required';
$attribute = '';
$price = '';
$attributeArr = [];

if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($con,"select * from dish where id='$id'"));
	$category_id=$row['category_id'];
	$dish=$row['dish'];
	$dish_type=$row['dish_type'];
	$dish_detail=$row['dish_detail'];
    $image = $row['image'];

    $required = '';

    // fetch dish details
	$attr_res = mysqli_query($con, "SELECT * FROM dish_details WHERE dish_id = '$id'");
	while($attr_row = mysqli_fetch_assoc($attr_res)){
		$attributeArr[] = $attr_row;
	}
}

if(isset($_POST['submit'])){
	$category_id=get_safe_value($_POST['category_id']);
	$dish=get_safe_value($_POST['dish']);
	$dish_type=get_safe_value($_POST['dish_type']);
	$dish_detail=get_safe_value($_POST['dish_detail']);
	$added_on=date('Y-m-d h:i:s');
    $attribute = $_POST['attribute'];
    $price = $_POST['price'];
  $image_name = '';
	if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
		$image_name = uniqid() . '_' . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], 'assets/uploads/' . $image_name);
	}
	
	if($id==''){
		$sql="select * from dish where dish='$dish'";
	}else{
		$sql="select * from dish where dish='$dish' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($con,$sql))>0){
		$msg="Dish Number already Exist";
	}else{
		if($id==''){
			mysqli_query($con,"insert into dish(category_id,dish,dish_detail,dish_type,image,status,added_on) values('$category_id','$dish','$dish_detail','$dish_type','$image_name',1,'$added_on')");
            $last_id = mysqli_insert_id($con);
            for($n=0; $n<sizeof($attribute); $n++){
                $attribute[$n] = get_safe_value($attribute[$n]);
                $price[$n] = get_safe_value($price[$n]);
                mysqli_query($con,"insert into dish_details(dish_id,attribute,price ,added_on , status) values($last_id , '$attribute[$n]' , '$price[$n]' , '$added_on' , '1')" );
            }

		}else{
      $upQuery = "update dish set category_id='$category_id', dish_detail='$dish_detail', dish='$dish' , dish_type='$dish_type' ";
      if($image_name != ''){
        $upQuery .= " , image = '$image_name'";
      }
      $upQuery .= "WHERE id = '$id'";
			mysqli_query($con,$upQuery);
		}

        mysqli_query($con, "DELETE FROM dish_details WHERE dish_id = '$id'");
			for($n=0; $n<sizeof($attribute); $n++){
				$attribute[$n] = get_safe_value($attribute[$n]);
				$price[$n] = get_safe_value($price[$n]);
				mysqli_query($con,"INSERT INTO dish_details(dish_id,attribute,price,added_on,status) VALUES('$id', '$attribute[$n]', '$price[$n]', '$added_on', 1)");
			}
		
		redirect('dish.php');
	}
}

$dish_type_array =  array("Veg" , "Non-Veg");

?>
<div class="row">
    <h1 class="grid_title ml10 ml15">Manage Dish</h1>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputName1">Category</label>
                        <select name="category_id" required class="form-control">
                            <option value="" disabled selected>Select Category</option>
                            <?php 
                           $cat_select = "SELECT * FROM category";
                           $cat_res = mysqli_query($con , $cat_select);
                           while($cat_row = mysqli_fetch_assoc($cat_res)){
                             
                            echo "<option value='".$cat_row['id']."'"  . ($category_id == $cat_row['id'] ? 'selected' : '')    .">".$cat_row['category']."</option>";
                           }

                         ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName1">Diah Type</label>
                        <select name="dish_type" required class="form-control">
                            <option value="" disabled selected>Select Dish Type</option>
                            <?php 
                            foreach($dish_type_array as $dish_arr ){
                                echo '<option value="' .$dish_arr . '" ' . ($dish_arr == $dish_type ? "selected" : "") . '>' . $dish_arr . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName1">Dish</label>
                        <input type="text" class="form-control" placeholder="dish Name" name="dish" required
                            value="<?php echo $dish?>">
                        <div class="error mt8"><?php echo $msg?></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail3" required>Dish Detail</label>
                        <textarea name="dish_detail" class="form-control" required><?php echo $dish_detail ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail3" required>Image</label>
                        <input type="file" name="image" class="form-control" <?php echo $required; ?>>
                    </div>

                    <div id="extra_attribute">
                        <?php 
                        $val = 1;
                        foreach($attributeArr as $attr){
                        ?>
                        <div class="row" id="row_<?php echo $val; ?>">
                            <div class="col-5 mb-3">
                                <label for="attribute">Attribute</label>
                                <input type="text" placeholder="Enter Attribute" name="attribute[]"
                                    value="<?php echo $attr['attribute']; ?>" required class="form-control">
                            </div>
                            <div class="col-5 mb-3">
                                <label for="price">Price</label>
                                <input type="text" placeholder="Enter Price" name="price[]"
                                    value="<?php echo $attr['price']; ?>" required class="form-control">
                            </div>
                            <div class="col-2 mb-3 d-flex align-items-end">
                                <div class="btn btn-danger fnn" onclick="hide('row_<?php echo $val; ?>')">Close</div>
                            </div>
                        </div>
                        <?php 
                        $val++;
                        } 
                        ?>
                    </div>


                    <button type="submit" class="btn btn-primary mr-2 mt-3" name="submit">Submit</button>
                    <div class="btn btn-secondary mr-2 mt-3" onclick='addAttribute()'>Add More</div>
                </form>
            </div>
        </div>
    </div>

</div>
<?php include('footer.php');?>


<script>


let val = <?php echo (count($attributeArr) > 0 ? count($attributeArr)+1 : 1); ?>;

function addAttribute() {
    let data = `
        <div class="row" id="row_${val}">
            <div class="col-5 mb-3">
                <label for="attribute">Attribute</label>
                <input type="text" placeholder="Enter Attribute" name="attribute[]" required class="form-control">
            </div>
            <div class="col-5 mb-3">
                <label for="price">Price</label>
                <input type="text" placeholder="Enter Price" name="price[]" required class="form-control">
            </div>
            <div class="col-2 mb-3 d-flex align-items-end">
                <div class="btn btn-danger fnn" onclick="hide('row_${val}')">Close</div>
            </div>
        </div>
    `;
    document.querySelector('#extra_attribute').insertAdjacentHTML('beforeend', data);
    if(val == 1){
        document.querySelector('.fnn').style.display = 'none';
    }
    val++;
}

function hide(id) {
    const row = document.getElementById(id);
    if (row) row.remove();
}

addAttribute();
</script>