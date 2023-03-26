<?php
    namespace lbs\order\services;
    
    class OrderRequestService {

        public function updateUser($data, $id){

            try{

                return \lbs\order\models\Commande::select("id", "livraison", "nom", "mail")->where("id", "like", $id)->update($data);
            
            } catch ( Error $e ){

                return $e;

            }


        }

        public function getOrder($id){
            try{

                return \lbs\order\models\Commande::select("id", "livraison", "nom", "mail")->where("id", "like", $id)->firstOrFail();
            
            } catch ( Error $e ){

                return $e;

            }

        }

        public function getOrders($filter, $sort, $page){
            try{

                if(!is_null($filter) && !is_null($sort)){

                    if($sort==="amount")
                       return \lbs\order\models\Commande::select("id", "nom as client_name", "created_at as order_date", "livraison as delivery_date", "status" )->where("mail", "like", "$filter")->orderBy("montant")->get();

                    elseif($sort==="date")
                        return \lbs\order\models\Commande::select("id", "nom as client_name", "created_at as order_date", "livraison as delivery_date", "status" )->where("mail", "like", "$filter")->orderBy("created_at", "DESC")->get();

                    else    
                        throw new \Exception("Sort value invalid");

                }

                if(!is_null($filter))
                    return \lbs\order\models\Commande::select("id", "nom as client_name", "created_at as order_date", "livraison as delivery_date", "status" )->where("mail", "like", "$filter")->get();
                    

                return \lbs\order\models\Commande::select("id", "nom as client_name", "created_at as order_date", "livraison as delivery_date", "status")->offset(15 * ($page-1))->limit(15 * $page)->get();
            
            } catch ( Error $e ){

                return $e;

            }
        }
        
        public function count(){
            try{

                return \lbs\order\models\Commande::count();
            
            } catch ( Error $e ){

                return $e;

            }
        }

        public function getItems($id){
            
            try{

                return  \lbs\order\models\Item::select("command_id", "id", "uri", "tarif as price", "quantite as quantity")->where("command_id", "like", "$id")->get();

            } catch( Error $e ) {
                
                return $e;

            }
        }

        public function  createOrder($data){
            try{

                $commande = new \lbs\order\models\Commande();
                
                $explodedDate = explode('-',$data['delivery']['date']);
                $explodedTime = explode('%3A',$data['delivery']['time']);
                


                






                $time = mktime(
                    $explodedTime[0],
                    $explodedTime[1],
                    0,
                    $explodedDate[0],
                    $explodedDate[1],
                    $explodedDate[2],
                );

                $date = date('Y-m-d H:i:s', $time);
                $uid = uniqid();
                $amount = 0;


                foreach($data['items'] as $item){
                    $i = new \lbs\order\models\Item();
                    $i->uri = $item['uri'];
                    $i->tarif = intval($item['price']);
                    $i->quantite = intval($item['qty']);
                    $i->libelle = $item['name'];
                    $i->command_id = $uid;

                    
                    $i->save();

                    $amount += intval($item['price']) * intval($item['qty']);

                }



                $commande->id = $uid ;
                $commande->nom = $data['client_name'] ;
                $commande->mail = $data['client_mail'] ;
                $commande->livraison = $date;
                $commande->montant = $amount;


                $commande->save();
                return [
                    "order" => [
                        "client_name" => $data['client_name'],
                        "client_mail" => $data['client_mail'],
                        "delivery_date" => $date ,
                        "id" => $uid,
                        "total_amount" => $amount
                    ]
                ];

            
            } catch ( Error $e ){

                return $e;

            }
        }
    }
