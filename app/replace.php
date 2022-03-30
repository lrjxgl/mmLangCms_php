<?php
$arr=[
	"forum/index",
	"forum/admin",
	"index/index",
	"index/admin"
];
$old='return json($reData);';
$new='
	$reJson=[
		"data"=>$reData,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);
';
foreach($arr as $dir){
	$files=glob($dir."/*");
	foreach($files as $file){
		$c=file_get_contents($file);
		$c=str_replace($old,$new,$c);
		file_put_contents($file,$c);
	}
}
echo "success";

?>