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

    public function create()
    {
        $model = new ExpenseModel();

        // Verileri topluyoruz
        $data = [
            'title' => $this->request->getPost('title'),
            'amount' => $this->request->getPost('amount'),
            'category' => $this->request->getPost('category'),
            'expense_date' => $this->request->getPost('expense_date'),
        ];

        // Kaydet ve geri dön
        $model->insert($data);
        return redirect()->to("/");
    }
}
