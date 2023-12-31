<?php
namespace app\models;
use yii\db\ActiveRecord;

class Emp_salarys extends ActiveRecord
{
    private $employee_id;
    // private $name;
    // private $mobile_no;
    private $month;
    private $year;
    private $salary_credited;
    private $paid_for_days;
   

    public function rules()
    {
        return[
            [['employee_id', 'month','employee_id', 'year', 'salary_credited', 'paid_for_days'], 'required']
        ];
    }

    // public function getEmp_salarys(){
    //     return $this->hasOne(Employees::className(),['id'=>'fullname']);
    // }
    public function getEmployees()
{
    return $this->hasOne(Employees::class, ['id' => 'employee_id']);
}
}
?>