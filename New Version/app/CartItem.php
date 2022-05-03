<?php 

class CartItem{

    private Item $item;
    private $quantity;

    public function __construct(Item $item, $quantity)
    {
        $this->item = $item;
        $this->quantity = $quantity;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function increseQuantity($num = 1){
        if($this->getQuantity() != 0  && $this->getItem()->getQuantity()==0){
            echo "No more in stock. You already have " . $this->getQuantity() . " in cart. \n";
            return;
        }
        else if($this->getItem()->getQuantity() == 0){
            echo $this->getItem()->getName() . " is out of stock. \n";
        }
        else if($num > $this->getItem()->getQuantity()){
            echo "Quantity can not be more then " . $this->getItem()->getQuantity() .".\n";
            return;
            
        }else if($this->getQuantity() + $num > $this->getItem()->getQuantity()){
            echo "Quantity can not be more then " . $this->getItem()->getQuantity() .".\n";
            return;
        }
        else {
            $this->quantity +=$num;

        }       
    }
}