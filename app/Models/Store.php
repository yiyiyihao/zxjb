<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Store extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'shop';

    public static function MyInsert($params)
    {
        $regionId = $params['province_id'] . $params['city_id'] . $params['county_id'];
        $regionName = $params['province_name'] . $params['city_name'] . $params['county_name'];

        DB::beginTransaction();
        try {
            //添加主管理员到用户列表
            $userData = [
                'is_admin' => 1,
                'username' => $params['username'],
                'realname' => $params['realname'],
                'phone' => $params['userphone'],
                'password' => encrypt($params['password']),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $userExist = User::where('phone', $params['userphone'])->orWhere('username',$params['username'])->first();
            if ($userExist) {
                $userId = $userExist->id;
                unset($userData['created_at']);
                User::where('id', $userId)->update($userData);
            } else {
                $userId = User::insertGetId($userData);
            }

            //添加门店
            $data = [
                'shop_type' => $params['shop_type'] ?? '',
                'shop_code' => date('YmdHis') . rand(1000, 9999),
                'shop_name' => $params['shop_name'] ?? '',
                'shop_phone' => $params['shop_phone'] ?? '',
                'owner_name' => $params['owner_name'] ?? '',
                'administrator_id' => $userId,
                'is_after_sale' => $params['is_after_sale'] ?? '',
                'logo' => $params['logo'] ?? '',
                'location' => $params['location'] ?? '',
                'region_id' => $regionId ?? '',
                'region_name' => $regionName ?? '',
                'address' => $params['address'] ?? '',
                'id_front' => $params['id_front'] ?? '',
                'id_back' => $params['id_back'] ?? '',
                'certification' => $params['certification'] ?? '',
                'remark' => $params['remark'] ?? '',
                'status' => $params['status'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $shopId = Store::insertGetId($data);
            if (!$shopId) {
                return false;
            }
            //添加门店职位(店主、店长、店员)
            $jobData = ['shop_id' => $shopId, 'job_name' => '店主', 'is_sys' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
            $jobId = DB::table('shop_job')->insertGetId($jobData);
            $jobData = [
                ['shop_id' => $shopId, 'job_name' => '店长', 'is_sys' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['shop_id' => $shopId, 'job_name' => '店员', 'is_sys' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ];
            DB::table('shop_job')->insert($jobData);

            //根据门店类型添加门店角色(主管理员、管理员、普通成员、信息员、？创业合伙人)？角色默认权限
            if ($params['shop_type'] == 0) {
                $roleData = [
                    'shop_id' => $shopId,
                    'role_name' => '主管理员',
                    'auth_ids' => '1,4,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,28,29,30,31,32,33,34,39,40,41,42,43,44,45,46,47,48,49,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,123,124,125,126,131,132,133,134,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100,127,128,130',
                    'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,billing,change_password,cloud_store,cloud_store_goods_add,cloud_store_goods_edit,cloud_store_goods_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,customer_order_deletion,customer_order_editing,customer_order_list,customer_order_view,del_customer_information,del_intention_customer,delete_in_store_personnel_list,edit_customer_information,edit_intention_customer,financial_info,financial_list,financial_withdrawal,home,in_store_personnel_list,in_store_personnel_list_editing,in_store_personnel_list_search,income_list,information_member_list_deletion,information_member_list_edit,information_member_list_search,intention_customer_list,invitation,invitation_in_store_staff,invitation_information_officer,invitation_venture_partner,list_editor_of_venture_partners,list_of_informants,list_of_products,list_of_venture_partners,list_of_venture_partners_deleted,list_venture_partners_verify,logout,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list,product_list_search,purchase,purchase_list,purchase_order_deletion,purchase_order_editing,purchase_order_list,purchase_order_view,revenue_information,revenue_management,search_customer_information,search_intention_customer,see_customer_information_list,see_income_list,see_of_informants,see_of_products,see_of_venture_partners,see_store_personnel_list,set_intention_customer,store_personnel_management,store_switching,system_message,usage_agreement,user,user_order,venture_partner_list_search,venture_partners,venture_partners_details,venture_partners_verify,venture_partners_verify_details,wanjiaan_college,withdrawal',
                    'is_sys' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')];
                $roleId = DB::table('shop_role')->insertGetId($roleData);
                $roleData = [
                    [
                        'shop_id' => $shopId,
                        'role_name' => '管理员',
                        'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,billing,change_password,cloud_store,cloud_store_goods_add,cloud_store_goods_edit,cloud_store_goods_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,customer_order_deletion,customer_order_editing,customer_order_list,customer_order_view,del_customer_information,del_intention_customer,delete_in_store_personnel_list,edit_customer_information,edit_intention_customer,financial_info,financial_list,financial_withdrawal,home,in_store_personnel_list,in_store_personnel_list_editing,in_store_personnel_list_search,income_list,information_member_list_deletion,information_member_list_edit,information_member_list_search,intention_customer_list,invitation,invitation_in_store_staff,invitation_information_officer,invitation_venture_partner,list_editor_of_venture_partners,list_of_informants,list_of_products,list_of_venture_partners,list_of_venture_partners_deleted,list_venture_partners_verify,logout,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list,product_list_search,purchase,purchase_list,purchase_order_deletion,purchase_order_editing,purchase_order_list,purchase_order_view,revenue_information,revenue_management,search_customer_information,search_intention_customer,see_customer_information_list,see_income_list,see_of_informants,see_of_products,see_of_venture_partners,see_store_personnel_list,set_intention_customer,store_personnel_management,store_switching,system_message,usage_agreement,user,user_order,venture_partner_list_search,venture_partners,venture_partners_details,venture_partners_verify,venture_partners_verify_details,wanjiaan_college,withdrawal',
                        'auth_ids' => '1,4,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,28,29,30,31,32,33,34,39,40,41,42,43,44,45,46,47,48,49,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,123,124,125,126,131,132,133,134,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100,127,128,130',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                    [
                        'shop_id' => $shopId,
                        'role_name' => '普通成员',
                        'auth' => 'invitation_venture_partner,about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,change_password,commodity_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,del_customer_information,del_intention_customer,edit_customer_information,edit_intention_customer,product_list,home,in_store_personnel_list,in_store_personnel_list_search,intention_customer_list,invitation,invitation_information_officer,list_of_informants,list_of_products,log_out,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list_search,search_customer_information,search_intention_customer,see_customer_information_list,see_of_informants,see_of_products,see_store_personnel_list,set_intention_customer,store_personnel_management,store_switching,system_message,usage_agreement,user,wanjiaan_college',
                        'auth_ids' => '61,1,10,11,12,13,14,16,17,18,19,20,21,22,39,40,41,42,43,44,45,46,47,48,49,59,62,63,64,67,68,69,70,76,77,80,81,82,83,2,84,85,86,87,88,3,89,90,94,95,96,97,98,99,100',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                    [
                        'shop_id' => $shopId,
                        'role_name' => '信息员',
                        'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,change_password,customer_information_list,customer_information_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,del_customer_information,edit_customer_information,product_list,home,in_store_personnel_list,income_list,list_of_products,log_out,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,news,personal_information_editing,personal_information_view,privacy_policy,product_list_search,revenue_information,search_customer_information,see_customer_information_list,see_income_list,see_of_products,store_switching,system_message,usage_agreement,user,wanjiaan_college,withdrawal',
                        'auth_ids' => '1,10,11,17,18,19,20,21,22,39,40,41,42,43,44,63,68,69,70,81,82,83,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                    ['shop_id' => $shopId,
                        'role_name' => '创业合伙人',
                        'auth_ids' => '1,10,11,12,13,16,17,18,19,20,21,22,39,40,41,42,43,44,45,46,47,48,49,59,62,68,69,70,76,77,79,80,81,82,83,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100',
                        'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,change_password,commodity_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,del_customer_information,del_intention_customer,edit_customer_information,edit_intention_customer,product_list,home,income_list,information_member_list_search,intention_customer_list,invitation,invitation_information_officer,list_of_informants,list_of_products,log_out,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list_search,revenue_information,search_customer_information,search_intention_customer,see_customer_information_list,see_income_list,see_of_informants,see_of_products,set_intention_customer,store_switching,system_message,usage_agreement,user,wanjiaan_college,withdrawal',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                ];
            } else {
                $roleData = [
                    'shop_id' => $shopId,
                    'role_name' => '主管理员',
                    'auth_ids' => '1,4,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,28,29,30,31,32,33,34,39,40,41,42,43,44,45,46,47,48,49,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,123,124,125,126,131,132,133,134,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100,127,128,130',
                    'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,billing,change_password,cloud_store,cloud_store_goods_add,cloud_store_goods_edit,cloud_store_goods_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,customer_order_deletion,customer_order_editing,customer_order_list,customer_order_view,del_customer_information,del_intention_customer,delete_in_store_personnel_list,edit_customer_information,edit_intention_customer,financial_info,financial_list,financial_withdrawal,home,in_store_personnel_list,in_store_personnel_list_editing,in_store_personnel_list_search,income_list,information_member_list_deletion,information_member_list_edit,information_member_list_search,intention_customer_list,invitation,invitation_in_store_staff,invitation_information_officer,invitation_venture_partner,list_editor_of_venture_partners,list_of_informants,list_of_products,list_of_venture_partners,list_of_venture_partners_deleted,list_venture_partners_verify,logout,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list,product_list_search,purchase,purchase_list,purchase_order_deletion,purchase_order_editing,purchase_order_list,purchase_order_view,revenue_information,revenue_management,search_customer_information,search_intention_customer,see_customer_information_list,see_income_list,see_of_informants,see_of_products,see_of_venture_partners,see_store_personnel_list,set_intention_customer,store_personnel_management,store_switching,system_message,usage_agreement,user,user_order,venture_partner_list_search,venture_partners,venture_partners_details,venture_partners_verify,venture_partners_verify_details,wanjiaan_college,withdrawal',
                    'is_sys' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $roleId = DB::table('shop_role')->insertGetId($roleData);
                $roleData = [
                    [
                        'shop_id' => $shopId,
                        'role_name' => '管理员',
                        'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,billing,change_password,cloud_store,cloud_store_goods_add,cloud_store_goods_edit,cloud_store_goods_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,customer_order_deletion,customer_order_editing,customer_order_list,customer_order_view,del_customer_information,del_intention_customer,delete_in_store_personnel_list,edit_customer_information,edit_intention_customer,financial_info,financial_list,financial_withdrawal,home,in_store_personnel_list,in_store_personnel_list_editing,in_store_personnel_list_search,income_list,information_member_list_deletion,information_member_list_edit,information_member_list_search,intention_customer_list,invitation,invitation_in_store_staff,invitation_information_officer,invitation_venture_partner,list_editor_of_venture_partners,list_of_informants,list_of_products,list_of_venture_partners,list_of_venture_partners_deleted,list_venture_partners_verify,logout,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list,product_list_search,purchase,purchase_list,purchase_order_deletion,purchase_order_editing,purchase_order_list,purchase_order_view,revenue_information,revenue_management,search_customer_information,search_intention_customer,see_customer_information_list,see_income_list,see_of_informants,see_of_products,see_of_venture_partners,see_store_personnel_list,set_intention_customer,store_personnel_management,store_switching,system_message,usage_agreement,user,user_order,venture_partner_list_search,venture_partners,venture_partners_details,venture_partners_verify,venture_partners_verify_details,wanjiaan_college,withdrawal',
                        'auth_ids' => '1,4,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,28,29,30,31,32,33,34,39,40,41,42,43,44,45,46,47,48,49,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,123,124,125,126,131,132,133,134,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100,127,128,130',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                    [
                        'shop_id' => $shopId,
                        'role_name' => '普通成员',
                        'auth' => 'invitation_venture_partner,about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,change_password,commodity_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,del_customer_information,del_intention_customer,edit_customer_information,edit_intention_customer,product_list,home,in_store_personnel_list,in_store_personnel_list_search,intention_customer_list,invitation,invitation_information_officer,list_of_informants,list_of_products,log_out,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list_search,search_customer_information,search_intention_customer,see_customer_information_list,see_of_informants,see_of_products,see_store_personnel_list,set_intention_customer,store_personnel_management,store_switching,system_message,usage_agreement,user,wanjiaan_college',
                        'auth_ids' => '61,1,10,11,12,13,14,16,17,18,19,20,21,22,39,40,41,42,43,44,45,46,47,48,49,59,62,63,64,67,68,69,70,76,77,80,81,82,83,2,84,85,86,87,88,3,89,90,94,95,96,97,98,99,100',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                    [
                        'shop_id' => $shopId,
                        'role_name' => '信息员',
                        'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,change_password,customer_information_list,customer_information_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,del_customer_information,edit_customer_information,product_list,home,in_store_personnel_list,income_list,list_of_products,log_out,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,news,personal_information_editing,personal_information_view,privacy_policy,product_list_search,revenue_information,search_customer_information,see_customer_information_list,see_income_list,see_of_products,store_switching,system_message,usage_agreement,user,wanjiaan_college,withdrawal',
                        'auth_ids' => '1,10,11,17,18,19,20,21,22,39,40,41,42,43,44,63,68,69,70,81,82,83,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                    ['shop_id' => $shopId,
                        'role_name' => '创业合伙人',
                        'auth_ids' => '1,10,11,12,13,16,17,18,19,20,21,22,39,40,41,42,43,44,45,46,47,48,49,59,62,68,69,70,76,77,79,80,81,82,83,2,84,85,86,87,88,3,35,36,37,38,89,90,94,95,96,97,98,99,100',
                        'auth' => 'about_us,announcement_news,app_information,audit_list,audit_list_audit,audit_list_view,change_password,commodity_list,customer_information_list,customer_information_sheet,customer_intent_sheet,customer_list_delete,customer_list_editing,customer_list_search,customer_list_view,del_customer_information,del_intention_customer,edit_customer_information,edit_intention_customer,product_list,home,income_list,information_member_list_search,intention_customer_list,invitation,invitation_information_officer,list_of_informants,list_of_products,log_out,messenger,mine,modify_mobile_phone,new_customer,new_customer_information,new_customer_list,new_intention_customer,news,personal_information_editing,personal_information_view,privacy_policy,product_list_search,revenue_information,search_customer_information,search_intention_customer,see_customer_information_list,see_income_list,see_of_informants,see_of_products,set_intention_customer,store_switching,system_message,usage_agreement,user,wanjiaan_college,withdrawal',
                        'is_sys' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')],
                ];
            }

            DB::table('shop_role')->insert($roleData);

            //添加主管理员到用户类型表
            $groupData = [
                'user_id' => $userId,
                'is_admin' => 1,
                'shop_id' => $shopId,
                'realname' => $params['realname'],
                'user_type' => 'manager',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('user_group')->insert($groupData);

            //添加到各种关联表
            $data = [
                'shop_id' => $shopId,
                'user_id' => $userId,
                'role_id' => $roleId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('shop_user_role')->insert($data);
            $data = [
                'shop_id' => $shopId,
                'user_id' => $userId,
                'job_id' => $jobId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $res = DB::table('shop_user_job')->insert($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e;
            die;
        }

        return true;
    }

    public static function MyEdit($params)
    {
        $regionId = $params['province_id'] . $params['city_id'] . $params['county_id'];
        $regionName = $params['province_name'] . $params['city_name'] . $params['county_name'];
        $data = Store::where('id', $params['id'])->first();
        $administratorId = $data->administrator_id;
        DB::beginTransaction();
        try {
            //添加主管理员到用户列表
            $userData = [
                'is_admin' => 1,
                'phone' => $params['userphone'],
                'password' => encrypt($params['password']),
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            User::where('id', $administratorId)->update($userData);

            //添加门店
            $data = [
                'shop_type' => $params['shop_type'] ?? '',
                'shop_name' => $params['shop_name'] ?? '',
                'shop_phone' => $params['shop_phone'] ?? '',
                'owner_name' => $params['owner_name'] ?? '',
                'administrator_id' => $administratorId,
                'is_after_sale' => $params['is_after_sale'] ?? '',
                'logo' => $params['logo'] ?? '',
                'location' => $params['location'] ?? '',
                'region_id' => $regionId ?? '',
                'region_name' => $regionName ?? '',
                'address' => $params['address'] ?? '',
                'id_front' => $params['id_front'] ?? '',
                'id_back' => $params['id_back'] ?? '',
                'certification' => $params['certification'] ?? '',
                'remark' => $params['remark'] ?? '',
                'status' => $params['status'] ?? '',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $shopId = Store::where('id',$params['id'])->update($data);
            if (!$shopId) {
                return false;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e;
            die;
        }

        return true;
    }

}
