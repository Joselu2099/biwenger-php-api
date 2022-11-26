<?php
    require_once '../BiwengerAPI.php';
    require_once 'League.php';

    class LeagueRepository {
        private $leagues;

        public function __construct(){
            foreach((BiwengerAPI::getInstance())->getLeagues() as $league){
                ($this->leagues)[$league["id"]] = new League($league["id"],
                    $league["name"],
                    $league["competition"],
                    $league["user"]["id"],
                    $league["user"]["name"],
                    $league["user"]["status"]["balance"],
                    ($league["name"]=="MURCIANOS LEAGUE"));
            }
        }

        public function getLeagues(){
            return $this->leagues;
        }

        public function getLeagueByID($id){
            return ($this->leagues)[$id];
        }

        public function getLeagueByName($name){
            foreach($this->leagues as $league){
                if($league["name"]==$name)
                    return $league;
            }
            return null;
        }
    }   
?>