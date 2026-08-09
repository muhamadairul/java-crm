<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        /** @var \Webkul\User\Models\User $user */
        $user = auth()->guard('user')->user();

        return view('admin::user.account.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        /** @var \Webkul\User\Models\User $user */
        $user = auth()->guard('user')->user();

        $this->validate(request(), [
            'name'             => 'required',
            'email'            => 'email|unique:users,email,'.$user->id,
            'password'         => 'nullable|min:6|confirmed',
            'current_password' => 'required|min:6',
            'image.*'          => 'nullable|mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $data = request()->only([
            'name',
            'email',
            'password',
            'password_confirmation',
            'current_password',
            'image',
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            session()->flash('warning', trans('admin::app.account.edit.invalid-password'));

            return redirect()->back();
        }

        if (isset($data['role_id']) || isset($data['view_permission'])) {
            session()->flash('warning', trans('admin::app.user.account.permission-denied'));

            return redirect()->back();
        }

        $isPasswordChanged = false;

        if (! $data['password']) {
            unset($data['password']);
        } else {
            $isPasswordChanged = true;

            $data['password'] = bcrypt($data['password']);
        }

        if (request()->hasFile('image')) {
            if ($user->image) {
                Storage::delete($user->image);
            }

            $file = current(request()->file('image'));
            $data['image'] = $this->compressAndStoreImage($file, 'admins/' . $user->id);
        } else {
            if (! isset($data['image'])) {
                if (! empty($user->image)) {
                    Storage::delete($user->image);
                }

                $data['image'] = null;
            } else {
                $data['image'] = $user->image;
            }
        }

        $user->update($data);

        if ($isPasswordChanged) {
            Event::dispatch('user.account.update-password', $user);
        }

        session()->flash('success', trans('admin::app.account.edit.update-success'));

        return back();
    }

    /**
     * Compress and resize uploaded profile image (max 500x500, 80% JPEG quality)
     */
    protected function compressAndStoreImage($file, string $folder): string
    {
        $maxDim = 500;
        $quality = 80;

        $path = $file->getRealPath();
        $mime = $file->getMimeType();

        $srcImage = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png'  => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            'image/bmp'  => @imagecreatefrombmp($path),
            default      => null,
        };

        if (! $srcImage) {
            return $file->store($folder);
        }

        $origWidth = imagesx($srcImage);
        $origHeight = imagesy($srcImage);

        if ($origWidth > $maxDim || $origHeight > $maxDim) {
            $ratio = min($maxDim / $origWidth, $maxDim / $origHeight);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);
        } else {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }

        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        if (in_array($mime, ['image/png', 'image/webp'])) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        $filename = \Illuminate\Support\Str::random(40) . '.jpg';
        $relativeStoragePath = trim($folder, '/') . '/' . $filename;
        $fullPath = storage_path('app/public/' . $relativeStoragePath);

        if (! file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        imagejpeg($dstImage, $fullPath, $quality);

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $relativeStoragePath;
    }
}
