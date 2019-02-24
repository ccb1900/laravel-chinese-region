<?php
/**
 * Created by PhpStorm.
 * User: guoji
 * Date: 2019/2/24
 * Time: 0:36
 */

namespace Ccb\Region;

use Ccb\Region\Models\Region;
class RegionRepository implements RegionRepositoryInterface
{
    public function update(Region $region, $pid, $name, $level, $spell, $abbr)
    {
        // TODO: Implement update() method.
    }

    public function destroy(Region $region)
    {
        // TODO: Implement destroy() method.
    }

    public function getAll($limit=10)
    {
        return Region::query()
                     ->where("level",RegionEnum::$level_district)
            ->orderBy("id")
                     ->paginate($limit);
    }

    public function getTreeByLevel($level)
    {
        // TODO: Implement getTreeByLevel() method.
    }

    public function getByDistrict($code)
    {
        // TODO: Implement getByDistrict() method.
    }

    public function getAllByLevel()
    {
        // TODO: Implement getAllByLevel() method.
    }
}