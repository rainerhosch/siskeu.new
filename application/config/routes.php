<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'auth';
// $route['default_controller'] = 'dashboard';
$route['404_override'] = 'My404';
$route['translate_uri_dashes'] = FALSE;


// $route['api'] = 'user/api';
// $route['api/(:any)'] = 'user/api/$1';

// for menu
$route['dashboard'] = 'user/dashboard';
$route['migrasitrxtotg'] = 'user/MigrasiTrxToTg';
$route['migrasitrxtotg/(:any)'] = 'user/MigrasiTrxToTg/$1';

$route['transaksi'] = 'user/transaksi';
$route['transaksi/(:any)'] = 'user/transaksi/$1';
$route['transaksi/hapus_transaksi/(:num)'] = 'user/transaksi/hapus_transaksi/$1';
$route['transaksi/cetak_kwitansi/(:num)'] = 'user/transaksi/cetak_kwitansi/$1';
$route['transaksi/cetak_ulang_kwitansi/(:num)'] = 'user/transaksi/cetak_ulang_kwitansi/$1';
$route['transaksi/cetak_kwitansi_dev/(:num)'] = 'user/transaksi/cetak_kwitansi_dev/$1';

// for admin access manajemen 
$route['sync-simak'] = 'admin/SyncSimak';
$route['sync-simak/(:any)'] = 'admin/SyncSimak/$1';

// $route['manajemen/(:any)'] = 'admin/Manajemen/$1';
$route['manajemen/user-access-menu'] = 'admin/Manajemen/UserAccessMenu';
$route['manajemen/manajemen-menu'] = 'admin/Manajemen/ManajemenMenu';
$route['manajemen/get-menu'] = 'admin/Manajemen/getDataMenu';
$route['manajemen/change-status-menu'] = 'admin/Manajemen/ChangeStatusMenu';
$route['manajemen/edit-menu'] = 'admin/Manajemen/EditMenu';
$route['manajemen/update-menu'] = 'admin/Manajemen/UpdateMenu';
// $route['manajemen/(:any)'] = 'admin/Manajemen/$1';
// submenu
$route['manajemen/manajemen-submenu'] = 'admin/Manajemen/ManajemenSubMenu';
$route['manajemen/get-submenu'] = 'admin/Manajemen/getDataSubMenu';
$route['manajemen/change-status-submenu'] = 'admin/Manajemen/ChangeStatusSubmenu';
$route['manajemen/edit-submenu'] = 'admin/Manajemen/EditSubmenu';
$route['manajemen/update-submenu'] = 'admin/Manajemen/UpdateSubmenu';
// user
$route['manajemen/getUserByID'] = 'admin/Manajemen/getUserByID';
$route['manajemen/manajemen-user'] = 'admin/Manajemen/ManajemenUser';
$route['manajemen/edit-user'] = 'admin/Manajemen/EditUser';
$route['manajemen/hapus-user'] = 'admin/Manajemen/DeleteUser';

// route for admin access datamaster
$route['aktivasi-mahasiswa'] = 'admin/AktivasiMhs';
$route['aktivasi-mahasiswa/(:any)'] = 'admin/AktivasiMhs/$1';
$route['masterdata/(:any)'] = 'admin/MasterData/$1';
$route['masterdata/GetDataMhs/(:any)'] = 'admin/MasterData/GetDataMhs/$1';
$route['laporan/(:any)'] = 'user/Laporan/$1';
$route['laporan/loadRecord/(:any)'] = 'user/Laporan/loadRecord/$1';
$route['rekap/(:any)'] = 'user/Rekap/$1';