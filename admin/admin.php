<!-- Show these admin pages only when the admin is logged in -->
<?php  require '../assets/partials/_admin-check.php';   ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins</title>
        <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- CSS -->
    <?php 
        require '../assets/styles/admin.php';
        require '../assets/styles/admin-options.php';
        $page="admin";
    ?>
</head>
<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php';?>

    <!-- Add, Edit and Delete Admins -->
    <?php
        /*
            1. Check if an admin is logged in
            2. Check if the request method is POST
        */
        if($loggedIn && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(isset($_POST["submit"]))
            {

                $fullName = $_POST["firstname"] . " " . $_POST["lastname"];
                $username = $_POST["username"];
                $password = $_POST["password"]; 
                $birthday = $_POST["birthday"];
                $address = $_POST["address"];
                $phone = $_POST["phone"]; 
        
                $admin_exists = exist_user($conn,$username);
                $admin_added = false;

                if(!$admin_exists)
                {
                    // add admin into users table
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO `users` (`user_name`, `user_fullname`, `user_birth`, `user_address`, `user_phone`, `user_password`, `user_created`) VALUES ('$username', '$fullName', '$birthday', '$address', '$phone', '$hash', current_timestamp());";
                    
                    $result = mysqli_query($conn, $sql);
                    
                    if($result){
                        $admin_added = true;
                    }
                }
    
                if($admin_added)
                {
                    // Show success alert
                    echo '<div class="my-0 alert alert-success alert-dismissible fade show" style="padding-right: 8px;" role="alert">
                    <strong>Successful!</strong> Admin Information Added
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                else{
                    
                    // Show error alert
                    echo    '<div class="my-0 alert alert-danger alert-dismissible fade show" style="padding-right: 8px;" role="alert">
                                <strong>Error!</strong> Admin username already exists
                                <button type="button" class="btn-close mr-4" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                }
            }
            if(isset($_POST["edit"]))
            {
                // EDIT Admin
                $id = $_POST["id"];
                $fullName = $_POST["fullname"];
                $username = $_POST["username"];
                $birthday = $_POST["birth"];
                $address = $_POST["address"];
                $phone = $_POST["phone"]; 
                
                // checking edit user
                $admin_exists = exist_user_edit($conn,$username,$id);
                $admin_added = false;

                if(!$admin_exists)
                {
                    $updateSql = "UPDATE `users` SET `user_fullname` = '$fullName', `user_name` = '$username', `user_birth` = '$birthday', `user_address` = '$address', `user_phone` = '$phone' WHERE `user_id` = $id;";
    
                    $updateResult = mysqli_query($conn, $updateSql);
                    $rowsAffected = mysqli_affected_rows($conn);
                    
                    $messageStatus = "danger";
                    $messageInfo = "";
                    $messageHeading = "Error!";

                    if(!$rowsAffected)
                    {
                        $messageInfo = "No Edits Administered!";
                    }
    
                    elseif($updateResult)
                    {
                        // Show success alert
                        $messageStatus = "success";
                        $messageHeading = "Successfull!";
                        $messageInfo = "Admin details Edited";
                    }
                    else{
                        // Show error alert
                        $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                    }
                    
                    // MESSAGE
                    echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
                    <strong>'.$messageHeading.'</strong> '.$messageInfo.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                else 
                {
                    // If Admin details already exists
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Admin username already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }

            }
            if(isset($_POST["delete"]))
            {
                // DELETE BUS
                $id = $_POST["id"];
                $username = get_from_table($conn, "users", "user_id", $id, "user_name");
                // Delete the bus with id => id
                $deleteSql = "DELETE FROM `users` WHERE `user_id` = $id";

                $deleteResult = mysqli_query($conn, $deleteSql);
                $rowsAffected = mysqli_affected_rows($conn);
                $messageStatus = "danger";
                $messageInfo = "";
                $messageHeading = "Error!";

                if(!$rowsAffected)
                {
                    $messageInfo = "Record Doesnt Exist";
                }

                elseif($deleteResult)
                {   
                    // echo $num;
                    // Show success alert
                    $messageStatus = "success";
                    $messageInfo = "Admin Details deleted";
                    $messageHeading = "Successfull!";
                }
                else{
                    // Show error alert
                    $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                }
                // Message
                echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
                <strong>'.$messageHeading.'</strong> '.$messageInfo.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
        ?>
        <?php
            $resultSql = "SELECT * FROM `users` ORDER BY user_created DESC";
                            
            $resultSqlResult = mysqli_query($conn, $resultSql);

            if(!mysqli_num_rows($resultSqlResult)){ ?>
                <!-- Admins are not present -->
                <div class="container mt-4">
                    <div id="noCustomers" class="alert alert-dark " role="alert">
                        <h1 class="alert-heading">No Admins Found!!</h1>
                        <p class="fw-light">Be the first person to add one!</p>
                        <hr>
                        <div id="addCustomerAlert" class="alert alert-success" role="alert">
                                Click on <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a admin!
                        </div>
                    </div>
                </div>
            <?php }
            else { ?>             
            <!-- If Admins are present -->
            <section id="admin" width="100%">
                <div id="head">
                    <h4>Admin Status</h4>
                </div>
                <div id="admin-results">
                    <div>
                        <button id="add-button" class="button btn-sm" type="button"data-bs-toggle="modal" data-bs-target="#addModal">Add Admins Details <i class="fas fa-plus"></i></button>
                    </div>
                    
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Fullname</th>
                            <th>Username</th>
                            <th>Birthday</th>
                            <th>Address</th>
                            <th>Phone</th>
                        </thead>
                        <?php
                            $ser_no = 0;
                            while($row = mysqli_fetch_assoc($resultSqlResult))
                            {   
                                $ser_no++;
                                // echo "<pre>";
                                // var_export($row);
                                // echo "</pre>";

                                $id = $row["user_id"];
                                $fullname = $row["user_fullname"]; 
                                $username = $row["user_name"]; 
                                $birth = $row["user_birth"]; 
                                $address = $row["user_address"]; 
                                $phone = $row["user_phone"]; 
                                ?>
                                <tr>
                                    <td> <?php echo $ser_no; ?> </td>
                                    <td><?php echo $fullname; ?></td>
                                    <td> <?php echo $username; ?> </td>
                                    <td><?php echo $birth; ?></td>
                                    <td> <?php echo $address; ?> </td>
                                    <td> <?php echo $phone; ?> </td>
                                    <td>
                                    <button class="button edit-button " 
                                                        data-link="<?php echo $_SERVER['REQUEST_URI']; ?>" 
                                                        data-id="<?php echo $id;?>" 
                                                        data-fullname="<?php echo $fullname;?>"
                                                        data-username="<?php echo $username;?>"
                                                        data-birth="<?php echo $birth;?>"
                                                        data-address="<?php echo $address;?>"
                                                        data-phone="<?php echo $phone;?>"
                                                        >Edit</button>
                                                    <button class="button delete-button" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $id;?>">Delete</button>
                                    </td>
                                </tr>
                                <?php 
                            }
                        ?>
                    </table>
                </div>
            </section>
            <?php } ?> 
        </div>
    </main>
    <!-- All Modals Here -->
    <!-- Add Admins Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add A Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addCustomerForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <div class="col-6">
                                    <label for="firstname" class="form-label">Firstname</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                                <div class="col-6">
                                    <label for="lastname" class="form-label">Lastname</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" maxlength="512" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" maxlength="12" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span id="passwordErr" class="error"></span>
                            </div>
                            <div class="mb-3">
                                <label for="confPassword" class="form-label">Confirmation Password</label>
                                <input type="password" class="form-control" id="confPassword" name="confPassword" required>
                                <span id="confPassErr" class="error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" name="submit">Submit</button>
                            <!-- Add Anything -->
                        </div>
                    </form>
                    </div>
                </div>
        </div>
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 class="text-center pb-4">
                    Are you sure?
                </h2>
                <p>
                    Do you really want to delete this admin? <strong>This process cannot be undone.</strong>
                </p>
                <!-- Needed to pass id -->
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="delete-form"  method="POST">
                    <input id="delete-id" type="hidden" name="id">
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="delete-form" name="delete" class="btn btn-danger">Delete</button>
            </div>
            </div>
        </div>
    </div>
    <!-- External JS -->
    <script src="../assets/scripts/admin_admin.js"></script>
    <script src="../assets/scripts/admin_signup.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>