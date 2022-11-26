<?php
    require_once '../BiwengerAPI.php';
    require_once 'Player.php';

    class PlayerRepository {
        private $players;

        public function __construct($league){
            foreach((BiwengerAPI::getInstance())->getPlayers($league->getCompetition()) as $id => $player){
                ($this->players)[$id] = new Player($player["id"],
                    $player["name"],
                    $player["teamID"],
                    $player["position"],
                    $player["price"],
                    $player["priceIncrement"],
                    $player["points"]);
            }
        }

        public function getPlayers(){
            return $this->players;
        }

        public function getPlayerByID($id){
            return ($this->players)[$id];
        }

        public function setPlayer($player){
            ($this->players)[$player->getId()] = $player;
        }

        public function getPlayerByName($name){
            foreach($this->players as $player){
                if($player["name"]==$name)
                    return $player;
            }
            return null;
        }

        public function searchPlayers($name, $players){
            if($name=="") return $players;
            $filteredArray = [];
            foreach($players as $player){
                $nameClean = $this->stripAccents(strtolower($name));
                $playerNameClean = $this->stripAccents(strtolower($player->getName()));

                if (str_contains($playerNameClean, $nameClean)) { 
                    array_push($filteredArray, $player);
                }
            }
            return $filteredArray;
        }
    
        public function stripAccents($str) {
            return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        }

        
    }   
?>