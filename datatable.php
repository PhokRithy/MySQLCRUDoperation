<?php
include("database.php");

if(isset($_POST['submit'])){
    $id = $_GET['id'];
    $uname = $_POST['username'];
    $upass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $str = "UPDATE users SET username='$uname', 
    password='$upass' WHERE userid=".$id;
    mysqli_query($con,$str) or die(mysqli_error($con));
}
//Delete
if(isset($_GET['p']) && isset($_GET['cmd']) && $_GET['cmd']=='delete'){
  $sql = "DELETE FROM users WHERE userid = ".$_GET['id'];
  if (mysqli_query($con, $sql)) {
      echo "Record deleted successfully!";
  } else {
      echo "Error deleting record: " . mysqli_error($con);
  }
}

  if(isset($_GET['p']) && isset($_GET['cmd']) && $_GET['cmd']=='edit'){
      $id = $_GET['id'];
      $sql = "select * from users where userid=".$id;
      $query = mysqli_query($con,$sql) or mysqli_error($con);
      while($row =  mysqli_fetch_assoc($query)){
          $uid = $row['userid'];
          $uname = $row['username'];
      }
  ?>

<div class="col-12">
 <!-- general form elements -->
 <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">►Update Data</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form method="post">
        <div class="card-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo $uname;?>" placeholder="Enter Username">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password">
          </div>
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
        <h3 class="card-title">► Insert Data</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form method="post" action="action_insert.php">
        <div class="card-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control" name="uname" placeholder="Enter Username">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" name="pass" placeholder="Password">
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
           <button type="submit" name="submit" class="btn btn-primary">Save</button>
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
                <h3 class="card-title">DataTable with minimal features & hover style</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                  <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Modify</th>
                  
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  
                    $sql = "select * from users order by userid asc";
                    $q = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                    while($row = mysqli_fetch_assoc($q)){
                    echo "<tr>";
                        echo "<td>".$row['userid']."</td>";
                        echo "<td>".$row['username']."</td>";
                        echo "<td>".$row['status']."</td>";
                        echo "<td>
                            <a href='?p=users&cmd=edit&id=".$row['userid']."'><i class='fas fa-edit'></i></a> 
                            | <a href='?p=users&cmd=delete&id=".$row['userid']."'><i class='fas fa-trash'></i></a></td>";
                    echo "</tr>";
                    }

                    // Check if $myArray is defined and has a value
                    // if (isset($myArray) && is_array($myArray)) {
                    //   // Handle the array
                    //   if (array_key_exists("status", $myArray)) {
                    //     $status = $myArray["status"];
                    //   } else {
                    //     $status = "Unknown";
                    //   }
                    // } else {
                    //   // Handle the case where $myArray is not defined or not an array
                    //   // (e.g., display an error message or use a default value)
                    // }
                    ?>
                 
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Staus</th>
                    <th>Modify</th>
                    
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

