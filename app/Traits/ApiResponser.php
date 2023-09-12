<?php

namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser{

	protected function successResponse($data,$code,$count=0){
		return response()->json(['data' => $data, 'code' =>$code,'count' =>$count]);
	}

	protected function errorResponse($message,$code){
		 return response()->json(['error' => $message, 'code' =>$code],$code);
	}

	protected function showAll(Collection $collection, $code=200){
		return $this->successResponse(['data' => $collection],$code);
	}

	protected function showOne(Model $model, $code=200){
		return $this->successResponse(['data' => $model],$code);
	}
}

?>
