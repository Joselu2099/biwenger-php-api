<?php

    class BiwengerAPI {
        private static $instance=null;
        private $token;
        private $basic_headers;

        private function __construct(){
            $this->token = "";
            $this->basic_headers = [];
        }

        public function __destruct() {
            self::$instance = null;
        }

        public static function getInstance(){
            if(is_null(self::$instance)) self::$instance = new BiwengerAPI();
            return self::$instance;
        }

        public static function isInit(){
            return !is_null(self::$instance);
        }

        public function setBasicHeaders($x_league, $x_user){
            $this->basic_headers["x_league"] = $x_league;
            $this->basic_headers["x_user"] = $x_user;
        }

        private function getToken($login, $password){
            $url = 'https://biwenger.as.com/api/v2/auth/login';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
            curl_setopt($ch, CURLOPT_POST, 1);
            $fields = array('email'=>$login, 'password'=>$password);
            $post_fields = http_build_query($fields);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            $result = curl_exec($ch);
            curl_close($ch);
            $jsonArray = json_decode($result,true);
            if(array_key_exists("token", $jsonArray))  
                return $jsonArray["token"];
            else return null;
        }

        public function login($email, $password){
            $token = $this->getToken($email, $password);
            $isTokenSet = (isset($token) && !is_null($token));
            if($isTokenSet) $this->token = $token;
            return $isTokenSet;
        }

        //$competition is world-cup, la-liga, etc.
        public function getPlayers($competition){
            $url = 'https://cf.biwenger.com/api/v2/competitions/'.$competition.'/data?lang=es&score=1';
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            
            curl_close($ch);
            $jsonArray = json_decode($data,true);
            return $jsonArray["data"]["players"];
        }

        public function getLeagues(){
            $url = 'https://biwenger.as.com/api/v2/account';
            $authorization = "Authorization: Bearer ".$this->token;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            
            curl_close($ch);
            $jsonArray = json_decode($data,true);
            return $jsonArray["data"]["leagues"];
        }

        public function getUsersOfLeague(){
            $url = 'https://biwenger.as.com/api/v2/league?include=all,-lastAccess&fields=*,standings,tournaments,group,settings(description)';
            $authorization = "Authorization: Bearer ".$this->token;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-league: '.$this->basic_headers["x_league"],
            'x-user: '.$this->basic_headers["x_user"], 'Content-Type: application/json' , $authorization ));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);

            $jsonArray = json_decode($data,true);
            return $jsonArray["data"]["standings"];
        }

        public function getPlayersOfUser($userID){
            $url = 'https://biwenger.as.com/api/v2/user/'.$userID.'?fields=*,account(id),players(id,owner),lineups(round,points,count,position),league(id,name,competition,type,mode,marketMode,scoreID),market,seasons,offers,lastPositions';
            $authorization = "Authorization: Bearer ".$this->token;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-league: '.$this->basic_headers["x_league"],
                                                    'x-user: '.$this->basic_headers["x_user"], 
                                                    'Content-Type: application/json', 
                                                    $authorization ));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            
            curl_close($ch);
            $jsonArray = json_decode($data,true);
            return $jsonArray["data"]["players"];
        }

        public function transferPlayer($data){
            $botEmail = "adminAccount@gmail.com"; //Introduce admin account
            $botPass = "adminPassword"; //Introduce admin password
            $token = $this->getToken($botEmail, $botPass);
            $url = 'https://biwenger.as.com/api/v2/league/'.$this->basic_headers["x_league"].'/transfer';
            $authorization = "Authorization: Bearer ".$token;
            $postdata = json_encode($data);

            $ch = curl_init($url); 
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-league: '.$this->basic_headers["x_league"],
                                                    'x-user: '.$this->getIDForBOT($token), 
                                                    'Content-Type: application/json', 
                                                    $authorization ));
            $result = curl_exec($ch);
            curl_close($ch);

            $jsonArray = json_decode($result,true);
            
            $msg = isset($jsonArray["userMessage"])? $jsonArray["userMessage"]:"";
            return $msg;
        }

        public function getIDForBOT($token){
            $url = 'https://biwenger.as.com/api/v2/account';
            $authorization = "Authorization: Bearer ".$token;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);

            curl_close($ch);
            $jsonArray = json_decode($data,true);
            foreach($jsonArray["data"]["leagues"] as $league){
                if($league["id"]==$_SESSION["x_league"]){
                    return $league["user"]["id"];
                }
            } 
        }

        public function getRounds(){
            $url = 'https://cf.biwenger.com/api/v2/competitions/la-liga/season';
            $ch = curl_init($url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $result = curl_exec($ch);
            curl_close($ch);

            $rounds = json_decode($result,true);

            return $rounds["data"]["rounds"];
        }

        public function getTransfers(){
            $url = 'https://biwenger.as.com/api/v2/league/'.$this->basic_headers["x_league"].'/board?type=transfer,market,loan,loanReturn,adminTransfer&limit=999999';
            $authorization = "Authorization: Bearer ".$this->token;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-league: '.$this->basic_headers["x_league"],
                                                    'x-user: '.$this->basic_headers["x_user"], 
                                                    'Content-Type: application/json', 
                                                    $authorization ));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = curl_exec($ch);
            curl_close($ch);

            $jsonArray = json_decode($result,true);
            return $jsonArray["data"];
        }
    
        public function getRoundsResult(){
            $url = 'https://biwenger.as.com/api/v2/league/'.$this->basic_headers["x_league"].'/board?type=roundFinished&limit=999999';
            $authorization = "Authorization: Bearer ".$this->token;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-league: '.$this->basic_headers["x_league"],
                                                    'x-user: '.$this->basic_headers["x_user"], 
                                                    'Content-Type: application/json', 
                                                    $authorization ));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = curl_exec($ch);
            curl_close($ch);

            $jsonArray = json_decode($result,true);
            return $jsonArray["data"];
        }

        public function existImg($url){
            $ch = curl_init ($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $raw=curl_exec($ch);
            curl_close ($ch);
            return !empty($raw);
        }
    }
?>