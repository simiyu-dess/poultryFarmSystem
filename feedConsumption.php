<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}
include 'includes/database.php';
include 'includes/action.php';
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
                <table>
                    <thead>
                        <th>Consumed On</th>
                        <th>Quantity Consumed</th>
                        <th>Feed name</th>
                        <th>Employee Responsible</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $feedConsumptionObject->viewMethod("FeedConsumption");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['ConsDate'];?></td>
                                <td><?php echo $row['Quantity'];?></td>
                                <td><?php echo $row['Price'];?></td>
                                <td>
                                    <?php 
                                        $employee = $row['Employee'];
                                        $sql = "select FirstName, LastName from Employee, FeedConsumption where Employee.Employee_ID = $employee";
                                        $query = new Database();
                                        $result = $query->connect()->query($sql);
                                        $result = mysqli_fetch_assoc($result);
                                        // print_r($result);
                                        echo $result['FirstName'].' '.$result['LastName'];
                                    ?>
                                </td>      
                                <td>
                                    <a class="edit_btn" href="feedConsumption.php?feedconsupdate=1&id=<?php echo $row["FeedConsumption_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?feedconsdelete=1&id=<?php echo $row["FeedConsumption_ID"]; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["feedconsupdate"])){
                        // Get the Employee_ID for the employee record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("FeedConsumption_ID" => $id);
                        // Call the selectEmployee method that displays the record to be edited
                        $row = $feedConsumptionObject->selectMethod("FeedConsumption", $where);
                        ?>
                            <form action="includes/action.php" method="post" onsubmit=" return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="ConsDate" id="date" value="<?php echo $row["ConsDate"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" id="quantity" name="Quantity" value="<?php echo $row["Quantity"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" step="any" id="price" name="Price" value="<?php echo $row["Price"]; ?>">
                                </div>
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                        <select name="Employee" id="">
                                        <?php
                                            $myrow = $employeeObject->viewMethod("Employee");
                                            foreach($myrow as $row){
                                                $foreignID = $row["Employee_ID"];
                                            ?>                                    
                                            <option class="selectoptions" value="<?php echo $foreignID; ?>"><?php echo $row["FirstName"] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedconsedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="ConsDate" id="date" max="<?php echo date('Y-m-d');?>" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">Name</label>
                                    <input type="text" name="Name" id="name" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" name="Quantity" id="quantity" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" step="any" id="price" name="Price" value="">
                                </div>
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                    <select name="Employee" id="">
                                    <?php
                                        $myrow = $feedConsumptionObject->viewMethod("Employee");
                                        foreach($myrow as $row){
                                            $foreignID = $row["Employee_ID"];
                                    ?>                                    
                                        <option class="selectoptions" value="<?php echo $foreignID; ?>"><?php echo $row["FirstName"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedconssave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script>
                    function validate(){
                        var dates = document.getElementById("date").value;
                        var name = document.getElementById("name").value;
                        var quantity = document.getElementById("quantity").value;
                        var price = document.getElementById("price").value;
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errorname = document.getElementById("errorName");
                        var errorquantity = document.getElementById("errorQuantity");
                        var errorprice = document.getElementById("errorPrice");
                        
                        
                        // Defining REGEX
                        var nameT = /[A-Za-z]/;
                        var jobT = /^(?![\s.]+$)[a-zA-Z\s.]*$/;
                        var quantityT = /^(\d+)(?:\.(\d{1,2}))?$/;
                        var priceT = /^(\d+)(?:\.(\d{1,2}))?$/;
                        
                        var truth = true;
                        if(!nameT.test(name)){
                            errorname.innerHTML = "Please enter a valid feed name";
                            truth = false;
                        }
                        if(name == ""){
                            errorname.innerHTML = "This field is required";
                            truth =  false;
                        }
                        if(!quantityT.test(quantity)){
                            errorquantity.innerHTML = "Please enter a valid quantity";
                            truth = false;
                        }
                        if(quantity == ""){
                            errorquantity.innerHTML = "feed quantity is  field is required";
                            truth = false;
                        }
                        if(!priceT.test(price))
                        {
                            errorprice.innerHTML = "Enter a valid price";
                            truth = false;
                        }
                        if(price == ""){
                            errorprice.innerHTML = "price field is required";
                            truth = false;
                        }

                    


                        return truth;

                    }
                    </script>
    <script src="script.js"></script>
</body>
</html>