<!-- Show these admin pages only when the admin is logged in -->
<?php  require '../assets/partials/_admin-check.php';   ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buses</title>
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
        $page="earnings";
    ?>
</head>
<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php';?>
        <?php
            $resultSql = "SELECT booking_id, booked_amount FROM bookings ORDER BY booking_created DESC";
                            
            $resultSqlResult = mysqli_query($conn, $resultSql);

            if(!mysqli_num_rows($resultSqlResult)){ ?>
                <!-- Earnings are not present -->
                <div class="container mt-4">
                    <div id="noCustomers" class="alert alert-dark " role="alert">
                        <h1 class="alert-heading">No Earnings Found!!</h1>
                        <!-- <p class="fw-light">Be the first person to add one!</p>
                        <hr>
                        <div id="addCustomerAlert" class="alert alert-success" role="alert">
                                Click on <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a bus!
                        </div> -->
                    </div>
                </div>
            <?php }
            else { ?>             
            <!-- If Earnings are present -->
            <section id="bus">
                <div id="head">
                    <h4>Earnings Status</h4>
                </div>
                <div id="bus-results">
                    
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>#</th>
                            <th>PNR</th>
                            <th>Actions</th>
                        </thead>
                        <?php
                            $ser_no = 0;
                            $total = 0;
                            while($row = mysqli_fetch_assoc($resultSqlResult))
                            {   
                                $ser_no++;

                                $pnr = $row["booking_id"]; 
                                $amount = $row["booked_amount"]; 
                                $total = $total + $amount;
                        ?>
                        <tr>
                            <td><?php echo $ser_no; ?></td>
                            <td><?php echo $pnr; ?></td>
                            <td>Rp. <?php echo $amount; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2"><b>Total</b></td>
                            <td>Rp. <?= $total; ?></td>
                        </tr>
                    </table>
                </div>
            </section>
            <?php } ?> 
        </div>
    </main>
    <!-- External JS -->
    <script src="../assets/scripts/admin_bus.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>