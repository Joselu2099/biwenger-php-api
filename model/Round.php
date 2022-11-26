<?php
    class Round{
        private $id;
        private $name;
        private $status;
        private $startDate;
        private $endDate;

        public function __construct($id, $name, $status, $startDate, $endDate){
            $this->id = $id;
            $this->name = $name;
            $this->status = $status;
            $this->startDate = $startDate;
            $this->endDate = $endDate;
        }

        public function getId(){
            return $this->id;
        }

        public function getName(){
            return $this->name;
        }

        public function getStatus(){
            return $this->status;
        }

        public function getStartDate(){
            return $this->startDate;
        }

        public function getEndDate(){
            return $this->endDate;
        }

        public function isFinished(){
            return ($this->status=="finished");
        }
    }
?>