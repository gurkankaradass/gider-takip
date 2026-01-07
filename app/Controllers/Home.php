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
        return redirect()->to("/")->with('success', 'Harcama başarıyla eklendi!');
    }

    public function delete($id)
    {
        $model = new ExpenseModel();
        $model->delete($id);
        return redirect()->to('/')->with('success', 'Harcama Silindi.');
    }

    public function exportCSV()
    {
        $model = new ExpenseModel();
        $expenses = $model->findAll();

        // Dosya adını belirleyelim: giderler_2026-01-07.csv gibi
        $fileName = 'giderler_' . date('Y-m-d') . '.csv';

        // Dosyayı yazmaya başla (çıktı akışına)
        $file = fopen("php://temp", "w");

        // Excel'in Türkçe karakterleri tanıması için BOM ekleyelim
        fprintf($file, "\xEF\xBB\xBF");

        // Başlık satırını ekle
        fputcsv($file, ['ID', 'Başlık', 'Tutar', 'Kategori', 'Tarih']);

        // Verileri ekle
        foreach ($expenses as $row) {
            fputcsv($file, [
                $row['id'],
                $row['title'],
                $row['amount'],
                $row['category'],
                $row['expense_date']
            ]);
        }

        rewind($file);
        $csvData = stream_get_contents($file);
        fclose($file);

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setBody($csvData);
    }

    public function exportXlsx()
    {
        $model = new ExpenseModel();
        $expenses = $model->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Başlıklar
        $headers = ['ID', 'Başlık', 'Tutar', 'Kategori', 'Tarih'];
        $sheet->fromArray($headers, NULL, 'A1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Veriler
        $row = 2;
        foreach ($expenses as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['title']);
            $sheet->setCellValue('C' . $row, $item['amount']);
            $sheet->setCellValue('D' . $row, $item['category']);
            $sheet->setCellValue('E' . $row, $item['expense_date']);
            $row++;
        }

        $fileName = 'giderler_' . date('Y-m-d') . '.xlsx';

        /** @var \PhpOffice\PhpSpreadsheet\Writer\Xlsx $writer */
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Dosyayı sunucuda geçici bir konuma yazalım
        $tempPath = WRITEPATH . 'uploads/' . $fileName;
        $writer->save($tempPath);

        // CodeIgniter'ın güvenli indirme fonksiyonunu kullan
        return $this->response->download($tempPath, null)->setFileName($fileName);
    }
}
