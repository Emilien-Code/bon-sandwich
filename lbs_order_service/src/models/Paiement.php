<?php 
    namespace lbs\order\models;


    class Paiement extends \Illuminate\Database\Eloquent\Model {

        protected $table      = 'paiement';  /* le nom de la table */
        protected $primaryKey = 'id';     /* le nom de la clé primaire */
        public    $timestamps = true;    /* si vrai la table doit contenir
                                             les deux colonnes updated_at,
                                             created_at */
 }