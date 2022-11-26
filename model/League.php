<?php
    class League{
        private $id;
        private $name;
        private $competition;
        private $loggedUserID;
        private $loggedUserName;
        private $loggedUserBalance;
        private $hasClausules;

        public function __construct($id, $name, $competition, $loggedUserID, $loggedUserName, $loggedUserBalance, $hasClausules){
            $this->id = $id;
            $this->name = $name;
            $this->competition = $competition;
            $this->loggedUserID = $loggedUserID;
            $this->loggedUserName = $loggedUserName;
            $this->loggedUserBalance = $loggedUserBalance;
            $this->hasClausules = $hasClausules;
        }

        public function getId(){
            return $this->id;
        }

        public function getName(){
            return $this->name;
        }

        public function getCompetition(){
            return $this->competition;
        }

        public function getLoggedUserID(){
            return $this->loggedUserID;
        }

        public function getLoggedUserName(){
            return $this->loggedUserName;
        }

        public function getLoggedUserBalance(){
            return $this->loggedUserBalance;
        }
   
        public function getImg(){
            return 'https://cdn.biwenger.com/i/l/'.$this->id.'.png';
        }

        public function hasClausules(){
            return $this->hasClausules;
        }
    }
?>