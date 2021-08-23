<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/action.php";

include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/functions.php";

checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<!-- head -->
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_head.php";?>
<body id="body">
    <div class="container">
        <!-- top navbar -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_top_navbar.php";?>
        <main>
            <div class="main__container">
            <?php if(isset($_SESSION['msg'])): ?>
                    <div class="msg">
                    <p>
                        <?php 
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']);
                        ?>
                    </p>
                    </div>
                <?php endif ?>
                <?php if(isset($_SESSION['error_msg'])): ?>
                    <div class="error_msg">
                    <p>
                        <?php 
                            echo $_SESSION['error_msg'];
                            unset($_SESSION['error_msg']);
                        ?>
                    </p>
                    </div>
                <?php endif ?>
                
               
                <?php
                    if(isset($_GET["medpurchUpdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("MedicinePurchase_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $medicineObject->selectMethod("MedicinePurchase", $where);
                        ?>
                        <p class="haeding">Insert A record of Medicine Used</p>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="my-div-error" id="errorName"></div>
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input type="date" name="Date" id="date" 
                                    max="<?php echo date('Y-m-d'); ?>" value="<?php echo $row["Date"]; ?>" >
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="erorName"></div> 
                                    <label for="">Medicine Name</label>
                                    <input type="text" id="medicineName" 
                                    name="MedName" value="<?php echo $row["MedicineName"]; ?>" >
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity</label>
                                    <input type="number" id="quantity" 
                                    step="any" name="Quantity" value="<?php echo $row["Quantity"]; ?>" >
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" step="any" id="price" 
                                    name="Price" value="<?php echo $row["Price"]; ?>" >
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="medpurchUpdate" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                       <p class="haeding">Insert A record of Medicine Purchased</p>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" id="date" name="Date" max="<?php echo date('Y-m-d');?>" value="" >
                                </div>
                                <div class="input-group" >
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">Medicine Name</label>
                                    <input type="text" name="MedName" id="medicineName" value="" >
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" id="quantity" name="Quantity" value="" >
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" step="any" id="price" name="Price" value="" >
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="medpurchSave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>

<table id="tb_table">
                    <thead>
                        <th>Date</th>
                        <th>Medicine Name</th>
                        <th>Quantity(KGS)</th>
                        <th>Amount(KSHS)</th>
                        <th>Updated by:</th>
                        <?php if($_SESSION['perm_admin'] == 1 || $_SESSION['perm_action'] == 1):?>
                        <th colspan="2">Action</th>
                        <?php endif ?>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $medicineObject->viewMethod("MedicinePurchase");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Date'];?></td>
                                <td><?php echo $row['MedicineName'];?></td>
                                <td><?php echo $row['Quantity'];?></td>
                                <td><?php echo $row['Price'];?></td>

                                <td>
                                <?php 

                                $userid = $row['User_ID'];
                                $user = getUserName($userid);
                                echo $user;
                                ?>
                                </td>
                                <?php if($_SESSION['perm_admin'] == 1 || $_SESSION['perm_action'] == 1):?>
                                <td>
                                    <a class="edit_btn"
                                     href="MedicinePurchase.php?medpurchUpdate=1&id=<?php echo $row["MedicinePurchase_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" 
                                    href="includes/action.php?medpurchDelete=1&id=<?php echo $row["MedicinePurchase_ID"]; ?>">Delete</a>
                                </td>
                                <?php endif ?>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script>
    function validate(){
                        var dates = document.getElementById("date").value;
                        var quantity = document.getElementById("quantity").value;
                        var name = document.getElementById("medicineName").value;
                        var price = document.getElementById("price").value;
                       
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errorQuantity = document.getElementById("errorQuantity");
                        var errorName = document.getElementById("errorName");
                        var errorPrice= document.getElementById("errorPrice");
                       
                        
                        
                        // Defining REGEX
                       
                        
                        var truth = true;
                        if(dates == ""){
                            errordate.innerHTML = "This field is required";
                            truth = false;
                        }
                       
                        if(quantity < 0)
                        {
                            errorQuantity.innerHTML = "The  quantity must be a positive integer";
                            truth = false;
                        }
                        if(quantity == ""){
                            errorQuantity.innerHTML = "This field is required";
                            truth =  false;
                        }
                        if(name == "")
                        {
                            errorName.innerHTML = "This field is required";
                            truth = false;
                        }
                        
                        if(price < 1)
                        {
                            errorPrice.innerHTML = "price must be a positive integer field";
                            truth = false;
                        }
                        if(price == "")
                        {
                            errorPrice.innerHTML = "Price field is required";
                            truth = false;
                        }
                    
                        return truth;

                    }
                    </script>
    
</body>
</html>