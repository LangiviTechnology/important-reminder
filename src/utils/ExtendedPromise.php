<?php
namespace Langivi\ImportantReminder\utils;

class ExtendedPromise extends \Promise 
{
    public static function all (array $promises):\Promise
    {   
        // $arrayPromise =[];
        // foreach($promises as $promise){
        //     set_timeout(function() use(&$arrayPromise,&$promise){
        //         if($promise->promiseFinalised) array_push($arrayPromise,$promise);
        //         // var_dump($promise->promiseFinalised);
        //     },1000);
        //     var_dump($promise);
        //     // set_interval(function() use(&$arrayPromise,&$promise){
        //     //     if($promise->promiseFinalised) array_push($arrayPromise,$promise);
        //     //     // var_dump($promise->promiseFinalised);
        //     // },1000);
        // }
        // var_dump($arrayPromise);
       
        //     return \Promise::resolve($arrayPromise);
    
        // // return \Promise::resolve($arrayPromise);
        return new \Promise(function($res,$rej) use(&$promises){
            $promiseArray = array();
            $lengthPromises = count($promises);
            if($lengthPromises == 0){
                $res($promiseArray);
            }
            foreach($promises as $key => $promise){
                $promise->then(function($result) use(&$lengthPromises,&$promiseArray,&$res,&$key){
                    $promiseArray[$key] = $result;
                    $lengthPromises -= 1;
                    if ($lengthPromises == 0){
                        $res($promiseArray);
                    }
                   
                });
            }
            
        });
    }
    public static function newAll (array $promises):\Promise
    {   
        $arrayPromise = [];
        $time = 1;
        foreach ($promises as $promis){
            set_timeout(function() use(&$promis, &$arrayPromise){
                $promis->then(fn($data)=>array_push($arrayPromise,$promis));
                
            },$time);
            $time += 10;
        }

        return \Promise::resolve($arrayPromise);
    }
    public static function newNew (array $promises):\Promise
    {   
        $arrayPromise = [];
        $time = 1000;
        foreach ($promises as $promis){
            set_timeout(function($promis){
                $promis->then(fn($data)=>var_dump($data));
                array_push($arrayPromise,$promis);
            },$time);
            $time += 1000;
        }
        
        return \Promise::resolve($arrayPromise);
    }
}