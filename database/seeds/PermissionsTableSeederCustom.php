<?php
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
class PermissionsTableSeederCustom extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'browse_bread',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('products');
        Permission::generateFor('coupons');
        Permission::generateFor('category');
        Permission::generateFor('category-product');
        Permission::generateFor('orders');
    }
}