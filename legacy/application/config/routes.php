<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

//_______________________________________________
//Admin : global features
$route['admin/settings'] = 'admin/settings';
$route['admin/diagnostic'] = 'admin/diagnostic';
$route['admin/oauthclients'] = 'admin/oauthClients';
$route['admin/oauthclients/create'] = 'admin/oauthClientsCreate';
$route['admin/oauthclients/delete'] = 'admin/oauthClientsDelete';
$route['admin/oauthtokens/purge'] = 'admin/oauthTokensPurge';
$route['admin'] = 'admin/settings';

//_______________________________________________
//Admin : user management
$route['users/myprofile'] = 'users/myProfile';
$route['users/employees'] = 'users/employees';
$route['users/employeesMultiSelect'] = 'users/employeesMultiSelect';
$route['users/export'] = 'users/export';
$route['users/reset/(:num)'] = 'users/reset/$1';
$route['users/create'] = 'users/create';
$route['users/edit/(:num)'] = 'users/edit/$1';
$route['users/check/login'] = 'users/checkLoginByAjax';
$route['users/account']['POST'] = 'users/account';
$route['users'] = 'users';

//_______________________________________________
//Human Resources Management
$route['hr/employees'] = 'hr/employees';
$route['hr/employees/entity/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'hr/employeesOfEntity/$1/$2/$3/$4/$5/$6/$7';
$route['hr/employees/export/(:num)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'hr/exportEmployees/$1/$2/$3/$4/$5/$6/$7';
$route['hr/employees/edit/manager'] = 'hr/editManager';
$route['hr/employees/edit/entity'] = 'hr/editEntity';
$route['hr/employees/edit/contract'] = 'hr/editContract';
$route['hr/employees/edit/entitlements'] = 'hr/editEntitlements';
$route['hr/employees/create/leave'] = 'hr/createLeaveRequest';
$route['hr/leaves/(:num)'] = 'hr/leaves/$1';
$route['hr/leaves/export/(:num)'] = 'hr/exportLeaves/$1';
$route['hr/overtime/(:num)'] = 'hr/overtime/$1';
$route['hr/counters/([^/]+)/(:num)'] = 'hr/counters/$1/$2';
$route['hr/counters/([^/]+)/(:num)/(:num)'] = 'hr/counters/$1/$2/$3';
$route['hr/overtime/export/(:num)'] = 'hr/exportOvertime/$1';
$route['hr/entitleddays/(:num)'] = 'hr/entitleddays/$1';
$route['hr/leaves/create/(:num)'] = 'hr/createleave/$1';
$route['hr/presence/([^/]+)/(:num)'] = 'hr/presence/$1/$2';
$route['hr/presence/([^/]+)/(:num)/(:num)/(:num)'] = 'hr/presence/$1/$2/$3/$4';
$route['hr/presence/export/([^/]+)/(:num)/(:num)/(:num)'] = 'hr/exportPresence/$1/$2/$3/$4';
$route['hr'] = 'hr/employees';

//_______________________________________________
//HR edit leave types
$route['leavetypes/delete/(:num)'] = 'leavetypes/delete/$1';
$route['leavetypes/edit/(:num)'] = 'leavetypes/edit/$1';
$route['leavetypes/index'] = 'leavetypes/index';
$route['leavetypes/create'] = 'leavetypes/create';
$route['leavetypes/export'] = 'leavetypes/export';
$route['leavetypes'] = 'leavetypes';

//_______________________________________________
//HR edit positions
$route['positions/delete/(:num)'] = 'positions/delete/$1';
$route['positions/edit/(:num)'] = 'positions/edit/$1';
$route['positions/index'] = 'positions/index';
$route['positions/select'] = 'positions/select';
$route['positions/create'] = 'positions/create';
$route['positions/export'] = 'positions/export';
$route['positions'] = 'positions';

//_______________________________________________
//HR edit contracts
$route['contracts/export'] = 'contracts/export';
$route['contracts/create'] = 'contracts/create';
$route['contracts/edit/(:num)'] = 'contracts/edit/$1';
$route['contracts/update'] = 'contracts/update';
$route['contracts/delete/(:num)'] = 'contracts/delete/$1';
$route['contracts/(:num)/calendar/(:num)'] = 'contracts/calendar/$1/$2';
$route['contracts/(:num)/calendar'] = 'contracts/calendar/$1';
$route['contracts/(:num)/calendar/(:num)/copy/(:num)'] = 'contracts/copydayoff/$1/$3/$2';
$route['contracts/(:num)/excludetypes'] = 'contracts/excludeTypes/$1';
$route['contracts/(:num)/types/(:num)/include'] = 'contracts/includeTypeFromContract/$1/$2';
$route['contracts/(:num)/types/(:num)/exclude'] = 'contracts/excludeTypeFromContract/$1/$2';
$route['contracts/calendar/edit'] = 'contracts/editdayoff';
$route['contracts/calendar/series'] = 'contracts/series';
$route['contracts/calendar/import'] = 'contracts/import';
$route['contracts/calendar/userdayoffs/(:num)'] = 'contracts/userDayoffs/$1';
$route['contracts/calendar/userdayoffs'] = 'contracts/userDayoffs';
$route['contracts/calendar/alldayoffs'] = 'contracts/allDayoffs';
$route['contracts/calendar/alldayoffs/lists'] = 'contracts/allDayoffsForList';
$route['contracts'] = 'contracts';

//_______________________________________________
//HR Organization
$route['organization/select'] = 'organization/select';
$route['organization/root'] = 'organization/root';
$route['organization/delete'] = 'organization/delete';
$route['organization/create'] = 'organization/create';
$route['organization/rename'] = 'organization/rename';
$route['organization/move'] = 'organization/move';
$route['organization/copy'] = 'organization/copy';
$route['organization/employees'] = 'organization/employees';
$route['organization/addemployee'] = 'organization/addemployee';
$route['organization/delemployee'] = 'organization/delemployee';
$route['organization/getsupervisor'] = 'organization/getsupervisor';
$route['organization/setsupervisor'] = 'organization/setsupervisor';
$route['organization/lists'] = 'organization/listsIndex';
$route['organization/lists/employees'] = 'organization/listsEmployees';
$route['organization/lists/create'] = 'organization/listsCreate';
$route['organization/lists/rename'] = 'organization/listsRename';
$route['organization/lists/delete'] = 'organization/listsDelete';
$route['organization/lists/addemployees'] = 'organization/listsAddEmployees';
$route['organization/lists/removeemployees'] = 'organization/listsRemoveEmployees';
$route['organization/lists/reorder'] = 'organization/listsReorder';
$route['organization/lists/name'] = 'organization/listName';
$route['organization'] = 'organization';

//_______________________________________________
//Various calendars
$route['calendar/individual'] = 'calendar/individual';
$route['calendar/workmates'] = 'calendar/workmates';
$route['calendar/collaborators'] = 'calendar/collaborators';
$route['calendar/organization'] = 'calendar/organization';
$route['calendar/department'] = 'calendar/department';
$route['calendar/tabular'] = 'calendar/tabular';
$route['calendar/tabular/(:num)/(:num)/(:num)/(:any)/(:any)'] = 'calendar/tabular/$1/$2/$3/$4/$5';
$route['calendar/tabular/partial/(:num)/(:num)/(:num)/(:any)/(:any)'] = 'calendar/tabularPartial/$1/$2/$3/$4/$5';
$route['calendar/tabular/export/(:num)/(:num)/(:num)/(:any)/(:any)'] = 'calendar/exportTabular/$1/$2/$3/$4/$5';
$route['calendar/tabular/list/partial/(:num)/(:num)/(:num)/(:any)'] = 'calendar/tabularPartialFromList/$1/$2/$3/$4';
$route['calendar/tabular/list/export/(:num)/(:num)/(:num)/(:any)'] = 'calendar/exportTabularFromList/$1/$2/$3/$4';
$route['calendar/year/(:num)/(:num)'] = 'calendar/year/$1/$2';
$route['calendar/year/(:num)'] = 'calendar/year/$1';
$route['calendar/year'] = 'calendar/year';
$route['calendar/year/export/(:num)/(:num)'] = 'calendar/exportYear/$1/$2';
$route['calendar'] = 'calendar/individual';

//_______________________________________________
//private Fullcalendar feeds
$route['leaves/individual/(:num)'] = 'leaves/individual/$1';
$route['leaves/individual'] = 'leaves/individual';
$route['leaves/workmates'] = 'leaves/workmates';
$route['leaves/department'] = 'leaves/department';
$route['leaves/organization/(:num)'] = 'leaves/organization/$1';
$route['leaves/list/(:num)'] = 'leaves/listEvents/$1';
$route['leaves/collaborators'] = 'leaves/collaborators';
$route['leaves/team'] = 'leaves/team';

//_______________________________________________
//public Fullcalendar feeds (available when public calendars are activated)
$route['leaves/public/organization/(:num)'] = 'calendar/publicOrganization/$1';
$route['contracts/public/calendar/alldayoffs'] = 'calendar/publicDayoffs';

//_______________________________________________
//Leave requests created by an employee
$route['leaves/counters'] = 'leaves/counters';
$route['leaves/counters/(:num)'] = 'leaves/counters/$1';
$route['leaves/export'] = 'leaves/export';
$route['leaves/create'] = 'leaves/create';
$route['leaves/edit/(:num)'] = 'leaves/edit/$1';
$route['leaves/request/(:num)'] = 'leaves/requestLeave/$1';
$route['leaves/cancel/(:num)'] = 'leaves/cancel/$1';
$route['leaves/update'] = 'leaves/update';
$route['leaves/delete/(:num)'] = 'leaves/delete/$1';
$route['leaves/(:num)/history'] = 'leaves/history/$1';
$route['leaves/(:num)/comments/add'] = 'leaves/createComment/$1';
$route['leaves/cancellation/(:num)'] = 'leaves/cancellation/$1';
$route['leaves/reminder/(:num)'] = 'leaves/reminder/$1';
$route['leaves/([^/]+)/(:num)'] = 'leaves/view/$1/$2';
$route['leaves/validate'] = 'leaves/validate';
$route['leaves'] = 'leaves';

//_______________________________________________
//leave requests (submitted to the line manager)
$route['requests/collaborators'] = 'requests/collaborators';
$route['requests/balance'] = 'requests/balance';
$route['requests/balance/(:num)'] = 'requests/balance/$1';
$route['requests/createleave/(:num)'] = 'requests/createleave/$1';
$route['requests/counters/(:num)'] = 'requests/counters/$1';
$route['requests/counters/(:num)/(:num)'] = 'requests/counters/$1/$2';
$route['requests/export/(:any)'] = 'requests/export/$1';
$route['requests/accept/(:num)'] = 'requests/accept/$1';
$route['requests/reject/(:num)'] = 'requests/reject/$1';
$route['requests/cancellation/accept/(:num)'] = 'requests/acceptCancellation/$1';
$route['requests/cancellation/reject/(:num)'] = 'requests/rejectCancellation/$1';
$route['requests/delegations/(:num)'] = 'requests/delegations/$1';
$route['requests/delegations'] = 'requests/delegations';
$route['requests/ajax/delegations/delete'] = 'requests/deleteDelegations';
$route['requests/ajax/delegations/add'] = 'requests/addDelegations';
$route['requests/(:any)'] = 'requests/index/$1';
$route['requests'] = 'requests/index/requested';

//_______________________________________________
//overtime requests
$route['extra/export'] = 'extra/export';
$route['extra/create'] = 'extra/create';
$route['extra/edit/(:num)'] = 'extra/edit/$1';
$route['extra/delete/(:num)'] = 'extra/delete/$1';
$route['extra/([^/]+)/(:num)'] = 'extra/view/$1/$2';
$route['extra'] = 'extra';

//_______________________________________________
//overtime validation
$route['overtime/export/(:any)'] = 'overtime/export/$1';
$route['overtime/accept/(:num)'] = 'overtime/accept/$1';
$route['overtime/reject/(:num)'] = 'overtime/reject/$1';
$route['overtime/(:any)'] = 'overtime/index/$1';
$route['overtime'] = 'overtime/index/requested';

//_______________________________________________
//Entitled days
$route['entitleddays/user/(:num)'] = 'entitleddays/user/$1';
$route['entitleddays/ajax/user'] = 'entitleddays/ajax_user';
$route['entitleddays/userdelete/(:num)'] = 'entitleddays/userdelete/$1';
$route['entitleddays/contract/(:num)'] = 'entitleddays/contract/$1';
$route['entitleddays/ajax/contract'] = 'entitleddays/ajax_contract';
$route['entitleddays/contractdelete/(:num)'] = 'entitleddays/contractdelete/$1';
$route['entitleddays/ajax/update'] = 'entitleddays/ajax_update';

//_______________________________________________
//Reports
$route['reports/balance'] = 'reports/balance';
$route['reports/balance/execute'] = 'reports/executeBalanceReport';
$route['reports/balance/export'] = 'reports/exportBalanceReport';
$route['reports/leaves'] = 'reports/leaves';
$route['reports/leaves/execute'] = 'reports/executeLeavesReport';
$route['reports/leaves/export'] = 'reports/exportLeavesReport';
$route['reports'] = 'reports';

//_______________________________________________
//HTTP API
$route['api/doc'] = 'api/doc';
$route['api/token'] = 'api/token';
$route['api/contracts/(:num)'] = 'api/contracts/$1';
$route['api/contracts/'] = 'api/contracts';
$route['api/contracts'] = 'api/contracts';
$route['api/entitleddayscontract/(:num)'] = 'api/entitleddayscontract/$1';
$route['api/addentitleddayscontract/(:num)'] = 'api/addentitleddayscontract/$1';
$route['api/entitleddaysemployee/(:num)'] = 'api/entitleddaysemployee/$1';
$route['api/addentitleddaysemployee/(:num)'] = 'api/addentitleddaysemployee/$1';
$route['api/leavessummary/(:num)/(:num)'] = 'api/leavessummary/$1/$2';
$route['api/leavessummary/(:num)'] = 'api/leavessummary/$1';
$route['api/leaves/(:any)/(:any)'] = 'api/leavesInRange/$1/$2';
$route['api/leavetypes/'] = 'api/leavetypes';
$route['api/leavetypes'] = 'api/leavetypes';
$route['api/positions/'] = 'api/positions';
$route['api/positions'] = 'api/positions';
$route['api/userdepartment/(:num)'] = 'api/userdepartment/$1';
$route['api/userextras/(:num)'] = 'api/userextras/$1';
$route['api/userleaves/(:num)'] = 'api/userleaves/$1';
$route['api/users/(:num)/leaves/'] = 'api/userleaves/$1';
$route['api/users/(:num)']['GET'] = 'api/users/$1';
$route['api/users/']['GET'] = 'api/users';
$route['api/users'] = 'api/users';
//v0.4.0
$route['api/monthlypresence/(:num)/(:num)/(:num)'] = 'api/monthlypresence/$1/$2/$3';
$route['api/deleteuser/(:num)'] = 'api/deleteuser/$1';
$route['api/users/(:num)']['DELETE'] = 'api/deleteuser/$1';
$route['api/updateuser/(:num)'] = 'api/updateuser/$1';
$route['api/users/(:num)']['PATCH'] = 'api/updateuser/$1';
$route['api/createuser/(:any)'] = 'api/createuser/$1';
$route['api/createuser'] = 'api/createuser';
$route['api/users/']['POST'] = 'api/createuser/$1';
$route['api/users/(:any)']['POST'] = 'api/createuser/$1';
$route['api/createleave'] = 'api/createleave';
$route['api/leaves']['POST'] = 'api/createleave';
$route['api/leaves/']['POST'] = 'api/createleave';
//v0.4.3
$route['api/getListOfEmployeesInEntity/(:num)/(:any)'] = 'api/getListOfEmployeesInEntity/$1/$2';
//v0.4.4
$route['api/acceptleaves/(:num)'] = 'api/acceptleave/$1';
$route['api/acceptleave/(:num)'] = 'api/acceptleave/$1';
$route['api/rejectleaves/(:num)'] = 'api/rejectleave/$1';
$route['api/rejectleave/(:num)'] = 'api/rejectleave/$1';
//v0.6.0
$route['api/users/ext'] = 'api/usersExt';

//_______________________________________________
//ICS Feeds
$route['ics/individual/(:num)'] = 'ics/individual/$1';
$route['ics/dayoffs/(:num)/(:num)'] = 'ics/dayoffs/$1/$2';
$route['ics/entity/(:num)/(:num)/(:any)'] = 'ics/entity/$1/$2/$3';
$route['ics/collaborators/(:num)'] = 'ics/collaborators/$1';
$route['ics/ical/(:num)'] = 'ics/ical/$1';

//_______________________________________________
//Session management
$route['session/login'] = 'connection/login';
$route['session/logout'] = 'connection/logout';
$route['session/oauth2'] = 'connection/loginOAuth2';
$route['session/language'] = 'connection/language';
$route['session/forgetpassword'] = 'connection/forgetpassword';
$route['api/metadata'] = 'connection/metadata';
$route['api/acs'] = 'connection/acs';
$route['api/slo'] = 'connection/slo';
$route['api/sls'] = 'connection/sls';
$route['api/sso'] = 'connection/sso';

//_______________________________________________
//Authorization endpoint
$route['api/authorization/authorize'] = 'authorization/authorize';
$route['api/authorization/login'] = 'authorization/login';
$route['api/authorization/userinfo'] = 'authorization/userinfo';

//_______________________________________________
//REST API (for mobile/HTML Clients)
$route['api/rest/self']['OPTIONS'] = 'Rest/options';
$route['api/rest/self']['GET'] = 'Rest/self';
$route['api/rest/profile']['OPTIONS'] = 'Rest/options';
$route['api/rest/profile']['GET'] = 'Rest/profile';

$route['api/rest/config']['OPTIONS'] = 'Rest/options';
$route['api/rest/config']['GET'] = 'Rest/config';
$route['api/rest/checksum/(:any)']['OPTIONS'] = 'Rest/options';
$route['api/rest/checksum/(:any)']['GET'] = 'Rest/checksum/$1';
$route['api/rest/checksum']['OPTIONS'] = 'Rest/options';
$route['api/rest/checksum']['GET'] = 'Rest/checksum';
$route['api/rest/submissions']['OPTIONS'] = 'Rest/options';
$route['api/rest/submissions']['GET'] = 'Rest/submissions';

$route['api/rest/leaves']['OPTIONS'] = 'RestLeaves/options';
$route['api/rest/leaves']['GET'] = 'RestLeaves/leaves';
$route['api/rest/leaves']['POST'] = 'RestLeaves/create';
$route['api/rest/leaves/(:num)']['OPTIONS'] = 'RestLeaves/options';
$route['api/rest/leaves/(:num)']['GET'] = 'RestLeaves/leaves/$1';
$route['api/rest/leaves/(:num)']['PATCH'] = 'RestLeaves/edit/$1';
$route['api/rest/leaves/(:num)']['DELETE'] = 'RestLeaves/delete/$1';

$route['api/rest/requests']['OPTIONS'] = 'RestRequests/options';
$route['api/rest/requests']['GET'] = 'RestRequests/requests';
$route['api/rest/requests/(:any)']['OPTIONS'] = 'RestRequests/requests/$1';
$route['api/rest/requests/(:any)']['GET'] = 'RestRequests/requests/$1';

//User properties : is admin, is manager, id, id of manager, manager, contract..
$route['api/rest/users/(:num)']['OPTIONS'] = 'RestUsers/options';
$route['api/rest/users/(:num)']['GET'] = 'RestUsers/users/$1';
$route['api/rest/users']['OPTIONS'] = 'RestUsers/options';
$route['api/rest/users']['GET'] = 'RestUsers/users';

$route['api/rest/users/(:num)/enable']['OPTIONS'] = 'RestUsers/options';
$route['api/rest/users/(:num)/enable']['GET'] = 'RestUsers/enable/$1';
$route['api/rest/users/(:num)/disable']['OPTIONS'] = 'RestUsers/options';
$route['api/rest/users/(:num)/disable']['GET'] = 'RestUsers/disable/$1';

$route['api/rest/admin/logs/(:any)']['OPTIONS'] = 'RestAdmin/options';
$route['api/rest/admin/logs/(:any)']['GET'] = 'RestAdmin/logs/$1';
$route['api/rest/admin/logs']['OPTIONS'] = 'RestAdmin/options';
$route['api/rest/admin/logs']['GET'] = 'RestAdmin/logs';

//_______________________________________________
//Default controllers
$route['default_controller'] = 'leaves';
$route['404_override'] = 'pages/notfound';
$route['(:any)'] = 'pages/view/$1';
