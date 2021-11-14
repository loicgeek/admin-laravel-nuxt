<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

function uploadImage(Request $request, Model $model)
{

    if ($request->hasFile("image")) {
        $path = $request->file('image')->store(
            '',
            'public_uploads'
        );
        $model->image =  $path;
        $model->save();
    }
}
