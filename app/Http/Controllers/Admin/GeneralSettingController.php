<?php

namespace App\Http\Controllers\Admin;

use App\GeneralSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Image;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $general = GeneralSetting::first();
        $page_title = 'General Settings';
        return view('admin.setting.general_setting', compact('page_title', 'general'));
    }

    public function update(Request $request)
    {
        $validation_rule = [
            'base_color' => ['nullable', 'regex:/^[a-f0-9]{6}$/i'],
            'secondary_color' => ['nullable', 'regex:/^[a-f0-9]{6}$/i']

        ];

        $validator = Validator::make($request->all(), $validation_rule, ['select_storage.in'=>'Invalid Selection']);
        $validator->validate();

        $general_setting = GeneralSetting::first();
        $request->merge(['ev' => isset($request->ev) ? 1 : 0]);
        $request->merge(['en' => isset($request->en) ? 1 : 0]);
        $request->merge(['sv' => isset($request->sv) ? 1 : 0]);
        $request->merge(['sn' => isset($request->sn) ? 1 : 0]);
        $request->merge(['registration' => isset($request->registration) ? 1 : 0]);

        $general_setting->update($request->only(['sitename','per_download','ref_bonus', 'cur_text', 'cur_sym', 'ev', 'en', 'sv', 'sn', 'registration', 'base_color', 'secondary_color']));
        $notify[] = ['success', 'General Setting has been updated.'];
        return back()->withNotify($notify);
    }


    public function logoIcon()
    {
        $page_title = 'Logo & Icon';
        return view('admin.setting.logo_icon', compact('page_title'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo' => 'image|mimes:jpg,jpeg,png',
            'favicon' => 'image|mimes:png',
        ]);
        if ($request->hasFile('logo')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->logo)->save($path . '/logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Logo could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', imagePath()['favicon']['size']);
                Image::make($request->favicon)->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Favicon could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo Icons has been updated.'];
        return back()->withNotify($notify);
    }


    public function uploadSetting(Request $request)
    {

        $request->validate([
            'heading' =>'required|string',
            'instruction' => 'required',
          
        ]);

        $general_setting = GeneralSetting::first();
        $general_setting->instruction =[
            'heading' => $request->heading,
            'instruction' => $request->instruction,
            
        ];
     

        if ($request->hasFile('txt')) {
          if($request->txt->getClientOriginalExtension()!= 'txt'){
            $notify[]=['error','Only txt file accepted'];
            return back()->withNotify($notify);
          }
            $filename = 'licence'.'.'. $request->txt->getClientOriginalExtension();
          
           $path   = 'assets/licence/'; 
            if($general_setting->file!=null){

                removeFile($path.$general_setting->file);
            }
         
            $request->txt->move($path,$filename);
            $general_setting->ins_file = $filename;   
        }
        $general_setting->update();
        $notify[]=['success','Instruction updated successfully'];
        return back()->withNotify($notify);
    
    }

    public function watermarkUpdate(Request $request)
    {
       
        $request->validate([
            'watermark' => 'image|mimes:png',
        ]);

        if ($request->hasFile('watermark')) {
            try {
                $path = imagePath()['watermark']['path'];
                $name = 'watermark'.'.'.$request->watermark->extension();
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', imagePath()['watermark']['size']);
                Image::make($request->watermark)->resize($size[0], $size[1])->save($path . '/'.$name);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'watermark could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'watermark has been updated.'];
        return back()->withNotify($notify);
    }


    public function ftpSettings(Request $request)
    {
        $page_title = "Storage Settings";
        return view('admin.ftp.storage_setting',compact('page_title'));
    }

    public function ftpSettingsUpdate(Request $request)
    {
       
        $request->validate([
            'select_storage' => 'in:1,2,3',
            'host_domain' => 'required_if:select_storage,2|url',
            'host' => 'required_if:select_storage,2',
            'username' => 'required_if:select_storage,2',
            'password' => 'required_if:select_storage,2',
            'port' => 'required_if:select_storage,2|integer',
            'root_path' => 'required_if:select_storage,2',
           
            'key' => 'required_if:select_storage,3',
            'secret' => 'required_if:select_storage,3',
            'region' => 'required_if:select_storage,3',
            'bucket' => 'required_if:select_storage,3',
            'url' => 'required_if:select_storage,3',
            'endpoint' => 'required_if:select_storage,3',

        ],
        [
            'host_domain.required_if' => ':host_domain is required when ftp storage is selected',
            'host.required_if' => ':host is required when ftp storage is selected',
            'username.required_if' => ':username is required when ftp storage is selected',
            'password.required_if' => ':password is required when ftp storage is selected',
            'port.required_if' => ':port is required when ftp storage is selected',
            'root_path.required_if' => ':root_path is required when ftp storage is selected',

            'key.required_if' => ':key is required when AWS storage is selected',
            'secret.required_if' => ':secret is required when AWS storage is selected',
            'region.required_if' => ':region is required when AWS storage is selected',
            'bucket.required_if' => ':bucket is required when AWS storage is selected',
            'url.required_if' => ':url is required when AWS storage is selected',
            'endpoint.required_if' => ':endpoint is required when AWS storage is selected',

        ]
    );
      
        $setting = GeneralSetting::first();
        $setting->select_storage = $request->select_storage;
        if( $request->select_storage == 2){
            $setting->ftp = $request->except('_token','select_storage');
        }
        if( $request->select_storage == 3){
            $setting->aws = $request->except('_token','select_storage');
        }
        $setting->update();
        $notify[]=['success','Storage Setting Updated successfully'];
        return back()->withNotify($notify);
    }
}

