<?php
namespace app\controllers;

class UserImage
{
    public function update()
    {
        isFileToUpload('file');

        $path = resize(100, 80, 'assets/images');

        $auth = sessionData(LOGGED_SESSION);

        read('photos');
        where('userId', $auth->id);
        $photoUser = execute(isfetchAll:false);

        if (!$photoUser) {
            $uploaded = create('photos', [
                'userId' => $auth->id,
                'path' => $path
            ]);
        } else {
            $uploaded = update('photos', [
                'userId' =>$auth->id,
                'path' => $path
            ], ['id' => $photoUser->id]);
            @unlink($photoUser->path);
        }

        $auth->path = $path;
        // dd($_SESSION[LOGGED_SESSION]);

        if ($uploaded) {
            return setMessageAndRedirect('Upload feito com sucesso', 'sucessMessageUploadUserPhoto', '/user/edit');
        }
    }
}
