<?php
    require_once 'data/BiwengerAPI.php';
    require_once 'Round.php';

    class RoundRepository {
        private $rounds;

        public function __construct(){
            foreach((BiwengerAPI::getInstance())->getRounds() as $round){
                ($this->rounds)[$round["id"]] = new Round($round["id"],
                    $round["name"],
                    $round["status"],
                    $round["start"],
                    $round["end"]);
            }
        }

        public function getRounds(){
            return $this->rounds;
        }

        public function getRoundByID($id){
            return ($this->rounds)[$id];
        }
    }   
?>