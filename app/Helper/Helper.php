<?php

use App\Models\Devlog;

    function DatabaseRollbackLog($data){

        $Devlog = new Devlog([
            'loggedby_name'=>$data[0]->name,
            'loggedby_email'=>$data[0]->email,
            'url'=>$data[1],
            'message'=>$data[2],
        ]);
        $Devlog->save();

        return response()->json([
            'LoggedStatus'=> 200,
            'StoredDevlog'=> $Devlog,
            'success' => false,
            'message' => 'Error Found',
        ],500);
    }


?>
