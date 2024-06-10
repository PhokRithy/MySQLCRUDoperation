<?php
include("database.php");
//Insert Data
if(isset($_POST['btnInsert'])){
    
    $pname = $_POST['productname'];
    $quantity = $_POST['qty'];
    $uprice = $_POST['price'];

    if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpeg")) && ($_FILES["file"]["size"] < 10000000))
    {
        if ($_FILES["file"]["error"] > 0) {
            echo "Error: " . $_FILES["file"]["error"] . "<br />";
        }else{
            
            if (file_exists("upload/" . $_FILES["file"]["name"]))
            {
                echo $_FILES["file"]["name"] . " already exists. ";
            }
            else
            {
                $Files	=	$_FILES['file']['name']; 
                $path_files= "upload/".$Files;
                move_uploaded_file($_FILES["file"]["tmp_name"],$path_files);
              
                $sql = "INSERT INTO tblproducts (images, product_name, qty, unit_price)
                VALUES ('$Files','$pname', $quantity, $uprice)";
                
                if (mysqli_query($con,$sql) === TRUE) {
                    echo "Record inserted successfully!";
                } else {
                    echo "Error: " . $sql . "<br>" . $con->error;
                }
            }
        }
    }else{
        echo "Upload File Invalid";
    } 
}

//Update
if(isset($_POST['submit'])){
    $id = $_GET['id'];
    $pname = $_POST['productname'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];

    $str = "UPDATE tblproducts SET product_name='$pname', qty=$qty, unit_price=$price
    WHERE productid=".$id;
    mysqli_query($con,$str) or die(mysqli_error($con));
}
//Delete
if(isset($_GET['p']) && isset($_GET['cmd']) && $_GET['cmd']=='delete'){
  $sql = "DELETE FROM tblproducts WHERE productid = ".$_GET['id'];
  if (mysqli_query($con, $sql)) {
      echo "Record deleted successfully!";
  } else {
      echo "Error deleting record: " . mysqli_error($con);
  }
}

  if(isset($_GET['p']) && isset($_GET['cmd']) && $_GET['cmd']=='edit'){
      $id = $_GET['id'];
      $sql = "select * from tblproducts where productid=".$id;
      $query = mysqli_query($con,$sql) or mysqli_error($con);
      while($row =  mysqli_fetch_assoc($query)){
          $pid = $row['productid'];
          $pname = $row['product_name'];
          $qty = $row['qty'];
          $price = $row['unit_price'];
      }
  ?>
<div class="col-12">
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">► Update Products Data</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="post" enctype="multipart/form-data">
            <div class="card-body">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" class="form-control" value="<?=$pname;?>" name="productname" placeholder="Enter Username">
            </div>
            <div class="form-group">
                <label>Qty</label>
                <input type="number" class="form-control" value="<?=$qty;?>" name="qty" placeholder="Password">
            </div>
            <div class="form-group">
                <label>Unit Price</label>
                <input type="number" class="form-control" value="<?=$price;?>" name="price" placeholder="Password">
            </div>
            <div class="input-group">
                <label>Upload File</label>
                <input type="file" name="file" class="form-control">
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
        
        </div>
        <!-- /.card -->
    </div>
</div>
<?php }else{ ?>
<div class="col-12">
<!-- general form elements -->
<div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">► Insert Product Data</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form method="post" enctype="multipart/form-data">
        <div class="card-body">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" class="form-control" name="productname" placeholder="Enter Username">
          </div>
          <div class="form-group">
            <label>Qty</label>
            <input type="number" class="form-control" name="qty" placeholder="Password">
          </div>
          <div class="form-group">
            <label>Unit Price</label>
            <input type="number" class="form-control" name="price" placeholder="Password">
          </div>
          <div class="input-group">
                <div class="custom-file">
                <input type="file" name="file" class="custom-file-input" id="exampleInputFile">
                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                </div>
                <div class="input-group-append">
                <span class="input-group-text">Upload</span>
                </div>
            </div>
            
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
           <button type="submit" name="btnInsert" class="btn btn-primary">Insert Data</button>
        </div>
      </form>
    
    </div>
    <!-- /.card -->
</div>
</div>
<?php } ?>


      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">DataTable with Products</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                  <tr>
                    <th>Product ID</th>
                    <th>images</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Action</th>                  
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    $sql = "SELECT * FROM tblproducts order by productid asc";
                    $q = mysqli_query($con,$sql) or die(mysqli_error($con));
                    while($row = mysqli_fetch_assoc($q)){
                        echo "<tr>";
                        echo "<td>{$row['productid']}</td>";
                        echo "<td><img src='upload/{$row['images']}' width='90px'></td>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['qty']}</td>";
                        echo "<td>{$row['unit_price']}</td>";
                        echo "<td>".$row['qty'] * $row['unit_price']."</td>";
                        echo "<td><a class='btn btn-success' href='?p=products&cmd=edit&id=".$row['productid']."'>Edit</a> ";
                        echo " <a class='btn btn-danger' href='?p=products&cmd=delete&id=".$row['productid']."'>Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>
                 
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Product ID</th>
                    <th>Images</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Action</th>   
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

