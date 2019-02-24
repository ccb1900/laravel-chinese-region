<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/2/22
 * Time: 11:53
 */

namespace Ccb\Region\Commands;


use Ccb\Region\Models\Region;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class RegionGenerate extends Command
{
    protected $signature = "region:generate";

    private $url = "http://a.amap.com/lbs/static/file/AMap_adcode_citycode.xlsx.zip";

    public function handle()
    {
        $zip_path = __DIR__."/../../resource/AMap_adcode_citycode.xlsx.zip";
        $client = new Client();
        $client->get($this->url,[
            'save_to'=>$zip_path
        ]);
        if (file_exists($zip_path)) {
            $zip = new  \ZipArchive();
            if ($zip->open($zip_path) === true) {
                if ($zip->extractTo(__DIR__."/../../resource/")) {
                    $this->info("unzip AMap_adcode_citycode.xlsx.zip success");
                }
                $zip->close();
                $this->info("close AMap_adcode_citycode.xlsx.zip  success");
            } else {
                $this->error("open AMap_adcode_citycode.xlsx.zip fail");
                exit();
            }
        } else {
            $this->error("AMap_adcode_citycode.xlsx.zip not exists");
            exit();
        }

        $path = __DIR__."/../../resource/AMap_adcode_citycode.xlsx";
        if (!file_exists($path)) {
            $this->error('excel not exists');
            exit();
        }
        if (Region::query()->take(10)->count() > 0) {
            $this->error('regions is not empty');
            exit();
        }
        $xlsx = new Xlsx();
        try {
            $resource = $xlsx->load($path);
            try {
                $data = $resource->getActiveSheet()->toArray();

                $this->info("load excel success");

                $pinyin = new \Overtrue\Pinyin\Pinyin(\Overtrue\Pinyin\MemoryFileDictLoader::class);
                array_shift($data);
                $chunk_data = array_chunk($data,100);
                foreach ($chunk_data as $chunk_datum) {
                    $regions = [];
                    foreach ($chunk_datum as $k=>$datum) {
                        $code = intval($datum[1]);
                        if ($code>100000 && $code < 900000) {
                            if ($code % 10000 == 0 && $code % 100 ==0) {
                                $level = 1;
                                $pid = 0;
                            } else if ($code % 10000 != 0 && $code % 100 ==0) {
                                $level = 2;
                                $pid = bcmul(bcdiv($code,10000,0),10000);
                            } else {
                                $level = 3;
                                $pid = bcmul(bcdiv($code,100,0),100);
                            }
                            $regions[] = [
                                "id"=>$datum['1'],
                                "pid"=>$pid,
                                "city_code"=>!is_null($datum['2'])?$datum['2']:0,
                                "name"=>$datum['0'],
                                "spell"=>$pinyin->phrase($datum['0']),
                                "abbr"=>$pinyin->abbr($datum['0']),
                                "level"=>$level,
                            ];
                        } else {
                            $this->warn("escape ".$datum[0]);
                        }

                    }
                    Region::query()->insert($regions);
                    $this->info("insert ".count($regions).' records success');
                }
            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                \Log::error($e);
                $this->error("get active sheet failed , please see log");
            }

        } catch (Exception $e) {
            \Log::error($e);
            $this->info("excel load fail , please see log");
        }
    }
}
