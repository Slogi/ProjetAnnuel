<?php

class User_model extends CI_Model
{
    protected $table = 'user';

    public function verifieConnexion($email,$mdp) {
        $query = $this->db->select('user_mail,user_password, user_pseudo')
            ->from($this->table)
            ->where('user_mail', $email)
            ->get()
            ->result();
        if ( $query != array() ) {
            if ($this->encryption->decrypt($query[0]->user_password) == $mdp ) return $query[0];
            else return false;

        }
        else return false;
    }

    public function insertUser($email, $mdp, $pseudo){
        $mdpEncrypt =$this->encryption->encrypt($mdp);
        $data = array(
            'user_mail' => $email,
            'user_password' => $mdpEncrypt,
            'user_pseudo' => $pseudo
        );

        if ( !$this->userExist($email) ){
            return $this->db->insert($this->table, $data);
        }
        else {
            return FALSE;
        }
    }

    public function userExist($email){
        return $this->db->select('user_mail')
            ->from($this->table)
            ->where('user_mail',$email)
            ->get()
            ->result();
    }
}