<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Goods extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'goods';

    public static function MyInsert($params)
    {
        //组装成金州规定的格式
        $specLi = Goods::package($params['attr_ids']);
        $specList = json_encode($specLi);
        $stock = 0;
        if (!empty($params['stock'])) {
            foreach ($params['stock'] as $v) {
                $stock += $v;
            }
        }
        $data = [
            'goods_sn' => $params['goods_sn'],
            'goods_name' => $params['goods_name'],
            'short_name' => $params['short_name'],
            'cate_id' => $params['cate_id'],
            'describe' => $params['describe'],
            'thumb' => $params['preview'] . '?imageView2/0/w/400/q/75|imageslim',
            'preview' => $params['preview'],
            'banner' => $params['banner'],
            'is_door_support' => $params['is_door_support'],
            'spec_list' => $specList,
            'detail' => $params['detail'],
            'status' => $params['status'],
            'sort' => $params['sortt'] ?? 255,
            'is_refund' => $params['is_refund'] ?? 0,
            'refund_percent' => $params['refund_percent'] ?? 0,
            'stock' => $stock,
            'sale' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        DB::beginTransaction();
        try {
            $resId = Goods::insertGetId($data);
            if (!$resId) {
                DB::rollBack();
                return false;
            }

            $specTemp = [];
            foreach ($params['attr_ids'] as $ke => $va) {
                foreach($va as $kk=>$vv){
                    $specTemp[] = array_column($params['attr_ids'], $kk);
                }
                break;
            }

            $ids = [];
            $stock = $params['stock'] ?? [];
            foreach ($stock as $k => $v) {
                $isDefault = 0;
                if ($params['is_default'] == $k) {
                    $isDefault = 1;
                }
                $data = [
                    'goods_id' => $resId,
                    'sku_sn' => $params['goods_sn'] . $k,
                    'sku_ids' => json_encode($specTemp[$k]),
                    'preview' => $params['skuImg'][$k] ?? $params['preview'],
                    'thumb' => $params['skuImg'][$k] ? $params['skuImg'][$k] . '?imageView2/0/w/400/q/75|imageslim' : $params['preview'] . '?imageView2/0/w/400/q/75|imageslim',
                    'stock' => $params['stock'][$k] ?? 0,
                    'sale' => 0,
                    'warning_stock' => $params['warning_stock'][$k] ?? 0,
                    'agent_price' => $params['agent_price'][$k] ?? 0,
                    'join_price' => $params['join_price'][$k] ?? 0,
                    'bottom_price' => $params['bottom_price'][$k] ?? 0,
                    'suggest_price' => $params['suggest_price'][$k] ?? 0,
                    'sort' => $params['sort'][$k] ?? 0,
                    'status' => $params['statu'][$k],
                    'is_default' => $isDefault,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $resultId = DB::table('sku')->insertGetId($data);
                $ids[] = $resultId;
            }


            $data = [];
            foreach ($specTemp as $k => $v) {
                foreach ($v as $vv) {
                    $data[] = [
                        'goods_id' => $resId,
                        'sku_id' => $ids[$k],
                        'attr_id' => $vv
                    ];
                }

            }
            DB::table('sku_attr')->insert($data);
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
        //组装成金州规定的格式
        $specLi = Goods::package($params['attr_ids']);
        $specList = json_encode($specLi);
        $stock = 0;
        if (!empty($params['stock'])) {
            foreach ($params['stock'] as $v) {
                $stock += $v;
            }
        }
        $data = [
            'goods_name' => $params['goods_name'],
            'short_name' => $params['short_name'],
            'cate_id' => $params['cate_id'],
            'describe' => $params['describe'],
            'thumb' => $params['preview'] . '?imageView2/0/w/400/q/75|imageslim',
            'preview' => $params['preview'],
            'banner' => $params['banner'],
            'is_door_support' => $params['is_door_support'],
            'spec_list' => $specList,
            'detail' => $params['detail'],
            'status' => $params['status'],
            'sort' => $params['sortt'] ?? 255,
            'is_refund' => $params['is_refund'] ?? 0,
            'refund_percent' => $params['refund_percent'] ?? 0,
            'stock' => $stock,
            'sale' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        DB::beginTransaction();
        try {
            $res = Goods::where('id',$params['id'])->update($data);
            if (!$res) {
                DB::rollBack();
                return false;
            }

            $specTemp = [];
            $skuIds = $params['attr_ids'] ?? [];
            foreach ($skuIds as $ke => $va) {
                foreach($va as $kk=>$vv){
                    $specTemp[] = array_column($skuIds, $kk);
                }
                break;
            }
            $ids = [];
            $stock = $params['stock'] ?? [];
            $skus = $params['sku_ids'] ?? [];
            $data = Goods::where('id', $params['id'])->first();
            $goodsSn = $data->goods_sn;
            //删除原来的规格商品
            DB::table('sku')->where('goods_id', $params['id'])->whereNotIn('id', $skus)->delete();
            foreach ($skus as $k => $v) {
                $isDefault = 0;
                if ($params['is_default'] == $k) {
                    $isDefault = 1;
                }

                $data = [
                    'id' => $v,
                    'goods_id' => $params['id'],
                    'sku_sn' => $goodsSn . $k,
                    'sku_ids' => json_encode($specTemp[$k]),
                    'preview' => $params['skuImg'][$k] ?? $params['preview'],
                    'thumb' => $params['skuImg'][$k] ? $params['skuImg'][$k] . '?imageView2/0/w/400/q/75|imageslim' : $params['preview'] . '?imageView2/0/w/400/q/75|imageslim',
                    'stock' => $params['stock'][$k],
                    'warning_stock' => $params['warning_stock'][$k],
                    'agent_price' => $params['agent_price'][$k],
                    'join_price' => $params['join_price'][$k],
                    'bottom_price' => $params['bottom_price'][$k],
                    'suggest_price' => $params['suggest_price'][$k],
                    'sort' => $params['sort'][$k] ?? 1,
                    'status' => $params['statu'][$k],
                    'is_default' => $isDefault,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                if($v <> '0'){
                    DB::table('sku')->where('id',$v)->update($data);
                    $ids[] = $v;
                }else{
                    $resultId = DB::table('sku')->insertGetId($data);
                    $ids[] = $resultId;
                }

            }

            $data = [];
            foreach ($specTemp as $k => $v) {
                foreach ($v as $vv) {
                    $data[] = [
                        'goods_id' => $id,
                        'sku_id' => $ids[$k],
                        'attr_id' => $vv
                    ];
                }

            }
            DB::table('sku_attr')->where('goods_id', $id)->delete();
            DB::table('sku_attr')->insert($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e;
            die;
        }

        return true;
    }

    //组装
    public static function package($skuIds)
    {
        $specList = [];
        foreach ($skuIds as $k => $v) {
            $optionInfo = DB::table('options')->where('id',$k)->first();
            $temp = [];
            $v = array_unique($v);
            foreach($v as $key => $value){
                $attrInfo = DB::table('attr')->where('id', $value)->first();
                $temp[] = [
                    'attr_id' => $value,
                    'attr_name' => $attrInfo->value
                ];
            }
            $specList[] = [
                'option_id' => $k,
                'option_name' => $optionInfo->name,
                'attr' => $temp
            ];
        }

        return $specList;
    }
}
