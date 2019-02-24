<?php
/**
 * Created by PhpStorm.
 * User: guoji
 * Date: 2019/2/24
 * Time: 0:37
 */

namespace Ccb\Region;


use Ccb\Region\Models\Region;

interface RegionRepositoryInterface
{
    public function update(Region $region, $pid, $name, $level, $spell, $abbr);

    public function destroy(Region $region);

    public function getAll($limit = 10);

    public function getTreeByLevel($level);

    public function getByDistrict($code);

    public function getAllByLevel();
}