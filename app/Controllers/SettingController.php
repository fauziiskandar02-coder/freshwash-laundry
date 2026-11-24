<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\ChartOfAccountModel;

class SettingController extends BaseController
{
    protected $settingModel;
    protected $coaModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->coaModel     = new ChartOfAccountModel();
    }

    public function index()
    {
        $settings = [];
        $rows = $this->settingModel->findAll();
        foreach ($rows as $r) {
            $settings[$r['setting_key']] = $r['setting_value'];
        }

        $coa = $this->coaModel->orderBy('account_code', 'ASC')->findAll();

        return view('settings/index', [
            'title'    => 'Pengaturan Pajak & Profil UMKM',
            'settings' => $settings,
            'coa'      => $coa,
        ]);
    }

    public function update()
    {
        $companyName    = $this->request->getPost('company_name');
        $companyPhone   = $this->request->getPost('company_phone');
        $companyEmail   = $this->request->getPost('company_email');
        $companyAddress = $this->request->getPost('company_address');
        $isPkp          = $this->request->getPost('is_pkp') !== null ? '1' : '0';
        $defaultTaxRate = $this->request->getPost('default_tax_rate') ?? '12.00';

        $this->settingModel->setVal('company_name', $companyName);
        $this->settingModel->setVal('company_phone', $companyPhone);
        $this->settingModel->setVal('company_email', $companyEmail);
        $this->settingModel->setVal('company_address', $companyAddress);
        $this->settingModel->setVal('is_pkp', $isPkp);
        $this->settingModel->setVal('default_tax_rate', $defaultTaxRate);

        return redirect()->to(base_url('settings'))->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
