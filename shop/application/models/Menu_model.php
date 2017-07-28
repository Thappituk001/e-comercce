<?php
class Menu_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}


	function menus() {
        $this->db->select("*");
        $this->db->from("menu_parents");
        $q = $this->db->get();

        $final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $this->db->select("*");
                $this->db->from("menu_children");
                $this->db->where("parent_id", $row->parent_id);
                $c = $this->db->get();
                
                if($c->num_rows() > 0 ){
                    $row->child = $c->result();

                    foreach ($row->child as $r) {

                        foreach ($r as $s) {
                            $this->db->select("*");
                            $this->db->from("menu_sub_children");
                            $this->db->where("parent_id", $r->parent_id);
                            $this->db->where("child_id", $r->id_child);
                            $sc = $this->db->get();
                            $r->sub_child = '';
                            if($sc->num_rows() > 0 ){
                               $r->sub_child = $sc->result();
                            }
                        }
                    }

                }
                
                array_push($final, $row);
            }
        }
        return $final;
    }

}