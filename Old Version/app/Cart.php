<?php 

class Cart 
{

    //this is the secound stage (cart)
    public function __construct()
    {
        echo "Add items in the cart here: ";
        $line = trim(fgets(STDIN,35));
        $input = explode(" ",$line);
        $this->systemStart($input);
    }

    //starting program 
    public function systemStart($input)
    {
        //checking what is the command that user writes
        if(trim($input[0]) == "ADD" || $input[0]=="add"){
            //checking if sku exists in inventory and checking if quantity input is correct
            if($this->checkSku($input) && $this->checkQuantity($input)){
                Cart::add($input);
            }
        }
        else if(trim($input[0]) == "REMOVE" || $input[0]=="remove"){
            //checking if session exists and if the sku exists in the cart session
               if(!empty($_SESSION['cart']) && $this->inputDoesntExists($input)){
                Cart::remove($input); 
               } else if(empty($_SESSION['cart'])){
                   echo "Your shopping cart is empty. \n";
               }     
        }
        else if(trim($input[0]) == "CHECKOUT" || $input[0]=="checkout"){
            if(!empty($_SESSION['cart'])){
                Cart::checkout($input);
            }
            else {
                echo "You have no items to CHECKOUT. \n";
            }
        }
        //end program
        else if(trim($input[0] == "END") || trim($input[0]) == "end"){
                 Cart::end();
        }
        else {
            echo "You can use ADD, REMOVE, CHECKOUT or END commands. \n";
        }
        new Cart;
    }

    //adding only sku and quantity to cart session
    public static function add($input)
    {   
        //if session cart is empty we add first item
        if(empty($_SESSION['cart'])){
            $_SESSION['cart'][0]=[
               'sku'=> trim($input[1]),
               'quantity'=>trim($input[2])
           ];
           echo "Item added. You currently have 1 item in your shopping cart. \n";
          }
          //if session cart is not empty we add a new item
          else {
           $count = count($_SESSION['cart']);
           $_SESSION['cart'][$count]=[
               'sku'=> trim($input[1]),
               'quantity'=>trim($input[2])
           ];
           echo "You added another item. You currently have " . $count+1 ." items in your shopping cart. \n";
          }
       }

    public static function remove($input)
    {
        //checking if the requested sku exists in shopping cart
            for($i=0;$i<count($_SESSION['cart']);$i++){
                if($_SESSION['cart'][$i]['sku'] == trim($input[1])){
                    // we decrease the current quantity if the remove quantity request is smaller then the existing quantity in the cart
                    // and we set the new quantity
                    if($_SESSION['cart'][$i]['quantity']>$input[2]){
                        $_SESSION['cart'][$i]['quantity']-=$input[2];
                    }else if($_SESSION['cart'][$i]['quantity']==$input[2]){
                        unset($_SESSION['cart'][$i]);
                    //reset session array keys so that later on my function inputExists wont check for empty array keys
                        $_SESSION['cart'] = array_values($_SESSION['cart']);
                    }
                    $count = count($_SESSION['cart']);
                    if($count==1){
                        echo "Item removed from the cart. You currently have ". $count ." item in the cart. \n";
                    }else {
                        echo "Item removed from the cart. You currently have ". $count ." items in the cart. \n";
                    } 
            }
        }
    }

    //checkout function prints the following data:
    public static function checkout()
    {
        $total=0;
        $itemPrice=0;
        //looping through both carts to match them by their SKU's and creating total and item price values
        foreach($_SESSION['inventory'] as $inventory){
            foreach($_SESSION['cart'] as $cart){
               if($inventory['sku'] == $cart['sku']){
                $total = $total + $cart['quantity'] * $inventory['price'];
                $itemPrice = $cart['quantity'] * $inventory['price'];
                echo $inventory['name'] ." ". $cart['quantity']. " x " . $inventory['price'] . " = " . $itemPrice . "$ \n";
                }
            }
        }
        echo "TOTAL = ". $total . "$ \n";
        //destroy last session
         unset($_SESSION['cart']);
        
    }

    //end function
    public static function end()
    {
        //end script
        echo "Thanks for shopping with us. Have a nice day! \n";
        exit();
          
    }

     //error checkers 

     //checking SKU
    public function checkSku($input)
    {
        if(empty($input[1])){
            echo "You have to enter SKU. \n";
            return false;
        }
        if($input[1]<=0){
            echo "SKU must be a (full) positive number. \n";
            return false;
        }
        if(!preg_match('/^[0-9]*$/', $input[1])){
            echo "SKU must be a (full) number. \n";
            return false;
        }
        if(!$this->skuExistsInInventory($input)){
            echo "This item does not exist in the inventory. \n";
            return false;
        }
        if(!$this->inputExists($input)){
            echo "This item is already added to the cart. \n";
            return false;
        }
         return true;     
    }

    //checking quantity
    public function checkQuantity($input)
    {
        if(empty($input[2])){
            echo "You must enter the quantity. \n";
            return false;
        }
        if($input[2]<=0){
            echo "Quantity must be a (full) positive number. \n";
            return false;
        }
        if(!preg_match('/^[0-9]*$/', $input[2])){
            echo "Quantity must be a (full) number. \n";
            return false;
        }
        if(!$this->countQuantity($input)){
            echo "Unfortunately we do not have that amout of selected item. \n";
            return false;
        }
        return true;
    }

    //function that checks if requested sku exists in the inventory session
    public function skuExistsInInventory($input)
    {
            for($i=0;$i<count($_SESSION['inventory']);$i++){
                if($_SESSION['inventory'][$i]['sku'] == trim($input[1])){
                    return true;
                }
            }
         return false;
    }

    //function that if requested sku exists in the cart session returns true
    public function inputDoesntExists($input)
    {
     if(isset($_SESSION['cart'])){
            for($i=0;$i<count($_SESSION['cart']);$i++){
                if($_SESSION['cart'][$i]['sku'] == trim($input[1])){
                    return true;
                }
            }
         }
         return false;
    }

    //function that if requested sku exists in the cart session returns false
    public function inputExists($input)
    {
     if(isset($_SESSION['cart'])){
            for($i=0;$i<count($_SESSION['cart']);$i++){
                if($_SESSION['cart'][$i]['sku'] == trim($input[1])){
                    return false;
                }
            }
         }
         return true;
    }

    //function that checks the max item quantity from inventory session
    public function countQuantity($input)
    {
        for($i=0;$i<count($_SESSION['inventory']);$i++){
            if($_SESSION['inventory'][$i]['sku'] == trim($input[1])){
               if($_SESSION['inventory'][$i]['quantity'] < trim($input[2])){
                    return false;
               }
            }
        }
        return true;
    }
}