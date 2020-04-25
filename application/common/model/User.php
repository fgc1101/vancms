<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
    //
    protected $table = 'wx_user';

    protected $pk = 'id';

    protected $autoWriteTimestamp = true;

}
