<?php 

include_once 'CartItem.php';

class ShoppingCart{

    protected array $cart = [];


    public function __construct()
    {
        $this->cart = array();    
    }

    public function getCart(){
        return $this->cart;
    }

    public function setCart($item){
        $this->cart = $item;
    }

    public function countItems()
    {
        return count($this->cart);
    }

    public function isEmpty()
    {
        return (empty($this->cart));
    }

    public function addItem(Item $item, int $quantity){
        
        $cartItem = $this->findCartItem($item->getSku());
        
        if($cartItem === null){
            $cartItem = new CartItem($item,0);
            $this->cart[$item->getSku()] = $cartItem;
            $cartItem->increseQuantity($quantity);
        }else {
            $cartItem->increseQuantity($quantity);
        }
        return $cartItem;
    }

    public function findCartItem($sku)
    {
        return $this->cart[$sku] ?? null;
    }

    public function removeItem(CartItem $item,$quantity){
        if($quantity == $item->getQuantity()){
            unset($this->cart[$item->getItem()->getSku()]);
        }
        else if($quantity > $item->getQuantity()){
            echo "You only have " . $item->getQuantity() . " in your shopping cart. \n";
        }else if($quantity < $item->getQuantity()){
            $item->setQuantity($item->getQuantity()-$quantity);
        }       
    }
    public function setNewQuantity(){
        foreach($this->cart as $item){
            $item->getItem()->setQuantity($item->getItem()->getQuantity()-$item->getQuantity());
        }
    }

    public function readItems(){
        foreach($this->cart as $item){
            echo $item->getItem()->getName() . ' ' . $item->getQuantity() . ' x ' . $item->getItem()->getPrice() . ' = ' . $item->getQuantity()*$item->getItem()->getPrice() . "\n";
        }
    }

    public function totalPrice(){
        $total = 0;
        foreach($this->cart as $item){
            $total += $item->getItem()->getPrice()*$item->getQuantity();
        }
        echo "Total: " ."$" .  $total . "\n";
    }

}