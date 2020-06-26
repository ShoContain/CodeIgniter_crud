<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {

    public function getAllEmployees(){
        $this->db->order_by('created_at','desc');
        $query = $this->db->get('ci_employee');
        if($query->num_rows() >0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function addEmployee(){
        $data = array(
            'name'=>$this->input->post('employeeName'),
            'address'=>$this->input->post('address'),
            'created_at'=>date('Y-m-d H:i:s'),
        );

        $this->db->insert('ci_employee',$data);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function editEmployee()
    {
        $id = $this->input->get('id');
        $this->db->where('id',$id);
        $query = $this->db->get('ci_employee');
        if($query->num_rows() >0){
            return $query->row();
        }else{
            return false;
        }
    }

    public function updateEmployee()
    {
        $id = $this->input->post('txtId');
        $data = array(
            'name'=>$this->input->post('employeeName'),
            'address'=>$this->input->post('address'),
            'updated_at'=>date('Y-m-d H:i:s'),
        );
        $this->db->where('id',$id);
        $this->db->update('ci_employee',$data);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteEmployee()
    {
        $id = $this->input->get('id');
        $this->db->where('id',$id);
        $query = $this->db->delete('ci_employee');
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

}