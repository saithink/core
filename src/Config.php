<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
return [
    // 文件上传配置
    'upload' => [
        //上传文件大小
        'filesize' => 5242880,
        //上传文件后缀类型
        'fileExt' => ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', 'mp4', 'pem', 'key'],
        //上传文件类型
        'fileMime' => [
            'image/jpeg', // jpg jpeg
            'image/gif', // gif
            'image/png', // png
            'text/plain', // 文本类型
            'audio/mpeg', // mp3
            'video/mp4', // mp4
            'application/pdf', // pdf
            'application/vnd.ms-excel', // xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
            'application/msword', // doc
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
            'application/zip', // zip
            'application/x-rar-compressed', // rar
        ]
    ]
];
