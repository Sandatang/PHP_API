

<html>
    <head>
       <title>List All Item</title> 
       
       <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <?php
            

            $connection = mysqli_connect("localhost","root","","inventory");

            if($connection){

                    function handleInvalidInputs($qty,$umsr,$price){
                            $nonDecimalUmsr = ["pc","set","doz"];
                            $isError = false;

                            if(is_numeric($qty) === FALSE || is_numeric($price) === FALSE){
                                displayAllItem("Quantity or Price should be a valid number");
                                $isError = true;
                            }
                            // if(is_numeric($price) === FALSE){
                            //     displayAllItem("Price on hand should be a valid number");
                            //     // echo "<p> price should be a number </p>";
                            //     $isError = true;
                            // }

                            if($qty < 0 || $price <0){
                                displayAllItem("Quantity on hand or price must not be a negative number");
                                // echo "<p> Quantity on hand or price must not be a negative number </p>";
                                $isError = true;
                            }

                            if((array_search($umsr,$nonDecimalUmsr)) !== FALSE){
                                if ((strpos($qty,'.') !== false)){
                                    displayAllItem("Decimal Values are only allowed in kilo");
                                    // echo "<p> Quantity on hand can only be decimal for Kilo</p>";
                                    $isError = true;
                                }	
                            }
                        return $isError;
                    }

                    // //handle message
                    // function mssgHandlers($msg){
                    //     echo "<p>".$msg."</p>";
                    // }

                    function displayAllItem($msg){ 
                        $connection = mysqli_connect("localhost","root","","inventory");  
                        $sql = " select 
                                    item.iid,
                                    item.description,
                                    item.umsr,
                                    category.description,
                                    item.qtyonhand,
                                    item.price
                                from
                                    item inner join category
                                        on item.cid = category.cid
                                order by
                                    item.description
                        ";
                        $displayRecords = mysqli_query($connection, $sql);
    
    
                        if(mysqli_num_rows($displayRecords) > 0){
                            
                            echo "<div class='w-[100vw] bg-green-400 py-10'>";
                            echo "<h2 class='text-center font-bold text-[25px] text-white'>Invetory Database</h2>";
                            echo "</div>";
                            echo "<p class='text-center font-bold text-[20px] tracking-widest text-green-500 mt-4 underline'>".$msg."</p>";
                            echo "<div class='flex flex-col justify-center items-center h-[55vh]'>";
                            echo "<button id='btnAdd' class='bg-blue-400 px-10 py-2 text-white font-bold rounded-md hover:bg-blue-400 hover:text-black'>Add Item</button>";
                            echo "<form>";
                            echo    "<table class='w-[1200px] border mt-[20px] text-center shadow-md'>";
                            echo        "<thead>";
                            echo            "<tr class='border uppercase'>";
                            echo                "<th>Sequence</th>";
                            echo                "<th>Item ID</th>";
                            echo                "<th>Description</th>";
                            echo                "<th>Unit Of measure</th>";
                            echo                "<th>Category</th>";
                            echo                "<th>Quantity on hand</th>";
                            echo                "<th>Price</th>";
                            echo                "<th colspan='2'>Action</th>";
                            echo            "</tr>";
                            echo        "</thead>";
    
                            echo        "<tbody>";
                                        $seq=1;
                                        while($rec = mysqli_fetch_array($displayRecords)){
                                            $updateThisId = $rec[0];
                                            echo "<tr class='hover:bg-gray-400'>";
                                            echo    "<td class='border py-[4px]'>".$seq."</td>";
                                            echo    "<td class='border'>".$rec[0]."</td>";
                                            echo    "<td class='border'>".$rec[1]."</td>";
                                            echo    "<td class='border'>".$rec[2]."</td>";  
                                            echo    "<td class='border'>".$rec[3]."</td>";
                                            echo    "<td class='border text-right'>".$rec[4]."</td>";
                                            echo    "<td class='border text-right'>&#x20B1; ".$rec[5]."</td>";
                                            echo    "<td><a class='no-underline bg-green-400 hover:bg-green-700 w-full block py-[4px]' id='updateAnchor' href='updater.php?updateId=".$rec[0]."'>&#9998;</a></td>"; // UPDATE ID and pass to self file
                                            // echo    "<td><button class='w-full py-[4px] bg-green-400' id='updaterId' name='update'>update</button></td>";
                                            echo    "<td><a class='no-underline bg-red-400 text-white hover:bg-red-500 w-full block py-[4px] px-[3px] font-bold' id='deleteId' href='list_all_items.php?deleteId=".$rec[0]."'>&#x2715;</a></td>";
                                            echo "</tr>";
                                            $seq++;
                                        }
                            echo        "</tbody>";
                            echo    "</table>";
                            echo "</form>";
                            echo "</div>";
    
                        }
                    }

                ///SAVE UPDATE
                if(isset($_POST['saveUpdate'])){
                    $idUpdate = $_POST['id'];
                    $descriptionUpdate = trim($_POST["description"]);
                    $umsrUpdate = $_POST["umsr"];
                    $categoryUpdate = $_POST["category"];
                    $qtyUpdate = trim($_POST["qtyonhand"]);
                    $price = trim($_POST["price"]);

                    $con = mysqli_connect("localhost", "root", "", "inventory");

                    $checker = handleInvalidInputs($qtyUpdate, $umsrUpdate, $price);
                        if($checker === false){
                            if($con){
                                $sql = "update 
                                            item 
                                        set 
                                            description = '".$descriptionUpdate."',
                                            umsr ='".$umsrUpdate."',
                                            cid = ".$categoryUpdate.",
                                            qtyonhand = ".$qtyUpdate.", 
                                            price = '".$price."'
                                        where 
                                            iid = ".$idUpdate."
                                    ";
                                mysqli_query($con, $sql);
                                // mssg_alert();
                                displayAllItem("Item was updated successfully...");
                                // echo "<p class='text-center font-bold my-6'>Item was updated successfully...</p>";
                            }
                        }
                    
                }

                // ADD ITEM TO DATABASE
                    $description = "";
                    $umsr = "";
                    $category = "";
                    $qty = "";
                    $price = "";


                    if(isset($_POST["save"]))
                    {
                        $description = trim($_POST["description"]);
                        $umsr = $_POST["umsr"];
                        $category = $_POST["category"];
                        $qty = trim($_POST["qtyonhand"]);
                        $price = trim($_POST["price"]);
                        
                        
                        $con = mysqli_connect("localhost", "root", "", "inventory");
                        $checker = handleInvalidInputs($qty, $umsr, $price);
                        if($checker === false){
                            if($con){
                                $sql = "insert into item (description, umsr, cid, qtyonhand, price) 
                                        values ('".$description."', '".$umsr."', ".$category.", ".$qty.", ".$price.") ";
                                mysqli_query($con, $sql);
                                mysqli_close($con);
                                // mssg_alert();
                                displayAllItem("Item was added successfully...");
                                // echo "<p class='text-center font-bold my-6'>Item was saved successfully...</p>";
                            }
                            else {
                                echo "<p>Error connecting to DB...</p>";
                            }
                        }
                    }

                    ///Delete ITEM
                    if(isset($_GET['deleteId'])){
                        $deleteId = $_GET['deleteId'];
                        $con = mysqli_connect("localhost", "root", "", "inventory");
                        if($con){
                            $sqlDelete = "delete from item where iid = '".$deleteId."'";

                            mysqli_query($con, $sqlDelete);
                            // mssg_alert();
                            displayAllItem("Deleted successfully...");
                            // echo "<p class='text-center font-bold my-6'>Deleted successfully...</p>";
                            mysqli_close($con);
                        }
                        
                    }
                    
                
            }

            //SSearch
            if(isset($_GET["search"])){
                $keyword = trim($_GET["keyword"]);

                $con = mysqli_connect("localhost","root","","inventory");
                if($con){
                    $records = mysqli_query($con, "select * from item where description like '%".$keyword."%' or umsr like '%".$keyword."%' order by description");					

                    if(mysqli_num_rows($records) > 0){
                        echo "<div class='w-[100vw] bg-green-400 py-10'>";
                        echo "<h2 class='text-center font-bold text-[25px] text-white'>Invetory Database</h2>";
                        echo "</div>";
                        echo "<div class='flex flex-col justify-center items-center h-[55vh]'>";
                        echo "<button id='btnAdd' class='bg-blue-400 px-10 py-2 text-white font-bold rounded-md hover:bg-blue-400 hover:text-black'>Add Item</button>";
                        echo "<form>";
                        echo "<table class='w-[1200px] border mt-[20px] text-center shadow-md'>";
                        echo "	<tr class='border uppercase'>";
                        echo "		<th>Seq. No.</th>";
                        echo "		<th>Item ID</th>";
                        echo "		<th>Description</th>";
                        echo "		<th>Unit of Measure</th>";
                        echo "		<th>Category</th>";
                        echo "		<th>Qty. on Hand</th>";
                        echo "		<th>Price</th>";
                        echo "      <th colspan='2'>Action</th>";
                        echo "	</tr>";
                        
                        $seq = 1;
                        while($rec = mysqli_fetch_array($records))
                        {
                            
                            echo "<tr class='hover:bg-gray-400'>";
                            echo "		<td class='border py-[4px]'>".$seq.".</td>";
                            echo "		<td class='border'>".$rec[0]."</td>";
                            echo "		<td class='border'>".$rec[1]."</td>";
                            echo "		<td class='border'>".$rec[2]."</td>";
                            echo "		<td class='border'>".$rec[3]."</td>";
                            echo "		<td class='border text-right'>".$rec[4]."</td>";
                            echo "		<td class='border text-right'>".$rec[5]."</td>";
                            echo    "<td><a class='no-underline bg-green-400 hover:bg-green-700 w-full block py-[4px]' id='updateAnchor' href='updater.php?updateId=".$rec[0]."'>&#9998;</a></td>"; // UPDATE ID and pass to self file
                            // echo    "<td><button class='w-full py-[4px] bg-green-400' id='updaterId' name='update'>update</button></td>";
                            echo    "<td><a class='no-underline bg-red-400 text-white hover:bg-red-500 w-full block py-[4px] px-[3px] font-bold' id='deleteId' href='list_all_items.php?deleteId=".$rec[0]."'>&#x2715;</a></td>";
                            echo "</tr>";
                            $seq++;
                        }
                        
                        
                        echo "</table>";
                        echo "</form>";
                        echo "</div>";
                    }
                    else{
                        displayAllItem("No records found.!");
                    }
				
			}else {
				echo "<p>Error connecting to DB...</p>";
			}
			mysqli_close($con);	
		}
            
            
            
        ?>
        
        <div class="w-[100vw] flex justify-center items-center">
            <div class="w-[80%] flex justify-center items-center my-10">
                <form action="list_all_items.php" class="flex flex-col" method="GET" >
                    <input type="text" class="w-[400px] outline-none border-b-2 text-center" name="keyword" value="" placeholder="Keyword"></br>
                    <button class="bg-blue-400 w-[150px] py-2 px-10 my-[5px] ml-[33%] rounded-md text-white font-bold" type="submit" name="search">Search</button>
                </form>
            </div>
        
        </div>
        <!-- <button id="btnAdd" class="bg-blue-400 px-10 py-2 text-white font-bold rounded-md hover:bg-blue-400 hover:text-black">Add Item</button> -->
        <!-- <div class="w-[60%] flex justify-start items-start">
            <button id="btnAd" class="bg-blue-300 px-10 py-2 text-white font-bold rounded-md hover:bg-blue-400 hover:text-black">Add Item</button>
        </div> -->
        <!-- MODAL FOR ADDING ITEM -->
        <div id="modal" class="hidden fixed z-10 left-0 top-0 w-[100vw] h-[100vh] backdrop-blur-sm bg-black bg-opacity-25 overflow-auto">
            <div class="bg-white w-[34vw] mt-[15%] m-auto py-4 rounded-md">
                <h1 class="text-center text-black font-bold uppercase text-2xl text-blue-400 tracking-widest">Add Item</h1>
            
            <iframe name="votar" style="display:none;"></iframe>
            <form id="addForm" onsubmit="validateData()" action="list_all_items.php" method="POST" target="votar">
                <div class="w-[80%] m-auto flex flex-col justify-start items-start">
                <p id="error" class="text-red-300 place-self-center text-[12px] underline"></p>
                    <!-- <label>Description</label> -->
                    <input type="text" class="w-full outline-none border-b-2 my-2" placeholder="Description" id="description" name="description" required/>

                    <div class="flex-row justif-start items-start my-2">
                        <label class="pr-[50px]">Unit of Measure</label>
                        <select class="bg-white border border-solid border-gray-400 rounded-md" id="umsr" name="umsr">
							<option value="pc">pc</option>
							<option value="set">set</option>
							<option value="kl">kilo</option>
							<option value="doz">dozen</option>
						</select>
                    </div>
                    <div class="flex-row justif-start items-start my-2">
                    <label class="pr-[100px]">Category</label>
                        <?php
							$con = mysqli_connect("localhost", "root", "", "inventory");
							if($con)
							{
								$categories = mysqli_query($con, "select * from category order by description");
								if(mysqli_num_rows($categories) > 0)
								{
									echo "<select class='bg-white border border-solid border-gray-400 rounded-md' name='category'>";
									while($row = mysqli_fetch_row($categories))
									{
										echo "<option value='".$row[0]."'>".$row[1]."</option>";
									}
									echo "</select>";
								}
							}
							else 
							{
								echo "<p>Error DB Connection</p>";
							}
							
						?>
                    </div>

                    <!-- <label>Quantity on Hand</label> -->
                    <input type="text" class="w-full outline-none border-b-2" placeholder="Quantiy on Hand" id="qty" name="qtyonhand" required />
                    <input type="text" class="w-full outline-none border-b-2 my-2" placeholder="Price" id="price" name="price" required />

                    <button type="submit" class="w-full bg-blue-400 py-2 rounded-md mt-6 hover:bg-blue-500 hover:text-black text-white text-[15px]" id="saveAddbtn" name="save">Save</button>
                </div>
            </form>
            <a class="mt-6 text-blue-400 hover:text-green-400 flex justify-center items-center hover:no-underline tracking-widest underline" href="list_all_items.php?keyword=&search=">Done?</a>
        </div>
        
        <script type="text/javascript" src="list.js"></script>
    </body>
</html>