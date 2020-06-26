<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Employee extends CI_Controller{

     function __construct()
    {
        parent::__construct();

        //データベースクラスの呼び出し
        $this->load->database();
        //モデルに別のオブジェクト名を割り当てたい場合は、ロードメソッドの 第 2 引数により指定することができます:
        $this->load->model('employee_model','e_m');
    }

    function index(){
        $this->load->view('layout/header');
        $this->load->view('employee/index');
        $this->load->view('layout/footer');
    }

    public function showAllEmployee(){
        $result = $this->e_m->getAllEmployees();
        echo json_encode($result);
    }

    public function add()
    {
        $result = $this->e_m->addEmployee();
        $msg['success']= false;
        $msg['type']='add';
        if($result){
            $msg['success']=true;
        }
        echo json_encode($msg);
    }

    public function edit()
    {
        $result = $this->e_m->editEmployee();
        echo json_encode($result);
    }

    public function update(){
        $result = $this->e_m->updateEmployee();
        $msg['success']= false;
        $msg['type']='update';
        if($result){
            $msg['success']=true;
        }
        echo json_encode($msg);
     }

    public function delete(){
        $result = $this->e_m->deleteEmployee();
        $msg['success']= false;
        $msg['type']='delete';
        if($result){
            $msg['success']=true;
        }
        echo json_encode($msg);
    }
}
