<?php
/**
 * 权限配置，角色保持和Role表一致
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/8/29
 * Time: 9:55
 */
return [
    // 角色 大小写保持一致
    'Administrator' => [],
    'Manager' => [
        // 控制器-方法
        'goods' => ['index', 'update', 'records']
    ],
    'Clerk' => [
        'users' => ['resetPwd', 'postReset']
    ]
];