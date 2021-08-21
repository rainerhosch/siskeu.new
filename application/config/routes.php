<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
// $route['default_controller'] = 'dashboard';
$route['404_override'] = 'My404';
$route['translate_uri_dashes'] = FALSE;

// for menu
$route['dashboard'] = 'user/dashboard';
$route['transaksi'] = 'user/transaksi';
$route['transaksi/(:any)'] = 'user/transaksi/$1';
$route['transaksi/hapus_transaksi/(:num)'] = 'user/transaksi/hapus_transaksi/$1';
$route['transaksi/cetak_kwitansi/(:num)'] = 'user/transaksi/cetak_kwitansi/$1';
$route['transaksi/cetak_ulang_kwitansi/(:num)'] = 'user/transaksi/cetak_ulang_kwitansi/$1';

// for admin access manajemen 
$route['sync-simak'] = 'admin/SyncSimak';
$route['sync-simak/(:any)'] = 'admin/SyncSimak/$1';

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
$route['manajemen/manajemen-user'] = 'admin/Manajemen/ManajemenUser';
$route['manajemen/edit-user'] = 'admin/Manajemen/EditUser';
$route['manajemen/hapus-user'] = 'admin/Manajemen/DeleteUser';

// route for admin access datamaster
// $route['masterdata/BiayaSpp'] = 'admin/MasterData/BiayaKuliahAngkatan';
$route['masterdata/(:any)'] = 'admin/MasterData/$1';
$route['laporan/(:any)'] = 'user/Laporan/$1';
$route['rekap/(:any)'] = 'user/Rekap/$1';
