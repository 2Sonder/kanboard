<?php

namespace Kanboard\Tasks;

use Kanboard\Controller\Base;
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/10/2016
 * Time: 10:38 PM
 */
class GoogleSync extends Base
{
    /**
     * @return \Kanboard\Model\User
     */
    public function index()
    {
        echo 'index';
    }

    public function connect(){
        echo "task connect";
    }

}