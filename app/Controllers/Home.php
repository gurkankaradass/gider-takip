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

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'amount' => 'required|decimal|greater_than[0]',
            'category' => 'required',
            'expense_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            // Eğer doğrulama başarısız olursa, hatalarla birlikte geri dön
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        };

        // Verileri topluyoruz
        $data = [
            'title' => $this->request->getPost('title'),
            'amount' => $this->request->getPost('amount'),
            'category' => $this->request->getPost('category'),
            'expense_date' => $this->request->getPost('expense_date'),
        ];

        // Kaydet ve geri dön
        $model->insert($data);
        return redirect()->to("/")->with('succes', 'Harcama başarıyla eklendi!');
    }

    public function delete($id)
    {
        $model = new ExpenseModel();
        $model->delete($id);
        return redirect()->to('/')->with('success', 'Harcama Silindi.');
    }
}
