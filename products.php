<?php
include("database.php");

// Ensure the upload directory exists
$uploadDir = 'upload/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data ?? '')));
}

// Initialize variables with default values
$pname = '';
$qty = 0;
$price = 0.0;
$pid = 0;
$image = '';    

// Insert Data
if (isset($_POST['btnInsert'])) {
    $pname = sanitize($_POST['productname']);
    $quantity = (int) sanitize($_POST['qty']);
    $uprice = (float) sanitize($_POST['price']);

    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        if ((($_FILES["file"]["type"] == "image/gif") || 
            ($_FILES["file"]["type"] == "image/png") || 
            ($_FILES["file"]["type"] == "image/jpeg")) && 
            ($_FILES["file"]["size"] < 10000000)) {
            
            $Files = basename($_FILES['file']['name']);
            $path_files = $uploadDir . $Files;

            if (file_exists($path_files)) {
                echo $Files . " already exists.";
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $path_files)) {
                    $sql = $conn->prepare("INSERT INTO products (image, product_name, qty, unit_price) VALUES (?, ?, ?, ?)");
                    $sql->bind_param("ssii", $Files, $pname, $quantity, $uprice);
                    
                    if ($sql->execute()) {
                        echo "Record inserted successfully!";
                    } else {
                        echo "Error: " . $sql->error;
                    }
                } else {
                    echo "Failed to move uploaded file.";
                }
            }
        } else {
            echo "Upload File Invalid";
        }
    } else {
        echo "No file uploaded.";
    }
}

// Update
if (isset($_POST['submit'])) {
    $id = (int) $_GET['id'];
    $pname = sanitize($_POST['productname']);
    $qty = (int) sanitize($_POST['qty']);
    $price = (float) sanitize($_POST['price']);
    $sql = null; // Ensure $sql is defined

    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        if ((($_FILES["file"]["type"] == "image/gif") || 
            ($_FILES["file"]["type"] == "image/png") || 
            ($_FILES["file"]["type"] == "image/jpeg")) && 
            ($_FILES["file"]["size"] < 10000000)) {
            
            $Files = basename($_FILES['file']['name']);
            $path_files = $uploadDir . $Files;

            if (file_exists($path_files)) {
                echo $Files . " already exists.";
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $path_files)) {
                    // Update query including the image
                    $sql = $conn->prepare("UPDATE products SET product_name=?, qty=?, unit_price=?, image=? WHERE productid=?");
                    $sql->bind_param("siiis", $pname, $qty, $price, $Files, $id);
                } else {
                    echo "Failed to move uploaded file.";
                }
            }
        } else {
            echo "Upload File Invalid";
        }
    } else {
        // Update query without the image
        $sql = $conn->prepare("UPDATE products SET product_name=?, qty=?, unit_price=? WHERE productid=?");
        $sql->bind_param("siii", $pname, $qty, $price, $id);
    }

    if ($sql && $sql->execute()) {
        echo "Record updated successfully!";
    } else {
        echo "Error: " . ($sql ? $sql->error : "SQL not defined");
    }
}

// Delete
if (isset($_GET['p']) && isset($_GET['cmd']) && $_GET['cmd'] == 'delete') {
    $id = (int) $_GET['id'];
    $sql = $conn->prepare("DELETE FROM products WHERE productid = ?");
    $sql->bind_param("i", $id);

    if ($sql->execute()) {
        echo "Record deleted successfully!";
    } else {
        echo "Error deleting record: " . $sql->error;
    }
}

// Edit
if (isset($_GET['p']) && isset($_GET['cmd']) && $_GET['cmd'] == 'edit') {
    $id = (int) $_GET['id'];
    $sql = $conn->prepare("SELECT * FROM products WHERE productid = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();

    while ($row = $result->fetch_assoc()) {
        $pid = $row['productid'];
        $pname = $row['product_name'] ?? '';
        $qty = $row['qty'] ?? 0;
        $price = $row['unit_price'] ?? 0;
        $image = $row['image'] ?? '';
    }
?>

<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">► Update Products Data</h3>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="card-body">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($pname); ?>" name="productname" placeholder="Enter Product Name">
                </div>
                <div class="form-group">
                    <label>Qty</label>
                    <input type="number" class="form-control" value="<?= htmlspecialchars((string) $qty); ?>" name="qty" placeholder="Enter Quantity">
                </div>
                <div class="form-group">
                    <label>Unit Price</label>
                    <input type="number" class="form-control" value="<?= htmlspecialchars((string) $price); ?>" name="price" placeholder="Enter Unit Price">
                </div>
                <div class="form-group">
                    <label>Current Image</label><br>
                    <img src="upload/<?= htmlspecialchars($image); ?>" width="90px"><br><br>
                    <label>Upload New Image</label>
                    <input type="file" name="file" class="form-control">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<?php } else { ?>
<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">► Insert Product Data</h3>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="card-body">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" class="form-control" name="productname" placeholder="Enter Product Name">
                </div>
                <div class="form-group">
                    <label>Qty</label>
                    <input type="number" class="form-control" name="qty" placeholder="Enter Quantity">
                </div>
                <div class="form-group">
                    <label>Unit Price</label>
                    <input type="number" class="form-control" name="price" placeholder="Enter Unit Price">
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
            <div class="card-footer">
                <button type="submit" name="btnInsert" class="btn btn-primary">Insert Data</button>
            </div>
        </form>
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
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Images</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>                  
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM products ORDER BY productid ASC";
                            $q = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                            while ($row = mysqli_fetch_assoc($q)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars((string) $row['productid']) . "</td>";
                                echo "<td><img src='upload/" . htmlspecialchars($row['image']) . "' width='90px'></td>";
                                echo "<td>" . htmlspecialchars($row['product_name'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars((string) $row['qty']) . "</td>";
                                echo "<td>" . htmlspecialchars((string) $row['unit_price']) . "</td>";
                                echo "<td>" . htmlspecialchars((string) ($row['qty'] * $row['unit_price'])) . "</td>";
                                echo "<td><a class='btn btn-success' href='?p=products&cmd=edit&id=" . htmlspecialchars((string) $row['productid']) . "'>Edit</a> ";
                                echo "<a class='btn btn-danger' href='?p=products&cmd=delete&id=" . htmlspecialchars((string) $row['productid']) . "'>Delete</a></td>";
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
            </div>
        </div>
    </div>
</div>

