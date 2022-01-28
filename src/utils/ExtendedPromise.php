<?php
namespace Langivi\ImportantReminder\utils;

use Promise;

class ExtendedPromise extends \Promise 
{
    public static function all (array $promises):\Promise
    {   
        return new \Promise(function($res,$rej) use(&$promises){
            $promiseArray = array();
            $lengthPromises = count($promises);
            var_dump("length Promises Array",$lengthPromises);
            if($lengthPromises == 0){
                $res($promiseArray);
            }
            foreach($promises as $key => $promise){
                $promise->then(function($result) use(&$lengthPromises,&$promiseArray,$res,$key){
                    var_dump("in Promise");
                    $promiseArray[$key] = $result;
                    $lengthPromises -= 1;
                    if ($lengthPromises == 0){
                        echo "hello\n";
                        var_dump($promiseArray);
                        $res($promiseArray);
                        echo "hello1\n";
                    }

                });
//                var_dump($promise);
            }
            
        });
    }
    public static function allTime (array $promises):\Promise
    {   
        return new \Promise(function($res,$rej) use(&$promises){
            $promiseArray = array();
            $lengthPromises = count($promises);
            $time = 1000;
            var_dump("length Promises Array",$lengthPromises);
            if($lengthPromises == 0){
                $res($promiseArray);
            }
            foreach($promises as $key => $promise){
                set_timeout(function() use(&$lengthPromises,&$promiseArray,$res,$key,$promise){ 
                    $promise->then(function($result) use(&$lengthPromises,&$promiseArray,$res,$key){
                        var_dump("in Promise");
                        $promiseArray[$key] = $result;
                        $lengthPromises -= 1;
                        if ($lengthPromises == 0){
                            $res($promiseArray);
                        }
                       
                    });
                },$time);
                $time += 1000;
            }
        });
    }
}