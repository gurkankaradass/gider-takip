<?php

namespace App\Controllers;

use App\Models\ExpenseModel;

class Home extends BaseController
{
    public function index(): string
    {
        $model = new ExpenseModel();

        //Tüm harcamaları tarih sırasına göre çekiyoruz
        $data['expenses'] = $model->orderBy('expense_date', 'DESC')->findAll();

        return view('expenses', $data);
    }
}
