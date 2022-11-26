<?php
    include_once 'data/BiwengerAPI.php';
    include_once 'Player.php';
    
    class User{
        private $id;
        private $name;
        private $balance;
        private $teamValue;
        private $players;
        private $clauses;
        private $playersPerTeam;

        public function __construct($id, $name, $teamValue, $players){
            $this->id = $id;
            $this->name = $name;
            $this->balance = 0;
            $this->teamValue = $teamValue;
            $this->players = $players;
            $this->clauses = [];
            foreach($players as $id => $player){
                $teamID = $player->getTeamID();
                if(!isset($this->playersPerTeam[$teamID])) $this->playersPerTeam[$teamID]=1;
                else $this->playersPerTeam[$teamID]++;
            }
        }

        public function getId(){
            return $this->id;
        }

        public function getName(){
            return $this->name;
        }

        public function getBalance(){
            return $this->balance;
        }

        public function getBalanceFormated(){
            return number_format($this->balance,0,".",".");
        }

        public function setBalance($balance){
            $this->balance = $balance;
        }

        public function addBalance($balance){
            $this->balance += $balance;
        }

        public function getTeamValue(){
            return $this->teamValue;
        }

        public function getTeamValueFormated(){
            return number_format($this->teamValue,0,".",".");
        }

        public function getMaxBid(){
            return (($this->teamValue)/4)+($this->balance);
        }

        public function getMaxBidFormated(){
            return number_format($this->getMaxBid(),0,".",".");
        }

        public function getPlayers(){
            return $this->players;
        }

        public function getClauses(){
            return $this->clauses;
        }

        public function addClauseConsumed($clausuleDate){
            $week = $this->witchWeekIsThisDate($clausuleDate);
            if(!in_array($week, $this->clauses)) array_push($this->clauses, $week);
        }

        public function witchWeekIsThisDate($dateInSeconds){
            $ddate = date('Y/m/d', $dateInSeconds);
            $duedt = explode("/", $ddate);
            $date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
            $week  = (int)date('W', $date);
            return $week;
        }

        public function getPlayersPerTeam(){
            return $this->playersPerTeam;
        }

        public function getImg(){
            $url = 'https://cdn.biwenger.com/i/u/'.$this->id.'.png';
            if((BiwengerAPI::getInstance())->existImg($url))
                return $url;
            return 'images/userDefaultIcon.png';
        }

        public function exceedsPlayersLimit(){
            foreach($this->playersPerTeam as $team => $amountOfPlayers){
                if($amountOfPlayers > 4) return true;
            }
            return false;
        }

        public function getExceededTeams(){
            $teams=[];
            foreach($this->playersPerTeam as $teamID => $amountOfPlayers){
                if($amountOfPlayers>=4) array_push($teams, $teamID);
            }
            return $teams;
        }

        public function canClausule(){
            $dateNow = date('Y/m/d', time());
            $dateInseconds = strtotime($dateNow);
            $week = $this->witchWeekIsThisDate($dateInseconds);
            return !in_array($week, $this->clauses);
        }

        public function hasPlayer($player){
            return in_array($player, $this->players);
        }

        public function getValueColor($value){
            return ($value<1000000 && $value>0)? 'orange':(($value>0)? '#08F535':'#FF0000');  
        }

        public function printResume(){
            echo "<td><strong>" . $this->id . "</strong></td>";
            echo "<td>" . $this->name . "</td>";
            echo "<td class='balance'><span style='color: ".$this->getValueColor($this->getBalance())."'>" . $this->getBalanceFormated() . "</span></td>";
            echo "<td class='teamValue'><span style='color: ".$this->getValueColor($this->getTeamValue())."'>" . $this->getTeamValueFormated() . "</span></td>";
            echo "<td class='maxBid'><span style='color: ".$this->getValueColor($this->getMaxBid())."'>" . $this->getMaxBidFormated() . "</span></td>";
        }
    }
?>