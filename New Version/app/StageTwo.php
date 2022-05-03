<?php 

include_once 'ShoppingCart.php';

class StageTwo{
    
    private $inventory;
    private $cart;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
        echo "\n";
        echo "**************************\n";
        echo "****WELCOME TO THE CART***\n";
        echo "**************************\n";
        echo "Add items in the cart here:\n";
        $line = trim(fgets(STDIN,35));
        $input = explode(" ",$line);
        $this->cart = new ShoppingCart();
        $this->systemStart($input);
    }


    public function systemStart($input)
    {

        if(trim($input[0]) == "ADD" || $input[0]=="add"){

            if($this->checkSku($input) && $this->checkQuantity($input)){      

               $item =  $this->inventory->findInventoryItem($input[1]);
               if($item == null){
                echo "This SKU does not exist. \n";
               }else if($item->getQuantity() < $input[2]){
                echo "In stock: " . $item->getQuantity() . "\n";
               }
               else {
                $this->cart->addItem($item,$input[2]);
               }
            }
        }
        else if(trim($input[0]) == "REMOVE" || $input[0]=="remove"){
           
            $item =  $this->cart->findCartItem($input[1]);
            if($item == null){
             echo "This SKU does not exist. \n";
            }else {

                $this->cart->removeItem($item,$input[2]);
            }

        }
        else if(trim($input[0]) == "CHECKOUT" || $input[0]=="checkout"){
            if(!$this->cart->isEmpty()){
                $this->cart->readItems($this->cart);
                $this->cart->totalPrice();
                $this->cart->setNewQuantity();
                $this->cart = new ShoppingCart();
            }
            else {
                echo "You have 0 items in your shopping cart. Add some before you CHECKOUT. \n";
            }
        }

        else if(trim($input[0] == "END") || trim($input[0]) == "end"){
            $this->end();
        }
        else {
            echo "You can use ADD, REMOVE, CHECKOUT or END commands. \n";
        }
        echo "Add items in the cart here: \n";
        $line = trim(fgets(STDIN,35));
        $input = explode(" ",$line);
        $this->systemStart($input);
    }


    public function end()
    {
        echo "Thanks for shopping with us. Have a nice day! \n";
        exit();       
    }

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
         return true;     
    }

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
        return true;
    }
}