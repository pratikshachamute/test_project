
<?php
include_once '../dbConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Submit'])) {
        //Seting Variable Values
        $Id = $_POST["galleryid"];
        $Title = $_POST["title"];
        $Desc = $_POST["desc"];
        $Path;       

        // Uploading Image
        if (isset($_FILES['imagefile'])) {
            $errors = array();
            $file_name = $_FILES['imagefile']['name'];
            $file_size = $_FILES['imagefile']['size'];
            $file_tmp = $_FILES['imagefile']['tmp_name'];
            $file_type = $_FILES['imagefile']['type'];
            // $file_ext=strtolower(end(explode('.',$file_name)));

            $expensions = array("jpeg", "jpg", "png");

//      if(in_array($file_ext,$expensions)=== false){
//         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
//      }

//            if ($file_size > 2097152) {
//                $errors[] = 'File size must be excately 2 MB';
//            }

            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, "../images/Gallery/" . $file_name);
                $Path = "images/Gallery/" . $file_name;
            } else {
                print_r($errors);
            }

            //insert into Database 
            if (!empty($Path)) {
                if ($Id > 0) {
                    $sql = "update tblGallery set Title='$Title',Description='$Desc',Path='$Path' where Id=$Id";
                    if ($conn->query($sql) === true) {
                        echo "<script> alert('Record Update.'); </script>";
                    } else {
                        echo "<script> alert('Failed To Update Record.'); </script>";
                    }
                } else {
                    $sql = "insert into tblGallery(Title,Description,Path)values('$Title','$Desc','$Path')";
                    if ($conn->query($sql) === true) {
                        echo "<script> alert('Record Inserted.'); </script>";
                    } else {
                        echo "<script> alert('Failed To Insert Record.'); </script>";
                    }
                }
            }
        }
    }

    if (isset($_POST['Hide'])) {
        $Hid = $_POST['Hide'];
        if ($Hid > 0) {
            $sql = "update tblGallery set Status=0 where Id=$Hid";
            if ($conn->query($sql) === true) {
                echo "<script> alert('Record Disabled.'); </script>";
            } else {
                echo "<script> alert('Failed To Disable Record.'); </script>";
            }
        }
    }

    if (isset($_POST['Show'])) {
        $Sid = $_POST['Show'];
        if ($Sid > 0) {
            $sql = "update tblGallery set Status=1 where Id=$Sid";
            if ($conn->query($sql) === true) {
                echo "<script> alert('Record Enabled.'); </script>";
            } else {
                echo "<script> alert('Failed To Enable Record.'); </script>";
            }
        }
    }
    
    if (isset($_POST['Delete'])) {
        $Did = $_POST['Delete'];
        if ($Did > 0) {
            $sql = "Delete From tblGallery where Id=$Did";
            if ($conn->query($sql) === true) {
                echo "<script> alert('Record Deleted.'); </script>";
            } else {
                echo "<script> alert('Failed To Delete Record.'); </script>";
            }
        }
    }
}
?>


<?php include_once 'header.php'; ?>

<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Gallery</li>
        </ul>
    </div>
</div>

<section class="forms">
    <div class="container-fluid">
        <!-- Page Header-->
        <header> 
            <h1 class="h3 display">Gallery</h1>
        </header>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>Upload Gallery Image</h4>
                    </div>
                    <div class="card-body">
                        <p>Upload gallery image.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">                            
                            <div class="form-group">
                                <input type="hidden" name="galleryid" value="0"/>
                                <label>Title</label>
                                <input type="text" placeholder="Title" id="title" name="title" class="form-control">
                            </div>
                            <div class="form-group">       
                                <label>Description</label>
                                <textarea placeholder="Description" id="desc" name="desc" class="form-control"></textarea>
                            </div>
                            <div class="form-group">       
                                <label>Select Image</label>
                                <input type="file" name="imagefile" id="imagefile" class="form-control"/>
                            </div>

                            <div class="form-group">       
                                <button type="submit" name="Submit" class="btn btn-primary">Save</button>
                                <input type="Reset" value="Reset" class="btn btn-danger">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>Gallery Images</h4>
                    </div>
                    <div class="card-body">                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once '../dbConnection.php';
                                    $sql = "SELECT Id,Title,Description,Path,Status FROM tblGallery";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tr role="row">
                                                <td><?php echo $row["Id"]; ?></td>
                                                <td><?php echo $row["Title"]; ?></td>
                                                <td><?php echo $row["Description"]; ?></td>
                                                <td><img src="../<?php echo $row["Path"]; ?>" height="50" width="50" alt="galleryimage" /></td>
                                                <td>
                                                    <?php
                                                    if ($row["Status"] == 1) {
                                                        ?>
                                                        <label class="label label-success">Enabled</label>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <label class="label label-danger">Disabled</label>
                                                        <?php
                                                    }
                                                    ?>

                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-xs" id="edit" value="<?php echo $row["Id"]; ?>" ><i class="fa fa-edit fa-2x"></i></button>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                                        <?php
                                                        if ($row["Status"] == 1) {
                                                            ?>

                                                            <button id="hide" type="submit" class="btn btn-danger btn-xs" name="Hide" value="<?php echo $row["Id"]; ?>" ><i class="fa fa-eye-slash fa-2x"></i></button>    


                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button id="show" type="submit"  name="Show" class="btn btn-success btn-xs" value="<?php echo $row["Id"]; ?>" ><i class="fa fa-eye fa-2x"></i></button>    
                                                            <?php
                                                        }
                                                        ?>
                                                        <button id="delete" type="submit"  name="Delete" class="btn btn-warning btn-xs" value="<?php echo $row["Id"]; ?>" ><i class="fa fa-trash fa-2x"></i></button>    
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>	
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<?php include_once 'footer.php'; ?>