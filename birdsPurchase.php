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
                        <th>Date</th>
                        <th>Number of Birds</th>
                        <th>Price</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $birdsPurchaseObject->viewMethod("BirdsPurchase");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Date'];?></td>
                                <td><?php echo $row['NumberOfBirds'];?></td>
                                <td><?php echo $row['Price'];?></td>
                                <td>
                                    <a class="edit_btn" href="birdsPurchase.php?birdspurchupdate=1&id=<?php echo $row["BirdsPurchase_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?birdspurchdelete=1&id=<?php echo $row["BirdsPurchase_ID"]; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["birdspurchupdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("BirdsPurchase_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $birdsPurchaseObject->selectMethod("BirdsPurchase", $where);
                        ?>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="my-div-error" id="errorDate"></div>
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input type="date" id="date" name="Date" max="<?php echo date('Y-m-d');?>" value="<?php echo $row["Date"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Birds Type</label>
                                    <input type="number" step="any" id="typeOfBirds" name="NumberOfBirds" value="<?php echo $row["NumberOfBirds"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Birds</label>
                                    <input type="number" step="any" id="numberOfBirds" name="NumberOfBirds" value="<?php echo $row["NumberOfBirds"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" step="any" id="price" name="Price" value="<?php echo $row["Price"]; ?>">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdspurchedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>  
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" id="date" max="<?php echo date('Y-m-d');?>" name="Date" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Birds Type</label>
                                    <input type="text"  name="typeOfBirds" id="typeOfBirds" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Birds</label>
                                    <input type="number" id ="numberOfBirds" step="any" name="NumberOfBirds" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price</label>
                                    <input type="number" id="price" step="any" name="Price" value="" >
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdspurchsave" class="btn">Save</button>
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
                        var type = document.getElementById("typeOfBirds").value;
                        var number = document.getElementById("numberOfBirds").value;
                        var price = document.getElementById("price").value;
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errortype = document.getElementById("errorType");
                        var errornumber = document.getElementById("errorNumber");
                        var errorprice = document.getElementById("errorPrice");
                        
                        
                        var truth = true;
                       
                        if(type == ""){
                            errortype.innerHTML = "This field is required";
                            truth =  false;
                        }
                        if(number < 1)
                        {
                            errornumber.innerHTML = "This field is required";
                            truth = false;
                        }
                        if(dates == "")
                        {
                            errordate.innerHTML = "This field is required";
                            truth = false;
                        }
                        
                        if(number == ""){
                            errornumber.innerHTML = " number is  field is required";
                            truth = false;
                        }
                        if(price < 1)
                        {
                            errorprice.innerHTML = "Price must be a positiveinteger";
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