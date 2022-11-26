<?php
    require_once 'RoundRepository.php';

    class Player{
        private $id;
        private $name;
        private $teamID;
        private $position;
        private $price;
        private $priceIncrement;
        private $points;
        private $signDate;

        public function __construct($id, $name, $teamID, $position, $price, $priceIncrement, $points){
            $this->id = $id;
            $this->name = $name;
            $this->teamID = $teamID;
            $this->position = $position;
            $this->price = $price;
            $this->priceIncrement = $priceIncrement;
            $this->points = $points;
            $this->signDate = null;
        }

        public function getId(){
            return $this->id;
        }

        public function getName(){
            return $this->name;
        }

        public function getTeamID(){
            return $this->teamID;
        }

        public function getPosition(){
            return $this->position;
        }

        public function getPrice(){
            return $this->price;
        }

        public function getClausulePrice(){
            return ($this->price)*2;
        }

        public function getPriceFormated(){
            return number_format($this->price,0,".",".");
        }

        public function getClausulePriceFormated(){
            return number_format($this->getClausulePrice(),0,".",".");
        }

        public function getPriceIncrement(){
            return $this->priceIncrement;
        }

        public function getPriceIncrementFormated(){
            if($this->priceIncrement>0)
                return "+".number_format($this->priceIncrement,0,'.','.');
            return number_format($this->priceIncrement,0,'.','.');
        }

        public function getPriceIncrementColor(){
            return ($this->priceIncrement==0)? 'white':(($this->priceIncrement>0)? '#08F535':'#FF0000');  
        }

        public function getPoints(){
            return $this->points;
        }

        public function getSignDate(){
            return $this->signDate;
        }

        public function getSignDateFormated(){
            return date("d/m/Y", $this->signDate);
        }

        public function setSignDate($signDate){
            $this->signDate = $signDate;
        }

        public function getImg(){
            $url = 'https://cdn.biwenger.com/i/p/'.$this->id.'.png';
            if((BiwengerAPI::getInstance())->existImg($url))
                return $url;
            return 'images/playerNotImg.png';
        }

        public function isOwned(){
            return !is_null($this->signDate);
        }

        public function isClausulable(){
            $numJornadas=0;
            $rounds = new RoundRepository();
            foreach($rounds as $round){
                $signDate = is_null($this->signDate)? null:$this->signDate;
                $startDate = $round->getStartDate();
                if(!is_null($signDate) && $round->isFinished() && $startDate>$signDate){
                    $numJornadas++;
                }
            }
            return ($numJornadas >= 2);
        }

        public function printPlayerPreview($hasPlayer, $playerExceeded){
            if($playerExceeded) echo '<div class="card player exceeded" style="width:18rem;">';
            else echo '<div class="card player" style="width:18rem;">';
                echo '<img src='.$this->getImg().' class="card-img-top" alt="Imagen Player">';
                echo '<div class="card-body">';
                    echo '<h5 class="card-title">'.$this->name.'</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">'.$this->getPriceFormated().'€</h6>';
                    echo '<p class="card-text playerIncrement" style="color: '.$this->getPriceIncrementColor().'">'.$this->getPriceIncrementFormated().'€</p>';
                    if($hasPlayer || !$this->isClausulable())
                        echo '<a href="clausular.php?player='.$this->id.'" class="btn btn-primary disabled">Clausular</a>';  
                    else echo '<a href="clausular.php?player='.$this->id.'" class="btn btn-primary">Clausular</a>';     
                echo '</div>';
            echo '</div>';
        }

        public function printPlayerClausePreview(){
            echo '<div class="card player">';
                echo '<img src='.$this->getImg().' class="card-img-top" alt="Imagen Player">';
                echo '<div class="card-body">';
                    echo '<h5 class="card-title">'.$this->name.'</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">'.$this->getClausulePriceFormated().'€</h6>';
                    echo '<p class="card-text playerIncrement" style="color: '.$this->getPriceIncrementColor().'">'.$this->getPriceIncrementFormated().'€</p>';  
                    echo '<p class="card-text signDate">'.$this->getSignDateFormated().'</p>';
                echo '</div>';
            echo '</div>';
        }

        public function __toString(){
            return "[Id=".$this->id.", Name=".$this->name.", TeamID=".$this->teamID.", Price=".$this->price.", PriceIncrement=".$this->priceIncrement.", Points=".$this->points.", SignDate=".$this->signDate."]";
        }
    }
?>