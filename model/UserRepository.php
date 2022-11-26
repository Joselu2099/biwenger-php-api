<?php
    require_once '../BiwengerAPI.php';
    require_once 'PlayerRepository.php';
    require_once 'User.php';

    class UserRepository {
        private $users;

        public function __construct($league){
            foreach((BiwengerAPI::getInstance())->getUsersOfLeague() as $user){
                if($user["name"]!="ProManagerBOT"){
                    $players = [];
                    $playersRepo = new PlayerRepository($league->getCompetition());
                    foreach((BiwengerAPI::getInstance())->getPlayersOfUser($user["id"]) as $prePlayer){
                        $player = $playersRepo->getPlayerByID($prePlayer["id"]);
                        $player->setSignDate($prePlayer["owner"]["date"]);
                        $playersRepo->setPlayer($player);
                        $players[$prePlayer["id"]] = $player;
                    }
                    ($this->users)[$user["id"]] = new User($user["id"],
                        $user["name"],
                        $user["teamValue"],
                        $players);
                }
            }
        }

        public function getUsers(){
            return $this->users;
        }

        public function getUserByID($id){
            return ($this->users)[$id];
        }

        public function getUserByName($name){
            foreach($this->users as $user){
                if($user["name"]==$name)
                    return $user;
            }
            return null;
        }
    }   
?>