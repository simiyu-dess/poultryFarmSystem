<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/classes.php";
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
                <th>Feed Purchases</th>
                <table id ="tb_table">
                    <thead>
                        <th>Date</th>
                        <th>Quantity(KGS)</th>
                        <th>Amount Paid(KSHS)</th>
                        <th colspan="2">Action</th>
                        <th> User </th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $feedConsumptionObject->viewMethod("FeedPurchase");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Date'];?></td>
                                <td><?php echo $row['Quantity'];?></td>
                                <td><?php echo $row['Price'];?></td>
                                <td>
                                    <a class="edit_btn" href="feedPurchase.php?feedpurchupdate=1&id=<?php echo $row["FeedPurchase_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?feedpurchdelete=1&id=<?php echo $row["FeedPurchase_ID"]; ?>">Delete</a>
                                </td>
                                <td></td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["feedpurchupdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("FeedPurchase_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $feedConsumptionObject->selectMethod("FeedPurchase", $where);
                        ?>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="Date" id="date" value="<?php echo $row["Date"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">Name</label>
                                    <input type="text"  name="feedname" id="name" value="<?php echo $row["Name"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity(KGS)</label>
                                    <input type="number" step="any" name="Quantity" id = "quantity" value="<?php echo $row["Quantity"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" step="any" name="Price" id = "price" value="<?php echo $row["Price"]; ?>">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedpurchedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group"> 
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="Date" id="date" max="<?php echo date('Y-m-d');?>" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">Name</label>
                                    <input type="text" step="any" id="name" name="feedname" value="" >
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity(KGS)</label>
                                    <input type="number" step="any" id="quantity" name="Quantity" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" step="any" id="price" name="Price" value="" >
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedpurchsave" class="btn">Save</button>
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