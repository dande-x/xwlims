<?php

return [
    'adminEmail' => 'admin@example.com',
    'server_ip' => '127.0.0.1',
    'server_path' => '/code/xwlims/basic/web/',
    'email_salt' => '7yq23ehsdo3289riesjfj84wifajdnf83w4jrjsi',
    'email_time_limit' => 3600,
    'instrument_image_path' => "/assets/img/instrument/",
    'pageSize' => [
        'users' => 10,
        'groups' => 10,
        'instrument' => 10,
        'follow_instrument' => 5,
        'group_appointment' => 20,
        'instrument_appointment' => 20,
        'appointment_history' => 20,
        'message' => 20,
    ],
    'errorCode' => [
        'success' => 0,
        'fail' => 1,
        'param_error' => 2,

    ],
    'errorMsg' => [
        0 => '操作成功',
        1 => '操作失败',
        2 => '未知错误',
    ],
    'message_content' => [
        'appointment_for_admin' => '您有新的预约申请，请前往处理',
        'admin_agree_appointment' => '管理员同意了您的预约申请',
        'admin_disagree_appointment' => '管理员拒绝了您的预约申请',
        'join_group_apply' => '有人申请加入您的课题组，请前往处理',
        'agree_join_group' => '管理员同意了您的加入课题组申请',
        'disagree_join_group' => '管理员拒绝了您的加入课题组申请',
    ],
];
